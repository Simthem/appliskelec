<?php
session_start();

if(!($_SESSION['username'])) {  
  
    header('Location: ../../signin.php');//redirect to login page to secure the welcome page without login access.  
}

// get database connection
include_once '../config/db_connexion.php';


include_once '../objects/user.php';


$database = new Database();
$db = $database->getConnection();
 

$user = new DeleteClass($bdd);

$query = "SELECT * FROM users WHERE id = " . $_GET['id'];
$result = $bdd->prepare($query);
$result->execute();
//return $result;
if ($result) {
    $stmt = "DELETE FROM users WHERE id = " . $_GET['id'];
    $del = $bdd->prepare($stmt);
    $del->execute();
    echo "user.delete";
    print_r($del);
    return $del;
} else  {
    echo "no \$result";
}

if($bdd === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>