<?php
if(!defined('_TUBEWEB_')) exit;

if(isset($sh_year))  {
    $qstr .= '&sh_year='.$sh_year;
}
if(isset($sh_month))  {
    $qstr .= '&sh_month='.$sh_month;
}
if(isset($sh_week))  {
    $qstr .= '&sh_week='.$sh_week;
}

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_partner_pay a left join shop_member b on (a.mb_id = b.id) ";
$sql_search = " where a.mb_id != 'admin'
				  and b.grade between 2 and 6
				  and a.total > 0 ";
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

if(in_array($sca, array('0','2','3')))
	$sql_search .= " and a.ragi='$sca' ";

// 년/월 기간
if($sh_year && $sh_month) {
	$month_date = $sh_year . "-" . $sh_month;
	$sql_search .= " and (a.month_date = '$month_date') ";
}

// 주별 기간
if($sh_week) {
	$month_date = $sh_year . "-" . $sh_month;
	$sql_search .= " and (a.ju_date = '$sh_week') ";
}

if(!$orderby) {
    $filed = "a.reg_date";
    $sod = "desc";
} else {
	$sod = $orderby;
}

$sql_order = "order by $filed $sod";

$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row[cnt];

$rows = 30;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select a.* $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

if(!$sh_year) $sh_year = $time_year;
if(!$sh_month) $sh_month = $time_month;

$btn_frmline = <<<EOF
<button type="button" onclick="btn_check('update')" class="btn_lsmall white">정산완료</button>
<button type="button" onclick="btn_check('defer')" class="btn_lsmall bx-white">정산유보</button>
<button type="button" onclick="btn_check('refusal')" class="btn_lsmall bx-white">정산거절</button>
<a href="./partner/pt_pay_excel.php?$q1" class="btn_lsmall bx-white"><i class="fa fa-file-excel-o"></i> 엑셀다운로드</a>
EOF;
?>

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
				<option <?php echo get_selected($sfl, 'mb_id'); ?> value='mb_id'>아이디</option>
				<option <?php echo get_selected($sfl, 'name'); ?> value='name'>회원명</option>
			</select>
			<input type="text" name="stx" value="<?php echo $stx; ?>" class="frm_input w200">
		</td>
	</tr>
	<tr>
		<th>기간</th>
		<td>
			<select name="sh_year" id="sh_year" onchange="makeweekopt(0);">
				<?php
				for($i=($time_year-3);$i<($time_year+1);$i++) {
					echo "<option ".get_selected($i, $sh_year)." value='$i'>${i}년</option>";
				}
				?>
			</select>
			<select name="sh_month" id="sh_month" onchange="makeweekopt(0);">
				<?php
				for($i=1;$i<=12;$i++) {
					$k = sprintf('%02d',$i);
					echo "<option ".get_selected($k, $sh_month)." value='$k'>${k}월</option>";
				}
				?>
			</select>
			<select name="sh_week" id="sh_week" <?php echo ($config['p_type'] != 'ju')?"disabled":""; ?>>
			<option value=''>주별검색</option>
			</select>
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
					echo "<option ".get_selected($sst, $row['index_no'])." value='$row[index_no]'>$row[grade_name]</option>";
				}
				?>
			</select>
			<select name="sca">
				<option <?php echo get_selected($sca, ''); ?> value='' >정산</option>
				<option <?php echo get_selected($sca, '0'); ?> value='0'>정산대기</option>
				<option <?php echo get_selected($sca, '2'); ?> value='2'>정산유보</option>
				<option <?php echo get_selected($sca, '3'); ?> value='3'>정산거절</option>
			</select>
		</td>
	</tr>
	</tbody>
	</table>
</div>
<div class="btn_confirm">
	<input type="submit" value="검색" class="btn_medium">
</div>
</form>

<form name="fpartnerlist" method="post">
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
		<col width="50px">
		<col width="130px">
		<col width="130px">
		<col width="130px">
		<col width="80px">
		<col width="80px">
		<col width="80px">
		<col width="">
		<col width="80px">		
	</colgroup>
	<thead>
	<tr>
		<th><input type=checkbox name=chkall value="1" onclick="check_all(this.form)"></th>
		<th>NO</th>
		<th>정산</th>
		<th><?php echo subject_sort_link('b.name',$q2); ?>회원명</a></th>
		<th><?php echo subject_sort_link('a.mb_id',$q2); ?>아이디</a></th>
		<th><?php echo subject_sort_link('b.grade',$q2); ?>레벨</a></th>
		<th>수수료</th>
		<th>세금공제</th>
		<th>실수령액</th>
		<th>입금계좌 은행</th>
		<th>기간</th>		
	</tr>
	</thead>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$level = sql_fetch("select * from shop_partner where mb_id='$row[mb_id]'");
		$bankinfo = $level[bank_name]." ".$level[bank_number]." ".$level[bank_company];
		$mb = get_member($row[mb_id]);

		if($config[p_month]=='y'){
			$h_y = date("Y",$mb[term_date]);
			$h_m = date("m",$mb[term_date]);
			$h_d = date("d",$mb[term_date]);
			$new_hold = mktime(0,0,1,$h_m,$h_d,$h_y);
			$ed = $new_hold - time();

			if($ed > 0) { $extra_date = round($ed/(60*60*24));  $default_check = 1; }
			else { $exceed_date = round(($ed/(60*60*24))*(-1)); $default_check = 2; }
		}

		$tax2 = round(($row[total] * $config[accent_tax]) / 100); // 세금공제
		$tax3 = $row[total] - $tax2; // 실수령액

		unset($p_type);
		if($config[p_type]=='month') {
			$p_type = str_replace("-","/",$row[month_date]);
		}
		else if($config[p_type]=='ju') {
			$week = explode("~", $row[ju_date]);
			$p_type = substr($week[0],2)."<br>".substr($week[1],2);
		}

		switch($row[ragi]){
			case '0' : $ragis = "대기"; break;
			case '1' : $ragis = "완료"; break;
			case '2' : $ragis = "<font class='fc_197'>유보</font>"; break;
			case '3' : $ragis = "<font class='fc_red'>거절</font>"; break;
		}

		$bg = 'list'.($i%2);

		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;
	?>
	<tr class='<?php echo $bg; ?>'>
		<td>
			<input type="checkbox" name="chk[]" value='<?php echo $i; ?>'>
			<input type="hidden" name="p_table[<?php echo $i; ?>]" value="<?php echo $row['index_no']; ?>">
		</td>
		<td><?php echo $num--; ?></td>
		<?php
		if($config[p_month]=='y') {
			if($default_check==1 )
				echo "<td>$ragis</td>";
			else
				echo "<td class='bold fc_red'>미납</td>";
		} else {
			echo "<td>$ragis</td>";
		}
		?>
		<td><a href='pop_member_main.php?index_no=<?php echo $mb['index_no']; ?>' onclick="openwindow(this,'pop_member','1000','600','yes');return false"><?php echo get_text($mb['name']); ?></a></td>
		<td><?php echo $mb['id']; ?></td>
		<td><?php echo get_grade($mb['grade']); ?></td>
		<td class="tar"><?php echo number_format($row['total']); ?></td>
		<td class="tar"><?php echo number_format($tax2); ?></td>
		<td class="tar bold"><?php echo number_format($tax3); ?></td>
		<td class="tal"><?php echo $bankinfo; ?></td>
		<td><?php echo $p_type; ?></td>
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

    if(act == "update") // 정산완료
    {
        var pt_pay_update = './partner/pt_pay_update.php?mode=update';
        str = "정산완료";
    }
	else if(act == "defer") // 정산유보
    {
        var pt_pay_update = './partner/pt_pay_update.php?mode=defer';
        str = "정산유보";
    }
	else if(act == "refusal") // 정산거절
    {
        var pt_pay_update = './partner/pt_pay_update.php?mode=refusal';
        str = "정산거절";
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
    } else {
        if(!confirm("선택한 자료를 " + str + " 하시겠습니까?"))
            return;

		f.action = pt_pay_update;
		f.submit();
	}
}
</script>

<script>
var sh_week = '<?php echo $sh_week; ?>';
function makeweekopt(n) {
	var year = $("#sh_year").val();
	var month = $("#sh_month").val();

	var today = new Date();

	var sdate = new Date(year, month-1, 01);
	var lastDay = (new Date(sdate.getFullYear(), sdate.getMonth()+1, 0)).getDate();
	var endDate = new Date(sdate.getFullYear(), sdate.getMonth(), lastDay);

	var week = sdate.getDay();
	sdate.setDate(sdate.getDate() - week);
	var edate = new Date(sdate.getFullYear(), sdate.getMonth(), sdate.getDate());

	var obj = document.getElementById("sh_week");
	obj.options.length = 1;
	var seled = "";
	while(endDate.getTime() >= edate.getTime()) {

		var sYear = sdate.getFullYear();
		var sMonth = (sdate.getMonth()+1);
		var sDay = sdate.getDate();

		sMonth = (sMonth < 10) ? "0"+sMonth : sMonth;
		sDay = (sDay < 10) ? "0"+sDay : sDay;

		var stxt = sYear + "-" + sMonth + "-" + sDay;

		edate.setDate(sdate.getDate() + 6);

		var eYear = edate.getFullYear();
		var eMonth = (edate.getMonth()+1);
		var eDay = edate.getDate();

		eMonth = (eMonth < 10) ? "0"+eMonth : eMonth;
		eDay = (eDay < 10) ? "0"+eDay : eDay;

		var etxt = eYear + "-" + eMonth + "-" + eDay;

		if(today.getTime() >= sdate.getTime() && today.getTime() <= edate.getTime()) {
			seled = stxt+"~"+etxt;
		}

		obj.options[obj.options.length] = new Option(stxt+"~"+etxt, stxt+"~"+etxt);

		sdate = new Date(edate.getFullYear(), edate.getMonth(), edate.getDate() + 1);
		edate = new Date(sdate.getFullYear(), sdate.getMonth(), sdate.getDate());
	}

	if(n == 1 && sh_week){
		obj.value = sh_week;
	}
	/*
	else if(seled){
		obj.value = seled;
	}
	*/
}

$(document).ready(function () {
	makeweekopt(1);
});
</script>
