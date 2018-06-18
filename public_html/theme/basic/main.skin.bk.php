<?php
if(!defined('_TUBEWEB_')) exit;
?>

<div class="cont_wrap">
	<!-- 베스트 쇼핑특가 및 배너 시작 { -->
	<div class="best_wrap">
		<div class="bnr1"><?php echo banner_view(3, 0, 0, $pt_id); ?></div>
		<div class="bnr2"><?php echo banner_view(4, 0, 0, $pt_id); ?></div>
		<div class="bnr3"><?php echo banner_view(5, 0, 0, $pt_id); ?></div>
		<div class="best_rol_slide">
			<h2>쇼핑특가</h2>
			<?php
			$res = display_itemtype($pt_id, 1, 20);
			$type1_count = sql_num_rows($res);
			if($type1_count) {
			?>
			<div class="best_rol">
				<?php
				for($i=0; $row=sql_fetch_array($res); $i++) {
					$it_href = TW_SHOP_URL.'/view.php?index_no='.$row['index_no'];
					$it_image = get_it_image($row['index_no'], $row['simg1'], 190, 190);
					$it_name = cut_str($row['gname'], 100);
					$it_price = get_price($row['index_no']);
					$it_amount = get_sale_price($row['index_no']);
					$it_point = display_point($row['gpoint']);

					// (시중가 - 할인판매가) / 시중가 X 100 = 할인률%
					$it_sprice = $sale = '';
					if($row['saccount'] > $it_amount && !is_uncase($row['index_no'])) {
						$sett = ($row['saccount'] - $it_amount) / $row['saccount'] * 100;
						$sale = '<dd class="sale">'.number_format($sett,0).'%</dd>';
						$it_sprice = display_price2($row['saccount']);
					}
				?>
				<dl>
					<?php echo $sale; ?>
					<a href="<?php echo $it_href; ?>">
						<dt class="pimg"><?php echo $it_image; ?></dt>
						<dd class="pname"><?php echo $it_name; ?></dd>
						<dd class="price"><?php echo $it_sprice; ?><?php echo $it_price; ?></dd>
					</a>
					<dd class="ic_bx"><span onclick="javascript:itemlistwish('<?php echo $row['index_no']; ?>');" id="<?php echo $row['index_no']; ?>" class="<?php echo $row['index_no'].' '.zzimCheck($row['index_no']); ?>"></span> <a href="<?php echo $it_href; ?>" target="_blank" class="nwin"></a></dd>
				</dl>
				<?php } ?>
			</div>
			<?php } ?>
		</div>
		<?php if($type1_count) { ?>
		<script>
		$(document).ready(function(){
			$('.best_rol').slick({
				autoplay: true,
				dots: false
			});
		});
		</script>
		<?php } ?>
	</div>
	<!-- } 베스트 쇼핑특가 및 배너 끝 -->
</div>

<!-- <?php echo $default['de_maintype_title']; ?> 시작 {-->
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
<!-- } <?php echo $default['de_maintype_title']; ?> 끝 -->

<!-- 베스트상품 시작 {-->
<div class="cont_bg mart40">
	<h2 class="mtit"><span>베스트셀러</span></h2>
	<?php echo get_listtype_best("2", '400', '400', '7', 'mart20'); ?>
</div>
<!-- } 베스트상품 끝 -->

<!-- 신상품 시작 { -->
<div class="cont_wrap mart60">
	<h2 class="mtit"><span>신상품</span></h2>
	<?php echo get_listtype_skin("3", '235', '235', '12', 'wli4 mart5'); ?>
</div>
<!-- } 신상품 끝 -->

<!-- 큰 배너 배경 및 문구 시작 { -->
<?php echo mask_banner(7, 0, 0, $pt_id); ?>
<!-- } 큰 배너 배경 및 문구 끝 -->

<!-- 인기상품 시작 { -->
<div class="cont_wrap mart60">
	<h2 class="mtit"><span>인기상품</span></h2>
	<?php echo get_listtype_skin("4", '315', '315', '6', 'wli3 mart5'); ?>
</div>
<!-- } 인기상품 끝 -->

<!-- 중간 배너영역 시작 { -->
<ul class="mmd_bn mart60">
	<li class="bnr1"><?php echo banner_view(8, 0, 0, $pt_id); ?></li>
	<li class="bnr2"><?php echo banner_view(9, 0, 0, $pt_id); ?></li>
	<li class="bnr3"><?php echo banner_view(10, 0, 0, $pt_id); ?></li>
	<li class="bnr4"><?php echo banner_view(11, 0, 0, $pt_id); ?></li>
</ul>
<!-- } 중간 배너영역 끝 -->

<!-- 추천상품 시작 { -->
<div class="cont_wrap mart60">
	<h2 class="mtit"><span>추천상품</span></h2>
	<?php echo get_listtype_skin("5", '190', '190', '10', 'wli5 mart5'); ?>
</div>
<!-- } 추천상품 끝 -->
ㅡ