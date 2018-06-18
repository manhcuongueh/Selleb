<?php
$dbconfig_file = $_SERVER["DOCUMENT_ROOT"]."/dbconfig.php";

// install 체크
if(file_exists("$dbconfig_file")) {
    header("Content-Type: text/html; charset=utf-8");
	if(is_dir($_SERVER["DOCUMENT_ROOT"]."/install"))
		die("install 디렉토리를 삭제하여야 정상 실행됩니다.");

	include_once("$dbconfig_file");
	$connect_db = @mysql_connect($hostName,$userName,$userPassword);
	$select_db = mysql_select_db($dbName,$connect_db);
    if(!$select_db)
        die("DB 접속 오류");
} else {
	header("Content-Type: text/html; charset=utf-8");
	die("DB 설정 파일이 존재하지 않습니다. 프로그램 설치 후 실행하시기 바랍니다.");
}

@mysql_query(" set names utf8 ");

// 항상 "www" 를 타고 들어오는 도메인은 "www" 를 제거
if(preg_match("/www\./i", $_SERVER['HTTP_HOST'])):
	header("Location:http://".preg_replace("/www\./i", "", $_SERVER['HTTP_HOST']).$_SERVER['REQUEST_URI']);
endif;
?>
