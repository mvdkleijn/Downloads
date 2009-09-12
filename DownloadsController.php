<?php

class DownloadsController extends PluginController {
	
	public function __construct() {
		$this->setLayout('backend');
		$this->assignToLayout('sidebar', new View('../../plugins/downloads/views/backend/sidebar'));
	}
	
	public function index() {
		$downloadManager = new DownloadFileManager();
		$downloads = $downloadManager->getAllDownloads();
		$this->display('downloads/views/backend/dashboard', array('downloads' => $downloads));
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
		} 
		else {
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
			redirect(get_url('plugin/downloads/'));	
		}
		elseif($id == 'editFile') {
			$fileManager = new DownloadFileManager();
			$editFile = $fileManager->editFile($_POST);
			Flash::set('success', __('Your File has been edited.'));
			redirect(get_url('plugin/downloads'));	
		}
		elseif($id == 'addFile') {
			$fileManager = new DownloadFileManager();
			$fileManager = $fileManager->addFile($_POST);
			if($fileManager == TRUE) {
				Flash::set('success', __('You File has been added.'));
				redirect(get_url('plugin/downloads'));	
			}
			else {
				Flash::set('error', __('There was a problem adding this file. Please try again'));
				redirect(get_url('plugin/downloads'));
			}
		}
		elseif($id == 'delete') {
			$id = end(explode('/', $_SERVER['REQUEST_URI']));
			$downloadManager = new DownloadFileManager();
			$deleteFile = $downloadManager->deleteFile($id);
			Flash::set('error', __('This file has been deleted.'));
			redirect(get_url('plugin/downloads'));	
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

	function publish($id) {
		$downloadManager = new DownloadFileManager();
		$downloadInfo = $downloadManager->publish($id);
		Flash::set('success', __('This download has been published'));
		redirect(get_url('plugin/downloads'));
	}
}