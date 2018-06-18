<?php
define('_PURENESS_', true);
include_once("./_common.php");

if(preg_match("/[^0-9a-z_]+/i", $mb_id)) {
    echo "<span class='fc_red strong'>영문자, 숫자, _ 만 입력하세요.</span>";
} else if(strlen($mb_id) < 3) {
    echo "<span class='fc_red strong'>최소 3자이상 입력하세요.</span>";
} else {
	$mb = get_member($mb_id);
    if($mb['id']) {
        echo "<span class='fc_red strong'>이미 사용중인 아이디 입니다.</span>";
    } else {
        if(preg_match("/[\,]?{$mb_id}/i", $config['sp_prohibit_id']))
			 echo "<span class='fc_red strong'>예약어로 금지된 회원아이디.</span>";
        else
             echo "<span class='fc_197 strong'>사용하셔도 좋은 아이디 입니다.</span>";
    }
}
?>