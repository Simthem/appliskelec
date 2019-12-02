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

if(!($_SESSION['username'])) {  
  
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
    echo "ERROR: Could not get 'id' of current user [first_method]";
}

?>

<!DOCTYPE html>

<html class="overflow-y mb-0">
    
    <?php include 'header.php'; ?>
    
                <div class="icons-navbar">
                    <div class="menu-btn-bars text-white"><button class="menu-btn fas fa-bars text-warning w-100 fa-3x p-0"></button></div>
                    <a href="index.php" class="text-warning m-auto"><h2 class="m-0">S.K.elec</h2></a>
                    <div action='api/user/edit_profil.php' method='GET'>
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
                                    echo "ERROR: Could not able to execute $admin_sql. " . mysqli_error($db);
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
                <h3 class="text-center mt-2 pb-4 pt-5">Liste des salariés</h3>
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

                                                $sql_admin_h = 
                                                "SELECT
                                                    a.id AS admin_id,
                                                    admin_name,
                                                    c.id AS chantier_id,
                                                    updated,
                                                    SUM(intervention_hours) AS totalheure
                                                FROM
                                                    chantiers AS c
                                                    JOIN
                                                    global_reference AS g ON c.id = chantier_id
                                                    JOIN
                                                    `admin` AS a ON g.user_id = a.id 
                                                WHERE
                                                    concat(month(g.updated)) = (
                                                                            SELECT 
                                                                                MAX(concat(month(updated)))
                                                                            FROM
                                                                                global_reference
                                                                            )
                                                    AND
                                                    a.id = 1
                                                GROUP BY admin_name , updated , c.id , a.id WITH ROLLUP";

                                                if ($reponse_hours = mysqli_query($db, $sql_admin_h)){
                                                    if (mysqli_num_rows($reponse_hours) > 0){

                                                        while ($row_h_a = $reponse_hours->fetch_array()) {

                                                            if (!empty($row_h_a['admin_name']) and !empty($row_h_a['updated'] and empty($row_h_a['chantier_id']))) {
                                                                $total = $row_h_a['totalheure'];
                                                                $hours = (int)$total;
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
                                                                echo '<td class="align-middle p-4 w-25" style="word-wrap: break-word; max-width: 90px;">' . $hours . ':' . $minutes . '</td>';
                                                            }
                                                        }
                                                    } else {
                                                        echo '<td class="align-middle p-4 w-25" style="word-wrap: break-word; max-width: 90px;">00:00</td>';
                                                    }
                                                } else {
                                                    echo "ERROR: Could not able to execute $reponse_hours. " . mysqli_error($db);
                                                }

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
                                        
                                        $sql_hours = 
                                        "SELECT 
                                            username,
                                            c.id AS chantier_id,
                                            u.id AS `user_id`,
                                            SUM(intervention_hours) AS totalheure
                                        FROM
                                            chantiers AS c
                                            JOIN
                                            global_reference AS g ON c.id = chantier_id
                                            JOIN
                                            users AS u ON g.user_id = u.id
                                        WHERE
                                            concat(month(g.updated)) = (
																	SELECT 
																		MAX(concat(month(updated)))
																	FROM
																		global_reference
																	)
											AND
                                            u.id = $id_user_row
                                        GROUP BY username , c.id , u.id WITH ROLLUP";
                                        
                                        if ($result_hours = mysqli_query($db, $sql_hours)){
                                            if (mysqli_num_rows($result_hours) > 0){

                                                while ($row_hours = $result_hours->fetch_array()) {

                                                    if (!empty($row_hours['username']) and empty($row_hours['chantier_id'])) {
                                                        $total = $row_hours['totalheure'];
                                                        $hours = (int)$total;
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
                                                        echo '<td class="align-middle p-4 w-25" style="word-wrap: break-word; max-width: 90px;">' . $hours . ':' . $minutes . '</td>';
                                                    }
                                                }
                                            } else {
                                                echo '<td class="align-middle p-4 w-25" style="word-wrap: break-word; max-width: 90px;">00:00</td>';
                                            }
                                        } else {
                                            echo "ERROR: Could not able to execute $result_hours. " . mysqli_error($db);
                                        }



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
                            mysqli_free_result($result_hours);
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