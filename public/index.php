<?php

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

        <!-- Custom styles for this template -->
        <link href="css/floating-labels.css" rel="stylesheet">

        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

        <link href="css/bg.css" rel="stylesheet">
        <link href="css/footer.css" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <ul class="nav nav-pills" role="tablist">
                <li class="active"><a data-toggle="tab" href="#login">Home</a></li>
                <li><a data-toggle="tab" href="#signup">Menu 1</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active in" id="login">
                    <form class="form-signin">
                        <div class="text-center mb-4">
                            <img class="mb-2" src="img/fsr_logo.png" alt="" width="250" height="250">
                            <h1 class="h3 mb-3 font-weight-normal">Free-Space-Ranger</h1>
                        </div>
                        <div class="form-label-group">
                            <input type="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
                            <label for="inputEmail">Email Adresse</label>
                        </div>
                        <div class="form-label-group">
                            <input type="password" id="inputPassword" class="form-control" placeholder="Password" required>
                            <label for="inputPassword">Passwort</label>
                        </div>
                        <button class="btn btn-lg btn-primary btn-block" type="submit">Einloggen</button>
                        <ul class="nav nav-pills">
                            <a data-toggle="tab" href="#signup" class="btn btn-lg btn-secondary btn-block" role="link">Registrieren</a>
                        </ul>
                        <p class="mt-5 mb-3 text-muted text-center">&copy; 2018</p>
                    </form>
                </div>
                <div class="tab-pane fade in" id="signup">
                    Signup
                    <ul class="nav nav-pills">
                        <a data-toggle="tab" href="#login" class="btn btn-lg btn-secondary btn-block" role="link">back</a>
                    </ul>
                </div>
            </div>
        </div>
        <footer class="footer">
            <div class="container">
                <span class="text-dark">test</span>
            </div>
        </footer>
    </body>
</html>
