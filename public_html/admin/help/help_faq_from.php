<?php
if(!defined('_TUBEWEB_')) exit;

$faq = sql_fetch("select * from shop_faq where index_no='$faq_table'");
?>

<form name="faqform" method="post" onsubmit="return faqform_submit(this);">
<input type="hidden" name="w" value="<?php echo $w;?>">
<input type="hidden" name="sst" value="<?php echo $sst;?>">
<input type="hidden" name="sfl" value="<?php echo $sfl;?>">
<input type="hidden" name="stx" value="<?php echo $stx;?>">
<input type="hidden" name="page" value="<?php echo $page;?>">
<input type="hidden" name="faq_table" value="<?php echo $faq_table?>">

<div class="tbl_frm02">
	<table>
	<colgroup>
		<col width="140px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th>분류</th>
		<td>
			<select name="faq_cate">
				<?php
				$sql = "select * from shop_faq_cate";
				$result = sql_query($sql);
				while($row = sql_fetch_array($result)){
					echo option_selected($row['index_no'], $faq['cate'], $row['catename']);
				}
				?>
			</select>
		</td>
	</tr>
	<tr>
		<th>제목</th>
		<td>
			<input type="text" name="subject" value="<?php echo $faq[subject];?>" required itemname="제목" class="frm_input" size="60">
		</td>
	</tr>
	<tr>
		<th>내용</th>
		<td>
			<?php echo editor_html('memo', get_text($faq['memo'], 0));?>
		</td>
	</tr>
	</tbody>
	</table>
</div>
<div class="btn_confirm">
	<input type="submit" class="btn_large" accesskey="s" value="저장">
	<a href="help.php?code=faq<?php echo $qstr;?>&page=<?php echo $page;?>" class="btn_large bx-white marl3">목록</a>
</div>
</form>

<script>
function faqform_submit(f) {
	<?php echo get_editor_js('memo');?>
	f.action = "/admin/help/help_faq_form_update.php";
    return true;
}
</script>
