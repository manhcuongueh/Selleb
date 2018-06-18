<?php
include_once("./_common.php");

$ss_cart_id = get_session('ss_cart_id');
if(!$ss_cart_id)
	alert("주문하실 상품이 없습니다.");

set_session('del_amt', '');
set_session('total_amt', '');
set_session('use_point', '');
set_session('ss_pay_method', ''); 

$gw_head_title = '주문서작성';
include_once("./_head.php");

if($member['id']) { // 회원일때
	// 주문자가 가맹점이면 추천인을 자신으로 변경
	$mb_recommend = $member['pt_id'];
	if(is_partner($member['id'])) {
		$mb_recommend = $member['id'];
	}
} else {
	$mb_recommend = $pt_id;
	$member['point'] = 0;
}

$sql = " select * from shop_cart where index_no IN ({$ss_cart_id}) group by gs_id order by index_no ";
$result = sql_query($sql);

$order_action_url = TW_SHOP_URL.'/orderform_update.php';
include_once($theme_path.'/orderform.skin.php');

include_once("./_tail.php");
?>