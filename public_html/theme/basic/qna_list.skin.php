<?php
if(!defined('_TUBEWEB_')) exit;

include_once($theme_path.'/aside_cs.skin.php');
?>

<div class="rbody">
	<p class="tit_navi">홈 <i class="ionicons ion-ios-arrow-right"></i> 고객센터 <i class="ionicons ion-ios-arrow-right"></i> 1:1 상담문의</p>
	<h2 class="stit">1:1 상담문의</h2>
	<div class="tbl_head02">
		<table class="wfull">
		<colgroup>
			<col width="40">
			<col width="100">
			<col>
			<col width="100">
			<col width="100">
			<col width="80">
		</colgroup>
		<thead>
		<tr>
			<th class="bl_nolne">번호</th>
			<th>분류</th>
			<th>제목</th>
			<th>작성자</th>
			<th>날짜</th>
			<th>상태</th>
		</tr>
		</thead>
		<tbody>
		<?php
		for($i=0; $row=sql_fetch_array($result); $i++) {
			$bg = 'list'.($i%2);
		?>
		<tr class="<?php echo $bg; ?>" align="center">
			<td class="bl_nolne"><?php echo $num--; ?></td>
			<td class="bold"><?php echo $row['catename']; ?></td>
			<td class="td_tal"><a href="./qna_read.php?index_no=<?php echo $row['index_no']; ?>"><?php echo cut_str($row['subject'],60); ?></a></td>
			<td><?php echo $row['mb_id']; ?></td>
			<td><?php echo substr($row['wdate'],0,10); ?></td>
			<td>
				<?php if($row['result_yes']) { ?>
				<a href="javascript:js_qna('<?php echo $i; ?>');" class="fc_197 tu">답변보기</a>
				<?php } else { ?>
				답변대기
				<?php } ?>
			</td>
		</tr>
		<tr id="sod_qa_con_<?php echo $i; ?>" class="sod_qa_con" style="display:none;">
			<td class="bl_nolne tal" colspan="6">
				<?php echo nl2br($row['reply']); ?>
			</td>
		</tr>
		<?php
		}
		if($total_count==0) {
		?>
		<tr><td colspan="6" class="empty_list">내역이 없습니다.</td></tr>
		<?php } ?>
		</tbody>
		</table>
	</div>

	<div class="page_wrap">
		<?php echo pagelist($page, $total_page, $total_count, "{$_SERVER['SCRIPT_NAME']}?page="); ?>
		<div class="rbt_box"><a href="./qna_write.php" class="btn_small wset">상담문의하기</a></div>
	</div>
</div>

<script>
function js_qna(id){
	var $con = $("#sod_qa_con_"+id);
	if($con.is(":visible")) {
		$con.hide();
	} else {
		$(".sod_qa_con:visible").hide();
		$con.show();
	}
}
</script>
