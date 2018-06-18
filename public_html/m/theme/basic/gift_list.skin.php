<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<div class="s_cont">
	<table class="navbar">
	<colgroup>
		<col width="50%">
		<col width="50%">
	</colgroup>
	<tbody>
	<tr>
		<td><a href="./gift.php">인증하기</a></td>
		<td class="selected"><a href="./gift_list.php">인증내역</a></td>
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
			if($row['gi_use'])
				$gi_use = "<span class='fc_red'>적립완료</span>";
			else				
				$gi_use = "<span class='fc_197'>적립대기</span>";
		?>
		<tr class="tit">
			<td class="mi_dt tal">쿠폰 : <strong><?php echo $row['gi_num'];?></strong></td>
			<td class="mi_at tar"><?php echo $gi_use;?></td>
		</tr>
		<tr>
			<td class="mi_dt tal">일시 : <?php echo (substr($row['mb_wdate'],0,1) != 0)?$row['mb_wdate']:'';?></td>
			<td class="mi_at tar"><strong><?php echo display_price($row['gr_price']);?></strong></td>
		</tr>
		<?php } ?>
		</tbody>
		</table>
	</div>

	<?php echo pageing($page, $total_page, $total_count, "{$_SERVER['SCRIPT_NAME']}?page=");?>
	<?php } ?>
</div>