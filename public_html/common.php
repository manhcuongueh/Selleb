<?php
include_once("$dir/inc/connect.php"); // 디비연결파일
include_once("$dir/inc/session.php"); // 세션설정파일
include_once("$dir/inc/config.php"); // 쇼핑몰 환경파일
include_once("$dir/inc/extend.php"); // 가맹점관련 환경파일

if(defined('TW_VIEW')) {
	if(get_cookie('ss_pr_idx')) {
		$arr_ss_pr_idx = get_cookie('ss_pr_idx');
		$arr_tmps = explode("|",$arr_ss_pr_idx);
		if(!in_array($_GET['index_no'],$arr_tmps)) {
			$ss_pr_idx = $_GET['index_no']."|".get_cookie('ss_pr_idx');
			set_cookie('ss_pr_idx', $ss_pr_idx, 86400 * 1);
		}
	} else {
		set_cookie('ss_pr_idx', $_GET['index_no'], 86400 * 1);
	}
}
?>