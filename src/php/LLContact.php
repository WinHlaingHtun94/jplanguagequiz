<?php
/**
 * 問い合わせコントロールクラス
 *
 * 問い合わせ操作の関する処理を定義
 *
 */

class LLContact
{
    /* ------------------------------------------------------------------
    変数定義
    ------------------------------------------------------------------ */

    private $setting_data = array();
	private $sess = array();
    private $confirm_flg = array();
    private $data = array();
    private $error = array();
	private $send_res = false;

    /* ------------------------------------------------------------------
    コンストラクタ
    ------------------------------------------------------------------ */

    public function __construct($setting_data = array(), &$sess, $confirm_flg=false)
    {
        $this->setting_data = $setting_data;
		$this->sess = &$sess;
        $this->confirm_flg = $confirm_flg;
    }

    /* ------------------------------------------------------------------
    バリデーション
    ------------------------------------------------------------------ */

    public function validation($data = array(), $other_check_flg=false)
    {
        $res = true;
        if (isset($data) && count($data)>0) {
            $this->data = $this->format_data($data);
            
			if ($this->is_data($this->data)) {
                foreach ($this->data as $key=>$val) {
                    if (in_array($key, $this->setting_data["required_list"])) {
                        $this->check_exist($this->data[$key], $key);
                    }
                }

                if($other_check_flg){
                    $this->validation_other($this->data);
                }
				
                if (count($this->error)>0) {
                    $res = false;
                }
            }
        }else{
			$res = false;
		}
        return $res;
    }

    /* ------------------------------------------------------------------
    その他のバリデーション
    ------------------------------------------------------------------ */
    private function validation_other($data = array()){
        if($data["recuperation_preiod"]<0){
            $this->error["recuperation_preiod"] = CONTACT_ERROR_RECUPERATION_PREIOD;
        }
    }

    /* ------------------------------------------------------------------
    データフォーマット
    ------------------------------------------------------------------ */

    private function format_data($data)
    {
        $format_data = array();
        foreach ($data as $key=>$val) {
            $format_data[$key] = trim($val);
        }
        return $format_data;
    }

    /* ------------------------------------------------------------------
    データ配列確認
    ------------------------------------------------------------------ */

    public function is_data($data = array())
    {
        $is_data = false;
        if (isset($data) && count($data)>0) {
            $is_data = true;
        }
        return $is_data;
    }

    /* ------------------------------------------------------------------
    存在確認、エラーセット
    ------------------------------------------------------------------ */

    private function check_exist($data, $name)
    {
        if (isset($data)) {
			if (in_array($name, $this->setting_data["required_list"]) && $data=="") {
                $this->error[$name] = $this->setting_data["error_text"][$name];
            }
        } else {
            $this->error[$name] = $this->setting_data["error_text"][$name];
        }
    }


	/* ------------------------------------------------------------------
    入力後のページ遷移
    ------------------------------------------------------------------ */

    public function go_next()
    {
		if($this->confirm_flg){
			$this->go_confirm();
		}else{
			$this->go_complete();
		}
	}


    /* ------------------------------------------------------------------
    確認画面に遷移
    ------------------------------------------------------------------ */

	public function go_confirm()
    {
        $this->sess->set("contact_instance", serialize($this));

        header('Location: '.CONTACT_CONFIRM_PAGE);
        exit;
    }

	/* ------------------------------------------------------------------
    確認画面に遷移
    ------------------------------------------------------------------ */

	public function go_complete()
    {
    	$this->sess->set("contact_instance", serialize($this));
		
        header('Location: '.CONTACT_COMPLETE_PAGE);
        exit;
    }

	/* ------------------------------------------------------------------
    メール送信
    ------------------------------------------------------------------ */

    public function send($replace_list=array(), $recaptcha_flg=false, $wordwrap_flg=true, $switch_email_flg=false)
    {
		//メール送信可能かの判断
		$send_ok_flg = $this->get_send_ok($recaptcha_flg);
		
		if($send_ok_flg){
            if($wordwrap_flg){
                $this->data["message_with_br"] = $this->mb_wordwrap($this->data["message"]);
            }
			$after_replace_data = $this->replace_id_to_text($this->data, $replace_list);

            if(isset($after_replace_data["title"]) && $after_replace_data["title"]=="Others" && isset($after_replace_data["extra_title"])){
                $after_replace_data["title"] .= " (".$after_replace_data["extra_title"].")";
            }

			$mail_body = file_get_contents($this->setting_data["mail_template"]);
			$mail_body = $this->replace_template($mail_body, $after_replace_data);

			$mail_to = $this->data["email"];

			$mail_title = $this->setting_data["mail_title"];

			if($switch_email_flg && isset($this->data["category"]) && $this->data["category"]>0){
				$category_id = $this->data["category"];
				$mail_header = ("From: ".$this->setting_data["mail_from"][$category_id]."\n");
				$mail_header .= ("Bcc: ".$this->setting_data["mail_bcc"][$category_id]."\n");
			}else{
				$mail_header = ("From: ".$this->setting_data["mail_from"][0]."\n");
				$mail_header .= ("Bcc: ".$this->setting_data["mail_bcc"][0]."\n");
			}

			$mail_header .= ("X-Mailer: PHP/" . phpversion() . "\n");
            $mail_header .= ("MIME-Version: 1.0\n");
            $mail_header .= ("Content-Type: text/plain; charset=ISO-2022-JP\n");
            $mail_header .= ("Content-Transfer-Encoding: 7bit\n");
            $this->send_res = mb_send_mail($mail_to, $mail_title, $mail_body, $mail_header);
            
			if($this->send_res){
				$this->delete_instance();
			}
		}
		
        return $this->send_res;
    }

	/* ------------------------------------------------------------------
    メール送信OKからのチェック
    ------------------------------------------------------------------ */
	private function get_send_ok($recaptcha_flg){
		$send_ok_flg = false;
		$referer_ok_flg = false;

		//recaptchaチェック
		if($recaptcha_flg){
			if ($this->check_recaptcha()) {
				$send_ok_flg = true;
			}
		}else{
			$send_ok_flg = true;
		}

		//セッション、リファラーチェック
		if($send_ok_flg && self::is_contact_instance($this->sess) && isset($_SERVER["HTTP_REFERER"])){
			$http_referer = $_SERVER["HTTP_REFERER"];
			
			foreach(ALLOW_SEND_LIST as $key=>$val){
				if(preg_match("/".preg_quote($val)."/", $http_referer)){
					$referer_ok_flg = true;
					break;
				}
			}
			if(!$referer_ok_flg){
				$send_ok_flg = false;
			}
		}
        
		return $send_ok_flg;
	}

	/* ------------------------------------------------------------------
    idからtextへの置き換え
    ------------------------------------------------------------------ */

	public function replace_id_to_text($data, $replace_list){
		$after_replace_data = $data;
		foreach($replace_list as $key=>$val){
            if(isset($after_replace_data[$key]) && isset($val[$after_replace_data[$key]])){
                $after_replace_data[$key] = $val[$after_replace_data[$key]];
            }else{
                $after_replace_data[$key] = "";
            }
			
		}
		return $after_replace_data;
	}

	/* ------------------------------------------------------------------
    recaptchaチェック
    ------------------------------------------------------------------ */

    private function check_recaptcha()
    {
        $res = false;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
            'secret' => GOOGLE_RECAPTCHA_SECRET_KEY,
            'response' => $this->data['g_recaptcha_token']
        )));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $api_response = curl_exec($ch);
        curl_close($ch);
        $recaptcha_res = json_decode($api_response);
        if (isset($recaptcha_res->success) && $recaptcha_res->success==1) {
            if (isset($recaptcha_res->score) && $recaptcha_res->score>=SCORE_LIMIT) {
                $res = true;
            }
        }
        return $res;
    }

	/* ------------------------------------------------------------------
    コンタクトインスタンスセッション削除
    ------------------------------------------------------------------ */

    public function delete_instance()
    {
		if (self::is_contact_instance($this->sess)) {
			$this->sess->delete("contact_instance");
		}
	}

	/* ------------------------------------------------------------------
    メールテンプレートの文字列置き換え
    ------------------------------------------------------------------ */

    private function replace_template($tmp, $replace)
    {
        foreach ($replace as $key => $value) {
            if (!is_array($value)) {
                $tmp = str_replace('<['.$key.']>', $value, $tmp);
            }
        }
        return $tmp;
    }

	/* ------------------------------------------------------------------
    送信結果の文章を表示
    ------------------------------------------------------------------ */

    public function show_send_res_text()
    {
        if($this->send_res){
			echo CONTACT_SUCCESS_TEXT;
		}else{
			echo CONTACT_FAILURE_TEXT;

		}
    }

	/* ------------------------------------------------------------------
    POSTデータ取得
    ------------------------------------------------------------------ */

    public function get_data()
    {
        return $this->data;
    }

    /* ------------------------------------------------------------------
    POSTデータに追加
    ------------------------------------------------------------------ */

    public function set_data($key, $val)
    {
        $this->data[$key] = $val;
    }

	/* ------------------------------------------------------------------
    エラー表示
    ------------------------------------------------------------------ */

    public function show_error()
    {
		$error_html = "";
		if(count($this->error)>0){
			$error_html = $this->setting_data["before_error_html"];
			
			foreach($this->error as $key=>$val){
            	$error_html .= "<p>".$val."</p>";
			}

			$error_html .= $this->setting_data["after_error_html"];
        }

		echo $error_html;
    }

	/* ------------------------------------------------------------------
    問い合わせデータをCSVに出力
    ------------------------------------------------------------------ */
	public function output_csv($replace_list){
		//CSV出力
		$csv_data = $this->get_csv_data($replace_list);
		LLFile::output_csv($csv_data, CONTACT_CSV_PATH);
	}

	/* ------------------------------------------------------------------
    CSVデータの取得
    ------------------------------------------------------------------ */

    public function get_csv_data($replace_list)
    {
		$csv_data = array();
		$after_replace_data = $this->replace_id_to_text($this->data, $replace_list);

        foreach(CONTACT_OUTPUT_CSV_LIST as $key=>$val){
			$csv_data[] = $after_replace_data[$val];
		}
		
		return array($csv_data);
    }

	/* ------------------------------------------------------------------
    問い合わせデータをDBに保存
    ------------------------------------------------------------------ */
	public function save_db($db){
		//DB保存
		$data = $this->data;
		$sql = 'INSERT INTO consultations (user_id, first_time, title, message, recuperation_preiod, situation, enable_flg) VALUES(:user_id, :first_time, :title, :message, :recuperation_preiod, :situation, :enable_flg)';
		$param = array(
			array("name"=>"user_id", "value"=>$data["user_id"], "type"=>PDO::PARAM_INT),
			array("name"=>"first_time", "value"=>$data["first_time"], "type"=>PDO::PARAM_INT),
			array("name"=>"title", "value"=>$data["title"], "type"=>PDO::PARAM_STR),
			array("name"=>"message", "value"=>$data["message"], "type"=>PDO::PARAM_STR),
			array("name"=>"recuperation_preiod", "value"=>$data["recuperation_preiod"], "type"=>PDO::PARAM_INT),
			array("name"=>"situation", "value"=>$data["situation"], "type"=>PDO::PARAM_INT),
			array("name"=>"enable_flg", "value"=>1, "type"=>PDO::PARAM_INT)
		);

		$res = $db->set_data($sql, $param);
		return $res;
	}

    /* ------------------------------------------------------------------
    文字の自動改行（メール1000バイト制限対応）
    ------------------------------------------------------------------ */

	private function mb_wordwrap($string, $width=450, $break="\n") {
        $res = "";
        $regexp = '#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){'.$width.'}#';

        $string = str_replace(array("\r\n", "\r", "\n"), "\n", $string);
        $string_list =explode("\n", $string);

        foreach($string_list as $key=>$val){
            $string = $val;

            $string_length = mb_strlen($string, 'UTF-8');
            $cut_length = ceil($string_length / $width);
            $i = 1;
            
            while ($i < $cut_length) {
                preg_match($regexp, $string, $matches);
                $new_string = $matches[0];
                $res .= $new_string.$break;
                $string = substr($string, strlen($new_string));
                $i++;
            }

            $res .= $string.$break;
        }
        
		return $res;
	}


	/* ------------------------------------------------------------------
    STATIC関数
    ------------------------------------------------------------------ */


	/* ------------------------------------------------------------------
    POSTデータがあればPOSTデータ、SESSIONデータがあればSESSIONデータを取得
	POST > SESSION
    ------------------------------------------------------------------ */

	public static function get_post_or_session($sess){
		$contact_data = array();
		
		if (isset($_POST) && count($_POST)>0) {
			$contact_data = $_POST;
		}else if (self::is_contact_instance($sess)) {
			$contact_instance = self::get_contact_instance($sess);
			$contact_data = $contact_instance->get_data();
		}

		return $contact_data;
	}
	
	/* ------------------------------------------------------------------
    問い合わせデータがセッションにあるか
    ------------------------------------------------------------------ */

    public static function is_contact_instance($sess)
    {
        if ($sess->get("contact_instance")) {
            return true;
        } else {
            return false;
        }
    }

	/* ------------------------------------------------------------------
    セッションの問い合わせデータを取得
    ------------------------------------------------------------------ */

    public static function get_contact_instance($sess, $go_flg=true)
    {
		$contact_instance = null;
        if (self::is_contact_instance($sess)) {
            $contact_instance = unserialize($sess->get("contact_instance"));
        } elseif($go_flg) {
            self::go_input();
        }

		return $contact_instance;
    }

	/* ------------------------------------------------------------------
    入力ページに遷移
    ------------------------------------------------------------------ */

    public static function go_input()
    {
        header('Location: '.CONTACT_INPUT_PAGE);
        exit;
    }

    /* ------------------------------------------------------------------
    recaptchaのJS表示
    ------------------------------------------------------------------ */
    public function show_recaptcha_js($recaptcha_site_key){
        echo <<< EOM
        <script type="text/javascript">
            $("#contactReForm").submit(function(event) {
                event.preventDefault();
                return grecaptcha.ready(function() {
                    grecaptcha.execute('{$recaptcha_site_key}', {
                    action: 'reContact'
                    }).then(function(token) {
                    $('#contactReForm').prepend('<input id="recaptchaToken" type="hidden" name="g_recaptcha_token" value="' + token + '">');
                    $('#contactReForm').prepend('<input type="hidden" name="action" value="reContact">');
                    $('#contactReForm').unbind('submit').submit();
                    });
                });
            });
        </script>
        EOM;
    }
}
