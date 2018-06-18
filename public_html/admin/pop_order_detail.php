<?php
define('_NEWWIN_', true);
include_once("_common.php");
include_once("admin_head.php");

$code = $_REQUEST['code'];
$index_no = $_REQUEST['index_no'];
?>

<div class="new_win_body">
	<?php
	include_once("./order/order_detail1.php");
	?>
</div>

<?php
include_once("admin_tail.sub.php");
?>