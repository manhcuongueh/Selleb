<?php
if(!defined('_TUBEWEB_')) exit;
?>

<form name="fregform" method="post" onsubmit="return fregform_submit(this);">
<input type="hidden" name="token" value="">

<h2>모바일 설정</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col width="180px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th>모바일 사용여부</th>
		<td class="td_label">
			<input id="mo_shop_yn1" type="radio" name="mo_shop_yn" value="1" <?php echo get_checked($config['mo_shop_yn'], '1'); ?>> <label for="mo_shop_yn1">사용함</label>
			<input id="mo_shop_yn2" type="radio" name="mo_shop_yn" value="0" <?php echo get_checked($config['mo_shop_yn'], '0'); ?>> <label for="mo_shop_yn2">사용안함</label>
		</td>
	</tr>
	<tr>
		<th>모바일 접속주소</th>
		<td><?php echo set_http($config['admin_shop_url']); ?>/m/</td>
	</tr>
	</tbody>
	</table>
</div>

<h2>모바일 메인설정</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col width="180px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th>메인 구매후기</th>
		<td><input type="text" name="mo_about_limit" value="<?php echo $config['mo_about_limit']; ?>" class="frm_input w50"> 라인 출력</td>
	</tr>
	<tr>
		<th>상단 검색폼 기본값</th>
		<td><input type="text" name="mo_se_default" value="<?php echo $config['mo_se_default']; ?>" placeholder="예) 여행가방을 검색해보세요!" class="frm_input w470"></td>
	</tr>
	<tr>
		<th>상단 인기검색어</th>
		<td class="td_label">
			<input id="mo_se_yn1" type="radio" name="mo_se_yn" value="1" <?php echo get_checked($config['mo_se_yn'], '1'); ?>> <label for="mo_se_yn1">사용함</label>
			<input id="mo_se_yn2" type="radio" name="mo_se_yn" value="0" <?php echo get_checked($config['mo_se_yn'], '0'); ?>> <label for="mo_se_yn2">사용안함</label>
		</td>
	</tr>
	<tr>
		<th>하단 공지사항</th>
		<td class="td_label">
			<input id="mo_noti_yn1" type="radio" name="mo_noti_yn" value="1" <?php echo get_checked($config['mo_noti_yn'], '1'); ?>> <label for="mo_noti_yn1">사용함</label>
			<input id="mo_noti_yn2" type="radio" name="mo_noti_yn" value="0" <?php echo get_checked($config['mo_noti_yn'], '0'); ?>> <label for="mo_noti_yn2">사용안함</label>
		</td>
	</tr>
	</tbody>
	</table>
</div>
<div class="btn_confirm">
	<input type="submit" class="btn_large" accesskey="s" value="저장">
</div>
</form>

<script>
function fregform_submit(f) {
	f.action = "./config/mobile_update.php";
    return true;
}
</script>