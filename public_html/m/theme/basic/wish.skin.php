<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<form name="fwishlist" method="post">
<input type="hidden" name="act" value="multi">
<input type="hidden" name="sw_direct">

<div class="stit_txt tal">※ 총 <?php echo number_format($total_count);?>개의 상품이 보관되어 있습니다.</div>
<div class="m_wish_bg">
	<?php if($total_count) { ?>
	<div class="m_wish">
		<table class="wi_box">
		<tbody>
		<?php
		for($i=0; $row=sql_fetch_array($result); $i++) {
			$out_cd = '';
			$sql = " select count(*) as cnt from shop_goods_option where gs_id = '{$row['gs_id']}' and io_type = '0' ";
			$tmp = sql_fetch($sql);
			if($tmp['cnt'])
				$out_cd = 'no';

			if($row['price_msg']) {
				$out_cd = 'price_msg';
			}
		?>
		<tr>
			<th>
				<?php if(is_soldout($row['gs_id'])) { ?>
				<span class="fc_red tx_small">품절</span>
				<?php } else { ?>
				<input type="checkbox" name="chk_gs_id[<?php echo $i;?>]" value="1" id="ct_chk_<?php echo $i;?>" onclick="out_cd_check(this, '<?php echo $out_cd;?>');" class="css-checkbox"><label for="ct_chk_<?php echo $i;?>" class="css-label"></label>
				<?php } ?>
				<input type="hidden" name="gs_id[<?php echo $i;?>]" value="<?php echo $row['gs_id'];?>">
				<input type="hidden" name="io_type[<?php echo $row['gs_id'];?>][0]" value="0">
				<input type="hidden" name="io_id[<?php echo $row['gs_id']; ?>][0]" value="">
				<input type="hidden" name="io_value[<?php echo $row['gs_id'];?>][0]" value="<?php echo $row['gname'];?>">
				<input type="hidden" name="ct_qty[<?php echo $row['gs_id'];?>][0]" value="1">
			</th>
			<td class="mi_dt"><a href="./view.php?gs_id=<?php echo $row['gs_id'];?>"><?php echo get_it_image($row['gs_id'], $row['simg1'], 60, 60); ?></a></td>
			<td class="mi_bt">
				<div class="mi_u">
					<a href="./view.php?gs_id=<?php echo $row['gs_id'];?>"><?php echo cut_str($row['gname'],60);?></a>
					<div class="strong mart5"><?php echo get_price($row['gs_id']);?></div>
				</div>
				<div class="mi_d"><a href="./wishupdate.php?w=d&amp;wi_id=<?php echo $row['wi_id'];?>" class="btn_small grey">삭제</a></div>
			</td>
		</tr>
		<?php } ?>
		</tbody>
		</table>
	</div>
	<?php } ?>

	<?php
	if($total_count == 0) {
		echo "<div class=\"sct_noitem\">보관함이 비었습니다.</div>";
		echo "<div>";
			echo "<button type=\"button\" onclick=\"location.href='$tb[root]';\" class=\"btn_medium bx-white wfull\">쇼핑계속하기</button>";
		echo "</div>";
	} else {
	?>

	<div class="mart10">
		<?php echo pageing($page, $total_page, $total_count, "{$_SERVER['SCRIPT_NAME']}?page=");?>
	</div>

	<ul class="bt_wrap mart10">
		<li class="padl0"><button type="button" onclick="return fwishlist_check(document.fwishlist,'');" class="btn_medium bx-white">장바구니 담기</button></li>
		<li><button type="button" onclick="return fwishlist_check(document.fwishlist,'direct_buy');" class="btn_medium">상품 주문하기</button></li>
	</ul>
	<?php } ?>
</div>
</form>

<script>
<!--
function out_cd_check(fld, out_cd)
{
	if(out_cd == 'no'){
		alert("옵션이 있는 상품입니다.\n\n상품을 클릭하여 상품페이지에서 옵션을 선택한 후 주문하십시오.");
		fld.checked = false;
		return;
	}

	if(out_cd == 'price_msg'){
		alert("이 상품은 전화로 문의해 주십시오.\n\n장바구니에 담아 구입하실 수 없습니다.");
		fld.checked = false;
		return;
	}
}

function fwishlist_check(f, act)
{
	var k = 0;
	var length = f.elements.length;

	for(i=0; i<length; i++) {
		if(f.elements[i].checked) {
			k++;
		}
	}

	if(k == 0)
	{
		alert("상품을 하나 이상 체크 하십시오");
		return false;
	}

	if(act == "direct_buy")
	{
		f.sw_direct.value = 1;
	}
	else
	{
		f.sw_direct.value = 0;
	}

	f.action = "./cartupdate.php";
	f.submit();
}
//-->
</script>
