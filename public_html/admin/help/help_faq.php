<?php
if(!defined('_TUBEWEB_')) exit;

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_faq ";
$sql_search = " where (1) ";
$sql_order  = " order by index_no desc";

if($sfl && $stx) {
    $sql_search .= " and ($sfl like '%$stx%') ";
}

if($sst) {
    $sql_search .= " and (cate='$sst') ";
}

$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 30;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select * $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

$btn_frmline = <<<EOF
<button type="button" onclick="btn_check('delete')" class="btn_lsmall bx-white">선택삭제</button>
<a href="help.php?code=faq_from" class="fr btn_lsmall red"><i class="ionicons ion-android-add"></i> 추가하기</a>
EOF;
?>

<h2>기본검색</h2>
<form name="fsearch" id="fsearch" method="get">
<input type="hidden" name="code" value="<?php echo $code; ?>">
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col width="100px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th>검색키워드</th>
		<td>
			<select name="sst">
				<option value="">FAQ카테고리</option>
				<?php
				$sql = "select * from shop_faq_cate";
				$res = sql_query($sql);
				while($row=sql_fetch_array($res)) {
					echo option_selected($row['index_no'], $sst, $row['catename']);
				}
				?>
			</select>
			<select name="sfl">
				<?php echo option_selected('subject', $sfl, '제목'); ?>
				<?php echo option_selected('memo', $sfl, '내용'); ?>
			</select>
			<input type="text" name="stx" value="<?php echo $stx;?>" class="frm_input w325">
		</td>
	</tr>
	</tbody>
	</table>
</div>
<div class="btn_confirm">
	<input type="submit" value="검색" class="btn_medium">
	<input type="button" value="초기화" id="frmRest" class="btn_medium grey">	
</div>
</form>

<form name="ffaqlist" method="post">
<input type='hidden' name='q1' value="<?php echo $q1; ?>">
<input type='hidden' name='page' value="<?php echo $page; ?>">

<div class="local_ov mart30">
	전체 : <b class="fc_red"><?php echo number_format($total_count); ?></b> 건 조회
</div>
<div class="local_frm01">
	<?php echo $btn_frmline; ?>
</div>
<div class="tbl_head01">
	<table>
	<colgroup>
		<col width="50px">
		<col width="50px">
		<col width="100px">
		<col>
		<col width="80px">
		<col width="60px">
	</colgroup>
	<thead>
	<tr>
		<th><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form)"></th>
		<th>번호</th>
		<th>분류</th>
		<th>제목</th>
		<th>등록일</th>
		<th>관리</th>
	</tr>
	</thead>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$faq_table = $row[index_no];
		$row2 = sql_fetch("select * from shop_faq_cate where index_no='$row[cate]'");

		$s_upd = "<a href='help.php?code=faq_from&w=u&faq_table=$faq_table$qstr&page=$page' class=\"btn_small\">수정</a>";

		$bg = 'list'.($i%2);

		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;
	?>
	<tr class="<?php echo $bg;?>">
		<td>
			<input type="checkbox" name="chk[]" value="<?php echo $i; ?>">
			<input type="hidden" name="faq_table[<?php echo $i; ?>]" value="<?php echo $faq_table; ?>">
		</td>
		<td><?php echo $num--;?></td>
		<td><?php echo $row2[catename];?></td>
		<td class="tal"><a href="javascript:ubDisplay.display('<?php echo $i; ?>')"><?php echo get_text($row[subject]);?></a></td>
		<td><?php echo substr($row[wdate],0,10);?></td>
		<td><?php echo $s_upd;?></td>
	</tr>
	<tr id="view_<?php echo $i; ?>" style="display:none" bgcolor='fdfbf5'>
		<td colspan="3"></td>
		<td colspan="3" class="bl_nolne tal">
			<?php echo get_view_thumbnail(conv_content($row['memo'], 1), 600); ?>
		</td>
	</tr>
	<?php 
	}
	if($i==0)
		echo '<tbody><tr><td colspan="6" class="empty_table">자료가 없습니다.</td></tr>';
	?>
	</tbody>
	</table>
</div>
<div class="local_frm02">
	<?php echo $btn_frmline; ?>
</div>
</form>

<?php if($total_count > 0) { ?>
<div class="btn_confirm">
	<?php echo pageing($page, $total_page, $total_count, "{$_SERVER['SCRIPT_NAME']}?$q1&page="); ?>
</div>
<?php } ?>

<script>
function check_all(f)
{
    var chk = document.getElementsByName("chk[]");

    for(i=0; i<chk.length; i++)
        chk[i].checked = f.chkall.checked;
}

function btn_check(act)
{
	var f = document.ffaqlist;

    if(act == "delete") // 선택삭제
    {
        f.action = './help/help_faq_list_delete.php';
        str = "삭제";
    }
    else
        return;

    var chk = document.getElementsByName("chk[]");
    var bchk = false;

    for(i=0; i<chk.length; i++)
    {
        if(chk[i].checked)
            bchk = true;
    }

    if(!bchk)
    {
        alert(str + "할 자료를 하나 이상 선택하세요.");
        return;
    }

    if(act == "delete")
    {
        if(!confirm("선택한 자료를 정말 삭제 하시겠습니까?"))
            return;
    }

    f.submit();
}

var ubDisplay = {
      q_old : "",
      a_old : "",

      display : function(seq){
      var a_new = document.getElementById("view_"+seq);

      if(this.a_old != a_new){
         if(this.a_old != "")
             this.a_old.style.display = "none";
             a_new.style.display = "";
             this.a_old = a_new;
         }else{
             a_new.style.display = "none";
             this.a_old = "";
         }
       }
   };
</script>
