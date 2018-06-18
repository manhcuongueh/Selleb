<?php
if(!defined('_TUBEWEB_')) exit;
?>

<form name="fregform" method="post" onsubmit="return fregform_submit(this);">
<input type="hidden" name="token" value="">

<h2><?php echo $default['de_maintype_title']; ?></h2>
<div class="tbl_head01">
	<table>
	<colgroup>
		<col width="180px">
		<col width="230px">
		<col>
	</colgroup>
	<thead>
	<tr>
		<th>구분</th>
		<th>명칭</th>
		<th>상품코드</th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<td class="list1">최상위 타이틀명</td>
		<td><input type="text" name="de_maintype_title" value="<?php echo $default['de_maintype_title']; ?>" class="frm_input wfull" placeholder="최상위 타이틀명" required itemname="최상위 타이틀명"></td>
		<td></td>
	</tr>
	<?php
	$list = unserialize(base64_decode($default['de_maintype_best']));
	for($i=0; $i<10; $i++) {
	?>
	<tr>
		<td class="list1">탭메뉴 <?php echo ($i+1); ?></td>
		<td><input type="text" name="maintype_subj[]" value="<?php echo $list[$i]['subj']; ?>" class="frm_input wfull" placeholder="탭메뉴명"></td>
		<td><input type="text" name="maintype_code[]" value="<?php echo $list[$i]['code']; ?>" class="frm_input wfull" placeholder="상품코드 입력 콤마(,)로 구분하세요."></td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<input type="submit" value="저장" class="btn_large" accesskey="s">
</div>
</form>

<script>
function fregform_submit(f) {
	f.action = "./design/web_best_item_update.php";
    return true;
}
</script>
