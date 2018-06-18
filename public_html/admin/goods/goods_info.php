<?php
if(!empty($_POST)) {
	define('_PURENESS_', true);
	include_once("./_common.php");
	include_once(TW_INC_PATH."/goodsinfo.lib.php");
}

if($gs['index_no']) {
    $gubun = $gs['info_gubun'];
} else {
    $gubun = $_POST['gubun'] ? $_POST['gubun'] : 'wear';
}
?>
<table>
<colgroup>
	<col width="180px">
	<col>
</colgroup>
<tbody>
    <?php
    if($gs['info_value'])
        $info_value = unserialize($gs['info_value']);
    $article = $item_info[$gubun]['article'];
    if($article) {
        // $el_no : 분류적용, 전체적용을 한번만 넣기 위해, $el_length : 수직병합할 셀 값
        $el_no = 0;
        $el_length = count($article);
        foreach($article as $key=>$value) {
            $el_name    = $key;
            $el_title   = $value[0];
            $el_example = $value[1];
            $el_value = '상품페이지 참고';

            if($gubun == $gs['info_gubun'] && $info_value[$key])
                $el_value = $info_value[$key];
    ?>
   <tr>
        <th><?php echo $el_title; ?></th>
        <td>
            <input type="hidden" name="ii_article[]" value="<?php echo $el_name; ?>">
			<label for="ii_article_<?php echo $el_name; ?>" class="sound_only"><?php echo $el_title; ?></label>
			<input type="text" name="ii_value[]" value="<?php echo $el_value; ?>" id="ii_article_<?php echo $el_name; ?>" required class="frm_input w325">
			<?php if($el_example) echo help($el_example,1); ?>
        </td>
    </tr>
    <?php
		$el_no++;
        }
    }
    ?>
</tbody>
</table>
