<?php
include_once("_common.php");
include_once("admin_access.php");

check_demo();

$mb = get_member_no($index_no);

if($mb['id'] == 'admin')
	alert('관리자는 탈퇴하실 수 없습니다.');

$banner_dir = TW_DATA_PATH."/banner";
$goods_dir  = TW_DATA_PATH."/goods";

// 회원 탈퇴시 레그구성 변경 즉! a > b > c : b가 탈퇴를 하면 c는 a아래로 붙음
$sql = "select id from shop_member where pt_id='$mb[id]'";
$res = sql_query($sql);
while($row = sql_fetch_array($res)) {
	$sql = "update shop_member set pt_id='$mb[pt_id]' where id='$row[id]'";
	sql_query($sql);

	$memo = $mb['id']."회원이 탈퇴하여 추천인".$mb['pt_id']." 변경 되었습니다.";
	$sql = "insert into shop_leave_log ( new_id, old_id, check_id, wdate, memo )
			values ( '$mb[pt_id]','$mb[id]','$row[id]','$server_time','$memo' )";
	sql_query($sql);
}

// 회원 탈퇴정보 기록저장
$ml = sql_fetch("select mb_no from shop_member_leave where mb_no='$mb[index_no]'");
if(!$ml['mb_no']) {
	$sql = "insert into shop_member_leave
	           set mb_no = '$mb[index_no]',
				   memo = '관리자 강제 영구탈퇴처리',
				   wdate = '$server_time',
				   isover = '1',
				   mb_id = '$mb[id]',
				   name	= '$mb[name]',
				   dwdate = '$time_ymd' ";
	sql_query($sql);
} else {
	$sql = " update shop_member_leave
			    set isover = '1',
					dwdate = '$time_ymd'
			  where mb_no = '$mb[index_no]' ";
	sql_query($sql);
}

// 카테고리 테이블 DROP
$target_table = 'shop_cate_'.$mb['id'];
sql_query(" DROP TABLE {$target_table} ", FALSE);

// 카테고리 폴더 전체 삭제
if($mb['id']) {
	rm_rf(TW_DATA_PATH.'/category/'.$mb['id']);
}

sql_query("delete from shop_partner where mb_id='$mb[id]'"); // 가맹점정보
sql_query("delete from shop_partner_term where mb_id='$mb[id]'"); // 가맹점 연장신청
sql_query("delete from shop_partner_pay where mb_no='$mb[index_no]'"); // 가맹점 수수료정보
sql_query("delete from shop_partner_payuse where mb_id='$mb[id]'"); // 가맹점 수수료정산내역
sql_query("delete from shop_leave_log where check_id='$mb[id]'"); // 가맹점 추천인변경로그
sql_query("delete from shop_partner_paylog where mb_id='$mb[id]'"); // 가맹점 전체실적로그
sql_query("delete from shop_partner_payrun where mb_id='$mb[id]'"); // 가맹점 정산요청

// 로고
$lg = sql_fetch("select * from shop_logo where mb_id='$mb[id]'");
if($lg['basic_logo']) @unlink($banner_dir.'/'.$lg['basic_logo']);
if($lg['mobile_logo']) @unlink($banner_dir.'/'.$lg['mobile_logo']);
if($lg['sns_logo']) @unlink($banner_dir.'/'.$lg['sns_logo']);
if($lg['favicon_ico']) @unlink($banner_dir.'/'.$lg['favicon_ico']);
sql_query("delete from shop_logo where mb_id='$mb[id]'");

// 배너
$sql = "select * from shop_banner where mb_id='$mb[id]' ";
$res = sql_query($sql);
for($i=0; $row=sql_fetch_array($res); $i++) {
	if($row['bn_file']) @unlink($banner_dir.'/'.$row['bn_file']);
}
sql_query("delete from shop_banner where mb_id='$mb[id]'");

// 메인배너
$sql = "select * from shop_banner_slider where mb_id='$mb[id]' ";
$res = sql_query($sql);
for($i=0; $row=sql_fetch_array($res); $i++) {
	if($row['bn_file']) @unlink($banner_dir.'/'.$row['bn_file']);
}
sql_query("delete from shop_banner_slider where mb_id='$mb[id]'");

// 공급사 상품정보
$sr = sql_fetch("select sup_code from shop_seller where mb_id='$mb[id]'");
$sql = "select * from shop_goods where mb_id='$sr[sup_code]' or mb_id='$mb[id]' ";
$res = sql_query($sql);
for($i=0; $row=sql_fetch_array($res); $i++) {
	$dir_list = $goods_dir.'/'.$row['index_no'];

	if($row['simg1']) {
		@unlink($goods_dir."/".$row['simg1']);
		delete_item_thumbnail($dir_list, $row['simg1']);
	}
	if($row['simg2']) {
		@unlink($goods_dir."/".$row['simg2']);
		delete_item_thumbnail($dir_list, $row['simg2']);
	}
	if($row['simg3']) {
		@unlink($goods_dir."/".$row['simg3']);
		delete_item_thumbnail($dir_list, $row['simg3']);
	}
	if($row['simg4']) {
		@unlink($goods_dir."/".$row['simg4']);
		delete_item_thumbnail($dir_list, $row['simg4']);
	}
	if($row['simg5']) {
		@unlink($goods_dir."/".$row['simg5']);
		delete_item_thumbnail($dir_list, $row['simg5']);
	}
	if($row['simg6']) {
		@unlink($goods_dir."/".$row['simg6']);
		delete_item_thumbnail($dir_list, $row['simg6']);
	}
	if($row['bimg1']) {
		@unlink($goods_dir."/".$row['bimg1']);
		delete_item_thumbnail($dir_list, $row['bimg1']);
	}
	if($row['bimg2']) {
		@unlink($goods_dir."/".$row['bimg2']);
		delete_item_thumbnail($dir_list, $row['bimg2']);
	}
	if($row['bimg3']) {
		@unlink($goods_dir."/".$row['bimg3']);
		delete_item_thumbnail($dir_list, $row['bimg3']);
	}
	if($row['bimg4']) {
		@unlink($goods_dir."/".$row['bimg4']);
		delete_item_thumbnail($dir_list, $row['bimg4']);
	}
	if($row['bimg5']) {
		@unlink($goods_dir."/".$row['bimg5']);
		delete_item_thumbnail($dir_list, $row['bimg5']);
	}

	// 에디터 이미지 삭제
	delete_editor_image($row['memo']);
}

sql_query("delete from shop_popup where mb_id='$mb[id]'"); // 팝업
sql_query("delete from shop_point where mb_no='$mb[index_no]'"); // 회원 포인트
sql_query("delete from shop_goods where mb_id='$sr[sup_code]'"); // 공급사 상품
sql_query("delete from shop_goods where mb_id='$mb[id]'"); // 가맹점 상품
sql_query("delete from shop_goods_type where mb_id='$mb[id]'"); // 상품진열관리
sql_query("delete from shop_goods_qa where mb_id='$mb[id]'"); // 상품문의
sql_query("delete from shop_brand where mb_id='$sr[sup_code]'"); // 브랜드정보
sql_query("delete from shop_brand where mb_id='$mb[id]'"); // 브랜드정보
sql_query("delete from shop_seller where mb_id='$mb[id]'"); // 공급사 신청정보
sql_query("delete from shop_seller_cal where mb_id='$mb[id]'"); // 공급사 정산내역
sql_query("delete from shop_visit where mb_id='$mb[id]'"); // 접속자집계
sql_query("delete from shop_visit_sum where mb_id='$mb[id]'"); // 접속자집계
sql_query("delete from shop_keyword where pt_id='$mb[id]'"); // 키워드
sql_query("delete from shop_member where index_no='$mb[index_no]'"); // 회원정보

if($replace == 'alldel') {
	alert("정상적으로 처리 되었습니다.","/admin/help.php?code=B$qstr&page=$page");
} else {
	echo "<script>alert('정상적으로 처리 되었습니다.'); top.opener=self;top.close();</script>";
}
?>