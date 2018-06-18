<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<div class="s_cont">
	<select name="faq_type" class="faq_sch" onchange="location=this.value;">
		<option value="./faq.php">전체보기</option>
		<?php
		$sql = "select * from shop_faq_cate order by index_no asc";
		$res = sql_query($sql);
		for($i=0; $row=sql_fetch_array($res); $i++) {
			$selected = "";
			if($row['index_no']==$faqcate) {
				$selected = ' selected';
			}
		?>
		<option value="./faq.php?faqcate=<?php echo $row['index_no']; ?>"<?php echo $selected; ?>><?php echo $row['catename']; ?></option>
		<?php } ?>
	</select>
	<div class="faq_li">
		<ul>
			<?php
			$sql = "select * from shop_faq ";
			if($faqcate) $sql .= " where cate='$faqcate'";
			$sql.= " order by index_no asc ";
			$rst = sql_query($sql);
			for($i=0; $row=sql_fetch_array($rst); $i++) {
			?>
			<li class="faq_q" onclick="js_faq('<?php echo $i; ?>');">
				<?php echo $row['subject']; ?>
			</li>
			<li id="sod_faq_con_<?php echo $i; ?>" class="faq_a">
				<?php echo nl2br($row['memo']); ?>
			</li>
			<?php } ?>
		</ul>
	</div>
	<?php if($i==0) { ?>
	<div class="sct_noitem">자료가 없습니다.</div>
	<?php } ?>
</div>

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
