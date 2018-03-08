<?php

require_once 'config/db.php';

$email = $password = $legacyAccount = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    


    // Validate password
    if(empty(trim($_POST['password']))){
        $password_err = "Please enter a password.";
    } elseif(strlen(trim($_POST['password'])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST['password']);
    }
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


        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>


        <!-- Custom styles for this template -->
        <link href="css/form.css" rel="stylesheet">

        <link href="css/bg.css" rel="stylesheet">
        <link href="css/signin.css" rel="stylesheet">
    </head>
    <body>
        <div class="container">

                    <form class="form-signin">
                        <div class="text-center mb-4">
                            <!--<img class="mb-2" src="img/fsr_logo.png" alt="" width="250" height="250">-->
                            <h1 class="h3 text-uppercase text-light font-weight-normal">Free-Space-Ranger</h1>
                            <h1 class="h6 text-uppercase text-primary font-weight-normal">Sign up</h1>
                        </div>
                        <div class="form-label-group">
                            <input type="email" id="inputEmail" class="form-control" placeholder="Email address" value="<?php echo $email; ?>" required autofocus>
                            <label for="inputEmail">Email</label>
                        </div>
                        <div class="form-label-group">
                            <input type="password" id="inputPassword" class="form-control" placeholder="Password" value="<?php echo $password; ?>" required>
                            <label for="inputPassword">Password</label>
                        </div>
                        <div class="alert alert-danger" role="alert">
                            Existing forum users check below!
                        </div>
                        <div class="custom-control custom-checkbox legacyBox">
                            <input type="checkbox" class="custom-control-input" id="legacyAccount" value="<?php echo $legacyAccount; ?>">
                            <label class="custom-control-label text-primary" for="legacyAccount">I already got a forum account.</label>
                        </div>
                            <div class="row">
                                <div class="col-md-auto text-button">
                                    <a href="signin">Already a member? Sign in</a>
                                </div>
                                <div class="col-sm">
                                    <button class="btn btn-lg btn btn-outline-primary btn-block" type="submit">Sign up</button>
                                </div>
                            </div>


                        <p class="mt-5 mb-3 text-muted text-center">&copy; 2018</p>
                    </form>

        </div>
    </body>
</html>
