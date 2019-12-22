<?php 
    include 'auth.php';
?>

<!DOCTYPE html>

<html class="overflow-y ml-auto mr-auto mb-0">
    
    <?php include 'header.php'; ?>

                <div class="icons-navbar" style="z-index: 1;">
                    <div class="menu-btn-bars text-white"><button class="menu-btn fas fa-bars text-warning w-100 fa-3x p-0"></button></div>
                    <a href="index.php" class="text-warning d-inline-flex m-auto"><img class="mr-2 ml-2" src="img/ampoule_skelec.png" alt="logo S.K.elec" height="45" width="30"><h2 class="d-inline-flex mt-0 mr-2 mb-0 ml-0">S.K.elec</h2></a>
                    <a href="list_profil.php" class="text-white pl-3"><i class="menu-btn-plus fas fa-search text-warning fa-3x rounded-circle"></i></a>
                </div>
            </div>
        </header>

        <?php

            
            if(($_GET['id'] != $admin['id'] and $_SESSION['id'] == $_GET['id'] and $_SESSION['id'] == $user['id']) or $_SESSION['id'] == $admin['id']) {
        ?>
        <!-- Content -->
                <div id="container">
                    <div class="content">
                        <form class="w-100 pt-2 pl-4 pb-0 pr-4" action="api/user/edit_profil.php" method="POST">
                            <div class="pt-5 pb-3 mt-4 ml-auto mr-auto">
                                <h3 class="text-center mb-5 pt-5">Édition du compte</h3>
                            <?php
                                $stmt = $bdd->prepare("SELECT * FROM users WHERE id = '". $_GET['id'] ."'");
                                $stmt->execute();
                                $user = $stmt->fetch();
                                
                                if ($user) {
                                    $modif_user['id'] = $user['id'];
                                    $modif_user['username'] = $user['username'];
                                    $modif_user['first_name'] = $user['first_name'];
                                    $modif_user['last_name'] = $user['last_name'];
                                    $modif_user['e_mail'] = $user['e_mail'];
                                    $modif_user['phone'] = $user['phone'];

                                    echo '<input type="text" value="' . $modif_user['id'] . '" id="id" name="id" style="display: none;">';
                                    echo '<div class="md-form mt-1">';
                                        echo '<label for="fusername">Username</label>';
                                        echo '<input type="text" value="' . $modif_user['username'] . '" id="username" name="username" class="form-control" placeholder="' . $modif_user['username'] . '" />';
                                    echo '</div>';
                                    echo '<div class="md-form mt-4">';
                                        echo '<label for="first-name">First name</label>';
                                        echo '<input type="text" value="' . $modif_user['first_name'] . '" id="first_name" name="first_name" class="form-control" placeholder="' . $modif_user['first_name'] . '" />';
                                    echo '</div>';
                                    echo '<div class="md-form mt-4">';
                                        echo '<label for="last_name">Last name</label>';
                                        echo '<input type="text" value="' . $modif_user['last_name'] . '" id="last_name" name="last_name" class="form-control" placeholder="' . $modif_user['last_name'] . '" />';
                                    echo '</div>';
                                    echo '<div class="md-form mt-4">';
                                        echo '<label for="e_mail">E_mail</label>';
                                        echo '<input type="email" value="' . $modif_user['e_mail'] . '" id="e-mail" name="e_mail" class="form-control" placeholder="' . $modif_user['e_mail'] . '" />';
                                    echo '</div>';
                                    echo '<div class="md-form mt-4 pb-3">';
                                        echo '<label for="phone">Téléphone</label>';
                                        echo '<input type="text" value="' . $modif_user['phone'] . '" id="phone" name="phone" class="form-control" placeholder="' . $modif_user['phone'] . '" />';
                                    echo '</div>';
                                    

                                    echo '<h4 class="w-75 mt-5 ml-auto mb-4 mr-auto pt-3 pb-2 text-center border-bottom">Heures sur 4 semaines</h4>';
                                    echo "<table class='table table-striped border mt-4 ml-auto mb-3 mr-auto w-100 text-center'>";
                                        echo "<thead>
                                            <tr>
                                                <th scope='col' class='text-center border-right align-middle w-25 font-weight-normal'>H/totales<br />[<strong><u>sans</u></strong> h/nuit]</th>
                                                <th scope='col' class='text-center border-right align-middle w-25 font-weight-normal'>H + 25%</th>
                                                <th scope='col' class='text-center align-middle w-25 font-weight-normal'>H + 50%</th>
                                            </tr>
                                        </thead>
                                    </table>";
                                    echo '<div class="m-auto">
                                        <table class="table table-striped border ml-auto mb-3 mr-auto w-100 text-center">';
                                            
                                            include_once './api/view/user_view.php';
                                            week($_GET['id']);  // Call function to display all hours of the user

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

                                    if ($_SESSION['id'] == $admin['id'] and $admin['id'] == $_GET['id']) {

                                        $stmt = $bdd->prepare("SELECT * FROM `admin` WHERE id = '". $_GET['id'] ."'");
                                        $stmt->execute();
                                        $admin = $stmt->fetch();

                                        echo '<input type="text" value="' . $admin['id'] . '" id="id" name="id" style="display: none;" />';
                                        echo '<div class="md-form mt-1">';
                                            echo '<label for="fusername">Username</label>';
                                            echo '<input type="text" value="' . $admin['admin_name'] . '" id="username" name="username" class="form-control" placeholder="' . $admin['admin_name'] . '" />';
                                        echo '</div>';
                                        echo '<div class="md-form mt-4">';
                                            echo '<label for="first-name">First name</label>';
                                            echo '<input type="text" value="' . $admin['first_name'] . '" id="first_name" name="first_name" class="form-control" placeholder="' . $admin['first_name'] . '" />';
                                        echo '</div>';
                                        echo '<div class="md-form mt-4">';
                                            echo '<label for="last_name">Last name</label>';
                                            echo '<input type="text" value="' . $admin['last_name'] . '" id="last_name" name="last_name" class="form-control" placeholder="' . $admin['last_name'] . '" />';
                                        echo '</div>';
                                        echo '<div class="md-form mt-4">';
                                            echo '<label for="e_mail">E_mail</label>';
                                            echo '<input type="email" value="' . $admin['e_mail'] . '" id="e-mail" name="e_mail" class="form-control" placeholder="' . $admin['e_mail'] . '" />';
                                        echo '</div>';
                                        echo '<div class="md-form mt-4 pb-3">';
                                            echo '<label for="phone">Téléphone</label>';
                                            echo '<input type="text" value="' . $admin['phone'] . '" id="phone" name="phone" class="form-control" placeholder="' . $admin['phone'] . '" />';
                                        echo '</div>';


                                        echo '<h4 class="w-75 mt-5 ml-auto mb-4 mr-auto pt-3 pb-2 text-center border-bottom">Heures sur 4 semaines</h4>';

                                        echo "<table class='table table-striped border mt-4 ml-auto mb-3 mr-auto w-100 text-center'>";
                                            echo "<thead>
                                                <tr>
                                                <th scope='col' class='text-center border-right align-middle w-25 font-weight-normal'>H/totales<br />[<strong><u>sans</u></strong> h/nuit]</th>
                                                <th scope='col' class='text-center border-right align-middle w-25 font-weight-normal'>H + 25%</th>
                                                <th scope='col' class='text-center align-middle w-25 font-weight-normal'>H + 50%</th>
                                                </tr>
                                            </thead>
                                        </table>";
                                        echo '<div class="container-list-details m-auto">
                                            <table class="table table-striped border ml-auto mb-3 mr-auto w-100 text-center">';
                                            
                                            include_once './api/view/admin_view.php';
                                            week_a($_GET['id']);  // Call function to display all hours of the user

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
                            </div>
                        </form>
        <?php 
            } else { 
        ?>
            <div id="container">
                <div class="content">
                    <form class="w-100 pt-2 pl-4 pb-0 pr-4">
                        <div class="pt-5 pb-3 mt-4 ml-auto mr-auto">
                            <h3 class="text-center mb-5 pt-5">Détails d'un compte</h3>
                        <?php
                            $stmt = $bdd->prepare("SELECT * FROM users WHERE id = '". $_GET['id'] ."'");
                            $stmt->execute();
                            $user = $stmt->fetch();

                            if($user) {
                                $modif_user['username'] = $user['username'];
                                $modif_user['first_name'] = $user['first_name'];
                                $modif_user['last_name'] = $user['last_name'];
                                $modif_user['e_mail'] = $user['e_mail'];
                                $modif_user['phone'] = $user['phone'];

                                echo '<div class="md-form mt-1">';
                                    echo '<label for="fusername" class="text-secondary">Username</label>';
                                    echo '<input type="text" value="' . $modif_user['username'] . '" id="username" name="username" class="form-control" placeholder="' . $modif_user['username'] . '" disabled />';
                                echo '</div>';
                                echo '<div class="md-form mt-4">';
                                    echo '<label for="first-name" class="text-secondary">First name</label>';
                                    echo '<input type="text" value="' . $modif_user['first_name'] . '" id="first_name" name="first_name" class="form-control" placeholder="' . $modif_user['first_name'] . '" disabled />';
                                echo '</div>';
                                echo '<div class="md-form mt-4">';
                                    echo '<label for="last_name" class="text-secondary">Last name</label>';
                                    echo '<input type="text" value="' . $modif_user['last_name'] . '" id="last_name" name="last_name" class="form-control" placeholder="' . $modif_user['last_name'] . '" disabled />';
                                echo '</div>';
                                echo '<div class="md-form mt-4">';
                                    echo '<label for="e_mail" class="text-secondary">E_mail</label>';
                                    echo '<input type="email" value="' . $modif_user['e_mail'] . '" id="e-mail" name="e_mail" class="form-control" placeholder="' . $modif_user['e_mail'] . '" disabled />';
                                echo '</div>';
                                echo '<div class="md-form mt-4 pb-3">';
                                    echo '<label for="phone" class="text-secondary">Téléphone</label>';
                                    echo '<input type="text" value="' . $modif_user['phone'] . '" id="phone" name="phone" class="form-control" placeholder="' . $modif_user['phone'] . '" disabled />';
                                echo '</div>';


                                echo '<h4 class="w-75 mt-5 ml-auto mb-4 mr-auto pt-3 pb-2 text-center border-bottom">Heures sur 4 semaines</h4>';
                                echo "<table class='table table-striped border mt-4 ml-auto mb-3 mr-auto w-100 text-center'>";
                                    echo "<thead>
                                        <tr>
                                        <th scope='col' class='text-center border-right align-middle w-25 font-weight-normal'>H/totales<br />[<strong><u>sans</u></strong> h/nuit]</th>
                                        <th scope='col' class='text-center border-right align-middle w-25 font-weight-normal'>H + 25%</th>
                                        <th scope='col' class='text-center align-middle w-25 font-weight-normal'>H + 50%</th>
                                        </tr>
                                    </thead>
                                </table>";
                                echo '<div class="m-auto">
                                    <table class="table table-striped border ml-auto mb-3 mr-auto w-100 text-center">';
                                        

                                    include_once './api/view/user_view.php';
                                    week($_GET['id']);  // Call function to display all hours of the user
                                    
                                echo '</table>
                                </div>';
                            }
                        ?>
                            <div class="pt-5 w-75 m-auto">
                                <a href="javascript:history.go(-1)" value="return" class="btn finish border-0 bg-white z-depth-1a mt-1 mb-4 text-dark">Précédent</a>
                            </div>
                        </div>
                    </form>
            <?php } ?>

                </div>
            </div>

    <?php include 'footer.php'; ?>