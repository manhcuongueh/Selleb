<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<div class="m_bo_bg mart10">
	<div class="title"><?php echo $qa['subject']; ?></div>
	<div class="wr_name"><?php echo $qa['mb_id']; ?><span class="wr_day"><?php echo substr($qa['wdate'],0,10); ?></span></div>
	<div class="wr_txt">
		<?php echo nl2br($qa['memo']); ?>
	</div>

	<?php if($qa['result_yes']) { ?>
	<div class="qna_reply">
		<p class="date"><span class="ic_tit">답변</span> <?php echo substr($qa['result_date'],0,10); ?></p>
		<p><?php echo nl2br($qa['reply']); ?></p>
	</div>
	<?php } ?>

	<div class="pg_wrap tac mart10">
		<a href="./qna_write.php" class="btn_medium">상담문의</a>		
		<a href="./qna_modify.php?index_no=<?php echo $index_no; ?>" class="btn_medium bx-white">수정</a>
		<a href="./qna_list.php" class="btn_medium bx-white">목록</a>
		<a href="javascript:del('./qna_read.php?index_no=<?php echo $index_no; ?>&mode=d');" class="btn_medium bx-white">삭제</a>
	</div>
</div>

<script>
function del(url) {
	answer = confirm('삭제 하시겠습니까?');
	if(answer==true) { 
		location.href = url; 
	}
}
</script>
