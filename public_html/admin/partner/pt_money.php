<?php
if(!defined('_TUBEWEB_')) exit;

$row1 = sql_fetch("select * from shop_partner_config where etc4='item1'");
$row2 = sql_fetch("select * from shop_partner_config where etc4='item2'");
$row3 = sql_fetch("select * from shop_partner_config where etc4='item3'");
$row4 = sql_fetch("select * from shop_partner_config where etc4='item4'");
$row5 = sql_fetch("select * from shop_partner_config where etc4='item5'");

$row_etc = sql_fetch("select * from shop_partner_config where etc4='item_etc'");
$item_row1 = sql_fetch("select * from shop_partner_config where etc4='item_tree1'");
$item_row2 = sql_fetch("select * from shop_partner_config where etc4='item_tree2'");
$item_row3 = sql_fetch("select * from shop_partner_config where etc4='item_tree3'");
$item_row4 = sql_fetch("select * from shop_partner_config where etc4='item_tree4'");
$item_row5 = sql_fetch("select * from shop_partner_config where etc4='item_tree5'");
$shop_row  = sql_fetch("select * from shop_partner_config where etc4='shop'");
?>

<div style="width:954px">
	<h2>레벨 설정</h2>
	<form name="fregform" method="post" onsubmit="return fregform_submit(this)">
	<input type="hidden" name="mode" value="w">
	<div class="tbl_head01">
		<table>
		<colgroup>
			<col width="8%">
			<col width="12%">
			<col width="20%">
			<col width="20%">
			<col width="20%">
			<col width="20%">
		</colgroup>
		<thead>
		<tr>
			<th scope="col">레벨사용<p class="fc_197 mart5">(사용시체크)</p></th>
			<th scope="col">가맹점 레벨<p class="fc_197 mart5">(하위 레벨부터 입력)</p></th>
			<th scope="col">가맹점 개설비<p class="fc_197 mart5">(VAT포함가)</p></th>
			<th scope="col">월관리비<p class="fc_197 mart5">(당월사용료)</th>
			<th scope="col">분양수수료 추가적립<p class="fc_197 mart5">(레벨별 추가적립 커미션)</p></th>
			<th scope="col">판매수수료 추가적립<p class="fc_197 mart5">(레벨별 추가적립 커미션)</p></th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td><input type="checkbox" name="item_state1" value="y" <?php echo get_checked($row1['state'], "y"); ?>> Lv.6</td>
			<td><input type="text" name="a1" value="<?php echo $row1['etc1']; ?>" class="frm_input"></td>
			<td><input type="text" name="a11" value="<?php echo number_format($row1['etc2']); ?>" class="frm_input w100" onkeyup="addComma(this)"> 원</td>
			<td><input type="text" name="a111" value="<?php echo number_format($row1['etc3']); ?>" class="frm_input w100" onkeyup="addComma(this)"> 원</td>
			<td>
				<input type="text" name="item1_ch" value="<?php echo number_format($row1['ch']); ?>" class="frm_input w80" onkeyup="addComma(this)">
				<select name="item1_ch_ty">
					<?php echo option_selected('%', $row1['ch_ty'], '%'); ?>
					<?php echo option_selected('$', $row1['ch_ty'], '원'); ?>
				</select>
			</td>
			<td>
				<input type="text" name="item1_shop" value="<?php echo number_format($row1['shop']); ?>" class="frm_input w80" onkeyup="addComma(this)">
				<select name="item1_shop_ty">
					<?php echo option_selected('%', $row1['shop_ty'], '%'); ?>
					<?php echo option_selected('$', $row1['shop_ty'], '원'); ?>
				</select>
			</td>
		</tr>
		<tr class="list1">
			<td><input type="checkbox" name="item_state2" value="y" <?php echo get_checked($row2['state'], "y"); ?>> Lv.5</td>
			<td><input type="text" name="a2" value="<?php echo $row2['etc1']; ?>" class="frm_input"></td>
			<td><input type="text" name="a22" value="<?php echo number_format($row2['etc2']); ?>" class="frm_input w100" onkeyup="addComma(this)"> 원</td>
			<td><input type="text" name="a222" value="<?php echo number_format($row2['etc3']); ?>" class="frm_input w100" onkeyup="addComma(this)"> 원</td>
			<td>
				<input type="text" name="item2_ch" value="<?php echo number_format($row2['ch']); ?>" class="frm_input w80" onkeyup="addComma(this)">
				<select name="item2_ch_ty">
					<?php echo option_selected('%', $row2['ch_ty'], '%'); ?>
					<?php echo option_selected('$', $row2['ch_ty'], '원'); ?>
				</select>
			</td>
			<td>
				<input type="text" name="item2_shop" value="<?php echo number_format($row2['shop']); ?>" class="frm_input w80" onkeyup="addComma(this)">
				<select name="item2_shop_ty">
					<?php echo option_selected('%', $row2['shop_ty'], '%'); ?>
					<?php echo option_selected('$', $row2['shop_ty'], '원'); ?>
				</select>
			</td>
		</tr>
		<tr>
			<td><input type="checkbox" name="item_state3" value="y" <?php echo get_checked($row3['state'], "y"); ?>>&nbsp;&nbsp;Lv.4</td>
			<td><input type="text" name="a3" value="<?php echo $row3['etc1']; ?>" class="frm_input"></td>
			<td><input type="text" name="a33" value="<?php echo number_format($row3['etc2']); ?>" class="frm_input w100" onkeyup="addComma(this)"> 원</td>
			<td><input type="text" name="a333" value="<?php echo number_format($row3['etc3']); ?>" class="frm_input w100" onkeyup="addComma(this)"> 원</td>
			<td>
				<input type="text" name="item3_ch" value="<?php echo number_format($row3['ch']); ?>" class="frm_input w80" onkeyup="addComma(this)">
				<select name="item3_ch_ty">
					<?php echo option_selected('%', $row3['ch_ty'], '%'); ?>
					<?php echo option_selected('$', $row3['ch_ty'], '원'); ?>
				</select>
			</td>
			<td>
				<input type="text" name="item3_shop" value="<?php echo number_format($row3['shop']); ?>" class="frm_input w80" onkeyup="addComma(this)">
				<select name="item3_shop_ty">
					<?php echo option_selected('%', $row3['shop_ty'], '%'); ?>
					<?php echo option_selected('$', $row3['shop_ty'], '원'); ?>
				</select>
			</td>
		</tr>
		<tr class="list1">
			<td><input type="checkbox" name="item_state4" value="y" <?php echo get_checked($row4['state'], "y"); ?>>&nbsp;&nbsp;Lv.3</td>
			<td><input type="text" name="a4" value="<?php echo $row4['etc1']; ?>" class="frm_input"></td>
			<td><input type="text" name="a44" value="<?php echo number_format($row4['etc2']); ?>" class="frm_input w100" onkeyup="addComma(this)"> 원</td>
			<td><input type="text" name="a444" value="<?php echo number_format($row4['etc3']); ?>" class="frm_input w100" onkeyup="addComma(this)"> 원</td>
			<td>
				<input type="text" name="item4_ch" value="<?php echo number_format($row4['ch']); ?>" class="frm_input w80" onkeyup="addComma(this)">
				<select name="item4_ch_ty">
					<?php echo option_selected('%', $row4['ch_ty'], '%'); ?>
					<?php echo option_selected('$', $row4['ch_ty'], '원'); ?>
				</select>
			</td>
			<td>
				<input type="text" name="item4_shop" value="<?php echo number_format($row4['shop']); ?>" class="frm_input w80" onkeyup="addComma(this)">
				<select name="item4_shop_ty">
					<?php echo option_selected('%', $row4['shop_ty'], '%'); ?>
					<?php echo option_selected('$', $row4['shop_ty'], '원'); ?>
				</select>
			</td>
		</tr>
		<tr>
			<td><input type="checkbox" name="item_state5" value="y"<?php echo get_checked($row5['state'], "y"); ?>>&nbsp;&nbsp;Lv.2</td>
			<td><input type="text" name="a5" value="<?php echo $row5['etc1']; ?>" class="frm_input"></td>
			<td><input type="text" name="a55" value="<?php echo number_format($row5['etc2']); ?>" class="frm_input w100" onkeyup="addComma(this)"> 원</td>
			<td><input type="text" name="a555" value="<?php echo number_format($row5['etc3']); ?>" class="frm_input w100" onkeyup="addComma(this)"> 원</td>
			<td>
				<input type="text" name="item5_ch" value="<?php echo number_format($row5['ch']); ?>" class="frm_input w80" onkeyup="addComma(this)">
				<select name="item5_ch_ty">
					<?php echo option_selected('%', $row5['ch_ty'], '%'); ?>
					<?php echo option_selected('$', $row5['ch_ty'], '원'); ?>
				</select>
			</td>
			<td>
				<input type="text" name="item5_shop" value="<?php echo number_format($row5['shop']); ?>" class="frm_input w80" onkeyup="addComma(this)">
				<select name="item5_shop_ty">
					<?php echo option_selected('%', $row5['shop_ty'], '%'); ?>
					<?php echo option_selected('$', $row5['shop_ty'], '원'); ?>
				</select>
			</td>
		</tr>
		</tbody>
		</table>
	</div>
	<div class="btn_confirm">
		<input type="submit" value="저장" class="btn_large" accesskey="s">
	</div>
	</form>

	<form name="fregform2" method="post" onsubmit="return fregform_submit(this);">
	<input type="hidden" name="mode" value="w2">
	<h2>광고수수료 설정</h2>
	<div class="tbl_frm01">
		<table>
		<colgroup>
			<col width="140px">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th>광고수수료</th>
			<td>
				<input type="text" name="p_login" value="<?php echo $row_etc['etc2']; ?>" class="frm_input w50"> 원 <span class="fc_197 vam marl5">자신의 도메인 링크를 통해 접속 될때마다 지급되는 수수료 (단! IP당 하루에 한번만 지급 , 단계 적용없음)</span>
			</td>
		</tr>
		</tbody>
		</table>
	</div>

	<h2>분양수수료 및 공통 판매수수료 설정</h2>
	<div class="tbl_frm01">
		<table>
		<colgroup>
			<col width="140px">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th>분양수수료 적립</th>
			<td>
				<input type="text" name="p_tree" value="<?php echo $row_etc['p_tree']; ?>" class="frm_input w50"> 단계
				<select name="state" class="marl5">
					<?php echo option_selected('$', $row_etc['state'], '(원) - 금액으로 수수료적립'); ?>
					<?php echo option_selected('%', $row_etc['state'], '(%) - 퍼센트로 수수료적립'); ?>
				</select>
			</td>
		</tr>
		<tr>
			<th>판매수수료 적립</th>
			<td>
				<input type="text" name="s_tree" value="<?php echo $shop_row['p_tree']; ?>" class="frm_input w50"> 단계
				<select name="s_state" class="marl5">
					<?php echo option_selected('$', $shop_row['state'], '(원) - 금액으로 수수료적립'); ?>
					<?php echo option_selected('%', $shop_row['state'], '(%) - 퍼센트로 수수료적립'); ?>
				</select>
			</td>
		</tr>
		</tbody>
		</table>
	</div>

	<table class="wfull">
	<colgroup>
		<col width="79%">
		<col width="1%">
		<col width="20%">
	</colgroup>
	<tr>
		<td class="vat">		
			<h2>분양수수료 설정</h2>
			<div class="tbl_head01">
				<table>
				<colgroup>
					<col width="10%">
					<col width="18%">
					<col width="18%">
					<col width="18%">
					<col width="18%">
					<col width="18%">
				</colgroup>
				<thead>
				<tr>
					<th>적립단계</th>
					<th><?php echo $row1['etc1']; ?></th>
					<th><?php echo $row2['etc1']; ?></th>
					<th><?php echo $row3['etc1']; ?></th>
					<th><?php echo $row4['etc1']; ?></th>
					<th><?php echo $row5['etc1']; ?></th>
				</tr>
				</thead>
				<tbody>
				<?php
				for($i=0; $i<$row_etc['p_tree']; $i++) {
					$k = $i+1;

					$item_row1_amt = 0;
					$item_row2_amt = 0;
					$item_row3_amt = 0;
					$item_row4_amt = 0;
					$item_row5_amt = 0;

					$item_row1_etc1 = explode("|",$item_row1['etc1']);
					if($item_row1_etc1[$i])
						$item_row1_amt = $item_row1_etc1[$i];

					$item_row2_etc1 = explode("|",$item_row2['etc1']);
					if($item_row2_etc1[$i])
						$item_row2_amt = $item_row2_etc1[$i];

					$item_row3_etc1 = explode("|",$item_row3['etc1']);
					if($item_row3_etc1[$i])
						$item_row3_amt = $item_row3_etc1[$i];

					$item_row4_etc1 = explode("|",$item_row4['etc1']);
					if($item_row4_etc1[$i])
						$item_row4_amt = $item_row4_etc1[$i];

					$item_row5_etc1 = explode("|",$item_row5['etc1']);
					if($item_row5_etc1[$i])
						$item_row5_amt = $item_row5_etc1[$i];
				?>
				<tr>
					<td class="list1"><?php echo $k; ?>단계</td>
					<td><input type="text" name="item1[]" value="<?php echo $item_row1_amt; ?>" class="frm_input w80" <?php echo ($row1['state']!='y')?"readonly disabled":""; ?>></td>
					<td><input type="text" name="item2[]" value="<?php echo $item_row2_amt; ?>" class="frm_input w80" <?php echo ($row2['state']!='y')?"readonly disabled":""; ?>></td>
					<td><input type="text" name="item3[]" value="<?php echo $item_row3_amt; ?>" class="frm_input w80" <?php echo ($row3['state']!='y')?"readonly disabled":""; ?>></td>
					<td><input type="text" name="item4[]" value="<?php echo $item_row4_amt; ?>" class="frm_input w80" <?php echo ($row4['state']!='y')?"readonly disabled":""; ?>></td>
					<td><input type="text" name="item5[]" value="<?php echo $item_row5_amt; ?>" class="frm_input w80" <?php echo ($row5['state']!='y')?"readonly disabled":""; ?>></td>
				</tr>
				<?php } ?>
				</tbody>
				</table>
			</div>
		</td>
		<td></td>
		<td class="vat">
			<h2>공통 판매수수료 설정</h2>
			<div class="tbl_head01">
				<table>
				<colgroup>
					<col width="10%">
					<col width="90%">
				</colgroup>
				<thead>
				<tr>
					<th>적립단계</th>
					<th>수수료</th>
				</tr>
				</thead>
				<tbody>
				<?php
				for($i=0; $i<$shop_row['p_tree']; $i++) {
					$k = $i+1;
					$shop_row_amt = 0;

					$shop_row_etc1 = explode("|",$shop_row[etc1]);
					if($shop_row_etc1[$i])
						$shop_row_amt = $shop_row_etc1[$i];
				?>
				<tr>
					<td class="list1"><?php echo $k; ?>단계</td>
					<td><input type="text" value="<?php echo $shop_row_amt; ?>" name="shop[]" class="frm_input w80"></td>
				</tr>
				<?php } ?>
				</tbody>
				</table>
			</div>
		</td>
	</tr>
	</table>

	<div class="btn_confirm">
		<input type="submit" value="저장" class="btn_large" accesskey="s">
	</div>
	</form>
</div>

<script>
function fregform_submit(f) {
	f.action = "./partner/pt_money_update.php";
    return true;
}
</script>