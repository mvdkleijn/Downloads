<?php

	Plugin::setInfos(array(
		'id'          => 'downloads',
		'title'       => 'Download Manager',
		'author'      => 'Andrew Waters',
		'type'        => 'both',
		'version'     => '1.0'
	));

	include('models/FileManager.php');
	include('models/CategoryManager.php');
	include('models/HistoryManager.php');
	include('models/SettingsManager.php');
	include('models/FrontendManager.php');

	Plugin::addController('downloads', 'Downloads', 'developer,administrator,editor', TRUE);

	Behavior::add('download_page', '');
	Observer::observe('page_found', 'downloads_page_found');

	function downloads_page_found($page) {
		global $__CMS_CONN__;
		$sql = "SELECT * FROM ".TABLE_PREFIX."page WHERE behavior_id='download_page'";
		$stmt = $__CMS_CONN__->prepare($sql);
		$stmt->execute();
		while($pages = $stmt->fetchObject()) {
			if($page->slug == $pages->slug) {
				$frontendManager = new DownloadFrontendManager();
				$serveFile = $frontendManager->serveFile();
			}
		}
	}

	function listDownloads($download_id, $displayCategoryName, $displayCategoryDescription, $displayDownloadDescription) {
		$frontendManager = new DownloadFrontendManager();
		$display = $frontendManager->displayDownloads($download_id);
		include('views/frontend/listDownloads.php');
	}