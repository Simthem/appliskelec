<?php
session_start();

// get database connection
include_once '../config/database.php';
include_once '../config/db_connexion.php';
include_once '../objects/intervention.php';
 
$sql_date = "SELECT id, created FROM chantiers WHERE `name` ='" . $_POST['chantier_name'] . "'";
if($res_date = mysqli_query($db, $sql_date)){
    if(mysqli_num_rows($res_date) > 0){
        if ( $db === false){
            die("ERROR: Could not connect. " . mysqli_connect_error());
        }
        #print_r($res_date);
        while ($date = $res_date->fetch_array()){
            echo '<br />';
            #print_r($date);
            $created = $date['created'];
            $chantier_id = $date['id'];
            $_POST['chantier_id'] = $chantier_id;
        }
        mysqli_free_result($res_date);
    } else{
        echo "No records matching your query were found.";
    }
} else {
    echo "ERROR: Could not able to execute $sql_date. " . mysqli_error($db);
}

$database = new Database();
$db = $database->getConnection();

$intervention = new Intervention($db);

// set user property values
$intervention->user_id = $_POST['user_id'];
$intervention->chantier_id = $chantier_id;
$intervention->intervention_hours = 0;
$intervention->absence = $_POST['intervention_hours'] * -1;
$intervention->state = 0;

if(isset($_POST['panier_repas'])) {
    $intervention->panier_repas = $_POST['panier_repas'];
} else {
    $intervention->panier_repas = 0;
}

$intervention->night_hours = 0;

if (!empty($_POST['commit'])) {
    $intervention->commit = $_POST['commit'];
} else {
    $intervention->commit = NULL;
}

$intervention->created = $created;
$intervention->updated = $_POST['up_inter'];

if(empty($chantier_id)) {
    echo "chantier_id required";
    header("refresh:2; url=../../index.php");
    exit();
} elseif(empty($_POST['intervention_hours'])) {
    echo "Les heures d'intervention sont requises (ou dépasse le nombre d'heures envisagé)";
    header("refresh:2; url=../../index.php");
    exit();
} else {
    echo "Success !! :)";
    $intervention->create();
    print_r($intervention);
    //echo '<br />';
    //print_r($_POST);
    header("refresh:2; url=../../valid_day.php?up_int=" . $_POST['up_inter']);
    exit();
}
?>