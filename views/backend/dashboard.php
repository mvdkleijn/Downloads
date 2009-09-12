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