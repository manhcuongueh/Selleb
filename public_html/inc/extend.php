<?php
if(!defined('_TUBEWEB_')) exit; // 개별 페이지 접근 불가

if(defined('_PURENESS_')) return;

// 이미 세션이 발급된경우. (밑에서 실적을 지급 하는 부분은 한번만 적용되어야 하므로, 최초 1회에만 적용)
$alreadyId = get_session('pt_id');

// 등록된 도메인을 검사 후 있으면 세션을 바꾼다
$sql = "select id from shop_member where homepage = '{$_SERVER['HTTP_HOST']}'";
$ss = sql_fetch($sql);
if($ss['id']) {
	$leftDomain = $ss['id'].'.';
} else {
	$hostdom = get_basedomain($_SERVER['HTTP_HOST']);
	$leftDomain = preg_replace("/{$hostdom}/","",$_SERVER['HTTP_HOST']);
}

// 확장자가 남겨진 상태에서 앞에 추천인 아이디를 안고왔는지 체크 한다.
// id.domain 형태인 경우에는 필요한 작업을 해야 한다.
if(substr_count($leftDomain, ".") == 1):

	// 그렇지 않을 경우에는 그냥 넘어 간다.
	$idDomains	= explode('.', $leftDomain);
	$pt_id = $idDomains[0];

	// 권한이 있는지 체크하는 로직이 있어야 함
	// 아직 값이 없거나, 값이 기존에 값과 다른 경우, 즉 새롭게 등장한 아이디가 오는 경우
	if($alreadyId == false || $alreadyId != $pt_id) :

		// 추천인 아이디가 있다면 추천인 코드를 세션으로 발급 한다.
		set_session('pt_id', $pt_id);
		if(!is_partner($pt_id)):
			$pt_id = 'admin';
			set_session('pt_id', $pt_id);
		endif;

		$uid = $pt_id;

		if($uid != 'admin')
		{
			// 미납이후 발생되는 수수료 관련 및 회원가입시 추천인은 본사로 자동변환됨
			if($config['p_month']=='y' && $config['accent_one']=='y') {
				$row = sql_fetch("select term_date from shop_member where id='$uid'");
				$h_y = date("Y",intval($row['term_date'],10));
				$h_m = date("m",intval($row['term_date'],10));
				$h_d = date("d",intval($row['term_date'],10));
				$new_hold = mktime(0,0,1,$h_m,$h_d,$h_y);
				$ed = $new_hold - time();
				if($ed > 0) {  $extra_date = round($ed/(60*60*24)); $default_check = 1; }
				else { $exceed_date = round(($ed/(60*60*24))*(-1)); $default_check = 2; }

				// 관리비 미납일 경우
				if($default_check=='2') {
					set_session('pt_id', 'admin');
					$pt_id = 'admin';
					$uid = $pt_id;
				}
			}

			// 클릭당 광고수수료 체크
			$sql = "select count(*) as cnt
					  from shop_partner_paylog
					 where month_date2 = '$time_ymd'
					   and ip = '{$_SERVER['REMOTE_ADDR']}'
					   and mb_id = '$pt_id'";
			$row = sql_fetch($sql);
			if(!$row['cnt']) {
				$cf = sql_fetch("select etc2 from shop_partner_config where etc4='item_etc'");
				if($config['p_login'] == 'y') {
					$sql_dayweek = sql_dayofweek();
					$dayofweek = get_dayofweek();

					// 광고수수료 로그 남기기.
					$sql = "insert into shop_partner_paylog
								   ( mb_id, pt_id, in_money, memo, wdate, ju_date, month_date, etc1, etc2, month_date2,ip )
							VALUES ('$uid','$pt_id','$cf[etc2]','광고수수료 적립',
									'$server_time','$dayofweek','$time_ym','$time_ymd','login','$time_ymd','{$_SERVER['REMOTE_ADDR']}')";
					sql_query($sql);

					// 회원에게 수수료적립
					$sql = "update shop_member set pay=pay+$cf[etc2] where id='$pt_id'";
					sql_query($sql);

					$sql_search = "where mb_id='$pt_id' ";
					if($config['p_type'] == 'month') // 월
						$sql_search .= " and month_date='$time_ym' ";
					else // 주, 실시간
						$sql_search .= " and $sql_dayweek ";

					$cm = sql_fetch("select index_no from shop_partner_pay $sql_search");
					if($cm['index_no']) {
						$sql = "update shop_partner_pay
								   set income	= income+$cf[etc2],
									   total	= total+$cf[etc2],
									   p_login	= p_login+$cf[etc2],
									   reg_date	= '$server_time'
									   $sql_search ";
						sql_query($sql);
					} else {
						$pp = sql_fetch("select index_no from shop_member where id='$pt_id'");

						$sql = "insert into shop_partner_pay
									   ( mb_no, income, total, wdate, ju_date, month_date, reg_date, p_login, mb_id )
								VALUES ('$pp[index_no]', '$cf[etc2]', '$cf[etc2]',
										'$server_time', '$dayofweek', '$time_ym', '$server_time', '$cf[etc2]', '$uid')";
						sql_query($sql);
					}
				}
			}
		}
	endif;
else :
	$pt_id = "admin";
endif;

/*
if(substr_count($leftDomain, ".") == 1 && $pt_id == 'admin'):
	$idDomains = explode('.', $_SERVER['HTTP_HOST']);
	$goDomain  = implode('.',array_slice($idDomains,1));

	if(preg_match("/tubeweb\.co\.kr|tubeweb\.kr|cafe24\.com/", $_SERVER['HTTP_HOST'])== false) :
		goto_url(set_http($goDomain));
	endif;
endif;
*/

set_session('pt_id', $pt_id);

$mk = sql_fetch("select * from shop_member where id='$pt_id'");
$pt = sql_fetch("select * from shop_partner where mb_id='$pt_id'");

if(IS_MOBILE && $config['mo_shop_yn']) { // 모바일 접속인가?
	if(!$mk['mobile_theme'])
		$mk['mobile_theme'] = 'basic';

	$theme_path = get_mobile_theme_path($mk['mobile_theme']);
	$theme_url = get_mobile_theme_url($mk['mobile_theme']);
} else {
	if(!$mk['theme'])
		$mk['theme'] = 'basic';

	$theme_path = get_theme_path($mk['theme']);
	$theme_url  = get_theme_url($mk['theme']);
}

// 방문자수의 접속을 남김
include_once(TW_INC_PATH.'/visit_insert.php');

$auth_good = false;
$auth_pg = false;

if($pt_id != 'admin') {
	$tb['category_table'] = 'shop_cate_'.$pt_id;

	// 카테고리가 생성되지 않았을때 새로 생성.
	if(!table_exists($tb['category_table'])) {
		sql_member_category($pt_id);
	}

	// 개별 상품판매
	if($config['p_use_good'] == 2 || ($config['p_use_good'] == 3 && $mk['use_good']))
		$auth_good = true;

	// 개별 PG결제
	if($config['p_use_pg'] == 2 || ($config['p_use_pg'] == 3 && $mk['use_pg']))
		$auth_pg = true;
}

// 인트로설정 체크
$intro_yes = array();
if(!$mb_yes && $config['sp_intro']) {
	if(IS_MOBILE && $config['mo_shop_yn']) { // 모바일 접속인가?
		$intro_yes[] = "/m/index.php";
		$intro_yes[] = "/m/tb/register.php";
		$intro_yes[] = "/m/tb/register_form.php";
		$intro_yes[] = "/m/tb/register_form_update.php";
		$intro_yes[] = "/m/tb/password_lost.php";
		$intro_yes[] = "/m/tb/password_lost2.php";
		$intro_yes[] = "/m/tb/password_lost_certify.php";
		$intro_yes[] = "/m/tb/login_check.php";
		$intro_yes[] = "/m/tb/ajax.mb_id_check.php";
		$intro_yes[] = "/m/tb/provision.php";
		$intro_yes[] = "/m/tb/policy.php";
		$intro_yes[] = "/m/tb/chekplus/checkplus_success.php";
		$intro_yes[] = "/m/tb/chekplus/ipin_result.php";
	} else {
		$intro_yes[] = "/index.php";
		$intro_yes[] = "/bbs/register.php";
		$intro_yes[] = "/bbs/register_form.php";
		$intro_yes[] = "/bbs/register_form_update.php";
		$intro_yes[] = "/bbs/password_lost.php";
		$intro_yes[] = "/bbs/password_lost2.php";
		$intro_yes[] = "/bbs/password_lost_certify.php";
		$intro_yes[] = "/bbs/login_check.php";
		$intro_yes[] = "/bbs/ajax.mb_id_check.php";
		$intro_yes[] = "/plugin/chekplus/checkplus_success.php";
		$intro_yes[] = "/plugin/chekplus/ipin_result.php";
	}

	if( !in_array($_SERVER['PHP_SELF'], $intro_yes) ) {
		goto_url(TW_URL);
	}
}

// 가맹점 사업자정보
if($pt_id != 'admin' && $pt['cf_saupja_use']) {
	$config['company_type'] = $pt['company_type'];
	$config['shop_name'] = $pt['shop_name'];
	$config['company_name'] = $pt['company_name'];
	$config['company_saupja_no'] = $pt['company_saupja_no'];
	$config['tongsin_no'] = $pt['tongsin_no'];
	$config['company_tel'] = $pt['company_tel'];
	$config['company_fax'] = $pt['company_fax'];
	$config['company_owner'] = $pt['company_owner'];
	$config['info_name'] = $pt['info_name'];
	$config['info_email'] = $pt['info_email'];
	$config['company_zip'] = $pt['company_zip'];
	$config['company_addr'] = $pt['company_addr'];
	$config['company_hours'] = $pt['company_hours'];
	$config['company_lunch'] = $pt['company_lunch'];
	$config['company_close'] = $pt['company_close'];
	$super['email'] = $mk['email'];
}

// 개별 결제연동
$ss_pg_id = '';
if($auth_pg) {
	$ss_pg_id = $pt_id;
	$default['de_bank_name'] = $pt['de_bank_name']; // 은행명
	$default['de_bank_account'] = $pt['de_bank_account']; // 계좌번호
	$default['de_bank_holder'] = $pt['de_bank_holder']; // 예금주명
	$default['cf_card_test_yn'] = $pt['cf_card_test_yn']; // 결제시스템 방식
	$default['cf_card_pg'] = $pt['cf_card_pg']; // 결제대행사
	$default['cf_nm_pg'] = $pt['cf_nm_pg']; // 상점명
	$default['cf_escrow_yn'] = $pt['cf_escrow_yn']; // Escrow 사용여부
	$default['cf_tax_flag_use'] = $pt['cf_tax_flag_use']; // 복합과세 사용여부
	$default['cf_bank_yn'] = $pt['cf_bank_yn']; // 무통장입금
	$default['cf_card_yn'] = $pt['cf_card_yn']; // 신용카드
	$default['cf_iche_yn'] = $pt['cf_iche_yn']; // 계좌이체
	$default['cf_hp_yn'] = $pt['cf_hp_yn']; // 휴대폰
	$default['cf_vbank_yn'] = $pt['cf_vbank_yn']; // 가상계좌
	$default['cf_bank_account'] = $pt['cf_bank_account']; // 입금계좌
	$default['cf_banking'] = $pt['cf_banking']; // 인터넷뱅킹주소
	$default['cf_kcp_id'] = $pt['cf_kcp_id']; // KCP PG ID
	$default['cf_kcp_key'] = $pt['cf_kcp_key']; // KCP KEY
	$default['cf_kcp_tax_yn'] = $pt['cf_kcp_tax_yn']; // 현금 영수증 발급
	$default['cf_kcp_noint_yn'] = $pt['cf_kcp_noint_yn']; // 무이자 사용 여부
	$default['cf_kcp_noint_mt'] = $pt['cf_kcp_noint_mt']; // 무이자 할부 기간
	$default['cf_kcp_quota'] = $pt['cf_kcp_quota']; // 최대 할부 개월
	$default['cf_inicis_id'] = $pt['cf_inicis_id']; // 이니시스 PG ID
	$default['cf_inicis_quota'] = $pt['cf_inicis_quota']; // 일반 할부기간
	$default['cf_inicis_tax_yn'] = $pt['cf_inicis_tax_yn']; // 현금 영수증 발급
	$default['cf_inicis_noint_yn'] = $pt['cf_inicis_noint_yn']; // 무이자 사용 여부
	$default['cf_inicis_noint_mt'] = $pt['cf_inicis_noint_mt']; // 무이자 할부 기간
	$default['cf_inicis_hp_unit'] = $pt['cf_inicis_hp_unit']; // 휴대폰 결제 설정
	$default['cf_inicis_skin'] = $pt['cf_inicis_skin']; // 결제창 스킨
	$default['cf_inicis_escrow_id'] = $pt['cf_inicis_escrow_id']; // Escrow ID
	$default['cf_ags_id'] = $pt['cf_ags_id']; // 올더게이트 PG ID
	$default['cf_ags_tax_yn'] = $pt['cf_ags_tax_yn']; // 현금 영수증
	$default['cf_ags_noint_yn'] = $pt['cf_ags_noint_yn']; // 무이자 사용 여부
	$default['cf_ags_noint_mt'] = $pt['cf_ags_noint_mt'];	// 무이자 할부 기간
	$default['cf_ags_quota'] = $pt['cf_ags_quota']; // 일반 할부기간
	$default['cf_ags_hp_id'] = $pt['cf_ags_hp_id']; // CP아이디 (휴대폰)
	$default['cf_ags_hp_pwd'] = $pt['cf_ags_hp_pwd']; // CP비밀번호 (휴대폰)
	$default['cf_ags_hp_subid'] = $pt['cf_ags_hp_subid']; // SUB-CP아이디 (휴대폰)
	$default['cf_ags_hp_code'] = $pt['cf_ags_hp_code']; // 상품코드 (휴대폰)
	$default['cf_ags_hp_unit'] = $pt['cf_ags_hp_unit']; // 상품구분
	$default['de_kakaopay_mid'] = $pt['de_kakaopay_mid']; // 카카오페이 상점MID
	$default['de_kakaopay_key'] = $pt['de_kakaopay_key']; // 카카오페이 상점키
	$default['de_kakaopay_enckey'] = $pt['de_kakaopay_enckey']; // 카카오페이 EncKey
	$default['de_kakaopay_hashkey'] = $pt['de_kakaopay_hashkey']; // 카카오페이 HashKey
	$default['de_kakaopay_cancelpwd'] = $pt['de_kakaopay_cancelpwd']; // 카카오페이 결제취소 P/W
	$default['de_naverpay_mid'] = $pt['de_naverpay_mid']; // 네이버페이 가맹점 아이디
	$default['de_naverpay_cert_key'] = $pt['de_naverpay_cert_key']; // 네이버페이 가맹점 인증키
	$default['de_naverpay_button_key'] = $pt['de_naverpay_button_key']; // 네이버페이 버튼 인증키
	$default['de_naverpay_test'] = $pt['de_naverpay_test']; // 네이버페이 결제테스트 아이디
	$default['de_naverpay_mb_id'] = $pt['de_naverpay_mb_id']; // 네이버페이 결제테스트 아이디
	$default['de_naverpay_sendcost'] = $pt['de_naverpay_sendcost']; // 네이버페이 추가배송비 안내
}

// 본사접속일 아닐때는 가맹점 설정정보를 불러옴
if($pt_id != 'admin') {
	// 소셜네트워크서비스(SNS : Social Network Service)
	$default['de_sns_login_use'] = $pt['de_sns_login_use'];
	$default['de_naver_appid'] = $pt['de_naver_appid'];
	$default['de_naver_secret'] = $pt['de_naver_secret'];
	$default['de_facebook_appid'] = $pt['de_facebook_appid'];
	$default['de_facebook_secret'] = $pt['de_facebook_secret'];
	$default['de_kakao_rest_apikey'] = $pt['de_kakao_rest_apikey'];
	$default['de_googl_shorturl_apikey'] = $pt['de_googl_shorturl_apikey'];

	// INSTAGRAM / SNS 연결
	$default['de_insta_url'] = $pt['de_insta_url'];
	$default['de_insta_client_id'] = $pt['de_insta_client_id'];
	$default['de_insta_redirect_uri'] = $pt['de_insta_redirect_uri'];
	$default['de_insta_access_token'] = $pt['de_insta_access_token'];
	$default['de_sns_facebook'] = $pt['de_sns_facebook'];
	$default['de_sns_twitter'] = $pt['de_sns_twitter'];
	$default['de_sns_instagram'] = $pt['de_sns_instagram'];
	$default['de_sns_pinterest'] = $pt['de_sns_pinterest'];
	$default['de_sns_naverblog'] = $pt['de_sns_naverblog'];
	$default['de_sns_naverband'] = $pt['de_sns_naverband'];
	$default['de_sns_kakaotalk'] = $pt['de_sns_kakaotalk'];
	$default['de_sns_kakaostory'] = $pt['de_sns_kakaostory'];

	// 메인 카테고리별 베스트
	if($pt['de_maintype_title'])
		$default['de_maintype_title'] = $pt['de_maintype_title'];

	if($pt['de_maintype_best'])
		$default['de_maintype_best'] = $pt['de_maintype_best'];

	$config['sp_provision'] = $pt['sp_provision'] ? $pt['sp_provision'] : $config['sp_provision'];
	$config['sp_private'] = $pt['sp_private'] ? $pt['sp_private'] : $config['sp_private'];
	$config['sp_policy'] = $pt['sp_policy'] ? $pt['sp_policy'] : $config['sp_policy'];
	$config['head_script'] = $pt['head_script'];
	$config['tail_script'] = $pt['tail_script'];
}

// 역슬래시가 생기는 현상을 방지
$config['sp_provision'] = preg_replace("/\\\/", "", $config['sp_provision']);
$config['sp_private'] = preg_replace("/\\\/", "", $config['sp_private']);
$config['sp_policy'] = preg_replace("/\\\/", "", $config['sp_policy']);
?>