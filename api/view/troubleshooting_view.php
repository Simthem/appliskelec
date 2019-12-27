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

function trouble_list() {
    
    include 'auth.php';
    
    echo '<div class="text-center border rounded mt-4 ml-auto mb-5 mr-auto w-50">
        <select id="chantier_name" name="chantier_name" class="w-100 border bg-white" size="1" style="max-width: -webkit-fill-available;"required>';

            $sql = select_t();

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
        echo '</select>
    </div>';
}
?>