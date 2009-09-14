<?php

	global $__CMS_CONN__;

	/**
		Sanity Check - decide whether we're enabling for the first time or after a disable
		Also check where our Core Root is
	**/

	$core_root = end(explode('/', CORE_ROOT));
	$server_path = $_SERVER['DOCUMENT_ROOT'] . '/' . $core_root . '/plugins/downloads/download_files/';

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
						('downloads','options_mode','basic'),
						('downloads','md5','yes'),
						('downloads','append_name','yes'),
						('downloads','core_root','$core_root'),
						('downloads','download_path','$server_path'),
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
					`published` enum ('yes', 'no') default 'yes',
					`available` enum ('yes', 'no') default 'yes',
					`require_login` enum ('yes', 'no') default 'no',
					`require_password` enum ('yes', 'no') default 'no',
					`password` varchar(128) default NULL,
					`serve_type` enum ('download', 'browse') default 'browse',
					`filetype` varchar(16) default NULL,
					`extension` varchar(11) default NULL,
					`filesize` varchar(16) default NULL,
					`date_added` int(16),
					`added_by_id` int(11),
					`added_by_name` varchar(128),
					`date_publish` int(16),
					`date_unpublish` int(16),
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
					`status` enum('success','fail') default NULL,
					`download_id` int(11) default NULL,
					`download_name` varchar(128) default NULL,
					`category_id` int(11) default NULL,
					`category_name` varchar(1024) default NULL,
					`user_id` int(11) default NULL,
					`user_name` varchar(128) default NULL,
					`user_email` varchar(128) default NULL,
					`user_ip` varchar(128) default NULL,
					`refererrer` varchar(1024) default NULL,
					`date_downloaded` timestamp,
					PRIMARY KEY (`record_id`)
				) AUTO_INCREMENT=0
			";
	$pdo = $__CMS_CONN__->prepare($sql);
	$pdo->execute();

	exit();