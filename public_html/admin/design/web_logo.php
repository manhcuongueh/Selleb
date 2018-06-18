<?php
if(!defined('_TUBEWEB_')) exit;

$mb_id = $mb_id ? $mb_id : "admin";
$logo = sql_fetch("select * from shop_logo where mb_id = '$mb_id'");
?>

<form name="flogo" method="post" onsubmit="return flogofrom_submit(this)" enctype="MULTIPART/FORM-DATA">
<input type="hidden" name="token" value="">

<h2>쇼핑몰 로고</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col width="180px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th>적용대상</th>
		<td>
			<?php echo get_level_select('mb_id', 1, 7, $mb_id,"onchange='user_chage(this.value)'"); ?>
		</td>
	</tr>
	<tr>
		<th>대표 로고</th>
		<td>
			<input type="file" name="basic_logo" id="basic_logo">
			<?php
			$file = TW_DATA_PATH.'/banner/'.$logo['basic_logo'];
			if(is_file($file) && $logo['basic_logo']) {
				$basic_logo = TW_DATA_URL.'/banner/'.$logo['basic_logo'];
			?>
			<input type="checkbox" name="basic_logo_del" value="1" id="basic_logo_del">
			<label for="basic_logo_del">삭제</label>
			<div class="banner_or_img"><img src="<?php echo $basic_logo; ?>"></div>
			<?php } ?>
			<?php echo help('권장 사이즈 ('.$default['cf_logo_wpx'].'px * '.$default['cf_logo_hpx'].'px)'); ?>
		</td>
	</tr>
	<tr>
		<th>모바일 로고</th>
		<td>
			<input type="file" name="mobile_logo" id="mobile_logo">
			<?php
			$file = TW_DATA_PATH.'/banner/'.$logo['mobile_logo'];
			if(is_file($file) && $logo['mobile_logo']) {
				$mobile_logo = TW_DATA_URL.'/banner/'.$logo['mobile_logo'];
			?>
			<input type="checkbox" name="mobile_logo_del" value="1" id="mobile_logo_del">
			<label for="mobile_logo_del">삭제</label>
			<div class="banner_or_img"><img src="<?php echo $mobile_logo; ?>"></div>
			<?php } ?>
			<?php echo help('권장 사이즈 ('.$default['cf_mobile_logo_wpx'].'px * '.$default['cf_mobile_logo_hpx'].'px)'); ?>
		</td>
	</tr>
	<tr>
		<th>SNS 기본 로고</th>
		<td>
			<input type="file" name="sns_logo" id="sns_logo">
			<?php
			$file = TW_DATA_PATH.'/banner/'.$logo['sns_logo'];
			if(is_file($file) && $logo['sns_logo']) {
				$sns_logo = TW_DATA_URL.'/banner/'.$logo['sns_logo'];
			?>
			<input type="checkbox" name="sns_logo_del" value="1" id="sns_logo_del">
			<label for="sns_logo_del">삭제</label>
			<div class="banner_or_img"><img src="<?php echo $sns_logo; ?>"></div>
			<?php } ?>
			<?php echo help('최소 사이즈 (200px * 200px)'); ?>
		</td>
	</tr>
	</tbody>
	</table>
</div>

<h2>파비콘 (favicon) 설정</h2>
<div class="tbl_frm01">
	<table class="tablef">
	<colgroup>
		<col width="180px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th rowspan="2">파비콘 아이콘 (ico파일)</th>
		<td>
			<input type="file" name="favicon_ico" id="favicon_ico">
			<?php
			$file = TW_DATA_PATH.'/banner/'.$logo['favicon_ico'];
			if(is_file($file) && $logo['favicon_ico']) {
				$favicon_ico = TW_DATA_URL.'/banner/'.$logo['favicon_ico'];
			?>			
			<img src="<?php echo $favicon_ico; ?>" width="16" height="16">
			<input type="checkbox" name="favicon_ico_del" value="1" id="favicon_ico_del">
			<label for="favicon_ico_del">삭제</label>
			<?php } ?>
			<?php echo help('고정 사이즈 (16px * 16px)'); ?>
		</td>
	</tr>
	<tr>
		<td>
			<strong>파비콘(favicon) 이란?</strong>
			<p class="padt5">브라우저의 타이틀 옆에 표시되거나 즐겨찾기시 설명 옆에 표시되는 사이트의 아이콘을 말합니다.<br>크롬, 사파리, 오페라등 익스플로러 외 다른 OS이거나 브라우저 버전에 따라 출력이 되지 않을 수 있습니다.<br>파비콘(favicon)은 크기 16x16픽셀, 최대 용량 150KB의 (*.ico) 파일만 사용하실 수 있습니다.</p>
			<p class="padt5"><img src="/img/visual_favicon.jpg"></p>
		</td>
	</tr>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<input type="submit" class="btn_large" value="저장">
</div>
</form>

<script>
function flogofrom_submit(f) {
	f.action = "/admin/design/web_logo_update.php";
    return true;
}

function user_chage(mb_id){
	location.href='/admin/design.php?code=logo&mb_id='+mb_id;
}
</script>