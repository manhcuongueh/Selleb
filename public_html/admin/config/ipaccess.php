<?php
if(!defined('_TUBEWEB_')) exit;
?>

<form name="fregform" method="post" onsubmit="return fregform_submit(this);">
<input type="hidden" name="token" value="">

<h2>접속 제한 / 차단 IP 설정</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col width="180px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">현재 접속 IP</th>
		<td><strong><?php echo $_SERVER['REMOTE_ADDR']; ?></strong></td>
	</tr>
	<tr>
		<th scope="row"><label for="sp_possible_ip">접근 가능 IP</label></th>
		<td>
			<textarea name="sp_possible_ip" id="sp_possible_ip" class="frm_textbox wfull" rows="5"><?php echo $config['sp_possible_ip']; ?></textarea>
			<?php echo help('입력된 IP의 컴퓨터만 접근할 수 있음. 123.123.+ 도 입력 가능. (엔터로 구분)'); ?>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="sp_intercept_ip">접근 차단 IP</label></th>
		<td>
			<textarea name="sp_intercept_ip" id="sp_intercept_ip" class="frm_textbox wfull" rows="5"><?php echo $config['sp_intercept_ip']; ?></textarea>
			<?php echo help('입력된 IP의 컴퓨터는 접근할 수 없음. 123.123.+ 도 입력 가능. (엔터로 구분)'); ?>
		</td>
	</tr>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<input type="submit" class="btn_large" accesskey="s" value="저장">
</div>
</form>

<div class="information">
	<h4>도움말</h4>
	<div class="content">
		<div class="desc02">
			<p>ㆍIP등록시 <em>123.+</em> 를 입력하셨다면 첫번째 항목에 해당하는 IP 주소는 접근 및 차단 됩니다. 
			예) <em>123</em>.456.789.225</p>
			<p>ㆍ테스트를 위해 접근 차단 IP 입력란에 최고관리자 IP주소를 입력하시면 절대 안됩니다.</p>
			<p>ㆍ유동적으로 변경되는 IP를 등록시 접속이 제한되실 수 있으니 참고하시기 바랍니다.</p>
		</div>
	 </div>
</div>

<script>
function fregform_submit(f) {
	f.action = "./config/ipaccess_update.php";
    return true;
}
</script>
