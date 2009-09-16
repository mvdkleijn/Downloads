<?php

class DownloadCategoryManager {

	function __construct() {
		global $__CMS_CONN__;
		$this->db = $__CMS_CONN__;
	}

	function getCategories($id) {
		if($id) { $sqlSuffix = "WHERE category_id='$id'"; }
		$sql = "SELECT * FROM ".TABLE_PREFIX."download_categories $sqlSuffix";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	function deleteCategory($id) {
		if($id) { $sqlSuffix = "WHERE category_id='$id'"; } else { exit(); }
		$sql = "DELETE FROM ".TABLE_PREFIX."download_categories $sqlSuffix";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		$count = $stmt->rowCount();
		if($count == 1) {	return TRUE;	}
		else {				return FALSE;	}
	}

	function addCategory() {
		if($_POST['name'] == '' ) { return FALSE; exit(); }
		$sql = "INSERT INTO ".TABLE_PREFIX."download_categories
				VALUES(
					'',
					'".filter_var($_POST['name'], FILTER_SANITIZE_STRING)."',
					'".filter_var($_POST['description'], FILTER_SANITIZE_STRING)."',
					'".filter_var($_POST['published'], FILTER_SANITIZE_STRING)."'
				)";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		$count = $stmt->rowCount();
		if($count == 1) {	return TRUE;	}
		else {				return FALSE;	}
	}

	function editCategory() {
		$sql = "UPDATE ".TABLE_PREFIX."download_categories
				SET name='".filter_var($_POST['name'], FILTER_SANITIZE_STRING)."',
					description='".filter_var($_POST['description'], FILTER_SANITIZE_STRING)."',
					published='".filter_var($_POST['published'], FILTER_SANITIZE_STRING)."'
				WHERE category_id='".filter_var($_POST['id'], FILTER_VALIDATE_INT)."'
				";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		$count = $stmt->rowCount();
		if($count == 1) {	return TRUE;	}
		else {				return FALSE;	}
	}

}