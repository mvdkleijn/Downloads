<?php

class DownloadFrontendManager {

	function __construct() {
		global $__CMS_CONN__;
		$this->db = $__CMS_CONN__;
	}

	function displayDownloads($displayCategory, $displayDescription, $displaySecurity, $displayStats, $download_id) {
		$categoryManager = new DownloadCategoryManager();
		$categories = $categoryManager->getCategories();
		$fileManager = new DownloadFileManager();
		$downloads = $fileManager->getDownloadInfo();
		$lists = array('categories'=>$categories, 'downloads'=>$downloads);
		return $lists;
	}

	function createFileLink() {
		$sql = "SELECT * FROM ".TABLE_PREFIX."page WHERE behavior_id='download_page'";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		while($pages = $stmt->fetchObject()) {
			$page_id = $pages->id;
		}
		$download_page = Record::findOneFrom('Page','id = '.$page_id.'');
		$page = Page::findById($download_page->id);
		return $page;
	}
	
	function createUrlId($download) {
		$settings = Plugin::getAllSettings('downloads');
		if($settings['md5'] == 'yes' && $settings['append_name'] == 'yes') {
			return md5($download['download_id'] . ' ' . $download['name']);
		}
		elseif($settings['md5'] == 'yes' && $settings['append_name'] == 'no') {
			return md5($download['download_id']);
		}
		else {
			return $download['download_id'];
		}
	}

	function serveFile() {
		$download_id = filter_var($_GET['id'], FILTER_SANITIZE_STRING);
		$settings = Plugin::getAllSettings('downloads');
		if($settings['md5'] == 'yes') {
			$download_id = self::checkMd5File($download_id, $settings);
		}
		$historyManager = new DownloadHistoryManager();
		if($download_id) {
			$fileManager = new DownloadFileManager();
			$fileInfo = $fileManager->getDownloadInfo($download_id);
			if(count($fileInfo) != 0) {
				$fileInfo = $fileInfo['0'];
				$fileServeAllowed = self::isFileServeAllowed($fileInfo);
				if($fileServeAllowed == 'serve') {
					$addHistory = $historyManager->addToHistory($fileInfo, 'success');
					$increment = $fileManager->incrementDownloads($download_id);
					$fileOnDisk = $settings['download_path'] . '/' . $download_id . '.' . $fileInfo['extension'];
					$fileOnDisk = str_replace('//', '/', $fileOnDisk);
					header('Content-Type: '.$fileInfo['filetype'].'');
					if($fileInfo['serve_type'] == 'download') {
						header('Content-Disposition: attachment; filename="' . $fileInfo['name'] . '"');
					}
					elseif($fileInfo['serve_type'] == 'browse') {
						header('Content-Disposition: inline; filename="' . $fileInfo['name'] . '"');
					}
					header('Content-Length: ' . $fileInfo['filesize']);
					header('Content-Transfer-Encoding: binary');
					header('Accept-Ranges: bytes');
					// TODO: build caching features
					header('Cache-Control: private');
					header('Pragma: private');
					// The time yours truly appeared in this world :D
					header('Expires: Sat, 25 Sep 1982 07:33:00 GMT');
					readfile($fileOnDisk);
					exit;
				}
				else {
					$addHistory = $historyManager->addToHistory($fileInfo, 'fail');
				}
			}
		}
	}

	function checkMd5File($id, $settings) {
		if($settings['append_name'] == 'yes') {
			$sql = "SELECT * FROM ".TABLE_PREFIX."download";
			$stmt = $this->db->prepare($sql);
			$stmt->execute();
			while($download = $stmt->fetchObject()) {
				$correctId = $download->download_id;
				$correctName = $download->name;
				$mdFiveId = md5($correctId . ' ' . $correctName);
				if($mdFiveId == $id) {
					return $correctId;
				}
			}
		}
		else {
			$sql = "SELECT * FROM ".TABLE_PREFIX."download";
			$stmt = $this->db->prepare($sql);
			$stmt->execute();
			while($download = $stmt->fetchObject()) {
				$correctId = $download->download_id;
				$mdFiveId = md5($correctId);
				if($mdFiveId == $id) {
					return $correctId;
				}
			}
		}
	}

	function isFileDisplayAllowed($fileInfo) {
		$fileIsAvailable = self::fileisAvailable($fileInfo);
		$fileIsPublished = self::fileIsPublished($fileInfo);
		$fileIsWithinPublishedTime = self::fileIsWithinPublishedTime($fileInfo);
		if(	$fileIsAvailable == 'serve' &&
			$fileIsWithinPublishedTime == 'serve' &&
			$fileIsPublished == 'serve'
			) {
			return 'display';
		}
		else {
			return 'no';
		}
	}

	function isFileServeAllowed($fileInfo) {
		$fileIsAvailable = self::fileisAvailable($fileInfo);
		$fileIsWithinPublishedTime = self::fileIsWithinPublishedTime($fileInfo);
		$fileRequiresLogin = self::fileRequiresLogin($fileInfo);
		$fileRequiresPassword = self::fileRequiresPassword($fileInfo);
		if(	$fileIsAvailable == 'serve' &&
			$fileRequiresLogin == 'serve' &&
			$fileIsWithinPublishedTime == 'serve' &&
			$fileRequiresPassword == 'serve'
			) {
			return 'serve';
		}
		else {
			return 'no';
		}
	}

	function fileIsAvailable($fileInfo) {
		if($fileInfo['available'] == 'yes') {
			return 'serve';
		}
		else {
			return 'no';
		}
	}

	function fileIsPublished($fileInfo) {
		if($fileInfo['published'] == 'yes') {
			return 'serve';
		}
		else {
			return 'no';
		}
	}
	
	function fileIsWithinPublishedTime($fileInfo) {
		$now = time();
		if(($fileInfo['date_publish'] == '0') && ($fileInfo['date_unpublish'] == '0')) {
			return 'serve';
		}
		elseif(($fileInfo['date_publish'] != '0') && ($fileInfo['date_unpublish'] != '0')) {
			if((($fileInfo['date_publish'] - $now) <= 0) && (($fileInfo['date_unpublish'] - $now) >= 0)) {
				return 'serve';
			}
			else {
				return 'no';
			}
		}
		else {
			if(($fileInfo['date_publish'] != '0') && ($fileInfo['date_unpublish'] == '0')) {
				if(($fileInfo['date_publish'] - $now) <= 0) {
					return 'serve';
				}
				else {
					return 'no';
				}
			}
			elseif(($fileInfo['date_publish'] == '0') && ($fileInfo['date_unpublish'] != '0')) {
				if(($fileInfo['date_unpublish'] - $now) >= 0) {
					return 'serve';
				}
				else {
					return 'no';
				}
			}
		}
	}

	function fileRequiresLogin($fileInfo) {
		if($fileInfo['require_login'] == 'yes') {
			AuthUser::load();
			if(AuthUser::isLoggedIn()) {
				return 'serve';
			}
			else {
				return 'no';
			}
		}
		else {
			return 'serve';
		}
	}

	function fileRequiresPassword($fileInfo) {
		if($fileInfo['require_password'] == 'yes') {
			$password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
			if($password) {
				$passwordSha = sha1($password);
				if($passwordSha = $fileInfo['password']) {
					return 'serve';
				}
				else {
					// header relocation to wrong password page
					return 'no';
				}
			}
			else {
				// header relocation to no password supplied
				return 'no';
			}
		}
		else {
			return 'serve';
		}
	}

}