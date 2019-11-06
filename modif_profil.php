<?php
session_start();
include 'api/config/db_connexion.php';
//require_once 'api/user/edit_profil.php';
/*
if(!($_SESSION['username'])) {  
  
    header("Location: signin.php");//redirect to login page to secure the welcome page without login access.  
}*/

if (isset($_COOKIE['id'])) {
    $auth = explode('---', $_COOKIE['id']);
 
    if (count($auth) === 2) {
        $req = $bdd->prepare('SELECT id, username, `password` FROM users WHERE id = :id');
        $req->execute([ ':id' => $auth[0] ]);
        $user = $req->fetch(PDO::FETCH_ASSOC);
         
        if ($user && $auth[1] === hash('sha512', $user['username'].'---'.$user['password'])) {
            // Ce que tu avais mis pour ta session à la connection
            $_SESSION['id'] = $user['id'];
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
         
        if ($admin && $auth[1] === hash('sha512', $admin['admin_name'].'---'.$user['admin_pass'])) {
            // Ce que tu avais mis pour ta session à la connection
            $_SESSION['id'] = $admin['id'];
            $_SESSION['username'] = "admin";
        } else {
            header("Location: signin.php");//redirect to login page to secure the welcome page without login access.  
        }
    }
}

$stmt = $bdd->prepare("SELECT id FROM users WHERE username = '". $_SESSION['username'] ."'");
$stmt->execute();
$user = $stmt->fetch();
$stmt_admin = $bdd->prepare("SELECT * FROM `admin` WHERE admin_name = '". $_SESSION['admin_name'] ."'");
$stmt_admin->execute();
$admin = $stmt_admin->fetch();
if($user) {
    $_SESSION['id'] = $user['id'];
} elseif ($admin and empty($user)) {
    $_SESSION['id'] = $admin['id'];
} else {
    echo "ERROR: Could not get 'id' of current user [first_method]";
}
?>

<!DOCTYPE html>

<?php
    if ($_SESSION['id'] != $_GET['id'] and $_SESSION['id'] != $admin['id']) {
        echo "<html class='overflow-hidden'>";
    } else {
        echo "<html class='overflow-y mb-0'>";
    }
?>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <link  rel="stylesheet" href="assets/css/bootstrap.css">
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
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
                                <li class="bg-dark border-top border-warning rounded-0 p-0 menu-link"><a href="#" class="text-warning">Paramètres</a></li>
                                <li class="bg-dark border-top border-bottom border-warning rounded-0 p-0 menu-link"><a href="signin.php" class="text-warning">Déconnexion</a></li>
                        </ul>
                    </div>
                </nav>
                <div class="icons-navbar">
                    <div class="menu-btn-bars text-white"><button class="menu-btn fas fa-bars text-warning w-100 fa-3x p-0"></button></div>
                    <a href="index.php" class="text-warning m-auto"><h2 class="m-0">S.K.elec</h2></a>
                    <a href="list_profil.php" class="text-white pl-3"><i class="menu-btn-plus fas fa-search text-warning fa-3x rounded-circle"></i></a>
                </div>
            </div>
        </header>

        <?php
            $sql = $bdd->prepare(
            "SELECT 
                c.id AS chantier_id,
                c.created as date_chantier,
                concat(year(g.created),
                month(g.created),
                week(g.created)),
                c.name AS name_chantier,
                username,
                u.id AS user_id,
                SUM(intervention_hours) AS totalheure,
                if (SUM(intervention_hours) - 350000 > 0, if( SUM(intervention_hours) - 350000 > 80000, 80000, SUM(intervention_hours) - 350000), NULL) AS maj25,
                if (SUM(intervention_hours) > 430000, SUM(intervention_hours) - 430000, NULL) AS maj50,
                SUM(night_hours) AS h_night
            FROM
                chantiers AS c
                JOIN
                global_reference AS g ON c.id = chantier_id
                JOIN
                users AS u ON g.user_id = u.id
            WHERE
                u.id = '". $_GET['id'] ."'
                #g.created BETWEEN \'2019-10-01\' AND \'2019-11-30\'
            GROUP BY c.id , u.id , c.created , username , c.name , concat(year(g.created) , month(g.created), week(g.created)) with ROLLUP");
            
            $sql->execute();
            while ($total_user = $sql->fetch()) {
                if ($total_user['chantier_id'] == NULL) {
                    $total['totalheure'] = $total_user['totalheure'];
                    $m25['maj25'] = $total_user['maj25'];
                    $m50['maj50'] = $total_user['maj50'];
                    $mnight = $total_user['h_night'];
                } 
            }

            if(($_GET['id'] != $admin['id'] and $_SESSION['id'] == $_GET['id'] and $_SESSION['id'] == $user['id']) or $_SESSION['id'] == $admin['id']) {
        ?>
        <!-- Content -->
                <div id="container">
                    <div class="content">
                        <h3 class="text-center mt-0 mb-3 pt-5">Édition du compte</h3>
                        <form class="w-100 pt-2 pl-4 pb-0 pr-4" action="api/user/edit_profil.php" method="POST">
                            <?php
                                $stmt = $bdd->prepare("SELECT * FROM users WHERE id = '". $_GET['id'] ."'");
                                $stmt->execute();
                                $user = $stmt->fetch();
                                if($user) {
                                    $modif_user['id'] = $user['id'];
                                    $modif_user['username'] = $user['username'];
                                    $modif_user['first_name'] = $user['first_name'];
                                    $modif_user['last_name'] = $user['last_name'];
                                    $modif_user['e_mail'] = $user['e_mail'];
                                    $modif_user['phone'] = $user['phone'];

                                    echo '<input type="text" value="' . $modif_user['id'] . '" id="id" name="id" style="display: none;"">';
                                    echo '<div class="md-form mt-1">';
                                        echo '<label for="fusername">Username</label>';
                                        echo '<input type="text" value="' . $modif_user['username'] . '" id="username" name="username" class="form-control" placeholder="' . $modif_user['username'] . '"">';
                                    echo '</div>';
                                    echo '<div class="md-form mt-4">';
                                        echo '<label for="first-name">First name</label>';
                                        echo '<input type="text" value="' . $modif_user['first_name'] . '" id="first_name" name="first_name" class="form-control" placeholder="' . $modif_user['first_name'] . '">';
                                    echo '</div>';
                                    echo '<div class="md-form mt-4">';
                                        echo '<label for="last_name">Last name</label>';
                                        echo '<input type="text" value="' . $modif_user['last_name'] . '" id="last_name" name="last_name" class="form-control" placeholder="' . $modif_user['last_name'] . '">';
                                    echo '</div>';
                                    echo '<div class="md-form mt-4">';
                                        echo '<label for="e_mail">E_mail</label>';
                                        echo '<input type="email" value="' . $modif_user['e_mail'] . '" id="e-mail" name="e_mail" class="form-control" placeholder="' . $modif_user['e_mail'] . '">';
                                    echo '</div>';
                                    echo '<div class="md-form mt-4">';
                                        echo '<label for="phone">Téléphone</label>';
                                        echo '<input type="text" value="' . $modif_user['phone'] . '" id="phone" name="phone" class="form-control" placeholder="' . $modif_user['phone'] . '">';
                                    echo '</div>';
                                    echo '<div class="d-inline-flex">';
                                        echo '<div class="md-form mt-4 col-4">';
                                            echo '<label class="w-100 text-center" for="total_hours">H/totales</label>';?>
                                            <input type="number" value="<?php 
                                                $total = $total['totalheure'];
                                                $hours = (int)($total / 10000);
                                                $minutes = ((int)($total - ($hours * 10000)) / 100) / 60;
                                                $total = $hours + $minutes;
                                                echo $total;?>" 
                                            id="total_hours" name="total_hours" class="form-control" placeholder="<?php
                                                echo $total;?>" step=".01"
                                            disabled>
                                        <?php 
                                        echo '</div>';
                                        echo '<div class="md-form mt-4 col-4">';
                                            echo '<label class="w-100 text-center" for="total_hours">H + 25%</label>';?>
                                            <input type="number" value="<?php
                                                $m25 = $m25['maj25'];
                                                $hours = (int)($m25 / 10000);
                                                $minutes = ((int)($m25 - ($hours * 10000)) / 100) / 60;
                                                $m25 = $hours + $minutes;
                                                echo $m25;?>"
                                            id="total_hours" name="total_hours" class="form-control" placeholder="<?php
                                                echo $m25;?>" step=".01"
                                            disabled>
                                        <?php 
                                        echo '</div>';
                                        echo '<div class="md-form mt-4 col-4">';
                                            echo '<label class="w-100 text-center" for="total_hours">H + 50%</label>';?>
                                            <input type="number" value="<?php
                                                $m50 = $m50['maj50'];
                                                $hours = (int)($m50 / 10000);
                                                $minutes = ((int)($m50 - ($hours * 10000)) / 100) / 60;
                                                $hnight = (int)($mnight / 10000);
                                                $min_night = ((int)($mnight - ($hnight * 10000)) / 100) / 60;
                                                $m50 = $hours + $hnight + $minutes + $min_night;
                                                echo $m50;?>"
                                            id="total_hours" name="total_hours" class="form-control" placeholder="<?php
                                                echo $m50;?>" step=".01"
                                            disabled>
                                        <?php 
                                        echo '</div>';
                                    echo '</div>';

                                    if (($_SESSION['id'] == $admin['id']) or ($_SESSION['id'] == $user['id'] and $_GET['id'] == $user['id'])) {
                                        echo '<div class="md-form mt-4">';
                                            echo '<label for="pass1">Password</label>';
                                            echo '<input type="password" id="pass1" name="pass1" class="form-control" data-type="password" required>';
                                        echo '</div>';
                                        echo '<div class="md-form mt-4">';
                                            echo '<label for="pass2">Confirm Password</label>';
                                            echo '<input type="password" id="pass2" name="pass2" class="form-control">';
                                        echo '</div>';
                                    }
                                } elseif ($admin) {

                                    $admin_sql = $bdd->prepare("SELECT 
                                        c.id AS chantier_id,
                                        c.created as date_chantier,
                                        concat(year(g.created),
                                        month(g.created),
                                        week(g.created)),
                                        c.name AS name_chantier,
                                        admin_name,
                                        a.id AS admin_id,
                                        SUM(intervention_hours) AS totalheure,
                                        if (SUM(intervention_hours) - 350000 > 0, if( SUM(intervention_hours) - 350000 > 430000, 80000, SUM(intervention_hours) - 350000), NULL) AS maj25,
                                        if (SUM(intervention_hours) > 430000, SUM(intervention_hours) - 430000, NULL) AS maj50,
                                        SUM(night_hours) AS h_night
                                    FROM
                                        chantiers AS c
                                        JOIN
                                        global_reference AS g ON c.id = chantier_id
                                        JOIN
                                        admin AS a ON g.user_id = a.id
                                    WHERE
                                        a.id = '" . $admin['id'] . "'
                                        #g.created BETWEEN \'2019-10-01\' AND \'2019-11-30\'
                                    GROUP BY c.id , a.id , c.created , admin_name , c.name , concat(year(g.created) , month(g.created), week(g.created)) with ROLLUP");
                                    
                                    $admin_sql->execute();
                                    while ($total_admin = $admin_sql->fetch()) {
                                        if ($total_admin['chantier_id'] == NULL) {
                                            $total['totalheure'] = $total_admin['totalheure'];
                                            $m25['maj25'] = $total_admin['maj25'];
                                            $m50['maj50'] = $total_admin['maj50'];
                                            $mnight = $total_admin['h_night'];
                                        } 
                                    }
                                    
                                    if ($_SESSION['id'] == $admin['id'] and $admin['id'] == $_GET['id']) {
                                        /*
                                        $_SESSION['id'] = $admin['id'];
                                        $_SESSION['username'] = $admin['admin_name'];
                                        $_SESSION['first_name'] = $admin['first_name'];
                                        $_SESSION['last_name'] = $admin['last_name'];
                                        $_SESSION['e_mail'] = $admin['e_mail'];
                                        $_SESSION['phone'] = $admin['phone'];
                                        $_SESSION['total_hours'] = $admin['total_hours'];*/

                                        echo '<input type="text" value="' . $admin['id'] . '" id="id" name="id" style="display: none;"">';
                                        echo '<div class="md-form mt-1">';
                                            echo '<label for="fusername">Username</label>';
                                            echo '<input type="text" value="' . $admin['admin_name'] . '" id="username" name="username" class="form-control" placeholder="' . $admin['username'] . '"">';
                                        echo '</div>';
                                        echo '<div class="md-form mt-4">';
                                            echo '<label for="first-name">First name</label>';
                                            echo '<input type="text" value="' . $admin['first_name'] . '" id="first_name" name="first_name" class="form-control" placeholder="' . $admin['first_name'] . '">';
                                        echo '</div>';
                                        echo '<div class="md-form mt-4">';
                                            echo '<label for="last_name">Last name</label>';
                                            echo '<input type="text" value="' . $admin['last_name'] . '" id="last_name" name="last_name" class="form-control" placeholder="' . $admin['last_name'] . '">';
                                        echo '</div>';
                                        echo '<div class="md-form mt-4">';
                                            echo '<label for="e_mail">E_mail</label>';
                                            echo '<input type="email" value="' . $admin['e_mail'] . '" id="e-mail" name="e_mail" class="form-control" placeholder="' . $admin['e_mail'] . '">';
                                        echo '</div>';
                                        echo '<div class="md-form mt-4">';
                                            echo '<label for="phone">Téléphone</label>';
                                            echo '<input type="text" value="' . $admin['phone'] . '" id="phone" name="phone" class="form-control" placeholder="' . $admin['phone'] . '">';
                                        echo '</div>';
                                        echo '<div class="d-inline-flex">';
                                            echo '<div class="md-form mt-4 col-4">';
                                                echo '<label class="w-100 text-center" for="total_hours">H/totales</label>';?>
                                                <input type="number" value="<?php 
                                                    $total = $total['totalheure'];
                                                    $hours = (int)($total / 10000);
                                                    $minutes = ((int)($total - ($hours * 10000)) / 100) / 60;
                                                    $total = $hours + $minutes;
                                                    echo $total;?>" 
                                                id="total_hours" name="total_hours" class="form-control" placeholder="<?php 
                                                    echo $total;?>" step=".01"
                                                disabled>
                                            <?php 
                                            echo '</div>';
                                            echo '<div class="md-form mt-4 col-4">';
                                                echo '<label class="w-100 text-center" for="total_hours">H + 25%</label>';?>
                                                    <input type="number" value="<?php
                                                    $m25 = $m25['maj25'];
                                                    $hours = (int)($m25 / 10000);
                                                    $minutes = ((int)($m25 - ($hours * 10000)) / 100) / 60;
                                                    $m25 = $hours + $minutes;
                                                    echo $m25;?>"
                                                id="total_hours" name="total_hours" class="form-control" placeholder="<?php
                                                    echo $m25;?>" step=".01"
                                                disabled>
                                            <?php 
                                            echo '</div>';
                                            echo '<div class="md-form mt-4 col-4">';
                                                echo '<label class="w-100 text-center" for="total_hours">H + 50%</label>';?>
                                                <input type="number" value="<?php
                                                    $m50 = $m50['maj50'];
                                                    $hours = (int)($m50 / 10000);
                                                    $minutes = ((int)($m50 - ($hours * 10000)) / 100) / 60;
                                                    $hnight = (int)($mnight / 10000);
                                                    $min_night = ((int)($mnight - ($hnight * 10000)) / 100) / 60;
                                                    $m50 = $hours + $hnight + $minutes + $min_night;
                                                    echo $m50;?>"
                                                id="total_hours" name="total_hours" class="form-control" placeholder="<?php
                                                    echo $m50;?>" step=".01"
                                                disabled>
                                            <?php 
                                            echo '</div>';
                                        echo '</div>';
                                        echo '<div class="md-form mt-4">';
                                            echo '<label for="pass1">Password</label>';
                                            echo '<input type="password" id="pass1" name="pass1" class="form-control" data-type="password" required>';
                                        echo '</div>';
                                        echo '<div class="md-form mt-4">';
                                            echo '<label for="pass2">Confirm Password</label>';
                                            echo '<input type="password" id="pass2" name="pass2" class="form-control" data-type="password" required>';
                                        echo '</div>';
                                    
                                    }
                                } else {
                                    echo "ERROR : Could not get 'id' of current user [second_method]";
                                }
                            ?>
                            <div class="pt-5 w-75 m-auto">
                                <input type="submit" value="Valider" class="btn send border-0 bg-white z-depth-1a mt-3 mb-4 text-dark">
                                <a href="javascript:history.go(-1)" value="return" class="btn finish border-0 bg-white z-depth-1a mt-1 mb-4 text-dark">Précédent</a>
                            </div>
                        </form>
                        
                    </div>
                </div>
        <?php 
            } else { 
        ?>
            <div id="container">
                <div class="content">
                    <h3 class="text-center mt-0 mb-3 pt-5">Détails d'un compte</h3>
                    <form class="w-100 pt-2 pl-4 pb-0 pr-4">
                        <?php
                            $stmt = $bdd->prepare("SELECT * FROM users WHERE id = '". $_GET['id'] ."'");
                            $stmt->execute();
                            $user = $stmt->fetch();
                            if($user) {
                                //$modif_user['id'] = $user['id'];
                                $modif_user['username'] = $user['username'];
                                $modif_user['first_name'] = $user['first_name'];
                                $modif_user['last_name'] = $user['last_name'];
                                $modif_user['e_mail'] = $user['e_mail'];
                                $modif_user['phone'] = $user['phone'];

                                //echo '<input type="text" value="' . $modif_user['id'] . '" id="id" name="id" style="display: none;"">';
                                echo '<div class="md-form mt-1">';
                                    echo '<label for="fusername" class="text-secondary">Username</label>';
                                    echo '<input type="text" value="' . $modif_user['username'] . '" id="username" name="username" class="form-control" placeholder="' . $modif_user['username'] . '"" disabled>';
                                echo '</div>';
                                echo '<div class="md-form mt-4">';
                                    echo '<label for="first-name" class="text-secondary">First name</label>';
                                    echo '<input type="text" value="' . $modif_user['first_name'] . '" id="first_name" name="first_name" class="form-control" placeholder="' . $modif_user['first_name'] . '" disabled>';
                                echo '</div>';
                                echo '<div class="md-form mt-4">';
                                    echo '<label for="last_name" class="text-secondary">Last name</label>';
                                    echo '<input type="text" value="' . $modif_user['last_name'] . '" id="last_name" name="last_name" class="form-control" placeholder="' . $modif_user['last_name'] . '" disabled>';
                                echo '</div>';
                                echo '<div class="md-form mt-4">';
                                    echo '<label for="e_mail" class="text-secondary">E_mail</label>';
                                    echo '<input type="email" value="' . $modif_user['e_mail'] . '" id="e-mail" name="e_mail" class="form-control" placeholder="' . $modif_user['e_mail'] . '" disabled>';
                                echo '</div>';
                                echo '<div class="md-form mt-4">';
                                    echo '<label for="phone" class="text-secondary">Téléphone</label>';
                                    echo '<input type="text" value="' . $modif_user['phone'] . '" id="phone" name="phone" class="form-control" placeholder="' . $modif_user['phone'] . '" disabled>';
                                echo '</div>';
                                echo '<div class="d-inline-flex">';
                                    echo '<div class="md-form mt-4 col-4">';
                                        echo '<label class="w-100 text-center" for="total_hours">H/totales</label>';?>
                                        <input type="number" value="<?php 
                                            $total = $total['totalheure'];
                                            $hours = (int)($total / 10000);
                                            $minutes = ((int)($total - ($hours * 10000)) / 100) / 60;
                                            $total = $hours + $minutes; 
                                            echo $total;?>" id="total_hours" name="total_hours" class="form-control" placeholder="<?php
                                            echo $total;?>" step=".01"
                                        disabled>
                                    <?php
                                    echo '</div>';
                                    echo '<div class="md-form mt-4 col-4">';
                                        echo '<label class="w-100 text-center" for="total_hours">H + 25%</label>';?>
                                        <input type="number" value="<?php
                                            $m25 = $m25['maj25'];
                                            $hours = (int)($m25 / 10000);
                                            $minutes = ((int)($m25 - ($hours * 10000)) / 100) / 60;
                                            $m25 = $hours + $minutes;
                                            echo $m25;?>"
                                        id="total_hours" name="total_hours" class="form-control" placeholder="<?php
                                            echo $m25;?>" step=".01"
                                        disabled>
                                    <?php 
                                    echo '</div>';
                                    echo '<div class="md-form mt-4 col-4">';
                                        echo '<label class="w-100 text-center" for="total_hours">H + 50%</label>';?>
                                        <input type="number" value="<?php
                                            $m50 = $m50['maj50'];
                                            $hours = (int)($m50 / 10000);
                                            $minutes = ((int)($m50 - ($hours * 10000)) / 100) / 60;
                                            $hnight = (int)($mnight / 10000);
                                            $min_night = ((int)($mnight - ($hnight * 10000)) / 100) / 60;
                                            $m50 = $hours + $hnight + $minutes + $min_night;
                                            echo $m50;?>"
                                        id="total_hours" name="total_hours" class="form-control" placeholder="<?php
                                            echo $m50;?>" step=".01"
                                        disabled>
                                    <?php 
                                    echo '</div>';
                                echo '</div>';
                            } else {
                                echo "ERROR: Could not get 'id' of current user [third_method]";
                            }
                        ?>
                        <div class="pt-5 w-75 m-auto">
                            <!--<input type="submit" value="Valider" class="btn send border-0 bg-white z-depth-1a mt-3 mb-4 text-dark">-->
                            <a href="javascript:history.go(-1)" value="return" class="btn finish border-0 bg-white z-depth-1a mt-1 mb-4 text-dark">Précédent</a>
                        </div>
                    </form>
            <?php } ?>

            </div>
        </div>
    </body>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="js/script.js"></script>
    <script src="js/bootstrap.js"></script>
</html>