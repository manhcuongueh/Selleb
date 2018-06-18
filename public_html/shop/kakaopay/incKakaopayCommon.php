<?php
if(!defined('_TUBEWEB_')) exit; // 개별 페이지 접근 불가

//인증,결제 및 웹 경로
$CNSPAY_WEB_SERVER_URL = "https://kmpay.lgcns.com:8443";
$msgName = "/merchant/requestDealApprove.dev";
$CnsPayDealRequestUrl = "https://pg.cnspay.co.kr:443";

if($default['cf_card_test_yn']) {
	$MID = "cnstest25m";
	$merchantEncKey = "10a3189211e1dfc6";
	$merchantHashKey = "10a3189211e1dfc6";
	$cancelPwd = "123456";
    $merchantKey = '33F49GnCMS1mFYlGXisbUDzVf2ATWCl9k3R++d5hDd3Frmuos/XLx8XhXpe+LDYAbpGKZYSwtlyyLOtS/8aD7A==';
} else {
    $MID = trim($default['de_kakaopay_mid']);
    $merchantEncKey = trim($default['de_kakaopay_enckey']);
    $merchantHashKey = trim($default['de_kakaopay_hashkey']);
    $cancelPwd = trim($default['de_kakaopay_cancelpwd']);
    $merchantKey = trim($default['de_kakaopay_key']);
}

//버전
$phpVersion = "PLP-0.1.1.3";

//로그 경로
$LogDir = ROOT_KAKAOPAY.'/log';
?>