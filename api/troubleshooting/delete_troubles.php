<?php
session_start();

if(!($_SESSION['username'])) {  
  
    header('Location: ../../signin.php');//redirect to login page to secure the welcome page without login access.  
}

// get database connection
include_once '../objects/troubleshooting.php';
include_once '../config/db_connexion.php';


$database = new Database();
$db = $database->getConnection();
 

$user = new DeleteTroubles($bdd);

$stmt = "DELETE FROM chantiers WHERE id = " . $_GET['id'];
$del = $bdd->prepare($stmt);
$del->execute();
return $del;

if($bdd === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>