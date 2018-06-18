<?php
if(!defined('_TUBEWEB_')) exit; // 개별 페이지 접근 불가

if(!trim($mb_id) || !trim($token_value)) {
	alert_close("정보가 제대로 넘어오지 않아 오류가 발생했습니다.");
}

//소셜아이디
$sns_id = $mb_id;

// 소셜아이디(sns_id) 체크
$mb = sql_fetch(" select * from shop_member where sns_id = '{$sns_id}' ", false);
if(!isset($mb['sns_id'])) {
	// sn_id 필드생성
	sql_query(" ALTER TABLE `shop_member` ADD `sns_id` varchar(255) NOT NULL COMMENT '소셜아이디(sns_id) 체크' AFTER `id` ", false);
}

// 소셜아이디가 없으면 기존방식으로 회원가입여부 체크
if(!$mb['sns_id']) {
	//기존에 풀인증번호로 변경하신 분은 여기를 수정해 주셔야 합니다.
	$mb_id = substr($mb_id,0,18); //최대 20자 = 구분자(2자) + 아이디값(18자)
	$mb_id = $mb_gubun.''.$mb_id;
	$mb = get_member($mb_id);
}

$register_script = '';
if($mb['id']) { // 가입된 회원이면

	// 소셜아이디 업데이트
	if(!$mb['sns_id']) {
		$mb['sns_id'] = $sns_id;
		sql_query(" update shop_member set sns_id = '{$sns_id}' where id = '{$mb['id']}' ", false);
	}

	// 로그인 포인트적립
	if($config['login_point']) {
		$sql = " select count(*) as cnt 
				   from shop_point 
				  where DATE_FORMAT(FROM_UNIXTIME(wdate),'%Y-%m-%d')='$time_ymd' 
					and po_ty = 'login'
					and mb_no = '$mb[index_no]' ";
		$row = sql_fetch($sql);
		if(!$row['cnt']) {
			insert_point($mb['index_no'], $config['login_point'], "$time_ymd 로그인 포인트적립", "login");
		}
	}

	// 세션 생성
	set_session('ss_mb_id', $mb['id']);

	set_cookie('ck_mb_id', '', 0);
    set_cookie('ck_auto', '', 0);

} else {

	//회원아이디 처리 - 16자리 임의 아이디 발급
	//$mb_id = $mb_gubun . (get_microtime() * 100);
	$arr_id = str_split('abcdefghijklmnopqrstuvwxyz012345678901234567890123456789');
	for($i = 0; $i < 999; $i++) {
		shuffle($arr_id); 
		$tmp_id = $mb_gubun . implode('',array_slice($arr_id,0,16));
		$sql = " select count(*) as cnt from shop_member where id = '$tmp_id' ";
		$row = sql_fetch($sql);
		if(!$row['cnt'])
			break;
	}

	$mb_id = $tmp_id;

	//이름
	$mb_name = clean_xss_tags($mb_name);
	if(!$mb_name) {
		$mb_name = $mb_nick;
	}

	// 스크립트 알림
	$msg_alert = '회원가입을 축하드립니다.';
	if($mb_email) {
		if(!preg_match("/([0-9a-zA-Z_-]+)@([0-9a-zA-Z_-]+)\.([0-9a-zA-Z_-]+)/", $mb_email)) {
			$mb_email = '';
		}
	}

	//임시비밀번호 생성 - 10자리 임의 발급
	$arr_pw = str_split('abcdefghijklmnopqrstuvwxyz012345678901234567890123456789');
	shuffle($arr_pw); 
	$tmp_pw = implode('',array_slice($arr_pw,0,10));

	unset($value);	
	$value['id']			= $mb_id; //회원아이디
	$value['sns_id']		= $sns_id; //sns_id
	$value['name']			= $mb_name; //회원명
	$value['passwd']		= $tmp_pw; //비밀번호
	$value['email']			= $mb_email; //이메일
	$value['gender']		= 'M'; //성별	
	$value['mailser']		= 'N'; //E-Mail을 수신
	$value['smsser']		= 'N'; //SMS를 수신
	$value['pt_id']			= $pt_id; //추천인
	$value['reg_time']		= $time_ymdhis; //가입일 
	$value['grade']			= '9'; //레벨
	$r = insert("shop_member", $value);
	$mb_no = sql_insert_id();
	if(!$r)
		alert_close("회원가입에 실패하였습니다.");

	$mb = get_member_no($mb_no);

	// 회원가입 포인트
	if((int)$config['join_point'] > 0) {
		insert_point($mb_no, $config['join_point'], "신규 회원가입 적립 포인트");
	}

	// 추천인 포인트
	$pt = sql_fetch("select index_no,id,email from shop_member where id='$mb[pt_id]' ");
	if((int)$config['reco_point'] > 0 && $pt['id'] != 'admin') {
		insert_point($pt['index_no'], $config['reco_point'], "{$mb[name]}님의 회원가입 추천 적립 포인트");
	}

	// 신규회원가입 쿠폰발급
	if($config['sp_coupon']) {
		$is_coupon = false;
		$coupon = sql_fetch("select * from shop_coupon where cp_type = '5'");
		if($coupon['cp_id'] && $coupon['cp_use']) {	
			if(($coupon['cp_pub_sdate'] <= $time_ymd || $coupon['cp_pub_sdate'] == '9999999999') &&
			   ($coupon['cp_pub_edate'] >= $time_ymd || $coupon['cp_pub_edate'] == '9999999999'))
				$is_coupon = true;	

			if($is_coupon) {
				tbl_publish_coupon($mb['id'], $mb['name']);		
			}
		}
	}

	// 회원가입 문자발송
	icode_sms_send($mb_no, '1');

	// 회원가입 메일발송
	if($mb['email']) {

		include_once(TW_INC_PATH."/mail.php");

		// 회원님께 메일 발송
		$subject = '['.$config['company_name'].'] 회원가입을 축하드립니다.';

		ob_start();
		include_once(TW_BBS_PATH.'/register_form_update_mail1.php');
		$content = ob_get_contents();
		ob_end_clean();

		mailer($config['company_name'], $super['email'], $mb['email'], $subject, $content, 1);

		// 최고관리자님께 메일 발송
		$subject = '['.$config['company_name'].'] '.$mb['name'] .'님께서 회원으로 가입하셨습니다.';

		ob_start();
		include_once(TW_BBS_PATH.'/register_form_update_mail2.php');
		$content = ob_get_contents();
		ob_end_clean();

		mailer($mb['name'], $mb['email'], $super['email'], $subject, $content, 1);
	}

	// 가입완료 알림
	$register_script = 'alert("'.$msg_alert.'");';

	// 세션 생성
	set_session('ss_mb_id', $mb['id']);

	set_cookie('ck_mb_id', '', 0);
    set_cookie('ck_auto', '', 0);
}

// 메타태그 사용안함
$is_no_meta = true;

$gw_head_title = 'SNS LOGIN';
include_once(TW_PATH.'/head.sub.php');
?>

<script>
var slr = opener.document.getElementsByName("slr_url").length;
var url = "";

<?php echo $register_script;?>

if(slr) {
	url = opener.document.getElementsByName("slr_url")[0].value;
}

if(url) {
	opener.location.href = decodeURIComponent(url);
} else {
	opener.location.reload();
}

window.close();
</script>

<?php
include_once(TW_PATH.'/tail.sub.php');
?>