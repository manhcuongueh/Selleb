<?php
if(!defined('_TUBEWEB_')) exit;
?>

<div><img src="<?php echo TW_IMG_URL; ?>/seller_reg.gif"></div>
<div class="mart20 lh4">
	<?php echo get_view_thumbnail(conv_content($config['shop_reg_guide'], 1), 1000); ?>
</div>
<div class="tac mart20">
	<a href="<?php echo TW_BBS_URL; ?>/seller_reg_from.php" class="btn_medium">확인</a>
	<a href="javascript:history.go(-1);" class="btn_medium bx-white">취소</a>
</div>