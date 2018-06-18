<?php
if(!defined('_TUBEWEB_')) exit;

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_order ";
$sql_search = " where (left(gs_se_id,3) != 'AP-' and gs_se_id != 'admin') and dan != '0' ";

if($sfl && $stx) {
    $sql_search .= " and ($sfl like '%$stx%') ";
}

if($sst) {
    $sql_search .= " and ( ";
    switch($sst) {
		case "Y" :
			$sql_search .= " (mb_yes = '1') ";
			break;
		case "N" :
			$sql_search .= " (mb_yes = '0') ";
			break;
		case "monitor" :
			$sql_search .= " (path = '0') ";
			break;
		case "mobile" :
			$sql_search .= " (path = '1') ";
			break;
		default :
			if(in_array($sst, array('C','B','R','H','S','ER','ES')))
				$sql_search .= " (buymethod = '$sst') ";
			else
				$sql_search .= " (dan = '$sst') ";
			break;
    }
    $sql_search .= " ) ";
}

if($j_sdate && $j_ddate) {
	$sql_search .= " and (orderdate_s >= '$j_sdate' and orderdate_s <= '$j_ddate')";
}
if($j_sdate && !$j_ddate) {
	$sql_search .= " and (orderdate_s >= '$j_sdate' and orderdate_s <= '$j_sdate')";
}
if(!$j_sdate && $j_ddate) {
	$sql_search .= " and (orderdate_s >= '$j_ddate' and orderdate_s <= '$j_ddate')";
}

if(!$orderby) {
    $filed = "orderdate";
    $sod = "desc";
} else {
	$sod = $orderby;
}

$sql_order = " order by $filed $sod, index_no asc";

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

// 총금액 뽑기
$sql = " select SUM(use_account) as use_a,
			    SUM(use_point) as use_p,
			    SUM(del_account) as del_a ,
			    SUM(dc_exp_amt) as dc_amt
			$sql_common
			$sql_search ";
$total = sql_fetch($sql);

include_once(TW_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$btn_frmline = <<<EOF
<button type="button" onclick="btn_check('delete')" class="btn_lsmall bx-white">선택삭제</button>
<a href="./order/order_aff_excel.php?$q1" class="btn_lsmall bx-white"><i class="fa fa-file-excel-o"></i> 엑셀다운로드</a>
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
				<?php echo option_selected('name', $sfl, '주문자명'); ?>
				<?php echo option_selected('odrkey', $sfl, '주문번호'); ?>
				<?php echo option_selected('orderno', $sfl, '일련번호'); ?>
				<?php echo option_selected('incomename', $sfl, '입금자명'); ?>
				<?php echo option_selected('b_name', $sfl, '수령자명'); ?>
				<?php echo option_selected('b_telephone', $sfl, '수령자집전화'); ?>
				<?php echo option_selected('b_cellphone', $sfl, '수령자핸드폰'); ?>
				<?php echo option_selected('b_addr1', $sfl, '배송지주소'); ?>
				<?php echo option_selected('gonumber', $sfl, '송장번호'); ?>
				<?php echo option_selected('gs_se_id', $sfl, '판매자ID'); ?>
			</select>
			<input type="text" name="stx" value="<?php echo $stx;?>" class="frm_input w325">
		</td>
	</tr>
	<tr>
		<th>주문일</th>
		<td>
			<?php echo get_search_date("j_sdate", "j_ddate", $j_sdate, $j_ddate); ?>
		</td>
	</tr>
	<tr>
		<th>구분</th>
		<td>
			<select name="sst">
				<option value=''>선택</option>
				<optgroup id="optg1">
					<option value='Y'>회원</option>
					<option value='N'>비회원</option>
				</optgroup>
				<optgroup id="optg2">
					<option value='monitor'>일반결제</option>
					<option value='mobile'>모바일샵결제</option>
				</optgroup>
				<optgroup id="optg3">
					<?php
					if($default['cf_card_yn'])
						echo "<option value='C'>".$ar_method['C']."</option>\n";
					if($default['cf_bank_yn'])
						echo "<option value='B'>".$ar_method['B']."</option>\n";
					if($default['cf_iche_yn'])
						echo "<option value='R'>".$ar_method['R']."</option>\n";
					if($default['cf_hp_yn'])
						echo "<option value='H'>".$ar_method['H']."</option>\n";
					if($default['cf_vbank_yn'])
						echo "<option value='S'>".$ar_method['S']."</option>\n";
					if($default['cf_iche_yn'])
						echo "<option value='ER'>".$ar_method['ER']."</option>\n";
					if($default['cf_vbank_yn'])
						echo "<option value='ES'>".$ar_method['ES']."</option>\n";
					?>
				</optgroup>
				<optgroup id="optg4">
					<?php
					for($i=1; $i<=10; $i++) {
						if($i != 9)
							echo "<option value='{$i}'>".$ar_dan[$i]."</option>\n";
					}
					?>
				</optgroup>
			</select>
			<script>document.fsearch.sst.value='<?php echo $sst;?>';</script>
			<script language="JavaScript">
				document.getElementById("optg1").label = "회원구분";
				document.getElementById("optg2").label = "모바일샵";
				document.getElementById("optg3").label = "결제방법";
				document.getElementById("optg4").label = "주문상태";
			</script>
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

<ul class="or_totalbox mart20">
	<li>결제금액 합계 <p class="mart5 fc_red"><b><?php echo number_format($total[use_a]);?></b> 원</p></li>
	<li>쿠폰할인 <p class="mart5 fc_7d6"><b><?php echo number_format($total[dc_amt]);?></b> 원</p></li>
	<li>적립금결제 <p class="mart5 fc_7d6"><b><?php echo number_format($total[use_p]);?></b> 원</p></li>
	<li>배송비결제 <p class="mart5 fc_7d6"><b><?php echo number_format($total[del_a]);?></b> 원</p></li>
</ul>

<form name="frmlist" method="post">
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
		<col width="60px">
		<col width="100px">
		<col>
		<col width="80px">
		<col width="90px">
		<col width="50px">
		<col width="80px">
		<col width="90px">
		<col width="90px">
		<col width="90px">
	</colgroup>
	<thead>
	<tr>
		<th><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form)"></th>
		<th>NO</th>
		<th>이미지</th>
		<th><?php echo subject_sort_link('orderno',$q2);?>일련번호</a></th>
		<th><?php echo subject_sort_link('odrkey',$q2);?>주문번호</a></th>
		<th><?php echo subject_sort_link('orderdate_s',$q2);?>주문날짜</a></th>
		<th><?php echo subject_sort_link('b_name',$q2);?>수령자명</a></th>
		<th><?php echo subject_sort_link('mb_yes',$q2);?>회원</a></th>
		<th><?php echo subject_sort_link('use_account',$q2);?>결제금액</a></th>
		<th><?php echo subject_sort_link('buymethod',$q2);?>결제방법</a></th>
		<th><?php echo subject_sort_link('gs_se_id',$q2);?>판매자ID</a></th>
		<th><?php echo subject_sort_link('dan',$q2);?>현황</a></th>
	</tr>
	</thead>
	<tbody>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$od_table = $row[index_no];

		$cart = get_shop_cart($row[orderno]);
		$gs = get_order_goods($row[orderno]);
		$mb = get_member($row[gs_se_id]);

		if($row[mb_yes]) {
			$s_upd = "<a href='pop_member_main.php?index_no=$row[mb_no]' onclick=\"openwindow(this,'pop_mb','1000','600','yes');return false\">".get_text($row[name])."</a>";
		} else {
			$s_upd = get_text($row[name]);
		}

		$max = get_order_max($sql_search, $row[odrkey]);
		$sum = get_order_sum($sql_search, $row[odrkey]);

		// 배송추적 값이 없을때
		$baesong = "<div class='mart5'>";
		$delivery = explode('|', $row['delivery']);
		if(!$delivery[1]) {
			$baesong .= "<a href=\"javascript:alert('집하 준비중이거나 배송정보를 입력하지 못하였습니다.')\" class=\"btn_small bx-white\">";
		} else {
			$baesong .= "<a href='".$delivery[1].$row['gonumber']."' onclick=\"openwindow(this,'pop_delivery','600','650','yes');return false\" class=\"btn_small bx-white\">";
		}
		$baesong .= "배송추적</a></div>";
	?>
	<tr>
		<td rowspan="2">
			<input type="checkbox" name="chk[]" value="<?php echo $i;?>">
			<input type="hidden" name="od_table[<?php echo $i; ?>]" value="<?php echo $od_table; ?>">
		</td>
		<td rowspan="2"><?php echo $num--;?></td>
		<td rowspan="2"><a href="<?php echo TW_SHOP_URL;?>/view.php?index_no=<?php echo $cart[gs_id];?>" target="_blank"><?php echo get_od_image($row['odrkey'], $gs['simg1'], 40, 40); ?></a></td>
		<td><a href='<?php echo TW_ADMIN_URL;?>/pop_order_main.php?index_no=<?php echo $od_table;?>' onclick="openwindow(this,'pop_order','953','800','yes');return false" class="fc_197"><?php echo get_text($row[orderno]);?></a></td>
		<td><?php echo get_text($row[odrkey]);?></td>
		<td><?php echo $row[orderdate_s];?></td>
		<td rowspan="2"><?php echo $row[b_name].$baesong;?></td>
		<td rowspan="2"><?php echo $row[mb_yes]?'yes':'no';?></td>
		<td rowspan="2" class="tar bold"><?php echo number_format($row[use_account]);?></td>
		<td rowspan="2"><?php echo $ar_method[$row[buymethod]];?></td>
		<td rowspan="2"><a href='pop_member_main.php?index_no=<?php echo $mb[index_no];?>' onclick="openwindow(this,'pop_member','1000','600','yes');return false"><b><?php echo get_text($mb[name]);?></b></a><br><?php echo $mb[id]?></td>
		<td rowspan="2"><?php echo $ar_dan[$row[dan]];?></td>
	</tr>
	<tr class="rows">
		<td colspan="3"><?php echo $delivery[0].'&nbsp;&nbsp;'.$row[gonumber]; ?></td>
	</tr>
	<?php
		if($max[max_idx] == $od_table) {
			echo "<tr class='list1'>";
			echo "<td colspan='2'><a href=\"javascript:window.open('".TW_ADMIN_URL."/order/order_print2.php?odrkey={$row[odrkey]}', '', 'scrollbars=yes,width=670,height=600,top=10,left=20');\" class=\"btn_small bx-blue\"><i class=\"fa fa-print\"></i> 인쇄하기</a></b></td>";
			echo "<td colspan='5'>&#183;쿠폰할인 : <b class='marr20'>".number_format($sum[dc_amt])."</b>";
			echo "적립금결제 : <b class='marr20'>".number_format($sum[po_amt])."</b>";
			echo "배송비 : <b class='marr20'>".number_format($sum[del_amt])."</b>";
			echo "주문자명 : <b>".$s_upd."</b></td>";
			echo "<td>".buypath($row[path])."</td>";
			echo "<td class='tar fc_255 bold'>".number_format($sum[use_amt])."</td>";
			echo "<td class='tar fc_red bold' colspan='3'>총계 : ".number_format($sum[amt]+$sum[del_amt])."</td>";
			echo "</tr>";
		}
	}
	if($i==0)
		echo '<tr><td colspan="12" class="empty_table">자료가 없습니다.</td></tr>';
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
	var f = document.frmlist;

	if(act == "delete") // 선택삭제
    {
        f.action = './order/order_delete.php';
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
