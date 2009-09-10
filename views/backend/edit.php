<?php
	global $__CMS_CONN__;
	$downloadlist = "SELECT * FROM ".TABLE_PREFIX."downloads WHERE id='$id'";
	$downloadlist = $__CMS_CONN__->prepare($downloadlist);
	$downloadlist->execute();

	while ($download = $downloadlist->fetchObject()) {

		$id					= $download->id;
		$name				= $download->name;
		$description		= $download->description;
		$category			= $download->category;
		$published			= $download->published;
	}
?>

<script language="javascript" type="text/javascript">
	function ShowHide(obj) {
		if(document.getElementById(obj).style.display == '')
			document.getElementById(obj).style.display = 'none';
		else
			document.getElementById(obj).style.display = '';
	}
</script>

<h1>Editing "<?php echo $name ?>"</h1>

<form name="edit_download" action="<?php echo get_url('plugin/downloads/edit_download/'); ?>" method="post" name="edit_download" enctype="multipart/form-data">

	<input type="hidden" name="id" value="<?php echo $id ?>" />

	<table class="fieldset" cellpadding="0" cellspacing="0" border="0">

		<tbody>
			<tr>
				<td><small>Name</small></td>
				<td><input class="textbox" type="text" id="name" name="name" size="30" value="<?php echo $name ?>"></td>
			</tr>
			<tr>
				<td><small>File</small></td>
				<td>
					<small>
						<p><strong>Replace File?</strong> <input type="checkbox" name="replace" value="yes" onclick="ShowHide('profile')" /></p>
						<div id="profile" style="display:none">
							<p><label for="download_file">New File :</label> <input name="download_file" type="file" /></p>
						</div>
					</small>
				</td>
			</tr>
			<tr>
				<td><small>Description</small></td>
				<td><textarea class="textarea" id="description"  name="description" style="height:50px;"><?php echo $description ?></textarea></td>
			</tr>
			<tr>
				<td><small>Category</small></td>
				<td>
					<p><select name="category">
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
						<option value="<?php echo $id ?>" <?php if($id == $category) { echo 'selected'; } ?>><?php echo $name ?></option>
	
	<?php	} ?>
					</select></p>
				</td>
			</tr>
			<tr>
				<td><small>Published</small></td>
				<td>
					<p>
					<select name="published">
						<option value="1"<?php if($published == '1') { echo 'selected'; } ?>>Yes</option>
						<option value="0"<?php if($published == '0') { echo 'selected'; } ?>>No</option>
					</select>
					- when published, this file will appear in the downloads list.</p>
				</td>
			</tr>
			<tr>
				<td><p>&nbsp;</p></td>
				<td><input class="button" name="edit_download" type="submit" value="Edit this Download"></td>
			</tr>
		</tbody>
	</table>
</form>