<?php
if(!defined('_TUBEWEB_')) exit;

$j_sdate1 = preg_replace('/[^0-9]/', '', $j_sdate);
$j_sdate2 = strtotime($j_sdate1);
$j_sdate3 = $j_sdate2 + 86400;

$j_ddate1 = preg_replace('/[^0-9]/', '', $j_ddate);
$j_ddate2 = strtotime($j_ddate1);
$j_ddate3 = $j_ddate2 + 86400;

$l_sdate1 = preg_replace('/[^0-9]/', '', $l_sdate);
$l_sdate2 = strtotime($l_sdate1);
$l_sdate3 = $l_sdate2 + 86400;

$l_ddate1 = preg_replace('/[^0-9]/', '', $l_ddate);
$l_ddate2 = strtotime($l_ddate1);
$l_ddate3 = $l_ddate2 + 86400;

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_partner_term a left join shop_member b on a.mb_id=b.id ";
$sql_search = " where (1)";

if($stx && $sfl) {
    switch($sfl) {
        case "mb_id" :
            $sql_search .= " and (a.$sfl like '%$stx%') ";
            break;
		default :
            $sql_search .= " and (b.$sfl like '%$stx%') ";
            break;
    }
}

if($sst)
	$sql_search .= " and b.grade='$sst' ";

if($sca)
	$sql_search .= " and a.bank='$sca' ";

if($j_sdate && $j_ddate)
	$sql_search .= " and (a.wdate >= '$j_sdate2' and a.wdate <= '$j_ddate3')";

if($j_sdate && !$j_ddate)
	$sql_search .= " and (a.wdate >= '$j_sdate2' and a.wdate <= '$j_sdate3')";

if(!$j_sdate && $j_ddate)
	$sql_search .= " and (a.wdate >= '$j_ddate2' and a.wdate <= '$j_ddate3')";

if($l_sdate && $l_ddate)
	$sql_search .= " and (b.term_date >= '$l_sdate2' and b.term_date <= '$l_ddate3')";

if($l_sdate && !$l_ddate)
	$sql_search .= " and (b.term_date >= '$l_sdate2' and b.term_date <= '$l_sdate3')";

if(!$l_sdate && $l_ddate)
	$sql_search .= " and (b.term_date >= '$l_ddate2' and b.term_date <= '$l_ddate3')";

if(!$orderby) {
    $filed = "a.wdate";
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

$sql = " select a.* $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

include_once(TW_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$btn_frmline = <<<EOF
<button type="button" onclick="btn_check('update')" class="btn_lsmall bx-white">선택승인</button>
<button type="button" onclick="btn_check('delete')" class="btn_lsmall bx-white">선택삭제</button>
EOF;
?>

<script>
$(function(){
	// 날짜 검색 : TODAY MAX값으로 인식 (maxDate: "+0d")를 삭제하면 MAX값 해제
	$("#j_sdate,#j_ddate,#l_sdate,#l_ddate").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
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
				<?php echo option_selected('mb_id', $sfl, '아이디'); ?>
				<?php echo option_selected('name', $sfl, '회원명'); ?>
			</select>
			<input type="text" name="stx" value="<?php echo $stx;?>" class="frm_input w325">
		</td>
	</tr>
	<tr>
		<th>신청일</th>
		<td>
			<?php echo get_search_date("j_sdate", "j_ddate", $j_sdate, $j_ddate); ?>
		</td>
	</tr>
	<tr>
		<th>등업일</th>
		<td>
			<?php echo get_search_date("l_sdate", "l_ddate", $l_sdate, $l_ddate); ?>
		</td>
	</tr>
	<tr>
		<th>그룹별</th>
		<td>
			<select name="sst">
				<option value=''>레벨</option>
				<?php
				$sql = "select * from shop_member_grade where index_no!='1' and grade_name!=''";
				$res = sql_query($sql);
				for($i=0; $row=sql_fetch_array($res); $i++){
					echo option_selected($row[index_no], $sst, $row[grade_name]);
				}
				?>
			</select>
			<select name="sca">
				<?php echo option_selected('',  $sca, '결제'); ?>
				<?php echo option_selected('1', $sca, '무통장'); ?>
				<?php echo option_selected('2', $sca, '신용카드'); ?>
			</select>
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

<form name="fpartnerlist" method="post">
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
		<col width="130px">
		<col width="130px">
		<col width="80px">
		<col width="80px">
		<col>
		<col width="80px">
		<col width="80px">
		<col width="80px">		
	</colgroup>
	<thead>
	<tr>
		<th scope="col"><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form)"></th>
		<th scope="col">NO</th>
		<th scope="col"><?php echo subject_sort_link('a.state',$q2)?>완료</a></th>
		<th scope="col"><?php echo subject_sort_link('b.name',$q2)?>회원명</a></th>
		<th scope="col"><?php echo subject_sort_link('b.grade',$q2)?>레벨</a></th>
		<th scope="col">
			<p><?php echo subject_sort_link('a.wdate',$q2)?>신청일</a></p>
			<p class="mart5"><?php echo subject_sort_link('b.term_date',$q2)?>만료일</a></p>
		</th>
		<th scope="col"><?php echo subject_sort_link('a.go_date',$q2)?>기간</a></th>
		<th scope="col">입금계좌</th>
		<th scope="col"><?php echo subject_sort_link('a.bank',$q2)?>결제방식</a></th>
		<th scope="col"><?php echo subject_sort_link('a.bank_acc',$q2)?>입금자명</a></th>
		<th scope="col"><?php echo subject_sort_link('a.money',$q2)?>결제금액</a></th>		
	</tr>
	</thead>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$mb = get_member($row[mb_id]);

		if($config[p_month]=='y') {
			$h_y = date("Y",$mb[term_date]);
			$h_m = date("m",$mb[term_date]);
			$h_d = date("d",$mb[term_date]);

			$new_hold = mktime(0,0,1,$h_m,$h_d,$h_y);

			$ed = $new_hold - time();
			if($ed > 0) { $extra_date = round($ed/(60*60*24)); $default_check = 1; }
			else { $exceed_date = round(($ed/(60*60*24))*(-1)); $default_check = 2; }

			if($default_check==2)
				$month = "미납[".$exceed_date."일]";
			else
				$month = date('Y/m/d',$mb[term_date]);
		} else {
			$month = "-";
		}

		$bg = 'list'.($i%2);

		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;
	?>
	<tr class="<?php echo $bg;?>">
		<td>
			<input type="checkbox" name="chk[]" <?php echo $row[state]?"disabled":"";?> value="<?php echo $i;?>">
			<input type="hidden" name="si_table[<?php echo $i; ?>]" value="<?php echo $row['index_no']; ?>">
		</td>
		<td><?php echo $num--;?></td>
		<td><?php echo $row[state]?'yes':'no';?></td>
		<td><a href='pop_member_main.php?index_no=<?php echo $mb[index_no];?>' onclick="openwindow(this,'pop_member','1000','600','yes');return false;"><b><?php echo get_text($mb[name]);?></b></a><br>(<?php echo $mb[id];?>)</td>
		<td><?php echo get_grade($mb[grade]);?></td>
		<td><?php echo date('Y/m/d',$row[wdate])?><br><span class="fc_197"><?php echo $month;?></span></td>
		<td><?php echo $row[go_date]; ?>개월</td>
		<td><?php if($row[bank]=='1') { echo get_text($row[bank_name]); } ?></td>
		<td><?php if($row[bank]=='1') { echo '무통장'; } else { echo "카드"; }?></td>
		<td><?php if($row[bank]=='1') { echo $row[bank_acc]; } ?></td>
		<td class="tar"><?php echo number_format($row[money])?></td>		
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
	var f = document.fpartnerlist;

    if(act == "update") // 선택수정
    {
        f.action = '/admin/partner/pt_sin_update.php';
        str = "승인";
    }
    else if(act == "delete") // 선택삭제
    {
        f.action = '/admin/partner/pt_sin_delete.php';
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
