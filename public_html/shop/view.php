<?php
define('TW_VIEW', true);
include_once("./_common.php");

$is_seometa = 'it'; // SEO 메타태그

$gs = get_goods($index_no);
if(!$gs['index_no'])
	alert('등록된 상품이 없습니다');
else if(!is_admin() && $gs['shop_state'])
	alert('현재 판매가능한 상품이 아닙니다.');

include_once("./_head.php");

// 공급업체 정보
$sr = sql_fetch("select * from shop_seller where sup_code='$gs[mb_id]'");
if($gs['use_aff']) {
	$sr = sql_fetch("select * from shop_partner where mb_id='$gs[mb_id]'");
}

// 배송방법
$sc_method = $config['delivery_type'];
if($sr['delivery_type']) {
	$sc_method = $sr['delivery_type'];
}

// 적립금 적용에 따른 출력형태
if($gs['gpoint'] > 0 && $gs['account'] > 0){
	$rate = number_format((($gs['gpoint'] / $gs['account']) * 100), 0);
	$gpoint = display_point($gs['gpoint'])." <span class='fc_107'>($rate%)</span>";
}

//상품평 건수 구하기
$sql = "select count(*) as cnt from shop_goods_review where gs_id = '$index_no'";
if($default['de_review_wr_use']) {
	$sql .= " and pt_id = '$pt_id' ";
}
$row = sql_fetch($sql);
$item_use_count = (int)$row['cnt'];

// 고객선호도 별점수
$star_score = get_star_image($index_no);

// 고객선호도 평점
$aver_score = ($star_score * 10) * 2;

// 대표 카테고리
$sql = "select * from shop_goods_cate where gs_id='$index_no' order by index_no asc limit 1 ";
$ca = sql_fetch($sql);

// 상품조회 카운터하기
sql_query("update shop_goods set readcount = readcount + 1 where index_no='$index_no'");

// 페이지경로
$navi = "<a href='".TW_URL."' class='fs11'>HOME</a>".get_move($ca['gcate']);

// 수량체크
if(!$gs['stock_mod']) {
	$gs['stock_qty'] = 999999999;
}

if($gs['odr_min']) // 최소구매수량
	$odr_min = (int)$gs['odr_min'];
else
	$odr_min = 1;

if($gs['odr_max']) // 최대구매수량
	$odr_max = (int)$gs['odr_max'];
else
	$odr_max = 0;

$is_only = false;
$is_buy_only = false;
$is_pr_msg = false;
$is_social_end = false;
$is_social_ing = false;

$mb_grade = $member['grade'];
if(!$mb_yes) {
	$mb_grade = 10;
}

// 품절체크
$is_soldout = is_soldout($index_no);

if($is_soldout) {
	$script_msg = "현재상품은 품절 상품입니다.";
} else {
	if($gs['price_msg']) {
		$is_pr_msg = true;
		$script_msg = "현재상품은 구매신청 하실 수 없습니다.";
	} else if($gs['buy_only'] == 1 && $mb_grade > $gs['buy_level']) {
		$is_only = true;
		$script_msg = "현재상품은 구매신청 하실 수 없습니다.";
	} else if($gs['buy_only'] == 0 && $mb_grade > $gs['buy_level']) {
		if(!$mb_yes) {
			$is_buy_only = true;
			$script_msg = "현재상품은 회원만 구매 하실 수 있습니다.";
		} else {
			$script_msg = "현재상품을 구매하실 권한이 없습니다.";
		}
	} else {
		$script_msg = "";
	}

	if(substr($gs['sb_date'],0,1) != '0' && substr($gs['eb_date'],0,1) != '0') {
		if($gs['eb_date'] < $time_ymd) {
			$is_social_end	= true;
			$is_social_txt	= "<span>[판매종료]</span>&nbsp;&nbsp;시작일 : ".substr($gs['sb_date'],0,4)."년 ";
			$is_social_txt .= substr($gs['sb_date'],5,2)."월 ";
			$is_social_txt .= substr($gs['sb_date'],8,2)."일 ~ ";
			$is_social_txt .= "종료일 : ".substr($gs['eb_date'],0,4)."년 ";
			$is_social_txt .= substr($gs['eb_date'],5,2)."월 ";
			$is_social_txt .= substr($gs['eb_date'],8,2)."일";

			$script_msg	= "현재 상품은 판매기간이 종료 되었습니다.";
		} else if($gs['sb_date'] > $time_ymd) {
			$is_social_end	= true;
			$is_social_txt	= "<span>[판매대기]</span>&nbsp;&nbsp;시작일 : ".substr($gs['sb_date'],0,4)."년 ";
			$is_social_txt .= substr($gs['sb_date'],5,2)."월 ";
			$is_social_txt .= substr($gs['sb_date'],8,2)."일 ~ ";
			$is_social_txt .= "종료일 : ".substr($gs['eb_date'],0,4)."년 ";
			$is_social_txt .= substr($gs['eb_date'],5,2)."월 ";
			$is_social_txt .= substr($gs['eb_date'],8,2)."일";

			$script_msg	= "현재 상품은 판매대기 상품 입니다.";
		} else if($gs['sb_date'] <= $time_ymd && $gs['eb_date'] >= $time_ymd) {
			$is_social_ing	= true;
		}
	}
}

// 필수 옵션
$option_item = get_item_options($index_no, $gs['opt_subject']);

// 추가 옵션
$supply_item = get_item_supply($index_no, $gs['spl_subject']);

// 가맹점상품은 쿠폰발급안함
if(!$gs['use_aff'] && $config['sp_coupon']) {
	// 쿠폰발급 (회원직접 다운로드)
	$tmp_coupon = tbl_chk_coupon('0', $index_no);

	// 쿠폰발급 (적용가능쿠폰)
	if($mb_yes)
		$tmp_coupon_btn = "<a href=\"./pop_coupon.php?gs_id=$index_no\" onclick=\"openwindow(this,'win_coupon','670','500','yes');return false\" class=\"btn_ssmall bx-blue\">적용가능쿠폰</a>";
	else
		$tmp_coupon_btn = "<a href=\"javascript:alert('로그인 후 이용 가능합니다.')\" class=\"btn_ssmall bx-blue\">적용가능쿠폰</a>";
}

//$sns_title = get_text($gs['gname']).' | '.get_text($gw_head_title);
//$sns_url = TW_SHOP_URL.'/view.php?index_no='.$index_no;
//$sns_share_links .= get_sns_share_link('facebook', $sns_url, $sns_title, TW_IMG_URL.'/sns/facebook.gif');
//$sns_share_links .= get_sns_share_link('twitter', $sns_url, $sns_title, TW_IMG_URL.'/sns/twitter.gif');
//$sns_share_links .= get_sns_share_link('kakaostory', $sns_url, $sns_title, TW_IMG_URL.'/sns/kakaostory.gif');
//$sns_share_links .= get_sns_share_link('naverband', $sns_url, $sns_title, TW_IMG_URL.'/sns/naverband.gif');
//$sns_share_links .= get_sns_share_link('googleplus', $sns_url, $sns_title, TW_IMG_URL.'/sns/googleplus.gif');
//$sns_share_links .= get_sns_share_link('naver', $sns_url, $sns_title, TW_IMG_URL.'/sns/naver.gif');
//$sns_share_links .= get_sns_share_link('pinterest', $sns_url, $sns_title, TW_IMG_URL.'/sns/pinterest.gif');

$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

include_once(TW_INC_PATH.'/goodsinfo.lib.php');
//include_once(TW_SHOP_PATH.'/settle_naverpay.inc.php');

include_once($theme_path.'/view.skin.php');

include_once("./_tail.php");
?>