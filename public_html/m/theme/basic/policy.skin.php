<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<h2 class="pop_title">
	<?php echo $tb['title']; ?>
	<a href="javascript:window.close();" class="btn_small bx-white">창닫기</a>
</h2>
<div class="m_agree">
	<?php echo nl2br($config['sp_policy']); ?>
</div>
<div class="pop_btn"><button type="button" onclick="window.close();" class="btn_medium bx-white">창닫기</button></div>
