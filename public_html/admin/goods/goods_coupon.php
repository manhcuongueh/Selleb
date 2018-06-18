<?php
if(!defined('_TUBEWEB_')) exit;

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_coupon ";
$sql_search = " where (1) ";

if($sfl && $stx) {
    $sql_search .= " and ($sfl like '%$stx%') ";
}

if($j_sdate!='' && $j_ddate!='')
{	$sql_search .= " and (cp_pub_sdate >= '$j_sdate' and cp_pub_edate <= '$j_ddate')"; }

if($j_sdate!='' && $j_ddate=='')
{	$sql_search .= " and (cp_pub_sdate >= '$j_sdate' and cp_pub_sdate <= '$j_sdate')"; }

if($j_sdate=='' && $j_ddate!='')
{	$sql_search .= " and (cp_pub_edate >= '$j_ddate' and cp_pub_edate <= '$j_ddate')"; }

if(!$orderby) {
    $filed = "cp_wdate";
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

include_once(TW_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$btn_frmline = <<<EOF
<button type="button" onclick="btn_check('delete');" class="btn_lsmall bx-white">선택삭제</button>
<a href="goods.php?code=coupon_form" class="fr btn_lsmall red"><i class="ionicons ion-android-add"></i> 쿠폰등록</a>
EOF;
?>

<script>
$(function(){
	// 날짜 검색 : TODAY MAX값으로 인식 (maxDate: "+0d")를 삭제하면 MAX값 해제
	$("#j_sdate,#j_ddate").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
});
</script>

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
				<?php echo option_selected('cp_subject', $sfl, '쿠폰명'); ?>
				<?php echo option_selected('cp_explan', $sfl, '설명'); ?>
			</select>
			<input type="text" name="stx" value="<?php echo $stx; ?>" class="frm_input w325">
		</td>
	</tr>
	<tr>
		<th>사용기간</th>
		<td>
			<?php echo get_search_date("j_sdate", "j_ddate", $j_sdate, $j_ddate); ?>
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

<form name="fcouponlist" method="post">
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
		<col width="50px">
		<col>
		<col width="80px">
		<col width="80px">
		<col width="80px">
		<col width="80px">
		<col width="50px">
		<col width="60px">
	</colgroup>
	<thead>
	<tr>
		<th><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form)"></th>
		<th>NO</th>
		<th><?php echo subject_sort_link('cp_type',$q2); ?>쿠폰유형</a> (쿠폰명)</th>
		<th>사용시작</th>
		<th>사용종료</th>
		<th>다운로드</th>
		<th>주문건수</th>
		<th><?php echo subject_sort_link('cp_use',$q2); ?>사용</a></th>
		<th>관리</th>
	</tr>
	</thead>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$cp_id = $row[cp_id];

		$s_upd = "<a href='./goods.php?code=coupon_form&w=u&cp_id=$cp_id$qstr&page=$page' class=\"btn_small\">수정</a>";

		switch($row[cp_type]){
			case '3':
				$sdate = $row[cp_pub_sday].'일';
				$edate = $row[cp_pub_eday].'일';
				break;
			default :
				if($row[cp_pub_sdate] == '9999999999')
					$sdate = '무제한';
				else
					$sdate = str_replace("-",".",$row[cp_pub_sdate]);

				if($row[cp_pub_edate] == '9999999999')
					$edate = '무제한';
				else
					$edate = str_replace("-",".",$row[cp_pub_edate]);
				break;
		}
		// 발행매수
		$log = sql_fetch("select count(cp_id) as cnt from shop_coupon_log where cp_id='$cp_id'");

		$bg = 'list'.($i%2);

		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;
	?>
	<tr class='<?php echo $bg; ?>'>
		<td>
			<input type="checkbox" name="chk[]" value="<?php echo $i; ?>">
			<input type="hidden" name="cp_id[<?php echo $i; ?>]" value="<?php echo $cp_id; ?>">
		</td>
		<td><?php echo $num--; ?></td>
		<td class="tal"><p class="fc_255"><?php echo $ar_coupon[$row[cp_type]]; ?></p><p class="mart2"><?php echo get_text(cut_str($row[cp_subject],40)); ?></p></td>
		<td><?php echo $sdate?></td>
		<td><?php echo $edate?></td>
		<td><?php echo number_format($log[cnt]); ?></td>
		<td><?php echo number_format($row[cp_odr_cnt]); ?></td>
		<td><?php echo $row[cp_use]?'yes':''; ?></td>
		<td><?php echo $s_upd; ?></td>
	</tr>
	<?php 
	}
	if($i==0)
		echo '<tbody><tr><td colspan="9" class="empty_table">자료가 없습니다.</td></tr>';
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
	var f = document.fcouponlist;

    if(act == "delete") // 선택삭제
    {
        f.action = './goods/goods_coupon_list_delete.php';
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
