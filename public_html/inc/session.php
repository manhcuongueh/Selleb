<?php
ini_set("session.cache_expire", 180); // 세션 캐쉬 보관시간 (분)
ini_set("session.gc_maxlifetime", 10800); // session data의 garbage collection 존재 기간을 지정 (초)
ini_set("session.gc_probability", 1); // session.gc_probability는 session.gc_divisor와 연계하여 gc(쓰레기 수거) 루틴의 시작 확률을 관리합니다. 기본값은 1입니다. 자세한 내용은 session.gc_divisor를 참고하십시오.
ini_set("session.gc_divisor", 100); // session.gc_divisor는 session.gc_probability와 결합하여 각 세션 초기화 시에 gc(쓰레기 수거) 프로세스를 시작할 확률을 정의합니다. 확률은 gc_probability/gc_divisor를 사용하여 계산합니다. 즉, 1/100은 각 요청시에 GC 프로세스를 시작할 확률이 1%입니다. session.gc_divisor의 기본값은 100입니다.

$SESS_LIFE = ini_get("session.gc_maxlifetime");

function sess_open($save_path, $session_name)
{
	global $SESS_DBH;
	return true;
}

function sess_close()
{
  return true;
}

function sess_read($key)
{
	global $SESS_LIFE;

	$qry = "select value from shop_sessions where sesskey = '$key' AND expiry > " . time();
	$qid = mysql_query($qry);

	if(list($value) = mysql_fetch_row($qid)) {
			return $value;
	}

	return false;
}

function sess_write($key, $val)
{
	global $SESS_LIFE;

	$expiry = time() + $SESS_LIFE;
	$ar_value = explode(";",str_replace("\"","",$val));
	$mb_no = explode(":",$ar_value[3]);
	$value = addslashes($val);
	$qry = "insert into shop_sessions (sesskey,expiry,value) VALUES ('$key', $expiry, '$value')";
	$qid = mysql_query($qry);

	if(! $qid) {
		$qry = "UPDATE shop_sessions SET expiry = $expiry, value = '$value' where sesskey = '$key' AND expiry > " . time();
		$qid = mysql_query($qry);
		mysql_query("update shop_nows SET mb_no='$mb_no[2]' where keys_v = '$key' AND end_time > " . time());
	}
	else
	{	
		mysql_query("insert into `shop_nows` ( `keys_v` , `mb_no` , `end_time` ) VALUES ('$key', '$mb_no[2]', '$expiry')");	
	}
	return $qid;
}

function sess_destroy($key)
{
	$qry = "delete from shop_nows where keys_v = '$key'";
	$qid = mysql_query($qry);
	$qry = "delete from shop_sessions where sesskey = '$key'";
	$qid = mysql_query($qry);
	return $qid;
}

function sess_gc()
{
	$qry = "delete from shop_nows where end_time < " . time();
	$qid = mysql_query($qry);
	$qry = "delete from shop_sessions where expiry < " . time();
	$qid = mysql_query($qry);
	return true;
}

function set_session_id($SESSID){
	if($SESSID) @session_id($SESSID);
}

$SESSID = $_GET['SESSID'];
set_session_id($SESSID);

// 보안설정이나 프레임이 달라도 쿠키가 통하도록 설정
header('P3P: CP="ALL CURa ADMa DEVa TAIa OUR BUS IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE LOC OTC"');
session_set_save_handler("sess_open", "sess_close", "sess_read", "sess_write", "sess_destroy", "sess_gc");

session_start();
$HTTP_SESSION_VARS	= $_SESSION;
$HTTP_POST_VARS		= $_POST;
$HTTP_GET_VARS		= $_GET;
$HTTP_COOKIE_VARS	= $_COOKIE;
$HTTP_SERVER_VARS	= $_SERVER;
?>
