<?php
session_start();

// get database connection
include_once '../config/database.php';
 
// instantiate user object
include_once '../objects/troubleshooting.php';
 
$database = new Database();
$db = $database->getConnection();
 
$troubles = new Troubles($db);
 
// set user property values
$troubles->num_chantier = $_POST['num_chantier'];
$troubles->name = $_POST['name'];
$troubles->contact_name = $_POST['contact_name'];
$troubles->contact_phone = $_POST['contact_phone'];
$troubles->contact_address = $_POST['contact_address'];
$troubles->type = $_POST['type'];
$troubles->created = date('Y-m-d H:i:s');
$troubles->commit = $_POST['commit'];
$troubles->state = "Ouvert";

// create the user
$reponse = $db->query('SELECT * FROM chantiers WHERE num_chantier = "' . $_POST['num_chantier'] . '" ');
$num_chantier = $reponse->fetch();
if($num_chantier) {
    if ($_POST['num_chantier'] == $num_chantier['num_chantier'] and $_POST['num_chantier'] != 0) {
        echo "Chantiers ID already exists";
        header("refresh:2; url=../../add_troubleshooting.php");
        exit();
    } elseif($_POST['num_chantier'] == 0) {
        $troubles->type = "Dépannage";
    }
} else {
    $troubles->type = "Chantier";
}
if(empty($_POST['name'])) {
    echo "Chantiers name is required";
    header("refresh:2; url=../../add_troubleshooting.php");
    exit();
} elseif(empty($_POST['contact_name'])) {
    echo "Contacts name is required";
    header("refresh:2; url=../../add_troubleshooting.php");
    exit();
} else {
    echo "Success !! :)";
    $troubles->create();
    print_r($troubles);
    header("refresh:2; url=../../troubleshooting_list.php");
    exit();
}
?>