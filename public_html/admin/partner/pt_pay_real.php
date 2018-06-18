<?php
if(!defined('_TUBEWEB_')) exit;

$j_sdate1 = preg_replace('/[^0-9]/', '',$j_sdate);
$j_sdate2 = strtotime($j_sdate1);
$j_sdate3 = $j_sdate2 + 86400;

$j_ddate1 = preg_replace('/[^0-9]/', '',$j_ddate);
$j_ddate2 = strtotime($j_ddate1);
$j_ddate3 = $j_ddate2 + 86400;

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_partner_payrun a left join shop_member b on a.mb_id=b.id ";
$sql_search = " where a.mb_id!='admin' and (b.grade between 2 and 6) and a.state!=1 ";

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
{	$sql_search .= " and b.grade='$sst' "; }

if(in_array($sca, array('0','2','3')))
{	$sql_search .= " and a.state='$sca' "; }

if($j_sdate && $j_ddate)
{	$sql_search .= " and (a.wdate >= '$j_sdate2' and a.wdate <= '$j_ddate3') "; }

if($j_sdate && !$j_ddate)
{	$sql_search .= " and (a.wdate >= '$j_sdate2' and a.wdate <= '$j_sdate3') "; }

if(!$j_sdate && $j_ddate)
{	$sql_search .= " and (a.wdate >= '$j_ddate2' and a.wdate <= '$j_ddate3') "; }

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
<button type="button" onclick="btn_check('update')" class="btn_lsmall bx-white">정산완료</button>
<button type="button" onclick="btn_check('defer')" class="btn_lsmall bx-white">정산유보</button>
<button type="button" onclick="btn_check('refusal')" class="btn_lsmall bx-white">정산거절</button>
<button type="button" onclick="btn_check('delete')" class="btn_lsmall bx-white">선택삭제</button>
<a href="./partner/pt_pay_real_excel.php?$q1" class="btn_lsmall bx-white"><i class="fa fa-file-excel-o"></i> 엑셀다운로드</a>
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
<input type="hidden" name="code" value="<?php echo $code;?>">
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
				<?php echo option_selected('',  $sca, '정산'); ?>
				<?php echo option_selected('0', $sca, '정산대기'); ?>
				<?php echo option_selected('2', $sca, '정산유보'); ?>
				<?php echo option_selected('3', $sca, '정산거절'); ?>
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
		<col width="130px">
		<col width="100px">
		<col width="100px">
		<col width="100px">
		<col width="100px">
		<col>
	</colgroup>
	<thead>
	<tr>
		<th><input type=checkbox name=chkall value="1" onclick="check_all(this.form)"></th>
		<th>NO</th>
		<th><?php echo subject_sort_link('a.state',$q2)?>정산</a></th>
		<th><?php echo subject_sort_link('b.name',$q2)?>회원명</a></th>
		<th><?php echo subject_sort_link('a.mb_id',$q2)?>아이디</a></th>
		<th><?php echo subject_sort_link('b.grade',$q2)?>레벨</a></th>
		<th>현재잔액</th>
		<th><?php echo subject_sort_link('a.money',$q2)?>출금요청</a></th>
		<th><?php echo subject_sort_link('a.tax1_money',$q2)?>세금공제</a></th>
		<th><?php echo subject_sort_link('a.tax2_money',$q2)?>실수령액</a></th>
		<th><?php echo subject_sort_link('a.wdate',$q2)?>신청일</a> / 입금계좌 은행</th>		
	</tr>
	</thead>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$b_point = sql_fetch("select sum(total) as total from shop_partner_pay where mb_id='$row[mb_id]' group by mb_id");
		$mb = get_member($row[mb_id]);

		if($config[p_month]=='y'){
			$h_y = date("Y",$mb[term_date]);
			$h_m = date("m",$mb[term_date]);
			$h_d = date("d",$mb[term_date]);
			$new_hold = mktime(0,0,1,$h_m,$h_d,$h_y);
			$ed = $new_hold - time();

			if($ed > 0) {  $extra_date = round($ed/(60*60*24)); $default_check = 1;}
			else { $exceed_date = round(($ed/(60*60*24))*(-1)); $default_check = 2; }
		}

		switch($row[state]){
			case '0' : $ragis = "대기"; break;
			case '1' : $ragis = "완료"; break;
			case '2' : $ragis = "<font color='blue'>유보</font>"; break;
			case '3' : $ragis = "<font color='red'>거절</font>"; break;
		}

		$bg = 'list'.($i%2);

		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;
	?>
	<tr class='<?php echo $bg;?>'>
		<td<?php echo (!$row[state])?" bgcolor='f2f981'":"";?>>
			<input type="checkbox" name="chk[]" value="<?php echo $i; ?>">
			<input type="hidden" name="p_table[<?php echo $i; ?>]" value="<?php echo $row['index_no']; ?>">
		</td>
		<td><?php echo $num--;?></td>
		<?php
		if($config[p_month]=='y') {
			if($default_check==1 ) {
				echo "<td>$ragis</td>";
			} else {
				echo "<td class='bold fc_red'>미납</td>";
			}
		} else {
			echo "<td>$ragis</td>";
		}
		?>
		<td><a href='pop_member_main.php?index_no=<?php echo $mb[index_no];?>' onclick="openwindow(this,'pop_member','1000','600','yes');return false"><?php echo get_text($mb[name]);?></a></td>
		<td><?php echo $mb[id];?></td>
		<td><?php echo get_grade($mb[grade]);?></td>
		<td class="tar"><?php echo number_format($b_point[total]);?></td>
		<td class="tar"><?php echo number_format($row[money]);?></td>
		<td class="tar"><?php echo number_format($row[tax1_money]);?></td>
		<td class="tar bold"><?php echo number_format($row[tax2_money]);?></td>
		<td><font color='blue'>신청일 : <?php echo date("Y/m/d H:i:s",$row[wdate]);?></font><br><?php echo $row[membank];?></td>
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
        var pt_pay_update = '/admin/partner/pt_pay_real_update.php?mode=update';
        str = "정산완료";
    }
	else if(act == "defer") // 정산유보
    {
        var pt_pay_update = '/admin/partner/pt_pay_real_update.php?mode=defer';
        str = "정산유보";
    }
	else if(act == "refusal") // 정산거절
    {
        var pt_pay_update = '/admin/partner/pt_pay_real_update.php?mode=refusal';
        str = "정산거절";
    }
    else if(act == "delete") // 선택삭제
    {
        var pt_pay_update = '/admin/partner/pt_pay_real_delete.php';
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
    } else {
		if(act == "delete")
		{
			if(!confirm("선택한 자료를 정말 삭제 하시겠습니까?"))
				return;
		} else {

			if(!confirm("선택한 자료를 " + str + " 하시겠습니까?"))
			 return;
		}

		f.action = pt_pay_update;
		f.submit();
	}
}
</script>
