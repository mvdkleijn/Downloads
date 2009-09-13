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
		AuthUser::load();
		$user_id = AuthUser::getRecord()->id;
		$user_name = AuthUser::getRecord()->name;
		if($_POST['name'] == '' ) { return FALSE; exit(); }
		if($_POST['future_date'] != '') {
			$pub_date = explode('-', $_POST['future_date']);
			$pub_time_unix = mktime($_POST['future_hour'], $_POST['future_minute'], 0, $pub_date['1'], $pub_date['2'], $pub_date['0']);
		}
		if($_POST['future_unpublish_date'] != '') {
			$unp_date = explode('-', $_POST['future_unpublish_date']);
			$unp_time_unix = mktime($_POST['future_un_hour'], $_POST['future_un_minute'], 0, $unp_date['1'], $unp_date['2'], $unp_date['0']);		
		}
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
					'".filter_var($_POST['serve_type'], FILTER_SANITIZE_STRING)."',
					'".filter_var($fileType, FILTER_SANITIZE_STRING)."',
					'".filter_var($extension, FILTER_SANITIZE_STRING)."',
					'".$fileSize."',
					'".time()."',
					'$user_id',
					'$user_name',					
					'".filter_var($pub_time_unix, FILTER_SANITIZE_STRING)."',
					'".filter_var($unp_time_unix, FILTER_SANITIZE_STRING)."'
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


	function editFile($_POST) {
		if($_POST['name'] == '' ) { return FALSE; exit(); }
		if($_POST['future_date'] != '') {
			$pub_date = explode('-', $_POST['future_date']);
			$pub_time_unix = mktime($_POST['future_hour'], $_POST['future_minute'], 0, $pub_date['1'], $pub_date['2'], $pub_date['0']);
		}
		if($_POST['future_unpublish_date'] != '') {
			$unp_date = explode('-', $_POST['future_unpublish_date']);
			$unp_time_unix = mktime($_POST['future_un_hour'], $_POST['future_un_minute'], 0, $unp_date['1'], $unp_date['2'], $unp_date['0']);		
		}
		if($_POST['password'] != '') {
			$password = sha1(filter_var($_POST['password'], FILTER_SANITIZE_STRING));
			$sql = "UPDATE ".TABLE_PREFIX."download SET
						password='$password'
					WHERE download_id='".$_POST['download_id']."'
			";
			$stmt = $this->db->prepare($sql);
			$stmt->execute();
		}
		$sql = "
					UPDATE ".TABLE_PREFIX."download SET 
						name='".filter_var($_POST['name'], FILTER_SANITIZE_STRING)."',
						description='".filter_var($_POST['description'], FILTER_SANITIZE_STRING)."',
						category='".filter_var($_POST['category'], FILTER_SANITIZE_STRING)."',
						published='".filter_var($_POST['published'], FILTER_SANITIZE_STRING)."',
						available='".filter_var($_POST['available'], FILTER_SANITIZE_STRING)."',
						require_login='".filter_var($_POST['require_login'], FILTER_SANITIZE_STRING)."',
						require_password='".filter_var($_POST['require_password'], FILTER_SANITIZE_STRING)."',
						serve_type='".filter_var($_POST['serve_type'], FILTER_SANITIZE_STRING)."',
						date_publish='".filter_var($pub_time_unix, FILTER_SANITIZE_STRING)."',
						date_unpublish='".filter_var($unp_time_unix, FILTER_SANITIZE_STRING)."'
					WHERE download_id='".$_POST['download_id']."'
				";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		$count = $stmt->rowCount();
		if(isset($_FILES['download_file']) && ($_FILES['download_file']['name'] != '')) {
			$deleteOldFile = self::deleteFileFromDisk($_POST['download_id']);
			$fileName = $_FILES['download_file']['name'];
			$extension = end(explode('.', $fileName));
			$fileTmpName = $_FILES['download_file']['tmp_name'];
			$fileType = $_FILES['download_file']['type'];
			$fileSize = $_FILES['download_file']['size'];
			$sql = "UPDATE ".TABLE_PREFIX."download SET
						filetype='$fileType',
						extension='$extension',
						filesize='$fileSize'
					WHERE download_id='".$_POST['download_id']."'
			";
			$stmt = $this->db->prepare($sql);
			$stmt->execute();
			$uploader = self::uploadFile($fileTmpName, $fileType, $_POST['download_id'], $extension);
		}
	}

	function deleteFile($id) {
		if($id) {
			$deleteFromDisk = self::deleteFileFromDisk($id);
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

	function incrementDownloads($id) {
		$sql = "SELECT * FROM ".TABLE_PREFIX."download WHERE download_id='$id'";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		while($result = $stmt->fetchObject()) {
			$newCount = $result->downloads + 1;		
		}
		$sql = "UPDATE ".TABLE_PREFIX."download SET
					downloads='".$newCount."'
				WHERE download_id='".$id."'
		";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
	}

	function deleteFileFromDisk($id) {
		$settings = Plugin::getAllSettings('downloads');
		$downloadPath = $settings['download_path'];
		$fileInfo = self::getDownloadInfo($id);
		$deleteTarget = $downloadPath.'/'.$id.'.'.$fileInfo['0']['extension'];
		if(unlink($deleteTarget)) {
			return TRUE;
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

	function publish($id) {
		$currentStatus = self::getDownloadInfo($id);
		$currentStatus = $currentStatus['0']['published'];
		if($currentStatus == 'yes') { $newStatus = 'no'; } else { $newStatus = 'yes'; }
		$sql = "UPDATE ".TABLE_PREFIX."download SET
					published='".$newStatus."'
				WHERE download_id='".$id."'
		";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
	}

}