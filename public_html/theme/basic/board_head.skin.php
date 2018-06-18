<?php
if(!defined('_TUBEWEB_')) exit;

include_once($theme_path.'/aside_cs.skin.php');
?>

<div class="rbody">
	<p class="tit_navi">홈 <i class="ionicons ion-ios-arrow-right"></i> 고객센터 <i class="ionicons ion-ios-arrow-right"></i> <?php echo $boardconfig['boardname']; ?></p>
	<h2 class="stit"><?php echo $boardconfig['boardname']; ?></h2>

	<?php if($boardconfig['fileurl1']) { ?>
	<p><img src='<?php echo TW_DATA_URL; ?>/board/boardimg/<?php echo $boardconfig['fileurl1']; ?>'></p>
	<?php } ?>