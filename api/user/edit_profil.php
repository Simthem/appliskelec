<?php
session_start();

//include_once '../config/database.php';
include_once '../objects/user.php';
include_once '../config/db_connexion.php';

if(!($_SESSION['username'])) {  
  
    header('Location: ../../signin.php');//redirect to login page to secure the welcome page without login access.  
}

if($bdd === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

print_r($_SESSION);
if($_SESSION) {
    /*if(isset($_POST['username']) AND !empty($_POST['username']) AND $_POST['username'] != $user['username']) {
        echo "Vous n'avez pas les droits pour changer votre username";
        header("refresh:2; url= ../../modif_profil.php");
        exit();
    } else {*/
        if(/*isset($_POST['first_name']) AND*/ !empty($_POST['username']) AND $_POST['username'] != $user['username']) {
            $newusername = htmlspecialchars($_POST['username']);
            $insertusername = $bdd->prepare("UPDATE users SET username = '". $newusername ."' WHERE id = '". $_SESSION['id'] ."'");
            $insertusername->execute(array($newusername, $_SESSION['username']));
            echo "first";
            header('refresh:5; url=../../list_profil.php');
        }
        if(/*isset($_POST['first_name']) AND*/ !empty($_POST['first_name']) AND $_POST['first_name'] != $user['first_name']) {
            $newfirst_name = htmlspecialchars($_POST['first_name']);
            $insertfirst_name = $bdd->prepare("UPDATE users SET first_name = '". $newfirst_name ."' WHERE id = '". $_SESSION['id'] ."'");
            $insertfirst_name->execute(array($newfirst_name, $_SESSION['first_name']));
            echo "second";
            header('refresh:5; url=../../list_profil.php');
        }
        if(/*isset($_POST['last_name']) AND*/ !empty($_POST['last_name']) AND $_POST['last_name'] != $user['last_name']) {
            $newlast_name = htmlspecialchars($_POST['last_name']);
            $insertlast_name = $bdd->prepare("UPDATE users SET last_name = '". $newlast_name ."' WHERE id = '". $_SESSION['id'] ."'");
            $insertlast_name->execute(array($newlast_name, $_SESSION['last_name']));
            echo "three";
            header('refresh:5; url=../../list_profil.php');
        }
        if(/*isset($_POST['e_mail']) AND*/ !empty($_POST['e_mail']) AND $_POST['e_mail'] != $user['e_mail']) {
            $newe_mail = htmlspecialchars($_POST['e_mail']);
            $inserte_mail = $bdd->prepare("UPDATE users SET e_mail = '". $newe_mail ."' WHERE id = '". $_SESSION['id'] ."'");
            $inserte_mail->execute(array($newe_mail, $_SESSION['e_mail']));
            echo "four";
            header('refresh:5; url=../../list_profil.php');
        }
        if(/*isset($_POST['phone']) AND*/ !empty($_POST['phone']) AND $_POST['phone'] != $user['phone']) {
            $newphone = htmlspecialchars($_POST['phone']);
            $insertphone = $bdd->prepare("UPDATE users SET phone = '". $newphone ."' WHERE id = '". $_SESSION['id'] ."'");
            $insertphone->execute(array($newphone, $_SESSION['phone']));
            echo "five";
            header('refresh:5; url=../../list_profil.php');
        }
        if(/*isset($_POST['phone']) AND*/ !empty($_POST['total_hours']) AND $_POST['total_hours'] != $user['total_hours']) {
            $newtotal = htmlspecialchars($_POST['total_hours']);
            $inserttotal = $bdd->prepare("UPDATE users SET total_hours = '". $newtotal ."' WHERE id = '". $_SESSION['id'] ."'");
            $inserttotal->execute(array($newtotal, $_SESSION['total_hours']));
            echo "five";
            header('refresh:5; url=../../list_profil.php');
        }
        if(/*isset($_POST['pass1']) AND*/ !empty($_POST['pass1']) AND /*isset($_POST['pass2']) AND */!empty($_POST['pass2'])) {
            $pass1 = md5($_POST['pass1']);
            $pass2 = md5($_POST['pass2']);
            if($pass1 == $pass2 and $pass1 != md5($user['password'])) {
                $insertpassword = $bdd->prepare("UPDATE users SET password = '". $pass1 ."' WHERE id = '". $_SESSION['id'] ."'");
                $insertpassword->execute(array($pass1, $_SESSION['password']));
                echo "six";
                header('Location: ../../index.php');
            } elseif ($pass1 == $pass2 and $pass1 == md5($user['password'])) {
                print_r($user);
                echo "Success !! :)";
                //header('refresh:5; url=../../index.php');
            }
            else {
                echo "Vos deux mdp ne correspondent pas !";
                //header("refresh:5; url= ../../modif_profil.php");
            }
        }
    }
//}
else {
    print_r($user);
    //header('refresh:5; url=../../signin.php');
}
?>