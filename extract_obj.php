<?php

include 'auth.php';

$stmt_user =
"SELECT
    id,
    username
FROM
    users";

$sql =
"SELECT 
    g.updated as inter_chantier,
    c.name AS name_chantier,
    #concat(year(g.updated),
    #month(g.updated),
    #week(g.updated)),
    #c.created as date_chantier,
    #c.id AS chantier_id,
    #admin_name,
    #a.id AS admin_id,
    night_hours,
    SUM(night_hours) AS h_night_tot,
    SUM(intervention_hours) AS totalheure,
    SUM(intervention_hours - night_hours) AS tothsnight,
    if (SUM(intervention_hours - night_hours) - 350000 > 0, if (SUM(intervention_hours - night_hours) - 350000 > 80000, 80000, SUM(intervention_hours - night_hours) - 350000), NULL) AS maj25,
    if (SUM(intervention_hours - night_hours) > 430000, if (SUM(night_hours) > 0, SUM(intervention_hours) - 430000, SUM(intervention_hours - night_hours) - 430000), if (SUM(intervention_hours - night_hours) < 430000, if (SUM(night_hours) > 0, SUM(night_hours), NULL), NULL)) AS maj50
FROM
    chantiers AS c
    JOIN
    global_reference AS g ON c.id = chantier_id
    JOIN
    `admin` AS a ON g.user_id = a.id
GROUP BY 
g.updated , c.name , night_hours with ROLLUP
#ORDER BY
#concat(year(g.updated), month(g.updated), week(g.updated))";
?>

<!DOCTYPE html>

<html class="overflow-y ml-auto mr-auto mb-0">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=0">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
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
                            <li data-toggle="collapse" href="#preview2" role="button" aria-expanded="false" aria-controls="preview2" class="bg-dark border-top border-warning rounded-0 p-0 collapsed text-warning"><a>Paramètres</a></li>
                                <div id="preview2" class="bg-light collapse" action='api/user/edit_profil.php' method='GET'>
                                    <?php
                                        if ($_SESSION['id'] == $admin['id']) {
                                            $admin_sql = "SELECT * FROM `admin`";
                                            if ($admin_result = mysqli_query($db, $admin_sql)){
                                                if (mysqli_num_rows($admin_result) > 0){
                                                    if ($db === false){
                                                        die("ERROR: Could not connect. " . mysqli_connect_error());
                                                    }
                                                    while($row = $admin_result->fetch_array()) {
                                                        echo "<li class='rounded-0 p-0 menu-link' style='height: 60px;'><a href='modif_profil.php?id=" . $row['id'] . "' class='mt-auto ml-auto mb-auto mr-auto text-dark w-75'><div class='mt-auto mb-auto pr-3 float-left'> • </div>Profile</a></li>";
                                                    }
                                                    mysqli_free_result($admin_result);
                                                } else {
                                                    echo "No records matching your query were found.";
                                                }
                                            } else {
                                                echo "ERROR: Could not able to execute $admin_sql. " . mysqli_error($db);
                                            }
                                            echo "<li class='rounded-0 p-0 menu-link border-top'><a href='extract_obj.php' class='pt-4 pr-0 pb-4 mt-auto ml-auto mb-auto mr-auto  text-dark w-75'><div class='mt-auto mb-auto pr-3 pt-3 float-left'> • </div><div class='w-100'>Extraire un compte rendu</div></a></li>";
                                        } else {
                                            $user_sql = "SELECT * FROM users";
                                            if ($user_result = mysqli_query($db, $user_sql)){
                                                if (mysqli_num_rows($user_result) > 0){
                                                    if ($db === false) {
                                                        die("ERROR: Could not connect. " . mysqli_connect_error());
                                                    }
                                                    while ($row = $user_result->fetch_array()){
                                                        if ($row['id'] == $_SESSION['id']) {
                                                            echo "<li><a href='modif_profil.php?id=" . $row['id'] . "' class='mt-auto ml-auto mb-auto mr-auto text-dark w-75'>Profile</a></li>";
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
                            </li>
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

        <div id="container">
            <div class="content">
                <form id="extraction" action="./api/index_global/file_ext.php" method="POST">
                    <h3 class="mt-0 pb-2 pt-3 text-center">Extraction d'un fichier .CSV</h3>
                    <?php
                        echo '<div class="pt-2 pb-5 mb-5">
                            <h4 class="ml-auto mr-auto pb-3 pt-3 text-center border-top border-bottom w-75">Durée de la période</h4>
                            <div class="pt-4 pl-3 pr-3 pb-5 mb-4 w-50 float-left">';
                                if (isset($_GET['beg']) && !empty($_GET['beg'])) {
                                    $date_beg = date_create($_GET['beg']);
                                    echo '<h6 class="mt-0 pb-3 text-center">Date de début *</h6>';
                                    echo '<div class="text-center w-100 mr-auto ml-auto pb-4"><input class="bg-white col-8 m-0 p-0 text-right" type="date" id="beg_ext" name="beg_ext" value="' . $_GET['beg'] . '" placeholder="' . date_format($date_beg, "d-m-Y") . '" onfocus="(this.type=\'date\')" onblur="if(this.value==\'\'){this.type=\'text\'}" style="height: 26px;" required="required"></div>';
                                } else {
                                    echo '<h6 class="mt-0 pb-3 text-center">Date de début *</h6>';
                                    echo '<div class="text-center w-100 mr-auto ml-auto pb-4"><input class="bg-white col-8 m-0 p-0 text-right" type="date" id="beg_ext" name="beg_ext"  placeholder="" style="height: 26px;" required="required"></div>';
                                }
                            echo '</div>
                            <div class="pt-4 pl-3 pr-3 pb-5 mb-4 w-50 float-right">';
                                if (isset($_GET['end']) && !empty($_GET['end'])) {
                                    $date_end = date_create($_GET['end']);
                                    echo '<h6 class="mt-0 pb-3 text-center">Date de fin *</h6>';
                                    echo '<div class="text-center w-100 mr-auto ml-auto pb-4"><input class="bg-white col-8 m-0 p-0 text-right" type="date" id="end_ext" name="end_ext" value="' . $_GET['end'] . '" placeholder="' . date_format($date_end, "d-m-Y") . '" onChange="extract(this.form)" onfocus="(this.type=\'date\')" onblur="if(this.value==\'\'){this.type=\'text\'}" style="height: 26px;" required="required"></div>';
                                } else {
                                    echo '<h6 class="mt-0 pb-3 text-center">Date de fin *</h6>';
                                    echo '<div class="text-center w-100 mr-auto ml-auto pb-4"><input class="bg-white col-8 m-0 p-0 text-right" type="date" id="end_ext" name="end_ext" placeholder="" onChange="extract(this.form)" style="height: 26px;" required="required"></div>';
                                }
                            echo '</div>';
                        echo '</div>';

                        $count = 0;
                        $n_inp = 0;
                    ?>
                    <div class="pt-5 mt-5">
                    <h4 class="mt-5 ml-auto mr-auto pb-1 pt-1 text-center border-top w-75">Pour quelle(s) personne(s) ?</h4>
                    <div class="m-auto d-flex flex-column border-top pt-4 w-75">
                        <div class="w-100 m-auto pt-1 pb-3 text-center">
                            <div class="w-50 float-right">
                                <div class="pr-1 pl-0">
                                    <input type="checkbox" id="all" name="all" value="*" class="form-check-input align-middle mt-0 mb-1 position-relative" onChange="extract(this.form)" />
                                    <label class="h6 mb-auto mt-auto ml-2 pl-2 text-left" for="#chant_tot">Tout le monde</label>
                                </div>
                                <div class="mt-3 mb-3 pr-4 pl-0">
                                    <input type="checkbox" id="a_name" name="a_name" value="1" class="form-check-input align-middle mt-0 mb-1 position-relative" onChange="extract(this.form)" />
                                    <label class="h6 mb-auto mt-auto ml-2 pl-2 text-left" for="#me">Admin [moi]</label>
                                </div>
                            </div>
                            <?php
                                echo '<select id="username" name="username" class="w-50 float-left bg-white border-white" size="1" placeholder="" required>';

                                    $sql = 
                                    "SELECT 
                                        id, username
                                    FROM 
                                        users";

                                    if ($result = mysqli_query($db, $sql)) {
                                        if (mysqli_num_rows($result) > 0) {

                                            if ($db === false){
                                                die("ERROR: Could not connect. " . mysqli_connect_error());
                                            }
                                            while ($row = $result->fetch_array()){
                                                echo "<option value='" . $row['username'] . "'  onClick='pre_extract(" . $n_inp . ")'>" . $row['username'] . "</option>";
                                                echo "<option id='count' type='number' value='" . $count . "' style='display: none;'>" . $count . "</option>";
                                                $count += 1;
                                                $n_inp += 1;
                                            }
                                            mysqli_free_result($result);
                                        } else {
                                            echo "No records matching your query were found.";
                                        }
                                    } else{
                                        echo "ERROR: Could not able to execute $sql. " . mysqli_error($db);
                                    }

                                echo '</select>';
                                echo '<div id="pre_ext" name="pre_ext" class="w-100 d-inline-flex p-0"></div>';
                                echo '<div name="liste" id="liste" class="text-center bg-white" style="display:block"></div>';
                            ?>
                    </div>
                </form>
            </div>
        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="/js/script.js"></script>
        <script src="/js/jQuery.stayInWebApp-master/jquery.stayInWebApp.js"></script>
        <script src="/js/jQuery.stayInWebApp-master/jquery.stayInWebApp.min.js"></script>
        <script src="/js/bootstrap.js"></script>
        <script>
            $(function() {
                $.stayInWebApp();
            });
        </script>

        <footer>
        </footer>
    </body>
</html>