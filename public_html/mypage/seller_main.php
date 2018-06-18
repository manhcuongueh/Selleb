<?php
if(!defined('_TUBEWEB_')) exit;

$pg_title = "전체 거래진행 통계내역";
include_once("./admin_head.sub.php");

$sql_where = " where gs_se_id='$seller[sup_code]' ";

$row1 = admin_order_status_sum("$sql_where and dan!='0' "); // 총 주문내역
$row2 = admin_order_status_sum("$sql_where and orderdate_s='$time_ymd' and dan!='0'"); // 오늘접수 된 주문

$odr1 = admin_order_status_sum("$sql_where and dan='1' "); // 총 주문접수
$odr2 = admin_order_status_sum("$sql_where and dan='2' "); // 총 입금확인
$odr3 = admin_order_status_sum("$sql_where and dan='3' "); // 총 배송대기
$odr4 = admin_order_status_sum("$sql_where and dan='4' "); // 총 배송중
$odr5 = admin_order_status_sum("$sql_where and dan='5' "); // 총 배송완료
$odr6 = admin_order_status_sum("$sql_where and user_ok='0' and (dan between '4' and '5') "); // 총 구매미확정
$odr7 = admin_order_status_sum("$sql_where and user_ok='1' and (dan between '4' and '5') "); // 총 구매확정
$odr8 = admin_order_status_sum("$sql_where and dan between '7' and '9' "); // 총 주문취소
$odr9 = admin_order_status_sum("$sql_where and dan='6' "); // 총 반품처리
$odr10 = admin_order_status_sum("$sql_where and dan='10' "); // 총 교환처리
?>

<div id="main_wrap">
	<section>
		<h2>
			전체 주문 통계
			<a href="<?php echo TW_MYPAGE_URL; ?>/page.php?code=seller_odr&set=whole" class="btn_small">주문내역 바로가기</a>
		</h2>
		<div class="order_vbx">
			<dl class="od_bx1">
				<dt>전체 주문현황</dt>
				<dd>
					<p class="ddtit">총 주문건수</p>
					<p><?php echo number_format($row1['cnt']); ?></p>
				</dd>
				<dd class="total">
					<p class="ddtit">총 결제금액</p>
					<?php echo display_price2($row1['price']); ?>
				</dd>
			</dl>

			<dl class="od_bx2">
				<dt>주문상태 현황</dt>
				<dd>
					<p class="ddtit">주문접수</p>
					<p><?php echo number_format($odr1['cnt']); ?></p>
				</dd>
				<dd>
					<p class="ddtit">입금확인</p>
					<p><?php echo number_format($odr2['cnt']); ?></p>
				</dd>
				<dd>
					<p class="ddtit">배송대기</p>
					<p><?php echo number_format($odr3['cnt']); ?></p>
				</dd>
				<dd>
					<p class="ddtit">배송중</p>
					<p><?php echo number_format($odr4['cnt']); ?></p>
				</dd>
				<dd>
					<p class="ddtit">배송완료</p>
					<p><?php echo number_format($odr5['cnt']); ?></p>
				</dd>
			</dl>
			<dl class="od_bx2">
				<dt>구매이후 현황</dt>
				<dd>
					<p class="ddtit">구매미확정</p>
					<p><?php echo number_format($odr6['cnt']); ?></p>
				</dd>
				<dd>
					<p class="ddtit">구매확정</p>
					<p><?php echo number_format($odr7['cnt']); ?></p>
				</dd>
				<dd>
					<p class="ddtit">취소</p>
					<p><?php echo number_format($odr8['cnt']); ?></p>
				</dd>
				<dd>
					<p class="ddtit">반품</p>
					<p><?php echo number_format($odr9['cnt']); ?></p>
				</dd>
				<dd>
					<p class="ddtit">교환</p>
					<p><?php echo number_format($odr10['cnt']); ?></p>
				</dd>
			</dl>
		</div>
	</section>

	<section class="sidx_head01">
		<h2>
			오늘접수 된 주문
			<a href="<?php echo TW_MYPAGE_URL; ?>/page.php?code=seller_odr&set=today" class="btn_small">주문내역 바로가기</a>
		</h2>
		<table>
		<thead>
		<tr>
			<th>주문번호</th>
			<th>주문자명</th>
			<th>전화번호</th>
			<th>결제방법</th>
			<th>결제금액</th>
			<th>주문현황</th>
			<th>주문일시</th>
		</tr>
		</thead>
		<tbody>
		<?php
		$sql = "select * from shop_order {$sql_where} and orderdate_s='$time_ymd' and dan!='0' limit 5";
		$result = sql_query($sql);
		for($i=0; $row=sql_fetch_array($result); $i++){
		?>
		<tr>
			<td class="tac"><?php echo $row['odrkey']; ?></td>
			<td class="tac"><?php echo $row['name']; ?></td>
			<td class="tac"><?php echo $row['b_cellphone']; ?></td>
			<td class="tac"><?php echo $ar_method[$row['buymethod']]; ?></td>
			<td class="tac"><?php echo number_format($row['account']+$row['del_account']); ?></td>
			<td class="tac"><?php echo $ar_dan[$row['dan']]; ?></td>
			<td class="tac"><?php echo date("Y-m-d H:i:s", $row['orderdate']); ?></td>
		</tr>
		<?php
		} 
		if($i == 0) { 
		?>
		<tr><td colspan="7" class="empty_table">자료가 없습니다.</td></tr>
		<?php } ?>
		</tbody>
		</table>
	</section>

	<section>
		<table class="wfull">
		<tr>
			<td width="49.5%" valign="top" class="sidx_head01">
				<h2>공지사항<a href="<?php echo TW_BBS_URL; ?>/list.php?boardid=20" class="btn_small">바로가기</a></h2>
				<table>
				<colgroup>
					<col width='20%'>
					<col width='80%'>
				</colgroup>
				<thead>
				<tr>
					<th>등록일</th>
					<th>제목</th>
				</tr>
				</thead>
				<tbody>
				<?php
				$sql = "select * from shop_board_20 order by wdate desc limit 5 ";
				$res = sql_query($sql);
				for($i=0;$row=sql_fetch_array($res);$i++){
					$subject = cut_str($row['subject'],40);
					$wdate = date('Y-m-d',intval($row['wdate'],10));
					$href = TW_BBS_URL."/read.php?boardid=20&index_no=$row[index_no]";
				?>
				<tr>
					<td class="tac"><?php echo $wdate; ?></td>
					<td class="tac"><a href="<?php echo $href; ?>"><?php echo $subject; ?></a></td>
				</tr>
				<?php
				}
				if($i == 0)
					echo '<tr><td colspan="2" class="empty_table">자료가 없습니다.</td></tr>';
				?>
				</tbody>
				</table>
			</td>
			<td width="1%"></td>
			<td width="49.5%" valign="top" class="sidx_head01">
				<h2>질문과답변<a href="<?php echo TW_BBS_URL; ?>/list.php?boardid=21" class="btn_small">바로가기</a></h2>
				<table>
				<colgroup>
					<col width='20%'>
					<col width='80%'>
				</colgroup>
				<thead>
				<tr>
					<th>등록일</th>
					<th>제목</th>
				</tr>
				</thead>
				<tbody>
				<?php
				$sql = "select * from shop_board_21 order by wdate desc limit 5 ";
				$res = sql_query($sql);
				for($i=0;$row=sql_fetch_array($res);$i++){
					$subject = cut_str($row['subject'],40);
					$wdate = date('Y-m-d',intval($row['wdate'],10));
					$href = TW_BBS_URL."/read.php?boardid=21&index_no=$row[index_no]";
				?>
				<tr>
					<td class="tac"><?php echo $wdate; ?></td>
					<td class="tac"><a href="<?php echo $href; ?>"><?php echo $subject; ?></a></td>
				</tr>
				<?php
				}
				if($i == 0)
					echo '<tr><td colspan="2" class="empty_table">자료가 없습니다.</td></tr>';
				?>
				</tbody>
				</table>
			</td>
		</tr>
		</table>
	</section>
</div>

<?php
include_once("./admin_tail.sub.php");
?>