<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

check_admin_token();

unset($value);
$value['join_point'] = conv_number($_POST['join_point']);  //회원가입 포인트
$value['reco_point'] = conv_number($_POST['reco_point']);  //추천인 포인트
$value['sp_use_hp'] = $_POST['sp_use_hp']; //핸드폰 입력
$value['sp_rep_hp'] = $_POST['sp_rep_hp']; //핸드폰 필수입력
$value['sp_use_tel'] = $_POST['sp_use_tel']; //전화번호 입력
$value['sp_req_tel'] = $_POST['sp_req_tel']; //전화번호 필수입력
$value['sp_use_addr'] = $_POST['sp_use_addr']; //주소 입력
$value['sp_req_addr'] = $_POST['sp_req_addr']; //주소 필수입력
$value['sp_use_email'] = $_POST['sp_use_email']; //이메일 입력
$value['sp_req_email'] = $_POST['sp_req_email']; //이메일 필수입력
$value['sp_prohibit_id'] = $_POST['sp_prohibit_id']; //아이디 금지단어
$value['sp_prohibit_email'] = $_POST['sp_prohibit_email']; //입력 금지 메일
$value['sp_provision'] = $_POST['sp_provision']; // 회원가입약관
$value['sp_private'] = $_POST['sp_private']; // 개인정보 수집 및 이용
$value['sp_policy'] = $_POST['sp_policy']; // 개인정보처리방침
update("shop_config", $value);

goto_url('../config.php?code=register');
?>