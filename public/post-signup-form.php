<?php
error_reporting(E_ALL);

require_once 'config/db.php';

$email = $password = "";
$legacyAccount = "0";
$auth_password_hash = $ipb3_password_hash = $ipb3Salt = "";

function generateIPB3PasswordSalt($len=5)
{
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

    $email = $_REQUEST['formEmail'];
    $password = $_REQUEST['formPassword'];

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

}

print "Account created!";

?>
