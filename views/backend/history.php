<h1>Download History</h1>

<?php

	$settings = Plugin::getAllSettings('downloads');

	if($id == '') {
		echo '<h3>Full History</h3>';
	}
	else {
		echo '<h3>Viewing History for '.$file['name'].'</h3>';

		$now = time();
		$todaysDom = date('d', $now);
		$count = 0;
		$failCount = 0;
		$tableRows = '';
		$graphRows = '';
		$days = array();
		$months = array();
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
			elseif($item['user_id'] == '0') {
				$profileLink = $item['user_ip'];
			}
			$tableRows .= '
			<tr class="'.odd_even().'">
				<td><img src="../'.$settings['core_root'].'/plugins/downloads/images/download-status-'.$status.'.png" /></td>
				<td>'.$item['download_name'].'</td>
				<td>'.$item['user_name']. $profileLink .'</td>
				<td>'.$item['date_downloaded'].'</td>
			</tr>';
			$actualDate = explode('-', $item['date_downloaded']);
			$year = $actualDate['0'];
			$originalMonth = $actualDate['1'];
			$originalDay = explode(' ', $actualDate['2']);
			$originalDay = $originalDay['0'];
			$day = date('l', mktime('0', '0', '0', $originalMonth, $originalDay, $year));
			$month = date('M', mktime('0', '0', '0', $originalMonth, $originalDay, $year));
			$days[] .= $day;
			$months[] .= $month;
		}
		$days = array_count_values($days);
		$maxDay = max($days);
		$months = array_count_values($months);
		$maxMonth = max($months);
?>


<script type="text/javascript" src="<?php echo URL_PUBLIC . $settings['core_root'] .'/plugins/downloads/assets/js/swfobject.js'; ?>"></script>

<p>Added by <a href="<?php echo get_url('user/edit/'.$file['added_by_id'].''); ?>"><?php echo $file['added_by_name']; ?></a> on <?php echo date('dS F, Y', $file['date_added']); ?></p>
<p>This file has been downloaded <strong><?php echo $count; ?> times</strong></p>

<p>&nbsp;</p>

<p class="downloads-button"><span class="downloads-button"><a href="#" onclick="toggle_popup('request-results-popup', 'dummy_input'); return false;">View Request Chart</a></span> 
<span class="downloads-button"><a href="#" onclick="toggle_popup('downloads-by-month-popup', 'dummy_input'); return false;">Downloads by Month</a></span> 
<span class="downloads-button"><a href="#" onclick="toggle_popup('downloads-by-day-popup', 'dummy_input'); return false;">Downloads by Day</a></span></p>

<div class="popup" id="request-results-popup" style="display:none;">
	<h4 id="dummy_input">Request Results</h4>
	<p>This graph shows you the ratio of successful:failed file requests.</p>
	<div class="graph" id="success_fail"></div>
	<p>A file request will fail if:</p>
	<ul>
		<li>It requires login and the user is not logged in</li>
		<li>It requires a password which was not submitted, or was incorrect</li>
		<li>The file is either unavailable or is requested outside of permitted publishing times</li>
	</ul>
	<p class="downloads-button"><span class="downloads-button"><a href="#" onclick="Element.hide('request-results-popup'); return false;">Close</a></span></p>
</div>

<div class="popup" id="downloads-by-month-popup" style="display:none;">
	<h4 id="dummy_input">Download Popularity by Month</h4>
	<p>This graph shows you the number of requests/month for the lifetime of the download.</p>
	<div class="graph" id="downloads_by_month"></div>
	<p class="downloads-button"><span class="downloads-button"><a href="#" onclick="Element.hide('downloads-by-month-popup'); return false;">Close</a></span></p>
</div>

<div class="popup" id="downloads-by-day-popup" style="display:none;">
	<h4 id="dummy_input">Most Popular Days for this downloads</h4>
	<p>This graph shows you the number of requests/day for the lifetime of the download.</p>
	<div class="graph" id="downloads_by_day"></div>
	<p class="downloads-button"><span class="downloads-button"><a href="#" onclick="Element.hide('downloads-by-day-popup'); return false;">Close</a></span></p>
</div>

<script type="text/javascript">
    var so = new SWFObject("<?php echo URL_PUBLIC . $settings['core_root'] .'/plugins/downloads/assets/flash/open-flash-chart.swf'; ?>", "ofc", "600", "200", "9", "#FFFFFF");
		so.addVariable("variables","true"); 
		so.addVariable("pie","40,#E4F0DB,#000000,0, ,0");
		so.addVariable("values","<?php echo $failCount; ?>,<?php echo $count; ?>");
		so.addVariable("pie_labels","Failed Requests,Successful Requests");
		so.addVariable("bg_colour","#ffffff");
		so.addVariable("colours","#d01f3c,#649b2c");
		so.addVariable("allowScriptAccess", "always" );
		so.write("success_fail");
</script>

<script type="text/javascript">
    var downloads_by_day = new SWFObject("<?php echo URL_PUBLIC . $settings['core_root'] .'/plugins/downloads/assets/flash/open-flash-chart.swf'; ?>", "ofc", "600", "200", "9", "#FFFFFF");
		downloads_by_day.addVariable("variables","true");
		downloads_by_day.addVariable("bar","40,0x649b2c, ,1");
		downloads_by_day.addVariable("values","<?php echo '0' . $days['Monday'] .',0'.$days['Tuesday'] .',0'.$days['Wednesday'] .',0'.$days['Thursday'] .',0'.$days['Friday'] .',0'.$days['Saturday'] .',0'.$days['Sunday']; ?>");
		downloads_by_day.addVariable("x_labels","Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday");
		downloads_by_day.addVariable("y_max","<?php echo $maxDay ?>");
		downloads_by_day.addVariable("bg_colour","#FFFFFF");
		downloads_by_day.addVariable("x_axis_colour","#FFFFFF");
		downloads_by_day.addVariable("y_axis_colour","#FFFFFF");
		downloads_by_day.addVariable("x_grid_colour","#FFFFFF");
		downloads_by_day.addVariable("y_grid_colour","#FFFFFF");
		downloads_by_day.write("downloads_by_day");
</script>
 	
<script type="text/javascript">
    var downloads_by_month = new SWFObject("<?php echo URL_PUBLIC . $settings['core_root'] .'/plugins/downloads/assets/flash/open-flash-chart.swf'; ?>", "ofc", "600", "200", "9", "#FFFFFF");
		downloads_by_month.addVariable("variables","true");
		downloads_by_month.addVariable("bar","40,0x649b2c, ,1");
		downloads_by_month.addVariable("values","<?php  echo '0'.$months['Jan'] .',0'. $months['Feb'] .',0'. $months['Mar'] .',0'. $months['Apr'] .',0'. $months['May'] .',0' .$months['Jun'] .',0'.$months['Jul'] .',0'. $months['Aug'] .',0'. $months['Sep'] .',0'. $months['Oct'] .',0'. $months['Nov'] .',0'. $months['Dec']; ?>");
		downloads_by_month.addVariable("x_labels","Jan,Feb,Mar,Apr,May,June,July,Aug,Sep,Oct,Nov,Dec");
		downloads_by_month.addVariable("y_max","<?php echo $maxMonth; ?>");
		downloads_by_month.addVariable("bg_colour","#FFFFFF");
		downloads_by_month.addVariable("x_axis_colour","#FFFFFF");
		downloads_by_month.addVariable("y_axis_colour","#FFFFFF");
		downloads_by_month.addVariable("x_grid_colour","#FFFFFF");
		downloads_by_month.addVariable("y_grid_colour","#FFFFFF");
		downloads_by_month.write("downloads_by_month");
</script>

<p>&nbsp;</p>

<h4>Table of Activity:</h4>

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

<p class="downloads-button"><span class="downloads-button"><a href="javascript: history.go(-1)">Back</a></span></p>
