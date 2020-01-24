<?php

function select_t() {

    include_once 'auth.php';

    $sql = 
    "SELECT 
        id, `name`, `state`, num_chantier
    FROM 
        chantiers
    WHERE
        `state`
    ORDER BY 
        id DESC";

    if (isset($sql) && !empty($sql)) {
        return $sql;
    } else {
        return false;
    }
}


function select_t_all() {

    include_once 'auth.php';

    $sql = 
    "SELECT 
        id, `name`, `state`, num_chantier , e_mail
    FROM 
        chantiers
    ORDER BY 
        id DESC";

    if (isset($sql) && !empty($sql)) {
        return $sql;
    } else {
        return false;
    }
}


function trouble_list($state) {
    
    include 'auth.php';

    if (isset($state) && $state == 0) {
        $sql = select_t_all();
    } else {
        $sql = select_t();
    }

    if ($result = mysqli_query($db, $sql)) {
        if (mysqli_num_rows($result) > 0) {
            if ($db === false){
                die("ERROR: Could not connect. " . mysqli_connect_error());
            }
            while ($row = $result->fetch_array()) {
                if ($row['num_chantier'] != -1) {
                    echo "<option class='w-100' value='" . $row['name'] . "'>" . $row['num_chantier'] . ' / '. $row['name'] .  "</option>";
                }
            }
            echo '<option value="Journée complète" hidden>Journée complète</option>';
            mysqli_free_result($result);
        } else {
            echo "No records matching your query were found.";
        }
    } else {
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($db);
    }
}
?>