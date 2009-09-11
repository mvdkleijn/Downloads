<h1>Downloads Settings</h1>

<form action="<?php echo $url; ?>update" method="POST">
	<input type="hidden" name="active" value="1" />
	<table class="fieldset">
		<tr>
			<td class="label">Download URL</td>
			<td class="field"><input name="download_url" class="textbox" type="text" value="<?php echo $settings['download_url'] ?>" /></td>
			<td class="help">The URL to your downloads Page<br />This should be a FULL URL, which will then allow you to use subdomains if you like<br /><br /><small>e.g. 'http://www.domain.com/downloads'<br />e.g. 'http://downloads.domain.com'</small></td>
		</tr>
		<tr>
			<td class="label">Server Path</td>
			<td class="field"><input name="download_path" class="textbox" type="text" value="<?php echo $settings['download_path'] ?>" /></td>
			<td class="help">The URL to your downloads Page<br /><br /><small>e.g. 'home/username/public_html/downloads'</small></td>
		</tr>
		<tr>
			<td></td>
			<td colspan="2"><button class="submit">Save Settings</button> or <a href="<?php echo str_replace('settings/', '', $url); ?>">cancel changes</a></td>
		</tr>
	</table>
</form>