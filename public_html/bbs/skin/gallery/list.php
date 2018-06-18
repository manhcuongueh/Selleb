<?php
if(!defined('_TUBEWEB_')) exit;

$sql_search2 = "";
if($default['de_board_wr_use']) {
	$sql_search2 = " and pt_id = '$pt_id' ";
}

$sql_common = " from shop_board_{$boardid} ";
$sql_search = " where btype = '2' {$sql_search2} ";

if($key && $keyword) {
    $sql_search .= " and ($key like '%$keyword%') ";
}

$sql_order  = " order by fid desc, thread asc ";

$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $boardconfig['page_num'];
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select * $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

$reply_limit = 6;
$run = 0;

$colspan = 6;
if(is_admin()) $colspan++;

$qstr1 = "boardid=$boardid&key=$key&keyword=$keyword&page=$page";
$qstr2 = "boardid=$boardid&key=$key&keyword=$keyword";
?>

<form name="fboardlist" method="post" action="del_all.php" onsubmit="return Check_Select(this);">
<input type='hidden' name='boardid' value="<?php echo $boardid; ?>">
<input type='hidden' name='key' value="<?php echo $key; ?>">
<input type='hidden' name='keyword' value="<?php echo $keyword; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">

<p class="marb7 tal">
	<?php if(is_admin()) { ?><input type="checkbox" name="chkall" id="chkall" value="1" onclick="check_all(this.form);"> <label for="chkall" class="marr20">전체선택</label><?php } ?>
	총 <b class="fc_red"><?php echo $total_count; ?></b>개의 게시물이 있습니다.
</p>
<div class="gallery">
	<?php
	$sql = " select * from shop_board_{$boardid} where btype = '1' {$sql_search2} order by fid desc ";
	$rst = sql_query($sql);
	for($i=0; $row=sql_fetch_array($rst); $i++) {
		$bo_subject	= cut_str($row['subject'], $boardconfig['list_cut']);
		$bo_wdate	= date("Y-m-d", $row['wdate']);

		if($row['fileurl1'] || $row['fileurl2'])
			$bo_file_yes ="<img src='".$bo_img_url."/img/file_on.gif'>";
		else
			$bo_file_yes ="<img src='".$bo_img_url."/img/file_off.gif'>";

		if(($server_time-$row['wdate']) < (60*60*24))
			$bo_newicon = "&nbsp;<img src='".$bo_img_url."/img/iconY.gif'>";
		else
			$bo_newicon = "";

		$bo_href = './read.php?index_no='.$row['index_no'].'&'.$qstr1;

		$thumbnail = ($row['fileurl1']) ? TW_DATA_PATH.'/board/'.$boardid.'/'.$row['fileurl1'] : '/img/noimage.gif';
	?>
	<dl>
		<a href="<?php echo $bo_href; ?>">
		<dt><img src="<?php echo $thumbnail; ?>"></dt>
		<dd class="bo_tit">
			<p class="bo_notice">공지</p> <?php echo $bo_subject; ?><?php if($row['issecret'] == 'Y') { ?>&nbsp;<img src='<?php echo $bo_img_url; ?>/img/icon_secret.gif'><?php } ?><?php echo $bo_newicon; ?>
		</dd>
		<dd><?php echo $row['writer_s']; ?><span><?php echo $bo_wdate; ?></span><span>조회 : <?php echo $row['readcount']; ?></span></dd>
		</a>
		<?php if(is_admin()) { ?>
		<dd class="bo_chk"><input type="checkbox" name="OrderNum[]" value="<?php echo $row['index_no']; ?>"></dd>
		<?php } ?>
	</dl>
	<?php
		$run++;
	}

	for($i=0; $row=sql_fetch_array($result); $i++) {
		$bo_wdate = date("Y-m-d", $row['wdate']);

		if($row['fileurl1'] || $row['fileurl2'])
			$bo_file_yes ="<img src='".$bo_img_url."/img/file_on.gif'>";
		else
			$bo_file_yes ="<img src='".$bo_img_url."/img/file_off.gif'>";

		$spacer = strlen($row['thread'] != 'A');
		if($spacer > $reply_limit) {
			$spacer = $reply_limit;
		}

		$bo_subject = "";
		for($g=0; $g<$spacer; $g++) {
			$bo_subject .= "<img src='".$bo_img_url."/img/icon_reply.gif'>&nbsp;";
		}

		if($boardconfig['use_category'] == '1'  && $row['ca_name']) {
			$bo_subject .= '<strong>['.$row['ca_name'].']</strong>&nbsp;';
		}

		$bo_subject .= cut_str($row['subject'], $boardconfig['list_cut']);

		if(($server_time-$row['wdate']) < (60*60*24))
			$bo_newicon = "&nbsp;<img src='".$bo_img_url."/img/iconY.gif'>";
		else
			$bo_newicon = "";

		$bo_href = './read.php?index_no='.$row['index_no'].'&'.$qstr1;

		$thumbnail = ($row['fileurl1']) ? TW_DATA_PATH.'/board/'.$boardid.'/'.$row['fileurl1'] : '/img/noimage.gif';
	?>
	<dl>
		<a href="<?php echo $bo_href; ?>">
		<dt><img src="<?php echo $thumbnail; ?>"></dt>
		<dd class="bo_tit">
			<?php echo $bo_subject; ?><?php if($row['issecret'] == 'Y') { ?>&nbsp;<img src='<?php echo $bo_img_url; ?>/img/icon_secret.gif'><?php } ?><?php echo $bo_newicon; ?>
		</dd>
		<dd><?php echo $row['writer_s']; ?><span><?php echo $bo_wdate; ?></span><span>조회 : <?php echo $row['readcount']; ?></span></dd>
		</a>
		<?php if(is_admin()) { ?>
		<dd class="bo_chk"><input type="checkbox" name="OrderNum[]" value="<?php echo $row['index_no']; ?>"></dd>
		<?php } ?>
	</dl>
	<?php
		$run++;
	}
	?>

	<?php if(!$run) { ?>
	<p class="empty_list">게시물이 없습니다.</p>
	<?php } ?>
</div>

<div class="page_wrap">
	<?php if(is_admin()) { ?>
	<div class="lbt_box">
		<input type="submit" value="삭제" class="btn_lsmall bx-white">
	</div>
	<?php } ?>

	<?php echo pageing($page, $total_page, $total_count, "{$_SERVER['SCRIPT_NAME']}?$qstr2&page="); ?>

	<?php if($grade <= $boardconfig['write_priv']) { ?>
	<div class="rbt_box">
		<a href="write.php?boardid=<?php echo $boardid; ?>" class="btn_lsmall">글쓰기</a>
	</div>
	<?php } ?>
</div>
</form>

<form name="searchform" method="get">
<input type='hidden' name='boardid' value="<?php echo $boardid; ?>">
<div class="bottom_sch">
	<select name="key">
	<?php
	for($i=0;$i<sizeof($gw_search_value);$i++) {
		echo "<option value='{$gw_search_value[$i]}'".get_selected($gw_search_value[$i], $key).">{$gw_search_text[$i]}</option>\n";
	}
	?>
	</select>
	<input type="text" name="keyword" class="ed" value="<?php echo $keyword; ?>">
	<input type="submit" value="검색" class="btn_lsmall grey">
</div>
</form>

<script>
function Check_Select(form) {
	var check_nums = document.fboardlist.elements.length;
	for(var i=0; i<check_nums; i++) {
		var checkbox_obj = eval("document.fboardlist.elements[" + i + "]");
		if(checkbox_obj.checked == true) {
			break;
		}
	}

	if(i == check_nums) {
		alert ("삭제할 게시물을 하나 이상 선택하세요!");
			return false;
	} else {
		if(!confirm("한번 삭제한 자료는 복구할 수 없습니다.\n\n선택한 항목을 정말 삭제 하시겠습니까?"))
			return false;

		document.fboardlist.submit();
	}
}

function check_all(f)
{
    var chk = document.getElementsByName("OrderNum[]");

    for(i=0; i<chk.length; i++)
        chk[i].checked = f.chkall.checked;
}
</script>
