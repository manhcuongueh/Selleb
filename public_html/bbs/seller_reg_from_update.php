<?php
include_once("./_common.php");

check_demo();

if(!$member['id']) {
    alert("로그인 후 신청 가능합니다.");
}

if(!$config['shop_reg_yes']) {
	alert('서비스가 일시 중단 되었습니다.');
}

if($_POST["token"] && get_session("ss_token") == $_POST["token"]) {
	// 맞으면 세션을 지워 다시 입력폼을 통해서 들어오도록 한다.
	set_session("ss_token", "");
} else {
	alert("잘못된 접근 입니다.");
	exit;
}

if($_POST['mode'] == 'w') {
	unset($value);
	$value['sup_code']       = code_uniqid();
	$value['mb_id']			 = $member['id'];
	$value['in_item']		 = $_POST['in_item'];
	$value['in_compay']		 = $_POST['in_compay'];
	$value['in_sanumber']	 = $_POST['in_sanumber'];
	$value['in_up']			 = $_POST['in_up'];
	$value['in_upte']		 = $_POST['in_upte'];
	$value['in_name']		 = $_POST['in_name'];
	$value['in_phone']		 = $_POST['in_phone'];
	$value['in_zipcode']	 = $_POST['in_zipcode'];
	$value['in_addr1']		 = $_POST['in_addr1'];
	$value['in_addr2']		 = $_POST['in_addr2'];
	$value['in_addr3']		 = $_POST['in_addr3'];
	$value['in_addr_jibeon'] = $_POST['in_addr_jibeon'];
	$value['in_fax']		 = $_POST['in_fax'];
	$value['in_home']		 = $_POST['in_home'];
	$value['in_dam']		 = $_POST['in_dam'];
	$value['in_jik']		 = $_POST['in_jik'];
	$value['memo']			 = $_POST['memo'];
	$value['n_name']		 = $_POST['n_name'];
	$value['n_bank']		 = $_POST['n_bank'];
	$value['n_bank_num']	 = $_POST['n_bank_num'];
	$value['n_email']		 = $_POST['n_email'];
	$value['n_phone']		 = $_POST['n_phone'];
	$value['wdate']			 = $server_time;
	insert("shop_seller", $value);

	$wr_content = conv_content(conv_unescape_nl(stripslashes($_POST['memo'])), 0);
	$wr_name = get_text($member['name']);
	$subject = '['.$in_compay.'] '.$wr_name.'님께서 입점신청을 하셨습니다.';

	if($member['email']) {
		include_once(TW_INC_PATH."/mail.php");	
		
		ob_start();
		include_once('./seller_reg_from_update_mail.php');
		$content = ob_get_contents();
		ob_end_clean();

		mailer($member['name'], $member['email'], $super['email'], $subject, $content, 1);
	}

	icode_member_send($super_hp, $subject);

	alert('정상적으로 신청 되었습니다.', TW_BBS_URL.'/seller_reg_from.php');
}
?>