<?php
session_start();

include_once '../objects/user.php';
include_once '../config/db_connexion.php';

if(!($_SESSION['username'])) {  
  
    header('Location: ../../signin.php');//redirect to login page to secure the welcome page without login access.  
}

$stmt = $bdd->prepare("SELECT id FROM users WHERE username = '". $_SESSION['username'] ."'");
$stmt->execute();
$user = $stmt->fetch();
$stmt_admin = $bdd->prepare("SELECT * FROM `admin` WHERE admin_name = '". $_SESSION['admin_name'] ."'");
$stmt_admin->execute();
$admin = $stmt_admin->fetch();
if($user) {
    $_SESSION['id'] = $user['id'];
} elseif ($admin) {
    $_SESSION['id'] = $admin['id'];
} else {
    echo "ERROR: Could not get 'id' of current user [first_method]";
}


$sql = $bdd->prepare("SELECT * FROM chantiers WHERE id =" . $_POST['id']);
$sql->execute();
$chantier = $sql->fetch();

if($bdd === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}


if ($_POST['state'] != $chantier['state']) {
    $newstate = htmlspecialchars($_POST['state']);
    $insertstate = $bdd->prepare("UPDATE chantiers SET `state` = '". $newstate ."' WHERE id = '". $_POST['id'] ."'");
    $insertstate->execute(array($newstate, $_POST['state']));
    //echo "first";
    header('Location: ../../modif_troubleshooting.php?id=' . $_POST['id']);
}

if (!empty($_POST['num_chantier'])) {
    if(!empty($_POST['num_chantier']) && $_POST['num_chantier'] != $chantier['num_chantier']) {
        $newnum = htmlspecialchars($_POST['num_chantier']);
        $insertnum = $bdd->prepare("UPDATE chantiers SET num_chantier = '". $newnum ."' WHERE id = '". $_POST['id'] ."'");
        $insertnum->execute(array($newnum, $_POST['num_chantier']));
        //echo "first";
        header('Location: ../../modif_troubleshooting.php?id=' . $_POST['id']);
    }
    if(!empty($_POST['name']) && $_POST['name'] != $chantier['name']) {
        $newname = htmlspecialchars($_POST['name']);
        $insertname = $bdd->prepare("UPDATE chantiers SET `name` = '". $newname ."' WHERE id = '". $_POST['id'] ."'");
        $insertname->execute(array($newname, $_POST['name']));
        //echo "first";
        header('Location: ../../modif_troubleshooting.php?id=' . $_POST['id']);
    }
    if(!empty($_POST['contact_name']) && $_POST['contact_name'] != $chantier['contact_name']) {
        $newc_name = htmlspecialchars($_POST['contact_name']);
        $insertc_name = $bdd->prepare("UPDATE chantiers SET contact_name = '". $newc_name ."' WHERE id = '". $_POST['id'] ."'");
        $insertc_name->execute(array($newc_name, $_POST['contact_name']));
        //echo "first";
        header('Location: ../../modif_troubleshooting.php?id=' . $_POST['id']);
    }
    if(!empty($_POST['contact_phone']) && $_POST['contact_phone'] != $chantier['contact_phone']) {
        $newc_phone = htmlspecialchars($_POST['contact_phone']);
        $insertc_phone = $bdd->prepare("UPDATE chantiers SET contact_phone = '". $newc_phone ."' WHERE id = '". $_POST['id'] ."'");
        $insertc_phone->execute(array($newc_phone, $_POST['contact_phone']));
        //echo "first";
        header('Location: ../../modif_troubleshooting.php?id=' . $_POST['id']);
    }
    if(!empty($_POST['contact_address']) && $_POST['contact_address'] != $chantier['contact_address']) {
        $newc_address = htmlspecialchars($_POST['contact_address']);
        $insertc_address = $bdd->prepare("UPDATE chantiers SET contact_address = '". $newc_phone ."' WHERE id = '". $_POST['id'] ."'");
        $insertc_address->execute(array($newc_address, $_POST['contact_address']));
        //echo "first";
        header('Location: ../../modif_troubleshooting.php?id=' . $_POST['id']);
    }
    if(!empty($_POST['commit']) && $_POST['commit'] != $chantier['commit']) {
        $newcom = htmlspecialchars($_POST['commit']);
        $insertcom = $bdd->prepare("UPDATE chantiers SET `commit` = '". $newcom ."' WHERE id = '". $_POST['id'] ."'");
        $insertcom->execute(array($newcom, $_POST['commit']));
        //echo "first";
        header('Location: ../../modif_troubleshooting.php?id=' . $_POST['id']);
    }
} else {
    header('Location: ../../modif_troubleshooting.php?id=' . $_POST['id']);
}
?>