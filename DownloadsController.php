<?php

class DownloadsController extends PluginController {
	
	public function __construct() {
		$this->setLayout('backend');
		$this->assignToLayout('sidebar', new View('../../plugins/downloads/views/backend/sidebar'));
	}
	
	public function index() {
		$this->display('downloads/views/backend/dashboard');
	}

	public function settings($id) {
		if($id == 'update') {
			$settingsManager = new DownloadSettingsManager();
			$editSettings = $settingsManager->editSettings($_POST);
			Flash::set('success', __('Your Settings have been updated.'));
			redirect(get_url('plugin/downloads/settings'));	
		}
		else {
			$settings = Plugin::getAllSettings('downloads');
			$this->display('downloads/views/backend/settings',
				array(
					'settings' => $settings,
					'url' => get_url('plugin/downloads/settings/')
				)
			);
		}
	}

	public function categories($id) {
		if($id == 'edit') {
			$categoryManager = new DownloadCategoryManager();
			$editCategory = $categoryManager->editCategory($_POST);
			if($editCategory == TRUE) {
				Flash::set('success', __('Your Category has been edited.'));
				redirect(get_url('plugin/downloads/categories'));	
			}
			else {
				Flash::set('error', __('There was a problem editing this category'));
				redirect(get_url('plugin/downloads/categories'));	
			}
		}
		elseif($id == 'addCategory') {
			$categoryManager = new DownloadCategoryManager();
			$addCategory = $categoryManager->addCategory($_POST);
			if($addCategory == TRUE) {
				Flash::set('success', __('You Category has been added.'));
				redirect(get_url('plugin/downloads/categories'));	
			}
			else {
				Flash::set('error', __('There was a problem adding this category'));
				redirect(get_url('plugin/downloads/categories'));	
			}
		}
		elseif($id == 'delete') {
			$id = end(explode('/', $_SERVER['REQUEST_URI']));
			$categoryManager = new DownloadCategoryManager();
			$deleteCategory = $categoryManager->deleteCategory($id);
			if($deleteCategory == TRUE) {
				Flash::set('error', __('This category has been deleted.'));
				redirect(get_url('plugin/downloads/categories'));	
			}
			else {
				Flash::set('error', __('There was a problem deleting this category'));
				redirect(get_url('plugin/downloads/categories'));	
			}
		} else {
			$this->display('downloads/views/backend/categories',
				array(
					'id' => $id,
					'categories' => new DownloadCategoryManager(),
					'url' => get_url('plugin/downloads/categories/')
				)
			);
		}
	}

	public function files($id) {
		if($id == '') {
			$this->display('downloads/views/backend/dashboard');
		}
		elseif($id == 'edit') {
			//edit
		}
		elseif($id == 'addFile') {
			$fileManager = new DownloadFileManager();
			$fileManager = $fileManager->addFile($_POST);
			if($fileManager == TRUE) {
				Flash::set('success', __('You File has been added.'));
				redirect(get_url('plugin/downloads/files'));	
			}
			else {
				Flash::set('error', __('There was a problem adding this file. Please try again'));
				redirect(get_url('plugin/downloads/files'));	
			}
		}
		else {
			$categories = new DownloadCategoryManager();
			$categories = $categories->getCategories();
			$downloadInfo = new DownloadFileManager();
			$downloadInfo = $downloadInfo->getDownloadInfo($id);
			$this->display('downloads/views/backend/downloads',
				array(
					'id' => $id,
					'categories' => $categories,
					'downloadInfo' => $downloadInfo,
					'url' => get_url('plugin/downloads/files/')
				)
			);
		}
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