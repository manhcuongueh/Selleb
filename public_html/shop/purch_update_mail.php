<?php
if(!defined('_TUBEWEB_')) exit; // 개별 페이지 접근 불가
?>

<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<title>대량구매 문의 메일</title>
</head>

<body>

<div style="margin:30px auto;width:600px;border:10px solid #f7f7f7">
    <div style="border:1px solid #dedede">
        <h1 style="padding:30px 30px 0;background:#f7f7f7;color:#555;font-size:1.4em">
            대량구매 문의 메일
        </h1>
        <span style="display:block;padding:10px 30px 30px;background:#f7f7f7;text-align:right">
            <a href="<?php echo TW_URL; ?>" target="_blank"><?php echo $config['company_name']; ?></a>
        </span>
        <p style="margin:20px 0 0;padding:30px 30px 50px;min-height:200px;height:auto !important;height:200px;border-bottom:1px solid #eee">
            상품명 : <?php echo get_text($wr_gname); ?><br>
			상품코드 : <?php echo get_text($wr_gcode); ?><br><br>
			<?php echo nl2br($wr_content); ?><br><br>
            회원님의 성원에 보답하고자 더욱 더 열심히 하겠습니다.<br>
            감사합니다.
        </p>

        <a href="<?php echo TW_URL; ?>" target="_blank" style="display:block;padding:30px 0;background:#484848;color:#fff;text-decoration:none;text-align:center">사이트바로가기</a>
    </div>
</div>

</body>
</html>