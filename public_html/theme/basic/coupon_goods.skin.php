<?php
if(!defined('_TUBEWEB_')) exit;
?>

<div class="pop_wrap">
	<h2 class="pop_tit"><i class="fa fa-pencil-square-o"></i> <?php echo $gw_head_title; ?> <a href="javascript:self.close();" class="pop_close"></a></h2>

	<div class="pop_inner">
		<p>이 쿠폰으로, 구매하실 수 있는 상품내역 입니다. 상품을 클릭 후 주문시 쿠폰을 사용하세요.</p>
		<p class="mart3">발행된 쿠폰은 <b>[마이페이지 > 쿠폰관리]</b> 에서 확인 할 수 있습니다.</p>
		<p class="mart10">전체 : <b><?php echo number_format($total_count); ?></b>건 조회</p>
		<table class="wfull bt bb mart12">
		<tr>
			<?php
			for($i=0; $row=sql_fetch_array($result); $i++)
			{	
				if($i && $i%$mod==0)
					echo "</tr><tr><td colspan='$mod' width='100%' height=1 style='background:#e5e5e5'></td></tr><tr>";

			echo "<td width=''{$td_width}%' align='center' class='vat padt15'>";
			?>
			<table class="w140">
			<tr>
				<td style="border:1px solid #e5e5e5" onMouseOver="this.style.border='1px solid #232428';this.style.cursor='hand'" onMouseOut="this.style.border='1px solid #e5e5e5'"><a href="javascript:goto_item('<?php echo $row['index_no']; ?>');"><?php echo get_it_image($row['index_no'], $row['simg1'], 140, 140); ?></a></td>
			</tr>
			<tr align="center">
				<td class="vat padt10"><?php echo get_price($row['index_no']); ?></td>
			</tr>
			<tr align="center">
				<td class="vat padt10 padb10"><?php echo cut_str($row['gname'], 44); ?></td>
			</tr>
			</table>
		<?php
			echo "</td>";
		}

		// 나머지 td
		$cnt = ($i%$mod);
		if($cnt) {
			for($i=$cnt; $i<$mod; $i++) {
				echo "<td width='{$td_width}%'>&nbsp;</td>";
			}
		}
		
		if($total_count==0)
			echo '<tr><td colspan="5" class="empty_list">자료가 없습니다.</td></tr>';
		?>
		</table>		

		<?php if($total_count > 0) { ?>
		<div class="mart20">
			<?php echo pagelist($page, $total_page, $total_count, "{$_SERVER['SCRIPT_NAME']}?lo_id=$lo_id&page="); ?>
		</div>
		<?php } ?>
	</div>
</div>

<script>
function goto_item(gs_id){
	opener.document.location.href = "<?php echo TW_SHOP_URL; ?>/view.php?index_no="+gs_id;
	self.close();
}
</script>
