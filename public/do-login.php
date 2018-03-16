<?php

require_once 'config/db.php';

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
