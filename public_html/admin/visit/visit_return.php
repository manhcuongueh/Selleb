<?php
if(!defined('_TUBEWEB_')) exit;

if(!$year) $year = $time_year;
if(!$month)	$month = $time_month;
$returndate = preg_replace("/([0-9]{4})([0-9]{2})/", "\\1-\\2", $year.$month);

$sql = " select count(*) as cnt,
				sum(account + del_account) as od_price
		   from shop_order 
		   where dan = '6' 
		     and left(returndate_s,7) = '$returndate' ";
$row = sql_fetch($sql);
$tot_count = (int)$row['cnt'];
$tot_price = (int)$row['od_price'];
?>

<form name="fsearch" id="fsearch" method="get">
<input type='hidden' name='code' value="<?php echo $code;?>">
<div class="local_frm01">
	<p class="fl mart3">
		<b><?php echo $returndate;?></b>월 , 전체 : <b class="fc_197"><?php echo number_format($tot_count);?></b> 건 조회 , 전체주문금액 : <b class="fc_197"><?php echo number_format($tot_price);?></b> 원
	</p>
	<p class="fr">
		<select name="year">
			<?php
			for($i=($time_year-3); $i<=$time_year; $i++) {
				echo option_selected($i, $year, $i.'년');
			}
			?>
		</select>
		<select name="month">
			<?php
			for($i=1; $i<=12; $i++) {
				$j = sprintf("%02d",$i);
				echo option_selected($j, $month, $j.'월');
			}
			?>
		</select>
		<input type="submit" value="검색" class="btn_small">
	</p>
</div>
</form>

<div class="tbl_head01">
	<table>
	<colgroup>
		<col width="100px">
		<col>
		<col width="80px">
		<col width="100px">
		<col width="80px">
	</colgroup>
	<thead>
	<tr>
		<th>일별</th>
		<th>그래프</th>
		<th>주문(건)</th>
		<th>금액(원)</th>
		<th>비율 %</th>
	</tr>
	</thead>
	<tbody class="list">
	<?php
	if($tot_price==0) $tot_price = 1;
	if($tot_count==0) $tot_count = 1;

	for($i=1;$i<=31;$i++) {
		$per = $sum_count = $sum_price = 0;

		$j = sprintf("%02d",$i);
		$date = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3", $year.$month.$j);

		$sql = " select count(*) as cnt,
						sum(account + del_account) as od_price
				   from shop_order 
				   where dan = '6' 
					 and returndate_s = '$date' ";
		$row = sql_fetch($sql);
		$sum_count = (int)$row['cnt'];
		$sum_price = (int)$row['od_price'];

		$per = (($sum_price/$tot_price) * 100);

		$bg = 'list'.($i%2);
	?>
	<tr class="<?php echo $bg;?>">
		<td><?php echo $date;?></td>
		<td><div class="graph"><span class="bar" style="width:<?php echo $per;?>%"></span></div></td>
		<td><?php echo number_format($sum_count);?></td>
		<td class="tar"><?php echo number_format($sum_price);?></td>
		<td><?php echo number_format($per,2);?></td>
	</tr>
	<?php 
	}
	?>
	</tbody>
	</table>
</div>
