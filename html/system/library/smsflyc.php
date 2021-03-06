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
            $data['error_message'] = '?????? ??????????????????????!';
            $this->lastactionstatus = false;
        } elseif (strpos($obj, 'Access denied') !== false) {
            $data['error_message'] = '???? ???????????????????? ?????????? ?????? ????????????!';
            $this->lastactionstatus = false;
        } else {
            try {
                $xml = simplexml_load_string($obj);

                if (!$xml) throw new Exception('???????????? XML');

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
                                $data['error_message'] = "???????????????????????? ??????????????";
                                break;
                            case 'ERRPHONES':
                                $data['error_message'] = "???????????????????????? ?????????? ????????????????????";
                                break;
                            case 'ACCEPT':
                                $data['success'] = true;
                                break;
                            case 'ERRTEXT':
                                $data['error_message'] = "?????????? ?????????????????? ???? ?????????? ???????? ????????????";
                                break;
                            case 'ERRALFANAME':
                                $this->lastactionstatus = false;
                                $data['error_message'] = "???????????????????????? ????????????????";
                                break;
                            default:
                                $this->lastactionstatus = false;
                                $data['error_message'] = "?????????????????????? ????????????";
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
            '??' => 'a',
            '??' => 'b',
            '??' => 'v',
            '??' => 'g',
            '??' => 'd',
            '??' => 'e',
            '??' => 'jo',
            '??' => 'zh',
            '??' => 'z',
            '??' => 'i',
            '??' => 'jj',
            '??' => 'k',
            '??' => 'l',
            '??' => 'm',
            '??' => 'n',
            '??' => 'o',
            '??' => 'p',
            '??' => 'r',
            '??' => 's',
            '??' => 't',
            '??' => 'u',
            '??' => 'f',
            '??' => 'kh',
            '??' => 'c',
            '??' => 'ch',
            '??' => 'sh',
            '??' => 'shh',
            '??' => '"',
            '??' => 'y',
            '??' => "'",
            '??' => 'eh',
            '??' => 'ju',
            '??' => 'ja',
            '??' => 'A',
            '??' => 'B',
            '??' => 'V',
            '??' => 'G',
            '??' => 'D',
            '??' => 'E',
            '??' => 'Jo',
            '??' => 'Zh',
            '??' => 'Z',
            '??' => 'I',
            '??' => 'Jj',
            '??' => 'K',
            '??' => 'L',
            '??' => 'M',
            '??' => 'N',
            '??' => 'O',
            '??' => 'P',
            '??' => 'R',
            '??' => 'S',
            '??' => 'T',
            '??' => 'U',
            '??' => 'F',
            '??' => 'Kh',
            '??' => 'C',
            '??' => 'Ch',
            '??' => 'Sh',
            '??' => 'Shh',
            '??' => '""',
            '??' => 'Y',
            '??' => "''",
            '??' => 'Eh',
            '??' => 'Ju',
            '??' => 'Ja',
            '??' => 'E',
            '??' => 'i',
            '??' => 'I',
            '??' => 'i',
            '??' => 'I',
            '???' => '#'
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