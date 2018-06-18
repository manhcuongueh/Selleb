<?php
include_once("./_common.php");

if($act == "buy")
{
    if(!count($_POST['ct_chk']))
        alert("주문하실 상품을 하나이상 선택해 주십시오.");

	$tmp_comma	 = "";
	$ss_cart_id	 = "";
	set_session('ss_cart_id', "");

    $fldcnt = count($_POST['gs_id']);
    for($i=0; $i<$fldcnt; $i++) {
        $ct_chk = $_POST['ct_chk'][$i];
        if($ct_chk) {
			$gs_id = $_POST['gs_id'][$i];
			$sql = "select * from shop_cart where gs_id='$gs_id' and ct_select='0' and mb_no='$mb_no'";
			$result = sql_query($sql);
			for($k=0; $row=sql_fetch_array($result); $k++) {
				$ss_cart_id .= $tmp_comma . $row['index_no'];
				$tmp_comma = ",";
			}
        }
    }

	set_session('ss_cart_id', $ss_cart_id);

    if($is_member) // 회원인 경우
        goto_url("./orderform.php");
    else
		goto_url("./login.php?url=".urlencode("$tb[bbs_root]/orderform.php"));
}
else if($act == "alldelete") // 모두 삭제이면
{
    $sql = " delete from shop_cart where ct_select='0' and mb_no='$mb_no' ";
    sql_query($sql);
}
else if($act == "seldelete") // 선택삭제
{
    if(!count($_POST['ct_chk']))
        alert("삭제하실 상품을 하나이상 선택해 주십시오.");

    $fldcnt = count($_POST['gs_id']);
    for($i=0; $i<$fldcnt; $i++) {
        $ct_chk = $_POST['ct_chk'][$i];
        if($ct_chk) {
            $gs_id = $_POST['gs_id'][$i];
            $sql = " delete from shop_cart where gs_id='$gs_id' and ct_select='0' and mb_no='$mb_no' ";
            sql_query($sql);
        }
    }
}
else // 장바구니에 담기
{
	$count = count($_POST['gs_id']);
	if($count < 1)
		alert('장바구니에 담을 상품을 선택하여 주십시오.');

	$tmp_comma	 = "";
	$ss_cart_id	 = "";
	set_session('ss_cart_id', "");
	
	for($i=0; $i<$count; $i++) {
		// 보관함의 상품을 담을 때 체크되지 않은 상품 건너뜀
        if($act == 'multi' && !$_POST['chk_gs_id'][$i])
            continue;

		$gs_id = $_POST['gs_id'][$i];
		$opt_count = count($_POST['io_id'][$gs_id]);

        if($opt_count && $_POST['io_type'][$gs_id][0] != 0)
            alert('상품의 주문옵션을 선택해 주십시오.');

        for($k=0; $k<$opt_count; $k++) {
            if($_POST['ct_qty'][$gs_id][$k] < 1)
                alert('수량은 1 이상 입력해 주십시오.');
        }

		// 상품정보
		$gs = get_goods($gs_id);
		$gs['account'] = get_sale_price($gs_id);

		// 옵션정보를 얻어서 배열에 저장
		$opt_list = array();
		$sql = " select * from shop_goods_option where gs_id = '$gs_id' order by io_no asc ";
		$result = sql_query($sql);
		$lst_count = 0;
		for($k=0; $row=sql_fetch_array($result); $k++) {
			$opt_list[$row['io_type']][$row['io_id']]['id'] = $row['io_id'];
			$opt_list[$row['io_type']][$row['io_id']]['use'] = $row['io_use'];
			$opt_list[$row['io_type']][$row['io_id']]['price'] = $row['io_price'];
			$opt_list[$row['io_type']][$row['io_id']]['stock'] = $row['io_stock_qty'];

			// 선택옵션 개수
			if(!$row['io_type'])
				$lst_count++;
		}

        //--------------------------------------------------------
        //  재고 검사
        //--------------------------------------------------------
        // 이미 장바구니에 있는 같은 상품의 수량합계를 구한다.
        for($k=0; $k<$opt_count; $k++) {
			$io_id = preg_replace(TW_OPTION_ID_FILTER, '', $_POST['io_id'][$gs_id][$k]);
			$io_type = preg_replace('#[^01]#', '', $_POST['io_type'][$gs_id][$k]);
            $io_value = $_POST['io_value'][$gs_id][$k];

             // 재고 구함
            $ct_qty = $_POST['ct_qty'][$gs_id][$k];
            if(!$io_id)
                $it_stock_qty = get_it_stock_qty($gs_id);
            else
                $it_stock_qty = get_option_stock_qty($gs_id, $io_id, $io_type);

            if($ct_qty > $it_stock_qty)
            {
                alert($io_value." 의 재고수량이 부족합니다.\\n\\n현재 재고수량 : " . number_format($it_stock_qty) . " 개");
            }
        }
        //--------------------------------------------------------
		
		// 옵션수정일 때 기존 장바구니 자료를 먼저 삭제
        //if($act == 'optionmod')
		sql_query(" delete from shop_cart where gs_id='$gs_id' and ct_select='0' and mb_no='$mb_no' ");

		// 장바구니에 Insert
		$comma = '';
		for($k=0; $k<$opt_count; $k++) {
            $io_id = preg_replace(TW_OPTION_ID_FILTER, '', $_POST['io_id'][$gs_id][$k]);
            $io_type = preg_replace('#[^01]#', '', $_POST['io_type'][$gs_id][$k]);
			$io_value = $_POST['io_value'][$gs_id][$k];

			// 선택옵션정보가 존재하는데 선택된 옵션이 없으면 건너뜀
			if($lst_count && $io_id == '')
				continue;

			// 구매할 수 없는 옵션은 건너뜀
			if($io_id && !$opt_list[$io_type][$io_id]['use'])
				continue;

			$io_price = $opt_list[$io_type][$io_id]['price'];
			$ct_qty = $_POST['ct_qty'][$gs_id][$k];

			// 동일옵션의 상품이 있으면 수량 더함
			$sql2 = " select index_no
						from shop_cart 
					   where gs_id = '$gs_id' 
						 and mb_no = '$mb_no' 
						 and ct_select = '0' 
						 and io_id = '$io_id' ";
			$row2 = sql_fetch($sql2);
			if($row2['index_no']) {
				$sql3 = " update shop_cart
							 set ct_qty = ct_qty + '$ct_qty'
						   where index_no = '{$row2['index_no']}' ";
				sql_query($sql3);
				continue;
			}

			// 중복되지 않는 유일키를 생성
			$orderno = cart_uniqid();

			$sql  = " insert into shop_cart
						( ca_id, mb_yes, mb_no, gs_id, ct_time, ct_price, ct_qty, ct_point, io_id, io_type, io_price, ct_option, ct_send_cost, orderno, ct_ip )
					VALUES ";
			$sql .= "( '$ca_id', '$mb_yes', '$mb_no', '{$gs['index_no']}', '$time_ymdhis', '{$gs['account']}', '$ct_qty', '{$gs['gpoint']}', '$io_id', '$io_type', '$io_price', '$io_value', '$ct_send_cost', '$orderno', '{$_SERVER['REMOTE_ADDR']}' )";			
			sql_query($sql);
			$ss_cart_id .= $tmp_comma . sql_insert_id();
			$tmp_comma = ",";
		}
	}

	set_session('ss_cart_id', $ss_cart_id);
}

// 바로 구매일 경우
if($sw_direct) {
    if($is_member)
		goto_url("./orderform.php");
    else
    	goto_url("./login.php?url=".urlencode("$tb[bbs_root]/orderform.php"));
} else {
   goto_url("./cart.php");
}
?>
