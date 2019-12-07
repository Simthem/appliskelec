<?php
session_start();

include '../config/db_connexion.php';

if(!($_SESSION['username'])) {  
  
    header('Location: ../../signin.php');//redirect to login page to secure the welcome page without login access.  
}

$stmt = $bdd->prepare("SELECT id FROM users WHERE username = '". $_SESSION['username'] ."'");
$stmt->execute();
$user = $stmt->fetch();
$stmt_admin = $bdd->prepare("SELECT * FROM `admin` WHERE admin_name = '". $_SESSION['admin_name'] ."'");
$stmt_admin->execute();
$admin = $stmt_admin->fetch();
if ($user) {
    $_SESSION['id'] = $user['id'];
} elseif ($admin) {
    $_SESSION['id'] = $admin['id'];
} else {
    echo "ERROR: Could not get 'id' of current user [first_method]";
}

if ($bdd === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}


if (isset($_POST['up_inter']) && !empty($_POST['up_inter'])) {

    $inter_glo = $bdd->prepare("SELECT * FROM global_reference WHERE updated = '" . $_POST['up_inter'] . "' AND `user_id`= '" . $_SESSION['id'] . "' ORDER BY id");
    $inter_glo->execute();

    if ($bdd === false){
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }

    $x = 0;

    while ($reponse = $inter_glo->fetchColumn(PDO::FETCH_ASSOC)) {
        $x += 1;
    }

    $inter_glo->closeCursor();
    $inter_glo->execute();

    
    if ($test = $inter_glo->fetchAll(PDO::FETCH_ASSOC)) {
        
        $i = 0;
        
        while ($i < $x) {

            $temp[$i] = $test[$i]['id'];

            if (isset($temp[$i]) AND !empty($temp[$i])) {

                if ((isset($_POST["tot_h_ab$i"]) AND (!empty($_POST["tot_h_ab$i"]) OR $_POST["tot_h_ab$i"] != 0)) AND (($_POST["tot_h$i"] == 0) OR empty($_POST["tot_h$i"]))) {

                    $sql = $bdd->prepare("SELECT * FROM global_reference WHERE updated = '" . $_POST['up_inter'] . "' AND `user_id`= '" . $_SESSION['id'] . "' AND id = '" . $test[$i]['id'] . "'");
                    $sql->execute();

                    $final = $sql->fetch();
                    $id = $final['id'];
                    $glo_id = $id . $_POST['gid'];

                    if (isset($_POST["chantier_id$i"]) AND !empty($_POST["chantier_id$i"])) {

                        if ($_POST["chantier_id$i"] != $final['chantier_id']) {

                            $newid = htmlspecialchars($_POST["chantier_id$i"]);
                            $insteridchant = $bdd->prepare("UPDATE global_reference SET chantier_id = '" . $newid . "' WHERE updated = '" . $_POST['up_inter'] . "' AND `user_id`= '" . $_SESSION['id'] . "' AND id = '" . $test[$i]['id'] . "'");
                            $insteridchant->execute(array($newid, $_POST["chantier_id$i"]));
                        }
                    }


                    $h_ab = explode(':', $_POST["tot_h_ab$i"]);
                    $m_ab = $h_ab[1] / 60;
                    $h_ab = $h_ab[0] + $m_ab;

                    echo $h_ab . 'absence';

                    if ($h_ab != $final['absence']) {
                        $newab = htmlspecialchars($h_ab);
                        $insertab = $bdd->prepare("UPDATE global_reference SET absence = '" . $newab . "' WHERE updated = '" . $_POST['up_inter'] . "' AND `user_id`= '" . $_SESSION['id'] . "' AND id = '" . $test[$i]['id'] . "'");
                        $insertab->execute(array($newab, $h_ab));
                    }
                    $i++;

                } else {

                    $sql = $bdd->prepare("SELECT * FROM global_reference WHERE updated = '" . $_POST['up_inter'] . "' AND `user_id`= '" . $_SESSION['id'] . "' AND id = '" . $test[$i]['id'] . "'");
                    $sql->execute();

                    $final = $sql->fetch();
                    $id = $final['id'];
                    $glo_id = $id . $_POST['gid'];

                    if (isset($_POST["chantier_id$i"]) AND !empty($_POST["chantier_id$i"])) {

                        if ($_POST["chantier_id$i"] != $final['chantier_id']) {

                            $newid = htmlspecialchars($_POST["chantier_id$i"]);
                            $insteridchant = $bdd->prepare("UPDATE global_reference SET chantier_id = '" . $newid . "' WHERE updated = '" . $_POST['up_inter'] . "' AND `user_id`= '" . $_SESSION['id'] . "' AND id = '" . $test[$i]['id'] . "'");
                            $insteridchant->execute(array($newid, $_POST["chantier_id$i"]));
                        }
                    }


                    if (isset($_POST["tot_h$i"]) AND !empty($_POST["tot_h$i"])) {

                        $norm_h = explode(':', $_POST["tot_h$i"]);
                        $norm_m = $norm_h[1] / 60;
                        $norm_h = $norm_h[0] + $norm_m;
                        
                        echo $norm_h . 'heures normales<br />';

                        if ($norm_h != $final['intervention_hours']) {
                            $new_normh = htmlspecialchars($norm_h);
                            $insertnormh = $bdd->prepare("UPDATE global_reference SET intervention_hours = '" . $new_normh . "' WHERE updated = '" . $_POST['up_inter'] . "' AND `user_id`= '" . $_SESSION['id'] . "' AND id = '" . $test[$i]['id'] . "'");
                            $insertnormh->execute(array($new_normh, $norm_h));
                        } 
                    } 
                    
                    

                    if (isset($_POST["tot_h_night$i"]) AND !empty($_POST["tot_h_night$i"])) {

                        $night_h = explode(':', $_POST["tot_h_night$i"]);
                        $night_m = $night_h[1] / 60;
                        $night_h = $night_h[0] + $night_m;

                        if ($night_h != $final['night_hours']) {
                            $new_nighth = htmlspecialchars($night_h);
                            $insertnighth = $bdd->prepare("UPDATE global_reference SET night_hours = '" . $new_nighth . "' WHERE updated = '" . $_POST['up_inter'] . "' AND `user_id`= '" . $_SESSION['id'] . "' AND id = '" . $test[$i]['id'] . "'");
                            $insertnighth->execute(array($new_nighth, $night_h));
                        }
                    }

                    if (isset($_POST['panier_repas']) AND !empty($_POST['panier_repas'])) {
                        $newpan = htmlspecialchars($_POST['panier_repas']);
                        $insertpan = $bdd->prepare("UPDATE global_reference SET panier_repas = '" . $newpan . "' WHERE updated = '" . $_POST['up_inter'] . "' AND `user_id`= '" . $_SESSION['id'] . "' AND id = '" . $test[$i]['id'] . "'");
                        $insertpan->execute(array($newpan, $_POST['panier_repas']));
                    } else if (!isset($_POST['panier_repas'])) {
                        $newpan = htmlspecialchars(0);
                        $insertpan = $bdd->prepare("UPDATE global_reference SET panier_repas = '" . $newpan . "' WHERE updated = '" . $_POST['up_inter'] . "' AND `user_id`= '" . $_SESSION['id'] . "' AND id = '" . $test[$i]['id'] . "'");
                        $insertpan->execute(array($newpan, $_POST['panier_repas']));
                    }
                $i += 1;
                }
            }
        }

        if (isset($_POST['flag']) AND $_POST['flag'] != 0) {
            for ($j = 0; $j < $x; $j++) {
                $newstate = htmlspecialchars($_POST['flag']);
                $insertstate = $bdd->prepare("UPDATE global_reference SET `state`= '" . $newstate . "' WHERE updated = '" . $_POST['up_inter'] . "' AND `user_id`= '" . $_SESSION['id'] . "' AND id = '" . $test[$j]['id'] . "'");
                $insertstate->execute(array($newstate, $_POST['flag']));
            }
            echo '<script type="text/javascript">alert("Édition et validation correctement réalisées :)")</script>';
            header("refresh:0; url= ../../valid_day.php?up_int=" . $_POST['up_inter']);
            exit ();
        } else {
            echo '<script type="text/javascript">alert("Édition validée :)")</script>';
            header("refresh:0; url= ../../valid_day.php?up_int=" . $_POST['up_inter']);
            exit ();
        }
    }
}


?>