<?php
if(!defined('_TUBEWEB_')) exit;

include_once($theme_path.'/aside_my.skin.php');
?>

<div class="rbody">
	<p class="tit_navi">홈 <i class="ionicons ion-ios-arrow-right"></i> 마이페이지 <i class="ionicons ion-ios-arrow-right"></i> 찜한상품</p>
	<h2 class="stit">찜한상품</h2>
	<p class="marb5">총 <b class="fc_red"><?php echo number_format($wish_count); ?></b>개의 찜한상품이 있습니다.</p>

	<form name="fwishlist" id="fwishlist" method="post">
	<input type="hidden" name="act" value="multi">
	<input type="hidden" name="sw_direct">
	<div class="tbl_head02">
		<table class="wfull">
		<colgroup>
			<col width="40">
			<col>
			<col width="150">
			<col width="120">
			<col width="40">
		</colgroup>
		<thead>
		<tr>
			<th class="bl_nolne">선택</th>
			<th>상품정보</th>
			<th>보관일시</th>
			<th>상품금액</th>
			<th>삭제</th>
		</tr>
		</thead>
		<tbody>
		<?php
		for($i=0; $row = sql_fetch_array($result); $i++) {
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
			<td class="bl_nolne">
				<?php if(is_soldout($row['gs_id'])) { ?>
				품절
				<?php } else { ?>
				<input type="checkbox" name="chk_gs_id[<?php echo $i; ?>]" value="1" onclick="out_cd_check(this, '<?php echo $out_cd; ?>');">
				<?php } ?>
				<input type="hidden" name="gs_id[<?php echo $i; ?>]" value="<?php echo $row['gs_id']; ?>">
				<input type="hidden" name="io_type[<?php echo $row['gs_id']; ?>][0]" value="0">
				<input type="hidden" name="io_id[<?php echo $row['gs_id']; ?>][0]" value="">
				<input type="hidden" name="io_value[<?php echo $row['gs_id']; ?>][0]" value="<?php echo $row['gname']; ?>">
				<input type="hidden" name="ct_qty[<?php echo $row['gs_id']; ?>][0]" value="1">
			</td>
			<td>
				<div class="tbl_wrap">
					<table class="wfull">
					<colgroup>
						<col width="90">
						<col>
					</colgroup>
					<tr>
						<td class="vat tal"><a href="<?php echo TW_SHOP_URL; ?>/view.php?index_no=<?php echo $row['gs_id']; ?>"><?php echo get_it_image($row['gs_id'], $row['simg1'], 80, 80); ?></a></td>
						<td class="vat tal">
							<a href="<?php echo TW_SHOP_URL; ?>/view.php?index_no=<?php echo $row['gs_id']; ?>" class="bold fs13"><?php echo $row['gname']; ?></a>
							<p class="fc_137"><?php echo $row['explan']; ?></p>
						</td>
					</tr>
					</table>
				</div>
			</td>
			<td><?php echo $row['wi_time']; ?></td>
			<td><?php echo get_price($row['gs_id']); ?></td>
			<td><a href="<?php echo TW_SHOP_URL; ?>/wishupdate.php?w=d&amp;wi_id=<?php echo $row['wi_id']; ?>"><img src="<?php echo TW_IMG_URL; ?>/btn_del.gif"></a></td>
		</tr>
		<?php } ?>
		</tbody>
		</table>
		<?php
		if($i == 0) {
			echo "<div class='empty_list bb'>보관함이 비었습니다.</div>\n";
			echo "<div class='tac mart20'><a href='".TW_URL."' class='btn_medium grey'>쇼핑 계속하기</a></div>\n";
		} else {
		?>
		<div class="tac mart20">
			<a href="javascript:void(0);" onclick="return fwishlist_check(document.fwishlist,'');" class="btn_medium">장바구니 담기</a>
			<a href="javascript:void(0);" onclick="return fwishlist_check(document.fwishlist,'direct_buy');" class="btn_medium wset">주문하기</a>
		</div>
		<?php } ?>
	</div>
	</form>
</div>

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
