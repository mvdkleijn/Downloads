<h1>Add a new download</h1>
<form name="add_download" action="<?php echo get_url('plugin/downloads/add_download/'); ?>" method="post" name="add_download" enctype="multipart/form-data">

	<table class="fieldset" cellpadding="0" cellspacing="0" border="0">

		<tbody>
			<tr>
				<td><small>Name</small></td>
				<td><input class="textbox" type="text" id="name" name="name" size="30"></td>
			</tr>
			<tr>
				<td><small>File</small></td>
				<td><small><input name="download_file" type="file" /></small></td>
			</tr>
			<tr>
				<td><small>Description</small></td>
				<td><textarea class="textarea" id="description"  name="description" style="height:50px;"></textarea></td>
			</tr>
			<tr>
				<td><small>Category</small></td>
				<td>
					<select name="category">
	<?php
	
		global $__CMS_CONN__;
	
		$downloadcats = "SELECT * FROM ".TABLE_PREFIX."download_categories ORDER BY name";
		$downloadcats = $__CMS_CONN__->prepare($downloadcats);
		$downloadcats->execute();
	
		while ($downloadcat = $downloadcats->fetchObject()) {
			$id			= $downloadcat->id;
			$name		= $downloadcat->name;
			$name		= str_replace('-', ' ', $name);
			$name		= ucwords($name);
	?>
						<option value="<?php echo $id ?>"><?php echo $name ?></option>
	
	<?php	} ?>
					</select>
				</td>
			</tr>
			<tr>
				<td><small>Published</small></td>
				<td>
					<p>
					<select name="published">
						<option value="1">Yes</option>
						<option value="0">No</option>
					</select>
					- when published, this file will appear in the downloads list in the selected category.</p>
				</td>
			</tr>
			<tr>
				<td><p>&nbsp;</p></td>
				<td><input class="button" name="add_download" type="submit" value="Add Download"></td>
			</tr>
		</tbody>
	</table>
</form>