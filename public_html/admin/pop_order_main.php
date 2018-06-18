<?php
include_once("_common.php");
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta http-equiv="imagetoolbar" content="no">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<title>주문정보상세조회</title>
<?php if($gw_ico = display_logo_url('favicon_ico')) { // 파비콘 ?>
<link rel="shortcut icon" href="<?php echo $gw_ico; ?>" type="image/x-icon">
<?php } ?>
</head>
<frameset rows="0,*" border="0">
<frame name="left" scrolling="no" marginwidth="0" marginheight="0" noresize src="">
<frame name="right" scrolling="yes" marginwidth="0" marginheight="0"  src="pop_order_detail.php?code=A&index_no=<?php echo $_GET['index_no']; ?>">
</frameset>
</html>