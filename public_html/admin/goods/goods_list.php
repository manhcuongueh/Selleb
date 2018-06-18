<?php
if(!defined('_TUBEWEB_')) exit;

include_once(TW_ADMIN_PATH.'/goods/goods_sub.php');
?>

<form name="fgoodslist" id="fgoodslist" method="post">
<input type="hidden" name="q1" value="<?php echo $q1; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">

<div class="local_ov mart30">
	전체 : <b class="fc_red"><?php echo number_format($total_count);?></b> 건 조회
	<span class="ov_a">
		<select id="page_rows" onchange="location='<?php echo "{$_SERVER['SCRIPT_NAME']}?{$q1}&page=1";?>&page_rows='+this.value;">
			<?php echo option_selected('30',  $page_rows, '30줄 정렬'); ?>
			<?php echo option_selected('50',  $page_rows, '50줄 정렬'); ?>
			<?php echo option_selected('100', $page_rows, '100줄 정렬'); ?>
			<?php echo option_selected('150', $page_rows, '150줄 정렬'); ?>
		</select>
	</span>
</div>
<div class="local_frm01">
	<?php echo $btn_frmline; ?>
</div>

<div class="tbl_head02">
	<table id="sodr_list" class="tablef">
	<colgroup>
		<col width="50px">
		<col width="50px">
		<col width="60px">
		<col width="120px">
		<col>
		<col>
		<col width="80px">
		<col width="80px">
		<col width="90px">
		<col width="90px">
		<col width="90px">
		<col width="90px">
		<col width="60px">
		<col width="60px">
	</colgroup>
	<thead>
	<tr>
		<th rowspan="2"><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form);"></th>
		<th rowspan="2">번호</th>
		<th rowspan="2">이미지</th>
		<th><?php echo subject_sort_link('a.gcode',$q2); ?>상품코드</a></th>		
		<th colspan="2"><?php echo subject_sort_link('a.gname',$q2); ?>상품명</a></th>
		<th><?php echo subject_sort_link('a.reg_time',$q2); ?>최초등록일</a></th>
		<th><?php echo subject_sort_link('a.isopen',$q2); ?>진열</a></th>
		<th colspan="4" class="th_bg">가격정보</th>
		<th rowspan="2"><?php echo subject_sort_link('a.rank',$q2); ?>순위</a></th>
		<th rowspan="2">관리</th>
	</tr>
	<tr class="rows">
		<th><?php echo subject_sort_link('a.mb_id',$q2); ?>업체코드</a></th>
		<th>업체명</th>
		<th>카테고리</th>
		<th><?php echo subject_sort_link('a.update_time',$q2); ?>최근수정일</a></th>
		<th><?php echo subject_sort_link('a.stock_qty',$q2); ?>재고</a></th>
		<th class="th_bg"><?php echo subject_sort_link('a.saccount',$q2); ?>시중가</a></th>
		<th class="th_bg"><?php echo subject_sort_link('a.daccount',$q2); ?>공급가</a></th>
		<th class="th_bg"><?php echo subject_sort_link('a.account',$q2); ?>판매가</a></th>
		<th class="th_bg"><?php echo subject_sort_link('a.gpoint',$q2); ?>적립금</a></th>
	</tr>
	</thead>
	<tbody>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {		
		$gs_id = $row['index_no'];

		if($row['stock_mod'])
			$stockQty = number_format($row['stock_qty']);
		else
			$stockQty = '<span class="txt_false">무제한</span>';		

		$bg = 'list'.$i%2;
	?>
	<tr class="<?php echo $bg; ?>">
		<td rowspan="2">
			<input type="checkbox" name="chk[]" value="<?php echo $i; ?>">
			<input type="hidden" name="gs_id[<?php echo $i; ?>]" value="<?php echo $gs_id; ?>">
		</td>
		<td rowspan="2"><?php echo $num--; ?></td>
		<td rowspan="2"><a href="<?php echo TW_SHOP_URL;?>/view.php?index_no=<?php echo $gs_id; ?>" target="_blank"><?php echo get_it_image($gs_id, $row['simg1'], 40, 40); ?></a></td>
		<td><?php echo $row['gcode']; ?></td>
		<td colspan="2" class="tal"><?php echo get_text($row['gname']); ?></td>
		<td><?php echo substr($row['reg_time'],2,8);?></td>
		<td><?php echo $ar_isopen[$row['isopen']];?></td>
		<td rowspan="2" class="tar"><?php echo number_format($row['saccount']);?></td>
		<td rowspan="2" class="tar"><?php echo number_format($row['daccount']);?></td>
		<td rowspan="2" class="tar"><?php echo number_format($row['account']);?></td>
		<td rowspan="2" class="tar"><?php echo number_format($row['gpoint']);?></td>
		<td rowspan="2"><input type="text" class="frm_input" name="rank[<?php echo $i; ?>]" value='<?php echo $row['rank']; ?>'></td>
		<td rowspan="2"><a href="goods.php?code=form&w=u&gs_id=<?php echo $gs_id.$qstr; ?>&page=<?php echo $page; ?>&bak=<?php echo $code; ?>" class="btn_small">수정</a></td>
	</tr>
	<tr class="<?php echo $bg; ?>">
		<td class="fc_00f"><?php echo $row['mb_id']; ?></td>
		<td class="tal txt_succeed"><?php echo get_seller_name($row['mb_id']); ?></td>
		<td class="tal txt_succeed"><?php echo get_cgy_info($row);?></td>
		<td class="fc_00f"><?php echo substr($row['update_time'],2,8);?></td>
		<td><?php echo $stockQty; ?></td>
	</tr>
	<?php 
	}
	if($i==0)
		echo '<tr><td colspan="14" class="empty_table">자료가 없습니다.</td></tr>';
	?>
	</tbody>
	</table>
</div>
<div class="local_frm02">
	<?php echo $btn_frmline; ?>
</div>
</form>

<?php if($total_count > 0) { ?>
<div class="btn_confirm">
	<?php echo pageing($page, $total_page, $total_count, "{$_SERVER['SCRIPT_NAME']}?$q1&page="); ?>
</div>
<?php } ?>

<script>
function check_all(f)
{
    var chk = document.getElementsByName("chk[]");

    for (i=0; i<chk.length; i++)
        chk[i].checked = f.chkall.checked;
}

function btn_check(act)
{
	var f = document.fgoodslist;

    if(act == "delete") // 선택삭제
    {
        f.action = './goods/goods_list_delete.php';
        str = "삭제";
    }
	else if(act == "rank") // 선택순위수정
    {
        f.action = './goods/goods_list_rankupdate.php';
        str = "수정";
    }
	else if(act == "copy") // 선택상품복사
    {
        f.action = './goods/goods_list_copyupdate.php';
        str = "복사";
    }
    else
        return;

    var chk = document.getElementsByName("chk[]");
    var bchk = false;

    for (i=0; i<chk.length; i++)
    {
        if(chk[i].checked)
            bchk = true;
    }

    if(!bchk)
    {
        alert(str + "할 자료를 하나 이상 선택하세요.");
        return;
    }

    if(act == "delete")
    {
        if(!confirm("선택한 자료를 정말 삭제 하시겠습니까?"))
            return;
    }

    f.submit();
}

$(function(){
	<?php if($sel_ca1) { ?>
	$("select#sel_ca1").val('<?php echo $sel_ca1; ?>'); 
	categorychange('<?php echo $sel_ca1; ?>', 'sel_ca2');
	<?php } ?>
	<?php if($sel_ca2) { ?>
	$("select#sel_ca2").val('<?php echo $sel_ca2; ?>'); 
	categorychange('<?php echo $sel_ca2; ?>', 'sel_ca3');
	<?php } ?>
	<?php if($sel_ca3) { ?>
	$("select#sel_ca3").val('<?php echo $sel_ca3; ?>'); 
	categorychange('<?php echo $sel_ca3; ?>', 'sel_ca4');
	<?php } ?>
	<?php if($sel_ca4) { ?>
	$("select#sel_ca4").val('<?php echo $sel_ca4; ?>'); 
	categorychange('<?php echo $sel_ca4; ?>', 'sel_ca5');
	<?php } ?>
	<?php if($sel_ca5) { ?>
	$("select#sel_ca5").val('<?php echo $sel_ca5; ?>'); 
	<?php } ?>

	// 날짜 검색 : TODAY MAX값으로 인식 (maxDate: "+0d")를 삭제하면 MAX값 해제
	$("#j_sdate,#j_ddate").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
});
</script>
