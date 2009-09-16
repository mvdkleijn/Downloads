<h1>Downloads Settings</h1>

<form action="<?php echo $url; ?>update" method="POST">
	<input type="hidden" name="active" value="1" />
	<table class="fieldset">
		<tr>
			<td class="help" colspan="3">Plugin Interface</td>
		</tr>
		<tr>
			<td class="label">Options</td>
			<td class="field">
				<input type="radio" name="options_mode" value="basic"<?php if($settings['options_mode'] == 'basic') { echo ' checked="checked"'; } ?>> <small>Basic</small><br />
				<input type="radio" name="options_mode" value="advanced"<?php if($settings['options_mode'] == 'advanced') { echo ' checked="checked"'; } ?>> <small>Advanced</small>
			</td>
			<td class="help">Would you like to run this plugin in basic or advanced mode? Advanced offers you publishing customisation, password and login protection.</td>
		</tr>
		<tr>
			<td class="label">Display Documentation in Sidebar</td>
			<td class="field">
				<input type="radio" name="documentation_link" value="yes"<?php if($settings['documentation_link'] == 'yes') { echo ' checked="checked"'; } ?>> <small>Yes</small><br />
				<input type="radio" name="documentation_link" value="no"<?php if($settings['documentation_link'] == 'no') { echo ' checked="checked"'; } ?>> <small>No</small>
			</td>
			<td class="help">Do you want the documentation link to appear in the sidebar navigation?</td>
		</tr>
		<tr>
			<td class="label">New Windows</td>
			<td class="field">
				<input type="radio" name="open_new_windows" value="yes"<?php if($settings['open_new_windows'] == 'yes') { echo ' checked="checked"'; } ?>> <small>Yes</small><br />
				<input type="radio" name="open_new_windows" value="no"<?php if($settings['open_new_windows'] == 'no') { echo ' checked="checked"'; } ?>> <small>No</small>
			</td>
			<td class="help">Would you like downloads to open up in new windows? (Applies to Front End only)</td>
		</tr>
		<tr>
			<td class="help" colspan="3">Environment</td>
		</tr>
		<tr>
			<td class="label">Download URL</td>
			<td class="field"><input name="download_url" class="textbox" type="text" value="<?php echo $settings['download_url'] ?>" /></td>
			<td class="help">The URL to your downloads Page<br />This should be a FULL URL, which will then allow you to use subdomains if you like<br /><br /><small>e.g. 'http://www.domain.com/downloads'<br />e.g. 'http://downloads.domain.com'</small></td>
		</tr>
		<tr>
			<td class="label">Server Path</td>
			<td class="field"><input name="download_path" class="textbox" type="text" value="<?php echo $settings['download_path'] ?>" /></td>
			<td class="help">The absolute path to your downloads on the server. <strong>Be sure to CHMOD this folder to 777</strong><br /><br /><small>e.g. 'home/username/public_html/downloads'</small></td>
		</tr>
		<tr>
			<td class="label">Core Root</td>
			<td class="field"><input name="core_root" class="textbox" type="text" value="<?php echo $settings['core_root'] ?>" /></td>
			<td class="help">Only change this if you have changed your core root.</small></td>
		</tr>
		<tr>
			<td class="help" colspan="3">Security</td>
		</tr>
		<tr>
			<td class="label">MD5</td>
			<td class="field">
				<input type="radio" name="md5" value="yes"<?php if($settings['md5'] == 'yes') { echo ' checked="checked"'; } ?>> <small>Yes</small><br />
				<input type="radio" name="md5" value="no"<?php if($settings['md5'] == 'no') { echo ' checked="checked"'; } ?>> <small>No</small>
			</td>
			<td class="help">Use MD5 encryption in id. This is quite an expensive process but good for security.</td>
		</tr>
		<tr>
			<td class="label">Append Name</td>
			<td class="field">
				<input type="radio" name="append_name" value="yes"<?php if($settings['append_name'] == 'yes') { echo ' checked="checked"'; } ?>> <small>Yes</small><br />
				<input type="radio" name="append_name" value="no"<?php if($settings['append_name'] == 'no') { echo ' checked="checked"'; } ?>> <small>No</small>
			</td>
			<td class="help">Append download name to id (stops sequence guessing).</td>
		</tr>
		<tr>
			<td></td>
			<td colspan="2"><button class="submit">Save Settings</button> or <a href="<?php echo str_replace('settings/', '', $url); ?>">cancel changes</a></td>
		</tr>
	</table>
</form>