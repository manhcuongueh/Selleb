<?php
if(!defined('_TUBEWEB_')) exit;
?>

<div class="s_lnb">
	<div class="s_lnb_hd">
		<p class="hd">MY PAGE</p>
		<p class="hd_sub">마이페이지</p>
	</div>
	<ul class="s_lnb_inner">
		<li><a href="<?php echo TW_BBS_URL; ?>/register_mod.php">회원정보수정</a></li>
		<li><a href="<?php echo TW_SHOP_URL; ?>/orderlist.php">주문/배송조회</a></li>
		<li><a href="<?php echo TW_SHOP_URL; ?>/point.php">적립금조회</a></li>
		<?php if($config['sp_gift']) { ?>
		<li><a href="<?php echo TW_SHOP_URL; ?>/gift.php">쿠폰인증</a></li>
		<?php } ?>
		<?php if($config['sp_coupon']) { ?>
		<li><a href="<?php echo TW_SHOP_URL; ?>/coupon.php">쿠폰관리</a></li>
		<?php } ?>
		<li><a href="<?php echo TW_SHOP_URL; ?>/wish.php">찜한상품</a></li>
	</ul>
</div>