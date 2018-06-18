<?php
if(!defined('_TUBEWEB_')) exit;

include_once($theme_path.'/aside_my.skin.php');
?>

<div class="rbody">
	<p class="tit_navi">홈 <i class="ionicons ion-ios-arrow-right"></i> 마이페이지 <i class="ionicons ion-ios-arrow-right"></i> 쿠폰관리</p>
	<h2 class="stit">쿠폰관리</h2>
	<div class="tac">
		<a href="<?php echo TW_SHOP_URL; ?>/coupon.php" class="btn_medium<?php if($sca) echo ' bx-white'; ?>">사용가능한 쿠폰</a>
		<a href="<?php echo TW_SHOP_URL; ?>/coupon.php?sca=1" class="btn_medium<?php if(!$sca) echo ' bx-white'; ?>">사용완료 및 기한만료 쿠폰</a>
	</div>
	<div class="tbl_head02 mart15 marb20">
		<table class="wfull">
		<colgroup>
			<col width="50">
			<col>
			<col width="150">
			<col>
			<col width="150">
			<col width="140">
		</colgroup>
		<thead>
		<tr>
			<th class="bl_nolne">번호</th>
			<th>할인쿠폰</th>
			<th>할인금액(율)</th>
			<th>사용가능대상</th>
			<th>사용기한</th>
			<th>취득일자</th>
		</tr>
		</thead>
		<tbody>
		<?php
		for($i=0; $row=sql_fetch_array($result); $i++) {

			// 할인금액(율)
			if($row['cp_sale_type'] == '0') {
				if($row['cp_sale_amt_max'] > 0)
					$cp_sale_amt_max = "&nbsp;(최대 ".display_price2($row['cp_sale_amt_max']).")";
				else
					$cp_sale_amt_max = "";

				$sale_amt = $row['cp_sale_percent']. '%' . $cp_sale_amt_max;
			} else {
				$sale_amt = display_price2($row['cp_sale_amt']);
			}

			// 쿠폰 사용기한
			if($row['cp_inv_type'] == '0') {
				if($row['cp_inv_sdate'] == '9999999999') $cp_inv_sdate = '무제한';
				else $cp_inv_sdate = date_conv($row['cp_inv_sdate'],4);

				if($row['cp_inv_edate'] == '9999999999') $cp_inv_edate = '무제한';
				else $cp_inv_edate = date_conv($row['cp_inv_edate'],4);

				if($row['cp_inv_sdate'] == '9999999999' && $row['cp_inv_edate'] == '9999999999')
					$inv_date = '무제한';
				else
					$inv_date = $cp_inv_sdate . " ~ " . $cp_inv_edate;
			} else {
				$inv_date = '다운로드 후 ' . $row['cp_inv_day']. '일간';
			}
		?>
		<tr>
			<td class="bl_nolne"><?php echo $num--; ?></td>
			<td class="td_tal"><a href='<?php echo TW_SHOP_URL; ?>/coupon_goods.php?lo_id=<?php echo $row['lo_id']; ?>' onclick="openwindow(this,'coupon_goods','800','800','yes');return false;"><?php echo get_text(cut_str($row['cp_subject'],44)); ?></a></td>
			<td><?php echo $sale_amt; ?></td>
			<td><?php echo $u_part[$row['cp_use_part']]; ?></td>
			<td><?php echo $inv_date; ?></td>
			<td><?php echo $row['cp_wdate']; ?></td>
		</tr>
		<?php
		}
		if($total_count==0) {
		?>
		<tr><td colspan="6" class="empty_list">자료가 없습니다.</td></tr>
		<?php } ?>
		</tbody>
		</table>
	</div>

	<?php
	echo pagelist($page, $total_page, $total_count, "{$_SERVER['SCRIPT_NAME']}?$qstr&page=");
	?>
</div>
