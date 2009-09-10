<?php

Plugin::setInfos(array(
	'id'          => 'downloads',
	'title'       => 'Download Manager',
	'author'      => 'Andrew Waters',
	'version'     => '1.0'
));

Plugin::addController('downloads', 'Downloads', 'developer,administrator,editor', TRUE);

function downloaddoc() {

	global $__CMS_CONN__;
	$id = $_GET['id'];
	$referer = $_SERVER['HTTP_REFERER'];
	
	if (empty($id)) {
		header("Location: ".$referer."");
	}
	
	else {
	
		$downloads = "SELECT * FROM ".TABLE_PREFIX."downloads WHERE id='$id'";
		$downloads = $__CMS_CONN__->prepare($downloads);
		$downloads->execute();
		
		while ($download = $downloads->fetchObject()) {
			$name		= $download->name;
			$published	= $download->published;
			$downloads	= $download->downloads;
			$extension	= $download->extension;
	
			$name		= str_replace(' ', '_', $name);
			$name		= str_replace('\'', '_', $name);
			$name		= str_replace('/', '-', $name);
			$name		= str_replace(':', '_', $name);
			$name		= str_replace('  ', '_', $name);
			$name		= str_replace('"', '_', $name);
			$name		= str_replace(';', '_', $name);
			$name		= strtolower($name);
	
			if ($published == 1) {

				$newcount	= $downloads + 1;
				$update		= "UPDATE ".TABLE_PREFIX."downloads SET downloads='".$newcount."' WHERE id='".$id."'";
				$update		= $__CMS_CONN__->prepare($update);
				$update->execute();

				$current_dir			= DOWNLOADS_DIRECTORY . '/';
				$current_dir			= str_replace('//', '/', $current_dir);
				$current_file			= $current_dir . $id . $extension ;
				$target_file			= $current_dir . 'cache/' . $name . $extension;

				if(copy($current_file, $target_file)) {
					header('Location: '.URL_DOWNLOADS.'cache/'.$name.''.$extension.'');
				}

				else {
					echo $current_file;
					echo '<br />';
					echo $target_file;
				}
			}
			else {
				header('Location: '.URL_PUBLIC.'download/unavailable');
			}
		}
	}
}
