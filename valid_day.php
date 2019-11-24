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

        <div id="container">
            <div class="content">
                <?php
                    $recap = "SELECT 
                        g.updated as inter_chantier,
                        u.id,
                        c.name AS name_chantier,
                        SUM(night_hours) AS h_night_tot,
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
                        updated = '" . $_GET['up_int'] . "'
                    GROUP BY g.updated , u.id, c.name";

                    $date = date_create($_GET['up_int']);
                    //print_r($recap_2);

                    echo "<div class='w-75 text-center mt-4 ml-auto mb-4 mr-auto h4'><u>Récapitulatif de la journée du<br />'" . date_format($date, 'd-m-Y') . "'</u></div>";

                    if ($result = mysqli_query($db, $recap)) {

                        if (mysqli_num_rows($result) > 0) {

                            //print_r($result);
                            $h_global = 0;
                            $h_ni_glob = 0;

                            if ($db === false){
                                die("ERROR: Could not connect. " . mysqli_connect_error());
                            }

                            echo '<fieldset class="pt-3 pl-3 pb-4 pr-3 text-dark bg-white border rounded w-100 mt-4 ml-auto mb-3 mr-auto">';

                            while ($row = $result->fetch_array()){

                                if ($row['name_chantier']) {

                                    $total = $row['tot_glob'];
                                    $h_global += $total;

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
                                    $h_ni_glob += $night_tot;

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
                                    echo '<div class="d-inline-flex m-0">
                                            <div class="mt-2 mb-2 p-0 text-wrap" style="width: 33%; height: 20.4px">' . $row['name_chantier'] . '</div><div class="col-1 p-0 mt-2 mb-2 text-center">:&nbsp</div>
                                            <p id="tot_h" class="d-inline bg-white border-0 p-0 mt-2 ml-auto mr-auto mb-2 col-7"><input class="hours col-1 border-0 rounded bg-secondary p-0 text-white text-center" value="' . $hours . '"><strong> h </strong><input class="minutes col-1 border-0 rounded bg-secondary p-0 text-white text-center" value="' . $minutes . '" /><strong> [ </strong><input class="night_h col-1 border-0 rounded bg-secondary p-0 text-white text-center" value="' . $night_h . '" /><strong> h </strong><input class="night_m col-1 border-0 rounded bg-secondary p-0 text-white text-center" value="' . $night_m . '" /><strong> h/nuit ]</strong></p>';
                                            //<input class="bg-white border-0 p-0 mt-0 ml-auto mr-auto mb-0 w-100" value="' . $hours . ' h ' . $minutes . ' [' . $night_h . ' h ' . $night_m . ' h/nuit]" />
                                    echo '</div>';
                                }
                            }
                            echo '<div class="w-100 text-center mt-3 mb-3"><a role="button" class="w-50 ml-auto mr-auto btn pt-1 pl-2 pb-1 pr-2 border bg-success text-white text-center" onClick="calcul()">Vérifier</a></div>';
                            echo '<div class="mb-4"></div>';
                            echo '<div class="mb-4 border"></div>';

                            $hours = (int)($h_global / 100);
                            $minutes = ((int)($h_global - ($hours * 100)) / 100) * 60;

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


                            $night_h = (int)($h_ni_glob / 10000);
                            $night_m = ((int)($h_ni_glob - ($night_h * 10000)) / 100) * 60;
                            
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

                            echo '<div class="d-inline-flex m-0">
                                    <h5 class="mt-2 mb-2 p-0 text-wrap" style="width: 33%;">Heures globales sur la journée du&nbsp;<strong><u>' . date_format($date, 'd-m-Y') . '</h5></strong></u><div class="col-1 p-0 text-center mt-auto mb-auto">:&nbsp;</div>
                                    <span id="pouet"></span>
                                    <fieldset class="col-7 p-0 mt-auto mb-auto" disabled><p class="d-inline bg-white border-0 p-0 mt-auto ml-auto mr-auto mb-auto col-7"><strong><input id="recap_h" class="col-1 border-0 rounded bg-white p-0 text-center" value="' . $hours . '"> h <input id="recap_m" class="col-1 border-0 rounded bg-white p-0 text-center" value="' . $minutes . '" /> [ <input id="rec_h_night" class="col-1 border-0 rounded bg-white p-0 text-center" value="' . $night_h . '" /> h <input id="rec_m_night" class="col-1 border-0 rounded bg-white p-0 text-center" value="' . $night_m . '" /> h/nuit ]</strong></p></fieldset>
                            </div><br />';

                            mysqli_free_result($result);
                        } else {
                            echo "No records matching your query were found.";
                        }
                        echo '</fieldset>';
                    } else{
                        echo "ERROR: Could not able to execute $recap. " . mysqli_error($db);
                    }
                    
                    mysqli_close($db);
                ?>
            </div>
        </div>

<?php include 'footer.php'; ?>