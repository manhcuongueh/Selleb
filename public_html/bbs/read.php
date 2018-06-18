<?php
include_once("./_common.php");

$boardconfig = sql_fetch(" select * from shop_board_conf where index_no='$boardid'" );
$boardinfo = sql_fetch(" select * from shop_board_{$boardid} where index_no='$index_no'" );

//회원 권한추출
$grade = $member['grade'];

//비회원일경우 "99" 번호 임시부여
if(empty($grade)) { $grade = 99; }

//게시물읽기 권한이 비회원이 아닐경우 체킹
if($boardconfig['read_priv'] < '99')
{
	if($grade > $boardconfig['read_priv']) {
		alert('권한이 없습니다.');
	}
}

$bo_wdate		= date("Y-m-d",$boardinfo['wdate']);
$bo_writer		= $boardinfo['writer'];
$bo_writer_s	= $boardinfo['writer_s'];
$bo_issecret	= $boardinfo['issecret'];
$bo_subject		= $boardinfo['subject'];
$bo_memo		= nl2br($boardinfo['memo']);
$bo_file1		= $boardinfo['fileurl1'];
$bo_file2		= $boardinfo['fileurl2'];
$bo_hit			= $boardinfo['readcount'] + 1;
$bo_passwd		= $boardinfo['passwd'];

$qstr1 = "boardid=$boardid&key=$key&keyword=$keyword&page=$page";
$qstr2 = "index_no=$index_no&boardid=$boardid&key=$key&keyword=$keyword&page=$page";
$qstr3 = "boardid=$boardid&key=$key&keyword=$keyword";

$mb = sql_fetch("select id from shop_member where index_no = '$bo_writer'");
$bo_writer_id = $mb['id'];

if($bo_file1)
	$refile1 = "<a href='".TW_DATA_URL."/board/$boardid/$bo_file1' target='_blank'>$bo_file1</a>";

if($bo_file2)
	$refile2 = "<a href='".TW_DATA_URL."/board/$boardid/$bo_file2' target='_blank'>$bo_file1</a>";

sql_query("update shop_board_{$boardid} set readcount='$bo_hit' where index_no='$index_no' ");

$accept = array("gif","jpg","GIF","JPG","PNG","png");
$bo_subject = "<b>".$bo_subject."</b>";

if($bo_issecret=='Y')
{
	if($bo_writer!=0) {
		include_once(TW_INC_PATH."/access.php");
	}

	if(!is_admin())
	{
		if($memid)
		{
			//관리자가 답변을 달면 본인이 글을 볼수가 없었던문제 버그수정 jck
			$sb_sql = MQ(" select fid from shop_board_{$boardid} where index_no = '$index_no' ");
			if( sql_num_rows($sb_sql) > 0 )
			{
				$bo_fid = mysql_result($sb_sql,0,0);
				$bo_writer = mysql_result(MQ(" select writer from shop_board_{$boardid} where fid = '$bo_fid' and thread = 'A' "),0,0);
			}

			if($member['index_no'] != $bo_writer) {
				alert("비밀글은 열람하실 수 없습니다.");
			}
		}
		else
		{
			$inpasswd = $_GET['inpasswd'];
			$memindex = 0;

			if($inpasswd != $bo_passwd) {
				goto_url("secret.php?index_no=$index_no&$qstr1");
			}
			else
			{
				//관리자가 답변을 달면 본인이 글을 볼수가 없었던문제 버그수정 jck
				$sb_sql = MQ(" select fid from shop_board_{$boardid} where index_no = '$index_no' ");
				if( sql_num_rows($sb_sql) > 0 )
				{
					$bo_fid = mysql_result($sb_sql,0,0);
					$bo_writer = mysql_result(MQ(" select writer from shop_board_{$boardid} where fid = '$bo_fid' and thread = 'A' "),0,0);
				}
			}
		}
	}
}

if($boardconfig['topfile']) {
	include $boardconfig['topfile'];
}

if($boardconfig['content_head']) {
	echo $boardconfig['content_head'];
}

if($boardconfig['width']<=100) {
	$boardconfig['width']  = $boardconfig['width'] ."%";
}

$bo_img_url = TW_BBS_URL.'/skin/'.$boardconfig['skin'];

include "skin/".$boardconfig['skin']."/read.php";

if($boardconfig['content_tail']) {
	echo $boardconfig['content_tail'];
}

if($boardconfig['downfile']) {
	include $boardconfig['downfile'];
}
?>