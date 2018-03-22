<?php

class authTokenCollection {

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

    function createAuthToken($userid) {

        $db = new Database();

        $dbSelector = generateRandomString(12);
        $validator = generateRandomString(64);
        $dbValidator = hashValidator($validator);

        $expires = new DateTime();
        $expires = $expires->modify('+1 month');
        $expires = $expires->format('Y-m-d H:i:s');

        // prepare statement
        $db->query("INSERT INTO auth_tokens (auth_tokens.selector, auth_tokens.hashedValidator, auth_tokens.userid, auth_tokens.expires) VALUES (:selector,:hashValidator,:userid,:expires)");
        // bind values
        $db->bind(':selector', $dbSelector);
        $db->bind(':hashValidator', $dbValidator);
        $db->bind(':userid', $userid);
        $db->bind(':expires', $expires);
        // execute
        $db->execute();

        // set the cookies
        setcookie('fsrAuthCookie', $dbSelector.$validator, time() + 60*60*24*30, '/auth/');

    }


    function grabAuthToken() {

    }

    function extendAuthToken() {

    }

    function authLog($email,$status,$userIp,$userAgent) {
        $db = new Database();

        // prepare statement
        $db->query("INSERT INTO auth_log (auth_log.email, auth_log.status, auth_log.ip, auth_log.agent, auth_log.timestamp) VALUES (:email,:status,:ip,:agent,now())");
        // bind values
        $db->bind(':email', $email);
        $db->bind(':status', $status);
        $db->bind(':ip', $userIp);
        $db->bind(':agent', $userAgent);
        // execute
        $db->execute();
    }

}

$authTokenCollection = new authTokenCollection;

?>
