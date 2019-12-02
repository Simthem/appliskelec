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

$stmt_admin = $bdd->prepare("SELECT id FROM `admin` WHERE admin_name = '". $_SESSION['admin_name'] ."'");
$stmt_admin->execute();
$admin = $stmt_admin->fetch();

if($user) {
    $_SESSION['id'] = $user['id'];
} elseif ($admin) {
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
                <form id="sign_ab" action="" method="">
                    <?php echo "<input type='number' id='user_id' name='user_id' value='" . $_SESSION['id'] . "' style='display: none;'>"; ?>
                        <div class="text-center">
                            <?php
                                echo '<div class="m-auto p-3">';
                                if (isset($_GET['store']) && !empty($_GET['store'])) {
                                    $date = date_create($_GET['store']);
                                    echo '<div class="text-center w-75 mr-auto ml-auto pb-4"><input class="bg-white col-7 text-center pl-4" type="date" id="up_inter" name="up_inter" value="' . $_GET['store'] . '" placeholder="' . date_format($date, "d-m-Y") . '" onChange="date_ab(' . $_GET['id'] . ')" onfocus="(this.type=\'date\')" onblur="if(this.value==\'\'){this.type=\'text\'}" style="height: 26px;" required="required"></div>';
                                } else {
                                    echo '<div class="text-center w-75 mr-auto ml-auto pb-4"><input class="bg-white col-7 text-center pl-4" type="date" id="up_inter" name="up_inter"  placeholder="" onChange="date_ab(' . $_GET['id'] . ')" style="height: 26px;" required="required"></div>';
                                }
                                echo '</div>';
                            ?>
                            <div class="bg-white border rounded m-auto" style="width: max-content">
                                <?php
                                echo '<select id="chantier_name" name="chantier_name" class="bg-white border-white" size="1" required>';

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
                            echo '<div class="w-100 m-auto p-5">
                            <div class="col-6 float-left">
                                <input type="checkbox" id="chantier" name="chantier" value="1" class="form-check-input align-middle mt-1 mb-auto" />
                                <label class="mb-auto mt-auto ml-4 pl-1 text-center" for="chantier">Par chantier</label>
                            </div>
                                <div class="col-6 float-right">
                                    <input type="checkbox" id="ab_day" name="ab_day" value="1" class="form-check-input align-middle mt-1 mb-auto" />
                                    <label class="mb-auto mt-auto ml-4 pl-1 text-center" for="ab_day">Par journée</label>
                                </div>
                                
                                <div name="flag_chant" class="d-none">
                                    <h5 class="w-100 text-center">Ca fonctionne ! [chantier]</h5>
                                    <div class="border-0 p-0 mt-2 ml-auto mr-auto mb-2 col-7">
                                        <input id="intervention_hours" name="intervention_hours" value="" style="display: none;" />
                                        <select type="number" id="h_index" class="col-3 p-0 border-0 rounded bg-secondary text-white text-center" style="height: 19px;">
                                            <option value="0" selected>0</option>
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
                                <div name="flag_day" class="d-none">
                                    <h5 class="w-100 text-center">Ca fonctionne ! [jour]</h5>
                                </div>
                            </div>';
                            ?>
                </form>
            </div>
        </div>

<?php include 'footer.php'; ?>