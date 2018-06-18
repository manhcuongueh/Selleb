<?php
define('_PURENESS_', true);
include_once("./_common.php");

include_once(TW_ADMIN_PATH."/admin_head.php");

$target_table = 'shop_cate_'.$member['id'];

$srcfile = TW_DATA_PATH.'/category/'.$member['id'];
$upload_file = new upload_files($srcfile);
$ca_no = $_REQUEST['index_no'];

if(!is_dir($srcfile)) {
	@mkdir($srcfile, TW_DIR_PERMISSION);
	@chmod($srcfile, TW_DIR_PERMISSION);
}

if($w == 'u') {
	check_demo();

	$ca = sql_fetch("select * from {$target_table} where index_no='$ca_no'");

	$sql_commend = '';

	if($img_name_del) {
		$upload_file->del($img_name_del);
		$sql_commend .= " , img_name = '' ";
	}
	if($img_name_over_del) {
		$upload_file->del($img_name_over_del);
		$sql_commend .= " , img_name_over = '' ";
	}
	if($img_head_del) {
		$upload_file->del($img_head_del);
		$sql_commend .= " , img_head = '' ";
	}
	if($_FILES['img_name']['name']) {
		$upload_file->del($ca['img_name']);
		$img_name = $upload_file->upload($_FILES['img_name']);
		$sql_commend .= " , img_name = '$img_name' ";
	}
	if($_FILES['img_name_over']['name']) {
		$upload_file->del($ca['img_name_over']);
		$img_name_over = $upload_file->upload($_FILES['img_name_over']);
		$sql_commend .= " , img_name_over = '$img_name_over' ";
	}
	if($_FILES['img_head']['name']) {
		$upload_file->del($ca['img_head']);
		$img_head = $upload_file->upload($_FILES['img_head']);
		$sql_commend .= " , img_head = '$img_head' ";
	}

	$len = strlen($ca['catecode']);
	$sql_where = " where SUBSTRING(catecode,1,$len) = '{$ca['catecode']}' ";

	// 본사 카테고리 숨김
	$sql = "update {$target_table} set u_hide='$u_hide' {$sql_where} ";
	sql_query($sql);

	$sql = "update {$target_table}
			   set catename='".trim($catename)."',
				   img_head_url = '".trim($img_head_url)."'
			      {$sql_commend}
			 where index_no='$ca_no' ";
	sql_query($sql);

	goto_url("./partner_category_mod.php?index_no=$ca_no");
}

$ca = sql_fetch("select * from {$target_table} where index_no='$ca_no'");
?>

<form name="fcgyform" method="post" onsubmit="return fcgyform_submit(this);" enctype="MULTIPART/FORM-DATA">
<input type="hidden" name="w" value="u">
<input type="hidden" name="index_no" value="<?php echo $ca_no; ?>">
<input type="hidden" name="upcate" value="<?php echo $ca['catecode']; ?>">

<div class="tbl_frm02 mart10">
	<table>
	<colgroup>
		<col width="130">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th>카테고리 소속</th>
		<td>
			<?php
			$str = '';
			$ca_len = strlen($ca['catecode']);
			for($i=1;$i<=($ca_len/3);$i++) {
				$tmp = substr($ca['catecode'],0,($i*3));
				$row = sql_fetch("select * from {$target_table} where catecode='$tmp' ");
				$len = strlen($row['catecode']);
				if($len == 3) {
					$str .= $row['catename'];
				} else {
					$str .= " > ".$row['catename'];
				}
			}
			echo '<b>'.$str.'</b>';
			?>
		</td>
	</tr>
	<tr>
		<th>카테고리명</th>
		<td>
			<input type="text" name="catename" value="<?php echo $ca['catename']; ?>" required itemname="카테고리명" class="frm_input required" size="50">
			<input type="checkbox" name="u_hide" id="u_hide" value="1" <?php echo ($ca['u_hide'])?"checked='checked'":""; ?>> <label for="u_hide">카테고리 숨김</label>
		</td>
	</tr>
	<?/*?>
	<tr>
		<th>카테고리 아이콘</th>
		<td>
			<input type="file" name="img_name">
			<?php
			$mimg_str = "";
			$mimg = $srcfile.'/'.$ca['img_name'];
			if(is_file($mimg) && $ca['img_name']) {
				$size = @getimagesize($mimg);
				if($size[0] && $size[0] > 300)
					$width = 300;
				else
					$width = $size[0];

				echo '<input type="checkbox" name="img_name_del" value="'.$ca['img_name'].'" id="img_name_del"> <label for="img_name_del">삭제</label>';
				$mimg_str = '<img src="'.$mimg.'" width="'.$width.'">';
			}
			if($mimg_str) {
				echo '<div class="banner_or_img">'.$mimg_str.'</div>';
			}
			?>	
		</td>
	</tr>
	<tr>
		<th>카테고리 아이콘 (ON)</th>
		<td>
			<input type="file" name="img_name_over">
			<?php
			$timg_str = "";
			$timg = $srcfile.'/'.$ca['img_name_over'];
			if(is_file($timg) && $ca['img_name_over']) {
				$size = @getimagesize($timg);
				if($size[0] && $size[0] > 300)
					$width = 300;
				else
					$width = $size[0];

				echo '<input type="checkbox" name="img_name_over_del" value="'.$ca['img_name_over'].'" id="img_name_over_del"> <label for="img_name_over_del">삭제</label>';
				$timg_str = '<img src="'.$timg.'" width="'.$width.'">';
			}
			if($timg_str) {
				echo '<div class="banner_or_img">'.$timg_str.'</div>';
			}
			?>	
		</td>
	</tr>
	<?*/?>
	<?php if(strlen($ca['catecode']) == 3) { ?>
	<tr>
		<th>카테고리 상단배너</th>
		<td>
			<input type="file" name="img_head">
			<?php
			$himg_str = "";
			$himg = $srcfile.'/'.$ca['img_head'];
			if(is_file($himg) && $ca['img_head']) {
				$size = @getimagesize($himg);
				if($size[0] && $size[0] > 300)
					$width = 300;
				else
					$width = $size[0];

				echo '<input type="checkbox" name="img_head_del" value="'.$ca['img_head'].'" id="img_head_del"> <label for="img_head_del">삭제</label>';
				$himg_str = '<img src="'.$himg.'" width="'.$width.'">';
			}
			if($himg_str) {
				echo '<div class="banner_or_img">'.$himg_str.'</div>';
			}
			?>	
		</td>
	</tr>
	<tr>
		<th>카테고리 상단배너 링크</th>
		<td><input type="text" name="img_head_url" value="<?php echo $ca['img_head_url']; ?>" class="frm_input" size="50"></td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
</div>
<div class="btn_confirm">
	<input type="submit" value="확인" class="btn_lsmall">
	<button type="button" onClick="cancel('<?php echo $ca_no; ?>')" class="btn_lsmall bx-white">닫기</button>
</div>
</form>

<script>
function fcgyform_submit(f) {
	f.action = "./partner_category_mod.php";
    return true;
}

function cancel(index){
	parent.document.all['co'+index].style.display='none';
}
</script>

<?php
include_once(TW_ADMIN_PATH.'/admin_tail.sub.php');
?>