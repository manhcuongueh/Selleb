<?php
if(!defined('_TUBEWEB_')) exit;
?>

<form name="fregform" method="post" onsubmit="return fregform_submit(this);">
<input type="hidden" name="token" value="">

<h2>사업자 정보</h2>
<div class="tbl_frm01">	
	<table>
	<colgroup>
		<col width="180px">
		<col width="">
	</colgroup>
	<tbody>
	<tr>
		<th>회원선택</th>
		<td>
			<select name="mb_id" required>
				<option value="">선택하세요</option>
				<?php
				$sql = "select * from shop_member where grade > '1' and supply = '' order by name ";
				$rst = sql_query($sql);
				while($row=sql_fetch_array($rst)){
					$s_item = sql_fetch(" select * from shop_seller where mb_id = TRIM('$row[id]') ");
					if(!$s_item[index_no]) {
						echo "<option value='$row[id]'>[Lv.{$row[grade]}] $row[name] ($row[id])</option>\n";
					}
				}
				?>
			<select>
			<a href='./seller/item_supply.php' onclick="openwindow(this,'pop_supply','550','500','1'); return false" class="btn_small grey">선택</a>
		</td>
	</tr>
	<tr>
		<th>제공상품</th>
		<td><input type="text" name="in_item" required class="frm_input w200"> <span class="marl10 fc_197">예 : 가전제품</span></td>
	</tr>
	<tr>
		<th>업체(법인)명</th>
		<td><input type="text" name="in_compay" required class="frm_input w200"></td>
	</tr>
	<tr>
		<th>사업자등록번호</th>
		<td><input type="text" name="in_sanumber" required class="frm_input w200"> <span class="marl10 fc_197">예 : 206-23-12552</span></td>
	</tr>
	<tr>
		<th>전화번호</th>
		<td><input type="text" name="in_phone" class="frm_input w200"> <span class="marl10 fc_197">예 : 02-1234-5678</span></td>
	</tr>
	<tr>
		<th>팩스번호</th>
		<td><input type="text" name="in_fax" class="frm_input w200"> <span class="marl10 fc_197">예 : 02-1234-5678</span></td>
	</tr>
	<tr>
		<th>사업장주소</th>
		<td>
			<p><input class="frm_input" type="text" name="in_zipcode" value="" size="7" maxlength="5"> <a href="javascript:win_zip('fregform', 'in_zipcode', 'in_addr1', 'in_addr2', 'in_addr3', 'in_addr_jibeon');" class="btn_small grey">우편번호</a></p>
			<p class="mart3"><input class="frm_input" type="text" name="in_addr1" size="60"></p>
			<p class="mart3"><input class="frm_input" type="text" name="in_addr2" size="60"> ※ 상세주소</p>
			<p class="mart3"><input class="frm_input" type="text" name="in_addr3" size="60"> ※ 참고항목
			<input type="hidden" name="in_addr_jibeon" value=""></p>
		</td>
	</tr>
	<tr>
		<th>업태</th>
		<td><input type="text" name="in_upte" required class="frm_input w200"> <span class="marl10 fc_197">예 : 서비스업</span></td>
	</tr>
	<tr>
		<th>종목</th>
		<td><input type="text" name="in_up" required class="frm_input w200"> <span class="marl10 fc_197">예 : 전자상거래업</span></td>
	</tr>
	<tr>
		<th>대표자명</th>
		<td><input type="text" name="in_name" required class="frm_input w200"></td>
	</tr>
	<tr>
		<th>홈페이지</th>
		<td><input type="text" name="in_home" class="frm_input w200"> <span class="marl10 fc_197">http://를 포함하여 입력하세요</span></td>
	</tr>
	</tbody>
	</table>
</div>

<h2>정산계좌 정보</h2>
<div class="tbl_frm01">
	<table class="marb10">
	<colgroup>
		<col width="180px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th>은행명</th>
		<td><?php echo get_bank_select("n_bank");?></td>
	</tr>
	<tr>
		<th>계좌번호</th>
		<td><input type="text" name="n_bank_num" class="frm_input w200"></td>
	</tr>
	<tr>
		<th>예금주명</th>
		<td><input type="text" name="n_name" class="frm_input w200"> <span class="marl10 fc_197">예 : 홍길동</span></td>
	</tr>
	</tbody>
	</table>
</div>

<h2>담당자 정보</h2>
<div class="tbl_frm01">
	<table class="marb10">
	<colgroup>
		<col width="180px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th>담당자명</th>
		<td><input type="text" name="in_dam" required class="frm_input w200"></td>
	</tr>
	<tr>
		<th>담당자 핸드폰</th>
		<td><input type="text" name="n_phone" required class="frm_input w200"></td>
	</tr>
	<tr>
		<th>담당자 이메일</th>
		<td><input type="text" name="n_email" required class="frm_input w200"></td>
	</tr>
	</tbody>
	</table>
</div>
<div class="btn_confirm">
	<input type="submit" class="btn_large" accesskey="s" value="저장">
</div>
</form>

<script>
function fregform_submit(f) {
	if(!confirm("등록 하시겠습니까?"))
		return;

	f.action = "./seller/item_rigister_update.php";
    return true;
}
</script>