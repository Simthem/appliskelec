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
$user->password = base64_encode($_POST['pass1']);
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
        header("refresh:2; url=../../signin.php");
        exit;
    }
}
if(empty($_POST['username1'])) {
    echo "Username is required";
    header("refresh:2; url=../../signin.php");
    exit;
} elseif(empty($pass1) or empty($pass2)) {
    echo "Password(s) is required!";
    header("refresh:2; url=../../signin.php");
    exit;
} elseif($pass1 != $pass2) {
    echo "Passwords should be the same";
    header("refresh:2; url=../../signin.php");
    exit;
} else {
    echo "Success !! Just log in now ! :)";
    $user->signup();
    header("refresh:2; url=../../signin.php");
    exit;
}
?>