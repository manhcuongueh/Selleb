<?php
if(!defined('_TUBEWEB_')) exit;
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
				<option value='1'<?php echo get_selected($config['delivery_type'], "1"); ?>>택배발송</option>
				<option value='2'<?php echo get_selected($config['delivery_type'], "2"); ?>>퀵서비스</option>
				<option value='3'<?php echo get_selected($config['delivery_type'], "3"); ?>>직접배달</option>
				<option value='4'<?php echo get_selected($config['delivery_type'], "4"); ?>>방문수령</option>
			</select>
		</td>
	</tr>
	<tr>
		<th>배송업체</th>
		<td>
			<div id="sit_supply_frm" class="tbl_frm02">
				<table>
				<colgroup>
					<col width="140px">
					<col>
					<col width="70px">
				</colgroup>
				<thead>
				<tr>
					<th class="tac">배송업체명</th>
					<th class="tac">배송추적 링크주소</th>
					<th class="tac">삭제</th>
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
					<td><input type="text" name="spl_name[]" class="frm_input wfull" value="<?php echo $seq[0]; ?>"></td>
					<td><input type="text" name="spl_url[]" class="frm_input wfull" value="<?php echo $seq[1]; ?>"></td>
					<td class="tac">
						<?php if($i == 0) { ?>
						<button type="button" id="add_supply_row" class="btn_small">추가</button>
						<?php } ?>
						<?php if($i > 0) { ?>
						<button type="button" id="del_supply_row" class="btn_small red">삭제</button>
						<?php } ?>				
					</td>
				</tr>
				<?php
					$i++;
				} while($i < $spl_count);
				?>
				</tbody>
				</table>
			</div>

			<script>
			$(function() {
				// 입력필드추가
				$("#add_supply_row").click(function() {
					var $el = $("#sit_supply_frm tbody tr:last");
					var fld = "<tr>\n";
					fld += "<td><input type=\"text\" name=\"spl_name[]\" value=\"\" class=\"frm_input wfull\"></td>\n";
					fld += "<td><input type=\"text\" name=\"spl_url[]\" value=\"\" class=\"frm_input wfull\"></td>\n";
					fld += "<td class=\"tac\"><button type=\"button\" id=\"del_supply_row\" class=\"btn_small red\">삭제</button></td>\n";
					fld += "</tr>";

					$el.after(fld);
				});

				// 입력필드삭제
				$("#del_supply_row").live("click", function() {
					$(this).closest("tr").remove();
				});
			});
			</script>
		</td>
	</tr>
	<tr>
		<th>기본 배송정책<br><font color="red">(4가지 타입중 선택)</font></th>
		<td>
			<div class="tbl_frm02">
			<table>
			<colgroup>
				<col width="140px">
				<col>
			</colgroup>
			<tr>
				<th><input id='ids_dm1' type='radio' name='delivery_method' value='101' <?php echo get_checked($config['delivery_method'], "101"); ?>> <label for='ids_dm1'>무료배송</label></th>
				<td>배송비가 부과되지 않습니다</td>
			</tr>
			<tr>
				<th><input id='ids_dm2' type='radio' name='delivery_method' value='102' <?php echo get_checked($config['delivery_method'], "102"); ?>> <label for='ids_dm2'>착불배송</label></th>
				<td>주문시 또는 장바구니에 배송비가 <b>[착불]</b> 이라는 글이 출력되며 배송비는 부과되지 않습니다</td>
			</tr>
			<tr>
				<th><input id='ids_dm3' type='radio' name='delivery_method' value='103' <?php echo get_checked($config['delivery_method'], "103"); ?>> <label for='ids_dm3'>유료배송</label></th>
				<td><input type='text' name='delivery_103mon' value="<?php echo number_format($config['delivery_103mon']); ?>" class="frm_input w60" onkeyup="addComma(this)"> 원을 주문금액 또는 수량에 상관없이 동일 주문건에 배송비를 한번만 부과됩니다
			</tr>
			<tr>
				<th><input id='ids_dm4' type='radio' name='delivery_method' value='104' <?php echo get_checked($config['delivery_method'], "104"); ?>> <label for='ids_dm4'>조건부 무료배송</label></th>
				<td>
					<input type='text' name='delivery_104mon' value="<?php echo number_format($config['delivery_104mon']); ?>" class="frm_input w60" onkeyup="addComma(this)"> 원의 배송비를 부과하며 단! 주문금액이
					<input type='text' name='delivery_104mon_up' value="<?php echo number_format($config['delivery_104mon_up']); ?>" class="frm_input w60" onkeyup="addComma(this)"> 원 이상이면 무료배송 처리됩니다
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
	<?php echo editor_html('sp_send_cost', get_text($config['sp_send_cost'], 0));?>
</div>

<h2>모바일 배송/교환/반품안내</h2>
<div>
	<?php echo editor_html('mo_send_cost', get_text($config['mo_send_cost'], 0));?>
</div>

<div class="btn_confirm">
	<input type="submit" value="저장" class="btn_large" accesskey="s">
</div>
</form>

<div class="information">
	<h4>도움말</h4>
	<div class="content">
		<div class="hd">ㆍ꼭! 알아두기</div>
		<div class="desc01 accent">
			<p>ㆍ정책 안내1) 본사, 가맹점, 공급업체는 배송정책을 각각 설정할 수 있고, 주문시 판매자의 배송정책에 따라 배송비가 개별 부과됩니다.</p>
			<p>ㆍ정책 안내2) <b>배송업체</b> 등록은 본사에서만 등록 가능합니다. 가맹점과 공급업체에서 등록요청시 추가등록 해주셔야 합니다.</p>
			<p>ㆍ정책 안내3) 주문이 발생되면 판매자의 상품에 대해서만 분기하여 개별적으로 직배송 처리 됩니다.</p>
		</div>
	 </div>
</div>

<script>
function fregform_submit(f) {
	<?php echo get_editor_js('sp_send_cost');?>
	<?php echo get_editor_js('mo_send_cost');?>
	f.action = "./config/ship_update.php";
    return true;
}
</script>