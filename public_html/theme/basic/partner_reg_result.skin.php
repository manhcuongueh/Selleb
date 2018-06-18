<?php
if(!defined('_TUBEWEB_')) exit;
?>

<p class="tit_navi">홈 <i class="ionicons ion-ios-arrow-right"></i> 쇼핑몰분양신청</p>
<h2 class="stit">쇼핑몰분양신청</h2>

<div class="tbl_frm01">
	<table class="wfull">
	<colgroup>
		<col width='18%'>
		<col width='82%'>
	</colgroup>
	<tr>
		<th>신청일시</th>
		<td><?php echo date('Y-m-d H:i:s', $partner['wdate']); ?></td>
	</tr>
	<tr>
		<th>은행명</th>
		<td><?php echo $partner['bank_company']; ?></td>
	</tr>
	<tr>
		<th>계좌번호</th>
		<td><?php echo $partner['bank_number']; ?></td>
	</tr>
	<tr>
		<th>예금주명</th>
		<td><?php echo $partner['bank_name']; ?></td>
	</tr>
	<tr>
		<th>전달사항</th>
		<td><?php echo nl2br($partner['memo']); ?></td>
	</tr>
	<tr>
		<th>결제방식</th>
		<td><?php echo ($partner['bank_type']=='1')?"무통장":"신용카드"; ?></td>
	</tr>
	<tr>
		<th>결제금액</th>
		<td><?php echo display_price($partner['bank_money']); ?></td>
	</tr>
	<?php if($partner['bank_type']=='1') { ?>
	<tr>
		<th>입금자명</th>
		<td><?php echo $partner['bank_name2']; ?></td>
	</tr>
	<tr>
		<th>무통장입금계좌</th>
		<td><?php echo $partner['bank_acc']; ?></td>
	</tr>
	<?php } ?>
	</table>
</div>

<div class="tac mart20">
	<a href="<?php echo TW_URL; ?>" class="btn_medium">확인</a>
</div>