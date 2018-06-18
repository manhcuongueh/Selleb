<?php
if(!defined('_TUBEWEB_')) exit;

include_once(TW_ADMIN_PATH.'/goods/goods_sub.php');
?>

<form name="fgoodslist" id="fgoodslist" method="post">
<input type="hidden" name="q1" value="<?php echo $q1; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">
<input type="hidden" name="token" value="">

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

<h2>판매가 및 시중가 일괄처리</h2>
<form name="frmprice" id="frmprice" method="post" autocomplete="off">
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col width="100px">
		<col>
	</colgroup>
	<tbody>	
	<tr>
		<th scope="row">적용조건</th>
		<td>
			<input type="radio" name="gubun" value="1" id="gubun_1" checked="checked">
			<label for="gubun_1">선택한 상품만 적용</label>
			<input type="radio" name="gubun" value="2" id="gubun_2">
			<label for="gubun_2">검색된 상품 전체(<?php echo number_format($total_count); ?>개)를 적용</label>	
		</td>
	</tr>
	<tr>
		<th scope="row">가격조건</th>
		<td>
			<label for="price_field" class="sound_only">적용기준</label>
			<select name="price_field" id="price_field">
				<option value="account">판매가에서</option>
				<option value="saccount">시중가에서</option>
				<option value="daccount">공급가에서</option>
			</select>
			<label for="price" class="sound_only">적용금액(율)</label>
			<input type="text" name="price" id="price" class="frm_input" size="7">
			<label for="price_type" class="sound_only">적용타입</label>
			<select name="price_type" id="price_type">
				<option value="$">(원)을</option>
				<option value="%">(%)를</option>						
			</select>
			<label for="price_both" class="sound_only">가격 (할인/할증)</label>
			<select name="price_both" id="price_both">
				<option value="down">할인된 가격으로</option>
				<option value="up">할증된 가격으로</option>	
			</select>
			<label for="price_target" class="sound_only">적용대상</label>
			<select name="price_target" id="price_target">
				<option value="account">판매가를</option>
				<option value="saccount">시중가를</option>	
			</select>
			<label for="price_unit" class="sound_only">적용단위</label>
			<select name="price_unit" id="price_unit">
				<option value="1">1원 단위로</option>
				<option value="10">10원 단위로</option>	
				<option value="100">100원 단위로</option>	
				<option value="1000">1000원 단위로</option>	
			</select>	
			<label for="price_cut" class="sound_only">가격절사</label>
			<select name="price_cut" id="price_cut">
				<option value="floor">내림</option>
				<option value="round">반올림</option>
				<option value="ceil">올림</option>											
			</select>					
		</td>
	</tr>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<input type="button" value="일괄적용하기" onclick="js_chk_frm(this.form);" class="btn_large">
</div>
</form>

<div class="information">
	<h4>도움말</h4>
	<div class="content">
		<div class="desc02">
			<p>ㆍ일괄수정 할 상품을 검색 후 상품 가격을 일괄처리 조건에 맞춰 적용합니다.</p>
			<p>ㆍ일괄수정 이후에는 이전상태로 복원이 안되므로 신중하게 변경하시기 바랍니다.</p>			<p>ㆍ서버 부하등 안정적인 서비스를 위해서 수정할 상품이 많은 경우에는 "검색된 상품에 적용"은 피하시기 바랍니다.</p>
			<p>ㆍ할인/할증율(%) : 소수점 첫째자리까지 입력하실 수 있습니다.</p>
			<p>ㆍ<em>[예] 5.5% 할인(가능), 5.5% 할증(가능), 5.55% 할인(불가능)</em></p>
		</div>
		<div class="hd">ㆍ가격수정 예제</div>
		<div class="desc01">
			<p>ㆍ판매가의 5.5% 할인된 가격으로 판매가를 일괄적으로 수정하고, 가격 단위는 100원 단위로 내림하여 수정한다면,</p>
			<p>ㆍ판매가 10,000원인 상품의 계산식은 다음과 같습니다.</p>
			<p class="fc_red">ㆍ10,000 × (1 - (5.5 / 100)) = 9,450원이며,</p>
			<p class="fc_red">ㆍ100원 단위 내림하면 9,400원 으로 최종 가격수정이 됩니다.</p>
		</div>
	</div>
</div>

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

// 일괄적용하기
function js_chk_frm(f2)
{
	var f = document.fgoodslist;
	if(getRadioVal(f2.gubun) == 1) {
		var chk = document.getElementsByName("chk[]");
		var bchk = false;
		for(i=0; i<chk.length; i++)
		{
			if(chk[i].checked)
				bchk = true;
		}

		if(!bchk) 
		{
			alert("적용할 자료를 하나 이상 선택하세요.");
			return;
		}	
	}
	
	if(!f2.price.value) {
		alert("적용할 숫자를 입력하세요.");
		f2.price.focus();
		return;
	}

    if(isNaN(f2.price.value)) {
		alert("숫자만 입력하세요.");
		f2.price.focus();
        return;
	}

	addHidden(f, 'gubun', getRadioVal(f2.gubun));
	addHidden(f, 'price', setFloor(f2.price.value,1));
	addHidden(f, 'price_field', getSelectVal(f2.price_field));
	addHidden(f, 'price_type', getSelectVal(f2.price_type));
	addHidden(f, 'price_both', getSelectVal(f2.price_both));
	addHidden(f, 'price_target', getSelectVal(f2.price_target));
	addHidden(f, 'price_unit', getSelectVal(f2.price_unit));
	addHidden(f, 'price_cut', getSelectVal(f2.price_cut));

	if(confirm("일괄적용 후에는 이전상태로 복원이 되지않습니다.\n\n정말 적용하시겠습니까?")) {
		var token = get_ajax_token();
        if(!token) {
            alert("토큰 정보가 올바르지 않습니다.");
            return false;
        }

		f.token.value = token;
		f.action = './goods/goods_getprice_update.php';
		f.submit();
	}
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
