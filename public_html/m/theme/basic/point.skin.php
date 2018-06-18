<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<div class="stit_txt tal">※ <?php echo $member['name']; ?>님의 적립금내역</div>
<div class="m_mypage_bg">
	<table class="my_tbox">
	<colgroup>
		<col width="25%">
		<col width="25%">
		<col width="25%">
		<col width="25%">
	</colgroup>
	<tbody>
	<tr>
		<td class="tal">총 적립금</td>
		<td class="tar bold"><?php echo display_point($sum['incom']); ?></td>
		<td class="tal mi_bt">총 차감금</td>
		<td class="tar bold"><?php echo display_point($sum['outcom']); ?></td>
	</tr>
	</tbody>
	</table>

	<table class="mynavbar mart10">
	<colgroup>
		<col width="50%">
		<col width="50%">
	</colgroup>
	<tbody>
	<tr>
		<td class="selected"><span class="strong">적립/사용내역</span>&nbsp;&nbsp;<span class="tx_small fc_125">(최근 1년기준)</span></td>
		<td>&nbsp;</td>
	</tr>
	</tbody>
	</table>

	<?php
	if(!$total_count) {
		echo "<div class=\"sct_noitem\">자료가 없습니다.</div>";
	} else {
	?>
	<div class="my_list">
		<table class="my_box">
		<colgroup>
			<col width="70%">
			<col width="30%">
		</colgroup>
		<tbody>
		<?php
		for($i=0; $row=sql_fetch_array($result); $i++) {
			if($row['income'] > 0) {
				$point = "+".display_point($row['income']);
				$pomsg = "<span class='fc_197'>적립</span>";
			} else {
				$point = "-".display_point($row['outcome']);
				$pomsg = "<span class='fc_red'>차감</span>";
			}
		?>
		<tr class="tit">
			<td class="mi_dt tal strong"><?php echo date("Y-m-d",$row['wdate']); ?></td>
			<td class="mi_at tar"><?php echo $pomsg; ?></td>
		</tr>
		<tr>
			<td class="mi_dt"><?php echo get_text($row['memo']); ?></td>
			<td class="mi_at tar"><?php echo $point; ?></td>
		</tr>
		<tr>
			<td class="mi_dt tar strong" colspan="2">잔여포인트 : <?php echo display_point($row['total'])?></td>
		</tr>
		<?php } ?>
		</tbody>
		</table>
	</div>

	<?php echo pageing($page, $total_page, $total_count, "{$_SERVER['SCRIPT_NAME']}?page="); ?>
	<?php } ?>
</div>
