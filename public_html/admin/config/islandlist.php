<?php
if(!defined('_TUBEWEB_')) exit;

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_island ";
$sql_search = " where (1) ";

if($sfl && $stx) {
    $sql_search .= " and ($sfl like '%$stx%') ";
}

if(!$orderby) {
    $filed = "is_id";
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
<button type="button" onclick="btn_check('update')" class="btn_lsmall bx-white">선택수정</button>
<button type="button" onclick="btn_check('delete')" class="btn_lsmall bx-white">선택삭제</button>
EOF;
?>

<form name="fsearch" id="fsearch" method="get">
<input type="hidden" name="code" value="<?php echo $code; ?>">
<h2>기본검색</h2>
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
				<option<?php echo get_selected($sfl, "is_name"); ?> value="is_name">할증 지역명</option>
				<option<?php echo get_selected($sfl, "is_zip1"); ?> value="is_zip1">우편번호 시작</option>
				<option<?php echo get_selected($sfl, "is_zip2"); ?> value="is_zip2">우편번호 끝</option>
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

<form name="fisland" method="post">
<input type="hidden" name="q1" value="<?php echo $q1; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">

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
		<col>
		<col width="100px">
		<col width="100px">
		<col width="100px">
	</colgroup>
	<thead>
	<tr>
		<th><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form)"></th>
		<th><?php echo subject_sort_link('is_name',$q2); ?>할증 지역명</a></th>
		<th><?php echo subject_sort_link('is_zip1',$q2); ?>우편번호 시작</a></th>
		<th><?php echo subject_sort_link('is_zip2',$q2); ?>우편번호 끝</a></th>
		<th><?php echo subject_sort_link('is_price',$q2); ?>추가금액</a></th>
	</tr>
	</thead>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$bg = 'list'.($i%2);

		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;
	?>
	<tr class="<?php echo $bg; ?>">
		<td>
			<input type="checkbox" name="chk[]" value="<?php echo $i; ?>">
			<input type="hidden" name="is_id[<?php echo $i; ?>]" value="<?php echo $row['is_id']; ?>">
		</td>
		<td><input type="text" class="frm_input" name="is_name[<?php echo $i; ?>]" value='<?php echo$row[is_name]; ?>'></td>
		<td><input type="text" class="frm_input" name="is_zip1[<?php echo $i; ?>]" value='<?php echo$row[is_zip1]; ?>'></td>
		<td><input type="text" class="frm_input" name="is_zip2[<?php echo $i; ?>]" value='<?php echo$row[is_zip2]; ?>'></td>
		<td><input type="text" class="frm_input" name="is_price[<?php echo $i; ?>]" value='<?php echo number_format($row['is_price']); ?>' onkeyup="addComma(this)"></td>
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
	<?php echo pageing($page, $total_page, $total_count, "{$_SERVER['SCRIPT_NAME']}?$q1&page=");?>
</div>
<?php } ?>

<form name="fisland2" action="./config/islandlistupdate.php" method="post" autocomplete="off">
<input type="hidden" name="q1" value="<?php echo $q1; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">
<input type="hidden" name="act_button" value="추가">
<h2>추가배송지역 등록</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col width="140px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th>지역명</th>
		<td>
			<input type="text" name="is_name" id="is_name" required itemname="지역명" class="frm_input w325">
		</td>
	</tr>
	<tr>
		<th>우편번호 범위</th>
		<td>
			<label for="is_zip1" class="sound_only">우편번호 시작</label>
			<input type="text" name="is_zip1" id="is_zip1" required itemname="우편번호 시작" class="frm_input w80" maxlength="5"> 부터
			<label for="is_zip2" class="sound_only">우편번호 끝</label>
			<input type="text" name="is_zip2" id="is_zip2" required itemname="우편번호 끝" class="frm_input w80 marl10" maxlength="5"> 까지
			<p class="fc_197 mart5">
				구 우편번호는 입력하시면 안됩니다.<br>우편번호는 00000 형식(5자)이며 숫자로만 입력하셔야 합니다.<br>예) <span class="fc_red">53321</span> 부터 <span class="fc_red">53325</span> 까지
			</p>
		</td>
	</tr>
	<tr>
		<th>추가배송비</th>
		<td>
			<input type="text" name="is_price" id="is_price" onkeyup="addComma(this)" required itemname="추가배송비" class="frm_input w100"> 원
		</td>
	</tr>
	</tbody>
	</table>
</div>
<div class="btn_confirm">
	<input type="submit" class="btn_medium red" value="추가">
</div>
</form>

<div class="information">
	<h4>도움말</h4>
	<div class="content">
		<div class="desc02">
			<p>ㆍ우편번호 범위로 할증지역을 관리합니다.</p>
			<p>ㆍ배송비가 추가되는 설정입니다. 우편번호를 정확하게 입력해 주시기 바랍니다.</p>
			<p class="fc_red">ㆍ우편번호 범위가 크면 다른 지역이 포함되는 경우가 간혹 발생되므로, 범위를 작게 설정하시기를 권장합니다.</p>
		</div>
	 </div>
</div>

<script>
function check_all(f)
{
    var chk = document.getElementsByName("chk[]");

    for(i=0; i<chk.length; i++)
        chk[i].checked = f.chkall.checked;
}

function btn_check(act)
{
	var f = document.fisland;

    if(act == "update") // 선택수정
    {
        f.action = './config/islandlistupdate.php';
        str = "수정";
    }
    else if(act == "delete") // 선택삭제
    {
        f.action = './config/islandlistdelete.php';
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