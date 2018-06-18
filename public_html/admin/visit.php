<?php
include_once("_common.php");
include_once("admin_access.php");
include_once("admin_head.php");

$pg_title = ADMIN_MENU7;
$pg_num = 7;
$snb_icon = "<i class=\"fa fa-bar-chart\"></i>";

if($member['id'] != 'admin' && !$member['auth_'.$pg_num]) {
	alert("접근권한이 없습니다.");
}

if($code == "hour")		$pg_title2 = ADMIN_MENU7_1;
if($code == "date")		$pg_title2 = ADMIN_MENU7_2;
if($code == "week")		$pg_title2 = ADMIN_MENU7_3;
if($code == "month")	$pg_title2 = ADMIN_MENU7_4;
if($code == "year")		$pg_title2 = ADMIN_MENU7_5;
if($code == "browser")	$pg_title2 = ADMIN_MENU7_6;
if($code == "os")		$pg_title2 = ADMIN_MENU7_7;
if($code == "domain")	$pg_title2 = ADMIN_MENU7_8;
if($code == "search")	$pg_title2 = ADMIN_MENU7_9;
if($code == "order1")	$pg_title2 = ADMIN_MENU7_10;
if($code == "order2")	$pg_title2 = ADMIN_MENU7_11;
if($code == "cancel")	$pg_title2 = ADMIN_MENU7_12;
if($code == "return")	$pg_title2 = ADMIN_MENU7_13;
if($code == "change")	$pg_title2 = ADMIN_MENU7_14;

include_once("admin_topmenu.php");
?>

<div class="s_wrap">
	<h1><?php echo $pg_title2; ?></h1>
	<?php	
	include_once("./visit/visit_{$code}.php");
	?>
</div>

<?php 
include_once("admin_tail.php");
?>