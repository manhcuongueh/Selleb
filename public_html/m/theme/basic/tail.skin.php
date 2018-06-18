<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

	</div>
	<span class="btn_top fa fa-chevron-up"></span>
	<span class="btn_bottom fa fa-chevron-down"></span>

	<?php
	if($default['de_insta_access_token']) { // 인스타그램
	   $userId = explode(".", $default['de_insta_access_token']);
	?>
	<script src="<?php echo TW_JS_URL; ?>/instafeed.min.js"></script>
	<script>
		var userFeed = new Instafeed({
			get: 'user',
			userId: <?php echo $userId[0]; ?>,
			limit: 6,
			template: '<li class="ins_li"><a href="{{link}}" target="_blank"><img src="{{image}}" /></a></li>',
			accessToken: "<?php echo $default['de_insta_access_token']; ?>"
		});
		userFeed.run();
	</script>

	<div class="insta">
		<h2 class="tac"><i class="fa fa-instagram"></i> INSTAGRAM<a href="https://www.instagram.com/<?php echo $default['de_insta_url']; ?>" target="_blank">@ <?php echo $default['de_insta_url']; ?></a></h2>
		<ul id="instafeed">
		</ul>
	</div>
	<?php } ?>

	<div class="sns_wrap">
		<?php if($default['de_sns_facebook']) { ?><a href="<?php echo $default['de_sns_facebook']; ?>" target="_blank" class="sns_fa"><img src="<?php echo $theme_url; ?>/img/sns_fa.png" title="facebook"></a><?php } ?>
		<?php if($default['de_sns_twitter']) { ?><a href="<?php echo $default['de_sns_twitter']; ?>" target="_blank" class="sns_tw"><img src="<?php echo $theme_url; ?>/img/sns_tw.png" title="twitter"></a><?php } ?>
		<?php if($default['de_sns_instagram']) { ?><a href="<?php echo $default['de_sns_instagram']; ?>" target="_blank" class="sns_in"><img src="<?php echo $theme_url; ?>/img/sns_in.png" title="instagram"></a><?php } ?>
		<?php if($default['de_sns_pinterest']) { ?><a href="<?php echo $default['de_sns_pinterest']; ?>" target="_blank" class="sns_pi"><img src="<?php echo $theme_url; ?>/img/sns_pi.png" title="pinterest"></a><?php } ?>
		<?php if($default['de_sns_naverblog']) { ?><a href="<?php echo $default['de_sns_naverblog']; ?>" target="_blank" class="sns_bl"><img src="<?php echo $theme_url; ?>/img/sns_bl.png" title="naverblog"></a><?php } ?>
		<?php if($default['de_sns_naverband']) { ?><a href="<?php echo $default['de_sns_naverband']; ?>" target="_blank" class="sns_ba"><img src="<?php echo $theme_url; ?>/img/sns_ba.png" title="naverband"></a><?php } ?>
		<?php if($default['de_sns_kakaotalk']) { ?><a href="<?php echo $default['de_sns_kakaotalk']; ?>" target="_blank" class="sns_kt"><img src="<?php echo $theme_url; ?>/img/sns_kt.png" title="kakaotalk"></a><?php } ?>
		<?php if($default['de_sns_kakaostory']) { ?><a href="<?php echo $default['de_sns_kakaostory']; ?>" target="_blank" class="sns_ks"><img src="<?php echo $theme_url; ?>/img/sns_ks.png" title="kakaostory"></a><?php } ?>
	</div>

	<?php if($config['mo_noti_yn']) { //공지사항?>
	<div class="notice">
		<span class="not_tit">공지</span><?php echo get_display_board(13, 40, 1); ?>
	</div>
	<?php } ?>

	<footer id="ft">
		<ul class="ft_menu">
			<li><a href="<?php echo TW_URL; ?>/?device=pc">PC버전</a></li>
			<li><a href="<?php echo $tb['bbs_root']; ?>/provision.php" target="_blank">회원약관</a></li>
			<li><a href="<?php echo $tb['bbs_root']; ?>/policy.php" target="_blank">개인정보처리방침</a></li>
			<li><a href="javascript:saupjaonopen('<?php echo conv_number($config['company_saupja_no']); ?>');">사업자정보확인</a></li>
		</ul>
		<dl class="ft_cs">
			<dt>고객센터 / 계좌안내</dt>
			<dd class="tel"><?php echo $config['company_tel']; ?></dd>
			<dd>상담 : <?php echo $config['company_hours']; ?> (<?php echo $config['company_close']; ?>)</dd>
			<dd>점심 : <?php echo $config['company_lunch']; ?></dd>
			<dd><?php echo $default['de_bank_name']; ?> <span class="bank_num"><?php echo $default['de_bank_account']; ?></span> 예금주 : <?php echo $default['de_bank_holder']; ?></dd>
		</dl>
		<dl class="ft_address">
			<dd><strong><?php echo $config['company_name']; ?></strong> <span class="marl15">대표자 : <?php echo $config['company_owner']; ?></span></dd>
			<dd><?php echo $config['company_addr']; ?></dd>
			<dd>고객센터 : <?php echo $config['company_tel']; ?> <span class="marl15">FAX : <?php echo $config['company_fax']; ?></span></dd>
			<dd>사업자등록번호 : <?php echo $config['company_saupja_no']; ?></dd>
			<dd>통신판매업신고 : <?php echo $config['tongsin_no']; ?></dd>
			<dd>E-mail : <?php echo $super['email']; ?></dd>
			<dd>개인정보보호책임자 : <?php echo $config['info_name']; ?> (<?php echo $config['info_email']; ?>)</dd>
		</dl>
		<p class="ft_crt">COPYRIGHT © <?php echo $config['company_name']; ?> ALL RIGHTS RESERVED.</p>
	</footer>
</div>

<script>
$(function() {
	// 상위로이동
	$(".btn_top").click(function(){
		$("html, body").animate({ scrollTop: 0 }, 300);
	});
	// 하위로이동
    $(".btn_bottom").click(function(){
		$("html, body").animate({ scrollTop: $(document).height() }, 300);
    });

	$(window).scroll(function () {
		if($(this).scrollTop() > 0) {
			$(".btn_top, .btn_bottom").fadeIn(300);
		} else {
			$(".btn_top, .btn_bottom").fadeOut(300);
		}
	});

	// 상단메뉴 스크롤시 fixed
	var adheight = $(".top_ad").height() + $("#gnb").height();
	$(window).scroll(function () {
		if($(this).scrollTop() > adheight) {
			$("#header").addClass('active');
			$("#container").addClass('padt45');
		} else {
			$("#header").removeClass('active');
			$("#container").removeClass('padt45');
		}
	});

	// 리스트 가로 갯수 조절
	$('.sct_li_type > a').on('click', function() {
		var this_type = $(this).closest('.sct_li_type');
		var this_a = $(this);
		var listtype = $(this).attr('href');

		// 링크 > 이미지 초기화
		this_type.find('a').each(function() {
			var imgSrc = $(this).find('img').attr('src').replace('<?php echo $theme_url; ?>/img/', '').split('.');
			$(this).find('img').attr('src', '<?php echo $theme_url; ?>/img/'+imgSrc[0].replace('_on','')+'.'+imgSrc[1]);
		});

		// 선택한 링크 > 이미지 _on 붙임
		var img_src = this_a.find('img').attr('src').replace('<?php echo $theme_url; ?>/img/', '').split('.');
		this_a.find('img').attr('src', '<?php echo $theme_url; ?>/img/'+img_src[0]+'_on'+'.'+img_src[1]);

		this_type.next('ul').removeClass('wli1 wli2 wli3');
		this_type.next('ul').addClass(listtype);

		return false;
	});
});
</script>
