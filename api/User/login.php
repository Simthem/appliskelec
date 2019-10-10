<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/user.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare user object
$user = new User($db);
// set ID property of user to be edited
$user->username = isset($_POST['username']) ? $_POST['username'] : die();
$user->password = base64_encode(isset($_POST['password']) ? $_POST['password'] : die());
// read the details of user to be edited
$stmt = $user->login();
if($stmt->rowCount() > 0){
    $check_user="SELECT * FROM users WHERE username = " . $_POST['username'] . " AND password = " . $_POST['password'] . " ";  
  
    $run=mysqli_query($dbcon,$check_user);  
  
    if(mysqli_num_rows($run))  
    {  
        echo "<script>console.log(tamère)</script>";  
  
        $_SESSION['username']= $_POST['username'];//here session is used and value of $user_email store in $_SESSION.  
  
    }
    header("Location:../../index.php");
    exit();
}
else{
    echo "Username and/or Password incorrect.\\nTry again.";
    header("refresh:2; url=../../signin.php");
    exit();
}

?>