<?php
define('_NEWWIN_', true);
include_once("_common.php");
include_once("admin_access.php");
include_once("admin_head.php");

$code = $_REQUEST['code'];
$index_no = $_REQUEST['index_no'];
$mode = $_REQUEST['mode'];
$mb_id = $_REQUEST['mb_id'];
$mb_grade = $_REQUEST['mb_grade'];

// 회원 정보수정
if($mode == 'w') {
	check_demo();

	check_admin_token();

	$return_url = './pop_member_detail.php?code=pview&index_no='.$index_no;

	if($mb_id == 'admin') {
		alert('관리자는 수정하실 수 없습니다.', $return_url);
	} else {

		unset($mfrm);
		$mfrm['memo'] = $_POST['memo']; // 메모
		$mfrm['name'] = $_POST['name']; // 회원명
		$mfrm['pt_id'] = $_POST['pt_id']; // 추천인
		$mfrm['gender'] = strtoupper($_POST['gender']); // 성별
		$mfrm['birth_type']	= strtoupper($_POST['birth_type']); // 음력/양력
		$mfrm['birth_year']= $_POST['birth_year']; // 년
		$mfrm['birth_month'] = sprintf('%02d',$_POST['birth_month']); // 월
		$mfrm['birth_day']	= sprintf('%02d',$_POST['birth_day']); // 일
		$mfrm['age'] = substr(date("Y")-$_POST['birth_year'],0,1).'0'; // 연령대
		$mfrm['email'] = $_POST['email']; // 이메일
		$mfrm['grade'] = $_POST['mb_grade']; // 레벨
		$mfrm['cellphone'] = replace_tel($_POST['cellphone']);	 //핸드폰
		$mfrm['telephone'] = replace_tel($_POST['telephone']);	 //전화번호
		$mfrm['zip'] = $_POST['zip']; // 우편번호
		$mfrm['addr1'] = $_POST['addr1']; // 주소
		$mfrm['addr2'] = $_POST['addr2']; // 상세주소
		$mfrm['addr3'] = $_POST['addr3']; // 참고항목
		$mfrm['addr_jibeon'] = $_POST['addr_jibeon']; // 지번주소
		$mfrm['use_good'] = $_POST['use_good']; // 개별상품판매
		$mfrm['use_pg'] = $_POST['use_pg']; // 개인결제
		$mfrm['payment'] = $_POST['payment']; // 추가 판매수수료
		$mfrm['payflag'] = $_POST['payflag']; // 추가 판매수수료 유형
		$mfrm['homepage'] = $_POST['homepage']; // 도메인
		$mfrm['theme'] = $_POST['theme']; //테마스킨
		$mfrm['mobile_theme'] = $_POST['mobile_theme']; //모바일테마스킨	
		$mfrm['auth_1'] = $_POST['auth_1'];
		$mfrm['auth_2'] = $_POST['auth_2'];
		$mfrm['auth_3'] = $_POST['auth_3'];
		$mfrm['auth_4'] = $_POST['auth_4'];
		$mfrm['auth_5'] = $_POST['auth_5'];
		$mfrm['auth_6'] = $_POST['auth_6'];
		$mfrm['auth_7'] = $_POST['auth_7'];
		$mfrm['auth_8'] = $_POST['auth_8'];
		$mfrm['auth_9'] = $_POST['auth_9'];
		$mfrm['auth_10'] = $_POST['auth_10'];

		if($_POST['passwd']) {
			$mfrm['passwd'] = $_POST['passwd']; // 패스워드
		}

		$mb = get_member_no($index_no);
		$pt = sql_fetch("select * from shop_partner where mb_id='$mb[id]'");

		$sql = "update shop_partner
				   set bank_company	= '$_POST[bank_company]',
					   bank_name	= '$_POST[bank_name]',
					   bank_number	= '$_POST[bank_number]'
				 where mb_id = '$mb[id]'";
		sql_query($sql);

		if(in_array($mb_grade, array('9','8','7'))) {

			$mfrm['term_date'] = '';
			$mfrm['anew_date'] = '';

			sql_query("delete from shop_partner where mb_id='$mb[id]'");

			// 카테고리 테이블 DROP
			$target_table = 'shop_cate_'.$mb['id'];
			sql_query(" drop table {$target_table} ", FALSE);

			// 카테고리 폴더 전체 삭제
			rm_rf(TW_DATA_PATH.'/category/'.$mb['id']);

		} else {

			if($mb['term_date'] == '') {
				$mfrm['term_date'] = get_term_date();
			}
			if($mb['anew_date'] == '') {
				$mfrm['anew_date'] = $time_Yhs;
			}

			// 카테고리 생성
			sql_member_category($mb['id']);

			if($mb_grade == 6)      { $cf_1 = 1; }
			else if($mb_grade == 5) { $cf_1 = 2; }
			else if($mb_grade == 4) { $cf_1 = 3; }
			else if($mb_grade == 3) { $cf_1 = 4; }
			else if($mb_grade == 2) { $cf_1 = 5; }

			// 회원 아이디가 존재하지 않을 경우만 실행
			if(!$pt['mb_id']) {
				$cf = sql_fetch("select * from shop_partner_config where index_no='$cf_1'");

				unset($pfrm);
				$pfrm['mb_id'] = $mb['id']; //회원명
				$pfrm['bank_name'] = $_POST['bank_name']; //예금주
				$pfrm['bank_number'] = $_POST['bank_number']; //계좌번호
				$pfrm['bank_company'] = $_POST['bank_company']; //은행명
				$pfrm['state'] = 1; //처리결과 1은 완료, 0은 대기
				$pfrm['memo'] = '관리자에 의해 승인처리 되었습니다.'; //메모
				$pfrm['wdate'] = $server_time; // 등록일
				$pfrm['cf_1'] = $cf_1; //레벨 인덱스번호
				$pfrm['bank_money'] = $cf['etc2']; //분양개설비
				$pfrm['bank_name2'] = $mb['name']; //입금자명
				$pfrm['bank_type'] = 1; //결제방식 1은 무통장, 2는 신용카드결제
				insert("shop_partner", $pfrm);

				// 추천 수수료적립
				include_once(TW_ADMIN_PATH."/partner/pt_rebate.php");

			} else {
				unset($pfrm);
				$pfrm['cf_1'] = $cf_1; // 레벨 인덱스번호
				if(!$pt['state']) $pfrm['state'] = 1; // 처리결과 "1"은 완료, "0"은 대기
				update("shop_partner", $pfrm," where mb_id='$mb[id]' ");
			}
		}

		update("shop_member", $mfrm," where index_no='$index_no'");
	}

	goto_url($return_url);
}


// 공급사 정보수정
if($mode == 'pw') {
	check_demo();

	check_admin_token();

	unset($sfrm);
    $sfrm['in_item'] = $_POST['in_item'];
	$sfrm['in_compay'] = $_POST['in_compay'];
	$sfrm['in_sanumber'] = $_POST['in_sanumber'];
	$sfrm['in_phone'] = $_POST['in_phone'];
	$sfrm['in_fax'] = $_POST['in_fax'];
	$sfrm['in_zipcode'] = $_POST['in_zipcode'];
	$sfrm['in_addr1'] = $_POST['in_addr1'];
	$sfrm['in_addr2'] = $_POST['in_addr2'];
	$sfrm['in_addr3'] = $_POST['in_addr3'];
	$sfrm['in_addr_jibeon'] = $_POST['in_addr_jibeon'];
	$sfrm['in_up'] = $_POST['in_up'];
	$sfrm['in_upte'] = $_POST['in_upte'];
	$sfrm['in_name'] = $_POST['in_name'];
	$sfrm['in_home'] = $_POST['in_home'];
	$sfrm['in_dam'] = $_POST['in_dam'];
	$sfrm['n_phone'] = $_POST['n_phone'];
	$sfrm['n_email'] = $_POST['n_email'];
	$sfrm['n_name'] = $_POST['n_name'];
	$sfrm['n_bank'] = $_POST['n_bank'];
	$sfrm['n_bank_num'] = $_POST['n_bank_num'];
	$sfrm['memo'] = $_POST['memo'];
	$sfrm['state'] = $_POST['state'];
	$sfrm['shop_open']	= $_POST['shop_open'];
	update("shop_seller", $sfrm," where mb_id='$mb_id'");

	unset($sfrm);
	$sfrm['isopen'] = $_POST['shop_open'];
	update("shop_goods", $sfrm," where mb_id='$_POST[sup_code]' ");

	goto_url('./pop_member_detail.php?code=pitem&index_no='.$index_no);
}
?>

<div class="new_win_body">
	<?php 
	include_once("./member/mem_{$code}.php"); 
	?>
</div>

<?php
include_once("admin_tail.sub.php");
?>