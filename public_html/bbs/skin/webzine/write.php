<?php
if(!defined('_TUBEWEB_')) exit;
?>

<form name="fboardform" method="post" onsubmit="return fboardform_submit(this);" enctype="MULTIPART/FORM-DATA">
<input type="hidden" name="mode" value="w">
<input type="hidden" name="boardid" value="<?php echo $boardid; ?>">

<div class="tbl_frm01">
	<table class="wfull">
	<colgroup>
		<col width="80">
		<col>
	</colgroup>
	<tr>
		<th>이 름</th>
		<td>
			<?php
			if($member['id']) {
				echo $member['name'];
				echo "<input type='hidden' name='writer_s' value='$member[name]'>";
			} else {
				echo "<input type='text' name='writer_s' class='ed'>";
			}
			?>
		</td>
	</tr>
	<?php if(!$member['id']) { ?>
	<tr>
		<th>비밀번호</td>
		<td><input name="passwd" type="password" class="ed"></td>
	</tr>
	<?php } ?>
	<?php if($boardconfig['use_category'] == '1') { ?>
	<tr>
		<th>분 류</th>
		<td>
			<select name="ca_name">
			<option value="">선택하세요</option>
			<?php echo get_category_option($boardconfig['usecate']); ?>
			</select>
		</td>
	</tr>
	<?php } ?>
	<?php
	$option = "";
	$option_hidden = "";
	if(is_admin()) {
		$option .= "<input type=checkbox name='btype' value='1'> 공지사항";
		$option .= "<input type=checkbox name='issecret' value='Y' class='marl15'> 비밀글";
	} else {

		switch($boardconfig['use_secret']){
			case '0':
				$option_hidden .= "<input type=hidden value='N' name='issecret'>";
				break;
			case '1':
				$option .= "<input type=checkbox value='Y' name='issecret'> 비밀글";
				break;
			case '2':
				$option_hidden .= "<input type=hidden value='Y' name='issecret'>";
				break;
		}
	}

	echo $option_hidden;
	if($option) {
	?>
	<tr>
		<th>옵 션</th>
		<td><?php echo $option; ?></td>
	</tr>
	<?php } ?>
	<tr>
		<th>목록 이미지</th>
		<td><input type='file' name='file1'></td>
	</tr>
	<tr>
		<th>제 목</th>
		<td><input type='text' name='subject' class="ed wfull"></td>
	</tr>
	<tr>
		<td colspan="2">
			<?php echo editor_html('memo', get_text($boardconfig['insert_content'], 0)); ?>
		</td>
	</tr>
	<?php if($boardconfig['usefile']=='Y') { ?>
	<tr>
		<th>첨부파일</th>
		<td><input type='file' name='file2'></td>
	</tr>
	<?php } ?>
	</table>
</div>
<div class="tac mart15">
	<input name="button" type="submit" value="글쓰기" class="btn_lsmall">
	<a href="javascript:history.go(-1);" class="btn_lsmall bx-white">취소</a>
</div>
</form>

<script>
function fboardform_submit(f) {
	<?php if(!$member['id']) { ?>
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

	<?php if($boardconfig['use_category'] == '1') { ?>
	if(!f.ca_name.value) {
		alert('분류를 선택하세요.');
		f.ca_name.focus();
		return false;
	}
	<?php } ?>

	if(!f.subject.value) {
		alert('제목을 입력하세요.');
		f.subject.focus();
		return false;
	}

	<?php echo get_editor_js('memo'); ?>
	<?php echo chk_editor_js('memo'); ?>

	f.action = './writepro.php';
    return true;
}
</script>
