<?php
require_once 'config/db.php';

$email = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $email = $_REQUEST['formEmail'];
    
}
?>
