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
            <form class="form-signin" id="signup-form" method="post">
                <div class="text-center mb-4">
                    <!--<img class="mb-2" src="img/fsr_logo.png" alt="" width="250" height="250">-->
                    <h1 class="h3 text-uppercase text-light font-weight-normal">Free-Space-Ranger</h1>
                    <h1 class="h6 text-uppercase text-primary font-weight-normal">Sign up</h1>
                </div>
                <div class="form-label-group">
                    <input type="email" id="formEmail" class="form-control" name="formEmail" placeholder="Email" autofocus>
                    <label for="formEmail">Email</label>
                </div>
                <div class="form-label-group">
                    <input type="password" id="formPassword" class="form-control" name="formPassword" placeholder="Password">
                    <label for="formPassword">Password</label>
                </div>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    Existing forum users check below!
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="custom-control custom-checkbox legacyBox">
                    <input type="checkbox" class="custom-control-input" id="formLegacyAccount" name="formLegacyAccount" value="1">
                    <label class="custom-control-label text-primary" for="formLegacyAccount">I already got a forum account.</label>
                </div>
                <div class="row">
                    <div class="col-md-auto text-button">
                        <a href="signin">Already a member? Sign in</a>
                    </div>
                    <div class="col-sm">
                        <button class="btn btn-lg btn btn-outline-primary btn-block" type="submit">Sign up</button>
                    </div>
                </div>
            </form>
            <div class="statusBox">
                <div class="ajax_response alert alert-danger alert-dismissible fade show" id="error_message" role="alert">
                </div>
                <div class="ajax_response alert alert-success" id="success_message" role="alert">
                    Success!
                </div>
            </div>
            <script>
                $("#signup-form").submit(function(e) {
                    e.preventDefault();
                    var formEmail = $("#formEmail").val();
                    var formPassword = $("#formPassword").val();
                    var formLegacyAccount = $("#formLegacyAccount").val();

                    if(formEmail == "" || formPassword == "" ) {
                        $("#error_message").show().html("Email & Password are required!<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>");
                    } else {
                        $("#success_message").html("").hide();
                        $.ajax({
                            type: "POST",
                            url: "post-signup-form.php",
                            data: "formEmail="+formEmail+"&formPassword="+formPassword+"&formLegacyAccount="+formLegacyAccount,
                            success: function(data){
                                $('#success_message').fadeIn().html(data);
                                setTimeout(function() {
                                    window.location.replace("https://www.free-space-ranger.org:444/auth/signin");
                                }, 2000 );

                            }
                        });
                    }
                })
            </script>
            <p class="mt-5 mb-3 text-muted text-center">&copy; 2018</p>
        </div>
    </body>
</html>
