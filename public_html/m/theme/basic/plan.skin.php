<?php
if(!defined('_TUBEWEB_')) exit;
?>

<ul class="plan">
	<?php
	$sql = "select * from shop_plan where pl_use = '1' ";
	$res = sql_query($sql);
	for($i=0; $row=sql_fetch_array($res); $i++) {
		$href = $tb['bbs_root'].'/planlist.php?pl_no='.$row['pl_no'];
		$bimg = TW_DATA_PATH.'/plan/'.$row['pl_limg'];
		if(is_file($bimg) && $row['pl_limg']) {
			$pl_limgurl = TW_DATA_URL.'/plan/'.$row['pl_limg'];
		} else {
			$pl_limgurl = TW_IMG_URL.'/plan_noimg.gif';
		}
	?>
	<li>
		<a href="<?php echo $href; ?>">
		<p class="plan_img"><img src="<?php echo $pl_limgurl; ?>"></p>
		<p class="plan_tit"><?php echo $row['pl_name']; ?></p>
		</a>
	</li>
	<?php } ?>
</ul>
