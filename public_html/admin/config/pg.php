<?php
if(!defined('_TUBEWEB_')) exit;
?>

<form name="fregform" method="post" onsubmit="return fregform_submit(this)" enctype="MULTIPART/FORM-DATA">
<input type="hidden" name="token" value="">

<h2>전자결제 (PG) 설정</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col width="180px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th>결제수단</th>
		<td class="td_label">
			<input id='cf_bank_yn' type="checkbox" name="cf_bank_yn" value="1" <?php echo get_checked($default['cf_bank_yn'], "1"); ?>>
			<label for="cf_bank_yn">무통장결제</label>
			<input id='cf_card_yn' type="checkbox" name="cf_card_yn" value="1" <?php echo get_checked($default['cf_card_yn'], "1"); ?>>
			<label for="cf_card_yn">신용카드</label>
			<input id='cf_iche_yn' type="checkbox" name="cf_iche_yn" value="1" <?php echo get_checked($default['cf_iche_yn'], "1"); ?>>
			<label for="cf_iche_yn">실시간계좌이체</label>
			<input id='cf_vbank_yn' type="checkbox" name="cf_vbank_yn" value="1" <?php echo get_checked($default['cf_vbank_yn'], "1"); ?>>
			<label for="cf_vbank_yn">가상계좌</label>
			<input id='cf_hp_yn' type="checkbox" name="cf_hp_yn" value="1" <?php echo get_checked($default['cf_hp_yn'], "1"); ?>>
			<label for="cf_hp_yn">휴대폰결제</label>
		</td>
	</tr>
	<tr>
		<th>결제시스템 방식</th>
		<td class="td_label">
			<input id="cf_card_test_yn2" type="radio" name="cf_card_test_yn" value="0" <?php echo get_checked($default['cf_card_test_yn'], "0"); ?>> <label for="cf_card_test_yn2">실결제</label>
			<input id="cf_card_test_yn1" type="radio" name="cf_card_test_yn" value="1" <?php echo get_checked($default['cf_card_test_yn'], "1"); ?>> <label for="cf_card_test_yn1">테스트결제</label>
		</td>
	</tr>
	<tr>
		<th>복합과세 결제</th>
		<td class="td_label">
			<input id="cf_tax_flag_use_1" type="radio" name="cf_tax_flag_use" value="1" <?php echo get_checked($default['cf_tax_flag_use'], "1"); ?>> <label for="cf_tax_flag_use_1">사용함</label>
			<input id="cf_tax_flag_use_2" type="radio" name="cf_tax_flag_use" value="0" <?php echo get_checked($default['cf_tax_flag_use'], "0"); ?>> <label for="cf_tax_flag_use_2">사용안함</label>
		</td>
	</tr>
	<tr>
		<th>결제대행사</th>
		<td>
			<select name="cf_card_pg" onchange="chk_pay_method(this.value);" class="vam">
				<option value="ini"<?php echo get_selected($default['cf_card_pg'], "ini"); ?>>KG이니시스</option>
				<option value="kcp"<?php echo get_selected($default['cf_card_pg'], "kcp"); ?>>KCP</option>
				<option value="all"<?php echo get_selected($default['cf_card_pg'], "all"); ?>>올더게이트</option>
			</select>
			<a href="https://www.inicis.com/pg-hosting?cd=hostinglanding&product=inostation&settlement=N" class="btn_small grey" target="_blank" id="ini_link">KG이니시스 신청하기</a>
			<a href="https://admin8.kcp.co.kr/hp.HomePageAction.do?cmd=apply&host_id=투비웹" class="btn_small grey" target="_blank" id="kcp_link">KCP 신청하기</a>
			<a href="https://www.allthegate.com/ags/app/app_03.jsp" class="btn_small grey" target="_blank" id="all_link">올더게이트 신청하기</a>
		</td>
	</tr>
	<tr>
		<th>상점명</th>
		<td><input type="text" name="cf_nm_pg" value="<?php echo $default['cf_nm_pg']; ?>" class="frm_input w325">
		<span class="fc_197 marl10">예 : 행복을주는쇼핑몰</span></td>
	</tr>
	</tbody>
	</table>
</div>

<!-- KCP (AX-HUB V6 ESCROW [PHP]) { -->
<div id="ids_kcp">
<h2>KCP (AX-HUB V6 ESCROW [PHP])</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col width="180px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th>KCP PG ID</th>
		<td><input type="text" name="cf_kcp_id" value="<?php echo $default['cf_kcp_id']; ?>"
		class="frm_input w200"> <span class="fc_197 marl5">발급받으신 KCP PG ID를 입력하세요</span></td>
	</tr>
	<tr>
		<th>KCP KEY</th>
		<td><input type="text" name="cf_kcp_key" value="<?php echo $default['cf_kcp_key']; ?>" class="frm_input w325"> <span class="fc_197 marl5">발급받으신 KCP KEY를 입력하세요</span></td>
	</tr>
	<tr>
		<th>현금영수증 발급</th>
		<td class="td_label">
			<input id="cf_kcp_tax_use1" type="radio" name="cf_kcp_tax_yn" value="Y" <?php echo get_checked($default['cf_kcp_tax_yn'], "Y"); ?>>
			<label for="cf_kcp_tax_use1">사용함</label>
			<input id="cf_kcp_tax_use2" type="radio" name="cf_kcp_tax_yn" value="N" <?php echo get_checked($default['cf_kcp_tax_yn'], "N"); ?>>
			<label for="cf_kcp_tax_use2">사용안함</label>
			<p class="fc_197 mart5">주의1 : 현금영수증 사용 시 KCP 상점관리자 페이지에서 현금영수증 사용 동의를 하셔야 합니다.</p>
			<p class="fc_197">주의2 : 현금영수증 발급 취소는 PG사에서 지원하는 현금영수증 취소 기능을 사용하시기 바랍니다.</p>
		</td>
	</tr>
	<tr>
		<th>최대 할부 개월</th>
		<td>
			<input type="text" name="cf_kcp_quota" value="<?php echo $default['cf_kcp_quota']; ?>" class="frm_input w325">
			<p class="fc_197 mart5">할부옵션 : Payplus Plug-in에서 카드결제시 최대로 표시할 할부개월 수를 설정합니다. (0 ~ 18 까지 설정 가능)</p>
			<p class="fc_197">주의  - 할부 선택은 결제금액이 50,000원 이상일 경우에만 가능, 50000원 미만의 금액은 일시불로만 표기됩니다</p>
			<p class="fc_197">예) 값을 "5" 로 설정했을 경우 => 카드결제시 결제창에 일시불부터 5개월까지 선택가능</p>
		</td>
	</tr>
	<tr>
		<th>무이자 사용 여부</th>
		<td class="td_label">
			<input id="cf_kcp_noint_yn1" type="radio" name="cf_kcp_noint_yn" value="N" <?php echo get_checked($default['cf_kcp_noint_yn'], "N"); ?>>
			<label for="cf_kcp_noint_yn1">일반할부</label>
			<input id="cf_kcp_noint_yn2" type="radio" name="cf_kcp_noint_yn" value="Y" <?php echo get_checked($default['cf_kcp_noint_yn'], "Y"); ?>>
			<label for="cf_kcp_noint_yn2">일반 설정할부 (아래 할부기간 입력)</label>
			<input id="cf_kcp_noint_yn3" type="radio" name="cf_kcp_noint_yn" value="" <?php echo get_checked($default['cf_kcp_noint_yn'], ""); ?>>
			<label for="cf_kcp_noint_yn3">관리자 설정할부 (KCP 상점관리자에서 설정)</label>
			<p class="fc_197 mart5">주의 : 무이자결제는 반드시 PG사와 계약체결 후에 사용해야 합니다</p>
			<p class="fc_197">참고 : 일반할부 - KCP 이벤트 이외에 설정 된 모든 무이자 설정을 무시합니다.</p>
		</td>
	</tr>
	<tr>
		<th>무이자 할부 기간</th>
		<td>
			<input type="text" name="cf_kcp_noint_mt" value="<?php echo $default['cf_kcp_noint_mt']; ?>" class="frm_input w325">
			<span class="fc_197">(아래 카드 코드표를 참조하세요!)</span>
			<p class="fc_197 mart5">1) 전 카드사 2,3,4개월 무이자 설정 시 : 예) <b>ALL-02:03:04</b></p>
			<p class="fc_197">2) BC 2,3,6개월, 국민 3,6개월, 삼성 6,9개월 무이자 설정 시 : 예) <b>CCBC-02:03:06,CCKM-03:06,CCSS-03:06:04</b></p>
		</td>
	</tr>
	<tr>
		<th>카드 코드표</th>
		<td class="tbl_frm02">
			<table>
			<colgroup>
				<col width='17%'>
				<col width='17%'>
				<col width='17%'>
				<col width='17%'>
				<col width='17%'>
				<col width='17%'>
			</colgroup>
			<thead>
			<tr>
				<th class="tac">코드</th>
				<th class="tac">기관명</th>
				<th class="tac">코드</th>
				<th class="tac">기관명</th>
				<th class="tac">코드</th>
				<th class="tac">기관명</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td class="tac">CCKM</td>
				<td class="tac">KB국민</td>
				<td class="tac">CCBC</td>
				<td class="tac">BC</td>
				<td class="tac">CCKE</td>
				<td class="tac">외환</td>
			</tr>
			<tr>
				<td class="tac">CCAM</td>
				<td class="tac">롯데</td>
				<td class="tac">CCNH</td>
				<td class="tac">농협NH</td>
				<td class="tac">CCSS</td>
				<td class="tac">삼성</td>
			</tr>
			<tr>
				<td class="tac">CCLG</td>
				<td class="tac">신한</td>
				<td class="tac">CCDI</td>
				<td class="tac">현대</td>
				<td class="tac" colspan='2'></td>
			</tr>
			</tbody>
			</table>
		</td>
	</tr>
	<tr>
		<th>가상계좌 입금통보 URL</th>
		<td>
			<input type='text' value="<?php echo set_http($config['admin_shop_url']); ?>/shop/kcp/kcp_common.php"
			readonly class="frm_input wfull list2">
			<p class="fc_197 mart5"><b>KCP 관리자 > 상점정보관리 > 정보변경 > 공통URL 정보 > 공통URL 변경후</b>자동으로 입금 통보됩니다.</p>
		</td>
	</tr>
	<tr>
		<th>PG 결제관리</th>
		<td><a href="http://admin.kcp.co.kr" target="_blank" class="btn_small grey">승인내역조회 / 승인취소 / 상점관리</a></td>
	</tr>
	</tbody>
	</table>
</div>
</div>
<!-- } KCP (AX-HUB V6 ESCROW [PHP]) -->

<!-- KG이니시스 (INIpay V5.0) { -->
<div id="ids_ini">
<h2>KG이니시스 (INIpay V5.0)</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col width="180px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th>PG ID</th>
		<td><input type="text" name="cf_inicis_id" value="<?php echo $default['cf_inicis_id']; ?>" class="frm_input w200"> <span class="fc_197 marl5">발급받으신 PG ID를 입력하세요</span></td>
	</tr>
	<tr>
		<th>일반 할부기간</th>
		<td>
			<input type="text" name="cf_inicis_quota" value="<?php echo $default['cf_inicis_quota']; ?>" class="frm_input wfull">
			<p class="fc_197 mart5">1) 일시불만 가능하도록 사용할 경우 : 예) <b>lumpsum:00</b></p>
			<p class="fc_197">2) 일시불 ~ 6개월까지 사용할 경우 : 예) <b>lumpsum:00:02:03:04:05:06</b></p>
		</td>
	</tr>
	<tr>
		<th>현금영수증 발급</th>
		<td class="td_label">
			<input id="cf_inicis_tax_use1" type="radio" name="cf_inicis_tax_yn" value="receipt" <?php echo get_checked($default['cf_inicis_tax_yn'], "receipt"); ?>>
			<label for="cf_inicis_tax_use1">사용함</label>
			<input id="cf_inicis_tax_use2" type="radio" name="cf_inicis_tax_yn" value="no_receipt" <?php echo get_checked($default['cf_inicis_tax_yn'], "no_receipt"); ?>>
			<label for="cf_inicis_tax_use2">사용안함</label>
			<span class="fc_197 marl10">이니시스와 현금영수증 발급 계약이 반드시 되어 있어야 사용가능 합니다</span>
		</td>
	</tr>
	<tr>
		<th>무이자 사용 여부</th>
		<td class="td_label">
			<input id="cf_inicis_noint_yn1" type="radio" name="cf_inicis_noint_yn" value="no" <?php echo get_checked($default['cf_inicis_noint_yn'], "no"); ?>>
			<label for="cf_inicis_noint_yn1">일반결제</label>
			<input id="cf_inicis_noint_yn2" type="radio" name="cf_inicis_noint_yn" value="yes" <?php echo get_checked($default['cf_inicis_noint_yn'], "yes"); ?>>
			<label for="cf_inicis_noint_yn2">무이자결제</label>
			<span class="fc_197 marl10">무이자할부 판매를 시행하려면 이니시스와의 별도 계약 후 사용하셔야 합니다.</span>
		</td>
	</tr>
	<tr>
		<th>무이자 할부 기간</th>
		<td>
			<input type="text" name="cf_inicis_noint_mt" value="<?php echo $default['cf_inicis_noint_mt'];?>" class="frm_input w325">
			<span class="fc_197">(아래 카드 코드표를 참조하세요!)</span>
			<p class="fc_197 mart5">1) 모든 카드에 대해서 3,6개월 무이자로 처리 : 예) <b>ALL-3:6</b></p>
			<p class="fc_197">2) BC카드 3개월, 6개월 할부 및 삼성카드 3개월 할부시 : 예) <b>11-3:6,12-3</b></p>
		</td>
	</tr>
	<tr>
		<th>카드 코드표</th>
		<td class="tbl_frm02">
			<table>
			<colgroup>
				<col width='17%'>
				<col width='17%'>
				<col width='17%'>
				<col width='17%'>
				<col width='17%'>
				<col width='17%'>
			</colgroup>
			<thead>
			<tr>
				<th class="tac">코드</th>
				<th class="tac">기관명</th>
				<th class="tac">코드</th>
				<th class="tac">기관명</th>
				<th class="tac">코드</th>
				<th class="tac">기관명</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td class="tac">01</td>
				<td class="tac">외환</td>
				<td class="tac">03</td>
				<td class="tac">롯데</td>
				<td class="tac">04</td>
				<td class="tac">현대</td>
			</tr>
			<tr>
				<td class="tac">06</td>
				<td class="tac">KB</td>
				<td class="tac">11</td>
				<td class="tac">BC</td>
				<td class="tac">12</td>
				<td class="tac">삼성</td>
			</tr>
			<tr>
				<td class="tac">13</td>
				<td class="tac">LG</td>
				<td class="tac">14</td>
				<td class="tac">신한</td>
				<td class="tac">15</td>
				<td class="tac">한미</td>
			</tr>
			<tr>
				<td class="tac">16</td>
				<td class="tac">NH</td>
				<td class="tac">17</td>
				<td class="tac">하나 SK 카드</td>
				<td class="tac" colspan='2'></td>
			</tr>
			</tbody>
			</table>
		</td>
	</tr>
	<tr>
		<th>휴대폰 결제 설정</th>
		<td>
			<select name="cf_inicis_hp_unit">
				<option value="1" <?php echo get_selected($default['cf_inicis_hp_unit'], "1"); ?>>1 - 컨텐츠</option>
				<option value="2" <?php echo get_selected($default['cf_inicis_hp_unit'], "2"); ?>>2 - 실물(상품)</option>
			</select>
			<span class="fc_197 marl10">상품 배송 방식으로 판매하는 경우 '실물'로 설정합니다</span>
		</td>
	</tr>
	<tr>
		<th>결제창 스킨</th>
		<td class="td_label">
			<input id="cf_inicis_skin1" type="radio" name="cf_inicis_skin" value="ORIGINAL" <?php echo get_checked($default['cf_inicis_skin'], "ORIGINAL"); ?>>
			<label for="cf_inicis_skin1">기본</label>
			<input id="cf_inicis_skin2" type="radio" name="cf_inicis_skin" value="GREEN" <?php echo get_checked($default['cf_inicis_skin'], "GREEN"); ?>>
			<label for="cf_inicis_skin2">녹색</label>
			<input id="cf_inicis_skin3" type="radio" name="cf_inicis_skin" value="PURPLE" <?php echo get_checked($default['cf_inicis_skin'], "PURPLE"); ?>>
			<label for="cf_inicis_skin3">보라색</label>
			<input id="cf_inicis_skin4" type="radio" name="cf_inicis_skin" value="RED" <?php echo get_checked($default['cf_inicis_skin'], "RED"); ?>>
			<label for="cf_inicis_skin4">빨강</label>
			<input id="cf_inicis_skin5" type="radio" name="cf_inicis_skin" value="YELLOW" <?php echo get_checked($default['cf_inicis_skin'], "YELLOW"); ?>>
			<label for="cf_inicis_skin5">노랑</label>
		</td>
	</tr>
	<tr>
		<th>가상계좌 입금통보 URL</th>
		<td>
			<input type='text' value="<?php echo set_http($config['admin_shop_url']); ?>/shop/INIpay50/vacct/vacctinput.php" readonly class="frm_input wfull list2">
			<p class="fc_197 mart5"><b>KG이니시스 관리자 > 상점정보 > 결제수단정보</b>에서  채번방식을 (건별 체번) 으로 반드시 변경하세요!</p>
		</td>
	</tr>
	<tr>
		<th>PG 결제관리</th>
		<td><a href="https://iniweb.inicis.com/home/intro.jsp" target="_blank" class="btn_small grey">승인내역조회 / 승인취소 / 상점관리</a></td>
	</tr>
	<tr>
		<th>이니시스 인증마크</th>
		<td><a href="http://www.inicis.com/blog/archives/824" target="_blank" class="btn_small grey">[KG 이니시스 인증센터] 에서 제공 받은곳 바로가기</a></td>
	</tr>
	</tbody>
	</table>
</div>
</div>
<!-- } KG이니시스 (INIpay V5.0) -->

<!-- 올더게이트 (AGSPay V4.0 for PHP) { -->
<div id="ids_all">
<h2>올더게이트 (AGSPay V4.0 for PHP)</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col width="180px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th>올더게이트 PG ID</th>
		<td><input type="text" name="cf_ags_id" value="<?php echo $default['cf_ags_id']; ?>" class="frm_input w200">
		<span class="fc_197 marl5">발급받으신 올더게이트 PG ID를 입력하세요</td>
	</tr>
	<tr>
		<th>일반 할부기간</th>
		<td>
			<input type="text" name="cf_ags_quota" value="<?php echo $default['cf_ags_quota']; ?>" class="frm_input w325">
			<span class="fc_197 marl5">(2 ~ 12개월까지, 50,000원 이상 주문시)</span>
			<div class="fc_197 mart5">1) 할부기간을 일시불만 가능하도록 사용할 경우 : 0</div>
			<div class="fc_197">2) 할부기간을 일시불 ~ 12개월까지 사용할 경우 : 0:2:3:4:5:6:7:8:9:10:11:12</div>
		</td>
	</tr>
	<tr>
		<th>무이자 사용 여부</th>
		<td class="td_label">
			<input id="cf_ags_noint_yn1" type="radio" name="cf_ags_noint_yn" value="0" <?php echo get_checked($default['cf_ags_noint_yn'], "0"); ?>>
			<label for="cf_ags_noint_yn1">일반결제</label>
			<input id="cf_ags_noint_yn2" type="radio" name="cf_ags_noint_yn" value="1" <?php echo get_checked($default['cf_ags_noint_yn'], "1"); ?>>
			<label for="cf_ags_noint_yn2">무이자결제</label>
			<span class="fc_197 marl5">무이자결제는 반드시 PG사와 계약체결 후에 사용해야 합니다, (50,000원 이상 주문시)</span>
		</td>
	</tr>
	<tr>
		<th>무이자 할부 기간</th>
		<td>
			<input type="text" name="cf_ags_noint_mt" value="<?php echo $default['cf_ags_noint_mt']; ?>" class="frm_input w325">
			<span class="fc_197 marl5">(아래 카드 코드표를 참조하세요!)</span>
			<p class="fc_197 mart5">1) 모든 할부거래를 무이자로 하고 싶을경우 : 예) <b>ALL</b></p>
			<p class="fc_197">2) 국민카드 특정개월수만 무이자를 하고 싶을경우 샘플 (2:3:4:5:6개월) : 예) <b>200-2:3:4:5:6</b></p>
			<p class="fc_197">3) 외환카드 특정개월수만 무이자를 하고 싶을경우 샘플 (2:3:4:5:6개월) : 예) <b>300-2:3:4:5:6</b></p>
			<p class="fc_197">4) 국민,외환카드 특정개월수만 무이자를 하고 싶을경우 샘플 (2:3:4:5:6개월) : 예) <b>200-2:3:4:5:6,300-2:3:4:5:6</b></p>
			<p class="fc_197">5) 무이자 할부기간 설정을 하지 않을 경우에는 NONE로 설정 : 예) <b>NONE</b></p>
			<p class="fc_197">6) 전카드사 특정개월수만 무이자를 하고 싶은경우 (2:3:6개월) : 예) <b>ALL-02:03:06</b></p>
		</td>
	</tr>
	<tr>
		<th>카드 코드표</th>
		<td class="tbl_frm02">
			<table>
			<colgroup>
				<col width='17%'>
				<col width='17%'>
				<col width='17%'>
				<col width='17%'>
				<col width='17%'>
				<col width='17%'>
			</colgroup>
			<thead>
			<tr>
				<th class="tac">코드</th>
				<th class="tac">기관명</th>
				<th class="tac">코드</th>
				<th class="tac">기관명</th>
				<th class="tac">코드</th>
				<th class="tac">기관명</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td class="tac">100</td>
				<td class="tac">BC</td>
				<td class="tac">200</td>
				<td class="tac">국민</td>
				<td class="tac">201</td>
				<td class="tac">NH</td>
			</tr>
			<tr>
				<td class="tac">300</td>
				<td class="tac">외환</td>
				<td class="tac">310</td>
				<td class="tac">하나SK</td>
				<td class="tac">400</td>
				<td class="tac">삼성</td>
			</tr>
			<tr>
				<td class="tac">500</td>
				<td class="tac">신한</td>
				<td class="tac">800</td>
				<td class="tac">현대</td>
				<td class="tac">900</td>
				<td class="tac">롯데</td>
			</tr>
			</tbody>
			</table>
		</td>
	</tr>
	<tr>
		<th>올더게이트 휴대폰</th>
		<td>
			<p>올더게이트 PG사의 휴대폰 결제를 이용하기 위해서 필요한 정보입니다.</p>
			<p class="mart5"><b class="fc_255">주의사항</b><br>
			1) 테스트결제모드 일때는 올더게이트는 신용카드 결제만 테스트하실수 있습니다.<br>
			2) 신용카드 외 결제수단을 테스트하기 위해서는 올더게이트에서 발급 받은 실제 상점 아이디로 하셔야만 합니다.<br>
			3) 실상점 아이디 등록시 실제 결제가 일어나므로, 테스트 이후 상점관리자 모드에서 취소를 하셔야 합니다.</p>
		</td>
	</tr>
	<tr>
		<th>CP아이디 (휴대폰)</th>
		<td><input type="text" name="cf_ags_hp_id" value="<?php echo $default['cf_ags_hp_id']; ?>" class="frm_input w200">
		<span class="fc_197 marl5">발급받으신 CP아이디를 입력하세요</span></td>
	</tr>
	<tr>
		<th>CP비밀번호 (휴대폰)</th>
		<td><input type="text" name="cf_ags_hp_pwd" value="<?php echo $default['cf_ags_hp_pwd']; ?>" class="frm_input w200">
		<span class="fc_197 marl5">발급받으신 CP비밀번호를 입력하세요</span></td>
	</tr>
	<tr>
		<th>SUB-CP아이디 (휴대폰)</th>
		<td><input type="text" name="cf_ags_hp_subid" value="<?php echo $default['cf_ags_hp_subid']; ?>" class="frm_input w200">
		<span class="fc_197 marl5">발급받으신 SUB-CP아이디를 입력하세요</span></td>
	</tr>
	<tr>
		<th>상품코드 (휴대폰)</th>
		<td><input type="text" name="cf_ags_hp_code" value="<?php echo $default['cf_ags_hp_code']; ?>" class="frm_input w200">
		<span class="fc_197 marl5">발급받으신 상품코드를 입력하세요</span></td>
	</tr>
	<tr>
		<th>상품구분</th>
		<td>
			<select name="cf_ags_hp_unit">
				<option value="1" <?php echo get_selected($default['cf_ags_hp_unit'], "1"); ?>>1 - 컨텐츠</option>
				<option value="2" <?php echo get_selected($default['cf_ags_hp_unit'], "2"); ?>>2 - 실물(상품)</option>
			</select>
			<span class="fc_197 marl5">상품 배송 방식으로 판매하는 경우 '실물'로 설정합니다</span>
		</td>
	</tr>
	<tr>
		<th>가상계좌 입금통보 URL</th>
		<td>
			<input type='text' value="<?php echo set_http($config['admin_shop_url']); ?>/shop/allthegate/AGS_VirAcctResult.php" readonly class="frm_input wfull list2">
			<p class="fc_197 mart5"><b>KCP 관리자 > 상점정보관리 > 정보변경 > 공통URL 정보 > 공통URL 변경후</b>자동으로 입금 통보됩니다.</p>
		</td>
	</tr>
	<tr>
		<th>상점 관리</th>
		<td><a href="https://admin7.allthegate.com/chaMng/login/login.jsp" target="_blank" class="btn_small grey">승인내역조회 / 승인취소 / 상점관리</a></td>
	</tr>
	</tbody>
	</table>
</div>
</div>
<!-- } 올더게이트 (AGSPay V4.0 for PHP) -->

<h2>에스크로 설정 <span class="fs11 normal">(현금성 결제시 의무적으로 에스크로결제를 허용해야 합니다)</span></h2>
<div class="local_cmd01 lh4">
	<p>
		구매안전서비스(에스크로 또는 전자보증)는 전자상거래소비자보호법 및 시행령 개정에 따라 2011년 7월 29일부터 5만원 이상 현금성 결제시 의무 시행됩니다.<br>
		에스크로 사용범위 및 사용금액에 대한것은 신청한 PG사나 은행에 따라 다를 수 있으므로 협의를 하셔야 합니다.<br>
		소비자는 2008. 7. 1일부터 현금영수증 발급대상금액이 5천원이상에서 1원이상으로 변경되어 5천원 미만의 현금거래도 현금영수증을 요청하여 발급 받을 수 있습니다.
	</p>
</div>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col width="180px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th>사용여부</th>
		<td class="td_label">
			<input id="cf_inicis_escrow_yn1" type="radio" name="cf_escrow_yn" value="1" <?php echo get_checked($default['cf_escrow_yn'], "1"); ?>>
			<label for="cf_inicis_escrow_yn1">사용함</label>
			<input id="cf_inicis_escrow_yn2" type="radio" name="cf_escrow_yn" value="0" <?php echo get_checked($default['cf_escrow_yn'], "0"); ?>>
			<label for="cf_inicis_escrow_yn2">사용안함</label>
		</td>
	</tr>
	<tr id='ids_ini_escrow'>
		<th>Escrow ID</th>
		<td><input type="text" name="cf_inicis_escrow_id" value="<?php echo $default['cf_inicis_escrow_id']; ?>"
		maxlength="50" class="frm_input w325"> <span class="fc_197 marl5">발급받으신 Escrow ID를 입력하세요</span></td>
	</tr>
	<tr>
		<th>Escrow 결제 수단</th>
		<td>계좌이체 또는 가상계좌 결제수단 사용 시 자동으로 적용됩니다.</td>
	</tr>
	</tbody>
	</table>
</div>

<h2>입금계좌 정보</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col width="180px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th>무통장입금계좌</th>
		<td>
			<textarea name="cf_bank_account" class="frm_textbox wfull" rows="5"><?php echo $default['cf_bank_account']; ?></textarea>
			<p class="fc_197 mart7">무통장입금계좌번호를 줄바꿈으로 구분 / 예 : 은행명 (공백) 계좌번호 (공백) 예금주 (엔터)</p>
		</td>
	</tr>
	<tr>
		<th>인터넷뱅킹주소</th>
		<td>
			<textarea name="cf_banking"class="frm_textbox wfull" rows="5"><?php echo $default['cf_banking']; ?></textarea>
			<p class="fc_197 mart7">인터넷뱅킹 주소를 줄바꿈으로 구분 / 예 : 은행명 (공백) 링크주소 (엔터)</p>
		</td>
	</tr>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<input type="submit" value="저장" class="btn_large" accesskey="s">
</div>
</form>

<div class="information">
	<h4>도움말</h4>
	<div class="content">
		<div class="desc02">
			<p>ㆍ필독1) PG사와 계약을 맺은 이후에는 메일로 받으신 실제 PG ID를 넣으시면 됩니다.</p>
			<p>ㆍ필독2) PG사의 결제정보 설정후 고객님께서 카드결제 테스트를 꼭 해보시기 바랍니다.</p>
			<p>ㆍ필독3) 간혹 PG사를 통해 카드승인된 값을 받지못하여 주문관리페이지에서 입금확인으로 자동변경되지 않을수 있습니다.</p>
			<p>ㆍ필독4) 반드시 주문관리페이지의 주문상태와 PG사에서 제공하는 관리자화면내의 카드승인내역도 동시에 확인해 주십시요.</p>
		</div>
	 </div>
</div>

<script language="javascript">
function fregform_submit(f) {
	f.action = "./config/pg_update.php";
    return true;
}

function chk_pay_method(obj) {
	var f = document.fregform;
	switch(obj){
		case 'kcp':
			eval("ids_kcp").style.display ='';
			eval("ids_ini").style.display ='none';
			eval("ids_ini_escrow").style.display ='none';
			eval("ids_all").style.display ='none';
			eval("ini_link").style.display ='none';
			eval("kcp_link").style.display ='';
			eval("all_link").style.display ='none';			
			break;
		case 'ini':
			eval("ids_kcp").style.display ='none';
			eval("ids_ini").style.display ='';
			eval("ids_ini_escrow").style.display ='';
			eval("ids_all").style.display ='none';
			eval("ini_link").style.display ='';
			eval("kcp_link").style.display ='none';
			eval("all_link").style.display ='none';
			break;
		case 'all':
			eval("ids_kcp").style.display ='none';
			eval("ids_ini").style.display ='none';
			eval("ids_ini_escrow").style.display ='none';
			eval("ids_all").style.display ='';
			eval("ini_link").style.display ='none';
			eval("kcp_link").style.display ='none';
			eval("all_link").style.display ='';
			break;
	}
}

chk_pay_method('<?php echo $default[cf_card_pg]; ?>');
</script>
