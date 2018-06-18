<?php
if(!defined('_TUBEWEB_')) exit;

// 메인배너 출력
function get_display_mbn($mb_id)
{
	global $mk;

	$sql_where = " where bn_device = 'mobile' and bn_mobile_theme = '{$mk['mobile_theme']}' and bn_use='0' ";

	$r = sql_fetch("select * from shop_banner_slider {$sql_where} and mb_id='$mb_id' ");
	if(!$r['index_no']) $mb_id = 'admin';

	$bf = sql_fetch("select count(*) cnt from shop_banner_slider {$sql_where} and mb_id='$mb_id' ");
	$bf_cnt = (int)$bf['cnt'];

	$str = "";
	if($bf_cnt) {
		$sql = "select * from shop_banner_slider {$sql_where} and mb_id='$mb_id' order by bn_rank asc ";
		$result = sql_query($sql);
		for($i=0; $row = sql_fetch_array($result); $i++) {
			$a1 = $a2 = '';
			$file = TW_DATA_PATH.'/banner/'.$row['bn_file'];
			if(is_file($file) && $row['bn_file']) {
				if($row['bn_link']) {
					$a1 = "<a href=\"{$row['bn_link']}\" target=\"{$row['bn_target']}\">";
					$a2 = "</a>";
				}
				$file_url = TW_DATA_URL.'/banner/'.$row['bn_file'];
				$str .= $a1."<img src='".$file_url."'>".$a2;
			}
		}
	}

	return $str;
}

// 배너 출력
function get_display_bn($code, $mb_id)
{
	global $mk;

	if(!$code) return;

	$str = $a1 = $a2 = "";

	$sql_where = " where bn_mobile_theme = '{$mk['mobile_theme']}' and bn_code='$code' and bn_use='0' ";
	$sql_order = " order by rand() ";

	$row = sql_fetch(" select * from shop_banner {$sql_where} and mb_id='$mb_id' {$sql_order} ");
	if(!$row['index_no'] && $mb_id != 'admin') {
		$row = sql_fetch(" select * from shop_banner {$sql_where} and mb_id='admin' {$sql_order} ");
	}

	$file = TW_DATA_PATH.'/banner/'.$row['bn_file'];
	if(is_file($file) && $row['bn_file']) {
		if($row['bn_link']) {
			$a1 = "<a href=\"{$row['bn_link']}\" target=\"{$row['bn_target']}\">";
			$a2 = "</a>";
		}

		$file_url = TW_DATA_URL.'/banner/'.$row['bn_file'];
		$str = "{$a1}<img src=\"{$file_url}\">{$a2}";
	}

	return $str;
}

// 5차카테고리
function tree_category($catecode)
{
	global $tb, $config;

	$sql_search = " and p_hide='0' ";

	// 본사카테고리 고정일때
	if($config['p_use_cate'] == 1)
		$sql_search .= " and p_oper='y' ";

	$t_catecode = $catecode;

	$sql_common = " from {$tb['category_table']} ";
	$sql_where  = " where u_hide='0' {$sql_search} ";
	$sql_order  = " order by list_view asc ";

	$sql = " select count(*) as cnt {$sql_common} {$sql_where} and upcate = '$catecode' ";
	$res = sql_fetch($sql);
	if($res['cnt'] < 1) {
		$catecode = substr($catecode,0,-3);
	}

	$sql = "select * {$sql_common} {$sql_where} and upcate = '$catecode' {$sql_order} ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		if($i==0) {
			echo '<div id="sct_ct">'.PHP_EOL;
			echo '<ul>'.PHP_EOL;
		}

		$addclass = "";
		if($t_catecode==$row['catecode'])
			$addclass = ' class="sct_here"';

		$href = $tb['bbs_root'].'/list.php?ca_id='.$row['catecode'];

		echo "<li><a href=\"{$href}\"{$addclass}>{$row['catename']}</a></li>\n";
	}

	if($i > 0) {
		echo '</ul>'.PHP_EOL;
		echo '</div>'.PHP_EOL;
	}
}

// get_display_goods("영역", "출력수", "타이틀", "클래스명")
function get_display_goods($type, $rows, $mtxt, $li_css='')
{
	global $tb, $default, $pt_id, $theme_url;

	echo "<h2 class=\"mtit\"><span>{$mtxt}</span></h2>\n";
	echo "<p class=\"sct_li_type\">\n";
		echo "<a href=\"\"><img src=\"{$theme_url}/img/bt_litype1.gif\"></a>\n";
		echo "<a href=\"wli2\"><img src=\"{$theme_url}/img/bt_litype2_on.gif\"></a>\n";
		echo "<a href=\"wli3\"><img src=\"{$theme_url}/img/bt_litype3.gif\"></a>\n";
	echo "</p>\n";

	echo "<ul class=\"{$li_css}\">\n";
	$result = display_itemtype($pt_id, $type, $rows);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$it_href = $tb['bbs_root'].'/view.php?gs_id='.$row['index_no'];
		$it_imageurl = get_it_image_url($row['index_no'], $row['simg2'], $default['cf_item_medium_wpx'], $default['cf_item_medium_hpx']);
		$it_name = get_text($row['gname']);
		$it_price = get_price($row['index_no']);
		$it_amount = get_sale_price($row['index_no']);
		$it_point = display_point($row['gpoint']);

		// (시중가 - 할인판매가) / 시중가 X 100 = 할인률%
		$it_sprice = $sale = '';
		if($row['saccount'] > $it_amount && !is_uncase($row['index_no'])) {
			$sett = ($row['saccount'] - $it_amount) / $row['saccount'] * 100;
			$sale = '<span class="sale">['.number_format($sett,0).'%]</span>';
			$it_sprice = display_price2($row['saccount']);
		}

		echo "<li>\n";
			echo "<a href=\"{$it_href}\">\n";
			echo "<dl>\n";
				echo "<dt><img src=\"{$it_imageurl}\"></dt>\n";
				echo "<dd class=\"pname\">{$it_name}</dd>\n";
				if($row['info_color']) {
					echo "<dd class=\"op_color\">\n";
					$arr = explode(",", trim($row['info_color']));
					for($g=0; $g<count($arr); $g++) {
						echo get_color_boder(trim($arr[$g]), 1);
					}
					echo "</dd>\n";
				}
				echo "<dd class=\"price\">{$it_sprice}{$it_price}</dd>\n";
			echo "</dl>\n";
		echo "</a>\n";
		echo "<span onclick='javascript:itemlistwish(\"$row[index_no]\")' id='$row[index_no]' class='$row[index_no] ".zzimCheck($row['index_no'])."'></span>\n";
		echo "</li>\n";
	}
	echo "</ul>\n";
	echo "<p class=\"sct_btn\"><a href=\"$tb[bbs_root]/listtype.php?type=$type\" class=\"btn_lsmall bx-white wfull\">더보기 <i class=\"fa fa-angle-right marl3\"></i></a></p>\n";
}

// get_slide_goods("영역", "출력수", "타이틀", "클래스명")
function get_slide_goods($type, $rows, $mtxt, $li_css='')
{
	global $tb, $default, $pt_id;

	echo "<h2><span>{$mtxt}</span></h2>\n";
	echo "<div class=\"{$li_css}\">\n";

	$result = display_itemtype($pt_id, $type, $rows);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$it_href = $tb['bbs_root'].'/view.php?gs_id='.$row['index_no'];
		$it_imageurl = get_it_image_url($row['index_no'], $row['simg2'], $default['cf_item_medium_wpx'], $default['cf_item_medium_hpx']);
		$it_name = get_text($row['gname']);
		$it_price = get_price($row['index_no']);
		$it_amount = get_sale_price($row['index_no']);
		$it_point = display_point($row['gpoint']);

		// (시중가 - 할인판매가) / 시중가 X 100 = 할인률%
		$it_sprice = $sale = '';
		if($row['saccount'] > $it_amount && !is_uncase($row['index_no'])) {
			$sett = ($row['saccount'] - $it_amount) / $row['saccount'] * 100;
			$sale = '<span class="sale">['.number_format($sett,0).'%]</span>';
			$it_sprice = display_price2($row['saccount']);
		}

		echo "<dl>\n";
			echo "<a href=\"{$it_href}\">\n";
				echo "<dt><img src=\"{$it_imageurl}\"></dt>\n";
				echo "<dd class=\"pname\">{$it_name}</dd>\n";
				echo "<dd class=\"price\">{$it_sprice}{$it_price}</dd>\n";
			echo "</a>\n";
		echo "</dl>\n";
	}
	echo "</div>\n";
}

// 메인 고객상품평 배열을 리턴
function get_display_appraise($name, $rows)
{
	global $tb, $default, $pt_id;

	echo "<div class=\"main_post tline10\">\n";
	echo "<h2 class=\"m_tit\"><span class=\"mtxt\">$name</span></h2>\n";
	echo "<ul>\n";

	$sql_common = " from shop_goods_review ";
	$sql_search = " where (left(gs_se_id,3)='AP-' or gs_se_id = 'admin' or gs_se_id = '$pt_id') ";
	if($default['de_review_wr_use']) $sql_search .= " and pt_id = '$pt_id' ";
	$sql_order = " order by wdate desc limit $rows ";

	$sql = " select * $sql_common $sql_search $sql_order ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$gs = get_goods($row['gs_id']);
		$it_href = $tb['bbs_root'].'/view.php?gs_id='.$row['gs_id'];
		$it_name = get_text(cut_str($gs['gname'], 40));

		echo "<li>\n";
			echo "<a href=\"{$it_href}\">\n";
			echo "<p class=\"tit\">{$it_name}</p>\n";
			echo "<p>{$row['memo']}</p>\n";
			echo "</a>\n";
		echo "</li>\n";
	}

	if($i == 0) {
		echo "<li class=\"sct_noitem\">자료가 없습니다</li>\n";
	}

	echo "</ul>\n";
	echo "<p class=\"sct_btn\"><a href=\"$tb[bbs_root]/review.php\" class=\"btn_lsmall bx-white wfull\">더보기 <i class=\"fa fa-angle-right marl3\"></i></a></p>\n";
	echo "</div>\n";
}

// 최근게시물 추출
function get_display_board($bo_table, $rows)
{
	global $tb, $default, $pt_id;

	$sql_where = "";
	if($default['de_board_wr_use']) {
		$sql_where = " where pt_id = '$pt_id' ";
	}

	$sql = "select * from shop_board_{$bo_table} $sql_where order by wdate desc limit $rows ";
	$res = sql_query($sql);
	for($i=0;$row=sql_fetch_array($res);$i++){
		$subject = get_text($row['subject']);
		$wdate	= date('Y.m.d',intval($row['wdate'],10));
		echo "<a href=\"$tb[bbs_root]/board_read.php?boardid=$bo_table&index_no=$row[index_no]\">$subject</a>";
	}

	if($i==0){ echo "게시물이 없습니다"; }
}

// 인기 검색어 추출
function get_display_tick($name, $rows)
{
	global $tb, $pt_id;

	echo "<h2>{$name}</h2>\n";
	echo "<ul id='ticker'>\n";

	$sql_common = " from shop_keyword ";
	$sql_search = " where pt_id = '$pt_id' ";
	$sql_order  = " order by scount desc, old_scount desc limit $rows ";

	$sql = " select * $sql_common $sql_search $sql_order ";
	$result = sql_query($sql);
	for($i=0; $row = sql_fetch_array($result); $i++){
		if($row['old_scount'] > 0) // 과거 데이터가 있는 경우
			$rank_gap = $row['scount'] - $row['old_scount'];
		else // 과거 데이터가 없는 경우
			$rank_gap = 'N';

		if($rank_gap > 0)
			$u_rkw = "rkw_icon rkw_up";
		else if($rank_gap < 0)
			$u_rkw = "rkw_icon rkw_dw";
		else if($rank_gap == '0')
			$u_rkw = "rkw_icon rkw_sm";
		else
			$u_rkw = "rkw_icon rkw_nw";

		$rkn = $i + 1;
		echo "<li>\n";
			echo "<a href='$tb[bbs_root]/search.php?ss_tx=$row[keyword]'><span class='rkw_num'>{$rkn}</span> {$row['keyword']}</a>\n";
			echo "<span class='{$u_rkw}'>{$rank_gap}</span>\n";
		echo "</li>\n";
	}

	echo "</ul>\n";
}

// 금주의 인기 검색어 추출
function get_display_rank()
{
	global $tb, $pt_id;

	echo "<div class='m_rkw'>\n";
	echo "<ul>\n";

	$sql_common = " from shop_keyword ";
	$sql_search = " where pt_id = '$pt_id' ";
	$sql_order  = " order by scount desc, old_scount desc limit 10 ";

	$sql = " select * $sql_common $sql_search $sql_order ";
	$result = sql_query($sql);
	for($i=0; $row = sql_fetch_array($result); $i++) {
		if($row['old_scount'] > 0) // 과거 데이터가 있는 경우
			$rank_gap = $row['scount'] - $row['old_scount'];
		else // 과거 데이터가 없는 경우
			$rank_gap = 'N';

		if($rank_gap > 0)
			$u_rkw = "rkw_icon rkw_up";
		else if($rank_gap < 0)
			$u_rkw = "rkw_icon rkw_dw";
		else if($rank_gap == '0')
			$u_rkw = "rkw_icon rkw_sm";
		else
			$u_rkw = "rkw_icon rkw_nw";

		$rkn = $i + 1;
		echo "<li>\n";
			echo "<a href='$tb[bbs_root]/search.php?ss_tx=$row[keyword]'><span class='rkw_num'>{$rkn}</span> {$row['keyword']}</a>\n";
			echo "<span class='{$u_rkw}'>{$rank_gap}</span>\n";
		echo "</li>\n";
	}

	echo "</ul>\n";
	echo "</div>\n";
}

// alert 메세지 출력
function alert($msg,$move="back")
{
	if(!$msg) $msg = '올바른 방법으로 이용해 주십시오.';

	switch ($move)
	{
		case "back" :
			$url = "history.go(-1);void(1);";
			break;
		case "close" :
			$url = "window.close();";
			break;
		case "parent" :
			$url = "parent.document.location.reload();";
			break;
		case "replace" :
			$url = "opener.document.location.reload();window.close();";
			break;
		case "no" :
			$url = "";
			break;
		default :
			$url = "location.href='{$move}'";
			break;
	}

	echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\">";
	echo "<script type='text/javascript'>alert(\"{$msg}\");{$url}</script>";
	exit;
}

// 상품 가격정보의 배열을 리턴
function get_price($gs_id, $msg='<span>원</span>')
{
	global $member, $mb_yes;

	$gs = sql_fetch("select index_no,price_msg,buy_level,buy_only from shop_goods where index_no = '$gs_id'");

	$price = get_sale_price($gs_id);

	$mb_grade = $member['grade'];
	if(!$mb_yes) {
		$mb_grade = 10;
	}

	// 재고가 한정상태이고 재고가 없을때, 품절상태일때..
	if(is_soldout($gs['index_no'])) {
		$str = "<p class='soldout'>품절</p>";
	} else {
		if($gs['price_msg']) {
			$str = $gs['price_msg'];
		} else if($gs['buy_only'] == 1 && $mb_grade > $gs['buy_level']) {
			$str = "";
		} else if($gs['buy_only'] == 0 && $mb_grade > $gs['buy_level']) {
			if(!$mb_yes)
				$str = "<p class='memopen'>회원공개</p>";
			else
				$str = "<p class='mpr'>".number_format($price).$msg."</p>";
		} else {
			$str = "<p class='mpr'>".number_format($price).$msg."</p>";
		}
	}

	return $str;
}

// 쿠폰 : 상세내역
function get_cp_contents()
{
	global $row, $arr_use_part;

	$str = "";
	$str .= "<div>&#183; <strong>".get_text($row['cp_subject'])."</strong></div>";

	// 동시사용 여부
	$str .= "<div class='fc_197'>&#183; ";
	if(!$row['cp_dups']) {
		$str .= '동일한 주문건에 중복할인 가능';
	} else {
		$str .= '동일한 주문건에 중복할인 불가 (1회만 사용가능)';
	}
	$str .= "</div>";

	// 쿠폰유효 기간
	$str .= "<div>&#183; 쿠폰유효 기간 : ";
	if(!$row['cp_inv_type']) {
		// 날짜
		if($row['cp_inv_sdate'] == '9999999999') $cp_inv_sdate = '';
		else $cp_inv_sdate = $row['cp_inv_sdate'];

		if($row['cp_inv_edate'] == '9999999999') $cp_inv_edate = '';
		else $cp_inv_edate = $row['cp_inv_edate'];

		if($row['cp_inv_sdate'] == '9999999999' && $row['cp_inv_sdate'] == '9999999999')
			$str .= '제한없음';
		else
			$str .= $cp_inv_sdate . " ~ " . $cp_inv_edate;

		// 시간대
		$str .= "&nbsp;(시간대 : ";
		if($row['cp_inv_shour1'] == '99') $cp_inv_shour1 = '';
		else $cp_inv_shour1 = $row['cp_inv_shour1'] . "시부터";

		if($row['cp_inv_shour2'] == '99') $cp_inv_shour2 = '';
		else $cp_inv_shour2 = $row['cp_inv_shour2'] . "시까지";

		if($row['cp_inv_shour1'] == '99' && $row['cp_inv_shour1'] == '99')
			$str .= '제한없음';
		else
			$str .= $cp_inv_shour1 . " ~ " . $cp_inv_shour2 ;
		$str .= ")";
	} else {
		$cp_inv_day = date("Y-m-d",strtotime("+{$row[cp_inv_day]} days",strtotime($row['cp_wdate'])));
		$str .= '다운로드 완료 후 ' . $row['cp_inv_day']. '일간 사용가능, 만료일('.$cp_inv_day.')';
	}
	$str .= "</div>";

	// 혜택
	$str .= "<div>&#183; ";
	if($row['cp_sale_type'] == '0') {
		if($row['cp_sale_amt_max'] > 0)
			$cp_sale_amt_max = "&nbsp;(최대 ".display_price($row['cp_sale_amt_max'])."까지 할인)";
		else
			$cp_sale_amt_max = "";

		$str .= $row['cp_sale_percent']. '% 할인' . $cp_sale_amt_max;
	} else {
		$str .= display_price($row['cp_sale_amt']). ' 할인';
	}
	$str .= "</div>";

	// 최대금액
	if($row['cp_low_amt'] > 0) {
		$str .= "<div>&#183; ".display_price($row['cp_low_amt'])." 이상 구매시</div>";
	}

	// 사용가능대상
	$str .= "<div>&#183; ".$arr_use_part[$row['cp_use_part']]."</div>";

	return $str;
}

//  상품 상세페이지 : 배송비
function get_del_amt()
{
	global $gs, $config, $sr;

	// 공통설정
	if($gs['sc_type']=='0') {

		if($gs['mb_id'] == 'admin') {
			$sc_type	= $config['delivery_method'];
			$sc_103amt	= $config['delivery_103mon'];
			$sc_104amt	= $config['delivery_104mon'];
			$sc_minimum = $config['delivery_104mon_up'];
		} else {
			$sc_type	= $sr['delivery_method'];
			$sc_103amt	= $sr['delivery_103mon'];
			$sc_104amt	= $sr['delivery_104mon'];
			$sc_minimum = $sr['delivery_104mon_up'];
		}

		switch($sc_type) {
			case '101':
				$str = "무료배송";
				break;
			case '102':
				$str = "상품수령시 결제(착불)";
				break;
			case '103':
				$str = display_price($sc_103amt);
				break;
			case '104':
				$str = "무료~".display_price($sc_104amt)."&nbsp;(조건부무료)";
				break;
		}

		// sc_type(배송비 유형)		0:공통설정, 1:무료배송, 2:조건부 무료배송, 3:유료배송
		// sc_method(배송비 결제)	0:선불, 1:착불, 2:사용자선택
		if(in_array($sc_type, array('103','104'))) {
			if($gs['sc_method'] == 1)
				$str = '상품수령시 결제(착불)';
			else if($gs['sc_method'] == 2) {
				$str = "<select name=ct_send_cost style='width:100%'>
							<option value='0'>주문시 결제(선결제)</option>
							<option value='1'>상품수령시 결제(착불)</option>
						</select>";
			}
		}
	}

	// 무료배송
	else if($gs['sc_type']=='1') {
		$str = "무료배송";
	}

	// 조건부 무료배송
	else if($gs['sc_type']=='2') {
		$str = "무료~".display_price($gs['sc_amt'])."&nbsp;(조건부무료)";
	}

	// 유료배송
	else if($gs['sc_type']=='3') {
		$str = display_price($gs['sc_amt']);
	}

	// sc_type(배송비 유형)		0:공통설정, 1:무료배송, 2:조건부 무료배송, 3:유료배송
	// sc_method(배송비 결제)	0:선불, 1:착불, 2:사용자선택
	if(in_array($gs['sc_type'], array('2','3'))) {
		if($gs['sc_method'] == 1)
			$str = '상품수령시 결제(착불)';
		else if($gs['sc_method'] == 2) {
			$str = "<select name=ct_send_cost style='width:100%'>
						<option value='0'>주문시 결제(선결제)</option>
						<option value='1'>상품수령시 결제(착불)</option>
					</select>";
		}
	}

	return $str;
}

//  상품 상세페이지 : 구매하기, 장바구니, 찜 버튼
function get_buy_button($msg, $gs_id)
{
	global $gs, $pt_id;

	$ui_btn   = array("1"=>"구매하기","2"=>"장바구니","3"=>"찜하기");
	$ui_class = array("1"=>"btn_medium wset","2"=>"btn_medium bx-white","3"=>"btn_medium bx-white");

	$str = "<div class=\"sp_btn\">";
	for($i=1; $i<=3; $i++) {
		switch($i){
			case '1':
				$sw_direct = "buy";
				break;
			case '2':
				$sw_direct = "cart";
				break;
			case '3':
				$sw_direct = "wish";
				break;
		}

		if($msg) {
			if($sw_direct == "buy") {
				$str .= "<p><button type=\"button\" onclick=\"alert('$msg');\" class='$ui_class[$i]'>$ui_btn[$i]</button></p>";
			} else {
				$str .= "<span><button type=\"button\" onclick=\"alert('$msg');\" class='$ui_class[$i]'>$ui_btn[$i]</button></span>";
			}
		} else {
			if($sw_direct == "wish") {
				$str .= "<span><button type=\"button\" onclick=\"item_wish(document.fbuyform);\" class='$ui_class[$i]'>$ui_btn[$i]</button></span>";
			} else if($sw_direct == "buy") {
				$str .= "<p><button type=\"button\" onclick=\"fbuyform_submit('".$sw_direct."');\" class='$ui_class[$i]'>$ui_btn[$i]</button></p>";
			} else {
				$str .= "<span><button type=\"button\" onclick=\"fbuyform_submit('".$sw_direct."');\" class='$ui_class[$i]'>$ui_btn[$i]</button></span>";
			}
		}
	}

	$str .= "</div>";

	return $str;
}

//  상품 상세페이지 : 고객상품평
function get_goods_review($name, $cnt, $gs_id, $rows=10)
{
	global $tb, $member, $arr_sco, $pt_id, $default;

	$sql_common = " from shop_goods_review ";
	$sql_search = " where gs_id = '$gs_id' ";
	if($default['de_review_wr_use']) {
		$sql_search .= " and pt_id = '$pt_id' ";
	}

	$sql_order  = " order by wdate desc limit $rows ";

	echo "<div class=sp_vbox_mr>\n";
		echo "<ul>\n";
			echo "<li class='tlst'>$name <span class=cate_dc>($cnt)</span></li>\n";
			echo "<li class='trst'><a href=\"javascript:window.open('$tb[bbs_root]/view_user.php?gs_id=$gs_id');\">더보기</a><span class='im im_arr'></span></li>\n";
		echo "</ul>\n";
	echo "</div>\n";

	echo "<ul class=lst_w>\n";

	$sql = " select * $sql_common $sql_search $sql_order ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$tmp_date  = date("Y-m-d", $row['wdate']);
		$tmp_score = $arr_sco[$row['score']];

		$len = strlen($row['writer_s']);
		$str = substr($row['writer_s'],0,3);
		$tmp_name = $str.str_repeat("*",$len - 3);

		$hash = md5($row['index_no'].$row['wdate'].$row['writer_s']);

		echo "<li class='lst'><span class=lst_post>$row[memo]</span>";
		echo "<span class='lst_h'><span class='fc_255'>$tmp_score</span> ";
		echo "<span class='fc_999'> / $tmp_name / $tmp_date";
		if(is_admin() || ($member['id'] == $row['writer_s'])) {
			echo "&nbsp;&nbsp;&nbsp;<a href=\"javascript:window.open('$tb[bbs_root]/view_user_form.php?gs_id=$row[gs_id]&amp;me_id=$row[index_no]&amp;w=u');\" /><span class='under fc_blk'>수정</span></a>&nbsp;&nbsp;&nbsp;<a href=\"$tb[bbs_root]/view_user_form_update.php?gs_id=$row[gs_id]&amp;me_id=$row[index_no]&amp;w=d&amp;hash=$hash\" class='itemqa_delete'><span class='under fc_blk'>삭제</span></a>";
		}
		echo "</span></span>";
		echo "</li>\n";
	}

	if($i == 0) {
		echo "<li class=lst><span class='lst_a tac'>자료가 없습니다</span></li>\n";
	}

	echo "</ul>\n";
}

//  상품 상세페이지 : Q&A
function get_goods_qa($name, $cnt, $gs_id)
{
	global $tb, $member;

	echo "<div class=sp_vbox_qa>\n";
		echo "<ul>\n";
			echo "<li class='tlst'>$name <span class=cate_dc>($cnt)</span></li>\n";
			echo "<li class='trst'><a href=\"javascript:window.open('$tb[bbs_root]/qaform.php?gs_id=$gs_id');\" class='btn_lsmall bx-white'>Q&A쓰기</a></li>\n";
		echo "</ul>\n";
	echo "</div>\n";

	echo "<ul class=lst_w>\n";

	$sql = " select * from shop_goods_qa where gs_id='$gs_id' order by iq_time desc ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$iq_time = substr($row['iq_time'],0,10);

		$is_secret = false;
		if($row['iq_secret']) {
			$icon_secret = "<img src='".TW_IMG_URL."/icon/icon_secret.jpg' class='vam' alt='비밀글'>";

			if(is_admin() || $member['id' ] == $row['mb_id']) {
				$iq_answer = $row['iq_answer'];
			} else {
				$iq_answer = "";
				$is_secret = true;
			}
		} else {
			$icon_secret = "";
			$iq_answer = $row['iq_answer'];
		}

		if($row['iq_answer'])
			$icon_answer = "<span class='fc_7d6'>답변완료</span>&nbsp;&nbsp;";
		else
			$icon_answer = "<span class='fc_999'>답변대기</span>&nbsp;&nbsp;";

		$iq_subject = "";
		if(!$is_secret) { $iq_subject .= "<a href='javascript:void(0);' onclick=\"qna('".$i."')\">"; }
		$iq_subject .= "<span class=lst_post>".$icon_answer.$row['iq_subject']."</span>";

		$len = strlen($row['mb_id']);
		$str = substr($row['mb_id'],0,3);
		$mb_id = $str.str_repeat("*",$len - 3);

		$hash = md5($row['iq_id'].$row['iq_time'].$row['iq_ip']);

		echo "<li class='lst'>\n$iq_subject";
			echo "<span class='lst_h'><span class='fc_255'>$row[iq_ty]</span> ";
			echo "<span class='fc_999'> / $mb_id / $iq_time $icon_secret </span></span>";
			if(!$is_secret) { echo "</a>"; }

			echo "<div class='faq' id='qna".$i."' style='display:none;'>\n";
				echo "<table class='faqbody'>\n";
				echo "<tbody>\n";
				echo "<tr>\n";
					echo "<td class='mi_dt'><img src='".TW_IMG_URL."/sub/FAQ_Q.gif'></td>\n";
					echo "<td class='mi_bt fc_125'>\n".nl2br($row['iq_question']);

					if(is_admin() || $member['id' ] == $row['mb_id'] && !$iq_answer) {
						echo "<div class='padt10'><a href=\"javascript:window.open('$tb[bbs_root]/qaform.php?gs_id=$row[gs_id]&amp;iq_id=$row[iq_id]&amp;w=u');\" /><span class='under fc_blk'>수정</span></a>&nbsp;&nbsp;&nbsp;<a href=\"$tb[bbs_root]/qaform_update.php?gs_id=$row[gs_id]&amp;iq_id=$row[iq_id]&amp;w=d&amp;hash=$hash\" class='itemqa_delete'><span class='under fc_blk'>삭제</span></a></div>\n";
					}
					echo "</td>\n";
				echo "</tr>\n";

				if($iq_answer) {
					echo "<tr>\n";
						echo "<td class='mi_dt padt20'><img src='".TW_IMG_URL."/sub/FAQ_A.gif'></td>\n";
						echo "<td class='mi_bt padt20 fc_7d6'>".nl2br($iq_answer)."</td>\n";
					echo "</tr>\n";
				}
				echo "</tbody>\n";
				echo "</table>\n";
			echo "</div>\n";
		echo "</li>\n";
	}

	if($i == 0) {
		echo "<li class=lst><span class='lst_a tac'>자료가 없습니다</span></li>\n";
	}

	echo "</ul>\n";
}

// 페이징
function pageing($cur_page, $total_page, $total_count, $url)
{
	if(!$total_count) return;

	$retValue  = "<div class=\"pageing\">";

	if($cur_page > 1) {
		$retValue .= "<a class=\"prev btn_lsmall grey\" href='" . $url . ($cur_page-1) . "'>이전</a>";

	} else {
		$retValue .= "<a class=\"prev btn_lsmall bx-white\" href='javascript:void(0);'>이전</a>";
	}

	$start_page = ( ( (int)( ($cur_page - 1 ) / 5 ) ) * 5 ) + 1;
	$end_page	= $start_page + 4;

	if($end_page >= $total_page) $end_page = $total_page;
	if($total_page >= 1) {
		for($k=$start_page;$k<=$end_page;$k++) {
			if($cur_page != $k) {
				$retValue .= "<span><a href='$url$k'>{$k}</a></span>";
			} else {
				$retValue .= "<span class=\"active\"><a href=\"javascript:void(0);\">{$k}</a></span>";
			}
		}
	}


	if($cur_page < $total_page) {
		$retValue .= "<a class=\"next btn_lsmall grey\" href='$url" . ($cur_page+1) . "'>다음</a>";
	} else {
		$retValue .= "<a class=\"next btn_lsmall bx-white\" href='javascript:void(0);'>다음</a>";
	}

	$retValue .= "</div>";
	return $retValue;
}

// 상품 선택옵션
function get_item_options($gs_id, $subject, $event='')
{
	global $tb;

	if(!$gs_id || !$subject)
		return '';

	$amt = get_sale_price($gs_id);

	$sql = " select * from shop_goods_option where io_type = '0' and gs_id = '$gs_id' and io_use = '1' order by io_no asc ";
	$result = sql_query($sql);
	if(!sql_num_rows($result))
		return '';

	$str = '';
	$subj = explode(',', $subject);
	$subj_count = count($subj);

	if($subj_count > 1) {
		$options = array();

		// 옵션항목 배열에 저장
		for($i=0; $row=sql_fetch_array($result); $i++) {
			$opt_id = explode(chr(30), $row['io_id']);

			for($k=0; $k<$subj_count; $k++) {
				if(!is_array($options[$k]))
					$options[$k] = array();

				if($opt_id[$k] && !in_array($opt_id[$k], $options[$k]))
					$options[$k][] = $opt_id[$k];
			}
		}

		// 옵션선택목록 만들기
		for($i=0; $i<$subj_count; $i++) {
			$opt = $options[$i];
			$opt_count = count($opt);
			$disabled = '';
			if($opt_count) {
				$seq = $i + 1;
				if($i > 0)
					$disabled = ' disabled="disabled"';
				$str .= '<div class=sp_obox>'.PHP_EOL;
				$str .= '<ul>'.PHP_EOL;
				$str .= '<li class=tlst><label for="it_option_'.$seq.'">'.$subj[$i].'</label></li>'.PHP_EOL;

				$select  = '<select id="it_option_'.$seq.'" class="it_option"'.$disabled.' '.$event.'>'.PHP_EOL;
				$select .= '<option value="">(필수) 선택하세요</option>'.PHP_EOL;
				for($k=0; $k<$opt_count; $k++) {
					$opt_val = $opt[$k];
					if($opt_val) {
						$select .= '<option value="'.$opt_val.'">'.$opt_val.'</option>'.PHP_EOL;
					}
				}
				$select .= '</select>'.PHP_EOL;

				$str .= '<li class=trst>'.$select.'</li>'.PHP_EOL;
				$str .= '</ul>'.PHP_EOL;
				$str .= '</div>'.PHP_EOL;
			}
		}
	} else {
		$str .= '<div class=sp_obox>'.PHP_EOL;
		$str .= '<ul>'.PHP_EOL;
		$str .= '<li class=tlst><label for="it_option_1">'.$subj[0].'</label></li>'.PHP_EOL;

		$select  = '<select id="it_option_1" class="it_option" '.$event.'>'.PHP_EOL;
		$select .= '<option value="">(필수) 선택하세요</option>'.PHP_EOL;
		for($i=0; $row=sql_fetch_array($result); $i++) {
			if($row['io_price'] >= 0)
				$price = '&nbsp;&nbsp;(+'.display_price($row['io_price']).')';
			else
				$price = '&nbsp;&nbsp;('.display_price($row['io_price']).')';

			if(!$row['io_stock_qty'])
				$soldout = '&nbsp;&nbsp;[품절]';
			else
				$soldout = '';

			$select .= '<option value="'.$row['io_id'].','.$row['io_price'].','.$row['io_stock_qty'].','.$amt.'">'.$row['io_id'].$price.$soldout.'</option>'.PHP_EOL;
		}
		$select .= '</select>'.PHP_EOL;

		$str .= '<li class=trst>'.$select.'</li>'.PHP_EOL;
		$str .= '</ul>'.PHP_EOL;
		$str .= '</div>'.PHP_EOL;
	}

	return $str;
}

// 상품 추가옵션
function get_item_supply($gs_id, $subject, $event='')
{
	global $tb;

	if(!$gs_id || !$subject)
		return '';

	$sql = " select * from shop_goods_option where io_type = '1' and gs_id = '$gs_id' and io_use = '1' order by io_no asc ";
	$result = sql_query($sql);
	if(!sql_num_rows($result))
		return '';

	$str = '';

	$subj = explode(',', $subject);
	$subj_count = count($subj);
	$options = array();

	// 옵션항목 배열에 저장
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$opt_id = explode(chr(30), $row['io_id']);

		if($opt_id[0] && !array_key_exists($opt_id[0], $options))
			$options[$opt_id[0]] = array();

		if($opt_id[1]) {
			if($row['io_price'] >= 0)
				$price = '&nbsp;&nbsp;(+'.display_price($row['io_price']).')';
			else
				$price = '&nbsp;&nbsp;('.display_price($row['io_price']).')';
			$io_stock_qty = get_option_stock_qty($gs_id, $row['io_id'], $row['io_type']);

			if($io_stock_qty < 1)
				$soldout = '&nbsp;&nbsp;[품절]';
			else
				$soldout = '';

			$options[$opt_id[0]][] = '<option value="'.$opt_id[1].','.$row['io_price'].','.$io_stock_qty.',0">'.$opt_id[1].$price.$soldout.'</option>';
		}
	}

	// 옵션항목 만들기
	for($i=0; $i<$subj_count; $i++) {
		$opt = $options[$subj[$i]];
		$opt_count = count($opt);
		if($opt_count) {
			$seq = $i + 1;
			$str .= '<div class=sp_obox>'.PHP_EOL;
			$str .= '<ul>'.PHP_EOL;
			$str .= '<li class=tlst><label for="it_supply_'.$seq.'">'.$subj[$i].'</label></li>'.PHP_EOL;

			$select = '<select id="it_supply_'.$seq.'" class="it_supply" '.$event.'>'.PHP_EOL;
			$select .= '<option value="">선택안함</option>'.PHP_EOL;
			for($k=0; $k<$opt_count; $k++) {
				$opt_val = $opt[$k];
				if($opt_val) {
					$select .= $opt_val.PHP_EOL;
				}
			}
			$select .= '</select>'.PHP_EOL;

			$str .= '<li class=trst>'.$select.'</li>'.PHP_EOL;
			$str .= '</ul>'.PHP_EOL;
			$str .= '</div>'.PHP_EOL;
		}
	}

	return $str;
}

// 장바구니 옵션호출
function print_item_options($gs_id, $mb_no)
{
	global $tb;

	$sql = " select ct_option, ct_qty, io_price, ct_price, io_type
				from shop_cart where gs_id = '$gs_id' and mb_no='$mb_no' and ct_select='0' order by io_type asc, index_no asc ";
	$result = sql_query($sql);

	$str = '';
	for($i=0; $row=sql_fetch_array($result); $i++) {
		if($i == 0) {
			$str .= '<table class="op_box">'.PHP_EOL;
			$str .= '<tbody>'.PHP_EOL;
		}

        $price_plus = '';
        if($row['io_price'] >= 0)
            $price_plus = '+';

		$str .= '<tr>';

		unset($io_price);

		if($row['io_type'])
			$io_price = $price_plus.display_price($row['io_price'] * $row['ct_qty']);
		else
			$io_price = $price_plus.display_price(($row['io_price'] + $row['ct_price']) * $row['ct_qty']);

		// 추가상품일때
		if($row['io_type'])
			$str .= "<td class='tal mi_lt fc_255'>[추가상품]&nbsp;".$row['ct_option']." ".$row['ct_qty']."개</td>".PHP_EOL;
		else
			$str .= "<td class='tal mi_lt fc_197'>[옵션]&nbsp;".$row['ct_option']." ".$row['ct_qty']."개</td>".PHP_EOL;

		$str .= "<td class='tar mi_rt'>".$io_price."</td>".PHP_EOL;
		$str .= '</tr>';
	}

	if($i > 0) {
		$str .= '</tbody>';
		$str .= '</table>';
	}

	return $str;
}

// 주문완료 옵션호출
function print_complete_options($gs_id, $odrkey)
{
	global $tb;

	$sql = " select ct_option, ct_qty, io_type, io_price
				from shop_cart where odrkey = '$odrkey' and gs_id = '$gs_id' order by io_type asc, index_no asc ";
	$result = sql_query($sql);

	$str = '';
	$comma = '';
	for($i=0; $row=sql_fetch_array($result); $i++) {
		if($i == 0)
			$str .= '<ul>'.PHP_EOL;

		$price_plus = '';
        if($row['io_price'] >= 0)
            $price_plus = '+';

		if($row['io_type'])
			$str .= "<li class='fc_255'>[추가상품]&nbsp;".$row['ct_option']." ".display_qty($row['ct_qty'])." (".$price_plus.display_price($row['io_price']).")</li>".PHP_EOL;
		else
			$str .= "<li class='fc_197'>".$row['ct_option']." ".display_qty($row['ct_qty'])." (".$price_plus.display_price($row['io_price']).")</li>".PHP_EOL;
	}

	if($i > 0)
		$str .= '</ul>';

	return $str;
}

// 계좌정보를 select 박스 형식으로 얻는다
function get_bank_account($name, $selected='')
{
	global $default;

	$str  = '<select id="'.$name.'" name="'.$name.'" style="width:100%">'.PHP_EOL;
	$str .= '<option value="">선택하십시오</option>'.PHP_EOL;

	$bank_account = explode("\n",$default['cf_bank_account']);
	for($i=0;$i<count($bank_account);$i++)
	{
		$bank_info = trim($bank_account[$i]);
		if($bank_info) {
			$str .= option_selected($bank_info, $selected, $bank_info);
		}
	}
	$str .= '</select>'.PHP_EOL;

	return $str;
}

// 금액 표시
function display_price($price)
{
	return number_format($price, 0);
}

// 금액 표시
function display_price2($price)
{
	return '<p class="spr">'.number_format($price).'<span>원</span></p>';
}

// 포인트 표시
function display_point($price)
{
	return number_format($price, 0).'P';
}

// 수량 표시
function display_qty($price)
{
	return number_format($price, 0).'개';
}

// 로고
function display_logo($fld='mobile_logo')
{
	global $tb, $pt_id;

	$row = sql_fetch("select $fld from shop_logo where mb_id='$pt_id'");
	if(!$row[$fld] && $pt_id != 'admin') {
		$row = sql_fetch("select $fld from shop_logo where mb_id='admin'");
	}

	$file = TW_DATA_PATH.'/banner/'.$row[$fld];
	if(is_file($file) && $row[$fld]) {
		$file_url = TW_DATA_URL.'/banner/'.$row[$fld];
		return '<a href="'.TW_URL.'/m/"><img src="'.$file_url.'" class="lg_wh"></a>';
	} else {
		return '';
	}
}

// get_listtype_cate('설정값')
function get_listtype_cate($list_best)
{
	global $tb, $default;

	$mod = 3;
	$ul_str = '';

	for($i=0; $i<count($list_best); $i++) {
		$str = '';

		$list_code = explode(",", $list_best[$i]['code']); // 배열을 만들고
		$list_code = array_unique($list_code); //중복된 아이디 제거
		$list_code = array_filter($list_code); // 빈 배열 요소를 제거
		$list_code = array_values($list_code); // index 값 주기

		$succ_count = 0;
		for($g=0; $g<count($list_code); $g++) {
			$gcode = trim($list_code[$g]);
			$row = sql_fetch(" select * from shop_goods where gcode = '$gcode' ");
			if(!$row['index_no']) continue;
			if($succ_count >= 3) break;

			$it_href = $tb['bbs_root'].'/view.php?gs_id='.$row['index_no'];
			$it_imageurl = get_it_image_url($row['index_no'], $row['simg2'], $default['cf_item_medium_wpx'], $default['cf_item_medium_hpx']);
			$it_name = get_text($row['gname']);
			$it_price = get_price($row['index_no']);
			$it_amount = get_sale_price($row['index_no']);
			$it_point = display_point($row['gpoint']);

			// (시중가 - 할인판매가) / 시중가 X 100 = 할인률%
			$it_sprice = $sale = '';
			if($row['saccount'] > $it_amount && !is_uncase($row['index_no'])) {
				$sett = ($row['saccount'] - $it_amount) / $row['saccount'] * 100;
				$sale = '<p class="sale">'.number_format($sett,0).'<span>%</span></p>';
				$it_sprice = display_price2($row['saccount']);
			}

			$str .= "<li>\n";
			$str .=		"<a href=\"{$it_href}\">\n";
			$str .=		"<dl>\n";
			$str .=			"<dd class=\"pname\">{$it_name}</dd>\n";
			$str .=			"<dd class=\"pimg\"><img src=\"{$it_imageurl}\"></dd>\n";
			$str .=			"<dd class=\"price\">{$it_sprice}{$it_price}</dd>\n";
			$str .=		"</dl>\n";
			$str .=		"</a>\n";
			$str .= "</li>\n";

			$succ_count++;
		} // for end

		// 나머지 li
		$cnt = $succ_count%$mod;
		if($cnt) {
			for($k=$cnt; $k<$mod; $k++) { $str .= "<li></li>\n"; }
		}

		if(!$str) $str = "<li class='empty_list'>자료가 없습니다.</li>\n";

		$ul_str .= "<ul>\n{$str}</ul>\n";
	}

	return $ul_str;
}
?>