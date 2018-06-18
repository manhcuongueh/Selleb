<?php
if(!defined('_TUBEWEB_')) exit;
?>

<!-- kcp결제 시작 { -->
<div><img src="<?php echo TW_IMG_URL; ?>/orderform_pay.gif"></div>
<div class="tbl_head02 mart20">
	<table class="wfull">
	<colgroup>
		<col>
		<col width="100">
		<col width="60">
		<col width="80">
		<col width="100">
	</colgroup>
	<thead>
	<tr>
		<th class="bl_nolne">상품/옵션정보</th>
		<th>상품금액</th>
		<th>수량</th>
		<th>적립금</th>
		<th>주문금액</th>
	</tr>
	</thead>
	<tbody>
	<?php
	$comm_tax_mny  = 0; // 과세금액
	$comm_vat_mny  = 0; // 부가세
	$comm_free_mny = 0; // 면세금액
	$tot_tax_mny   = 0;
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$gs = get_goods($row['gs_id']);

		// 합계금액 계산
		$sql = " select SUM(IF(io_type = 1, (io_price * ct_qty),((io_price + ct_price) * ct_qty))) as price,
						SUM(IF(io_type = 1, (0),(ct_point * ct_qty))) as point,
						SUM(IF(io_type = 1, (0),(ct_qty))) as qty,
						SUM(io_price * ct_qty) as opt_price
					from shop_cart
				   where odrkey = '$odrkey'
					 and gs_id = '$row[gs_id]'
					 order by io_type asc, index_no asc ";
		$sum = sql_fetch($sql);

		if(!$goodname)
			$goodname = preg_replace("/\'|\"|\||\,|\&|\;/", "", $gs['gname']);

		$goods_count++;

		// 에스크로 상품정보
		if($default['cf_escrow_yn'] && in_array($ss_pay_method, array('ER','ES'))) {
			if($i > 0)
				$good_info .= chr(30);

			$good_info .= "seq=".($i+1).chr(31);
			$good_info .= "ordr_numb={$odrkey}_".sprintf("%04d", ($i+1)).chr(31);
			$good_info .= "good_name=".addslashes($gs['gname']).chr(31);
			$good_info .= "good_cntx=".$sum['qty'].chr(31);
			$good_info .= "good_amtx=".$sum['price'].chr(31);
		}

		unset($it_name);
		$it_options = print_complete_options($row['gs_id'], $odrkey);
		if($it_options && $row['io_id']){
			$it_name = '<div class="sod_opt">'.$it_options.'</div>';
		}

		$sell_price = $sum['price'];
		$sell_qty = $sum['qty'];
		$sell_amt = $sum['price'] - $sum['opt_price'];

		// 복합과세금액
		if($od['taxflag']) {
			if($gs['notax']) {
				$tot_tax_mny += (int)$sell_price;
			} else {
				$comm_free_mny += (int)$sell_price;
			}
		}

		if($row['mb_yes'])
			$point = $sum['point'];
		else
			$point = 0;

		$href = TW_SHOP_URL.'/view.php?index_no='.$row['gs_id'];
	?>
	<tr>
		<td class="bl_nolne">
			<div class="tbl_wrap">
				<table class="wfull">
				<colgroup>
					<col width="90">
					<col>
				</colgroup>
				<tr>
					<td class="vat tal"><a href="<?php echo $href; ?>"><?php echo get_it_image($row['gs_id'], $gs['simg1'], 80, 80); ?></a></td>
					<td class="vat tal"><a href="<?php echo $href; ?>"><?php echo get_text($gs['gname']); ?></a><?php echo $it_name; ?></td>
				</tr>
				</table>
			</div>
		</td>
		<td><?php echo display_price2($sell_amt); ?></td>
		<td><?php echo display_qty($sell_qty); ?></td>
		<td><?php echo display_point($point); ?></td>
		<td class="bold"><?php echo display_price2($sell_price); ?></td>
	</tr>
	<?php
		if($row['mb_yes'])
			$tot_point += $point;
	}

	// 총금액 뽑기
	$sql = " select SUM(account) as it_amt,
					SUM(del_account) as de_amt,
					SUM(dc_exp_amt) as dc_amt,
					SUM(use_point) as po_amt,
					SUM(use_account) as buy_amt
			   from shop_order
			  where odrkey='$odrkey' ";
	$tot_sum = sql_fetch($sql);

	if($goods_count) $goodname .= ' 외 '.$goods_count.'건';

	// 복합과세처리
	if($od['taxflag']) {
		$comm_tax_mny = round(($tot_tax_mny + $tot_sum['de_amt']) / 1.1);
		$comm_vat_mny = ($tot_tax_mny + $tot_sum['de_amt']) - $comm_tax_mny;
	}

	$buyr_tel1 = $od['telephone'];
	$buyr_tel2 = $od['cellphone'];
	$buyr_name = $od['name'];
	$buyr_mail = $od['email'];

	// 지불방법
	switch($ss_pay_method) {
		case 'C': //신용카드
			$pay_method = "100000000000";
			break;
		case 'ER': //계좌이체
			$pay_method = "010000000000";
			break;
		case 'R':
			$pay_method = "010000000000";
			$default['cf_escrow_yn'] = 0;
			break;
		case 'ES': //가상계좌
			$pay_method = "001000000000";
			break;
		case 'S':
			$pay_method = "001000000000";
			$default['cf_escrow_yn'] = 0;
			break;
		case 'H': //핸드폰
			$pay_method = "000010000000";
			break;
	}
	?>
	</tbody>
	</table>
	<div class="total_price">
		<span class="fl">적립포인트 합계 : <strong><?php echo display_point($tot_point); ?></strong></span>
		<span class="fr">
			(주문금액 : <strong><?php echo display_price2($tot_sum['it_amt']); ?></strong> +
			배송비결제 : <strong><?php echo display_price2($tot_sum['de_amt']); ?></strong>) -
			(쿠폰할인 : <strong><?php echo display_price2($tot_sum['dc_amt']); ?></strong> +
			포인트결제 : <strong><?php echo display_price2($tot_sum['po_amt']); ?></strong>) =
			총계 : <strong class="fc_red fs18"><?php echo display_price2($tot_sum['buy_amt']); ?></strong>
		</span>
	</div>
</div>

<form name='order_info' method='post' action='./kcp/pp_ax_hub.php'>
<input type='hidden' name='pay_method'  value="<?php echo $pay_method; ?>"> <!-- 지불방법 -->
<input type='hidden' name='ordr_idxx'   value="<?php echo $odrkey; ?>"> <!-- 주문번호(한글불가)-->
<input type='hidden' name='good_name'   value="<?php echo $goodname; ?>"> <!-- 상품명 -->
<input type='hidden' name='good_mny'    value="<?php echo $good_mny; ?>"> <!-- 결제금액 -->
<input type='hidden' name='buyr_name'   value="<?php echo $buyr_name; ?>"> <!-- 주문자명 -->
<input type='hidden' name='buyr_mail'   value="<?php echo $buyr_mail; ?>"> <!-- 주문자 이메일 -->
<input type='hidden' name='buyr_tel1'   value="<?php echo $buyr_tel1; ?>"> <!-- 주문자 연락처 -->
<input type='hidden' name='buyr_tel2'   value="<?php echo $buyr_tel2; ?>"> <!-- 주문자 핸드폰 -->
<?php
/* ============================================================================== */
/* =   1-2. 에스크로 정보                                                       = */
/* = -------------------------------------------------------------------------- = */
/* =   에스크로 사용업체에 적용되는 정보입니다.                                 = */
/* = -------------------------------------------------------------------------- = */
?>
<input type="hidden" name="rcvr_name"	value="<?php echo $od['b_name']; ?>"> <!-- 수취인명 -->
<input type="hidden" name="rcvr_tel1"	value="<?php echo $od['b_telephone']; ?>"> <!-- 수취인 전화번호 -->
<input type="hidden" name="rcvr_tel2"	value="<?php echo $od['b_cellphone']; ?>"> <!-- 수취인 휴대폰번호 -->
<input type="hidden" name="rcvr_mail"	value="<?php echo $buyr_mail; ?>"> <!-- 수취인 E-mail -->
<input type="hidden" name="rcvr_zipx"	value="<?php echo $od['b_zip']; ?>"> <!-- 수취인 우편번호 -->
<input type="hidden" name="rcvr_add1"	value="<?php echo $od['b_addr1']; ?>"> <!-- 수취인 주소 -->
<input type="hidden" name="rcvr_add2"	value="<?php echo $od['b_addr2']; ?>"> <!-- 수취인 상세주소 -->
<?php
/* ============================================================================== */
/* =   2. 가맹점 필수 정보 설정                                                 = */
/* = -------------------------------------------------------------------------- = */
/* =   ※ 필수 - 결제에 반드시 필요한 정보입니다.                               = */
/* =   site_conf_inc.php 파일을 참고하셔서 수정하시기 바랍니다.                 = */
/* = -------------------------------------------------------------------------- = */
// 요청종류 : 승인(pay)/취소,매입(mod) 요청시 사용
?>
<input type="hidden" name="req_tx"      value="pay"> <!--요청 구분(승인/취소)-->
<input type="hidden" name="site_cd"     value="<?php echo $g_conf_site_cd; ?>"> <!-- 사이트코드 -->
<input type="hidden" name="site_name"   value="<?php echo $g_conf_site_name; ?>"> <!-- 상점명 -->
<?php
/*
할부옵션 : Payplus Plug-in에서 카드결제시 최대로 표시할 할부개월 수를 설정합니다.(0 ~ 18 까지 설정 가능)
※ 주의  - 할부 선택은 결제금액이 50,000원 이상일 경우에만 가능, 50000원 미만의 금액은 일시불로만 표기됩니다
		   예) value 값을 "5" 로 설정했을 경우 => 카드결제시 결제창에 일시불부터 5개월까지 선택가능
*/

$kcp_quota = $default['cf_kcp_quota']; // 할부옵션
?>
<input type="hidden" name="quotaopt"	value="<?php echo ($kcp_quota ? $kcp_quota : '12'); ?>"> <!-- 할부옵션 -->
<input type="hidden" name="currency"	value="WON"> <!-- 화폐단위 -->
<?php
/* ============================================================================== */
/* =   3. Payplus Plugin 필수 정보(변경 불가)                                   = */
/* = -------------------------------------------------------------------------- = */
/* =   결제에 필요한 주문 정보를 입력 및 설정합니다.                            = */
/* = -------------------------------------------------------------------------- = */
?>
<!-- PLUGIN 설정 정보입니다(변경 불가) -->
<input type="hidden" name="module_type"     value="01">
<!-- 복합 포인트 결제시 넘어오는 포인트사 코드 : OK캐쉬백(SCSK), 베네피아 복지포인트(SCWB) -->
<input type="hidden" name="epnt_issu"       value="">
<?php
/* ============================================================================== */
/* =   3-1. Payplus Plugin 에스크로결제 사용시 필수 정보                        = */
/* = -------------------------------------------------------------------------- = */
/* =   결제에 필요한 주문 정보를 입력 및 설정합니다.                            = */
/* = -------------------------------------------------------------------------- = */
?>
<!-- 에스크로 사용 여부 : 반드시 Y 로 설정 -->
<input type="hidden" name="escw_used"       value="Y">
<!-- 에스크로 결제처리 모드 : 에스크로: Y, 일반: N, KCP 설정 조건: O  -->
<input type="hidden" name="pay_mod"         value="<?php echo ($default['cf_escrow_yn']?"Y":"N"); ?>">
<!-- 배송 소요일 : 예상 배송 소요일을 입력 -->
<input type="hidden" name="deli_term" value="03">
<!-- 장바구니 상품 개수 : 장바구니에 담겨있는 상품의 개수를 입력(good_info의 seq값 참조) -->
<input type="hidden" name="bask_cntx" value="<?php echo (int)$goods_count + 1; ?>">
<!-- 장바구니 상품 상세 정보 (자바 스크립트 샘플 create_goodInfo()가 온로드 이벤트시 설정되는 부분입니다.) -->
<input type="hidden" name="good_info"       value="<?php echo $good_info; ?>">

<input type="hidden" name="res_cd"          value="">
<input type="hidden" name="res_msg"         value="">
<input type="hidden" name="tno"             value="">
<input type="hidden" name="trace_no"        value="">
<input type="hidden" name="enc_info"        value="">
<input type="hidden" name="enc_data"        value="">
<input type="hidden" name="ret_pay_method"  value="">
<input type="hidden" name="tran_cd"         value="">
<input type="hidden" name="bank_name"       value="">
<input type="hidden" name="bank_issu"       value="">
<input type="hidden" name="use_pay_method"  value="">

<!--  현금영수증 관련 정보 : Payplus Plugin 에서 설정하는 정보입니다 -->
<input type="hidden" name="cash_tsdtime"    value="">
<input type="hidden" name="cash_yn"         value="">
<input type="hidden" name="cash_authno"     value="">
<input type="hidden" name="cash_tr_code"    value="">
<input type="hidden" name="cash_id_info"    value="">

<!-- 2012년 8월 18일 정자상거래법 개정 관련 설정 부분 -->
<!-- 제공 기간 설정 0:일회성 1:기간설정(ex 1:2012010120120131)  -->
<input type="hidden" name="good_expr"		value="0">

<!-- 가맹점에서 관리하는 고객 아이디 설정을 해야 합니다.(필수 설정) -->
<input type="hidden" name="shop_user_id"    value="<?php echo $member['id']; ?>">

<!-- 복지포인트 결제시 가맹점에 할당되어진 코드 값을 입력해야합니다.(필수 설정) -->
<input type="hidden" name="pt_memcorp_cd"   value="">

<input type="hidden" name="kcp_noint"       value="<?php echo ($default['cf_kcp_noint_yn']); ?>">

<input type="hidden" name="kcp_noint_quota" value="<?php echo ($default['cf_kcp_noint_mt']); ?>"/>

<!-- 가상계좌 은행 선택 파라미터 -->
<input type="hidden" name="wish_vbank_list" value="05:03:04:07:11:23:26:32:34:81:71">

<!-- 현금영수증 등록 창을 출력 여부를 설정하는 파라미터 입니다 -->
<input type="hidden" name="disp_tax_yn"     value="<?php echo $default['cf_kcp_tax_yn']; ?>">
<input type="hidden" name="site_logo"       value="http://testpay.kcp.co.kr/plugin/img/KcpLogo.jpg">

<!-- skin_indx 값은 스킨을 변경할 수 있는 파라미터이며 총 7가지가 지원됩니다. -->
<input type='hidden' name='skin_indx'       value='1'>
<?php
/* ============================================================================== */
/* =   4. 옵션 정보                                                             = */
/* = -------------------------------------------------------------------------- = */
/* =   ※ 옵션 - 결제에 필요한 추가 옵션 정보를 입력 및 설정합니다.             = */
/* = -------------------------------------------------------------------------- = */

/* 사용카드 설정 여부 파라미터 입니다.(통합결제창 노출 유무)
<input type="hidden" name="used_card_YN"        value="Y"/> */
/* 사용카드 설정 파라미터 입니다. (해당 카드만 결제창에 보이게 설정하는 파라미터입니다. used_card_YN 값이 Y일때 적용됩니다.
/<input type="hidden" name="used_card"        value="CCBC:CCKM:CCSS"/> */

/* 신용카드 결제시 OK캐쉬백 적립 여부를 묻는 창을 설정하는 파라미터 입니다
	 포인트 가맹점의 경우에만 창이 보여집니다
	<input type="hidden" name="save_ocb"        value="Y"/> */

/* 고정 할부 개월 수 선택
	   value값을 "7" 로 설정했을 경우 => 카드결제시 결제창에 할부 7개월만 선택가능
<input type="hidden" name="fix_inst"        value="07"/> */

/*  무이자 옵션
		※ 설정할부    (가맹점 관리자 페이지에 설정 된 무이자 설정을 따른다)                             - "" 로 설정
		※ 일반할부    (KCP 이벤트 이외에 설정 된 모든 무이자 설정을 무시한다)                           - "N" 로 설정
		※ 무이자 할부 (가맹점 관리자 페이지에 설정 된 무이자 이벤트 중 원하는 무이자 설정을 세팅한다)   - "Y" 로 설정
<input type="hidden" name="kcp_noint"       value=""/> */

/*  무이자 설정
		※ 주의 1 : 할부는 결제금액이 50,000 원 이상일 경우에만 가능
		※ 주의 2 : 무이자 설정값은 무이자 옵션이 Y일 경우에만 결제 창에 적용
		예) 전 카드 2,3,6개월 무이자(국민,비씨,엘지,삼성,신한,현대,롯데,외환) : ALL-02:03:04
		BC 2,3,6개월, 국민 3,6개월, 삼성 6,9개월 무이자 : CCBC-02:03:06,CCKM-03:06,CCSS-03:06:04
<input type="hidden" name="kcp_noint_quota" value="CCBC-02:03:06,CCKM-03:06,CCSS-03:06:09"/> */


/* 해외카드 구분하는 파라미터 입니다.(해외비자, 해외마스터, 해외JCB로 구분하여 표시)
<input type="hidden" name="used_card_CCXX"        value="Y"/> */

/*  가상계좌 은행 선택 파라미터
	 ※ 해당 은행을 결제창에서 보이게 합니다.(은행코드는 매뉴얼을 참조)
<input type="hidden" name="wish_vbank_list" value="05:03:04:07:11:23:26:32:34:81:71"/> */

/*  가상계좌 입금 기한 설정하는 파라미터 - 발급일 + 3일
<input type="hidden" name="vcnt_expire_term" value="3"/> */

/*  가상계좌 입금 시간 설정하는 파라미터
	 HHMMSS형식으로 입력하시기 바랍니다
	 설정을 안하시는경우 기본적으로 23시59분59초가 세팅이 됩니다
	 <input type="hidden" name="vcnt_expire_term_time" value="120000"/> */

/* 포인트 결제시 복합 결제(신용카드+포인트) 여부를 결정할 수 있습니다.- N 일경우 복합결제 사용안함
	<input type="hidden" name="complex_pnt_yn" value="N"/>    */

/* 현금영수증 등록 창을 출력 여부를 설정하는 파라미터 입니다
	 ※ Y : 현금영수증 등록 창 출력
	 ※ N : 현금영수증 등록 창 출력 안함
※ 주의 : 현금영수증 사용 시 KCP 상점관리자 페이지에서 현금영수증 사용 동의를 하셔야 합니다
	<input type="hidden" name="disp_tax_yn"     value="Y"/> */

/* 결제창에 가맹점 사이트의 로고를 플러그인 좌측 상단에 출력하는 파라미터 입니다
   업체의 로고가 있는 URL을 정확히 입력하셔야 하며, 최대 150 X 50  미만 크기 지원

※ 주의 : 로고 용량이 150 X 50 이상일 경우 site_name 값이 표시됩니다.
	<input type="hidden" name="site_logo"       value="" /> */

/* 결제창 영문 표시 파라미터 입니다. 영문을 기본으로 사용하시려면 Y로 세팅하시기 바랍니다
	2010-06월 현재 신용카드와 가상계좌만 지원됩니다
	<input type='hidden' name='eng_flag'      value='Y'> */

/* KCP는 과세상품과 비과세상품을 동시에 판매하는 업체들의 결제관리에 대한 편의성을 제공해드리고자,
   복합과세 전용 사이트코드를 지원해 드리며 총 금액에 대해 복합과세 처리가 가능하도록 제공하고 있습니다
   복합과세 전용 사이트 코드로 계약하신 가맹점에만 해당이 됩니다
   상품별이 아니라 금액으로 구분하여 요청하셔야 합니다
   총결제 금액은 과세금액 + 부과세 + 비과세금액의 합과 같아야 합니다.
   (good_mny = comm_tax_mny + comm_vat_mny + comm_free_mny)

	<input type="hidden" name="tax_flag"       value="TG03">  <!-- 변경불가	   -->
	<input type="hidden" name="comm_tax_mny"   value=""    >  <!-- 과세금액	   -->
	<input type="hidden" name="comm_vat_mny"   value=""    >  <!-- 부가세	   -->
	<input type="hidden" name="comm_free_mny"  value=""    >  <!-- 비과세 금액 --> */

/* skin_indx 값은 스킨을 변경할 수 있는 파라미터이며 총 7가지가 지원됩니다.
   변경을 원하시면 1부터 7까지 값을 넣어주시기 바랍니다.

	<input type='hidden' name='skin_indx'      value='1'> */

/* 상품코드 설정 파라미터 입니다.(상품권을 따로 구분하여 처리할 수 있는 옵션기능입니다.)
	<input type='hidden' name='good_cd'      value=''> */

/* = -------------------------------------------------------------------------- = */
/* =   4. 옵션 정보 END                                                         = */
/* ============================================================================== */
?>
<?php
if($od['taxflag']) {
/* KCP는 과세상품과 비과세상품을 동시에 판매하는 업체들의 결제관리에 대한 편의성을 제공해드리고자,
   복합과세 전용 사이트코드를 지원해 드리며 총 금액에 대해 복합과세 처리가 가능하도록 제공하고 있습니다

   복합과세 전용 사이트 코드로 계약하신 가맹점에만 해당이 됩니다

   상품별이 아니라 금액으로 구분하여 요청하셔야 합니다

   총결제 금액은 과세금액 + 부과세 + 비과세금액의 합과 같아야 합니다.
   (good_mny = comm_tax_mny + comm_vat_mny + comm_free_mny) */
?>
<input type="hidden" name="tax_flag"		value="TG03"> <!-- 변경불가 -->
<input type="hidden" name="comm_tax_mny"	value="<?php echo $comm_tax_mny; ?>"> <!-- 과세금액 -->
<input type="hidden" name="comm_vat_mny"	value="<?php echo $comm_vat_mny; ?>"> <!-- 부가세 -->
<input type="hidden" name="comm_free_mny"	value="<?php echo $comm_free_mny; ?>"> <!-- 비과세 금액 -->
<?php
}
?>
<h3 class="s_stit mart30 marb5">주문고객 정보</h3>
<div class="tbl_frm01">
	<table class="wfull">
	<colgroup>
		<col width="125">
		<col>
	</colgroup>
	<tr>
		<th>주문자</th>
		<td><?php echo $od['name']; ?></td>
	</tr>
	<tr>
		<th>핸드폰</th>
		<td><?php echo $od['cellphone']; ?></td>
	</tr>
	<tr>
		<th>전화번호</th>
		<td><?php echo $od['telephone']; ?></td>
	</tr>
	<tr>
		<th>이메일</th>
		<td><?php echo $od['email']; ?></td>
	</tr>
	<tr>
		<th>주소</th>
		<td><?php echo print_address($od['addr1'], $od['addr2'], $od['addr3'], $od['addr_jibeon']); ?></td>
	</tr>
	</table>
</div>

<h3 class="s_stit mart30 marb5">배송지 정보</h3>
<div class="tbl_frm01">
	<table class="wfull">
	<colgroup>
		<col width="125">
		<col>
	</colgroup>
	<tr>
		<th>수령인</th>
		<td><?php echo $od['b_name']; ?></td>
	</tr>
	<tr>
		<th>핸드폰</th>
		<td><?php echo $od['b_cellphone']; ?></td>
	</tr>
	<tr>
		<th>전화번호</th>
		<td><?php echo $od['b_telephone']; ?></td>
	</tr>
	<tr>
		<th>주소</th>
		<td><?php echo print_address($od['b_addr1'], $od['b_addr2'], $od['b_addr3'], $od['b_addr_jibeon']); ?></td>
	</tr>
	<?php if($od['memo']) { ?>
	<tr>
		<th>배송시 요청사항</th>
		<td><?php echo $od['memo']; ?></td>
	</tr>
	<?php } ?>
	</table>
</div>

<h3 class="s_stit mart30 marb5">결제 정보</h3>
<div class="tbl_frm01">
	<table class="wfull">
	<colgroup>
		<col width="125">
		<col>
	</colgroup>
	<tr>
		<th>결제방법</th>
		<td><?php echo $ar_method[$ss_pay_method]; ?></td>
	</tr>
	<tr>
		<th>총 결제금액</th>
		<td class="fs14 bold"><?php echo display_price2($good_mny); ?></td>
	</tr>
	</table>
</div>

<div id="display_pay_button" style="display:none" class="mart20 tac">
	<input type="submit" value="결제하기" alt="결제를 요청합니다" onclick="return jsf__pay(this.form);" class="btn_medium">
</div>

<!-- Payplus Plug-in 설치 안내 -->
<div id="display_setup_message" class="mart20 tac">
	<span class="red">결제를 계속 하시려면 상단의 노란색 표시줄을 클릭</span>하시거나<br/>
	<a href="http://pay.kcp.co.kr/plugin/file_vista/PayplusWizard.exe"><span class="bold">[수동설치]</span></a>를 눌러 Payplus Plug-in을 설치하시기 바랍니다.<br/>[수동설치]를 눌러 설치하신 경우 <span class="red bold">새로고침(F5)키</span>를 눌러 진행하시기 바랍니다.
</div>
</form>

<? /* 위치변경 하지 마세요! */ ?>
<script>
CheckPayplusInstall();
</script>
<!-- } kcp결제 끝 -->
