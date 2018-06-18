<?php
if(!defined('_TUBEWEB_')) exit;

$file = TW_DATA_PATH.'/board/boardimg/'.$boardconfig['fileurl2'];
if(is_file($file) && $boardconfig['fileurl2']) {
	$file_url = TW_DATA_URL.'/board/boardimg/'.$boardconfig['fileurl2'];
	echo '<p><img src="'.$file_url.'"></p>';
}
?>
		<?php
		include_once(TW_MYPAGE_PATH."/admin_tail.sub.php"); 
		?>
	</div>
</div>

<?php
include_once(TW_MYPAGE_PATH."/admin_tail.php"); 
?>