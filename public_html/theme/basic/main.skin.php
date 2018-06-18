<?php
if(!defined('_TUBEWEB_')) exit;
?>

<!--상단 메인배너그룹-->
<div class="main_tag_texts">
	<h1 style="text-align:center; margin-top:50px;">#KEY WORDS</h2>
	<!---->
	<div id="hd_sch" style="margin-top:20px;">
		<fieldset class="sch_frm" style="margin:auto;">
			<legend>사이트 내 전체검색</legend>
			<form name="fsearch" id="fsearch" method="post" action="<?php echo TW_SHOP_URL; ?>/search_update.php" onsubmit="return fsearch_submit(this);" autocomplete="off">
			<input type="hidden" name="enc_field" value="<?php echo ENC_FIELD; ?>">
			<input type="text" name="ss_tx" class="sch_stx" maxlength="20" placeholder="검색어를 입력해주세요">
			<button type="submit" class="sch_submit fa fa-search" value="검색"></button>
			</form>
			<script>
			function fsearch_submit(f){
				if(!f.ss_tx.value){
					alert('검색어를 입력하세요.');
					return false;
				}
				return true;
			}
			</script>
		</fieldset>
	</div>
	<!---->
	<ul class="tag1">
		<li>#COLLAGEN</li>
		<li>#HOTCREAM</li>
		<li>#SNORKELING-MASK</li>
	</ul>
	<ul class="tag2">
		<li>#DAILY</li>
		<li>#PERFUME</li>
		<li>#MAKEUP-SPONGE</li>
	</ul>
</div>
<div class="para_main">
	<div class="banner_box1">
		<div class="img01">
			<div class="text">
				<h1 style="font-size:70px;">HAPPYRIM</h1>
				<p style="font-size:30px;">makeup sponge</p>
			</div>
		</div>
	</div>
</div>
<!--//상단 메인배너그룹-->

<!--상단 배너그룹-->
<div class="cont_wrap">
	
</div>
<!--//상단 배너그룹-->

<!--카테고리별 베스트-->
<!-- <?php echo $default['de_maintype_title']; ?> 시작 {-->
<!--
<div class="cont_wrap">
	<?php
	$list_best = unserialize(base64_decode($default['de_maintype_best']));
	$list_count = count($list_best);
	$tab_width = (float)(100 / $list_count);

	if($list_count > 0) {
	?>
	<h2 class="mtit mart65"><span><?php echo $default['de_maintype_title']; ?></span></h2>
	<ul class="bestca_tab">
		<?php for($i=0; $i<$list_count; $i++) { ?>
		<li data-tab="bstab_c<?php echo $i; ?>" style="width:<?php echo $tab_width; ?>%"><span><?php echo trim($list_best[$i]['subj']); ?></span></li>
		<?php } ?>
	</ul>
	<div class="bestca">
		<?php echo get_listtype_cate($list_best, '209', '209'); ?>
	</div>
	<script>
	$(document).ready(function(){
		$(".bestca_tab>li:eq(0)").addClass('active');
		$("#bstab_c0").show();

		$(".bestca_tab>li").click(function() {
			var activeTab = $(this).attr('data-tab');
			$(".bestca_tab>li").removeClass('active');
			$(".bestca ul").hide();
			$(this).addClass('active');
			$("#"+activeTab).fadeIn(250);
		});
	});
	</script>
	<?php } ?>

	<div class="wide_bn mart40"><?php echo banner_view(6, 0, 0, $pt_id); ?></div>
</div>
-->
<!-- } <?php echo $default['de_maintype_title']; ?> 끝 -->
<!--//카테고리별 베스트-->



<!-- 신상품 시작 { -->
<div class="cont_wrap mart60">
	<!--
	<h2 class="mtit"><span>신상품</span></h2>
	-->
	<?php echo get_listtype_skin("3", '235', '235', '12', 'wli4 mart5'); ?>
</div>
<!-- } 신상품 끝 -->

<!-- 큰 배너 배경 및 문구 시작 { -->
<!--
<?php echo mask_banner(7, 0, 0, $pt_id); ?>
-->
<!-- } 큰 배너 배경 및 문구 끝 -->

