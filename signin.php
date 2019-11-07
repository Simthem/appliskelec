<?php
include './api/config/database.php';

if($_POST['username'] and $_POST['password']) {
  $_SESSION['username'] = $_POST['username'];
}

if (isset($_COOKIE['id'])) {
  setcookie('id', '', time()-7000000, '/');
} elseif (isset($_COOKIE['auth'])) {
  setcookie('auth', '', time()-7000000, '/');
}
?>

<!DOCTYPE html>

<html>
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

    <body class="card signin-card">
      <header class=" bg-dark">
        <div class="icons-navbar text-center text-white">
          <h2 class="m-0">S.K.elec</h2>
        </div>
      </header>

      <section class="form-elegant mt-auto mb-auto">
        <div class="card-body">
          <div class="tab-content">
            <div class="tab-pane active" id="tab1">
              <form class="w-100 h-75 ml-auto mb-auto" action="./api/user/login.php" method="POST">
                <div class="md-form">
                    <label for="user" class="mb-3 mt-3">Username</label>
                    <input type="text" id="username" name="username" class="form-control mb-3 mt-3" required>
                </div>

                <div class="md-form">
                    <label for="password" class="mb-3 mt-4">Password</label>
                    <input type="password" id="password" name="password" class="form-control mb-3 mt-3" data-type="password" required>
                    <p class="font-small blue-text d-flex justify-content-end">Forgot <a href="#" class="blue-text ml-1"> Password?</a></p>
                </div>

                <div class="text-center mt-5 mb-4 pt-3">
                  <input type="submit" href="index.php" value="Sign In" class="btn send border-0 bg-white z-depth-1a pl-5 pr-5 text-dark">
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
