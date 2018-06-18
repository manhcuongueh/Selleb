<?php
if(!defined('_TUBEWEB_')) exit;

$query_string = "index_no=$index_no".$qstr;
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_goods_qa ";
$sql_search = " where gs_id='$index_no' ";

if($sfl) {
    $sql_search .= " and (iq_ty like '$sfl%') ";
}

if(!$orderby) {
    $filed = "iq_id";
    $sod = "desc";
} else {
	$sod = $orderby;
}

$sql_order = " order by $filed $sod";

$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 30;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select * $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);
?>

<a name="it_qa"></a>
<form name="f_search" method="post">
<table class="wfull marb5">
<colgroup>
	<col width="30%">
	<col width="80%">
</colgroup>
<tr>
	<td class="tal fs11">총 <b class="fc_red"><?php echo $total_count; ?></b>개의 문의가 있습니다.</td>
	<td class='tar'>
		<select name='sfl' onchange="location='<?php echo TW_SHOP_URL; ?>/view.php?<?php echo $q2; ?>&sfl='+this.value" class="frm_select">
			<option <?php echo get_selected($sfl, ''); ?> value=''>문의유형(전체)</option>
			<option <?php echo get_selected($sfl, '상품'); ?> value='상품'>상품</option>
			<option <?php echo get_selected($sfl, '배송'); ?> value='배송'>배송</option>
			<option <?php echo get_selected($sfl, '반품/환불/취소'); ?> value='반품/환불/취소'>반품/환불/취소</option>
			<option <?php echo get_selected($sfl, '교환/변경'); ?> value='교환/변경'>교환/변경</option>
			<option <?php echo get_selected($sfl, '기타'); ?> value='기타'>기타</option>
		</select>
	</td>
</tr>
</table>
</form>

<div class="tbl_head01">
<table class="wfull">
<colgroup>
	<col width='8%'>
	<col width='14%'>
	<col width='6%'>
	<col width='2%'>
	<col>
	<col width='12%'>
	<col width='14%'>
</colgroup>
<thead>
<tr>
	<th>번호</th>
	<th>문의유형</th>
	<th colspan=3>문의/답변</th>
	<th>작성자</th>
	<th>작성일</th>
</tr>
</thead>
</table>
<?php
for($i=0; $row=sql_fetch_array($result); $i++) {
	$iq_subject = cut_str($row['iq_subject'], 66);

	if(substr($row['iq_time'],0,10) == $time_ymd) {
		$iq_subject .= '&nbsp;<img src="'.TW_IMG_URL.'/icon/icon_new.gif" alt="new">';
	}

	$is_secret = false;
	if($row['iq_secret']) {
		$icon_secret = '<img src="'.TW_IMG_URL.'/icon/icon_secret.jpg" alt="비밀글">';

		if(is_admin() || $member['id' ] == $row['mb_id']) {
			$iq_answer = $row['iq_answer'];
		} else {
			$iq_answer = "";
			$is_secret = true;
		}
	} else {
		$icon_secret = "";
		$iq_answer = $row['iq_answer'];
	}

	if($row['iq_answer']) {
		$icon_answer = '<img src="'.TW_IMG_URL.'/icon/icon_answer.jpg" alt="답변완료">';
	} else {
		$icon_answer = '<img src="'.TW_IMG_URL.'/icon/icon_standby.jpg" alt="미답변">';
	}

	$len = strlen($row['mb_id']);
	$str = substr($row['mb_id'],0,3);
	$mb_id = $str.str_repeat("*",$len - 3);

	$hash = md5($row['iq_id'].$row['iq_time'].$row['iq_ip']);

	$bg = 'list'.$i%2;
?>
<table class="wfull">
<colgroup>
	<col width='8%'>
	<col width='14%'>
	<col width='6%'>
	<col width='2%'>
	<col>
	<col width='10%'>
	<col width='14%'>
</colgroup>
<tbody>
<tr class="<?php echo $bg; ?>" height="30" align="center">
	<td><?php echo $num--; ?></td>
	<td><?php echo $row['iq_ty']; ?></td>
	<td><?php echo $icon_answer; ?></td>
	<td><?php echo $icon_secret; ?></td>
	<td class='tal padl5'>
		<?php
		if(!$is_secret) { echo "<a href='javascript:void(0);' onclick=\"js_qna('".$i."')\">"; }
		echo $iq_subject;
		if(!$is_secret) { echo "</a>"; }
		?>
	</td>
	<td><?php echo $mb_id; ?></td>
	<td><?php echo $row['iq_time']; ?></td>
</tr>
</tbody>
</table>

<div id="sod_qa_con_<?php echo $i; ?>" class="sod_qa_con" style="display:none;">
	<table class="wfull">
	<colgroup>
		<col width="16">
		<col>
	</colgroup>
	<tr>
		<td class="vat tal padt10 padb10 padl5 padr5"><img src="<?php echo TW_IMG_URL; ?>/sub/FAQ_Q.gif" align='absmiddle'></td>
		<td class="vat tal padt10 padb10 padl5 padr5">
			<?php echo nl2br($row['iq_question']); ?>
			<?php if(is_admin() || $member['id' ] == $row['mb_id'] && !$iq_answer) { ?>
			<div class='padt10'><a href='<?php echo TW_SHOP_URL; ?>/qaform.php?gs_id=<?php echo $row['gs_id']; ?>&amp;iq_id=<?php echo $row['iq_id']; ?>&amp;w=u' onclick="openwindow(this,'upd','600','530','yes');return false"><span class="tu">수정</span></a>&nbsp;<a href="<?php echo TW_SHOP_URL; ?>/qaform_update.php?gs_id=<?php echo $row['gs_id']; ?>&amp;iq_id=<?php echo $row['iq_id']; ?>&amp;w=d&amp;hash=<?php echo $hash; ?>" class="itemqa_delete"><span class="tu">삭제</span></a></div>
			<?php } ?>
		</td>
	</tr>
	<?php if($iq_answer) { ?>
	<tr>
		<td class="vat tal padt10 padb10 padl5 padr5"><img src="<?php echo TW_IMG_URL; ?>/sub/FAQ_A.gif" align='absmiddle'></td>
		<td class="vat tal padt10 padb10 padl5 padr5"><?php echo nl2br($iq_answer); ?></td>
	</tr>
	<?php } ?>
	</table>
</div>
<?php
}
if($total_count==0){
?>
<div class="empty_list bb">문의 내역이 없습니다.</div>
<?php } ?>
</div>

<table class="wfull mart10 marb30">
<tr>
	<td class="tal">
		<?php
		echo pagelist($page, $total_page, $total_count, "{$_SERVER['SCRIPT_NAME']}?$q1&page=");
		?>
	</td>
	<td class="tar">
		<a href="<?php echo TW_SHOP_URL; ?>/qaform.php?gs_id=<?php echo $index_no; ?>" onclick="openwindow(this,'qaform','600','600','yes');return false" class="btn_lsmall grey">상품Q&A작성</a>
		<a href="<?php echo TW_BBS_URL; ?>/qna_list.php" target="_blank" class="btn_lsmall grey">고객센터 문의하기</a>
	</td>
</tr>
</table>

<script>
function js_qna(id){
	var $con = $("#sod_qa_con_"+id);
	if($con.is(":visible")) {
		$con.hide(200);
	} else {
		$(".sod_qa_con:visible").hide();
		$con.show(200);
	}
}

$(function(){
    $(".itemqa_delete").click(function(){
        return confirm("정말 삭제 하시겠습니까?\n\n삭제후에는 되돌릴수 없습니다.");
    });
});
</script>
