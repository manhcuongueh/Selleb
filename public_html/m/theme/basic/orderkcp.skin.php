<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<form name="order_info" method="POST" accept-charset="euc-kr">
<div class="m_mypage_bg">
	<table class="mynavbar mart10">
	<colgroup>
		<col width="50%">
		<col width="50%">
	</colgroup>
	<tbody>
	<tr>
		<td class="selected"><span class="strong">주문정보</span></td>
		<td class="fc_125">주문일 : <?php echo $od['orderdate_s']; ?>&nbsp;(<?php echo get_yoil($od['orderdate_s']); ?>)</td>
	</tr>
	</tbody>
	</table>

	<div class="my_vbox mart10">
		<table>
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
							SUM(IF(io_type = 1, (0),(ct_qty))) as qty
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
			if($it_options) {
				if($row['io_id']){
					$it_name = '<div class="padt5">'.$it_options.'</div>';
				}
			}

			$sell_price = $sum['price'];
			$sell_qty = $sum['qty'];

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
		?>
		<tr>
			<td class="mi_at" colspan=2>
				<span class="strong"><?php echo get_text($gs['gname']); ?></span>
				<?php echo $it_name; ?>
			</td>
		</tr>
		<tr>
			<td class="mi_dt">&bull; 주문금액</td>
			<td class="mi_bt tar"><?php echo display_price2($sell_price); ?></td>
		</tr>
		<tr>
			<td class="mi_dt">&bull; 주문수량</td>
			<td class="mi_bt tar"><?php echo display_qty($sell_qty); ?></td>
		</tr>
		<?php
			if($row['mb_yes']) {
				$tot_point += $point;
			}
		}

		if($goods_count) $goodname .= ' 외 '.$goods_count.'건';

		// 복합과세처리
		if($od['taxflag']) {
			$comm_tax_mny = round(($tot_tax_mny + $tot_sum['de_amt']) / 1.1);
			$comm_vat_mny = ($tot_tax_mny + $tot_sum['de_amt']) - $comm_tax_mny;
		}
		?>
		</tbody>
		</table>
	</div>

	<div class="mart20">
		<input type="hidden" name="ordr_idxx" value="<?php echo $odrkey; ?>"> <!-- 주문번호 -->
		<input type="hidden" name="good_name" value="<?php echo $goodname; ?>">	<!-- 상품명 -->
		<input type="hidden" name="good_mny"  value="<?php echo $good_mny; ?>">	<!-- 결제금액 -->
		<input type="hidden" name="buyr_name" value="<?php echo $od['name']; ?>"> <!-- 주문자명 -->
		<input type='hidden' name='buyr_mail' value="<?php echo $od['email']; ?>"> <!-- 주문자 이메일 -->
		<input type='hidden' name='buyr_tel1' value="<?php echo $od['telephone']; ?>"> <!-- 주문자 연락처 -->
		<input type='hidden' name='buyr_tel2' value="<?php echo $od['cellphone']; ?>"> <!-- 주문자 핸드폰 -->

	<?php
		/* ============================================================================== */
		/* =   에스크로결제 사용시 필수 정보                                           = */
		/* = -------------------------------------------------------------------------- = */

		// 일반 계좌이체, 가상계좌결제시 환경설정을 무시하고 에스크로 사용안함으로 강제변경
		if($default['cf_escrow_yn'] && !in_array($ss_pay_method, array('ER','ES'))) {
			$default['cf_escrow_yn'] = 0;
		}
	?>
		<input type="hidden" name="rcvr_name" value="<?php echo $od['b_name']; ?>"> <!-- 수취인명 -->
		<input type="hidden" name="rcvr_tel1" value="<?php echo $od['b_telephone']; ?>"> <!-- 수취인 전화번호 -->
		<input type="hidden" name="rcvr_tel2" value="<?php echo $od['b_cellphone']; ?>"> <!-- 수취인 휴대폰번호 -->
		<input type="hidden" name="rcvr_mail" value="<?php echo $od['email']; ?>"> <!-- 수취인 E-mail -->
		<input type="hidden" name="rcvr_zipx" value="<?php echo $od['b_zip']; ?>"> <!-- 수취인 우편번호 -->
		<input type="hidden" name="rcvr_add1" value="<?php echo $od['b_addr1']; ?>"> <!-- 수취인 주소 -->
		<input type="hidden" name="rcvr_add2" value="<?php echo $od['b_addr2'].' '.$od['b_addr3']; ?>"> <!-- 수취인 상세주소 -->


		<!-- 에스크로 사용유무 에스크로 사용 업체(가상계좌, 계좌이체 해당)는 escw_used 를 Y로 세팅 해주시기 바랍니다.-->
		<input type="hidden" name="escw_used" value="Y">

		<!-- 에스크로 결제처리모드 KCP 설정된 금액 결제(사용 : 설정된금액적용: 사용안함: -->
		<input type="hidden" name='pay_mod' value="<?php echo ($default['cf_escrow_yn']?"Y":"N"); ?>">

		<!-- 장바구니 상품 개수 -->
		<input type='hidden' name='bask_cntx' value="<?php echo (int)$goods_count + 1; ?>">
		<!-- 장바구니 정보(상단 스크립트 참조) -->
		<input type="hidden" name="good_info" value="<?php echo $good_info; ?>">
		<!-- 배송소요기간 -->
		<input type="hidden" name='deli_term' value='03'>

		<input type="hidden" name="ActionResult" value="">


		<!-- 공통정보 -->
		<input type="hidden" name="req_tx" value="pay"> <!-- 요청 구분 -->
		<input type="hidden" name="shop_name" value="<?php echo $g_conf_site_name; ?>"> <!-- 사이트 이름 -->
		<input type="hidden" name="site_cd" value="<?php echo $g_conf_site_cd; ?>"> <!-- 사이트 코드 -->
		<input type="hidden" name="currency" value="410"/> <!-- 통화 코드 -->
		<input type="hidden" name="eng_flag" value="N"/> <!-- 한 / 영 -->

		<!-- 결제등록 키 -->
		<input type="hidden" name="approval_key" id="approval">
		<!-- 인증시 필요한 파라미터(변경불가)-->
		<input type="hidden" name="pay_method" value="">
		<input type="hidden" name="van_code" value="<?php echo $van_code; ?>">
		<!-- 신용카드 설정 -->
		<!-- 최대 할부개월수 -->
		<input type="hidden" name="quotaopt" value="<?php echo ($default['cf_kcp_quota'] ? $default['cf_kcp_quota'] : '12'); ?>"/>
		<!-- 가상계좌 설정 -->
		<input type="hidden" name="ipgm_date" value="<?php echo $ipgm_date; ?>">
		<!-- 가맹점에서 관리하는 고객 아이디 설정을 해야 합니다.(필수 설정) -->
		<input type="hidden" name="shop_user_id"     value="<?php echo $member['id']; ?>">
		<!-- 복지포인트 결제시 가맹점에 할당되어진 코드 값을 입력해야합니다.(필수 설정) -->
		<input type="hidden" name="pt_memcorp_cd" value="" >
		<!-- 현금영수증 설정 -->
		<input type="hidden" name="disp_tax_yn" value="<?php echo $default['cf_kcp_tax_yn']; ?>">
		<!-- 리턴 URL (kcp와 통신후 결제를 요청할 수 있는 암호화 데이터를 전송 받을 가맹점의 주문페이지 URL) -->
		<input type="hidden" name="Ret_URL" value="<?php echo $ret_url; ?>">
		<!-- 화면 크기조정 -->
		<input type="hidden" name="tablet_size" value="<?php echo $tablet_size; ?>">

		<input type="hidden" name="kcp_noint_quota" value="<?php echo ($default['cf_kcp_noint_mt']); ?>">

		<input type="hidden" name="kcp_noint" value="<?php echo ($default['cf_kcp_noint_yn']); ?>">

		<?php
		/* ============================================================================== */
		/* =   옵션 정보                                                                = */
		/* = -------------------------------------------------------------------------- = */
		/* =   ※ 옵션 - 결제에 필요한 추가 옵션 정보를 입력 및 설정합니다.             = */
		/* = -------------------------------------------------------------------------- = */
		/* 카드사 리스트 설정
		예) 비씨카드와 신한카드 사용 설정시
		<input type="hidden" name='used_card'    value="CCBC:CCLG">

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
		/* = -------------------------------------------------------------------------- = */
		/* =   옵션 정보 END                                                            = */
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
		<table class="mynavbar">
		<colgroup>
			<col width="50%">
			<col width="50%">
		</colgroup>
		<tbody>
		<tr>
			<td class="selected"><span class="strong">결제정보</span></td>
			<td class="fc_125">주문일 : <?php echo $od['orderdate_s']; ?>&nbsp;(<?php echo get_yoil($od['orderdate_s']); ?>)</td>
		</tr>
		</tbody>
		</table>

		<div class="my_vbox mart10">
			<table>
			<tbody>
			<tr>
				<td class="tal mi_dt">&bull; 주문금액</td>
				<td class="tal mi_bt">
					상품금액
					<?php echo display_price2($tot_sum['it_amt']); ?> + 배송비 <?php echo display_price2($tot_sum['de_amt']); ?>
					<div class="strong"><?php echo display_price2($tot_sum['it_amt']+$tot_sum['de_amt']); ?></div>
				</td>
			</tr>
			<tr>
				<td class="tal mi_dt">&bull; 쿠폰할인</td>
				<td class="tal mi_bt">(-) <?php echo display_price2($tot_sum['dc_amt']); ?></td>
			</tr>
			<tr>
				<td class="tal mi_dt">&bull; 포인트결제</td>
				<td class="tal mi_bt">(-) <?php echo display_point($tot_sum['po_amt']); ?></td>
			</tr>
			<tr>
				<td class="tal mi_dt">&bull; 배송비결제</td>
				<td class="tal mi_bt">(+) <?php echo display_price2($tot_sum['de_amt']); ?></td>
			</tr>
			<tr>
				<td class="tal mi_dt">&bull; 적립혜택</td>
				<td class="tal mi_bt"><?php echo display_point($tot_point); ?></td>
			</tr>
			<tr>
				<td class="tal mi_dt">&bull; 총결제금액</td>
				<td class="tal mi_bt"><span class="strong fc_red"><?php echo display_price2($tot_sum['buy_amt']); ?></span> (<?php echo $arr_mhd[$od['buymethod']]; ?>)</td>
			</tr>
			</tbody>
			</table>
		</div>
	</div>

	<div class="mart20">
		<table class="mynavbar">
		<colgroup>
			<col width="50%">
			<col width="50%">
		</colgroup>
		<tbody>
		<tr>
			<td class="selected"><span class="strong">배송정보</span></td>
			<td class="fc_125">주문일 : <?php echo $od['orderdate_s']; ?>&nbsp;(<?php echo get_yoil($od['orderdate_s']); ?>)</td>
		</tr>
		</tbody>
		</table>

		<div class="my_vbox mart10">
			<table>
			<tbody>
			<tr>
				<td class="tal mi_dt">&bull; 주문자명</td>
				<td class="tal mi_bt"><?php echo $od['name']; ?></td>
			</tr>
			<tr>
				<td class="tal mi_dt">&bull; 주문자 연락처</td>
				<td class="tal mi_bt"><?php echo $od['cellphone']; ?></td>
			</tr>
			<tr>
				<td class="tal mi_dt">&bull; 주문자 이메일</td>
				<td class="tal mi_bt"><?php echo $od['email']; ?></td>
			</tr>
			<tr>
				<td class="tal mi_dt">&bull; 받으시는분</td>
				<td class="tal mi_bt"><?php echo $od['b_name']; ?></td>
			</tr>
			<tr>
				<td class="tal mi_dt">&bull; 연락처 1</td>
				<td class="tal mi_bt"><?php echo $od['b_telephone']; ?></td>
			</tr>
			<tr>
				<td class="tal mi_dt">&bull; 연락처 2</td>
				<td class="tal mi_bt"><?php echo $od['b_cellphone']; ?></td>
			</tr>
			<tr>
				<td class="tal mi_dt">&bull; 배송지주소</td>
				<td class="tal mi_bt"><?php echo print_address($od['b_addr1'], $od['b_addr2'], $od['b_addr3'], $od['b_addr_jibeon']); ?></td>
			</tr>
			<tr>
				<td class="tal mi_dt">&bull; 주문시메모</td>
				<td class="tal mi_bt"><?php echo $od['memo'] ? $od['memo'] : '없음'; ?></td>
			</tr>
			</tbody>
			</table>
		</div>
	</div>
	<div class="tac mart10">
		<button type="button" onClick="kcp_AJAX();" class="btn_medium wfull"><?php echo $arr_mhd[$ss_pay_method]; ?> 결제하기</button>
	</div>
</div>
</form>