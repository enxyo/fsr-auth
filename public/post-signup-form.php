<?php

require_once 'config/db.php';
require_once 'classes/authTokenCollection.php';
require_once 'classes/ipb3Collection.php';

$db = new Database();

$email = $password = "";
$legacyAccount = "0";
$auth_password_hash = $ipb3_password_hash = $ipb3Salt = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $email = $_REQUEST['formEmail'];
    $password = $_REQUEST['formPassword'];

    // # Check User DB
    // prepare statement
    $db->query("SELECT * FROM users WHERE users.email = :email");
    // bind values
    $db->bind(':email', $email);
    // execute
    $checkUser = $db->single();

    if($db->rowCount() == 0) {

        // Auth password hashing
        $hash_options = [
        'cost' => 12,
        ];
        $auth_password_hash = password_hash($password, PASSWORD_BCRYPT, $hash_options);

        // IPB3 password hashing
        $ipb3Salt = $ipb3Collection->generateIPB3PasswordSalt();
        $ipb3_password_hash = md5(md5($ipb3Salt).md5($password));

        // Legacy account
        if(isset($_REQUEST['formLegacyAccount'])){
            $legacyAccount = 1;
        }


        // # Insert into DB
        // prepare statement
        $db->query("INSERT INTO users (users.email,users.auth_password_hash,users.status,users.ipb3_password_hash,users.ipb3_password_salt,users.legacy) VALUES (:email,:auth_password_hash,:status,:ipb3_password_hash,:ipb3_password_salt,:legacy)");
        // bind values
        $db->bind(':email', $email);
        $db->bind(':auth_password_hash', $auth_password_hash);
        $db->bind(':status', 'validating');
        $db->bind(':ipb3_password_hash', $ipb3_password_hash);
        $db->bind(':ipb3_password_salt', $ipb3Salt);
        $db->bind(':legacy', $legacyAccount);
        // execute
        $db->execute();


        // Insert into DB
        $accountId = $db->lastInsertId();
        $key = $authTokenCollection->generateRandomString(64);

        // prepare statement
        $db->query("INSERT INTO users_verify (users_verify.userID,users_verify.key) VALUES (:userid,:key)");
        // bind values
        $db->bind(':userid', $accountId);
        $db->bind(':key', $key);
        // execute
        $db->execute();

        // Send mail
        $subject = 'Account Registration at FSR Auth';
        $message = "Hello Ranger,\r\n\r\nwe have received your request to register an account on FSR Auth website.\r\n\r\nTO CONFIRM ACCOUNT REGISTRATION\r\n\r\nClick the following link to confirm your email address and to activate your personal account ". $email ."(please note that the link will expire in 24 hours).\r\n\r\nhttps://www.free-space-ranger.org:444/auth/verify-email?id=".$accountId."&key=".$key."\r\n\r\nIf clicking on the above link does not work, Copy and Paste the full text of the link above into your web browser\'s address bar.\r\n\r\nIf you did not request, or do not want, an account on FSR Auth website,\r\nplease accept our apologies and ignore/delete this message.\r\n\r\nSincerely,\r\nFSR IT";
        $headers = 'From: auth@free-space-ranger.org' . "\r\n" .
            'Reply-To: info@free-space-ranger.org' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        mail($email, $subject, $message, $headers);

        $response = "success";
        $res_message = "Account created!";

    } else {
        $response = "error";
        $res_message = "Account already exists!";
    }



}

$return[] = array("response" => $response, "message" => $res_message);
echo json_encode($return);
//print "Account created!";

?>
