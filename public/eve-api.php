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

        // ### process returned api from ccp
        if(isset($_GET['code']) && $_GET['code'] !== ''){
            $tokens = json_decode(authInit($_GET['code'],$client_id,$client_secret), TRUE);
            $char = json_decode(getCharId($tokens['access_token']), TRUE);

            storeApi($char['CharacterID'], $char['CharacterName'], $char['TokenType'], $tokens['access_token'], $tokens['refresh_token'], $userid);
        }

        // ### set primary char
        if(isset($_GET['charid']) && $_GET['charid'] !== ''){

            setPrimaryChar($_GET['charid'],$userid);

        }


        // prepare statement
        $db->query("SELECT * FROM users WHERE users.id = :id");
        // bind values
        $db->bind(':id', $userid);
        // execute
        $getUser = $db->single();

        // get api
        $db->query("SELECT * FROM api_tokens WHERE api_tokens.authUserId = :id");
        $db->bind(':id', $userid);
        $apis = $db->resultset();

        $api_count = $db->rowCount();

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
        <link href="css/dashboard.css" rel="stylesheet">
        <link href="css/header.css" rel="stylesheet">
        <link href="css/footer.css" rel="stylesheet">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

        <script>
            window.history.replaceState({}, document.title, "/auth/eve-api");
        </script>

    </head>
    <body>
        <header class="header">
            <div class="container">
                <div class="row">
                    <div class="col-sm">
                        <span class="text-uppercase font-weight-bold text-danger">FSR Auth</span>
                    </div>
                    <div class="col-">
                    </div>
                    <div class="col-md text-right">
                        <span class="text-muted m-3"><?php echo $getUser['email']; ?></span>
                        <a href="account" class="btn btn-outline-danger">
                            <span class="fa fa-cog"></span>
                        </a>
                        <a href="logout" role="button" class="btn btn-outline-danger">Logout</a>
                    </div>
                </div>
            </div>
        </header>
        <!-- Begin page content -->
        <main role="main" class="container dash-content">
            <table class="table table-striped table-dark">
                <thead>
                    <tr>
                        <th scope="col">Character</th>
                        <th scope="col">CharacterID</th>
                        <th scope="col">Type</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    for ($i = 0; $i < $api_count; $i++) {
                        echo '<tr><th scope="row">'. $apis[$i][characterName] .'</th><td>'. $apis[$i][characterID] .'</td><td>'. $apis[$i][tokenType] .'</td><td class="text-right">';
                        if ($apis[$i][primary] == 0) { echo '<a href="?charid='. $apis[$i][characterID] .'" class="btn btn-outline-danger mr-2" role="button">Primary</a>'; }
                        if ($apis[$i][primary] == 1) { echo '<a href="" class="btn btn-outline-danger active mr-2" role="button">Primary</a>'; }
                        echo '<a href="" class="btn btn-outline-danger"><span class="fa fa-trash"></span></a></td>';
                    }
                ?>
                </tbody>
            </table>
            <a href="<?php echo $apiAuthUrl ?>" role="button" class="btn btn-outline-danger">API hinzufügen</a>
        </main>
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <span class="text-muted">&copy; 2018 Free-Space-Ranger</span>
                    </div>
                    <div class="col-sm-8 text-right">
                        <a class="text-danger m-1" target="_blank" href="https://www.free-space-ranger.org/forum/">Forum</a>
                        <a class="text-danger m-1" target="_blank" href="https://discord.gg/qte4x2B">Discord</a>
                        <a class="text-danger m-1" href="ts3server://85.214.142.178?port=9987">Teamspeak</a>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>
