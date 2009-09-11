<?php

class DownloadHistoryManager {

	function __construct() {
		global $__CMS_CONN__;
		$this->db = $__CMS_CONN__;
	}

	function getAllHistory() {
		$sql = "SELECT * FROM ".TABLE_PREFIX."download_history";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

}