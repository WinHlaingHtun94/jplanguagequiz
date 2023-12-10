<?php
	require_once('php/init.php');

	$current_url = get_current_url();

	//キャッシュ削除
	$common_css_mtime = get_file_mtime(WWW_ASSETS_ROOT."css/common.min.css");
	$common_js_mtime = get_file_mtime(WWW_ASSETS_ROOT."js/common.min.js");
?>

<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="IE=edge">

	<title></title>
	<meta property="og:title" content="">
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

	<!-- build:css assets/css/vendor.min.css -->
	
	<!-- endbuild -->

	<link rel="stylesheet" href="assets/css/common.min.css?<?=$common_css_mtime?>">
</head>

<body>

	<!-- wrapper -->
	<div id="wrapper">

		<!-- innerWrapper -->
		<div id="innerWrapper">

			<!-- header -->
			<?php require_once("php/layouts/contents_header.php"); ?>
			<!-- /header -->

			<!-- ctArea -->
			<div id="ctArea" class="ctTop">
				TOP page
			</div>
			<!-- /ctArea -->

			<!-- footer -->
			<?php require_once("php/layouts/contents_footer.php"); ?>
			<!-- /footer -->

		</div>
		<!-- / innerWrap -->

	</div>
	<!-- / wrapper -->


	<!--build:js assets/js/vendor.min.js -->
	<script src="assets/js/vendor/jquery-3.7.0.min.js"></script>
	<script src="assets/js/vendor/jquery.easing.min.js"></script>
	<!-- endbuild -->

	<script src="assets/js/common.min.js?<?=$common_js_mtime?>">"></script>
</body>

</html>
