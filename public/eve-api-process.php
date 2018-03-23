<?php
require_once 'config/db.php';
require_once 'config/eveApi.php';
require_once 'classes/authTokenCollection.php';
require_once 'functions/eveApiFunctions.php';

$db = new Database();

if (isset($_COOKIE['fsrAuthCookie'])) {
    $string = $_COOKIE['fsrAuthCookie'];
    $selector = substr($string, 0, 12);
    $validator = substr($string, -64);
    $hashedValidator = $authTokenCollection->hashValidator($validator);

    // prepare statement
    $db->query("SELECT * FROM auth_tokens WHERE auth_tokens.selector = :selector AND auth_tokens.hashedValidator = :hashValidator");
    // bind values
    $db->bind(':selector', $selector);
    $db->bind(':hashValidator', $hashedValidator);
    // execute
    $checkToken = $db->single();

    // check auth token in db
    if ($db->rowCount() == 1) {
        $userid = $checkToken['userid'];

        // ### start api work
        if(isset($_GET['code']) && $_GET['code'] !== ''){
            $tokens = json_decode(authInit($_GET['code'],$client_id,$client_secret), TRUE);
            $char = json_decode(getCharId($tokens['access_token']), TRUE);

            storeApi($char['CharacterID'], $char['CharacterName'], $char['TokenType'], $tokens['access_token'], $tokens['refresh_token'], $userid);

            header("Location: https://www.free-space-ranger.org:444/auth/eve-api"); /* Redirect browser */
            exit();
        }

    } else {
        unset($_COOKIE['fsrAuthCookie']);
        setcookie('fsrAuthCookie', '', time() - 3600, '/auth/'); // empty value and old timestamp

        header("Location: https://www.free-space-ranger.org:444/auth/signin"); /* Redirect browser */
        exit();
    }
} else {
    header("Location: https://www.free-space-ranger.org:444/auth/signin"); /* Redirect browser */
    exit();
}

?>
