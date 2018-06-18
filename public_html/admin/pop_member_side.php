<?php
define('_NEWWIN_', true);
include_once("_common.php");
include_once("admin_access.php");
include_once("admin_head.php");

$mb = get_member_no($index_no);
$sr = sql_fetch("select * from shop_seller  where mb_id='$mb[id]'");
?>

<div class="new_win_lnb">
	<div class="lnb_tit">
		<p><?php echo $mb[name]; ?></p>
		<p><?php echo $mb[id]; ?></p>
	</div>
	<ul>
		<li><a href="pop_member_detail.php?code=pview&index_no=<?php echo $index_no; ?>" target="right"><i class="fa fa fa-angle-right"></i> 회원정보수정</a></li>
		<?php if($sr[mb_id]) { ?>
		<li><a href="pop_member_detail.php?code=pitem&index_no=<?php echo $index_no; ?>" target="right"><i class="fa fa fa-angle-right"></i> 업체정보수정</a></li>
		<li><a href="pop_member_detail.php?code=psell&index_no=<?php echo $index_no; ?>" target="right"><i class="fa fa fa-angle-right"></i> 상품판매내역</a></li>
		<?php } ?>
		<li><a href="pop_member_detail.php?code=porder&index_no=<?php echo $index_no; ?>" target="right"><i class="fa fa fa-angle-right"></i> 상품구매내역</a></li>
		<li><a href="pop_member_detail.php?code=ppoint&index_no=<?php echo $index_no; ?>" target="right"><i class="fa fa fa-angle-right"></i> 포인트적립내역</a></li>
	</ul>
</div>
