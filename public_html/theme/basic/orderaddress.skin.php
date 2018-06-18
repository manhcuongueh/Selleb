<?php
if(!defined('_TUBEWEB_')) exit;
?>

<form name="forderaddress" method="post" class="pop_wrap">
<h2 class="pop_tit"><i class="fa fa-search"></i> <?php echo $gw_head_title; ?> <a href="javascript:self.close();" class="pop_close"></a></h2>
<div class="pop_inner">
	<table class="marb20 wfull center">
	<tr>
		<td valign="top">
		<table class="wfull">
		<colgroup>
			<col width="40">
			<col>
			<col width="40">
		</colgroup>
		<?php
		if(!$total_count) {
		?>
		<tr><td colspan="3" class="empty_list">자료가 없습니다.</td></tr>
		<?php
		} 
		else {
			$sep = chr(30);
			$k = 0; $ar_mk = array();
			for($i=0; $row=sql_fetch_array($result); $i++)
			{
				$info = array();
				$info[] = $row['b_name'];			
				$info[] = $row['b_cellphone'];
				$info[] = $row['b_telephone'];
				$info[] = $row['b_zip'];
				$info[] = $row['b_addr1'];
				$info[] = $row['b_addr2'];
				$info[] = $row['b_addr3'];
				$info[] = $row['b_addr_jibeon'];

				$addr = implode($sep, $info);			
				$addr = get_text($addr);

				if(!in_array($addr, $ar_mk)) {
					$k++;
					$ar_mk[$i] = $addr;
		?>
		<tr height="30">
			<td class="tac"><?php echo $k; ?></td>
			<td class="tal"><?php echo print_address($row['b_addr1'], $row['b_addr2'], $row['b_addr3'], $row['b_addr_jibeon']); ?></td>
			<td class="tac">
				<input type="hidden" value="<?php echo $addr; ?>">	
				<a href="javascript:void(0);" class="sel_address btn_small">선택</a>
			</td>
		</tr>
		<tr><td colspan="3" height="1" bgcolor="#efefef"></td></tr>
		<?php
				}
			}
		}
		?>
		</table>
		</td>
	</tr>
	</table>
	<div class="tac">
		<a href="javascript:window.close()" class="btn_lsmall bx-white">창닫기</a>
	</div>	
</div>
</form>

<script>
$(function() {
    $(".sel_address").on("click", function() {
        var addr = $(this).siblings("input").val().split(String.fromCharCode(30));

        var f = window.opener.buyform;
		f.b_name.value			= addr[0];
		f.b_cellphone.value		= addr[1];
		f.b_telephone.value		= addr[2];
		f.b_zip.value			= addr[3];
		f.b_addr1.value			= addr[4];
		f.b_addr2.value			= addr[5];
		f.b_addr3.value			= addr[6];
		f.b_addr_jibeon.value	= addr[7];

        var zip = addr[3].replace(/[^0-9]/g, "");
        if(zip != "") {
            var code = String(zip);
			window.opener.calculate_sendcost(code);
        }

        window.close();
    });
});
</script>
