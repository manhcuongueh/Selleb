<?php
if(!defined('_TUBEWEB_')) exit;

$pg_title = "상품 평점관리";
include_once("./admin_head.sub.php");

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_goods_review ";
$sql_search = " where gs_se_id = '$seller[sup_code]' ";

if($stx && $sfl) {
    $sql_search .= " and $sfl like '%$stx%' ";
}

if(!$orderby) {
    $filed = "wdate";
    $sod = "desc";
} else {
	$sod = $orderby;
}

$sql_order = "order by $filed $sod";

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
<button type="button" onclick="btn_check('delete');" class="btn_lsmall bx-white">선택삭제</button>
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
		<th>검색키워드</th>
		<td>
			<select name="sfl">				
				<?php echo option_selected('writer_s', $sfl, '작성자'); ?>
				<?php echo option_selected('gs_se_id', $sfl, '판매자'); ?>
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

<form name='fitemlist' method='post'>
<input type='hidden' name='q1' value="<?php echo $q1; ?>">
<input type='hidden' name='page' value="<?php echo $page; ?>">
<div class="local_ov mart30">
	전체 : <b class="fc_red"><?php echo number_format($total_count); ?></b> 건 조회
</div>
<div class="local_frm01">
	<?php echo $btn_frmline; ?>
</div>
<div class="tbl_head01">
	<table>
	<colgroup>
		<col width="50px">
		<col width="50px">
		<col width="60px">
		<col>
		<col width="100px">
		<col width="80px">
		<col width="100px">
	</colgroup>
	<thead>
	<tr>
		<th><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form)"></th>
		<th>번호</th>
		<th>이미지</th>
		<th>내용</th>
		<th><?php echo subject_sort_link('writer_s',$q2); ?>작성자</a></th>
		<th><?php echo subject_sort_link('wdate',$q2); ?>작성일</a></th>
		<th><?php echo subject_sort_link('score',$q2); ?>평점</a></th>
	</tr>
	</thead>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$gmid = $row['index_no'];

		$gs = get_goods($row['gs_id'],'simg1, gname');

		$bg = 'list'.($i%2);

		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;
	?>
	<tr class="<?php echo $bg; ?>">
		<td>
			<input type="checkbox" name="chk[]" value="<?php echo $i; ?>">
			<input type="hidden" name="gmid[<?php echo $i; ?>]" value="<?php echo $gmid; ?>">
		</td>
		<td><?php echo $num--; ?></td>
		<td><a href="<?php echo TW_SHOP_URL;?>/view.php?index_no=<?php echo $row[gs_id]; ?>" target="_blank"><?php echo get_it_image($row[gs_id], $gs['simg1'], 40, 40); ?></a></td>
		<td class="tal"><a href="<?php echo TW_SHOP_URL;?>/view.php?index_no=<?php echo $row[gs_id]; ?>" target="_blank"><b class="fs13"><?php echo cut_str($gs['gname'],46); ?></b></a><p class="mart5 fc_137"><?php echo get_text($row[memo]); ?></p></td>
		<td><?php echo get_text($row[writer_s]); ?></td>
		<td><?php echo date("Y/m/d",$row[wdate]); ?></td>
		<td><img src="/img/sub/score_<?php echo $row[score]; ?>.gif"></td>
	</tr>
	<?php 
	}
	if($i==0)
		echo '<tbody><tr><td colspan="7" class="empty_table">자료가 없습니다.</td></tr>';
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
	<?php echo pageing($page, $total_page, $total_count, "{$_SERVER['SCRIPT_NAME']}?$q1&page="); ?>
</div>
<?php } ?>

<script>
function check_all(f)
{
    var chk = document.getElementsByName("chk[]");

    for (i=0; i<chk.length; i++)
        chk[i].checked = f.chkall.checked;
}

function btn_check(act)
{
	var f = document.fitemlist;

    if(act == "delete") // 선택삭제
    {
        f.action = './seller_goods_review_delete.php';
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

<?php
include_once("./admin_tail.sub.php");
?>