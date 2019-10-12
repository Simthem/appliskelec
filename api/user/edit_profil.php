<?php
session_start();

include_once '../objects/user.php';
include '../config/db_connexion.php';

if(!($_SESSION['username'])) {  
  
    header('Location: ../../signin.php');//redirect to login page to secure the welcome page without login access.  
}

if ($db->connect_errno) {
    die('Erreur de connexion : ' .$db->connect_errno);
  
  /* Définition de la requête */
} elseif($db){
    $query  = "
    UPDATE
        users
    SET
        username = '".$_POST['username1']."',
        first_name = '".$_POST['first_name']."',
        last_name = '".$_POST['last_name']."',
        e_mail = '".$_POST['e_mail']."',
        phone = '".$_POST['phone']."',
        password = '".$_POST['pass1']."'
    WHERE
        username = '".$_SESSION['username']."'";

    /* Envoie de la requête */
    $result = $db->query($query);

    /* Affichage du nombre de lignes affectées par la requête */
    echo "Nombre de lignes affectées (UPDATE): " . $db->affected_rows;

    /* Affichage de la requête (comme ça tu vois ce que contiennent tes variables $_POST) */
    echo '<pre>' . $query . '</pre>';
} else {
    echo "ERREUR, so sorry .. :/";
    header("refresh:2; url=../../list_profil.php");
}
/*
if(isset($_SESSION['username'])) {
    $requser = $db->prepare("SELECT * FROM users WHERE username = ?");
    $requser->execute(array($_SESSION['username']));
    $current_user = $requser->fetch();
    if(isset($_POST['username1']) AND !empty($_POST['username1']) AND $_POST['username1'] != $current_user['username']) {
        $newusername = htmlspecialchars($_POST['username1']);
        $insertusername = $db->prepare("UPDATE users SET username = '".$_POST['username1']."' WHERE username = ?");
        $insertusername->execute(array($newusername, $_SESSION['username']));
        header("Location: ../../list_profil.php");
    }
    if(isset($_POST['first_name']) AND !empty($_POST['first_name']) AND $_POST['first_name'] != $current_user['first_name']) {
        $newfirst_name = htmlspecialchars($_POST['first_name']);
        $insertfirst_name = $db->prepare("UPDATE users SET first_name = $newfirst_name WHERE username = ?");
        $insertfirst_name->execute(array($newfirst_name, $_SESSION['first_name']));
        header('Location: ../../list_profil.php');
    }
    if(isset($_POST['last_name']) AND !empty($_POST['last_name']) AND $_POST['last_name'] != $current_user['last_name']) {
    $newlast_name = htmlspecialchars($_POST['last_name']);
    $insertlast_name = $db->prepare("UPDATE users SET last_name = ? WHERE username = ?");
    $insertlast_name->execute(array($newlast_name, $_SESSION['last_name']));
    header('Location: ../../list_profil.php');
    }
    if(isset($_POST['e_mail']) AND !empty($_POST['e_mail']) AND $_POST['e_mail'] != $current_user['e_mail']) {
        $newe_mail = htmlspecialchars($_POST['e_mail']);
        $inserte_mail = $db->prepare("UPDATE users SET e_mail = ? WHERE username = ?");
        $inserte_mail->execute(array($newe_mail, $_SESSION['e_mail']));
        header('Location: ../../list_profil.php');
    }
    if(isset($_POST['phone']) AND !empty($_POST['phone']) AND $_POST['phone'] != $current_user['phone']) {
        $newphone = htmlspecialchars($_POST['phone']);
        $insertphone = $db->prepare("UPDATE users SET phone = ? WHERE username = ?");
        $insertphone->execute(array($newphone, $_SESSION['phone']));
        header('Location: ../../list_profil.php');
    }
    if(isset($_POST['pass1']) AND !empty($_POST['pass1']) AND isset($_POST['pass2']) AND !empty($_POST['pass2'])) {
        $pass1 = sha1($_POST['pass1']);
        $pass2 = sha1($_POST['pass2']);
        if($pass1 == $pass2) {
            $insertpassword = $db->prepare("UPDATE users SET password = ? WHERE username = ?");
            $insertpassword->execute(array($pass1, $_SESSION['password']));
            header('Location: ../../list_profil.php');
        } else {
            $msg = "Vos deux mdp ne correspondent pas !";
        } 
    }
}
else {
    header('Location: ../../signin.php');
}*/
?>