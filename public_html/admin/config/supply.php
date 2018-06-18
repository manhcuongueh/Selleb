<?php
if(!defined('_TUBEWEB_')) exit;
?>

<form name="fregform" method="post" onsubmit="return fregform_submit(this);">
<input type="hidden" name="token" value="">

<h2>기본설정</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col width="180px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th>입점서비스 사용</th>
		<td class="td_label">
			<label><input type="radio" name="shop_reg_yes" value="1"<?php echo ($config[shop_reg_yes]==1)?" checked":""?>> 사용함</label>
			<label><input type="radio" name="shop_reg_yes" value="0"<?php echo ($config[shop_reg_yes]==0)?" checked":""?>> 사용안함</label>
		</td>
	</tr>
	<tr>
		<th>신규상품등록 진열</th>
		<td class="td_label">			
			<label><input type="radio" name="shop_reg_auto" value="1"<?php echo ($config[shop_reg_auto]==1)?" checked":""?>> 관리자 승인</label>
			<label><input type="radio" name="shop_reg_auto" value="0"<?php echo ($config[shop_reg_auto]==0)?" checked":""?>> 등록시 바로 승인</label>
		</td>
	</tr>
	<tr>
		<th>기존상품수정 진열</th>
		<td class="td_label">			
			<label><input type="radio" name="shop_mod_auto" value="1"<?php echo ($config[shop_mod_auto]==1)?" checked":""?>> 관리자 승인</label>
			<label><input type="radio" name="shop_mod_auto" value="0"<?php echo ($config[shop_mod_auto]==0)?" checked":""?>> 수정시 바로 승인</label>
		</td>
	</tr>
	<tr>
		<th>결제수수료 처리여부</th>
		<td>
			<p>
				<input id='ids_i1' type="radio" value="0" name="shop_i" <?php echo ($config[shop_i]==0)?"checked":""?>>
				<label for='ids_i1'>공급업체 부담 <span class="fc_197">(공급업체 정산시 결제수수료를 제외합니다.)</span></label>
			</p>
			<p class="mart5">
				<input id='ids_i2' type="radio" value="1" name="shop_i" <?php echo ($config[shop_i]==1)?"checked":""?>>
				<label for='ids_i2'>본사상점 부담 <span class="fc_197">(본사 순수 마진에서 결제수수료를 제외합니다.)</span></label>
			</p>
		</td>
	 </tr>
	 <tr>
		<th>결제수수료 설정</th>
		<td class="tbl_frm02">
			<table>
			<colgroup>
				<col width="150px">
				<col>
			</colgroup>
			<tbody>
			<tr>
				<th>신용카드</th>
				<td>
					<input type="text" size="10" name="shop_card" value="<?php echo $config[shop_card]; ?>" class="frm_input w80"> %
					<span class="fc_197 marl5">소수점까지 포함하여 입력하세요!</span>
				</th>
			</tr>
			<tr>
				<th>실시간계좌이체</th>
				<td>
					<input type="text" size="10" name="shop_bank" value="<?php echo $config[shop_bank]; ?>" class="frm_input w80"> %
					<span class="fc_197 marl5">소수점까지 포함하여 입력하세요!</span>
				</th>
			</tr>
			<tr>
				<th>휴대폰결제</th>
				<td>
					<input type="text" name="shop_phone" value="<?php echo $config[shop_phone]; ?>" class="frm_input w80">
					<select name="shop_phone_type">
					<option value="won" <?php echo ($config[shop_phone_type]=="won")?"selected":""?>>원</option>
					<option value="%" <?php echo ($config[shop_phone_type]=="%")?"selected":""?>>%</option>
					</select>
					<span class="fc_197 marl5">금액일 경우 콤마 (",") 없이 입력하세요!</span>
				</td>
			</tr>
			<tr>
				<th>가상계좌</th>
				<td>
					<input type="text" size="10" name="shop_yesc" value="<?php echo $config[shop_yesc]; ?>" class="frm_input w80">
					<select name="shop_yesc_type">
					<option value="won" <?php echo ($config[shop_yesc_type]=="won")?"selected":""?>>원</option>
					<option value="%" <?php echo ($config[shop_yesc_type]=="%")?"selected":""?>>%</option>
					</select>
					<span class="fc_197 marl5">금액일 경우 콤마 (",") 없이 입력하세요!</span>
				</td>
			</tr>
			</tbody>
			</table>
			<?php echo help('PG사와 실제 계약한 정보를 그대로 입력 하세요! 만약 빈공란일경우 차감되지 않습니다.'); ?>
		</td>
	</tr>
	</tbody>
	</table>
</div>

<h2>가입설정</h2>
<div class="tbl_frm01">
	<table class="tablef">
	<colgroup>
		<col width="180px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th>입점 가입약관</th>
		<td><textarea name="shop_reg_agree" class="frm_textbox wfull" rows="7"><?php echo preg_replace("/\\\/", "", $config['shop_reg_agree']); ?></textarea></td>
	</tr>	
	<tr>
		<th>입점 이용안내</th>
		<td>
			<?php echo editor_html('shop_reg_guide', get_text(stripslashes($config['shop_reg_guide']), 0));?>	
		</td>
	</tr>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<input type="submit" value="저장" class="btn_large" accesskey="s">
</div>
</form>

<div class="information">
	<h4>도움말</h4>
	<div class="content">
		<div class="hd">ㆍ꼭! 알아두기</div>
		<div class="desc01 accent">
			<p>ㆍ정책 안내1) 공급업체의 상품등록은 카테고리 제한을 두지 않습니다. 단! 관리자의 승인 여하에 따라 매장에 노출 됩니다.</p>
			<p>ㆍ정책 안내2) 공급업체에서 등록한 상품들은 "공급업체관리" 에서 관리 가능합니다.</p>
			<p>ㆍ정책 안내3) 고객에게 주문이 들어오면 공급업체별로 자사의 상품에 대해서만 분기하여 개별적으로 직배송 처리 됩니다.</p>
		</div>
	 </div>
</div>

<script>
function fregform_submit(f) {
	<?php echo get_editor_js('shop_reg_guide');?>
	f.action = "./config/supply_update.php";
    return true;
}
</script>
