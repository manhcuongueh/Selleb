<?php
if(!defined('_TUBEWEB_')) exit;

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$basicBoard = array(40,39,38,37,36,35,22,21,20,13);

$sql_common = " from shop_board_conf ";
$sql_search = " where (1) ";
$sql_order  = " order by gr_id desc,index_no desc";

if($stx && $sfl) {
    switch($sfl) {
        case "bo_table" :
            $sql_search .= " and (index_no like '$stx%') ";
            break;
        default :
            $sql_search .= " and ($sfl like '%$stx%') ";
            break;
    }
}

$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 30;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select * $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

$btn_frmline = <<<EOF
<button type="button" onclick="btn_check('update')" class="btn_lsmall bx-white">선택수정</button>
<button type="button" onclick="btn_check('delete')" class="btn_lsmall bx-white">선택삭제</button>
<a href="config.php?code=board_form" class="fr btn_lsmall red"><i class="ionicons ion-android-add"></i> 추가하기</a>
EOF;
?>

<h2>기본검색</h2>
<form name="fsearch" id="fsearch" method="get">
<input type="hidden" name="code" value="<?php echo $code; ?>">
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col width="100px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th>검색어</th>
		<td>
			<select name="sfl">
				<option value='bo_table'<?php echo get_selected($sfl, "bo_table"); ?>>TABLE</option>
				<option value='boardname'<?php echo get_selected($sfl, "boardname"); ?>>제목</option>
				<option value='gr_id'<?php echo get_selected($sfl, "gr_id"); ?>>그룹ID</option>
			</select>
			<input type="text" name="stx" value="<?php echo $stx; ?>" class="frm_input w325">
		</td>
	</tr>
	</tbody>
	</table>
</div>
<div class="btn_confirm">
	<input type="submit" value="검색" class="btn_medium">
	<input type="button" value="초기화" id="frmRest" class="btn_medium grey">	
</div>
</form>

<form name="fboardlist" method="post">
<input type="hidden" name="q1" value="<?php echo $q1;?>">
<input type="hidden" name="page" value="<?php echo $page;?>">

<div class="local_ov mart30">
	전체 : <b class="fc_red"><?php echo number_format($total_count);?></b> 건 조회
</div>
<div class="local_frm01">
	<?php echo $btn_frmline; ?>
</div>
<div class="tbl_head01">
	<table>
	<colgroup>
		<col width="50px">
		<col width="50px">
		<col width="50px">
		<col>
		<col width="100px">
		<col width="80px">
		<col width="80px">
		<col width="80px">
		<col width="60px">
		<col width="60px">
		<col width="60px">
	</colgroup>
	<thead>
	<tr>
		<th><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form)"></th>
		<th>NO</th>
		<th>TABLE</th>
		<th>게시판제목</th>
		<th>그룹</th>
		<th>목록</th>
		<th>읽기</th>
		<th>쓰기</th>
		<th>답글</th>
		<th>코멘트</th>
		<th>관리</th>
	</tr>
	</thead>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$bo_table = $row[index_no];

		if($row[list_priv]==99) {
			$list_m = "비회원";
		} else {
			$list_m = get_grade($row[list_priv]);
		}

		if($row[read_priv]==99) {
			$read_m = "비회원";
		} else {
			$read_m = get_grade($row[read_priv]);
		}

		if($row[write_priv]==99) {
			$write_m = "비회원";
		} else {
			$write_m = get_grade($row[write_priv]);
		}

		$s_upd = "<a href='config.php?code=board_form&w=u&bo_table=$bo_table$qstr&page=$page' class=\"btn_small\">수정</a>";

		$bg = 'list'.$i%2;

		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;
	?>
	<tr class="<?php echo $bg; ?>">
		<td>
			<input type="checkbox" name="chk[]" value="<?php echo $i; ?>">
			<input type="hidden" name="bo_table[<?php echo $i; ?>]" value="<?php echo $bo_table; ?>">
		</td>
		<td><?php echo $num--; ?></td>
		<td><a href="/bbs/list.php?boardid=<?php echo $bo_table; ?>" target="_blank"><?php echo $bo_table; ?></a></td>
		<td><input type="text" class="frm_input" name="bo_subject[<?php echo $i; ?>]" value="<?php echo $row[boardname]?>"></td>
		<td><?php echo get_group_select("gr_id[$i]", $row[gr_id]); ?></td>
		<td><?php echo $list_m; ?></td>
		<td><?php echo $read_m; ?></td>
		<td><?php echo $write_m; ?></td>
		<td><?php echo ($row[usereply]=='Y')?'yes':''; ?></td>
		<td><?php echo ($row[usetail]=='Y')?'yes':''; ?></td>
		<td><?php echo $s_upd; ?></td>
	</tr>
	<?php 
	}
	if($i==0)
		echo '<tbody><tr><td colspan="11" class="empty_table">자료가 없습니다.</td></tr>';
	?>
	</tbody>
	</table>
</div>
<div class="local_frm02">
	<?php echo $btn_frmline; ?>
</div>
</form>

<?php if($total_count > 0) { ?>
<div class="btn_confirm">
	<?php echo pageing($page, $total_page, $total_count, "{$_SERVER['SCRIPT_NAME']}?$q1&page=");?>
</div>
<?php } ?>

<script>
function check_all(f)
{
    var chk = document.getElementsByName("chk[]");

    for(i=0; i<chk.length; i++)
        chk[i].checked = f.chkall.checked;
}

function btn_check(act)
{
	var f = document.fboardlist;

    if(act == "update") // 선택수정
    {
        f.action = './config/board_list_update.php';
        str = "수정";
    }
    else if(act == "delete") // 선택삭제
    {
        f.action = './config/board_list_delete.php';
        str = "삭제";
    }
    else
        return;

    var chk = document.getElementsByName("chk[]");
    var bchk = false;

    for(i=0; i<chk.length; i++)
    {
        if(chk[i].checked)
            bchk = true;
    }

    if(!bchk)
    {
        alert(str + "할 자료를 하나 이상 선택하세요.");
        return;
    }

    if(act == "delete")
    {
        if(!confirm("선택한 자료를 정말 삭제 하시겠습니까?"))
            return;
    }

    f.submit();
}

</script>
