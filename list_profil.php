<?php
session_start();
//include 'api/config/database.php';
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
                            <li class="bg-dark border-top border-bottom border-warning rounded-0 p-0 menu-link"><a href="api/User/logout.php" class="text-warning">Déconnexion</a></li>
                        </ul>
                    </div>
                </nav>
                <div class="icons-navbar">
                    <div class="menu-btn-bars text-white"><button class="menu-btn fas fa-bars text-warning w-100 fa-3x p-0"></button></div>
                    <a href="index.php" class="text-warning m-auto"><h2 class="m-0">S.K.elec</h2></a>
                    <a href="#" class="text-white pl-3"><i class="menu-btn-plus fas fa-search text-warning fa-3x rounded-circle"></i></a>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div id="container">
            <div class="content">
                <h3 class="text-center mt-0 pb-3 pt-5">Liste des salariés</h3>
                <div class="container-list h-50 m-auto">
                    <table class="table table-striped w-75 pr-4 pl-4 mt-5 ml-auto mr-auto text-center">
                        <?php
                        if($db === false){
                            die("ERROR: Could not connect. " . mysqli_connect_error());
                        }
                        
                        $sql = "SELECT * FROM users";
                        if($result = mysqli_query($db, $sql)){
                            if(mysqli_num_rows($result) > 0){
                                echo '<thead>';
                                    echo '<tr>';
                                        echo '<th scope="col" class="text-center" id="first_name">Prénom</th>';
                                        echo '<th scope="col" class="text-center" id="e_mail">E-mail</th>';
                                        echo '<th scope="col" class="text-center" id="phone">Téléphone</th>';
                                        echo '<th scope="col" class="text-center" id="hours">Heures totales</th>';
                                    echo '</tr>';
                                echo '</thead>';
                                echo '<tbody>';
                                    while($row = $result->fetch_array()){
                                        echo '<tr>';
                                            echo '<td class="align-middle">' . $row['username'] . '</td>';
                                            echo '<td class="align-middle" style="word-wrap: break-word; max-width: 85px;">' . $row['e_mail'] . '</td>';
                                            echo '<td class="align-middle">' . $row['phone'] . '</td>';
                                            echo '<td class="align-middle">' . $row[''] . '</td>';
                                            echo '<td class="p-0 align-middle"><a href="modif_profil.html"><i class="fas fa-tools"></i></a></td>';
                                        echo '</tr>';
                                    }
                                echo '</tbody>';
                                mysqli_free_result($result);
                            } else{
                                echo "No records matching your query were found.";
                            }
                        } else{
                            echo "ERROR: Could not able to execute $sql. " . mysqli_error($db);
                        }
                        mysqli_close($db);
                        ?>
                    </table>
                </div>
                <form>
                    <div class="pt-5 w-75 m-auto">
                        <a href="add_profil.html" type="submit" value="valid" class="btn send border-0 bg-white z-depth-1a mt-4 mb-3 text-dark">Ajouter un compte</a>
                        <a href="#" type="submit" value="delete" class="btn finish border-0 bg-white z-depth-1a mt-4 mb-3 text-dark">Supprimer un compte</a>
                    </div>
                </form>
            </div>
        </div>
    </body>

    <footer>
    </footer>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="js/script.js"></script>
    <script src="js/bootstrap.js"></script>
</html>