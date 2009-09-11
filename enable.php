<?php

	global $__CMS_CONN__;


	/**
		Sanity Check - decide whether we're enabling for the first time or after a disable
	**/

	$sql = "
				SELECT * FROM `".TABLE_PREFIX."plugin_settings` WHERE plugin_id='downloads'
			;";
	$pdo = $__CMS_CONN__->prepare($sql);
	$pdo->execute();
	$rowCount = $pdo->rowCount();

	if($rowCount > 1) {
		$sql =	"
					UPDATE `".TABLE_PREFIX."plugin_settings`
					SET value='1'
					WHERE name='active' AND plugin_id='downloads'
			;";
	} else {
		$sql =	"
					INSERT INTO `".TABLE_PREFIX."plugin_settings` (`plugin_id`,`name`,`value`)
					VALUES
						('downloads','active','1'),
						('downloads','core_root','wolf'),
						('downloads','download_path',''),
						('downloads','download_url','')
				;";
	}
	$pdo = $__CMS_CONN__->prepare($sql);
	$pdo->execute();


	/**
		Let's create the tables. If they exist, they won't be overwritten
	**/

	$sql =	"
				CREATE TABLE `".TABLE_PREFIX."download` (
					`download_id` int(11) NOT NULL auto_increment,
					`name` varchar(128) default NULL,
					`description` varchar(1024) default NULL,
					`downloads` int(11) default '0',
					`category` int(11) default NULL,
					`published` enum ('yes', 'no'),
					`available` enum ('yes', 'no'),
					`require_login` enum ('yes', 'no'),
					`require_password` enum ('yes', 'no'),
					`password` varchar(128) default NULL,
					`filetype` varchar(16) default NULL,
					`extension` varchar(11) default NULL,
					`filesize` varchar(16) default NULL,
					PRIMARY KEY (`download_id`)
				) AUTO_INCREMENT=0
			";
	$pdo = $__CMS_CONN__->prepare($sql);
	$pdo->execute();

	$sql =	"
				CREATE TABLE `".TABLE_PREFIX."download_categories` (
					`category_id` int(11) NOT NULL auto_increment,
					`name` varchar(128) default NULL,
					`description` varchar(4096) default NULL,
					PRIMARY KEY (`category_id`)
				) AUTO_INCREMENT=0
			";

	$pdo = $__CMS_CONN__->prepare($sql);
	$pdo->execute();
	$sql =	"
				CREATE TABLE `".TABLE_PREFIX."download_history` (
					`record_id` int(11) NOT NULL auto_increment,
					`download_id` varchar(128) default NULL,
					`category_id` varchar(1024) default NULL,
					`user_id` int(11) default NULL,
					`user_name` varchar(128) default NULL,
					`user_email` varchar(128) default NULL,
					`user_ip` varchar(128) default NULL,
					`refererrer` varchar(1024) default NULL,
					PRIMARY KEY (`record_id`)
				) AUTO_INCREMENT=0
			";
	$pdo = $__CMS_CONN__->prepare($sql);
	$pdo->execute();

	exit();