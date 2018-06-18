<?php
if(!defined('_TUBEWEB_')) exit;
?>

<p class="tit_navi">홈 <i class="ionicons ion-ios-arrow-right"></i> 쇼핑몰분양신청</p>
<h2 class="stit">쇼핑몰분양신청</h2>

<form name="fpartner" id="fpartner" method="post" action="<?php echo $from_action_url; ?>" onsubmit="return fpartner_submit(this);" autocomplete="off">
<input type="hidden" name="token" value="<?php echo $token; ?>">

<div class="regi_box mart20">
	<h3 class="s_stit">가맹점 이용약관</h3>
	<div class="agree_box mart7">
		<?php echo preg_replace("/\\\/", "", $config['p_reg_agree']); ?>
	</div>
	<p class="mart12 tac">
		<input type="checkbox" name="chk_agree" id="chk_agree" class="mart3">
		<label for="chk_agree" class="fs13">위 내용을 읽었으며 약관에 동의합니다.</label>
	</p>
</div>

<h3 class="s_stit mart30 marb5">입금받으실 계좌정보</h3>
<div class="tbl_frm01">
	<table class="wfull">
	<colgroup>
		<col width='18%'>
		<col width='82%'>
	</colgroup>
	<tr>
		<th>은행명</th>
		<td>
			<?php echo get_bank_select("bank_company","required itemname='은행명'"); ?>
		</td>
	</tr>
	<tr>
		<th>계좌번호</th>
		<td><input class="ed" type="text" name="bank_number" required itemname='계좌번호' size="30"></td>
	</tr>
	<tr>
		<th>예금주명</th>
		<td><input class="ed" type="text" name="bank_name" value="<?php echo $member['name']; ?>"
		required itemname='예금주명' size="15">
		<span class="marl7">* 예 : 홍길동</span></td>
	</tr>
	<tr>
		<th>전달사항</th>
		<td><textarea name="memo" class="frm_textbox wufll h60"></textarea></td>
	</tr>
	</table>
</div>

<h3 class="s_stit mart30 marb5">서비스 신청정보</h3>
<div class="tbl_frm01">
	<table class="wfull">
	<colgroup>
		<col width='18%'>
		<col width='82%'>
	</colgroup>
	<tr>
		<th>서비스명 선택</th>
		<td class="td_label">
			<?php
			$money = array();
			$sql = " select * from shop_partner_config ";
			$sql.= " where etc4 IN ('item1','item2','item3','item4','item5') and state='y' ";
			$res = sql_query($sql);
			for($i=0; $row=sql_fetch_array($res); $i++) {
				$money[] = $row['etc2'];
			?>
			<input type="radio" name="cf_1" onclick="chk_price('<?php echo number_format($row['etc2']); ?>')" value="<?php echo $row['index_no']; ?>" id="cf_1_<?php echo $i; ?>" <?php echo get_checked($i, 0); ?>> <label for="cf_1_<?php echo $i; ?>"><?php echo $row['etc1']; ?></label>
			<?php } ?>
		</td>
	</tr>
	<tr>
		<th>결제금액</th>
		<td><input class="ed" type="text" name="bank_money" value="<?php echo number_format($money[0]); ?>" readonly size="10"> 원</td>
	</tr>
	<tr>
		<th>결제방식</th>
		<td>
			<input type="radio" name="bank_type" value="1" id="bank_type" checked="checked">
			<label for="bank_type">무통장</label>
		</td>
	</tr>
	<tr>
		<th>입금자명</th>
		<td><input class="ed" type="text" name="bank_name2" value="<?php echo $member['name']; ?>" size="15">
		<span class="marl7">* 예 : 홍길동</span></td>
	</tr>
	<tr>
		<th>무통장입금계좌</th>
		<td>
			<?php echo get_bank_account("bank_acc"); ?>
		</td>
	</tr>
	</table>
</div>

<div class="tac mart20">
	<input type="submit" value="신청하기" class="btn_medium">
	<a href="javascript:history.go(-1);" class="btn_medium bx-white">취소</a>
</div>
</form>

<script>
function fpartner_submit(f) {
	if(f.chk_agree.checked == false) {
		alert('약관에 동의하셔야 신청 가능합니다.');
		return false;
	}

	if(confirm("입력하신 사항들이 맞는지 확인하시기 바랍니다.\n\n신청하시겠습니까?") == false)
		return false;

	return true;
}

function chk_price(val){
	var f = document.fpartner;
	f.bank_money.value = val;
}
</script>
