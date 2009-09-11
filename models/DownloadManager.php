<?php

class DownloadDownloadManager {

	function __construct() {
		global $__CMS_CONN__;
		$this->db = $__CMS_CONN__;
	}

	function getAllDownloads() {
		$sql = "SELECT * FROM ".TABLE_PREFIX."download";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

}