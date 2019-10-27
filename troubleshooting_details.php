<?php
session_start();

include 'api/config/db_connexion.php';

if(!($_SESSION['username'])) {  
  
    header("Location: signin.php");//redirect to login page to secure the welcome page without login access.  
}

$sql = "SELECT 
            c.id AS chantier_id,
            c.created as date_chantier,
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
            chantier_id = '" . $_GET['chantier_id'] . "'
            #g.created BETWEEN \'2019-10-01\' AND \'2019-11-30\'
        GROUP BY c.id , u.id , c.created , username , c.name with ROLLUP";#, concat(year(g.created) , month(g.created), week(g.created));
?>

<!DOCTYPE html>

<html class="overflow-hidden">
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
            <div class="content">
                <?php
                    
                    
                    if($result = mysqli_query($db, $sql)) {
                        if (mysqli_num_rows($result) > 0) {

                            $flag = 0;

                            while ($row = $result->fetch_array()) {
                                if( $db === false){
                                    die("ERROR: Could not connect. " . mysqli_connect_error());
                                }

                                if (!empty($row['date_chantier'])) {
                                    $created = date_create($row['date_chantier']);
                                }

                                if (empty($row['user_id']) and empty($row['chantier_id']) and empty($row['name_chantier']) and $flag == 0) {
                                    echo '<h3 class="text-center mt-0 mb-3 pt-5">Détails du chantier</h3>';
                                    echo "<h5 class='text-center mt-2'>" . $row['name_chantier'] . "</h5>";
                                    echo '<table class="table table-striped mt-5 ml-auto mb-5 mr-auto w-75 text-center">';
                                    echo "<thead>
                                            <tr>
                                                <th scope='col' class='align-middle text-center w-50'>Date de création</th>
                                                <th scope='col' class='align-middle text-center w-50'>Temps d'intervention réalisé</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class='align-middle bg-white p-1'>" . date_format($created, 'd-M-Y') . "</td>
                                                <td class='align-middle bg-white p-1'>";
                                                $total = $row['totalheure'];
                                                $hours = (int)($total / 10000);
                                                $minutes = (int)($total - ($hours * 10000)) / 100;
                                                echo $hours;
                                                echo ":";
                                                if ($minutes > 10) {
                                                    echo $minutes;
                                                } elseif ($minutes < 10 and $minutes > 0) {
                                                    echo "0";
                                                    echo $minutes;
                                                } else {
                                                    echo "00";
                                                }
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
                            echo "Chantier programmé pour des horaires à venir.";
                        }
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

                                            if (!empty($row['username']) and !empty($row['name_chantier'])){
                                                echo "<tr>";
                                                    echo "<td class='align-middle p-1 w-25' style='word-wrap: break-word; max-width: 85px;'>" . $row['username'] . "</td>";
                                                    echo "<td class='align-middle p-1 w-25' style='word-wrap: break-word; max-width: 85px;'>";
                                                        $total = $row['totalheure'];
                                                        $hours = (int)($total / 10000);
                                                        $minutes = (int)($total - ($hours * 10000)) / 100;
                                                        echo $hours;
                                                        echo ":";
                                                        if ($minutes > 10) {
                                                            echo $minutes;
                                                        } elseif ($minutes < 10 and $minutes > 0) {
                                                            echo "0";
                                                            echo $minutes;
                                                        } else {
                                                            echo "00";
                                                        }
                                                    echo "</td>";
                                                    echo "<td class='align-middle p-1 w-25'><a href='list_profil.php'><i class='fas fa-tools align-middle'></i></a></td>";
                                                echo "</tr>";
                                            }
                                        }
                                    echo "</tbody>";
                                ;}
                            }
                        ?>
                    </table>
                </div>
                <div class="ml-auto mr-auto mt-5 w-75">
                    <a href="#" type="submit" value="valid" class="btn send border-0 bg-white z-depth-1a mt-3 mb-4 text-dark">Modifier</a>
                    <a href="troubleshooting_list.php" type="submit" value="return" class="btn finish border-0 bg-white z-depth-1a mt-1 mb-4">Précédent</a>
                </div>
            </div>
        </div>
    </body>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="js/script.js"></script>
    <script src="js/bootstrap.js"></script>
</html>