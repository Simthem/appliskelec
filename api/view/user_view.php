<?php

function request_hours($id) {
    
    include 'auth.php';
    
    $month_sql = "SELECT
        concat(year(g.updated)) AS `year`,
        concat(month(g.updated)) AS `month`,
        concat(week(g.updated)) AS `week`,
        concat(day(g.updated)) AS `day`,
        g.updated AS inter_chantier,
        u.id AS `user_id`,
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
        users AS u ON g.user_id = u.id
    WHERE
        u.id = '" . $id . "'
    GROUP BY concat(year(g.updated)) DESC, concat(month(g.updated)) DESC, concat(week(g.updated)) DESC , g.updated DESC, u.id , intervention_hours, night_hours  with ROLLUP";


    if (isset($month_sql) && !empty($month_sql)) {
        return $month_sql;
    } else {
        return false;
    }
}


function week($id) {

    include 'auth.php';
    include './api/view/hours_view.php';
    
    if ($month_sql = request_hours($id)) {

        if ($result = mysqli_query($db, $month_sql)) {

            if ($db === false){
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
            if ($result = mysqli_query($db, $month_sql)) {

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
                            }
                        }
                    mysqli_free_result($result);
                } else {
                    echo "Cette personne n'a pas encore effectué d'heure pour le moment.2";
                }
            mysqli_close($db);
            }

        echo '</table>';
        if (isset($absence) && !empty($absence)) {
            echo '<div class="small text-right">Heure(s) d\'absence(s) = ' . $absence . ' h</div>';
        }
    echo '</div>';
}



function month($id) {

    include 'auth.php';

    $month_sql = request_hours($id);

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

function select_u($id) {

    include_once 'auth.php';

    if (isset($id) && !empty($id)) {
        $sql = "SELECT 
            id, username, phone 
        FROM 
            users
        WHERE
            id = '" . $id . "'";
    } else {
        $sql = "SELECT 
            id, username, phone 
        FROM 
            users";
    }

    if (isset($sql) && !empty($sql)) {
        return $sql;
    } else {
        return false;
    }
}

function user_list($id) {

    include 'auth.php';

    if ($id != 0) {
        $sql = select_u($id);
        if ($reponse = mysqli_query($db, $sql)) {
            if (mysqli_num_rows($reponse) > 0) {
                while ($chk = $reponse->fetch_array()) {
                    $name_u = $chk['username'];
                }
                return $name_u;
            }
        }
    } else {
        $sql = select_u(0);

        if ($result = mysqli_query($db, $sql)) {
            if (mysqli_num_rows($result)) {
                if ($db === false) {
                    die("ERROR: Could not connect. " . mysqli_connect_error());
                }


                while ($user = $result->fetch_array()) {
                    echo '<option>' . $user['username'] . '</option>';
                }

                mysqli_free_result($result);
            } else {
                echo "Aucun résultat ne correspond à votre requête.";
            }
        } else {
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($db);
        }
    }
}
?>