<?php
/**
 * Utilクラス
 *
 * よく使用する処理を定義
 *
 */
class LLUtil{

	/* ------------------------------------------------------------------
    コンストラクタ
    ------------------------------------------------------------------ */
	public function __construct(){

	}



	/* ------------------------------------------------------------------
	
	
	一般
	
	
	------------------------------------------------------------------ */

	/* ------------------------------------------------------------------
    URLの取得
    ------------------------------------------------------------------ */
	public static function get_current_url(){
		if(isset($_SERVER["REQUEST_SCHEME"])){
			$current_url = $_SERVER["REQUEST_SCHEME"]."://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
		}else{
			$current_url = DOMAIN.$_SERVER["REQUEST_URI"];
		}
		return $current_url;
	}

	/* ------------------------------------------------------------------
    SCRIPT名の取得
    ------------------------------------------------------------------ */
	public static function get_script_name(){
		$script_name = "";
		if(isset($_SERVER["SCRIPT_NAME"])){
			$script_name = $_SERVER["SCRIPT_NAME"];
		}
		return $script_name;
	}

	/* ------------------------------------------------------------------
    POSTデータ取得
    ------------------------------------------------------------------ */
	public static function get_post(){
		$res = array();
		
		if (isset($_POST) && is_array($_POST) && count($_POST)>0) {
			$res = $_POST;
		}

		return $res;
	}

	/* ------------------------------------------------------------------
    GETデータ取得
    ------------------------------------------------------------------ */
	public static function get_get(){
		$res = array();
		
		if (isset($_GET) && is_array($_GET) && count($_GET)>0) {
			$res = $_GET;
		}

		return $res;
	}



	/* ------------------------------------------------------------------
	
	
	フォームタグ関連
	
	
	------------------------------------------------------------------ */

	/* ------------------------------------------------------------------
	配列データからselectタグのoptionを表示
	------------------------------------------------------------------ */
	public static function show_option_tag($data, $value, $label, $default=0){
		$html = "";
		foreach($data as $key=>$val){
			if($val[$value]==$default){
				$html .= "<option value='".$val[$value]."' selected>".$val[$label]."</option>";
			}else{
				$html .= "<option value='".$val[$value]."'>".$val[$label]."</option>";
			}
		}
		echo $html;
	}

	/* ------------------------------------------------------------------
	selected表示
	------------------------------------------------------------------ */

	public static function show_option_tag_by_one_array($data, $default=false){
		$html = "";
		foreach($data as $key=>$val){
			if($key==$default){
				$html .= "<option value='".$key."' selected>".$val."</option>";
			}else{
				$html .= "<option value='".$key."'>".$val."</option>";
			}
		}
		echo $html;
	}

	/* ------------------------------------------------------------------
    checked表示
    ------------------------------------------------------------------ */
	public static function show_checked($data, $val, $default=false){
		$checked = "";
		if (LLUtil::is_var($data)) {
			if($data==$val){
				$checked = " checked";
			}
		}elseif($default){
			$checked = " checked";
		}
		echo $checked;
	}

	/* ------------------------------------------------------------------
    radioテキスト表示
    ------------------------------------------------------------------ */
	public static function show_radio($val, $list){
		if(isset($list[$val])){
			echo $list[$val];
		}
	}

	/* ------------------------------------------------------------------
    disabled表示
    ------------------------------------------------------------------ */
	public static function show_disabled($val, $val2){
		$html = "";
		if($val){
			if($val==$val2){
				$html = " disabled";
			} 
		}else{
			$html = " disabled";
		}
		echo $html;
	}


	/* ------------------------------------------------------------------
	単発チェックボックスタグ取得
	------------------------------------------------------------------ */
	public static function get_textarea_tag($name, $text)
	{
		$res = "<textarea name={$name}>";
		$res .= $text;
		$res .= "</textarea>";

		return $res;
	}



	/* ------------------------------------------------------------------
	
	
	ソート関連
	
	
	------------------------------------------------------------------ */

	/* ------------------------------------------------------------------
	ソート種類の取得
	------------------------------------------------------------------ */
	public static function get_order_list($def_order_list){
		$order_list = array();
		$data = $_GET;
		if(isset($data) && count($data)>0){
			if(isset($data["c"]) && isset($data["b"])){
				$order_list["c"] = $data["c"];
				$order_list["b"] = $data["b"];
			}else{
				$order_list = $def_order_list;
			}
		}else{
			$order_list = $def_order_list;
		}
		return $order_list;
	}

	/* ------------------------------------------------------------------
	ソートパラメータの取得
	------------------------------------------------------------------ */
	public static function get_order_setting($data){
		if(count($data)>0){
			if($data["b"]=="asc"){
				$data["b"] = "desc";
			}else if($data["b"]=="desc"){
				$data["b"] = "asc";
			}
		}
		return $data;
	}

	/* ------------------------------------------------------------------
	ソートクエリー文字列の取得
	------------------------------------------------------------------ */
	public static function get_order_query($data, $col_name){
		$query = "?c=$col_name&b=";

		if(count($data)==0){
			$query .= "asc";
		}else if($data["c"]==$col_name){
			$query .= $data["b"];
		}else{
			$query .= "desc";
		}

		return $query;
	}



	/* ------------------------------------------------------------------
	
	
	ページング関連
	
	
	------------------------------------------------------------------ */
	
	/* ------------------------------------------------------------------
	ページングパスの取得
	------------------------------------------------------------------ */
	public static function get_paging_path(){
		$data = $_GET;
		$page_path = "";
		if(isset($_SERVER["QUERY_STRING"]) && $_SERVER["QUERY_STRING"]!=""){
			$page_path = $_SERVER["SCRIPT_NAME"]."?";
			foreach($data as $key=>$value){
				if($key!="p"){
					$page_path .= $key."=".$value."&";
				}
			}
		}else{
			$page_path = $_SERVER["SCRIPT_NAME"]."?";
		}
		return $page_path;
	}

	/* ------------------------------------------------------------------
	カレントページの取得
	------------------------------------------------------------------ */
	public static function get_current_page(){
		$data = $_GET;
		$current_page = 1;
		if(isset($data) && count($data)>0 && isset($data["p"])){
			$current_page = (int) $data["p"];
		}
		return $current_page;
	}



	/* ------------------------------------------------------------------
	
	
	その他
	
	
	------------------------------------------------------------------ */

	/* ------------------------------------------------------------------
    存在チェック
    ------------------------------------------------------------------ */
	public static function is_var($data){
		$res = false;
		
		if (isset($data) && $data!="") {
			$res = true;
		}

		return $res;
	}

	/* ------------------------------------------------------------------
    存在チェック
    ------------------------------------------------------------------ */
	public static function get_array_var($data, $key){
		$res = "";
		
		if (isset($data) && is_array($data) && isset($data[$key])) {
			$res = $data[$key];
		}

		return $res;
	}

	/* ------------------------------------------------------------------
    変数表示
    ------------------------------------------------------------------ */
	public static function show_var($data){
		if (LLUtil::is_var($data)) {
			echo $data;
		}
	}

	/* ------------------------------------------------------------------
    Enable表示
    ------------------------------------------------------------------ */
	public static function show_enable($data, $true_text="○", $false_text="×"){
		if ($data) {
			echo $true_text;
		}else{
			echo $false_text;
		}
	}

	/* ------------------------------------------------------------------
    Enableタグ表示
    ------------------------------------------------------------------ */
	public static function show_enable_tag($data, $value, $label, $default=0){
		$html = "";
		foreach($data as $key=>$val){
			if($val[$value]==$default){
				$html .= "<option value='".$val[$value]."' selected>".$val[$label]."</option>";
			}else{
				$html .= "<option value='".$val[$value]."'>".$val[$label]."</option>";
			}
		}
		echo $html;
	}

	/* ------------------------------------------------------------------
    Enableの値を取得
    ------------------------------------------------------------------ */
	public static function get_enable_value($data){
		if(isset($data["enable_flg"])){
			$res = $data["enable_flg"];
		}else{
			$res = 0;
		}
		return $res;
	}
}
?>
