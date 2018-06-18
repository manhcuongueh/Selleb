<?php
include_once("./_common.php");

if(!$member['id'])
    alert_close("로그인 후 작성 가능합니다.");

$gw_head_title = '구매후기 작성';
include_once(TW_PATH."/head.sub.php");

$gs = get_goods($gs_id);

$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

$form_action_url = TW_SHOP_URL.'/orderreview_update.php';

include_once($theme_path.'/orderreview.skin.php');

include_once(TW_PATH."/tail.sub.php");
?>