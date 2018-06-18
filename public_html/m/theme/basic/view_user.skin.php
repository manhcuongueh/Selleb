<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<h2 class="pop_title">
	<?php echo $tb['title']; ?> <span class="fc_red">(<?php echo number_format($total_count); ?>)</span>
	<a href="javascript:cl_list();" class="btn_small bx-white">전체상품보기</a>
</h2>

<div class="m_post">
	<table class="tbl_post">
	<colgroup>
		<col width="80">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<td class="mi_dt"><?php echo get_it_image($gs_id, $gs['simg1'], 80, 80); ?></td>
		<td class="mi_bt">
			<?php echo get_text($gs['gname']); ?>
			<p class="bold mart5"><?php echo get_price($gs_id); ?></p>
		</td>
	</tr>
	</tbody>
	</table>

	<?php
	echo "<ul class=lst_w>";
	for($i=0; $row=sql_fetch_array($result); $i++)
	{
		$len = strlen($row['writer_s']);
		$str = substr($row['writer_s'],0,3);
		$tmp_name  = $str.str_repeat("*",$len - 3);
		$tmp_date  = date("Y-m-d", $row['wdate']);
		$tmp_score = $arr_sco[$row['score']];

		$hash = md5($row['index_no'].$row['wdate'].$row['writer_s']);

		echo "<li class='lst'><span class=lst_post>$row[memo]</span>";
		echo "<span class='lst_h'><span class='fc_255'>$tmp_score</span> ";
		echo "<span class='fc_999'> / $tmp_name / $tmp_date";

		if(is_admin() || ($member['id'] == $row['writer_s'])) {
			echo "&nbsp;&nbsp;&nbsp;<a href=\"javascript:window.open('./view_user_form.php?gs_id=$row[gs_id]&amp;me_id=$row[index_no]&amp;w=u');\" /><span class='under fc_blk'>수정</span></a>&nbsp;&nbsp;&nbsp;<a href=\"./view_user_form_update.php?gs_id=$row[gs_id]&amp;me_id=$row[index_no]&amp;w=d&amp;hash=$hash&amp;p=1\" class='itemqa_delete'><span class='under fc_blk'>삭제</span></a>";
		}
		echo "</span></span>";
		echo "</li>";
	}

	if($i == 0) {
		echo "<li class=lst><span class='lst_a tac'>자료가 없습니다</span></li>";
	}

	echo "</ul>";

	if($i > 0) {
	?>

	<div class="mart10 marb25">
		<?php echo pageing($page, $total_page, $total_count, "{$_SERVER['SCRIPT_NAME']}?$q1&page="); ?>
	</div>
	<?php } ?>

	<div class="tac mart10">
		<a href="javascript:window.open('./view_user_form.php?gs_id=<?php echo $gs_id; ?>');" class="btn_medium">구매후기쓰기</a>
		<a href="javascript:window.close();" class="btn_medium bx-white">창닫기</a>
	</div>
</div>

<script>
function cl_list(){
	opener.location.href = './list.php?ca_id=<?php echo $ca[gcate]; ?>';
	window.close();
}

// 삭제
$(function(){
    $(".itemqa_delete").click(function(){
        return confirm("정말 삭제 하시겠습니까?\n\n삭제후에는 되돌릴수 없습니다.");
    });
});
</script>
