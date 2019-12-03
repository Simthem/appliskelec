<?php
session_start();
//print_r(session_get_cookie_params());

include 'api/config/db_connexion.php';

if (isset($_COOKIE['id'])) {

    $auth = explode('---', $_COOKIE['id']);

    if (count($auth) === 2) {
        $req = $bdd->prepare('SELECT id, username, `password` FROM users WHERE id = :id');
        $req->execute([ ':id' => $auth[0] ]);
        $user = $req->fetch(PDO::FETCH_ASSOC);
         
        if ($user && $auth[1] === hash('sha512', $user['username'].'---'.$user['password'])) {
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['admin_name'] = null;
            $admin['id'] = null;
        } else {
            header("Location: signin.php");//redirect to login page to secure the welcome page without login access.  
        }
    }
} elseif (isset($_COOKIE['auth'])) {

    $auth = explode('---', $_COOKIE['auth']);
 
    if (count($auth) === 2) {
        $req = $bdd->prepare('SELECT id, admin_name, admin_pass FROM `admin` WHERE id = :id');
        $req->execute([ ':id' => $auth[0] ]);
        $admin = $req->fetch(PDO::FETCH_ASSOC);
         
        if ($admin && $auth[1] === hash('sha512', $admin['admin_name'].'---'.$admin['admin_pass'])) {
            $_SESSION['id'] = $admin['id'];
            $_SESSION['username'] = "admin";
            $_SESSION['admin_name'] = $admin['admin_name'];
        } else {
            header("Location: signin.php"); 
        }
    }
}

if(!($_SESSION['username'])){
  
    header("Location: signin.php");
}

$stmt = $bdd->prepare("SELECT id FROM users WHERE username = '". $_SESSION['username'] ."'");
$stmt->execute();
$user = $stmt->fetch();

if (isset($_SESSION['admin_name']) && !empty($_SESSION['admin_name'])) {

    $stmt_admin = $bdd->prepare("SELECT id FROM `admin` WHERE admin_name = '". $_SESSION['admin_name'] ."'");
    $stmt_admin->execute();
    $admin = $stmt_admin->fetch();
}

if($user) {
    $_SESSION['id'] = $user['id'];
} elseif (isset($admin) && !empty($admin)) {
    $_SESSION['id'] = $admin['id'];
} else {
    header("Location: signin.php");
}
?>

<!DOCTYPE html>

<html class="overflow-y mb-0">
    
    <?php include 'header.php'; ?>

                <div class="icons-navbar">
                    <div class="menu-btn-bars text-white"><button class="menu-btn fas fa-bars text-warning w-100 fa-3x p-0"></button></div>
                    <a href="index.php" class="text-warning m-auto"><h2 class="m-0">S.K.elec</h2></a>
                    <a href="list_profil.php" class="text-white pl-3"><i class="menu-btn-plus fas fa-search text-warning fa-3x rounded-circle"></i></a>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div id="container">
            <div class="content">
                <h3 class="text-center mt-0 mb-3 pt-5">Création d'un compte</h3>
                <form id="add_u" class="w-100 pt-3 pl-4 pb-0 pr-4" action="./api/user/signup.php" method="POST">
                    <div class="md-form mt-1">
                        <label for="username1">Username *</label>
                        <input id="username1" name="username1" type="text" class="form-control" required>
                    </div>
                    <div class="md-form mt-4">
                        <label for="first-name">First name *</label>
                        <input type="text" id="first_name" name="first_name" class="form-control" required>
                    </div>
                    <div class="md-form mt-4">
                        <label for="last_name">Last name</label>
                        <input type="text" id="last-name" name="last_name" class="form-control">
                    </div>
                    <div class="md-form mt-4">
                        <label for="e_mail">Email</label>
                        <input type="email" id="e_mail" name="e_mail" class="form-control">
                    </div>
                    <div class="md-form mt-4">
                        <label for="phone">Phone *</label>
                        <input type="phone" id="phone" name="phone" class="form-control">
                    </div>
                    <div class="md-form mt-4">
                        <label for="pass1">Password *</label>
                        <input type="password" id="pass1" name="pass1" class="form-control" required>
                    </div>
                    <div class="md-form mt-4">
                        <label for="pass2">Confirm password *</label>
                        <input type="password" id="pass2" name="pass2" class="form-control" required>
                    </div>
                    <div class="pt-5 w-75 m-auto">
                        <input type="" value="Créer" class="btn send border-0 bg-white z-depth-1a mt-3 mb-4 text-dark" onClick="checkFUser()">
                        <a href="list_profil.php" value="return" class="btn finish border-0 bg-white z-depth-1a mt-1 mb-4 text-dark">Précédent</a>
                    </div>
                </form>
            </div>
        </div>

    <?php include 'footer.php'; ?>