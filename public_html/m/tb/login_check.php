<?php
define('_PURENESS_', true);
include_once("./_common.php");

// 현재로그인 && 세션삭제
sql_query(" delete from shop_nows where end_time < '$server_time' ");
sql_query(" delete from shop_sessions where expiry < '$server_time' ");

// 공급사권한이 풀리는 현상이 있어 다시 검사
$sql = "select mb_id from shop_seller where state=1";
$res = sql_query($sql);
while($row=sql_fetch_array($res)){
	$sql = " update shop_member set supply='Y' where id = '$row[mb_id]' ";
	sql_query($sql, false);
}

$mb_id = trim($_POST['mb_id']);
$mb_password = trim($_POST['mb_password']);

// 비회원 로그인일때
if($_POST['od_id'] && $_POST['od_pwd']) {
	$od_id = trim($_POST['od_id']);
	$od_pwd = get_encrypt_string($_POST['od_pwd']);

	$od = sql_fetch("select mb_no from shop_order where odrkey = '$od_id' and passwd = '$od_pwd' ");
	if($od['mb_no']) {
		set_cookie("ck_guest", $od['mb_no'], 86400 * 365);
		set_session("ck_guest_od", $od['mb_no']);
		goto_url($tb['bbs_root'].'/orderlist.php');
	} else {
		alert("주문내역이 존재하지 않습니다.");
	}
}

$mb = get_member($mb_id);

// 가입된 회원이 아니다. 패스워드가 틀리다. 라는 메세지를 따로 보여주지 않는 이유는
// 회원아이디를 입력해 보고 맞으면 또 패스워드를 입력해보는 경우를 방지하기 위해서입니다.
// 불법사용자의 경우 회원아이디가 틀린지, 패스워드가 틀린지를 알기까지는 많은 시간이 소요되기 때문입니다.
if(!$mb['id'] || (sql_password($mb_password) != $mb['passwd']))
    alert("가입된 회원이 아니거나 패스워드가 틀립니다.\\n\\n패스워드는 대소문자를 구분합니다.");

// 인트로 사용시 승인된 회원인지 체크
if($mb['grade'] > 1 && !$mb['use_app'] && $config['sp_app'])	
	alert("승인 된 회원만 로그인 가능합니다.");

// 여기부터 관리비사용시 미납여부 체그
if($config['p_month']=='y' && $config['accent_tree']=='y' && is_partner($mb['id'])) {
	$h_y = date("Y",intval($mb['term_date'],10));
	$h_m = date("m",intval($mb['term_date'],10));
	$h_d = date("d",intval($mb['term_date'],10));
	$new_hold = mktime(0,0,1,$h_m,$h_d,$h_y);
	$ed = $new_hold - $server_time;

	if($ed > 0) { $default_check = 1; }
	else { $default_check = 2; }

	if($default_check == 2)	{
		set_session('pt_id', 'admin');		
		alert("현재 회원님께서는 관리비 미납으로 로그인이 중지된 상태입니다.");
	}
}

// 로그인 포인트적립
if($config['login_point']) {
	$sql = " select count(*) as cnt 
			   from shop_point 
			  where DATE_FORMAT(FROM_UNIXTIME(wdate),'%Y-%m-%d')='$time_ymd' 
				and po_ty = 'login'
				and mb_no = '$mb[index_no]' ";
	$row = sql_fetch($sql);
	if(!$row['cnt']) {
		insert_point($mb['index_no'], $config['login_point'], "$time_ymd 로그인 포인트적립", "login");
	}
}

// 로그인 카운터 증가
$sql = " update shop_member 
			set login_sum = login_sum + 1,
				login_ip = '{$_SERVER['REMOTE_ADDR']}',
				today_login = '$time_ymdhis'
		  where id = '$mb[id]'";
sql_query($sql);

// 회원아이디 세션 생성
set_session('ss_mb_id', $mb['id']);

// FLASH XSS 공격에 대응하기 위하여 회원의 고유키를 생성해 놓는다. 관리자에서 검사함 - 110106
set_session('ss_mb_key', md5($mb['reg_time'] . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']));

// 자동로그인 : 아이디 쿠키에 한달간 저장
if($auto_login) {
    // 쿠키 한달간 저장
    $key = md5($_SERVER['SERVER_ADDR'] . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'] . $mb['passwd']);
    set_cookie('ck_mb_id', $mb['id'], 86400 * 31);
    set_cookie('ck_auto', $key, 86400 * 31);
} else {
    set_cookie('ck_mb_id', '', 0);
    set_cookie('ck_auto', '', 0);
}

// 아이디 저장
if($auto_saveid) {
    // 쿠키 한달간 저장
    set_cookie('ck_saveid', $mb['id'], 86400 * 31);
} else {
    set_cookie('ck_saveid', '', 0);
}

if($url)
{
    $link = urldecode($url);

	// 비회원 주문시도 후 회원구매로 전환할 경우
	if($link == "../tb/orderform.php") {
		$ss_cart_id = get_session('ss_cart_id');
		if($ss_cart_id) {
			$sql = "update shop_cart 
					   set mb_no  = '$mb[index_no]',
						   mb_yes = '1'
					 where index_no IN ($ss_cart_id)";
			sql_query($sql);
		}				
	}

    // 2003-06-14 추가 (다른 변수들을 넘겨주기 위함)
    if(preg_match("/\?/", $link))
        $split= "&";
    else
        $split= "?";

    // $_POST 배열변수에서 아래의 이름을 가지지 않은 것만 넘김
    foreach($_POST as $key=>$value)
    {
        if($key != "mb_id" && $key != "mb_password" && $key != "x" && $key != "y" && $key != "url")
        {
            $link .= "$split$key=$value";
            $split = "&";
        }
    }
}
else
    $link = $tb['root'];

goto_url($link);
?>