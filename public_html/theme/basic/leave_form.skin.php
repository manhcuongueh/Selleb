<?php
if(!defined('_TUBEWEB_')) exit;
?>

<form name="fleaveform" method="post" action="<?php echo $form_action_url; ?>" onsubmit="return fleaveform_submit(this);" autocomplete="off">
<p class="tit_navi">홈 <i class="ionicons ion-ios-arrow-right"></i> 마이페이지 <i class="ionicons ion-ios-arrow-right"></i> 회원정보수정 <i class="ionicons ion-ios-arrow-right"></i> 회원탈퇴</p>
<h2 class="stit">회원탈퇴</h2>
<div class="tbl_frm01">
	<table class="wfull">
	<colgroup>
		<col width="18%">
		<col width="82%">
	</colgroup>
	<tbody>
	<tr>
		<th>고객명(ID)</th>
		<td><b><?php echo $member['name']; ?></b> (<?php echo $member['id']; ?>)</td>
	</tr>
	<tr>
		<th>보유 적립금</th>
		<td><b class="fs14"><?php echo number_format($member['point']); ?>P</b> <span class='fc_red marl10'>※ 탈퇴이후 적립금은 모두 소멸됩니다.</span></td>
	</tr>
	<tr>
		<th>이메일</th>
		<td><?php echo $member['email']; ?></td>
	</tr>
	<tr>
		<th>휴대전화</th>
		<td><?php echo replace_tel($member['cellphone']); ?></td>
	</tr>
	</tbody>
	</table>
</div>

<div class="leave_box">
	<p class="s_stit marb10">탈퇴하시기 전 반드시 필독 후 진행하시기 바랍니다!</p>
	<p class="line_box">
		1. 회원탈퇴를 하시면 해당 아이디는 관리자 승인후 탈퇴처리가 되며, 동일 아이디로는 영구적으로 재가입이 불가능 합니다.<br>
		2. 회원탈퇴를 하시면 적립금은 물론 모든 정보가 삭제됨을 알려드립니다.
	</p>
	<p class="s_stit mart20 fc_197">탈퇴 이유에 대해 고객님의 소중한 의견 남겨주시면 보다 나은 서비스를 위해 노력하겠습니다.</p>
	<ul class="mart12">
		<li>
			<input type="radio" name="out" id="out1" value="다른 ID로 변경">
			<label for="out1">다른 ID로 변경</label>
		</li>
		<li>
			<input type="radio" name="out" id="out2" value="회원가입의 혜택이 적음">
			<label for="out2">회원가입의 혜택이 적음</label>
		</li>
		<li>
			<input type="radio" name="out" id="out3" value="개인정보(통신 및 신용정보)의 노출 우려">
			<label for="out3">개인정보(통신 및 신용정보)의 노출 우려</label>
		</li>
		<li>
			<input type="radio" name="out" id="out4" value="시스템장애 (속도저조,잦은에러등)">
			<label for="out4">시스템장애 (속도저조,잦은에러등)</label>
		</li>
		<li>
			<input type="radio" name="out" id="out5" value="서비스의 불만 (늦은배송, 가격불만족, 복잡한 절차등)">
			<label for="out5">서비스의 불만 (늦은배송, 가격불만족, 복잡한 절차등)</label>
		</li>
		<li>
			<input type="radio" name="out" id="out6" value="장시간의부재">
			<label for="out6">장시간의부재</label>
		</li>
		<li>
			<input type="radio" name="out" id="out7" value="기타" onclick="showDiv('other');">
			<label for="out7">기타</label> <input type="text" class="ed marl10" size="60" name="other" style="visibility:hidden">
		</li>
	</ul>
</div>
<div class="tac mart20">
	<input type="submit" value="확인" class="btn_medium">
	<a href="javascript:history.go(-1);" class="btn_medium bx-white marl3">취소</a>
</div>
</form>

<script>
function fleaveform_submit(f) {
	if(confirm("탈퇴신청 하시겠습니까?") == false)
		return false;

    return true;
}

function showDiv( id ) {
    document.all.other.style.visibility = 'hidden';
    document.all.other.value = '';
    document.all[ id ].style.visibility = 'visible';
    document.all[ id ].focus();
}
</script>
