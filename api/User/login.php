<?php
session_start();
// include database and object files
include_once '../config/database.php';
include '../config/db_connexion.php';

//instanciate objects
include_once '../objects/user.php';

//api_pass
include '../../m_p/password_compat-master/lib/password.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

$user = new User($db);

// set ID property of user to be edited
$user->username = isset($_POST['username']) ? $_POST['username'] : die();

if (!empty($_POST['username'])) {
    $sql_user = $bdd->prepare("SELECT `password` FROM users WHERE username ='" . $_POST['username'] . "'");
    $sql_user->execute();
    $reponse = $sql_user->fetch();

    if ($sql_user->rowCount()> 0) {
        if ($reponse) {
            
            $hash = $reponse['password'];
            
            if (password_verify($_POST['password'], $hash)) {
                echo "<br />ok";
                $user->password = $hash ? $hash : die();
            } else {
                echo "Password_hash non reconnu";
                header("refresh:2; url=../../signin.php");
                exit();
            }
        } else {
            echo "Nom d'utilisateur ou mot de passe non reconnu";
            header("refresh:2; url=../../signin.php");
            exit();
        } 
    } else {
        $admin = new Admin($db);
        $admin->admin_name = isset($_POST['username']) ? $_POST['username'] : die();

        $sql_admin = $bdd->prepare("SELECT admin_pass FROM `admin` WHERE admin_name ='" . $_POST['username'] . "'");
        $sql_admin->execute();
        $result = $sql_admin->fetch();

        if ($sql_admin->rowCount()> 0) {
            if ($result) {

                $hash = $result['admin_pass'];

                if (password_verify($_POST['password'], $hash)) {
                    echo "<br />ok";
                    $admin->admin_pass = $hash ? $hash : die();
                } else {
                    echo "hash non reconnu";
                    header("refresh:2; url=../../signin.php");
                    exit();
                }
            } else {
                echo "Nom d'utilisateur ou mot de passe non reconnu";
                header("refresh:2; url=../../signin.php");
                exit();
            } 
        }
    }
} else {
    echo "Nom d'utilisateur ou mot de passe non reconnu";
    header("refresh:2; url=../../signin.php");
    exit();
} 


// read the details of user to be edited  
$stmt = $user->login();

if ($stmt->rowCount() > 0){
    
    $_SESSION['username'] = $_POST['username'];//here session is used and value of 'username' store in $_SESSION.

    $pdo_user = $bdd->prepare("SELECT id, `password`, username FROM users WHERE username = '" . $_POST['username'] . "'");
    $pdo_user->execute();
    $auth = $pdo_user->fetch();

    if ($pdo_user->rowCount() > 0){
        
        if($auth) {
            $value = $auth['id'].'---'.hash('sha512', $auth['username'].'---'.$auth['password']);
            setcookie('id', $value, time() + (7 * 24 * 3600) , '/', null, false, true);
            session_start();
            header("Location:../../index.php");
            exit();
        } else {
            echo "ERROR: Username ou mot de passe non reconnu ..";
            header("refresh:2; url=../../signin.php");
            exit();
        }
    }

} elseif (!($stmt->rowCount() > 0)){
    
    $sql = $admin->log_admin();

    if ($sql->rowCount() > 0) {

        $_SESSION['username'] = "admin";
        $_SESSION['admin_name'] = $_POST['username'];

        $stmt_admin = $bdd->prepare("SELECT id, admin_name, admin_pass FROM `admin` WHERE admin_name = '". $_SESSION['admin_name'] ."'");
        $stmt_admin->execute();
        $admin = $stmt_admin->fetch();
        if ($stmt_admin->rowCount() > 0) {
            echo "<br />";
            print_r($stmt_admin);
            if ($admin) {
                $value = $admin['id'].'---'.hash('sha512', $admin['admin_name'].'---'.$admin['admin_pass']);
                setcookie('auth', $value, time() + (7 * 24 * 3600) , '/', null, false, true);
                session_start();
                header("Location:../../index.php");
                exit();
            } else {
                echo "ERROR: Username/mot de passe non reconnu ..";
            }
        }
    } else {
        echo "Username and/or Password incorrect.\\nTry again.";
        header("refresh:2; url=../../signin.php");
        exit();
    }
} else {
    echo "Username and/or Password incorrect.\\nTry again.";
    header("refresh:2; url=../../signin.php");
    exit();
}
?>