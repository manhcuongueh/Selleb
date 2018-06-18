<?php
define('_PURENESS_', true);
include_once("./_common.php");

$gw_head_title = '포인트적립, 차감';
include_once(TW_ADMIN_PATH."/admin_head.php");

$mb	= get_member_no($index_no);
?>

<form name="fpointform" method="post" onsubmit="return fpointform_submit(this);">
<input type="hidden" name="index_no" value="<?php echo $index_no; ?>">
<input type="hidden" name="token" value="">

<h2 class="newp_tit"><?php echo $gw_head_title; ?></h2>
<div class="newp_wrap">
	<div class="tbl_frm02">
		<table>
		<colgroup>
			<col width="100px">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th>아이디</th>
			<td><?php echo $mb['id']; ?></td>
		</tr>
		<tr>
			<th>회원명</th>
			<td><?php echo $mb['name']; ?></td>
		</tr>
		<tr>
			<th>구분</th>
			<td class="td_label">
				<input id="kind1" type="radio" name="po_kind" value="I"> <label for="kind1">포인트적립</label>
				<input id="kind2" type="radio" name="po_kind" value="O"> <label for="kind2">포인트차감</label>
			</td>
		</tr>
		<tr>
			<th>포인트</th>
			<td><input type="text" name="po_point" required numeric itemname="포인트" class="frm_input w100 required">원</td>
		</tr>
		<tr>
			<th>사유</th>
			<td><textarea name="po_content" required itemname="적립/변경사유" class="frm_textbox wfull required" rows="7"></textarea></td>
		</tr>
		</tbody>
		</table>
	</div>
	<div class="btn_confirm">
		<input type="submit" class="btn_medium" accesskey="s" value="저장">
		<button type="button" onclick="self.close();" class="btn_medium bx-white">닫기</button>
	</div>
</div>
</form>

<script>
function fpointform_submit(f) {
	f.action = "./mem_point_req_update.php";
    return true;
}
</script>

<?php
include_once(TW_ADMIN_PATH.'/admin_tail.sub.php');
?>