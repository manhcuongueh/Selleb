<?php
if(!defined('_TUBEWEB_')) exit;

if($w == "u") {
	$co	= sql_fetch("select * from shop_content where co_id='$co_id'");
    if(!$co['co_id'])
        alert('등록된 자료가 없습니다.');
}

$frm_submit = '<div class="btn_confirm">
	<input type="submit" value="저장" class="btn_large" accesskey="s">
	<a href="design.php?code=contentlist'.$qstr.'&page='.$page.'" class="btn_large bx-white marl3">목록</a>'.PHP_EOL;
if($w == 'u') {
	$frm_submit .= '<a href="design.php?code=contentform" class="btn_large bx-red marl3">추가</a>'.PHP_EOL;
}
$frm_submit .= '</div>';
?>

<form name="frmcontentform" onsubmit="return frmcontentform_check(this);" method="post">
<input type="hidden" name="w" value="<?php echo $w;?>">
<input type="hidden" name="sfl" value="<?php echo $sfl;?>">
<input type="hidden" name="stx" value="<?php echo $stx;?>">
<input type="hidden" name="page"  value="<?php echo $page;?>">
<input type="hidden" name="co_id" value="<?php echo $co_id;?>">

<div class="tbl_frm02">
	<table>
	<colgroup>
		<col width="180px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th>제목</th>
		<td><input type='text' name='co_subject' value='<?php echo $co['co_subject'];?>' class="frm_input" size="80"></td>
	</tr>
	<?php if($w == 'u') { ?>
	<tr>
		<th>페이지 URL</th>
		<td><input type='text' value="/bbs/content.php?co_id=<?php echo $co_id;?>" readonly class="frm_input list2" size="80"> <a href="/bbs/content.php?co_id=<?php echo $co_id; ?>" target="_blank" class="btn_small grey">페이지 바로가기</a></td>
	</tr>
	<?php } ?>
	<tr>
		<th>PC 내용</th>
		<td>
			<?php echo editor_html('co_content', get_text($co['co_content'], 0));?>
		</td>
	</tr>
	<tr>
		<th>모바일 내용</th>
		<td>
			<?php echo editor_html('co_mobile_content', get_text($co['co_mobile_content'], 0));?>
		</td>
	</tr>
	</tbody>
	</table>
</div>

<?php echo $frm_submit; ?>
</form>

<script>
function frmcontentform_check(f) {
	<?php echo get_editor_js('co_content');?>
	<?php echo get_editor_js('co_mobile_content');?>

	f.action = './design/web_contentformupdate.php';
    return true;
}
</script>