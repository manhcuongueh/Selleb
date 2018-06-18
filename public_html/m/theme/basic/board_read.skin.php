<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<div class="m_bo_bg mart10">
	<div class="title"><?php echo $bo_subject; ?></div>
	<div class="wr_name"><?php echo $row['writer_s']; ?><span class="wr_day"><?php echo $bo_wdate; ?></span></div>
	<div class="wr_txt">
		<?php
		$file1 = TW_DATA_PATH."/board/{$boardid}/{$row['fileurl1']}";
		if(is_file($file1) && preg_match("/\.(gif|jpg|png)$/i", $row['fileurl1'])) {
			$file1_url = TW_DATA_URL."/board/{$boardid}/{$row['fileurl1']}";
		?>
		<img src="<?php echo $file1_url; ?>" class="img_fix">
		<?php } ?>
		<?php
		$file2 = TW_DATA_PATH."/board/{$boardid}/{$row['fileurl2']}";
		if(is_file($file2) && preg_match("/\.(gif|jpg|png)$/i", $row['fileurl2'])) {
			$file2_url = TW_DATA_URL."/board/{$boardid}/{$row['fileurl2']}";
		?>
		<img src="<?php echo $file2_url; ?>" class="img_fix">
		<?php } ?>
		<p><?php echo get_image_resize($row['memo']); ?></p>
	</div>
</div>

<div class="pg_wrap tac mart10">
	<a href="./board_list.php?<?php echo $qstr1;?>" class="btn_medium bx-white">목록</a>
	<?php if($mb_grade<=$board['reply_priv'] && $board['usereply']=='Y') { ?>
	<a href="./board_write.php?<?php echo $qstr2;?>&w=r" class="btn_medium bx-white">답변</a>
	<?php } if(($mb_no == $row['writer']) || is_admin()) { ?>
	<a href="./board_write.php?<?php echo $qstr2;?>&w=u" class="btn_medium bx-white">수정</a>
	<a href="./board_delete.php?<?php echo $qstr2;?>" class="btn_medium bx-white">삭제</a>
	<?php } ?>
</div>
