<?php
if(!defined('_TUBEWEB_')) exit;

$pg_title = "브랜드관리";
include_once("./admin_head.sub.php");

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_brand ";
$sql_search = " where br_user_yes = '0' ";

if($sfl && $stx) {
    $sql_search .= " and ($sfl like '%$stx%') ";
}

if(!$orderby) {
    $filed = "br_id";
    $sod = "desc";
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
<button type="button" onclick="btn_check('update');" class="btn_lsmall bx-white">선택수정</button>
<button type="button" onclick="btn_check('delete');" class="btn_lsmall bx-white">선택삭제</button>
EOF;
?>

<h2>브랜드 등록</h2>
<form name='fregform' method='post' onsubmit="return fregform_submit(this)" enctype="MULTIPART/FORM-DATA">
<input type='hidden' name="q1" value="<?php echo $q1; ?>">
<input type='hidden' name="page" value="<?php echo $page; ?>">
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col width="100px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th>브랜드명 (KOR)</th>
		<td>
			<input type='text' name='br_name' required itemname="브랜드명 (KOR)" class="frm_input w325">
			<span class="fc_197 marl10">예시) 아르마니 익스체인지</span>
		</td>
	</tr>
	<tr>
		<th>브랜드명 (ENG)</th>
		<td>
			<input type='text' name='br_name_eng' itemname="브랜드명 (ENG)" class="frm_input w325">
			<span class="fc_197 marl10">예시) Armani Exchange</span>
		</td>
	</tr>
	<tr>
		<th>브랜드로고</th>
		<td>
			<input type='file' name='br_logo' id='br_logo'>
			<span class="fc_197 marl10">사이즈(128픽셀 * 40픽셀)</span>
		</td>
	</tr>
	</tbody>
	</table>
</div>
<div class="btn_confirm">
	<input type="submit" value="추가" class="btn_medium red">
</div>
</form>

<h2>기본검색</h2>
<form name="fsearch" id="fsearch" method="get">
<input type="hidden" name='code' value="<?php echo $code; ?>">
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
				<?php echo option_selected('br_name', $sfl, '브랜드명 (KOR)'); ?>
				<?php echo option_selected('br_name_eng', $sfl, '브랜드명 (ENG)'); ?>
				<?php echo option_selected('mb_id', $sfl, '회원ID'); ?>
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

<form name='fbrandlist' method='post'>
<input type='hidden' name='q1' value="<?php echo $q1; ?>">
<input type='hidden' name='page' value="<?php echo $page; ?>">

<div class="local_ov mart30">
	전체 : <b class="fc_red"><?php echo number_format($total_count);?></b> 건 조회
	<span class="fc_red">(본사에서 등록 된 브랜드는 삭제 및 수정이 불가합니다.)</span>
</div>
<div class="local_frm01">
	<?php echo $btn_frmline; ?>
</div>

<div class="tbl_head01">
	<table>
	<colgroup>
		<col width="50px">
		<col width="50px">
		<col>
		<col>
		<col width="130px">
		<col width="80px">
		<col width="110px">
		<col width="60px">
	</colgroup>
	<thead>
	<tr>
		<th><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form)"></th>
		<th>번호</th>
		<th><?php echo subject_sort_link('br_name',$q2); ?>브랜드명 (KOR)</a></th>
		<th><?php echo subject_sort_link('br_name_eng',$q2); ?>브랜드명 (ENG)</a></th>
		<th><?php echo subject_sort_link('mb_id',$q2); ?>회원ID</a></th>
		<th>상품수</th>
		<th>바로가기</th>
		<th>관리</th>
	</tr>
	</thead>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$br_id = $row['br_id'];

		$br = sql_fetch("select count(*) as cnt from shop_goods where brand_uid='{$row['br_id']}'");

		if($row['mb_id'] == $seller['sup_code']) {
			$s_upd = "<a href='./page.php?code=seller_goods_brand_form&w=u&br_id=$br_id$qstr&page=$page' class=\"btn_small\">수정</a>";
			$readonly = "";
			$disabled = "";
			$td_bg = " style='background:yellow;'";
		} else {
			$s_upd = '-';			
			$readonly = " readonly style='background-color:#f4f4f4;'";
			$disabled = " disabled";
			$td_bg = "";
		}

		$bg = 'list'.($i%2);

		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;
	?>
	<tr class="<?php echo $bg; ?>">
		<td<?php echo $td_bg; ?>>
			<input type="checkbox" name="chk[]" value="<?php echo $i; ?>"<?php echo $disabled; ?>>
			<input type="hidden" name="br_id[<?php echo $i; ?>]" value="<?php echo $br_id; ?>">
		</td>
		<td><?php echo $num--; ?></td>
		<td><input type="text" class="frm_input" name="br_name[<?php echo $i; ?>]" value="<?php echo$row['br_name']; ?>"<?php echo $readonly; ?>></td>
		<td><input type="text" class="frm_input" name="br_name_eng[<?php echo $i; ?>]" value="<?php echo$row['br_name_eng']; ?>"<?php echo $readonly; ?>></td>
		<td><?php echo $row['mb_id'] == 'admin' ? "본사":$row['mb_id']; ?></td>
		<td><?php echo number_format($br['cnt']); ?></td>
		<td><a href="/shop/brandlist.php?br_id=<?php echo $br_id; ?>" target="_blank" class="btn_small grey">브랜드 바로가기</a></td>
		<td><?php echo $s_upd; ?></td>
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
function fregform_submit(f){

	if(!confirm("등록 하시겠습니까?"))
		return false;

	f.action = "./seller_goods_brand_update.php";
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
	var f = document.fbrandlist;

    if(act == "update") // 선택수정
    {
        f.action = './seller_goods_brand_list_update.php';
        str = "수정";
    }
    else if(act == "delete") // 선택삭제
    {
        f.action = './seller_goods_brand_list_delete.php';
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