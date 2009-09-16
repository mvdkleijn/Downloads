<?php

$settings = Plugin::getAllSettings('downloads');
foreach($display['categories'] as $category_key=>$category_value) {
	if($category_value['published'] == 'yes') {
		echo '
		<div class="category" id="'.$category_value['name'].'">
		<h4>'.$category_value['name'].'</h4>';
		echo '<p>'.nl2br($category_value['description']).'</p>';
		foreach($display['downloads'] as $download_key=>$download_value) {
			if($download_value['category'] == $category_value['category_id']) {
				$frontendManager = new DownloadFrontendManager();
				$isFileServeAllowed = $frontendManager->isFileDisplayAllowed($download_value);
				if($isFileServeAllowed == 'display') {
					$download_url = $frontendManager->createFileLink($download_value);
					$rewrite = (USE_MOD_REWRITE == false) ? '?' : '';
					$download_url = URL_PUBLIC . $rewrite . $download_url->getUri() . URL_SUFFIX;
					$download_url_id = $frontendManager->createUrlId($download_value);
					echo '<div class="download" id="'.$download_value['name'].'"><p><strong>'.$download_value['name'].'</strong>';
					echo '- <a href="'.$download_url.'?id='.$download_url_id.'"';
					if($settings['open_new_windows'] == 'yes' && $download_value['serve_type'] == 'browse') {
						echo ' target="_blank"';
					}
					echo '>Download</a>';
					if($download_value['description'] != '') {
						echo '<br />- '.$download_value['description'].'</p>';
					}
					else {
						echo '</p>';
					}
					echo '</div>';
				}
			}
		}
		echo '</div>';
	}
}

?>