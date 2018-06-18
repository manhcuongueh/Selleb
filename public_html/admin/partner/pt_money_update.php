<?php
define('_PURENESS_', true);
include_once("./_common.php");

check_demo();

check_admin_token();

unset($value);
$value[etc1]	= '';
$value[etc2]	= '';
$value[etc3]	= '';
$value[ch]		= '';
$value[shop]	= '';
$value[ch_ty]	= '';
$value[shop_ty]	= '';

update("shop_partner_config",$value,"where state='' and etc4!='item_etc' and etc4!='item_tree1' and etc4!='item_tree2' and etc4!='item_tree3' and etc4!='item_tree4' and etc4!='item_tree5' and etc4!='shop' and etc4!='item_level1' and etc4!='item_level2' ");

if($_POST['mode']=='w') {
	
	unset($value_gr);
	$value_gr[grade_name] = $_POST['a1'];
	update("shop_member_grade",$value_gr,"where index_no='6'");	// 레벨명1

	unset($value_gr);
	$value_gr[grade_name] = $_POST['a2'];
	update("shop_member_grade",$value_gr,"where index_no='5'");	// 레벨명2
	
	unset($value_gr);
	$value_gr[grade_name] = $_POST['a3'];
	update("shop_member_grade",$value_gr,"where index_no='4'"); // 레벨명3
	
	unset($value_gr);
	$value_gr[grade_name] = $_POST['a4'];
	update("shop_member_grade",$value_gr,"where index_no='3'"); // 레벨명4
	
	unset($value_gr);
	$value_gr[grade_name] = $_POST['a5'];
	update("shop_member_grade",$value_gr,"where index_no='2'"); // 레벨명5
	
	unset($value);
	if($_POST['item_state1']=='y') {
		$value[etc1]	= $_POST['a1'];
		$value[etc2]	= str_replace(",","",$_POST['a11']);
		$value[etc3]	= str_replace(",","",$_POST['a111']);
		$value[state]	= $_POST['item_state1'];
		$value[ch]		= str_replace(",","",$_POST['item1_ch']);
		$value[shop]	= str_replace(",","",$_POST['item1_shop']);
		$value[ch_ty]	= $_POST['item1_ch_ty'];
		$value[shop_ty]	= $_POST['item1_shop_ty'];
		update("shop_partner_config",$value,"where etc4='item1'");
	} else {
		$value[state]	= '';
		update("shop_partner_config",$value,"where etc4='item1'");
	}

	unset($value);
	if($_POST['item_state2']=='y') {
		$value[etc1]	= $_POST['a2'];
		$value[etc2]	= str_replace(",","",$_POST['a22']);
		$value[etc3]	= str_replace(",","",$_POST['a222']);
		$value[state]	= $_POST['item_state2'];
		$value[ch]		= str_replace(",","",$_POST['item2_ch']);
		$value[shop]	= str_replace(",","",$_POST['item2_shop']);
		$value[ch_ty]	= $_POST['item2_ch_ty'];
		$value[shop_ty]	= $_POST['item2_shop_ty'];
		update("shop_partner_config",$value,"where etc4='item2'");
	} else {
		$value[state]	= '';
		update("shop_partner_config",$value,"where etc4='item2'");
	}


	unset($value);
	if($_POST['item_state3']=='y') {
		$value[etc1]	= $_POST['a3'];
		$value[etc2]	= str_replace(",","",$_POST['a33']);
		$value[etc3]	= str_replace(",","",$_POST['a333']);
		$value[state]	= $_POST['item_state3'];
		$value[ch]		= str_replace(",","",$_POST['item3_ch']);
		$value[shop]	= str_replace(",","",$_POST['item3_shop']);
		$value[ch_ty]	= $_POST['item3_ch_ty'];
		$value[shop_ty]	= $_POST['item3_shop_ty'];
		update("shop_partner_config",$value,"where etc4='item3'");
	} else {
		$value[state]	= '';
		update("shop_partner_config",$value,"where etc4='item3'");
	}

	unset($value);
	if($_POST['item_state4']=='y') {
		$value[etc1]	= $_POST['a4'];
		$value[etc2]	= str_replace(",","",$_POST['a44']);
		$value[etc3]	= str_replace(",","",$_POST['a444']);
		$value[state]	= $_POST['item_state4'];
		$value[ch]		= str_replace(",","",$_POST['item4_ch']);
		$value[shop]	= str_replace(",","",$_POST['item4_shop']);
		$value[ch_ty]	= $_POST['item4_ch_ty'];
		$value[shop_ty]	= $_POST['item4_shop_ty'];
		update("shop_partner_config",$value,"where etc4='item4'");
	} else {
		$value[state]	= '';
		update("shop_partner_config",$value,"where etc4='item4'");
	}

	unset($value);
	if($_POST['item_state5']=='y') {
		$value[etc1]	= $_POST['a5'];
		$value[etc2]	= str_replace(",","",$_POST['a55']);
		$value[etc3]	= str_replace(",","",$_POST['a555']);
		$value[state]	= $_POST['item_state5'];
		$value[ch]		= str_replace(",","",$_POST['item5_ch']);
		$value[shop]	= str_replace(",","",$_POST['item5_shop']);
		$value[ch_ty]	= $_POST['item5_ch_ty'];
		$value[shop_ty]	= $_POST['item5_shop_ty'];
		update("shop_partner_config",$value,"where etc4='item5'");
	} else {
		$value[state]	= '';
		update("shop_partner_config",$value,"where etc4='item5'");
	}
}

if($_POST['mode']=='w2') {
	unset($value);

	$value[etc2]		=	$_POST['p_login'];
	$value[etc3]		=	$_POST['p_member'];
	$value[p_tree]		=	$_POST['p_tree'];
	$value[state]		=	$_POST['state'];
	update("shop_partner_config",$value,"where etc4='item_etc' ");	

	$value[p_tree]		=	$_POST['s_tree'];
	$value[state]		=	$_POST['s_state'];
	update("shop_partner_config",$value,"where etc4='shop' ");	

	$item1				=	$_POST['item1'];
	$item2				=	$_POST['item2'];
	$item3				=	$_POST['item3'];
	$item4				=	$_POST['item4'];
	$item5				=	$_POST['item5'];
	$shop				=	$_POST['shop'];
	$level1				=	$_POST['level1'];
	$level2				=	$_POST['level2'];
	
	unset($value);
	unset($comma);
	if(is_array($item1)){
		for($i=0;$i<count($item1);$i++){	
			$item_tree1 .= $comma . $item1[$i];
			$comma = '|';
		}

		$value[etc1] = $item_tree1;
		update("shop_partner_config",$value,"where etc4='item_tree1' ");
	}

	unset($value);
	unset($comma);
	if(is_array($item2)){
		for($i=0;$i<count($item2);$i++){	
			$item_tree2 .= $comma . $item2[$i];
			$comma = '|';
		}

		$value[etc1] = $item_tree2;
		update("shop_partner_config",$value,"where etc4='item_tree2' ");
	}

	unset($value);
	unset($comma);
	if(is_array($item3)){
		for($i=0;$i<count($item3);$i++){
			$item_tree3 .= $comma . $item3[$i];
			$comma = '|';
		}

		$value[etc1] = $item_tree3;
		update("shop_partner_config",$value,"where etc4='item_tree3' ");
	}

	unset($value);
	unset($comma);	
	if(is_array($item4)){
		for($i=0;$i<count($item4);$i++){
			$item_tree4 .= $comma . $item4[$i];
			$comma = '|';
		}

		$value[etc1] = $item_tree4;
		update("shop_partner_config",$value,"where etc4='item_tree4' ");
	}

	unset($value);
	unset($comma);
	if(is_array($item5)){
		for($i=0;$i<count($item5);$i++){
			$item_tree5 .= $comma . $item5[$i];
			$comma = '|';
		}

		$value[etc1] = $item_tree5;
		update("shop_partner_config",$value,"where etc4='item_tree5' ");
	}

	unset($value);
	unset($comma);
	if(is_array($level1)){
		for($i=0;$i<count($level1);$i++){	
			$item_level1 .= $comma . $level1[$i];
			$comma = '|';
		}

		$value[etc1] = $item_level1;
		update("shop_partner_config",$value,"where etc4='item_level1' ");
	}

	unset($value);
	unset($comma);
	if(is_array($level2)){
		for($i=0;$i<count($level2);$i++){	
			$item_level2 .= $comma . $level2[$i];
			$comma = '|';
		}

		$value[etc1] = $item_level2;
		update("shop_partner_config",$value,"where etc4='item_level2' ");
	}

	unset($value);
	unset($comma);
	if(is_array($shop)){
		for($i=0;$i<count($shop);$i++){	
			$item_shop .= $comma . $shop[$i];
			$comma = '|';
		}

		$value[etc1] = $item_shop;
		update("shop_partner_config",$value,"where etc4='shop' ");
	}	
}

goto_url('../partner.php?code=money');
?>