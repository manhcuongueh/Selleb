<?php
if(!defined('_TUBEWEB_')) exit;

include_once(TW_MYPAGE_PATH."/admin_head.php");

$pg_title = $boardconfig['boardname'];
?>

<div id="wrapper">
	<div id="snb">
		<?php 
		include_once(TW_MYPAGE_PATH."/admin_menu.php");
		?>
	</div>
	<div id="content">
		<?php 
		include_once(TW_MYPAGE_PATH."/admin_head.sub.php");

		$file = TW_DATA_PATH.'/board/boardimg/'.$boardconfig['fileurl1'];
		if(is_file($file) && $boardconfig['fileurl1']) {
			$file_url = TW_DATA_URL.'/board/boardimg/'.$boardconfig['fileurl1'];
			echo '<p><img src="'.$file_url.'"></p>';
		}
		?>
