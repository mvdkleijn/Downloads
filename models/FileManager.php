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
		$today = date('Y-m-d G:i:s');
		if($_POST['future_date'] != '') {
			$future_publish = $_POST['future_date'] . ' ' . $_POST['future_hour'] . ':' . $_POST['future_minute'];
			$future_unpublish = $_POST['future_unpublish_date'] . ' ' . $_POST['future_un_hour'] . ':' . $_POST['future_un_minute'];
			$pub_time = explode('-', $_POST['future_date']);
			$unp_time = explode('-', $_POST['future_unpublish_date']);
			$now = time();
			$pub_time_unix = mktime($_POST['future_hour'], $_POST['future_minute'], 0, $pub_time['1'], $pub_time['2'], $pub_time['0']);
			$unp_time_unix = mktime($_POST['future_un_hour'], $_POST['future_un_minute'], 0, $unp_time['1'], $unp_time['2'], $unp_time['0']);
			$pub_result = ($pub_time_unix - $now) / 86400;
			$unp_result = ($unp_time_unix - $now) / 86400;
			if($pub_result < 0) {
				if($pub_result < $unp_result) {
					$published = $_POST['published'];
				}
				else {
					$published = 'no';
				}
			}
			else {
				$published = 'no';
			}
		}
		else {
			$published = $_POST['published'];
		}
		echo $published; exit();
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
					'".filter_var($published, FILTER_SANITIZE_STRING)."',
					'".filter_var($_POST['available'], FILTER_SANITIZE_STRING)."',
					'".filter_var($_POST['require_login'], FILTER_SANITIZE_STRING)."',
					'".filter_var($_POST['require_password'], FILTER_SANITIZE_STRING)."',
					'".$password."',
					'".filter_var($fileType, FILTER_SANITIZE_STRING)."',
					'".filter_var($extension, FILTER_SANITIZE_STRING)."',
					'".$fileSize."',
					'".$today."',
					'".filter_var($future_publish, FILTER_SANITIZE_STRING)."',
					'".filter_var($future_unpublish, FILTER_SANITIZE_STRING)."'
				)";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		$count = $stmt->rowCount();
		$download_id = $this->db->lastInsertId();
		$uploader = self::uploadFile($fileTmpName, $fileType, $download_id, $extension);
		if($uploader == TRUE) {
			if($count == 1) {	return TRUE;	}
			else {				return FALSE;	}
		}
		else {
			return FALSE;
		}
	}

	function deleteFile($id) {
		if($id) {
			$sql = "DELETE FROM ".TABLE_PREFIX."download WHERE download_id='$id'";
			$stmt = $this->db->prepare($sql);
			$stmt->execute();
			$downloadInfo = self::getDownloadInfo($id);
			$settings = Plugin::getAllSettings('downloads');
			$downloadPath = $settings['download_path'];
			if(unlink($downloadPath . '/' . $id . '.' . $downloadInfo['0']['extension'])) {
				return TRUE;
			}
			else {
				return FALSE;
			}
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
			else {
				return FALSE;
			}
		}
		else {
			return FALSE;
		}
	}

}