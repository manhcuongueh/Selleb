<?php
if(!defined('_TUBEWEB_')) exit;

$pg_title = "기본정보 관리";
include_once("./admin_head.sub.php");
?>

<form name="fregform" method="post" onsubmit="return fregform_submit(this);">
<input type="hidden" name="token" value="">

<h2>기본정보</h2>
<div class="tbl_frm01">
	<table class="tablef">
	<colgroup>
		<col width="180px">
		<col>
		<col width="180px">
		<col>
	</colgroup>
	<tr>
		<th scope="row">쇼핑몰 분양주소</th>
		<td colspan="3"><a href="<?php echo $admin_shop_url; ?>" target="_blank" class="sitecode"><?php echo $admin_shop_url; ?></a></td>
	</tr>
	<tr>
		<th scope="row">회원명 (아이디)</th>
		<td><?php echo $member['name']; ?> (<?php echo $member['id']; ?>)</td>
		<th scope="row">가맹점 신청일</th>
		<td><?php echo date('Y-m-d H:i:s', $partner['wdate']); ?></td>
	</tr>
	<tr>
		<th scope="row">회원레벨</th>
		<td><?php echo get_grade($member['grade']); ?></td>
		<th scope="row">승인여부</th>
		<td><?php echo ($partner['state'])?"승인완료":"승인대기"; ?></td>
	</tr>
	</tbody>
	</table>
</div>

<h2>스킨 설정</h2>
<div class="tbl_frm01">
	<table class="tablef">
	<colgroup>
		<col width="180px">
		<col>
		<col width="180px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row"><label for="theme">PC 쇼핑몰스킨</label></th>
		<td>
			<?php echo get_theme_select('theme', $member['theme']); ?>
		</td>
		<th scope="row"><label for="mobile_theme">모바일 쇼핑몰스킨</label></th>
		<td>
			<?php echo get_mobile_theme_select('mobile_theme', $member['mobile_theme']); ?>
		</td>
	</tr>
	</tbody>
	</table>
</div>

<h2>입금받으실 계좌정보</h2>
<div class="local_cmd01">
	<p>※ 아래 계좌정보는 수수료 정산시 이용 됩니다. 정확히 입력해주세요.</p>
</div>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col width="180px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row"><label for="bank_company">은행명</label></th>
		<td>
			<?php echo get_bank_select("bank_company","required itemname='은행명'");?>
			<script>document.fregform.bank_company.value = '<?php echo $partner[bank_company]; ?>';</script>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="bank_number">계좌번호</label></th>
		<td><input type="text" name="bank_number" value="<?php echo $partner['bank_number']; ?>" id="bank_number" class="frm_input" size="30"></td>
	</tr>
	<tr>
		<th scope="row"><label for="bank_name">예금주명</label></th>
		<td><input type="text" name="bank_name" value="<?php echo $partner['bank_name']; ?>" id="bank_name" class="frm_input" size="30"></td>
	</tr>
	</tbody>
	</table>
</div>

<h2>사업자정보</h2>
<div class="local_cmd01">
	<p>※ 아래 사업자정보는 쇼핑몰 하단에 노출되며 노출안함으로 설정시 본사 사업자정보가 노출 됩니다.</p>
</div>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col width="180px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">쇼핑몰 사업자노출 여부</th>
		<td>
			<?php echo radio_checked('cf_saupja_use', $partner['cf_saupja_use'], '1', '노출함'); ?>
			<?php echo radio_checked('cf_saupja_use', $partner['cf_saupja_use'], '0', '노출안함'); ?>			
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="shop_name">쇼핑몰명</label></th>
		<td>
			<input type="text" name="shop_name" value="<?php echo $partner['shop_name']; ?>" id="shop_name" class="frm_input" size="30">
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="shop_name_us">쇼핑몰 영문명</label></th>
		<td>
			<input type="text" name="shop_name_us" value="<?php echo $partner['shop_name_us']; ?>" id="shop_name_us" class="frm_input" size="30">
		</td>
	</tr>
	<tr>
		<th scope="row">사업자유형</th>
		<td>
			<?php echo radio_checked('company_type', $partner['company_type'], '0', '일반과세자'); ?>
			<?php echo radio_checked('company_type', $partner['company_type'], '1', '간이과세자'); ?>
			<?php echo radio_checked('company_type', $partner['company_type'], '2', '면세사업자'); ?>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="company_name">회사명</label></th>
		<td>
			<input type="text" name="company_name" value="<?php echo $partner['company_name']; ?>" id="company_name" class="frm_input" size="30">
			<em>세무서에 등록되어 있는 회사명 입력</em>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="company_owner">대표자명</label></th>
		<td>
			<input type="text" name="company_owner" value="<?php echo $partner['company_owner']; ?>" id="company_owner" class="frm_input" size="30">
			<em>예) 홍길동</em>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="company_saupja_no">사업자등록번호</label></th>
		<td>
			<input type="text" name="company_saupja_no" value="<?php echo $partner['company_saupja_no']; ?>" id="company_saupja_no" class="frm_input" size="30">
			<em>예) 000-00-00000</em>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="company_item">업태</label></th>
		<td>
			<input type="text" name="company_item" value="<?php echo $partner['company_item']; ?>" id="company_item" class="frm_input" size="30">
			<em>예) 소매업</em>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="company_service">종목</label></th>
		<td>
			<input type="text" name="company_service" value="<?php echo $partner['company_service']; ?>" id="company_service" class="frm_input" size="30">
			<em>예) 전자상거래업</em>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="company_zip">사업장우편번호</label></th>
		<td>
			<input type="text" name="company_zip" maxlength="5" value="<?php echo $partner['company_zip']; ?>" id="company_zip" class="frm_input" size="5">
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="company_addr">사업장주소</label></th>
		<td>
			<input type="text" name="company_addr" value="<?php echo $partner['company_addr']; ?>" id="company_addr" class="frm_input" size="60">
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="tongsin_no">통신판매업신고번호</label></th>
		<td>
			<input type="text" name="tongsin_no" value="<?php echo $partner['tongsin_no']; ?>" id="tongsin_no" class="frm_input" size="30">
			<em>예) <?php echo $time_year.'-서울강남-0000호'; ?></em>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="company_tel">대표전화번호</label></th>
		<td>
			<input type="text" name="company_tel" value="<?php echo $partner['company_tel']; ?>" id="company_tel" class="frm_input" size="30">
			<em>예) 1544-0000, 070-0000-0000</em>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="company_fax">팩스번호</label></th>
		<td>
			<input type="text" name="company_fax" value="<?php echo $partner['company_fax']; ?>" id="company_fax" class="frm_input" size="30">
			<em>예) 02-0000-0000</em>
		</td>
	</tr>	
	<tr>
		<th scope="row"><label for="info_name">정보책임자 이름</label></th>
		<td>
			<input type="text" name="info_name" value="<?php echo $partner['info_name']; ?>" id="info_name" class="frm_input" size="30">
			<em>예) 홍길동</em>
		</td>
	</tr>		
	<tr>
		<th scope="row"><label for="info_email">정보책임자 e-mail</label></th>
		<td>
			<input type="text" name="info_email" value="<?php echo $partner['info_email']; ?>" id="info_email" class="email frm_input" size="30">
			<em>예) help@domain.com</em>
		</td>
	</tr>
	</tbody>
	</table>
</div>

<h2>CS 운영시간</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col width="180px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row"><label for="company_hours">상담가능시간</label></th>
		<td>
			<input type="text" name="company_hours" value="<?php echo $partner['company_hours']; ?>" id="company_hours" class="frm_input" size="60">
			<em>예) 오전9시~오후6시</em>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="company_lunch">점심시간</label></th>
		<td>
			<input type="text" name="company_lunch" value="<?php echo $partner['company_lunch']; ?>" id="company_lunch" class="frm_input" size="60">
			<em>예) 오후12시~오후1시</em>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="company_close">휴무일</label></th>
		<td>
			<input type="text" name="company_close" value="<?php echo $partner['company_close']; ?>" id="company_close" class="frm_input" size="60">
			<em>예) 토요일,공휴일 휴무</em>
		</td>
	</tr>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<input type="submit" value="저장" class="btn_large" accesskey="s">
</div>
</form>

<script>
function fregform_submit(f) {
	f.action = "./partner_info_update.php";
    return true;
}
</script>

<?php
include_once("./admin_tail.sub.php");
?>