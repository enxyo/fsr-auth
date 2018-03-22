<?php

require_once 'config/db.php';
require_once 'classes/authTokenCollection.php';

$db = new Database();

function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function hashValidator($string) {
    $hash = hash('sha256',$string);
    return $hash;
}

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $email = $_REQUEST['formEmail'];
    $password = $_REQUEST['formPassword'];

    $userIp = $_SERVER['REMOTE_ADDR'];
    $userAgent = $_SERVER['HTTP_USER_AGENT'];

    // # Check User DB
    // prepare statement
    $db->query("SELECT * FROM users WHERE users.email = :email");
    // bind values
    $db->bind(':email', $email);
    // execute
    $checkUser = $db->single();

    if($db->rowCount() == 1){

        $dbPasswordHash = $checkUser['auth_password_hash'];

        if(password_verify($password, $dbPasswordHash)) {

            // # auth log
            $authTokenCollection->authLog($email, 1, $userIp, $userAgent);

            // # create Auth Token
            $authTokenCollection->createAuthToken($checkUser['id']);

            $response = "success";
            $res_message = "Login successful!";
        } else {
            // auth log
            $authTokenCollection->authLog($email, 0, $userIp, $userAgent);

            $response = "error";
            $res_message = "Password does not match!";
        }
    } else {
        // auth log
        $authTokenCollection->authLog($email, 2, $userIp, $userAgent);

        $response = "error";
        $res_message = "No Account found!";
    }
} else {
    $response = "error";
    $res_message = "Something went wrong!";
}

$return[] = array("response" => $response, "message" => $res_message);
echo json_encode($return);
?>
