<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<form name="fcoupon" id="fcoupon" method="post" action="<?php echo $form_action_url; ?>" onsubmit="return fcoupon_submit(this);" autocomplete="off">
<input type="hidden" name="token" value="<?php echo $token; ?>">

<div class="s_cont">
	<table class="navbar">
	<colgroup>
		<col width="50%">
		<col width="50%">
	</colgroup>
	<tbody>
	<tr>
		<td class="selected"><a href="./gift.php">인증하기</a></td>
		<td><a href="./gift_list.php">인증내역</a></td>
	</tr>
	</tbody>
	</table>

	<div class="m_gift">
		<p class="relay">
			※ 쿠폰번호 인증 완료 후 포인트가 실시간 적립됩니다.<br>
			※ 적립된 포인트는 상품구매시 바로 사용하실 수 있습니다.
		</p>
		<table class="gi_box">
		<tbody>
		<tr>
			<td class="mi_dt">
				<table style='width:100%;margin:0'>
				<colgroup>
					<col width="25%">
					<col width="25%">
					<col width="25%">
					<col width="25%">
				</colgroup>
				<tbody>
				<tr>
					<td style="padding:5px;"><input type="text" name="gi_num1" required itemname="쿠폰번호" maxlength="4" onkeyup="if(this.value.length==4) document.fcoupon.gi_num2.focus();"></td>
					<td style="padding:5px;"><input type="text" name="gi_num2" required itemname="쿠폰번호" maxlength="4" onkeyup="if(this.value.length==4) document.fcoupon.gi_num3.focus();"></td>
					<td style="padding:5px;"><input type="text" name="gi_num3" required itemname="쿠폰번호" maxlength="4" onkeyup="if(this.value.length==4) document.fcoupon.gi_num4.focus();"></td>
					<td style="padding:5px;"><input type="text" name="gi_num4" required itemname="쿠폰번호" maxlength="4"></td>
				</tr>
				</tbody>
				</table>
			</td>
		</tr>
		</tbody>
		</table>
		<p class="hofont">
			1. 쿠폰은 현금으로 교환 및 환불이 불가능 합니다.<br>
			2. 쿠폰번호는 대/소문자를 구분할 수 있으니 그대로 입력해 주세요.
		</p>
	</div>

	<div class="mart10 tac">
		<input type='submit' value='쿠폰인증하기' class='btn_large red wfull'>
	</div>
</div>
</form>

<script>
function fcoupon_submit(f) {
	if(confirm("인증 하시려면 '확인'버튼을 클릭하세요!") == false)
		return false;

	return true;
}
</script>