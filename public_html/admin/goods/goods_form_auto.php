<?php
define('_PURENESS_', true);
include_once("./_common.php");

$gs = get_goods($index, 'money_acc');

for($i=0; $i<(int)$no; $i++){
	$check = explode("|", $gs['money_acc']);
	$val = "";
	if($check[$i]=='')
		$val = 0;
	else
		$val = $check[$i];
?>
<input type="text" name="money_acc[]" value="<?php echo $val; ?>" class="frm_input w80">
<?php } ?>