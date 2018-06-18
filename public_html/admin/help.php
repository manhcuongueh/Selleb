<?php
include_once("_common.php");
include_once("admin_access.php");
include_once("admin_head.php");

$pg_title = ADMIN_MENU8;
$pg_num = 8;
$snb_icon = "<i class=\"fa fa-comments-o\"></i>";

if($member['id'] != 'admin' && !$member['auth_'.$pg_num]) {
	alert("접근권한이 없습니다.");
}

if(in_array($code, array('qa','qa_form'))) $pg_title2 = ADMIN_MENU8_1;
if($code == "out") $pg_title2 = ADMIN_MENU8_2;
if(in_array($code, array('faq','faq_from'))) $pg_title2 = ADMIN_MENU8_3;
if($code == "faq_group") $pg_title2 = ADMIN_MENU8_4;

include_once("admin_topmenu.php");
?>

<div class="s_wrap">
	<h1><?php echo $pg_title2; ?></h1>
	<?php 
	include_once("./help/help_{$code}.php"); 
	?>
</div>

<?php
include_once("admin_tail.php");
?>