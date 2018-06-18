<?php
@ini_set("session.use_trans_sid", 0); // PHPSESSID를 자동으로 넘기지 않음
@ini_set("url_rewriter.tags",""); // 링크에 PHPSESSID가 따라다니는것을 무력화함 (해뜰녘님께서 알려주셨습니다.)

if(isset($SESSION_CACHE_LIMITER))
    @session_cache_limiter($SESSION_CACHE_LIMITER);
else
    @session_cache_limiter("no-cache, must-revalidate");

ini_set("session.cache_expire", 180); // 세션 캐쉬 보관시간 (분)
ini_set("session.gc_maxlifetime", 108000); // session data의 garbage collection 존재 기간을 지정 (초)
ini_set("session.gc_probability", 1); // session.gc_probability는 session.gc_divisor와 연계하여 gc(쓰레기 수거) 루틴의 시작 확률을 관리합니다. 기본값은 1입니다. 자세한 내용은 session.gc_divisor를 참고하십시오.
ini_set("session.gc_divisor", 100); // session.gc_divisor는 session.gc_probability와 결합하여 각 세션 초기화 시에 gc(쓰레기 수거) 프로세스를 시작할 확률을 정의합니다. 확률은 gc_probability/gc_divisor를 사용하여 계산합니다. 즉, 1/100은 각 요청시에 GC 프로세스를 시작할 확률이 1%입니다. session.gc_divisor의 기본값은 100입니다.

session_set_cookie_params(0, "/");
ini_set("session.cookie_domain", $tb['cookie_domain']);

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
	global $tb, $SESS_LIFE;

	$sql = "select value from shop_sessions where sesskey = '$key' and expiry > '$tb[server_time]'";
	$r = sql_query($sql);

	if(list($value) = mysql_fetch_row($r)) {
		return $value;
	}

	return false;
}

function sess_write($key, $val)
{
	global $tb, $SESS_LIFE;

	$expiry = $tb['server_time'] + $SESS_LIFE;
	$values = explode(";",str_replace("\"","",$val));
	$mb_no = explode(":",$values[3]);
	$value  = addslashes($val);
	$sql = "insert into shop_sessions 
	           set sesskey  = '$key',
			       expiry	= '$expiry',
				   value	= '$value' ";
	$r = mysql_query($sql);

	if(!$r) {
		$sql = "update shop_sessions 
		           set expiry = $expiry, 
				       value = '$value' 
				 where sesskey = '$key' 
				   and expiry > '$tb[server_time]' ";
		$r = sql_query($sql);
		sql_query("update shop_nows set mb_no='$mb_no[2]' where keys_v = '$key' and end_time > '$tb[server_time]'");
	
	} else {	
		sql_query("insert into shop_nows ( keys_v, mb_no, end_time ) VALUES ('$key', '$mb_no[2]', '$expiry')");	
	}
	return $r;
}

function sess_destroy($key)
{
	global $tb;

	$r = sql_query("delete from shop_nows where keys_v = '$key'");
	$r = sql_query("delete from shop_sessions where sesskey = '$key'");
	
	return $r;
}

function sess_gc()
{
	global $tb;

	sql_query("delete from shop_nows where end_time < '$tb[server_time]'");
	sql_query("delete from shop_sessions where expiry < '$tb[server_time]'");

	return true;
}

function set_session_id($SESSID){
	if($SESSID) @session_id($SESSID);
}

$SESSID = $_GET['SESSID'];
set_session_id($SESSID);
session_set_save_handler("sess_open", "sess_close", "sess_read", "sess_write", "sess_destroy", "sess_gc");
session_start();
?>
