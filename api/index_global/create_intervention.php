<?php
session_start();

// get database connection
include_once '../config/database.php';
include_once '../config/db_connexion.php';
include_once '../objects/intervention.php';
 
$database = new Database();
$bdd = $database->getConnection();
 
$intervention = new Intervention($bdd);

// set user property values
$intervention->user_id = $_SESSION['id'];
$intervention->chantier_id = $_POST['chantier_id'];
$intervention->intervention_hours = $_POST['intervention_hours'];
$intervention->panier_repas = $_POST['panier_repas'];
$intervention->night_hours = $_POST['night_hours'];
$intervention->commit = $_POST['commit'];
$intervention->created = date('Y-m-d H:i:s');

if(empty($_POST['chantier_id'])) {
    echo "chantier_id required";
    header("refresh:2; url=../../index.php");
    exit();
} elseif(empty($_POST['intervention_hours'])) {
    echo "intervention_hours is required";
    header("refresh:2; url=../../index.php");
    exit();
} elseif($_POST['night_hours'] > 8) {
    echo "night_hours is wrong!";
    header("refresh:2; url=../../index.php");
    exit();
} else {
    echo "Success !! :)";
    //print_r($intervention);
    $intervention->create();
    print_r($intervention);
    header("refresh:2; url=../../index.php");
    exit();
}
?>