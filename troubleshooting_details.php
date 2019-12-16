<?php

include 'auth.php';

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

<html class="overflow-y ml-auto mr-auto mb-0">
    
            <?php include 'header.php'; ?>
            
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
                    if ($result = mysqli_query($db, $sql)) {
                        if (mysqli_num_rows($result) > 0) {

                            $flag = 1;

                            if ($db === false) {
                                die("ERROR: Could not connect. " . mysqli_connect_error());
                            }

                            echo '<div class="pt-5 pb-3 mt-4 ml-auto mr-auto">
                                <h3 class="text-center mb-5 pt-5">Détails du chantier</h3>
                            </div>';
                            
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
                                    } else {
                                        echo '<div class="w-25 text-center mt-4 ml-auto mb-4 mr-auto" style="height: 15.4px;"></div>';
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
    
<?php include 'footer.php'; ?>