<?php
define('_PURENESS_', true);
include_once("./_common.php");

$gw_head_title = '수수료 적립/차감';
include_once(TW_ADMIN_PATH."/admin_head.php");

$mb	= get_member_no($index_no);
?>

<h1 class="newp_tit"><?php echo $gw_head_title; ?></h1>
<div class="newp_wrap">
	<form name="fpointform" method="post" onsubmit="return fpointform_submit(this);">
	<input type="hidden" name="index_no" value="<?php echo $index_no; ?>">
	<input type="hidden" name="mode" value="w">
	<div class="tbl_frm02">
		<table>
		<colgroup>
			<col width="100px">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th>회원명</th>
			<td><?php echo $mb['name']; ?></td>
		</tr>
		<tr>
			<th>아이디</th>
			<td><?php echo $mb['id']; ?></td>
		</tr>
		<tr>
			<th>종류</th>
			<td class="td_label">
				<input id="kind1" type="radio" name="po_kind" value="I" checked> <label for="kind1">적립</label>
				<input id="kind2" type="radio" name="po_kind" value="O"> <label for="kind2">차감</label>
			</td>
		</tr>
		<tr>
			<th>수수료</th>
			<td><input type="text" name="po_point" required numeric itemname="수수료" class="frm_input w80">원</td>
		</tr>
		<tr>
			<th>사유</th>
			<td><textarea name="po_content" required itemname="사유" rows="7" class="frm_textbox wfull"></textarea></td>
		</tr>
		</tbody>
		</table>
	</div>
	<div class="btn_confirm">
		<input type="submit" class="btn_medium" accesskey="s" value="저장">
		<button type="button" onclick="self.close();" class="btn_medium bx-white">닫기</button>
	</div>
	</form>
</div>

<script src="<?php echo TW_JS_URL; ?>/wrest.js"></script>
<script>
function fpointform_submit(f) {
	f.action = "/admin/partner/pt_req_update.php";
    return true;
}
</script>

<?php
include_once(TW_ADMIN_PATH.'/admin_tail.sub.php');
?>