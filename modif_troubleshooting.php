<?php
session_start();
include './api/config/db_connexion.php';
//require './api/troubleshooting/add_site.php';

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
$stmt_admin = $bdd->prepare("SELECT id FROM `admin` WHERE admin_name = '". $_SESSION['admin_name'] ."'");
$stmt_admin->execute();
$admin = $stmt_admin->fetch();
if($user) {
    $_SESSION['id'] = $user['id'];
} elseif ($admin and empty($user)) {
    $_SESSION['id'] = $admin['id'];
} else {
    echo "ERROR: Could not get 'id' of current user [first_method]";
}

$sql = $bdd->prepare("SELECT * FROM chantiers WHERE id =" . $_GET['id']);
$sql->execute();
$cur_chant = $sql->fetch();

?>

<!DOCTYPE html>

<html class="overflow-y mb-0">
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
                    <a href="troubleshooting_list.php" class="text-white pl-3"><i class="menu-btn-plus fas fa-search text-warning fa-3x rounded-circle"></i></a>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div id="container">
            <div class="content">
                <h3 class="text-center mt-0 mb-3 pt-5">Édition d'un chantier</h3>
                <form class="w-100 pt-3 pl-4 pb-0 pr-4" action="./api/troubleshooting/edit_site.php" method="POST">
                    <?php
                        if ($_SESSION['id'] == $admin['id']) {
                            if ($cur_chant['state']) {
                                echo '<button type="submit" id="state" name="state" value="0" class="bg-danger text-white float-right">Clôturer le chantier</button>';
                                echo "<input type='number' value='" . $cur_chant['id'] . "' id='id' name='id' style='display: none;'>";
                                echo "<input type='number' value='" . $cur_chant['num_chantier'] . "' id='num_chantier' name='num_chantier' style='display: none;'>";
                                echo "<input type='text' value='" . $cur_chant['name'] . "' id='name' name='name' style='display: none;'>";
                                echo "<input type='text' value='" . $cur_chant['contact_name'] . "' id='contact_name' name='contact_name' style='display: none;'>";
                                echo "<input type='text' value='" . $cur_chant['contact_phone'] . "' id='contact_phone' name='contact_phone' style='display: none;'>";
                                echo "<input type='text' value='" . $cur_chant['contact_address'] . "' id='contact_address' name='contact_address' style='display: none;'>";
                                echo "<input type='text' value='" . $cur_chant['commit'] . "' id='commit' name='commit' style='display: none;'>";
                            } else {
                                echo '<button type="submit" id="state" name="state" value="1" class="bg-success text-white float-right">Réouvrir le chantier</button>';
                                echo "<input type='number' value='" . $cur_chant['id'] . "' id='id' name='id' style='display: none;'>";
                                echo "<input type='number' value='" . $cur_chant['num_chantier'] . "' id='num_chantier' name='num_chantier' style='display: none;'>";
                                echo "<input type='text' value='" . $cur_chant['name'] . "' id='name' name='name' style='display: none;'>";
                                echo "<input type='text' value='" . $cur_chant['contact_name'] . "' id='contact_name' name='contact_name' style='display: none;'>";
                                echo "<input type='text' value='" . $cur_chant['contact_phone'] . "' id='contact_phone' name='contact_phone' style='display: none;'>";
                                echo "<input type='text' value='" . $cur_chant['contact_address'] . "' id='contact_address' name='contact_address' style='display: none;'>";
                                echo "<input type='text' value='" . $cur_chant['commit'] . "' id='commit' name='commit' style='display: none;'>";
                            }
                        }
                    ?>
                </form>
                <form class="w-100 pt-3 pl-4 pb-0 pr-4" action="./api/troubleshooting/edit_site.php" method="POST">
                    <?php
                        if ($_SESSION['id'] == $admin['id']) {
                            echo "<div class='md-form mt-1'>
                                <div class='md-form mt-2'>
                                    <label for='num_chantier'>ID de chantier</label>
                                    <input type='number' value='" . $cur_chant['state'] . "' id='state' name='state' style='display: none;'>
                                    <input type='number' value='" . $cur_chant['id'] . "' id='id' name='id' style='display: none;'>
                                    <input type='number' value='" . $cur_chant['num_chantier'] . "' id='num_chantier' name='num_chantier' class='form-control'>
                                </div>
                                <div class='md-form mt-4'>
                                    <label for='name'>Libellé de chantier</label>
                                    <input type='text' id='name' name='name' class='form-control' value='" . $cur_chant['name'] . "' required>
                                </div>
                                <div class='md-form mt-4'>
                                    <label for='contact_name'>Nom du client</label>
                                    <input type='text' id='contact_name' name='contact_name' class='form-control' value='" . $cur_chant['contact_name'] . "' required>
                                </div>
                                <div class='md-form mt-4'>
                                    <label for='contact_phone'>Téléphone</label>
                                    <input type='text' id='contact_phone' name='contact_phone' class='form-control' value='" . $cur_chant['contact_phone'] . "'>
                                </div>
                                <div class='md-form mt-4'>
                                    <label for='contact_address'>Adresse</label>
                                    <input type='text' id='contact_address' name='contact_address' class='form-control' value='" . $cur_chant['contact_address'] . "'>
                                </div>
                                <div class='md-form mt-4'>
                                    <label for='commit'>Commentaires</label>
                                    <textarea type='text' id='commit' name='commit' class='form-control' value='" . $cur_chant['commit'] . "' placeholder='" . $cur_chant['commit'] . "'></textarea>
                                </div>
                            </div>";
                        } else {
                            echo "<div class='md-form mt-1'>
                                <div class='md-form mt-2'>
                                    <label for='num_chantier'>ID de chantier</label>
                                    <input type='number' id='num_chantier' name='num_chantier' class='form-control' value='" . $cur_chant['num_chantier'] . "' disabled>
                                </div>
                                <div class='md-form mt-4'>
                                    <label for='name'>Libellé de chantier</label>
                                    <input type='text' id='name' name='name' class='form-control' value='" . $cur_chant['name'] . "' disabled>
                                </div>
                                <div class='md-form mt-4'>
                                    <label for='contact_name'>Nom du client</label>
                                    <input type='text' id='contact_name' name='contact_name' class='form-control' value='" . $cur_chant['contact_name'] . "' required>
                                </div>
                                <div class='md-form mt-4'>
                                    <label for='contact_phone'>Téléphone</label>
                                    <input type='text' id='contact_phone' name='contact_phone' class='form-control' value='" . $cur_chant['contact_phone'] . "'>
                                </div>
                                <div class='md-form mt-4'>
                                    <label for='contact_address'>Adresse</label>
                                    <input type='text' id='contact_address' name='contact_address' class='form-control' value='" . $cur_chant['contact_address'] . "'>
                                </div>
                                <div class='md-form mt-4'>
                                    <label for='commit'>Commentaires</label>
                                    <textarea type='text' id='commit' name='commit' class='form-control' value='" . $cur_chant['commit'] . "' placeholder='" . $cur_chant['commit'] . "'></textarea><br />
                                </div>
                            </div>";
                        }
                    ?>
                    <!--<input type="text" id="type" name="type" value="<?php// echo $cur_chant['type']; ?>" style="display: none;">-->
                    <div class="pt-5 w-75 m-auto">
                        <input type="submit" value="Valider" class="btn send border-0 bg-white z-depth-1a mt-3 mb-4 text-dark">
                        <a href="troubleshooting_list.php" value="return" class="btn finish border-0 bg-white z-depth-1a mt-1 mb-4 text-dark">Précédent</a>
                    </div>
                </form>
            </div>
        </div>
    </body>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="js/script.js"></script>
    <script src="js/bootstrap.js"></script>
</html>