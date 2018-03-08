<?php
//DB credentials
define('DB_HOST','');
define('DB_NAME','');
define('DB_USER','');
define('DB_PASS','');

$pdo = new PDO('mysql:host=DB_HOST;dbname=DB_NAME', 'DB_USER', 'DB_PASS');
?>
