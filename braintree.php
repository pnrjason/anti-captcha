<?php
require_once 'braintreesdk/lib/Braintree.php';

$gateway = new Braintree\Gateway([
    'environment' => 'sandbox', // braintree production keys
    'merchantId' => 'fvhw7898pxbn22gm',
    'publicKey' => 'b8bxd8h8rm9hnq4k',
    'privateKey' => '521c150e9bb8c94478cbd0f78b19c479'
]);

//best option list(), you only need change the delimeter.
list($num, $month, $year, $sec) = explode("|", $_GET['lista'], 4);

//random amount min = 0.xx, max = 5;
$amount = mt_rand(1*10, 5*10)/10;

$result = $gateway->transaction()->sale([
    'amount' => $amount,
    'creditCard' => [
        'number' => $num,
        'expirationDate' => "$month/$year",
        'cvv' => $sec,
    ],
    'customer' => [
        'firstName' => 'Ragnar',
        'lastName' => 'Magnusson'
    ],
    'options' => [
      'submitForSettlement' => true
    ],
    'billing' => [
        'postalCode' => mt_rand(10080, 94545)
    ]
]);

$msg = array(
    'approved' => $result->success, 
    'responsecode' => $result->transaction->networkResponseCode, 
    'responsetext' => $result->transaction->networkResponseText, 
    'cvv' => $result->transaction->cvvResponseCode, 
    'reject' => $result->transaction->gatewayRejectionReason, 
    'amount' => amount, 
    'processorcode' => $result->transaction->processorResponseCode, 
    'processortext' => $result->transaction->processorResponseText
);

header("Content-type: application/json; charset=utf-8");
echo json_encode($msg);

?>
 