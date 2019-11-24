<?php
session_start();
//print_r(session_get_cookie_params());

include 'api/config/db_connexion.php';

if (isset($_COOKIE['id'])) {

    $auth = explode('---', $_COOKIE['id']);

    if (count($auth) === 2) {
        $req = $bdd->prepare('SELECT id, username, `password` FROM users WHERE id = :id');
        $req->execute([ ':id' => $auth[0] ]);
        $user = $req->fetch(PDO::FETCH_ASSOC);
         
        if ($user && $auth[1] === hash('sha512', $user['username'].'---'.$user['password'])) {
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
        } else {
            header("Location: signin.php");//redirect to login page to secure the welcome page without login access.  
        }
    }
} elseif (isset($_COOKIE['auth'])) {

    $auth = explode('---', $_COOKIE['auth']);
 
    if (count($auth) === 2) {
        $req = $bdd->prepare('SELECT id, admin_name, admin_pass FROM `admin` WHERE id = :id');
        $req->execute([ ':id' => $auth[0] ]);
        $admin = $req->fetch(PDO::FETCH_ASSOC);
         
        if ($admin && $auth[1] === hash('sha512', $admin['admin_name'].'---'.$admin['admin_pass'])) {
            $_SESSION['id'] = $admin['id'];
            $_SESSION['username'] = "admin";
            $_SESSION['admin_name'] = $admin['admin_name'];
        } else {
            header("Location: signin.php");//redirect to login page to secure the welcome page without login access.  
        }
    }
}

if(!($_SESSION['username'])){// or !($_SESSION['admin_name'])) {  
  
    header("Location: signin.php");//redirect to login page to secure the welcome page without login access.  
}

$stmt = $bdd->prepare("SELECT id FROM users WHERE username = '". $_SESSION['username'] ."'");
$stmt->execute();
$user = $stmt->fetch();

$stmt_admin = $bdd->prepare("SELECT id FROM `admin` WHERE admin_name = '". $_SESSION['admin_name'] ."'");
$stmt_admin->execute();
$admin = $stmt_admin->fetch();

if($user) {
    $_SESSION['id'] = $user['id'];
} elseif ($admin) {
    $_SESSION['id'] = $admin['id'];
} else {
    //echo "ERROR: Could not get 'id' of current user [first_method]";
    header("Location: signin.php");
}
?>

<!DOCTYPE html>

<html class="overflow-y mb-0">
    
    <?php include 'header.php'; ?>

                <div class="icons-navbar">
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
                    <div class="m-auto p-3">
                        <?php
                            if (isset($_GET['store']) && !empty($_GET['store'])) {
                                $date = date_create($_GET['store']);
                                echo '<div class="text-center w-75 mr-auto ml-auto pb-4"><input class="bg-white col-5 m-0 p-0 text-right" type="date" id="up_inter" name="up_inter" value="' . $_GET['store'] . '" placeholder="' . date_format($date, "d-m-Y") . '" onChange="preview1(this.form)" onfocus="(this.type=\'date\')" onblur="if(this.value==\'\'){this.type=\'text\'}" style="height: 26px;" required="required"></div>';
                            } else {
                                echo '<div class="text-center w-75 mr-auto ml-auto pb-4"><input class="bg-white col-5 m-0 p-0 text-right" type="date" id="up_inter" name="up_inter"  placeholder="" onChange="preview1(this.form)" style="height: 26px;" required="required"></div>';
                            }
                        ?>
                        <div class="text-center pt-2"><?php echo $_SESSION['username']; ?></div>
                        <div class="text-center"><?php 
                                                    if ($_SESSION['username'] == "admin") { 
                                                        echo "Administrateur de S.K.elec_app ;)";
                                                    }
                                                ?>
                        </div>
                    </div>
                    <?php echo "<input type='number' id='user_id' name='user_id' value='" . $_SESSION['id'] . "' style='display: none;'>";
                        //echo "<input type='number' id='state' name='state' value='0' style='display: none;'" ?>
                    <div class="text-center">
                        <div class="bg-white border rounded m-auto" style="width: max-content">
                            <?php
                            echo '<select id="chantier_name" name="chantier_name" class="bg-white border-white" size="1" required>';

                                $sql = 
                                "SELECT 
                                    id, `name`, `state`
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
                                            echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
                                        }
                                        mysqli_free_result($result);
                                    } else {
                                        echo "No records matching your query were found.";
                                    }
                                } else{
                                    echo "ERROR: Could not able to execute $sql. " . mysqli_error($db);
                                }

                            echo '</select>';
                            ?>
                        </div>
                    </div>
                    <div class="pt-5 w-50 m-auto text-center">
                        <label for="input_time m-auto">Heures réalisées</label>
                        <div class="w-50 m-auto pb-4">
                            <input type="time" id="intervention_hours" name="intervention_hours" class="form-control text-center align-middle m-auto p-1" style="line-height: 25px;" step="900" required />
                        </div>
                    </div>
                    <div class="m-auto d-flex flex-column border-top pt-4 w-75">
                        <div class="pt-1 pb-3">
                            <div class="col-8 mb-2 p-0 position-relative">
                                <input type="checkbox" id="panier_repas" name="panier_repas" value="1" class="form-check-input align-middle mt-1 mb-auto">
                                <label class="mb-auto mt-auto ml-4 pl-1 text-center" for="">Panier repas</label>
                            </div>
                            <div class="d-inline-flex h-25 w-100">
                                <div class="col-3 mt-auto pl-0 mb-auto pr-0 position-relative">
                                    <div class="form-check-input mt-auto mb-auto ml-0">
                                        <input type="checkbox" name="coch_night" class="m-0">
                                    </div>
                                    <label class="mt-auto mb-auto ml-4 pl-1 text-center" for="">Dont :</label>
                                </div>
                                <div class="col-7 d-inline-flex m-auto text-center pr-0 pl-0 mt-auto mb-auto">
                                    <input type="time" id="night_hours" name="night_hours" class="col-7 form-control text-center align-middle m-auto p-1" style="line-height: 25px;" step="900">
                                    <label class="col-8 mt-auto ml-3 mb-auto text-wrap text-left">heures de nuit</label>
                                </div>
                            </div>
                            <div class="mt-2 mb-2 pt-5 pb-2">
                                <textarea class="form-control textarea" id="commit" name="commit" placeholder="Informations ?" maxlength="450"></textarea>
                            </div>
                        </div>
                    </div>
                    <?php
                        $date_sql = date_format($date, 'Y-m-d');
                        
                        echo '<div class="collapse" id="preview">
                            <h4 class="w-75 mt-2 ml-auto mb-3 mr-auto text-center">Récapitulatif</h4>
                            <fieldset class="pl-3 text-dark bg-white border rounded w-75 m-auto" disabled>
                                <br />
                                Date du jour :   <input id="date" class="bg-white border-0 p-0 mt-0 ml-auto mr-auto mb-0 w-50" value="' . date_format($date, 'd-m-Y') . '" /><br />
                                Nom du chantier :  <input id="chant_name" class="bg-white border-0 p-0 mt-0 ml-auto mr-auto mb-0 w-50" /><br />
                                Total des heures :  <input id="inter_h" class="bg-white border-0 p-0 mt-0 ml-auto mr-auto mb-0 w-50" /><br />
                                Panier repas :  <input id="pan_rep" class="bg-white border-0 p-0 mt-0 ml-auto mr-auto mb-0 w-50" /><br />
                                Horaires de nuit :  <input id="h_night" class="bg-white border-0 p-0 mt-0 ml-auto mr-auto mb-0 w-50" /><br />
                                <div class="d-inline-flex">
                                Commentaires :  <textarea id="com" class="bg-white border-0 pt-0 pl-2 mt-0 ml-auto mb-0" cols="18" rows="2" style="resize: none;"></textarea></div><br />';
                                
                                if (isset($date) && !empty($date)) {

                                    $recap="SELECT 
                                        concat(month(g.updated)) AS `concat`,
                                        g.updated as inter_chantier,
                                        u.id,
                                        c.name AS name_chantier,
                                        SUM(night_hours) AS h_night_tot,
                                        SUM(intervention_hours) AS totalheure,
                                        SUM(intervention_hours - night_hours) AS tothsnight,
                                        floor((SUM(floor(intervention_hours / 10000)) + (SUM(((floor(intervention_hours) - floor(floor(intervention_hours) / 10000) * 10000) / 100) / 60))) * 100) AS tot_glob
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
                                        AND
                                        concat(month(g.updated)) = (
                                                                SELECT 
                                                                    MAX(concat(month(updated)))
                                                                FROM
                                                                    global_reference
                                                                )
                                    GROUP BY concat(month(g.updated)) , g.updated , u.id, c.name with ROLLUP";

                                    //print_r($recap_2);
                                    if ($result = mysqli_query($db, $recap)) {

                                        if (mysqli_num_rows($result) > 0) {

                                            echo "<h4>Récap. du jour</h4>";

                                            //print_r($result);

                                            if ($db === false){
                                                die("ERROR: Could not connect. " . mysqli_connect_error());
                                            }

                                            while ($row = $result->fetch_array()){

                                                if ($row['name_chantier']) {

                                                    $total = $row['tot_glob'];
                                                    $hours = (int)($total / 100);
                                                    $minutes = ((int)($total - ($hours * 100)) / 100) * 60;
                                                    if ($minutes > 59) {
                                                        $hours += 1;
                                                        $minutes -= 60;
                                                    }
                                                    if ($minutes > 10) {
                                                        $minutes = $minutes;
                                                    } elseif ($minutes < 10 and $minutes > 0) {
                                                        $minutes = "0" . $minutes;
                                                    } else {
                                                        $minutes = "00";
                                                    }

                                                    $night_tot = $row['h_night_tot'];
                                                    $night_h = (int)($night_tot / 10000);
                                                    $night_m = ((int)($night_tot - ($night_h * 10000)) / 100) * 60;
                                                    if ($night_m > 59) {
                                                        $night_h += 1;
                                                        $night_m -= 60;
                                                    }
                                                    if ($night_m > 10) {
                                                        $night_m = $night_m;
                                                    } elseif ($night_m < 10 and $night_m > 0) {
                                                        $night_m = "0" . $night_m;
                                                    } else {
                                                        $night_m = "00";
                                                    }
                                                    echo '<div class="d-inline-flex h6 m-0">' . $row['name_chantier'] . '<input class="bg-white border-0 p-0 mt-0 ml-auto mr-auto mb-0 w-100" value=" : ' . $hours . 'h' . $minutes . ' [' . $night_h . 'h' . $night_m . ' h/nuit]" /></div><br />';
                                                }
                                            }
                                            echo '<br />';
                                            mysqli_free_result($result);
                                        }/* else {
                                            echo "No records matching your query were found.";
                                        }*/
                                    } else{
                                        echo "ERROR: Could not able to execute $recap. " . mysqli_error($db);
                                    }
                                }
                            echo '</fieldset>';
                        echo '<div class="w-75 mt-3 ml-auto mr-auto">
                            <input type="submit" value="Soumettre" class="btn send border-0 bg-white z-depth-1a mt-3 mb-0 align-middle text-dark" />
                        </div>';
                        ?>
                    </div>
                    <div class="mt-4 w-75 pt-2 mr-auto ml-auto">
                        <a data-toggle="collapse" href="#preview" role="button" aria-expanded="false" aria-controls="preview" class="btn send border-0 bg-white z-depth-1a mt-3 mb-4 text-dark" onClick="preview2()">Prévisualiser</a>
                    </div>
                </form>
            </div>
        </div>
        <?php
            mysqli_close($db);
        ?>

    <?php include 'footer.php'; ?>