<?php
session_start();

// get database connection
include_once '../config/database.php';
 
// instantiate troubles object
include_once '../objects/troubleshooting.php';
 
$database = new Database();
$db = $database->getConnection();
 
$troubles = new Troubles($db);
 
// set troubles property values
$troubles->num_chantier = $_POST['num_chantier'];
$troubles->name = $_POST['name'];
$troubles->contact_name = $_POST['contact_name'];
$troubles->contact_phone = $_POST['contact_phone'];
$troubles->contact_address = $_POST['contact_address'];
$troubles->commit = $_POST['commit'];
$troubles->type = NULL;
$troubles->state = $_POST['state'];
$troubles->created = date('Y-m-d H:i:s');

// create the troubles
$reponse = $db->query('SELECT num_chantier FROM chantiers WHERE num_chantier = "' . $_POST['num_chantier'] . '" ');
$num_chantier = $reponse->fetch();

if($num_chantier) {
    if ($_POST['num_chantier'] == $num_chantier['num_chantier'] and ($_POST['num_chantier'] != 0 or $_POST['num_chantier'] != NULL)) {
        echo "Chantiers ID already exists !";
        header("refresh:3; url=../../add_troubleshooting.php");
        exit();
    } else {
        echo "dépannage";
        $troubles->num_chantier = "0";
        $troubles->type = "Dépannage";
    }
} elseif ($_POST['num_chantier'] < 19001) {
    echo "Chantiers ID should be greater .. !";
    header("refresh:3; url=../../add_troubleshooting.php");
    exit();
} 
else {
    echo "chantier";
    $troubles->type = "Chantier";
}

if(empty($_POST['name'])) {
    echo "Name is required";
    header("refresh:2; url=../../add_troubleshooting.php");
    exit();
} elseif(empty($_POST['contact_name'])) {
    echo "Contact name is required";
    header("refresh:2; url=../../add_troubleshooting.php");
    exit();
} else {
    echo "Success !! :)";
    $troubles->create_troubles();
    header("refresh:2; url=../../troubleshooting_list.php");
    exit();
}
?>