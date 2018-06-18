<?php
include_once("./_common.php");

check_demo();

if(!$config['partner_reg_yes']) {
	alert('서비스가 일시 중단 되었습니다.');
}

if(!$member['id'])
    alert("로그인 후 신청 가능합니다.");

if(is_admin())
	alert('관리자는 신청을 하실 수 없습니다.');

if($partner['mb_id'])
    alert('중복된 신청은 하실 수 없습니다.');

if($_POST["token"] && get_session("ss_token") == $_POST["token"]) {
	// 맞으면 세션을 지워 다시 입력폼을 통해서 들어오도록 한다.
	set_session("ss_token", "");
} else {
	alert("잘못된 접근 입니다.");
	exit;
}

unset($value);
$value['mb_id']			= $member['id'];
$value['bank_name']		= $_POST['bank_name'];
$value['bank_number']	= $_POST['bank_number'];
$value['bank_company']	= $_POST['bank_company'];
$value['memo']			= $_POST['memo'];
$value['cf_1']			= $_POST['cf_1'];
$value['bank_money']	= conv_number($_POST['bank_money']);
$value['bank_name2']	= $_POST['bank_name2'];
$value['bank_type']		= $_POST['bank_type'];
$value['bank_acc']		= $_POST['bank_acc'];
$value['wdate']			= $server_time;
insert("shop_partner", $value);

$wr_content = conv_content(conv_unescape_nl(stripslashes($_POST['memo'])), 0);
$wr_name = get_text($member['name']);
$subject = $wr_name.'님께서 분양신청을 하셨습니다.';

if($member['email']) {
	include_once(TW_INC_PATH."/mail.php");

	ob_start();
	include_once('./partner_reg_update_mail.php');
	$content = ob_get_contents();
	ob_end_clean();

	mailer($member['name'], $member['email'], $super['email'], $subject, $content, 1);
}

icode_member_send($super_hp, $subject);

alert('정상적으로 신청 되었습니다.', TW_BBS_URL.'/partner_reg.php');
?>