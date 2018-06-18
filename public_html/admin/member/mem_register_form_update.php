<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

check_admin_token();

if(!$_POST['id']) {
	alert("잘못된 접근입니다.");
}

if(!is_check("shop_member", "id", $_POST['id'])) {
	alert("중복된 아이디 입니다.");
}

// 미성년자 체크
if($_POST['birth_year'] && $_POST['birth_month'] && $_POST['birth_day']) {
	$mb_birth = trim($_POST['birth_year']);
	$mb_birth .= sprintf('%02d',trim($_POST['birth_month']));
	$mb_birth .= sprintf('%02d',trim($_POST['birth_day']));

	$todays = date("Ymd", $server_time);

	// 오늘날짜에서 생일을 빼고 거기서 140000 을 뺀다.
	// 결과가 0 이상의 양수이면 만 14세가 지난것임
	$check = $todays - (int)$mb_birth - 140000;
	if($check < 0) {
		alert("만 14세가 지나지 않은 어린이는 정보통신망 이용촉진 및 정보보호 등에 관한 법률\\r\\n제 31조 1항의 규정에 의하여 법정대리인의 동의를 얻어야 하므로\\r\\n법정대리인의 이름과 연락처를 '자기소개'란에 별도로 입력하시기 바랍니다.");
	}
}

unset($value);
$value['name']			= $_POST['name']; //회원명
$value['id']			= $_POST['id']; //회원아이디
$value['passwd']		= $_POST['passwd']; //비밀번호
$value['birth_year']	= $_POST['birth_year']; //년
$value['birth_month']	= sprintf('%02d',$_POST['birth_month']); //월
$value['birth_day']		= sprintf('%02d',$_POST['birth_day']); //일
$value['age']			= substr(date("Y")-$_POST['birth_year'],0,1).'0'; //연령대
$value['birth_type']	= strtoupper($_POST['birth_type']); //음력,양력
$value['gender']		= strtoupper($_POST['gender']); //성별
$value['email']			= $_POST['email']; //이메일
$value['cellphone']		= replace_tel($_POST['cellphone']); //핸드폰
$value['telephone']		= replace_tel($_POST['telephone']); //전화번호
$value['zip']			= $_POST['zip']; //우편번호
$value['addr1']			= $_POST['addr1']; //주소
$value['addr2']			= $_POST['addr2']; //상세주소
$value['addr3']			= $_POST['addr3']; //참고항목
$value['addr_jibeon']	= $_POST['addr_jibeon']; //지번주소
$value['mailser']		= $_POST['mailser'] ? $_POST['mailser'] : 'N'; //E-Mail을 수신
$value['smsser']		= $_POST['smsser'] ? $_POST['smsser'] : 'N'; //SMS를 수신
$value['pt_id']			= $_POST['pt_id']; //추천인
$value['reg_time']		= $time_ymdhis; //가입일
$value['grade']			= '9'; //레벨
$r = insert("shop_member",$value);
$mb_no = sql_insert_id();
if(!$r)
	alert("회원가입에 실패하였습니다.");

if((int)$config['join_point'] > 0) {
	insert_point($mb_no, $config['join_point'], "신규 회원가입 적립 포인트");
}

// 추천인 포인트
$pt = sql_fetch("select index_no,id from shop_member where id=TRIM('$_POST[pt_id]') ");
if((int)$config['reco_point'] > 0 && $pt['id'] != 'admin') {
	insert_point($pt['index_no'], $config['reco_point'], "{$_POST[name]}님의 회원가입 추천 적립 포인트");
}

// 회원가입 메일발송
if($_POST['email']) {

	include_once (TW_INC_PATH."/mail.php");

	$subject = '['.$config['company_name'].'] 회원가입을 축하드립니다.';

	ob_start();
	include_once (TW_BBS_PATH.'/register_form_update_mail1.php');
	$content = ob_get_contents();
	ob_end_clean();

	mailer($config['company_name'], $super['email'], $_POST['email'], $subject, $content, 1);
}

alert("회원가입이 완료 되었습니다.", "/admin/member.php?code=register_form");
?>
