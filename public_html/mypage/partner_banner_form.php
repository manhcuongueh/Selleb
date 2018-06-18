<?php
if(!defined('_TUBEWEB_')) exit;

$pg_title = "기타배너 관리";
include_once("./admin_head.sub.php");

if($w == "") {
	$bn['bn_width'] = $bn_width ? $bn_width : '1000';
	$bn['bn_height'] = $bn_height ? $bn_height : '70';
	$bn['bn_code'] = $bn_code ? $bn_code : '1';
} else if($w == "u") {
	$bn	= sql_fetch("select * from shop_banner where index_no='$ba_table'");
    if(!$bn['index_no'])
        alert("존재하지 않은 배너 입니다.");
}

$frm_submit = '<div class="btn_confirm">
	<input type="submit" value="저장" class="btn_large" accesskey="s">
	<a href="page.php?code=partner_banner_list&page='.$page.'" class="btn_large bx-white marl3">목록</a>'.PHP_EOL;
if($w == 'u')
	$frm_submit .= '<a href="page.php?code=partner_banner_form" class="btn_large bx-red marl3">추가</a>'.PHP_EOL;
$frm_submit .= '</div>';

// 코드표 테이블
$page_dir = TW_ADMIN_PATH.'/design';
@include_once($page_dir."/table.{$super['theme']}.php");
@include_once($page_dir."/table.{$super['mobile_theme']}.mobile.php");
?>

<form name="fbanner" method="post" onsubmit="return fbannerform_submit(this)" enctype="MULTIPART/FORM-DATA">
<input type="hidden" name="w" value="<?php echo $w;?>">
<input type="hidden" name="page"  value="<?php echo $page;?>">
<input type="hidden" name="ba_table" value="<?php echo $ba_table;?>">

<h2>배너정보</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col width="140px">
		<col> 
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">배너파일</th>
		<td>
			<input type="file" name="bn_file">
			<?php
			$bimg_str = "";
			$bimg = TW_DATA_PATH.'/banner/'.$bn['bn_file'];
			if(is_file($bimg) && $bn['bn_file']) {
				$size = @getimagesize($bimg);
				if($size[0] && $size[0] > 700)
					$width = 700;
				else
					$width = $size[0];

				$bimg = TW_DATA_URL.'/banner/'.$bn['bn_file'];

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
		<th scope="row">링크주소</th>
		<td>
			<input type='text' name='bn_link' value='<?php echo $bn['bn_link'];?>' class="frm_input w325">
			<?php echo help('예시) /shop/view.php?index_no=1'); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">링크유형</th>
		<td>
			<select name="bn_target">
				<option <?php echo get_selected($bn['bn_target'],"_self"); ?> value="_self">현재창</option>
				<option <?php echo get_selected($bn['bn_target'],"_blank"); ?> value="_blank">새창</option>
			</select>
		</td>
	</tr>
	<tr>
		<th scope="row">배너크기</th>
		<td>
			<input type="text" name='bn_width' value='<?php echo $bn['bn_width'];?>' class="frm_input w70">px
			<input type="text" name='bn_height' value='<?php echo $bn['bn_height'];?>' class="frm_input w70 marl10">px
		</td>
	</tr>
	<tr>
		<th scope="row">백그라운드 컬러</th>
		<td><input type='text' name='bn_bg' value='<?php echo $bn['bn_bg'];?>' class="frm_input w100"> <span class="fc_197 marl5">예) #FFFFFF</span></td>
	</tr>
	<tr>
		<th scope="row">배너 문구</th>
		<td><input type='text' name='bn_text' value='<?php echo $bn['bn_text'];?>' class="frm_input w470"></td>
	</tr>
	<tr>
		<th scope="row">배너코드</th>
		<td>
			<select name='bn_code'>
			<?php
			for($i=1; $i<=200; $i++) {
				echo "<option value='$i' ".get_selected($bn['bn_code'], $i).">$i</option>";
			}
			?>
			</select>
		</td>
	</tr>
	<tr>
		<th scope="row">노출여부</th>
		<td>
			<input type="checkbox" name="bn_use" id="bn_use" value='1' <?php echo get_checked($bn['bn_use'], "1"); ?>> <label for="bn_use">노출안함</label>
		</td>
	</tr>
	</tbody>
	</table>
</div>

<?php echo $frm_submit; ?>
</form>

<script>
function fbannerform_submit(f) {
	f.action = "./partner_banner_form_update.php";
    return true;
}

$(function() {
	$("select[name=bn_code]").on("change", function() {
		var no = $(this).val();
		var info = $("#sit_size_"+no).text().split(' * ');
		var w = info[0];
		var h = info[1].replace(/[^0-9]/g, '');
		$("input[name=bn_width]").val(w);
		$("input[name=bn_height]").val(h);
	});
});
</script>

<?php
include_once("./admin_tail.sub.php");
?>