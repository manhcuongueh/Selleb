<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<div class="stit_txt tal">※ 총 <?php echo number_format($total_count); ?>개의 구매후기가 있습니다.</div>
<div class="a_post">
	<?php
	if($total_count == 0) {
		echo "<div class=\"sct_noitem\">자료가 없습니다.</div>";
		echo "<div>";
			echo "<button type=\"button\" onclick=\"location.href='$tb[root]';\" class=\"btn_medium bx-white wfull\">쇼핑계속하기</button>";
		echo "</div>";
	} else {
	?>
	<table class="tbl_post">
	<colgroup>
		<col width="80">
		<col>
	</colgroup>
	<tbody>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$tmp_name = cut_str($row['writer_s'], 4);
		$tmp_date = date("Y-m-d", $row['wdate']);
		$tmp_score = $arr_sco[$row['score']];
		$gs = get_goods($row['gs_id']);
	?>
	<tr>
		<td class="mi_dt"><a href="<?php echo $tb['bbs_root']?>/view.php?gs_id=<?php echo $row['gs_id']; ?>"><?php echo get_it_image($row['gs_id'], $gs['simg1'], 80, 80); ?></a></td>
		<td class="mi_bt">
			<a href="<?php echo $tb['bbs_root']?>/view.php?gs_id=<?php echo $row['gs_id']; ?>"><?php echo get_text($row['memo']); ?></a>
			<p class="mart5 fs12">
				<span class='fc_255'><?php echo $tmp_score; ?></span><span class='fc_999'> / <?php echo $tmp_name; ?> / <?php echo $tmp_date; ?></span>
			</p>
		</td>
	</tr>
	<?php } ?>
	</tbody>
	</table>

	<div class="mart10 marb25">
		<?php echo pageing($page, $total_page, $total_count, "{$_SERVER['SCRIPT_NAME']}?page="); ?>
	</div>
	<?php } ?>
</div>
