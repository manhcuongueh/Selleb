<?php
if(!defined('_TUBEWEB_')) exit;

if(!$gw_head_title)
    $gw_head_title = '관리자 페이지';
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<title><?php echo $gw_head_title; ?></title>
<link rel="stylesheet" href="<?php echo TW_ADMIN_URL; ?>/css/admin.css?ver=<?php echo $time_Yhs;?>">
<?php if($gw_ico = display_logo_url('favicon_ico')) { // 파비콘 ?>
<link rel="shortcut icon" href="<?php echo $gw_ico; ?>" type="image/x-icon">
<?php } ?>
<script>
// 자바스크립트에서 사용하는 전역변수 선언
var gw_url		 = "<?php echo TW_URL; ?>";
var gw_bbs_url	 = "<?php echo TW_BBS_URL; ?>";
var gw_inc_url   = "<?php echo TW_INC_URL; ?>";
var gw_shop_url  = "<?php echo TW_SHOP_URL; ?>";
var gw_admin_url = "<?php echo TW_ADMIN_URL; ?>";
</script>
<script src="<?php echo TW_JS_URL; ?>/jquery-1.8.3.min.js"></script>
<script src="<?php echo TW_JS_URL; ?>/jquery-ui-1.10.3.custom.js"></script>
<script src="<?php echo TW_JS_URL; ?>/common.js?ver=<?php echo $time_Yhs;?>"></script>
<script src="<?php echo TW_JS_URL; ?>/categorylist.js?ver=<?php echo $time_Yhs;?>"></script>
</head>
<body>
