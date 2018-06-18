<?php
if(!defined('_TUBEWEB_')) exit;

$pg_title = "업체 배송정책";
include_once("./admin_head.sub.php");
?>

<form name="fregform" method="post" onsubmit="return fregform_submit(this);">
<input type="hidden" name="token" value="">

<h2>배송정책 설정</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col width="180px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th>배송방법</th>
		<td>
			<select name="delivery_type">
				<option value='1'<?php echo get_selected($seller['delivery_type'], "1"); ?>>택배발송</option>
				<option value='2'<?php echo get_selected($seller['delivery_type'], "2"); ?>>퀵서비스</option>
				<option value='3'<?php echo get_selected($seller['delivery_type'], "3"); ?>>직접배달</option>
				<option value='4'<?php echo get_selected($seller['delivery_type'], "4"); ?>>방문수령</option>
			</select>
		</td>
	</tr>
	<tr>
		<th>배송업체</th>
		<td>
			<div id="sit_supply_frm" class="tbl_frm02">
				<table>
				<colgroup>
					<col width="180px">
					<col>
				</colgroup>
				<thead>
				<tr>
					<th class="tac">배송업체명</th>
					<th class="tac">배송추적 링크주소</th>
				</tr>
				</thead>
				<tbody>
				<?php
				$spl_sorts = explode(",", $config['delivery_sorts']);
				$spl_count = count($spl_sorts);

				$i = 0;
				do {
					$seq = explode('|', trim($spl_sorts[$i]));
				?>
				<tr>
					<td><input type="text" class="frm_input wfull" value="<?php echo $seq[0]; ?>"></td>
					<td><input type="text" class="frm_input wfull" value="<?php echo $seq[1]; ?>"></td>
				</tr>
				<?php
					$i++;
				} while($i < $spl_count);
				?>
				</tbody>
				</table>
				<?php echo help('※ 배송업체등록은 본사에서만 가능하며 추가하실 업체는 본사로 문의주시기 바랍니다.'); ?>
			</div>
		</td>
	</tr>
	<tr>
		<th>기본 배송정책<br><font color="red">(4가지 타입중 선택)</font></th>
		<td>
			<div class="tbl_frm02">
			<table>
			<colgroup>
				<col width="180px">
				<col>
			</colgroup>
			<tr>
				<th><input id='ids_dm1' type='radio' name='delivery_method' value='101' <?php echo get_checked($seller['delivery_method'], "101"); ?>> <label for='ids_dm1'>무료배송</label></th>
				<td>배송비가 부과되지 않습니다</td>
			</tr>
			<tr>
				<th><input id='ids_dm2' type='radio' name='delivery_method' value='102' <?php echo get_checked($seller['delivery_method'], "102"); ?>> <label for='ids_dm2'>착불배송</label></th>
				<td>주문시 또는 장바구니에 배송비가 <b>[착불]</b> 이라는 글이 출력되며 배송비는 부과되지 않습니다</td>
			</tr>
			<tr>
				<th><input id='ids_dm3' type='radio' name='delivery_method' value='103' <?php echo get_checked($seller['delivery_method'], "103"); ?>> <label for='ids_dm3'>유료배송</label></th>
				<td><input type='text' name='delivery_103mon' value="<?php echo number_format($seller['delivery_103mon']); ?>" class="frm_input w60" onkeyup="addComma(this)"> 원을 주문금액 또는 수량에 상관없이 동일 주문건에 배송비를 한번만 부과됩니다
			</tr>
			<tr>
				<th><input id='ids_dm4' type='radio' name='delivery_method' value='104' <?php echo get_checked($seller['delivery_method'], "104"); ?>> <label for='ids_dm4'>조건부 무료배송</label></th>
				<td>
					<input type='text' name='delivery_104mon' value="<?php echo number_format($seller['delivery_104mon']); ?>" class="frm_input w60" onkeyup="addComma(this)"> 원의 배송비를 부과하며 단! 주문금액이
					<input type='text' name='delivery_104mon_up' value="<?php echo number_format($seller['delivery_104mon_up']); ?>" class="frm_input w60" onkeyup="addComma(this)"> 원 이상이면 무료배송 처리됩니다
				</td>
			</tr>
			</table>
		</td>
	</tr>
	</tbody>
	</table>
</div>

<h2>쇼핑몰 배송/교환/반품안내</h2>
<div>
	<?php echo editor_html('sp_send_cost', get_text($seller['sp_send_cost'], 0));?>
</div>

<h2>모바일 배송/교환/반품안내</h2>
<div>
	<?php echo editor_html('mo_send_cost', get_text($seller['mo_send_cost'], 0));?>
</div>

<div class="btn_confirm">
	<input type="submit" value="저장" class="btn_large" accesskey="s">
</div>
</form>

<script>
function fregform_submit(f) {
	<?php echo get_editor_js('sp_send_cost');?>
	<?php echo get_editor_js('mo_send_cost');?>

	f.action = "./seller_conf_update.php";
    return true;
}
</script>

<?php
include_once("./admin_tail.sub.php");
?>