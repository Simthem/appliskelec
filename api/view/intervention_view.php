<?php
function select_inter($date) {

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
        updated = '" . $date . "'
    GROUP BY
        user_id, chantier_id, name, updated, e_mail";
    
    if (isset($sql) && !empty($sql)) {
        return $sql;
    } else {
        return false;
    }
}


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


function list_inter($date) {

    include 'auth.php';
    include_once 'user_view.php';
    include_once 'admin_view.php';

    $list = select_inter($date);

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
                        echo '<th scope="col" class="text-center align-middle p-2 w-25" id="e_mail">E-mail</th>';
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
                                        echo '<td class="align-middle p-4 w-25" style="word-wrap: break-word; max-width: 85px;">' . $username_u . '</td>';
                                    } elseif (isset($username_a) && !empty($username_a)){
                                        echo '<td class="align-middle p-4 w-25" style="word-wrap: break-word; max-width: 85px;">' . $username_a . '</td>';
                                    }
                                    
                                    echo '<td class="align-middle p-4 w-25" style="word-wrap: break-word; max-width: 85px;">' . $row['name'] . '</td>';
                                    echo '<td class="p-0 align-middle w-25" style="word-wrap: break-word; max-width: 85px;">' . $row['e_mail'] . '</td>';
                                    echo '<td class="p-0 align-middle w-25" style="word-wrap: break-word; max-width: 85px;">';
                                    ?>
                                        <form action="api/user/delete_troubles.php" method="GET" class="m-0 p-0">
                                            <div class="float-left pl-0" id="<?php echo $row['chantier_id']; ?>" name="<?php echo $row['chantier_id']; ?>" onClick="reply_click_troubles(this.id)"><i class="fas fa-trash-alt"></i></div>
                                        </form>
                                        <div class="w-100 text-center"><a href="troubleshooting_details.php?id=<?php echo $row['chantier_id']; ?>"><i class="fas fa-tools mr-2"></i></a></div>
                                    <?php
                                    echo '</td>';
                                } elseif ($temp_id == $row['user_id']) {
                                    echo '<td class="align-middle p-4 w-25" style="word-wrap: break-word; max-width: 85px;"></td>';
                                    echo '<td class="align-middle p-4 w-25" style="word-wrap: break-word; max-width: 85px;">' . $row['name'] . '</td>';
                                    echo '<td class="p-0 align-middle w-25" style="word-wrap: break-word; max-width: 85px;">' . $row['e_mail'] . '</td>';
                                    echo '<td class="p-0 align-middle w-25" style="word-wrap: break-word; max-width: 85px;">';
                                    ?>
                                        <form action="api/user/delete_troubles.php" method="GET" class="m-0 p-0">
                                            <div class="float-left pl-0" id="<?php echo $row['chantier_id']; ?>" name="<?php echo $row['chantier_id']; ?>" onClick="reply_click_troubles(this.id)"><i class="fas fa-trash-alt"></i></div>
                                        </form>
                                        <div class="w-100 text-center"><a href="troubleshooting_details.php?id=<?php echo $row['chantier_id']; ?>"><i class="fas fa-tools mr-2"></i></a></div>
                                    <?php
                                    echo '</td>';
                                }
                            }
                            echo '</tr>';
                        }
                    echo '</tbody>
                    </table>
                </div>
            </div>';
            mysqli_free_result($result);

            echo '<div class="w-75 mt-3 ml-auto mb-3 mr-auto pb-5">
                <input type="return" value="Soumettre" class="btn send border-0 bg-white z-depth-1a mt-4 mb-0 align-middle text-dark" />
            </div>';
        } else {
            echo "<div class='w-100 text-center'>Aucun résultat ne correspond à votre requête.</div>";
        }
    }
}
?>