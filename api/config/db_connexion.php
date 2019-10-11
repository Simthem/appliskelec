<?php
include_once '../config/database.php';
include_once '../objects/user.php';

$db=mysqli_connect("localhost","admingroot","4e;3di7;","appli_skelec");  
mysqli_select_db($db,"users");  
?>