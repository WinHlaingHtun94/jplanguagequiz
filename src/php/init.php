<?php
/* ------------------------------------------------------------------
1：本番、2：テスト環境、3：ローカル、4：コーダー
------------------------------------------------------------------ */
define("DEBUG", 3);

//HTTPS自動遷移
if (DEBUG==1) {
	if (!isset($_SERVER['HTTPS'])) {
		header("Location: https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
		exit();
	}
	if (strpos($_SERVER['HTTP_HOST'], 'www.') === 0) {
		$url = 'http://ferrotec.my' . $_SERVER['REQUEST_URI'];
		header("Location: $url", true, 301);
		exit(0);
	}
}


/* ------------------------------------------------------------------
初期設定
------------------------------------------------------------------ */
mb_language('uni');
mb_internal_encoding('UTF-8');

spl_autoload_register(function ($class_name) {
	include 'php/'.$class_name . '.php';
});


/* ------------------------------------------------------------------
初期定義
------------------------------------------------------------------ */
if(DEBUG==1){
	//本番
	define('DOMAIN', 'https://sbizodiacustody.com');
	define("DOC_ROOT", "/");
	define('WWW_ASSETS_ROOT', '');
}else if(DEBUG==2){
	//テスト環境
	define('DOMAIN', 'https://staging.sbizodiacustody.com');
	define("DOC_ROOT", "/");
	define('WWW_ASSETS_ROOT', '');
}else if(DEBUG==3){
	//ローカル
	define('DOMAIN', 'http://localhost:3000');
	define("DOC_ROOT", "/");
	define('WWW_ASSETS_ROOT', '/Users/linktale/Linkalink Dropbox/Enokido Haruaki/Data/sbi_zc/git/dist/assets/');
}else if(DEBUG==4){
	//コーダー
	define('DOMAIN', 'http://localhost:3000');
	define("DOC_ROOT", "/");
	define('WWW_ASSETS_ROOT', '');
}

/* ------------------------------------------------------------------
初期値
------------------------------------------------------------------ */
define("META_TITLE_EN", "");
define("PAGE_NUM", 20);

//Contact

define("MAIL_TITLE", "Thank you for your inquiry");
define("FROM_EMAIL", array("0"=>"noreply@ferrotec.my"));

define("CONTACT_INPUT_PAGE", "/contact/");
define("CONTACT_CONFIRM_PAGE", "");
define("CONTACT_COMPLETE_PAGE", "/contact/complete/");

define("CONTACT_SUCCESS_TEXT", "CONTACT_SUCCESS_TEXT");
define("CONTACT_FAILURE_TEXT", "");

define("CONTACT_CATEGORY", array("1"=>"Product", "2"=>"Other"));

//Google reCAPTCHA
define("GOOGLE_RECAPTCHA_SITE_KEY", '');
define("GOOGLE_RECAPTCHA_SECRET_KEY", '');
define("SCORE_LIMIT", 0.5);

/* ------------------------------------------------------------------
クラス定義
------------------------------------------------------------------ */
require_once("function.php");
require_once("LLUtil.php");
require_once("LLContact.php");

?>
