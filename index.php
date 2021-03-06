<?php include 'auth.php'; ?>

<!DOCTYPE html>

<html class="overflow-y ml-auto mr-auto mb-0">
    
    <?php include 'header.php'; ?>

                <div class="icons-navbar" style="z-index: 1;">
                    <div class="menu-btn-bars text-white"><button class="menu-btn fas fa-bars text-warning w-100 fa-3x p-0"></button></div>
                    <a href="index.php" class="text-warning d-inline-flex m-auto"><img class="mr-2 ml-2" src="img/ampoule_skelec.png" alt="logo S.K.elec" height="45" width="30"><h2 class="d-inline-flex mt-0 mr-2 mb-0 ml-0">S.K.elec</h2></a>
                    <a href="add_troubleshooting.php" class="text-white pl-3"><i class="menu-btn-plus fas fa-plus-circle text-warning fa-3x rounded-circle"></i></a>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div id="container">
            <div class="content">
                <form id="inter" action="./api/index_global/create_intervention.php" method="POST">
                    <div class="pt-5 pb-4 mt-5 ml-auto mr-auto">
                        <div class="w-75 m-auto text-center pt-2"><?php if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
                                                                            echo $_SESSION['username'];
                                                                        } 
                                                                    ?>
                        </div>
                            <div class="text-center"><?php 
                                                    if ($_SESSION['username'] == "admin") { 
                                                        echo "Administrateur de S.K.elec_app ;)";
                                                    }
                                                ?>
                            </div>
                            <?php
                                if (isset($_GET['store']) && !empty($_GET['store'])) {
                                    $date = date_create($_GET['store']);
                                    echo '<div class="text-center w-75 mt-4 mr-auto ml-auto pb-4"><input class="bg-white col-7 text-center pl-4" type="date" id="up_inter" name="up_inter" value="' . $_GET['store'] . '" placeholder="' . date_format($date, "d-m-Y") . '" onChange="preview1(this.form)" onfocus="(this.type=\'date\')" onblur="if(this.value==\'\'){this.type=\'text\'}" style="height: 26px;" required="required"></div>';
                                } else {
                                    echo '<div class="text-center w-75 mt-4 mr-auto ml-auto pb-4"><input class="bg-white col-7 text-center pl-4" type="date" id="up_inter" name="up_inter"  placeholder="" onChange="preview1(this.form)" style="height: 26px;" required="required"></div>';
                                }
                            ?>
                        </div>
                    <?php echo "<input type='number' id='user_id' name='user_id' value='" . $_SESSION['id'] . "' style='display: none;'>"; ?>
                    <div class="text-center">
                        <?php
                            echo '<div class="text-center border rounded mt-4 ml-auto mb-5 mr-auto w-50">
                                <select id="chantier_name" name="chantier_name" class="w-100 border bg-white" size="1" style="max-width: -webkit-fill-available;" required>';
                                    include 'api/view/troubleshooting_view.php';

                                    trouble_list(1);
                                    
                                echo '</select>
                            </div>';
                        ?>
                    </div>
                    <div class="pt-3 w-75 m-auto text-center">
                        <label for="input_time m-auto">Heures réalisées</label>
                        <div class="w-100 m-auto pb-4">
                            <div class="d-inline-flex justify-content-center border-0 p-0 mt-2 ml-auto mr-auto mb-2 col-7">
                                <input id="intervention_hours" name="intervention_hours" value="" style="display: none;" />
                                <select type="number" id="h_index" class="col-3 p-0 border-0 rounded bg-secondary text-white text-center" style="height: 19px; max-width: 90px; min-width: 20px;">
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                    <option value="13">13</option>
                                    <option value="14">14</option>
                                </select><!--
                                --><strong>&nbsp;h&nbsp;</strong><!--
                                --><select type="number" id="m_index" class="col-3 p-0 border-0 rounded bg-secondary text-white text-center" style="height: 19px; max-width: 90px;">
                                    <option value="00">00</option>
                                    <option value="30">30</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-column mt-2 ml-auto mb-auto mr-auto w-100">
                        <div class="border-top w-75 m-auto pb-3"></div>
                        <div class="pt-5 pb-3">
                            <div class="pl-5 col-5 mb-2 p-0 position-relative" style="margin-right: -7%; margin-left: 7%;">
                                <input type="checkbox" id="panier_repas" name="panier_repas" value="1" class="form-check-input align-middle mt-1 mb-auto">
                                <label class="mb-auto mt-auto ml-4 pl-1 text-center" for="">Panier repas</label>
                            </div>
                            <div class="d-inline-flex h-25 w-100">
                                <div class="col-3 pl-5 mt-auto mb-auto pr-0 position-relative" style="margin-right: -7%; margin-left: 7%;">
                                    <div class="form-check-input mt-auto mb-auto ml-0">
                                        <input type="checkbox" name="coch_night" class="m-0">
                                    </div>
                                    <label class="mt-auto mb-auto ml-4 pl-1 text-center" for="">Dont :</label>
                                </div>
                                <div class="col-7 d-inline-flex m-auto text-center pr-0 pl-0 mt-auto mb-auto">
                                    <input id="night_hours" name="night_hours" value="" style="display: none;"/>
                                    <div class="d-inline-flex  justify-content-center col-7 p-0 mt-auto mb-auto">
                                        <select type="number" id="ni_h_index" class="col-4 p-0 border-0 rounded bg-secondary text-white mt-auto mb-auto text-center" style="height: 19px; max-width: 90px;">
                                            <option value="0">0</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                            <option value="11">11</option>
                                            <option value="12">12</option>
                                        </select><!--
                                        --><strong class="">&nbsp;h&nbsp;</strong><!--
                                        --><select type="number" id="ni_m_index" class="col-4 p-0 border-0 rounded bg-secondary text-white mt-auto mb-auto text-center" style="height: 19px; max-width: 90px;">
                                            <option value="00">00</option>
                                            <option value="30">30</option>
                                        </select>
                                    </div>
                                    <label class="col-4 mt-auto pl-0 mb-auto text-wrap text-left">heures de nuit</label>
                                </div>
                            </div>
                            <div class="mt-2 mb-2 pt-5 pb-3 w-75 m-auto">
                                <textarea class="form-control textarea" id="commit" name="commit" placeholder="Informations ?" maxlength="450"></textarea>
                            </div>
                        </div>
                        <div class="mt-2 w-75 pb-3 mr-auto ml-auto">
                            <a data-toggle="collapse" href="#preview" role="button" aria-expanded="false" aria-controls="preview" class="btn send border-0 bg-white z-depth-1a mt-3 mb-4 text-dark" onClick="preview2()">Prévisualiser</a>
                        </div>
                    </div>
                    
                    <?php
                        $date_sql = date_format($date, 'Y-m-d');
                        
                        echo '<div class="collapse" id="preview">
                            <h4 class="w-75 mt-3 ml-auto mb-3 mr-auto pt-3 border-top text-center">Récapitulatif</h4>
                            <fieldset class="pl-3 pr-3 text-dark bg-white border rounded w-75 m-auto" disabled>
                                <br />
                                <div class="d-inline-flex w-100">
                                    <div class="col-5 pl-0 pr-0 h6 mt-auto mb-auto">Date du jour </div>:
                                    <input id="date" class="bg-white border-0 p-0 mt-0 ml-auto mr-auto mb-0 w-50" value="' . date_format($date, 'd-m-Y') . '" />
                                </div>
                                <div class="d-inline-flex w-100">
                                    <div class="col-5 pl-0 pr-0 h6 mt-auto mb-auto">Nom du chantier </div>:
                                    <input id="chant_name" class="bg-white border-0 p-0 mt-0 ml-auto mr-auto mb-0 w-50" />
                                </div>
                                <div class="d-inline-flex w-100">
                                    <div class="col-5 pl-0 pr-0 h6 mt-auto mb-auto">Total des heures </div>:
                                    <input id="inter_h" class="bg-white border-0 p-0 mt-0 ml-auto mr-auto mb-0 w-50" />
                                </div>
                                <div class="d-inline-flex w-100">
                                    <div class="col-5 pl-0 pr-0 h6 mt-auto mb-auto">Panier repas </div>:
                                    <input id="pan_rep" class="bg-white border-0 p-0 mt-0 ml-auto mr-auto mb-0 w-50" />
                                </div>
                                <div class="d-inline-flex w-100">
                                    <div class="col-5 pl-0 pr-0 h6 mt-auto mb-auto">Horaires de nuit </div>:
                                    <input id="h_night" class="bg-white border-0 p-0 mt-0 ml-auto mr-auto mb-0 w-50" />
                                </div>
                                <div class="d-inline-flex w-100">
                                    <div class="col-5 pl-0 pr-0 h6 mt-0 mb-auto d-inline">Commentaires </div><div class="h6 mt-0 mb-auto">:</div>
                                    <textarea id="com" class="bg-white border-0 p-0 mt-0 ml-auto mb-0 mr-auto w-50" cols="18" rows="2" style="resize: none;"></textarea>
                                </div>';
                                
                                if ($_SESSION['id']) {
                                    if ($user) {
                                        if (isset($date_sql) && !empty($date_sql)) {
                                            //echo $date_sql . '<br />';

                                            $recap="SELECT 
                                                concat(month(g.updated)) AS `concat`,
                                                g.updated as inter_chantier,
                                                u.id,
                                                c.name AS name_chantier,
                                                SUM(absence) AS absence,
                                                SUM(night_hours) AS h_night_tot,
                                                SUM(intervention_hours) AS totalheure
                                            FROM
                                                chantiers AS c
                                                JOIN
                                                global_reference AS g ON c.id = chantier_id
                                                JOIN
                                                users AS u ON g.user_id = u.id
                                            WHERE
                                                u.id = '" . $_SESSION['id'] . "'
                                                AND
                                                updated = '" . $date_sql . "' 
                                            GROUP BY concat(month(g.updated)) , g.updated , u.id, c.name with ROLLUP";

                                            //print_r($recap);
                                            //echo '<br />';
                                        }
                                    } else if ($admin) {
                                        if (isset($date_sql) && !empty($date_sql)) {
                                            
                                            $recap="SELECT 
                                                concat(month(g.updated)) AS `concat`,
                                                g.updated as inter_chantier,
                                                a.id,
                                                c.name AS name_chantier,
                                                SUM(absence) AS  absence,
                                                SUM(night_hours) AS h_night_tot,
                                                SUM(intervention_hours) AS totalheure
                                            FROM
                                                chantiers AS c
                                                JOIN
                                                global_reference AS g ON c.id = chantier_id
                                                JOIN
                                                `admin` AS a ON g.user_id = a.id
                                            WHERE
                                                a.id = '" . $_SESSION['id'] . "'
                                                AND
                                                updated = '" . $date_sql . "'
                                            GROUP BY concat(month(g.updated)) , g.updated , a.id, c.name with ROLLUP";
                                        }
                                    }

                                    if ($result = mysqli_query($db, $recap)) {

                                        //print_r($result);

                                        if (mysqli_num_rows($result) > 0) {

                                            echo "<h4>Récap. du jour</h4>";

                                            if ($db === false){
                                                die("ERROR: Could not connect. " . mysqli_connect_error());
                                            }

                                            while ($row = $result->fetch_array()){

                                                if ($row['name_chantier']) {

                                                    $total = $row['totalheure'];
                                                    $absence = $row['absence'];

                                                    if ((!empty($total) and $total != 0) and (empty($absence) or $absence == 0)) {
                                                        $hours = (int)$total;
                                                        $minutes = ($total - $hours) * 60;

                                                        if ($minutes == 0) {
                                                            $minutes = "00";
                                                        }

                                                        $night_tot = $row['h_night_tot'];
                                                        $night_h = (int)$night_tot;
                                                        $night_m = ($night_tot - $night_h) * 60;

                                                        if ($night_m == 0) {
                                                            $night_m = "00";
                                                        }

                                                        echo '<div class="d-inline-flex w-100 m-0">
                                                            <div class="col-5 pl-0 pr-0 h6 mt-auto mb-auto h-50 overflow-hidden">' . $row['name_chantier'] . '</div><div class="mt-auto ml-0 mb-auto mr-0 p-0">:</div>
                                                            <input class="bg-white border-0 pt-0 pl-2 pb-0 pr-0 mt-auto ml-auto mr-auto mb-auto w-50" value="' . $hours . 'h' . $minutes . ' [' . $night_h . 'h' . $night_m . ' h/nuit]" />
                                                        </div>
                                                        <br />';
                                                    } elseif ($absence != 0 and !empty($absence)) {
                                                        $h_ab = (int)$absence;
                                                        $m_ab = ($absence - $h_ab) * 60;

                                                        if ($h_ab == 0 && $m_ab < 0) {
                                                            $h_ab = "-0";
                                                            $m_ab *= -1;
                                                        }
                                                        if ($m_ab == 0) {
                                                            $m_ab = "00";
                                                        }

                                                        echo '<div class="d-inline-flex w-100 m-0">
                                                            <div class="col-5 pl-0 pr-0 h6 mt-auto mb-auto h-50 overflow-hidden">' . $row['name_chantier'] . '</div><div class="mt-auto ml-0 mb-auto mr-0 p-0">:</div>
                                                            <input class="bg-white border-0 p-0 mt-auto ml-auto mr-auto mb-auto w-50" value="' . $h_ab . 'h' . $m_ab . ' [heure(s) d\'absence]" />
                                                        </div>
                                                        <br />';
                                                    }
                                                }
                                            }
                                            echo '<br />';
                                            mysqli_free_result($result);
                                        }
                                    } else{
                                        echo '<p class="pb-2 text-center">Il n\'y a pas encore d\'intervention enregistrée à ce jour.</p>';
                                    }
                                }
                            echo '</fieldset>';
                        echo '<div class="w-75 mt-3 ml-auto mr-auto">
                            <input id="submit_int" type="submit" value="Soumettre" class="btn send border-0 bg-white z-depth-1a mt-4 mb-0 align-middle text-dark" />
                        </div>';
                        ?>
                    </div>
                </form>
            </div>
        </div>
        <?php
            mysqli_close($db);
        ?>

    <?php include 'footer.php'; ?>