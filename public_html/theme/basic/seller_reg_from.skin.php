<?php
if(!defined('_TUBEWEB_')) exit;
?>

<form name="fseller" id="fseller" method="post" action="<?php echo $from_action_url; ?>" onsubmit="return fseller_submit(this);">
<input type="hidden" name="mode" value="w">
<input type="hidden" name="token" value="<?php echo $token; ?>">

<div><img src="<?php echo TW_IMG_URL; ?>/seller_reg_from.gif"></div>
<div class="regi_box mart20">
	<h3 class="s_stit">이용약관</h3>
	<div class="agree_box mart7">
		<?php echo preg_replace("/\\\/", "", $config['shop_reg_agree']); ?>
	</div>
	<p class="mart12 tac">
		<input type="checkbox" name="chk_agree" id="chk_agree" class="mart3">
		<label for="chk_agree" class="fs13">위 내용을 읽었으며 약관에 동의합니다.</label>
	</p>
</div>

<div class="tbl_frm01 mart10">
	<table class="wfull">
	<colgroup>
		<col width="18%">
		<col width="82%">
	</colgroup>
	<tbody>
	<tr>
		<th><span class="fc_red">*</span> 제공상품</th>
		<td><input class="ed" type="text" name="in_item" required itemname='제공상품' size="35">
		<span class="marl7">* 예 : 가전제품</span></td>
	</tr>
	<tr>
		<th><span class="fc_red">*</span> 업체(법인)명</th>
		<td><input class="ed" type="text" name="in_compay" required itemname='업체(법인)명' size="35"></td>
	</tr>
	<tr>
		<th><span class="fc_red">*</span> 사업자등록번호</th>
		<td><input class="ed" type="text" name="in_sanumber" required itemname='사업자등록번호' size="35">
		<span class="marl7">* 예 : 206-23-12552</span></td>
	</tr>
	<tr>
		<th><span class="marl9"></span> 전화번호</th>
		<td><input class="ed" type="text" name="in_phone" telnumber itemname='전화번호' size="35">
		<span class="marl7">* 예 : 02-1234-5678</span></td>
	</tr>
	<tr>
		<th><span class="marl9"></span> 팩스번호</th>
		<td><input class="ed" type="text" name="in_fax" telnumber itemname='팩스번호' size="35">
		<span class="marl7">* 예 : 02-1234-5678</span></td>
	</tr>
	<tr>
		<th><span class="fc_red">*</span> 사업장주소</th>
		<td>
			<div class="tbl_wrap">
				<input class="ed" type="text" name="in_zipcode" required numeric itemname="우편번호" size="7" maxlength="5" readonly><a href="javascript:win_zip('fseller', 'in_zipcode', 'in_addr1', 'in_addr2', 'in_addr3', 'in_addr_jibeon');" class="btn_small grey marl5">우편번호</a>
			</div>
			<div class="mart5"><input class="ed" type="text" name="in_addr1" required itemname="주소" size="80" readonly></div>
			<div class="mart5"><input class="ed marr7" type="text" name="in_addr2" required itemname="상세주소" size="80"> ※ 상세주소</div>
			<div class="mart5"><input class="ed marr7" type="text" name="in_addr3" itemname="참고항목" size="80"> ※ 참고항목
			<input type="hidden" name="in_addr_jibeon" value=""></div>
		</td>
	</tr>
	<tr>
		<th><span class="fc_red">*</span> 업태</th>
		<td><input class="ed" type="text" name="in_upte" required itemname='업태' size="35">
		<span class="marl7">* 예 : 서비스업</span></td>
	</tr>
	<tr>
		<th><span class="fc_red">*</span> 종목</th>
		<td><input class="ed" type="text" name="in_up" required itemname='종목' size="35">
		<span class="marl7">* 예 : 전자상거래업</span></td>
	</tr>
	<tr>
		<th><span class="fc_red">*</span> 대표자명</th>
		<td><input class="ed" type="text" name="in_name" required itemname='대표자명' size="35"></td>
	</tr>
	<tr>
		<th><span class="marl9"></span> 홈페이지</th>
		<td><input class="ed" type="text" name="in_home" size="35">
		<span class="marl7">http://를 포함하여 입력하세요</span></td>
	</tr>
	<tr>
		<th><span class="marl9"></span> 은행명</th>
		<td><?php echo get_bank_select("n_bank"); ?></td>
	</tr>
	<tr>
		<th><span class="marl9"></span> 예금주명</th>
		<td><input class="ed" type="text" name="n_name" size="35"><span class="marl7">* 예 : 홍길동</span></td>
	</tr>
	<tr>
		<th><span class="marl9"></span> 계좌번호</th>
		<td><input class="ed" type="text" name="n_bank_num" size="35"></td>
	</tr>
	<tr>
		<th><span class="fc_red">*</span> 담당자명</th>
		<td><input class="ed" type="text" name="in_dam" required itemname='담당자명' size="35"></td>
	</tr>
	<tr>
		<th><span class="fc_red">*</span> 담당자 핸드폰</th>
		<td><input class="ed" type="text" name="n_phone" required itemname='핸드폰' size="35"></td>
	</tr>
	<tr>
		<th><span class="fc_red">*</span> 담당자 이메일</th>
		<td><input class="ed" type="text" name="n_email" required email itemname='이메일' size="35"></td>
	</tr>
	<tr>
		<th><span class="marl9"></span>전달사항</th>
		<td><textarea name="memo" rows="10" class="frm_textbox wufll"></textarea></td>
	</tr>
	</tbody>
	</table>
</div>

<div class="tac mart20">
	<input type="submit" value="신청하기" class="btn_medium">
	<a href="javascript:history.go(-1);" class="btn_medium bx-white">취소</a>
</div>

</form>

<script>
function fseller_submit(f) {
	if(f.chk_agree.checked == false) {
		alert('약관에 동의하셔야 신청 가능합니다.');
		return false;
	}

	if(confirm("입력하신 사항들이 맞는지 확인하시기 바랍니다.\n\n신청하시겠습니까?") == false)
		return false;

	return true;
}
</script>
