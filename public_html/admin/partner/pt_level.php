<?php
if(!defined('_TUBEWEB_')) exit;

$j_sdate1 = preg_replace('/[^0-9]/', '', $j_sdate);
$j_sdate2 = strtotime($j_sdate1);
$j_sdate3 = $j_sdate2 + 86400;

$j_ddate1 = preg_replace('/[^0-9]/', '', $j_ddate);
$j_ddate2 = strtotime($j_ddate1);
$j_ddate3 = $j_ddate2 + 86400;

$l_sdate1 = preg_replace('/[^0-9]/', '', $l_sdate);
$l_ddate1 = preg_replace('/[^0-9]/', '', $l_ddate);

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_partner a left join shop_member b on a.mb_id=b.id ";
$sql_search = " where (1) ";

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

if(in_array($sca, array('0','1')))
	$sql_search .= " and a.state='$sca' ";

if($j_sdate && $j_ddate)
	$sql_search .= " and (a.wdate >= '$j_sdate2' and a.wdate <= '$j_ddate3') ";

if($j_sdate && !$j_ddate)
	$sql_search .= " and (a.wdate >= '$j_sdate2' and a.wdate <= '$j_sdate3') ";

if(!$j_sdate && $j_ddate)
	$sql_search .= " and (a.wdate >= '$j_ddate2' and a.wdate <= '$j_ddate3') ";

if($l_sdate && $l_ddate)
	$sql_search .= " and (b.anew_date >= '$l_sdate1%' and b.anew_date <= '$l_ddate1%') ";

if($l_sdate && !$l_ddate)
	$sql_search .= " and (b.anew_date like '$l_sdate1%') ";

if(!$l_sdate && $l_ddate)
	$sql_search .= " and (b.anew_date like '$l_ddate1%') ";

if(!$orderby) {
    $filed = "a.wdate";
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
				<?php echo option_selected('pt_id', $sfl, '추천인'); ?>
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
				<?php echo option_selected('',  $sca, '상태'); ?>
				<?php echo option_selected('0', $sca, '대기'); ?>
				<?php echo option_selected('1', $sca, '완료'); ?>
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
		<col width="80px">
		<col width="80px">
		<col width="80px">
		<col>		
	</colgroup>
	<thead>
	<tr>
		<th scope="col"><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form);"></th>
		<th scope="col">NO</th>
		<th scope="col"><?php echo subject_sort_link('a.state',$q2)?>승인</a></th>
		<th scope="col"><?php echo subject_sort_link('b.name',$q2)?>회원명</a></th>
		<th scope="col"><?php echo subject_sort_link('b.pt_id',$q2)?>추천인</a></th>
		<th scope="col"><?php echo subject_sort_link('a.cf_1',$q2)?>신청등급</a></th>
		<th scope="col"><?php echo subject_sort_link('a.bank_money',$q2)?>결제금액</a></th>
		<th scope="col"><?php echo subject_sort_link('a.wdate',$q2)?>신청일</a></th>
		<th scope="col"><?php echo subject_sort_link('b.anew_date',$q2)?>등업일</a></th>
		<th scope="col">본사 입금계좌 및 전달사항</th>		
	</tr>
	</thead>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$mb = get_member($row[mb_id]);
		$mb_recommend = get_member($mb[pt_id]);
		$bm_config = sql_fetch("select * from shop_partner_config where index_no='$row[cf_1]'");

		$bg = 'list'.($i%2);

		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;
	?>
	<tr class="<?php echo $bg;?>">
		<td>
			<input type="checkbox" name="chk[]" <?php echo $row[state]?"disabled":"";?> value='<?php echo $i;?>'>
			<input type="hidden" name="mb_id[<?php echo $i; ?>]" value="<?php echo $row['mb_id']; ?>">
		</td>
		<td><?php echo $num--;?></td>
		<td><?php echo $row[state]?'완료':'<span class="fc_255">대기</span>';?></td>
		<td><a href='pop_member_main.php?index_no=<?php echo $mb[index_no];?>' onclick="openwindow(this,'pop_member1','1000','600','yes');return false;"><b><?php echo get_text($mb[name]);?></b></a><br>(<?php echo $mb[id];?>)</td>
		<td><a href='pop_member_main.php?index_no=<?php echo $mb_recommend[index_no];?>' onclick="openwindow(this,'pop_member2','1000','600','yes');return false;"><b><?php echo get_text($mb_recommend[name]);?></b></a><br><?php echo ($mb_recommend[id])?"(".$mb_recommend[id].")":"";?></td>
		<td><?php echo $bm_config[etc1];?></td>
		<td><?php echo number_format($row[bank_money])?></td>
		<td><?php echo date("Y/m/d",$row[wdate]);?></td>
		<td>
			<?php
			if($mb[anew_date]) {
				echo '<p class="fc_197">' . substr($mb[anew_date],'0','4') . '/' . substr($mb[anew_date],'4','2') . '/' . substr($mb[anew_date],'6','2') . '</p>';
			}
			?>
		</td>
		<td class="tal"><?php echo ($row[bank_type]=='1' && $row[bank_acc]) ? "<p class='fc_197'>".$row[bank_acc]."</p>":""?><?php echo get_text($row[memo]);?></td>		
	</tr>
	<?php 
	}
	if($i==0)
		echo '<tbody><tr><td colspan="10" class="empty_table">자료가 없습니다.</td></tr>';
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
        f.action = '/admin/partner/pt_level_update.php';
        str = "승인";
    }
    else if(act == "delete") // 선택삭제
    {
        f.action = '/admin/partner/pt_level_delete.php';
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