<?php
$frog_url = "../../../../";

include $frog_url . 'config.php';
include $frog_url . 'frog/plugins/counter/sub/connect.php';

$id = $_GET['id'];
$sql = mysql_query("SELECT * FROM ".TABLE_PREFIX."download WHERE id='".$id."'");
$row = mysql_fetch_array($sql);
$downloads = $row['downloads'];
$url = $row['linkurl'];
$newcount = $downloads+1;
$update = mysql_query("UPDATE ".TABLE_PREFIX."download SET downloads='".$newcount."' WHERE id='".$id."'");

if(!$update){
	die("Can not update downloads. The following MySQL error has occured <br />: ".mysql_error());
}

header("Location: ".$url."");
?>