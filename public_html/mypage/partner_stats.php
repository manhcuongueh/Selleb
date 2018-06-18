<?php
if(!defined('_TUBEWEB_')) exit;

$pg_title = "수익금 정산";
include_once("./admin_head.sub.php");

if(!$year) $year = $time_year;
if(!$month) $month = $time_month;

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

// 총계
$sql = " select SUM(total) as balance,
			    SUM(income) as plus,
			    SUM(outcome) as minus
		   from shop_partner_pay
		  where mb_id = '$member[id]' 
		  group by mb_id ";
$sum = sql_fetch($sql);

//실시간 수수료정산
if($config['p_type'] == 'time') {
	$sql = "select sum(money) as payrun from shop_partner_payrun where mb_id='$member[id]' and state='0'";
	$res = sql_fetch($sql);
	$balance = (int)$sum['balance']- (int)$res['payrun'];
?>

<h2>수수료 정산현황</h2>
<div class="tbl_head01">
	<table>
	<colgroup>
		<col width="50px">
		<col width="140px">
		<col width="100px">
		<col width="100px">
		<col width="100px">
		<col>
		<col width="60px">
	</colgroup>
	<thead>
	<tr>
		<th scope="col">번호</th>
		<th scope="col">신청일시</th>
		<th scope="col">출금요청</th>
		<th scope="col">세금공제</th>
		<th scope="col">실수령액</th>
		<th scope="col">정산처리 입금계좌</th>
		<th scope="col">현황</th>
	</tr>
	</thead>
	<?php
	$sql_common = " from shop_partner_payrun ";
	$sql_search = " where mb_id = '$member[id]' ";
	$sql_order  = " order by index_no desc";

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
	for($i=0; $row=sql_fetch_array($result); $i++) {
		switch($row['state']){
			case '0' : $t_state = "대기"; break;
			case '1' : $t_state = "완료"; break;
			case '2' : $t_state = "유보"; break;
			case '3' : $t_state = "거절"; break;
		}

		$bg = 'list'.($i%2);

		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;
	?>
	<tr class="<?php echo $bg; ?>">
		<td><?php echo $num--; ?></td>
		<td><?php echo date('Y-m-d H:i:s', $row['wdate']); ?></td>
		<td class="tar"><?php echo number_format($row['money']); ?></td>
		<td class="tar"><?php echo number_format($row['tax1_money']); ?></td>
		<td class="tar"><?php echo number_format($row['tax2_money']); ?></td>
		<td><?php echo $row['membank']; ?></td>
		<td><?php echo $t_state; ?></td>
	</tr>
	<?php 
	}
	if($i==0)
		echo '<tbody><tr><td colspan="7" class="empty_list">자료가 없습니다.</td></tr>';
	?>
	</tbody>
	</table>
</div>

<?php if($total_count > 0) { ?>
<div class="btn_confirm">
	<?php echo pageing($page, $total_page, $total_count, "{$_SERVER['SCRIPT_NAME']}?$q1&page="); ?>
</div>
<?php } ?>

<h2>수수료 출금요청</h2>
<form name="fregform" method="post" onsubmit="return fregform_submit(this);">
<input type="hidden" name="balance" value="<?php echo $balance; ?>">
<input type="hidden" name="max_price" value="<?php echo $config['accent_max']; ?>">
<input type="hidden" name="token" value="">
<div class="tbl_frm01">
	<table class="tablef">
	<colgroup>
		<col width="120px">
		<col>
	</colgroup>
	<tr>
		<th scope="row">출금가능 금액</th>
		<td><b><?php echo number_format($balance); ?></b>원<?php if($config['accent_max']) { ?><span class="fc_red marl10">(최소 <strong><?php echo number_format($config['usepoint']); ?></strong>원 부터 출금가능)</span><?php } ?></td>
	</tr>
	<tr>
		<th scope="row">결제요청 금액</th>
		<td><input type="text" name="reg_price" required numeric itemname="결제요청 금액" class="frm_input" size="10"> 원</td>
	</tr>
	<tr>
		<th scope="row">정산계좌 정보</th>
		<td>
			<div class="mart0">
				<label for="bank_company">입금은행</label>
				<?php echo get_bank_select("bank_company","required itemname='은행명'"); ?>
				<script>document.fregform.bank_company.value = "<?php echo $partner[bank_company]; ?>";</script>
			</div>
			<div class="mart5">
				<label for="bank_number">계좌번호</label>
				<input type="text" name="bank_number" id="bank_number" required itemname="계좌번호" value="<?php echo $partner['bank_number']; ?>" class="frm_input w200">
			</div>
			<div class="mart5">
				<label for="bank_name">예금주명</label>
				<input type="text" name="bank_name" id="bank_name" required itemname="예금주명" value="<?php echo $partner['bank_name']; ?>" class="frm_input w150">
			</div>			
		</td>
	</tr>
	</table>
</div>
<div class="btn_confirm">
	<input type="submit" value="출금요청" class="btn_medium red">
</div>
</form>

<script>
function fregform_submit(f) {
	var balance = parseInt(f.balance.value, 10); // 출금가능 금액
	var reg_price = parseInt(f.reg_price.value, 10); // 결제요청 금액
	var max_price = parseInt(f.max_price.value, 10); // 출금가능 최소금액

	if(balance < max_price) {
		alert(max_price+'원 이상부터 신청 가능합니다.');
		return false;
	}

	if(balance < reg_price) {
		alert('신청가능 금액을 초과 하였습니다.');
		f.reg_price.value = balance;
		return false;
	}

	if(confirm("신청 하시겠습니까?") == false)
		return false;

	f.action = "./partner_stats_update.php";
    return true;
}
</script>
<?php } ?>

<?php if($config['p_type'] != 'time') { // 월별,주별일 경우 ?>
<h2>수수료 정산현황</h2>
<div class="tbl_head01">
	<table>
	<colgroup>
		<col width="50px">
		<col width="140px">
		<col width="100px">
		<col width="100px">
		<col width="100px">
		<col>
		<col width="100px">
		<col>		
	</colgroup>
	<thead>
	<tr>
		<th scope="col">번호</th>
		<th scope="col">정산일시</th>
		<th scope="col">확정수수료</th>
		<th scope="col">세금공제</th>
		<th scope="col">실수령액</th>
		<th scope="col">정산처리 입금계좌</th>
		<th scope="col">정산상태</th>
	</tr>
	</thead>
	<?php
	$sql_common = " from shop_partner_payuse ";
	$sql_search = " where mb_id = '$member[id]' ";
	$sql_order  = " order by index_no desc";

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
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$bg = 'list'.($i%2);

		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;
	?>
	<tr class="<?php echo $bg; ?>">
		<td><?php echo $num--; ?></td>
		<td><?php echo date('Y-m-d H:i:s', $row['wdate']); ?></td>
		<td class="tar"><?php echo number_format($row['out_money']); ?></td>
		<td class="tar"><?php echo number_format($row['tax2_money']); ?></td>
		<td class="tar"><?php echo number_format($row['tax3_money']); ?></td>
		<td><?php echo get_text($row['bankinfo']); ?></td>
		<td><?php echo get_text($row['memo']); ?></td>
	</tr>
	<?php 
	}
	if($i==0)
		echo '<tbody><tr><td colspan="7" class="empty_list">자료가 없습니다.</td></tr>';
	?>
	</tbody>
	</table>
</div>

<?php if($total_count > 0) { ?>
<div class="btn_confirm">
	<?php echo pageing($page, $total_page, $total_count, "{$_SERVER['SCRIPT_NAME']}?$q1&page="); ?>
</div>
<?php } ?>
<?php } ?>

<h2>수수료 누적 리포트</h2>
<div class="tbl_frm01">
	<table class="tablef">
	<colgroup>
		<col width="120px">
		<col>
		<col width="120px">
		<col>
		<col width="120px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">전체 누적수수료</th>
		<td><b><?php echo number_format($sum['plus']); ?></b>원</td>
		<th scope="row">전체 지급수수료</th>
		<td><b><?php echo number_format($sum['minus']); ?></b>원</td>
		<th scope="row">현재 남은잔액</th>
		<td><b><?php echo number_format($sum['balance']); ?></b>원</td>
	</tr>
	</tbody>
	</table>
</div>

<h2>수수료 적립 리포트</h2>
<form name="fsearch" id="fsearch" method="get">
<input type="hidden" name='code' value="<?php echo $code; ?>">
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col width="120px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">적립일</th>
		<td>
			<select name="year">
			<?php
			for($i=($time_year-3);$i<($time_year+1);$i++) {
				echo "<option ".get_selected($year, $i)." value='$i'>${i}년</option>";
			}
			?>
			</select>
			<select name="month">
			<?php
			for($i=1;$i<=12;$i++) {
				$k = sprintf('%02d',$i);
				echo "<option ".get_selected($month, $i)." value='$k'>${k}월</option>";
			}
			?>
			</select>
			<input type="submit" value="검색" class="btn_small">
		</td>
	</tr>
	</tbody>
	</table>
</div>
</form>

<div class="tbl_head01 mart20">
	<table class="tablef">
	<colgroup>
		<col width="120px">
	</colgroup>
	<thead>
	<tr>
		<th scope="col">기간</th>
		<?php if($config['p_member']=='y') { ?>
		<th scope="col" colspan="2"><?php echo $ar_record['member']; ?></th>
		<?php } ?>
		<?php if($config['p_shop']=='y') { ?>
		<th scope="col" colspan="2"><?php echo $ar_record['shop']; ?></th>
		<?php } ?>
		<?php if($config['p_login']=='y') { ?>
		<th scope="col" colspan="2"><?php echo $ar_record['login']; ?></th>
		<?php } ?>
		<th scope="col" colspan="2"><?php echo $ar_record['admin']; ?></th>
	</tr>
	</thead>
	<tbody class="list">
	<?php
	$g = 0;
	for($i=1; $i<=31; $i++){

		$j = sprintf('%02d',$i);
		$search_d = $year."-".$month."-".$j;

		$sql_secret = " where mb_id	= '$member[id]' and month_date2	= '$search_d' and shop_ban != '1' ";

		$sql = "select sum(in_money) as price, count(mb_id) as cnt
				  from shop_partner_paylog
					   $sql_secret
				   and etc2 = 'member' ";
		$r1 = sql_fetch($sql);

		$sql = "select sum(in_money) as price, count(mb_id) as cnt
				 from shop_partner_paylog
					  $sql_secret
				  and etc2 = 'shop' ";
		$r2 = sql_fetch($sql);

		$sql = "select sum(in_money) as price, count(mb_id) as cnt
				  from shop_partner_paylog
					   $sql_secret
				   and etc2 = 'login' ";
		$r3 = sql_fetch($sql);

		$sql = "select sum(in_money) as price, count(mb_id) as cnt
				  from shop_partner_paylog
					   $sql_secret
				   and etc2 = 'admin' ";
		$r4 = sql_fetch($sql);

		if($r1['cnt']) { $td_cl1 = 'bold txt_true'; } else { $td_cl1 = 'txt_false'; }
		if($r2['cnt']) { $td_cl2 = 'bold txt_true'; } else { $td_cl2 = 'txt_false'; }
		if($r3['cnt']) { $td_cl3 = 'bold txt_true'; } else { $td_cl3 = 'txt_false'; }
		if($r4['cnt']) { $td_cl4 = 'bold txt_true'; } else { $td_cl4 = 'txt_false'; }

		$atot = ($r1['cnt'] + $r2['cnt'] + $r3['cnt'] + $r4['cnt']);
		if($atot) { $th_cl = ' txt_true'; } else { $th_cl = ''; }

		$bg = 'list'.($g%2);
	?>
	<tr class="<?php echo $bg; ?>">
		<td class="bold<?php echo $th_cl; ?>"><?php echo $search_d; ?></td>
		<?php if($config['p_member']=='y') { ?>
		<td class="tar <?php echo $td_cl1; ?>"><?php echo $r1['cnt']; ?>건</td>
		<td class="tar <?php echo $td_cl1; ?>"><?php echo number_format($r1['price']); ?>원</td>
		<?php } ?>
		<?php if($config['p_shop']=='y') { ?>
		<td class="tar <?php echo $td_cl2; ?>"><?php echo $r2['cnt']; ?>건</td>
		<td class="tar <?php echo $td_cl2; ?>"><?php echo number_format($r2['price']); ?>원</td>
		<?php } ?>
		<?php if($config['p_login']=='y') { ?>
		<td class="tar <?php echo $td_cl3; ?>"><?php echo $r3['cnt']; ?>건</td>
		<td class="tar <?php echo $td_cl3; ?>"><?php echo number_format($r3['price']); ?>원</td>
		<?php } ?>
		<td class="tar <?php echo $td_cl4; ?>"><?php echo $r4['cnt']; ?>건</td>
		<td class="tar <?php echo $td_cl4; ?>"><?php echo number_format($r4['price']); ?>원</td>
	</tr>
	<?php 
		$p_member		+= (int)$r1['price'];
		$p_member_count	+= (int)$r1['cnt'];
		$p_shop			+= (int)$r2['price'];
		$p_shop_count	+= (int)$r2['cnt'];
		$p_login		+= (int)$r3['price'];
		$p_login_count	+= (int)$r3['cnt'];
		$p_admin		+= (int)$r4['price'];
		$p_admin_count	+= (int)$r4['cnt'];

		$g++;
	} 
	?>
	</tbody>
	<tfoot>
	<tr>
		<th>총합계</th>
		<?php if($config['p_member']=='y') { ?>
		<td class="tar"><?php echo $p_member_count; ?>건</td>
		<td class="tar"><?php echo number_format($p_member); ?>원</td>
		<?php } ?>
		<?php if($config['p_shop']=='y') { ?>
		<td class="tar"><?php echo $p_shop_count; ?>건</td>
		<td class="tar"><?php echo number_format($p_shop); ?>원</td>
		<?php } ?>
		<?php if($config['p_login']=='y') { ?>
		<td class="tar"><?php echo $p_login_count; ?>건</td>
		<td class="tar"><?php echo number_format($p_login); ?>원</td>
		<?php } ?>
		<td class="tar"><?php echo $p_admin_count; ?>건</td>
		<td class="tar"><?php echo number_format($p_admin); ?>원</td>
	</tr>
	</tfoot>
	</table>
</div>

<?php
include_once("./admin_tail.sub.php");
?>