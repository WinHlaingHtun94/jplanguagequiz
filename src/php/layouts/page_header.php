<?php
	$current_url = get_current_url();

	//キャッシュ削除
	$vendor_css_mtime = get_file_mtime(WWW_ASSETS_ROOT."css/vendor.min.css");
	$common_css_mtime = get_file_mtime(WWW_ASSETS_ROOT."css/common.min.css");
?>
<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="IE=edge">

	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<meta name="copyright" content="Copyright © 2023 ">
	<meta name="author" content="">
	<meta name="rating" content="general">
	<meta name="content-language" content="">
	<meta name="format-detection" content="telephone=no">
	<link rel="canonical" href="<?=$current_url?>">

	<meta property="og:site_name" content="">
	<meta property="og:locale" content="">
	<meta property="og:type" content="website">
	<meta property="og:url" content="<?=$current_url?>">
	<meta property="og:image" content="<?=DOMAIN?>/assets/img/ogp.png">
	<meta property="og:description" content="">

	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:image" content="<?=DOMAIN?>/assets/img/ogp.png">

	<link rel="stylesheet" href="<?=DOC_ROOT?>assets/css/vendor.min.css?<?=$vendor_css_mtime?>">
	<link rel="stylesheet" href="<?=DOC_ROOT?>assets/css/common.min.css?<?=$common_css_mtime?>">
