<?php

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

/*
function createAuthToken($userid) {

    $dbSelector = generateRandomString(12);
    $validator = generateRandomString(64);
    $dbValidator = hashValidator($validator);

    $expires = new DateTime();
    $expires->modify('+1 month');
    $expires->format('Y-m-d H:i:s');

    echo "1";
    // prepare statement
    $createToken = $pdo->prepare("INSERT INTO auth_tokens (auth_tokens.selector, auth_tokens.hashedValidator, auth_tokens.userid, auth_tokens.expires) VALUES (?,?,?,?)");

    // create auth token in db
    $createToken->execute(array($dbSelector, $dbValidator, $userid, $expires));
    //print_r($createToken->errorInfo());

    // set the cookies
    setcookie("fsrAuthCookie", $dbSelector.$validator, $expires, "/auth/", "www.free-space-ranger.org:444", 1);

//    return "done";
}
*/

function grabAuthToken() {

}

function extendAuthToken() {

}



class authTokenCollection {

    function classTest() {
        $db = new Database();
        // prepare statement
        $db->query('SELECT * FROM users WHERE id = :id');
        //$test = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $db->bind(':id', '1');
        // execute
        //$test->execute(array(1));
        //$result = $test->fetch();
        $row = $db->single();
        return $row['email'];
    }
}

$authTokenCollection = new authTokenCollection;
?>
