<?php
error_reporting(E_ALL);

function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$dbSelector = generateRandomString(12);
$validator = generateRandomString(64);

unset($_COOKIE['fsrAuthCookie']);
setcookie('fsrAuthCookie', '', time() - 3600, '/auth/'); // empty value and old timestamp
echo "remove";

//setcookie('fsrAuthCookie', $dbSelector.$validator, time() + 43200, '/auth/');
//echo "set";

?>
