<?php include 'auth.php'; ?>

<!DOCTYPE html>

<html class="overflow-y ml-auto mr-auto mb-0">
    
    <?php include 'header.php'; ?>
    
                <div class="icons-navbar">
                    <div class="menu-btn-bars text-white"><button class="menu-btn fas fa-bars text-warning w-100 fa-3x p-0"></button></div>
                    <a href="index.php" class="text-warning d-inline-flex m-auto"><img class="mr-2 ml-2" src="img/ampoule_skelec.png" alt="logo S.K.elec" height="45" width="30"><h2 class="d-inline-flex mt-0 mr-2 mb-0 ml-0">S.K.elec</h2></a>
                    <div class="menu-btn-bars" action="api/user/edit_profil.php" method="GET">
                        <?php
                            if ($_SESSION['id'] == $admin['id']) {

                                $admin_sql = "SELECT * FROM `admin`";

                                if ($admin_result = mysqli_query($db, $admin_sql)){
                                    if (mysqli_num_rows($admin_result) > 0){
                                        if ($db === false){
                                            die("ERROR: Could not connect. " . mysqli_connect_error());
                                        }
                                        while($row = $admin_result->fetch_array()) {
                                            echo "<a href='modif_profil.php?id=" . $row['id'] . "' class='text-white pl-3'><i class='menu-btn-plus fas fa-user text-warning fa-3x rounded-circle'></i></a>";
                                        }
                                        mysqli_free_result($admin_result);
                                    } else {
                                        echo "No records matching your query were found.";
                                    }
                                } else {
                                    echo "ERROR: Could not able to execute $admin_sql. " . mysqli_error($db);
                                }
                            } else {

                                $user_sql = "SELECT * FROM users";

                                if ($user_result = mysqli_query($db, $user_sql)){
                                    if (mysqli_num_rows($user_result) > 0){
                                        if ($db === false) {
                                            die("ERROR: Could not connect. " . mysqli_connect_error());
                                        }
                                        while ($row = $user_result->fetch_array()){
                                            if ($row['id'] == $_SESSION['id']) {
                                                echo "<a href='modif_profil.php?id=" . $row['id'] . "' class='text-white pl-3'><i class='menu-btn-plus fas fa-user text-warning fa-3x rounded-circle'></i></a>";
                                            }
                                        }
                                        mysqli_free_result($user_result);
                                    } else {
                                        echo "No records matching your query were found.";
                                    }
                                } else {
                                    echo "ERROR: Could not able to execute $user_result. " . mysqli_error($db);
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div id="container">
            <div class="content pt-0 overflow-hidden">
                <div class="pt-5 pb-3 mt-4 ml-auto mr-auto">
                    <h3 class="text-center mb-5 pt-5">Liste des salariés</h3>
                    <table class="table table-striped mt-0 ml-0 mb-0 text-center" style="height: 50px;">
                    <?php

                    $sql = 
                    "SELECT 
                        id, username, phone 
                    FROM 
                        users";

                    $sql_a = 
                    "SELECT
                        id, admin_name, phone
                    FROM
                        `admin`";

                    if ($result = mysqli_query($db, $sql)){
                        if (mysqli_num_rows($result)){
                            echo '<thead>';
                                echo '<tr>';
                                    echo '<th scope="col" class="text-center align-middle p-2 w-25" id="first_name">Prénom</th>';
                                    echo '<th scope="col" class="text-center align-middle p-2 w-25" id="phone">Téléphone</th>';
                                    echo '<th scope="col" class="text-center align-middle p-2 w-25" id="hours">H/totales</th>';
                                    echo '<th scope="col" class="text-center align-middle p-2 w-25" id="">Détails</th>';
                                echo '</tr>';
                            echo '</thead>';
                    ?>
                    </table>
                <div class="container-list m-auto">
                    <table class="table table-striped pr-4 pl-4 mt-3 ml-auto mr-auto text-center" action="api/user/edit_profil.php" method="GET">
                        <?php
                            if ($db === false){
                                die("ERROR: Could not connect. " . mysqli_connect_error());
                            }
                            echo '<tbody>';

                                if ($reponse = mysqli_query($db, $sql_a)) {
                                    if (mysqli_num_rows($reponse)) {

                                        while ($row_a = $reponse->fetch_array()) {
                                            echo '<tr>';
                                                echo '<td class="align-middle p-4 w-25" style="word-wrap: break-word; max-width: 90px;">' . $row_a['admin_name'] . '</td>';
                                                echo '<td class="align-middle p-4 w-25" style="word-wrap: break-word; max-width: 90px;">' . $row_a['phone'] . '</td>';
                                                $id_user_row = $row_a['id'];
                                                

                                                include_once './api/view/admin_view.php';
                                                month_a($row_a['id']);

                                                if ($_SESSION['id'] == $admin['id']) {
                                                    echo '<td class="p-0 align-middle w-25>';
                                                    ?>
                                                    <div class="w-100 text-center"><a href="modif_profil.php?id=<?php echo $row_a['id'] ?>"><i class="fas fa-tools mr-2"></i></a></div>
                                                    <div class="float-left pl-0" style="width: 12.25px; height: 14px;"></div>
                                                    <?php
                                                    echo '</td>';
                                                } else {
                                                    echo '<td class="p-0 align-middle w-25>';
                                                    ?>
                                                    <div class="w-100 text-center" style="width: 12.25px; height: 14px;"></div>
                                                    <div class="float-left pl-0" style="width: 12.25px; height: 14px;"></div>
                                                    <?php
                                                    echo '</td>';
                                                }
                                            echo '</tr>';
                                        }
                                    }
                                }
                                    
                                while ($row = $result->fetch_array()){
                                    echo '<tr>';
                                        echo '<td class="align-middle p-4 w-25" style="word-wrap: break-word; max-width: 90px;">' . $row['username'] . '</td>';
                                        echo '<td class="align-middle p-4 w-25" style="word-wrap: break-word; max-width: 90px;">' . $row['phone'] . '</td>';
                                        $id_user_row = $row['id'];

                                        include_once './api/view/user_view.php';
                                        month($row['id']);

                                        if ($_SESSION['id'] == $admin['id']) {
                                            echo '<td class="p-0 align-middle w-25>';
                                            ?>
                                            <form action="api/user/delete_user.php" method="GET" >
                                                <div class="float-left pl-0" id="<?php echo $id_user_row; ?>" name="<?php echo $id_user_row; ?>" class="remove" onClick="reply_click_user(this.id)"><i class="fas fa-trash-alt"></i></div>
                                            </form>
                                            <div class="w-100 text-center"><a href="modif_profil.php?id=<?php echo $id_user_row; ?>"><i class="fas fa-tools mr-2"></i></a></div>

                                            <?php
                                            echo '</td>';
                                        } else {
                                            echo "<td class='p-0 align-middle w-25'><a href='modif_profil.php?id=" . $id_user_row . "'><i class='fas fa-tools'></i></a></td>";
                                        }
                                    echo '</tr>';
                                }
                            mysqli_free_result($result);
                            echo '</tbody>';
                        } else {
                            echo "No records matching your query were found.";
                        }
                    } else{
                        echo "ERROR: Could not able to execute $sql. " . mysqli_error($db);
                    }
                        ?>
                    </table>
                </div>
                <?php
                    if ($_SESSION['id'] == $admin['id']) {
                        echo "<form class='pt-5 mt-5'>";
                            echo "<div class='pt-5 w-75 m-auto'>";
                                echo "<a href='add_profil.php' class='btn send border-0 bg-white z-depth-1a mt-2 mb-2 text-dark'>Ajouter un compte</a>";
                            echo "</div>";
                        echo "</form>";
                    } else {
                        echo "<div class='pt-5 mt-5'>";
                            echo "<div class='pt-5 w-75 m-auto'>";
                                echo "<a href='javascript:history.go(-1)' value='return' class='btn finish border-0 bg-white z-depth-1a mt-4 mb-3 text-dark'>Précédent</a>";
                            echo "</div>";
                        echo "</div>";
                    }
                    mysqli_close($db); 
                ?>
            </div>
        </div>
    </body>

    <?php include 'footer.php'; ?>