<?php
include_once("./_common.php");

check_demo();

$boardconfig = sql_fetch(" select * from shop_board_conf where index_no='$boardid'" );
$boardinfo = sql_fetch(" select * from shop_board_{$boardid} where index_no='$index_no'" );

if($mode == 'd')
{
	if($boardinfo['writer']!=0)
	{
		include_once(TW_INC_PATH."/access.php");
		if(!is_admin()) {
			if($boardinfo['writer'] != $memindex) {
				alert('권한이 없습니다.');
			}
		}
	} else {
		if(!is_admin()) {
			$passwd = $_POST['passwd'];
			if($passwd != $boardinfo['passwd']) {
				alert('비밀번호가 일치하지 않습니다.');
			}
		}
	}
	$savedir = TW_DATA_PATH."/board/".$boardid;
	if($boardinfo['fileurl1']) @unlink($savedir."/".$boardinfo['fileurl1']);
	if($boardinfo['fileurl2']) @unlink($savedir."/".$boardinfo['fileurl2']);

	delete_editor_image($boardinfo['memo']);
    
	$sql = " delete from shop_board_{$boardid} where index_no='$index_no' "; 
	sql_query($sql);
	
	$sql = " delete from shop_board_{$boardid}_tail where board_index='$index_no' ";
	sql_query($sql);
	
	goto_url("list.php?boardid=$boardid&key=$key&keyword=$keyword&page=$page");
}

if($boardinfo['writer']!=0) {
	include_once(TW_INC_PATH."/access.php");
	if(!is_admin()) {
		if($boardinfo['writer'] != $memindex) {
			alert('권한이 없습니다.');
		}
	}
}

if($boardconfig['topfile']) {	
	@include_once($boardconfig['topfile']);
}

if($boardconfig['width'] <= 100) {	
	$boardconfig['width'] = $boardconfig['width'] ."%";	
}

include "skin/{$boardconfig['skin']}/del.php";

if($boardconfig['downfile']) {	
	@include_once($boardconfig['downfile']);
}
?>
