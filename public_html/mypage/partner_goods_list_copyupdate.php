<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

if(!$p_use_good) {
	alert('개별 상품판매 권한이 있어야만 이용 가능합니다.');
}

$dest_path = TW_DATA_PATH."/goods";

for($i=0; $i<count($chk); $i++) 
{
    // 실제 번호를 넘김
    $k = $chk[$i];

	$gs_id = trim($_POST['gs_id'][$k]);

	$sql = " select * from shop_goods where index_no = '$gs_id' limit 1 ";
	$cp = sql_fetch($sql);

	// 상품테이블의 필드가 추가되어도 수정하지 않도록 필드명을 추출하여 insert 퀴리를 생성한다.
	$sql_common = "";
	$fields = sql_field_names("shop_goods");
	foreach($fields as $fld) {
		if(in_array($fld, array('index_no', 'gcode', 'readcount', 'rank', 'm_count', 'sum_qty', 'reg_time', 'update_time'))) continue;

		$sql_common .= " , $fld = '".addslashes($cp[$fld])."' ";
	}

	$sql = " insert into shop_goods
				set reg_time = '$time_ymdhis',
					update_time = '$time_ymdhis'
					$sql_common ";
	sql_query($sql);
	$new_gs_id = sql_insert_id();

	$sql_img = "";

	mt_srand((double)microtime()*1000000);
	$ret_value = mt_rand(10000,99999);

	for($g=1; $g<=6; $g++) {
		if($cp['simg'.$g] && preg_match("/^(http[s]?:\/\/)/", $cp['simg'.$g]) == false) {
			$file = $dest_path.'/'.$cp['simg'.$g];
			$dstfile = $dest_path."/{$ret_value}_".$cp['simg'.$g];
			$new_img = basename($dstfile);

			@copy($file, $dstfile);
			@chmod($dstfile, TW_FILE_PERMISSION);
			$sql_img .= " , simg{$g} = '$new_img' ";
		}
	}
	for($g=1; $g<=5; $g++) {
		if($cp['bimg'.$g] && preg_match("/^(http[s]?:\/\/)/", $cp['bimg'.$g]) == false) {
			$file = $dest_path.'/'.$cp['bimg'.$g];
			$dstfile = $dest_path."/{$ret_value}_".$cp['bimg'.$g];
			$new_img = basename($dstfile);

			@copy($file, $dstfile);
			@chmod($dstfile, TW_FILE_PERMISSION);
			$sql_img .= " , bimg{$g} = '$new_img' ";
		}
	}

	$sql = " update shop_goods
				set gcode = $server_time+$new_gs_id
					$sql_img
			  where index_no = '$new_gs_id' ";
	sql_query($sql);

	// 분류 copy
	$cgy_sql = " insert ignore into shop_goods_cate
						( gcate, gs_id )
				 select gcate, '$new_gs_id'
				   from shop_goods_cate
				  where gs_id = '$gs_id'
				  order by index_no asc ";
	sql_query($cgy_sql);
	
	// 옵션 copy
	$opt_sql = " insert ignore into shop_goods_option
						( io_id, io_type, gs_id, io_price, io_stock_qty, io_noti_qty, io_use )
				 select io_id, io_type, '$new_gs_id', io_price, io_stock_qty, io_noti_qty, io_use
				   from shop_goods_option
				  where gs_id = '$gs_id'
				  order by io_no asc ";
	sql_query($opt_sql);
}

goto_url("./page.php?$q1&page=$page");
?>