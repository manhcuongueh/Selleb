<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

for($i=0; $i<count($chk); $i++) 
{
    // 실제 번호를 넘김
    $k = $chk[$i];

	$gr_id = trim($_POST['gr_id'][$k]);

	$row = sql_fetch(" select count(*) as cnt from shop_board_conf where gr_id = '$gr_id' ");
	if($row[cnt])
		alert("이 그룹에 속한 게시판이 존재하여 게시판 그룹을 삭제할 수 없습니다.\\n\\n이 그룹에 속한 게시판을 먼저 삭제하여 주십시오.", "../config.php?code=board&sfl=gr_id&stx=$gr_id");

	// 그룹 삭제
	sql_query(" delete from shop_board_group where gr_id = '$gr_id' ");
}

goto_url("../config.php?$q1&page=$page");
?>