<?php
session_start();

include_once '../objects/user.php';
include_once '../config/db_connexion.php';
include_once '../../m_p/password_compat-master/lib/password.php';

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

if($bdd === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

if (($_POST['id'] != $admin['id'] AND $_SESSION['id'] == $admin['id']) OR ($_POST['id'] == $user['id'] AND $_SESSION['id'] == $user['id'])) {
    
    if(!empty($_POST['username']) AND $_POST['username'] != $user['username']) {
        $newusername = htmlspecialchars($_POST['username']);
        $insertusername = $bdd->prepare("UPDATE users SET username = '". $newusername ."' WHERE id = '". $_POST['id'] ."'");
        $insertusername->execute(array($newusername, $_POST['username']));
    }

    if (!empty($_POST['first_name']) AND $_POST['first_name'] != $user['first_name']) {
        $newfirst_name = htmlspecialchars($_POST['first_name']);
        $insertfirst_name = $bdd->prepare("UPDATE users SET first_name = '". $newfirst_name ."' WHERE id = '". $_POST['id'] ."'");
        $insertfirst_name->execute(array($newfirst_name, $_POST['first_name']));
    }

    if (!empty($_POST['last_name']) AND $_POST['last_name'] != $user['last_name']) {
        $newlast_name = htmlspecialchars($_POST['last_name']);
        $insertlast_name = $bdd->prepare("UPDATE users SET last_name = '". $newlast_name ."' WHERE id = '". $_POST['id'] ."'");
        $insertlast_name->execute(array($newlast_name, $_POST['last_name']));
    }

    if (!empty($_POST['e_mail']) AND $_POST['e_mail'] != $user['e_mail']) {
        $newe_mail = htmlspecialchars($_POST['e_mail']);
        $inserte_mail = $bdd->prepare("UPDATE users SET e_mail = '". $newe_mail ."' WHERE id = '". $_POST['id'] ."'");
        $inserte_mail->execute(array($newe_mail, $_POST['e_mail']));
    }

    if (!empty($_POST['phone']) AND $_POST['phone'] != $user['phone']) {
        $newphone = htmlspecialchars($_POST['phone']);
        $insertphone = $bdd->prepare("UPDATE users SET phone = '". $newphone ."' WHERE id = '". $_POST['id'] ."'");
        $insertphone->execute(array($newphone, $_POST['phone']));
    }

    if (($_POST['id'] != $admin['id'] AND $_SESSION['id'] == $_POST['id'] AND $_SESSION['id'] == $user['id']) OR ($_POST['id'] != $admin['id'] && $_SESSION['id'] == $admin['id'])) { 
        if (!empty($_POST['pass1']) AND !empty($_POST['pass2'])) {

            $pass1 = password_hash($_POST['pass1'], PASSWORD_BCRYPT, array("cost" => 10));

            if ($_POST['pass1'] == $_POST['pass2'] AND !(password_verify($pass1, $user['password']))) {

                $insertpassword = $bdd->prepare("UPDATE users SET password = '". $pass1 ."' WHERE id = '". $_POST['id'] ."'");
                $insertpassword->execute(array($pass1, $_POST['password']));

                $pdo_user = $bdd->prepare("SELECT id, `password`, username FROM users WHERE username = '" . $_POST['username'] . "'");
                $pdo_user->execute();
                $auth = $pdo_user->fetch();

                if ($pdo_user->rowCount() > 0 AND ($_SESSION['id'] != $admin['id'])){
                    
                    $_SESSION['username'] = $_POST['username'];

                    if ($auth) {
                        $value = $auth['id'].'---'.hash('sha512', $auth['username'].'---'.$auth['password']);
                        setcookie('id', $value, time() + (7 * 24 * 3600) , '/', null, false, true);
                        session_start();
                        echo '<script type="text/javascript">alert("Édition validée :)")</script>';
                        header("refresh:0; url= ../../index.php");
                        exit ();
                    } else {
                        echo "ERROR: Username ou mot de passe non reconnu ..";
                        header("refresh:2; url=../../signin.php");
                        exit ();
                    }
                } else {
                    echo '<script type="text/javascript">alert("Édition validée :)")</script>';
                    header("refresh:0; url= ../../index.php");
                    exit ();
                }

            } elseif ($_POST['pass1'] == $_POST['pass2'] AND password_verify($pass1, $user['password'])) {
                
                $_SESSION['username'] = $_POST['username'];//here session is used and value of 'username' store in $_SESSION.

                $pdo_user = $bdd->prepare("SELECT id, `password`, username FROM users WHERE username = '" . $_POST['username'] . "'");
                $pdo_user->execute();
                $auth = $pdo_user->fetch();

                if ($pdo_user->rowCount() > 0 AND ($_SESSION['id'] != $admin['id'])){
                    
                    if ($auth) {
                        $value = $auth['id'].'---'.hash('sha512', $auth['username'].'---'.$auth['password']);
                        setcookie('id', $value, time() + (7 * 24 * 3600) , '/', null, false, true);
                        echo '<script type="text/javascript">alert("Édition correctement réalisée :)")</script>';
                        header("refresh:0; url=../../index.php");
                        exit ();
                    } else {
                        echo "ERROR: Username ou mot de passe non reconnu ..";
                        header('refresh:5; url= ../../modif_profil.php?id=' . $_POST['id']);
                        exit ();
                    }
                }
            } else {
                echo '<script type="text/javascript">alert("Vos deux mots de passent ne correspondent pas !")</script>';
                header("refresh:0; url=../../modif_profil.php?id=" . $_POST['id']);
                exit();
            }
        }
    }
} elseif ($_POST['id'] == $admin['id']) {

    if (!empty($_POST['username']) AND $_POST['username'] != $admin['admin_name']) {
        $newusername = htmlspecialchars($_POST['username']);
        $insertusername = $bdd->prepare("UPDATE `admin` SET admin_name = '". $newusername ."' WHERE id = '". $_POST['id'] ."'");
        $insertusername->execute(array($newusername, $_POST['username']));
    }

    if (!empty($_POST['first_name']) AND $_POST['first_name'] != $admin['first_name']) {
        $newfirst_name = htmlspecialchars($_POST['first_name']);
        $insertfirst_name = $bdd->prepare("UPDATE `admin` SET first_name = '". $newfirst_name ."' WHERE id = '". $_POST['id'] ."'");
        $insertfirst_name->execute(array($newfirst_name, $_POST['first_name']));
    }

    if (!empty($_POST['last_name']) AND $_POST['last_name'] != $admin['last_name']) {
        $newlast_name = htmlspecialchars($_POST['last_name']);
        $insertlast_name = $bdd->prepare("UPDATE `admin` SET last_name = '". $newlast_name ."' WHERE id = '". $_POST['id'] ."'");
        $insertlast_name->execute(array($newlast_name, $_POST['last_name']));
    }

    if (!empty($_POST['e_mail']) AND $_POST['e_mail'] != $admin['e_mail']) {
        $newe_mail = htmlspecialchars($_POST['e_mail']);
        $inserte_mail = $bdd->prepare("UPDATE `admin` SET e_mail = '". $newe_mail ."' WHERE id = '". $_POST['id'] ."'");
        $inserte_mail->execute(array($newe_mail, $_POST['e_mail']));
    }

    if (!empty($_POST['phone']) AND $_POST['phone'] != $admin['phone']) {
        $newphone = htmlspecialchars($_POST['phone']);
        $insertphone = $bdd->prepare("UPDATE `admin` SET phone = '". $newphone ."' WHERE id = '". $_POST['id'] ."'");
        $insertphone->execute(array($newphone, $_POST['phone']));
    }

    if (!empty($_POST['total_hours']) AND $_POST['total_hours'] != $admin['total_hours']) {
        $newtotal = htmlspecialchars($_POST['total_hours']);
        $inserttotal = $bdd->prepare("UPDATE `admin` SET total_hours = '". $newtotal ."' WHERE id = '". $_POST['id'] ."'");
        $inserttotal->execute(array($newtotal, $_POST['total_hours']));
    }

    if (!empty($_POST['pass1']) AND !empty($_POST['pass2'])) {

        $pass1 = password_hash($_POST['pass1'], PASSWORD_BCRYPT, array("cost" => 10));
        
        if ($_POST['pass1'] == $_POST['pass2'] AND !(password_verify($pass1, $admin['admin_pass']))) {
            
            $insertpassword = $bdd->prepare("UPDATE `admin` SET admin_pass = '". $pass1 ."' WHERE id = '". $_POST['id'] ."'");
            $insertpassword->execute(array($pass1, $_POST['pass1']));

            $_SESSION['username'] = "admin";
            $_SESSION['admin_name'] = $_POST['username'];

            $stmt_admin = $bdd->prepare("SELECT id, admin_name, admin_pass FROM `admin` WHERE admin_name = '". $_SESSION['admin_name'] ."'");
            $stmt_admin->execute();
            $admin = $stmt_admin->fetch();

            if ($stmt_admin->rowCount() > 0) {

                if ($admin) {
                    $value = $admin['id'].'---'.hash('sha512', $admin['admin_name'].'---'.$admin['admin_pass']);
                    setcookie('auth', $value, time() + (7 * 24 * 3600) , '/', null, false, true);
                    echo '<script type="text/javascript">alert("Édition correctement réalisée :)")</script>';
                    header("refresh:0; url=../../index.php");
                    exit ();
                } else {
                    echo "ERROR while the profil edition";
                    header("refresh:5; url=../../modif_profil.php");
                    exit ();
                }
            }
        } elseif ($pass1 == $pass2 AND password_verify($pass1, $admin['admin_pass'])) {
            echo '<script type="text/javascript">alert("Édition correctement réalisée :)")</script>';
            header('refresh:0; url=../../index.php');
            exit ();
        } else {
            echo '<script type="text/javascript">alert("Vos deux mots de passent ne correspondent pas !")</script>';
            header('refresh:0; url=../../modif_profil.php?id=' . $_POST['id']);
            exit ();
        }
    } else {
        echo '<script type="text/javascript">alert("Les mots de passes sont nécessaires à l\'édition du profile ..")</script>';
        header('refresh:0; url=../../modif_profil.php?id=' . $_POST['id']);
        exit ();
    }
} else {
    echo '<script type="text/javascript">alert("ERROR: Permissions default !")</script>';
    header('refresh:0; url=../../signin.php');
    exit ();
}
?>