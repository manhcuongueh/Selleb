<?php
define('_PURENESS_', true);
include_once("./_common.php");

if(is_admin()) {
	alert('현재 관리자로 접속중입니다.', TW_URL);
}

if(!$member['id']) {
	alert('로그인 후 이용하세요.', TW_BBS_URL.'/login.php');
}

if(is_partner($member['id'])) {
	if($member['homepage'])
		$admin_shop_url = set_http($member['homepage']);
	else
		$admin_shop_url = set_http($member['id'].'.'.$config['admin_shop_url']);
	
	// 월관리비를 사용중인가?
	if($config['p_month'] == 'y' && $member['term_date']) {
		$partner_term = '<em>(가맹점 만료일: '.date("Y년 m월 d일", $member['term_date']).')</em>';
	}
} else {
	$admin_shop_url = set_http($config['admin_shop_url']);
}

$b_config = sql_fetch(" select * from shop_partner_config where mb_grade='$member[grade]' ");
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<title>마이페이지</title>
<link rel="stylesheet" href="<?php echo TW_MYPAGE_URL; ?>/css/mypage.css?ver=<?php echo $time_Yhs;?>">
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
<div id="header"<?php if(is_seller($member['id'])) { ?> class="supply"<?php } ?>>
	<?php if(is_partner($member['id'])) { ?>
	<h1><a href="<?php echo TW_MYPAGE_URL; ?>/page.php?code=partner_info">가맹점 관리자</a></h1>
	<?php } ?>
	<?php if(is_seller($member['id'])) { ?>
	<h1><a href="<?php echo TW_MYPAGE_URL; ?>/page.php?code=seller_main">공급사 관리자</a></h1>
	<?php } ?>
	<div id="tnb">
		<ul>
			<li><?php echo $member['name']; ?>님! 접속중..<?php echo $partner_term; ?></li>
			<li>고객센터 : <?php echo $config['company_tel']; ?></li>
			<li><a href="<?php echo $admin_shop_url; ?>">쇼핑몰</a></li>
			<?php if(is_partner($member['id'])) { ?>
			<li><a href="<?php echo TW_MYPAGE_URL; ?>/page.php?code=partner_info">가맹점 관리</a></li>
			<?php } ?>
			<?php if(is_seller($member['id'])) { ?>
			<li><a href="<?php echo TW_MYPAGE_URL; ?>/page.php?code=seller_main">공급사 관리</a></li>
			<?php } ?>
			<li id="tnb_logout"><a href="<?php echo TW_BBS_URL; ?>/logout.php">로그아웃</a></li>
		</ul>
	</div>
</div>
