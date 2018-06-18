<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<div class="stit_txt tal">※ 총 <?php echo number_format($total_count); ?>개의 최근 본 상품이 있습니다.</div>
<div class="m_rece_bg">
	<?php if($total_count) { ?>
	<div class="m_rece marb10">
		<table class="re_box">
		<tbody>
		<?php
		for($i=0; $row=sql_fetch_array($result); $i++) {
			$gs_id = $row['index_no'];
		?>
		<tr>
			<td class="mi_dt"><a href="<?php echo $tb['bbs_root']; ?>/view.php?gs_id=<?php echo $gs_id; ?>"><?php echo get_it_image($gs_id, $row['simg1'], 80, 80); ?></a></td>
			<td class="mi_bt">
				<div class="mi_u">
					<a href="<?php echo $tb['bbs_root']; ?>/view.php?gs_id=<?php echo $gs_id; ?>"><?php echo cut_str($row['gname'],80); ?></a>
					<div class="strong mart5"><?php echo get_price($gs_id)?></div>
				</div>
				<div class="mi_d"><a href="<?php echo $tb['bbs_root']; ?>/today.php?w=d&amp;gs_id=<?php echo $gs_id; ?>" class="btn_small grey">삭제</a></div>
			</td>
		</tr>
		<?php } ?>
		</tbody>
		</table>
	</div>
	<?php } ?>

	<?php
	if($total_count == 0) {
		echo "<div class=\"sct_noitem\">자료가 없습니다.</div>";
		echo "<div>";
			echo "<button type=\"button\" onclick=\"location.href='$tb[root]';\" class=\"btn_medium bx-white wfull\">쇼핑계속하기</button>";
		echo "</div>";
	} else {
		 echo pageing($page, $total_page, $total_count, "{$_SERVER['SCRIPT_NAME']}?page=");
	}
	?>
</div>
