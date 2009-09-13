<?php

class DownloadFrontendManager {

	function __construct() {
		global $__CMS_CONN__;
		$this->db = $__CMS_CONN__;
	}

	function serveFile() {
		// TODO: add different ways of presenting ID in the URL - hence sanitize String, not Int
		$download_id = filter_var($_GET['id'], FILTER_SANITIZE_STRING);
		$historyManager = new DownloadHistoryManager();
		if($download_id) {
			$fileManager = new DownloadFileManager();
			$fileInfo = $fileManager->getDownloadInfo($download_id);
			if(count($fileInfo) != 0) {
				$fileInfo = $fileInfo['0'];
				$fileServeAllowed = self::isFileServeAllowed($fileInfo);
				if($fileServeAllowed == 'serve') {
					$addHistory = $historyManager->addToHistory($fileInfo, 'success');
					$settings = Plugin::getAllSettings('downloads');
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
		// TODO
		return 'serve';
	}


}