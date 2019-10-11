<?php
session_start();

include_once '../config/database.php';
//if($_POST['username'] and $_POST['password']) {
//  $_SESSION = $_POST['username'];
//}
?>

<!DOCTYPE html>

<html class="signin-card">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <link  rel="stylesheet" href="assets/css/bootstrap.css">
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    </head>

    <body class="card h-75">
      <header class=" bg-dark">
          <div class="icons-navbar text-center text-white">
              <h2 class="m-0">S.K.elec</h2>
          </div>
      </header>

      <section class="form-elegant">
        <div class="card-body">
          <ul class="nav nav-pills">
            <li class="active h-50"><a href="#tab1" data-toggle="tab" class="h-75">Sign In</a></li>
            <li class="h-50"><a href="#tab2" data-toggle="tab" class="h-75">Sign Up</a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab1">
              <form class="w-100 h-75 pt-1 mt-0 mr-2 mb-0 ml-2" action="./api/user/login.php" method="POST">
                  <div class="md-form pt-3 pb-3">
                      <label for="user">Your email</label>
                      <input type="text" id="username" name="username" class="form-control">
                  </div>

                  <div class="md-form pt-3">
                      <label for="password">Your password</label>
                      <input type="password" id="password" name="password" class="form-control" data-type="password">
                      <p class="font-small blue-text d-flex justify-content-end">Forgot <a href="#" class="blue-text ml-1"> Password?</a></p>
                  </div>

                  <div class="text-center mt-5 mb-4">
                    <input type="submit" href="index.php" value="Sign In" class="btn send border-0 bg-white z-depth-1a pl-5 pr-5 text-dark">
                  </div>
              </form>
            </div>
            <div class="tab-pane" id="tab2">
              <form id="signup" class="w-100 h-75 pt-1 mt-0 mr-2 mb-0 ml-2" action="./api/user/signup.php" method="POST">
                <div class="md-form pt-0 pb-3">
                  <label for="username1">Username</label>
                  <input id="username1" name="username1" type="text" class="form-control" required>
                </div>
                <div class="md-form pt-0">
                  <label for="pass1">Password</label>
                  <input id="pass1" name="pass1" type="password" class="form-control" data-type="password" required>
                </div>
                <div class="md-form pt-0">
                  <label for="pass2">Confirm Password</label>
                  <input id="pass2" name="pass2" type="password" class="form-control" data-type="password" required>
                </div>
                <div class="text-center mt-3 mb-4">
                  <input type="submit" class="btn send border-0 bg-white z-depth-1a pl-5 pr-5 text-dark" value="Sign Up">
                </div>
              </form>
            </div>
          </div>
        </div>
        

        <footer>
        </footer>
      </section>
    </body>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="js/bootstrap.js"></script>
</html>
