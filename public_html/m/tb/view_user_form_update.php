<?php
include_once("./_common.php");

check_demo();

if(!$is_member) {
	alert("로그인 후 작성 가능합니다.");
}

if($w == "" || $w == "u") {
	if($_POST["token"] && get_session("ss_token") == $_POST["token"]) {
		// 맞으면 세션을 지워 다시 입력폼을 통해서 들어오도록 한다.
		set_session("ss_token", "");
	} else {
		alert("잘못된 접근 입니다.");
		exit;
	}

	$gs_id = trim(strip_tags($_POST['gs_id']));
	$me_id = trim(strip_tags($_POST['me_id']));
	$wr_score = trim(strip_tags($_POST['wr_score']));
	$gs_se_id = trim(strip_tags($_POST['gs_se_id']));

	if(substr_count($_POST['wr_content'], "&#") > 50) {
		alert("내용에 올바르지 않은 코드가 다수 포함되어 있습니다.");
	}

	if(!get_magic_quotes_gpc()) {
		$wr_content = addslashes($wr_content);
	}
}

if($w == "") 
{ 
	$sql = "insert into shop_goods_review 
			   set gs_id	= '$gs_id', 
				   writer	= '$member[index_no]',
				   writer_s	= '$member[id]',
				   memo		= '$wr_content',
				   score	= '$wr_score',
				   wdate	= '$server_time',
				   gs_se_id	= '$gs_se_id',
				   pt_id	= '$pt_id' ";
	sql_query($sql);

	sql_query("update shop_goods set m_count = m_count + 1 where index_no='$gs_id'");

	alert("정상적으로 등록 되었습니다.","replace");
}
else if($w == "u")
{
    $sql = " update shop_goods_review
                set memo	= '$wr_content',
					score	= '$wr_score'
			  where index_no = '$me_id' ";
    sql_query($sql);

	alert("정상적으로 수정 되었습니다.","replace");
}
else if($w == "d")
{
	if(!is_admin())
    {
        $sql = " select index_no from shop_goods_review where writer_s = '{$member['id']}' and index_no = '$me_id' ";
        $row = sql_fetch($sql);
        if(!$row)
            alert("자신의 글만 삭제하실 수 있습니다.");
    }

	// 구매후기 삭제
    sql_query("delete from shop_goods_review where index_no='$me_id' and md5(concat(index_no,wdate,writer_s)) = '{$hash}' ");
	
	// 구매후기 삭제시 상품테이블에 상품평 카운터를 감소한다
	sql_query("update shop_goods set m_count=m_count - 1 where index_no='$gs_id'");
	
	if($p == "1")
		alert("정상적으로 삭제 되었습니다","./view_user.php?gs_id=$gs_id");
	else
		alert("정상적으로 삭제 되었습니다","./view.php?gs_id=$gs_id");		
}
?>