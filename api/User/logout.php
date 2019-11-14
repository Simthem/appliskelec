<?php
session_start();  

if (isset($_COOKIE['id'])) {
    setcookie('id', '', time()-7000000, '/');
    unset($_COOKIE['id']);
} elseif (isset($_COOKIE['auth'])) {
    setcookie('auth', '', time()-7000000, '/');
    unset($_COOKIE['auth']);
}

session_destroy();
echo '<script type="text/javascript">alert("Vous allez être déconnecté(é)")</script>';
header("refresh:0; url= ../../signin.php");
exit();
?>