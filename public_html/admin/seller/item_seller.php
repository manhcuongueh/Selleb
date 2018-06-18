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

$sql_common = " from shop_seller a left join shop_member b on a.mb_id=b.id ";
$sql_search = " where (1) ";
if($stx && $sfl) {
    switch($sfl) {
        case "name" :
            $sql_search .= " and (b.$sfl like '%$stx%') ";
            break;
		default :
            $sql_search .= " and (a.$sfl like '%$stx%') ";
            break;
    }
}

if($sst)
	$sql_search .= " and b.grade='$sst' ";

if(in_array($sca, array('0','1')))
	$sql_search .= " and a.state='$sca' ";

if($j_sdate && $j_ddate)
	$sql_search .= " and (a.wdate >= '$j_sdate2' and a.wdate <= '$j_ddate3')";

if($j_sdate && !$j_ddate)
	$sql_search .= " and (a.wdate >= '$j_sdate2' and a.wdate <= '$j_sdate3')";

if(!$j_sdate && $j_ddate)
	$sql_search .= " and (a.wdate >= '$j_ddate2' and a.wdate <= '$j_ddate3')";

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
<a href='./help/sendmail3.php' onclick="openwindow(this,'allemail','750','680','no');return false" class="btn_lsmall bx-white">전체메일발송</a>
<a href='./sms/sms_seller.php' onclick="openwindow(this,'allsms','245','360','no');return false" class="btn_lsmall bx-white">전체문자발송</a>
<a href='./seller/item_seller_excel.php?$q1' class="btn_lsmall bx-white"><i class="fa fa-file-excel-o"></i> 엑셀다운로드</a>
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
<input type="hidden" name='code' value="<?php echo $code;?>">
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
				<option <?php echo get_selected($sfl, 'in_compay'); ?> value='in_compay'>업체명</option>
				<option <?php echo get_selected($sfl, 'sup_code'); ?> value='sup_code'>업체코드</option>
				<option <?php echo get_selected($sfl, 'in_dam'); ?> value='in_dam'>담당자명</option>
				<option <?php echo get_selected($sfl, 'in_name'); ?> value='in_name'>대표자명</option>
				<option <?php echo get_selected($sfl, 'mb_id'); ?> value='mb_id'>아이디</option>
				<option <?php echo get_selected($sfl, 'name'); ?> value='name'>회원명</option>
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
				for($i=0; $row=sql_fetch_array($res); $i++)
					echo option_selected($row[index_no], $sst, $row[grade_name]);
				?>
			</select>
			<select name="sca">
				<option value=''>승인</option>
				<option <?php echo get_selected($sca, '0'); ?> value='0'>대기</option>
				<option <?php echo get_selected($sca, '1'); ?> value='1'>완료</option>
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

<form name="fitemlist" method="post">
<input type="hidden" name="q1" value="<?php echo $q1?>">
<input type="hidden" name="page" value="<?php echo $page?>">

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
		<col width="60px">
		<col width="130px">
		<col width="130px">
		<col width="80px">
		<col>
		<col width="100px">
		<col width="80px">
		<col width="50px">
		<col width="50px">		
		<col width="50px">
		<col width="50px">				
	</colgroup>
	<thead>
	<tr>
	    <th><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form)"></th>
		<th>NO</th>
		<th>로그인</th>
		<th><?php echo subject_sort_link('b.name',$q2)?>회원명</a></th>
		<th><?php echo subject_sort_link('a.mb_id',$q2)?>아이디</a></th>
		<th><?php echo subject_sort_link('a.sup_code',$q2)?>업체코드</a></th>
		<th><?php echo subject_sort_link('a.in_compay',$q2)?>업체명</a></th>
		<th>전화번호</th>
		<th><?php echo subject_sort_link('a.wdate',$q2)?>신청일</a></th>
		<th><?php echo subject_sort_link('a.state',$q2)?>승인</a></th>	
		<th><?php echo subject_sort_link('a.shop_open',$q2)?>상품</a></th>		
		<th>문자</th>
		<th>메일</th>			
	</tr>
	</thead>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$mb = get_member($row[mb_id]);

		$bg = 'list'.($i%2);

		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;
	?>
	<tr class='<?php echo $bg;?>'>
		<td>
			<input type="checkbox" name="chk[]" <?php echo $row[state]?"disabled":"";?> value='<?php echo $i?>'>
			<input type="hidden" name="index_no[<?php echo $i; ?>]" value="<?php echo $row['index_no']; ?>">
		</td>
		<td><?php echo $num--;?></td>
		<td><a href="./admin_ss_login.php?mb_id=<?php echo $row[mb_id];?>&sel_field=item" class="btn_small" target='_blank'>로그인</a></td>
		<td><a href='pop_member_main.php?index_no=<?php echo $mb[index_no];?>' onclick="openwindow(this,'pop_member','1000','600','yes');return false;"><?php echo get_text($mb[name]);?></a></td>
		<td><?php echo $mb[id];?></td>
		<td><a href='pop_member_main.php?code=pitem&index_no=<?php echo $mb[index_no];?>' onclick="openwindow(this,'pop_seller','1000','600','yes');return false;"><?php echo get_text($row[sup_code]);?></a></td>
		<td class="tal"><?php echo get_text($row[in_compay]);?></td>
		<td><?php echo preg_replace("/\s+/","",$row[in_phone]);?></td>
		<td><?php echo date('Y/m/d',$row[wdate]);?></td>
		<td><?php echo $row[state]?'yes':'no';?></td>
		<td><?php echo $ar_isopen[$row[shop_open]];?></td>		
		<td><a href='./sms/sms_user.php?ph=<?php echo $row[n_phone];?>' onclick="openwindow(this,'pop_sms','243','360','no');return false;"><img src='./img/ico_sms_true.gif' border="0"></a></td>
		<td><a href='./help/sendmail2.php?mail=<?php echo $mb[email]?>' onclick="openwindow(this,'pop_email','750','680','no');return false;"><img src='./img/bt_item_email.gif' border="0"></a></td>				
	</tr>
	<?php 
	}
	if($i==0)
		echo '<tbody><tr><td colspan="13" class="empty_table">자료가 없습니다.</td></tr>';
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
	var f = document.fitemlist;

    if(act == "update") // 선택수정
    {
        f.action = './seller/item_seller_update.php';
        str = "승인";
    }
    else if(act == "delete") // 선택삭제
    {
        f.action = './seller/item_seller_delete.php';
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