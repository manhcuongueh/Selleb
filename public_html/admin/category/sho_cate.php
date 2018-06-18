<?php
if(!defined('_TUBEWEB_')) exit;

if(isset($sel_ca1)) $qstr .= '&sel_ca1=' . $sel_ca1;
if(isset($sel_ca2)) $qstr .= '&sel_ca2=' . $sel_ca2;
if(isset($sel_ca3)) $qstr .= '&sel_ca3=' . $sel_ca3;
if(isset($sel_ca4)) $qstr .= '&sel_ca4=' . $sel_ca4;

$query_string = "code=$code$qstr";
$q1 = $query_string;

$target_table = 'shop_cate';
include_once(TW_INC_PATH."/categoryinfo.lib.php");
?>

<h2>카테고리 등록</h2>
<form name="fcgyform" method="post" onsubmit="return fcgyform_submit(this);" enctype="MULTIPART/FORM-DATA">
<input type="hidden" name="q1" value="<?=$q1;?>">
<input type="hidden" name="token" value="">

<div class="tbl_frm01">
	<table>
	<colgroup>
		<col width="180px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th>카테고리 소속</th>
		<td>
			<script>multiple_select2('sel_ca');</script>
		</td>
	</tr>
	<tr>
		<th>카테고리명</th>
		<td><input type="text" name="catename" class="frm_input w325 required" required itemname="카테고리명"></td>
	</tr>
	<tr>
		<th>카테고리 상단배너</th>
		<td><input type="file" name="img_head"></td>
	</tr>
	<tr>
		<th>카테고리 상단배너 링크</th>
		<td>
			<input type="text" name="img_head_url" class="frm_input w325">
			<?=help('예시) /shop/view.php?index_no=1');?>
		</td>
	</tr>
	</tbody>
	</table>
</div>
<div class="btn_confirm">
	<input type="submit" class="btn_large" value="저장">
</div>
</form>

<div class="sho_cate_bx mart30">
	<div class="local_frm02">
		<a href="./category/sho_cate_excel.php" class="btn_lsmall bx-white"><i class="fa fa-file-excel-o"></i> 카테고리 엑셀다운로드</a>
		<a href="javascript:post_reset('./category/sho_cate_reset.php');" class="btn_lsmall bx-red"><i class="fa fa-refresh fa-spin"></i> 본사와 동일하게 가맹점 설정값 초기화</a>
	</div>
	<ul>
	<?php
	$sql = "select * from shop_cate where length(catecode)='3' order by list_view asc";
	$res = sql_query($sql);
	while($row=sql_fetch_array($res)) {
		$count1 = sel_count("shop_cate", "where upcate='{$row['catecode']}'");
		$href1 = "?code=cate&sel_ca1={$row['catecode']}";
		echo "<li>\n";
	?>
		<div>
			<img src='/img/icon/no_01_over.gif' class="vam" alt='1차'>
			<b><?=$row['catecode'];?></b>
			<a href="javascript:post_delete('./category/sho_cate_delete.php', '<?=$row['index_no'];?>');" class="btn_ssmall red">삭제</a>
			<a href="javascript:modok('<?=$row['index_no'];?>');" class="btn_ssmall">수정</a>
			<a href="<?=$href1;?>"><b><?=$row['catename'];?></b></a> <b class="fc_255">(<?=$count1;?>)</b>
			<div id="co<?=$row['index_no'];?>" style="display:none;"><iframe id="cos<?=$row['index_no'];?>" frameborder="0" width="100%" height="350"></iframe></div>
		</div>
	<?php
	if($sel_ca1 && $sel_ca1==$row['catecode']) { // 2차
		echo "<dl class=\"cate2_bx\">\n";
		$sql2 = "select * from shop_cate where upcate='$sel_ca1' order by list_view asc ";
		$res2 = sql_query($sql2);
		while($row2=sql_fetch_array($res2)) {
			$count2 = sel_count("shop_cate", "where upcate='{$row2['catecode']}'");
			$href2 = "{$href1}&sel_ca2={$row2['catecode']}";
	?>
		<dt>
			<img src='/img/icon/no_02.gif' class="vam" alt='2차'>
			<b><?=$row2['catecode'];?></b>
			<a href="javascript:post_delete('./category/sho_cate_delete.php', '<?=$row2['index_no'];?>');" class="btn_ssmall red">삭제</a>
			<a href="javascript:modok('<?=$row2['index_no'];?>');" class="btn_ssmall">수정</a>
			<a href="<?=$href2;?>"><b><?=$row2['catename'];?></b></a> <b class="fc_255">(<?=$count2;?>)</b>
			<div style="display:none;" id="co<?=$row2['index_no'];?>"><iframe id="cos<?=$row2['index_no'];?>" frameborder="0" width="100%" height="270"></iframe></div>
		</dt>
	<?php
	if($sel_ca2 && $sel_ca2==$row2['catecode']) { // 3차
		echo "<dd>\n<dl class=\"cate3_bx\">\n";
		$sql3 = "select * from shop_cate where upcate='$sel_ca2' order by list_view asc";
		$res3 = sql_query($sql3);
		while($row3=sql_fetch_array($res3)) {
			$count3 = sel_count("shop_cate", "where upcate='{$row3['catecode']}'");
			$href3 = "{$href2}&sel_ca3={$row3['catecode']}";
	?>
		<dd>
			<img src='/img/icon/no_03.gif' align='absmiddle' alt='3차'>
			<b><?=$row3['catecode'];?></b>
			<a href="javascript:post_delete('./category/sho_cate_delete.php', '<?=$row3['index_no'];?>');" class="btn_ssmall red">삭제</a>
			<a href="javascript:modok('<?=$row3['index_no'];?>');" class="btn_ssmall">수정</a>
			<a href="<?=$href3;?>"><b><?=$row3['catename'];?></b></a> <b class="fc_255">(<?=$count3;?>)</b>
			<div style="display:none;" id="co<?=$row3['index_no'];?>"><iframe id="cos<?=$row3['index_no'];?>"  frameborder="0" width="100%" height="270"></iframe></div>
		</dd>
	<?php
	if($sel_ca3 && $sel_ca3==$row3['catecode']) { // 4차
		echo "<dd>\n<dl class=\"cate4_bx\">\n";
		$sql4 = "select * from shop_cate where upcate='$sel_ca3' order by list_view asc";
		$res4 = sql_query($sql4);
		while($row4=sql_fetch_array($res4)) {
			$count4 = sel_count("shop_cate", "where upcate='{$row4['catecode']}'");
			$href4 = "{$href3}&sel_ca4={$row4['catecode']}";
	?>
		<dd>
			<img src='/img/icon/no_04.gif' align='absmiddle' alt='4차'>
			<b><?=$row4['catecode'];?></b>
			<a href="javascript:post_delete('./category/sho_cate_delete.php', '<?=$row4['index_no'];?>');" class="btn_ssmall red">삭제</a>
			<a href="javascript:modok('<?=$row4['index_no'];?>');" class="btn_ssmall">수정</a>
			<a href="<?=$href4;?>"><b><?=$row4['catename'];?></b></a> <b class="fc_255">(<?=$count4;?>)</b>
			<div style="display:none;" id="co<?=$row4['index_no'];?>"><iframe id="cos<?=$row4['index_no'];?>"  frameborder="0" width="100%" height="270"></iframe></div>
		</dd>
	<?php
	if($sel_ca4 && $sel_ca4==$row4['catecode']) { // 5차
		echo "<dd>\n<dl class=\"cate5_bx\">\n";
		$sql5 = "select * from shop_cate where upcate='$sel_ca4' order by list_view asc";
		$res5 = sql_query($sql5);
		while($row5=sql_fetch_array($res5)) {
	?>
		<dd>
			<img src='/img/icon/no_05.gif' align='absmiddle' alt='5차'>
			<b><?=$row5['catecode'];?></b>
			<a href="javascript:post_delete('./category/sho_cate_delete.php', '<?=$row5['index_no'];?>');" class="btn_ssmall red">삭제</a>
			<a href="javascript:modok('<?=$row5['index_no'];?>');" class="btn_ssmall">수정</a>
			<b><?=$row5['catename'];?></b>
			<div style="display:none;" id="co<?=$row5['index_no'];?>"><iframe id="cos<?=$row5['index_no'];?>"  frameborder="0" width="100%" height="270"></iframe></div>
		</dd>
	<?php
									} //while 5
									echo "</dl>\n</dd>\n";
								} //if
							} //while 4
							echo "</dl>\n</dd>\n";
						} //if
					} //while 3
					echo "</dl>\n</dd>\n";
				} //if

			} //while 2
			echo "</dl>\n";
		} //if
		echo "</li>\n";
	} //while 1
	?>
	</ul>
</div>

<script>
$(function(){
	<?php if($sel_ca1) { ?>
	$("select#sel_ca1").val('<?=$sel_ca1;?>');
	categorychange('<?=$sel_ca1;?>', 'sel_ca2');
	<?php } ?>
	<?php if($sel_ca2) { ?>
	$("select#sel_ca2").val('<?=$sel_ca2;?>');
	categorychange('<?=$sel_ca2;?>', 'sel_ca3');
	<?php } ?>
	<?php if($sel_ca3) { ?>
	$("select#sel_ca3").val('<?=$sel_ca3;?>');
	categorychange('<?=$sel_ca3;?>', 'sel_ca4');
	<?php } ?>
	<?php if($sel_ca4) { ?>
	$("select#sel_ca4").val('<?=$sel_ca4;?>');
	categorychange('<?=$sel_ca4;?>', 'sel_ca5');
	<?php } ?>
});

function fcgyform_submit(f) {
	f.action = "./category/sho_cate_update.php";
    return true;
}

// POST 방식으로 초기화
function post_reset(action_url)
{
	var f = document.fpost;

	if(confirm("한번 초기화 자료는 복구할 방법이 없습니다.\n\n정말 초기화하시겠습니까?")) {
        f.ca_no.value = 'reset';
		f.action = action_url;
		f.submit();
	}
}

// POST 방식으로 삭제
function post_delete(action_url, val)
{
	var f = document.fpost;

	if(confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) {
        f.ca_no.value = val;
		f.action = action_url;
		f.submit();
	}
}

function modok(index)
{
	document.all['cos'+index].src = "./category/sho_cate_mod.php?index_no="+index;
	document.all['co'+index].style.display = "";
}
</script>

<form name='fpost' method='post'>
<input type='hidden' name='q1' value="<?=$q1;?>">
<input type='hidden' name='ca_no'>
</form>
