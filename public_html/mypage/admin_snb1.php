<?php
if(!defined('_TUBEWEB_')) exit;

$pg_navi = '공급사 관리자';

// 재고부족 상품
$sql = " select count(*) as cnt
		   from shop_goods
		  where mb_id='$seller[sup_code]'
			and stock_qty <= noti_qty and stock_mod = 1 and opt_subject = '' ";
$row = sql_fetch($sql);
$stock1Cnt = (int)$row['cnt'];

// 옵션재고부족 상품
$sql = " select count(*) as cnt 
		   from shop_goods_option a left join shop_goods b on (a.gs_id=b.index_no)
		  where b.mb_id='$seller[sup_code]'
		    and a.io_stock_qty <= a.io_noti_qty and a.io_noti_qty != '999999999' ";
$row = sql_fetch($sql);
$stock2Cnt = (int)$row['cnt'];

$sql_where = " where gs_se_id='$seller[sup_code]' ";

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
$sql_cancel = " from shop_order_cancel where ca_yn='0' and ca_it_aff='0' and ca_cancel_use ='주문취소' ";
$row = sql_fetch("select count(*) as cnt $sql_cancel and ca_it_seller ='$seller[sup_code]' ");
$cancelCnt = (int)$row['cnt'];

// 총 상품문의
$row = sql_fetch("select count(*) as cnt from shop_goods_qa $sql_where ");
$qaCnt = (int)$row['cnt'];

// 총 상품평점
$row = sql_fetch("select count(*) as cnt from shop_goods_review $sql_where ");
$useCnt = (int)$row['cnt'];

function printMenu1($key, $subject)
{	
	$svc_class = 'smenu'.$key;
	if(get_cookie("ck_{$svc_class}")) {
		$svc_class .= ' menu_close';
	}

	return '<dt class="'.$svc_class.' menu_toggle">'.$subject.'</dt>';
}

function printMenu2($key, $subject, $url, $menu_cnt='')
{	
	$svc_class = 'smenu'.$key;
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
	<?php echo printMenu1(1, '정보관리'); ?>
	<?php echo printMenu2(1, '업체 정보관리', asideUrl.'?code=seller_info'); ?>
	<?php echo printMenu2(1, '업체 배송정책', asideUrl.'?code=seller_conf'); ?>
	<?php echo printMenu1(2, '상품관리'); ?>
	<?php echo printMenu2(2, '전체 상품관리', asideUrl.'?code=seller_goods_list'); ?>
	<?php echo printMenu2(2, '상품 재고관리', asideUrl.'?code=seller_goods_stock', $stock1Cnt); ?>
	<?php echo printMenu2(2, '상품 옵션재고관리', asideUrl.'?code=seller_goods_optstock', $stock2Cnt); ?>
	<?php echo printMenu2(2, '브랜드관리', asideUrl.'?code=seller_goods_brand'); ?>
	<?php echo printMenu2(2, '상품 문의관리', asideUrl.'?code=seller_goods_qa', $qaCnt); ?>
	<?php echo printMenu2(2, '상품 평점관리', asideUrl.'?code=seller_goods_review', $useCnt); ?>
	<?php echo printMenu1(3, '일괄처리'); ?>
	<?php echo printMenu2(3, '상품 엑셀일괄등록', asideUrl.'?code=seller_goods_xls_reg'); ?>
	<?php echo printMenu2(3, '상품 엑셀일괄수정', asideUrl.'?code=seller_goods_xls_mod'); ?>
	<?php echo printMenu1(4, '주문관리'); ?>
	<?php echo printMenu2(4, '오늘 접수된주문', asideUrl.'?code=seller_odr&set=today', $tdayCnt); ?>
	<?php echo printMenu2(4, '1단계 주문확인', asideUrl.'?code=seller_odr&set=1', $dan1Cnt); ?>
	<?php echo printMenu2(4, '2단계 입금확인', asideUrl.'?code=seller_odr&set=2', $dan2Cnt); ?>
	<?php echo printMenu2(4, '3단계 배송대기', asideUrl.'?code=seller_odr&set=3', $dan3Cnt); ?>
	<?php echo printMenu2(4, '4단계 배송중', asideUrl.'?code=seller_odr&set=4', $dan4Cnt); ?>
	<?php echo printMenu2(4, '5단계 배송완료', asideUrl.'?code=seller_odr&set=5', $dan5Cnt); ?>
	<?php echo printMenu2(4, '입금후 주문취소', asideUrl.'?code=seller_odr&set=7', $dan7Cnt); ?>
	<?php echo printMenu2(4, '입금전 주문취소', asideUrl.'?code=seller_odr&set=8', $dan8Cnt); ?>
	<?php echo printMenu2(4, '상품 반품목록', asideUrl.'?code=seller_odr&set=6', $dan6Cnt); ?>
	<?php echo printMenu2(4, '상품 교환목록', asideUrl.'?code=seller_odr&set=10', $dan10Cnt); ?>
	<?php echo printMenu2(4, '주문 취소요청', asideUrl.'?code=seller_odr_cancel', $cancelCnt); ?>
	<?php echo printMenu2(4, '전체 주문처리현황', asideUrl.'?code=seller_odr&set=whole', $cumuCnt); ?>
	<?php echo printMenu2(4, '관리자메모 확인', asideUrl.'?code=seller_odr_memo', $memoCnt); ?>
	<?php echo printMenu1(5, '정산관리'); ?>
	<?php echo printMenu2(5, '매출 통계분석', asideUrl.'?code=seller_stats'); ?>
	<?php echo printMenu2(5, '정산내역', asideUrl.'?code=seller_present'); ?>
	<?php echo printMenu2(5, '공지사항', boardUrl.'?boardid=20'); ?>
	<?php echo printMenu2(5, '질문과답변', boardUrl.'?boardid=21'); ?>
</dl>
