<?php include 'auth.php'; ?>

<!DOCTYPE html>

<html class="overflow-y ml-auto mr-auto mb-0">

    <?php include 'header.php'; ?>


                    <div class="icons-navbar">
                    <div class="menu-btn-bars text-white"><button class="menu-btn fas fa-bars text-warning w-100 fa-3x p-0"></button></div>
                    <a href="index.php" class="text-warning d-inline-flex m-auto"><img class="mr-2 ml-2" src="img/ampoule_skelec.png" alt="logo S.K.elec" height="45" width="30"><h2 class="d-inline-flex mt-0 mr-2 mb-0 ml-0">S.K.elec</h2></a>
                    <a href="add_troubleshooting.php" class="text-white pl-3"><i class="menu-btn-plus fas fa-plus-circle text-warning fa-3x rounded-circle"></i></a>
                </div>
            </div>
        </header>

        <div id="container">
            <div class="content">
                <form id="sign_ab" class="pt-4" action="api/index_global/deletion.php" method="POST">
                    <?php echo "<input type='number' id='user_id' name='user_id' value='" . $_SESSION['id'] . "' style='display: none;'>"; ?>
                        <div class="text-center">
                            <?php
                            echo '<div class="w-100 m-auto pb-4">
                            <div class="w-75 ml-auto mr-auto" id="default">
                                <div class="pt-5 pb-3 mt-4 ml-auto mr-auto">
                                    <div class="w-100 mt-3 pb-3 border-top">
                                        <h3 class="w-100 mt-3 ml-auto mb-3 mr-auto text-center">Signaler une absence</h3>
                                        <h5 class="w-100 mt-3 ml-auto mb-0 mr-auto pt-4 border-top text-center">Faites votre choix :</h5>
                                    </div>
                                </div>
                            </div>';
                                echo '<div class="m-auto pt-2 pb-3">';
                                if (isset($_GET['store']) && !empty($_GET['store'])) {
                                    $date = date_create($_GET['store']);
                                    echo '<div class="text-center w-75 mr-auto ml-auto pb-4"><input class="bg-white col-7 text-center pl-4" type="date" id="up_inter" name="up_inter" value="' . $_GET['store'] . '" placeholder="' . date_format($date, "d-m-Y") . '" onChange="date_ab(' . $_GET['id'] . ')" onfocus="(this.type=\'date\')" onblur="if(this.value==\'\'){this.type=\'text\'}" style="height: 26px;" required="required"></div>';
                                } else {
                                    echo '<div class="text-center w-75 mr-auto ml-auto pb-4"><input class="bg-white col-7 text-center pl-4" type="date" id="up_inter" name="up_inter"  placeholder="" onChange="date_ab(' . $_GET['id'] . ')" style="height: 26px;" required="required"></div>';
                                }
                            echo '<div class="col-6 float-left">
                                    <input type="checkbox" id="chantier" name="chantier" class="form-check-input align-middle mt-1 mb-auto" />
                                    <label class="mb-auto mt-auto ml-4 pl-1 text-center" for="chantier">Par chantier</label>
                                </div>
                                <div class="col-6 float-right">
                                    <input type="checkbox" id="ab_day" name="ab_day" class="form-check-input align-middle mt-1 mb-auto" />
                                    <label class="mb-auto mt-auto ml-4 pl-1 text-center" for="ab_day">Par journée</label>
                                </div>
                                
                                <div name="flag_chant" class="pt-5 d-none">
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
                                                    while ($row = $result->fetch_array()){
                                                        echo "<option value='" . $row['name'] . "'>" . $row['num_chantier'] . ' / '. $row['name'] .  "</option>";
                                                    }
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
                                                    <option value="-1">-1</option>
                                                    <option value="-2">-2</option>
                                                    <option value="-3">-3</option>
                                                    <option value="-4">-4</option>
                                                    <option value="-5">-5</option>
                                                    <option value="-6">-6</option>
                                                    <option value="-7">-7</option>
                                                    <option value="-8">-8</option>
                                                    <option value="-9">-9</option>
                                                    <option value="-10">-10</option>
                                                    <option value="-11">-11</option>
                                                    <option value="-12">-12</option>
                                                    <option value="-13">-13</option>
                                                    <option value="-14">-14</option>
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
                                </div>
                            </div>

                            <div name="flag_day" class="d-none">
                                <h5 class="w-100 text-center">Ca fonctionne ! [jour]</h5>
                            </div>

                            <div class="w-75 ml-auto mr-auto collapse" id="preview">
                                <div class="w-100 pt-0 pb-3">
                                    <h4 class="w-100 mt-3 ml-auto mb-3 mr-auto pt-3 border-top text-center">Récapitulatif</h4>
                                    <fieldset class="pl-3 pr-3 text-dark bg-white border rounded w-100 m-auto text-left" disabled>
                                        <br />
                                        <div class="d-inline-flex w-100">
                                            <div class="pl-0 pr-0 h6 mt-auto mb-auto w-50">Date du jour </div>:
                                            <input id="date" class="bg-white border-0 pl-3 mt-0 ml-auto mr-auto mb-0 w-50" value="' . date_format($date, 'd-m-Y') . '" />
                                        </div>
                                        <div class="d-inline-flex w-100">
                                            <div class="pl-0 pr-0 h6 mt-auto mb-auto w-50">Nom du chantier </div>:
                                            <input id="chant_name" class="bg-white border-0 pl-3 mt-0 ml-auto mr-auto mb-0 w-50" />
                                        </div>
                                        <div class="d-inline-flex w-100">
                                            <div class="pl-0 pr-0 h6 mt-auto mb-auto w-50">Total des heures </div>:
                                            <input id="inter_h" class="bg-white border-0 pl-3 mt-0 ml-auto mr-auto mb-0 w-50" />
                                        </div>
                                        <div class="d-inline-flex w-100">
                                            <div class="pl-0 pr-0 h6 mt-0 mb-auto w-50 d-inline">Commentaires </div><div class="h6 mt-0 mb-auto">:</div>
                                            <textarea id="com" class="bg-white border-0 pl-3 mt-0 ml-auto mb-0 mr-auto w-50" cols="18" rows="2" style="resize: none;"></textarea>
                                        </div>
                                    </fieldset>';
                                    echo '<div class="w-100 mt-3 ml-auto mr-auto">
                                        <input id="submit" type="submit" value="Soumettre" class="btn send border-0 bg-white z-depth-1a mt-4 mb-0 align-middle text-dark" />
                                    </div>
                                </div>
                            </div>';
                            ?>
                </form>
            </div>
        </div>

<?php include 'footer.php'; ?>