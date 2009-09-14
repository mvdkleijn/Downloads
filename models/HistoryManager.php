<?php

class DownloadHistoryManager {

	function __construct() {
		global $__CMS_CONN__;
		$this->db = $__CMS_CONN__;
	}

	function getAllHistory($download_id) {
		$sql = "SELECT * FROM ".TABLE_PREFIX."download_history";
		if($download_id) {
			$sql .= " WHERE download_id='$download_id' ORDER BY date_downloaded DESC";
		}
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	function addToHistory($fileInfo, $status) {
		AuthUser::load();
		$user_id = AuthUser::getRecord()->id;
		$user_name = AuthUser::getRecord()->name;
		$user_email = AuthUser::getRecord()->email;
		$categoryManager = new DownloadCategoryManager();
		$category = $categoryManager->getCategories($fileInfo['category']);
		$user_ip = $_SERVER['REMOTE_ADDR'];
		$referrer = $_SERVER['HTTP_REFERER'];
		$today = date('Y-m-d G:i:s');
		$sql = "INSERT INTO ".TABLE_PREFIX."download_history
				VALUES (
					'',
					'$status',
					'".$fileInfo['download_id']."',
					'".$fileInfo['name']."',
					'".$fileInfo['category']."',
					'".$category['0']['name']."',
					'$user_id',
					'$user_name',
					'$user_email',
					'$user_ip',
					'$referrer',
					'$today'
				)";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
	}

}