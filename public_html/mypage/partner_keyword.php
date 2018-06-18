<?php
if(!defined('_TUBEWEB_')) exit;

$pg_title = "검색키워드 관리";
include_once("./admin_head.sub.php");

$query_string = "code=$code";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

if($w == "reg") {
	check_demo();

	$keyword  = trim(strip_tags($_POST['keyword']));
	$time_ymd = date('W');

	if(!$keyword) alert('검색키워드가 값이 넘어오지 않았습니다.');

	if(substr_count($keyword, "&#") > 50) {
		alert("내용에 올바르지 않은 코드가 다수 포함되어 있습니다.");
	}

	unset($value);
	$value['keyword'] = $_POST['keyword'];
	$value['scount'] = 1;
	$value['pp_date'] = $time_ymd;
	$value['pt_id']  = $member['id'];
	insert("shop_keyword", $value);

	goto_url("page.php?code=partner_keyword&page=$page");
}

$sql_common = " from shop_keyword ";
$sql_search = " where pt_id='$member[id]' ";
$sql_order  = " order by scount desc, old_scount desc";

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
EOF;
?>

<form name="fregform" method="post" onsubmit="return fregform_submit(this);">
<input type="hidden" name="w" value="reg">
<input type="hidden" name="page" value="<?php echo $page; ?>">

<h2>검색어 등록</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col width="100px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th>키워드입력</th>
		<td><input type="text" name="keyword" class="frm_input" required itemname="키워드입력" size="40"></td>
	</tr>
	</tbody>
	</table>
</div>
<div class="btn_confirm">
	<input type="submit" value="추가" class="btn_medium red">
</div>
</form>

<form name="fwordlist" method="post">
<input type='hidden' name='q1' value="<?php echo $q1; ?>">
<input type='hidden' name='page' value="<?php echo $page; ?>">

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
		<col width="100px">
		<col width="100px">
		<col>
	</colgroup>
	<thead>
	<tr>
		<th><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form);"></th>
		<th>NO</th>
		<th>이번주검색수</th>
		<th>지난검색수</th>
		<th>검색어</th>
	</tr>
	</thead>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$bg = 'list'.$i%2;

		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;
	?>
	<tr class="<?php echo $bg; ?>">
		<td>
			<input type="checkbox" name="chk[]" value="<?php echo $i; ?>">
			<input type="hidden" name="index_no[<?php echo $i; ?>]" value="<?php echo $row[index_no]; ?>">
		</td>
		<td><?php echo $num--; ?></td>
		<td><input type="text" class="frm_input" name="scount[<?php echo $i; ?>]" value="<?php echo $row[scount]; ?>"></td>
		<td><input type="text" class="frm_input" name="old_scount[<?php echo $i; ?>]" value="<?php echo $row[old_scount]; ?>"></td>
		<td><input type="text" class="frm_input" name="keyword[<?php echo $i; ?>]" value="<?php echo $row[keyword]; ?>"></td>
	</tr>
	<?php 
	}
	if($i==0)
		echo '<tbody><tr><td colspan="5" class="empty_table">자료가 없습니다.</td></tr>';
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
function fregform_submit(f) {
	if(!confirm("등록 하시겠습니까?"))
		return false;

	f.action = "page.php?code=partner_keyword";
    return true;
}

function check_all(f)
{
    var chk = document.getElementsByName("chk[]");

    for(i=0; i<chk.length; i++)
        chk[i].checked = f.chkall.checked;
}

function btn_check(act)
{
	var f = document.fwordlist;

    if(act == "update") // 선택수정
    {
        f.action = './partner_keyword_update.php';
        str = "수정";
    }
    else if(act == "delete") // 선택삭제
    {
        f.action = './partner_keyword_delete.php';
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