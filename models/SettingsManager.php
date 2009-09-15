<?php

class DownloadSettingsManager {

	function __construct() {
		global $__CMS_CONN__;
		$this->db = $__CMS_CONN__;
	}

	function editSettings() {
		foreach($_POST as $key => $value) {
			$sql = "UPDATE ".TABLE_PREFIX."plugin_settings
					SET value='".filter_var($value, FILTER_SANITIZE_STRING)."'
					WHERE plugin_id='downloads'
					AND name='".filter_var($key, FILTER_SANITIZE_STRING)."'
					";
			$stmt = $this->db->prepare($sql);
			$stmt->execute();
		}
	}

	function dismissSetupMessage() {
		$sql = "UPDATE ".TABLE_PREFIX."plugin_settings
				SET value='yes'
				WHERE plugin_id='downloads'
				AND name = 'setup'
				";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
	}

}