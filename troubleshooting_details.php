<?php
session_start();

include 'api/config/db_connexion.php';

if(!($_SESSION['username'])) {  
  
    header("Location: signin.php");//redirect to login page to secure the welcome page without login access.  
}
?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <link  rel="stylesheet" href="assets/css/bootstrap.css">
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
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
                            <li class="bg-dark border-top border-warning rounded-0 p-0 menu-link"><a href="troubleshooting_list.html" class="text-warning">Chantiers</a></li>
                            <li class="bg-dark border-top border-warning rounded-0 p-0 menu-link"><a href="list_profil.php" class="text-warning">Salariés</a></li>
                            <li class="bg-dark border-top border-warning rounded-0 p-0 menu-link"><a href="#" class="text-warning">Paramètres</a></li>
                            <li class="bg-dark border-top border-bottom border-warning rounded-0 p-0 menu-link"><a href="signin.php" class="text-warning">Déconnexion</a></li>
                        </ul>
                    </div>
                </nav>
                <div class="icons-navbar">
                    <div class="menu-btn-bars text-white"><button class="menu-btn fas fa-bars text-warning w-100 fa-3x p-0"></button></div>
                    <a href="index.php" class="text-warning m-auto"><h2 class="m-0">S.K.elec</h2></a>
                    <a href="troubleshooting_list.html" class="text-white pl-3"><i class="menu-btn-plus fas fa-search text-warning fa-3x rounded-circle"></i></a>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div id="container">
            <div class="content">
                <h3 class="text-center mt-0 mb-3 pt-5">Détails du chantier</h3>
                <h5 class="text-center">"ID du chantier"</h5>
                <h5 class="text-center mt-2">"Libellé du Chantier"</h5>
                <table class="table table-striped mt-5 ml-auto mb-5 mr-auto w-75 text-center">
                    <thead>
                        <tr>
                            <th scope="col" class="align-middle text-center w-50">Date de création</th>
                            <th scope="col" class="align-middle text-center w-50">Temps d'intervention réalisé</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="align-middle border bg-white p-1">"date de début"</td>
                            <td class="align-middle border bg-white p-1">"nombre d'heures"</td>
                        </tr>
                    </tbody>
                </table>
                
                <table class="table table-striped border ml-auto mb-3 mr-auto w-75 text-center">
                    <thead>
                        <tr>
                            <th scope="col" class="border-right align-middle w-50">Salarié(s) sur le chantier</th>
                            <th scope="col" class="border-right align-middle w-50">Nombre d'heures correspondant</th>
                            <th scope="col" class="align-middle">Détails</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="align-middle border p-1">"nom du salarié"</td>
                            <td class="align-middle border p-1">"nombre d'heures"</td>
                            <td class="align-middle border bg-white p-1"><a href="list_profil.html" class="w-25"><i class="fas fa-tools align-middle"></i></a></td>
                        </tr>
                        <tr>
                            <td class="align-middle border p-1">"nom du salarié"</td>
                            <td class="align-middle border p-1">"nombre d'heures"</td>
                            <td class="align-middle border p-1"><a href="list_profil.html" class="w-25"><i class="fas fa-tools align-middle"></i></a></td>
                        </tr>
                    </tbody>
                </table>
                <div class="ml-auto mr-auto mt-5 w-75">
                    <a href="#" type="submit" value="valid" class="btn send border-0 bg-white z-depth-1a mt-3 mb-4 text-dark">Modifier</a>
                    <a href="troubleshooting_list.html" type="submit" value="return" class="btn finish border-0 bg-white z-depth-1a mt-1 mb-4">Précédent</a>
                </div>
            </div>
        </div>
    </body>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="js/script.js"></script>
    <script src="js/bootstrap.js"></script>
</html>