<?php
define('_NEWWIN_', true);
include_once("_common.php");
include_once("admin_head.php");
?>

<div class="new_win_lnb">
	<ul>
		<li><a href="pop_order_detail.php?code=A&index_no=<?php echo $index_no;?>" target="right"><i class="fa fa fa-angle-right"></i> 주문내역조회</a></li>
		<li><a href="javascript:win_open('./order/order_print.php?index_no=<?php echo $index_no;?>','pop_print','670','600','yes');"><i class="fa fa fa-angle-right"></i> 인쇄용주문서</a></li>
	</ul>
</div>