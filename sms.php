<?php
$APP_KEY = "xxxx";
$APP_SECRET = "xxxx";
$url =  "https://api.telstra.com/v1/oauth/token?client_id=$APP_KEY&client_secret=$APP_SECRET&grant_type=client_credentials&scope=SMS";
// get token
$token = getInitialToken($url);
$token = preg_replace('/["]/', '', $token[1]);
$token = ltrim($token);
// send sms
$result = sendSms($token,'xxxx');
// get sms status
$fullMessageId = explode(':',$result);
$response = preg_replace('/["]/', '', $fullMessageId[1]);
$response = rtrim($response,'}');
$status = getStatus($token,$response);

// var_dump all status or response

// all function
function getInitialToken($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $output = curl_exec($ch);
    curl_close($ch);
    $temp = explode(',',$output);
    $target = explode(':',$temp[0]);

    return $target;
}

function sendSms($token,$number){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.telstra.com/v1/sms/messages');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: Bearer '.$token
    ));
    $content = "{\"to\":\"$number\", \"body\":\"Website is Down!\"}";
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

function getStatus($token,$messageId){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.telstra.com/v1/sms/messages/'.$messageId);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization: Bearer '.$token
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}






