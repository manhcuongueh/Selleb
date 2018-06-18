<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

check_admin_token();

unset($value);
$value['in_item'] = $_POST['in_item']; //제공상품
$value['in_compay'] = $_POST['in_compay']; //업체(법인)명
$value['in_sanumber'] = $_POST['in_sanumber'];//사업자등록번호
$value['in_phone'] = $_POST['in_phone']; //전화번호
$value['in_fax'] = $_POST['in_fax']; //팩스번호
$value['in_zipcode'] = $_POST['in_zipcode'];
$value['in_addr1'] = $_POST['in_addr1'];
$value['in_addr2'] = $_POST['in_addr2'];
$value['in_addr3'] = $_POST['in_addr3'];
$value['in_addr_jibeon'] = $_POST['in_addr_jibeon'];
$value['in_up'] = $_POST['in_up']; //종목
$value['in_upte'] = $_POST['in_upte']; //업태
$value['in_name'] = $_POST['in_name']; //대표자명
$value['in_home'] = $_POST['in_home']; //홈페이지
$value['in_dam'] = $_POST['in_dam']; //담당자명
$value['n_phone'] = $_POST['n_phone']; //담당자 연락처
$value['n_email'] = $_POST['n_email']; //담당자 이메일
$value['n_name'] = $_POST['n_name']; //결제계좌 명의
$value['n_bank'] = $_POST['n_bank']; //결제은행
$value['n_bank_num'] = $_POST['n_bank_num']; //입금계좌번호
update("shop_seller",$value,"where mb_id='$member[id]'");

goto_url('./page.php?code=seller_info');
?>