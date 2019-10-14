<?php
session_start();

include_once '../config/database.php';
include_once '../objects/user.php';
include '../config/db_connexion.php';

if(!($_SESSION['username'])) {  
  
    header('Location: ../../signin.php');//redirect to login page to secure the welcome page without login access.  
}

if($bdd === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
/*
$stmt = $bdd->prepare("SELECT * FROM users WHERE id=?");
$stmt->execute([$id]); 
$user = $stmt->fetch();
if($user) {
    $_SESSION['id'] = $user['id'];
    echo $_SESSION['id'];
    exit();
} else {
    echo "ERROR: Could not get 'id' of current user [first_method]";
}*/

/*
$requser = $bdd->prepare('SELECT * FROM users WHERE username = "' . $_SESSION['username'] . '"');
$requser->execute($_SESSION['id'] = $id);
$_SESSION = $requser->fetch();
if($_SESSION) {
    $_SESSION['id'] = $_SESSION['id'];
    echo $_SESSION['id'];
    exit();
} else {
    echo "ERROR: Could not get 'id' of current user";
}*/
//print_r($_SESSION);
/*
if ($bdd->connect_errno) {
    die('Erreur de connexion : ' .$bdd->connect_errno);
  
   Définition de la requête 
} elseif($bdd){
    $query  = "
    UPDATE
        users
    SET
        username = '".$_GET['username1']."',
        first_name = '".$_GET['first_name']."',
        last_name = '".$_GET['last_name']."',
        e_mail = '".$_GET['e_mail']."',
        phone = '".$_GET['phone']."',
        password = '".$_GET['pass1']."'
    WHERE
        username = '".$_SESSION['username']."'";

     Envoie de la requête 
    $result = $bdd->query($query);

     Affichage du nombre de lignes affectées par la requête 
    echo "Nombre de lignes affectées (UPDATE): " . $bdd->affected_rows;

    Affichage de la requête (comme ça tu vois ce que contiennent tes variables $_GET) 
    echo '<pre>' . $query . '</pre>';
} else {
    echo "ERREUR, so sorry .. :/";
    header("refresh:2; url=../../list_profil.php");
}*/

if(isset($_SESSION['username'])) {
    if(isset($_GET['username']) AND !empty($_GET['username']) AND $_GET['username'] != $_SESSION['username']) {
        $newusername = htmlspecialchars($_GET['username']);
        $insertusername = $bdd->prepare("UPDATE users SET username = '". $newusername ."' WHERE id = '". $_SESSION['id'] ."'");
        $username = $_GET['username'];
        $insertusername->execute(array($newusername, $_SESSION['username']));
        //header("Location: ../../list_profil.php");
    }
    if(isset($_GET['first_name']) AND !empty($_GET['first_name']) AND $_GET['first_name'] != $_SESSION['first_name']) {
        $newfirst_name = htmlspecialchars($_GET['first_name']);
        $insertfirst_name = $bdd->prepare("UPDATE users SET first_name = '". $newfirst_name ."' WHERE id = '". $_SESSION['id'] ."'");
        $insertfirst_name->execute(array($newfirst_name, $_SESSION['first_name']));
        //header('Location: ../../list_profil.php');
    }
    if(isset($_GET['last_name']) AND !empty($_GET['last_name']) AND $_GET['last_name'] != $_SESSION['last_name']) {
        $newlast_name = htmlspecialchars($_GET['last_name']);
        $insertlast_name = $bdd->prepare("UPDATE users SET last_name = '". $newlast_name ."' WHERE id = '". $_SESSION['id'] ."'");
        $insertlast_name->execute(array($newlast_name, $_SESSION['last_name']));
        //header('Location: ../../list_profil.php');
    }
    if(isset($_GET['e_mail']) AND !empty($_GET['e_mail']) AND $_GET['e_mail'] != $_SESSION['e_mail']) {
        $newe_mail = htmlspecialchars($_GET['e_mail']);
        $inserte_mail = $bdd->prepare("UPDATE users SET e_mail = '". $newe_mail ."' WHERE id = '". $_SESSION['id'] ."'");
        $inserte_mail->execute(array($newe_mail, $_SESSION['e_mail']));
        //header('Location: ../../list_profil.php');
    }
    if(isset($_GET['phone']) AND !empty($_GET['phone']) AND $_GET['phone'] != $_SESSION['phone']) {
        $newphone = htmlspecialchars($_GET['phone']);
        $insertphone = $bdd->prepare("UPDATE users SET phone = '". $newphone ."' WHERE id = '". $_SESSION['id'] ."'");
        $insertphone->execute(array($newphone, $_SESSION['phone']));
        //header('Location: ../../list_profil.php');
    }
    if(isset($_GET['pass1']) AND !empty($_GET['pass1']) AND isset($_GET['pass2']) AND !empty($_GET['pass2'])) {
        $pass1 = md5($_GET['pass1']);
        $pass2 = md5($_GET['pass2']);
        if($pass1 == $pass2) {
            $insertpassword = $bdd->prepare("UPDATE users SET password = '". $pass1 ."' WHERE id = '". $_SESSION['id'] ."'");
            $insertpassword->execute(array($pass1, $_SESSION['password']));
            //header('Location: ../../list_profil.php');
            //print_r($_SESSION);
            // get database connection
            //$database = new Database();
            //$bdd = $database->getConnection();
            //print_r($_SESSION); 
            // prepare user object
            $user = new User($bdd);
            // set ID property of user to be edited
            $user->username = isset($username) ? $_SESSION['username'] : die();
            $user->password = md5(isset($pass1) ? $_SESSION['password'] : die());
            //print_r($_SESSION);
            // read the details of user to be edited  
            $stmt = $user->login();
            print_r($stmt);
            print_r($_SESSION);
            if($stmt->rowCount() > 0){
                $_SESSION['username'] = $username;//here session is used and value of 'username' store in $_SESSION.
                echo $_SESSION['username'];
                header("Location:../../index.php");
                exit();
            } else {
                echo "Username and/or Password incorrect.\\nTry again.";
                //header("refresh:2; url=../../signin.php");
                exit();
            }
        } else {
            $msg = "Vos deux mdp ne correspondent pas !";
        }
    }
}
else {
    header('Location: ../../signin.php');
}
/*
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare user object
$user = new User($db);
// set ID property of user to be edited
$user->username = isset($_GET['username']) ? $_GET['username'] : die();
$user->password = md5(isset($_GET['password']) ? $_GET['password'] : die());
// read the details of user to be edited  
$stmt = $user->login();
if($stmt->rowCount() > 0){
    $_SESSION['username']= $_GET['username'];//here session is used and value of 'username' store in $_SESSION.
    //echo $_SESSION['username'];
    header("Location:../../index.php");
    exit();
}
else{
    echo "Username and/or Password incorrect.\\nTry again.";
    header("refresh:2; url=../../signin.php");
    exit();
}
*/
?>