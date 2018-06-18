<?php
if(!defined('_TUBEWEB_')) exit;

$pg_navi = '가맹점 관리자';

// 재고부족 상품
$sql = " select count(*) as cnt
		   from shop_goods
		  where mb_id='$member[id]'
			and stock_qty <= noti_qty and stock_mod = 1 and opt_subject = '' ";
$row = sql_fetch($sql);
$stock1Cnt = (int)$row['cnt'];

// 옵션재고부족 상품
$sql = " select count(*) as cnt 
		   from shop_goods_option a left join shop_goods b on (a.gs_id=b.index_no)
		  where b.mb_id='$member[id]'
		    and a.io_stock_qty <= a.io_noti_qty and a.io_noti_qty != '999999999' ";
$row = sql_fetch($sql);
$stock2Cnt = (int)$row['cnt'];

$sql_where = " where gs_se_id='$member[id]' ";

$row = admin_order_status_sum("$sql_where and orderdate_s='$time_ymd' and dan!='0'"); // 오늘접수 된 주문
$tdayCnt = (int)$row['cnt'];

$row = admin_order_status_sum("$sql_where and dan='1' "); // 총 주문접수
$dan1Cnt = (int)$row['cnt'];

$row = admin_order_status_sum("$sql_where and dan='2' "); // 총 입금확인
$dan2Cnt = (int)$row['cnt'];

$row = admin_order_status_sum("$sql_where and dan='3' "); // 총 배송대기
$dan3Cnt = (int)$row['cnt'];

$row = admin_order_status_sum("$sql_where and dan='4' "); // 총 배송중
$dan4Cnt = (int)$row['cnt'];

$row = admin_order_status_sum("$sql_where and dan='5' "); // 총 배송완료
$dan5Cnt = (int)$row['cnt'];

$row = admin_order_status_sum("$sql_where and dan='6' "); // 총 반품처리
$dan6Cnt = (int)$row['cnt'];

$row = admin_order_status_sum("$sql_where and dan='7' "); // 총 입금후 주문취소
$dan7Cnt = (int)$row['cnt'];

$row = admin_order_status_sum("$sql_where and dan='8' "); // 총 입금전 주문취소
$dan8Cnt = (int)$row['cnt'];

$row = admin_order_status_sum("$sql_where and dan='10' "); // 총 교환처리
$dan10Cnt = (int)$row['cnt'];

$row = admin_order_status_sum("$sql_where and dan!='0' "); // 총 주문내역
$cumuCnt = (int)$row['cnt'];

// 총 관리자메모
$row = sql_fetch("select count(*) as cnt from shop_order_memo $sql_where ");
$memoCnt = (int)$row['cnt'];

// 총 주문취소 요청
$sql_cancel = " from shop_order_cancel where ca_yn='0' and ca_it_aff='1' and ca_cancel_use ='주문취소' ";
$row = sql_fetch("select count(*) as cnt $sql_cancel and ca_it_seller ='$member[id]' ");
$cancelCnt = (int)$row['cnt'];

// 총 상품문의
$row = sql_fetch("select count(*) as cnt from shop_goods_qa $sql_where ");
$qaCnt = (int)$row['cnt'];

// 총 상품평점
$row = sql_fetch("select count(*) as cnt from shop_goods_review $sql_where ");
$useCnt = (int)$row['cnt'];

function printMenu1($key, $subject)
{	
	$svc_class = 'pmenu'.$key;
	if(get_cookie("ck_{$svc_class}")) {
		$svc_class .= ' menu_close';
	}

	return '<dt class="'.$svc_class.' menu_toggle">'.$subject.'</dt>';
}

function printMenu2($key, $subject, $url, $menu_cnt='')
{	
	$svc_class = 'pmenu'.$key;
	if(get_cookie("ck_{$svc_class}")) {
		$svc_class .= ' menu_close';
	}

	$current_class = '';
	$count_class = '';
	if(is_numeric($menu_cnt)) {
		if($menu_cnt > 0) 
			$current_class = ' class="snb_air"';
		$count_class = '<em'.$current_class.'>'.$menu_cnt.'</em>';
	}

	return '<dd class="'.$svc_class.'"><a href="'.$url.'">'.$subject.$count_class.'</a></dd>';
}

define('asideUrl', TW_MYPAGE_URL.'/page.php');
define('boardUrl', TW_BBS_URL.'/list.php');
?>

<dl>
	<?php echo printMenu1(1, '기본환경 설정'); ?>
	<?php echo printMenu2(1, '기본정보 관리', asideUrl.'?code=partner_info'); ?>
	<?php if($config['p_month'] == 'y') { // 월관리비를 사용중인가? ?>	
	<?php echo printMenu2(1, '가맹점 연장신청', asideUrl.'?code=partner_term'); ?>
	<?php } ?>
	<?php echo printMenu2(1, '검색엔진 최적화(SEO) 설정', asideUrl.'?code=partner_meta'); ?>
	<?php echo printMenu2(1, '소셜 네트워크 설정', asideUrl.'?code=partner_sns'); ?>
	<?php if($p_use_good) { // 상품판매 권한이있나? ?>
	<?php echo printMenu2(1, '배송/교환/반품 설정', asideUrl.'?code=partner_conf'); ?>
	<?php } ?>
	<?php echo printMenu2(1, '쇼핑몰 약관 설정', asideUrl.'?code=partner_agree'); ?>
	<?php echo printMenu2(1, '검색키워드 관리', asideUrl.'?code=partner_keyword'); ?>
	<?php if($p_use_pg) { // 개별 PG결제 권한이있나? ?>	
	<?php echo printMenu1(2, '결제관리'); ?>
	<?php echo printMenu2(2, '전자결제 (PG) 설정', asideUrl.'?code=partner_pg'); ?>
	<?php echo printMenu2(2, '카카오페이 설정', asideUrl.'?code=partner_kakaopay'); ?>
	<?php echo printMenu2(2, '네이버페이 설정', asideUrl.'?code=partner_naverpay'); ?>
	<?php } ?>
	<?php echo printMenu1(3, '디자인관리'); ?>
	<?php echo printMenu2(3, '로고 관리', asideUrl.'?code=partner_logo'); ?>
	<?php echo printMenu2(3, '메인배너 관리', asideUrl.'?code=partner_slider_list'); ?>
	<?php echo printMenu2(3, '기타배너 관리', asideUrl.'?code=partner_banner_list'); ?>
	<?php echo printMenu2(3, '메인진열 관리', asideUrl.'?code=partner_best_item'); ?>
	<?php echo printMenu2(3, '팝업 관리', asideUrl.'?code=partner_popup_list'); ?>
	<?php echo printMenu1(4, '회원관리'); ?>
	<?php echo printMenu2(4, '회원목록', asideUrl.'?code=partner_member_list'); ?>
	<?php echo printMenu2(4, '신규 회원등록', asideUrl.'?code=partner_register_form'); ?>
	<?php echo printMenu2(4, '트리 회원조회', asideUrl.'?code=partner_tree'); ?>
	<?php echo printMenu2(4, '일별 가입통계분석', asideUrl.'?code=partner_stats_day'); ?>
	<?php echo printMenu2(4, '월별 가입통계분석', asideUrl.'?code=partner_stats_month'); ?>
	<?php echo printMenu2(4, '접속자검색', asideUrl.'?code=partner_visit'); ?>
	<?php echo printMenu1(5, '본사 상품관리'); ?>
	<?php echo printMenu2(5, '본사 상품목록', asideUrl.'?code=partner_goods_admlist'); ?>
	<?php echo printMenu2(5, '본사 상품판매실적', asideUrl.'?code=partner_order_admlist'); ?>
	<?php echo printMenu2(5, '상품 진열관리', asideUrl.'?code=partner_goods_type'); ?>
	<?php echo printMenu1(6, '카테고리 관리'); ?>
	<?php echo printMenu2(6, '카테고리 관리', asideUrl.'?code=partner_category'); ?>
	<?php echo printMenu2(6, '카테고리 순위관리', asideUrl.'?code=partner_category_view'); ?>
	<?php if($p_use_good) { // 상품판매 권한이있나? ?>
	<?php echo printMenu1(7, '상품관리'); ?>
	<?php echo printMenu2(7, '전체 상품관리', asideUrl.'?code=partner_goods_list'); ?>
	<?php echo printMenu2(7, '상품 재고관리', asideUrl.'?code=partner_goods_stock', $stock1Cnt); ?>
	<?php echo printMenu2(7, '상품 옵션재고관리', asideUrl.'?code=partner_goods_optstock', $stock2Cnt); ?>
	<?php echo printMenu2(7, '브랜드관리', asideUrl.'?code=partner_goods_brand'); ?>
	<?php echo printMenu2(7, '상품 문의관리', asideUrl.'?code=partner_goods_qa', $qaCnt); ?>
	<?php echo printMenu2(7, '상품 평점관리', asideUrl.'?code=partner_goods_review', $useCnt); ?>
	<?php echo printMenu1(8, '일괄처리'); ?>
	<?php echo printMenu2(8, '상품 일괄등록', asideUrl.'?code=partner_goods_xls_reg'); ?>
	<?php echo printMenu2(8, '상품 일괄수정', asideUrl.'?code=partner_goods_xls_mod'); ?>
	<?php echo printMenu1(9, '주문관리'); ?>
	<?php echo printMenu2(9, '오늘 접수된주문', asideUrl.'?code=partner_odr&set=today', $tdayCnt); ?>
	<?php echo printMenu2(9, '1단계 주문확인', asideUrl.'?code=partner_odr&set=1', $dan1Cnt); ?>
	<?php echo printMenu2(9, '2단계 입금확인', asideUrl.'?code=partner_odr&set=2', $dan2Cnt); ?>
	<?php echo printMenu2(9, '3단계 배송대기', asideUrl.'?code=partner_odr&set=3', $dan3Cnt); ?>
	<?php echo printMenu2(9, '4단계 배송중', asideUrl.'?code=partner_odr&set=4', $dan4Cnt); ?>
	<?php echo printMenu2(9, '5단계 배송완료', asideUrl.'?code=partner_odr&set=5', $dan5Cnt); ?>
	<?php echo printMenu2(9, '입금후 주문취소', asideUrl.'?code=partner_odr&set=7', $dan7Cnt); ?>
	<?php echo printMenu2(9, '입금전 주문취소', asideUrl.'?code=partner_odr&set=8', $dan8Cnt); ?>
	<?php echo printMenu2(9, '상품 반품목록', asideUrl.'?code=partner_odr&set=6', $dan6Cnt); ?>
	<?php echo printMenu2(9, '상품 교환목록', asideUrl.'?code=partner_odr&set=10', $dan10Cnt); ?>
	<?php echo printMenu2(9, '주문 취소요청', asideUrl.'?code=partner_odr_cancel', $cancelCnt); ?>
	<?php echo printMenu2(9, '전체 주문처리현황', asideUrl.'?code=partner_odr&set=whole', $cumuCnt); ?>
	<?php echo printMenu2(9, '관리자메모 확인', asideUrl.'?code=partner_odr_memo', $memoCnt); ?>
	<?php } ?>
	<?php echo printMenu1(10, '수수료 리포트'); ?>
	<?php echo printMenu2(10, '수익금 정산', asideUrl.'?code=partner_stats'); ?>
	<?php echo printMenu2(10, '수익금 로그분석', asideUrl.'?code=partner_record'); ?>
	<?php echo printMenu2(10, '공지사항', boardUrl.'?boardid=22'); ?>
	<?php echo printMenu2(10, '질문과답변', boardUrl.'?boardid=36'); ?>
</dl>
