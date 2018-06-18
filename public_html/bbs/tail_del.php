<?php
include_once("./_common.php");

$boardconfig = sql_fetch("select * from shop_board_conf where index_no='$boardid'");
$boardinfo	 = sql_fetch("select * from shop_board_{$boardid}_tail where index_no='$tailindex'");
$boardinfo2	 = sql_fetch("select * from shop_board_{$boardid} where index_no='$index_no'");

if($mode=='d') {
	if($boardinfo['writer']!=0) {
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
	
	sql_query("delete from shop_board_{$boardid}_tail where index_no='$tailindex'");
	sql_query("update shop_board_{$boardid} set tailcount=tailcount-1 where index_no='$index_no'");
	
	goto_url("read.php?index_no=$index_no&page=$page&boardid=$boardid&key=$key&keyword=$keyword");
}

if($boardinfo['writer']!=0)
{
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

include "skin/{$boardconfig['skin']}/tail_del.php";

if($boardconfig['downfile']) {	
	@include_once($boardconfig['downfile']);
}
?>
