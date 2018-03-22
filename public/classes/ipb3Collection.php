<?php

class ipb3Collection {

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

}

$ipb3Collection = new ipb3Collection();

?>
