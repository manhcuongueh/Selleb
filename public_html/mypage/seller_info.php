<?php
if(!defined('_TUBEWEB_')) exit;

$pg_title = "업체 정보관리";
include_once("./admin_head.sub.php");
?>

<form name="fregform" method="post" onsubmit="return fregform_submit(this);">
<input type="hidden" name="token" value="">

<h2>사업자 정보</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col width="180px">
		<col>
	</colgroup>
	<tr>
		<th scope="row">업체코드</th>
		<td><?php echo $seller[sup_code]; ?></td>
	</tr>
	<tr>
		<th scope="row">제공상품</th>
		<td><input type="text" name="in_item" value="<?php echo $seller[in_item]; ?>" required itemname='제공상품' class="frm_input" size="30"></td>
	</tr>
	<tr>
		<th scope="row">업체(법인)명</th>
		<td><input type="text" name="in_compay" value="<?php echo $seller[in_compay]; ?>" required itemname='업체(법인)명' class="frm_input" size="30"></td>
	</tr>
	<tr>
		<th scope="row">사업자등록번호</th>
		<td><input type="text" name="in_sanumber" value="<?php echo $seller[in_sanumber]; ?>" required trim itemname='사업자등록번호' class="frm_input" size="30"></td>
	</tr>
	<tr>
		<th scope="row">전화번호</th>
		<td><input type="text" name="in_phone" value="<?php echo $seller[in_phone]; ?>" class="frm_input"></td>
	</tr>
	<tr>
		<th scope="row">팩스번호</th>
		<td><input type="text" name="in_fax" value="<?php echo $seller[in_fax]; ?>" class="frm_input" size="30"></td>
	</tr>
	<tr>
		<th scope="row">업태</th>
		<td><input type="text" name="in_upte" value="<?php echo $seller[in_upte]; ?>" required itemname='업태' class="frm_input" size="30"></td>
	</tr>
	<tr>
		<th scope="row">종목</th>
		<td><input type="text" name="in_up" value="<?php echo $seller[in_up]; ?>" required itemname='종목' class="frm_input" size="30"></td>
	</tr>
	<tr>
		<th scope="row">대표자명</th>
		<td><input type="text" name="in_name" value="<?php echo $seller[in_name]; ?>" required itemname='대표자명' class="frm_input" size="30"></td>
	</tr>
	<tr>
		<th scope="row">홈페이지</th>
		<td><input type="text" name="in_home" value="<?php echo $seller[in_home]; ?>" class="frm_input" size="30">
		<?php echo help('http://를 포함하여 입력하세요'); ?></td>
	</tr>
	<tr>
		<th scope="row">사업장주소</th>
		<td>
			<p>
				<input class="frm_input w100" type="text" name="in_zipcode" value="<?php echo $seller[in_zipcode]; ?>" numeric itemname="우편번호" maxlength="5">
				<a href="javascript:win_zip('fregform', 'in_zipcode', 'in_addr1', 'in_addr2', 'in_addr3', 'in_addr_jibeon');" class="btn_small grey">우편번호</a>
			</p>
			<p class="mart5"><input class="frm_input" type="text" name="in_addr1" value="<?php echo $seller[in_addr1]; ?>" itemname="주소" size="60"></p>
			<p class="mart5"><input class="frm_input" type="text" name="in_addr2" value="<?php echo $seller[in_addr2]; ?>" itemname="상세주소" size="60"> ※ 상세주소</p>
			<p class="mart5"><input class="frm_input" type="text" name="in_addr3" value="<?php echo $seller[in_addr3]; ?>" itemname="참고항목" size="60"> ※ 참고항목
			<input type="hidden" name="in_addr_jibeon" value="<?php echo $seller[in_addr_jibeon]; ?>"></p>
		</td>
	</tr>
	</tbody>
	</table>
</div>

<h2>정산계좌 정보</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col width="180px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">은행명</th>
		<td>
			<?php echo get_bank_select("n_bank"); ?>
			<script>document.fregform.n_bank.value = '<?php echo $seller[n_bank]; ?>';</script>
		</td>
	</tr>
	<tr>
		<th scope="row">계좌번호</th>
		<td><input type="text" name="n_bank_num" value="<?php echo $seller[n_bank_num]; ?>" class="frm_input" size="30"></td>
	</tr>
	<tr>
		<th scope="row">예금주명</th>
		<td><input type="text" name="n_name" value="<?php echo $seller[n_name]; ?>" class="frm_input"></td>
	</tr>
	</tbody>
	</table>
</div>

<h2>담당자 정보</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col width="180px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">담당자명</th>
		<td><input type="text" name="in_dam" value="<?php echo $seller[in_dam]; ?>" required itemname='담당자명' size="30" class="frm_input"></td>
	</tr>
	<tr>
		<th scope="row">담당자 핸드폰</th>
		<td><input type="text" name="n_phone" value="<?php echo $seller[n_phone]; ?>" required itemname='담당자 핸드폰' size="30" class="frm_input"></td>
	</tr>
	<tr>
		<th scope="row">담당자 이메일</th>
		<td><input type="text" name="n_email" value="<?php echo $seller[n_email]; ?>" required email itemname='담당자 이메일' size="30" class="frm_input"></td>
	</tr>
	</table>
	</td>
</tr>
</table>
</div>

<div class="btn_confirm">
	<input type="submit" value="저장" class="btn_large" accesskey="s">
</div>
</form>

<script>
function fregform_submit(f) {
	f.action = "./seller_info_update.php";
	return true;
}
</script>

<?php
include_once("./admin_tail.sub.php");
?>