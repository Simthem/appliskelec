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

$sql = $bdd->prepare("SELECT * FROM chantiers WHERE id =" . $_GET['id']);
$sql->execute();
$cur_chant = $sql->fetch();

?>

<!DOCTYPE html>

<html class="overflow-y ml-auto mr-auto mb-0">

    <?php include 'header.php'; ?>

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
                                    <label for='contact_phone'>E-mail</label>
                                    <input type='email' id='e_mail' name='e_mail' class='form-control' value='" . $cur_chant['e_mail'] . "'>
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
                                    <label for='contact_phone'>E-mail</label>
                                    <input type='email' id='e_mail' name='e_mail' class='form-control' value='" . $cur_chant['e_mail'] . "'>
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
                        <input type="submit" value="Valider" class="btn send border-0 bg-white z-depth-1a mt-3 mb-4 text-dark" onClick="checkForm()">
                        <a href="troubleshooting_list.php" value="return" class="btn finish border-0 bg-white z-depth-1a mt-1 mb-4 text-dark">Précédent</a>
                    </div>
                </form>
            </div>
        </div>
    </body>

    <?php include 'footer.php'; ?>