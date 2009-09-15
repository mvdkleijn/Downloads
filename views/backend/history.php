<h1>Download History</h1>

<?php

	if($id == '') {
		echo '<h3>Full History</h3>';
	}
	else {
		echo '<h3>Viewing History for '.$file['name'].'</h3>';

		$count = 0;
		$tableRows = '';
		foreach($history as $item) {
			if($item['status'] == 'success') {
				$count = $count + 1;
			}
			if($item['user_id'] != '0') {
				$profileLink = ' <a href="'.get_url('user/edit/'.$item['user_id'].'').'">[View Profile]</a>';
			}
			$tableRows .= '
			<tr>
				<td>'.$item['download_name'].'</td>
				<td>'.$item['user_name']. $profileLink .'</td>
				<td>'.$item['date_downloaded'].'</td>
			</tr>';
		}
?>

<p>Added by <a href="<?php echo get_url('user/edit/'.$file['added_by_id'].''); ?>"><?php echo $file['added_by_name']; ?></a> on <?php echo date('dS F, Y', $file['date_added']); ?></p>

<p>This file has been downloaded <strong><?php echo $count; ?> times</strong></p>

<p>The file name is shown so you can see what it was when the user downloaded it. If you've edited the name since then, you will see the old name here.</p>
<table class="index">
	<thead>
		<th>File Name</th>
		<th>Downloaded by</th>
		<th>Time Downloaded</th>
	</thead>
	<tbody>
<?php echo $tableRows; ?>
	</tbody>
</table>
<?php
	}
?>