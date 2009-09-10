<?php

class DownloadsController extends PluginController {
	
	public function __construct() {
		$this->setLayout('backend');
		$this->assignToLayout('sidebar', new View('../../plugins/downloads/views/backend/sidebar'));
	}
	
	public function index() {
		$this->display('downloads/views/backend/index');
	}

	function remove($id) {
		global $__CMS_CONN__;

		$sql = "SELECT * FROM ".TABLE_PREFIX."downloads WHERE id='".$id."'";
		$pdo = $__CMS_CONN__->prepare($sql);
		$pdo->execute();

		while ($file = $pdo->fetchObject()) {
			$name		= $file->name;
			$extension	= $file->extension;
		}

		$oldfile = DOWNLOADS_DIRECTORY . $id . $extension;
		unlink($oldfile);

		$sql = "DELETE FROM ".TABLE_PREFIX."downloads WHERE id='".$id."'";
		$pdo = $__CMS_CONN__->prepare($sql);
		$pdo->execute();

		Flash::set('success', __(''.$name.' has been deleted.'));
		redirect(get_url('plugin/downloads/index'));
	}

	public function add() {
		$this->display('downloads/views/backend/add');
	}

	public function add_download() {

		global $__CMS_CONN__;
		$name				= mysql_escape_string($_POST['name']);
		$description		= mysql_escape_string($_POST['description']);
		$category			= mysql_escape_string($_POST['category']);
		$published			= mysql_escape_string($_POST['published']);

		if(empty($_POST['name'])) {
			Flash::set('error', __('You need to give your download a name!'));
			redirect(get_url('plugin/downloads/add'));		
		}

		else {
			$upload_dir			= DOWNLOADS_DIRECTORY . '/';
			$upload_dir			= str_replace('//', '/', $upload_dir);
			$upload_file		= $upload_dir . basename($_FILES['download_file']['name']);
	
			$upload_dir_tmp		= DOWNLOADS_DIRECTORY . '/tmp/';
			$upload_dir_tmp		= str_replace('//', '/', $upload_dir_tmp);
			$upload_file_tmp	= $upload_dir_tmp . basename($_FILES['download_file']['name']);

			if (is_uploaded_file($_FILES['download_file']['tmp_name'])) {

				if (move_uploaded_file($_FILES['download_file']['tmp_name'], $upload_file_tmp)) {

					$sql = "INSERT INTO ".TABLE_PREFIX."downloads (name, description, downloads, category, published) VALUES ('".$name."', '".$description."', '0', '".$category."', '".$published."')";
					$pdo = $__CMS_CONN__->prepare($sql);
					$pdo->execute();

					$sql = "SELECT * FROM ".TABLE_PREFIX."downloads WHERE description='$description' AND NAME='$name' AND category='$category'" ;
					$pdo = $__CMS_CONN__->prepare($sql);
					$pdo->execute();

					while ($download = $pdo->fetchObject()) {
						$id	= $download->id;
					}

					$extension = strchr($upload_file_tmp, '.');
					$new_file = DOWNLOADS_DIRECTORY . $id . $extension;
					rename($upload_file_tmp, $new_file);

					$sql = "UPDATE ".TABLE_PREFIX."downloads SET extension='$extension' WHERE id='$id'" ;
					$pdo = $__CMS_CONN__->prepare($sql);
					$pdo->execute();

				}
			}

			Flash::set('success', __(''.$name.' has been added to the Wunderbar downloads!'));
			redirect(get_url('plugin/downloads/index'));
		}
	}
	
	public function edit($id) {
		$this->display('downloads/views/backend/edit', array('id' => $id));
	}

	public function edit_download() {

		global $__CMS_CONN__;

		$id					= mysql_escape_string($_POST['id']);
		$replace			= mysql_escape_string($_POST['replace']);
		$name				= mysql_escape_string($_POST['name']);
		$description		= mysql_escape_string($_POST['description']);
		$category			= mysql_escape_string($_POST['category']);
		$published			= mysql_escape_string($_POST['published']);

		if(empty($_POST['name'])) {
			Flash::set('error', __('You need to give this download a name!'));
			redirect(get_url('plugin/downloads/edit/'.$id.''));		
		}

		else {

			if ($replace == 'yes') {

				$upload_dir			= DOWNLOADS_DIRECTORY . '/';
				$upload_dir			= str_replace('//', '/', $upload_dir);
				$upload_file		= $upload_dir . basename($_FILES['download_file']['name']);

				$upload_dir_tmp		= DOWNLOADS_DIRECTORY . '/tmp/';
				$upload_dir_tmp		= str_replace('//', '/', $upload_dir_tmp);
				$upload_file_tmp	= $upload_dir_tmp . basename($_FILES['download_file']['name']);

				if (is_uploaded_file($_FILES['download_file']['tmp_name'])) {

					if (move_uploaded_file($_FILES['download_file']['tmp_name'], $upload_file_tmp)) {

						$sql = "SELECT * FROM ".TABLE_PREFIX."downloads WHERE id='$id'" ;
						$pdo = $__CMS_CONN__->prepare($sql);
						$pdo->execute();

						while($pd = $pdo->fetchObject()) {
							$oldextension = $pd->extension;
						}

						$old_file = DOWNLOADS_DIRECTORY . $id . $oldextension;
						unlink($old_file);

						$extension = strchr($upload_file_tmp, '.');
						$new_file = DOWNLOADS_DIRECTORY . $id . $extension;
						rename($upload_file_tmp, $new_file);

						$sql = "UPDATE ".TABLE_PREFIX."downloads SET extension='$extension' WHERE id='$id'" ;
						$pdo = $__CMS_CONN__->prepare($sql);
						$pdo->execute();

					}
				}
			}
			
			$sql = "
				UPDATE ".TABLE_PREFIX."downloads
				SET	`name`='$name',
					`description`='$description',
					`category`='$category',
					`published`='$published'
				WHERE id='$id';
			";
			$pdo = $__CMS_CONN__->prepare($sql);
			$pdo->execute();

			Flash::set('success', __(''.$name.' has been edited'));
			redirect(get_url('plugin/downloads/index'));
		}
	}

	function publish($id) {
		global $__CMS_CONN__;
		$sql = "
			UPDATE ".TABLE_PREFIX."downloads
			SET `published`='1'
			WHERE id='".$id."'";
		$pdo = $__CMS_CONN__->prepare($sql);
		$pdo->execute();
		Flash::set('success', __('This download has been published'));
		redirect(get_url('plugin/downloads'));
	}

	function unpublish($id) {
		global $__CMS_CONN__;
		$sql = "
			UPDATE ".TABLE_PREFIX."downloads
			SET `published`='0'
			WHERE id='".$id."'";
		$pdo = $__CMS_CONN__->prepare($sql);
		$pdo->execute();
		Flash::set('error', __('This download has been unpublished from the main site'));
		redirect(get_url('plugin/downloads'));
	}
}