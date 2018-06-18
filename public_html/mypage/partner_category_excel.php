<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

$target_table = 'shop_cate_'.$member['id'];
$sql_order = " and p_hide = '0' order by list_view asc ";

$sql = " select * from {$target_table} where length(catecode)='3' {$sql_order} ";
$result = sql_query($sql);
$cnt = @sql_num_rows($result);
if(!$cnt)
	alert("출력할 자료가 없습니다.");

/*================================================================================
php_writeexcel http://www.bettina-attack.de/jonny/view.php/projects/php_writeexcel/
=================================================================================*/

include_once(TW_INC_PATH.'/Excel/php_writeexcel/class.writeexcel_workbook.inc.php');
include_once(TW_INC_PATH.'/Excel/php_writeexcel/class.writeexcel_worksheet.inc.php');

$fname = tempnam(TW_DATA_PATH, "tmp-category.xls");
$workbook = new writeexcel_workbook($fname);
$worksheet = $workbook->addworksheet();

// Put Excel data
$data = array('1차분류','2차분류','3차분류','4차분류','5차분류','분류코드');
$data = array_map('iconv_euckr', $data);

$col = 0;
foreach($data as $cell) {
    $worksheet->write(0, $col++, $cell);
}

$i = 1;
while($row1 = sql_fetch_array($result)) { // 1차 분류
	$row1 = array_map('iconv_euckr', $row1);    
	$worksheet->write($i, 0, $row1['catename']);
	$worksheet->write($i, 1, '');
	$worksheet->write($i, 2, '');
	$worksheet->write($i, 3, '');
	$worksheet->write($i, 4, '');
	$worksheet->write($i, 5, ' '.$row1['catecode']);
	$i++;

	$sql2 = " select * from {$target_table} where upcate='{$row1['catecode']}' {$sql_order} ";
	$result2 = sql_query($sql2);
	while($row2 = sql_fetch_array($result2)) { // 2차 분류
		$row2 = array_map('iconv_euckr', $row2);		
		$worksheet->write($i, 0, $row1['catename']);
		$worksheet->write($i, 1, $row2['catename']);
		$worksheet->write($i, 2, '');
		$worksheet->write($i, 3, '');
		$worksheet->write($i, 4, '');
		$worksheet->write($i, 5, ' '.$row2['catecode']);
		$i++;

		$sql3 = " select * from {$target_table} where upcate='{$row2['catecode']}' {$sql_order} ";
		$result3 = sql_query($sql3);
		while($row3 = sql_fetch_array($result3)) { // 3차 분류			
			$row3 = array_map('iconv_euckr', $row3);
			$worksheet->write($i, 0, $row1['catename']);
			$worksheet->write($i, 1, $row2['catename']);
			$worksheet->write($i, 2, $row3['catename']);
			$worksheet->write($i, 3, '');
			$worksheet->write($i, 4, '');
			$worksheet->write($i, 5, ' '.$row3['catecode']);
			$i++;

			$sql4 = " select * from {$target_table} where upcate='{$row3['catecode']}' {$sql_order} ";
			$result4 = sql_query($sql4);
			while($row4 = sql_fetch_array($result4)) { // 4차 분류			
				$row4 = array_map('iconv_euckr', $row4);
				$worksheet->write($i, 0, $row1['catename']);
				$worksheet->write($i, 1, $row2['catename']);
				$worksheet->write($i, 2, $row3['catename']);
				$worksheet->write($i, 3, $row4['catename']);
				$worksheet->write($i, 4, '');
				$worksheet->write($i, 5, ' '.$row4['catecode']);
				$i++;

				$sql5 = " select * from {$target_table} where upcate='{$row4['catecode']}' {$sql_order} ";
				$result5 = sql_query($sql5);
				while($row5 = sql_fetch_array($result5)) { // 5차 분류			
					$row5 = array_map('iconv_euckr', $row5);
					$worksheet->write($i, 0, $row1['catename']);
					$worksheet->write($i, 1, $row2['catename']);
					$worksheet->write($i, 2, $row3['catename']);
					$worksheet->write($i, 3, $row4['catename']);
					$worksheet->write($i, 4, $row5['catename']);
					$worksheet->write($i, 5, ' '.$row5['catecode']);
					$i++;
				}
			}
		}
	}	
}

$workbook->close();

$title = iconv_euckr("분류");
header("Content-Type: application/x-msexcel; name=\"{$title}-".date("ymd", time()).".xls\"");
header("Content-Disposition: inline; filename=\"{$title}-".date("ymd", time()).".xls\"");
$fh=fopen($fname, "rb");
fpassthru($fh);
unlink($fname);
?>