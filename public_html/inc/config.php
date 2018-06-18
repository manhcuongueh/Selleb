<?php
/*******************************************************************************
** 분양몰 Ver.2.1.1
*******************************************************************************/
error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING | E_RECOVERABLE_ERROR );

//==============================================================================
// php.ini 의 magic_quotes_gpc 값이 Off 인 경우 addslashes() 적용
// SQL Injection 등으로 부터 보호
// http://kr.php.net/manual/en/function.get-magic-quotes-gpc.php#97783
//------------------------------------------------------------------------------
if(!get_magic_quotes_gpc()) {
    $escape_function = 'addslashes($value)';
    $addslashes_deep = create_function('&$value, $fn', '
        if(is_string($value)) {
            $value = ' . $escape_function . ';
        } else if(is_array($value)) {
            foreach ($value as &$v) $fn($v, $fn);
        }
    ');

    // Escape data
    $addslashes_deep($_POST, $addslashes_deep);
    $addslashes_deep($_GET, $addslashes_deep);
    $addslashes_deep($_COOKIE, $addslashes_deep);
    $addslashes_deep($_REQUEST, $addslashes_deep);
}
//==============================================================================

//==============================================================================
// XSS(Cross Site Scripting) 공격에 의한 데이터 검증 및 차단
//------------------------------------------------------------------------------
function xss_clean($data)
{
    // If its empty there is no point cleaning it :\
    if(empty($data))
        return $data;

    // Recursive loop for arrays
    if(is_array($data))
    {
        foreach($data as $key => $value)
        {
            $data[$key] = xss_clean($value);
        }

        return $data;
    }

    // http://svn.bitflux.ch/repos/public/popoon/trunk/classes/externalinput.php
    // +----------------------------------------------------------------------+
    // | Copyright (c) 2001-2006 Bitflux GmbH                                 |
    // +----------------------------------------------------------------------+
    // | Licensed under the Apache License, Version 2.0 (the "License");      |
    // | you may not use this file except in compliance with the License.     |
    // | You may obtain a copy of the License at                              |
    // | http://www.apache.org/licenses/LICENSE-2.0                           |
    // | Unless required by applicable law or agreed to in writing, software  |
    // | distributed under the License is distributed on an "AS IS" BASIS,    |
    // | WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or      |
    // | implied. See the License for the specific language governing         |
    // | permissions and limitations under the License.                       |
    // +----------------------------------------------------------------------+
    // | Author: Christian Stocker <chregu@bitflux.ch>                        |
    // +----------------------------------------------------------------------+

    // Fix &entity\n;
    $data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
    $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/', '$1;', $data);
    $data = preg_replace('/(&#x*[0-9A-F]+);*/i', '$1;', $data);

    if(function_exists("html_entity_decode"))
    {
        $data = html_entity_decode($data);
    }
    else
    {
        $trans_tbl = get_html_translation_table(HTML_ENTITIES);
        $trans_tbl = array_flip($trans_tbl);
        $data = strtr($data, $trans_tbl);
    }

    // Remove any attribute starting with "on" or xmlns
    $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#i', '$1>', $data);

    // Remove javascript: and vbscript: protocols
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#i', '$1=$2nojavascript...', $data);
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#i', '$1=$2novbscript...', $data);
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#', '$1=$2nomozbinding...', $data);

    // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#i', '$1>', $data);

    // Remove namespaced elements (we do not need them)
    $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

    do
    {
        // Remove really unwanted tags
        $old_data = $data;
        $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
    }
    while ($old_data !== $data);

    return $data;
}

$_GET = xss_clean($_GET);
//====================================================================================================

//====================================================================================================
// extract($_GET); 명령으로 인해 page.php?_POST[var1]=data1&_POST[var2]=data2 와 같은 코드가 _POST 변수로 사용되는 것을 막음
//----------------------------------------------------------------------------------------------------
$ext_arr = array ('PHP_SELF', '_ENV', '_GET', '_POST', '_FILES', '_SERVER', '_COOKIE', '_SESSION', '_REQUEST',
                  'HTTP_ENV_VARS', 'HTTP_GET_VARS', 'HTTP_POST_VARS', 'HTTP_POST_FILES', 'HTTP_SERVER_VARS',
                  'HTTP_COOKIE_VARS', 'HTTP_SESSION_VARS', 'GLOBALS');
$ext_cnt = count($ext_arr);
for($i=0; $i<$ext_cnt; $i++) {
    // GET 으로 선언된 전역변수가 있다면 unset() 시킴
    if(isset($_GET[$ext_arr[$i]])) unset($_GET[$ext_arr[$i]]);
}
//====================================================================================================

// PHP 4.1.0 부터 지원됨
// php.ini 의 register_globals=off 일 경우
@extract($_GET);
@extract($_POST);
@extract($_SERVER);

// $member 에 값을 직접 넘길 수 있음
$config  = array();
$default = array();
$super	 = array();
$member  = array();
$partner = array();
$seller	 = array();
$tb		 = array();

// 이 상수가 정의되지 않으면 각각의 개별 페이지는 별도로 실행될 수 없음
define("_TUBEWEB_", TRUE);

if(PHP_VERSION >= '5.1.0') {
    date_default_timezone_set("Asia/Seoul");
}

$server_time = time();
$time_year	 = date("Y", $server_time);
$time_month  = date("m", $server_time);
$time_day	 = date("d", $server_time);
$time_ym	 = date("Y-m", $server_time);
$time_his	 = date("H:i:s", $server_time);
$time_Yhs	 = date("YmdHis", $server_time);
$time_ymd	 = date("Y-m-d", $server_time);
$time_ymdhis = date("Y-m-d H:i:s", $server_time);

// QUERY_STRING
$qstr = "";
if(isset($set))  {
    $set = mysql_real_escape_string($set);
    $qstr .= '&set=' . urlencode($set);
}

if(isset($sca))  {
    $sca = mysql_real_escape_string($sca);
    $qstr .= '&sca=' . urlencode($sca);
}

if(isset($sfl))  {
    $sfl = mysql_real_escape_string($sfl);
    // 크롬에서만 실행되는 XSS 취약점 보완
    // 코드 $sfl 변수값에서 < > ' " % = ( ) 공백 문자를 없앤다.
    $sfl = preg_replace("/[\<\>\'\"\%\=\(\)\s]/", "", $sfl);
    $qstr .= '&sfl=' . urlencode($sfl); // search field (검색 필드)
}

if(isset($stx))  {
    //$stx = mysql_real_escape_string($stx);
    $qstr .= '&stx=' . urlencode($stx);
}

if(isset($sst))  {
    $sst = mysql_real_escape_string($sst);
    $qstr .= '&sst=' . urlencode($sst); // search sort (검색 정렬 필드)
}

if(isset($sod))  {
    $sod = mysql_real_escape_string($sod);
    $qstr .= '&sod=' . urlencode($sod);
}

if(isset($sop))  {
    $sop = mysql_real_escape_string($sop);
    $qstr .= '&sop=' . urlencode($sop);
}

if(isset($cate))  {
    $cate = mysql_real_escape_string($cate);
    $qstr .= '&cate=' . urlencode($cate);
}

if(isset($spt))  {
    $spt = mysql_real_escape_string($spt);
    $qstr .= '&spt=' . urlencode($spt);
}

if(isset($p_schsh))  {
    $p_schsh = mysql_real_escape_string($p_schsh);
    $qstr .= '&p_schsh=' . urlencode($p_schsh);
}

if(isset($p_dchsh))  {
    $p_dchsh = mysql_real_escape_string($p_dchsh);
    $qstr .= '&p_dchsh=' . urlencode($p_dchsh);
}

if(isset($j_sdate))  {
    $j_sdate = mysql_real_escape_string($j_sdate);
    $qstr .= '&j_sdate=' . urlencode($j_sdate);
}

if(isset($j_ddate))  {
    $j_ddate = mysql_real_escape_string($j_ddate);
    $qstr .= '&j_ddate=' . urlencode($j_ddate);
}

if(isset($l_sdate))  {
    $l_sdate = mysql_real_escape_string($l_sdate);
    $qstr .= '&l_sdate=' . urlencode($l_sdate);
}

if(isset($l_ddate))  {
    $l_ddate = mysql_real_escape_string($l_ddate);
    $qstr .= '&l_ddate=' . urlencode($l_ddate);
}

if(isset($filed))  {
    $filed = mysql_real_escape_string($filed);
    $qstr .= '&filed=' . urlencode($filed);
}

if(isset($orderby))  {
    $orderby = mysql_real_escape_string($orderby);
    $qstr .= '&orderby=' . urlencode($orderby);
}

// URL ENCODING
if(isset($url)) {
    $urlencode = urlencode($url);
}
else {
    // Cross Site Scripting 때문에 수정
    $urlencode = urlencode($_SERVER['REQUEST_URI']);
}

define('TW_PATH', $dir);
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
define('TW_MYPAGE_PATH', TW_PATH.'/mypage');
define('TW_MYPAGE_URL', TW_URL.'/mypage');
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
define('TW_DOMAINS', 'asia|info|name|mobi|com|net|org|biz|tel|xxx|kr|co|so|me|eu|cc|or|pe|ne|re|tv|jp|tw|shop');

// 모바일 인지 결정 $_SERVER['HTTP_USER_AGENT']
define('TW_MOBILE_AGENT', 'phone|samsung|lgtel|mobile|[^A]skt|nokia|blackberry|android|sony');

// 입력값 검사 상수 (숫자를 변경하시면 안됩니다.)
define('TW_ALPHAUPPER',		1); // 영대문자
define('TW_ALPHALOWER',		2); // 영소문자
define('TW_ALPHABETIC',		4); // 영대,소문자
define('TW_NUMERIC',		8); // 숫자
define('TW_HANGUL',		   16); // 한글
define('TW_SPACE',         32); // 공백
define('TW_SPECIAL',       64); // 특수문자

// 퍼미션
define('TW_DIR_PERMISSION',  0707); // 디렉토리 생성시 퍼미션
define('TW_FILE_PERMISSION', 0644); // 파일 생성시 퍼미션

define('TW_SMTP', '127.0.0.1');
define('TW_ICODE_COIN', 100);
define('TW_IP_DISPLAY', '\\1.♡.\\3.\\4');

// 썸네일 png Compress 설정
define('TW_THUMB_PNG_COMPRESS', 5);

// 썸네일 jpg Quality 설정
define('TW_THUMB_JPG_QUALITY', 90);

// 옵션 ID 특수문자 필터링 패턴
define('TW_OPTION_ID_FILTER', '/[\'\"\\\'\\\"]/');

// 암복호화를 위한 키값
define('ENC_FIELD', '9130BDB4470944DFB1B95143928E0D2A');

// PG사별 처리파일 경로
define('ROOT_KCP', $_SERVER["DOCUMENT_ROOT"]."/shop/kcp");
define('ROOT_INICIS', $_SERVER["DOCUMENT_ROOT"]."/shop/INIpay50");
define('ROOT_AGS', $_SERVER["DOCUMENT_ROOT"]."/shop/allthegate");
define('ROOT_KAKAOPAY', $_SERVER["DOCUMENT_ROOT"]."/shop/kakaopay");

// 암호화 함수 지정
// 사이트 운영 중 설정을 변경하면 로그인이 안되는 등의 문제가 발생합니다.
define('TW_STRING_ENCRYPT_FUNCTION', 'sql_password');

include_once(TW_INC_PATH."/thumbnail.lib.php"); // 썸네일 라이브러리
include_once(TW_INC_PATH."/global.lib.php"); // 공통 라이브러리
include_once(TW_INC_PATH."/common.lib.php"); // PC 라이브러리
include_once(TW_INC_PATH."/login-oauth.php"); // SNS 로그인

// 관리자페이지에서 사용
if(isset($_REQUEST['page_rows']) && $_REQUEST['page_rows']) {
	set_session('ss_page_rows', $_REQUEST['page_rows']);
}

// 로그인시에 돌아갈 페이지를 만들어 냄 */
if($_SERVER["QUERY_STRING"])
	$nowurl	= $_SERVER['PHP_SELF'] . "?" . $_SERVER["QUERY_STRING"];
else
	$nowurl	= $_SERVER['PHP_SELF'];

if($_SESSION['ss_mb_id']) {
	$member = get_member($_SESSION['ss_mb_id']);
	$memindex = $member['index_no'];
	$memid	  = $member['id'];
	$memname  = $member['name'];
	$grade    = $member['grade'];

	$partner = sql_fetch("select * from shop_partner where mb_id = '{$member['id']}'");
	$seller = sql_fetch("select * from shop_seller where mb_id = '{$member['id']}'");
	$is_admin = get_admin($member['id']);
}

// 회원, 비회원 구분
if($member['id']) {
	$mb_yes = 1;
	$mb_no = $member['index_no'];
} else {
	$mb_yes = 0;
	$mb_no = get_cookie('ck_guest');
}

// 비회원구매를 위해 쿠키를 1년간 저장
if(!get_cookie("ck_guest"))
	set_cookie("ck_guest", $server_time, 86400 * 365);

$config = sql_fetch("select * from shop_config");
$default = sql_fetch("select * from shop_default");
$super = get_member('admin');
$super_hp = $super['cellphone'];

// DB 최적화
include_once(TW_INC_PATH."/db_table.optimize.php");

if(!is_admin()) {
    // 접근가능 IP
    $sp_possible_ip = trim($config['sp_possible_ip']);
    if($sp_possible_ip) {
        $is_possible_ip = false;
        $pattern = explode("\n", $sp_possible_ip);
        for($i=0; $i<count($pattern); $i++) {
            $pattern[$i] = trim($pattern[$i]);
            if(empty($pattern[$i]))
                continue;

			$pattern[$i] = str_replace(".", "\.", $pattern[$i]);
            $pat = "/^{$pattern[$i]}/";
            $is_possible_ip = preg_match($pat, $_SERVER['REMOTE_ADDR']);
            if($is_possible_ip)
                break;
        }
        if(!$is_possible_ip)
            die ("접근이 가능하지 않습니다.");
    }

    // 접근차단 IP
    $is_intercept_ip = false;
    $pattern = explode("\n", trim($config['sp_intercept_ip']));
    for($i=0; $i<count($pattern); $i++) {
        $pattern[$i] = trim($pattern[$i]);
        if(empty($pattern[$i]))
            continue;

        $pattern[$i] = str_replace(".", "\.", $pattern[$i]);
        $pat = "/^{$pattern[$i]}/";
        $is_intercept_ip = preg_match($pat, $_SERVER['REMOTE_ADDR']);
        if($is_intercept_ip)
            die ("접근 불가합니다.");
    }
}

// admin으로 카테고리생성시 테이블 삭제
if(table_exists("shop_cate_admin")) {
	sql_query(" DROP TABLE `shop_cate_admin` ", FALSE);
}

$p_use_good = false;
$p_use_pg   = false;
$p_use_cate = false;

// 개별 상품판매
if($config['p_use_good'] == 2 || ($config['p_use_good'] == 3 && $member['use_good']))
	$p_use_good = true;

// 개별 결제연동
if($config['p_use_pg'] == 2 || ($config['p_use_pg'] == 3 && $member['use_pg']))
	$p_use_pg = true;

// 개별 카테고리
if($config['p_use_cate'] == 2)
	$p_use_cate = true;

// 기본값 본사 카테고리 테이블명
$tb['category_table'] = 'shop_cate';

// 게시판에서 사용되는 변수들
$gw_search_value = array("subject","writer_s","memo");
$gw_search_text = array("제목","작성자","내용");

// 상품 정렬탭
$gw_sort = array(
	array("readcount",  "desc", "인기상품순"),
	array("account", "asc", "낮은가격순"),
	array("account", "desc", "높은가격순"),
	array("m_count", "desc", "후기많은순"),
	array("index_no", "desc", "최근등록순")
);

$gw_auth = array(
	'회원관리',
	'가맹점관리',
	'공급사관리',
	'카테고리관리',
	'상품관리',
	'주문관리',
	'통계분석',
	'고객지원',
	'디자인관리',
	'환경설정'
);

$ar_method = array(
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

$ar_delivery = array(
	"1"=>"택배발송",
	"2"=>"퀵서비스",
	"3"=>"직접배달",
	"4"=>"방문수령"
);

$ar_state = array(
	"0"=>"승인",
	"1"=>"대기",
	"2"=>"보류"
);

$ar_dan = array(
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

$ar_record = array(
	"shop"=>"판매수수료",
	"member"=>"분양수수료",
	"p_month"=>"관리비적립",
	"login"=>"광고수수료",
	"admin"=>"관리자적립 ",
	"cancel"=>"수수료차감"
);

$ar_isopen = array(
	"1"=>"진열",
	"2"=>"품절",
	"3"=>"단종",
	"4"=>"중지"
);

$ar_set = array(
	"today"=>"오늘 접수된주문",
	"1"=>"1단계 주문확인",
	"2"=>"2단계 입금확인",
	"3"=>"3단계 배송대기",
	"4"=>"4단계 배송중",
	"5"=>"5단계 배송완료",
	"6"=>"상품 반품목록",
	"7"=>"입금후 주문취소",
	"8"=>"입금전 주문취소",
	"10"=>"상품 교환목록",
	"whole"=>"전체 주문처리현황"
);

$ar_coupon = array(
	"0"=>"발행 날짜 지정",
	"1"=>"발행 시간/요일 지정",
	"2"=>"성별구분으로 발급",
	"3"=>"회원 생일자 발급",
	"4"=>"연령 구분으로 발급",
	"5"=>"신규회원가입 발급"
);

$ar_use_part = array(
	"0"=>"전체상품에 사용가능",
	"1"=>"일부 상품만 사용가능",
	"2"=>"일부 카테고리만 사용가능",
	"3"=>"일부 상품에서는 사용불가",
	"4"=>"일부 카테고리에서는 사용불가"
);

//==============================================================================
// Mobile 모바일 설정
// 쿠키에 저장된 값이 모바일이라면 브라우저 상관없이 모바일로 실행
// 그렇지 않다면 브라우저의 HTTP_USER_AGENT 에 따라 모바일 결정
//------------------------------------------------------------------------------
$is_mobile = false;
if(IS_MOBILE) {
    if($_REQUEST['device']=='pc')
        $is_mobile = false;
    else if(isset($_SESSION['ss_is_mobile']))
        $is_mobile = $_SESSION['ss_is_mobile'];
    else if(is_mobile())
        $is_mobile = true;
}

$_SESSION['ss_is_mobile'] = $is_mobile;
define('IS_MOBILE', $is_mobile);
if(IS_MOBILE && $config['mo_shop_yn']) {

	$mobile_url = '/m/';

	if(strstr($_SERVER['PHP_SELF'],'content.php') && isset($_GET['co_id']))
		$mobile_url .= 'tb/content.php?co_id='.$_GET['co_id'];
	if(strstr($_SERVER['PHP_SELF'],'list.php') && isset($_GET['cate']))
		$mobile_url .= 'tb/list.php?ca_id='.$_GET['cate'];
	if(strstr($_SERVER['PHP_SELF'],'view.php') && isset($_GET['index_no']))
		$mobile_url .= 'tb/view.php?gs_id='.$_GET['index_no'];
	if(strstr($_SERVER['PHP_SELF'],'listtype.php') && isset($_GET['type']))
		$mobile_url .= 'tb/listtype.php?type='.$_GET['type'];
	if(strstr($_SERVER['PHP_SELF'],'brand.php'))
		$mobile_url .= 'tb/brand.php';
	if(strstr($_SERVER['PHP_SELF'],'brandlist.php') && isset($_GET['br_id']))
		$mobile_url .= 'tb/brandlist.php?br_id='.$_GET['br_id'];
	if(strstr($_SERVER['PHP_SELF'],'plan.php'))
		$mobile_url .= 'tb/plan.php';
	if(strstr($_SERVER['PHP_SELF'],'planlist.php') && isset($_GET['pl_no']))
		$mobile_url .= 'tb/planlist.php?pl_no='.$_GET['pl_no'];
	if(strstr($_SERVER['PHP_SELF'],'timesale.php'))
		$mobile_url .= 'tb/timesale.php';
	if(strstr($_SERVER['PHP_SELF'],'faq.php'))
		$mobile_url .= 'tb/faq.php';
	if(strstr($_SERVER['PHP_SELF'],'qna_list.php'))
		$mobile_url .= 'tb/qna_list.php';
	if(strstr($_SERVER['PHP_SELF'],'review.php'))
		$mobile_url .= 'tb/review.php';
	if(strstr($_SERVER['PHP_SELF'],'list.php') && isset($_GET['boardid']))
		$mobile_url .= 'tb/board_list.php?boardid='.$_GET['boardid'];
	if(strstr($_SERVER['PHP_SELF'],'read.php') && isset($_GET['boardid']))
		$mobile_url .= 'tb/board_read.php?boardid='.$_GET['boardid'].'&index_no='.$_GET['index_no'];

	// 아래의 경로는 모바일환경으로 이동하지 않는다.
	$desktop_yes = array();
	$desktop_yes[] = "/inc/sns_send.php";
	$desktop_yes[] = "/plugin/login-oauth/login_with_facebook.php";
	$desktop_yes[] = "/plugin/login-oauth/login_with_google.php";
	$desktop_yes[] = "/plugin/login-oauth/login_with_kakao.php";
	$desktop_yes[] = "/plugin/login-oauth/login_with_naver.php";
	$desktop_yes[] = "/plugin/login-oauth/login_with_twitter.php";
	$desktop_yes[] = "/shop/naverpay/naverpay_item.php";
	$desktop_yes[] = "/shop/naverpay/naverpay_order.php";
	$desktop_yes[] = "/shop/naverpay/naverpay_wish.php";
	if(!in_array($_SERVER['PHP_SELF'], $desktop_yes)) {
		goto_url($mobile_url);
	}
}
//==============================================================================
?>