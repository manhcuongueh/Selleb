<?php
include_once("_common.php");
include_once("admin_access.php");
include_once("admin_head.php");

$pg_title = ADMIN_MENU3;
$pg_num = 3;
$snb_icon = "<i class=\"fa fa-truck\"></i>";

if($member['id'] != 'admin' && !$member['auth_'.$pg_num]) {
	alert("접근권한이 없습니다.");
}

if($code == "seller") $pg_title2 = ADMIN_MENU3_1;
if($code == "rigister") $pg_title2 = ADMIN_MENU3_2;
if($code == "total") $pg_title2 = ADMIN_MENU3_3;
if(in_array($code, array('xls','xls_update'))) $pg_title2 = ADMIN_MENU3_4;

include_once("admin_topmenu.php");
?>

<div class="s_wrap">
	<h1><?php echo $pg_title2; ?></h1>
	<?php
	include_once("./seller/item_{$code}.php");
	?>
</div>

<?php 
include_once("admin_tail.php");
?>