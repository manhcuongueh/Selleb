<?php
if(!defined('_TUBEWEB_')) exit;
?>

<form name="fregform" method="post" onsubmit="return fregform_submit(this);">
<input type="hidden" name="token" value="">

<h2>수수료 실행여부</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col width="160px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th>수수료 정산일자</th>
		<td class="bo_label">
			<label><input type="radio" name="p_type" value="month" <?php echo ($config[p_type]=='month')?"checked":"";?>> 월단위 수수료정산<span>(매월 1일 ~ 말일까지 기준으로 정산 합니다)</span></label>	<br>		
			<label><input type="radio" name="p_type" value="ju" <?php echo ($config[p_type]=='ju')?"checked":"";?>> 주단위 수수료정산<span>(매주 일요일~토요일까지 7일 단위로 정산합니다)</span></label><br>				
			<label><input type="radio" name="p_type" value="time" <?php echo ($config[p_type]=='time')?"checked":"";?>> 실시간 수수료정산<span>(회원이 수익금이 있을때마다 직접 정산 요청합니다)</span></label>				
		</td>
	</tr>
	<tr>
		<th>추천 분양수수료</th>
		<td>
			<label><input type="radio" name="p_member" value="y" <?php echo ($config[p_member]=='y')?"checked":"";?>> 사용함</label>
			<label><input type="radio" name="p_member" value="n" <?php echo ($config[p_member]=='n')?"checked":"";?>> 사용안함</label>
			<?php echo help('기존 가맹점의 추천으로 다시 상점이 분양될경우 수수료를 지급합니다.'); ?>
		</td>
	</tr>
	<tr>
		<th rowspan="2">상품 판매수수료</th>
		<td>
			<label><input type="radio" name="p_shop" value="y" <?php echo ($config[p_shop]=='y')?"checked":"";?>> 사용함</label>
			<label><input type="radio" name="p_shop" value="n" <?php echo ($config[p_shop]=='n')?"checked":"";?>> 사용안함</label>
			<?php echo help('자신의 가맹점에 가입한 회원이 상품구매 발생건당 수수료를 지급합니다.<br>본사 또는 다른 가맹점 상점에서 상품을 구매하더라도 자신에게 수수료가 지급 됩니다.'); ?>
		</td>
	</tr>
	<tr>
		<td class="bo_label">
			<label><input type="radio" name="p_shop_flag" value="0" <?php echo ($config[p_shop_flag]=='0')?"checked":"";?>> <em class="fc_red">결제액 - (배송비 + 쿠폰 + 적립금결제액) = 순수결제액</em> 에서 판매수수료를 배분</label><br>
			<label><input type="radio" name="p_shop_flag" value="1" <?php echo ($config[p_shop_flag]=='1')?"checked":"";?>> <em class="fc_red">판매가 - (사입가 + 쿠폰 + 적립금결제액) = 마진</em> 에서 판매수수료를 배분<span>(마진이 없으면 적립되지 않음)</span></label><br>
			<label><input type="radio" name="p_shop_flag" value="2" <?php echo ($config[p_shop_flag]=='2')?"checked":"";?>> <em class="fc_red">판매가 - 사입가 = 마진</em> 에서 판매수수료를 배분<span>(쿠폰 및 적립금 사용액은 무시하고 무조건 적립)</span></label>
		</td>
	</tr>
	<tr>
		<th>클릭당 광고수수료</th>
		<td>
			<label class="marr10"><input type="radio" name="p_login" value="y" <?php echo ($config[p_login]=='y')?"checked":"";?>> 사용함</label>
			<label><input type="radio" name="p_login" value="n" <?php echo ($config[p_login]=='n')?"checked":"";?>> 사용안함</label>
			<?php echo help('블로그, 카페, 웹사이트, SNS등 자신의 광고물을 클릭하여 자신의 분양몰에 접속될경우 건당 접속수수료를 지급합니다.<br>단! IP당 하루에 한번만 지급되며 관리자에 항시 모니터링이 필요 합니다.'); ?>
		</td>
	</tr>
	</tbody>
	</table>
</div>

<h2>기타 환경설정</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col width="160px">
		<col width="">
	</colgroup>
	<tbody>
	<tr>
		<th>가맹점 모집</th>
		<td>
			<label><input type="radio" name="partner_reg_yes" value="1" <?php echo ($config[partner_reg_yes]=='1')?"checked":"";?>> 사용함</label>
			<label><input type="radio" name="partner_reg_yes" value="0" <?php echo ($config[partner_reg_yes]=='0')?"checked":"";?>> 사용안함</label>
		</td>
	</tr>
	<tr>
		<th>판매수수료 노출여부</th>
		<td>
			<label><input type="radio" name="p_payment_yes" value="1" <?php echo ($config[p_payment_yes]=='1')?"checked":"";?>> 노출함</label>
			<label><input type="radio" name="p_payment_yes" value="0" <?php echo ($config[p_payment_yes]=='0')?"checked":"";?>> 노출안함</label>
			<?php echo help('가맹점 마이페이지 > 본사상품목록과 상품상세페이지에 노출합니다.'); ?>
		</td>
	</tr>
	<tr>
		<th>수수료정산시 세금공제</th>
		<td>
			<input type="text" name="accent_tax" value="<?php echo $config[accent_tax];?>" class="frm_input w70"> %
			<?php echo help('수수료정산시 세금공제 후 나머지 실금액만 정산처리됩니다'); ?>
		</td>
	</tr>
	<tr>
		<th>정산요청 가능금액</th>
		<td>
			<input type="text" name="accent_max" value="<?php echo number_format($config[accent_max]);?>" class="frm_input w70" onkeyup="addComma(this)"> 원
			<?php echo help('실시간 수수료정산일때만 입력하세요! 입력하신 금액 이상일때만 신청 가능'); ?>
		</td>
	</tr>
	<tr>
		<th>개별 상품판매</th>
		<td class="bo_label">
			<label><input type="radio" name="p_use_good" value="1" <?php echo ($config[p_use_good]=='1')?"checked":"";?>> 개별 상품판매 불가<span>(본사 상품만 판매가능하며 개별 상품은 판매할 수 없습니다.)</span></label><br>				
			<label><input type="radio" name="p_use_good" value="2" <?php echo ($config[p_use_good]=='2')?"checked":"";?>> 개별 상품판매 허용 (전체가맹점)<span>(전체 가맹점 모두 개별 상품을 판매할 수 있습니다.)</span></label><br>			
			<label><input type="radio" name="p_use_good" value="3" <?php echo ($config[p_use_good]=='3')?"checked":"";?>> 개별 상품판매 허용 (본사지정)<span>(본사에서 지정한 가맹점만 개별 상품을 판매할 수 있습니다. 회원정보 수정에서 승인 가능!)</span></label>				
		</td>
	</tr>
	<tr>
		<th>개별 카테고리</th>
		<td class="bo_label">
			<label><input type="radio" name="p_use_cate" value="1" <?php echo ($config[p_use_cate]=='1')?"checked":"";?>> 본사 카테고리 고정<span>(본사 카테고리를 수정은 할수있지만 개별적으로 생성은 불가합니다.)</span></label><br>			
			<label><input type="radio" name="p_use_cate" value="2" <?php echo ($config[p_use_cate]=='2')?"checked":"";?>> 개별 카테고리 허용<span>(본사 카테고리 + 개별 카테고리 혼합설정 가능합니다)</span></label>				
		</td>
	</tr>
	<tr>
		<th>개별 결제연동</th>
		<td class="bo_label">
			<label><input type="radio" name="p_use_pg" value="1" <?php echo ($config[p_use_pg]=='1')?"checked":"";?>> 본사 PG결제 고정<span>(본사 PG결제 및 입금계좌로 고정 결제 됩니다.)</span></label><br>			
			<label><input type="radio" name="p_use_pg" value="2" <?php echo ($config[p_use_pg]=='2')?"checked":"";?>> 개별 PG결제 허용 (무조건)<span>(전체 가맹점 모두 본사+본인상품 제한없이 무조건 본인 PG결제 및 입금계좌로 결제 됩니다.)</span></label><br>	
			<label><input type="radio" name="p_use_pg" value="3" <?php echo ($config[p_use_pg]=='3')?"checked":"";?>> 개별 PG결제 허용 (본사지정)<span>(본사에서 지정한 가맹점만 본사+본인상품 제한없이 무조건 본인 PG결제 및 입금계좌로 결제 됩니다. 회원정보 수정에서 승인 가능!)</span></label>
		</td>
	</tr>
	<tr>
		<th>가맹점 이용약관</th>
		<td><textarea name="p_reg_agree" class="frm_textbox wfull" rows="7"><?php echo preg_replace("/\\\/", "", $config['p_reg_agree']); ?></textarea></td>
	</tr>
	</tbody>
	</table>
</div>

<h2>월관리비 설정</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col width="160px">
		<col width="">
	</colgroup>
	<tbody>
	<tr>
		<th>월관리비</th>
		<td>
			<label><input type="radio" name="p_month" value="y" <?php echo ($config[p_month]=='y')?"checked":"";?>> 사용함</label>
			<label><input type="radio" name="p_month" value="n" <?php echo ($config[p_month]=='n')?"checked":"";?>> 사용안함</label>
			<?php echo help('가맹점을 신청하여 승인된 날을 기준으로 월관리비를 받습니다.'); ?>
		</td>
	</tr>
	<tr>
		<th>월관리비 미납시</th>
		<td class="bo_label">
			<label><input type="checkbox" name="accent_tree" value="y" <?php echo ($config[accent_tree]=='y')?"checked":"";?>> 상점 로그인차단<span>(관리비 미납으로 로그인차단 이라는 경고문구가 출력되며 로그인을 차단)</span></label><br>
			<label><input type="checkbox" name="accent_one" value="y" <?php echo ($config[accent_one]=='y')?"checked":"";?>> 모든 운영권한을 본사로 귀속함<span>(미납이후 발생되는 수수료 관련 및 회원가입시 추천인은 본사로 자동변환됨)</span></label>
		</td>
	</tr>
	</tbody>
	</table>
</div>
<div class="btn_confirm">
	<input type="submit" value="저장" class="btn_large" accesskey="s">
</div>
</form>

<script>
function fregform_submit(f) {
	f.action = "./partner/pt_regis_update.php";
    return true;
}
</script>
