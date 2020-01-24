<?php
session_start();

// get database connection
include_once '../config/database.php';
include '../config/db_connexion.php';
include_once '../objects/intervention.php';
 
$sql_date = "SELECT id, created FROM chantiers WHERE `name` ='" . $_POST['chantier_name'] . "'";
$sql_state = "SELECT `state` FROM global_reference WHERE user_id = '" . $_POST['user_id'] . "' AND updated = '" . $_POST['up_inter'] . "'";

if ($res_date = mysqli_query($db, $sql_date)) {
    if ($db === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }
    if (mysqli_num_rows($res_date) > 0 ){
        
        while ($date = $res_date->fetch_array()) {
            $created = $date['created'];
            $chantier_id = $date['id'];
            $_POST['chantier_id'] = $chantier_id;
        }
        mysqli_free_result($res_date);
    } else {
        echo "No records matching your query were found.";
    }
} else {
    echo "ERROR: Could not able to execute $sql_date. " . mysqli_error($db);
}

if ($res_state = mysqli_query($db, $sql_state)) {
    if ($db === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }
    if (mysqli_num_rows($res_state) > 0) {
        while ($state = $res_state->fetch_array()) {
            if ($state['state'] != 0) {
                echo 'dans ta face2';
                echo '<script type="text/javascript">alert("Votre journée a déjà était validée ! Veuillez contacter votre responsable afin de signaler votre absence à ce jour.")</script>';
                header("refresh:0; url=../../absence.php?id=" . $_POST['user_id']);
                exit();
            }
        }
    }
}



$database = new Database();
$db = $database->getConnection();

$intervention = new Intervention($db);

// set user property values
$intervention->user_id = $_POST['user_id'];
$intervention->chantier_id = $chantier_id;
$intervention->intervention_hours = 0;
$intervention->absence = $_POST['intervention_hours'];

if(isset($_POST['panier_repas'])) {
    $intervention->panier_repas = $_POST['panier_repas'];
} else {
    $intervention->panier_repas = 0;
}

$intervention->night_hours = 0;

if (!empty($_POST['commit']) && isset($_POST['chantier']) && !empty($_POST['chantier'])) {
    $intervention->commit = $_POST['commit'];
} elseif (!empty($_POST['com_day']) && isset($_POST['ab_day']) && !empty($_POST['ab_day'])) {
    $intervention->commit = $_POST['com_day'];
} else {
    $intervention->commit = NULL;
}

$intervention->created = $created;
$intervention->updated = $_POST['up_inter'];

$intervention->state = 0;

if(empty($chantier_id)) {
    echo '<script type="text/javascript">alert("Un nom de chantier est requis afin d\'enregistrer votre absence.")</script>';
    header("refresh:0; url=../../absence.php?id=" . $_POST['user_id']);
    exit();
} elseif(empty($_POST['intervention_hours'])) {
    echo '<script type="text/javascript">alert("Les heures d\'intervention sont requises (ou dépasse le nombre d\'heures envisagées)</script>';
    header("refresh:0; url=../../index.php");
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