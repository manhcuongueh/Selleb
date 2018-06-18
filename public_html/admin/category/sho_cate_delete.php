<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

$srcfile = TW_DATA_PATH.'/category/admin';
$upload_file = new upload_files($srcfile); 

$ca_no = trim($_POST['ca_no']);

$ca = sql_fetch("select * from shop_cate where index_no='$ca_no'");
$len = strlen($ca['catecode']);
if($len > 0) 
{
	// 본사 삭제
	$sql = "select * from shop_cate where left(upcate,$len)='$ca[catecode]'"; 
	$rst = sql_query($sql);
	while($row = sql_fetch_array($rst)) {
		if($row['index_no']) { // 대상 하위 삭제
			if($row['img_name']) { $upload_file->del($row['img_name']); }
			if($row['img_name_over']) { $upload_file->del($row['img_name_over']); }
			if($row['img_head']) { $upload_file->del($row['img_head']); }
			sql_query("delete from shop_cate where index_no='$row[index_no]'", FALSE);
		}
	}

	if($ca['img_name']) { $upload_file->del($ca['img_name']); }
	if($ca['img_name_over']) { $upload_file->del($ca['img_name_over']); }
	if($ca['img_head']) { $upload_file->del($ca['img_head']); }
	sql_query("delete from shop_cate where index_no='$ca_no'", FALSE);  // 삭제대상

	// 가맹점 삭제
	$sql = "select id from shop_member where grade between 2 and 6 ";
	$rst = sql_query($sql);
	while($row = sql_fetch_array($rst)) {

		$mb_id = $row['id'];
		$target_table = 'shop_cate_'.$mb_id;

		$mq = "select * from {$target_table} where p_catecode='$ca[catecode]' and p_oper='y'";
		$tg = sql_fetch($mq);
		$len = strlen($tg['catecode']);
		if($len > 0)
		{
			$t_srcfile = TW_DATA_PATH.'/category/'.$mb_id;
			$t_upload_file = new upload_files($t_srcfile); 

			$sql2 = "select * from {$target_table} where left(upcate,$len)='$tg[catecode]' "; 
			$rst2 = sql_query($sql2);
			while($row2 = sql_fetch_array($rst2)){
				if($row2['index_no']) { // 대상 하위 삭제
					if($row2['img_name']) { $t_upload_file->del($row2['img_name']); }
					if($row2['img_name_over']) { $t_upload_file->del($row2['img_name_over']); }
					if($row2['img_head']) { $t_upload_file->del($row2['img_head']); }
					sql_query("delete from {$target_table} where index_no='$row2[index_no]'", FALSE);
				}
			}		

			// 대상을 삭제함.
			if($tg['img_name']) { $t_upload_file->del($tg['img_name']); }
			if($tg['img_name_over']) { $t_upload_file->del($tg['img_name_over']); }
			if($tg['img_head']) { $t_upload_file->del($tg['img_head']); }
			sql_query("delete from {$target_table} where index_no='$tg[index_no]'", FALSE);
		}
	}
}

goto_url("../category.php?$q1");
?> 