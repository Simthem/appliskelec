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
            $_SESSION['admin_name'] = null;
            $admin['id'] = null;
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
            header("Location: signin.php"); 
        }
    }
}

if(!($_SESSION['username'])){
  
    header("Location: signin.php");
}

$stmt = $bdd->prepare("SELECT id FROM users WHERE username = '". $_SESSION['username'] ."'");
$stmt->execute();
$user = $stmt->fetch();

if (isset($_SESSION['admin_name']) && !empty($_SESSION['admin_name'])) {

    $stmt_admin = $bdd->prepare("SELECT id FROM `admin` WHERE admin_name = '". $_SESSION['admin_name'] ."'");
    $stmt_admin->execute();
    $admin = $stmt_admin->fetch();
}

if($user) {
    $_SESSION['id'] = $user['id'];
} elseif (isset($admin) && !empty($admin)) {
    $_SESSION['id'] = $admin['id'];
} else {
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
                <form action="api/index_global/validation.php" method="POST">
                    <?php
                        $recap = "SELECT 
                            g.id AS gid,
                            g.updated as inter_chantier,
                            u.id,
                            c.name AS name_chantier,
                            c.id AS chantier_id,
                            absence,
                            SUM(night_hours) AS h_night_tot,
                            SUM(intervention_hours - night_hours) AS tothsnight,
                            SUM(intervention_hours) AS tot_glob,
                            panier_repas,
                            g.state AS `state`
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
                        GROUP BY g.id, g.updated, u.id, c.name, c.id, absence, panier_repas, g.state";

                        $date = date_create($_GET['up_int']);
                        echo '<input id="up_inter" name="up_inter" value="' . date_format($date, 'Y-m-d') . '" style="display: none" />';

                        echo "<h3 class='mt-0 mb-3 pt-5 text-center'>Récapitulatif de la journée du<br />'" . date_format($date, 'd-m-Y') . "'</h3>";
                        #<div class='w-75 text-center ml-auto mb-5 mr-auto h4'><u>Récapitulatif de la journée du<br />'" . date_format($date, 'd-m-Y') . "'</u></div>";

                        if ($result = mysqli_query($db, $recap)) {

                            if (mysqli_num_rows($result) > 0) {

                                $h_global = 0;
                                $h_ni_glob = 0;
                                $flag = 0;

                                if ($db === false){
                                    die("ERROR: Could not connect. " . mysqli_connect_error());
                                }

                                echo '<fieldset class="pt-3 pl-2 pb-4 pr-2 text-dark bg-white border rounded w-100 mt-4 ml-auto mb-3 mr-auto">';

                                while ($row = $result->fetch_array()){

                                    if ($row['state'] == 1 && $flag == 0) {
                                        echo '<div class="text-center m-auto w-75"><p class="bg-warning w-50 pt-2 pl-2 pb-2 pr-2 ml-auto mr-auto rounded border-0 text-center text-white">Journée validée</p></div>';
                                    }

                                    if ($row['state'] != 1) {

                                        echo '<fieldset class="w-100">';

                                            $flag += 1;
                                            $temp = 0;

                                            if ($row['panier_repas']) {
                                                $pan = 1;
                                            }

                                            if (isset($row['absence']) && !empty($row['absence'])) {

                                                $h_glo_ab += $row['absence'];
                                                $h_ab = (int)($h_glo_ab);
                                                $m_ab = ($h_glo_ab - $h_ab) * 60;

                                                while ($m_ab > 59) {
                                                    $h_ab += 1;
                                                    $m_ab -= 60;
                                                }
                                                if ($m_ab == 0) {
                                                    $m_ab = "00";
                                                } elseif ($m_ab < 10 and $m_ab > 0) {
                                                    $m_ab = "0" . $minutes;
                                                }
                                                if ($m_ab < 0) {
                                                    $m_ab *= -1;
                                                }
                                            }

                                            if ($row['name_chantier']) {

                                                // NORMAL HOURS ---------------
                                                $total = $row['tot_glob'];
                                                $h_global += $total;
                                                $hours = (int)($total);
                                                $minutes = ($total - $hours) * 60;

                                                //echo $total . '<br />' . $h_global . '<br />' . $hours . '<br />' . $minutes;

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
                                                
                                                //echo '<br />' . $h_glo_ab . '<br />' . $h_global . '<br />';
                                                $h_global += $h_glo_ab;
                                                $h_glo_ab = NULL;
                                                //echo $h_glo_ab . '<br />' . $h_global . '<br />';

                                                //echo $h_glo_ab . '<br />' . $h_global;
                                                // NIGHT HOURS ----------------

                                                $night_tot = $row['h_night_tot'];
                                                $h_ni_glob += $night_tot;

                                                $night_h = (int)($night_tot);
                                                $night_m = ($night_tot - $night_h) * 60;

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
                                                echo '<div class="w-100 d-inline-flex m-0">';
                                                    echo "<select class='chantier mt-2 mb-2 p-0 text-wrap' style='height: 20.4px;max-width: 23%;' size='1'>";

                                                        $sql = 
                                                        "SELECT 
                                                            c.id AS chantier_id, `name`, c.state AS `state`
                                                        FROM 
                                                            chantiers AS c
                                                        WHERE
                                                            c.state
                                                        ORDER BY 
                                                            c.id DESC";

                                                        if ($reponse = mysqli_query($db, $sql)) {

                                                            if (mysqli_num_rows($reponse) > 0) {

                                                                if ($db === false){
                                                                    die("ERROR: Could not connect. " . mysqli_connect_error());
                                                                }
                                                                while ($chk = $reponse->fetch_array()){

                                                                    if ($chk['chantier_id'] == $row['chantier_id']) {
                                                                            echo "<option value='" . $chk['chantier_id'] . "' selected>" . $chk['name'] . "</option>";
                                                                    } else {
                                                                        echo "<option value='" . $chk['chantier_id'] . "'>" . $chk['name'] . "</option>";
                                                                    }
                                                                }
                                                                mysqli_free_result($reponse);
                                                            } else {
                                                                echo "No records matching your query were found.";
                                                            }
                                                        } else{
                                                            echo "ERROR: Could not able to execute $sql. " . mysqli_error($db);
                                                        }

                                                    echo '</select>';
                                                    echo '<input name="glo_ch_id" value="' . $row['chantier_id'] . '" class="mt-2 mb-2 p-0 text-wrap" style="min-width: 27%; max-width=27%; height: 20.4px; display: none;" />';
                                                    echo '<div class="mt-2 mb-2 p-0 text-wrap" style="min-width: 27%; max-width=27%; height: 20.4px; display: none;">' . $row['name_chantier'] . '</div>
                                                    <div class="w-75 p-0 text-center d-inline-flex">
                                                        <div class="p-0 mt-2 mb-2 float-left">&nbsp;:&nbsp;</div>';

                                                            if (!isset($row['absence']) || empty($row['absence'])) {
                                                            echo '<div class="border-0 p-0 mt-2 ml-0 mr-0 mb-2 col-12 float-right d-inline-flex">
                                                                <div class="col-5 p-0">
                                                                    <select id="hours" class="hours border-0 rounded bg-secondary text-white text-center" style="width: 40px;">
                                                                        <option value="' . $hours . '" selected disabled>' . $hours . '</option>
                                                                        <option value="0">&nbsp;0&nbsp;</option>
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
                                                                    </select>
                                                                    <strong>h</strong>
                                                                    <select class="minutes border-0 rounded bg-secondary text-white text-center" style="width: 40px;">
                                                                        <option value="' . $minutes . '" selected disabled>' . $minutes . '</option>
                                                                        <option value="00">00</option>
                                                                        <option value="30">30</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-7 p-0">
                                                                    <strong>[</strong>
                                                                    <select class="night_h border-0 rounded bg-secondary text-white text-center" style="width: 40px;">
                                                                        <option value="' . $night_h . '" selected disabled>' . $night_h . '</option>
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
                                                                    </select>
                                                                    <strong>h </strong>';
                                                                    echo '<select class="night_m border-0 rounded bg-secondary text-white text-center" style="width: 40px;">
                                                                        <option value="' . $night_m . '" selected disabled>' . $night_m . '</option>
                                                                        <option value="00">00</option>
                                                                        <option value="30">30</option>
                                                                    </select>
                                                                    <strong>/nuit]</strong>
                                                                </div>
                                                                <input class"h_ab" value="0" style="display: none;" />';
                                                            } else {
                                                            echo '<input id="h_ab" value="1" style="display: none;" />
                                                            <input class="hours" value="0" style="display: none;" />
                                                            <input class="minutes" value="0" style="display: none;" />
                                                            <input class="night_h" value="0" style="display: none;" />
                                                            <input class="night_m" value="0" style="display: none;" />
                                                            <div class="border-0 p-0 mt-2 ml-0 mr-0 mb-2 col-12 float-left d-inline-flex">
                                                                <div class="float-left col-5 p-0">
                                                                    <select class="h_ab border-0 rounded bg-secondary text-white text-center" style="width: 40px;">
                                                                        <option value="' . $h_ab . '" selected disabled>' . $h_ab . '</option>';
                                                                        $i = 0;
                                                                        while ($i <= 0 and $i >= -14) {
                                                                            echo '<option value=' . $i . '>' . $i . '</option>';
                                                                            $i--;
                                                                        }
                                                                    echo '</select>
                                                                    <strong>h</strong>
                                                                    <select class="m_ab border-0 rounded bg-secondary text-white text-center" style="width: 40px;">
                                                                        <option value="' . $m_ab . '" selected disabled>' . $m_ab . '</option>
                                                                        <option value="00">00</option>
                                                                        <option value="30">30</option>
                                                                    </select>
                                                                </div>
                                                                <div class="float-right col-7 p-0">
                                                                    <small><strong>[comptée(s) en "absence"]</strong></small>
                                                                </div>';
                                                            }
                                                        echo '</div>
                                                    </div>';
                                                echo '</div>';
                                            }
                                        echo '</fieldset>';
                                    } else {
                                        echo '<fieldset class="w-100" disabled>';

                                            $flag += 1;
                                            $temp = 0;

                                            $valid = 1;

                                            if ($row['panier_repas']) {
                                                $pan = 1;
                                            }

                                            if (isset($row['absence']) && !empty($row['absence'])) {

                                                $h_glo_ab += $row['absence'];
                                                $h_ab = (int)($h_glo_ab);
                                                $m_ab = ($h_glo_ab - $h_ab) * 60;

                                                while ($m_ab > 59) {
                                                    $h_ab += 1;
                                                    $m_ab -= 60;
                                                }
                                                if ($m_ab > 10) {
                                                    $m_ab = $m_ab;
                                                } elseif ($m_ab < 10 and $m_ab > 0) {
                                                    $m_ab = "0" . $minutes;
                                                } else {
                                                    $m_ab = "00";
                                                }
                                            }

                                            if ($row['name_chantier']) {

                                                // NORMAL HOURS ---------------
                                                $total = $row['tot_glob'];
                                                $h_global += $total;
                                                $hours = (int)($total);
                                                $minutes = ($total - $hours) * 60;

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

                                                $h_global += $h_glo_ab;
                                                $h_glo_ab = NULL;

                                                // NIGHT HOURS ----------------

                                                $night_tot = $row['h_night_tot'];
                                                $h_ni_glob += $night_tot;

                                                $night_h = (int)($night_tot);
                                                $night_m = ($night_tot - $night_h) * 60;

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
                                                echo '<div class="w-100 d-inline-flex m-0">';
                                                    echo "<select class='chantier mt-2 mb-2 p-0 text-wrap' style='height: 20.4px;max-width: 23%;' size='1'>";

                                                        $sql = 
                                                        "SELECT 
                                                            c.id AS chantier_id, `name`, c.state AS `state`
                                                        FROM 
                                                            chantiers AS c
                                                        WHERE
                                                            c.state
                                                        ORDER BY 
                                                            c.id DESC";

                                                        if ($reponse = mysqli_query($db, $sql)) {

                                                            if (mysqli_num_rows($reponse) > 0) {

                                                                if ($db === false){
                                                                    die("ERROR: Could not connect. " . mysqli_connect_error());
                                                                }
                                                                while ($chk = $reponse->fetch_array()){

                                                                    if ($chk['chantier_id'] == $row['chantier_id']) {
                                                                            echo "<option value='" . $chk['chantier_id'] . "' selected>" . $chk['name'] . "</option>";
                                                                    } else {
                                                                        echo "<option value='" . $chk['chantier_id'] . "'>" . $chk['name'] . "</option>";
                                                                    }
                                                                }
                                                                mysqli_free_result($reponse);
                                                            } else {
                                                                echo "No records matching your query were found.";
                                                            }
                                                        } else{
                                                            echo "ERROR: Could not able to execute $sql. " . mysqli_error($db);
                                                        }

                                                    echo '</select>';
                                                    echo '<input name="glo_ch_id" value="' . $row['chantier_id'] . '" class="mt-2 mb-2 p-0 text-wrap" style="min-width: 27%; max-width=27%; height: 20.4px; display: none;" />';
                                                    echo '<div class="mt-2 mb-2 p-0 text-wrap" style="min-width: 27%; max-width=27%; height: 20.4px; display: none;">' . $row['name_chantier'] . '</div>
                                                    <div class="w-75 p-0 text-center d-inline-flex">
                                                        <div class="p-0 mt-2 mb-2 float-left">&nbsp;:&nbsp;</div>';

                                                            if (!isset($row['absence']) || empty($row['absence'])) {
                                                            echo '<div class="border-0 p-0 mt-2 ml-0 mr-0 mb-2 col-12 float-right d-inline-flex">
                                                                <div class="col-5 p-0">
                                                                    <select class="hours border-0 rounded bg-secondary text-white text-center">
                                                                        <option value="' . $hours . '" selected disabled>' . $hours . '</option>
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
                                                                    </select>
                                                                    <strong>h</strong>
                                                                    <select class="minutes border-0 rounded bg-secondary text-white text-center">
                                                                        <option value="' . $minutes . '" selected disabled>' . $minutes . '</option>
                                                                        <option value="00">00</option>
                                                                        <option value="30">30</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-7 p-0">
                                                                    <strong>[</strong>
                                                                    <select class="night_h border-0 rounded bg-secondary text-white text-center">
                                                                        <option value="' . $night_h . '" selected disabled>' . $night_h . '</option>
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
                                                                    </select>
                                                                    <strong>h </strong>';
                                                                    echo '<select class="night_m border-0 rounded bg-secondary text-white text-center" />
                                                                        <option value="' . $night_m . '" selected disabled>' . $night_m . '</option>
                                                                        <option value="00">00</option>
                                                                        <option value="30">30</option>
                                                                    </select>
                                                                    <strong>/nuit]</strong>
                                                                </div>';
                                                            } else {
                                                            echo '<div class="border-0 p-0 mt-2 ml-0 mr-0 mb-2 col-12 float-left d-inline-flex">
                                                                <div class="float-left col-5 p-0">
                                                                    <select class="h_ab border-0 rounded bg-secondary text-white text-center">
                                                                        <option value="' . $h_ab . '" selected disabled>' . $h_ab . '</option>';
                                                                        $i = 0;
                                                                        while ($i <= 0 and $i >= -14) {
                                                                            echo '<option value=' . $i . '>' . $i . '</option>';
                                                                            $i--;
                                                                        }
                                                                    echo '</select>
                                                                    <strong>h</strong>
                                                                    <select class="m_ab border-0 rounded bg-secondary text-white text-center">
                                                                        <option value="' . $m_ab . '" selected disabled>' . $m_ab . '</option>
                                                                        <option value="00">00</option>
                                                                        <option value="30">30</option>
                                                                    </select>
                                                                </div>
                                                                <div class="float-right col-7 p-0">
                                                                    <small><strong>[comptée(s) en "absence"]</strong></small>
                                                                </div>';
                                                            }
                                                        echo '</div>
                                                    </div>';
                                                echo '</div>';
                                            }

                                        echo '</fieldset>';
                                    }
                                } 
                                

                                echo '<fieldset>';

                                    while ($temp < $flag) { 
                                        echo '<input id="tot_h' . $temp . '" name="tot_h' . $temp . '"style="display: none;" />';
                                        echo '<input id="tot_h_night' . $temp . '" name="tot_h_night' . $temp . '"style="display: none;" />';
                                        echo '<input id="chantier_id' . $temp . '" name="chantier_id' . $temp . '"style="display: none;" />';
                                        echo '<input id="tot_h_ab' . $temp . '" name="tot_h_ab' . $temp . '"style="display: none;" />';
                                        //echo $flag;
                                        $temp += 1;
                                    }

                                    if ($valid == 0) {
                                        echo '<br /><br /><div class="ml-2 p-0 position-relative">';
                                            if (isset($pan) && !empty($pan)) {
                                                echo '<input type="checkbox" id="panier_repas" name="panier_repas" value="1" class="form-check-input align-middle mt-1 mb-auto" checked>';
                                            } else {
                                                echo '<input type="checkbox" id="panier_repas" name="panier_repas" value="1" class="form-check-input align-middle mt-1 mb-auto">';
                                            }
                                                echo '<label class="font-weight-normal mb-auto mt-auto ml-4 pl-1 text-center" for="">Panier repas</label>
                                        </div>
                                        <br />';
                                        echo '<div class="w-100 text-center mb-3"><a role="button" id="verif" class="w-50 ml-auto mr-auto btn pt-1 pl-2 pb-1 pr-2 border bg-light text-center">Vérifier</a></div>';
                                        echo '<div class="mb-4"></div>';
                                        echo '<div class="mb-4 border"></div>';
                                    }  else {
                                        echo '<fieldset class="w-100" disabled>
                                            <br /><br /><div class="ml-2 p-0 position-relative">';
                                                if (isset($pan) && !empty($pan)) {
                                                    echo '<input type="checkbox" id="panier_repas" name="panier_repas" value="1" class="form-check-input align-middle mt-1 mb-auto" checked>';
                                                } else {
                                                    echo '<input type="checkbox" id="panier_repas" name="panier_repas" value="1" class="form-check-input align-middle mt-1 mb-auto">';
                                                }
                                                    echo '<label class="font-weight-normal mb-auto mt-auto ml-4 pl-1 text-center" for="">Panier repas</label>
                                            </div>
                                            <br />';
                                            echo '<div class="w-100 text-center mb-3"><a role="button" id="verif" class="w-50 ml-auto mr-auto btn pt-1 pl-2 pb-1 pr-2 border bg-light text-center">Vérifier</a></div>';
                                            echo '<div class="mb-4"></div>';
                                            echo '<div class="mb-4 border"></div>
                                        </fieldset>';
                                    }

                                    $hours = (int)$h_global;
                                    $minutes = ($h_global - $hours) * 60;

                                    /*echo $hours;
                                    echo '<br />' . $minutes;
                                    echo '<br />' . $h_global;*/

                                    if ($minutes != 0 || $minutes != 30) {
                                        if ($minutes < 10 && $minutes > 0) {
                                            $minutes = "0" . $minutes;
                                        }
                                        if ($minutes < 0 && ($hours < 0 || $hours > 0)) {
                                            $minutes *= -1;
                                        } elseif ($minutes < 0 && $hours == 0) {
                                            $minutes *= -1;
                                            $hours = "-".$hours;
                                        }
                                    }
                                    if ($minutes == 0) {
                                        $minutes = "00";
                                    }

                                    $night_h = (int)$h_ni_glob;
                                    $night_m = ($h_ni_glob - $night_h) * 60;

                                    if ($night_m > 59) {
                                        $night_m -= 60;
                                        $night_h += 1;
                                    }
                                    if ($night_m > 10) {
                                        $night_m = $night_m;
                                    } elseif ($night_m < 10 && $night_m > 0) {
                                        $night_m = '0' . $night_m;
                                    } else {
                                        $night_m = "00";
                                    }

                                    echo '<div class="d-inline-flex m-0 pb-3 text-center">
                                            <h5 class="mt-2 mb-2 p-0 text-wrap" style="width: 33%;">Heures globales sur la journée du&nbsp;<strong><u>' . date_format($date, 'd-m-Y') . '</h5></strong></u><div class="col-1 p-0 text-center mt-auto mb-auto">:&nbsp;</div>
                                            <fieldset class="col-7 p-0 mt-auto mb-auto" disabled>
                                                <p class="d-inline bg-white border-0 p-0 mt-auto ml-auto mr-auto mb-auto col-7">
                                                    <strong><input id="recap_h" class="col-1 border-0 rounded bg-white p-0 text-center" value="' . $hours . '"> h <input id="recap_m" class="col-1 border-0 rounded bg-white p-0 text-center" value="' . $minutes . '" />&nbsp;[<input id="rec_h_night" class="col-1 border-0 rounded bg-white p-0 text-center" value="' . $night_h . '" /> h <input id="rec_m_night" class="col-1 border-0 rounded bg-white p-0 text-center" value="' . $night_m . '" /> /nuit ]</strong>
                                                    <br />
                                                    Panier repas :  <input id="pan_rep" class="bg-white border-0 p-0 mt-0 ml-auto mr-auto mb-0 col-1" value="' . $pan . '"/>
                                                    
                                                </p>
                                            </fieldset>
                                    </div>
                                    <br />';
                                    if ($valid == 0) {
                                        echo '<div class="w-100 text-center">
                                                <input id="flag" name="flag" value="0" style="display: none;" />
                                                <input type="submit" value="Éditer mon jour" class="btn pt-1 pl-2 pb-1 pr-2 border bg-light w-50" />
                                            </div>';
                                        echo '<input type="submit" value="Valider mon jour" class="float-right mt-5 ml-0 mr-5 btn send border-0 bg-success z-depth-1a align-middle text-white" style="width: 40%;" onClick="change()" />
                                            <a href="javascript:history.go(-1)" value="return" class="float-left mt-5 ml-5 mr-0 btn finish border-0 bg-white z-depth-1a align-middle text-dark" style="width: 40%;">Précédent</a>';
                                    } else {
                                        echo '<div class="m-auto text-center">
                                            <a href="javascript:history.go(-1)" value="return" class="mt-5 ml-auto ml-5 mr-auto btn finish border-0 bg-white z-depth-1a align-middle text-dark w-75">Précédent</a>
                                        </div>';
                                    }

                                    mysqli_free_result($result);
                                echo '</fieldset>';
                            } else {
                                echo "No records matching your query were found.";
                            }
                        } else{
                            echo "ERROR: Could not able to execute $recap. " . mysqli_error($db);
                        }
                        
                        mysqli_close($db);
                    ?>
                </form>
            </div>
        </div>

<?php include 'footer.php'; ?>