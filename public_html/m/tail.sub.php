
<!-- 새창 대신 사용하는 iframe -->
<iframe width=0 height=0 name='hiddenframe' style='display:none;'></iframe>

<script src="<?php echo $tb['url']; ?>/js/wrest.js"></script>
<?php echo $config['tail_script']; /* script tag */ ?>
<?php
if($tb['kcp_footer']) { // kcp 결제시 필요 (지우지마세요)
	echo $tb['kcp_footer'];
} else {
?>
</body>
</html>
<?php
}
?>