<?php
if(!defined('_TUBEWEB_')) exit;

$pg_title = "상품 문의관리";
include_once("./admin_head.sub.php");

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_goods_qa ";
$sql_search = " where gs_se_id = '$seller[sup_code]' ";

if($sfl && $stx) {
    $sql_search .= " and ($sfl like '%$stx%') ";
}

if($sst) {
    $sql_search .= " and (iq_ty='$sst') ";
}

if(!$orderby) {
    $filed = "iq_reply";
    $sod = "asc, iq_id desc";
} else {
	$sod = $orderby;
}

$sql_order = " order by $filed $sod";

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
			<select name="sst">
				<option value=''>구분</option>
				<option <?php echo get_selected($sst, '상품'); ?> value='상품'>상품</option>
				<option <?php echo get_selected($sst, '배송'); ?> value='배송'>배송</option>
				<option <?php echo get_selected($sst, '반품/환불/취소'); ?> value='반품/환불/취소'>반품/환불/취소</option>
				<option <?php echo get_selected($sst, '교환/변경'); ?> value='교환/변경'>교환/변경</option>
				<option <?php echo get_selected($sst, '기타'); ?> value='기타'>기타</option>
			</select>
			<select name="sfl">
				<option <?php echo get_selected($sfl, 'iq_name'); ?> value='iq_name'>작성자명</option>
				<option <?php echo get_selected($sfl, 'iq_email'); ?> value='iq_email'>작성자 이메일</option>
				<option <?php echo get_selected($sfl, 'iq_hp'); ?> value='iq_hp'>작성자 핸드폰</option>
				<option <?php echo get_selected($sfl, 'iq_subject'); ?> value='iq_subject'>제목</option>
				<option <?php echo get_selected($sfl, 'iq_question'); ?> value='iq_question'>질문내용</option>
				<option <?php echo get_selected($sfl, 'iq_answer'); ?> value='iq_answer'>답변내용</option>
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

<form name="fqalist" method="post">
<input type="hidden" name="q1" value="<?php echo $q1; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">

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
		<col width="130px">
		<col>
		<col width="130px">
		<col width="80px">
		<col width="60px">
		<col width="60px">
	</colgroup>
	<thead>
	<tr>
		<th><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form)"></th>
		<th>No</th>
		<th><?php echo subject_sort_link('iq_ty',$q2); ?>구분</a></th>
		<th><?php echo subject_sort_link('iq_subject',$q2); ?>제목</a></th>
		<th><?php echo subject_sort_link('iq_name',$q2); ?>작성자</a></th>
		<th><?php echo subject_sort_link('iq_time',$q2); ?>작성일</a></th>
		<th><?php echo subject_sort_link('iq_reply',$q2); ?>답변</a></th>
		<th>관리</th>
	</tr>
	</thead>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$iq_id = $row['iq_id'];

		$iq_url = "code=seller_goods_qa_form&w=u&iq_id=$iq_id$qstr&page=$page";
		$iq_upd = "<a href='page.php?$iq_url' class=\"btn_small\">수정</a>";
		$iq_subject = "<a href='page.php?$iq_url'>".cut_str($row['iq_subject'],50)."</a>";

		$bg = 'list'.($i%2);

		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;
	?>
	<tr class="<?php echo $bg; ?>">
		<td>
			<input type="checkbox" name="chk[]" value="<?php echo $i; ?>">
			<input type="hidden" name="iq_id[<?php echo $i; ?>]" value="<?php echo $iq_id; ?>">
		</td>
		<td><?php echo $num--; ?></td>
		<td><?php echo $row['iq_ty']; ?></td>
		<td class="tal"><?php echo $iq_subject; ?></td>
		<td><?php echo $row['iq_name']; ?></a></td>
		<td><?php echo substr($row['iq_time'],0,10); ?></td>
		<td><?php echo $row['iq_answer']?'yes':''; ?></td>
		<td><?php echo $iq_upd; ?></td>
	</tr>
	<?php 
	}
	if($i==0)
		echo '<tbody><tr><td colspan="8" class="empty_table">자료가 없습니다.</td></tr>';
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

    for(i=0; i<chk.length; i++)
        chk[i].checked = f.chkall.checked;
}

function btn_check(act)
{
	var f = document.fqalist;

    if(act == "delete") // 선택삭제
    {
        f.action = './seller_goods_qa_list_delete.php';
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