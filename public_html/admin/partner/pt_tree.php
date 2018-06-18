<?php
if(!defined('_TUBEWEB_')) exit;
?>

<?php
$inc_level = 0;
$mb_id;
$down_level=0;
$tr_name = $mb_id;
if(!$type)
	$type=1;
	function mb_tree($mb_recommend,$level,$line_array,$pre_cnt=0){
		global $inc_level,$mb_id,$type,$down_level,$tr_name;

		$sql = "select count(*) as cnt from shop_member WHERE grade>0 ";
		$where = " AND pt_id='$mb_recommend' ";
		$row = sql_fetch($sql.$where);
		$cnt = $row['cnt'];

		$sql = "select name,id,grade from shop_member WHERE grade>0 ";
		$where = " AND id='$mb_recommend' ";
		$row = sql_fetch($sql.$where);

		$blank = "";
		for($i=0;$i<$level-1;$i++){
			if($line_array[$i]==0){
				$blank .="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			} else {
				$blank .= "<img align='absbottom' border='0' src='/admin/img/line.gif'>&nbsp;";
			}
		}

		if($level==0){
			echo("<table border='0' cellpadding='0' cellspacing='0' margin='0'>");
		} else {
			if($pre_cnt==0){
				if($cnt==0){
					$blank .= "<img align='absbottom' border='0' src='/admin/img/join1.gif'>&nbsp;";
				} else {

					$blank .= "<img id='$mb_recommend"."_img"."' align='absbottom' border='0' src='/img/minus1.gif'>&nbsp;";
				}
			} else {
				if($cnt==0){
					$blank .= "<img align='absbottom' border='0' src='/admin/img/join2.gif'>&nbsp;";
				} else {
					$blank .= "<img id='$mb_recommend"."_img"."' align='absbottom' border='0' src='/admin/img/minus2.gif'>&nbsp;";
				}
			}
		}

		if($inc_level==1 && $level > 1){
			$tr_name = $mb_id;
			$inc_level=0;
		}
		if($down_level){
			$tr_name = $mb_recommend;
			$down_level = 0;
		}
		echo("	<tr name='$tr_name' ");
		if($level!=1){	echo("	style='display:block;'"); }
		echo("><td nowrap height='17'>$blank$row[mb_name] ");

		$r = sql_fetch("select * from shop_member where id='$mb_recommend' ");
		if($r['gender']=='M') { $img_name="man"; } else { $img_name="guir"; }

		$mb_grade = get_grade($r['grade']);

		$line = "&nbsp;<img src='/img/sub/tree_line.gif' align='absmiddle'>&nbsp;";

		$t_cnt = sel_count("shop_member","where pt_id='$mb_recommend'");
		if($t_cnt > 0) { $t_color = 'blue'; } else { $t_color = 'red'; }
		if($r['grade']>1  || $mb_recommend =='admin'){
			echo("<img src='/admin/img/$img_name.gif' width='15' height='15' align='absmiddle'>&nbsp;<a href=\"javascript:win_open('pop_member_main.php?index_no=$r[index_no]','pop_member','1000','600','yes');\"><b><font color='$t_color'>($t_cnt)</font>&nbsp;{$r[name]}{$line}{$mb_recommend}{$line}<font color='ed8e06'>{$mb_grade}</font>{$line}휴대폰:<font color='#939393'>{$r[cellphone]}</font>{$line}가입:<font color='#939393'>".substr($r['reg_time'],0,10)."</font>{$line}로그인수:<font color='#939393'>{$r[login_sum]}</font></a>");
		} else {
			echo("<img src='/admin/img/$img_name.gif' width='15' height='15' align='absmiddle'>&nbsp;<a href=\"javascript:win_open('pop_member_main.php?index_no=$r[index_no]','pop_member','1000','600','yes');\"><b><font color='cccccc'>{$r[name]}{$line}{$mb_recommend}{$line}{$mb_grade}{$line}휴대폰:{$r[cellphone]}{$line}가입:".substr($r['reg_time'],0,10)."{$line}로그인수:{$r[login_sum]}</font></a>");
		}

		echo("</td></tr>");
		if($cnt <= 0)
			return;

		$sql = "select id from shop_member where grade>0 ";
		$where = " and pt_id='$mb_recommend' order by index_no asc";
		$result = sql_query($sql.$where);
		$pre_cnt = $cnt;
		$inc_level=1;
		while($row = sql_fetch_array($result)){
			if($inc_level==1)
				$mb_id = $mb_recommend;
				$pre_cnt--;

			if($pre_cnt==0){
				$line_array[$level]=0;
			} else {
				$line_array[$level]=1;
			}

			mb_tree($row['id'],$level+1,$line_array,$pre_cnt);

	}

	$down_level=1;
}
?>
	<table class="wfull" style="border:1px solid #d5d5d5;table-layout:fixed">
	<tr>
		<td align="center" style="padding:10px;">
		<div style='width:100%;background:url(/admin/img/les/Code_line.gif);overflow-x:auto; overflow-y:auto;'>
			<table class="wfull">
			<tr>
				<td style="padding:0 3px;">
				<?php
				$line_array=array();
				mb_tree('admin',0,$line_array);
				?>
				</td>
			</tr>
			</table>
		</div>
		</td>
	</tr>
	</table>
	</td>
</tr>
</table>
