<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

check_admin_token();

unset($value);
$value['cf_bank_yn']			= $_POST['cf_bank_yn']; // 무통장입금
$value['cf_card_yn']			= $_POST['cf_card_yn']; // 신용카드
$value['cf_iche_yn']			= $_POST['cf_iche_yn']; // 계좌이체
$value['cf_hp_yn']				= $_POST['cf_hp_yn']; // 휴대폰
$value['cf_vbank_yn']			= $_POST['cf_vbank_yn']; // 가상계좌
$value['cf_card_test_yn']		= $_POST['cf_card_test_yn']; // 결제시스템 방식
$value['cf_card_pg']			= $_POST['cf_card_pg']; // 결제대행사
$value['cf_nm_pg']				= $_POST['cf_nm_pg']; // 상점명
$value['cf_escrow_yn']			= $_POST['cf_escrow_yn']; // Escrow 사용여부
$value['cf_tax_flag_use']		= $_POST['cf_tax_flag_use']; // 복합과세 결제 사용여부
// KG이니시스 (INIpay V5.0)
$value['cf_inicis_id']			= $_POST['cf_inicis_id']; // 이니시스 PG ID
$value['cf_inicis_quota']		= $_POST['cf_inicis_quota']; // 일반 할부기간
$value['cf_inicis_tax_yn']		= $_POST['cf_inicis_tax_yn']; // 현금 영수증 발급
$value['cf_inicis_noint_yn']	= $_POST['cf_inicis_noint_yn']; // 무이자 사용 여부
$value['cf_inicis_noint_mt']	= $_POST['cf_inicis_noint_mt']; // 무이자 할부 기간	
$value['cf_inicis_hp_unit']		= $_POST['cf_inicis_hp_unit']; // 휴대폰 결제 설정
$value['cf_inicis_skin']		= $_POST['cf_inicis_skin']; // 결제창 스킨	
$value['cf_inicis_escrow_id']	= $_POST['cf_inicis_escrow_id']; // Escrow ID	
// KCP (ESCROW AX-HUB V6) 
$value['cf_kcp_id']				= $_POST['cf_kcp_id']; // KCP PG ID
$value['cf_kcp_key']			= $_POST['cf_kcp_key']; // KCP KEY
$value['cf_kcp_tax_yn']			= $_POST['cf_kcp_tax_yn']; // 현금 영수증 발급
$value['cf_kcp_noint_yn']		= $_POST['cf_kcp_noint_yn']; // 무이자 사용 여부	
$value['cf_kcp_noint_mt']		= $_POST['cf_kcp_noint_mt']; // 무이자 할부 기간
$value['cf_kcp_quota']			= $_POST['cf_kcp_quota']; // 최대 할부 개월
// 올더게이트 (AGSPay V4.0 for PHP)
$value['cf_ags_id']				= $_POST['cf_ags_id']; // 올더게이트 PG ID
$value['cf_ags_tax_yn']			= $_POST['cf_ags_tax_yn']; // 현금 영수증 
$value['cf_ags_quota']			= $_POST['cf_ags_quota']; // 일반 할부기간
$value['cf_ags_noint_yn']		= $_POST['cf_ags_noint_yn']; // 무이자 사용 여부
$value['cf_ags_noint_mt']		= $_POST['cf_ags_noint_mt']; // 무이자 할부 기간	
$value['cf_ags_hp_id']			= $_POST['cf_ags_hp_id']; // CP아이디 (휴대폰)
$value['cf_ags_hp_pwd']			= $_POST['cf_ags_hp_pwd']; // CP비밀번호 (휴대폰)
$value['cf_ags_hp_subid']		= $_POST['cf_ags_hp_subid']; // SUB-CP아이디 (휴대폰)
$value['cf_ags_hp_code']		= $_POST['cf_ags_hp_code']; // 상품코드 (휴대폰)
$value['cf_ags_hp_unit']		= $_POST['cf_ags_hp_unit']; // 상품구분
$value['cf_bank_account']		= $_POST['cf_bank_account']; // 상점 입금계좌등록
$value['cf_banking']			= $_POST['cf_banking']; // 인터넷뱅킹주소
update("shop_default", $value);

goto_url('../config.php?code=pg');
?>