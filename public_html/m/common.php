<?php
/*******************************************************************************
** 공통 변수, 상수, 코드
*******************************************************************************/
error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING | E_RECOVERABLE_ERROR );

// 보안설정이나 프레임이 달라도 쿠키가 통하도록 설정
header('P3P: CP="ALL CURa ADMa DEVa TAIa OUR BUS IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE LOC OTC"');

if(!defined('TW_SET_TIME_LIMIT')) define('TW_SET_TIME_LIMIT', 0);
@set_time_limit(TW_SET_TIME_LIMIT);

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

if($_GET['tb_root'] || $_POST['tb_root'] || $_COOKIE['tb_root']) {
    unset($_GET['tb_root']);
    unset($_POST['tb_root']);
    unset($_COOKIE['tb_root']);
    unset($tb_root);
}

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
$member  = array();
$board   = array();
$tb      = array();

// index.php 가 있는곳의 상대경로
// php 인젝션 ( 임의로 변수조작으로 인한 리모트공격) 취약점에 대비한 코드
if(!$tb_root || preg_match("/:\/\//", $tb_root))
    die("<meta http-equiv='content-type' content='text/html; charset=utf-8'><script type='text/javascript'> alert('잘못된 방법으로 변수가 정의되었습니다.'); </script>");

$tb['root'] = $tb_root;
$tb['mall'] = $_SERVER['DOCUMENT_ROOT'];

// 경로의 오류를 없애기 위해 $tb_root 변수는 해제
unset($tb_root);

include_once("$tb[root]/config.php");			 // 설정 파일
include_once("$tb[mall]/inc/global.lib.php");	 // 공통 라이브러리
include_once("$tb[root]/lib/common.lib.php");	 // 모바일 라이브러리
include_once("$tb[mall]/inc/thumbnail.lib.php"); // 썸네일 라이브러리
include_once("$tb[mall]/inc/connect.php");		 // DB 설정 파일
include_once("$tb[root]/lib/session.lib.php");	 // SESSION 설정

// config.php 가 있는곳의 웹경로
if(!$tb['url'])
{
    $tb['url'] = 'http://' . $_SERVER['HTTP_HOST'];
    $dir = dirname($_SERVER["PHP_SELF"]);
    if(!file_exists("config.php"))
        $dir = dirname($dir);
    $cnt = substr_count($tb['root'], "..");
    for($i=2; $i<=$cnt; $i++)
        $dir = dirname($dir);
    $tb['url'] .= $dir;
}
// \ 를 / 롤 변경
$tb['url'] = strtr($tb['url'], "\\", "/");
// url 의 끝에 있는 / 를 삭제한다.
$tb['url'] = preg_replace("/\/$/", "", $tb['url']);

$dirname = dirname(__FILE__).'/';
$_SERVER['PHP_SELF'] = htmlentities($_SERVER['PHP_SELF']);

//==============================================================================
// 공용 변수
//==============================================================================
// 기본환경설정
// 기본적으로 사용하는 필드만 얻은 후 상황에 따라 필드를 추가로 얻음
$config	 = sql_fetch("select * from shop_config");
$default = sql_fetch("select * from shop_default");
if($boardid) {
	$board = sql_fetch("select * from shop_board_conf where index_no='$boardid'");
}

// DB 최적화
include_once("$tb[mall]/inc/db_table.optimize.php");

// 4.00.03 : [보안관련] PHPSESSID 가 틀리면 로그아웃한다.
if($_REQUEST['PHPSESSID'] && $_REQUEST['PHPSESSID'] != session_id())
    goto_url("{$tb['bbs_root']}/logout.php");

// QUERY_STRING
$qstr = "";

if(isset($ca_id))  {
    $ca_id = mysql_real_escape_string($ca_id);
    $qstr .= '&amp;ca_id=' . urlencode($ca_id);
}

if(isset($sca))  {
    $sca = mysql_real_escape_string($sca);
    $qstr .= '&amp;sca=' . urlencode($sca);
}

if(isset($sfl))  {
    $sfl = mysql_real_escape_string($sfl);
    // 크롬에서만 실행되는 XSS 취약점 보완
    // 코드 $sfl 변수값에서 < > ' " % = ( ) 공백 문자를 없앤다.
    $sfl = preg_replace("/[\<\>\'\"\%\=\(\)\s]/", "", $sfl);
    //$sfl = preg_replace("/[^\w\,\|]+/", "", $sfl);
    $qstr .= '&amp;sfl=' . urlencode($sfl); // search field (검색 필드)
}

if(isset($stx))  { // search text (검색어)
    //$stx = mysql_real_escape_string($stx);
    $qstr .= '&amp;stx=' . urlencode($stx);
}

if(isset($sst))  {
    $sst = mysql_real_escape_string($sst);
    $qstr .= '&amp;sst=' . urlencode($sst); // search sort (검색 정렬 필드)
}

if(isset($sod))  { // search order (검색 오름, 내림차순)
    $sod = preg_match("/^(asc|desc)$/i", $sod) ? $sod : "";
    $qstr .= '&amp;sod=' . urlencode($sod);
}

if(isset($sop))  { // search operator (검색 or, and 오퍼레이터)
    $sop = preg_match("/^(or|and)$/i", $sop) ? $sop : "";
    $qstr .= '&amp;sop=' . urlencode($sop);
}

if(isset($spt))  { // search part (검색 파트[구간])
    $spt = (int)$spt;
    $qstr .= '&amp;spt=' . urlencode($spt);
}

if($wr_id) {
    $wr_id = (int)$wr_id;
}

if($boardid) {
    $boardid = preg_match("/^[a-zA-Z0-9_]+$/", $boardid) ? $boardid : "";
}

// URL ENCODING
if(isset($url)) {
    $urlencode = urlencode($url);
}
else {
    // 2008.01.25 Cross Site Scripting 때문에 수정
    //$urlencode = $_SERVER['REQUEST_URI'];
    $urlencode = urlencode($_SERVER['REQUEST_URI']);
}
//===================================

// 로그인중이라면
$super = get_member('admin');
$super_hp = $super['cellphone'];
if(get_session('ss_mb_id')) {
	$member = get_member(get_session('ss_mb_id'));
	$partner = sql_fetch("select * from shop_partner where mb_id = TRIM('$member[id]')");
	$seller = sql_fetch("select * from shop_seller where mb_id = TRIM('$member[id]')");
}
else
{
	// 자동로그인 ---------------------------------------
    // 회원아이디가 쿠키에 저장되어 있다면 (3.27)
    if($tmp_mb_id = get_cookie("ck_mb_id"))
    {
        $tmp_mb_id = substr(preg_replace("/[^a-zA-Z0-9_]*/", "", $tmp_mb_id), 0, 20);
        // 최고관리자는 자동로그인 금지
        if($tmp_mb_id != 'admin')
        {
            $sql = " select * from shop_member where id = '{$tmp_mb_id}' ";
            $row = sql_fetch($sql);
            $key = md5($_SERVER['SERVER_ADDR'] . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'] . $row['passwd']);
            // 쿠키에 저장된 키와 같다면
            $tmp_key = get_cookie("ck_auto");
            if($tmp_key == $key && $tmp_key)
            {
                // 인트로 사용이 아니라면
                if(!$config[sp_intro])
				{
                    // 세션에 회원아이디를 저장하여 로그인으로 간주
                    set_session("ss_mb_id", $tmp_mb_id);

                    // 페이지를 재실행
                    echo "<script type='text/javascript'> window.location.reload(); </script>";
                    exit;
                }
            }
            // $row 배열변수 해제
            unset($row);
        }
    }
    // 자동로그인 end ---------------------------------------
}

// 비회원구매를 위해 쿠키를 1년간 저장
if(!get_cookie("ck_guest"))   
	set_cookie("ck_guest", $server_time, 86400 * 365);

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

// 회원, 비회원 구분
$is_member = $is_guest = false;
if($member['id']) {
	$is_member = true;
	$mb_yes = 1; 
	$mb_no = $member['index_no'];
} else {
	$is_guest = true;
	$mb_yes = 0;    
	$mb_no = get_cookie('ck_guest');
}

// 모바일 사용중지
if(!$config['mo_shop_yn'])
	goto_url(TW_URL);

include_once("$tb[mall]/inc/extend.php"); // 가맹점체크
include_once("$tb[mall]/inc/login-oauth.php"); // SNS 로그인
?>