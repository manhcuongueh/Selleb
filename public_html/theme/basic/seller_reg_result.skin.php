<?php
if(!defined('_TUBEWEB_')) exit;
?>

<div><img src="<?php echo TW_IMG_URL; ?>/seller_reg_result.gif"></div>
<div class="regi_box tac fs14 mart10">입점(공급사) 신청이 완료 되었습니다.</div>
<div class="tbl_frm01 mart5">
	<table class="wfull">
	<colgroup>
		<col width="18%">
		<col width="82%">
	</colgroup>
	<tbody>
	<tr>
		<th>제공상품</th>
		<td><?php echo $seller['in_item']; ?></td>
	</tr>
	<tr>
		<th>업체(법인)명</th>
		<td><?php echo $seller['in_compay']; ?></td>
	</tr>
	<tr>
		<th>사업자등록번호</th>
		<td><?php echo $seller['in_sanumber']; ?></td>
	</tr>
	<tr>
		<th>전화번호</th>
		<td><?php echo $seller['in_phone']; ?></td>
	</tr>
	<tr>
		<th>팩스번호</th>
		<td><?php echo $seller['in_fax']; ?></td>
	</tr>
	<tr>
		<th>업태</th>
		<td><?php echo $seller['in_upte']; ?></td>
	</tr>
	<tr>
		<th>종목</th>
		<td><?php echo $seller['in_up']; ?></td>
	</tr>
	<tr>
		<th>대표자명</th>
		<td><?php echo $seller['in_name']; ?></td>
	</tr>
	<?php if($seller['in_home']) { ?>
	<tr>
		<th>홈페이지</th>
		<td><?php echo $seller['in_home']; ?></td>
	</tr>
	<?php } ?>
	<tr>
		<th>사업장주소</th>
		<td><?php echo print_address($seller['in_addr1'], $seller['in_addr2'], $seller['in_addr3'], $seller['in_addr_jibeon']); ?></td>
	</tr>
	<tr>
		<th>은행명</th>
		<td><?php echo $seller['n_bank']; ?></td>
	</tr>
	<tr>
		<th>예금주명</th>
		<td><?php echo $seller['n_name']; ?></td>
	</tr>
	<tr>
		<th>계좌번호</th>
		<td><?php echo $seller['n_bank_num']; ?></td>
	</tr>
	<tr>
		<th>담당자명</th>
		<td><?php echo $seller['in_dam']; ?></td>
	</tr>
	<tr>
		<th>담당자 핸드폰</th>
		<td><?php echo replace_tel($seller['n_phone']); ?></td>
	</tr>
	<tr>
		<th>담당자 이메일</th>
		<td><?php echo $seller['n_email']; ?></td>
	</tr>
	<tr>
		<th>전달사항</th>
		<td><?php echo $seller['memo']; ?></td>
	</tr>
	</tbody>
	</table>
</div>

<div class="tac mart20">
	<a href="<?php echo TW_URL; ?>" class="btn_medium">확인</a>
</div>
