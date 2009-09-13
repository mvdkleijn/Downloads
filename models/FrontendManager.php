<?php

class DownloadFrontendManager {

	function __construct() {
		global $__CMS_CONN__;
		$this->db = $__CMS_CONN__;
	}

	function serveFile() {
		$download_id = $_GET['id'];
		if($download_id) {
			$fileManager = new DownloadFileManager();
			$fileInfo = $fileManager->getDownloadInfo($download_id);
			if(count($fileInfo) != 0) {
				$fileServeAllowed = self::isFileServeAllowed($download_id);
				if($fileServeAllowed == TRUE) {
					$settings = Plugin::getAllSettings('downloads');
					$fileOnDisk = $settings['download_path'] . '/' . $download_id . '.' . $fileInfo['0']['extension'];
					$fileOnDisk = str_replace('//', '/', $fileOnDisk);
					header('Content-Type: '.$fileInfo['0']['filetype'].'');
					if($fileInfo['0']['serve_type'] == 'download') {
						header('Content-Disposition: attachment; filename="' . $fileInfo['0']['name'] . '"');
					}
					elseif($fileInfo['0']['serve_type'] == 'browse') {
						header('Content-Disposition: inline; filename="' . $fileInfo['0']['name'] . '"');
					}
					header('Content-Length: ' . $fileInfo['0']['filesize']);
					header('Content-Transfer-Encoding: binary');
					header('Accept-Ranges: bytes');
					header('Cache-Control: private');
					header('Pragma: private');
					// The time yours truly appeared in this world :D
					header('Expires: Sat, 25 Sep 1982 07:33:00 GMT');
					readfile($fileOnDisk);
					exit;
				}
			}
		}
	}

	function isFileServeAllowed($id) {
		return TRUE;
	}

}