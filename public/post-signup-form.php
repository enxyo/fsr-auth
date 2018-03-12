<?php

require_once 'config/db.php';

$email = $password = "";
$legacyAccount = "0";
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
    $password = $_REQUEST['formPassword'];

    // Check User DB
    $statement = $pdo->prepare("SELECT * FROM users WHERE users.email = ?");
    $statement->execute(array($email));
    $count = $statement->rowCount();

    if($count == 0) {

        // Auth password hashing
        $hash_options = [
        'cost' => 12,
        ];
        $auth_password_hash = password_hash($password, PASSWORD_BCRYPT, $hash_options);

        // IPB3 password hashing
        $ipb3Salt = generateIPB3PasswordSalt();
        $ipb3_password_hash = md5(md5($ipb3Salt).md5($password));

        // Legacy account
        if(isset($_REQUEST['formLegacyAccount'])){
            $legacyAccount = 1;
        }


        // Insert into DB
        $statement = $pdo->prepare("INSERT INTO users (users.email,users.auth_password_hash,users.status,users.ipb3_password_hash,users.ipb3_password_salt,users.legacy) VALUES (?,?,?,?,?,?)");
        $statement->execute(array($email, $auth_password_hash, 'validating', $ipb3_password_hash, $ipb3Salt, $legacyAccount));
        //$statement->debugDumpParams();

        // Insert into DB
        $accountId = $pdo->lastInsertId();
        $key = generateRandomString();

        $statement = $pdo->prepare("INSERT INTO users_verify (users_verify.userID,users_verify.key) VALUES (?,?)");
        $statement->execute(array($accountId, $key));

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
