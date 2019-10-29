<?php
session_start();

include 'api/config/db_connexion.php';


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
?>

<!DOCTYPE html>

<html class="overflow-y mb-0">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <link  rel="stylesheet" href="assets/css/bootstrap.css">
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <script src="https://kit.fontawesome.com/f14bbc71a6.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <title>Appli Skelec</title>
    </head>
    <body>
        <header class="header">
            <!-- Menu Button -->
            <div class="navbar-expand-md double-nav scrolling-navbar navbar-dark bg-dark">
                <!--Menu -->
                <nav class="menu left-menu">
                    <div class="menu-content">
                        <ul class="pl-0">
                            <li class="bg-dark border-top border-warning rounded-0 p-0 menu-link"><a href="troubleshooting_list.php" class="text-warning">Chantiers</a></li>
                            <li class="bg-dark border-top border-warning rounded-0 p-0 menu-link"><a href="list_profil.php" class="text-warning">Salariés</a></li>
                            <li class="bg-dark border-top border-warning rounded-0 p-0 menu-link"><a href="#" class="text-warning">Paramètres</a></li>
                            <li class="bg-dark border-top border-bottom border-warning rounded-0 p-0 menu-link"><a href="api/User/logout.php" class="text-warning">Déconnexion</a></li>
                        </ul>
                    </div>
                </nav>
                <div class="icons-navbar">
                    <div class="menu-btn-bars text-white"><button class="menu-btn fas fa-bars text-warning w-100 fa-3x p-0"></button></div>
                    <a href="index.php" class="text-warning m-auto"><h2 class="m-0">S.K.elec</h2></a>
                    <div action='api/user/edit_profil.php' method='GET'>
                        <?php
                            //echo $_SESSION['id'];
                            
                            if ($_SESSION['id'] == $admin['id']) {
                                $admin_sql = "SELECT * FROM `admin`";
                                if ($admin_result = mysqli_query($db, $admin_sql)){
                                    if (mysqli_num_rows($admin_result) > 0){
                                        if ($db === false){
                                            die("ERROR: Could not connect. " . mysqli_connect_error());
                                        }
                                        while($row = $admin_result->fetch_array()) {
                                            echo "<a href='modif_profil.php?id=" . $row['id'] . "' class='text-white pl-3'><i class='menu-btn-plus fas fa-user text-warning fa-3x rounded-circle'></i></a>";
                                        }
                                        mysqli_free_result($admin_result);
                                    } else {
                                        echo "No records matching your query were found.";
                                    }
                                } else {
                                    echo "ERROR: Could not able to execute $admin_sql. " . mysqli_error($db);
                                }
                            } else {
                                $user_sql = "SELECT * FROM users";
                                if ($user_result = mysqli_query($db, $user_sql)){
                                    if (mysqli_num_rows($user_result) > 0){
                                        if ($db === false) {
                                            die("ERROR: Could not connect. " . mysqli_connect_error());
                                        }
                                        while ($row = $user_result->fetch_array()){
                                            if ($row['id'] == $_SESSION['id']) {
                                                echo "<a href='modif_profil.php?id=" . $row['id'] . "' class='text-white pl-3'><i class='menu-btn-plus fas fa-user text-warning fa-3x rounded-circle'></i></a>";
                                            }
                                        }
                                        mysqli_free_result($user_result);
                                    } else {
                                        echo "No records matching your query were found.";
                                    }
                                } else {
                                    echo "ERROR: Could not able to execute $admin_sql. " . mysqli_error($db);
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div id="container">
            <div class="content pt-0 overflow-hidden">
                <h3 class="text-center mt-2 pb-4 pt-5">Liste des salariés</h3>
                <table class="table table-striped mt-0 ml-0 mb-0 text-center" style="height: 50px;">
                    <?php

                    $sql = 
                    "SELECT id, username, phone 
                    FROM users";

                    if ($result = mysqli_query($db, $sql)){
                        if (mysqli_num_rows($result) > 0){
                            echo '<thead>';
                                echo '<tr>';
                                    echo '<th scope="col" class="text-center align-middle p-2 w-25" id="first_name">Prénom</th>';
                                    //echo '<th scope="col" class="text-center align-middle p-4" id="e_mail">E-mail</th>';
                                    echo '<th scope="col" class="text-center align-middle p-2 w-25" id="phone">Téléphone</th>';
                                    echo '<th scope="col" class="text-center align-middle p-2 w-25" id="hours">H/totales</th>';
                                    echo '<th scope="col" class="text-center align-middle p-2 w-25" id="">Détails</th>';
                                echo '</tr>';
                            echo '</thead>';
                    ?>
                </table>
                <div class="container-list m-auto">
                    <table class="table table-striped pr-4 pl-4 mt-3 ml-auto mr-auto text-center" action="api/user/edit_profil.php" method="GET">
                        <?php
                            if ($db === false){
                                die("ERROR: Could not connect. " . mysqli_connect_error());
                            }
                            echo '<tbody>';
                            
                                while ($row = $result->fetch_array()){
                                    echo '<tr>';
                                        echo '<td class="align-middle p-4 w-25" style="word-wrap: break-word; max-width: 90px;">' . $row['username'] . '</td>';
                                        //echo '<td class="align-middle p-4" style="word-wrap: break-word; max-width: 85px;">' . $row['e_mail'] . '</td>';
                                        echo '<td class="align-middle p-4 w-25" style="word-wrap: break-word; max-width: 90px;">' . $row['phone'] . '</td>';
                                        $id_user_row = $row['id'];
                                        
                                        $sql_hours = 
                                        "SELECT 
                                            username,
                                            c.id AS chantier_id,
                                            #c.created as date_chantier,
                                            #concat(year(g.created),
                                            #month(g.created),
                                            #week(g.created)),
                                            #c.name AS name_chantier,
                                            u.id AS `user_id`,
                                            #if (SUM(intervention_hours) > 80000, SUM(intervention_hours) - 80000, NULL) as \'> 80000\',
                                            #if (SUM(intervention_hours)-40000 > 0,if( SUM(intervention_hours) -40000>30000,30000,SUM(intervention_hours) - 40000), NULL) as \'> 40000\',
                                            SUM(intervention_hours) AS totalheure
                                            #SUM(night_hours) AS maj50
                                        FROM
                                            chantiers AS c
                                            JOIN
                                            global_reference AS g ON c.id = chantier_id
                                            JOIN
                                            users AS u ON g.user_id = u.id
                                        WHERE
                                            username = '" . $row['username'] . "'
                                        GROUP BY username , c.id , u.id WITH ROLLUP";
                                        
                                        if ($result_hours = mysqli_query($db, $sql_hours)){
                                            if (mysqli_num_rows($result_hours) > 0){
                                                while ($row_hours = $result_hours->fetch_array()) {
                                                    if (!empty($row_hours['username']) and empty($row_hours['chantier_id']) /*and !empty($row_hours['user_id'])*/) {
                                                        $total = $row_hours['totalheure'];
                                                        $hours = (int)($total / 10000);
                                                        $minutes = ((int)($total - ($hours * 10000)) / 100);
                                                        if ($minutes > 10) {
                                                            $minutes = $minutes;
                                                        } elseif ($minutes < 10 and $minutes > 0) {
                                                            $minutes = "0" . $minutes;
                                                        } else {
                                                            $minutes = "00";
                                                        }
                                                        echo '<td class="align-middle p-4 w-25" style="word-wrap: break-word; max-width: 90px;">' . $hours . ':' . $minutes . '</td>';
                                                    }
                                                }
                                            } else {
                                                echo '<td class="align-middle p-4 w-25" style="word-wrap: break-word; max-width: 90px;">00:00</td>';
                                            }
                                        } else {
                                            echo "ERROR: Could not able to execute $result_hours. " . mysqli_error($db);
                                        }



                                        if ($_SESSION['id'] == $admin['id']) {
                                            echo '<td class="p-0 align-middle w-25>';
                                            ?>
                                            <form action="api/user/delete_user.php" method="GET" >
                                                <div class="float-left pl-0" id="<?php echo $id_user_row; ?>" name="<?php echo $id_user_row; ?>" class="remove" onClick="reply_click_user(this.id)"><i class="fas fa-trash-alt"></i></div>
                                            </form>
                                            <div class="w-100 text-center"><a href="modif_profil.php?id=<?php echo $id_user_row; ?>"><i class="fas fa-tools mr-2"></i></a></div>

                                            <?php
                                            echo '</td>';
                                        } else {
                                            echo "<td class='p-0 align-middle w-25'><a href='modif_profil.php?id=" . $id_user_row . "'><i class='fas fa-tools'></i></a></td>";
                                        }
                                    echo '</tr>';
                                }
                            mysqli_free_result($result_hours);
                            mysqli_free_result($result);
                            echo '</tbody>';
                        } else {
                            echo "No records matching your query were found.";
                        }
                    } else{
                        echo "ERROR: Could not able to execute $sql. " . mysqli_error($db);
                    }
                        ?>
                    </table>
                </div>
                <?php
                    if ($_SESSION['id'] == $admin['id']) {
                        echo "<form class='pt-5 mt-5'>";
                            echo "<div class='pt-5 w-75 m-auto'>";
                                echo "<a href='add_profil.php' class='btn send border-0 bg-white z-depth-1a mt-2 mb-2 text-dark'>Ajouter un compte</a>";
                            echo "</div>";
                        echo "</form>";
                    } else {
                        echo "<div class='pt-5 mt-5'>";
                            echo "<div class='pt-5 w-75 m-auto'>";
                                echo "<a href='javascript:history.go(-1)' value='return' class='btn finish border-0 bg-white z-depth-1a mt-4 mb-3 text-dark'>Précédent</a>";
                            echo "</div>";
                        echo "</div>";
                    }
                    mysqli_close($db); 
                ?>
            </div>
        </div>
    </body>

    <footer>
    </footer>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="js/script.js"></script>
    <script src="js/bootstrap.js"></script>
</html>