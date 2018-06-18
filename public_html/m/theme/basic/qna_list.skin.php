<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<div class="m_bo_bg mart10">
	<ul>
		<?php
		for($i=0; $row=sql_fetch_array($result); $i++) {
			$wdate = $row['mb_id']."<span class='padl10'>".substr($row['wdate'],0,10);
		?>
		<li class="list">
			<a href="./qna_read.php?index_no=<?php echo $row['index_no']; ?>">
			<p class="subj"><b class="cate">[ <?php echo $row['catename']; ?> ]</b> <?php echo cut_str($row['subject'],60); ?></p>
			<p class="date"><?php echo $wdate; ?></p>
			</a>
		</li>
		<?php
		}
		if($total_count==0) { 
		?>
		<li class="sct_noitem">자료가 없습니다.</li>
		<?php } ?>
	</ul>
</div>

<div class="pg_wrap mart10">
	<p class="marb10"><a href="./qna_write.php" class="btn_medium wfull">상담문의하기</a></p>
	<?php echo pageing($page, $total_page, $total_count, "{$_SERVER['SCRIPT_NAME']}?page="); ?>
</div>
