<h1>Downloads</h1>
<?php

	$download = $downloadInfo['0'];
	foreach($categories as $category) {
		if($download['category'] == $category['category_id']) {	$selectedStatus = ' selected="selected"'; } else { $selectedStatus = ''; }
		$categoryOuput[] = '<option value="'.$category['category_id'].'"'.$selectedStatus.'>'.$category['name'].'</option>:!:!:';
	}
	$categoryList = implode(':!:!:', $categoryOuput);
	$categoryList = str_replace(':!:!:', '', $categoryList);
	if(is_numeric($id)) {
		$action = 'editFile';
		$fileHelp = 'Select a replacement File';
	}
	else {
		$action = 'addFile';
		$fileHelp = 'Choose the file you want to upload';
	}

	if(is_numeric($id)) {
		echo '<p>Added by <a href="'.get_url('user/edit/'.$download['added_by_id'].'').'">'.$download['added_by_name'].'</a> on '.date('dS F, Y', $download['date_added']).'';
		if($download['date_publish'] != '0') {
			$publishDate		= date('Y-m-d', $download['date_publish']);
			$publishTime		= date('G:i', $download['date_publish']);
			$publishedArray		= explode(':', $publishTime);
		}
		if($download['date_unpublish'] != '0') {
			$unPublishDate		= date('Y-m-d', $download['date_unpublish']);
			$unPublishTime		= date('G:i', $download['date_unpublish']);
			$unPublishedArray	= explode(':', $unPublishTime);
		}
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
			<td class="label">Future Publishing</td>
			<td class="field">
				<input maxlength="10" name="future_date" size="10" type="text" value="<?php echo $publishDate ?>" /> 
				<img onclick="displayDatePicker('future_date');" src="images/icon_cal.gif" alt="Show Calendar" /> at 
				<select name="future_hour">
					<?php

						$hour = -1;
						do {
							$hour++;
							if($hour < 10) {
								$hour = '0' . $hour;
							}
							if($hour == $publishedArray['0']) {
								$selected = ' selected="selected"';
							}
							else {
								$selected = '';
							}
							echo '<option value="'.$hour.'"'.$selected.'>'.$hour.'</option>';
						}
						while($hour <= 23);

					?>
				</select> : 
				<select name="future_minute">
					<?php

						$minute = -1;
						do {
							$minute++;
							if($minute < 10) {
								$minute = '0' . $minute;
							}
							if($minute == $publishedArray['1']) {
								$selected = ' selected="selected"';
							}
							else {
								$selected = '';
							}
							echo '<option value="'.$minute.'"'.$selected.'>'.$minute.'</option>';
						}
						while($minute <= 59);

					?>
				</select>
			</td>
			<td class="help" rowspan="2">Set a date to publish and unpublish this file. If Published setting is set to 'No', this download will not be available until the time specified here. If it is set to 'Yes' and you have set times here, your download will be available within those times. If you have left the unpublishing field blank, the download will be available from the time publishing is set to (ie now or in the future) until further notice.</td>
		</tr>
		<tr>
			<td class="label">Future <strong>UN</strong>Publishing</td>
			<td class="field">
				<input maxlength="10" name="future_unpublish_date" size="10" type="text" value="<?php echo $unPublishDate ?>" /> 
				<img onclick="displayDatePicker('future_unpublish_date');" src="images/icon_cal.gif" alt="Show Calendar" /> at 
				<select name="future_un_hour">
					<?php

						$hour = -1;
						do {
							$hour++;
							if($hour < 10) {
								$hour = '0' . $hour;
							}
							if($hour == $unPublishedArray['0']) {
								$selected = ' selected="selected"';
							}
							else {
								$selected = '';
							}
							echo '<option value="'.$hour.'"'.$selected.'>'.$hour.'</option>';
						}
						while($hour <= 23);

					?>
				</select> : 
				<select name="future_un_minute">
					<?php

						$minute = -1;
						do {
							$minute++;
							if($minute < 10) {
								$minute = '0' . $minute;
							}
							if($minute == $unPublishedArray['1']) {
								$selected = ' selected="selected"';
							}
							else {
								$selected = '';
							}
							echo '<option value="'.$minute.'"'.$selected.'>'.$minute.'</option>';
						}
						while($minute <= 59);

					?>
				</select>
			</td>
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
			<td class="field" colspan="2"><button id="download-add">Save this Download</button> or <a href="<?php echo $url; ?>">cancel changes</a></td>
		</tr>
	</table>
</form>