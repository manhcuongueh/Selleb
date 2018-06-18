<?php
if(!defined('_TUBEWEB_')) exit;

$j_sdate1 = preg_replace('/[^0-9]/', '', $j_sdate);
$j_sdate2 = strtotime($j_sdate1);
$j_sdate3 = $j_sdate2 + 86400;

$j_ddate1 = preg_replace('/[^0-9]/', '', $j_ddate);
$j_ddate2 = strtotime($j_ddate1);
$j_ddate3 = $j_ddate2 + 86400;

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_member ";
$sql_search = " where id!='admin' and (grade between 2 and 6) ";
if($stx && $sfl) {
    $sql_search .= " and $sfl like '%$stx%' ";
}

if($sst)
	$sql_search .= " and grade='$sst' ";

if($j_sdate && $j_ddate)
	$sql_search .= " and (term_date >= '$j_sdate2' and term_date <= '$j_ddate3')";

if($j_sdate && !$j_ddate)
	$sql_search .= " and (term_date >= '$j_sdate2' and term_date <= '$j_sdate3')";

if(!$j_sdate && $j_ddate)
	$sql_search .= " and (term_date >= '$j_ddate2' and term_date <= '$j_ddate3')";

if(!$orderby) {
    $filed = "index_no";
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

include_once(TW_PLUGIN_PATH.'/jquery-ui/datepicker.php');
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
			<select name="sst">
				<option value=''>레벨</option>
				<?php
				$sql = "select * from shop_member_grade where index_no!='1' and grade_name!=''";
				$res = sql_query($sql);
				for($i=0; $row=sql_fetch_array($res); $i++)
					echo option_selected($row[index_no], $sst, $row[grade_name]);
				?>
			</select>
			<select name="sfl">
				<?php echo option_selected('id', $sfl, '아이디'); ?>
				<?php echo option_selected('name', $sfl, '회원명'); ?>
			</select>
			<input type="text" name="stx" value="<?php echo $stx; ?>" class="frm_input w325">
		</td>
	</tr>
	<tr>
		<th>만료일</th>
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

<form name="fmemberlist" method="post">
<input type="hidden" name="q1" value="<?php echo $q1; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">

<div class="local_ov mart30">
	전체 : <b class="fc_red"><?php echo number_format($total_count);?></b> 건 조회
</div>
<div class="local_frm01">
	<select name="term_date">
		<option value='0'>기간선택</option>
		<?php
		for($i=1; $i <= 36; $i++)
			echo "<option value='{$i}'>{$i}개월</option>\n";
		?>
	</select>
	<a href="javascript:btn_check('update')" class="btn_small bx-white">기간연장</a>
	<a href="javascript:btn_check('reset')" class="btn_small bx-white">카테고리 초기화</a>
</div>

<div class="tbl_head02">
	<table id="sodr_list">
	<colgroup>
		<col width="50px">
		<col width="50px">
		<col width="60px">
		<col width="130px">
		<col width="130px">
		<col width="130px">
		<col width="80px">
		<col width="">
		<col width="">
		<col width="">
		<col width="">
		<col width="60px">
		<col width="60px">
		<col width="60px">
		<col width="60px">
		<col width="80px">
		<col width="80px">
	</colgroup>
	<thead>
	<tr>
		<th rowspan="2"><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form);"></th>
		<th rowspan="2">NO</th>
		<th rowspan="2">로그인</th>
		<th rowspan="2"><?php echo subject_sort_link('name',$q2); ?>회원명</a></th>
		<th rowspan="2"><?php echo subject_sort_link('id',$q2); ?>아이디</a></th>
		<th rowspan="2"><?php echo subject_sort_link('grade',$q2); ?>레벨</a></th>
		<th rowspan="2"><?php echo subject_sort_link('term_date',$q2); ?>만료일</a></th>
		<th rowspan="2">개별도메인</th>
		<th colspan="3">수수료</th>
		<th colspan="4">쇼핑몰 접속자</th>
		<th rowspan="2">강제적립</th>
		<th rowspan="2">카테고리</th>		
	</tr>
	<tr class="rows">
		<th>현재잔액</th>
		<th>전체누적</th>
		<th>전체지급</th>
		<th style="background-color:#f7f8e0;"><?php echo subject_sort_link('vi_today',$q2); ?>오늘</a></th>
		<th style="background-color:#f7f8e0;"><?php echo subject_sort_link('vi_yesterday',$q2); ?>어제</a></th>
		<th style="background-color:#f7f8e0;"><?php echo subject_sort_link('vi_max',$q2); ?>최대</a></th>
		<th style="background-color:#f7f8e0;"><?php echo subject_sort_link('vi_sum',$q2); ?>전체</a></th>
	</tr>
	</thead>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$sql1 = "select SUM(total) as total,
					    SUM(income) as income,
						SUM(outcome)as outcome
				   from shop_partner_pay
				  where mb_id = '$row[id]'";
		$sum = sql_fetch($sql1);

		if($config[p_month]=='y') {
			$h_y = date("Y",$row[term_date]);
			$h_m = date("m",$row[term_date]);
			$h_d = date("d",$row[term_date]);

			$new_hold = mktime(0,0,1,$h_m,$h_d,$h_y);

			$ed = $new_hold - time();
			if($ed > 0) {  $extra_date = round($ed/(60*60*24)); $default_check = 1;}
			else { $exceed_date = round(($ed/(60*60*24))*(-1)); $default_check = 2; }

			if($default_check==2)
				$month = "미납[".$exceed_date."일]";
			else
				$month = date('Y/m/d',$row[term_date]);
		} else {
			$month = "-";
		}

		$homepage = '';
		if($row['homepage']) {
			$homepage = set_http($row['homepage']);
			$homepage = '<a href="'.$homepage.'" target="_blank">'.$homepage.'</a>';
		}

		echo "<input type=\"hidden\" name=mb_table[$i] value='$row[index_no]'>";

		$bg = 'list'.($i%2);

		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;
	?>
	<tr class="<?php echo $bg; ?>">
		<td><input type="checkbox" name="chk[]" value='<?php echo $i?>'></td>
		<td><?php echo $num--; ?></td>
		<td><a href="./admin_ss_login.php?mb_id=<?php echo $row[id]; ?>&sel_field=drapt" target='_blank' class="btn_small">로그인</a></td>
		<td><a href='pop_member_main.php?index_no=<?php echo $row[index_no]; ?>' onclick="openwindow(this,'pop_member','1000','600','yes');return false;"><?php echo get_text($row[name]); ?></a></td>
		<td><?php echo $row[id]; ?></td>
		<td><?php echo get_grade($row[grade]); ?></td>
		<td><?php echo $month; ?></td>
		<td class="tal"><?php echo $homepage; ?></td>
		<td class="tar bold"><?php echo number_format($sum[total]); ?></td>
		<td class="tar"><?php echo number_format($sum[income]); ?></td>
		<td class="tar"><?php echo number_format($sum[outcome]); ?></td>
		<td class="tar"><?php echo number_format($row['vi_today']); ?></td>
		<td class="tar"><?php echo number_format($row['vi_yesterday']); ?></td>
		<td class="tar"><?php echo number_format($row['vi_max']); ?></td>
		<td class="tar"><?php echo number_format($row['vi_sum']); ?></td>
		<td><a href='partner/pt_req.php?index_no=<?php echo $row[index_no]; ?>' onclick="openwindow(this,'pop_point_req','600','500','no');return false;" class="btn_small bx-white">강제적립</a></td>
		<td><a href='partner/pt_category.php?mb_id=<?php echo $row[id]; ?>' onclick="openwindow(this,'pop_point_category','900','687','yes');return false;" class="btn_small bx-white">카테고리</a></td>		
	</tr>
	<?php 
	}
	if($i==0)
		echo '<tbody><tr><td colspan="17" class="empty_table">자료가 없습니다.</td></tr>';
	?>
	</tbody>
	</table>
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
	var f = document.fmemberlist;

    if(act == "update") // 선택연장
    {
        f.action = './partner/pt_member_update.php';
        str = "연장";
    }
	else if(act == "reset") // 카테고리 초기화
    {
        f.action = './partner/pt_member_cgy_update.php';
        str = "카테고리 초기화";
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

	if(act == "update")
    {
        if(f.term_date.value == 0) {
			alert(str+'할 기간을 선택하세요.');
			f.term_date.focus();
			return;
		}
    }

	if(!confirm("선택한 자료를 "+str+" 하시겠습니까?"))
		return;

    f.submit();
}
</script>