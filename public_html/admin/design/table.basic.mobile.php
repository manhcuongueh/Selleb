<?php
if(!defined('_TUBEWEB_')) exit;
?>

<h2>모바일스킨(<?php echo $super['mobile_theme']; ?>)</h2>
<div class="tbl_head01">
	<table class="tablef">
	<colgroup>
		<col>
		<col width="100px">
		<col width="50px">
		<col>
		<col width="100px">
		<col width="50px">
	</colgroup>
	<thead>
	<tr>
		<th scope="col">배너영역</th>
		<th scope="col">사이즈</th>
		<th scope="col">코드</th>
		<th scope="col">배너영역</th>
		<th scope="col">사이즈</th>
		<th scope="col">코드</th>
	</tr>
	</thead>
	<tbody class="list">
	<tr>
		<?php echo get_input_jq("모바일 > 최상단 배너", 960, 120, 100);?>
		<?php echo get_input_jq("모바일 > 메인 > 메인배너 하단 > 상단 좌측", 475, 270, 101);?>
	</tr>
	<tr>
		<?php echo get_input_jq("모바일 > 메인 > 메인배너 하단 > 상단 우측", 475, 270, 102);?>
		<?php echo get_input_jq("모바일 > 메인 > 메인배너 하단 > 하단", 960, 233, 103);?>
	</tr>
	<tr>
		<?php echo get_input_jq("모바일 > 메인 > 카테고리별 베스트 하단", 960, 300, 104);?>
		<?php echo get_input_jq("모바일 > 메인 > 베스트셀러 하단", 960, 300, 105);?>
	</tr>
	<tr>
		<?php echo get_input_jq("모바일 > 메인 > 신상품 하단", 960, 300, 106);?>
		<?php echo get_input_jq("모바일 > 메인 > 인기상품 하단", 960, 300, 107);?>
	</tr>
	</tbody>
	</table>
</div>
