<?php
if(!defined('_TUBEWEB_')) exit;

include_once($theme_path.'/aside_cs.skin.php');
?>

<div class="rbody">
	<p class="tit_navi">홈 <i class="ionicons ion-ios-arrow-right"></i> 고객센터 <i class="ionicons ion-ios-arrow-right"></i> 고객상품평</p>
	<h2 class="stit">고객상품평</h2>
	<p class="mart20 marb5">총 <b class="fc_red"><?php echo number_format($total_count); ?></b>건의 상품평이 있습니다.</p>
	<div class="tbl_head02 marb20">
		<table class="wfull">
		<colgroup>
			<col width="40">
			<col width="70">
			<col>
			<col width="80">
			<col width="90">
			<col width="90">
		</colgroup>
		<thead>
		<tr>
			<th class="bl_nolne">번호</th>
			<th>이미지</th>
			<th>상품평</th>
			<th>작성자</th>
			<th>작성일</th>
			<th>평점</th>
		</tr>
		</thead>
		<tbody>
		<?php
		for($i=0; $row=sql_fetch_array($result); $i++) {
			$bg = 'list'.($i%2);
			$gs = get_goods($row['gs_id']);
		?>
		<tr class="<?php echo $bg; ?>" align="center">
			<td class="bl_nolne"><?php echo $num--; ?></td>
			<td><a href="<?php echo TW_SHOP_URL; ?>/view.php?index_no=<?php echo $row['gs_id']; ?>" target="_blank"><?php echo get_it_image($gs['index_no'], $gs['simg1'], 50, 50); ?></a></td>
			<td class="td_tal">
				<p class="fs13 bold"><a href="<?php echo TW_SHOP_URL; ?>/view.php?index_no=<?php echo $row['gs_id']; ?>" target="_blank"><?php echo cut_str($gs['gname'], 55); ?></a></p>
				<p class="mart3"><?php echo cut_str($row['memo'], 100); ?></p>
			</td>
			<td><?php echo $row['writer_s']; ?></td>
			<td><?php echo date("Y-m-d",$row['wdate']); ?></td>
			<td><img src="<?php echo TW_IMG_URL; ?>/sub/score_<?php echo $row['score']; ?>.gif"></td>
		</tr>
		<?php
		}
		if($total_count==0)
			echo '<tr><td colspan="6" class="empty_list">내역이 없습니다.</td></tr>';
		?>
		</tbody>
		</table>
	</div>

	<?php
	echo pagelist($page, $total_page, $total_count, "{$_SERVER['SCRIPT_NAME']}?page=");
	?>
</div>
