<?php
if(!defined('_TUBEWEB_')) exit;

if($w == "") {
	$bn['bn_width']	 = $bn_width  ? $bn_width  : '410';
	$bn['bn_height'] = $bn_height ? $bn_height : '410';
	$bn['bn_code']	 = $bn_code   ? $bn_code   : '1';
} else if($w == "u") {
	$bn	= sql_fetch("select * from shop_banner_intro where bn_id='$bn_id'");
    if(!$bn[bn_id])
        alert("존재하지 않은 배너 입니다.");
}

include "./design/web_intro_table.php";

$frm_submit = '<div class="btn_confirm">
	<input type="submit" value="저장" class="btn_large" accesskey="s">
	<a href="design.php?code=intro'.$qstr.'&page='.$page.'" class="btn_large bx-white marl3">목록</a>'.PHP_EOL;
if($w == 'u') {
	$frm_submit .= '<a href="design.php?code=intro_form" class="btn_large bx-red marl3">추가</a>'.PHP_EOL;
}
$frm_submit .= '</div>';
?>

<form name="fbanner" method="post" onsubmit="return fbannerform_submit(this)" enctype="MULTIPART/FORM-DATA">
<input type="hidden" name="w" value="<?php echo $w;?>">
<input type="hidden" name="sfl" value="<?php echo $sfl;?>">
<input type="hidden" name="stx" value="<?php echo $stx;?>">
<input type="hidden" name="page" value="<?php echo $page;?>">
<input type="hidden" name="bn_id" value="<?php echo $bn_id;?>">

<div class="tbl_frm02 mart10">
	<table>
	<colgroup>
		<col width="140px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th>배너파일</th>
		<td>
			<input type="file" name="bn_file">
			<?php
			$bimg_str = "";
			$bimg = TW_DATA_PATH.'/intro/'.$bn['bn_file'];
			if(is_file($bimg) && $bn['bn_file']) {
				$size = @getimagesize($bimg);
				if($size[0] && $size[0] > 700)
					$width = 700;
				else
					$width = $size[0];

				$bimg = TW_DATA_URL.'/intro/'.$bn['bn_file'];

				echo '<input type="checkbox" name="bn_file_del" value="'.$bn['bn_file'].'" id="bn_file_del"> <label for="bn_file_del">삭제</label>';
				$bimg_str = '<img src="'.$bimg.'" width="'.$width.'">';
			}
			if($bimg_str) {
				echo '<div class="banner_or_img">'.$bimg_str.'</div>';
			}
			?>
		</td>
	</tr>
	<tr>
		<th>링크주소</th>
		<td>
			<input class="frm_input w325" type="text" name="bn_link" value="<?php echo $bn[bn_link];?>">
			<span class='fc_197 mart7'>예) /shop/view.php?index_no=7</span>
		</td>
	</tr>
	<tr>
		<th>링크유형</th>
		<td>
		<select name="bn_target">
			<option <?php echo get_selected($bn['bn_target'],"_self"); ?> value="_self">현재창</option>
			<option <?php echo get_selected($bn['bn_target'],"_blank"); ?> value="_blank">새창</option>
		</select>
		</td>
	</tr>
	<tr>
		<th>배너크기</td>
		<td>
			<input class="frm_input w70" type="text" name="bn_width" value="<?php echo $bn[bn_width];?>">px
			<input class="frm_input w70 marl10" type="text" name="bn_height" value="<?php echo $bn[bn_height];?>">px
		</td>
	</tr>
	<tr>
		<th>배너코드</th>
		<td>
			<select name="bn_code">
			<?php
			for($i=1; $i<=10; $i++) {
				echo "<option value='$i' ".get_selected($bn['bn_code'], $i).">$i</option>";
			}
			?>
			</select>
		</td>
	</tr>
	<tr>
		<th>출력여부</th>
		<td>
			<input type="checkbox" name="bn_use" id="bn_use" value="1" <?php echo get_checked($bn['bn_use'], "1"); ?>> <label for="bn_use">배너감춤</label>
		</td>
	</tr>
	</tbody>
	</table>
</div>

<?php echo $frm_submit; ?>
</form>

<script>
function fbannerform_submit(f) {
	f.action = "./design/web_intro_form_update.php";
    return true;
}
</script>