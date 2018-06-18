<?php
include_once("./_common.php");

check_demo();

// 세션에 저장된 토큰과 폼으로 넘어온 토큰을 비교하여 틀리면 에러
if($token && get_session("ss_token") == $token) {
    // 맞으면 세션을 지워 다시 입력폼을 통해서 들어오도록 한다.
    set_session("ss_token", "");
} else {
    alert("토큰 에러");
}

// 취소
$sql = "insert into shop_order_cancel 
		   set ca_key		 = '{$_POST['ca_key']}', 
			   ca_type		 = '{$_POST['ca_type']}',
			   ca_od_uid	 = '{$_POST['ca_od_uid']}',	
			   ca_od_dan	 = '{$_POST['ca_od_dan']}',
			   ca_it_aff	 = '{$_POST['ca_it_aff']}',
			   ca_it_seller	 = '{$_POST['ca_it_seller']}',
			   ca_cancel_use = '{$_POST['ca_cancel_use']}',
			   ca_cancel	 = '{$_POST['ca_cancel']}',
			   ca_memo		 = '{$_POST['ca_memo']}', 
			   ca_bankcd	 = '{$_POST['ca_bankcd']}',
			   ca_banknum	 = '{$_POST['ca_banknum']}',	
			   ca_bankname	 = '{$_POST['ca_bankname']}',
			   ca_ip		 = '{$_SERVER['REMOTE_ADDR']}',
			   ca_wdate		 = '$time_ymdhis'";
sql_query($sql);

$sql = "update shop_order set dan='9' where index_no='{$_POST['ca_od_uid']}'";
sql_query($sql);

$subject = '[일련번호:'.$ca_key.'] 주문취소가 접수되었습니다.';	
if($_POST['ca_it_aff']) { // 가맹점상품인가?
	$mb = get_member($_POST['ca_it_seller']);
	$super_hp = $mb['cellphone'];
}

icode_member_send($super_hp, $subject);

alert("정상적으로 요청 되었습니다.", "replace");
?>