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
echo "Vous allez être déconnecté(é)";
header("Location: ../../signin.php");
exit;
?>