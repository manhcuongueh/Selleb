<?php
if(!defined('_TUBEWEB_')) exit;
?>

<div id="ajax-loading"><img src="<?php echo TW_IMG_URL; ?>/ajax-loader.gif"></div>
<?php if(!defined('_NEWWIN_')) { // 팝업창은 실행하지 않는다 ?>
<div id="anc_header"><a href="#anc_hd"><span></span>TOP</a></div>
<?php } ?>

<script src="<?php echo TW_ADMIN_URL; ?>/js/admin.js?ver=<?php echo $time_Yhs;?>"></script>

<script src="<?php echo TW_JS_URL; ?>/wrest.js"></script>
</body>
</html>