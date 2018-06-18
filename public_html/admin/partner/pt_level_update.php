<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

$chk = array_reverse($chk);
for($i=0; $i<count($chk); $i++) 
{
    // 실제 번호를 넘김
    $k = $chk[$i];

	$mb_id = trim($_POST['mb_id'][$k]);

	$dr = sql_fetch("select cf_1 from shop_partner where mb_id='$mb_id'");
	$mb = sql_fetch("select id,name,pt_id,index_no from shop_member where id='$mb_id'");
	$cf = sql_fetch("select mb_grade,etc1,etc2,etc4 from shop_partner_config where index_no='$dr[cf_1]'");
	
	$sql = " update shop_partner set state = '1' where mb_id='$mb_id' ";		
	sql_query($sql);

	include(TW_ADMIN_PATH."/partner/pt_rebate.php");

	// 1달 자동연장 처리
	$term_date = get_term_date();
	$sql = " update shop_member 
			    set grade  = '$cf[mb_grade]',
				    anew_date = '$time_Yhs',
				    term_date = '$term_date'
			  where id = '$mb_id' ";
	sql_query($sql);

	// 카테고리 생성
	sql_member_category($mb_id);	
}

goto_url("../partner.php?$q1&page=$page");
?>
