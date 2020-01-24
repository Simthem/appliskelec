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

function res_troubles($chantier_name) {
    include_once 'auth.php';

    $sql = "SELECT 
        `name`,
        chantier_id,
        user_id,
        #e_mail,
        updated
    FROM 
        global_reference AS g
        JOIN
        chantiers AS c ON g.chantier_id = c.id
    WHERE
        `name` = '" . $chantier_name . "'
    GROUP BY
        user_id, updated DESC, `name`, chantier_id";

    if (isset($sql) && !empty($sql)) {
        return $sql;
    } else {
        return false;
    }
}

function res_us($chk_id, $chantier_name) {

    include_once 'auth.php';

    //echo 'pouet<br />' . $chantier_name . '<br />';
    if ($chantier_name != null) {
        $sql = "SELECT 
                user_id,
                chantier_id,
                #e_mail,
                updated,
                `name`
            FROM 
                global_reference AS g
                JOIN
                chantiers AS c ON g.chantier_id = c.id
            WHERE
                user_id = '" . $chk_id . "'
                AND
                `name` = '" . $chantier_name . "'
            GROUP BY
                updated DESC, user_id, chantier_id, `name`";
    } else {
        $sql = "SELECT 
                user_id,
                chantier_id,
                #e_mail,
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
    }

    if (isset($sql) && !empty($sql)) {
        return $sql;
    } else {
        return false;
    }
}

function res_date($date, $chk_id, $chantier_name) {

    include_once 'auth.php';

    echo '<br />' . $date;
    //echo '<br />' . $between;
    echo '<br />' . $chk_id;
    echo '<br />' . $chantier_name;
    if ($chk_id != null && $chantier_name != null) {
        $sql = "SELECT 
                user_id,
                chantier_id,
                #e_mail,
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
                AND
                `name` = '" . $chantier_name . "'
            GROUP BY
                updated DESC, user_id, chantier_id, `name`";
    } elseif ($chk_id != null && $chantier_name == null) {
        $sql = "SELECT 
                user_id,
                chantier_id,
                #e_mail,
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
    } elseif ($chk_id == null && $chantier_name != null) {
        $sql = "SELECT 
                user_id,
                chantier_id,
                #e_mail,
                updated,
                `name`
            FROM 
                global_reference AS g
                JOIN
                chantiers AS c ON g.chantier_id = c.id
            WHERE
                updated = '" . $date . "'
                AND
                `name` = '" . $chantier_name . "'
            GROUP BY
                updated DESC, user_id, chantier_id, `name`";
    } else {

        echo 'ta race de chien';
        $sql = "SELECT 
                user_id,
                chantier_id,
                #e_mail,
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


function between($date, $between, $chk_id, $chantier_name) {

    include_once 'auth.php';

    if (isset($chk_id) && !empty($chk_id) && isset($chantier_name) && !empty($chantier_name)) {
        $sql = "SELECT 
                user_id,
                chantier_id,
                #e_mail,
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
                AND
                `name` = '" . $chantier_name . "'
            GROUP BY
                updated DESC, user_id, chantier_id, `name`";

    } elseif (isset($chk_id) && !empty($chk_id)) {
        $sql = "SELECT 
                user_id,
                chantier_id,
                #e_mail,
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
                user_id, updated DESC, chantier_id, `name`";

    } elseif (isset($chantier_name) && !empty($chantier_name)) {
        $sql = "SELECT 
                user_id,
                chantier_id,
                #e_mail,
                updated,
                `name`
            FROM 
                global_reference AS g
                JOIN
                chantiers AS c ON g.chantier_id = c.id
            WHERE
                updated BETWEEN '" . $date . "' AND '" . $between . "'
                AND
                `name` = '" . $chantier_name . "'
            GROUP BY
                updated DESC, user_id, chantier_id, `name`";
    } else {
        $sql = "SELECT 
                user_id,
                chantier_id,
                #e_mail,
                updated,
                `name`
            FROM 
                global_reference AS g
                JOIN
                chantiers AS c ON g.chantier_id = c.id
            WHERE
                updated BETWEEN '" . $date . "' AND '" . $between . "'
            GROUP BY
                user_id, updated DESC, chantier_id, `name`";
    }
    
    if (isset($sql) && !empty($sql)) {
        return $sql;
    } else {
        return false;
    }
}

function select_inter($date, $between, $chk_id, $chantier_name) {

    include_once 'auth.php';
    
    echo 'enter<br />';
    echo $date . '<br />';
    echo $between . '<br />';
    echo $chantier_name . '<br />';
    if ($date != 0 && (empty($between) || $between == 0)) {
        echo 'first in select<br />';
        $sql = res_date($date, $chk_id, $chantier_name);
    } elseif ($date != 0 && $between != 0) {
        echo 'second in select<br />';
        $sql = between($date, $between, $chk_id, $chantier_name);
    } elseif ($chk_id != null && $chantier_name != null) {
        $sql = res_us($chk_id, $chantier_name);
    } elseif ($chantier_name == null) {
        /*echo '<br />' . $date;
        echo '<br />' . $between;
        echo '<br />' . $chk_id;
        echo '<br />' . $chantier_name;
        echo '<br />c\'est ok';*/
        $sql = res_us($chk_id, 0);
    } elseif (!empty($chantier_name)) {
        $sql = res_troubles($chantier_name);
        //echo '<br />ta race 2';
    } else {
        //echo '<br />ta race 2';
        return false;
    }
    echo $chantier_name;
    if (isset($sql) && !empty($sql)) {
        print_r($sql);
        return $sql;
    } else {
        return false;
    }
}


function list_inter($date, $between, $chk_id, $chantier_name) {

    include 'auth.php';
    include_once 'user_view.php';
    include_once 'admin_view.php';


    /*echo '<br />' . $date;
    echo '<br />' . $between;*/
    //echo '<br />' . $chk_id;
    //echo '<br />' . $chantier_name;
    //  SEE CONDITION WITH 'WINDOW.LOCATION.PATHNAME' FOR EXTRACT REQUEST


    if (($between != 0 && explode('/', $between) > explode('/', $date) && $date != 0) || ($between == 0 && $date != 0)) {
        if ($chk_id == 0 && (isset($chantier_name) && !empty($chantier_name))) {
            $list = select_inter($date, $between, 0, $chantier_name);
            //echo 'first';
        } elseif ($chantier_name == 0 && $chk_id == 0) {
            //echo '<br />' . $chantier_name . '<br />';
            $list = select_inter($date, $between, 0, 0);
            //echo 'second';
        } else {
            $list = select_inter($date, $between, $chk_id, $chantier_name);
            //echo 'third';
        }
    } elseif ($chk_id != null && $chantier_name != null) {
        //echo 'c\'est ok';
        $list = select_inter(0, 0, $chk_id, $chantier_name);
    } elseif ($chk_id != null && $chantier_name == null) {
        //echo 'pas ok ertyuiklkjhgfdfghjkjhgfdfghjkl;lkjhgfdfghjkl;';
        $list = select_inter(0, 0, $chk_id, 0);
    } elseif ($chk_id == null && $chantier_name != null) {
        //echo 'ca va finir par fonctionner bordel';
        $list = select_inter(0, 0, 0, $chantier_name);
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