<?php
if(!defined('_TUBEWEB_')) exit;
?>

<a name="it_comment"></a>
<table class="list1 wfull">
<tr>
	<td class="padt10 padb10 padl10">전체 <b>[<?php echo $item_use_count; ?>]</b> 개의 상품평이 있습니다. 상품평 이외에 다른 목적이나 불건전한 내용을 올리실 경우 삭제 처리될 수 있습니다. </td>
</tr>
</table>

<table class="wfull">
<?php
$sql = "select * from shop_goods_review where gs_id = '$index_no' ";
if($default['de_review_wr_use']) { 
	$sql .= " and pt_id = '$pt_id' ";
}
$sql .= " order by wdate desc ";
$res = sql_query($sql);
while($row = sql_fetch_array($res)) {
?>
<tr height="26">
	<td class="tal padl10 padt15 padb10 lh6"><?php if(is_admin() || ($member['id'] == $row['writer_s'])) { ?><a href="javascript:tdel('<?php echo TW_SHOP_URL; ?>/view_user_update.php?index_no=<?php echo $index_no; ?>&it_mid=<?php echo $row['index_no']; ?>&mode=d');"><img src="<?php echo TW_IMG_URL; ?>/icon/icon_x.gif" width="15" height="15" align="absmiddle"></a>&nbsp;&nbsp;<?php } ?><?php echo $row['memo']; ?></td>
	<td width="130" class='tac'><?php echo cut_str($row['writer_s'], 10); ?></td>
	<td width="80" class='tac'><?php echo date("Y-m-d",$row['wdate']); ?></td>
	<td width="80" class="tac"><?php for($i=0;$i<(int)$row['score'];$i++) { ?><img src="<?php echo TW_IMG_URL; ?>/sub/comment_start.jpg" align="absmiddle"><?php } ?></td>
</tr>
<tr><td height="1" bgcolor="#eeeeee" colspan="4"></td></tr>
<?php } ?>
</table>

<form name="fuserform" id="fuserform" action="<?php echo TW_SHOP_URL; ?>/view_user_update.php" method="post" onsubmit="return fuserform_submit(this);" class="mart20">
<input type="hidden" name="mode" value="w">
<input type="hidden" name="index_no" value="<?php echo $index_no; ?>">
<input type="hidden" name="token" value="<?php echo $token; ?>">
<input type="hidden" name="gs_se_id" value="<?php echo $gs['mb_id']; ?>">
<table class="wfull center" cellpadding="30" cellspacing="1" bgcolor="#f1f1f1">
<tr>
	<td bgcolor="white">
		<table class="wfull">
		<tr>
			<td align="left">Name : <?php if($member['id']) { echo $member['id']; } else { echo "로그인 후 작성하여 주십시오."; } ?></td>
			<td align="right">
				<?php
				for($i=1; $i<=5; $i++) {
					$checked = "";
					if($i == 1) $checked = "checked";
				?>
				<input type="radio" name="score" value="<?php echo $i; ?>" <?php echo $checked; ?>>
				<img src="<?php echo TW_IMG_URL; ?>/sub/score_<?php echo $i; ?>.gif" align='absmiddle'>
				<?php } ?>
			</td>
		</tr>
		</table>

		<table class="wfull mart10">
		<tr>
			<td><textarea name="memo" class="letter_bx" <?php if(!$member['id']) { echo "disabled"; } ?>></textarea></td>
			<td width="10"></td>
			<td width="78">
				<?php if($member['id']) { ?>
				<button type="submit" name="formimage1" class="btn_letter">사용후기<br>등록하기</button>
				<?php } else { ?>
				<a href="javascript:tguest();" class="btn_letter">사용후기<br>등록하기</a>
				<?php } ?>
			</td>
		</tr>
		</table>
		<p class="fc_137 mart7">회원인 분은 먼저 로그인 부탁드립니다.</p>
	</td>
</tr>
</table>
</form>

<script>
function fuserform_submit(f){
	if(!f.memo.value){
		alert('내용을 입력하세요.');
		f.memo.focus();
		return false;
	}

	if(confirm("등록 하시겠습니까?") == false)
		return false;
}

function tdel(url){
	if(confirm('삭제 하시겠습니까?')){
		location.href = url;
	}
}

function tguest(){
	answer = confirm('로그인 하셔야 상품평 작성이 가능합니다. 로그인 하시겠습니까?');
	if(answer==true) {	
		location.href = '<?php echo TW_BBS_URL; ?>/login.php?url=<?php echo $nowurl; ?>';
	}
}
</script>
