<?php
session_start();

include 'api/config/db_connexion.php';

if (isset($_COOKIE['id'])) {
    $auth = explode('---', $_COOKIE['id']);
 
    if (count($auth) === 2) {
        $req = $bdd->prepare('SELECT id, username, `password` FROM users WHERE id = :id');
        $req->execute([ ':id' => $auth[0] ]);
        $user = $req->fetch(PDO::FETCH_ASSOC);
         
        if ($user && $auth[1] === hash('sha512', $user['username'].'---'.$user['password'])) {
            // Ce que tu avais mis pour ta session à la connection
            $_SESSION['id'] = $user['id'];
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
         
        if ($admin && $auth[1] === hash('sha512', $admin['admin_name'].'---'.$user['admin_pass'])) {
            // Ce que tu avais mis pour ta session à la connection
            $_SESSION['id'] = $admin['id'];
            $_SESSION['username'] = "admin";
        } else {
            header("Location: signin.php");//redirect to login page to secure the welcome page without login access.  
        }
    }
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


$sql = "SELECT 
            c.id AS chantier_id,
            c.num_chantier AS num_chantier,
            c.created as date_chantier,
            g.updated as inter_chantier,
            #concat(year(g.created),
            #month(g.created),
            #week(g.created)),
            c.name AS name_chantier,
            username,
            u.id AS user_id,
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
            chantier_id = '" . $_GET['id'] . "'
            #g.created BETWEEN \'2019-10-01\' AND \'2019-11-30\'
        GROUP BY c.id , num_chantier , username , u.id , c.created , g.updated , c.name with ROLLUP";#, concat(year(g.created) , month(g.created), week(g.created));
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
                            <li class="bg-dark border-top border-bottom border-warning rounded-0 p-0 menu-link"><a href="signin.php" class="text-warning">Déconnexion</a></li>
                        </ul>
                    </div>
                </nav>
                <div class="icons-navbar">
                    <div class="menu-btn-bars text-white"><button class="menu-btn fas fa-bars text-warning w-100 fa-3x p-0"></button></div>
                    <a href="index.php" class="text-warning m-auto"><h2 class="m-0">S.K.elec</h2></a>
                    <a href="troubleshooting_list.php" class="text-white pl-3"><i class="menu-btn-plus fas fa-search text-warning fa-3x rounded-circle"></i></a>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div id="container">
            <div class="content pt-0 overflow-hidden">
                <?php
                    if($result = mysqli_query($db, $sql)) {
                        if (mysqli_num_rows($result) > 0) {

                            $flag = 1;

                            if ($db === false) {
                                die("ERROR: Could not connect. " . mysqli_connect_error());
                            }

                            echo '<h3 class="text-center mt-0 mb-3 pt-5">Détails du chantier</h3>';
                            
                            while ($row = $result->fetch_array()) {
                                if (!empty($row['date_chantier'])) {
                                    $created = date_create($row['date_chantier']);
                                }

                                if ($flag == 1 and !empty($row['name_chantier'])) {
                                    echo "<h5 scope='col' class='align-middle text-center'>" . $row['name_chantier'] . "</h5>";
                                    echo '<a href="commit_list.php?id=' . $_GET['id'] . '" scope="col" class="btn send border-0 bg-white z-depth-1a text-dark float-right mr-5 mr-5 pt-1 pr-2 pb-1 pl-2" style="width: 110px;">Commentaires</a>';
                                    if (!empty($row['num_chantier'])) {
                                        echo '<div class="w-25 text-center mt-4 ml-auto mb-4 mr-auto">';
                                            echo "<h5 class='w-50 text-center mt-2 ml-auto mr-auto'>" . $row['num_chantier'] . "</h5>";
                                        echo '</div>';
                                    }
                                    $flag = 0;
                                } else {
                                    $flag = 0;
                                }
                                
                                if (empty($row['user_id']) and empty($row['chantier_id']) and empty($row['name_chantier']) and $flag == 0) {
                                    echo '<table class="table table-striped mt-4 ml-auto mb-5 mr-auto w-75 text-center">';
                                    echo "<thead>
                                            <tr>
                                                <th scope='col' class='align-middle text-center w-50'>Date de création</th>
                                                <th scope='col' class='align-middle text-center w-50'>Totalité des heures</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class='align-middle p-1'>" . date_format($created, 'd-M-Y') . "</td>
                                                <td class='align-middle p-1'>";
                                                $total = $row['totalheure'];
                                                $hours = (int)($total / 10000);
                                                $minutes = (int)($total - ($hours * 10000)) / 100;
                                                if ($minutes > 59) {
                                                    $hours += 1;
                                                    $minutes -= 60;
                                                }
                                                if ($minutes > 10) {
                                                    $minutes = $minutes;
                                                } elseif ($minutes < 10 and $minutes > 0) {
                                                    $minutes = "0" . $minutes;
                                                } else {
                                                    $minutes = "00";
                                                }
                                                echo $hours . ':' . $minutes;
                                                echo "</td>
                                            </tr>
                                        </tbody>
                                    </table>";

                                    $flag = 1;

                                ;}
                            }
                            echo "<table class='table table-striped border ml-auto mb-3 mr-auto w-75 text-center'>";
                                echo "<thead>
                                    <tr>
                                        <th scope='col' class='text-center border-right align-middle w-25'>Salarié(s) sur le chantier</th>
                                        <th scope='col' class='text-center border-right align-middle w-25'>Nombre d'heures correspondant</th>
                                        <th scope='col' class='text-center align-middle w-25'>Détails</th>
                                    </tr>
                                </thead>
                            </table>";
                        } else {
                            $no_hours = "SELECT * FROM chantiers WHERE id =" . $_GET['id'];
                            if($reponse = mysqli_query($db, $no_hours)) {
                                if (mysqli_num_rows($reponse) > 0) {
                                    
                                    while ($chant = $reponse->fetch_array()) {
                                        if (!empty($chant['created'])) {
                                            $created = date_create($chant['created']);
                                        }

                                        echo '<table class="table table-striped mt-5 ml-auto mb-5 mr-auto w-50 text-center">';
                                            echo "<thead>
                                                <tr>
                                                    <th scope='col' class='align-middle text-center w-50'>Date de création</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class='align-middle bg-white p-1'>" . date_format($created, 'd-M-Y') . "</td>
                                                </tr>
                                            </tbody>
                                        </table>";
                                        echo "<div class='h6 m-auto text-center w-75'>Chantier programmé pour des horaires à venir.</div>";
                                        echo "<div class='ml-auto mr-auto mt-5 w-75'>
                                            <a href='modif_troubleshooting.php?id=" . $chant['id'] . "' class='btn send border-0 bg-white z-depth-1a mt-3 mb-4 text-dark'>Modifier</a>
                                            <a href='troubleshooting_list.php' value='return' class='btn finish border-0 bg-white z-depth-1a mt-1 mb-4'>Précédent</a>
                                        </div>";
                                    }
                                    mysqli_free_result($reponse);
                                } else {
                                    echo "No records matching your query were found.";
                                }
                            } else {
                                echo "ERROR: Could not able to execute $no_hours. " . mysqli_error($db);
                            }
                        }
                        mysqli_free_result($result);
                    }
                
                ?>
                <div class="container-list-details m-auto">
                    <table class="table table-striped border ml-auto mb-3 mr-auto w-75 text-center">
                        <?php
                            if($result = mysqli_query($db, $sql)) {
                                if (mysqli_num_rows($result) > 0) {
                                    echo "<tbody>";
                                        while ($row = $result->fetch_array()) {

                                            if( $db === false){
                                                die("ERROR: Could not connect. " . mysqli_connect_error());
                                            }

                                            if (!empty($row['user_id'])) {
                                                $id_user = $row['user_id'];
                                            }

                                            if (!empty($row['username']) and empty($row['name_chantier']) and empty($row['inter_chantier']) and empty($row['user_id'])){
                                                echo "<tr>";
                                                    echo "<td class='align-middle p-1 w-25' style='word-wrap: break-word; max-width: 85px;'>" . $row['username'] . "</td>";
                                                    echo "<td class='align-middle p-1 w-25' style='word-wrap: break-word; max-width: 85px;'>";
                                                        $total = $row['totalheure'];
                                                        $hours = (int)($total / 10000);
                                                        $minutes = (int)($total - ($hours * 10000)) / 100;
                                                        if ($minutes > 59) {
                                                            $hours += 1;
                                                            $minutes -= 60;
                                                        }
                                                        if ($minutes > 10) {
                                                            $minutes = $minutes;
                                                        } elseif ($minutes < 10 and $minutes > 0) {
                                                            $minutes = "0" . $minutes;
                                                        } else {
                                                            $minutes = "00";
                                                        }
                                                        echo $hours . ':' . $minutes;
                                                    echo "</td>";
                                                    echo "<td class='align-middle p-1 w-25'><a href='modif_profil.php?id=" . $id_user . "'><i class='fas fa-tools align-middle'></i></a></td>";
                                                    
                                                echo "</tr>";
                                            }
                                        }
                                    mysqli_free_result($result);
                                    echo "</tbody>";
                        ?>
                    </table>
                    <?php
                    ?>
                </div>
                <div class="ml-auto mr-auto mt-2 w-75">
                    <a href="modif_troubleshooting.php?id=<?php echo $_GET['id']; ?>" class="btn send border-0 bg-white z-depth-1a mt-3 mb-4 text-dark">Modifier</a>
                    <a href="troubleshooting_list.php" value="return" class="btn finish border-0 bg-white z-depth-1a mt-1 mb-1">Précédent</a>
                </div>
                            <?php
                                }
                            } else {
                                echo "ERROR: Could not able to execute $sql. " . mysqli_error($db);
                            }
                            mysqli_close($db);
                            ?>
            </div>
        </div>
    </body>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="js/script.js"></script>
    <script src="js/bootstrap.js"></script>
</html>