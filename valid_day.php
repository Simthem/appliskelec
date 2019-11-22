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
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=0">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="apple-mobile-web-app-title" content="S.K.elec">
        <link  rel="stylesheet" href="assets/css/bootstrap.css">
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css?family=Lato:300,400,400italic,700,700italic" rel="stylesheet">
        <script src="https://kit.fontawesome.com/f14bbc71a6.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <title>Appli Skelec</title>
    </head>
    <body>
        <header class="header">
            <!-- Menu Button -->
            <div class="navbar-expand-md double-nav scrolling-navbar navbar-dark bg-dark">
                <!--Menu -->
                <nav class="menu left-menu">
                    <div class="menu-content">
                        <ul class="pl-0">
                            <li class="bg-dark border-top border-warning rounded-0 p-0 menu-link"><a href="troubleshooting_list.php" class="text-warning">Chantiers</a></li>
                            <li class="bg-dark border-top border-warning rounded-0 p-0 menu-link"><a href="list_profil.php" class="text-warning">Salariés</a></li>
                            
                            <!--<li class="bg-dark border-top border-warning rounded-0 p-0 collapsed"><a id="params" data-toggle="collapse" href="#submenu" role="button" aria-expanded="true" aria-controls="#submenu" class="text-warning open-col">Paramètres<div class="mr-0 float-right" style="width: 40px;"><img src="img/fleche_menu.png" class="ml-3 mt-auto mb-auto open-col" style="width: 13px; height: 13px;"></div></a>-->
                            <li data-toggle="collapse" href="#preview2" role="button" aria-expanded="false" aria-controls="preview2" class="bg-dark border-top border-warning rounded-0 p-0 collapsed text-warning"><a>Paramètres</a></li>
                                <div id="preview2" class="bg-light collapse" action='api/user/edit_profil.php' method='GET'>
                                    <?php
                                        if ($_SESSION['id'] == $admin['id']) {
                                            $admin_sql = "SELECT * FROM `admin`";
                                            if ($admin_result = mysqli_query($db, $admin_sql)){
                                                if (mysqli_num_rows($admin_result) > 0){
                                                    if ($db === false){
                                                        die("ERROR: Could not connect. " . mysqli_connect_error());
                                                    }
                                                    while($row = $admin_result->fetch_array()) {
                                                        echo "<li class='rounded-0 p-0 menu-link' style='height: 60px;'><a href='modif_profil.php?id=" . $row['id'] . "' class='mt-auto ml-auto mb-auto mr-auto text-dark w-75'><div class='mt-auto mb-auto pr-3 float-left'> • </div>Profile</a></li>";
                                                    }
                                                    mysqli_free_result($admin_result);
                                                } else {
                                                    echo "No records matching your query were found.";
                                                }
                                            } else {
                                                echo "ERROR: Could not able to execute $admin_sql. " . mysqli_error($db);
                                            }
                                            echo "<li class='rounded-0 p-0 menu-link border-top'><a href='extract_obj.php' class='pt-4 pr-0 pb-4 mt-auto ml-auto mb-auto mr-auto  text-dark w-75'><div class='mt-auto mb-auto pr-3 pt-3 float-left'> • </div><div class='w-100'>Extraire un compte rendu</div></a></li>";
                                        } else {
                                            $user_sql = "SELECT * FROM users";
                                            if ($user_result = mysqli_query($db, $user_sql)){
                                                if (mysqli_num_rows($user_result) > 0){
                                                    if ($db === false) {
                                                        die("ERROR: Could not connect. " . mysqli_connect_error());
                                                    }
                                                    while ($row = $user_result->fetch_array()){
                                                        if ($row['id'] == $_SESSION['id']) {
                                                            echo "<li><a href='modif_profil.php?id=" . $row['id'] . "' class='mt-auto ml-auto mb-auto mr-auto text-dark w-75'>Profile</a></li>";
                                                        }
                                                    }
                                                    mysqli_free_result($user_result);
                                                } else {
                                                    echo "No records matching your query were found.";
                                                }
                                            } else {
                                                echo "ERROR: Could not able to execute $admin_sql. " . mysqli_error($db);
                                            }
                                        }
                                    ?>
                                </div>
                            </li>
                            <li class="bg-dark border-top border-bottom border-warning rounded-0 p-0 menu-link"><a href="api/User/logout.php" class="text-warning">Déconnexion</a></li>
                        </ul>
                    </div>
                </nav>
                <div class="icons-navbar">
                    <div class="menu-btn-bars text-white"><button class="menu-btn fas fa-bars text-warning w-100 fa-3x p-0"></button></div>
                    <a href="index.php" class="text-warning d-inline-flex m-auto"><img class="mr-2 ml-2" src="img/ampoule_skelec.png" alt="logo S.K.elec" height="45" width="30"><h2 class="d-inline-flex mt-0 mr-2 mb-0 ml-0">S.K.elec</h2></a>
                    <a href="add_troubleshooting.php" class="text-white pl-3"><i class="menu-btn-plus fas fa-plus-circle text-warning fa-3x rounded-circle"></i></a>
                </div>
            </div>
        </header>


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

            //print_r($recap_2);
            if ($result = mysqli_query($db, $recap)) {

                if (mysqli_num_rows($result) > 0) {

                    //print_r($result);
                    $h_global = 0;
                    $h_ni_glob = 0;

                    if ($db === false){
                        die("ERROR: Could not connect. " . mysqli_connect_error());
                    }

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
                            echo '<div class="d-inline-flex h6 m-0">' . $row['name_chantier'] . '<input class="bg-white border-0 p-0 mt-0 ml-auto mr-auto mb-0 w-100" value=" : ' . $hours . 'h' . $minutes . ' [' . $night_h . 'h' . $night_m . ' h/nuit]" /></div><br />';
                        }
                    }
                    echo '<br />';

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

                    echo '<div class="d-inline-flex h6 m-0">Heures globales sur la journée du ' . $_GET['up_inter'] . ' <input class="bg-white border-0 p-0 mt-0 ml-auto mr-auto mb-0 w-100" value=" : ' . $hours . 'h' . $minutes . ' [' . $night_h . 'h' . $night_m . ' h/nuit]" /></div><br />';
                    mysqli_free_result($result);
                } else {
                    echo "No records matching your query were found.";
                }
            } else{
                echo "ERROR: Could not able to execute $recap. " . mysqli_error($db);
            }
            
            mysqli_close($db);
        ?>


        <footer>
        </footer>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="/js/script.js"></script>
        <script src="/js/jQuery.stayInWebApp-master/jquery.stayInWebApp.js"></script>
        <script src="/js/jQuery.stayInWebApp-master/jquery.stayInWebApp.min.js"></script>
        <script src="/js/bootstrap.js"></script>
        <script>
            $(function() {
                $.stayInWebApp();
            });
        </script>
        
    </body>
</html>