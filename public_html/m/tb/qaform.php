<?php
include_once("./_common.php");

if(!$is_member) {
	alert_close("로그인 후 작성 가능합니다.");
}

$tb['title'] = '상품문의 쓰기';
include_once("$tb[root]/head.sub.php");

$gs = sql_fetch(" select * from shop_goods where index_no='$gs_id' ");

if($w == "") {
	$iq_name	 = $member['name'];
	$iq_email	 = $member['email'];
	$iq_hp		 = replace_tel($member['cellphone']);
}
else if($w == "u") {
	$iq = sql_fetch("select * from shop_goods_qa where iq_id='$iq_id'");
	$iq_ty		 = $iq['iq_ty'];
	$iq_name	 = $iq['iq_name'];
	$iq_email	 = $iq['iq_email'];
	$iq_hp		 = $iq['iq_hp'];
	$iq_subject  = $iq['iq_subject'];
	$iq_question = $iq['iq_question'];
	$iq_secret	 = nl2br($iq['iq_secret']);
}

$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

$form_action_url = './qaform_update.php';
include_once($theme_path.'/qaform.skin.php');

include_once("$tb[root]/tail.sub.php");
?>