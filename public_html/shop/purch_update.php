<?php
include_once("./_common.php");
include_once(TW_INC_PATH."/mail.php");

check_demo();

if($_POST["token"] && get_session("ss_token") == $_POST["token"]) {
	// 맞으면 세션을 지워 다시 입력폼을 통해서 들어오도록 한다.
	set_session("ss_token", "");
} else {
	alert("잘못된 접근 입니다.");
	exit;
}

$wr_gname = trim($_POST['wr_gname']);
$wr_gcode = trim($_POST['wr_gcode']);
$wr_guser = trim($_POST['wr_guser']);

## 메일발송
// 제목
$subject = "[대량구매문의] ".$_POST['wr_subject'];

ob_start();
include_once('./purch_update_mail.php');
$content = ob_get_contents();
ob_end_clean();

mailer($wr_name, $wr_semail, $wr_remail, $subject, $content, 1);

if($wr_guser) { // 가맹점상품인가?
	$mb = get_member($mb_id);
	$super_hp = $mb['cellphone'];
}

icode_member_send($super_hp, $subject);

alert_close('정상적으로 메일이 발송 되었습니다');
?>