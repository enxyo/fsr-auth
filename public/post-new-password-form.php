<?php
require_once 'config/db.php';
require_once 'classes/ipb3Collection.php';

$db = new Database();

$auth_password_hash = $ipb3_password_hash = $ipb3Salt = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $password = $_REQUEST['formPassword'];
    $accountId = $_REQUEST['id'];
    $key = $_REQUEST['key'];

    // # verify reset request
    // prepare statement
    $db->query("SELECT * FROM users_reset WHERE users_reset.userID = :userid");
    // bind values
    $db->bind(':userid', $accountId);
    // execute
    $checkToken = $db->single();

    if ($db->rowCount() == 1) {

        if($key == $checkToken['key']) {
            // # clear resets
            // prepare statement
            $db->query("DELETE FROM users_reset WHERE users_reset.userID = :userid");
            // bind values
            $db->bind(':userid', $accountId);
            // execute
            $db->execute();

            // Auth password hashing
            $hash_options = [
            'cost' => 12,
            ];
            $auth_password_hash = password_hash($password, PASSWORD_BCRYPT, $hash_options);

            // IPB3 password hashing
            $ipb3Salt = $ipb3Collection->generateIPB3PasswordSalt();
            $ipb3_password_hash = md5(md5($ipb3Salt).md5($password));

            // # update user
            // prepare statement
            $db->query("UPDATE users SET users.auth_password_hash = :auth_password_hash, users.status = :status, users.ipb3_password_hash = :ipb3_password_hash, users.ipb3_password_salt = :ipb3_password_salt, users.modified = now() WHERE users.id = :userid");
            // bind values
            $db->bind(':auth_password_hash', $auth_password_hash);
            $db->bind(':status', 'active');
            $db->bind(':ipb3_password_hash', $ipb3_password_hash);
            $db->bind(':ipb3_password_salt', $ipb3Salt);
            $db->bind(':userid', $accountId);
            // execute
            $db->execute();

            // # get email
            // prepare statement
            $db->query("SELECT users.email FROM users WHERE users.id = :userid");
            // bind values
            $db->bind(':userid', $accountId);
            // execute
            $getEmail = $db->single();

            // send confirmation email

            // Send mail
            $subject = 'Password successfully updated at FSR Auth';
            $message = "Hello Ranger,\r\n\r\nwe have successfully updated your password on FSR Auth website.\r\n\r\nYOU DID NOT CHANGE YOUR PASSWORD?\r\n\r\nClick the following link to lock your account and set a new password.\r\n\r\nhttps://www.free-space-ranger.org:444/auth/lock-account?id=".$accountId."\r\n\r\nIf clicking on the above link does not work, Copy and Paste the full text of the link above into your web browser\'s address bar.\r\n\r\nSincerely,\r\nFSR IT";
            $headers = 'From: auth@free-space-ranger.org' . "\r\n" .
                'Reply-To: info@free-space-ranger.org' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();

            mail($getEmail['email'], $subject, $message, $headers);

            $response = "success";
            $res_message = "Password updated!";

        } else {
            $response = "error";
            $res_message = "Invalid reset token!";
        }
    } else {
        $response = "error";
        $res_message = "Invalid reset token!";
    }

}

$return[] = array("response" => $response, "message" => $res_message);
echo json_encode($return);
?>
