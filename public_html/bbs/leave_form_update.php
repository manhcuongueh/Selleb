<?php
include_once("./_common.php");

check_demo();

if(!$member['id'])
    alert("로그인 후 이용 가능합니다.");

if(is_admin())
	alert('관리자는 탈퇴하실 수 없습니다.');

$sql = " select count(*) as cnt from shop_member_leave where mb_no = '$mb_no' ";
$row = sql_fetch($sql);
if($row['cnt']) {
	alert('고객님께서는 이미 탈퇴신청이 접수 된 상태입니다.');
}

$sql = "insert into shop_member_leave 
			   ( mb_no , memo , wdate , other , mb_id , name ) 
	    VALUES ('$mb_no', '$_POST[out]', '$server_time','$_POST[other]','$member[id]','$member[name]')";
sql_query($sql);

$subject = get_text($member['name']).'님께서 회원탈퇴를 신청하셨습니다.';	
icode_member_send($super_hp, $subject);

alert('정상적으로 신청 되었습니다.', TW_BBS_URL.'/leave_form.php');
?>