<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가

$begin_time = get_microtime();

if(!$tb['title'])
    $tb['title'] = get_head_title('head_title', $pt_id);

$lo_location = addslashes($tb['title']);
if(!$lo_location)
    $lo_location = $_SERVER['REQUEST_URI'];
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
<meta name="HandheldFriendly" content="true">
<meta name="format-detection" content="telephone=no">
<?php
include_once(TW_INC_PATH.'/seometa.lib.php');

if($config['add_meta'])
    echo $config['add_meta'].PHP_EOL;
?>
<title><?php echo $tb['title']; ?></title>
<link rel="stylesheet" href="<?php echo $tb['url']; ?>/css/default.css?ver=<?php echo $time_Yhs;?>">
<link rel="stylesheet" href="<?php echo $theme_url; ?>/style.css?ver=<?php echo $time_Yhs;?>">
<?php if($gw_ico = display_logo_url('favicon_ico')) { // 파비콘 ?>
<link rel="shortcut icon" href="<?php echo $gw_ico; ?>" type="image/x-icon">
<?php } ?>
<script src="<?php echo $tb['url']; ?>/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo $tb['url']; ?>/js/iscroll.js"></script>
<script src="<?php echo $tb['url']; ?>/js/common.js?ver=<?php echo $time_Yhs;?>"></script>
<script src="<?php echo TW_URL; ?>/js/jquery-ui-1.10.3.custom.js"></script>
<script src="<?php echo TW_URL; ?>/js/slick.js"></script>
<script>
var tb_mall = "<?php echo $tb[mall]; ?>";
var tb_root = "<?php echo $tb[root]; ?>";
var tb_bbs = "<?php echo $tb[bbs]; ?>";
var tb_bbs_root = "<?php echo $tb[bbs_root]; ?>";
var tb_url = "<?php echo $tb[url]; ?>";
var tb_is_member = "<?php echo $is_member; ?>";
var tb_charset = "<?php echo $tb[charset]; ?>";
var tb_cookie_domain = "<?php echo $tb[cookie_domain]; ?>";
</script>
<?php
if($config['head_script']) { // head 내부태그
    echo $config['head_script'].PHP_EOL;
}
?>
</head>
<body<?php echo isset($tb['body_script']) ? $tb['body_script'] : ''; ?>>
<?php echo $tb['kcp_header']; /* kcp header tag */ ?>