<?php
include_once("./_common.php");

check_demo();

$upl_dir = TW_DATA_PATH."/board/".$boardid;
$upl = new upload_files($upl_dir);

if(substr_count($_POST['memo'], "&#") > 50) {
    alert("내용에 올바르지 않은 코드가 다수 포함되어 있습니다.");
}

if(!$_POST['subject']) { alert("게시판 제목을 입력하세요."); }
if(!$_POST['writer_s']) { alert("작성자명이 없습니다."); }

$upload_max_filesize = ini_get('upload_max_filesize');
if(empty($_POST))
    alert("파일 또는 글내용의 크기가 서버에서 설정한 값을 넘어 오류가 발생하였습니다.\\n\\npost_max_size=".ini_get('post_max_size')." , upload_max_filesize=$upload_max_filesize\\n\\n게시판관리자 또는 서버관리자에게 문의 바랍니다.");

$boardconfig = get_boardconf($boardid);
$writer = 0;

if($_POST['havehtml']!='Y')	$_POST['havehtml'] = "N";
if($_POST['btype']!='1') $_POST['btype'] = '2';
if($_POST['issecret']!='Y') $_POST['issecret'] = "N";
if($member['id']) $writer = $member['index_no'];

$fid = get_next_num("shop_board_".$boardid);

if($_POST['mode'] == "w") {
	$sql_commend = " , btype	= '$_POST[btype]'                
					 , ca_name	= '$_POST[ca_name]'
					 , issecret	= '$_POST[issecret]'
					 , havehtml	= '$_POST[havehtml]'
					 , writer	= '$writer'
					 , writer_s	= '$_POST[writer_s]'
					 , subject	= '$_POST[subject]'
					 , memo		= '$_POST[memo]'							
					 , passwd	= '$_POST[passwd]'
					 , average	= '$_POST[average]'
					 , product	= '$_POST[product]'
					 , pt_id	= '$pt_id' ";

	if($_POST['del_file1']) {
		$upl->del($_POST['del_file1']);
		$sql_commend .= " , fileurl1 = '' ";
	}
	if($_POST['del_file2']) {
		$upl->del($_POST['del_file2']);
		$sql_commend .= " , fileurl2 = '' ";
	}
	if($_FILES['file1']['name']) {
		$new_file1 = $upl->upload($_FILES['file1']); 
		$sql_commend .= " , fileurl1 = '$new_file1' ";
	}
	if($_FILES['file2']['name']) {
		$new_file2 = $upl->upload($_FILES['file2']); 
		$sql_commend .= " , fileurl2 = '$new_file2' ";
	}
	   
	$sql = " insert into shop_board_{$boardid}
				set fid		= '$fid'
				  , wdate	= '$server_time'
				  , wip		= '{$_SERVER['REMOTE_ADDR']}'
				  , thread	= 'A'
				{$sql_commend} ";
	sql_query($sql);

	if($member['email']) {
		include_once(TW_INC_PATH."/mail.php");

		$wr_subject = get_text(stripslashes($_POST['subject']));
		$wr_content = conv_content(conv_unescape_nl(stripslashes($_POST['memo'])), 1);
		$wr_name = get_text($member['name']);

		$subject = '['.$boardconfig['boardname'].'] 게시판에 새글이 등록되었습니다.';

		ob_start();
		include_once('./write_update_mail.php');
		$content = ob_get_contents();
		ob_end_clean();

		mailer($member['name'], $member['email'], $super['email'], $subject, $content, 1);
	}

	goto_url("list.php?boardid=$boardid");
} else {
	alert("정상적인 접근이 아닌것 같습니다.");
}
?>