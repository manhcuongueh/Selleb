<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

if(!$p_use_good) {
	alert('개별 상품판매 권한이 있어야만 이용 가능합니다.');
}

$goods_path = TW_DATA_PATH."/goods";

for($i=0; $i<count($chk); $i++) 
{
    // 실제 번호를 넘김
    $k = $chk[$i];

	$gs_id = trim($_POST['gs_id'][$k]);
	$gs = get_goods($gs_id);

	$dir_list = $goods_path.'/'.$gs_id;

	if($gs['simg1']) { 
		@unlink($goods_path."/".$gs['simg1']);
		delete_item_thumbnail($dir_list, $gs['simg1']);
	}
	if($gs['simg2']) {
		@unlink($goods_path."/".$gs['simg2']); 
		delete_item_thumbnail($dir_list, $gs['simg2']);
	}
	if($gs['simg3']) { 
		@unlink($goods_path."/".$gs['simg3']);
		delete_item_thumbnail($dir_list, $gs['simg3']);
	}
	if($gs['simg4']) { 
		@unlink($goods_path."/".$gs['simg4']);
		delete_item_thumbnail($dir_list, $gs['simg4']);
	}
	if($gs['simg5']) { 
		@unlink($goods_path."/".$gs['simg5']);
		delete_item_thumbnail($dir_list, $gs['simg5']);
	}
	if($gs['simg6']) { 
		@unlink($goods_path."/".$gs['simg6']);
		delete_item_thumbnail($dir_list, $gs['simg6']);
	}
	if($gs['bimg1']) { 
		@unlink($goods_path."/".$gs['bimg1']);
		delete_item_thumbnail($dir_list, $gs['bimg1']);
	}
	if($gs['bimg2']) { 
		@unlink($goods_path."/".$gs['bimg2']);
		delete_item_thumbnail($dir_list, $gs['bimg2']);
	}
	if($gs['bimg3']) { 
		@unlink($goods_path."/".$gs['bimg3']);
		delete_item_thumbnail($dir_list, $gs['bimg3']);
	}
	if($gs['bimg4']) { 
		@unlink($goods_path."/".$gs['bimg4']);
		delete_item_thumbnail($dir_list, $gs['bimg4']);
	}
	if($gs['bimg5']) { 
		@unlink($goods_path."/".$gs['bimg5']);
		delete_item_thumbnail($dir_list, $gs['bimg5']);
	}

	// 에디터 이미지 삭제
	delete_editor_image($gs['memo']);

	// 삭제
	sql_query("delete from shop_goods where index_no='$gs_id'"); // 상품테이블
	sql_query("delete from shop_goods_type where gs_id='$gs_id'"); //진열관리
	sql_query("delete from shop_goods_cate where gs_id='$gs_id'"); // 카테고리
	sql_query("delete from shop_goods_review where gs_id='$gs_id'"); // 상품평
	sql_query("delete from shop_goods_option where gs_id='$gs_id'"); // 옵션
	sql_query("delete from shop_cart where gs_id='$gs_id' and ct_select='0'"); // 장바구니
	sql_query("delete from shop_wish where gs_id='$gs_id'"); // 찜목록
	sql_query("delete from shop_goods_qa where gs_id='$gs_id'"); // 상품문의
	sql_query("delete from shop_goods_relation where gs_id = '$gs_id'");// 관련상품
	sql_query("delete from shop_goods_relation where gs_id2 = '$gs_id'");// 관련상품
}

goto_url("./page.php?$q1&page=$page");
?>