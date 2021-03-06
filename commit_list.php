<?php 

include 'auth.php';

$sql =
"SELECT
    c.id AS chantier_id,
    g.updated AS inter_chantier,
    u.id AS `user_id`,
    username,
    g.commit AS `commit`
FROM
    chantiers AS c
    JOIN
    global_reference AS g ON c.id = chantier_id
    JOIN
    users AS u on g.user_id = u.id
WHERE
    c.id ='" . $_GET['id'] . "' AND g.commit is not NULL AND g.commit != ''
GROUP BY
    c.id, u.id, username, g.updated, g.commit
ORDER BY
    g.updated DESC";
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

        <div id="container">
            <div class="content">
                <div class="pt-5 pb-3 mt-4 ml-auto mr-auto">
                    <h3 class="text-center mb-5 pt-5">Commentaires</h3>
                    <div class="container-list mt-5">
                        <?php
                            if ($result = mysqli_query($db, $sql)) {
                                if (mysqli_num_rows($result) > 0) {
                                    if ($db === false) {
                                        die("ERROR: Could not connect. " . mysqli_connect_error());
                                    }

                                    while ($v_com = $result->fetch_array())
                                    {
                                        echo '<table class="table table-striped ml-auto mr-auto w-75">';
                                            echo '<thead class=" mt-5">';
                                                    echo '<tr>';
                                                        echo '<th class="align-middle pt-4 pl-2 pb-3 pr-2 w-25">' . $v_com['inter_chantier'] . ' - ' . $v_com['username'] . '</th>';
                                                    echo '</tr>';
                                            echo '</thead>';
                                            echo '<tbody>';
                                                echo '<tr>';
                                                    echo '<td class="align-middle p-1 w-25" style="word-wrap: break-word;">' . $v_com['commit'] . '</td>';
                                                echo '</tr>';
                                            echo '</tbody>';
                                        echo '</table>';
                                    }
                                    mysqli_free_result($result);
                                } else {
                                    echo "Pas de commentaires à l'heure actuelle pour ce chantier";
                                } 
                            } else {
                                echo "ERROR: Could not able to execute $sql. " . mysqli_error($db);
                            }
                        ?>
                    </div>
                </div>
                <div class="pt-5 w-75 m-auto">
                    <a href="javascript:history.go(-1)" value="return" class="btn send border-0 bg-white z-depth-1a mt-1 mb-4 text-dark">Retour aux détails</a>
                    <a href="troubleshooting_list.php" value="return" class="btn send border-0 bg-white z-depth-1a mt-1 mb-4 text-dark">Liste des chantiers</a>
                </div>
            </div>
        </div>

    <?php include 'footer.php'; ?>