<?php
session_start();

// get database connection
include_once '../config/database.php';
 
// instantiate user object
include_once '../objects/user.php';
 
$database = new Database();
$db = $database->getConnection();
 
$user = new User($db);
 
// set user property values
$user->first_name = $_POST['first_name'];
$user->last_name = $_POST['last_name'];
$user->e_mail = $_POST['e_mail'];
if (preg_match('/^[+0-9]{10,12}$/m', $_POST['phone']) && isset($_POST['phone']) && !empty($_POST['phone'])) {
    $user->phone = $_POST['phone'];
} else {
    $user->phone = NULL;
}
$user->password = md5($_POST['pass1']);
$user->username = $_POST['username1'];
$user->created = date('Y-m-d H:i:s');
$pass1 = $_POST['pass1'];
$pass2 = $_POST['pass2'];

// create the user
$reponse = $db->query('SELECT username FROM users WHERE username = "' . $_POST['username1'] . '" ');
$username = $reponse->fetch();
if($username) {
    if (strtolower($_POST['username1']) == strtolower($username['username'])) {
        echo "Username already exists";
        header("refresh:2; url=../../add_profil.php");
        exit();
    }
}
if(empty($_POST['first_name'])) {
    echo "First_name is required";
    header("refresh:2; url=../../add_profil.php");
    exit();
} elseif(empty($_POST['username1'])) {
    echo "Username is required";
    header("refresh:2; url=../../add_profil.php");
    exit();
} elseif(empty($pass1) or empty($pass2)) {
    echo "Password(s) is required!";
    header("refresh:2; url=../../add_profil.php");
    exit();
} elseif($pass1 != $pass2) {
    echo "Passwords should be the same";
    header("refresh:2; url=../../add_profil.php");
    exit();
} else {
    echo "Success !! :)";
    $user->signup();
    header("refresh:2; url=../../list_profil.php");
    exit();
}
?>