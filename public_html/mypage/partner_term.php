<?php
if(!defined('_TUBEWEB_')) exit;

if($config['p_month'] != 'y') {
	alert('관리비 사용이 중지되었습니다.', './page.php?code=partner_info');
}

$pg_title = "가맹점 연장신청";
include_once("./admin_head.sub.php");

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_partner_term ";
$sql_search = " where mb_id = '$member[id]' ";
$sql_order  = " order by index_no desc ";

if($sfl && $stx) {
    $sql_search .= " and ($sfl like '%$stx%') ";
}

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

$btn_frmline = <<<EOF
<button type="button" onclick="btn_check('delete');" class="btn_lsmall bx-white">선택취소</button>
EOF;

// 남은기간 검사
$h_y = date("Y", $member['term_date']);
$h_m = date("m", $member['term_date']);
$h_d = date("d", $member['term_date']);
$new_hold = mktime(0,0,1,$h_m,$h_d,$h_y);
$ed = $new_hold - time();

if($ed > 0) {  $extra_date = round($ed/(60*60*24)); $default_check = 1;}
else { $exceed_date = round(($ed/(60*60*24))*(-1)); $default_check = 2; }

// 총 납부 건수
$sql2 = " select count(*) as cnt 
		    from shop_partner_term 
		   where mb_id = '$member[id]' 
		     and state = '1' ";
$term = sql_fetch($sql2);
$term_count = ((int)$term['cnt'] + 1);
?>

<h2>기간 연장</h2>
<form name='fregform' method='post' onsubmit="return fregform_submit(this);">
<input type="hidden" name="token" value="">
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col width="100px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">가맹점 만료일</th>
		<td>
			<b><?php echo date("Y년 m월 d일", $member['term_date']); ?> 만료</b> (남은기간 : <?php if($default_check==1) { ?><b><?php echo number_format($extra_date); ?></b>일<?php } else { ?><span class="fc_red">만료</span><?php } ?>)
		</td>
	</tr>
	<tr>
		<th scope="row">결제금액</th>
		<td>
			<b class="fc_red"><?php echo number_format($b_config['etc3']);?></b>원
			(총: <?php echo $term_count; ?>회 납부)
		</td>
	</tr>
	<tr>
		<th scope="row">연장개월수</th>
		<td>
			<input type="text" name="go_date" value="1" required numeric itemname="연장 개월수" class="frm_input" size="2"> 개월<span class="fc_197 marl10">* 숫자만 입력해 주세요</span>
		</td>
	</tr>
	<tr>
		<th scope="row">결제방법</th>
		<td>
			<input type="radio" name="bank" value="1" checked="checked" id="bank">
			<label for="bank">무통장입금</label>		
		</td>
	</tr>
	<tr>
		<th scope="row">입금자명</th>
		<td><input type="text" name="bank_acc" required itemname="입금자명" value="<?php echo $member['name']; ?>"' size="20" class="frm_input"></td>
	</tr>
	<tr>
		<th scope="row">본사입금계좌</th>
		<td>
			<select name="bank_name">
			<?php
			$superbank = explode("\n", trim($default['cf_bank_account']));
			for($i=0; $i<count($superbank); $i++) {
				echo '<option value="'.trim($superbank[$i]).'">'.trim($superbank[$i]).'</option>'.PHP_EOL;
			}
			?>
			</select>
		</td>
	</tr>
	</tbody>
	</table>
</div>
<div class="btn_confirm">
	<input type="submit" value="신청" class="btn_medium red">
</div>
</form>

<h2>기본검색</h2>
<form name="fsearch" id="fsearch" method="get">
<input type="hidden" name='code' value="<?php echo $code; ?>">
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col width="100px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">검색키워드</th>
		<td>
			<select name="sfl">
				<?php echo option_selected('bank_name', $sfl, '본사입금계좌'); ?>
			</select>
			<input type="text" name="stx" value="<?php echo $stx; ?>" class="frm_input w325">
		</td>
	</tr>
	</tbody>
	</table>
</div>
<div class="btn_confirm">
	<input type="submit" value="검색" class="btn_medium">
</div>
</form>

<form name='ftermlist' method='post'>
<input type='hidden' name='q1' value="<?php echo $q1; ?>">
<input type='hidden' name='page' value="<?php echo $page; ?>">

<div class="local_ov mart30">
	전체 : <b class="fc_red"><?php echo number_format($total_count);?></b> 건 조회
</div>	

<div class="local_frm01">
	<?php echo $btn_frmline; ?>
</div>

<div class="tbl_head01">
	<table>
	<colgroup>
		<col width="50px">
		<col width="50px">
		<col width="60px">
		<col width="140px">
		<col width="100px">
		<col width="100px">
		<col width="100px">
		<col width="140px">
		<col>		
	</colgroup>
	<thead>
	<tr>
		<th><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form);"></th>
		<th>번호</th>
		<th>상태</th>
		<th>신청일시</th>
		<th>연장개월수</th>
		<th>결제방법</th>
		<th>결제금액</th>
		<th>입금자명</th>
		<th>본사입금계좌</th>		
	</tr>
	</thead>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		if($row['state']) { // 승인완료
			$disabled = " disabled";
			$td_bg = "";
		} else {	
			$disabled = "";			
			$td_bg = " style='background:yellow;'";
		}

		$bg = 'list'.($i%2);

		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;
	?>
	<tr class="<?php echo $bg; ?>">
		<td<?php echo $td_bg; ?>>
			<input type="checkbox" name="chk[]" value="<?php echo $i; ?>"<?php echo $disabled; ?>>
			<input type="hidden" name="index_no[<?php echo $i; ?>]" value="<?php echo $row['index_no']; ?>">
		</td>
		<td><?php echo $num--; ?></td>
		<td><?php echo $row['state']?"완료":"대기"; ?></td>
		<td><?php echo date('Y/m/d H:i:s',$row['wdate']); ?></td>
		<td><?php echo $row['go_date']; ?>개월</td>
		<td><?php echo ($row['bank']=='1')?"무통장":"신용카드"; ?></td>
		<td><?php echo number_format($row['money']); ?>원</td>
		<td><?php echo $row['bank_acc']; ?></td>
		<td class="tal"><?php echo $row['bank_name']; ?></td>		
	</tr>
	<?php 
	}
	if($i==0)
		echo '<tbody><tr><td colspan="9" class="empty_table">자료가 없습니다.</td></tr>';
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
function fregform_submit(f){

	if(!confirm("신청 하시겠습니까?"))
		return false;

	f.action = "./partner_term_update.php";
    return true;
}

function check_all(f)
{
    var chk = document.getElementsByName("chk[]");

    for(i=0; i<chk.length; i++)
        chk[i].checked = f.chkall.checked;
}

function btn_check(act)
{
	var f = document.ftermlist;

    if(act == "delete") // 선택취소
    {
        f.action = './partner_term_delete.php';
        str = "취소";
    }
    else
        return;

    var chk = document.getElementsByName("chk[]");
    var bchk = false;

    for(i=0; i<chk.length; i++)
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
        if(!confirm("선택한 자료를 정말 취소 하시겠습니까?"))
            return;
    }

    f.submit();
}
</script>

<?php
include_once("./admin_tail.sub.php");
?>