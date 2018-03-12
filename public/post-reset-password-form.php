<?php
require_once 'config/db.php';

$email = "";

function generateRandomString($length = 64) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $email = $_REQUEST['formEmail'];

    // prepare SQL
    $getId = $pdo->prepare("SELECT * FROM users WHERE users.email = ?");
    $clearkReset = $pdo->prepare("DELETE FROM users_reset WHERE users_reset.userID = ?");
    $createReset = $pdo->prepare("INSERT INTO users_reset (users_reset.userID, users_reset.key) VALUES (?,?)");

    //get account id
    $getId->execute(array($email));
    $result = $getId->fetch();
    $accountId = $result['id'];

    // clear resets
    $clearkReset->execute(array($accountId));

    // insert reset
    $key = generateRandomString();
    $createReset->execute(array($accountId, $key));

    // Send mail
    $subject = 'Password reset at FSR Auth';
    $message = "Hello Ranger,\r\n\r\nwe have received your request to reset your password on FSR Auth website.\r\n\r\nTO RESET YOUR PASSWORD\r\n\r\nClick the following link to set a new password (please note that the link will expire in 1 hour).\r\n\r\nhttps://www.free-space-ranger.org:444/auth/new-password?id=".$accountId."&key=".$key."\r\n\r\nIf clicking on the above link does not work, Copy and Paste the full text of the link above into your web browser\'s address bar.\r\n\r\nIf you did not request a password reset,\r\nplease accept our apologies and ignore/delete this message.\r\n\r\nSincerely,\r\nFSR IT";
    $headers = 'From: auth@free-space-ranger.org' . "\r\n" .
        'Reply-To: info@free-space-ranger.org' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    mail($email, $subject, $message, $headers);

    $response = "success";
    $res_message = "Password reseted, check your email.";

}

$return[] = array("response" => $response, "message" => $res_message);
echo json_encode($return);
?>
