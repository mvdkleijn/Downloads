<h1>Downloads</h1>
<?php

	$settings = Plugin::getAllSettings('downloads');

	if($settings['setup'] == 'no') { ?>

		<div id="setup" class="no">
			<h3>Welcome to the Downloads plugin</h3>
			<p>Since this is the first time you've run this plugin, I just want to check a few things are correct.</p>
			<p>First of all there are two ways to run this plugin : Basic and Advanced. You are running in <?php echo $settings['options_mode'] ?> mode right now. Advanced mode has a lot of really nice features such as protection for your downloads and future publishing / unpublishing. You can change this at any time via the settings icon at the bottom of the side navigation.</p>
			<?php
				$fileperm = substr(decoct(fileperms($settings['download_path'])), 1);
				if($fileperm != '0777') {
			?>
			<p><strong>I've just check the server and it would appear that I don't have write access to the downloads folder, which I'm going to need to be able to upload your files. Please CHMOD 777 ther folder that exists at <em><?php echo $settings['download_path']; ?></em></strong></p>
			<?php } ?>
			<p>Also, this plugin offers more security over your downloads out of the box. It uses md5 encryption and appends the file name to the ID number in order to stop anyone guessing the sequence of ID's (eg ?id=1, ?id=2 etc). If you don't understand what that means, feel free to ignore it. You have the most secure method enabled out of the box. Again, if you don't want to run this, you can change it in the settings page.</p>
			<p>I'd also like to ask that you go to the settings page first and set up your environment. I've done my best to install this plugin based on the information available when you installed me but it's probably worth double checking and familiarising yourself with the settings in case you need to amend them later on.</p>
			<p>&nbsp;</p>
			<p><a href="<?php echo get_url('plugin/downloads/setup') ?>">Dismiss this message</a></p>
		</div>

<?php
	}
	else {
?>
<h3>All Downloads</h3>
<table class="index">
	<thead>
		<th>Name</th>
		<th>Category</th>
		<th>Downloads</th>
		<th>Published</th>
		<th>Available</th>
		<th>Size</th>
		<th>Options</th>
	</thead>
	<tbody>
<?php
	foreach($downloads as $download) {
?>
		<tr class="<?php echo odd_even(); ?>">
			<td><img align="top" src="../<?php echo $settings['core_root']; ?>/plugins/downloads/images/download-small.png" alt="download" /> <a href="<?php echo get_url('plugin/downloads/files/'); echo $download['download_id']; ?>"><?php echo $download['name'] ?></a></td>
			<td>
				<form action="<?php echo get_url('plugin/downloads/categoryUpdate/'.$download['download_id'].''); ?>" method="post">
					<select name="category_id">
						<option value="0">-- none --</option>
				<?php
					$categoryList = '';
					$categoryOuput = array();
					foreach($categories as $category) {
						if($download['category'] == $category['category_id']) {	$selectedStatus = ' selected="selected"'; } else { $selectedStatus = ''; }
						$categoryOuput[] = '<option value="'.$category['category_id'].'"'.$selectedStatus.'>'.$category['name'].'</option>:!:!:';
					}
					$categoryList = implode(':!:!:', $categoryOuput);
					$categoryList = str_replace(':!:!:', '', $categoryList);
					echo $categoryList;
				?>
					</select>
					<button type="submit">Change</button>
				</form>
			</td>
			<td><?php echo $download['downloads'] ?></td>
			<td><a href="<?php echo get_url('plugin/downloads/publish/'.$download['download_id'].''); ?>"><img src="../<?php echo $settings['core_root']; ?>/plugins/downloads/images/download-published-<?php echo $download['published']; ?>.png" /></a></td>
			<td><a href="<?php echo get_url('plugin/downloads/available/'.$download['download_id'].''); ?>"><img src="../<?php echo $settings['core_root']; ?>/plugins/downloads/images/download-available-<?php echo $download['available']; ?>.png" /></a><?php
			
				if($download['date_publish'] != '0' || $download['date_unpublish'] != '0') {
					echo ' <img class="download-help-hover" src="../'.$settings['core_root'].'/plugins/downloads/images/download-clock.png" title="Published: '.date('dS M, Y \a\t G:i:s', $download['date_publish']).'   Unpublished: '.date('dS M, Y \a\t G:i:s', $download['date_unpublish']).'" />';
				}
			
			?></td>
			<td><?php echo number_format((($download['filesize'] / 1024) / 1024), 2) ?><small>MB</small></td>
			<td>
				<a href="<?php echo get_url('plugin/downloads/history/'); echo $download['download_id']; ?>"><img src="../<?php echo $settings['core_root']; ?>/plugins/downloads/images/download-history-small.png" align="middle" alt="Download History" /></a>  
				<a href="<?php echo get_url('plugin/downloads/files/'); echo $download['download_id']; ?>"><img src="../<?php echo $settings['core_root']; ?>/plugins/downloads/images/download-edit-small.png" align="middle" alt="Edit Download" /></a> 
				<a href="<?php echo get_url('plugin/downloads/files/delete/'); echo $download['download_id']; ?>" onclick="return confirm('Are you sure you want to DELETE this FILE?\n\nIt will be deleted from the server as well - perhaps you\'d like to consider unpublishing instead?');"><img src="../<?php echo $settings['core_root']; ?>/plugins/downloads/images/download-delete-small.png" align="middle" alt="Delete Download" /></a></td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>