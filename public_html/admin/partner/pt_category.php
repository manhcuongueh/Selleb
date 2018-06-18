<?php
define('_PURENESS_', true);
include_once("./_common.php");

$gw_head_title = '카테고리 설정';
include_once(TW_ADMIN_PATH."/admin_head.php");

$target_table = 'shop_cate_'.$mb_id;
$sql_order = " order by list_view asc ";
?>
<h1 class="newp_tit"><?=$gw_head_title;?></h1>
<div class="new_win_body">
	<div class="sho_cate_bx">
		<div class="local_frm02">
			<a href="./pt_category.php?mb_id=<?=$mb_id;?>" class="btn_lsmall bx-blue">처음으로</a>
			<a href="javascript:post_reset('./pt_category_reset.php');" class="btn_lsmall bx-red"><i class="fa fa-refresh fa-spin"></i> 본사와 동일하게 설정값 초기화</a>
		</div>
		<ul>
		<?php
		$sql = "select * from {$target_table} where length(catecode)='3' $sql_order ";
		$res = sql_query($sql);
		while($row=sql_fetch_array($res)) {
			$count1 = sel_count($target_table, "where upcate='{$row['catecode']}' $sql_order");
			$href1 = "./pt_category.php?mb_id=$mb_id&sel_ca1={$row['catecode']}";

			if($row['p_oper'] == 'y')
				$row['catename'] = '<span class="fc_00f">[본사]</span>&nbsp;'.$row['catename'];
			else
				$row['catename'] = '<span class="fc_red">[개별]</span>&nbsp;'.$row['catename'];

			echo "<li>\n";
		?>
			<div>
				<img src='/img/icon/no_01_over.gif' class="vam" alt='1차'>
				<b><?=$row['catecode'];?></b>
				<input type="checkbox" name="p_hide" value="1" <?=($row['p_hide'])?"checked='checked'":"";?> onclick="check_sub('<?=$row['index_no']; ?>','<?=$mb_id;?>');"> <b class="fc_red">감춤</b>
				<a href="<?=$href1;?>"><b><?=$row['catename'];?></b></a> <b class="fc_255">(<?=$count1;?>)</b>
			</div>
		<?php
		if($sel_ca1 && $sel_ca1==$row['catecode']) { // 2차
			echo "<dl class=\"cate2_bx\">\n";
			$sql2 = "select * from {$target_table} where upcate='$sel_ca1' $sql_order ";
			$res2 = sql_query($sql2);
			while($row2=sql_fetch_array($res2)) {
				$count2 = sel_count($target_table, "where upcate='{$row2['catecode']}' $sql_order");
				$href2 = "{$href1}&sel_ca2={$row2['catecode']}";

				if($row2['p_oper'] == 'y')
					$row2['catename'] = '<span class="fc_00f">[본사]</span>&nbsp;'.$row2['catename'];
				else
					$row2['catename'] = '<span class="fc_red">[개별]</span>&nbsp;'.$row2['catename'];
		?>
			<dt>
				<img src='/img/icon/no_02.gif' class="vam" alt='2차'>
				<b><?=$row2['catecode'];?></b>
				<input type="checkbox" name="p_hide" value="1" <?=($row2['p_hide'])?"checked='checked'":"";?> onclick="check_sub('<?=$row2['index_no']; ?>','<?=$mb_id;?>');"> <b class="fc_red">감춤</b>
				<a href="<?=$href2;?>"><b><?=$row2['catename'];?></b></a> <b class="fc_255">(<?=$count2;?>)</b>
			</dt>
		<?php
		if($sel_ca2 && $sel_ca2==$row2['catecode']) { // 3차
			echo "<dd>\n<dl class=\"cate3_bx\">\n";
			$sql3 = "select * from {$target_table} where upcate='$sel_ca2' $sql_order";
			$res3 = sql_query($sql3);
			while($row3=sql_fetch_array($res3)) {
				$count3 = sel_count($target_table, "where upcate='{$row3['catecode']}' $sql_order");
				$href3 = "{$href2}&sel_ca3={$row3['catecode']}";

				if($row3['p_oper'] == 'y')
					$row3['catename'] = '<span class="fc_00f">[본사]</span>&nbsp;'.$row3['catename'];
				else
					$row3['catename'] = '<span class="fc_red">[개별]</span>&nbsp;'.$row3['catename'];
		?>
			<dd>
				<img src='/img/icon/no_03.gif' align='absmiddle' alt='3차'>
				<b><?=$row3['catecode'];?></b>
				<input type="checkbox" name="p_hide" value="1" <?=($row3['p_hide'])?"checked='checked'":"";?> onclick="check_sub('<?=$row3['index_no']; ?>','<?=$mb_id;?>');"> <b class="fc_red">감춤</b>
				<a href="<?=$href3;?>"><b><?=$row3['catename'];?></b></a> <b class="fc_255">(<?=$count3;?>)</b>
			</dd>
		<?php
		if($sel_ca3 && $sel_ca3==$row3['catecode']) { // 4차
			echo "<dd>\n<dl class=\"cate4_bx\">\n";
			$sql4 = "select * from {$target_table} where upcate='$sel_ca3' $sql_order";
			$res4 = sql_query($sql4);
			while($row4=sql_fetch_array($res4)) {
				$count4 = sel_count($target_table, "where upcate='{$row4['catecode']}' $sql_order");
				$href4 = "{$href3}&sel_ca4={$row4['catecode']}";

				if($row4['p_oper'] == 'y')
					$row4['catename'] = '<span class="fc_00f">[본사]</span>&nbsp;'.$row4['catename'];
				else
					$row4['catename'] = '<span class="fc_red">[개별]</span>&nbsp;'.$row4['catename'];
		?>
			<dd>
				<img src='/img/icon/no_04.gif' align='absmiddle' alt='4차'>
				<b><?=$row4['catecode'];?></b>
				<input type="checkbox" name="p_hide" value="1" <?=($row4['p_hide'])?"checked='checked'":"";?> onclick="check_sub('<?=$row4['index_no']; ?>','<?=$mb_id;?>');"> <b class="fc_red">감춤</b>
				<a href="<?=$href4;?>"><b><?=$row4['catename'];?></b></a> <b class="fc_255">(<?=$count4;?>)</b>
			</dd>
		<?php
		if($sel_ca4 && $sel_ca4==$row4['catecode']) { // 5차
			echo "<dd>\n<dl class=\"cate5_bx\">\n";
			$sql5 = "select * from {$target_table} where upcate='$sel_ca4' $sql_order";
			$res5 = sql_query($sql5);
			while($row5=sql_fetch_array($res5)) {
				if($row5['p_oper'] == 'y')
					$row5['catename'] = '<span class="fc_00f">[본사]</span>&nbsp;'.$row5['catename'];
				else
					$row5['catename'] = '<span class="fc_red">[개별]</span>&nbsp;'.$row5['catename'];
		?>
			<dd>
				<img src='/img/icon/no_05.gif' align='absmiddle' alt='5차'>
				<b><?=$row5['catecode'];?></b>
				<input type="checkbox" name="p_hide" value="1" <?=($row5['p_hide'])?"checked='checked'":"";?> onclick="check_sub('<?=$row5['index_no']; ?>','<?=$mb_id;?>');"> <b class="fc_red">감춤</b>
				<b><?=$row5['catename'];?></b>
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
	<div class="btn_confirm">
		<button type="button" onclick="self.close();" class="btn_medium bx-white">닫기</button>
	</div>
</div>

<script>
function check_sub(ca_no, mb_id) {
	var error = "";
	$.ajax({
		url: "./pt_category_update.php",
		type: "POST",
		data: {
			"mode": "w",
			"mb_id": mb_id,
			"ca_no": ca_no
		},
		dataType: "json",
		async: false,
		cache: false,
		success: function(data, textStatus) {
			error = data.error;
		}
	});

	if (error) {
		alert(error);
		location.reload();
	}
}

// POST 방식으로 초기화
function post_reset(action_url)
{
	var f = document.fpost;

	if(confirm("한번 초기화 자료는 복구할 방법이 없습니다.\n\n정말 초기화하시겠습니까?")) {
        f.w.value = 'reset';
		f.action = action_url;
		f.submit();
	}
}
</script>

<form name="fpost" method="post">
<input type="hidden" name="mb_id" value="<?=$mb_id;?>">
<input type="hidden" name="w" value="">
</form>

<?php
include_once(TW_ADMIN_PATH.'/admin_tail.sub.php');
?>