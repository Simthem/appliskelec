<?php

function select_inter_a($date) {

    include_once 'auth.php';
    
    $sql = "SELECT 
        *
    FROM 
        appli_skelec.global_reference 
    WHERE
        updated = '" . $date . "'";
    
    if (isset($sql) && !empty($sql)) {
        return $sql;
    } else {
        return false;
    }
}

function res_us($chk_id) {

    include_once 'auth.php';

    $sql = "SELECT 
            user_id,
            chantier_id,
            e_mail,
            updated,
            `name`
        FROM 
            global_reference AS g
            JOIN
            chantiers AS c ON g.chantier_id = c.id
        WHERE
            user_id = '" . $chk_id . "'
        GROUP BY
            updated DESC, user_id, chantier_id, `name`";

    if (isset($sql) && !empty($sql)) {
        return $sql;
    } else {
        return false;
    }
}

function res_date($date, $chk_id) {

    include_once 'auth.php';

    if (isset($chk_id) && !empty($chk_id) && $chk_id != 0) {
        $sql = "SELECT 
                user_id,
                chantier_id,
                e_mail,
                updated,
                `name`
            FROM 
                global_reference AS g
                JOIN
                chantiers AS c ON g.chantier_id = c.id
            WHERE
                updated = '" . $date . "'
                AND
                user_id = '" . $chk_id . "'
            GROUP BY
                updated DESC, user_id, chantier_id, `name`";
    } else {
        $sql = "SELECT 
                user_id,
                chantier_id,
                e_mail,
                updated,
                `name`
            FROM 
                global_reference AS g
                JOIN
                chantiers AS c ON g.chantier_id = c.id
            WHERE
                updated = '" . $date . "'
            GROUP BY
                updated DESC, user_id, chantier_id, `name`";
    }

    if (isset($sql) && !empty($sql)) {
        return $sql;
    } else {
        return false;
    }
}


function between($date, $between, $chk_id) {

    include_once 'auth.php';

    if (isset($chk_id) && !empty($chk_id) && $chk_id != 0) {
        $sql = "SELECT 
                user_id,
                chantier_id,
                e_mail,
                updated,
                `name`
            FROM 
                global_reference AS g
                JOIN
                chantiers AS c ON g.chantier_id = c.id
            WHERE
                updated BETWEEN '" . $date . "' AND '" . $between . "'
                AND 
                user_id = '" . $chk_id . "'
            GROUP BY
                updated DESC, user_id, chantier_id, `name`";
    } else {
        $sql = "SELECT 
                user_id,
                chantier_id,
                e_mail,
                updated,
                `name`
            FROM 
                global_reference AS g
                JOIN
                chantiers AS c ON g.chantier_id = c.id
            WHERE
                updated BETWEEN '" . $date . "' AND '" . $between . "'
            GROUP BY
                updated DESC, user_id, chantier_id, `name`";
    }
    
    if (isset($sql) && !empty($sql)) {
        return $sql;
    } else {
        return false;
    }
}

function select_inter($date, $between, $chk_id) {

    include_once 'auth.php';
    
    if ($date != 0 && (empty($between) || $between == 0)) {
        $sql = res_date($date, $chk_id);
    } else if ($date != 0 && $between != 0) {
        $sql = between($date, $between, $chk_id);
    } else if ($chk_id != 0) {
        $sql = res_us($chk_id);
    } else {
        return false;
    }
    
    if (isset($sql) && !empty($sql)) {
        return $sql;
    } else {
        return false;
    }
}


function list_inter($date, $between, $chk_id) {

    include 'auth.php';
    include_once 'user_view.php';
    include_once 'admin_view.php';


    //  SEE CONDITION WITH 'WINDOW.LOCATION.PATHNAME' FOR EXTRACT REQUEST


    if (($between != 0 && explode('/', $between) > explode('/', $date) && $date != 0) || ($between == 0 && $date != 0)) {
        if (!isset($chk_id) || empty($chk_id) || $chk_id == 0) {
            $list = select_inter($date, $between, 0);
        } else {
            $list = select_inter($date, $between, $chk_id);
        }
    } elseif (isset($chk_id) && !empty($chk_id)) {
        $list = select_inter(0, 0, $chk_id);
    } 
    else {
        echo '<script>alert("Une erreur est présente dans la demande de date(s). Veuillez vérifier le bon ordre des informations demandées.")</script>';
        return false;
    }
    
    if ($result = mysqli_query($db, $list)) {

        if ($db === false) {
            die("ERROR: Could not connect. " . mysqli_connect_error());
        }

        if (mysqli_num_rows($result) > 0) {
            echo '<table class="table table-striped mt-0 ml-0 mb-0 text-center" style="height: 50px;">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th scope="col" class="text-center align-middle p-2 w-25" id="username">Nom</th>';
                        echo '<th scope="col" class="text-center align-middle p-2 w-25" id="name">Libellés</th>';
                        echo '<th scope="col" class="text-center align-middle p-2 w-25" id="date">Date</th>';
                        echo '<th scope="col" class="text-center align-middle p-0 w-25" id="">Détails</th>';
                    echo '</tr>';
                echo '</thead>';
            echo '</table>
            <div class="tab-content">
                <div class="container-list m-auto tab-pane active in">
                    <table class="table table-striped pr-4 pl-4 mt-3 ml-auto mr-auto text-center" action="" method="POST">
                        <tbody>';
                        
                        $temp_id = 0;

                        while ($row = $result->fetch_array()) {

                            echo '<tr>';

                            $username_u = user_list($row['user_id']);

                            if (isset($username_u) && !empty($username_u)) {
                                $username_a = 0;
                            } else {
                                $username_a = admin_list($row['user_id']);
                            }
                            
                            if (isset($row) && !empty($row)) {
                                if (isset($temp_id) && $temp_id != $row['user_id']) {
                                    
                                    $temp_id = $row['user_id'];

                                    if (isset($username_u) && !empty($username_u) && $username_a == 0) {
                                        echo '<td class="align-middle w-25" style="word-wrap: break-word; max-width: 85px;"><a href="modif_profil.php?id=' . $temp_id . '">' . $username_u . '</a></td>';
                                    } elseif (isset($username_a) && !empty($username_a)){
                                        echo '<td class="align-middle w-25" style="word-wrap: break-word; max-width: 85px;"><a href="modif_profil.php?id=' . $temp_id . '">' . $username_a . '</a></td>';
                                    }
                                    
                                    echo '<td class="align-middle w-25" style="word-wrap: break-word; max-width: 85px; padding: 0 !important;">' . $row['name'] . '</td>';
                                    echo '<td class="align-middle w-25" style="word-wrap: break-word; max-width: 85px;">' . $row['updated'] . '</td>';
                                    echo '<td class="align-middle w-25" style="word-wrap: break-word; max-width: 85px;">';
                                    echo '<div class="w-100 text-center"><a href="troubleshooting_details.php?id=' . $row['chantier_id'] . '"><i class="fas fa-tools mr-2"></i></a></div>
                                    </td>';
                                } elseif ($temp_id == $row['user_id']) {
                                    echo '<td class="align-middle w-25" style="word-wrap: break-word; max-width: 85px;"></td>';
                                    echo '<td class="align-middle w-25" style="word-wrap: break-word; max-width: 85px; padding: 0 !important;">' . $row['name'] . '</td>';
                                    echo '<td class="align-middle w-25" style="word-wrap: break-word; max-width: 85px;">' . $row['updated'] . '</td>';
                                    echo '<td class="align-middle w-25" style="word-wrap: break-word; max-width: 85px;">';
                                    echo '<div class="w-100 text-center"><a href="troubleshooting_details.php?id=' . $row['chantier_id'] . '"><i class="fas fa-tools mr-2"></i></a></div>
                                    </td>';
                                }
                            }
                            echo '</tr>';
                        }
                    echo '</tbody>
                    </table>
                </div>
            </div>';
            mysqli_free_result($result);

            echo '<div class="w-75 mt-3 ml-auto mb-3 mr-auto pb-5"></div>';
            //<input type="return" value="Soumettre" class="btn send border-0 bg-white z-depth-1a mt-4 mb-0 align-middle text-dark" />
        } else {
            echo "<div class='w-100 pb-5 mb-5 text-center'>Aucun résultat ne correspond à votre requête.</div>";
        }
    }
}
?>