<?php
require_once 'config/db.php';
require_once 'classes/authTokenCollection.php';

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

        // prepare statement
        $db->query("SELECT * FROM users WHERE users.id = :id");
        // bind values
        $db->bind(':id', $userid);
        // execute
        $getUser = $db->single();

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
                        <a href="#" class="btn btn-outline-danger">
                            <span class="fa fa-cog"></span>
                        </a>
                        <a href="logout" role="button" class="btn btn-outline-danger">Logout</a>
                    </div>
                </div>
            </div>
        </header>
        <!-- Begin page content -->
        <main role="main" class="container dash-content">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Willkommen im FSR Auth System</h5>
                    <p class="card-text">Wenn du bereits vor dem 1. Mai 2018 bei uns im Forum einen Account erstellt hast, verknüpfe diesen bitte.
                        <br><br>Seit dem 1. Mai 2018 ist eine Registrierung nur noch über das FSR Auth System möglich.
                        <br>Dein Forum Account wurde bei der Registrierung automatisch angelegt und du kannst dich mit der hier hinterlegten Email und Passwort im Forum anmelden.
                        <br><br>Bei Problem bitte bei Nathan Yates melden. <a href="mailto:nathan.yates@free-space-ranger.org">nathan.yates@free-space-ranger.org</a>
                    </p>
                    <a target="_blank" href="https://www.free-space-ranger.org/forum/index.php?app=core&module=global&section=login" class="btn btn-primary">Forum</a>
                </div>
            </div>
            <div class="card-deck mt-3">
                <div class="card" id="legacy_true" style="width: 18rem; border-color: red; display: none;">
                    <img class="card-img-top" src="img/forum.png" alt="Card image cap">
                    <div class="card-body">
                        <h5 class="card-title"><span class="fa fa-link"></span> Forum Account</h5>
                        <span class="text-danger">Account nicht verknüpft!</span>
                        <p class="card-text">Bitte verknüpfe deinen Foren Account mit deinem Auth Account.</p>
                        <a href="#" class="btn btn-primary"><span class="fa fa-link"></span> Account verknüpfen</a>
                    </div>
                </div>

                <div class="card" id="legacy_false" style="width: 18rem; border-color: green; display: none;">
                    <img class="card-img-top" src="img/forum.png" alt="Card image cap">
                    <div class="card-body">
                        <h5 class="card-title"><span class="fa fa-link"></span> Forum Account</h5>
                        <span class="text-success">Account verknüpft!</span>
                        <p class="card-text">Deinen Foren Account ist mit deinem Auth Account verknüpft.</p>
                    </div>
                </div>
                <script>
                    var legacy = "<?php echo $getUser['legacy']; ?>";
                    if(legacy == 1){
                        $("#legacy_true").show();
                    }
                    if(legacy == 0){
                        $("#legacy_false").show();
                    }
                </script>


                <div class="card" style="width: 18rem;">
                    <img class="card-img-top" src="img/eve.jpg" alt="Card image cap">
                    <div class="card-body">
                        <h5 class="card-title">EVE API</h5>
                        <p class="card-text">Notwendig für alle EVE Spieler.</p>
                        <p class="card-text">Verwaltung der Api Schlüssel. Bitte alle FSR Charakter hinterlegen.</p>
                        <a href="eve-api" class="btn btn-primary"><span class="fa fa-link"></span> API Management</a>
                    </div>
                </div>

                <div class="card" style="width: 18rem;">
                    <img class="card-img-top" src="img/discord.svg" alt="Card image cap">
                    <div class="card-body">
                        <h5 class="card-title">Discord</h5>
                        <p class="card-text">soon</p>
                        <a target="_blank" href="https://discord.gg/qte4x2B" class="btn btn-primary">Open Discord</a>
                    </div>
                </div>
            </div>
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
