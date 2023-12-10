<?php
//キャッシュ削除
$vendor_js_mtime = get_file_mtime(WWW_ASSETS_ROOT."js/vendor.min.js");
$common_js_mtime = get_file_mtime(WWW_ASSETS_ROOT."js/common.min.js");
?>

    <script src="<?=DOC_ROOT?>assets/js/vendor.min.js?<?=$vendor_js_mtime?>"></script>
    <script src="<?=DOC_ROOT?>assets/js/common.min.js?<?=$common_js_mtime?>"></script>

</body>
</html>
