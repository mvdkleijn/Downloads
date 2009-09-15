<h1>Download History</h1>

<?php

	$settings = Plugin::getAllSettings('downloads');

	if($id == '') {
		echo '<h3>Full History</h3>';
	}
	else {
		echo '<h3>Viewing History for '.$file['name'].'</h3>';

		$count = 0;
		$failCount = 0;
		$tableRows = '';
		$graphRows = '';
		foreach($history as $item) {
			$status = $item['status'];
			if($item['status'] == 'success') {
				$count = $count + 1;
			}
			if($item['status'] == 'fail') {
				$failCount = $failCount + 1;
			}
			if($item['user_id'] != '0') {
				$profileLink = ' <a href="'.get_url('user/edit/'.$item['user_id'].'').'">[View Profile]</a>';
			}
			$tableRows .= '
			<tr class="'.odd_even().'">
				<td><img src="../'.$settings['core_root'].'/plugins/downloads/images/download-status-'.$status.'.png" /></td>
				<td>'.$item['download_name'].'</td>
				<td>'.$item['user_name']. $profileLink .'</td>
				<td>'.$item['date_downloaded'].'</td>
			</tr>';
		}
?>


<script type="text/javascript" src="<?php echo URL_PUBLIC . $settings['core_root'] .'/plugins/downloads/assets/js/swfobject.js'; ?>"></script>

<p>Added by <a href="<?php echo get_url('user/edit/'.$file['added_by_id'].''); ?>"><?php echo $file['added_by_name']; ?></a> on <?php echo date('dS F, Y', $file['date_added']); ?></p>
<p>This file has been downloaded <strong><?php echo $count; ?> times</strong></p>

<h4>Request Results:</h4>
<div id="success_fail"></div>
 	
<script type="text/javascript">
    var so = new SWFObject("<?php echo URL_PUBLIC . $settings['core_root'] .'/plugins/downloads/assets/flash/open-flash-chart.swf'; ?>", "ofc", "300", "200", "9", "#FFFFFF");
		so.addVariable("variables","true"); 
		so.addVariable("pie","60,#E4F0DB,#000000,1,,1");
		so.addVariable("values","<?php echo $failCount; ?>,<?php echo $count; ?>");
		so.addVariable("pie_labels","Failed Requests,Successful Requests");
		so.addVariable("bg_colour","#ffffff");
		so.addVariable("colours","#d01f3c,#649b2c");
		so.addVariable("allowScriptAccess", "always" );
		so.write("success_fail");
</script>

<h4>Last 7 Days:</h4>

<div id="last_seven_days"></div>
 	
<script type="text/javascript">
    var my_chart = new SWFObject("<?php echo URL_PUBLIC . $settings['core_root'] .'/plugins/downloads/assets/flash/open-flash-chart.swf'; ?>", "ofc", "600", "200", "9", "#FFFFFF");
		my_chart.addVariable("variables","true");
		my_chart.addVariable("bar","20,0x649b2c, ,10");
		my_chart.addVariable("values","29,62,58,27,21,24,45");
		my_chart.addVariable("x_labels","Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday");
		my_chart.addVariable("y_max","100");
		my_chart.addVariable("bg_colour","#FFFFFF");
		my_chart.addVariable("x_axis_colour","#FFFFFF");
		my_chart.addVariable("y_axis_colour","#FFFFFF");
		my_chart.addVariable("x_grid_colour","#FFFFFF");
		my_chart.addVariable("y_grid_colour","#FFFFFF");
		my_chart.write("last_seven_days");
</script>

<h4>Downloads by Day:</h4>
<div id="downloads_by_day"></div>
 	
<script type="text/javascript">
    var my_chart = new SWFObject("<?php echo URL_PUBLIC . $settings['core_root'] .'/plugins/downloads/assets/flash/open-flash-chart.swf'; ?>", "ofc", "600", "200", "9", "#FFFFFF");
		my_chart.addVariable("variables","true");
		my_chart.addVariable("bar","20,0x649b2c, ,10");
		my_chart.addVariable("values","29,62,58,27,21,24,45");
		my_chart.addVariable("x_labels","Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday");
		my_chart.addVariable("y_max","100");
		my_chart.addVariable("bg_colour","#FFFFFF");
		my_chart.addVariable("x_axis_colour","#FFFFFF");
		my_chart.addVariable("y_axis_colour","#FFFFFF");
		my_chart.addVariable("x_grid_colour","#FFFFFF");
		my_chart.addVariable("y_grid_colour","#FFFFFF");
		my_chart.write("downloads_by_day");
</script>

<h4>Downloads by Month:</h4>

<div id="downloads_by_month"></div>
 	
<script type="text/javascript">
    var my_chart = new SWFObject("<?php echo URL_PUBLIC . $settings['core_root'] .'/plugins/downloads/assets/flash/open-flash-chart.swf'; ?>", "ofc", "600", "200", "9", "#FFFFFF");
		my_chart.addVariable("variables","true");
		my_chart.addVariable("bar","20,0x649b2c, ,10");
		my_chart.addVariable("values","29,62,58,27,21,24,45");
		my_chart.addVariable("x_labels","Jan,Feb,Mar,Apr,May,June,July,Aug,Sep,Oct,Nov,Dec");
		my_chart.addVariable("y_max","100");
		my_chart.addVariable("bg_colour","#FFFFFF");
		my_chart.addVariable("x_axis_colour","#FFFFFF");
		my_chart.addVariable("y_axis_colour","#FFFFFF");
		my_chart.addVariable("x_grid_colour","#FFFFFF");
		my_chart.addVariable("y_grid_colour","#FFFFFF");
		my_chart.write("downloads_by_month");
</script>



<p>The file name is shown so you can see what it was when the user downloaded it. If you've edited the name since then, you will see the old name here.</p>

<table class="index">
	<thead>
		<th>Status</th>
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