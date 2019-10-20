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

if(isset($user['username'])) {
    if(isset($_POST['username']) AND !empty($_POST['username']) AND $_POST['username'] != $user['username']) {
        echo "Vous n'avez pas les droits pour changer votre username";
        header("refresh:2; url= ../../modif_profil.php");
        exit();
    } else {
        if(isset($_POST['first_name']) AND !empty($_POST['first_name']) AND $_POST['first_name'] != $user['first_name']) {
            $newfirst_name = htmlspecialchars($_POST['first_name']);
            $insertfirst_name = $bdd->prepare("UPDATE users SET first_name = '". $newfirst_name ."' WHERE id = '". $user['id'] ."'");
            $insertfirst_name->execute(array($newfirst_name, $user['first_name']));
            header('Location: ../../list_profil.php');
        }
        if(isset($_POST['last_name']) AND !empty($_POST['last_name']) AND $_POST['last_name'] != $user['last_name']) {
            $newlast_name = htmlspecialchars($_POST['last_name']);
            $insertlast_name = $bdd->prepare("UPDATE users SET last_name = '". $newlast_name ."' WHERE id = '". $user['id'] ."'");
            $insertlast_name->execute(array($newlast_name, $user['last_name']));
            header('Location: ../../list_profil.php');
        }
        if(isset($_POST['e_mail']) AND !empty($_POST['e_mail']) AND $_POST['e_mail'] != $user['e_mail']) {
            $newe_mail = htmlspecialchars($_POST['e_mail']);
            $inserte_mail = $bdd->prepare("UPDATE users SET e_mail = '". $newe_mail ."' WHERE id = '". $user['id'] ."'");
            $inserte_mail->execute(array($newe_mail, $user['e_mail']));
            header('Location: ../../list_profil.php');
        }
        if(isset($_POST['phone']) AND !empty($_POST['phone']) AND $_POST['phone'] != $user['phone']) {
            $newphone = htmlspecialchars($_POST['phone']);
            $insertphone = $bdd->prepare("UPDATE users SET phone = '". $newphone ."' WHERE id = '". $user['id'] ."'");
            $insertphone->execute(array($newphone, $user['phone']));
            header('Location: ../../list_profil.php');
        }
        if(isset($_POST['pass1']) AND !empty($_POST['pass1']) AND isset($_POST['pass2']) AND !empty($_POST['pass2'])) {
            $pass1 = md5($_POST['pass1']);
            $pass2 = md5($_POST['pass2']);
            if($pass1 == $pass2) {
                $insertpassword = $bdd->prepare("UPDATE users SET password = '". $pass1 ."' WHERE id = '". $user['id'] ."'");
                $insertpassword->execute(array($pass1, $user['password']));
                header('Location: ../../index.php');
            } else {
                echo "Vos deux mdp ne correspondent pas !";
                header("refresh:5; url= ../../modif_profil.php");
            }
        }
    }
}
else {
    header('Location: ../../signin.php');
}
?>