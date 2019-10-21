<?php
session_start();
// include database and object files
include_once '../config/database.php';
include_once '../objects/user.php';

// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare user object
$user = new User($db);
// set ID property of user to be edited
$user->username = isset($_POST['username']) ? $_POST['username'] : die();
$user->password = md5(isset($_POST['password']) ? $_POST['password'] : die());
// read the details of user to be edited  
$stmt = $user->login();
$reponse = $db->query('SELECT admin_name FROM admin WHERE admin_name = "' . $_POST['username'] . '" ');
$admin = $reponse->fetch();
if($admin) {
    if ($_POST['username'] == $admin['admin_name']) {
        $_SESSION['username']= "admin";
        header("Location:../../index.php");
        exit();
    }
}

if($stmt->rowCount() > 0){
    $_SESSION['username']= $_POST['username'];//here session is used and value of 'username' store in $_SESSION.
    echo $_SESSION['username'];
    header("Location:../../index.php");
    exit();
}
else{
    echo "Username and/or Password incorrect.\\nTry again.";
    header("refresh:2; url=../../signin.php");
    exit();
}

?>