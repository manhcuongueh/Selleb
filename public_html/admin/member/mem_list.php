<?php
if(!defined('_TUBEWEB_')) exit;

if(isset($q_date_field) && $q_date_field) {
	$qstr .= "&q_date_field=$q_date_field";
}

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_member ";
$sql_search = " where id <> 'admin' ";

if($sfl && $stx) {
    $sql_search .= " and ($sfl like '%$stx%') ";
}
if($sst) {
	$sql_search .= " and grade='$sst' ";
}
if($sca) {
	$sql_search .= " and gender='$sca' ";
}

// 기간검색
if($j_sdate && $j_ddate)
    $sql_search .= " and $q_date_field between '$j_sdate 00:00:00' and '$j_ddate 23:59:59' ";
else if($j_sdate && !$j_ddate)
	$sql_search .= " and $q_date_field between '$j_sdate 00:00:00' and '$j_sdate 23:59:59' ";
else if(!$j_sdate && $j_ddate)
	$sql_search .= " and $q_date_field between '$j_ddate 00:00:00' and '$j_ddate 23:59:59' ";

if(!$orderby) {
    $filed = "index_no";
    $sod = "desc";
} else {
	$sod = $orderby;
}

$sql_order = "order by $filed $sod";

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

$is_intro = false;
$colspan = 12;
if($config['sp_app']) {	
	$is_intro = true;
	$colspan++;
}

$btn_frmline = <<<EOF
<a href="./help/sendmail.php" onclick="openwindow(this,'sendmail','750','680','no');return false" class="btn_lsmall bx-white">전체메일발송</a>
<a href="./sms/sms_member.php" onclick="openwindow(this,'sms_member','245','360','no');return false" class="btn_lsmall bx-white">전체문자발송</a>
<a href="./member/mem_excel.php?$q1" class="btn_lsmall bx-white"><i class="fa fa-file-excel-o"></i> 엑셀다운로드</a>
<a href="./member.php?code=register_form" class="fr btn_lsmall red"><i class="ionicons ion-android-add"></i> 회원추가</a>
EOF;

include_once(TW_PLUGIN_PATH.'/jquery-ui/datepicker.php');
?>

<script>
$(function(){
	// 날짜 검색 : TODAY MAX값으로 인식 (maxDate: "+0d")를 삭제하면 MAX값 해제
	$("#j_sdate,#j_ddate").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
});
</script>

<h2>기본검색</h2>
<form name="fsearch" id="fsearch" method="get">
<input type="hidden" name="code" value="<?php echo $code; ?>">
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col width="100px">
		<col width="220px">
		<col width="100px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th>검색키워드</th>
		<td colspan="3">
			<select name="sfl">
				<?php echo option_selected('id', $sfl, '아이디'); ?>
				<?php echo option_selected('name', $sfl, '회원명'); ?>
				<?php echo option_selected('pt_id', $sfl, '추천인'); ?>
				<?php echo option_selected('cellphone', $sfl, '핸드폰'); ?>
				<?php echo option_selected('telephone', $sfl, '전화번호'); ?>
			</select>
			<input type="text" name="stx" value="<?php echo $stx;?>" class="frm_input w325">
		</td>
	</tr>
	<tr>
		<th>기간검색</th>
		<td colspan="3">
			<select name="q_date_field" id="q_date_field">						
				<?php echo option_selected('reg_time', $q_date_field, "가입날짜"); ?>
				<?php echo option_selected('today_login', $q_date_field, "최근접속"); ?>
			</select>
			<?php echo get_search_date("j_sdate", "j_ddate", $j_sdate, $j_ddate); ?>
		</td>
	</tr>
	<tr>
		<th>그룹별</th>
		<td colspan="3">
			<select name="sst">
				<option value=''>레벨</option>
				<?php
				$sql = "select * from shop_member_grade where index_no!='1' and grade_name!=''";
				$res = sql_query($sql);
				for($i=0; $row=sql_fetch_array($res); $i++){
					echo option_selected($row[index_no], $sst, $row[grade_name]);
				}
				?>
			</select>
			<select name="sca">
				<?php echo option_selected('', $sca, '성별'); ?>
				<?php echo option_selected('M', $sca, '남자'); ?>
				<?php echo option_selected('F', $sca, '여자'); ?>
			</select>
		</td>
	</tr>
	</tbody>
	</table>
</div>
<div class="btn_confirm">
	<input type="submit" value="검색" class="btn_medium">
	<input type="button" value="초기화" id="frmRest" class="btn_medium grey">	
</div>
</form>

<div class="local_ov mart30">
	총 회원수 : <b class="fc_red"><?php echo number_format($total_count);?></b>명
</div>
<div class="local_frm01">
	<?php echo $btn_frmline; ?>
</div>
<div class="tbl_head01">
	<table>
	<colgroup>
		<col width="50px">
		<col width="60px">
		<col width="130px">
		<col width="150px">
		<col>
		<col width="130px">
		<col width="100px">
		<col width="80px">
		<col width="40px">
		<col width="40px">
		<col width="40px">
		<?php if($is_intro) { ?>
		<col width="40px">
		<?php } ?>
		<col width="80px">		
	</colgroup>
	<thead>
	<tr>
		<th scope="col">NO</th>
		<th scope="col">로그인</th>
		<th scope="col"><?php echo subject_sort_link('name',$q2);?>회원명</a></th>
		<th scope="col"><?php echo subject_sort_link('id',$q2);?>아이디</a></th>
		<th scope="col"><?php echo subject_sort_link('grade',$q2);?>레벨</a></th>
		<th scope="col"><?php echo subject_sort_link('pt_id',$q2);?>추천인</a></th>
		<th scope="col">핸드폰</th>
		<th scope="col">가입일</th>
		<th scope="col">문자</th>
		<th scope="col">메일</th>		
		<th scope="col">구매</th>
		<?php if($is_intro) { ?>
		<th scope="col"><?php echo subject_sort_link('use_app',$q2);?>인증</a></th>
		<?php } ?>
		<th scope="col"><?php echo subject_sort_link('point',$q2);?>적립금</a></th>		
	</tr>
	</thead>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$sel_field = '';
		if(is_partner($row['id'])) {
			$sel_field = "&sel_field=drapt";
		} else if(is_seller($row['id'])) {
			$sel_field = "&sel_field=item";
		}

		$bg = 'list'.($i%2);

		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;
	?>
	<tr class="<?php echo $bg;?>">
		<td><?php echo $num--;?></td>
		<td><a href="./admin_ss_login.php?mb_id=<?php echo $row['id'].$sel_field;?>" class="btn_small" target="_blank">로그인</a></td>
		<td class="tal"><a href="pop_member_main.php?index_no=<?php echo $row['index_no'];?>" onclick="openwindow(this,'pop_member','1000','600','yes');return false"><?php echo get_text($row['name']);?></a></td>
		<td class="tal"><?php echo $row['id'];?></td>
		<td><?php echo get_grade($row['grade']);?></td>
		<td><?php echo $row['pt_id'];?></td>
		<td><?php echo replace_tel($row['cellphone']);?></td>
		<td><?php echo substr($row['reg_time'],0,10);?></td>
		<td><a href="./sms/sms_user.php?ph=<?php echo conv_number($row['cellphone']);?>" onclick="openwindow(this,'pop_sms','245','360','no'); return false"><img src='/admin/img/ico_sms_true.gif'></a></td>
		<td><a href="./help/sendmail2.php?mail=<?php echo $row['email'];?>" onclick="openwindow(this,'pop_email','750','680','no'); return false"><img src='/admin/img/bt_item_email.gif'></a></td>		
		<td><?php echo number_format(shop_count($row['index_no']));?></td>		
		<?php if($is_intro) { ?>
		<td><input type='checkbox' name="use_app" value='1' <?php echo ($row['use_app'])?'checked':'';?> onclick="chk_use_app('<?php echo $row['id'];?>');"></td>
		<?php } ?>
		<td class="tar"><?php echo number_format($row['point']);?> P</td>		
	</tr>
	<?php 
	}
	if($i==0)
		echo '<tbody><tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
	?>
	</tbody>
	</table>
</div>
<div class="local_frm02">
	<?php echo $btn_frmline; ?>
</div>

<?php if($total_count > 0) { ?>
<div class="btn_confirm">
	<?php echo pageing($page, $total_page, $total_count, "{$_SERVER['SCRIPT_NAME']}?$q1&page=");?>
</div>
<?php } ?>

<?php if($is_intro) { ?>
<script>
function chk_use_app(mb_id) {
	var error = "";
	$.ajax({
		url: "<?php echo TW_ADMIN_URL; ?>/member/mem_use_app.php",
		type: "POST",
		data: {
			"mode": "u",
			"mb_id": mb_id
		},
		dataType: "json",
		async: false,
		cache: false,
		success: function(data, textStatus) {
			error = data.error;
		}
	});

	if (error) {
		alert(error);
		return false;
	}
}
</script>
<?php } ?>
