<?php

function request_hours_a($id) {

    include 'auth.php';
    //include './api/view/hours_view.php';

    $month_admin = "SELECT
        concat(year(g.updated)) AS `year`,
        concat(month(g.updated)) AS `month`,
        concat(week(g.updated)) AS `week`,
        concat(day(g.updated)) AS `day`,
        g.updated AS inter_chantier,
        a.id AS `admin_id`,
        night_hours,
        intervention_hours,
        SUM(intervention_hours) AS tot_h,
        SUM(night_hours) AS h_night_tot,
        if (SUM(night_hours > 0), SUM(intervention_hours) - SUM(night_hours), SUM(intervention_hours)) AS tot_glob,
        SUM(absence) AS absence,
        if (SUM(night_hours), if ((SUM(intervention_hours) - SUM(night_hours)) - 35 > 0, if ((SUM(intervention_hours) - SUM(night_hours)) - 35 > 8, 8, (SUM(intervention_hours) - SUM(night_hours)) - 35), NULL), if (SUM(intervention_hours) - 35 > 0, if (SUM(intervention_hours) - 35 > 8, 8, SUM(intervention_hours) - 35), NULL)) AS maj25,
        if (SUM(night_hours), if ((SUM(intervention_hours) - SUM(night_hours)) > 43, if (SUM(night_hours) > 0, SUM(intervention_hours) - 43, (SUM(intervention_hours) - SUM(night_hours)) - 43), if ((SUM(intervention_hours) - SUM(night_hours)) < 43, if (SUM(night_hours) > 0, SUM(night_hours), NULL), NULL)), if (SUM(intervention_hours) > 43, SUM(intervention_hours) - 43, NULL)) AS maj50
    FROM
        chantiers AS c
        JOIN
        global_reference AS g ON c.id = chantier_id
        JOIN
        `admin` AS a ON g.user_id = a.id
    WHERE
        a.id = '" . $id . "'
    GROUP BY concat(year(g.updated)) DESC, concat(month(g.updated)) DESC, concat(week(g.updated)) DESC , g.updated DESC, a.id , intervention_hours, night_hours  with ROLLUP";

    if (isset($month_admin) && !empty($month_admin)) {
        return $month_admin;
    } else {
        return false;
    }
}


function week_a($id) {

    include 'auth.php';
    include './api/view/hours_view.php';
    
    if ($month_admin = request_hours_a($id)) {

        if($result = mysqli_query($db, $month_admin)) {

            if( $db === false){
                die("ERROR: Could not connect. " . mysqli_connect_error());
            }

            if (mysqli_num_rows($result) > 0) {
                
                $flag = 1;
                $t_25 = 0;
                $t_50 = 0;

                echo "<tbody>";

                    while ($row = $result->fetch_array()) {

                        if (!empty($row['week']) && empty($row['day']) && !empty($row['tot_h'])) {
                            
                            $t_25 += $row['maj25'];
                            $t_50 += $row['maj50'];

                            if ($flag <= 4) {
                                
                                $flag += 1;
                                
                                echo '<tr>';
                                    
                                    calc_hours($row['tot_glob'], $row['maj25'], $row['maj50'], $row['h_night_tot'], 0, 0);  // Function called to calcul and display values

                            }

                                echo '</tr>';
                        }
                        $absence = $row['absence'];
                    }
                echo '</tbody>';
                mysqli_free_result($result);
            } else {
                echo "Cette personne n'a pas encore effectué d'heure pour le moment.";
            }
        }
        echo '</table>
        </div>';
    }


    echo '<h4 class="w-75 mt-5 ml-auto mb-4 mr-auto pb-2 text-center border-bottom">Heures globales du mois</h4>';
    echo "<table class='table table-striped border mt-2 ml-auto mb-3 mr-auto w-100 text-center'>";
        echo "<thead>
            <tr>
                <th scope='col' class='text-center border-right align-middle w-25 font-weight-normal'>H/totales<br />[<strong><u>avec</u></strong> h/nuit]</th>
                <th scope='col' class='text-center border-right align-middle w-25 font-weight-normal'>H + 25%</th>
                <th scope='col' class='text-center align-middle w-25 font-weight-normal'>H + 50%</th>
            </tr>
        </thead>
    </table>";
    echo '<div class="m-auto pb-5">
        <table class="table table-striped border ml-auto mb-1 mr-auto pb-3 w-100 text-center">';
            if($result = mysqli_query($db, $month_admin)) {

                if( $db === false){
                    die("ERROR: Could not connect. " . mysqli_connect_error());
                }

                if (mysqli_num_rows($result) > 0) {
                    
                    echo "<tbody>";

                    $flag = 0;
                    
                        while ($row = $result->fetch_array()) {
                            
                            if ($flag == 0 && empty($row['week'])) {
                                
                                $flag += 1;
                                
                                echo '<tr>';

                                    if (isset($absence) && !empty($absence)) {
                                        calc_hours($row['tot_h'], $t_25, $t_50, $row['h_night_tot'], $absence, 1);
                                    } else {
                                        calc_hours($row['tot_h'], $t_25, $t_50, $row['h_night_tot'], 0, 0);  // Function called to calcul and display values
                                    }

                                echo '</tr>';
                            }
                        }
                    echo '</tbody>';
                    mysqli_free_result($result);
                } else {
                    echo "Cette personne n'a pas encore effectué d'heure pour le moment.";
                }
            mysqli_close($db);
            }
        echo '</table>';
        if (isset($absence) && !empty($absence)) {
            echo '<div class="small text-right">Heure(s) d\'absence(s) = ' . $absence . ' h</div>';
        }
    echo '</div>';
}


function month_a($id) {

    include 'auth.php';

    $month_sql = request_hours_a($id);

    $absence = 0;

    if ($result_hours = mysqli_query($db, $month_sql)){
        
        if (mysqli_num_rows($result_hours) > 0){

            $flag = 0;
            
            while ($row_hours = $result_hours->fetch_array()) {
                
                if (empty($row_hours['week']) && !empty($row_hours['month']) && $flag == 0) {
                    
                    if (isset($row_hours['absence']) && !empty($row_hours['absence'])) {
                        $absence += $row_hours['absence'];
                    }
                    
                    include_once './api/view/hours_view.php';
                    $rep = value_h($row_hours['tot_h'], $row_hours['maj25'], $row_hours['maj50'], $absence, 1);
                    
                    convert_h($rep[0]);
                    
                    $flag = 1;
                }
            }
        } else {
            echo '<td class="align-middle p-4 w-25" style="word-wrap: break-word; max-width: 90px;">00:00</td>';
        }
    } else {
        echo "ERROR: Could not able to execute $result_hours. " . mysqli_error($db);
    }
}

function select_a($id) {

    include_once 'auth.php';

    if (isset($id) && !empty($id)) {
        $sql_a = "SELECT
            id, admin_name, phone
        FROM
            `admin`
        WHERE
            id = '" . $id . "'";
    } else {
        $sql_a = "SELECT
            id, admin_name, phone
        FROM
            `admin`";
    }

    if (isset($sql_a) && !empty($sql_a)) {
        return ($sql_a);
    } else {
        return false;
    }
}


function admin_list($id) {

    include 'auth.php';
    
    if (isset($id) && !empty($id)) {

        $sql_a = select_a($id);

        if ($reponse = mysqli_query($db, $sql_a)) {
            if (mysqli_num_rows($reponse) > 0) {
                while ($chk = $reponse->fetch_array()) {
                    $name_a = $chk['admin_name'];
                }
            }
        }
        return $name_a;
    } else {

        $sql_a = select_a(0);

        if ($reponse = mysqli_query($db, $sql_a)) {
            if (mysqli_num_rows($reponse)) {
                if ($db === false) {
                    die("ERROR: Could not connect. " . mysqli_connect_error());
                }

                while ($admin = $reponse->fetch_array()) {
                    echo '<option>' . $admin['admin_name'] . '</option>';
                }

                mysqli_free_result($reponse);
            } else {
                echo "Aucun résultat ne correspond à votre requête.";
            }
        } else {
            echo "ERROR: Could not able to execute $sql_a. " . mysqli_error($db);
        }
    }
}
?>