<?php
if(!defined('_TUBEWEB_')) exit;

$pg_title = "월별 가입통계분석";
include_once("./admin_head.sub.php");

if(!$year) $year = $time_year;

$sql_common = " from shop_member ";
$sql_where  = " where pt_id ='$member[id]' ";

$mb1 = sql_fetch("select count(*) as cnt $sql_common $sql_where ");
$mb2 = sql_fetch("select count(*) as cnt $sql_common $sql_where and grade between '7' and '9' ");
$mb3 = sql_fetch("select count(*) as cnt $sql_common $sql_where and grade between '2' and '6' ");
?>

<form name="fsearch" id="fsearch" method="get">
<input type="hidden" name="code" value="<?php echo $code?>">
<div class="local_frm01">
	<span class="stxt">전체회원 : <b class="fc_197"><?php echo number_format($mb1['cnt']);?></b>명,</span>
	<span class="stxt">일반회원 : <b class="fc_197"><?php echo number_format($mb2['cnt']);?></b>명,</span>
	<span class="stxt">가맹점회원 : <b class="fc_197"><?php echo number_format($mb3['cnt']);?></b>명</span>
	<span class="fr">
		<select name="year">
		<?php
		for($i=($time_year-3);$i<($time_year+1);$i++)
			echo option_selected($i, $year, $i.'년');
		?>
		</select>
		<input type="submit" value="검색" class="btn_small grey">
	</span>
</div>
</form>

<div class="tbl_head01">
	<table>
	<colgroup>
		<col width="60px">
		<col>
		<col width="80px">
		<col width="80px">
		<col width="80px">
		<col width="80px">
	</colgroup>
	<thead>
	<tr>
		<th scope="col">월별</th>
		<th scope="col">그래프분석</th>
		<th scope="col">비율 %</th>
		<th scope="col">전체</th>
		<th scope="col">일반</th>
		<th scope="col">가맹점</th>
	</tr>
	</thead>
	<tbody class="list">
	<?php
	for($i=1; $i<=12; $i++) {
		$per = 0;

		$search_d = $year."-".sprintf('%02d',$i);

		$row1 = sql_fetch("select count(*) as cnt $sql_common $sql_where and left(reg_time,7)='$search_d' and id!='admin'");
		$row2 = sql_fetch("select count(*) as cnt $sql_common $sql_where and left(reg_time,7)='$search_d' and grade between '7' and '9' ");
		$row3 = sql_fetch("select count(*) as cnt $sql_common $sql_where and left(reg_time,7)='$search_d' and grade between '2' and '6' ");

		if($row1['cnt'] == 0 || $mb1['cnt'] == 0) // 만약에 값이 없다면 = 0
			$per = 0;
		else
			$per = $row1['cnt']/$mb1['cnt'] * 100;

		$rate = number_format($per, 2);

		$bg = 'list'.($i%2);
	?>
	<tr class="<?php echo $bg;?>">
		<td><?php echo sprintf('%02d',$i);?></td>
		<td><div class="graph"><span class="bar" style="width:<?php echo $rate; ?>%"></span></div></td>
		<td><?php echo $rate; ?></td>
		<td><?php echo number_format($row1['cnt']);?></td>
		<td><?php echo number_format($row2['cnt']);?></td>
		<td><?php echo number_format($row3['cnt']);?></td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
</div>

<?php
include_once("./admin_tail.sub.php");
?>