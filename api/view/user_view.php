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
        SUM(intervention_hours) - SUM(night_hours) AS tot_glob,
        SUM(absence) AS absence,
        if ((SUM(intervention_hours) - SUM(night_hours)) - 35 > 0, if ((SUM(intervention_hours) - SUM(night_hours)) - 35 > 8, 8, (SUM(intervention_hours) - SUM(night_hours)) - 35), NULL) AS maj25,
        if ((SUM(intervention_hours) - SUM(night_hours)) > 43, if (SUM(night_hours) > 0, SUM(intervention_hours) - 43, (SUM(intervention_hours) - SUM(night_hours)) - 43), if ((SUM(intervention_hours) - SUM(night_hours)) < 43, if (SUM(night_hours) > 0, SUM(night_hours), NULL), NULL)) AS maj50
    FROM
        chantiers AS c
        JOIN
        global_reference AS g ON c.id = chantier_id
        JOIN
        users AS u ON g.user_id = u.id
    WHERE
        u.id = '" . $id . "'
    GROUP BY concat(year(g.updated)) DESC, concat(month(g.updated)) DESC, concat(week(g.updated)) DESC , g.updated DESC, u.id , intervention_hours, night_hours  with ROLLUP";

    return $month_sql;

}


function week($id) {

    include 'auth.php';
    include './api/view/hours_view.php';
    
    $month_sql = request_hours($id);

    if($result = mysqli_query($db, $month_sql)) {

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
                                
                                echo '<td class="small align-middle p-1 w-25" style="word-wrap: break-word;">';
                                    $total = $row['tot_glob'];
                                    echo $total;
                                echo '</td>';

                                calc_hours($total, $row['maj25'], $row['maj50'], $row['h_night_tot'], 0);  // Function called to calcul and display values
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
                            
                            if ($flag == 0 && empty($row['month'])) {
                                
                                $flag += 1;
        
                                echo '<tr>';
                                    
                                    echo '<td class="small align-middle p-1 w-25" style="word-wrap: break-word;">';
                                        $total = $row['tot_h'];
                                        echo $total;
                                    '</td>';

                                    if (isset($absence) && !empty($absence)) {
                                        calc_hours($total, $t_25, $t_50, $row['h_night_tot'], $absence);
                                    } else {
                                        calc_hours($total, $t_25, $t_50, $row['h_night_tot'], 0);  // Function called to calcul and display values
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
                echo '</tr>';
            echo '</tbody>';
            echo '<div class="small text-right">Heure(s) d\'absence(s) = ' . $absence . ' h</div>';
        } else {
                echo '</tr>';
            echo '</tbody>';
        }
    echo '</div>';
}

?>