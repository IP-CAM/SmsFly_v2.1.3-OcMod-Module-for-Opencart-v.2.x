<?php
class SmsFlyC {
    private $baseurl = 'http://sms-fly.com/api/api.php';
    private $login;
    private $password;
    private $source;
	private $appversion = 'opencart 2.2.3';

    public function __construct($login, $password, $source="InfoCentr")
    {
        $this->login = $login;
        $this->password = $password;
        if ($source == "") {
	        $this->source = "InfoCentr";
        } else {
	        $this->source = htmlspecialchars($source);
        }

    }

    public function sfDebug($var,$exit = true) {
        echo "<pre>";
        var_dump($var);
        if ($exit) {
            exit();
        }
    }

    static function sfTranslit($text) {
        $text_arr = $arChar = preg_split('/(?<!^)(?!$)/u', $text);
        $abc =  Array(
            'а' => 'a',
            'б' => 'b',
            'в' => 'v',
            'г' => 'g',
            'д' => 'd',
            'е' => 'e',
            'ё' => 'jo',
            'ж' => 'zh',
            'з' => 'z',
            'и' => 'i',
            'й' => 'jj',
            'к' => 'k',
            'л' => 'l',
            'м' => 'm',
            'н' => 'n',
            'о' => 'o',
            'п' => 'p',
            'р' => 'r',
            'с' => 's',
            'т' => 't',
            'у' => 'u',
            'ф' => 'f',
            'х' => 'kh',
            'ц' => 'c',
            'ч' => 'ch',
            'ш' => 'sh',
            'щ' => 'shh',
            'ъ' => '"',
            'ы' => 'y',
            'ь' => "'",
            'э' => 'eh',
            'ю' => 'ju',
            'я' => 'ja',
            'А' => 'A',
            'Б' => 'B',
            'В' => 'V',
            'Г' => 'G',
            'Д' => 'D',
            'Е' => 'E',
            'Ё' => 'Jo',
            'Ж' => 'Zh',
            'З' => 'Z',
            'И' => 'I',
            'Й' => 'Jj',
            'К' => 'K',
            'Л' => 'L',
            'М' => 'M',
            'Н' => 'N',
            'О' => 'O',
            'П' => 'P',
            'Р' => 'R',
            'С' => 'S',
            'Т' => 'T',
            'У' => 'U',
            'Ф' => 'F',
            'Х' => 'Kh',
            'Ц' => 'C',
            'Ч' => 'Ch',
            'Ш' => 'Sh',
            'Щ' => 'Shh',
            'Ъ' => '""',
            'Ы' => 'Y',
            'Ь' => "''",
            'Э' => 'Eh',
            'Ю' => 'Ju',
            'Я' => 'Ja',
            'Є' => 'E',
            'і' => 'i',
            'І' => 'I',
            'ї' => 'i',
            'Ї' => 'I',
            '№' => '#'
        );
        $i = 0; $lat = '';

        while (isset($text_arr[$i])) {
            $lat .= isset($abc[$text_arr[$i]]) ? $abc[$text_arr[$i]] : $text_arr[$i];
            $i++;
        }

        return $lat;
    }

    public function sfSendSms($settings, $debugmode = false)
    {
        $source         = $this->source;
        $recipient      = preg_replace("/[^0-9+]/",'', $settings['SMSFLY_PHONE']);
        $text           = htmlspecialchars($settings['SMSFLY_TEXT']);
        $start_time     = 'AUTO';
        $end_time       = 'AUTO';
        $rate           = 1;
        $lifetime       = 4;
        $description    = '';
        $version        = $this->appversion;
        $textQuery 	 = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
        $textQuery 	.= "<request>";
        $textQuery 	.= "<operation>SENDSMS</operation>";
        $textQuery 	.= '		<message start_time="'.$start_time.'" end_time="'.$end_time.'" lifetime="'.$lifetime.'" rate="'.$rate.'" desc="'.$description.'" source="'.$source.'" version="'.$version.'">'."\n";
        $textQuery 	.= "		<body>".$text."</body>";
        $textQuery 	.= "		<recipient>".$recipient."</recipient>";
        $textQuery 	.=  "</message>";
        $textQuery 	.= "</request>";

		$obj = $this->sfQuery($textQuery);

		if ($debugmode) {
			$this->sfDebug($obj);
		}

	    $text = $this->sfParser($obj,'code');
		return $text;
    }

    public function sfBalance()
    {
        $textQuery 	 = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
        $textQuery 	.= "<request>";
        $textQuery 	.= "<operation>GETBALANCE</operation>";
        $textQuery 	.= "</request>";

        $obj = $this->sfQuery($textQuery);
	    return $this->sfParser($obj,'balance');
    }

    private function sfQuery ($textQuery)
    {
        $auth = $this->login.':'.$this->password;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERPWD , $auth);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $this->baseurl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml", "Accept: text/xml"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $textQuery);
        $result = curl_exec($ch);
        curl_close($ch);

		if (isset($result)) {
			return $result;
		}
		return false;
    }

    private function sfParser ($obj, $child, $attribut=null)
    {
        $text = '';
        if ($obj == 'EMPTY REQUEST' || $obj == false) {
            $text = 'Нет авторизации!';
        } elseif (strpos($obj, 'Access denied') !== false) {
	        $text = 'Не правильный логин или пароль!';
        } else {
	        $xml = new SimpleXMLElement($obj);

	        if ($child == 'balance') {
		        //$text = "У Вас на счету ".(string)$xml->balance. " грн. ";
		        $text = (string)$xml->balance;
	        } elseif ($child == 'code') {
		        $code = (string)$xml->state['code'];
		        switch ($code) {
			        case 'ERRPHONES': $text = "Неправильный номер получателя!"; break;
			        case 'ACCEPT': $text = "Сообщение отправлено."; break;
			        case 'ERRTEXT': $text = "Текст сообщения не может быть пустым."; break;
			        case 'ERRALFANAME': $text = "Неправильное альфаимя."; break;
		        }
	        }
        }

        if ((string)$text !== '') {
            return (string)$text;
        }
        $this->sfDebug($obj);
        return false;
    }
}