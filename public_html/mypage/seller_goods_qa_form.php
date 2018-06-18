<?php
if(!defined('_TUBEWEB_')) exit;

$pg_title = "상품 문의관리";
include_once("./admin_head.sub.php");

$iq = sql_fetch("select * from shop_goods_qa where iq_id = '$iq_id'");
$gs = sql_fetch("select gname,gcode,mb_id from shop_goods where index_no = '$iq[gs_id]'");

if(!$iq['iq_id'])
	alert("자료가 존재하지 않습니다.");
?>

<form name="fregform" method="post" onsubmit="return fregform_submit(this);" autocomplete="off">
<input type="hidden" name="w" value="<?php echo $w; ?>">
<input type="hidden" name="sst" value="<?php echo $sst; ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
<input type="hidden" name="stx" value="<?php echo $stx; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">
<input type="hidden" name="iq_id" value="<?php echo $iq_id; ?>">

<div class="tbl_frm02">
	<table>
	<colgroup>
		<col width="180px">
		<col width="">
	</colgroup>
	<tbody>
	<tr>
		<th>상품명</th>
		<td><a href="<?php echo TW_SHOP_URL;?>/view.php?index_no=<?php echo $iq['gs_id']; ?>" target="_blank"><?php echo $gs['gname']; ?></a></td>
	</tr>
	<tr>
		<th>품목코드</th>
		<td><?php echo $gs['gcode']; ?></td>
	</tr>
	<tr>
		<th>판매자</th>
		<td><?php echo $gs['mb_id']; ?></td>
	</tr>
	<tr>
		<th>옵션</th>
		<td>
			<select name="iq_ty">
				<option <?php echo get_selected($iq['iq_ty'], ''); ?> value=''>문의유형(선택)</option>
				<option <?php echo get_selected($iq['iq_ty'], '상품'); ?> value='상품'>상품</option>
				<option <?php echo get_selected($iq['iq_ty'], '배송'); ?> value='배송'>배송</option>
				<option <?php echo get_selected($iq['iq_ty'], '반품/환불/취소'); ?> value='반품/환불/취소'>반품/환불/취소</option>
				<option <?php echo get_selected($iq['iq_ty'], '교환/변경'); ?> value='교환/변경'>교환/변경</option>
				<option <?php echo get_selected($iq['iq_ty'], '기타'); ?> value='기타'>기타</option>
			</select>
			<input id="iq_secret" type="checkbox" name="iq_secret" value='1' <?php echo get_checked($iq['iq_secret'], '1'); ?>> <label for="iq_secret">비밀글</label>
		</td>
	</tr>
	<tr>
		<th>성명</th>
		<td><input class="frm_input w200" type="text" name="iq_name" value='<?php echo $iq['iq_name']; ?>'></td>
	</tr>
	<tr>
		<th>이메일</th>
		<td><input class="frm_input w200" type="text" name="iq_email" value='<?php echo $iq['iq_email']; ?>'></td>
	</tr>
	<tr>
		<th>핸드폰</th>
		<td><input class="frm_input w200" type="text" name="iq_hp" value='<?php echo $iq['iq_hp']; ?>'></td>
	</tr>
	<tr>
		<th>제목</th>
		<td><input class="frm_input w325" type="text" name="iq_subject" value='<?php echo $iq['iq_subject']; ?>'></td>
	</tr>
	<tr>
		<th>질문</th>
		<td><textarea name="iq_question" class="frm_textbox h100"><?php echo $iq['iq_question']; ?></textarea></td>
	</tr>
	<tr>
		<th>답변</th>
		<td><textarea name="iq_answer" class="frm_textbox h100"><?php echo $iq['iq_answer']; ?></textarea></td>
	</tr>
	</tbody>
	</table>
</div>
<div class="btn_confirm">
	<input type="submit" value="저장" class="btn_large" accesskey="s">
	<a href="page.php?code=seller_goods_qa<?php echo $qstr; ?>&page=<?php echo $page; ?>" class="btn_large bx-white marl3">목록</a>
</div>
</form>

<script>
function fregform_submit(f) {
	f.action = "./seller_goods_qa_form_update.php";
    return true;
}
</script>

<?php
include_once("./admin_tail.sub.php");
?>