<?php
if(!defined('_TUBEWEB_')) exit;
?>

<div class="s_lnb">
	<div class="s_lnb_hd">
		<p class="hd">CUSTOMER CENTER</p>
		<p class="hd_sub">고객센터</p>
	</div>
	<ul class="s_lnb_inner">
		<li><a href="<?php echo TW_BBS_URL; ?>/faq.php?faqcate=1">자주묻는질문</a></li>
		<li><a href="<?php echo TW_BBS_URL; ?>/qna_list.php">1:1 상담문의</a></li>
		<?php
		$sql = " select * from shop_board_conf where gr_id='gr_mall' order by index_no asc ";
		$res = sql_query($sql);
		for($i=0; $row=sql_fetch_array($res); $i++) { ?>
		<li><a href="<?php echo TW_BBS_URL; ?>/list.php?boardid=<?php echo $row['index_no']; ?>"><?php echo $row['boardname']; ?></a></li>
		<?php } ?>		
		<li><a href="<?php echo TW_BBS_URL; ?>/review.php">고객상품평</a></li>
	</ul>
</div>