<?php
class SMSflyC {
    private $baseurl = 'https://sms-fly.ua/api/api.php';
    private $login, $password, $source, $balanceuah, $lastactionstatus = true;
    private $sourceList = [];
    private $appversion = 'opencart 2.1.3';

    public function __construct($login, $password, $source) {
        $this->login = $login;
        $this->password = $password;
        $this->source = $source;
    }

    public function __get($name) {
        switch ($name) {
            case 'auth': return $this->lastactionstatus;
            case 'names':
                if ( count($this->sourceList) === 0 ) {
                    $query = <<<XML
<?xml version="1.0" encoding="utf-8"?><request><operation>MANAGEALFANAME</operation><command id="GETALFANAMESLIST"/></request>
XML;
                    $this->apiquery($query);
                }

                return $this->sourceList;
            case 'balance':
                if ( empty($this->balanceuah) ) {
                    $query = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<request>
    <operation>GETBALANCE</operation>
</request>
XML;

                    $this->apiquery($query);
                }
                return $this->balanceuah;
            default: return null;
        }
    }

    private function apiquery ($query) {
        $auth = $this->login.':'.$this->password;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERPWD , $auth);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $this->baseurl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml", "Accept: text/xml"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        $result = ($this->lastactionstatus) ? curl_exec($ch) : null;
        curl_close($ch);

        if ( !empty($result) ) {
            return $this->parser($result, $query);
        }
        return false;
    }

    private function parser ($obj, $query) {
        $data = ['success' => false, 'response' => $obj, 'error_message' => null, 'query' => $query];

        if ( $obj === 'EMPTY REQUEST' || empty($obj) ) {
            $data['error_message'] = 'Нет авторизации!';
            $this->lastactionstatus = false;
        } elseif (strpos($obj, 'Access denied') !== false) {
            $data['error_message'] = 'Не правильный логин или пароль!';
            $this->lastactionstatus = false;
        } else {
            try {
                $xml = simplexml_load_string($obj);

                if (!$xml) throw new Exception('Ошибка XML');

                preg_match('/GETALFANAMESLIST|SENDSMS|GETBALANCE/', $query, $matches);
                switch ($matches[0]) {
                    case 'GETALFANAMESLIST':
                        $data['success'] = true;
                        foreach ($xml->state as $name) if ( (string)$name['status'] === 'ACTIVE' ) array_push($this->sourceList, (string)$name['alfaname']);
                        break;
                    case 'SENDSMS':
                        switch ((string)$xml->state['code']) {
                            case 'INSUFFICIENTFUNDS':
                                $this->lastactionstatus = false;
                                $data['error_message'] = "Недостаточно средств";
                                break;
                            case 'ERRPHONES':
                                $data['error_message'] = "Неправильный номер получателя";
                                break;
                            case 'ACCEPT':
                                $data['success'] = true;
                                break;
                            case 'ERRTEXT':
                                $data['error_message'] = "Текст сообщения не может быть пустым";
                                break;
                            case 'ERRALFANAME':
                                $this->lastactionstatus = false;
                                $data['error_message'] = "Неправильное альфаимя";
                                break;
                            default:
                                $this->lastactionstatus = false;
                                $data['error_message'] = "Неизвестная ошибка";
                        }
                        break;
                    case 'GETBALANCE':
                        $data['success'] = true;
                        $this->balanceuah = (string)$xml->balance;
                        break;
                }
            } catch (Exception $e) {
                $this->lastactionstatus = false;
                $data['error_message'] = $e->getMessage();
            }
        }

        return $data;
    }

    public function send($phone, $text) {
        $recipient      = preg_replace("/[^0-9+]/",'', $phone);
        $text           = htmlspecialchars($text);

        $query = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<request>
    <operation>SENDSMS</operation>
    <message start_time="AUTO" end_time="AUTO" lifetime="12" rate="1" desc="" source="$this->source" version="$this->appversion">
        <body>$text</body>
        <recipient>$recipient</recipient>
    </message>
</request>
XML;

        return $this->apiquery($query);
    }

    static function translit($text) {
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

    static function print_a($val, $name = '---', $var_dump = false, $return = false) {
        if ($return) {
            return "<hr><h3>$name</h3><pre>".(($var_dump) ? var_dump($val):print_r ($val, true))."</pre>";
        }
        $call_from = debug_backtrace();
        if(is_bool($val))
        {
            $val = ($val) ? 'true' : 'false';
        }
        print "<div style=\"text-align: left;\"><pre>--- [".basename($call_from[0]['file'])."][".$call_from[0]['line']."] --- <b>".str_pad($name.' ', 80, "-")."</b>\r\n"; (($var_dump) ? var_dump($val):print_r ($val)); print "</pre></div>";
    }
}