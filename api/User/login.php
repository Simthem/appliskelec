<?php
session_start();
// include database and object files
include_once '../config/database.php';
include '../config/db_connexion.php';
include_once '../objects/user.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare user object

$user = new User($db);

// set ID property of user to be edited
$user->username = isset($_POST['username']) ? $_POST['username'] : die();
$user->password = md5(isset($_POST['password']) ? $_POST['password'] : die());

$stmt = $user->login();
//verify admin
$reponse = $db->query('SELECT admin_name FROM `admin` WHERE admin_name = "' . $_POST['username'] . '" ');
$admin = $reponse->fetch();

if ($admin) {
    if ($_POST['username'] == $admin['admin_name']) {
        $_SESSION['username'] = "admin";
        $_SESSION['admin_name'] = $_POST['username'];
    }
}


$pdo_user = $bdd->prepare("SELECT id, `password`, username FROM users WHERE username = '". $_SESSION['username'] ."'");
$pdo_user->execute();
$auth = $pdo_user->fetch();

if($user) {
    $_SESSION['username'] = $_POST['username'];//here session is used and value of 'username' store in $_SESSION.
}

//echo $_COOKIE['id'] . "<br />";

if ($pdo_user->rowCount() > 0){

    if($auth) {
        $value = $auth['id'].'---'.hash('sha512', $auth['username'].'---'.$auth['password']);
        setcookie('id', $value, time() + (7 * 24 * 3600) , null, null, false, true);
    } else {
        echo "ERROR: Username non reconnu ..";
    }

    // read the details of user to be edited  
    if ($stmt->rowCount() > 0) {
        header("Location:../../index.php");
        exit();
    } else {
        echo "Username and/or Password incorrect.\\nTry again.";
        header("refresh:2; url=../../signin.php");
        exit();
    }

} elseif (!($pdo_user->rowCount() > 0)){

    $ad = new Admin($db);

    $reponse = $bdd->prepare('SELECT admin_name FROM `admin` WHERE admin_name = "' . $_POST['username'] . '" ');
    $reponse->execute();
    $ad_pse = $reponse->fetch();

    if ($ad_pse) {
        if ($_POST['username'] == $admin['admin_name']) {
            $_SESSION['username'] = "admin";
            $_SESSION['admin_name'] = $_POST['username'];
        }
    }

    $stmt_admin = $bdd->prepare("SELECT id, admin_name, admin_pass FROM `admin` WHERE admin_name = '". $_SESSION['admin_name'] ."'");
    $stmt_admin->execute();
    $admin = $stmt_admin->fetch();

    if ($admin) {
        $value = $admin['id'].'---'.hash('sha512', $admin['admin_name'].'---'.$admin['admin_pass']);
        setcookie('auth', $value, time() + (7 * 24 * 3600) , null, null, false, true);
    } else {
        echo "ERROR: Username non reconnu ..";
    }

    
    // set ID property of admin to be edited
    $ad->admin_name = isset($_POST['username']) ? $_POST['username'] : die();
    $ad->admin_pass = md5(isset($_POST['password']) ? $_POST['password'] : die());
    
    // read the details of admin to be edited
    $sql = $ad->login_ad();
    
    if ($sql->rowCount() > 0) {
        header("Location:../../index.php");
        exit();
    } else {
        echo "Username and/or Password incorrect.\\nTry again.";
        header("refresh:2; url=../../signin.php");
        exit();
    }
} else {
    echo "Username and/or Password incorrect.\\nTry again.";
    header("refresh:2; url=../../signin.php");
    exit();
}

?>