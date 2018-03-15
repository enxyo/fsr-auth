<?php
require_once 'config/db.php';

$auth_password_hash = $ipb3_password_hash = $ipb3Salt = "";

function generateIPB3PasswordSalt($len=5){
    $salt = '';

    for ( $i = 0; $i < $len; $i++ )
    {
        $num   = mt_rand(33, 126);

        if ( $num == '92' )
        {
            $num = 93;
        }

        $salt .= chr( $num );
    }

    return $salt;
}

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $password = $_REQUEST['formPassword'];
    $accountId = $_REQUEST['id'];
    $key = $_REQUEST['key'];

    // prepare SQL
    $verifyReset = $pdo->prepare("SELECT * FROM users_reset WHERE users_reset.userID = ?");
    $getEmail = $pdo->prepare("SELECT users.email FROM users WHERE users.id = ?");
    $clearkReset = $pdo->prepare("DELETE FROM users_reset WHERE users_reset.userID = ?");
    $updateUser = $pdo->prepare("UPDATE users SET users.auth_password_hash = ?, users.status = ?, users.ipb3_password_hash = ?, users.ipb3_password_salt = ?, users.modified = now() WHERE users.id = ?");

    // verify reset request
    $verifyReset->execute(array($accountId));
    $count = $verifyReset->rowCount();

    if ($count != 0) {
        $result = $verifyReset->fetch();
        if($key == $result['key']) {
            // clear resets
            $clearkReset->execute(array($accountId));

            // Auth password hashing
            $hash_options = [
            'cost' => 12,
            ];
            $auth_password_hash = password_hash($password, PASSWORD_BCRYPT, $hash_options);

            // IPB3 password hashing
            $ipb3Salt = generateIPB3PasswordSalt();
            $ipb3_password_hash = md5(md5($ipb3Salt).md5($password));

            // update user
            $updateUser->execute(array($auth_password_hash, 'active', $ipb3_password_hash, $ipb3Salt, $accountId));

            // get email
            $getEmail->execute(array($accountId));
            $result = $getEmail->fetch();

            // send confirmation email

            // Send mail
            $subject = 'Password successfully updated at FSR Auth';
            $message = "Hello Ranger,\r\n\r\nwe have successfully updated your password on FSR Auth website.\r\n\r\nYOU DID NOT CHANGE YOUR PASSWORD?\r\n\r\nClick the following link to lock your account and set a new password.\r\n\r\nhttps://www.free-space-ranger.org:444/auth/lock-account?id=".$accountId."\r\n\r\nIf clicking on the above link does not work, Copy and Paste the full text of the link above into your web browser\'s address bar.\r\n\r\nSincerely,\r\nFSR IT";
            $headers = 'From: auth@free-space-ranger.org' . "\r\n" .
                'Reply-To: info@free-space-ranger.org' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();

            mail($result['email'], $subject, $message, $headers);

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
