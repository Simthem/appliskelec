<?php
session_start();
//print_r(session_get_cookie_params());

include 'api/config/db_connexion.php';

if (isset($_COOKIE['id'])) {

    $auth = explode('---', $_COOKIE['id']);

    if (count($auth) === 2) {
        $req = $bdd->prepare('SELECT id, username, `password` FROM users WHERE id = :id');
        $req->execute([ ':id' => $auth[0] ]);
        $user = $req->fetch(PDO::FETCH_ASSOC);
         
        if ($user && $auth[1] === hash('sha512', $user['username'].'---'.$user['password'])) {
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
        } else {
            header("Location: signin.php");//redirect to login page to secure the welcome page without login access.  
        }
    }
} elseif (isset($_COOKIE['auth'])) {

    $auth = explode('---', $_COOKIE['auth']);
 
    if (count($auth) === 2) {
        $req = $bdd->prepare('SELECT id, admin_name, admin_pass FROM `admin` WHERE id = :id');
        $req->execute([ ':id' => $auth[0] ]);
        $admin = $req->fetch(PDO::FETCH_ASSOC);
         
        if ($admin && $auth[1] === hash('sha512', $admin['admin_name'].'---'.$admin['admin_pass'])) {
            $_SESSION['id'] = $admin['id'];
            $_SESSION['username'] = "admin";
            $_SESSION['admin_name'] = $admin['admin_name'];
        } else {
            header("Location: signin.php");//redirect to login page to secure the welcome page without login access.  
        }
    }
}

if(!($_SESSION['username'])) {  
  
    header("Location: signin.php");//redirect to login page to secure the welcome page without login access.  
}

$stmt = $bdd->prepare("SELECT id FROM users WHERE username = '". $_SESSION['username'] ."'");
$stmt->execute();
$user = $stmt->fetch();

$stmt_admin = $bdd->prepare("SELECT id FROM `admin` WHERE admin_name = '". $_SESSION['admin_name'] ."'");
$stmt_admin->execute();
$admin = $stmt_admin->fetch();

if($user) {
    $_SESSION['id'] = $user['id'];
} elseif ($admin) {
    $_SESSION['id'] = $admin['id'];
} else {
    echo "ERROR: Could not get 'id' of current user [first_method]";
}

$sql = 
"SELECT 
    id,
    `name`,
    contact_address,
    num_chantier,
    `state`
FROM
    appli_skelec.chantiers
WHERE
    num_chantier is not NULL 
    AND
    num_chantier != 0
GROUP BY
    id, num_chantier, `name`, contact_address, `state`
ORDER BY
    num_chantier DESC";

$stmt =
"SELECT 
    id,
    #created,
    `name`,
    contact_address,
    num_chantier,
    `state`
FROM
    appli_skelec.chantiers
WHERE
    num_chantier is NULL
ORDER BY
    id DESC";
?>

<!DOCTYPE html>

<html class="overflow-y mb-0">

    <?php include 'header.php'; ?>


                <div class="icons-navbar">
                    <div class="menu-btn-bars text-white"><button class="menu-btn fas fa-bars text-warning w-100 fa-3x p-0"></button></div>
                    <a href="index.php" class="text-warning d-inline-flex m-auto"><img class="mr-2 ml-2" src="img/ampoule_skelec.png" alt="logo S.K.elec" height="45" width="30"><h2 class="d-inline-flex mt-0 mr-2 mb-0 ml-0">S.K.elec</h2></a>
                    <a href="add_troubleshooting.php" class="text-white pl-3"><i class="menu-btn-plus fas fa-plus-circle text-warning fa-3x rounded-circle"></i></a>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div id="container">
            <div class="content pt-0 overflow-hidden">
                <h3 class="text-center mt-0 pt-5 pb-3">Liste des chantiers</h3>
                <ul class="nav nav-pills float-left pb-2">
                    <li class="active h-50"><a href="#tab1" data-toggle="pill" data-id="tab1" class="h-75 tab-1">Chantiers</a></li>
                    <li class="h-50"><a href="#tab2" data-toggle="pill" data-id="tab2" class="h-75 tab-2">Dépannages</a></li>
                </ul>
                <table class="table table-striped mt-0 ml-0 mb-0 text-center" style="height: 50px;">
                    <?php
                        if($result_chant = mysqli_query($db, $sql)){
                            if(mysqli_num_rows($result_chant) > 0){
                                echo '<thead>';
                                    echo '<tr>';
                                        echo '<th scope="col" class="text-center align-middle p-2 w-25" id="num_chantier">ID\'s</th>';
                                        //echo '<th scope="col" class="text-center align-middle p-4" id="e_mail">E-mail</th>';
                                        echo '<th scope="col" class="text-center align-middle p-2 w-25" id="name">Libellés</th>';
                                        echo '<th scope="col" class="text-center align-middle p-2 w-25" id="contact_address">Adresse</th>';
                                        echo '<th scope="col" class="text-center align-middle p-0 w-25" id="">Détails</th>';
                                    echo '</tr>';
                                echo '</thead>';
                    ?>
                </table>
                <div class="tab-content">
                    <div id="tab1" class="container-list m-auto tab-pane active in">
                        <table class="table table-striped pr-4 pl-4 mt-3 ml-auto mr-auto text-center" action="" method="POST">
                    <?php
                                if($db === false){
                                    die("ERROR: Could not connect. " . mysqli_connect_error());
                                }
                                echo '<tbody>';
                                    while($row = $result_chant->fetch_array()){
                                        echo '<tr>';
                                            if ($row['num_chantier'] != 0 or !empty($row['num_chantier'])) {
                                                if ($row['state']) {
                                                    echo '<td class="align-middle p-4 w-25">' . $row['num_chantier'] . '</td>';
                                                    //echo '<td class="align-middle p-4" style="word-wrap: break-word; max-width: 85px;">' . $row['e_mail'] . '</td>';
                                                    echo '<td class="align-middle p-4 w-25" style="word-wrap: break-word; max-width: 85px;">' . $row['name'] . '</td>';
                                                    echo '<td class="align-middle p-4 w-25" style="word-wrap: break-word; max-width: 85px;">' . $row['contact_address'] . '</td>';
                                                } else {
                                                    echo '<td class="align-middle p-4 w-25 border-top border-bottom border-danger">' . $row['num_chantier'] . '<br /><h6 class="text-danger">[Clôturé]</h6></td>';
                                                    //echo '<td class="align-middle p-4" style="word-wrap: break-word; max-width: 85px;">' . $row['e_mail'] . '</td>';
                                                    echo '<td class="align-middle p-4 w-25 border-top border-bottom border-danger" style="word-wrap: break-word; max-width: 85px;">' . $row['name'] . '</td>';
                                                    echo '<td class="align-middle p-4 w-25 border-top border-bottom border-danger" style="word-wrap: break-word; max-width: 85px;">' . $row['contact_address'] . '</td>';
                                                }
                                                if ($_SESSION['id'] == $admin['id']) {
                                                    if ($row['state']) {
                                                        echo '<td class="p-0 align-middle w-25">';
                                                        ?>
                                                        <form action="api/user/delete_troubles.php" method="GET" class="m-0 p-0">
                                                            <div class="float-left pl-0" id="<?php echo $row['id']; ?>" name="<?php echo $row['id']; ?>" onClick="reply_click_troubles(this.id)"><i class="fas fa-trash-alt"></i></div>
                                                        </form>
                                                        <div class="w-100 text-center"><a href="troubleshooting_details.php?id=<?php echo $row['id']; ?>"><i class="fas fa-tools mr-2"></i></a></div>
            
                                                        <?php
                                                        echo '</td>';
                                                    } else {
                                                        echo '<td class="p-0 align-middle w-25 border-top border-bottom border-danger">';
                                                        ?>
                                                        <form action="api/user/delete_troubles.php" method="GET" class="m-0 p-0">
                                                            <div class="float-left pl-0" id="<?php echo $row['id']; ?>" name="<?php echo $row['id']; ?>" onClick="reply_click_troubles(this.id)"><i class="fas fa-trash-alt"></i></div>
                                                        </form>
                                                        <div class="w-100 text-center"><a href="troubleshooting_details.php?id=<?php echo $row['id']; ?>"><i class="fas fa-tools mr-2"></i></a></div>
            
                                                        <?php
                                                        echo '</td>';
                                                    }
                                                } else {
                                                    if ($row['state']) {
                                                        echo "<td class='p-0 align-middle w-25'><a href='troubleshooting_details.php?id=" . $row['id'] . "'><i class='fas fa-tools'></i></a></td>";
                                                    } else {
                                                        echo "<td class='p-0 align-middle w-25 border-top border-bottom border-danger'><a href='troubleshooting_details.php?id=" . $row['id'] . "'><i class='fas fa-tools'></i></a></td>";
                                                    }
                                                }
                                            }
                                        echo '</tr>';
                                    }
                                echo '</tbody>';
                                mysqli_free_result($result_chant);
                            } else {
                                echo "No records matching your query were found.";
                            }
                        }
                    ?>
                        </table>
                    </div>
                </div>
                <div class="tab-content">
                    <div id="tab2" class="container-list m-auto tab-pane">
                        <table class="table table-striped pr-4 pl-4 mt-3 ml-auto mr-auto text-center" action="" method="POST">
                    <?php
                        if($result = mysqli_query($db, $stmt)){
                            if(mysqli_num_rows($result) > 0){

                                if($db === false){
                                    die("ERROR: Could not connect. " . mysqli_connect_error());
                                }
                                echo '<tbody>';
                                    while($row = $result->fetch_array()){
                                        echo '<tr>';
                                            if($row['num_chantier'] == 0 or empty($row['num_chantier'])) {
                                                if ($row['state']) {
                                                    echo '<td class="align-middle p-4 w-25 bg-success text-white">Dép.</td>';
                                                    //echo '<td class="align-middle p-4" style="word-wrap: break-word; max-width: 85px;">' . $row['e_mail'] . '</td>';
                                                    echo '<td class="align-middle p-4 w-25 bg-success text-white" style="word-wrap: break-word; max-width: 85px;">' . $row['name'] . '</td>';
                                                    echo '<td class="align-middle p-4 w-25 bg-success text-white" style="word-wrap: break-word; max-width: 85px;">' . $row['contact_address'] . '</td>';
                                                    //echo "<td class='p-0 align-middle w-25 bg-success'><a href='troubleshooting_details.php?chantier_id=" . $row['id']  . "'><i class='fas fa-tools text-white'></i></a></td>";
                                                } else {
                                                    echo '<td class="align-middle p-4 w-25 bg-success border-top border-bottom border-danger text-white">Dép.<br /><h6 class="text-danger">[Clôturé]</h6></td>';
                                                    //echo '<td class="align-middle p-4" style="word-wrap: break-word; max-width: 85px;">' . $row['e_mail'] . '</td>';
                                                    echo '<td class="align-middle p-4 w-25 bg-success border-top border-bottom border-danger text-white" style="word-wrap: break-word; max-width: 85px;">' . $row['name'] . '</td>';
                                                    echo '<td class="align-middle p-4 w-25 bg-success border-top border-bottom border-danger text-white" style="word-wrap: break-word; max-width: 85px;">' . $row['contact_address'] . '</td>';
                                                    //echo "<td class='p-0 align-middle w-25 bg-success border-top border-bottom border-danger'><a href='troubleshooting_details.php?chantier_id=" . $row['id']  . "'><i class='fas fa-tools text-white'></i></a></td>";
                                                }

                                                if ($_SESSION['id'] == $admin['id']) {
                                                    if ($row['state']) {
                                                        echo '<td class="bg-success p-0 align-middle w-25">';
                                                        ?>
                                                            <form action="api/user/delete_troubles.php" method="GET" class="m-0 p-0">
                                                                <div class="float-left pl-0" id="<?php echo $row['id']; ?>" name="<?php echo $row['id']; ?>" onClick="reply_click_troubles(this.id)"><i class="fas fa-trash-alt text-white"></i></div>
                                                            </form>
                                                            <div class="w-100 text-center mt-auto mb-auto"><a href="troubleshooting_details.php?id=<?php echo $row['id']; ?>"><i class="fas fa-tools mr-2 text-white"></i></a></div>
                                                        <?php
                                                        echo '</td>';
                                                    } else {
                                                        echo '<td class="bg-success p-0 align-middle w-25 border-top border-bottom border-danger">';
                                                        ?>
                                                            <form action="api/user/delete_troubles.php" method="GET" class="m-0 p-0">
                                                                <div class="float-left pl-0" id="<?php echo $row['id']; ?>" name="<?php echo $row['id']; ?>" onClick="reply_click_troubles(this.id)"><i class="fas fa-trash-alt text-white"></i></div>
                                                            </form>
                                                            <div class="w-100 text-center mt-auto mb-auto"><a href="troubleshooting_details.php?id=<?php echo $row['id']; ?>"><i class="fas fa-tools mr-2 text-white"></i></a></div>
                                                        <?php
                                                        echo '</td>';
                                                    }
                                                } else {
                                                    if ($row['state']) {
                                                        echo "<td class='p-0 align-middle w-25 bg-success'><a href='troubleshooting_details.php?id=" . $row['id'] . "'><i class='fas fa-tools text-white'></i></a></td>";
                                                    } else {
                                                        echo "<td class='p-0 align-middle w-25 bg-success border-top border-bottom border-danger'><a href='troubleshooting_details.php?id=" . $row['id'] . "'><i class='fas fa-tools text-white'></i></a></td>";
                                                    }
                                                }
                                            }
                                        echo '</tr>';
                                    }
                                echo '</tbody>';
                                mysqli_free_result($result);
                            } else {
                                echo "No records matching your query were found.";
                            }
                        } else {
                            echo "ERROR: Could not able to execute $stmt. " . mysqli_error($db);
                        }
                        mysqli_close($db);
                    ?>
                        </table>
                    </div>
                </div>
                <form class="pt-5 mt-5">
                    <div class="w-75 m-auto">
                        <a href="add_troubleshooting.php" class="btn send border-0 bg-white z-depth-1a mt-2 mb-2 text-dark">Ajouter un chantier</a>
                    </div>
                </form>
            </div>
        </div>
        
    <?php include 'footer.php'; ?>