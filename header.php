<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="S.K.elec">
    <meta name="msapplication-TileColor" content="#000000">
    <meta name="msapplication-TileImage" content="img/logo.ico/logo_apple_noir_bg.png">
    <meta name="theme-color" content="#000000">
    <link rel="apple-touch-icon" sizes="57x57" href="img/logo.ico/logo_apple_noir_bg.png">
    <link rel="apple-touch-icon" sizes="60x60" href="img/logo.ico/logo_apple_noir_bg.png">
    <link rel="apple-touch-icon" sizes="72x72" href="img/logo.ico/logo_apple_noir_bg.png">
    <link rel="apple-touch-icon" sizes="76x76" href="img/logo.ico/logo_apple_noir_bg.png">
    <link rel="apple-touch-icon" sizes="114x114" href="img/logo.ico/logo_apple_noir_bg.png">
    <link rel="apple-touch-icon" sizes="120x120" href="img/logo.ico/logo_apple_noir_bg.png">
    <link rel="apple-touch-icon" sizes="144x144" href="img/logo.ico/logo_apple_noir_bg.png">
    <link rel="apple-touch-icon" sizes="152x152" href="img/logo.ico/logo_apple_noir_bg.png">
    <link rel="apple-touch-icon" sizes="180x180" href="img/logo.ico/logo_apple_noir_bg.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="img/logo.ico/logo_apple_blanc_bg.png">
    <link rel="icon" type="image/png" sizes="32x32" href="img/logo.ico/logo_apple_blanc_bg.png">
    <link rel="icon" type="image/png" sizes="96x96" href="img/logo.ico/logo_apple_blanc_bg.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/logo.ico/logo_apple_blanc_bg.png">
    <link rel="manifest" href="img/logo.ico/manifest-noir.json">
    <link  rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,400italic,700,700italic" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.1/css/all.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
    <script src="https://kit.fontawesome.com/f14bbc71a6.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>-->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <title>S.K.elec</title>
</head>
<body class="ml-auto mr-auto">
    <header class="header position-fixed">
        <!-- Menu Button -->
        <div class="navbar-expand-md double-nav scrolling-navbar navbar-dark bg-dark position-relative">
            <!--Menu -->
            <nav class="menu left-menu">
                <div class="menu-content">
                    <ul class="pl-0">
                        <li class="bg-dark border-top border-warning rounded-0 p-0 menu-link"><a href="troubleshooting_list.php" class="text-warning">Chantiers</a></li>
                        <li class="bg-dark border-top border-warning rounded-0 p-0 menu-link"><a href="list_profil.php" class="text-warning">Salariés</a></li>
                        
                        <!--<li class="bg-dark border-top border-warning rounded-0 p-0 collapsed"><a id="params" data-toggle="collapse" href="#submenu" role="button" aria-expanded="true" aria-controls="#submenu" class="text-warning open-col">Paramètres<div class="mr-0 float-right" style="width: 40px;"><img src="img/fleche_menu.png" class="ml-3 mt-auto mb-auto open-col" style="width: 13px; height: 13px;"></div></a>-->
                        <li data-toggle="collapse" href="#preview2" role="button" aria-expanded="false" aria-controls="preview2" class="bg-dark border-top border-warning rounded-0 p-0 collapsed text-warning"><a class="mt-auto mb-auto">+ Options <img src="img/fleche_menu.png" class="float-right mt-auto pt-1 color-warning" alt="Fleche du menu indiquant ce dernier comme deroulant" height="18" width="16"></a></li>
                            <div id="preview2" class="bg-light collapse" action='api/user/edit_profil.php' method='GET'>
                                <?php
                                    if (isset($_SESSION['id']) && !empty($_SESSION['id']) && isset($admin['id']) && !empty($admin['id']) &&  $_SESSION['id'] == $admin['id']) {
                                        $admin_sql = "SELECT * FROM `admin`";
                                        if ($admin_result = mysqli_query($db, $admin_sql)){
                                            if (mysqli_num_rows($admin_result) > 0){
                                                if ($db === false){
                                                    die("ERROR: Could not connect. " . mysqli_connect_error());
                                                }
                                                while ($row = $admin_result->fetch_array()) {
                                                    echo "<li class='rounded-0 p-0 menu-link'><a href='modif_profil.php?id=" . $row['id'] . "' class='mt-auto ml-auto mb-auto mr-auto pr-3 pl-3 text-dark w-75' style='height: 65px;'><div class='mt-auto mb-auto pr-3 float-left'> • </div>Profile</a></li>";
                                                    echo "<li><a href='absence.php?id=" . $row['id'] . "' class='pt-4 pr-3 pb-4 pl-3 mt-auto ml-auto mb-auto mr-auto text-dark w-75 d-flex border-top'><div class='mt-auto mb-auto pr-3 float-left'> • </div><div class='float-right text-left'>Signaler une <br />ou des absence(s)</div></a></li>";
                                                }
                                                mysqli_free_result($admin_result);
                                            } else {
                                                echo "No records matching your query were found.";
                                            }
                                        } else {
                                            echo "ERROR: Could not able to execute $admin_sql. " . mysqli_error($db);
                                        }
                                        echo "<li class='rounded-0 p-0 menu-link'><a href='search.php' class='mt-auto ml-auto mb-auto mr-auto pr-3 pl-3  border-top text-dark w-75' style='height: 65px; padding-top: 22px;'><div class='mt-auto mb-auto pr-3 float-left'> • </div>Rechercher</a></li>";
                                        echo "<li class='rounded-0 p-0 menu-link'><a href='extract_obj.php' class='pt-4 pr-3 pb-4 pl-3 mt-auto ml-auto mb-auto mr-auto text-dark border-top w-75'><div class='mt-auto mb-auto pr-3 pt-3 float-left'> • </div><div class='w-100'>Extraire un compte rendu</div></a></li>";
                                    } else {
                                        $user_sql = "SELECT * FROM users";
                                        if ($user_result = mysqli_query($db, $user_sql)){
                                            if (mysqli_num_rows($user_result) > 0){
                                                if ($db === false) {
                                                    die("ERROR: Could not connect. " . mysqli_connect_error());
                                                }
                                                while ($row = $user_result->fetch_array()){
                                                    if (isset($_SESSION['id']) && !empty($_SESSION['id']) && $row['id'] == $_SESSION['id']) {
                                                        echo "<li><a href='modif_profil.php?id=" . $row['id'] . "' class='mt-auto ml-auto mb-auto mr-auto pl-3 pr-3 text-dark w-75' style='height: 65px;'><div class='mt-auto mb-auto pr-3 float-left'> • </div>Profile</a></li>";
                                                        echo "<li><a href='absence.php?id=" . $row['id'] . "' class='pt-4 pr-3 pb-4 pl-3 mt-auto ml-auto mb-auto mr-auto text-dark w-75 d-flex border-top'><div class='mt-auto mb-auto pr-3 float-left'> • </div><div class='float-right text-left'>Signaler une <br />ou des absence(s)</div></a></li>";
                                                    }
                                                }
                                                mysqli_free_result($user_result);
                                            } else {
                                                echo "No records matching your query were found.";
                                            }
                                        } else {
                                            echo "ERROR: Could not able to execute $admin_sql. " . mysqli_error($db);
                                        }
                                        echo "<li class='rounded-0 p-0 menu-link'><a href='search.php' class='mt-auto ml-auto mb-auto mr-auto pr-3 pl-3 border-top text-dark w-75' style='height: 65px; padding-top: 22px;'><div class='mt-auto mb-auto pr-3 float-left'> • </div>Rechercher</a></li>";
                                    }
                                ?>
                            </div>
                        <li class="bg-dark border-top border-bottom border-warning rounded-0 p-0 menu-link"><a href="api/User/logout.php" class="text-warning">Déconnexion</a></li>
                    </ul>
                </div>
            </nav>