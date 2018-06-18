<?php
include_once('./_common.php');

///////////////////////////////////////////////////////////////////////////////////////////////////
// �ô�����Ʈ ����� ���� ������ (EUC-KR)
///////////////////////////////////////////////////////////////////////////////////////////////////
	
require_once("./lib/AGSMobile.php");

$tracking_id = $_REQUEST["tracking_id"];
$transaction = $_REQUEST["transaction"];
$store_id = get_session('ss_store_id');
$log_path = null;
// log���� ������ ������ ��θ� �����մϴ�.
// ����� ���� null�� �Ǿ����� ��� "���� �۾� ���丮�� /lib/log/"�� ����˴ϴ�.

$agsMobile = new AGSMobile($store_id,$tracking_id,$transaction, $log_path);
$agsMobile->setLogging(true); //true : �αױ��, false : �αױ�Ͼ���.

////////////////////////////////////////////////////////
//
// getTrackingInfo() �� ���� �ô�����Ʈ �������� ȣ���� �� ���� �ߴ� Form ������ Array()�� ����Ǿ� �ֽ��ϴ�. 
//
////////////////////////////////////////////////////////

$info = $agsMobile->getTrackingInfo(); //$info ������ array() �����Դϴ�.

/////////////////////////////////////////////////////////////////////////////////
// -- tracking_info�� ����ִ� �÷� --
// 
// ������� : AuthTy (card,hp,virtual)
// ���������� : SubTy (ī���� ��� ���� : isp,visa3d)
//
// ȸ�����̵� : UserId
// �������̸� : OrdNm  
// �����̸� : StoreNm
// ������� : Job 
// ��ǰ�� : ProdNm
// 
// �޴�����ȣ : OrdPhone
// �����ڸ� : RcpNm
// �����ڿ���ó : RcpPhone
// �ֹ����ּ� : OrdAddr
// �ֹ���ȣ : OrdNo
// ������ּ� : DlvAddr
// ��ǰ�ڵ� : ProdCode
// �Աݿ����� : VIRTUAL_DEPODT
// ��ǰ���� : HP_UNITType
// ���� URL : RtnUrl
// �������̵� : StoreId
// ���� : Amt
// �̸��� : UserEmail
// ����URL : MallUrl
// ��� URL : CancelUrl
// �뺸������ : MallPage
// 
// ��Ÿ�䱸���� : Remark
// �߰�����ʵ�1 : Column1
// �߰�����ʵ�1 : Column2
// �߰�����ʵ�1 : Column3
// CP���̵� : HP_ID
// CP��й�ȣ :  HP_PWD
// SUB-CP���̵� : HP_SUBID
// ��ǰ�ڵ� :  ProdCode
// �������� : DeviId ( 9000400001:�Ϲݰ���, 9000400002:�����ڰ���)
// ī��缱�� : CardSelect
// �ҺαⰣ :  QuotaInf
// ������ �ҺαⰣ: NointInf
// 
////////////////////////////////////////////////////////////////////////////////////////////////

// tracking_info�� �������� �Ʒ��� ������� �������ø� �˴ϴ� 
//
// print_r($info); //tracking_info
// echo "�ֹ���ȣ : ".$info["OrdNo"]."</br>";
// echo "��ǰ�� : ".$info["ProdNm"]."</br>";
// echo "������� : ".$info["Job"]."</br>";
// echo "ȸ�����̵� : ".$info["UserId"]."</br>";
// echo "�������̸� : ".$info["OrdNm"]."</br>";  
//

// echo "AuthTy : ".$info["AuthTy"]."</br>";
// echo "SubTy : ".$info["SubTy"]."</br>";  

$ret = $agsMobile->approve();

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
// ��������� ���� ����DB ���� �� ��Ÿ �ʿ��� ó���۾��� �����ϴ� �κ��Դϴ�.
// �Ʒ��� ��������� ���Ͽ� �� �������ܺ� ����������� ����Ͻ� �� �ֽ��ϴ�.
// 
// $ret�� array() �������� ������ ���� ������ �����ϴ�.
//
// $ret = array (
//		'status' => 'ok' | 'error' //���μ����� ��� ok , ���и� error
//		'message' => '������ ��� �����޽���'
//		'data' => �������ܺ� ���� array() //���μ����� ��츸 ���õ˴ϴ�.
//	) 
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if ($ret['status'] == "ok") { // ���� ���� 

	$OrdNo = trim($ret["data"]["OrdNo"]);

	// ���������� ���� ����ó���κ�
	$cash = array();
	$cash['tpg'] = 'allthegate'; // PG��	
		
	if($ret["paytype"] == "card"){
		// ������ ���� �Ʒ��� ��ũ�� ����Ͻø� �˴ϴ�.
		$url = "http://www.allthegate.com/customer/receiptLast3.jsp";
		$url .= "?sRetailer_id=".$ret["data"]["StoreId"];
		$url .= "?approve=".$ret["data"]["AdmNo"];
		$url .= "?send_no=".$ret["data"]["DealNo"];
		$url .= "?send_dt=".substr($ret["data"]["AdmTime"],0,8);

		// ī�� ���� �� ���� ���� 
		$cash['tid']			= iconv_utf8($ret["data"]["DealNo"]);		// �ŷ���ȣ
		$cash['tracking_id']	= iconv_utf8($tracking_id);					// tracking_id
		$cash['card_code']		= iconv_utf8($ret["data"]["CardCd"]);		// ī����ڵ�
		$cash['card_bankcode']	= iconv_utf8($ret["data"]["CardNm"]);		// ī����
		$cash['appldate']		= iconv_utf8($ret["data"]['AdmTime']);		// ���νð�
		$cash['applnum']		= iconv_utf8($ret["data"]["AdmNo"]);		// ���ι�ȣ	
		$cash['BusiCd']			= iconv_utf8($ret["data"]["BusiCd"]);		// �����ڵ�
		$cash['PartialMm']		= iconv_utf8($ret["data"]["PartialMm"]);	// �Һΰ�����
		$cash['AuthTy']			= iconv_utf8($ret["data"]["AuthTy"]);		// AuthTy
		$cash['SubTy']			= iconv_utf8($ret["data"]["SubTy"]);		// SubTy
		$cash['StoreId']		= iconv_utf8($ret["data"]["StoreId"]);		// ��üID
		$cash['NetCancelId']	= iconv_utf8($ret["data"]["NetCancelId"]);	// �����ID
		$cash['OrdNo']			= iconv_utf8($ret["data"]["OrdNo"]);		// �ֹ���ȣ
		$cash['Amt']			= iconv_utf8($ret["data"]["Amt"]);			// �ŷ��ݾ�
		$cash['EscrowYn']		= iconv_utf8($ret["data"]["EscrowYn"]);		// y�̸� ����ũ��
		$cash['NoInt']			= iconv_utf8($ret["data"]["NoInt"]);		// y�̸� ������
		$cash['EscrowSendNo']	= iconv_utf8($ret["data"]["EscrowSendNo"]); // ����ũ��������ȣ
		$cash['url']			= iconv_utf8($url); // ī�念����
		$cash_info = serialize($cash);

		$sql = "update shop_order
				   set cash_info = '$cash_info',
					   dan = '2',
					   incomedate = '$server_time',
					   incomedate_s	= '$time_ymd'
				 where odrkey = '$OrdNo'";
		sql_query($sql);

		sql_query("update shop_cart set ct_select='1' where odrkey='$OrdNo'");

		alert(iconv_utf8("������ �Ϸ� �Ǿ����ϴ�."), $tb['bbs_root']."/orderinquiryview.php?odrkey=$OrdNo");
	
		// ī�� �ŷ��� ���,
		// ���� DB �� ��Ÿ ������ ���ܻ�Ȳ���� ������ �ٷ� ����ؾ� �Ѵٸ�
		// �Ʒ��� ���� ���� �Ʒ��� �Լ� ȣ��� ��Ұ� �����մϴ�.
		
		// �Ʒ� �κ��� �ּ����� �ϸ� �ٷ� ���� ��� �� �� �ֽ��ϴ�. (ī�� ���� ���� ���Ŀ��� ����)
		/*
		$cancelRet = $agsMobile->forceCancel();

		// ������ �Ʒ����� ó���ϼ���
		if ($cancelRet['status'] == "ok") {
			echo "��� ����<br/>";
			echo "��üID : ".$cancelRet["data"]["StoreId"]."<br/>";     
			echo "���ι�ȣ: ".$cancelRet["data"]["AdmNo"]."<br/>";   
			echo "���νð�: ".$cancelRet["data"]["AdmTime"]."<br/>";   
			echo "�ڵ�: ".$cancelRet["data"]['Code']."<br/>";   
		
		}else {
			//��� ��� ����
			echo "��� ���� : ".$cancelRet['message']; // ���� �޽���
		}
		*/
		
	} else if($ret["paytype"] == "hp"){
		// �ڵ��� ���� �� ���� ����
		$cash['tid']			= iconv_utf8($ret["data"]["AdmTID"]);		// �ڵ������� TID
		$cash['AuthTy']			= iconv_utf8($ret["data"]["AuthTy"]);		// AuthTy
		$cash['SubTy']			= iconv_utf8($ret["data"]["SubTy"]);		// SubTy
		$cash['StoreId']		= iconv_utf8($ret["data"]["StoreId"]);		// ��üID
		$cash['NetCancelId']	= iconv_utf8($ret["data"]["NetCancelId"]);	// �����ID
		$cash['OrdNo']			= iconv_utf8($ret["data"]["OrdNo"]);		// �ֹ���ȣ
		$cash['Amt']			= iconv_utf8($ret["data"]["Amt"]);			// �ŷ��ݾ�
		$cash['PhoneCompany']	= iconv_utf8($ret["data"]["PhoneCompany"]);	// �ڵ�����Ż�
		$cash['hpp_num']		= iconv_utf8($ret["data"]["Phone"]);		// �ڵ�����ȣ
		$cash_info = serialize($cash);

		$sql = "update shop_order
				   set cash_info = '$cash_info',
					   dan = '2',
					   incomedate = '$server_time',
					   incomedate_s	= '$time_ymd'
				 where odrkey = '$OrdNo'";
		sql_query($sql);

		sql_query("update shop_cart set ct_select='1' where odrkey='$OrdNo'");

		alert(iconv_utf8("������ �Ϸ� �Ǿ����ϴ�."), $tb['bbs_root']."/orderinquiryview.php?odrkey=$OrdNo");

		// �޴��� �ŷ��� ���,
		// ���� DB �� ��Ÿ ������ ���ܻ�Ȳ���� ������ �ٷ� ����ؾ� �Ѵٸ�
		// �Ʒ��� ���� ���� �Ʒ��� �Լ� ȣ��� ��Ұ� �����մϴ�.
		// �Ʒ� �κ��� �ּ����� �ϸ� �ٷ� ���� ��� �� �� �ֽ��ϴ�. (�޴��� ���� ���� ���Ŀ��� ����)
		/*
		$cancelRet = $agsMobile->forceCancel();

		// ������ �Ʒ����� ó���ϼ���
		if ($cancelRet['status'] == "ok") {
			
			echo "��üID : ".$cancelRet["data"]["StoreId"]."<br/>";     
			echo "�ڵ������� TID : ".$cancelRet["data"]["AdmTID"]."<br/>";    
			
		} else {
			//��� ��� ����
			echo "��� ���� : ".$cancelRet['message']; // ���� �޽���
		}
		*/
		
	} else if($ret["paytype"] == "virtual") {
		// ��������� ���������� ������¹߱��� �������� �ǹ��ϸ� �Աݴ����·� ���� ���� �Ա��� �Ϸ��� ���� �ƴմϴ�.
		// ���� ������� �����Ϸ�� �����Ϸ�� ó���Ͽ� ��ǰ�� ����Ͻø� �ȵ˴ϴ�.
		// ������ ���� �߱޹��� ���·� �Ա��� �Ϸ�Ǹ� MallPage(���� �Ա��뺸 ������(�������))�� �Աݰ���� ���۵Ǹ�
		// �̶� ��μ� ������ �Ϸ�ǰ� �ǹǷ� �����Ϸῡ ���� ó��(��ۿ�û ��)��  MallPage�� �۾����ּž� �մϴ�.
		//   
		// �������� : $ret["data"]["SuccessTime"]
		// ������¹�ȣ : $ret["data"]["VirtualNo"]
		// �Ա������ڵ� : $ret["data"]["BankCode"]

		// ������� ó�� �� ���� ���� 
		$cash['appldate']		= iconv_utf8($ret["data"]['SuccessTime']);	// ��������
		$cash['AuthTy']			= iconv_utf8($ret["data"]["AuthTy"]);		// AuthTy
		$cash['SubTy']			= iconv_utf8($ret["data"]["SubTy"]);		// SubTy
		$cash['StoreId']		= iconv_utf8($ret["data"]["StoreId"]);		// ��üID
		$cash['NetCancelId']	= iconv_utf8($ret["data"]["NetCancelId"]);	// �����ID
		$cash['OrdNo']			= iconv_utf8($ret["data"]["OrdNo"]);		// �ֹ���ȣ
		$cash['Amt']			= iconv_utf8($ret["data"]["Amt"]);			// �ŷ��ݾ�
		$cash['EscrowYn']		= iconv_utf8($ret["data"]["EscrowYn"]);		// y�̸� ����ũ��
		$cash['EscrowSendNo']	= iconv_utf8($ret["data"]["EscrowSendNo"]); // ����ũ��������ȣ
		$cash['vact_num']		= iconv_utf8($ret["data"]["VirtualNo"]);	// ������¹�ȣ
		$cash['vact_bankcode']	= iconv_utf8($ret["data"]["BankCode"]);		// �Ա��� ���� �ڵ�
		$cash['vact_date']		= iconv_utf8($ret["data"]["DueDate"]);		// �Աݿ�����
		$cash_info = serialize($cash);

		$sql = "update shop_order
				   set vact_num = '".$ret["data"]["VirtualNo"]."',
					   cash_info = '$cash_info',
					   dan = '1'
				 where odrkey = '$OrdNo'";
		sql_query($sql);

		sql_query("update shop_cart set ct_select='1' where odrkey='$OrdNo'");

		alert(iconv_utf8("������� �߱��� �Ϸ� �Ǿ����ϴ�."), $tb['bbs_root']."/orderinquiryview.php?odrkey=$OrdNo");
	}
} else {
	alert(iconv_utf8("���ν���({$ret['message']})"));
}	
?>