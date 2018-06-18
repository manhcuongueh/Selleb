<?php
if(!defined('_TUBEWEB_')) exit; // 개별 페이지 접근 불가

$sql = " select * 
		   from shop_popup
          where begin_date <= '$time_ymd' and end_date >= '$time_ymd'
            and state = '0' 
			and mb_id = '$pt_id'
          order by index_no asc ";
$result = sql_query($sql, false);
?>
<!-- 팝업레이어 시작 { -->
<div id="hd_pop">
    <h2>팝업레이어 알림</h2>

<?php
for($i=0; $nw=sql_fetch_array($result); $i++)
{
    // 이미 체크 되었다면 Continue
    if($_COOKIE["hd_pops_{$nw['index_no']}"])
        continue;

	$pattern[0]  = '/\r\n|\r|\n/';
	$pattern[1]  = '/\'/';
	$replace[0]  = '';
	$replace[1]  = '';

	$pop_memo = preg_replace($pattern, $replace, $nw['memo']);
?>

    <div id="hd_pops_<?php echo $nw['index_no'] ?>" class="hd_pops" style="top:<?php echo $nw['top']?>px;left:<?php echo $nw['lefts']?>px">
        <div class="hd_pops_con" style="width:<?php echo $nw['width'] ?>px;height:<?php echo $nw['height'] ?>px">
            <?php echo $pop_memo; ?>
        </div>
        <div class="hd_pops_footer">
            <button class="hd_pops_reject hd_pops_<?php echo $nw['index_no']; ?> 24"><strong>24</strong>시간 동안 다시 열지않음.</button>
            <button class="hd_pops_close hd_pops_<?php echo $nw['index_no']; ?>">닫기</button>
        </div>
    </div>
<?php }
if($i == 0) echo '<span class="sly">팝업레이어 알림이 없습니다.</span>';
?>
</div>

<script>
$(function() {
    $(".hd_pops_reject").click(function() {
        var id = $(this).attr('class').split(' ');
        var ck_name = id[1];
        var exp_time = parseInt(id[2]);
		var cookie_domain = '';
        $("#"+id[1]).css("display", "none");
        set_cookie(ck_name, 1, exp_time, cookie_domain);
    });
    $('.hd_pops_close').click(function() {
        var idb = $(this).attr('class').split(' ');
        $('#'+idb[1]).css('display','none');
    });
});
</script>
<!-- } 팝업레이어 끝 -->