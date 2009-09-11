<?php

class DownloadFileManager {

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

	function getDownloadInfo($download_id) {
		if($download_id) : $sqlSuffix = "WHERE download_id='$download_id'"; endif;
		$sql = "SELECT * FROM ".TABLE_PREFIX."download $sqlSuffix";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	function addFile($_POST) {
		if($_POST['name'] == '' ) { return FALSE; exit(); }
		$fileName = $_FILES['download_file']['name'];
		$extension = end(explode('.', $fileName));
		$fileTmpName = $_FILES['download_file']['tmp_name'];
		$fileType = $_FILES['download_file']['type'];
		$fileSize = $_FILES['download_file']['size'];
		if($_POST['password'] != '') { 
			$password = sha1(filter_var($_POST['password'], FILTER_SANITIZE_STRING));
		}
		$sql = "INSERT INTO ".TABLE_PREFIX."download
				VALUES(
					'',
					'".filter_var($_POST['name'], FILTER_SANITIZE_STRING)."',
					'".filter_var($_POST['description'], FILTER_SANITIZE_STRING)."',
					'0',
					'".filter_var($_POST['category'], FILTER_SANITIZE_STRING)."',
					'".filter_var($_POST['published'], FILTER_SANITIZE_STRING)."',
					'".filter_var($_POST['available'], FILTER_SANITIZE_STRING)."',
					'".filter_var($_POST['require_login'], FILTER_SANITIZE_STRING)."',
					'".filter_var($_POST['require_password'], FILTER_SANITIZE_STRING)."',
					'".$password."',
					'".filter_var($fileType, FILTER_SANITIZE_STRING)."',
					'".filter_var($extension, FILTER_SANITIZE_STRING)."',
					'".$fileSize."'
				)";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		$download_id = $this->db->lastInsertId();
		$uploader = self::uploadFile($fileTmpName, $fileType, $download_id, $extension);
		if($uploader == TRUE) {
			$count = $stmt->rowCount();
			if($count == 1) {	return TRUE;	}
			else {				return FALSE;	}
		}
		else {
			return FALSE;
		}
	}

	function uploadFile($fileTmpName, $fileType, $download_id, $extension) {
		$settings = Plugin::getAllSettings('downloads');
		$downloadPath = $settings['download_path'];
		$targetFile = $downloadPath . '/' . $download_id . '.' . $extension;
		if (is_uploaded_file($fileTmpName)) {
			if(move_uploaded_file($fileTmpName, $targetFile)) {
				return TRUE;
			}
		}
		else {
			return FALSE;
			exit();
		}
	}

}