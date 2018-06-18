<?php
if(!defined('_TUBEWEB_')) exit;
?>

<p class="tit_navi">홈 <i class="ionicons ion-ios-arrow-right"></i> <?php echo get_text($co["co_subject"]); ?></p>
<h2 class="stit"><?php echo get_text($co["co_subject"]); ?></h2>
<div>
	<?php echo get_view_thumbnail(conv_content($co["co_content"], 1), 1000); ?>
</div>
