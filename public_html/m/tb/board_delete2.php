<?php
include_once("./_common.php");

if($w=='d')
{
	check_demo();

	$sql = "select * from shop_board_{$boardid} where index_no='$index_no'";
	$row = sql_fetch($sql);

	if($row['writer'] != 0) {
		if(!$is_member) {
			goto_url("./login.php");
		}

		if(!is_admin()) {
			if($row['writer'] != $mb_no) {
				alert('삭제 권한이 없습니다.');
			}
		}

	} else {
		if(!is_admin()) {
			if($_POST['passwd'] != $row['passwd']) {
				alert('비밀번호가 일치하지 않습니다.');
			}
		}
	}

	$file1_dir = TW_DATA_PATH.'/board/'.$boardid.'/'.$row['fileurl1'];
	if(is_file($file1_dir) && $row['fileurl1'])
		@unlink($file1_dir);

	$file2_dir = TW_DATA_PATH.'/board/'.$boardid.'/'.$row['fileurl2'];
	if(is_file($file2_dir) && $row['fileurl2'])
		@unlink($file2_dir);
	
	delete_editor_image($row['memo']);
    
	$sql = " delete from shop_board_{$boardid} where index_no='$index_no' "; 
	sql_query($sql);

	$sql = " delete from shop_board_{$boardid}_tail where board_index='$index_no' ";
	sql_query($sql);

	goto_url("./board_list.php?boardid=$boardid&page=$page");
}
?>