<?php
include_once("_common.php");
include_once("admin_access.php");
include_once("admin_head.php");

$pg_title = ADMIN_MENU9;
$pg_num = 9;
$snb_icon = "<i class=\"ionicons ion-compose\"></i>";

if($member['id'] != 'admin' && !$member['auth_'.$pg_num]) {
	alert("접근권한이 없습니다.");
}

if(in_array($code, array('contentlist','contentform'))) $pg_title2 = ADMIN_MENU9_1;
if($code == "logo") $pg_title2 = ADMIN_MENU9_2;
if(in_array($code, array('banner','banner_form'))) $pg_title2 = ADMIN_MENU9_4;
if(in_array($code, array('slider','slider_form'))) $pg_title2 = ADMIN_MENU9_5;
if(in_array($code, array('intro','intro_form'))) $pg_title2 = ADMIN_MENU9_6;
if($code == "best_item") $pg_title2 = ADMIN_MENU9_7;

include_once("admin_topmenu.php");
?>

<div class="s_wrap">
	<h1><?php echo $pg_title2; ?></h1>
	<?php
	include_once("./design/web_{$code}.php");
	?>
</div>

<?php
include_once("admin_tail.php");
?>
