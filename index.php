<?php
session_start();


include 'api/config/db_connexion.php';
//include 'api/user/login.php';

if(!($_SESSION['username'])) {  
  
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
    echo "ERROR: Could not get 'id' of current user [first_method]";
}
//$date_now = date('Y-m-d');
?>

<!DOCTYPE html>

<html class="overflow-y mb-0">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=0">
        <meta name="apple-mobile-web-app-capable" content="yes">
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
                            <li class="bg-dark border-top border-warning rounded-0 p-0 menu-link"><a href="#" class="text-warning">Paramètres</a></li>
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

        <!-- Content -->
        <div id="container">
            <div class="content">
                <form action="./api/index_global/create_intervention.php" method="POST">
                    <div class="m-auto p-3">
                        <h6 class="text-center w-75 mr-auto ml-auto"><input class="col-5 m-0 p-0 text-right" type="date" id="up_inter" name="up_inter"  placeholder="<?//php echo $date_now; ?>" required="required"></h6>
                        <div class="text-center"><?php echo $_SESSION['username']; ?></div>
                        <div class="text-center"><?php 
                                                    if($_SESSION['username'] == "admin") { 
                                                        echo "Administrateur de S.K.elec_app ;)";
                                                    }
                                                ?>
                        </div>
                    </div>
                    <?php echo "<input type='number' id='user_id' name='user_id' value='" . $_SESSION['id'] . "' style='display: none'>" ?>
                    <div class="text-center">
                        <select id="chantier_name" name="chantier_name" size="1" required>
                            <?php
                                $sql = 
                                "SELECT 
                                    id, `name` 
                                FROM 
                                    chantiers 
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
                            ?>
                        </select>
                    </div>
                    <div class="pt-5 w-50 m-auto text-center">
                        <label for="input_time m-auto">Heures réalisées</label>
                        <div class="w-50 m-auto pb-4">
                            <input type="time" id="intervention_hours" name="intervention_hours" class="form-control text-center align-middle m-auto p-1" style="line-height: 25px;" placeholder="hh:mm" required />
                        </div>
                    </div>
                    <div class="m-auto d-flex flex-column border-top pt-4 w-75">
                        <div class="pt-1 pb-3">
                            <div class="col-5 mb-2 p-0 position-relative">
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
                                <div class="col-8 d-inline-flex pr-0 pl-0 ml-auto">
                                    <input type="time" id="night_hours" name="night_hours" class="col-6 form-control text-center align-middle m-auto p-1 " style="line-height: 25px;" placeholder="minutes/heures">
                                    <label class="mt-auto ml-3 mb-auto text-wrap">heures de nuit</label>
                                </div>
                            </div>
                            <div class="mt-2 mb-2 pt-5 pb-2">
                                <textarea class="form-control" id="commit" name="commit" placeholder="Informations ?"></textarea>
                            </div>
                        </div>
                    </div>
                    <?php
                        echo '<div class="collapse" id="preview">
                            <p class="pl-2 text-dark bg-white border rounded w-75 m-auto">
                                Nom du chantier :  <input id="chant_name" class="border-0 p-0 mt-0 ml-auto mr-auto mb-0 w-50"><br />
                                Total des heures :  <input id="inter_h" class="border-0 p-0 mt-0 ml-auto mr-auto mb-0 w-50"><br />
                                Commentaires :  <input id="com" class="border-0 p-0 mt-0 ml-auto mr-auto mb-0 w-50"><br />
                                Panier repas :  <input id="pan_rep"class="border-0 p-0 mt-0 ml-auto mr-auto mb-0 w-50"><br />
                                Horaires de nuit :  <input id="h_night"class="border-0 p-0 mt-0 ml-auto mr-auto mb-0 w-50"><br />
                            </p>';
                        echo '<div class="w-75 ml-auto mr-auto">
                            <input type="submit" value="Soumettre" class="btn send border-0 bg-white z-depth-1a mt-3 mb-0 align-middle text-dark">
                        </div>';
                        ?>
                    </div>
                    <div class="mt-4 w-75 pt-3 mr-auto ml-auto">
                        <a data-toggle="collapse" href="#preview" role="button" aria-expanded="false" aria-controls="preview" class="btn send border-0 bg-white z-depth-1a mt-3 mb-4 text-dark" onClick="preview()">Prévisualiser</a>
                        <!--<a href="#" type="submit" value="Clotûrer le chantier" class="btn finish border-0 bg-white z-depth-1a mt-3 mb-1 text-dark">Clôturer le chantier</a>-->
                    </div>
                </form>
            </div>
        </div>
        <?php
            
        ?>
        <script type="text/javascript">
            function preview() {
                var chant_name = document.getElementById("chant_name");
                chant_name.value = document.getElementById("chantier_name").value;
                var inter_h = document.getElementById("inter_h");
                inter_h.value = document.getElementById("intervention_hours").value;
                var pan_rep = document.getElementById("pan_rep");
                pan_rep.value = document.getElementById("panier_repas").value;
                var pan_rep = document.getElementById("pan_rep");
                if ($("input[name='panier_repas']").is(":checked")) {
                    pan_rep.value = document.getElementById("panier_repas").value;
                } else {
                    pan_rep.value = 0;
                }
                var h_night = document.getElementById("h_night");
                if ($("input[name='coch_night']").is(":checked")) {
                    h_night.value = document.getElementById("night_hours").value;
                } else {
                    h_night.value = 0;
                }
                var com = document.getElementById("com");
                com.value = document.getElementById("commit").value;
                <?php $flag = 0; ?>
            }
        </script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="js/script.js"></script>
        <script src="js/bootstrap.js"></script>
        <?php
            mysqli_close($db);
        ?>
    </body>
    <footer>
    </footer>
</html>