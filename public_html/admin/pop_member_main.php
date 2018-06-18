<?php
include_once("_common.php");

$index_no = $_GET['index_no'];
if(!$_GET['code']) $code = 'pview';
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta http-equiv="imagetoolbar" content="no">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<title>회원정보</title>
<?php if($gw_ico = display_logo_url('favicon_ico')) { // 파비콘 ?>
<link rel="shortcut icon" href="<?php echo $gw_ico; ?>" type="image/x-icon">
<?php } ?>
</head>
<frameset rows="1*" cols="160, 1*" border="0">
<frame name="left" scrolling="no" marginwidth="0" marginheight="0" noresize src="pop_member_side.php?index_no=<?php echo $index_no; ?>">
<frame name="right" scrolling="yes" marginwidth="0" marginheight="0" noresize src="pop_member_detail.php?code=<?php echo $code; ?>&index_no=<?php echo $index_no; ?>">
<noframes>
<body>
<p>이 페이지를 보려면, 프레임을 볼 수 있는 브라우저가 필요합니다.</p>
</body>
</noframes>
</frameset>
</html>