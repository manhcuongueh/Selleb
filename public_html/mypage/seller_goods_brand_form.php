<?php
if(!defined('_TUBEWEB_')) exit;

$pg_title = "브랜드 수정";
include_once("./admin_head.sub.php");

if($w == "u") {
	$br = sql_fetch("select * from shop_brand where br_id='$br_id'");
    if(!$br['br_id'])
        alert("자료가 존재하지 않습니다.");
}
?>

<h2>브랜드 수정</h2>
<form name="fregform" method="post" onsubmit="return fregform_submit(this);" enctype="MULTIPART/FORM-DATA">
<input type="hidden" name="w" value="<?php echo $w; ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
<input type="hidden" name="stx" value="<?php echo $stx; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">
<input type="hidden" name="br_id" value="<?php echo $br_id; ?>">

<div class="tbl_frm01">
	<table>
	<colgroup>
		<col width="100px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">브랜드명 (KOR)</th>
		<td><input type='text' name='br_name' value="<?php echo $br['br_name']; ?>" required itemname="브랜드명 (KOR)" class="frm_input w325"></td>
	</tr>
	<tr>
		<th scope="row">브랜드명 (ENG)</th>
		<td><input type='text' name='br_name_eng' value="<?php echo $br['br_name_eng']; ?>" itemname="브랜드명 (ENG)" class="frm_input w325"></td>
	</tr>
	<tr>
		<th scope="row">브랜드 URL</th>
		<td><input type='text' value="/shop/brandlist.php?br_id=<?php echo $br_id; ?>" readonly class="frm_input list1 w325"> <a href="/shop/brandlist.php?br_id=<?php echo $br_id; ?>" target="_blank" class="btn_small grey">브랜드 바로가기</a></td>
	</tr>
	<tr>
		<th scope="row">브랜드로고</th>
		<td>
			<input type="file" name="br_logo" id="br_logo"> 사이즈(128픽셀 * 40픽셀)
			<?php
			$file = TW_DATA_PATH.'/brand/'.$br['br_logo'];
			if(is_file($file) && $br['br_logo']) {
				$br_logo = TW_DATA_URL.'/brand/'.$br['br_logo'];
			?>
			<input type="checkbox" name="br_logo_del" value="<?php echo $br['br_logo']; ?>" id="br_logo_del">
			<label for="br_logo_del">삭제</label>
			<div class="banner_or_img"><img src="<?php echo $br_logo; ?>"></div>
			<?php } ?>
		</td>
	</tr>
	<tr>
		<th scope="row">등록일</th>
		<td><?php echo $br['br_wdate']; ?></td>
	</tr>
	<?php if(substr($br['br_udate'],0,1) > 0) { ?>
	<tr>
		<th scope="row">최근 수정일</th>
		<td><?php echo $br['br_udate']; ?></td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
</div>
<div class="btn_confirm">
	<input type="submit" class="btn_large" accesskey="s" value="저장">
	<a href="page.php?code=seller_goods_brand<?php echo $qstr; ?>&page=<?php echo $page; ?>" class="btn_large bx-white marl3">목록</a>
</div>
</form>

<script>
function fregform_submit(f){
    f.action = "./seller_goods_brand_update.php";
    return true;
}
</script>

<?php
include_once("./admin_tail.sub.php");
?>