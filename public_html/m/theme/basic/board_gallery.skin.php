<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<script src="<?php echo $tb['url']; ?>/js/jquery.lazyload.min.js"></script>
<script src="<?php echo $tb['url']; ?>/js/masonry.pkgd.js"></script>
<div class="m_gall mart10">
	<ul>
		<?php
		$sql = " select * from shop_board_{$boardid} where btype = '1' {$sql_search2} order by fid desc ";
		$rst = sql_query($sql);
		for($i=0; $row=sql_fetch_array($rst); $i++) {
			$href = './board_read.php?index_no='.$row['index_no'].'&boardid='.$boardid.'&page='.$page;

			$thumbnail = ($row['fileurl1']) ? TW_DATA_URL."/board/{$boardid}/{$row['fileurl1']}" : '/img/noimage.gif';

			$bo_subject = '<strong class="fc_eb7">[공지]</strong> '.get_text($row['subject']);
			$bo_wdate = get_text($row['writer_s'])."<span class='padl10'>".date("y/m/d",$row['wdate']);

			$REG_DATE = $row['wdate'];
			$REG_TIME = time();
			$TIME = 60*60*24;
			if(($REG_TIME-$REG_DATE) < $TIME) {
				$bo_subject .= " <img src='/img/iconY.gif' class='marl3'>";
			}
		?>
		<li class="item">
			<a href="<?php echo $href; ?>">
			<dl>
				<dt><img src="<?php echo $thumbnail; ?>" class="lazyload"></dt>
				<dd class="subj"><?php echo $bo_subject; ?></dd>
				<dd class="date"><?php echo $bo_wdate; ?></dd>
			</dl>
			</a>
		</li>
		<?php
		}

		for($i=0; $row=sql_fetch_array($result); $i++) {
			$href = './board_read.php?index_no='.$row['index_no'].'&boardid='.$boardid.'&page='.$page;

			$thumbnail = ($row['fileurl1']) ? TW_DATA_URL."/board/{$boardid}/{$row['fileurl1']}" : '/img/noimage.gif';

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
		<li class="item">
			<a href="<?php echo $href; ?>">
			<dl>
				<dt><img src="<?php echo $thumbnail; ?>" class="lazyload"></dt>
				<dd class="subj"><?php echo $bo_subject; ?></dd>
				<dd class="date"><?php echo $bo_wdate; ?></dd>
			</dl>
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

<script>
/* Mobile 브라우져 체크 함수 */
function mobile_chk(){
	var user_device = navigator.userAgent.toLowerCase();
	var mobile_device = new Array('iphone','ipad', 'firefox', 'android');
	for(var i=0;i<mobile_device.length;i++){
		if(user_device.indexOf(mobile_device[i]) != -1)	return true;
	}
	return false;
}
/*
setTimeout 체크값
*/
var ing = {'lazyloadCallback' : false};
/*
masonry 옵션값
*/
var masonryOptions = {itemSelector : ".item", columnWidth : ".item", percentPosition : true};
$(function(){
	/*
	Lazy Load 플러그인 적용
	(load 메서드를 통해 콜백함수 지정 / masonry의 재정렬은 layout 메서드가 아닌
	destroy 메서드를 이용한 masonry 제거후 masonry의 재적용으로 처리.
	destroy후 재적용시 pc 브라우져에서는 스크롤이 최상단으로 올라가는 문제가 있어
	navigator.userAgent의 값을 체크하여 모바일이 아닌경우
	scroll의 높이값을 저장했다 masonry가 적용 완료된 후 저장된 scroll 높이값으로 이동시켜준다.)
	*/
	$('img.lazyload').not('.lazyed').lazyload({
		effect : 'fadeIn',
		load : function(){
			$(this).addClass('lazyed');
			if($masgall){
				if(ing['lazyloadCallback']) clearTimeout(ing['lazyloadCallback']);
				ing['lazyloadCallback'] = window.setTimeout(function(){
					if(!mobile_chk())	var scroll = $(window).scrollTop();
					$masgall.masonry('destroy').masonry(masonryOptions);
					if(!mobile_chk())	$(window).scrollTop(scroll);
					console.log('함수 중복 체크 로그 - masonry 재정렬!');
				}, 100);
			}
		}
	});
	/*
	masonry 최초적용
	*/
	$masgall = $(".m_gall").masonry(masonryOptions);
});
</script>
