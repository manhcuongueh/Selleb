<?php
if(!defined('_TUBEWEB_')) exit;

include_once($theme_path.'/aside_my.skin.php');
?>

<div class="rbody">
	<p class="tit_navi">홈 <i class="ionicons ion-ios-arrow-right"></i> 마이페이지 <i class="ionicons ion-ios-arrow-right"></i> 적립금조회</p>
	<h2 class="stit">적립금조회</h2>

	<div class="tbl_frm01">
		<table class="wfull">
		<thead>
		<tr>
			<th class="tac">총 결제금액</th>
			<th class="tac">총 적립금액</th>
			<th class="tac">현재 보유잔액</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td class="tac bold fs14"><?php echo number_format($outcom); ?> P</td>
			<td class="tac bold fs14"><?php echo number_format($incom); ?> P</td>
			<td class="tac bold fs14 fc_255"><?php echo number_format($member['point']); ?> P</td>
		</tr>
		</tbody>
		</table>
	</div>

	<div class="tbl_head02 mart15 marb20">
		<table class="wfull">
		<colgroup>
			<col width="50">
			<col width="140">
			<col>
			<col width="90">
			<col width="90">
			<col width="90">
		</colgroup>
		<thead>
		<tr>
			<th class="bl_nolne">번호</th>
			<th>일시</th>
			<th>내역</th>
			<th>사용액</th>
			<th>적립액</th>
			<th>잔액</th>
		</tr>
		</thead>
		<tbody>
		<?php
		for($i=0; $row=sql_fetch_array($result); $i++) {
		?>
		<tr align="center">
			<td class="bl_nolne"><?php echo $num--; ?></td>
			<td><?php echo date("Y-m-d H:i:s",$row['wdate']); ?></td>
			<td class="td_tal"><?php echo get_text($row['memo']); ?></td>
			<td class="td_tar"><?php echo number_format($row['outcome']); ?></td>
			<td class="td_tar"><?php echo number_format($row['income']); ?></td>
			<td class="td_tar"><?php echo number_format($row['total']); ?></td>
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
