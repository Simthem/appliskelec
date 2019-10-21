<?php
include_once 'database.php';
include_once '../objects/user.php';

$db=mysqli_connect("localhost","admingroot","4e;3di7;","appli_skelec"); 
mysqli_select_db($db,"users");
$bdd = new PDO('mysql:host=localhost;dbname=appli_skelec', 'admingroot', '4e;3di7;');
?>