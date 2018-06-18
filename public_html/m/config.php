<?php
// 이 상수가 정의되지 않으면 각각의 개별 페이지는 별도로 실행될 수 없음
define("_TUBEWEB_", TRUE);

if (function_exists("date_default_timezone_set"))
    date_default_timezone_set("Asia/Seoul");

// 디렉토리
$tb['bbs']			 = "tb";
$tb['bbs_root']		 = $tb['root'] . "/" . $tb['bbs'];
$tb['img']			 = "img";
$tb['img_root']		 = $tb['root'] . "/" . $tb['img'];

$tb['server_time']	 = time();
$tb['time_year']	 = date("Y", $tb['server_time']);
$tb['time_month']	 = date("m", $tb['server_time']);
$tb['time_day']		 = date("d", $tb['server_time']);
$tb['time_ym']		 = date("Y-m", $tb['server_time']);
$tb['time_ymd']		 = date("Y-m-d", $tb['server_time']);
$tb['time_his']		 = date("H:i:s", $tb['server_time']);
$tb['time_Yhs']		 = date("YmdHis", $tb['server_time']);
$tb['time_ymdhis']	 = date("Y-m-d H:i:s", $tb['server_time']);

$server_time		 = $tb['server_time'];
$time_year			 = $tb['time_year'];
$time_month			 = $tb['time_month'];
$time_day			 = $tb['time_day'];
$time_ym			 = $tb['time_ym'];
$time_his			 = $tb['time_his'];
$time_Yhs			 = $tb['time_Yhs'];
$time_ymd			 = $tb['time_ymd'];
$time_ymdhis		 = $tb['time_ymdhis'];

$tb['charset']		 = "utf-8";
$tb['url']			 = "";
$tb['cookie_domain'] = "";
$tb['category_table'] = 'shop_cate'; // 분류 테이블

// PG사별 처리파일 경로
define('M_HTTP', "http://{$_SERVER['HTTP_HOST']}/m/".$tb['bbs']);
define('M_PATH', $_SERVER["DOCUMENT_ROOT"]."/m/".$tb['bbs']);
define('M_PATH_KCP', M_PATH."/kcp");
define('M_PATH_INI', M_PATH."/inipay");
define('M_PATH_AGS', M_PATH."/ags");
define('M_PATH_KAKAOPAY', M_PATH."/kakaopay");

define('TW_PATH', $tb['mall']);
define('TW_URL', 'http://'.$_SERVER['HTTP_HOST']);
define('TW_ADMIN_PATH', TW_PATH.'/admin');
define('TW_ADMIN_URL', TW_URL.'/admin');
define('TW_CSS_PATH', TW_PATH.'/css');
define('TW_CSS_URL', TW_URL.'/css');
define('TW_JS_PATH', TW_PATH.'/js');
define('TW_JS_URL', TW_URL.'/js');
define('TW_BBS_PATH', TW_PATH.'/bbs');
define('TW_BBS_URL', TW_URL.'/bbs');
define('TW_DATA_PATH', TW_PATH.'/data');
define('TW_DATA_URL', TW_URL.'/data');
define('TW_SHOP_PATH', TW_PATH.'/shop');
define('TW_SHOP_URL', TW_URL.'/shop');
define('TW_IMG_PATH', TW_PATH.'/img');
define('TW_IMG_URL', TW_URL.'/img');
define('TW_PLUGIN_PATH', TW_PATH.'/plugin');
define('TW_PLUGIN_URL', TW_URL.'/plugin');
define('TW_EDITOR_PATH', TW_PLUGIN_PATH.'/editor');
define('TW_EDITOR_URL', TW_PLUGIN_URL.'/editor');
define('TW_THEME_PATH', TW_PATH.'/theme');
define('TW_THEME_URL', TW_URL.'/theme');
define('TW_MOBILE_THEME_PATH', TW_PATH.'/m/theme');
define('TW_MOBILE_THEME_URL', TW_URL.'/m/theme');
define('TW_INC_PATH', TW_PATH.'/inc');
define('TW_INC_URL', TW_URL.'/inc');
define('TW_LGXPAY_PATH', TW_PATH.'/lgxpay');

// 도메인의 종류
define('TW_DOMAINS', 'asia|info|name|mobi|com|net|org|biz|tel|xxx|kr|co|so|me|eu|cc|or|pe|ne|re|tv|jp|tw');

// 모바일 인지 결정 $_SERVER['HTTP_USER_AGENT']
define('TW_MOBILE_AGENT', 'phone|samsung|lgtel|mobile|[^A]skt|nokia|blackberry|android|sony');

define('TW_SMTP', '127.0.0.1');
define('TW_ICODE_COIN', 100);

// 퍼미션
define('TW_DIR_PERMISSION',  0755); // 디렉토리 생성시 퍼미션
define('TW_FILE_PERMISSION', 0644); // 파일 생성시 퍼미션

// 썸네일 png Compress 설정
define('TW_THUMB_PNG_COMPRESS', 5);

// 썸네일 jpg Quality 설정
define('TW_THUMB_JPG_QUALITY', 90);

// 옵션 ID 특수문자 필터링 패턴
define('TW_OPTION_ID_FILTER', '/[\'\"\\\'\\\"]/');

// 암복호화를 위한 키값
define('ENC_FIELD', '9130BDB4470944DFB1B95143928E0D2A');

// 암호화 함수 지정
// 사이트 운영 중 설정을 변경하면 로그인이 안되는 등의 문제가 발생합니다.
define('TW_STRING_ENCRYPT_FUNCTION', 'sql_password');

// 상품 정렬탭
$gw_sort = array(
	array("readcount",  "desc", "인기상품순"),
	array("account", "asc", "낮은가격순"),
	array("account", "desc", "높은가격순"),
	array("m_count", "desc", "구매후기순"),
);

$arr_mhd = array(
	"K"=>"카카오페이",
	"C"=>"신용카드",
	"B"=>"무통장",
	"R"=>"계좌이체",
	"H"=>"핸드폰",
	"S"=>"가상계좌",
	"P"=>"포인트",
	"ER"=>"에스크로 계좌이체",
	"ES"=>"에스크로 가상계좌"
);

$arr_del = array(
	"1"=>"택배",
	"2"=>"퀵서비스",
	"3"=>"직접배달",
	"4"=>"방문수령"
);

$arr_sco = array(
	"1"=>"매우불만족",
	"2"=>"불만족",
	"3"=>"보통",
	"4"=>"만족",
	"5"=>"매우만족"
);

$arr_dan = array(
	"1"=>"주문접수",
	"2"=>"입금확인",
	"3"=>"배송준비중",
	"4"=>"배송중",
	"5"=>"배송완료",
	"6"=>"반품완료",
	"7"=>"취소완료(입금후)",
	"8"=>"취소완료(입금전)",
	"9"=>"취소접수",
	"10"=>"교환완료"
);

$arr_use_part = array(
	"0"=>"전체상품에 사용가능",
	"1"=>"일부 상품만 사용가능",
	"2"=>"일부 카테고리만 사용가능",
	"3"=>"일부 상품에서는 사용불가",
	"4"=>"일부 카테고리에서는 사용불가"
);
?>