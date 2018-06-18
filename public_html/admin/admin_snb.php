<?php
if(!defined('_TUBEWEB_')) exit;

function printMenu1($svc_class, $subject)
{	
	if(get_cookie("ck_{$svc_class}")) {
		$svc_class .= ' menu_close';
	}

	return '<dt class="'.$svc_class.' menu_toggle">'.$subject.'</dt>';
}

function printMenu2($svc_class, $subject, $url, $menu_cnt='')
{	
	global $pg_title2;

	if(get_cookie("ck_{$svc_class}")) {
		$svc_class .= ' menu_close';
	}

	if($pg_title2 == $subject)
		$svc_class .= ' active';

	$current_class = '';
	$count_class = '';
	if(is_numeric($menu_cnt)) {
		if($menu_cnt > 0) 
			$current_class = ' class="snb_air"';
		$count_class = '<em'.$current_class.'>'.$menu_cnt.'</em>';
	}

	return '<dd class="'.$svc_class.'"><a href="'.$url.'">'.$subject.$count_class.'</a></dd>';
}
?>

<div id="snb">
	<div class="snb_header ico_config">
		<h2><?php echo $snb_icon; ?><?php echo $pg_title; ?></h2>
	</div>
	<?php 
	if($pg_title == ADMIN_MENU1) { ?>
	<dl>
		<?php echo printMenu1('m10', '회원관리'); ?>
		<?php echo printMenu2('m10', ADMIN_MENU1_1, TW_ADMIN_URL.'/member.php?code=list'); ?>
		<?php echo printMenu2('m10', ADMIN_MENU1_2, TW_ADMIN_URL.'/member.php?code=level_form'); ?>
		<?php echo printMenu2('m10', ADMIN_MENU1_3, TW_ADMIN_URL.'/member.php?code=register_form'); ?>
		<?php echo printMenu2('m10', ADMIN_MENU1_4, TW_ADMIN_URL.'/member.php?code=xls'); ?>
		<?php echo printMenu2('m10', ADMIN_MENU1_5, TW_ADMIN_URL.'/member.php?code=month'); ?>
		<?php echo printMenu2('m10', ADMIN_MENU1_6, TW_ADMIN_URL.'/member.php?code=day'); ?>
		<?php echo printMenu1('m20', '포인트관리'); ?>
		<?php echo printMenu2('m20', ADMIN_MENU1_7, TW_ADMIN_URL.'/member.php?code=point'); ?>
	</dl>
	<?php } 
	else if($pg_title == ADMIN_MENU2) { 
		$anewCnt = mm_p("shop_partner");
		$termCnt = mm_p("shop_partner_term");
		$realCnt = mm_p("shop_partner_payrun");
	?>
	<dl>
		<?php echo printMenu1('p10', '가맹점 신청관리'); ?>
		<?php echo printMenu2('p10', ADMIN_MENU2_1, TW_ADMIN_URL.'/partner.php?code=regis'); ?>
		<?php echo printMenu2('p10', ADMIN_MENU2_2, TW_ADMIN_URL.'/partner.php?code=money'); ?>
		<?php echo printMenu2('p10', ADMIN_MENU2_3, TW_ADMIN_URL.'/partner.php?code=level', $anewCnt); ?>	
		<?php if($config['p_month']=='y') { // 월관리비를 사용중인가? ?>
		<?php echo printMenu2('p10', ADMIN_MENU2_4, TW_ADMIN_URL.'/partner.php?code=sin', $termCnt); ?>
		<?php } ?>
		<?php echo printMenu1('p20', '가맹점 운영관리'); ?>
		<?php echo printMenu2('p20', ADMIN_MENU2_5, TW_ADMIN_URL.'/partner.php?code=member'); ?>
		<?php echo printMenu2('p20', ADMIN_MENU2_6, TW_ADMIN_URL.'/partner.php?code=pay_exp'); ?>
		<?php if($config['p_type']=='time') { // 실시간 정산 ?>
		<?php echo printMenu2('p20', ADMIN_MENU2_8, TW_ADMIN_URL.'/partner.php?code=pay_real', $realCnt); ?>
		<?php } else { // 월&주단위 정산 ?>
		<?php echo printMenu2('p20', ADMIN_MENU2_7, TW_ADMIN_URL.'/partner.php?code=pay'); ?>
		<?php } ?>
		<?php echo printMenu2('p20', ADMIN_MENU2_9, TW_ADMIN_URL.'/partner.php?code=pay_log'); ?>
		<?php echo printMenu2('p20', ADMIN_MENU2_10, TW_ADMIN_URL.'/partner.php?code=record'); ?>
		<?php echo printMenu2('p20', ADMIN_MENU2_11, TW_ADMIN_URL.'/partner.php?code=pay_goods'); ?>
		<?php echo printMenu2('p20', ADMIN_MENU2_12, TW_ADMIN_URL.'/partner.php?code=leave'); ?>
		<?php echo printMenu2('p20', ADMIN_MENU2_13, TW_ADMIN_URL.'/partner.php?code=tree'); ?>
	</dl>
	<?php } 
	else if($pg_title == ADMIN_MENU3) {
		$anewCnt = mm_p("shop_seller");
	?>
	<dl>
		<?php echo printMenu1('s10', '공급사 관리'); ?>
		<?php echo printMenu2('s10', ADMIN_MENU3_1, TW_ADMIN_URL.'/seller.php?code=seller', $anewCnt); ?>
		<?php echo printMenu2('s10', ADMIN_MENU3_2, TW_ADMIN_URL.'/seller.php?code=rigister'); ?>
		<?php echo printMenu2('s10', ADMIN_MENU3_4, TW_ADMIN_URL.'/seller.php?code=xls'); ?>
		<?php echo printMenu2('s10', ADMIN_MENU3_3, TW_ADMIN_URL.'/seller.php?code=total'); ?>
	</dl>
	<?php }
	else if($pg_title == ADMIN_MENU4) { ?>
	<dl>
		<?php echo printMenu1('c10', '카테고리 관리'); ?>
		<?php echo printMenu2('c10', ADMIN_MENU4_1, TW_ADMIN_URL.'/category.php?code=cate'); ?>
		<?php echo printMenu2('c10', ADMIN_MENU4_2, TW_ADMIN_URL.'/category.php?code=cate_view'); ?>
	</dl>
	<?php } 
	else if($pg_title == ADMIN_MENU5) { 
		$regCnt = mm_p("shop_goods");
		$qaCnt  = mm_p("shop_goods_qa", "and (left(gs_se_id,3)='AP-' or gs_se_id = 'admin')");
	?>
	<dl>
		<?php echo printMenu1('g10', '상품관리'); ?>
		<?php echo printMenu2('g10', ADMIN_MENU5_1, TW_ADMIN_URL.'/goods.php?code=list'); ?>
		<?php echo printMenu2('g10', ADMIN_MENU5_2, TW_ADMIN_URL.'/goods.php?code=type'); ?>
		<?php echo printMenu2('g10', ADMIN_MENU5_10, TW_ADMIN_URL.'/goods.php?code=brand'); ?>
		<?php echo printMenu2('g10', ADMIN_MENU5_21, TW_ADMIN_URL.'/goods.php?code=plan'); ?>
		<?php echo printMenu2('g10', ADMIN_MENU5_19, TW_ADMIN_URL.'/goods.php?code=price'); ?>
		<?php echo printMenu1('g11', '재고관리'); ?>
		<?php echo printMenu2('g11', ADMIN_MENU5_6, TW_ADMIN_URL.'/goods.php?code=stock'); ?>
		<?php echo printMenu2('g11', ADMIN_MENU5_7, TW_ADMIN_URL.'/goods.php?code=optstock'); ?>
		<?php echo printMenu1('g20', '일괄처리'); ?>
		<?php echo printMenu2('g20', ADMIN_MENU5_11, TW_ADMIN_URL.'/goods.php?code=xls_reg'); ?>
		<?php echo printMenu2('g20', ADMIN_MENU5_20, TW_ADMIN_URL.'/goods.php?code=xls_option_reg'); ?>
		<?php echo printMenu2('g20', ADMIN_MENU5_12, TW_ADMIN_URL.'/goods.php?code=xls_mod'); ?>
		<?php echo printMenu2('g20', ADMIN_MENU5_4, TW_ADMIN_URL.'/goods.php?code=getprice'); ?>
		<?php echo printMenu2('g20', ADMIN_MENU5_22, TW_ADMIN_URL.'/goods.php?code=getpoint'); ?>
		<?php echo printMenu2('g20', ADMIN_MENU5_23, TW_ADMIN_URL.'/goods.php?code=getuse'); ?>
		<?php echo printMenu2('g20', ADMIN_MENU5_24, TW_ADMIN_URL.'/goods.php?code=getmove'); ?>
		<?php echo printMenu2('g20', ADMIN_MENU5_25, TW_ADMIN_URL.'/goods.php?code=getbrand'); ?>
		<?php echo printMenu2('g20', ADMIN_MENU5_26, TW_ADMIN_URL.'/goods.php?code=getdelivery'); ?>
		<?php echo printMenu2('g20', ADMIN_MENU5_27, TW_ADMIN_URL.'/goods.php?code=getbuylevel'); ?>
		<?php echo printMenu1('g30', '대기상품'); ?>
		<?php echo printMenu2('g30', ADMIN_MENU5_13, TW_ADMIN_URL.'/goods.php?code=supply', $regCnt); ?>
		<?php echo printMenu2('g30', ADMIN_MENU5_14, TW_ADMIN_URL.'/goods.php?code=userlist'); ?>
		<?php echo printMenu1('g40', '문의 / 후기'); ?>
		<?php echo printMenu2('g40', ADMIN_MENU5_15, TW_ADMIN_URL.'/goods.php?code=qa', $qaCnt); ?>
		<?php echo printMenu2('g40', ADMIN_MENU5_16, TW_ADMIN_URL.'/goods.php?code=review'); ?>
		<?php echo printMenu1('g50', '쿠폰관리'); ?>
		<?php echo printMenu2('g50', ADMIN_MENU5_17, TW_ADMIN_URL.'/goods.php?code=gift'); ?>
		<?php echo printMenu2('g50', ADMIN_MENU5_18, TW_ADMIN_URL.'/goods.php?code=coupon'); ?>
	</dl>
	<?php } 
	else if($pg_title == ADMIN_MENU6) { ?>
	<dl>
		<?php echo printMenu1('o10', '주문관리'); ?>
		<?php echo printMenu2('o10', ADMIN_MENU6_1, TW_ADMIN_URL.'/order.php?code=today', $tdayCnt); ?>
		<?php echo printMenu2('o10', ADMIN_MENU6_2, TW_ADMIN_URL.'/order.php?code=1', $dan1Cnt); ?>
		<?php echo printMenu2('o10', ADMIN_MENU6_3, TW_ADMIN_URL.'/order.php?code=2', $dan2Cnt); ?>
		<?php echo printMenu2('o10', ADMIN_MENU6_4, TW_ADMIN_URL.'/order.php?code=3', $dan3Cnt); ?>
		<?php echo printMenu2('o10', ADMIN_MENU6_5, TW_ADMIN_URL.'/order.php?code=4', $dan4Cnt); ?>
		<?php echo printMenu2('o10', ADMIN_MENU6_6, TW_ADMIN_URL.'/order.php?code=5', $dan5Cnt); ?>
		<?php echo printMenu2('o10', ADMIN_MENU6_13, TW_ADMIN_URL.'/order.php?code=whole', $cumuCnt); ?>
		<?php echo printMenu2('o10', ADMIN_MENU6_7, TW_ADMIN_URL.'/order.php?code=delivery_xls'); ?>
		<?php echo printMenu1('o20', '취소 / 반품 / 교환'); ?>
		<?php echo printMenu2('o20', ADMIN_MENU6_8, TW_ADMIN_URL.'/order.php?code=cancel', $cancelCnt); ?>
		<?php echo printMenu2('o20', ADMIN_MENU6_9, TW_ADMIN_URL.'/order.php?code=7', $dan7Cnt); ?>
		<?php echo printMenu2('o20', ADMIN_MENU6_10, TW_ADMIN_URL.'/order.php?code=8', $dan8Cnt); ?>
		<?php echo printMenu2('o20', ADMIN_MENU6_11, TW_ADMIN_URL.'/order.php?code=6', $dan6Cnt); ?>
		<?php echo printMenu2('o20', ADMIN_MENU6_12, TW_ADMIN_URL.'/order.php?code=10', $dan10Cnt); ?>
		<?php echo printMenu1('o30', '기타관리'); ?>
		<?php echo printMenu2('o30', ADMIN_MENU6_14, TW_ADMIN_URL.'/order.php?code=memo', $memoCnt); ?>
		<?php echo printMenu2('o30', ADMIN_MENU6_15, TW_ADMIN_URL.'/order.php?code=aff', $userCnt); ?>
	</dl>
	<?php } 
	else if($pg_title == ADMIN_MENU7) { ?>
	<dl>
		<?php echo printMenu1('v10', '접속자통계'); ?>
		<?php echo printMenu2('v10', ADMIN_MENU7_1, TW_ADMIN_URL.'/visit.php?code=hour'); ?>
		<?php echo printMenu2('v10', ADMIN_MENU7_2, TW_ADMIN_URL.'/visit.php?code=date'); ?>
		<?php echo printMenu2('v10', ADMIN_MENU7_3, TW_ADMIN_URL.'/visit.php?code=week'); ?>
		<?php echo printMenu2('v10', ADMIN_MENU7_4, TW_ADMIN_URL.'/visit.php?code=month'); ?>
		<?php echo printMenu2('v10', ADMIN_MENU7_5, TW_ADMIN_URL.'/visit.php?code=year'); ?>
		<?php echo printMenu2('v10', ADMIN_MENU7_6, TW_ADMIN_URL.'/visit.php?code=browser'); ?>
		<?php echo printMenu2('v10', ADMIN_MENU7_7, TW_ADMIN_URL.'/visit.php?code=os'); ?>
		<?php echo printMenu2('v10', ADMIN_MENU7_8, TW_ADMIN_URL.'/visit.php?code=domain'); ?>
		<?php echo printMenu2('v10', ADMIN_MENU7_9, TW_ADMIN_URL.'/visit.php?code=search'); ?>
		<?php echo printMenu1('v20', '주문통계'); ?>
		<?php echo printMenu2('v20', ADMIN_MENU7_10, TW_ADMIN_URL.'/visit.php?code=order1'); ?>
		<?php echo printMenu2('v20', ADMIN_MENU7_11, TW_ADMIN_URL.'/visit.php?code=order2'); ?>
		<?php echo printMenu2('v20', ADMIN_MENU7_12, TW_ADMIN_URL.'/visit.php?code=cancel'); ?>
		<?php echo printMenu2('v20', ADMIN_MENU7_13, TW_ADMIN_URL.'/visit.php?code=return'); ?>
		<?php echo printMenu2('v20', ADMIN_MENU7_14, TW_ADMIN_URL.'/visit.php?code=change'); ?>
	</dl>
	<?php } 
	else if($pg_title == ADMIN_MENU8) { ?>
	<dl>
		<?php echo printMenu1('h10', '고객지원'); ?>
		<?php echo printMenu2('h10', ADMIN_MENU8_1, TW_ADMIN_URL.'/help.php?code=qa'); ?>
		<?php echo printMenu2('h10', ADMIN_MENU8_2, TW_ADMIN_URL.'/help.php?code=out'); ?>
		<?php echo printMenu1('h20', 'FAQ 관리'); ?>
		<?php echo printMenu2('h20', ADMIN_MENU8_4, TW_ADMIN_URL.'/help.php?code=faq_group'); ?>
		<?php echo printMenu2('h20', ADMIN_MENU8_3, TW_ADMIN_URL.'/help.php?code=faq'); ?>
	</dl>
	<?php } 
	else if($pg_title == ADMIN_MENU9) { ?>
	<dl>
		<?php echo printMenu1('d10', '디자인관리'); ?>
		<?php echo printMenu2('d10', ADMIN_MENU9_2, TW_ADMIN_URL.'/design.php?code=logo'); ?>
		<?php echo printMenu2('d10', ADMIN_MENU9_4, TW_ADMIN_URL.'/design.php?code=banner'); ?>
		<?php echo printMenu2('d10', ADMIN_MENU9_1, TW_ADMIN_URL.'/design.php?code=contentlist'); ?>
		<?php echo printMenu1('d20', '메인관리'); ?>
		<?php echo printMenu2('d20', ADMIN_MENU9_5, TW_ADMIN_URL.'/design.php?code=slider'); ?>
		<?php echo printMenu2('d20', ADMIN_MENU9_6, TW_ADMIN_URL.'/design.php?code=intro'); ?>
		<?php echo printMenu2('d20', ADMIN_MENU9_7, TW_ADMIN_URL.'/design.php?code=best_item'); ?>
	</dl>
	<?php } 
	else if($pg_title == ADMIN_MENU10) { ?>
	<dl>
		<?php echo printMenu1('q10', '기본환경설정'); ?>
		<?php echo printMenu2('q10', ADMIN_MENU10_1, TW_ADMIN_URL.'/config.php?code=default'); ?>
		<?php echo printMenu2('q10', ADMIN_MENU10_2, TW_ADMIN_URL.'/config.php?code=mobile'); ?>
		<?php echo printMenu2('q10', ADMIN_MENU10_16, TW_ADMIN_URL.'/config.php?code=meta'); ?>
		<?php echo printMenu2('q10', ADMIN_MENU10_15, TW_ADMIN_URL.'/config.php?code=sns'); ?>
		<?php echo printMenu2('q10', ADMIN_MENU10_17, TW_ADMIN_URL.'/config.php?code=register'); ?>
		<?php echo printMenu2('q10', ADMIN_MENU10_18, TW_ADMIN_URL.'/config.php?code=sendmail_test'); ?>
		<?php echo printMenu2('q10', ADMIN_MENU10_6, TW_ADMIN_URL.'/config.php?code=sms'); ?>
		<?php echo printMenu2('q10', ADMIN_MENU10_8, TW_ADMIN_URL.'/config.php?code=supply'); ?>
		<?php echo printMenu2('q10', ADMIN_MENU10_10, TW_ADMIN_URL.'/config.php?code=super'); ?>		
		<?php echo printMenu1('q20', '결제관리'); ?>
		<?php echo printMenu2('q20', ADMIN_MENU10_7, TW_ADMIN_URL.'/config.php?code=pg'); ?>
		<?php echo printMenu2('q20', ADMIN_MENU10_19, TW_ADMIN_URL.'/config.php?code=kakaopay'); ?>
		<?php echo printMenu2('q20', ADMIN_MENU10_20, TW_ADMIN_URL.'/config.php?code=naverpay'); ?>
		<?php echo printMenu1('q30', '배송관리'); ?>
		<?php echo printMenu2('q30', ADMIN_MENU10_4, TW_ADMIN_URL.'/config.php?code=ship'); ?>
		<?php echo printMenu2('q30', ADMIN_MENU10_5, TW_ADMIN_URL.'/config.php?code=islandlist'); ?>
		<?php echo printMenu1('q40', '보안관리'); ?>
		<?php echo printMenu2('q40', ADMIN_MENU10_9, TW_ADMIN_URL.'/config.php?code=nicecheck'); ?>
		<?php echo printMenu2('q40', ADMIN_MENU10_21, TW_ADMIN_URL.'/config.php?code=ipaccess'); ?>
		<?php echo printMenu1('q50', '게시판관리'); ?>
		<?php echo printMenu2('q50', ADMIN_MENU10_11, TW_ADMIN_URL.'/config.php?code=board_group'); ?>
		<?php echo printMenu2('q50', ADMIN_MENU10_12, TW_ADMIN_URL.'/config.php?code=board'); ?>
		<?php echo printMenu2('q50', ADMIN_MENU10_13, TW_ADMIN_URL.'/config.php?code=keyword'); ?>
		<?php echo printMenu2('q50', ADMIN_MENU10_14, TW_ADMIN_URL.'/config.php?code=popup'); ?>
	</dl>
	<?php } ?>
</div>
