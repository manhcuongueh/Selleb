<?php
if(!defined('_TUBEWEB_')) exit;
?>

<div class="tbl_frm01">
	<table class="wfull">
	<tr>
		<td class="list1 fs14" colspan="2"><b><?php echo $bo_subject; ?></b></td>
	</tr>
	<tr>
		<td class="list1 tal"><b><?php echo $bo_writer_s; ?></b> <?php if($bo_writer){?>(<?php echo $bo_writer_id; ?>)<?php } ?></td>
		<td class="list1 tar"><b>작성일</b> : <?php echo $bo_wdate; ?>, <b>조회수</b> : <?php echo $bo_hit; ?></td>
	</tr>
	<?php if($bo_file2) { ?>
	<tr>
		<td colspan="2">첨부파일 : <a href="download.php?file=<?php echo $bo_file2; ?>&url=<?php echo TW_DATA_PATH; ?>/board/<?php echo $boardid; ?>/<?php echo $bo_file2; ?>"><b><?php echo $bo_file2; ?></b></a></td>
	</tr>
	<?php } ?>
	<tr>
		<td class="vat" colspan="2" style="height:200px;vertical-align:top;">
		<?php
		// 픽셀 (게시판에서 출력되는 이미지의 폭 크기)
		if($boardconfig['width'] > 100) {
			$thumbnail_width = $boardconfig['width'];
		} else {
			$thumbnail_width = 768;
		}
		if($bo_file2 && preg_match("/\.(gif|jpg|jpeg|png)$/i", $bo_file2))
		{
			$file2anal = explode(".",$bo_file2);
			if(in_array($file2anal[1],$accept))
			{
				$imgsize1 = getimagesize(TW_DATA_PATH."/board/".$boardid."/".$bo_file2);
				if($imgsize1[0] > $thumbnail_width) {
					$width = $thumbnail_width;
					$height = ($imgsize1[1] / $imgsize1[0]) * $thumbnail_width;
				} else {
					$width = $imgsize1[0];
					$height = $imgsize1[1];
				}
			}
		?>
		<a href="javascript:imgview('<?php echo TW_DATA_PATH; ?>/board/<?php echo $boardid; ?>/<?php echo $bo_file2; ?>');"><img src="<?php echo TW_DATA_PATH; ?>/board/<?php echo $boardid; ?>/<?php echo $bo_file2; ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>"></a>
		<?php
		}

		echo get_view_thumbnail(conv_content($bo_memo, 1), $thumbnail_width);
		?>
		</td>
	</tr>
	</table>
</div>
<div class="page_wrap">
	<div class="lbt_box">
		<a href="list.php?<?php echo $qstr1; ?>" class="btn_lsmall bx-white">목록</a>
	</div>
	<div class="rbt_box">
		<?php if(($memindex == $bo_writer) || is_admin()) { ?>
		<a href="modify.php?<?php echo $qstr2; ?>" class="btn_lsmall bx-white">수정</a>
		<?php } ?>
		<?php if($memindex && $grade<=$boardconfig['reply_priv'] && $boardconfig['usereply']=='Y') { ?>
		<a href="reply.php?<?php echo $qstr2; ?>" class="btn_lsmall bx-white">답글</a>
		<?php } ?>
		<?php if(($memindex == $bo_writer) || is_admin()) { ?>
		<a href="del.php?<?php echo $qstr2; ?>" class="btn_lsmall bx-white">삭제</a>
		<?php } ?>
		<?php if($grade <= $boardconfig['write_priv']){ ?>
		<a href="write.php?boardid=<?php echo $boardid; ?>" class="btn_lsmall">글쓰기</a>
		<?php } ?>
	</div>
</div>

<!--코멘트 출력부분-->
<?php if($boardconfig['usetail']=='Y') { ?>
<form name="fboardform" method="post" onsubmit="return fboardform_submit(this);">
<input type='hidden' name='mode' value="w">
<input type='hidden' name='index_no' value="<?php echo $index_no; ?>">
<input type='hidden' name='page' value="<?php echo $page; ?>">
<input type='hidden' name='boardid' value="<?php echo $boardid; ?>">
<input type='hidden' name='key' value="<?php echo $key; ?>">
<input type='hidden' name='keyword' value="<?php echo $keyword; ?>">

<table class="wfull">
<tr>
	<td>
	<?php
	$sql = "select * from shop_board_{$boardid}_tail where board_index='$index_no' order by wdate asc";
	$res = sql_query($sql);
	while($row=sql_fetch_array($res)) {
		$bo_wdate = date("Y-m-d H:i",$row['wdate']);
	?>
	<div class="tbl_frm01 marb5">
		<table class="wfull">
		<tr class="list1">
			<td>작성자 : <b><?php echo $row['writer_s']; ?></b> (<?php echo $bo_wdate; ?>) <?php echo "<a href=\"tail_del.php?tailindex={$row['index_no']}&{$qstr2}\" class=\"btn_ssmall bx-white\">삭제</a>"; ?></td>
		</tr>
		<tr>
			<td><?php echo nl2br($row['memo']); ?></td>
		</tr>
		</table>
	</div>
	<?php } ?>

	<?php
	if($memid) {
		if($grade > $boardconfig['tail_priv']) {
	?>
	<table class="wfull bd">
	<tr height="80" class="list1">
		<td width="9%" class="tac"><b><?php echo $member['name']; ?></b></td>
		<td width="81%"><textarea name="memo" class="frm_textbox h60">댓글을 작성할 권한이 없습니다.</textarea></td>
		<td class="tar padr10 padl10"><input type='button' onclick="alert('댓글을 작성할 권한이 없습니다.');" value="글쓰기" class="btn_medium grey h60"></td>
	</tr>
	</table>
	<?php } else { ?>
	<input type="hidden" name="writer_s" value="<?php echo $member['name']; ?>">
	<table class="wfull bd">
	<tr height="80" class="list1">
		<td width="9%" class="tac bold"><?php echo $member['name']; ?></td>
		<td width="81%"><textarea name="memo" class="frm_textbox h60"></textarea></td>
		<td class="tar padr10 padl10"><input type="submit" value="댓글입력" class="btn_medium grey h60"></td>
	</tr>
	</table>
	<?php } ?>

	<?php
	} else {
		if($boardconfig['tail_priv'] == '99') {
	?>
	<div class="tbl_frm01">
		<table class="wfull">
		<tr>
			<td colspan="2">작성자 : <input type='text' name='writer_s' size='20' class='ed marr15'> 비밀번호 : <input type='password' name='passwd' size='20' class='ed'></td>
		</tr>
		<tr class="list1">
			<td width="90%"style="padding:10px 0 10px 10px"><textarea name="memo" class="frm_textbox h60"></textarea></td>
			<td class="tar padr10 padl10"><input type="submit" value="댓글입력" class="btn_medium grey h60"></td>
		</tr>
		</table>
	</div>
	<?php } else { ?>
	<table class="wfull bd">
	<tr height="80" class="list1">
		<td width="9%" class="tac bold"><?php echo $bo_writer_s; ?></td>
		<td width="81%"><textarea name="memo" class="frm_textbox h60">로그인후 댓글을 작성 가능합니다.</textarea></td>
		<td class="tar padr10 padl10"><input type='button' onclick="alert('로그인후 댓글을 작성 가능합니다.');" value="댓글입력" class="btn_medium grey h60"></td>
	</tr>
	</table>
	<?php }
	}
	?>
	</td>
</tr>
</table>
</form>
<?php } ?>

<script>
function fboardform_submit(f)
{
	<?php if(!$memid) { ?>
	if(!f.writer_s.value) {
		alert('작성자명을 입력하세요.');
		f.writer_s.focus();
		return false;
	}
	if(!f.passwd.value) {
		alert('비밀번호를 입력하세요.');
		f.passwd.focus();
		return false;
	}
	<?php } ?>

	if(!f.memo.value) {
		alert('댓글을 작성하지 않았습니다!');
		f.memo.focus();
		return false;
	}

	f.action = './tail_write.php';
	return true;
}

function imgview(img) {
	 window.open("imgviewer.php?img="+img,"img",'width=150,height=150,status=no,top=0,left=0,scrollbars=yes');
}
</script>

<?php
include "skin/{$boardconfig['list_skin']}/read_list.php";
?>
