<?php
if(!defined('_TUBEWEB_')) exit;

$sql_common = " from shop_member_grade ";
$sql_order  = " order by index_no desc";

$sql = " select count(*) as cnt $sql_common ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 30;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select * $sql_common $sql_order limit $from_record, $rows ";
$result = sql_query($sql);
?>

<h2>세부설정</h2>
<form name="frmlist" method="post">
<div class="tbl_head01">
	<table>
	<colgroup>
		<col width="70px">
		<col width="170px">
		<col width="130px">
		<col width="150px">
		<col>
	</colgroup>
	<thead>
	<tr>
		<th>레벨</th>
		<th>레벨명</th>
		<th>할인률</th>
		<th>절삭</th>
		<th>비고</th>
	</tr>
	</thead>
	<tbody class="list">
	<?php
	$arr_help = array();
	$arr_help[0] = '쇼핑몰 이용회원 (최하위)';
	$arr_help[1] = '쇼핑몰 이용회원';
	$arr_help[2] = '쇼핑몰 이용회원 (최상위)';

	$arr_help[3] = '가맹점 회원 (최하위)';
	$arr_help[4] = '가맹점 회원';
	$arr_help[5] = '가맹점 회원';
	$arr_help[6] = '가맹점 회원';
	$arr_help[7] = '가맹점 회원 (최상위)';

	$arr_help[8] = '최고 관리자';
	for ($i=0; $row=sql_fetch_array($result); $i++) {
		echo "<input type='hidden' name='gr_table[$i]' value='$row[index_no]'>";
		echo "<input type='hidden' name='chk[]' value='$i' checked>";

		$bg = 'list'.($i%2);
	?>
	<tr class="<?php echo $bg;?>">
		<td class="bold">Lv.<?php echo $num--;?></td>
		<td><input type="text" class="frm_input wfull" name="mb_grade[<?php echo $i?>]" value="<?php echo $row[grade_name]?>"></td>
		<td>
			<input type="text" class="frm_input w70" name="mb_sale[<?php echo $i?>]" value="<?php echo $row[mb_sale]?>">
			<select name="mb_per[<?php echo $i?>]">
				<option value="0"<?php echo get_selected($row[mb_per], '0');?>>%</option>
				<option value="1"<?php echo get_selected($row[mb_per], '1');?>>원</option>
			</select>
		</td>
		<td>
			<select name="mb_cutting[<?php echo $i?>]" class="wfull">
				<option value='0'<?php echo get_selected($row[mb_cutting], '0');?>>사용안함</option>
				<option value='100'<?php echo get_selected($row[mb_cutting], '100');?>>백원 단위절삭</option>
				<option value='1000'<?php echo get_selected($row[mb_cutting], '1000');?>>천원 단위절삭</option>
				<option value='10000'<?php echo get_selected($row[mb_cutting], '10000');?>>만원 단위절삭</option>
			</select>
		</td>
		<td class="tal"><?php echo $arr_help[$i];?></td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
</div>
<div class="btn_confirm">
	<button type="button" onclick="btn_check('update')" class="btn_large">저장</button>
</div>
</form>

<script>
function btn_check(act)
{
	var f = document.frmlist;

    if(act == "update") // 선택수정
    {
        f.action = './member/mem_level_form_update.php';
    }
    else
        return;

    f.submit();
}
</script>
