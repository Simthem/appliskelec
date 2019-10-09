<?php
 
// get database connection
include_once '../config/database.php';
 
// instantiate user object
include_once '../objects/user.php';

function validate(){
    if(true){
        return true;
    }
    else {
        alert('error');
        echo "Tous les champs ne sont pas/ ou mal remplis .. Veuillez réssayer en vérifiant bien que ce soit le cas :)";
        reset($POST['signup']);
        header("refresh:2; url=../../signin.php");
        exit;
        return false;
    }
}
 
$database = new Database();
$db = $database->getConnection();
 
$user = new User($db);
 
// set user property values
$user->username = $_POST['username'];
$user->password = base64_encode($_POST['pass1']);
$user->created = date('Y-m-d');
 
// create the user
if($user->signup() and !empty($_POST['pass1']) and $POST['pass'] == $POST['pass1']){
    /*$user_arr=array(
        "status" => true,
        "message" => "Successfully Signup!",
        "id" => $user->id,
        "username" => $user->username
    );*/
    echo "Successfully Sign Up !!";
    header("refresh:2; url=../../signin.php");
    exit;
}
elseif(empty($_POST['pass1']) or $POST['pass'] != $POST['pass1']){
    echo "Tous les champs ne sont pas/ ou mal remplis .. Veuillez réssayer en vérifiant bien que ce soit le cas :)";
    reset($POST['signup']);
    header("refresh:2; url=../../signin.php");
    exit;
}
else{
    /*$user_arr=array(
        "status" => false,
        "message" => "Username already exists!"
    );*/
    echo " Username already exists !";
    header("refresh:2; url=../../signin.php");
    exit;
}

print_r(json_encode($user_arr));
?>