<?php

	$download = $downloadInfo['0'];
	foreach($categories as $category) {
		if($download['category'] == $category['category_id']) {	$selectedStatus = ' selected="selected"'; } else { $selectedStatus = ''; }
		$categoryOuput[] = '<option value="'.$category['category_id'].'"'.$selectedStatus.'>'.$category['name'].'</option>:!:!:';
	}
	$categoryList = implode(':!:!:', $categoryOuput);
	$categoryList = str_replace(':!:!:', '', $categoryList);
	if(is_numeric($id)) {
		$action = 'edit/'.$id.'';
		$fileHelp = 'Select a replacement File';
	}
	else {
		$action = 'addFile';
		$fileHelp = 'Choose the file you want to upload';
	}

?>
<form action="<?php echo $url . $action ?>" method="POST" enctype="multipart/form-data" autocomplete="off">
	<input type="hidden" name="download_id" value="<?php echo $download['download_id']; ?>" />
	<table class="fieldset" id="downloads-add">
		<tr>
			<td class="label">Name</td>
			<td class="field"><input name="name" type="text" class="textbox" value="<?php echo $download['name'] ?>" /></td>
			<td class="help">Name your file. When your file is served back via the site, it will be renamed with this name.</td>
		</tr>
		<tr>
			<td class="label">File</td>
			<td class="field"><small><input name="download_file" type="file" /></small></td>
			<td class="help"><?php echo $fileHelp ?></td>
		</tr>
		<tr>
			<td class="label">Description</td>
			<td class="field"><textarea name="description" class="textbox"><?php echo $download['description'] ?></textarea></td>
			<td class="help"></td>
		</tr>
		<tr>
			<td class="label">Category</td>
			<td class="field">
				<select name="category">
					<?php echo $categoryList ?>
				</select>
			</td>
			<td class="help"></td>
		</tr>
		<tr>
			<td class="label">Published</td>
			<td class="field">
				<select name="published">
					<option value="yes"<?php if($download['published'] == 'yes') { echo ' selected="selected"'; } ?>>Yes</option>
					<option value="no"<?php if($download['published'] == 'no') { echo ' selected="selected"'; } ?>>No</option>
				</select>
			</td>
			<td class="help">Should we publish this file?</td>
		</tr>
		<tr>
			<td class="label">Available</td>
			<td class="field">
				<select name="available">
					<option value="yes"<?php if($download['available'] == 'yes') { echo ' selected="selected"'; } ?>>Yes</option>
					<option value="no"<?php if($download['available'] == 'no') { echo ' selected="selected"'; } ?>>No</option>
				</select>
			</td>
			<td class="help">Should we stop this file from being downloaded?</td>
		</tr>
		<tr>
			<td class="label">Require Login</td>
			<td class="field">
				<select name="require_login">
					<option value="yes"<?php if($download['require_login'] == 'yes') { echo ' selected="selected"'; } ?>>Yes</option>
					<option value="no"<?php if($download['require_login'] == 'no' || $download['require_login'] != 'yes') { echo ' selected="selected"'; } ?>>No</option>
				</select>
			</td>
			<td class="help">Should we request that users are logged in to download this file?</td>
		</tr>
		<tr>
			<td class="label">Require Password</td>
			<td class="field">
				<select name="require_password">
					<option value="yes"<?php if($download['require_password'] == 'yes') { echo ' selected="selected"'; } ?>>Yes</option>
					<option value="no"<?php if($download['require_password'] == 'no' || $download['require_password'] != 'yes') { echo ' selected="selected"'; } ?>>No</option>
				</select>
			</td>
			<td class="help">Do you want to password protect this file?</td>
		</tr>
		<tr>
			<td class="label">Password</td>
			<td class="field"><input name="password" type="password" class="textbox" value="" /></td>
			<td class="help">If you want password protection, please enter it here. Leave blank for unchanged.</td>
		</tr>
		<tr>
			<td class="label"></td>
			<td class="field" colspan="2"><button id="download-add">Save this Download</button></td>
		</tr>
	</table>
</form>