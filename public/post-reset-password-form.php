<?php
require_once 'config/db.php';
require_once 'classes/authTokenCollection.php';

$db = new Database();

$email = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $email = $_REQUEST['formEmail'];

    // # get account id
    // prepare statement
    $db->query("SELECT * FROM users WHERE users.email = :email");
    // bind values
    $db->bind(':email', $email);
    // execute
    $getId = $db->single();
    $accountId = $getId['id'];

    // # clear resets
    // prepare statement
    $db->query("DELETE FROM users_reset WHERE users_reset.userID = :userid");
    // bind values
    $db->bind(':userid', $accountId);
    // execute
    $db->execute();

    // # insert reset
    $key = $authTokenCollection->generateRandomString(64);

    // prepare statement
    $db->query("INSERT INTO users_reset (users_reset.userID, users_reset.key) VALUES (:userid,:key)");
    // bind values
    $db->bind(':userid', $accountId);
    $db->bind(':key', $key);
    // execute
    $db->execute();

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
