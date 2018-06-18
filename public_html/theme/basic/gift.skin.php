<?php
if(!defined('_TUBEWEB_')) exit;

include_once($theme_path.'/aside_my.skin.php');
?>

<div class="rbody">
	<p class="tit_navi">홈 <i class="ionicons ion-ios-arrow-right"></i> 마이페이지 <i class="ionicons ion-ios-arrow-right"></i> 쿠폰인증</p>
	<h2 class="stit">쿠폰인증</h2>

	<form name="fcoupon" id="fcoupon" method="post" action="<?php echo $form_action_url; ?>" onsubmit="return fcoupon_submit(this);" autocomplete="off">
	<input type="hidden" name="token" value="<?php echo $token; ?>">

	<p class="fs13 fc_e06 marb7">
		※ 쿠폰번호 인증 완료 후 포인트가 실시간 적립되며 바로 사용하실 수 있습니다.
	</p>
	<p class="cp_txt_bx bt">
		1. 쿠폰은 현금으로 교환 및 환불이 불가능 합니다.<br>
		2. 쿠폰번호는 대/소문자를 구분할 수 있으니 받은 번호 그대로 입력해 주세요.
	</p>

	<p class="cp_txt_bx tac">
		<input type="text" name="gi_num1" required itemname="쿠폰번호" maxlength="4" class="frm_cp" onkeyup="if(this.value.length==4) document.fcoupon.gi_num2.focus();">
		<span>-</span>
		<input type="text" name="gi_num2" required itemname="쿠폰번호" maxlength="4" class="frm_cp" onkeyup="if(this.value.length==4) document.fcoupon.gi_num3.focus();">
		<span>-</span>
		<input type="text" name="gi_num3" required itemname="쿠폰번호" maxlength="4" class="frm_cp" onkeyup="if(this.value.length==4) document.fcoupon.gi_num4.focus();">
		<span>-</span>
		<input type="text" name="gi_num4" required itemname="쿠폰번호" maxlength="4" class="frm_cp">
		<input type="submit" value="인증하기" class="btn_lsmall blue">
	</p>
	</form>

	<script>
	function fcoupon_submit(f) {
		if(confirm("인증 하시려면 '확인'버튼을 클릭하세요!") == false)
			return false;

		return true;
	}
	</script>

	<?php
	$sql_common = " from shop_gift ";
	$sql_search = " where mb_id = '$member[id]' ";

	if($sfl && $stx) {
		$sql_search .= " and $sfl like '$stx%' ";
	}

	$sql = " select count(*) as cnt $sql_common $sql_search ";
	$row = sql_fetch($sql);
	$total_count = $row['cnt'];

	$rows = 30;
	$total_page = ceil($total_count / $rows); // 전체 페이지 계산
	if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
	$from_record = ($page - 1) * $rows; // 시작 열을 구함
	$num = $total_count - (($page-1)*$rows);

	$sql = " select * $sql_common $sql_search order by no desc limit $from_record, $rows ";
	$result = sql_query($sql);
	?>

	<div class="top_sch mart20">
		<form name="fsearch2" id="fsearch2" method="post">
		<p class="fl padt10">Total <b class="fc_255"><?php echo number_format($total_count); ?></b></p>
		<p class="fr">
			<select name="sfl">
				<option <?php echo get_selected($sfl, 'gi_num'); ?> value='gi_num'>쿠폰번호</option>
			</select>
			<input class="ed" type="text" name="stx" value="<?php echo $stx; ?>">
			<input type="submit" value="검색" class="btn_small grey">
		</p>
		</form>
	</div>

	<div class="tbl_head02 marb20">
		<table class="wfull">
		<colgroup>
			<col width="6%">
			<col>
			<col width="12%'">
			<col width="11%'">
			<col width="11%'">
			<col width="8%'">
			<col width="18%'">
		</colgroup>
		<thead>
		<tr>
			<th class="bl_nolne">번호</th>
			<th>쿠폰번호</th>
			<th>금액</th>
			<th>시작일</th>
			<th>종료일</th>
			<th>인증상태</th>
			<th>등록일</th>
		</tr>
		</thead>
		<tbody>
		<?php
		for($i=0; $row=sql_fetch_array($result); $i++) {
			if(is_null_time($row['mb_wdate'])) {
				$row['mb_wdate'] = '';
			}

			$bg = 'list'.($i%2);
		?>
		<tr class="<?php echo $bg; ?>" align="center">
			<td class="bl_nolne"><?php echo $num--; ?></td>
			<td><?php echo $row['gi_num']; ?></td>
			<td><?php echo display_price($row['gr_price']); ?></td>
			<td><?php echo $row['gr_sdate']; ?></td>
			<td><?php echo $row['gr_edate']; ?></td>
			<td><?php echo $row['gi_use']?'yes':''; ?></td>
			<td><?php echo $row['mb_wdate']; ?></td>
		</tr>
		<?php
		}
		if($total_count==0)
			echo '<tr><td colspan="7" class="empty_list">자료가 없습니다.</td></tr>';
		?>
		</tbody>
		</table>
	</div>

	<?php
	echo pagelist($page, $total_page, $total_count, "{$_SERVER['SCRIPT_NAME']}?$qstr&page=");
	?>
</div>
