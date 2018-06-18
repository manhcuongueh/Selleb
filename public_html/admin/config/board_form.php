<?php
$board = get_boardconf($bo_table);

$bo_table_attr = "readonly style='background-color:#dddddd'";
$board_dir = TW_DATA_PATH."/board/boardimg";

if($w == "") {
	$board[index_no]	= get_next_num('shop_board_conf');
	$board[width]		= '100';
	$board[page_num]	= '30';
	$board[list_cut]	= '40';
	$board[topfile]		= './board_head.php';
	$board[downfile]	= './board_tail.php';
	$board[read_list]	= '1';
	$board[use_secret]	= '0';
	$board[list_priv]	= '99';
	$board[read_priv]	= '99';
	$board[reply_priv]	= '1';
	$board[write_priv]	= '99';
	$board[tail_priv]	= '99';

} else if($w == "u") {
    if(!$board[index_no])
        alert("존재하지 않은 게시판 입니다.");
}
?>

<form name="fboardform" method="post" onsubmit="return fboardform_submit(this)" enctype="MULTIPART/FORM-DATA">
<input type="hidden" name="w" value="<?php echo $w; ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
<input type="hidden" name="stx" value="<?php echo $stx; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">

<div class="tbl_frm02">
	<table>
	<colgroup>
		<col width="180px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th>TABLE</th>
		<td><input type="text" name="bo_table" value="<?php echo $board[index_no]; ?>" <?php echo $bo_table_attr; ?> class="frm_input w200"></td>
	</tr>
	<tr>
		<th>그룹</th>
		<td>
			<?php echo get_group_select('gr_id', $board[gr_id], "required itemname='그룹' onChange='chk_head(this.form, this.value)'"); ?>
			<?php if($w=='u') { ?><a href="javascript:location.href='config.php?code=board&sfl=gr_id&stx='+document.fboardform.gr_id.value;" class="btn_small grey">동일그룹게시판목록</a><?php } ?>
		</td>
	</tr>
	<tr>
		<th>게시판 제목</th>
		<td><input type="text" name="boardname" value='<?php echo get_text($board[boardname]); ?>' required itemname="게시판 제목" class="frm_input w200"></td>
	</tr>
	<tr>
		<th>스킨 디렉토리</th>
		<td>
			<select name="skin" required itemname="스킨 디렉토리">
				<?php
				$arr = get_skin_dir();
				for($i=0; $i<count($arr); $i++) {
					echo option_selected($arr[$i], $board[skin], $arr[$i]);
				}
				?>
			</select>
		</td>
	</tr>
	<tr>
		<th>분류</th>
		<td>
			<input type="text" class="frm_input" name="usecate" value="<?php echo get_text($board[usecate]); ?>" size="80">
			<label><input type="checkbox" name="use_category" value="1" <?php echo $board[use_category]?'checked':''; ?>> 사용</label>
			<p class="mart5">				
				<span class="fc_197">분류와 분류 사이는 | 로 구분하세요. (예: 질문|답변) 첫자로 #은 입력하지 마세요. (예: #질문|#답변 [X])</span>
			</p>
		</td>
	</tr>
	<tr>
		<th>게시판 테이블 폭</th>
		<td>
			<input type="text" name="width" value="<?php echo $board[width]; ?>" required itemname="게시판 테이블 폭" class="frm_input w80"> <span class="fc_197">100 이하는 %로 작동 합니다. </span>
		</td>
	</tr>
	<tr>
		<th>페이지당 목록 수</th>
		<td>
			<input type="text" name="page_num" value="<?php echo $board[page_num]; ?>"
			required itemname="페이지당 목록 수" class="frm_input w80"> <span class="fc_197">목록에 출력되는 게시물 줄수를 의미합니다.</span>
		</td>
	</tr>
	<tr>
		<th>제목 길이</th>
		<td>
			<input type="text" name="list_cut" value="<?php echo $board[list_cut]; ?>"
			required itemname="제목 길이"  class="frm_input w80"> <span class="fc_197">게시판 목록에서 출력될 제목을 자릅니다.</span>
		</td>
	</tr>
	<tr>
		<th>상단 파일 경로</th>
		<td><input type="text" name="topfile" value="<?php echo $board[topfile]; ?>" class="frm_input w200"></td>
	</tr>
	<tr>
		<th>하단 파일 경로</th>
		<td><input type="text" name="downfile" value="<?php echo $board[downfile]; ?>" class="frm_input w200"></td>
	</tr>
	<tr>
		<th>상단 이미지</th>
		<td>
			<input type="file" name="image_head">
			<?php
			if($board[fileurl1])
				echo " <a href='{$board_dir}/{$board[fileurl1]}' target='_blank'><b><font color='df7004'>{$board[fileurl1]}</font></b></a> <input id='ids_d1' type=checkbox name='image_head_del' value='{$board[fileurl1]}'><label for='ids_d1'>삭제</label>";
			?>
		</td>
	</tr>
	<tr>
		<th>하단 이미지</th>
		<td>
			<input type="file" name="image_tail">
			<?php
			if($board[fileurl2])
				echo " <a href='{$board_dir}/{$board[fileurl2]}' target='_blank'><b><font color='df7004'>{$board[fileurl2]}</font></b></a> <input id='ids_d2' type=checkbox name='image_tail_del' value='{$board[fileurl2]}'><label for='ids_d2'>삭제</label>";
			?>
		</td>
	</tr>
	<tr>
		<th>상단 내용</th>
		<td><textarea class="frm_textbox wfull" name="content_head" rows="5"><?php echo $board[content_head] ?></textarea></td>
	</tr>
	<tr>
		<th>하단 내용</th>
		<td><textarea class="frm_textbox wfull" name="content_tail" rows="5"><?php echo $board[content_tail] ?></textarea></td>
	</tr>
	<tr>
		<th>글쓰기 기본 내용</th>
		<td><textarea class="frm_textbox wfull" name="insert_content" rows="5"><?php echo $board[insert_content] ?></textarea></td>
	</tr>
	<tr>
		<th>목록보기 권한</th>
		<td>
			<?php echo get_member_level_select('list_priv', 1, 9, $board[list_priv]); ?>
		</td>
	</tr>
	<tr>
		<th>글읽기 권한</th>
		<td>
			<?php echo get_member_level_select('read_priv', 1, 9, $board[read_priv]); ?>
		</td>
	</tr>
	<tr>
		<th>글쓰기 권한</th>
		<td>
			<?php echo get_member_level_select('write_priv', 1, 9, $board[write_priv]); ?>
		</td>
	</tr>
	<tr>
		<th>글답변 권한</th>
		<td>
			<?php echo get_member_level_select('reply_priv', 1, 9, $board[reply_priv]); ?>
			<label><input type="checkbox" name=usereply value='Y' <?php echo ($board[usereply] == 'Y')?'checked':''; ?>> 사용</label>
		</td>
	</tr>
	<tr>
		<th>코멘트쓰기 권한</th>
		<td>
			<?php echo get_member_level_select('tail_priv', 1, 9, $board[tail_priv]); ?>
			<label><input type="checkbox" name=usetail value='Y' <?php echo ($board[usetail] == 'Y')?'checked':''; ?>> 사용</label>
		</td>
	</tr>
	<tr>
		<th>파일 업로드</th>
		<td>
			<label><input type="checkbox" name="usefile" value="Y" <?php echo ($board[usefile] == 'Y')?'checked':''; ?>> 사용</label>
		</td>
	</tr>
	<tr>
		<th>비밀글 사용</th>
		<td>
			<select name="use_secret">
				<?php echo option_selected('0', $board[use_secret], '사용하지 않음'); ?>
				<?php echo option_selected('1', $board[use_secret], '체크박스'); ?>
				<?php echo option_selected('2', $board[use_secret], '무조건'); ?>
			</select>
			<p class="fc_197 mart5">'체크박스'는 글작성시 비밀글 체크가 가능합니다.<br>'무조건'은 작성되는 모든글을 비밀글로 작성합니다. (관리자는 체크박스로 출력합니다.)<br>스킨에 따라 적용되지 않을 수 있습니다.</p>
		</td>
	</tr>
	<tr>
		<th>글내용 옵션</th>
		<td>
			<select name="read_list">
				<?php echo option_selected('1', $board[read_list], '전체 목록 출력'); ?>
				<?php echo option_selected('2', $board[read_list], '이전글 다음글만 출력'); ?>
				<?php echo option_selected('3', $board[read_list], '사용안함'); ?>
			</select>
		</td>
	</tr>
	</tbody>
	</table>
</div>
<div class="btn_confirm">
	<input type="submit" class="btn_large" accesskey="s" value="저장">
	<a href="config.php?code=board<?php echo $qstr; ?>&page=<?php echo $page; ?>" class="btn_large bx-white marl3">목록</a>
</div>
</form>

<script>
function fboardform_submit(f) {
	f.action = "./config/board_form_update.php";
    return true;
}

function chk_head(f, val){
	switch(val) {
		case 'gr_item':
			f.topfile.value  = '../mypage/board_head.php';
			f.downfile.value = '../mypage/board_tail.php';
			break;
		case 'gr_mall':
			f.topfile.value  = './board_head.php';
			f.downfile.value = './board_tail.php';
			break;
		default:
			f.topfile.value  = '';
			f.downfile.value = '';
			break;
	}
}
</script>