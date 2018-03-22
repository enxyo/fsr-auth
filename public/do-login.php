<?php

require_once 'config/db.php';
//require_once 'classes/authTokenCollection.php';

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

    // prepare statements
    $checkUser = $pdo->prepare("SELECT * FROM users WHERE users.email = ?");
    // auth log status codes - 1=success, 0=wrong pw, 2=no account
    $authLog = $pdo->prepare("INSERT INTO auth_log (auth_log.email, auth_log.status, auth_log.ip, auth_log.agent, auth_log.timestamp) VALUES (?,?,?,?,now())");

    // Check User DB
    $checkUser->execute(array($email));
    $count = $checkUser->rowCount();

    if($count == 1){

        $result = $checkUser->fetch();
        $dbPasswordHash = $result['auth_password_hash'];

        if(password_verify($password, $dbPasswordHash)) {

            // auth log
            $authLog->execute(array($email,'1',$userIp,$userAgent));

            // createAuthToken
            $dbSelector = generateRandomString(12);
            $validator = generateRandomString(64);
            $dbValidator = hashValidator($validator);

            $expires = new DateTime();
            $expires = $expires->modify('+1 month');
            $expires = $expires->format('Y-m-d H:i:s');

            // prepare statement
            $createToken = $pdo->prepare("INSERT INTO auth_tokens (auth_tokens.selector, auth_tokens.hashedValidator, auth_tokens.userid, auth_tokens.expires) VALUES (?,?,?,?)");

            // create auth token in db
            $createToken->execute(array($dbSelector, $dbValidator, $result['id'], $expires));
            //print_r($createToken->errorInfo());

            // set the cookies
            setcookie('fsrAuthCookie', $dbSelector.$validator, time() + 60*60*24*30, '/auth/');

            $response = "success";
            $res_message = "Login successful!";
        } else {
            // auth log
            $authLog->execute(array($email,'0',$userIp,$userAgent));

            $response = "error";
            $res_message = "Password does not match!";
        }
    } else {
        // auth log
        $authLog->execute(array($email,'2',$userIp,$userAgent));

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
