<?php
require_once 'config/db.php';

if(isset($_GET['id']) && $_GET['id'] !== ''){
    $accountId = $_GET['id'];
    if(isset($_GET['key']) && $_GET['key'] !== ''){
        $key = $_GET['key'];

        // Check User DB
        $statement = $pdo->prepare("SELECT * FROM users_verify WHERE users_verify.userID = ?");
        $statement->execute(array($accountId));
        $count = $statement->rowCount();
        $result = $statement->fetch();

        // Prepare SQL
        $changeStatus = $pdo->prepare("UPDATE users SET users.status=?, users.modified=now() WHERE users.id = ?");
        $delVerification = $pdo->prepare("DELETE FROM users_verify WHERE users_verify.userID = ?");

        if($count != 0) {
            if($key == $result['key']){
                $success = 1;
                $changeStatus->execute(array('active', $accountId));
                $delVerification->execute(array($accountId));
            } else {
                $success = 0;
            }
        } else {
            $success = 0;
        }

    } else {
        $success = 0;
    }
} else {
    $success = 0;
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>FSR Auth</title>
        <!-- Bootstrap -->
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

        <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

        <!-- Custom styles for this template -->
        <link href="css/form.css" rel="stylesheet">

        <link href="css/bg.css" rel="stylesheet">
        <link href="css/signin.css" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <div class="statusBox">
                <div class="ajax_response alert alert-success" id="success_message"  role="alert">
                    Account verified!
                    <div class="text-button">
                        <a href="signin">Sign in.</a>
                    </div>
                </div>
                <div class="ajax_response alert alert-danger" id="error_message"  role="alert">
                    Something went wrong!
                    <div class="text-button">
                        <a href="signin">Go back.</a>
                    </div>
                </div>
            </div>
            <p class="mt-5 mb-3 text-muted text-center">&copy; 2018</p>
        </div>
        <script>
        var success = "<?php echo $success; ?>";
        if(success == 1){
            $("#success_message").show();
        }
        if(success == 0){
            $("#error_message").show();
        }

        </script>
    </body>
</html>
