<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<?php if($main_bn = get_display_mbn($pt_id)) { ?>
<!-- 메인배너 시작 { -->
<div id="main_bn">
	<?php echo $main_bn; ?>
</div>
<script>
$(document).on('ready', function() {
	$('#main_bn').slick({
		autoplay: true,
		autoplaySpeed: 4000,
		dots: true,
		fade: true
	});
});
</script>
<!-- } 메인배너 끝 -->
<?php } ?>

<!-- 메인배너 하단 시작 { -->
<ul class="mbm_bn01">
	<li class="bnr1"><?php echo get_display_bn('101', $pt_id); ?></li>
	<li class="bnr2"><?php echo get_display_bn('102', $pt_id); ?></li>
	<li class="bnr3"><?php echo get_display_bn('103', $pt_id); ?></li>
</ul>
<!-- } 메인배너 하단 끝 -->

<!-- 쇼핑특가 시작 { -->
<div class="pr_slide" id="type5">
	<?php echo get_slide_goods('5', '20', '쇼핑특가', 'slider'); ?>
	<script>
    $(document).on('ready', function() {
      $("#type5 .slider").slick({
		autoplay: true,
        dots: false,
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 1
      });
    });
	</script>
</div>
<!-- } 쇼핑특가 끝 -->

<?php
$list_best = unserialize(base64_decode($default['de_maintype_best']));
$list_count = count($list_best);
if($list_count > 0) {
?>
<!-- <?php echo $default['de_maintype_title']; ?> 시작 {-->
<div class="bscate mart30">
	<h2 class="mtit"><span><?php echo $default['de_maintype_title']; ?></span></h2>
	<div class="bscate_tab">
		<?php for($i=0; $i<$list_count; $i++) { ?>
		<a><span><?php echo trim($list_best[$i]['subj']); ?></span></a>
		<?php } ?>
	</div>
	<div class="bscate_li">
		<?php echo get_listtype_cate($list_best); ?>
	</div>
	<script>
	$(document).ready(function(){
		$('.bscate_li').slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			arrows: false,
			fade: true,
			asNavFor: '.bscate_tab'
		});
		$(".bscate_tab").slick({
			autoplay: false,
			dots: false,
			infinite: true,
			centerMode: true,
			variableWidth: true,
			slidesToScroll: 1,
			asNavFor: '.bscate_li',
			focusOnSelect: true
		});
	});
	</script>
</div>
<!-- } <?php echo $default['de_maintype_title']; ?> 끝 -->
<?php } ?>

<?php if($banner = get_display_bn('104', $pt_id)) { ?>
<div class="ad mart30"><?php echo $banner; ?></div>
<?php } ?>

<!-- 베스트셀러 시작 {-->
<div class="mart30">
	<?php echo get_display_goods('2', '6', '베스트셀러', 'pr_desc wli2'); ?>
</div>
<!-- } 베스트셀러 끝 -->

<?php if($banner = get_display_bn('105', $pt_id)) { ?>
<div class="ad mart30"><?php echo $banner; ?></div>
<?php } ?>

<!-- 신상품 시작 { -->
<div class="mart30">
	<?php echo get_display_goods('3', '6', '신상품', 'pr_desc wli2'); ?>
</div>
<!-- } 신상품 끝 -->

<?php if($banner = get_display_bn('106', $pt_id)) { ?>
<div class="ad mart30"><?php echo $banner; ?></div>
<?php } ?>

<!-- 인기상품 시작 { -->
<div class="mart30">
	<?php echo get_display_goods('4', '6', '인기상품', 'pr_desc wli2'); ?>
</div>
<!-- } 인기상품 끝 -->

<?php if($banner = get_display_bn('107', $pt_id)) { ?>
<div class="ad mart30"><?php echo $banner; ?></div>
<?php } ?>

<!-- 추천상품 시작 { -->
<div class="mart30">
	<?php echo get_display_goods('5', '6', '추천상품', 'pr_desc wli2'); ?>
</div>
<!-- } 추천상품 끝 -->
