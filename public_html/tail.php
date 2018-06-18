<?php
if(!defined('_TUBEWEB_')) exit;

include_once($theme_path.'/tail.skin.php'); // 하단

// BODY 내부 메시지
if($config['tail_script']) {
	echo $config['tail_script'].PHP_EOL;
}

include_once(TW_PATH."/tail.sub.php");
?>