<?php
session_start();

if(!($_SESSION['username'])) {  
  
    header('Location: ../../signin.php');//redirect to login page to secure the welcome page without login access.  
}

// get database connection
include_once '../objects/user.php';
include_once '../config/db_connexion.php';


$database = new Database();
$db = $database->getConnection();
 

$user = new DeleteClass($bdd);

$stmt = "DELETE FROM users WHERE id = " . $_GET['id'];
$del = $bdd->prepare($stmt);
$del->execute();
echo "step two - DELETE";
header("refresh:5, url=../../list_profil.php");
exit();
return $del;

if($bdd === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>