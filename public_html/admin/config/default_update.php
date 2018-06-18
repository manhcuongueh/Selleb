<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

if($_POST['mode']=='w') {

	check_admin_token();

	unset($value);
	$value['theme'] = $_POST['theme']; //테마스킨
	$value['mobile_theme'] = $_POST['mobile_theme']; //모바일테마스킨
	update("shop_member",$value,"where id='admin'");

	unset($value);
	$value['de_wish_day'] = $_POST['de_wish_day']; //찜목록 보관일수
	$value['de_cart_day'] = $_POST['de_cart_day']; //장바구니 보관일수
	$value['de_order_day'] = $_POST['de_order_day']; //미입금 주문내역
	$value['de_bank_name'] = $_POST['de_bank_name']; //은행명
	$value['de_bank_account'] = $_POST['de_bank_account']; //은행 계좌번호
	$value['de_bank_holder'] = $_POST['de_bank_holder']; //은행 예금주
	$value['de_review_wr_use'] = $_POST['de_review_wr_use']; //구매후기 노출
	$value['de_board_wr_use'] = $_POST['de_board_wr_use']; //게시판글 노출
	$value['cf_logo_wpx'] = $_POST['cf_logo_wpx']; //PC 쇼핑몰로고(가로)
	$value['cf_logo_hpx'] = $_POST['cf_logo_hpx']; //PC 쇼핑몰로고(세로)
	$value['cf_mobile_logo_wpx'] = $_POST['cf_mobile_logo_wpx']; //모바일 쇼핑몰로고(가로)
	$value['cf_mobile_logo_hpx'] = $_POST['cf_mobile_logo_hpx']; //모바일 쇼핑몰로고(세로)
	$value['cf_slider_wpx'] = $_POST['cf_slider_wpx']; //PC 메인배너(가로)
	$value['cf_slider_hpx'] = $_POST['cf_slider_hpx']; //PC 메인배너(세로)
	$value['cf_mobile_slider_wpx'] = $_POST['cf_mobile_slider_wpx']; //모바일 메인배너(가로)
	$value['cf_mobile_slider_hpx'] = $_POST['cf_mobile_slider_hpx']; //모바일 메인배너(세로)
	$value['cf_item_small_wpx'] = $_POST['cf_item_small_wpx']; //상품 소이미지(가로)
	$value['cf_item_small_hpx'] = $_POST['cf_item_small_hpx']; //상품 소이미지(세로)
	$value['cf_item_medium_wpx'] = $_POST['cf_item_medium_wpx']; //상품 중이미지(가로)
	$value['cf_item_medium_hpx'] = $_POST['cf_item_medium_hpx']; //상품 중이미지(세로)
	update("shop_default", $value);

	unset($value);
	$value['login_point'] = conv_number($_POST['login_point']); //로그인 포인트
	$value['usepoint'] = conv_number($_POST['usepoint']); //구매시포인트
	$value['usepoint_yes'] = $_POST['usepoint_yes']; //포인트결제 사용
	$value['admin_shop_url'] = $_POST['admin_shop_url']; //대표도메인
	$value['admin_reg_yes'] = $_POST['admin_reg_yes']; //본사몰 회원가입 여부
	$value['admin_reg_msg'] = $_POST['admin_reg_msg']; //본사몰 회원가입 거부시 출력 메시지
	$value['sp_mouse'] = $_POST['sp_mouse']; //마우스차단여부
	$value['dan'] = $_POST['dan']; //구매결정
	$value['shop_name'] = $_POST['shop_name']; //쇼핑몰명
	$value['shop_name_us'] = $_POST['shop_name_us']; //쇼핑몰 영문명
	$value['company_type'] = $_POST['company_type']; //사업자유형
	$value['company_name'] = $_POST['company_name']; //회사명
	$value['company_saupja_no'] = $_POST['company_saupja_no']; //사업자등록번호
	$value['tongsin_no'] = $_POST['tongsin_no']; //통신판매신고번호
	$value['company_tel'] = $_POST['company_tel']; //대표전화
	$value['company_fax'] = $_POST['company_fax']; //대표팩스
	$value['company_item'] = $_POST['company_item']; //업태
	$value['company_service'] = $_POST['company_service']; //종목
	$value['company_owner'] = $_POST['company_owner']; //대표자명
	$value['info_name'] = $_POST['info_name']; //정보책임자 이름
	$value['info_email'] = $_POST['info_email']; //정보책임자 e-mail	
	$value['company_zip'] = $_POST['company_zip']; //사업장우편번호
	$value['company_addr'] = $_POST['company_addr']; //사업장주소
	$value['company_hours'] = $_POST['company_hours']; //CS 상담가능시간
	$value['company_lunch'] = $_POST['company_lunch']; //CS 점심시간
	$value['company_close'] = $_POST['company_close']; //CS 휴무일
	$value['sp_intro'] = $_POST['sp_intro']; // 메인인트로 적용
	$value['sp_app'] = $_POST['sp_app']; //승인 후 로그인
	$value['sp_app_super'] = $_POST['sp_app_super']; //회원 승인권한
	$value['sp_coupon'] = $_POST['sp_coupon']; //쿠폰 (온라인) 사용여부
	$value['sp_gift'] = $_POST['sp_gift']; //쿠폰 (인쇄용) 사용여부
	update("shop_config", $value);
}

$goods_dir  = TW_DATA_PATH."/goods";
$banner_dir = TW_DATA_PATH."/banner";
$brand_dir  = TW_DATA_PATH."/brand";
$order_dir	= TW_DATA_PATH."/order";

if($_REQUEST['reset_type']=='1') { // 회원초기화

	// 회원 로고
	$sql = "select * from shop_logo where mb_id!='admin' ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		if($row['basic_logo']) { @unlink($banner_dir."/".$row['basic_logo']); }
		if($row['mobile_logo']) { @unlink($banner_dir."/".$row['mobile_logo']); }
		if($row['sns_logo']) { @unlink($banner_dir."/".$row['sns_logo']); }
		if($row['favicon_ico']) { @unlink($banner_dir."/".$row['favicon_ico']); }
	}
	sql_query("delete from shop_logo where mb_id!='admin' ");

	// 회원 배너
	$sql = "select * from shop_banner where mb_id!='admin' ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		if($row['bn_file']) { @unlink($banner_dir."/".$row['bn_file']); }
	}
	sql_query("delete from shop_banner where mb_id!='admin' ");

	// 회원 메인배너
	$sql = "select * from shop_banner_slider where mb_id!='admin' ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		if($row['bn_file']) { @unlink($banner_dir."/".$row['bn_file']); }
	}
	sql_query("delete from shop_banner_slider where mb_id!='admin' ");

	$sql = "select id from shop_member where grade between 2 and 6 ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$mb_id = $row['id'];

		// 카테고리 테이블 DROP
		$target_table = 'shop_cate_'.$mb_id;
		sql_query(" drop table {$target_table} ", FALSE);

		// 카테고리 폴더 전체 삭제
		if($mb_id) {
			rm_rf(TW_DATA_PATH.'/category/'.$mb_id);
		}
	}

	sql_query("delete from shop_member where id !='admin'"); //회원정보 내역
	sql_query("ALTER TABLE shop_member AUTO_INCREMENT=2");
	sql_query("delete from shop_member_leave"); //탈퇴신청 내역
	sql_query("ALTER TABLE shop_member_leave AUTO_INCREMENT=1");

	// 가맹점정보
	$sql = "select * from shop_partner ";
	$rst = sql_query($sql);
	for($i=0; $row=sql_fetch_array($rst); $i++) {
		delete_editor_image($row['sp_send_cost']);
		delete_editor_image($row['mo_send_cost']);
	}
	sql_query("delete from shop_partner");
	sql_query("ALTER TABLE shop_partner AUTO_INCREMENT=1");
	sql_query("delete from shop_partner_pay"); //가맹점 수수료 적립내역
	sql_query("ALTER TABLE shop_partner_pay AUTO_INCREMENT=1");
	sql_query("delete from shop_partner_payuse"); //가맹점 수수료 지급내역
	sql_query("ALTER TABLE shop_partner_payuse AUTO_INCREMENT=1");
	sql_query("delete from shop_partner_payrun"); //실시간 출금요청
	sql_query("ALTER TABLE shop_partner_payrun AUTO_INCREMENT=1");
	sql_query("delete from shop_partner_term"); //관리비연장 신청내역
	sql_query("ALTER TABLE shop_partner_term AUTO_INCREMENT=1");
	sql_query("delete from shop_point"); //포인트내역
	sql_query("ALTER TABLE shop_point AUTO_INCREMENT=1");
	sql_query("delete from shop_partner_paylog"); //가맹점 수수료적립 로그
	sql_query("ALTER TABLE shop_partner_paylog AUTO_INCREMENT=1");
	sql_query("delete from shop_leave_log"); //추천인변경 로그
	sql_query("ALTER TABLE shop_leave_log AUTO_INCREMENT=1");
	sql_query("delete from shop_joincheck"); //실명인증
	sql_query("ALTER TABLE shop_joincheck AUTO_INCREMENT=1");

	// 입점(공급업체) 정보
	$sql = "select * from shop_seller ";
	$rst = sql_query($sql);
	for($i=0; $row=sql_fetch_array($rst); $i++) {
		delete_editor_image($row['sp_send_cost']);
		delete_editor_image($row['mo_send_cost']);
	}
	sql_query("delete from shop_seller");
	sql_query("ALTER TABLE shop_seller AUTO_INCREMENT=1");
	sql_query("delete from shop_seller_cal"); //공급업체 정산내역
	sql_query("ALTER TABLE shop_seller_cal AUTO_INCREMENT=1");

	//상품정보
	$sql = "select * from shop_goods where mb_id!='admin' ";
	$rst = sql_query($sql);
	for($i=0; $row=sql_fetch_array($rst); $i++) {

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
	sql_query("delete from shop_goods where mb_id!='admin'");
	sql_query("delete from shop_goods_type where mb_id!='admin'");

	// 팝업삭제
	$sql = "select * from shop_popup where mb_id!='admin' ";
	$rst = sql_query($sql);
	for($i=0; $row=sql_fetch_array($rst); $i++) {
		delete_editor_image($row['memo']);
	}
	sql_query("delete from shop_popup where mb_id!='admin'");

	sql_query("delete from shop_goods_qa");// 상품문의 테이블
	sql_query("ALTER TABLE shop_goods_qa AUTO_INCREMENT=1");
	sql_query("delete from shop_brand where mb_id!='admin'"); // 브랜드정보
	sql_query("delete from shop_keyword where pt_id!='admin'"); // 검색 키워드
	sql_query("delete from shop_visit where mb_id!='admin'"); // 접속자집계
	sql_query("delete from shop_visit_sum where mb_id!='admin'"); // 접속자집계
} else if($_REQUEST['reset_type']=='2') { // 주문초기화
	sql_query("delete from shop_cart"); //장바구니 테이블
	sql_query("delete from shop_order"); //주문 테이블
	sql_query("delete from shop_order_cancel"); //주문취소 보관테이블
	sql_query("delete from shop_order_goods"); //상품주문시 상품정보 보관테이블
	sql_query("delete from shop_order_memo"); //주문메모

	sql_query("ALTER TABLE shop_cart AUTO_INCREMENT=1");
	sql_query("ALTER TABLE shop_order AUTO_INCREMENT=1");
	sql_query("ALTER TABLE shop_order_cancel AUTO_INCREMENT=1");
	sql_query("ALTER TABLE shop_order_goods AUTO_INCREMENT=1");
	sql_query("ALTER TABLE shop_order_memo AUTO_INCREMENT=1");

	rm_rf($order_dir);
	if(!is_dir($order_dir)) {
		@mkdir($order_dir, TW_DIR_PERMISSION);
		@chmod($order_dir, TW_DIR_PERMISSION);
	}
} else if($_REQUEST['reset_type']=='3') { // 가맹점 수수료 초기화
	sql_query("delete from shop_partner_pay"); //가맹점 수수료 적립내역
	sql_query("delete from shop_partner_payuse"); //가맹점 수수료 지급내역
	sql_query("delete from shop_partner_payrun"); //실시간 출금요청
	sql_query("delete from shop_partner_paylog"); //가맹점 수수료적립 로그
	sql_query("update shop_member set pay='0'"); //모든회원 및 가맹점 수수료 초기화
	sql_query("delete from shop_partner_term"); //관리비연장 신청내역

	sql_query("ALTER TABLE shop_partner_pay AUTO_INCREMENT=1");
	sql_query("ALTER TABLE shop_partner_payuse AUTO_INCREMENT=1");
	sql_query("ALTER TABLE shop_partner_payrun AUTO_INCREMENT=1");
	sql_query("ALTER TABLE shop_partner_paylog AUTO_INCREMENT=1");
	sql_query("ALTER TABLE shop_partner_term AUTO_INCREMENT=1");
} else if($_REQUEST['reset_type']=='4') { // 상품초기화
	sql_query("delete from shop_goods"); //상품 테이블
	sql_query("delete from shop_goods_type"); //상품 진열관리
	sql_query("delete from shop_goods_qa"); // 상품문의 테이블
	sql_query("delete from shop_goods_cate"); //상품 카테고리
	sql_query("delete from shop_goods_review"); //상품평관리
	sql_query("delete from shop_cart where ct_select='0'"); //장바구니
	sql_query("delete from shop_wish"); //찜목록
	sql_query("delete from shop_goods_option"); //옵션
	sql_query("delete from shop_goods_relation");// 관련상품
	//sql_query("delete from shop_order"); //주문테이블
	//sql_query("delete from shop_order_cancel"); //주문취소
	//sql_query("delete from shop_order_goods"); //상품주문시 상품정보 보관테이블
	//sql_query("delete from shop_order_memo"); //주문 관리자메모	

	sql_query("ALTER TABLE shop_goods AUTO_INCREMENT=1");
	sql_query("ALTER TABLE shop_goods_type AUTO_INCREMENT=1");
	sql_query("ALTER TABLE shop_goods_qa AUTO_INCREMENT=1");
	sql_query("ALTER TABLE shop_goods_cate AUTO_INCREMENT=1");
	sql_query("ALTER TABLE shop_goods_review AUTO_INCREMENT=1");
	sql_query("ALTER TABLE shop_wish AUTO_INCREMENT=1");
	sql_query("ALTER TABLE shop_goods_option AUTO_INCREMENT=1");

	rm_rf($goods_dir);
	if(!is_dir($goods_dir)) {
		@mkdir($goods_dir, TW_DIR_PERMISSION);
		@chmod($goods_dir, TW_DIR_PERMISSION);
	}

	/*
	rm_rf($order_dir);
	if(!is_dir($order_dir)) {
		@mkdir($order_dir, TW_DIR_PERMISSION);
		@chmod($order_dir, TW_DIR_PERMISSION);
	}
	*/
} else if($_REQUEST['reset_type']=='5') {// 회원포인트 초기화
	sql_query("update shop_member set point='0'"); //회원 전체 포인트
	sql_query("delete from shop_point"); //포인트 내역
	sql_query("ALTER TABLE shop_point AUTO_INCREMENT=1");
} else if($_REQUEST['reset_type']=='6') { // 접속통계 초기화
	sql_query("delete from shop_visit");
	sql_query("ALTER TABLE shop_visit AUTO_INCREMENT=1");
	sql_query("delete from shop_visit_sum");
	sql_query("ALTER TABLE shop_visit_sum AUTO_INCREMENT=1");
} else if($_REQUEST['reset_type']=='7') { // 검색키워드 초기화
	sql_query("delete from shop_keyword");
	sql_query("ALTER TABLE shop_keyword AUTO_INCREMENT=1");
} else if($_REQUEST['reset_type']=='8') { // 브랜드 초기화
	sql_query("delete from shop_brand");
	sql_query("ALTER TABLE shop_brand AUTO_INCREMENT=1");

	rm_rf($brand_dir);
	if(!is_dir($brand_dir)) {
		@mkdir($brand_dir, TW_DIR_PERMISSION);
		@chmod($brand_dir, TW_DIR_PERMISSION);
	}
}

goto_url('../config.php?code=default');
?>