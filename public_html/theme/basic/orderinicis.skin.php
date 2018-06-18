<?php
if(!defined('_TUBEWEB_')) exit;
?>

<!-- 이니시스 결제 시작 { -->
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
	<tr align="center">
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

	//지불방법
	switch($ss_pay_method) {
		case 'C' : //신용카드
			$gopaymethod = "Card";
			break;
		case 'ER' : // 에스크로 계좌이체
		case 'R' : //계좌이체
			$gopaymethod = "DirectBank";
			break;
		case 'H' : //휴대폰
			$gopaymethod = "HPP";
			break;
		case 'ES' : //에스크로 가상계좌
		case 'S' : //가상계좌
			$gopaymethod = "VBank";
			break;
	}

	/**************************
	* 1. 라이브러리 인클루드 *
	**************************/
	require(ROOT_INICIS."/libs/INILib.php");

	$inipay = new INIpay50;

	/**************************
	* 3. 암호화 대상/값 설정 *
	**************************/
	$inipay->SetField("inipayhome", ROOT_INICIS); // 이니페이 홈디렉터리(상점수정 필요)
	$inipay->SetField("type", "chkfake"); // 고정 (절대 수정 불가)
	$inipay->SetField("debug", "true"); // 로그모드("true"로 설정하면 상세로그가 생성됨.)
	$inipay->SetField("enctype","asym"); //asym:비대칭, symm:대칭(현재 asym으로 고정)
	$inipay->SetField("admin", "1111"); // 키패스워드(키발급시 생성, 상점관리자 패스워드와 상관없음)
	$inipay->SetField("checkopt", "false"); //base64함:false, base64안함:true(현재 false로 고정)

	$inipay->SetField("mid", $ini_mid); // 상점아이디 테스트용
	$inipay->SetField("price", $good_mny); // 가격

	$quotabase = $default['cf_inicis_quota'];
	if($default['cf_inicis_noint_mt']) {
		$quotabase .= "(".$default['cf_inicis_noint_mt'].")";
	}

	$inipay->SetField("nointerest", $default['cf_inicis_noint_yn']); //무이자여부(no:일반, yes:무이자)
	$inipay->SetField("quotabase", $quotabase); //일반 할부기간

	/********************************
	* 4. 암호화 대상/값을 암호화함 *
	********************************/
	$inipay->startAction();

	/*********************
	* 5. 암호화 결과  *
	*********************/
	if( $inipay->GetResult("ResultCode") != "00" )
	{
		echo $inipay->GetResult("ResultMsg");
		exit(0);
	}

	/*********************
	* 6. 세션정보 저장  *
	*********************/
	set_session('INI_MID', $ini_mid); // 상점ID 테스트용
	set_session('INI_ADMIN', '1111'); // 키패스워드(키발급시 생성, 상점관리자 패스워드와 상관없음)
	set_session('INI_PRICE', $good_mny); // 가격
	set_session('INI_RN', $inipay->GetResult("rn")); // 고정 (절대 수정 불가)
	set_session('INI_ENCTYPE', $inipay->GetResult("enctype")); //고정 (절대 수정 불가)
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

<form name="ini" method="post" action="./orderinicis_result.php" onSubmit="return pay(this);">
<input type='hidden' name='gopaymethod' value="<?php echo $gopaymethod; ?>"> <!-- 지불방법 -->
<input type='hidden' name='goodname' value="<?php echo $goodname; ?>"> <!-- 상품명 -->
<input type='hidden' name='buyername' value="<?php echo $od['name']; ?>"> <!-- 주문자명 -->
<input type='hidden' name='buyeremail' value="<?php echo $od['email']; ?>"> <!-- 주문자 이메일 -->
<input type='hidden' name='buyertel' value="<?php echo $od['cellphone']; ?>"> <!-- 주문자 핸드폰 -->
<input type='hidden' name='currency' value="WON"> <!-- 화폐단위 -->
<input type='hidden' name='acceptmethod' value="HPP(<?php echo $default['cf_inicis_hp_unit']; ?>):SKIN(<?php echo $default['cf_inicis_skin']; ?>):Card(0):OCB:<?php echo $default['cf_inicis_tax_yn']; ?>:cardpoint">
<input type='hidden' name='oid' value="<?php echo $odrkey; ?>"> <!-- 주문번호 -->
<input type='hidden' name='ini_encfield' value="<?php echo($inipay->GetResult("encfield")); ?>">
<input type='hidden' name='ini_certid' value="<?php echo($inipay->GetResult("certid")); ?>">
<input type='hidden' name='quotainterest' value="">
<input type='hidden' name='paymethod' value="">
<input type='hidden' name='cardcode' value="">
<input type='hidden' name='cardquota' value="">
<input type='hidden' name='rbankcode' value="">
<input type='hidden' name='reqsign' value="DONE">
<input type='hidden' name='encrypted' value="">
<input type='hidden' name='sessionkey' value="">
<input type='hidden' name='uid' value="">
<input type='hidden' name='sid' value="">
<input type='hidden' name='version' value="4000">
<input type='hidden' name='clickcontrol' value="">
<?php if($od['taxflag']) { ?>
<input type="hidden" name="comm_tax_mny"  value="<?php echo $comm_tax_mny; ?>">  <!-- 과세금액 -->
<input type="hidden" name="comm_vat_mny"  value="<?php echo $comm_vat_mny; ?>">  <!-- 부가세 -->
<input type="hidden" name="comm_free_mny" value="<?php echo $comm_free_mny; ?>"> <!-- 비과세 -->
<?php } ?>

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
		<th>결제금액</th>
		<td class="fs14 bold"><?php echo display_price2($good_mny); ?></td>
	</tr>
	</table>
</div>

<div id="display_pay_button" class="mart20 tac">
	<input type="submit" value="결제하기" alt="결제를 요청합니다" class="btn_medium">
</div>
</form>
<!-- } 이니시스 결제 끝 -->