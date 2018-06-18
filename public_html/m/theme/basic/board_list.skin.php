<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<div class="m_bo_bg mart10">
	<ul>
		<?php
		$sql = " select * from shop_board_{$boardid} where btype = '1' {$sql_search2} order by fid desc ";
		$rst = sql_query($sql);
		for($i=0; $row=sql_fetch_array($rst); $i++) {
			$href = './board_read.php?index_no='.$row['index_no'].'&boardid='.$boardid.'&page='.$page;

			$bo_subject = '<strong class="fc_eb7">[공지]</strong> '.get_text($row['subject']);
			$bo_wdate = get_text($row['writer_s'])."<span class='padl10'>".date("y/m/d",$row['wdate']);

			$REG_DATE = $row['wdate'];
			$REG_TIME = time();
			$TIME = 60*60*24;
			if(($REG_TIME-$REG_DATE) < $TIME) {
				$bo_subject .= " <img src='/img/iconY.gif' class='marl3'>";
			}
		?>
		<li class="list">
			<a href="<?php echo $href; ?>">
			<p class="subj"><?php echo $bo_subject; ?></p>
			<p class="date"><?php echo $bo_wdate; ?></p>
			</a>
		</li>
		<?php
		}

		for($i=0; $row=sql_fetch_array($result); $i++) {
			$href = './board_read.php?index_no='.$row['index_no'].'&boardid='.$boardid.'&page='.$page;

			$bo_subject = '';
			$bo_wdate_c = '';
			$spacer = strlen($row['thread'] != 'A');
			if($spacer>$reply_limit) {
				$spacer = $reply_limit;
			}

			for($i2=0; $i2<$spacer; $i2++) {
				$bo_subject = "<img src='{$bo_img_url}/img/icon_reply.gif'> ";
				$bo_wdate_c = " padl13";
			}

			$bo_subject = $bo_subject .get_text($row['subject']);
			$bo_wdate = get_text($row['writer_s'])."<span class='padl10'>".date("y/m/d",$row['wdate']);

			if($row['issecret'] == 'Y') {
				$bo_subject .= " <img src='{$bo_img_url}/img/icon_secret.gif'>";
			}

			$REG_DATE = $row['wdate'];
			$REG_TIME = time();
			$TIME = 60*60*24;
			if(($REG_TIME-$REG_DATE) < $TIME) {
				$bo_subject .= " <img src='{$bo_img_url}/img/iconY.gif'>";
			}
		?>
		<li class="list">
			<a href="<?php echo $href; ?>">
			<p class="subj"><?php echo $bo_subject; ?></p>
			<p class="date"><?php echo $bo_wdate; ?></p>
			</a>
		</li>
		<?php
		}
		?>
	</ul>
</div>

<div class="pg_wrap mart10">
<?php if($grade <= $board['write_priv']) { ?>
<p class="marb10">
	<a href="./board_write.php?boardid=<?php echo $boardid;?>" class="btn_medium wfull">글쓰기</a>
</p>
<?php } ?>

<?php
if(!$total_count) {
	echo "<div class='sct_noitem'>게시글이 없습니다.</div>";
} else {
	echo pageing($page, $total_page, $total_count, "{$_SERVER['SCRIPT_NAME']}?boardid=$boardid&page=");
}
?>
</div>
