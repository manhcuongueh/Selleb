<?php
// 이 파일은 새로운 파일 생성시 반드시 포함되어야 함
if(!defined('_TUBEWEB_')) exit; // 개별 페이지 접근 불가

if(!$gw_head_title)
    $gw_head_title = get_head_title('head_title', $pt_id);
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta http-equiv="imagetoolbar" content="no">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<?php
include_once(TW_INC_PATH.'/seometa.lib.php');

if($config['add_meta'])
    echo $config['add_meta'].PHP_EOL;
?>
<title><?php echo $gw_head_title; ?></title>
<link rel="stylesheet" href="<?php echo TW_CSS_URL; ?>/default.css?ver=<?php echo $time_Yhs;?>">
<link rel="stylesheet" href="<?php echo $theme_url; ?>/style.css?ver=<?php echo $time_Yhs;?>">
<?php if($gw_ico = display_logo_url('favicon_ico')) { // 파비콘 ?>
<link rel="shortcut icon" href="<?php echo $gw_ico; ?>" type="image/x-icon">
<?php } ?>
<script>
var gw_url		= "<?php echo TW_URL; ?>";
var gw_bbs_url	= "<?php echo TW_BBS_URL; ?>";
var gw_shop_url = "<?php echo TW_SHOP_URL; ?>";
</script>
<script src="<?php echo TW_JS_URL; ?>/jquery-1.8.3.min.js"></script>
<script src="<?php echo TW_JS_URL; ?>/jquery-ui-1.10.3.custom.js"></script>
<script src="<?php echo TW_JS_URL; ?>/common.js?ver=<?php echo $time_Yhs;?>"></script>
<script src="<?php echo TW_JS_URL; ?>/slick.js"></script>
<?php if($config['sp_mouse']) { // 마우스 우클릭 방지 ?>
<script>
$(document).ready(function(){
	$(document).bind("contextmenu", function(e) {
		return false;
	});
});
$(document).bind('selectstart',function() {return false;});
$(document).bind('dragstart',function(){return false;});
</script>
<?php } ?>
<?php
if($config['head_script']) { // head 내부태그
    echo $config['head_script'].PHP_EOL;
}
?>
</head>
<body<?php echo isset($body_script) ? $body_script : ""; ?>>