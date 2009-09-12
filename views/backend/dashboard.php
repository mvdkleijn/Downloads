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
<?php foreach($downloads as $download) {
		$categories = new DownloadCategoryManager();
		$category = $categories->getCategories($download['category']);
?>
		<tr class="<?php echo odd_even(); ?>">
			<td><a href="<?php echo get_url('plugin/downloads/files/'); echo $download['download_id']; ?>"><?php echo $download['name'] ?></a></td>
			<td><?php echo $category['0']['name']; ?></td>
			<td><?php echo $download['downloads'] ?></td>
			<td><?php echo ucwords($download['published']) ?></td>
			<td><?php echo number_format((($download['filesize'] / 1024) / 1024), 2) ?><small>MB</small></td>
			<td><a href="<?php echo get_url('plugin/downloads/files/'); echo $download['download_id']; ?>">Edit</a> | <a href="<?php echo get_url('plugin/downloads/files/delete/'); echo $download['download_id']; ?>">Delete</a></td>
		</tr>
<?php } ?>
	</tbody>
</table>