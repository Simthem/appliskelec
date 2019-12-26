<?php

function ab_chant() {

    include 'auth.php';

    echo '<div name="flag_chant" class="pt-5 d-none">
        <h5 class="w-100 text-center pt-3 pb-3">Choix : [Par chantier]</h5>
        <div class="border bg-white rounded mt-auto ml-auto mr-auto mb-5 w-50">
            <select id="chantier_name" name="chantier_name" class="bg-white border-white w-100" size="1" style="max-width: -webkit-fill-available;"required>';

                $sql = 
                "SELECT 
                    id, `name`, `state`, num_chantier
                FROM 
                    chantiers
                WHERE
                    `state`
                ORDER BY 
                    id DESC";

                if ($result = mysqli_query($db, $sql)) {
                    if (mysqli_num_rows($result) > 0) {
                        if ($db === false){
                            die("ERROR: Could not connect. " . mysqli_connect_error());
                        }
                        while ($row = $result->fetch_array()) {
                            if ($row['num_chantier'] != -1) {
                                echo "<option value='" . $row['name'] . "'>" . $row['num_chantier'] . ' / '. $row['name'] .  "</option>";
                            }
                        }
                        echo '<option value="Journée complète" hidden>Journée complète</option>';
                        mysqli_free_result($result);
                    } else {
                        echo "No records matching your query were found.";
                    }
                } else{
                    echo "ERROR: Could not able to execute $sql. " . mysqli_error($db);
                }
            echo '</select>
        </div>';

        echo '<div class="border-top border-bottom w-75 ml-auto mr-auto">
            <div class="pt-2 pb-4 mt-4 ml-auto mr-auto mb-2 w-75">
                <h6 class="w-100 text-center pb-3">Temps à soustraire :</h6>
                <input id="intervention_hours" name="intervention_hours" value="" style="display: none;" />
                <select type="number" id="h_index" class="col-3 p-0 border-0 rounded bg-secondary text-white text-center" style="height: 19px;">
                    <option value="0">0</option>
                    <option value="1">-1</option>
                    <option value="2">-2</option>
                    <option value="3">-3</option>
                    <option value="4">-4</option>
                    <option value="5">-5</option>
                    <option value="6">-6</option>
                    <option value="7">-7</option>
                    <option value="8">-8</option>
                    <option value="9">-9</option>
                    <option value="10">-10</option>
                    <option value="11">-11</option>
                    <option value="12">-12</option>
                    <option value="13">-13</option>
                    <option value="14">-14</option>
                </select><!--
                --><strong>&nbsp;h&nbsp;</strong><!--
                --><select type="number" id="m_index" class="col-3 p-0 border-0 rounded bg-secondary text-white text-center" style="height: 19px;">
                    <option value="00">00</option>
                    <option value="30">30</option>
                </select>
            </div>
        </div>

        <div class="mt-2 mb-2 pt-5 pb-3 w-75 m-auto">
            <textarea class="form-control textarea" id="commit" name="commit" placeholder="Informations ?" maxlength="450"></textarea>
        </div>

        <div class="mt-4 w-75 mr-auto ml-auto">
            <a data-toggle="collapse" href="#preview" role="button" aria-expanded="false" aria-controls="preview" class="btn send border-0 bg-white z-depth-1a mt-3 mb-4 text-dark" onClick="preview2()">Prévisualiser</a>
        </div>
    </div>';


    echo '<div name="flag_day" class="pt-5 d-none">
        <h5 class="w-100 text-center pt-3 pb-3">Choix : [Par journée]</h5>';
        echo "<input id='day' value='Journée complète' class='text-center' disabled/>";

        echo '<div class="border-top border-bottom w-75 ml-auto mr-auto">
            <div class="pt-2 pb-4 mt-4 ml-auto mr-auto mb-2 w-75">
                <h6 class="w-100 text-center pb-3">Temps à soustraire :</h6>
                <input id="hours_day" name="hours_day" value="" style="display: none;" />
                <select type="number" id="day_h" class="col-3 p-0 border-0 rounded bg-secondary text-white text-center" style="height: 19px;" disabled>
                    <option value="7" selected>-7</option>
                </select><!--
                --><strong>&nbsp;h&nbsp;</strong><!--
                --><select type="number" id="day_m" class="col-3 p-0 border-0 rounded bg-secondary text-white text-center" style="height: 19px;" disabled>
                    <option value="00" selected>00</option>
                </select>
            </div>
        </div>

        <div class="mt-2 mb-2 pt-5 pb-3 w-75 m-auto">
            <textarea class="form-control textarea" id="com_day" name="com_day" placeholder="Informations ?" maxlength="450"></textarea>
        </div>

        <div class="mt-4 w-75 mr-auto ml-auto">
            <a data-toggle="collapse" href="#preview" role="button" aria-expanded="false" aria-controls="preview" class="btn send border-0 bg-white z-depth-1a mt-3 mb-4 text-dark" onClick="preview2(1)">Prévisualiser</a>
        </div>
    </div>';

    //return true;
}
?>