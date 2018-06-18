<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

check_admin_token();

unset($value);
$value['theme'] = $_POST['theme']; //테마스킨
$value['mobile_theme'] = $_POST['mobile_theme']; //모바일테마스킨
update("shop_member",$value,"where id='$member[id]'");

unset($value);
$value['bank_company'] = $_POST['bank_company']; //은행명
$value['bank_number'] = $_POST['bank_number']; //계좌번호
$value['bank_name'] = $_POST['bank_name']; //예금주명
$value['cf_saupja_use'] = $_POST['cf_saupja_use']; //쇼핑몰 사업자노출 여부
$value['shop_name'] = $_POST['shop_name']; //쇼핑몰명
$value['shop_name_us'] = $_POST['shop_name_us']; //쇼핑몰 영문명
$value['company_type'] = $_POST['company_type']; //사업자유형
$value['company_name'] = $_POST['company_name']; //회사명
$value['company_saupja_no'] = $_POST['company_saupja_no']; //사업자등록번호
$value['tongsin_no'] = $_POST['tongsin_no']; //통신판매신고번호
$value['company_tel'] = $_POST['company_tel']; //대표전화
$value['company_fax'] = $_POST['company_fax']; //대표팩스
$value['company_item'] = $_POST['company_item']; //업태
$value['company_service'] = $_POST['company_service']; //종목
$value['company_owner'] = $_POST['company_owner']; //대표자명
$value['info_name'] = $_POST['info_name']; //정보책임자 이름
$value['info_email'] = $_POST['info_email']; //정보책임자 e-mail	
$value['company_zip'] = $_POST['company_zip']; //사업장우편번호
$value['company_addr'] = $_POST['company_addr']; //사업장주소
$value['company_hours'] = $_POST['company_hours']; //CS 상담가능시간
$value['company_lunch'] = $_POST['company_lunch']; //CS 점심시간
$value['company_close'] = $_POST['company_close']; //CS 휴무일
update("shop_partner",$value,"where mb_id='$member[id]'");

goto_url('./page.php?code=partner_info');
?>