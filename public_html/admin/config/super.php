<?php
if(!defined('_TUBEWEB_')) exit;
?>

<form name="fregform" method="post" onsubmit="return fregform_submit(this)">
<input type="hidden" name="token" value="">

<h2>상점 관리에 사용될 비밀번호</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col width="180px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th>관리자 비밀번호</th>
		<td>
			<input type="text" name="passwd" class="frm_input w200">
			<p class="fc_197 mart5">비밀번호는 되도록 영,숫자를 같이 사용하시는 것이 좋습니다.</p>
			<p class="fc_197 mart2">비밀번호는 상점 관리에 매우 중요하므로 상점 관리자외 정보유출을 주의하시고 정기적으로 비밀번호를 변경하세요! </p>
		</td>
	</tr>
	</tbody>
	</table>
</div>

<h2>상점 관리에 사용될 필수정보</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col width="180px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th>회원명</th>
		<td><input type="text" name='name' required itemname='회원명' value="<?php echo $super[name]; ?>" class="frm_input w200"></td>
	</tr>
	<tr>
		<th>이메일주소</th>
		<td>
			<input type="text" size="30" name=email required email itemname='이메일' value="<?php echo $super[email]; ?>" class="frm_input w200">
			<span class="fc_197 marl5">회원 메일발송시 사용되므로 실제 사용중인 메일주소를 입력하세요!</span>
		</td>
	</tr>
	<tr>
		<th>핸드폰</th>
		<td><input type="text" name='cellphone' required itemname='핸드폰' value="<?php echo $super[cellphone]; ?>" class="frm_input w200"></td>
	</tr>
	<tr>
		<th>주소</th>
		<td>
			<p>
				<input class="frm_input w100" type="text" name="zip" value="<?php echo $super[zip]; ?>" numeric itemname="우편번호" maxlength="5">
				<a href="javascript:win_zip('fregform', 'zip', 'addr1', 'addr2', 'addr3', 'addr_jibeon');" class="btn_small grey">우편번호</a>
			</p>
			<p class="mart5"><input class="frm_input" type="text" name="addr1" value="<?php echo $super[addr1]; ?>" itemname="주소" size="60"></p>
			<p class="mart5"><input class="frm_input" type="text" name="addr2" value="<?php echo $super[addr2]; ?>" itemname="상세주소" size="60"> ※ 상세주소</p>
			<p class="mart5"><input class="frm_input" type="text" name="addr3" value="<?php echo $super[addr3]; ?>" itemname="참고항목" size="60"> ※ 참고항목
			<input type="hidden" name="addr_jibeon" value="<?php echo $super[addr_jibeon]; ?>"></p>
		</td>
	</tr>
	<tr>
		<th>이메일 수신</th>
		<td>
			<select name="mailser">
				<option value="Y">수신함</option>
				<option value="N">수신안함</option>
				<?php echo option_selected('Y', $super[mailser], '수신함'); ?>
				<?php echo option_selected('N', $super[mailser], '수신안함'); ?>
			</select>
		</td>
	</tr>
	<tr>
		<th>SMS 수신</th>
		<td>
			<select name="smsser">
				<?php echo option_selected('Y', $super[smsser], '수신함'); ?>
				<?php echo option_selected('N', $super[smsser], '수신안함'); ?>
			</select>
		</td>
	</tr>
	<tr>
		<th>적립포인트</th>
		<td>
			<b class="fs14"><?php echo number_format($super[point]); ?></b> Point
			<a href="./member/mem_point_req.php?index_no=<?php echo $super[index_no]; ?>" onclick="openwindow(this,'pop_point_req','450','450','no');return false" class="btn_small grey marl10">강제적립</a>
		</td>
	</tr>
	<tr>
		<th>최후아이피</th>
		<td><?php echo $super[login_ip]; ?></td>
	</tr>
	<tr>
		<th>로그인횟수</th>
		<td><?php echo number_format($mb[login_sum]); ?> 회</td>
	</tr>
	<tr>
		<th>마지막로그인</th>
		<td><?php echo (!is_null_time($super[today_login])) ? $super[today_login] : ''; ?></td>
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
	f.action = "./config/super_update.php";
    return true;
}
</script>
