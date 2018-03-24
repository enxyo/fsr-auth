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

function storeApi($CharacterID,$CharacterName,$TokenType,$access_token,$refresh_token, $authUserId) {

    $db = new Database();

    // check for first entry
    $db->query("SELECT * FROM api_tokens WHERE api_tokens.authUserId = :authUserId");
    $db->bind(':authUserId', $authUserId);
    $db->execute();
    $numRows = $db->rowCount();

    // prepare statement
    $db->query("INSERT INTO api_tokens (api_tokens.characterID, api_tokens.accessToken, api_tokens.refreshToken, api_tokens.characterName, api_tokens.tokenType, api_tokens.authUserId, api_tokens.primary) VALUES (:CharacterID, :accessToken, :refreshToken, :characterName, :tokenType, :authUserId, :primary)");
    // bind values
    $db->bind(':CharacterID', $CharacterID);
    $db->bind(':accessToken', $access_token);
    $db->bind(':refreshToken', $refresh_token);
    $db->bind(':characterName', $CharacterName);
    $db->bind(':tokenType', $TokenType);
    $db->bind(':authUserId', $authUserId);
    if ($numRows == 0) {
        $db->bind(':primary', "1");
    } else {
        $db->bind(':primary', "0");
    }
    // execute
    $db->execute();
}

function setPrimaryChar($CharacterID,$authUserId) {

    $db = new Database();

    // clear primarys
    $db->query("UPDATE api_tokens SET api_tokens.primary = :primary WHERE api_tokens.authUserId = :authUserId");
    $db->bind(':authUserId', $authUserId);
    $db->bind(':primary', "0");
    $db->execute();

    // set new primary
    $db->query("UPDATE api_tokens SET api_tokens.primary = :primary WHERE api_tokens.characterID = :CharacterID");
    $db->bind(':CharacterID', $CharacterID);
    $db->bind(':primary', "1");
    $db->execute();

}

?>
