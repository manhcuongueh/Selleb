<?php
if(!defined('_TUBEWEB_')) exit;
?>

<!-- 올더게이트결제 시작 { -->
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
				$tot_tax_mny += $sell_price;
			} else {
				$comm_free_mny += $sell_price;
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

	switch($od['buymethod']) {
		case 'C'  : $Job = "onlycard"; break; // 신용카드
		case 'R'  : $Job = "onlyiche"; break; // 계좌이체
		case 'H'  : $Job = "onlyhp"; break; // 핸드폰 소액결제
		case 'S'  : $Job = "onlyvirtual"; break; // 가상계좌
		case 'ER' : $Job = "onlyichebankescrow"; break; // 에스크로 계좌이체
		case 'ES' : $Job = "onlyvirtualselfescrow"; break; // 에스크로 가상계좌
	}

	$AGS_HASHDATA = md5($StoreId.$odrkey.$good_mny);
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

<form name="frmAGS_pay" method="post" action='./allthegate/AGS_pay_ing.php'>
<input type="hidden" name=StoreId value="<?php echo $StoreId; ?>"> <!-- 상점아이디 -->
<input type="hidden" name=Job  value="<?php echo $Job; ?>"> <!-- 지불방법 --> 
<input type="hidden" name=StoreNm value="<?php echo $default['cf_nm_pg']; ?>"> <!-- 상점명 (50) -->
<input type="hidden" name=OrdNo value="<?php echo $odrkey; ?>"> <!-- 주문번호 (40)-->
<input type="hidden" name=ProdNm value="<?php echo $goodname; ?>"> <!-- 상품명 (300) -->
<input type="hidden" name=Amt value="<?php echo $good_mny; ?>"> <!-- 금액 (12) -->
<input type="hidden" name=MallUrl value="<?php echo TW_URL; ?>"> <!-- 상점URL (50) -->

<!-- [신용카드,핸드폰]결제와 [현금영수증자동발행]을 사용하시는 경우에 반드시 입력해 주시기 바랍니다. -->
<input type="hidden" name=UserId value="<?php echo $member['id']; ?>"> <!-- 회원아이디 (20) -->
<input type="hidden" name=OrdNm value="<?php echo $od['name']; ?>"> <!-- 주문자명 (40) -->
<input type="hidden" name=OrdPhone value="<?php echo $od['cellphone']; ?>"> <!-- 주문자연락처 (21) -->
<input type="hidden" name=UserEmail value="<?php echo $od['email']; ?>"> <!-- 주문자이메일 (50) -->
<input type="hidden" name=OrdAddr value="<?php echo print_address($od['addr1'], $od['addr2'], $od['addr3'], $od['addr_jibeon']); ?>"> <!-- 주문자주소 (100) -->
<input type="hidden" name=DlvAddr value="<?php echo print_address($od['b_addr1'], $od['b_addr2'], $od['b_addr3'], $od['b_addr_jibeon']); ?>"> <!-- 배송지주소 (100) -->
<input type="hidden" name=RcpNm value="<?php echo $od['b_name']; ?>"> <!-- 수신자명 (40) -->
<input type="hidden" name=RcpPhone value="<?php echo $od['b_cellphone']; ?>"> <!-- 수신자연락처 (21) -->
<input type="hidden" name=Remark value=""> <!-- 기타요구사항 (350) -->
<input type="hidden" name=CardSelect value="">
<!-- 결제창에 특정카드만 표기기능입니다. 
		  사용방법 예)  BC, 국민을 사용하고자 하는 경우 ☞ 100:200
						국민 만 사용하고자 하는 경우 ☞ 200
	 모두 사용하고자 할 때에는 아무 값도 입력하지 않습니다.
	 카드사별 코드는 매뉴얼에서 확인해 주시기 바랍니다. -->

<!-- 결제창 좌측상단에 상점의 로고이미지(85 * 38)를 표시할 수 있습니다. -->
<!-- 잘못된 값을 입력하거나 미입력시 올더게이트의 로고가 표시됩니다. -->
<input type="hidden" name=ags_logoimg_url value="">

<!-- 제목은 1컨텐츠당 5자 이내이며, 상점명;상품명;결제금액;제공기간; 순으로 입력해 주셔야 합니다. -->
<!-- 입력 예)업체명;판매상품;계산금액;제공기간; -->
<input type="hidden" name=SubjectData value="">

<!-- CP아이디를 핸드폰 결제 실거래 전환후에는 발급받으신 CPID로 변경하여 주시기 바랍니다. -->
<input type="hidden" name=HP_ID value="<?php echo $default['cf_ags_hp_id']; ?>">

<!-- CP비밀번호를 핸드폰 결제 실거래 전환후에는 발급받으신 비밀번호로 변경하여 주시기 바랍니다. -->
<input type="hidden" name=HP_PWD value="<?php echo $default['cf_ags_hp_pwd']; ?>">

<!-- SUB-CPID는 핸드폰 결제 실거래 전환후에 발급받으신 상점만 입력하여 주시기 바랍니다. -->
<input type="hidden" name=HP_SUBID value="<?php echo $default['cf_ags_hp_subid']; ?>">

<!-- 상품코드를 핸드폰 결제 실거래 전환후에는 발급받으신 상품코드로 변경하여 주시기 바랍니다. -->
<input type="hidden" name=ProdCode value="<?php echo $default['cf_ags_hp_code']; ?>">

<!-- 상품종류를 핸드폰 결제 실거래 전환후에는 발급받으신 상품종류로 변경하여 주시기 바랍니다. -->
<!-- 판매하는 상품이 디지털(컨텐츠)일 경우 = 1, 실물(상품)일 경우 = 2 -->
<input type="hidden" name=HP_UNITType value="<?php echo $default['cf_ags_hp_unit']; ?>">

<!-- 가상계좌 결제에서 입/출금 통보를 위한 필수 입력 사항 입니다. -->
<!-- 페이지주소는 도메인주소를 제외한 '/'이후 주소를 적어주시면 됩니다. -->
<input type="hidden" name=MallPage value="/shop/allthegate/AGS_VirAcctResult.php">

<!-- 가상계좌 결제에서 입금가능한 기한을 지정하는 기능입니다. -->
<!-- 발급일자로부터 최대 15일 이내로만 설정하셔야 합니다. -->
<!-- 값을 입력하지 않을 경우, 자동으로 발급일자로부터 5일 이후로 설정됩니다. -->
<input type="hidden" name=VIRTUAL_DEPODT value="">

<!-- 스크립트 및 플러그인에서 값을 설정하는 Hidden 필드  !!수정을 하시거나 삭제하지 마십시오-->
<!-- 각 결제 공통 사용 변수 -->
<input type="hidden" name=Flag value="">				<!-- 스크립트결제사용구분플래그 -->
<input type="hidden" name=AuthTy value="">				<!-- 결제형태 -->
<input type="hidden" name=SubTy value="">				<!-- 서브결제형태 -->
<input type="hidden" name=AGS_HASHDATA value="<?php echo $AGS_HASHDATA; ?>">	<!-- 암호화 HASHDATA -->

<!-- 신용카드 결제 사용 변수 -->
<input type="hidden" name=DeviId value="">				<!-- (신용카드공통)		단말기아이디 -->
<input type="hidden" name=QuotaInf value="0">			<!-- (신용카드공통)		일반할부개월설정변수 -->
<input type="hidden" name=NointInf value="NONE">		<!-- (신용카드공통)		무이자할부개월설정변수 -->
<input type="hidden" name=AuthYn value="">				<!-- (신용카드공통)		인증여부 -->
<input type="hidden" name=Instmt value="">				<!-- (신용카드공통)		할부개월수 -->
<input type="hidden" name=partial_mm value="">			<!-- (ISP사용)			일반할부기간 -->
<input type="hidden" name=noIntMonth value="">			<!-- (ISP사용)			무이자할부기간 -->
<input type="hidden" name=KVP_RESERVED1 value="">		<!-- (ISP사용)			RESERVED1 -->
<input type="hidden" name=KVP_RESERVED2 value="">		<!-- (ISP사용)			RESERVED2 -->
<input type="hidden" name=KVP_RESERVED3 value="">		<!-- (ISP사용)			RESERVED3 -->
<input type="hidden" name=KVP_CURRENCY value="">		<!-- (ISP사용)			통화코드 -->
<input type="hidden" name=KVP_CARDCODE value="">		<!-- (ISP사용)			카드사코드 -->
<input type="hidden" name=KVP_SESSIONKEY value="">		<!-- (ISP사용)			암호화코드 -->
<input type="hidden" name=KVP_ENCDATA value="">			<!-- (ISP사용)			암호화코드 -->
<input type="hidden" name=KVP_CONAME value="">			<!-- (ISP사용)			카드명 -->
<input type="hidden" name=KVP_NOINT value="">			<!-- (ISP사용)			무이자/일반여부(무이자=1, 일반=0) -->
<input type="hidden" name=KVP_QUOTA value="">			<!-- (ISP사용)			할부개월 -->
<input type="hidden" name=CardNo value="">				<!-- (안심클릭,일반사용)	카드번호 -->
<input type="hidden" name=MPI_CAVV value="">			<!-- (안심클릭,일반사용)	암호화코드 -->
<input type="hidden" name=MPI_ECI value="">				<!-- (안심클릭,일반사용)	암호화코드 -->
<input type="hidden" name=MPI_MD64 value="">			<!-- (안심클릭,일반사용)	암호화코드 -->
<input type="hidden" name=ExpMon value="">				<!-- (일반사용)			유효기간(월) -->
<input type="hidden" name=ExpYear value="">				<!-- (일반사용)			유효기간(년) -->
<input type="hidden" name=Passwd value="">				<!-- (일반사용)			비밀번호 -->
<input type="hidden" name=SocId value="">				<!-- (일반사용)			주민등록번호/사업자등록번호 -->

<!-- 계좌이체 결제 사용 변수 -->
<input type="hidden" name=ICHE_OUTBANKNAME value="">	<!-- 이체계좌은행명 -->
<input type="hidden" name=ICHE_OUTACCTNO value="">		<!-- 이체계좌예금주주민번호 -->
<input type="hidden" name=ICHE_OUTBANKMASTER value="">	<!-- 이체계좌예금주 -->
<input type="hidden" name=ICHE_AMOUNT value="">			<!-- 이체금액 -->

<!-- 핸드폰 결제 사용 변수 -->
<input type="hidden" name=HP_SERVERINFO value="">		<!-- 서버정보 -->
<input type="hidden" name=HP_HANDPHONE value="">		<!-- 핸드폰번호 -->
<input type="hidden" name=HP_COMPANY value="">			<!-- 통신사명(SKT,KTF,LGT) -->
<input type="hidden" name=HP_IDEN value="">				<!-- 인증시사용 -->
<input type="hidden" name=HP_IPADDR value="">			<!-- 아이피정보 -->

<!-- ARS 결제 사용 변수 -->
<input type="hidden" name=ARS_PHONE value="">			<!-- ARS번호 -->
<input type="hidden" name=ARS_NAME value="">			<!-- 전화가입자명 -->

<!-- 가상계좌 결제 사용 변수 -->
<input type="hidden" name=ZuminCode value="">			<!-- 가상계좌입금자주민번호 -->
<input type="hidden" name=VIRTUAL_CENTERCD value="">	<!-- 가상계좌은행코드 -->
<input type="hidden" name=VIRTUAL_NO value="">			<!-- 가상계좌번호 -->

<input type="hidden" name=mTId value="">	

<!-- 에스크로 결제 사용 변수 -->
<input type="hidden" name=ES_SENDNO value="">			<!-- 에스크로전문번호 -->

<!-- 계좌이체(소켓) 결제 사용 변수 -->
<input type="hidden" name=ICHE_SOCKETYN value="">		<!-- 계좌이체(소켓) 사용 여부 -->
<input type="hidden" name=ICHE_POSMTID value="">		<!-- 계좌이체(소켓) 이용기관주문번호 -->
<input type="hidden" name=ICHE_FNBCMTID value="">		<!-- 계좌이체(소켓) FNBC거래번호 -->
<input type="hidden" name=ICHE_APTRTS value="">			<!-- 계좌이체(소켓) 이체 시각 -->
<input type="hidden" name=ICHE_REMARK1 value="">		<!-- 계좌이체(소켓) 기타사항1 -->
<input type="hidden" name=ICHE_REMARK2 value="">		<!-- 계좌이체(소켓) 기타사항2 -->
<input type="hidden" name=ICHE_ECWYN value="">			<!-- 계좌이체(소켓) 에스크로여부 -->
<input type="hidden" name=ICHE_ECWID value="">			<!-- 계좌이체(소켓) 에스크로ID -->
<input type="hidden" name=ICHE_ECWAMT1 value="">		<!-- 계좌이체(소켓) 에스크로결제금액1 -->
<input type="hidden" name=ICHE_ECWAMT2 value="">		<!-- 계좌이체(소켓) 에스크로결제금액2 -->
<input type="hidden" name=ICHE_CASHYN value="">			<!-- 계좌이체(소켓) 현금영수증발행여부 -->
<input type="hidden" name=ICHE_CASHGUBUN_CD value="">	<!-- 계좌이체(소켓) 현금영수증구분 -->
<input type="hidden" name=ICHE_CASHID_NO value="">		<!-- 계좌이체(소켓) 현금영수증신분확인번호 -->

<!-- 텔래뱅킹-계좌이체(소켓) 결제 사용 변수 -->
<input type="hidden" name=ICHEARS_SOCKETYN value="">	<!-- 텔레뱅킹계좌이체(소켓) 사용 여부 -->
<input type="hidden" name=ICHEARS_ADMNO value="">		<!-- 텔레뱅킹계좌이체 승인번호 -->
<input type="hidden" name=ICHEARS_POSMTID value="">		<!-- 텔레뱅킹계좌이체 이용기관주문번호 -->
<input type="hidden" name=ICHEARS_CENTERCD value="">	<!-- 텔레뱅킹계좌이체 은행코드 -->
<input type="hidden" name=ICHEARS_HPNO value="">		<!-- 텔레뱅킹계좌이체 휴대폰번호 -->
<!-- 스크립트 및 플러그인에서 값을 설정하는 Hidden 필드  !!수정을 하시거나 삭제하지 마십시오-->

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

<div class="mart20 tac">
	<input type="button" value="결제하기" onclick="javascript:Pay(frmAGS_pay);" class="btn_medium">
</div>
</form>

<?php /* 절대 위치변경 및 지우지 마세요 */ ?>
<script>Enable_Flag(frmAGS_pay);</script>

<!-- 아래 JS는 반드시 frmAGS_pay Form 하단에 위치시켜 주세요 -->
<script type="text/javascript" src="https://www.allthegate.com/plugin/jquery-1.11.1.js"></script>
<script type="text/javascript" src="https://www.allthegate.com/payment/webPay/js/ATGClient_new.js"></script>
<!-- } 올더게이트결제 끝 -->
