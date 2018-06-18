<?php
if(!defined('_TUBEWEB_')) exit;
?>

<h2>PC스킨(<?php echo $super['theme']; ?>)</h2>
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
		<?php echo get_input_jq("최상단 배너", 1000, 70, 1);?>
		<?php echo get_input_jq("상단 > 로고좌측", 160, 60, 2);?>
	</tr>
	<tr>
		<?php echo get_input_jq("메인 > 메인배너 하단 > 좌측", 280, 400, 3);?>
		<?php echo get_input_jq("메인 > 메인배너 하단 > 가운데 상", 420, 195, 4);?>
	</tr>
	<tr>
		<?php echo get_input_jq("메인 > 메인배너 하단 > 가운데 하", 420, 195, 5);?>
		<?php echo get_input_jq("메인 > 카테고리별 베스트 하단", 1000, 200, 6);?>
	</tr>
	<tr>
		<?php echo get_input_jq("메인 > 신상품 하단 > 글자입력 배너 (배너 이미지 배경)", 1920, '자유', 7);?>
		<?php echo get_input_jq("메인 > 인기상품 하단 > 상단 좌측", 480, 290, 8);?>
	</tr>
	<tr>
		<?php echo get_input_jq("메인 > 인기상품 하단 > 상단 가운데", 200, 290, 9);?>
		<?php echo get_input_jq("메인 > 인기상품 하단 > 하단 좌측", 690, 200, 10);?>
	</tr>
	<tr>
		<?php echo get_input_jq("메인 > 인기상품 하단 > 우측", 300, 500, 11);?>
		<?php echo get_input_jq("퀵메뉴 좌측 (반복배너 / 세로는 배너에 맞춰 입력)", 80, '자유', 90);?>
	</tr>
	</tbody>
	</table>
</div>
