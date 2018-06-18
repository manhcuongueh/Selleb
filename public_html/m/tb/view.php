<?php
include_once("./_common.php");

$is_seometa = 'it'; // SEO 메타태그

$gs = get_goods($gs_id);
if(!$gs['index_no'])
	alert('등록된 상품이 없습니다');
else if(!is_admin() && $gs['shop_state'])
	alert('현재 판매가능한 상품이 아닙니다.');

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

// Q&A : 카운터
$sql = "select count(*) as cnt from shop_goods_qa where gs_id='$gs_id'";
$total_qna = sql_fetch($sql);

// 상품평 : 카운터
$sql = "select count(*) as cnt from shop_goods_review where gs_id = '$gs_id'";
if($default['de_review_wr_use']) {
	$sql .= " and pt_id = '$pt_id' ";
}
$total_comment = sql_fetch($sql);

// 고객선호도 별점수
$star_score = get_star_image($gs_id);

// 고객선호도 평점
$aver_score = ($star_score * 10) * 2;

// 상품 조회 카운터증가
sql_query("update shop_goods set readcount = readcount + 1 where index_no='$gs_id'");

// 카테고리 정보
$ca = sql_fetch("select gcate from shop_goods_cate where gs_id='$gs_id' order by index_no asc limit 1");

// 오늘 본 상품 저장 시작
if(get_session('ss_pr_idx')) {
	$arr_ss_pr_idx = get_session('ss_pr_idx');
	$arr_tmps = explode(",",$arr_ss_pr_idx);
	if(!in_array($gs_id,$arr_tmps)) {
		$ss_pr_idx = get_session('ss_pr_idx').",".$gs_id;
		set_session('ss_pr_idx', $ss_pr_idx);
	}
} else {
	$ss_pr_idx = get_session('ss_pr_idx').$gs_id;
	set_session('ss_pr_idx', $ss_pr_idx);
}

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
$is_soldout = is_soldout($gs_id);

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
			$is_social_txt	= "(판매종료) 시작일 : ".substr($gs['sb_date'],0,4)." / ";
			$is_social_txt .= substr($gs['sb_date'],5,2)." / ";
			$is_social_txt .= substr($gs['sb_date'],8,2)." ~ ";
			$is_social_txt .= "종료일 : ".substr($gs['eb_date'],0,4)." / ";
			$is_social_txt .= substr($gs['eb_date'],5,2)." / ";
			$is_social_txt .= substr($gs['eb_date'],8,2);

			$script_msg	= "현재 상품은 판매기간이 종료 되었습니다.";

		} else if($gs['sb_date'] > $time_ymd) {
			$is_social_end	= true;
			$is_social_txt	= "(판매대기) 시작일 : ".substr($gs['sb_date'],0,4)." / ";
			$is_social_txt .= substr($gs['sb_date'],5,2)." / ";
			$is_social_txt .= substr($gs['sb_date'],8,2)." ~ ";
			$is_social_txt .= "종료일 : ".substr($gs['eb_date'],0,4)." / ";
			$is_social_txt .= substr($gs['eb_date'],5,2)." / ";
			$is_social_txt .= substr($gs['eb_date'],8,2);

			$script_msg	= "현재 상품은 판매대기 상품 입니다.";

		} else if($gs['sb_date'] <= $time_ymd && $gs['eb_date'] >= $time_ymd) {
			$is_social_ing	= true;

			// 소셜 스크립트
			define('M_TIMESALE', $theme_path.'/time.skin.php');
		}
	}
}

// 필수 옵션
$option_item = get_item_options($gs_id, $gs['opt_subject'], " style='width:100%'");

// 추가 옵션
$supply_item = get_item_supply($gs_id, $gs['spl_subject'], " style='width:100%'");

// 가맹점상품은 쿠폰발급안함
if(!$gs['use_aff'] && $config['sp_coupon']) {
	// 쿠폰발급 (회원직접 다운로드)
	$tmp_coupon = tbl_chk_coupon('0', $gs_id);

	// 쿠폰발급 (적용가능쿠폰)
	if($mb_yes)
		$tmp_coupon_btn = "<a href=\"javascript:void(0);\" onclick=\"window.open('./pop_coupon.php?gs_id=$gs_id','_blank');\" class=\"btn_ssmall bx-blue\">쿠폰다운로드</a>";
	else
		$tmp_coupon_btn = "<a href=\"javascript:alert('로그인 후 이용 가능합니다.')\" class=\"btn_ssmall bx-blue\">쿠폰다운로드</a>";
}

// SNS
$gw_head_title = get_head_title('head_title', $pt_id);
$sns_title = get_text($gs['gname']).' | '.get_text($gw_head_title);
$sns_url = TW_SHOP_URL.'/view.php?index_no='.$gs_id;
$sns_share_links .= get_sns_share_link('facebook', $sns_url, $sns_title, TW_IMG_URL.'/sns/facebook.gif');
$sns_share_links .= get_sns_share_link('twitter', $sns_url, $sns_title, TW_IMG_URL.'/sns/twitter.gif');
$sns_share_links .= get_sns_share_link('kakaostory', $sns_url, $sns_title, TW_IMG_URL.'/sns/kakaostory.gif');
$sns_share_links .= get_sns_share_link('naverband', $sns_url, $sns_title, TW_IMG_URL.'/sns/naverband.gif');
$sns_share_links .= get_sns_share_link('googleplus', $sns_url, $sns_title, TW_IMG_URL.'/sns/googleplus.gif');
$sns_share_links .= get_sns_share_link('naver', $sns_url, $sns_title, TW_IMG_URL.'/sns/naver.gif');
$sns_share_links .= get_sns_share_link('pinterest', $sns_url, $sns_title, TW_IMG_URL.'/sns/pinterest.gif');

$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

$pg['pagename'] = '상품 상세보기';
$tb['title'] = $gs['gname'];
include_once("./_head.php");
include_once(TW_INC_PATH.'/goodsinfo.lib.php');
include_once(TW_SHOP_PATH.'/settle_naverpay.inc.php');

$slide_img = array();
for($i=2; $i<=6; $i++) { // 슬라이드 이미지
	$it_image = trim($gs['simg'.$i]);
	if(!$it_image) continue;

	if(preg_match("/^(http[s]?:\/\/)/", $it_image) == false) {
		$file = TW_DATA_PATH."/goods/".$it_image;	
		if(is_file($file)) {
			$slide_img[] = TW_DATA_URL."/goods/".$it_image;		
		}
	} else {
		$slide_img[] = $it_image;
	}	
}

$slide_url = implode('|', $slide_img);
$slide_cnt = count($slide_img);

include_once($theme_path.'/view.skin.php');

include_once("./_tail.php");
?>