<?php
if(!defined('_TUBEWEB_')) exit;

if(!$p_use_good) {
	alert('개별 상품판매 권한이 있어야만 이용 가능합니다.');
}

$pg_title = "주문 취소요청";
include_once("./admin_head.sub.php");

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_order_cancel a left join shop_order b on (a.ca_od_uid=b.index_no) ";
$sql_search = " where a.ca_cancel_use='주문취소' and a.ca_it_aff='1' and ca_it_seller ='$member[id]' ";

if($stx && $sfl) {
    $sql_search .= " and ($sfl like '%$stx%') ";
}

if($sst) {
    $sql_search .= " and ( ";
    switch($sst) {
		case "ca_g" :
			$sql_search .= " (a.ca_type = '일반취소') ";
			break;
		case "ca_s" :
			$sql_search .= " (a.ca_type = '부분취소') ";
			break;
		case "ca_n" :
			$sql_search .= " (a.ca_yn = '0') ";
			break;
		case "ca_y" :
			$sql_search .= " (a.ca_yn = '1') ";
			break;
		default :
			$sql_search .= " (b.buymethod = '$sst') ";
			break;
    }
    $sql_search .= " ) ";
}

if($j_sdate && $j_ddate)
	$sql_search .= " and (left(a.ca_wdate,10) >= '$j_sdate' and left(a.ca_wdate,10) <= '$j_ddate')";

if($j_sdate && !$j_ddate)
	$sql_search .= " and (left(a.ca_wdate,10) >= '$j_sdate' and left(a.ca_wdate,10) <= '$j_sdate')";

if(!$j_sdate && $j_ddate)
	$sql_search .= " and (left(a.ca_wdate,10) >= '$j_ddate' and left(a.ca_wdate,10) <= '$j_ddate')";

if(!$orderby) {
    $filed = "a.ca_yn";
    $sod = "asc";
} else {
	$sod = $orderby;
}

$sql_order = " order by $filed $sod, ca_uid desc";

$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 10;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select * $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

include_once(TW_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$btn_frmline = <<<EOF
<button type="button" onclick="btn_check('update')" class="btn_lsmall bx-white">선택수정</button>
<button type="button" onclick="btn_check('delete')" class="btn_lsmall bx-white">선택삭제</button>
<a href="./partner_odr_cancel_excel.php?$q1" class="btn_lsmall bx-white"><i class="fa fa-file-excel-o"></i> 엑셀다운로드</a>
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
				<?php echo option_selected('b.name', $sfl, '주문자명'); ?>
				<?php echo option_selected('b.odrkey', $sfl, '주문번호'); ?>
				<?php echo option_selected('b.orderno', $sfl, '일련번호'); ?>
				<?php echo option_selected('b.incomename', $sfl, '입금자명'); ?>
				<?php echo option_selected('b.b_name', $sfl, '수령자명'); ?>
				<?php echo option_selected('b.b_telephone', $sfl, '수령자집전화'); ?>
				<?php echo option_selected('b.b_cellphone', $sfl, '수령자핸드폰'); ?>
				<?php echo option_selected('b.b_addr1', $sfl, '배송지주소'); ?>
				<?php echo option_selected('b.gonumber', $sfl, '송장번호'); ?>
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
		<th>구분</th>
		<td>
			<select name="sst">
				<option value=''>선택</option>
				<optgroup id="optg1">
					<option value='ca_g'>일반취소</option>
					<option value='ca_s'>부분취소</option>
				</optgroup>
				<optgroup id="optg2">
					<option value='ca_n'>대기</option>
					<option value='ca_y'>완료</option>
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
			</select>

			<script>document.fsearch.sst.value='<?php echo $sst; ?>';</script>
			<script language="JavaScript">
				document.getElementById("optg1").label = "요청구분";
				document.getElementById("optg2").label = "처리상태";
				document.getElementById("optg3").label = "결제방법";
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

<form name="frmlist" method="post">
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
		<col width="150px">
		<col>
		<col width="50px">
	</colgroup>
	<thead>
	<tr>
		<th>선택</th>
		<th>NO</th>
		<th>주문정보</th>
		<th>취소상품 / 상세사유</th>
		<th>처리</th>
	</tr>
	</thead>
	<tbody>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$ca_uid = $row['ca_uid'];
		$gs = get_order_goods($row['orderno']);

		// 장바구니
		$sql = " select gs_id from shop_cart where orderno = '$row[orderno]' group by gs_id ";
		$ct = sql_fetch($sql);

		// 취소 잔액 조회
		$ca_rem_mny = 0;

		$od = sql_fetch(" select count(*) as cnt from shop_order where odrkey='$row[odrkey]' ");

		// 결제금액, 취소금액 합산
		$sql = " select SUM(use_account) as us_amt, SUM(cancel_amt) as ca_amt from shop_order where odrkey = '$row[odrkey]' ";
		$sum = sql_fetch($sql);
		$ca_rem_mny = $sum['us_amt'] - $sum['ca_amt'];

		// 주문취소 및 취소요청건중 배송비 합산
		$sql = " select SUM(del_account) as de_amt from shop_order where odrkey = '$row[odrkey]' and dan in('7','8','9') ";
		$cum = sql_fetch($sql);

		// 주문취소 카운팅
		$sql = " select count(*) as cnt from shop_order where odrkey='$row[odrkey]' and dan in('7','8') ";
		$can = sql_fetch($sql);

		// 동일주문건 - 취소건 및 취소요청건
		$tcnt = $od['cnt'] - $can['cnt'];

		// 동일주문건이 1보다 크고 현재 주문건에 배송비가 있다면 배송비를 차감한다.
		// 배송비를 먼저 차감해버리면 손실이기때문에...
		$ca_mod_mny = $row['use_account'];
		if($tcnt > 1 && $row['del_account'] > 0) {
			$ca_mod_mny = $row['use_account'] - $row['del_account'];
		}

		// 마지막 주문건이고 주문취소건중 배송비가 있고 현주문건에 배송비가 없을때 취소주문건 배송비를 더한다.
		// 마지막 배송비이기때문에 배송비를 더해야 한다.
		else if($tcnt == 1 && $cum['de_amt'] > 0 && $row['del_account'] == 0) {
			$ca_mod_mny = $row['use_account'] + $cum['de_amt'];
		}

		$bg = 'list'.($i%2);
	?>
	<tr class="<?php echo $bg; ?>">
		<td rowspan="5">
			<input type="hidden" name="ca_uid[<?php echo $i; ?>]" value="<?php echo $ca_uid; ?>">
			<input type="hidden" name="ca_mod_mny[<?php echo $i; ?>]" value="<?php echo $ca_mod_mny; ?>">
			<input type="hidden" name="ca_rem_mny[<?php echo $i; ?>]" value="<?php echo $ca_rem_mny; ?>">
			<input type="hidden" name="ca_type[<?php echo $i; ?>]" value="<?php echo $row['ca_type']; ?>">
			<input type="hidden" name="ca_od_dan[<?php echo $i; ?>]" value="<?php echo $row['ca_od_dan']; ?>">
			<input type="hidden" name="ca_od_uid[<?php echo $i; ?>]" value="<?php echo $row['ca_od_uid']; ?>">
			<input onClick="chkvalidate(<?php echo $i; ?>)" type="checkbox" name="chk[]"<?php echo $row['ca_yn']?" disabled":"";?> value="<?php echo $i; ?>">
		</td>
		<td rowspan="5"><?php echo $num--; ?></td>
		<td rowspan="5">
			<p><a href="<?php echo TW_SHOP_URL;?>/view.php?index_no=<?php echo $ct['gs_id']; ?>" target="_blank"><?php echo get_od_image($row['odrkey'], $gs['simg1'], 40, 40); ?></a></p>
			<p class="mart3"><a href='<?php echo TW_ADMIN_URL;?>/pop_order_main.php?index_no=<?php echo $row['ca_od_uid']; ?>' onclick="openwindow(this,'pop_order','953','800','yes');return false" class="fc_197"><?php echo get_text($row['orderno']); ?></a></p>
			<p>( <?php echo $row['odrkey']; ?> )</p>
			<p><?php echo substr($row['ca_wdate'],0,10); ?></p>
			<p class="mart5 bold"><?php echo $row['name']; ?></p>
			<p>(<?php echo $ar_method[$row['buymethod']]; ?>)</p>
			<p class="fc_red mart5"><?php echo $row['ca_type']; ?></p>
			<?php if(!$row['ca_yn']) { ?>
			<p class="mart5"><button type="button" onclick="btn_check('ca_yn')" class="btn_small red">취소승인</button></p>
			<?php } ?>
		</td>
		<td class="tal">
			<p class="marb3 bold fs13"><?php echo $gs['gname']; ?></p>
			<?php
			if($row['ca_logs']) {
				$row['ca_memo'] = "PG LOG (" . $row['ca_logs'] . ")\n=================================================\n" . $row['ca_memo'];
			}
			$sql = " select * from shop_cart where orderno = '$row[ca_key]' ";
			$sql.= " order by io_type asc, index_no asc ";
			$res = sql_query($sql);
			for($k=0; $ct=sql_fetch_array($res); $k++) {
				$it_name = '';
				if($ct['io_type'])
					$it_name = "[추가상품]&nbsp;".$ct['ct_option']." ".$ct['ct_qty']."개".PHP_EOL;
				else
					$it_name = $ct['ct_option']." ".$ct['ct_qty']."개".PHP_EOL;

				echo "<p class=\"fc_125\">$it_name</p>";
			}
			?>
		</td>
		<td rowspan="5"><?php echo $row['ca_yn']?'완료':'대기'; ?></td>
	</tr>
	<tr class="<?php echo $bg; ?> rows">
		<td class="tal">
			<?php echo get_cancel_select("ca_cancel[".$i."]", "id='cancel_{$i}'"); ?>
			<?php if($row['ca_yn']) { ?>
			<span style='color:#214CED;font-weight:bold;'>취소금액 : <?php echo number_format($row['cancel_amt']); ?>원</span>
			<?php } ?>
		</td>
	</tr>
	<tr class="<?php echo $bg; ?> rows">
		<td class="tal">
			<?php
			echo "쿠폰할인 : ".number_format($row['dc_exp_amt'])."&nbsp;/&nbsp;";
			echo "적립금결제 : ".number_format($row['use_point'])."&nbsp;/&nbsp;";
			echo "배송비 : ".number_format($row['del_account'])."&nbsp;/&nbsp;";
			echo "<font color=red>결제금액 : ".number_format($row['use_account'])."</font>&nbsp;/&nbsp;";
			echo "총계 : ".number_format($row['account']+$row['del_account']);
			?>
		</td>
	</tr>
	<tr class="<?php echo $bg; ?> rows">
		<td class="tal">
			<?php echo get_bank_select("ca_bankcd[".$i."]", "id='bankcd_{$i}'");?>
			계좌번호
			<input class="frm_input w140" type="text" name="ca_banknum[<?php echo $i; ?>]" value="<?php echo $row['ca_banknum']; ?>">
			예금주명
			<input class="frm_input w80" type="text" name="ca_bankname[<?php echo $i; ?>]" value="<?php echo $row['ca_bankname']; ?>">
		</td>
	</tr>
	<tr class="<?php echo $bg; ?> rows">
		<td><textarea name="ca_memo[<?php echo $i; ?>]" rows="3" class="frm_textbox"><?php echo $row['ca_memo']; ?></textarea></td>
	</tr>
	<?php
		echo "<script>document.getElementById('cancel_{$i}').value='$row[ca_cancel]';</script>";
		echo "<script>document.getElementById('bankcd_{$i}').value='$row[ca_bankcd]';</script>";
	}
	if($i==0)
		echo '<tr><td colspan="5" class="empty_table">자료가 없습니다.</td></tr>';
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

<div class="information">
	<h4>도움말</h4>
	<div class="content">
		<div class="desc02">
			<?php if($default['cf_card_pg'] == 'kcp') { ?>
			<p>ㆍ주의1) 부분취소 : 신용카드 / 계좌이체만 취소 연동 모듈이 탑재되어 있으며 그 외 결제수단은 환불계좌로 입금 하는 방식으로 처리 하셔야 합니다.</p>
			<p>ㆍ주의2) 일반취소 : 신용카드 / 계좌이체 (에스크로) / 휴대폰결제만 취소 연동 모듈이 탑재되어 있습니다.</p>
			<p>ㆍ참고1) 상품관리에서 <strong>엑셀다운로드</strong> 하시고 수정 후 그대로 업로드 하시면 됩니다!</p>
			<?php } ?>
			<?php if($default['cf_card_pg'] == 'ini') { ?>
			<p>ㆍ주의1) 부분취소 : KG이니시스는 부분취소가 지원은 되지만 이니시스와 별도 계약을 하셔야하며 이후 부분취소 사용이 가능합니다.</p>
			<p>ㆍ주의2) 부분취소 : 별도 계약이 완료 되셨다면 신용카드 외 다른 결제수단은 부분취소 불가능 합니다.</p>
			<p>ㆍ주의3) 일반취소 : 신용카드 / 계좌이체 / 계좌이체 (에스크로) / 휴대폰결제만 취소 연동 모듈이 탑재되어 있습니다.</p>
			<p>ㆍ참고1) 가상계좌취소는 이니시스 관리자에 접속 후 환불계좌 정보를 입력하신 후 취소요청을 하셔야 합니다. (이니시스 고객센터에 문의!)</p>
			<?php } ?>
			<p>ㆍ참고2) 일반취소는 즉시취소가 가능하지만 부분취소는 즉시취소가 되지 않습니다.</p>
			<p>ㆍ참고3) 간혹 PG사를 통해 승인된 값을 받지못하여 취소완료로 자동변경되지 않을수 있습니다.</p>
			<p>ㆍ참고4) 반드시 주문관리페이지의 주문상태와 PG사에서 제공하는 관리자화면내의 취소내역도 동시에 확인해 주십시요.</p>
			<p>ㆍ참고5) 취소처리는 PG사와 리턴되는 통신문제로 일괄처리가 되지 않으며 단건으로 체크 후 처리하시기 바랍니다.</p>
		</div>
	 </div>
</div>

<script>
function btn_check(act)
{
	var f = document.frmlist;

    if(act == "update") // 선택수정
    {
		f.action = './partner_odr_cancel_update.php';
        str = "수정";
    }
	else if(act == "ca_yn") // 선택승인
    {
        f.action = './partner_odr_cancel_yn.php';
        str = "취소";
    }
	else if(act == "delete") // 선택삭제
    {
        f.action = './partner_odr_cancel_delete.php';
        str = "삭제";
    }
    else
        return;

    var chk = document.getElementsByName("chk[]");
    var bchk = false;

    for(i=0; i<chk.length; i++)
    {
        if(chk[i].checked && !chk[i].disabled)
            bchk = true;
    }

    if(!bchk)
    {
        alert(str + "할 자료를 선택하세요.");
        return;
    }

    if(act == "delete" || act == "ca_yn")
    {
        if(!confirm("선택한 자료를 정말 " + str + " 하시겠습니까?"))
            return;
    }

    f.submit();
}

function chkvalidate(cb) {
	var chk = document.getElementsByName("chk[]");
	for(var i=0; i<chk.length; i++) {
		if(chk[i].checked == true) {
			chk[i].checked = false;
			if(i == cb) {
				chk[i].checked = true;
			}
		}
	}
}
</script>

<?php
include_once("./admin_tail.sub.php");
?>