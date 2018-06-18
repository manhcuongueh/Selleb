<?php
include_once("./_common.php");

check_demo();

if(!$member['id']) {
	alert('회원 전용 서비스입니다.',TW_BBS_URL."/login.php?url=$nowurl");
}

// 세션에 저장된 토큰과 폼값으로 넘어온 토큰을 비교.
if($_POST["token"] && get_session("ss_token") == $_POST["token"]) {
	// 맞으면 세션을 지워 다시 입력폼을 통해서 들어오도록 한다.
	set_session("ss_token", "");
} else {
	alert("잘못된 접근 입니다.");
	exit;
}

$gi_num = $_POST['gi_num1'].'-'.$_POST['gi_num2'].'-'.$_POST['gi_num3'].'-'.$_POST['gi_num4'];
$gi_num = preg_replace("/\s+/","",$gi_num);

$row = sql_fetch(" select * from shop_gift where gi_num = '$gi_num' ");

if(!$row['no'])
	alert("쿠폰번호가 존재하지 않습니다. 확인 후 다시 등록 바랍니다.");

if($row['gr_edate'] < $time_ymd)
	alert("현재 쿠폰은 사용기간이 만료 되었습니다. \\n\\n만료날짜 : ".$row['gr_edate']);

if($row['gr_sdate'] > $time_ymd)
	alert("현재 쿠폰은 ".$row['gr_sdate']."일 이후부터 사용 가능하십니다.");

if($row['gi_use'])
	alert("현재 쿠폰은 이미 등록 된 상태입니다.");

unset($value);
$value['mb_id']		= $member['id'];
$value['mb_name']	= $member['name'];
$value['mb_wdate']	= $time_ymdhis;
$value['gi_use']	= 1;
update("shop_gift", $value, "where gi_num = '$gi_num' ");

// 포인트적립
$content = $row['gr_subject']." ".number_format($row['gr_price'])."P 적립";
insert_point($member['index_no'], $row['gr_price'], $content);

alert("정상적으로 처리 되었습니다.", TW_SHOP_URL."/gift.php");
?>