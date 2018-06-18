<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

check_admin_token();

$index_no   = $_POST['index_no'];
$po_point   = $_POST['po_point'];
$po_content = $_POST['po_content'];
$po_kind	= $_POST['po_kind'];

$mb = sql_fetch("select point from shop_member where index_no='$index_no'");

if($po_kind=='I') {
	$total = $po_point + $mb[point];
	$sql = "insert into shop_point 
				   (  mb_no, income, total, memo, wdate ) 
			VALUES ('$index_no', '$po_point', '$total', '$po_content', '$server_time')";

} else if($po_kind=='O') {
	if(($mb[point] - $po_point) < 0) {
		alert('포인트가 음의 값이 되므로 변경할 수 없습니다.');
	}

	$total = $mb[point] - $po_point;
	$sql = "insert into shop_point 
				   ( mb_no, outcome, total, memo, wdate ) 
			VALUES ('$index_no', '$po_point', '$total', '$po_content', '$server_time')";
}
sql_query($sql);

sql_query("update shop_member set point='$total' where index_no=$index_no");

alert('정상적으로 처리 되었습니다.','replace');
?>