<?php
if(!defined('_TUBEWEB_')) exit;

include_once(TW_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$coupon = sql_fetch("select * from shop_coupon where cp_id = '$cp_id'");
$ck_join = sql_fetch("select * from shop_coupon where cp_type = '5'");

if($w == "") {
	$coupon[cp_use]			 = 1;
	$coupon[cp_download]	 = 0;
	$coupon[cp_overlap]		 = 0;
	$coupon[cp_sale_type]    = 0;
	$coupon[cp_dups]		 = 0;
	$coupon[cp_sale_percent] = 0;
	$coupon[cp_sale_amt_max] = 0;
	$coupon[cp_sale_amt]	 = 0;
	$coupon[cp_inv_type]	 = 0;
	$coupon[cp_low_amt]		 = 0;
	$coupon[cp_pub_1_cnt]	 = 0;
	$coupon[cp_pub_2_cnt]	 = 0;
	$coupon[cp_pub_3_cnt]	 = 0;

} else if($w == "u") {
    if(!$coupon[cp_id])
        alert("존재하지 않은 쿠폰 입니다.");

	$qstr .= "&page=$page";

	// 쿠폰 (예외) 카테고리
	$arr_category = explode(',', $coupon[cp_use_category]);

	// 쿠폰 (예외) 상품
	if($coupon[cp_use_goods]) {
		$arr_use_goods = explode(',',$coupon[cp_use_goods]);
		$cnt_use_goods = 0;
		foreach($arr_use_goods as $gs_id){
			$gs = get_goods($gs_id);
			$gd_img = get_it_image_url($gs_id, $gs[simg1], 40, 40);
			$gd_name = cut_str($gs[gname],70);
			$gd_price = number_format($gs[account]);

			if($gs[index_no]) {
				$ck_use_goods .= "<li id='chk_".$gs_id."'>";
				$ck_use_goods .= "<a href='".TW_SHOP_URL."/view.php?index_no=$gs_id' target='_blank'><img src='".$gd_img."'  class=\"pr_img\"></a>";
				$ck_use_goods .= "<p>".$gd_name."</p><p class=\"mart5 bold\">".$gd_price."원</p>";
				$ck_use_goods .= "<a href=\"javascript:chk_del('".$gs_id."');\" class=\"bt_del\"><img src='/admin/image/bt_delete.gif'></a>";
				$ck_use_goods .= "</li>";

				$cnt_use_goods++;
			}
		}
	}

	// 다운로드 (누적제한, 레벨제한)
	$arr_dlimit = array();
	$arr_dlevel = array();
	for($i=0; $i<5; $i++) {
		if($coupon[cp_type] == $i) {
			$arr_dlimit[$i] = $coupon[cp_dlimit];
			$arr_dlevel[$i] = $coupon[cp_dlevel];
			break;
		}
	}
}

$frm_submit = '<div class="btn_confirm">
	<input type="submit" value="저장" class="btn_large" accesskey="s">
	<a href="goods.php?code=coupon'.$qstr.'" class="btn_large bx-white marl3">목록</a>'.PHP_EOL;
if($w == 'u') {
	$frm_submit .= '<a href="goods.php?code=coupon_form" class="btn_large bx-red marl3">추가</a>'.PHP_EOL;
}
$frm_submit .= '</div>';
?>

<form name="fregform" method="post" onsubmit="return fregform_submit(this);" autocomplete="off">
<input type="hidden" name="w" value="<?php echo $w; ?>">
<input type="hidden" name="sst" value="<?php echo $sst; ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
<input type="hidden" name="stx" value="<?php echo $stx; ?>">
<input type="hidden" name="j_sdate" value="<?php echo $j_sdate; ?>">
<input type="hidden" name="j_ddate" value="<?php echo $j_ddate; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">
<input type="hidden" name="cp_id" value='<?php echo $cp_id; ?>'>
<input type="hidden" name="cp_dlimit" value="<?php echo $coupon[cp_dlimit]; ?>">
<input type="hidden" name="cp_dlevel" value="<?php echo $coupon[cp_dlevel]; ?>">

<h2>쿠폰유형선택</h2>
<div class="tbl_head01">
	<table>
	<colgroup>
		<col>
		<col width="200px">
		<col width="120px">
		<col width="80px">
	</colgroup>
	<thead>
	<tr>
		<th>쿠폰발급 유형</th>
		<th>누적 다운로드 제한</th>
		<th>레벨 다운로드 제한</th>
		<th>발급주기</th>
	</tr>
	</thead>
	<tbody>
	<tr class='<?php echo ($coupon[cp_type]=='0')?"list1":"list0"; ?>'>
		<td class="tal">
			<input id="ids_cp_type0" type="radio" name="cp_type" value="0" <?php echo ($coupon[cp_type]=='0')?"checked":""; ?> onclick="chk_show('0');">
			<label for="ids_cp_type0"><b>발행 날짜 지정</b></label>
			<span class="vam fc_197">(Ex : 11월 20일에만 5% 할인)</span>
		</td>
		<td>최대 <input class="frm_input w80" type="text" name="cp_dlimit0" value='<?php echo $arr_dlimit[0]; ?>'> 건 <a href="javascript:chk_obj('cp_dlimit0', '99999999999');" class="btn_small bx-white marl5">무제한</a></td>
		<td><?php echo get_goods_level_select('cp_dlevel0', $arr_dlevel[0]); ?></td>
		<td>자유</td>
	</tr>
	<tr class='<?php echo ($coupon[cp_type]=='1')?"list1":"list0"; ?>'>
		<td class="tal">
			<input id="ids_cp_type1" type="radio" name="cp_type" value="1" <?php echo ($coupon[cp_type]=='1')?"checked":""; ?> onclick="chk_show('1');">
			<label for="ids_cp_type1"><b>발행 시간/요일 지정</b></label>
			<span class="vam fc_197">(Ex : 11월 한달 동안 매일/지정)</span>
		</td>
		<td>최대 <input class="frm_input w80" type="text" name="cp_dlimit1" value='<?php echo $arr_dlimit[1]; ?>'> 건 <a href="javascript:chk_obj('cp_dlimit1', '99999999999');" class="btn_small bx-white marl5">무제한</a></td>
		<td><?php echo get_goods_level_select('cp_dlevel1', $arr_dlevel[1]); ?></td>
		<td>자유</td>
	</tr>
	<tr class='<?php echo ($coupon[cp_type]=='2')?"list1":"list0"; ?>'>
		<td class="tal">
			<input id="ids_cp_type2" type="radio" name="cp_type" value="2" <?php echo ($coupon[cp_type]=='2')?"checked":""; ?> onclick="chk_show('2');">
			<label for="ids_cp_type2"><b>성별구분으로 발급</b></label>
			<span class="vam fc_197">(Ex : 11월 20일에 여성고객에게)</span>
		</td>
		<td>최대 <input class="frm_input w80" type="text" name="cp_dlimit2" value='<?php echo $arr_dlimit[2]; ?>'> 건 <a href="javascript:chk_obj('cp_dlimit2', '99999999999');" class="btn_small bx-white marl5">무제한</a></td>
		<td><?php echo get_goods_level_select('cp_dlevel2', $arr_dlevel[2]); ?></td>
		<td>자유</td>
	</tr>
	<tr class='<?php echo ($coupon[cp_type]=='3')?"list1":"list0"; ?>'>
		<td class="tal">
			<input id="ids_cp_type3" type="radio" name="cp_type" value="3" <?php echo ($coupon[cp_type]=='3')?"checked":""; ?> onclick="chk_show('3');">
			<label for="ids_cp_type3"><b>회원 생일자 발급</b></label>
			<span class="vam fc_197">(Ex : 행복하고 즐거운 생일쿠폰)</span>
		</td>
		<td>최대 <input class="frm_input w80" type="text" name="cp_dlimit3" value='<?php echo $arr_dlimit[3]; ?>'> 건 <a href="javascript:chk_obj('cp_dlimit3', '99999999999');" class="btn_small bx-white marl5">무제한</a></td>
		<td><?php echo get_goods_level_select('cp_dlevel3', $arr_dlevel[3]); ?></td>
		<td>1년에 한번</td>
	</tr>
	<tr class='<?php echo ($coupon[cp_type]=='4')?"list1":"list0"; ?>'>
		<td class="tal">
			<input id="ids_cp_type4" type="radio" name="cp_type" value="4" <?php echo ($coupon[cp_type]=='4')?"checked":""; ?> onclick="chk_show('4');">
			<label for="ids_cp_type4"><b>연령 구분으로 발급</b></label>
			<span class="vam fc_197">(Ex : 11월 20일에 80년생부터)</span>
		</td>
		<td>최대 <input class="frm_input w80" type="text" name="cp_dlimit4" value='<?php echo $arr_dlimit[4]; ?>'> 건 <a href="javascript:chk_obj('cp_dlimit4', '99999999999');" class="btn_small bx-white marl5">무제한</a></td>
		<td><?php echo get_goods_level_select('cp_dlevel4', $arr_dlevel[4]); ?></td>
		<td>자유</td>
	</tr>
	<tr class='<?php echo ($coupon[cp_type]=='5')?"list1":"list0"; ?>'>
		<td class="tal">
			<input id="ids_cp_type5" type="radio" name="cp_type" value="5" <?php echo ($coupon[cp_type]=='5')?"checked":""; ?> onclick="chk_show('5');">
			<label for="ids_cp_type5"><b>신규회원가입 발급</b></label>
			<span class="vam fc_197">(Ex : 11월 회원가입시 3,000원)</span>
		</td>
		<td colspan="2">신규회원 가입시 자동발급 (다운로드 쿠폰 아님)</td>
		<td>최초 1회</td>
	</tr>
	</tbody>
	</table>
</div>

<h2>필수입력사항</h2>
<div class="tbl_frm02">
	<table>
	<colgroup>
		<col width="180px">
		<col width="">
	</colgroup>
	<tbody>
	<?php if($w == "u") { ?>
	<tr>
		<th>쿠폰 생성일</th>
		<td class="fc_7d6 bold"><?php echo $coupon[cp_wdate]; ?></td>
	</tr>
	<?php if(substr($coupon[cp_udate],0,1) != '0') { ?>
	<tr>
		<th>최근 수정일</th>
		<td class="fc_7d6 bold"><?php echo $coupon[cp_udate]; ?></td>
	</tr>
	<?php }
	}
	?>
	<tr>
		<th>쿠폰명</th>
		<td>
			<input class="frm_input w325" type="text" name="cp_subject" value='<?php echo get_text($coupon[cp_subject]); ?>' required itemname="쿠폰명">
			<span class="fc_197 marl5">(Ex : 오픈기념쿠폰, 추석할인쿠폰)</span>
		</td>
	</tr>
	<tr>
		<th>설명</th>
		<td>
			<input class="frm_input w325" type="text" name="cp_explan" value='<?php echo get_text($coupon[cp_explan]); ?>' size='60'>
			<span class="fc_197 marl5">(Ex : 특가이벤트! 여름상품 10% 할인쿠폰)</span>
		</td>
	</tr>
	<tr>
		<th>사용여부</th>
		<td class="td_label">
			<input id="ids_cp_use2" type="radio" name="cp_use" value="1" <?php echo ($coupon[cp_use]=='1')?'checked':''; ?>> <label for="ids_cp_use2">사용</label>
			<input id="ids_cp_use1" type="radio" name="cp_use" value="0" <?php echo ($coupon[cp_use]=='0')?'checked':''; ?>> <label for="ids_cp_use1">중지</label>
		</td>
	</tr>
	<tr id="ids_download">
		<th>쿠폰발급 시점</th>
		<td>
			<p>
				<input id="ids_cp_download1" type="radio" name="cp_download" value="0" <?php echo ($coupon[cp_download]=='0')?'checked':''; ?>>
				<label for="ids_cp_download1">회원직접 다운로드</label>
				<span class="fc_197 marl5">(상품상세정보에서 회원이 직접 쿠폰을 다운로드받습니다)</span>
			</p>
			<p class="mart3">
				<input id="ids_cp_download2" type="radio" name="cp_download" value="1" <?php echo ($coupon[cp_download]=='1')?'checked':''; ?>>
				<label for="ids_cp_download2">주문완료 후 자동발급</label>
				<span class="fc_197 marl5">(주문완료 즉시 쿠폰을 발급합니다. 입금대기 상태에서도 고객이 쿠폰을 받을 수 있습니다)</span>
			</p>
			<p class="mart3">
				<input id="ids_cp_download3" type="radio" name="cp_download" value="2" <?php echo ($coupon[cp_download]=='2')?'checked':''; ?>>
				<label for="ids_cp_download3">주문완료 후 배송완료시에 자동발급</label>
				<span class="fc_197 marl5">(주문상태가 배송완료로 변경되는 시점에 쿠폰을 발급합니다)</span>
			</p>
		</td>
	</tr>
	<tr>
		<th>중복발급 여부</th>
		<td>
			<p>
				<input id="ids_cp_overlap1" type="radio" name="cp_overlap" value="0" <?php echo ($coupon[cp_overlap]=='0')?'checked':''; ?>>
				<label for="ids_cp_overlap1">중복발급 안함</label>
				<span class="fc_197 marl5">(한 고객에게 무조건 1회만 쿠폰을 발급합니다)</span>
			</p>
			<p class="mart3">
				<input id="ids_cp_overlap2" type="radio" name="cp_overlap" value="1" <?php echo ($coupon[cp_overlap]=='1')?'checked':''; ?>>
				<label for="ids_cp_overlap2">중복발급 허용</label>
				<span class="fc_197 marl5">(쿠폰을 사용한 후 다음번 주문시에도 같은 상품의 쿠폰다운로드를 허용합니다)</span>
			</p>
		</td>
	</tr>
	<tr>
		<th>해택</th>
		<td>
			<p>
				<input type="radio" name="cp_sale_type" value="0" <?php echo ($coupon[cp_sale_type]=='0')?'checked':''; ?>>
				(상품 판매가 x 수량) 의
				<input class="frm_input w80" type="text" name="cp_sale_percent" value='<?php echo $coupon[cp_sale_percent]; ?>'numeric itemname="할인률">
				% 할인, 최대
				<input class="frm_input w80" type="text" name="cp_sale_amt_max" value='<?php echo $coupon[cp_sale_amt_max]; ?>' numeric itemname="최대 할인금액"> 원
			</p>
			<p class="mart3">
				<input type="radio" name="cp_sale_type" value="1" <?php echo ($coupon[cp_sale_type]=='1')?'checked':''; ?>>
				<input class="frm_input w80" type="text" name="cp_sale_amt" value='<?php echo $coupon[cp_sale_amt]; ?>' numeric itemname="할인금액"> 원 할인
			</p>
		</td>
	</tr>
	<tr>
		<th>중복사용 여부</th>
		<td>
			<p>
				<input id="ids_cp_dups1" type="radio" name="cp_dups" value="0" <?php echo ($coupon[cp_dups]=='0')?'checked':''; ?>>
				<label for="ids_cp_dups1">이 쿠폰은 동일한 주문건에 중복해서 동시에 사용할 수 있습니다.</label>
			</p>
			<p class="mart3">
				<input id="ids_cp_dups2" type="radio" name="cp_dups" value="1" <?php echo ($coupon[cp_dups]=='1')?'checked':''; ?>>
				<label for="ids_cp_dups2">이 쿠폰은 동일한 주문건에 중복해서 동시에 사용할 수 없습니다.</label>
			</p>
		</td>
	</tr>
	</tbody>
	</table>
</div>

<div class="tbl_frm02" id="ids_show">
	<h2>세부입력사항</h2>
	<table>
	<colgroup>
		<col width="180px">
		<col width="">
	</colgroup>
	<tbody>
	<tr id="ids_date">
		<th>쿠폰발행 기간</th>
		<td>
			<input type='text' name="cp_pub_sdate" value='<?php echo $coupon[cp_pub_sdate]; ?>' id="pub_sdate" class="frm_input w80"> <a href="javascript:chk_obj('cp_pub_sdate', '9999999999');" class="btn_small bx-white">무제한</a> ~
			<input type='text' value='<?php echo $coupon[cp_pub_edate]; ?>' name="cp_pub_edate" id="pub_edate" class="frm_input w80"> <a href="javascript:chk_obj('cp_pub_edate', '9999999999');" class="btn_small bx-white">무제한</a>
		</td>
	</tr>
	<tr id="ids_birthday">
		<th>쿠폰발행 기간 (생일)</th>
		<td>
			<input class="frm_input w60" type="text" name="cp_pub_sday" value='<?php echo $coupon[cp_pub_sday]; ?>' numeric itemname="쿠폰발행 기간 (생일)"> 일 전부터 ~
			<input class="frm_input w60" type="text" name="cp_pub_eday" value='<?php echo $coupon[cp_pub_eday]; ?>' numeric itemname="쿠폰발행 기간 (생일)"> 일 이후까지
			<span class="fc_197 marl5">(Ex : 3일 전부터 5일 후까지)</span>
		</td>
	</tr>
	<tr id="ids_sex">
		<th>성별</th>
		<td class="td_label">
			<input id="ids_cp_use_sex1" type="radio" name="cp_use_sex" <?php echo ($coupon[cp_use_sex]=='')?"checked":""; ?> value=""> <label for="ids_cp_use_sex1">모든고객</label>
			<input id="ids_cp_use_sex2" type="radio" name="cp_use_sex" <?php echo ($coupon[cp_use_sex]=='F')?"checked":""; ?> value="F"> <label for="ids_cp_use_sex2">여성고객</label>
			<input id="ids_cp_use_sex3" type="radio" name="cp_use_sex" <?php echo ($coupon[cp_use_sex]=='M')?"checked":""; ?> value="M"> <label for="ids_cp_use_sex3">남성고객</label>
		</td>
	</tr>
	<tr id="ids_age">
		<th>연령대</th>
		<td>
			<input class="frm_input w60" type="text" name="cp_use_sage" value='<?php echo $coupon[cp_use_sage]; ?>' maxlength="4" numeric itemname="연령대"> 년생부터 ~
			<input class="frm_input w60" type="text" name="cp_use_eage" value='<?php echo $coupon[cp_use_eage]; ?>' maxlength="4" numeric itemname="연령대"> 년생까지
			<span class="fc_197 marl5">생년월일 앞 4자리만 입력 (Ex : <?php echo $time_year-5; ?>년생부터 <?php echo $time_year; ?>년생까지)</span>
		</td>
	</tr>
	<tr id="ids_day">
		<th>쿠폰발행 요일</th>
		<td class="td_label">
			<?php
			$ar_week_day = $coupon[cp_week_day] ? $coupon[cp_week_day] : '';
			$cp_week_day = explode(",",$ar_week_day);
			$arr_yoil = array ("일", "월", "화", "수", "목", "금", "토");
			for($i=0; $i<7; $i++) {
				unset($checked);
				if(in_array($arr_yoil[$i], $cp_week_day)){ $checked = "checked";}

				echo "<input id='ids_week_day{$i}' type='checkbox' name='cp_week_day[]' value='{$arr_yoil[$i]}' $checked> <label for='ids_week_day{$i}'>{$arr_yoil[$i]}요일</label> ";
			} ?>
		</td>
	</tr>
	<tr id="ids_hour1">
		<th rowspan="3">발행시간 / 매수</th>
		<td>
			<input type="checkbox" name="cp_pub_1_use" value="1" <?php echo ($coupon[cp_pub_1_use])?"checked":""; ?>>
			<input class="frm_input w30" type="text" name="cp_pub_shour1" value='<?php echo $coupon[cp_pub_shour1]; ?>' maxlength="2" numeric itemname="발행시간">
			<a href="javascript:chk_obj('cp_pub_shour1', '99');">무제한</a> 시 부터 ~
			<input class="frm_input w30" type="text" name="cp_pub_ehour1" value='<?php echo $coupon[cp_pub_ehour1]; ?>' maxlength="2" numeric itemname="발행시간">
			<a href="javascript:chk_obj('cp_pub_ehour1', '99');">무제한</a> 시 까지 /
			<input class="frm_input w70" type="text" name="cp_pub_1_cnt" value='<?php echo $coupon[cp_pub_1_cnt]; ?>' numeric itemname="매수"> 매
			<span class="fc_197 marl5">(Ex : 08시 ~ 12시까지 / 선착순 : 100매)</span>
		</td>
	</tr>
	<tr id="ids_hour2">
		<td>
			<input type="checkbox" name="cp_pub_2_use" value="1" <?php echo ($coupon[cp_pub_2_use])?"checked":""; ?>>
			<input class="frm_input w30" type="text" name="cp_pub_shour2" value='<?php echo $coupon[cp_pub_shour2]; ?>' maxlength="2" numeric itemname="발행시간">
			<a href="javascript:chk_obj('cp_pub_shour2', '99');">무제한</a> 시 부터 ~
			<input class="frm_input w30" type="text" name="cp_pub_ehour2" value='<?php echo $coupon[cp_pub_ehour2]; ?>' maxlength="2" numeric itemname="발행시간">
			<a href="javascript:chk_obj('cp_pub_ehour2', '99');">무제한</a> 시 까지 /
			<input class="frm_input w70" type="text" name="cp_pub_2_cnt" value='<?php echo $coupon[cp_pub_2_cnt]; ?>' numeric itemname="매수"> 매
			<span class="fc_197 marl5">(Ex : 14시 ~ 16시까지 / 선착순 : 100매)</span>
		</td>
	</tr>
	<tr id="ids_hour3">
		<td>
			<input type="checkbox" name="cp_pub_3_use" value="1" <?php echo ($coupon[cp_pub_3_use])?"checked":""; ?>>
			<input class="frm_input w30" type="text" name="cp_pub_shour3" value='<?php echo $coupon[cp_pub_shour3]; ?>' maxlength="2" numeric itemname="발행시간">
			<a href="javascript:chk_obj('cp_pub_shour3', '99');">무제한</a> 시 부터 ~
			<input class="frm_input w30" type="text" name="cp_pub_ehour3" value='<?php echo $coupon[cp_pub_ehour3]; ?>' maxlength="2" numeric itemname="발행시간">
			<a href="javascript:chk_obj('cp_pub_ehour3', '99');">무제한</a>  시 까지 /
			<input class="frm_input w70" type="text" name="cp_pub_3_cnt" value='<?php echo $coupon[cp_pub_3_cnt]; ?>' numeric itemname="매수"> 매
			<span class="fc_197 marl5">(Ex : 18시 ~ 20시까지 / 선착순 : 100매)</span>
		</td>
	</tr>
	<tr>
		<th>쿠폰유효 기간</th>
		<td class="td_label">
			<div>
				<input id="ids_inv_date1" type="radio" name="cp_inv_type" <?php echo ($coupon[cp_inv_type]=='0')?"checked":""; ?> value="0" onclick="chk_inv_show('0')">
				<label for="ids_inv_date1">시작일, 종료일 선택</label>
				<input id="ids_inv_date2" type="radio" name="cp_inv_type" <?php echo ($coupon[cp_inv_type]=='1')?"checked":""; ?> value="1" onclick="chk_inv_show('1')">
				<label for="ids_inv_date2">발급일로부터 기간 제한</label>
			</div>
			<div id="ids_inv_type0" class="mart7">
				<input type='text' name="cp_inv_sdate" value='<?php echo $coupon[cp_inv_sdate]; ?>' id="inv_sdate" class="frm_input w80"> <a href="javascript:chk_obj('cp_inv_sdate', '9999999999');" class="btn_small bx-white">무제한</a> ~
				<input type='text' name="cp_inv_edate" value='<?php echo $coupon[cp_inv_edate]; ?>' id="inv_edate"  class="frm_input w80"> <a href="javascript:chk_obj('cp_inv_edate', '9999999999');" class="btn_small bx-white">무제한</a>

				<input class="frm_input w30 marl10" type="text" name="cp_inv_shour1" value='<?php echo $coupon[cp_inv_shour1]; ?>' maxlength=2 numeric itemname="유효시간">
				<a href="javascript:chk_obj('cp_inv_shour1', '99');" class="btn_small bx-white">무제한</a> 시 부터 ~
				<input class="frm_input w30" type="text" name="cp_inv_shour2" value='<?php echo $coupon[cp_inv_shour2]; ?>' maxlength=2 numeric itemname="유효시간">
				<a href="javascript:chk_obj('cp_inv_shour2', '99');" class="btn_small bx-white">무제한</a> 시 까지
			</div>
			<div id="ids_inv_type1" class="mart7">
				쿠폰발급일로부터 <input class="frm_input" type="text" name="cp_inv_day" value='<?php echo $coupon[cp_inv_day]; ?>' numeric itemname="발급일" size='3'> 일까지 사용기간을 제한
			</div>
		</td>
	</tr>
	<tr>
		<th>금액제한</th>
		<td>
			{상품 판매가 x 수량}+{좌동}+{좌동}…이 <input class="frm_input w80" type="text" name="cp_low_amt" value="<?php echo $coupon[cp_low_amt]; ?>" numeric itemname="금액제한"> 원 이상이면 사용가능.
			<span class="fc_197 marl5">(Ex : 2만원이상 구매시)</span>
		</td>
	</tr>
	<tr>
		<th>사용가능대상</th>
		<td>
			<p>
				<label><input type="radio" name="cp_use_part" value="0" <?php echo ($coupon[cp_use_part]=='0')?"checked":""; ?> onclick="chk_use_show('0');"> 전체상품에 쿠폰사용 가능</label>
			</p>
			<p class="mart3">
				<label><input type="radio" name="cp_use_part" value="1" <?php echo ($coupon[cp_use_part]=='1')?"checked":""; ?> onclick="chk_use_show('1');"> 일부 상품만 쿠폰사용 가능</label>
			</p>
			<p class="mart3">
				<label><input type="radio" name="cp_use_part" value="2" <?php echo ($coupon[cp_use_part]=='2')?"checked":""; ?> onclick="chk_use_show('2');"> 일부 카테고리만 쿠폰사용 가능</label>
			</p>
			<p class="mart3">
				<label><input type="radio" name="cp_use_part" value="3" <?php echo ($coupon[cp_use_part]=='3')?"checked":""; ?> onclick="chk_use_show('3');"> 일부 상품은 쿠폰사용 불가</label>
			</p>
			<p class="mart3">
				<label><input type="radio" name="cp_use_part" value="4" <?php echo ($coupon[cp_use_part]=='4')?"checked":""; ?> onclick="chk_use_show('4');"> 일부 카테고리는 쿠폰사용 불가</label>
			</p>
		</td>
	</tr>
	<tr id="ids_gpart">
		<th>쿠폰<span id="g_title"></span> 상품</th>
		<td>
			<input type="hidden" name="cp_use_goods" id="cp_use_goods" value='<?php echo $coupon[cp_use_goods]; ?>'>
			<input type="hidden" name="cp_use_category" value="">
			<p>
			전체 : <b><font color="4C64AB"><?php echo number_format($cnt_use_goods); ?></font></b> 건
			<a href='./goods/goods_coupon_goods.php' onclick="openwindow(this,'cp_goods','850','800','yes'); return false;" class="btn_small red marl10">상품등록</a>
			</p>
			<ul id="ck_use_goods" class="chk_opli">
				<?php echo $ck_use_goods; ?>
			</ul>
		</td>
	</tr>
	<tr id="ids_cpart">
		<th> 쿠폰<span id="c_title"></span> 카테고리</th>
		<td>
			<p>
				<?php echo get_goods_sca_select('use_category') ?>
				<a href="javascript:chk_cate_add();" class="btn_small bx-white">저장</a>
			</p>
			<p class="mart5">
				<select name="use_category_list" size="10" class="frm_input wfull h100 list1">
				<?php
				for($i=0; $i<count($arr_category); $i++)
					if($arr_category[$i]) {
						echo "<option value='$arr_category[$i]'>".get_move_admin($arr_category[$i])." ($arr_category[$i])</option>\n";
					}
				?>
				</select>
			</p>
			<p class="mart5">
				<a href="javascript:chk_cate_move(document.fregform.use_category_list.selectedIndex, -1, document.fregform.use_category_list);" class="btn_small bx-white">위로</a>
				<a href="javascript:chk_cate_move(document.fregform.use_category_list.selectedIndex, +1, document.fregform.use_category_list);" class="btn_small bx-white">아래로</a>
				<a href="javascript:chk_cate_del();" class="btn_small bx-red">삭제</a>
			</p>
		</td>
	</tr>
	</tbody>
	</table>
</div>

<?php echo $frm_submit; ?>
</form>

<script language="javascript">
function fregform_submit(f) {
	var cp_type = false;
	var cp_use_part = false;
	var cp_week_day = false;
	var ck_id = '<?php echo $ck_join[cp_id]; ?>';
	var cp_id = '<?php echo $cp_id; ?>';

	if(f.cp_type[5].checked == true && (ck_id && ck_id != cp_id)) {
		alert('신규회원가입 발급은 중복해서 등록하실 수 없습니다!');
		return false;
	}

	for(var i=0; i<f.elements.length; i++){
		if(f.elements[i].name == "cp_type" && f.elements[i].checked==true){
			cp_type = true;
		}

		if(f.elements[i].name == "cp_use_part" && f.elements[i].checked==true){
			cp_use_part = true;
		}

		if(f.elements[i].name == "cp_week_day[]" && f.elements[i].checked==true){
			cp_week_day = true;
		}
	}

	if(cp_type == false) {
		alert('쿠폰발급 유형을 선택하세요!');
		return false;
	}

	for(var i=0; i<f.cp_type.length - 1; i++){
		if(f.cp_type[i].checked==true && !eval("f.cp_dlimit"+i).value) {
			alert('누적제한 : 항목은 필수 입력입니다.');
			eval("f.cp_dlimit"+i).style.backgroundColor = backcolor;
			eval("f.cp_dlimit"+i).focus();
			return false;
		}
	}

	// 쿠폰발행 기간
	if(f.cp_type[3].checked == false){
		if(!f.cp_pub_sdate.value){
			alert('쿠폰발행 기간 : 항목은 필수 입력입니다.');
			f.cp_pub_sdate.style.backgroundColor = backcolor;
			f.cp_pub_sdate.focus();
			return false;
		}
		if(!f.cp_pub_edate.value){
			alert('쿠폰발행 기간 : 항목은 필수 입력입니다.');
			f.cp_pub_edate.style.backgroundColor = backcolor;
			f.cp_pub_edate.focus();
			return false;
		}
	} else {
		if(!f.cp_pub_sday.value){
			alert('쿠폰발행 기간 (생일) : 항목은 필수 입력입니다.');
			f.cp_pub_sday.style.backgroundColor = backcolor;
			f.cp_pub_sday.focus();
			return false;
		}
		if(!f.cp_pub_eday.value){
			alert('쿠폰발행 기간 (생일) : 항목은 필수 입력입니다.');
			f.cp_pub_eday.style.backgroundColor = backcolor;
			f.cp_pub_eday.focus();
			return false;
		}
	}

	// 발행 시간/요일 지정
	if(f.cp_type[4].checked == true) {
		if(!f.cp_use_sage.value){
			alert('연령대 : 항목은 필수 입력입니다.');
			f.cp_use_sage.style.backgroundColor = backcolor;
			f.cp_use_sage.focus();
			return false;
		}
		if(!f.cp_use_eage.value){
			alert('연령대 : 항목은 필수 입력입니다.');
			f.cp_use_eage.style.backgroundColor = backcolor;
			f.cp_use_eage.focus();
			return false;
		}
	}

	// 발행 시간/요일 지정
	if(f.cp_type[1].checked == true) {
		if(cp_week_day == false) {
			alert('쿠폰발행 요일 :  하나이상은 체크 하셔야 합니다.');
			return false;
		}

		if(f.cp_pub_1_use.checked == false && f.cp_pub_2_use.checked == false && f.cp_pub_3_use.checked == false){
			alert('발행시간 / 매수 : 하나이상은 체크 하셔야 합니다.');
			f.cp_pub_1_use.focus();
			return false;
		}

		for(var i = 1; i<=3; i++) {
			if(eval("f.cp_pub_"+i+"_use").checked == true) {
				if(!eval("f.cp_pub_shour"+i).value){
					alert('발행시간 : 항목은 필수 입력입니다.');
					eval("f.cp_pub_shour"+i).style.backgroundColor = backcolor;
					eval("f.cp_pub_shour"+i).focus();
					return false;
				}
				if(!eval("f.cp_pub_ehour"+i).value){
					alert('발행시간 : 항목은 필수 입력입니다.');
					eval("f.cp_pub_ehour"+i).style.backgroundColor = backcolor;
					eval("f.cp_pub_ehour"+i).focus();
					return false;
				}
			}
		}
	}

	// 쿠폰유효 기간 (시작일, 종료일 선택)
	if(f.cp_inv_type[0].checked == true){
		if(!f.cp_inv_sdate.value){
			alert('쿠폰유효 기간 : 항목은 필수 입력입니다.');
			f.cp_inv_sdate.style.backgroundColor = backcolor;
			f.cp_inv_sdate.focus();
			return false;
		}
		if(!f.cp_inv_edate.value){
			alert('쿠폰유효 기간 : 항목은 필수 입력입니다.');
			f.cp_inv_edate.style.backgroundColor = backcolor;
			f.cp_inv_edate.focus();
			return false;
		}
		if(!f.cp_inv_shour1.value){
			alert('쿠폰유효 시간 : 항목은 필수 입력입니다.');
			f.cp_inv_shour1.style.backgroundColor = backcolor;
			f.cp_inv_shour1.focus();
			return false;
		}
		if(!f.cp_inv_shour2.value){
			alert('쿠폰유효 시간 : 항목은 필수 입력입니다.');
			f.cp_inv_shour2.style.backgroundColor = backcolor;
			f.cp_inv_shour2.focus();
			return false;
		}
	}

	// 쿠폰유효 기간 (발급일로부터 기간 제한)
	if(f.cp_inv_type[1].checked == true){
		if(!f.cp_inv_day.value){
			alert('쿠폰유효 기간 : 항목은 필수 입력입니다.');
			f.cp_inv_day.style.backgroundColor = backcolor;
			f.cp_inv_day.focus();
			return false;
		}
	}

	if(cp_use_part == false) {
		alert('사용가능대상을 선택하세요!');
		return false;
	}

	var tmp_str = '';
	var tmp_comma = '';
	for(var i=0;i<f.use_category_list.length;i++)
	{
		tmp_str += tmp_comma + f.use_category_list.options[i].value;
		tmp_comma = ',';
	}

	f.cp_use_category.value = tmp_str;

	//누적제한 , 레벨제한 최종 체크
	for(var i=0; i<f.cp_type.length - 1; i++){
		if(f.cp_type[i].checked==true && eval("f.cp_dlimit"+i).value) {
			f.cp_dlimit.value = eval("f.cp_dlimit"+i).value;
			f.cp_dlevel.value = eval("f.cp_dlevel"+i).value;
			break;
		}
	}

	f.action = "./goods/goods_coupon_form_update.php";
    return true;
}

// 분류 추가
function chk_cate_add() {
	var f = document.fregform;
	if(!f.use_category.value) {
		alert('카테고리를 선택하세요.');
		f.use_category.focus();
		return;
	}

	var n = f.use_category_list.length;
	var k = f.use_category.options.selectedIndex;
	f.use_category_list.options[n] = new Option();
	f.use_category_list.options[n].text  = f.use_category.options[k].text;
	f.use_category_list.options[n].value = f.use_category.options[k].value;
}

// 분류 삭제
function chk_cate_del() {
	var f = document.fregform;
	var chk  = 0;

	for(var i = 0; i < f.use_category_list.length; i++) {
		if(f.use_category_list.options[i].selected == true) {
			f.use_category_list.options[i] = null;
			chk++;
			break;
		}
	}

	if(!chk) {
		alert('제외시킬 항목을 선택해 주세요.');
		return;
	}
}

// 뷴류 이동
function chk_cate_move(index,to,list) {
	var f = document.fregform;
	var total = list.options.length-1;
	var chk = 0;

	for(var i = 0; i < list.length; i++) {
		if(list.options[i].selected == true) {
			chk = 1;
			break;
		}
	}

	if(!chk) {
		alert('이동할 항목을 선택해 주세요.');
		return;
	}

	if(index == -1) return false;
	if(to == +1 && index == total) return false;
	if(to == -1 && index == 0) return false;

	var items = new Array;
	var values = new Array;

	for(i = total; i >= 0; i--)
	{
		items[i] = list.options[i].text;
		values[i] = list.options[i].value;
	}

	for(i = total; i >= 0; i--) {
		if(index == i) {
			list.options[i + to] = new Option(items[i],values[i], 0, 1);
			list.options[i] = new Option(items[i + to], values[i + to]);
			i--;
		} else {
			list.options[i] = new Option(items[i], values[i]);
		}
	}
}

// 상품 삭제
function chk_del(gs_id){
	var cp_use_goods = document.getElementById('cp_use_goods');  // 실제 입력 값
	var ck_use_table = document.getElementById('chk_'+gs_id);

	ck_use_table.style.display ='none';
	cp_use_goods.value = cp_use_goods.value.replace(gs_id,'');

	var tmp_str = '';
	var tmp_comma = '';
	var tmp_use_goods = cp_use_goods.value.split(',');
	for(var i=0;i<tmp_use_goods.length;i++)
	{
		if(tmp_use_goods[i]){
			tmp_str += tmp_comma + tmp_use_goods[i];
			tmp_comma = ',';
		}
	}

	cp_use_goods.value = tmp_str;
}

// 필드별로 무제한설정
function chk_obj(f_obj, num){
	var f = document.fregform;
	eval("f."+f_obj).value = num;
}

// 쿠폰발급 유형
function chk_show(n) {
	var f = document.fregform;
	switch(n){	
		case '0': // 발행 날짜 지정
			eval("ids_show").style.display ='';
			eval("ids_download").style.display ='';
			eval("ids_sex").style.display ='none';
			eval("ids_age").style.display ='none';
			eval("ids_day").style.display ='none';
			eval("ids_hour1").style.display ='none';
			eval("ids_hour2").style.display ='none';
			eval("ids_hour3").style.display ='none';
			eval("ids_date").style.display ='';
			eval("ids_birthday").style.display ='none';
			break;		
		case '1': // 발행 시간/요일 지정
			eval("ids_show").style.display ='';
			eval("ids_download").style.display ='';
			eval("ids_sex").style.display ='none';
			eval("ids_age").style.display ='none';
			eval("ids_day").style.display ='';
			eval("ids_hour1").style.display ='';
			eval("ids_hour2").style.display ='';
			eval("ids_hour3").style.display ='';
			eval("ids_date").style.display ='';
			eval("ids_birthday").style.display ='none';
			break;		
		case '2': // 성별구분으로 발급
			eval("ids_show").style.display ='';
			eval("ids_download").style.display ='';
			eval("ids_sex").style.display ='';
			eval("ids_age").style.display ='none';
			eval("ids_day").style.display ='none';
			eval("ids_hour1").style.display ='none';
			eval("ids_hour2").style.display ='none';
			eval("ids_hour3").style.display ='none';
			eval("ids_date").style.display ='';
			eval("ids_birthday").style.display ='none';
			break;		
		case '3': // 회원 생일자 발급
			eval("ids_show").style.display ='';
			eval("ids_download").style.display ='';
			eval("ids_sex").style.display ='none';
			eval("ids_age").style.display ='none';
			eval("ids_day").style.display ='none';
			eval("ids_hour1").style.display ='none';
			eval("ids_hour2").style.display ='none';
			eval("ids_hour3").style.display ='none';
			eval("ids_date").style.display ='none';
			eval("ids_birthday").style.display ='';
			break;		
		case '4': // 연령 구분으로 발급
			eval("ids_show").style.display ='';
			eval("ids_download").style.display ='';
			eval("ids_sex").style.display ='none';
			eval("ids_age").style.display ='';
			eval("ids_day").style.display ='none';
			eval("ids_hour1").style.display ='none';
			eval("ids_hour2").style.display ='none';
			eval("ids_hour3").style.display ='none';
			eval("ids_date").style.display ='';
			eval("ids_birthday").style.display ='none';
			break;		
		case '5': // 신규회원가입 발급
			eval("ids_show").style.display ='';
			eval("ids_download").style.display ='none';
			eval("ids_sex").style.display ='none';
			eval("ids_age").style.display ='none';
			eval("ids_day").style.display ='none';
			eval("ids_hour1").style.display ='none';
			eval("ids_hour2").style.display ='none';
			eval("ids_hour3").style.display ='none';
			eval("ids_date").style.display ='';
			eval("ids_birthday").style.display ='none';
			break;
		default:
			eval("ids_show").style.display ='none';
			break;
	}
}

// 쿠폰유효 기간 유형
function chk_inv_show(n) {
	var f = document.fregform;
	switch(n){
		case '0':
			eval("ids_inv_type0").style.display ='';
			eval("ids_inv_type1").style.display ='none';
			break;
		case '1':
			eval("ids_inv_type0").style.display ='none';
			eval("ids_inv_type1").style.display ='';
			break;
	}
}

// 쿠폰유효 기간 유형
function chk_use_show(n) {
	var f = document.fregform;
	switch(n){
		case '1':
			eval("ids_gpart").style.display ='';
			eval("ids_cpart").style.display ='none';
			eval("g_title").innerHTML ='&nbsp;적용';
			eval("c_title").innerHTML ='&nbsp;적용';
			break;
		case '2':
			eval("ids_gpart").style.display ='none';
			eval("ids_cpart").style.display ='';
			eval("g_title").innerHTML ='&nbsp;적용';
			eval("c_title").innerHTML ='&nbsp;적용';
			break;
		case '3':
			eval("ids_gpart").style.display ='';
			eval("ids_cpart").style.display ='none';
			eval("g_title").innerHTML ='&nbsp;예외';
			eval("c_title").innerHTML ='&nbsp;예외';
			break;
		case '4':
			eval("ids_gpart").style.display ='none';
			eval("ids_cpart").style.display ='';
			eval("g_title").innerHTML ='&nbsp;예외';
			eval("c_title").innerHTML ='&nbsp;예외';
			break;
		default:
			eval("ids_gpart").style.display ='none';
			eval("ids_cpart").style.display ='none';
			break;
	}
}

$(function(){
	// 날짜 검색 : TODAY MAX값으로 인식 (maxDate: "+0d")를 삭제하면 MAX값 해제
	$("#pub_sdate,#pub_edate,#inv_sdate,#inv_edate").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99"});
});

chk_show('<?php echo $coupon[cp_type]; ?>');
chk_inv_show('<?php echo $coupon[cp_inv_type]; ?>');
chk_use_show('<?php echo $coupon[cp_use_part]; ?>');
</script>