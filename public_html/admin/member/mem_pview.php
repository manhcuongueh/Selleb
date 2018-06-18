<?php
if(!defined('_TUBEWEB_')) exit;

$mb = get_member_no($index_no);
$pt = get_partner($mb['id']);
?>

<h2>회원정보수정</h2>
<form name="fregister" method="post" onsubmit="return fregister_submit(this);">
<input type="hidden" name="mode" value="w">
<input type="hidden" name="index_no" value="<?php echo $index_no;?>">
<input type="hidden" name="code" value="<?php echo $code;?>">
<input type="hidden" name="mb_id" value="<?php echo $mb['id'];?>">

<div class="tbl_frm02">
	<table class="tablef">
	<colgroup>
		<col width="130px">
		<col>
		<col width="130px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th>회원명</th>
		<td><input class="frm_input wfull" type="text" name="name" value="<?php echo $mb[name];?>"></td>
		<th>아이디</th>
		<td><?php echo $mb[id];?></td>
	</tr>
	<tr>
		<th>패스워드</th>
		<td><input class="frm_input wfull" type="text" name="passwd"></td>
		<th>추천인</th>
		<td><input class="frm_input wfull" type="text" name="pt_id" value="<?php echo $mb[pt_id];?>"></td>
	</tr>
	<tr>
		<th>생년월일</th>
		<td>
			<input class="frm_input w60" type="text" name="birth_year" value="<?php echo $mb[birth_year];?>"> -
			<input class="frm_input w60" type="text" name="birth_month" value="<?php echo $mb[birth_month];?>"> -
			<input class="frm_input w60" type="text" name="birth_day" value="<?php echo $mb[birth_day];?>">
		</td>
		<th>E-Mail</th>
		<td><input class="frm_input wfull" type="text" name="email" value="<?php echo $mb[email];?>"></td>
	</tr>
	<tr>
		<th>전화번호</th>
		<td><input class="frm_input wfull" type="text" name="telephone" value="<?php echo replace_tel($mb[telephone]);?>"></td>
		<th>휴대전화</th>
		<td><input class="frm_input wfull" type="text" name="cellphone" value="<?php echo replace_tel($mb[cellphone]);?>"></td>
	</tr>
	<tr>
		<th>주소</th>
		<td colspan="3">
			<p>
			<input class="frm_input w80" type="text" name="zip" value="<?php echo $mb[zip];?>" numeric itemname="우편번호" maxlength="5" readonly>
			<a href="javascript:win_zip('fregister', 'zip', 'addr1', 'addr2', 'addr3', 'addr_jibeon');" class="btn_small grey">우편번호</a>
			</p>
			<p class="mart5"><input class="frm_input w325" type="text" name="addr1" value="<?php echo $mb[addr1];?>" itemname="주소" readonly></p>
			<p class="mart5"><input class="frm_input w325" type="text" name="addr2" value="<?php echo $mb[addr2];?>" itemname="상세주소"> ※ 상세주소</p>
			<p class="mart5"><input class="frm_input w325" type="text" name="addr3" value="<?php echo $mb[addr3];?>" itemname="참고항목"> ※ 참고항목
			<input type="hidden" name="addr_jibeon" value="<?php echo $mb[addr_jibeon];?>"></p>
		</td>
	</tr>
	<tr>
		<th>성별</th>
		<td>
			<select name="gender">
				<option value="">선택</option>
				<option <?php echo get_selected($mb[gender], 'M'); ?> value="M">남자</option>
				<option <?php echo get_selected($mb[gender], 'F'); ?> value="F">여자</option>
			</select>
		</td>
		<th>음력/양력</th>
		<td>
			<select name="birth_type" class='select_pm'>
				<option value="">선택</option>
				<option <?php echo get_selected($mb[birth_type], 'S'); ?> value="S">양력</option>
				<option <?php echo get_selected($mb[birth_type], 'L'); ?> value="L">음력</option>
			</select>
		</td>
	</tr>
	<tr>
		<th>회원레벨</th>
		<td><?php echo get_member_select("mb_grade", $mb['grade']);?></td>
		<th>포인트</th>
		<td>
			<b><?php echo number_format($mb[point]);?></b> Point
			<a href='./member/mem_point_req.php?index_no=<?php echo $index_no; ?>' onclick="openwindow(this,'pop_point_req','600','500','yes');return false" class="btn_small grey marl10">강제적립</a>
		</td>
	</tr>
	<tr class="mb_adm_fld">
		<th>부운영자 접근허용</th>
		<td colspan="3">
			<div class="sub_frm02">
				<table>
				<tr>
					<?php for($i=0; $i<5; $i++) { $k = ($i+1); ?>
					<td><input id="auth_<?php echo $k;?>" type="checkbox" name="auth_<?php echo $k;?>" value="1" <?php echo get_checked($mb['auth_'.$k], '1'); ?>> <label for="auth_<?php echo $k;?>"><?php echo $gw_auth[$i];?></label></td>
					<?php } ?>
				</tr>
				<tr>
					<?php for($i=5; $i<10; $i++) { $k = ($i+1); ?>
					<td><input id="auth_<?php echo $k;?>" type="checkbox" name="auth_<?php echo $k;?>" value="1" <?php echo get_checked($mb['auth_'.$k], '1'); ?>> <label for="auth_<?php echo $k;?>"><?php echo $gw_auth[$i];?></label></td>
					<?php } ?>
				</tr>
				</table>
			</div>
		</td>
	</tr>
	<tr class="pt_pay_fld">
		<th class="fc_red">추가 판매수수료</th>
		<td colspan="3">
			<input class="frm_input w80" type="text" name="payment" value="<?php echo $mb['payment'];?>">
			<select name="payflag">
				<option value="1" <?php echo get_selected($mb['payflag'], '1');?>>%</option>
				<option value="0" <?php echo get_selected($mb['payflag'], '0');?>>원</option>
			</select>
			(판매수수료를 개별적으로 추가적립 하실 수 있습니다)
		</td>
	</tr>
	<tr class="pt_pay_fld">
		<th class="fc_red">계좌번호</th>
		<td><input class="frm_input wfull" type="text" name="bank_number" value="<?php echo $pt[bank_number];?>"></td>
		<th class="fc_red">은행명/예금주명</th>
		<td>
			<?php echo get_bank_select("bank_company");?>
			<input class="frm_input w100" type="text" name="bank_name" value="<?php echo $pt[bank_name];?>">
			<script>document.fregister.bank_company.value = '<?php echo $pt[bank_company];?>';</script>
		</td>
	</tr>
	<tr class="pt_pay_fld">
		<th class="fc_197">PC 쇼핑몰스킨</th>
		<td>
			<?php echo get_theme_select('theme', $mb['theme']); ?>
		</td>
		<th class="fc_197">모바일 쇼핑몰스킨</th>
		<td>
			<?php echo get_mobile_theme_select('mobile_theme', $mb['mobile_theme']); ?>
		</td>
	</tr>
	<tr class="pt_pay_fld">
		<th class="fc_197">개별 PG결제 허용</th>
		<td class="bo_label"><label><input type="checkbox" name="use_pg" value="1" <?php echo get_checked($mb[use_pg], '1'); ?>> 승인<span>(본사지정)</span></label></td>
		<th class="fc_197">개별 상품판매 허용</th>
		<td class="bo_label"><label><input type="checkbox" name="use_good" value="1" <?php echo get_checked($mb[use_good], '1'); ?>> 승인</b><span>(본사지정)</span></label></td>
	</tr>
	<tr class="pt_pay_fld">
		<th class="fc_197">개별 도메인</th>
		<td colspan="3">
			<span class="sitecode">www.</span><label><input type="text" class="frm_input w150" name="homepage" value="<?php echo $mb['homepage'];?>"></label>
			단독서버인경우만 입력하세요. 예시) naver.com
		</td>
	</tr>	
	<tr>
		<th>메일수신</th>
		<td><?php echo $mb[mailser];?></td>
		<th>SMS수신</th>
		<td><?php echo $mb[smsser];?></td>
	</tr>
	<tr>
		<th>가입일시</th>
		<td><?php echo $mb[reg_time];?></td>
		<th>최후아이피</th>
		<td><?php echo $mb[login_ip];?></td>
	</tr>
	<tr>
		<th>로그인횟수</th>
		<td><?php echo number_format($mb[login_sum]);?> 회</td>
		<th>마지막로그인</th>
		<td><?php echo (!is_null_time($mb[today_login])) ? $mb[today_login] : '';?></td>
	</tr>
	<tr>
		<th>구매횟수</th>
		<td><?php echo number_format(shop_count($index_no));?> 회</td>
		<th>총구매금액</th>
		<td><?php echo number_format(shop_money_total($index_no));?> 원</td>
	</tr>
	<tr>
		<th>메모</th>
		<td colspan="3"><textarea name="memo" class="frm_textbox" rows="3"><?php echo $mb[memo];?></textarea></td>
	</tr>
	</tbody>
	</table>
</div>
<div class="btn_confirm">
	<input type="submit" class="btn_medium" accesskey="s" value="저장">
	<button type="button" class="btn_medium bx-white marl3" onclick="chk_mb_leave('member_delete.php?index_no=<?php echo $index_no;?>');">탈퇴</button>
</div>
</form>

<script>
function fregister_submit(f) {
	f.action = "./pop_member_detail.php";
    return true;
}

function chk_mb_leave(url) {
	answer = confirm('영구 탈퇴처리 하시겠습니까?\n\n한번 삭제된 데이터는 복구 불가능합니다.');
	if(answer==true)
	{	location.href=url;	}
}

$(function() {
    $(".pt_pay_fld").hide();
	$(".mb_adm_fld").hide();
	<?php if(is_partner($mb[id])) { ?>
    $(".pt_pay_fld").show();
    <?php } ?>
	<?php if($mb[grade] == 1) { ?>
    $(".mb_adm_fld").show();
    <?php } ?>
	$("#mb_grade").on("change", function() {
		$(".pt_pay_fld:visible").hide();
		$(".mb_adm_fld:visible").hide();
        var level = $(this).val();
		if(level >= 2 && level <= 6) {
			$(".pt_pay_fld").show();
		} else if(level == 1) {
			$(".mb_adm_fld").show();
		}
    });
});
</script>
