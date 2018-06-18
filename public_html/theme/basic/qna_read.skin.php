<?php
if(!defined('_TUBEWEB_')) exit;

include_once($theme_path.'/aside_cs.skin.php');
?>

<div class="rbody">
	<p class="tit_navi">홈 <i class="ionicons ion-ios-arrow-right"></i> 고객센터 <i class="ionicons ion-ios-arrow-right"></i> 1:1 상담문의</p>
	<h2 class="stit">1:1 상담문의</h2>
	<div class="tbl_frm01">
		<table class="wfull">
		<colgroup>
			<col width='18%'>
			<col width='82%'>
		</colgroup>
		<tbody>
		<tr>
			<th>제목</th>
			<td><?php echo $qa['subject']; ?></td>
		</tr>
		<tr>
			<th>내용</th>
			<td style="height:150px;vertical-align:top;"><?php echo nl2br($qa['memo']); ?></td>
		</tr>
		</tbody>
		</table>
	</div>

	<?php if($qa['result_yes']) { ?>
	<div class="tbl_frm01 mart10">
		<table class="wfull">
		<colgroup>
			<col width='18%'>
			<col width='82%'>
		</colgroup>
		<tbody>
		<tr>
			<th>답변일 </th>
			<td><?php echo substr($qa['result_date'],0,10); ?></td>
		</tr>
		<tr>
			<th>답변내용 </th>
			<td style="height:150px;vertical-align:top;"><?php echo nl2br($qa['reply']); ?></td>
		</tr>
		</tbody>
		</table>
	</div>
	<?php } ?>

	<div class="mart15 tac">
		<a href="./qna_write.php" class="btn_lsmall marr3">상담문의하기</a>
		<a href="./qna_modify.php?index_no=<?php echo $index_no; ?>" class="btn_lsmall bx-white marr3">수정</a>
		<a href="./qna_list.php" class="btn_lsmall bx-white marr3">목록</a>
		<a href="javascript:del('./qna_read.php?index_no=<?php echo $index_no; ?>&mode=d');" class="btn_lsmall bx-white">삭제</a>
	</div>
</div>

<script>
function del(url) {
	answer = confirm('삭제 하시겠습니까?');
	if(answer==true) {
		location.href = url;
	}
}
</script>