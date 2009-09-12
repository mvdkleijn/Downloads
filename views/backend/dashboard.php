<h1>Downloads</h1>

<table class="index">
	<thead>
		<th>Name</th>
		<th>Category</th>
		<th>Downloads</th>
		<th>Published</th>
		<th>Size</th>
		<th>Edit</th>
	</thead>
	<tbody>
<?php foreach($downloads as $download) { ?>
		<tr class="<?php echo odd_even(); ?>">
			<td><a href="<?php echo get_url('plugin/downloads/files/edit/'); echo $download['download_id']; ?>"><?php echo $download['name'] ?></a></td>
			<td><?php echo $download['category'] ?></td>
			<td><?php echo $download['downloads'] ?></td>
			<td><?php echo $download['published'] ?></td>
			<td><?php echo $download['size'] ?></td>
			<td>Edit | <a href="<?php echo get_url('plugin/downloads/files/delete/'); echo $download['download_id']; ?>">Delete</a></td>
		</tr>
<?php } ?>
	</tbody>
</table>






<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>

<h1>Downloads list</h1>
<table id="downloads-list" class="index" cellpadding="0" cellspacing="0" border="0">
	<thead>
		<tr>
			<td></td>
			<td><p>Name</p></td>
			<td><p>Category</p></td>
			<td><p>Downloads</p></td>
			<td><p>Published</p></td>
			<td><p>Edit</p></td>
		</tr>
	</thead>
	<tbody>
<?php

	global $__CMS_CONN__;

	$downloads = "SELECT * FROM ".TABLE_PREFIX."downloads ORDER BY category DESC, name ASC";
	$downloads = $__CMS_CONN__->prepare($downloads);
	$downloads->execute();

	while ($download = $downloads->fetchObject()) {

		$id					= $download->id;
		$name				= $download->name;
		$downloadcount		= $download->downloads;
		$published			= $download->published;
		$category			= $download->category;

		$downloadcats = "SELECT * FROM ".TABLE_PREFIX."download_categories WHERE id='$category'";
		$downloadcats = $__CMS_CONN__->prepare($downloadcats);
		$downloadcats->execute();

		while ($cat = $downloadcats->fetchObject()) {
			$category 	= $cat->name;
			$category	= str_replace('-', ' ', $category);
			$category	= ucwords($category);
		}


?>
	<tr>
		<td width="10%"><img align="middle" alt="snippet-icon" src="images/download.png" /></td>
		<td width="40%"><?php echo '<a href="'.get_url('plugin/downloads/edit/'.$id).'">' ?><strong><?php echo $name; ?></strong></a></td>
		<td>
			<p><?php echo $category; ?></p>
		</td>
		<td><p><strong><?php echo $downloadcount; ?></strong></p></td>
		<td><?php if ($published == 1) { echo '<a href="'.get_url('plugin/downloads/unpublish/'.$id).'" onclick="return confirm(\'You are about to REMOVE this download from the main site. It will still exist here and can be published again at a later date.\n\nContinue?\');"><img src="'.URL_PUBLIC.'admin/images/active.png" align="middle"" /></a>'; } else { echo '<a href="'.get_url('plugin/downloads/publish/'.$id).'" onclick="return confirm(\'You are about to PUBLISH this download to the site.\n\nContinue?\');"><img src="'.URL_PUBLIC.'admin/images/inactive.png" align="middle"" /></a>'; } ?></td>
		<td>
			<a href="<?php echo get_url('plugin/downloads/edit/'.$id.''); ?>"><img src="images/edit-small.png" alt="edit icon" /></a>
			<a href="<?php echo get_url('plugin/downloads/remove/'.$id.''); ?>" onclick="return confirm('<?php echo __('Are you sure you wish to delete this download? You will destroy the file on the server and remove any reference to it on the main site...'); ?>');"><img src="images/delete-small.png" alt="remove icon" /></a>
		</td>
	</tr>
<?php } ?>
	</tbody>
</table>
