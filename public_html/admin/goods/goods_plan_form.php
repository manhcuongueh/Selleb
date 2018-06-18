<?php
if(!defined('_TUBEWEB_')) exit;

if($w == '') {
	$html_title = ' 등록';
	$pl['pl_use'] = 1;

} else if($w == 'u') {
	$html_title = ' 수정';

	$pl = sql_fetch("select * from shop_plan where pl_no = '{$pl_no}' ");
	if(!$pl['pl_no'])
		alert('자료가 존재하지 않습니다.');
}

$frm_submit = '<div class="btn_confirm">
	<input type="submit" value="저장" class="btn_large" accesskey="s">
	<a href="goods.php?code=plan'.$qstr.'&page='.$page.'" class="btn_large bx-white marl3">목록</a>'.PHP_EOL;
if($w == 'u') {
	$frm_submit .= '<a href="goods.php?code=plan_form" class="btn_large bx-red marl3">추가</a>'.PHP_EOL;
}
$frm_submit .= '</div>';
?>

<h2>기획전 <?php echo $html_title; ?></h2>
<form name="fregform" method="post" onsubmit="return fregform_submit(this);" enctype="MULTIPART/FORM-DATA">
<input type="hidden" name="w" value="<?php echo $w; ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
<input type="hidden" name="stx" value="<?php echo $stx; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">
<input type="hidden" name="pl_no" value="<?php echo $pl_no; ?>">

<div class="tbl_frm01">
	<table>
	<colgroup>
		<col width="140px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">기획전명</th>
		<td><input type='text' name='pl_name' value="<?php echo $pl['pl_name']; ?>" required class="frm_input required w325"></td>
	</tr>
	<?php if($w == 'u') { ?>
	<tr>
		<th scope="row">기획전 URL</th>
		<td><input type='text' value="/shop/planlist.php?pl_no=<?php echo $pl_no; ?>" readonly class="frm_input list1 w325"> <a href="/shop/planlist.php?pl_no=<?php echo $pl_no; ?>" target="_blank" class="btn_small grey">기획전 바로가기</a></td>
	</tr>
	<?php } ?>
	<tr>
		<th scope="row">노출여부</th>
		<td><input type="checkbox" name="pl_use" value='1' id="pl_use"<?php echo get_checked($pl['pl_use'], "1"); ?>> <label for="pl_use">노출함</label></td>
	</tr>
	<tr>
		<th scope="row">관련상품코드</th>
		<td>
			<textarea name='pl_it_code' class="frm_input wfull" style="height:350px;resize:none;"><?php echo $pl['pl_it_code']; ?></textarea>
			<?php echo help('※ 엔터로 구분해서 입력해주세요.'); ?>
		</td>
	</tr>	
	<tr>
		<th scope="row">목록이미지</th>
		<td>
			<input type="file" name="pl_limg" id="pl_limg">
			<?php
			$bimg_str = "";
			$bimg = TW_DATA_PATH.'/plan/'.$pl['pl_limg'];
			if(is_file($bimg) && $pl['pl_limg']) {
				$size = @getimagesize($bimg);
				if($size[0] && $size[0] > 700)
					$width = 700;
				else
					$width = $size[0];

				$bimg = TW_DATA_URL.'/plan/'.$pl['pl_limg'];

				echo '<input type="checkbox" name="pl_limg_del" value="'.$pl['pl_limg'].'" id="pl_limg_del"> <label for="pl_limg_del">삭제</label>';
				$bimg_str = '<img src="'.$bimg.'" width="'.$width.'">';
			}
			if($bimg_str) {
				echo '<div class="banner_or_img">'.$bimg_str.'</div>';
			}
			echo help('사이즈(318픽셀 * 159픽셀)');
			?>
		</td>
	</tr>
	<tr>
		<th scope="row">상단이미지</th>
		<td>
			<input type="file" name="pl_bimg" id="pl_bimg">
			<?php
			$bimg_str = "";
			$bimg = TW_DATA_PATH.'/plan/'.$pl['pl_bimg'];
			if(is_file($bimg) && $pl['pl_bimg']) {
				$size = @getimagesize($bimg);
				if($size[0] && $size[0] > 700)
					$width = 700;
				else
					$width = $size[0];

				$bimg = TW_DATA_URL.'/plan/'.$pl['pl_bimg'];

				echo '<input type="checkbox" name="pl_bimg_del" value="'.$pl['pl_bimg'].'" id="pl_bimg_del"> <label for="pl_bimg_del">삭제</label>';
				$bimg_str = '<img src="'.$bimg.'" width="'.$width.'">';
			}
			if($bimg_str) {
				echo '<div class="banner_or_img">'.$bimg_str.'</div>';
			}
			echo help('사이즈(1000픽셀 * auto)');
			?>
		</td>
	</tr>	
	</tbody>
	</table>
</div>

<?php echo $frm_submit; ?>
</form>

<script>
function fregform_submit(f){
    f.action = "./goods/goods_plan_form_update.php";
    return true;
}
</script>
