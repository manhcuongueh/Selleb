<?php
if(!defined('_TUBEWEB_')) exit;

$mb = get_member_no($index_no);
$sr = get_seller($mb['id']);
?>

<h2>업체정보수정</h2>
<form name="fregform" method="post" onsubmit="return fregform_submit(this);">
<input type="hidden" name="index_no" value="<?php echo $index_no;?>">
<input type="hidden" name="mb_id" value="<?php echo $mb[id];?>">
<input type="hidden" name="sup_code" value="<?php echo $sr[sup_code];?>">
<input type="hidden" name="mode" value="pw">
<input type="hidden" name="code" value="pitem">

<div class="tbl_frm02">
	<table>
	<colgroup>
		<col width="130px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th>입점 승인상태</th>
		<td class="td_label">			
			<?php echo radio_checked('state', $sr['state'], '1', '승인'); ?>
			<?php echo radio_checked('state', $sr['state'], '0', '대기'); ?>
		</td>
	</tr>
	<tr>
		<th>전체 상품진열</th>
		<td class="td_label">
			<?php echo radio_checked('shop_open', $sr['shop_open'], '1', '진열'); ?>
			<?php echo radio_checked('shop_open', $sr['shop_open'], '2', '품절'); ?>
			<?php echo radio_checked('shop_open', $sr['shop_open'], '3', '단종'); ?>
			<?php echo radio_checked('shop_open', $sr['shop_open'], '4', '중지'); ?>
		</td>
	</tr>
	<tr>
		<th>제공상품</th>
		<td><input type="text" name="in_item" value="<?php echo $sr[in_item];?>" required class="frm_input w200"></td>
	</tr>
	<tr>
		<th>업체(법인)명</th>
		<td><input type="text" name="in_compay" value="<?php echo $sr[in_compay];?>" required class="frm_input w200"></td>
	</tr>
	<tr>
		<th>사업자등록번호</th>
		<td><input type="text" name="in_sanumber" value="<?php echo $sr[in_sanumber];?>" required class="frm_input w200">
		<span class="fc_197">예) 123-456-789</td>
	</tr>
	<tr>
		<th>전화번호</th>
		<td><input type="text" name="in_phone" value="<?php echo $sr[in_phone];?>" class="frm_input w200">
		<span class="fc_197">예) 02-1234 5678</td>
	</tr>
	<tr>
		<th>팩스번호</th>
		<td><input type="text" name="in_fax" value="<?php echo $sr[in_fax];?>" class="frm_input w200">
		<span class="fc_197">예) 02-1234-5678</td>
	</tr>
	<tr>
		<th>사업장주소</th>
		<td>
			<p><input class="frm_input w80" type="text" name="in_zipcode" value="<?php echo $sr[in_zipcode];?>" maxlength="5">
			<a href="javascript:win_zip('fregform', 'in_zipcode', 'in_addr1', 'in_addr2', 'in_addr3', 'in_addr_jibeon');" class="btn_small grey">우편번호</a></p>
			<p class="mart5"><input class="frm_input w325" type="text" name="in_addr1" value="<?php echo $sr[in_addr1];?>"></p>
			<p class="mart5"><input class="frm_input w325" type="text" name="in_addr2" value="<?php echo $sr[in_addr2];?>"> ※ 상세주소</p>
			<p class="mart5"><input class="frm_input w325" type="text" name="in_addr3" value="<?php echo $sr[in_addr3];?>"> ※ 참고항목
			<input type="hidden" name="in_addr_jibeon" value="<?php echo $sr[in_addr_jibeon];?>"></p>
		</td>
	</tr>
	<tr>
		<th>업태</th>
		<td><input type="text" name="in_upte" value="<?php echo $sr[in_upte];?>" required class="frm_input w200"></td>
	</tr>
	<tr>
		<th>종목</th>
		<td><input type="text" name="in_up" value="<?php echo $sr[in_up];?>" required class="frm_input w200"></td>
	</tr>
	<tr>
		<th>대표자명</th>
		<td><input type="text" name="in_name" value="<?php echo $sr[in_name];?>" required class="frm_input w200"></td>
	</tr>
	<tr>
		<th>홈페이지</th>
		<td><input type="text" name="in_home" value="<?php echo $sr[in_home];?>" class="frm_input w200"></td>
	</tr>
	<tr>
		<th>전달사항</th>
		<td><textarea name="memo" class="frm_textbox wfull"><?php echo $sr[memo];?></textarea></td>
	</tr>
	</tbody>
	</table>

	<table class="mart10">
	<colgroup>
		<col width="130px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th>은행명</th>
		<td>
			<?php echo get_bank_select("n_bank");?>
			<script>document.fregform.n_bank.value = '<?php echo $sr[n_bank];?>';</script>
		</td>
	</tr>
	<tr>
		<th>계좌번호</th>
		<td><input type="text" name="n_bank_num" value="<?php echo $sr[n_bank_num];?>" class="frm_input w200"></td>
	</tr>
	<tr>
		<th>예금주명</th>
		<td><input type="text" name="n_name" value="<?php echo $sr[n_name];?>" class="frm_input w200"></td>
	</tr>
	</tbody>
	</table>

	<table class="mart10">
	<colgroup>
		<col width="130px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th>담당자명</th>
		<td><input type="text" name="in_dam" value="<?php echo $sr[in_dam];?>" required class="frm_input w200"></td>
	</tr>	
	<tr>
		<th>담당자 핸드폰</th>
		<td><input type="text" name="n_phone" value="<?php echo $sr[n_phone];?>" required class="frm_input w200"></td>
	</tr>
	<tr>
		<th>담당자 이메일</th>
		<td><input type="text" name="n_email" value="<?php echo $sr[n_email];?>" required class="frm_input w200"></td>
	</tr>
	</tbody>
	</table>
</div>
<div class="btn_confirm">
	<input type="submit" class="btn_medium" accesskey="s" value="저장">
</div>
</form>

<script>
function fregform_submit(f) {
	f.action = "pop_member_detail.php";
    return true;
}
</script>
