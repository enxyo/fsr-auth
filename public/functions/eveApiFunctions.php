<?php

function authInit($code,$client_id,$client_secret) {
    // The data to send to the API
    $postData = array(
        'grant_type' => 'authorization_code',
        'code' => $code,
    );
    // Setup cURL
    $ch = curl_init('https://login.eveonline.com/oauth/token');
    curl_setopt_array($ch, array(
        CURLOPT_POST => TRUE,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Basic '.base64_encode($client_id.':'.$client_secret)
        ),
        CURLOPT_POSTFIELDS => json_encode($postData)
    ));
    // Send the request
    $response = curl_exec($ch);
    return $response;
}
function refreshToken($refreshToken,$client_id,$client_secret) {

    // The data to send to the API
    $postData = array(
        'grant_type' => 'refresh_token',
        'code' => $refreshToken,
    );
    // Setup cURL
    $ch = curl_init('https://login.eveonline.com/oauth/token');
    curl_setopt_array($ch, array(
        CURLOPT_POST => TRUE,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Basic '.base64_encode($client_id.':'.$client_secret)
        ),
        CURLOPT_POSTFIELDS => json_encode($postData)
    ));
    // Send the request
    $response = curl_exec($ch);

    return $response;

}
function getCharId($accessToken) {

    // Setup cURL
    $ch = curl_init('https://login.eveonline.com/oauth/verify');
    curl_setopt_array($ch, array(
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$accessToken
        )
    ));
    // Send the request
    $response = curl_exec($ch);
    
    return $response;

}

?>
