<?php
if(!defined('_TUBEWEB_')) exit;

include_once($theme_path.'/aside_cs.skin.php');
?>

<div class="rbody">
	<p class="tit_navi">홈 <i class="ionicons ion-ios-arrow-right"></i> 고객센터 <i class="ionicons ion-ios-arrow-right"></i> 자주묻는질문</p>
	<h2 class="stit">자주묻는질문</h2>
	<div class="tac">
		<?php
		if(!$faqcate) { $faqcate = 1; }
		$sql = "select * from shop_faq_cate order by index_no asc";
		$res = sql_query($sql);
		for($i=0; $row=sql_fetch_array($res); $i++) {
			$co1 = "";
			if($row['index_no'] != $faqcate) {
				$co1 = " bx-white";
			}
		?>
		<a href="<?php echo TW_BBS_URL; ?>/faq.php?faqcate=<?php echo $row['index_no']; ?>" class="btn_medium<?php echo $co1; ?>"><?php echo $row['catename']; ?></a>
		<?php } ?>
	</div>
	<ul class="faq_li">
		<?php
		$sql = "select * from shop_faq where cate='$faqcate' order by index_no asc";
		$res = sql_query($sql);
		for($i=0; $row=sql_fetch_array($res); $i++) {
		?>
		<li class="faq_q" onclick="js_faq('<?php echo $i; ?>');">
			<?php echo $row['subject']; ?>
		</li>
		<li id="sod_faq_con_<?php echo $i; ?>" class="faq_a">
			<?php echo get_view_thumbnail(conv_content($row['memo'], 1), 700); ?>
		</li>
		<?php } ?>
	</ul>
	<?php if($i==0) { ?>
	<div class="empty_list">자료가 없습니다.</div>
	<?php } ?>

	<script>
	function js_faq(id){
		var $con = $("#sod_faq_con_"+id);
		if($con.is(":visible")) {
			$con.slideUp("fast");
			$(".faq_q").removeClass("active");
		} else {
			$(".faq_a:visible").slideUp("fast");
			$con.slideDown("fast");
			$(".faq_q").removeClass("active");
			$con.prev().addClass("active");
		}
	}
	</script>
	<div class="tac mart20"><a href="<?php echo TW_URL; ?>" class="btn_medium grey">쇼핑계속하기</a></div>
</div>