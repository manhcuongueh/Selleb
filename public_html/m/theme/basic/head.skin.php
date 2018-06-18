<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가

include_once($theme_path.'/slideMenu.skin.php');
?>

<div id="wrapper">
	<div onclick="history.go(-1);" class="page_cover"><span class="sm_close"></span></div>
	<?php if($banner1 = get_display_bn('100', $pt_id)) { // 상단 큰배너 ?>
	<div class="top_ad"><?php echo $banner1; ?></div>
	<?php } ?>
	<header id="header">
		<div id="m_gnb">
			<h1 class="logo"><?php echo display_logo(); ?></h1>
			<span class="btn_sidem fa fa-navicon"></span>
			<span class="btn_search fa fa-search"></span>
			<a href="<?php echo $tb['bbs_root']; ?>/cart.php" class="btn_cart fa fa-shopping-cart"><span class="ic_num"><?php echo get_cart_count(); ?></span></a>
		</div>
		<div id="hd_sch">
			<section>
			<form name="fsearch" method="post" onsubmit="return fsearch_submit(this);" autocomplete="off">
				<input type="hidden" name="enc_field" value="<?php echo ENC_FIELD; ?>">
				<input type="search" name="ss_tx" value="<?php echo $ss_tx; ?>" placeholder='<?php echo $config['mo_se_default']; ?>' onFocus="this.placeholder=''" class="search_inp" maxlength="255">
				<input type="submit" value="&#xf002;" id="sch_submit">
			</form>
			<script>
			function fsearch_submit(f){
				if(!f.ss_tx.value){
					alert('검색어를 입력하세요.');
					f.ss_tx.focus();
					return false;
				}

				f.action = tb_bbs_root+'/search_update.php';
				return true;
			}
			</script>
			</section>
			<script>
			$(function(){
				// 상단의 검색버튼 누르면 검색창 보이고 끄기
				$('.btn_search').click(function(){
					if($("#hd_sch").css('display') == 'none'){
						$("#hd_sch").slideDown('fast');
						$(this).attr('class','btn_search ionicons ion-android-close');
					} else {
						$("#hd_sch").slideUp('fast');
						$(this).attr('class','btn_search fa fa-search');
					}
				});
			});
			</script>
			<?php if($config['mo_se_yn']) { ?>
			<div class="m_rkw_se" id="rkw_open">
				<?php echo get_display_tick("금주의 검색어", 6); ?>
				<button type="button" class="btn_open"></button>
			</div>

			<div class="m_rkw_se" id="rkw_close" style="display:none;">
				<h2>금주의 검색어 순위</h2>
				<button type="button" class="btn_close"></button>
			</div>

			<div class="m_rkw_bg" style="display:none;">
				<?php echo get_display_rank(); ?>
			</div>

			<script>
			// 금주의 인기검색어 펼침
			$(".m_rkw_se .btn_open").click(function(){
				$("#rkw_open").hide();
				$("#rkw_close").show();
				$(".m_rkw_bg").show();
			});

			// 금주의 인기검색어 닫음
			$(".m_rkw_se .btn_close").click(function(){
				$("#rkw_open").show();
				$("#rkw_close").hide();
				$(".m_rkw_bg").hide();
			});

			// 인기 검색어 롤링
			function tick(){
				$('#ticker li:first').slideUp( function () {
					$(this).appendTo($('#ticker')).slideDown();
				});
			}
			setInterval(function(){ tick () }, 4000);
			</script>
			<?php } ?>
		</div>
	</header>

	<!-- content -->
	<div id="container"<?php if(!defined("_MINDEX_")) { ?> class="sub_wrap"<?php } ?>>
		<nav id="gnb">
			<ul>
				<li><a href="<?php echo $tb['bbs_root']; ?>/listtype.php?type=2">베스트셀러</a></li>
				<li><a href="<?php echo $tb['bbs_root']; ?>/listtype.php?type=3">신상품</a></li>
				<li><a href="<?php echo $tb['bbs_root']; ?>/listtype.php?type=4">인기상품</a></li>
				<li><a href="<?php echo $tb['bbs_root']; ?>/listtype.php?type=5">추천상품</a></li>
				<li><a href="<?php echo $tb['bbs_root']; ?>/brand.php">브랜드샵</a></li>
				<li><a href="<?php echo $tb['bbs_root']; ?>/plan.php">기획전</a></li>
				<li><a href="<?php echo $tb['bbs_root']; ?>/timesale.php">타임세일</a></li>
			</ul>
		</nav>
		<script>
		//상단 슬라이드 메뉴
		var menuScroll = null;
		$(window).ready(function() {
			menuScroll = new iScroll('gnb', {
				hScrollbar:false, vScrollbar:false, bounce:false, click:true
			});
		});
		</script>
		<?php if(!defined("_MINDEX_")) { ?>
		<div id="content_title">
			<span><?php echo ($pg['pagename'] ? $pg['pagename'] : $tb['title']); ?></span>
		</div>
		<?php } ?>
