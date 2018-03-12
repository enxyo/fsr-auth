<?php
require_once 'config/db.php';

$email = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $email = $_REQUEST['formEmail'];

}

$return[] = array("response" => $response, "message" => $res_message);
echo json_encode($return);
?>
