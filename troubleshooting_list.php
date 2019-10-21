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
                            <li class="bg-dark border-top border-warning rounded-0 p-0 menu-link"><a href="troubleshooting_list.php" class="text-warning">Chantiers</a></li>
                            <li class="bg-dark border-top border-warning rounded-0 p-0 menu-link"><a href="list_profil.php" class="text-warning">Salariés</a></li>
                            <li class="bg-dark border-top border-warning rounded-0 p-0 menu-link"><a href="#" class="text-warning">Paramètres</a></li>
                            <li class="bg-dark border-top border-bottom border-warning rounded-0 p-0 menu-link"><a href="signin.php" class="text-warning">Déconnexion</a></li>
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
                <h3 class="text-center mt-0 pt-5 pb-3">Liste des chantiers</h3>
                <table class="table table-striped mt-0 ml-0 mb-0 text-center" style="height: 50px;">
                    <?php
                        $sql = "SELECT * FROM chantiers";
                        if($result = mysqli_query($db, $sql)){
                            if(mysqli_num_rows($result) > 0){
                                echo '<thead>';
                                    echo '<tr>';
                                        echo '<th scope="col" class="text-center align-middle p-2 w-25" id="num_chantier">ID\'s</th>';
                                        //echo '<th scope="col" class="text-center align-middle p-4" id="e_mail">E-mail</th>';
                                        echo '<th scope="col" class="text-center align-middle p-2 w-25" id="name">Libellés</th>';
                                        echo '<th scope="col" class="text-center align-middle p-2 w-25" id="contact_address">Adresse</th>';
                                        echo '<th scope="col" class="text-center align-middle p-0 w-25" id="">Détails</th>';
                                    echo '</tr>';
                                echo '</thead>';
                    ?>
                </table>
                <div class="container-list m-auto">
                    <table class="table table-striped pr-4 pl-4 mt-3 ml-auto mr-auto text-center" action="" method="POST">
                        <?php
                                if($db === false){
                                    die("ERROR: Could not connect. " . mysqli_connect_error());
                                }
                                echo '<tbody>';
                                    while($row = $result->fetch_array()){
                                        echo '<tr>';
                                            if($row['num_chantier'] != 0 or !empty($row['num_chantier'])) {
                                                echo '<td class="align-middle p-4 w-25">' . $row['num_chantier'] . '</td>';
                                                //echo '<td class="align-middle p-4" style="word-wrap: break-word; max-width: 85px;">' . $row['e_mail'] . '</td>';
                                                echo '<td class="align-middle p-4 w-25">' . $row['name'] . '</td>';
                                                echo '<td class="align-middle p-4 w-25">' . $row['contact_address'] . '</td>';
                                                echo '<td class="p-0 align-middle w-25"><a href="troubleshooting_details.html"><i class="fas fa-tools"></i></a></td>';
                                            } else {
                                                echo '<td class="align-middle p-4 w-25 bg-success text-white">Dép.</td>';
                                                //echo '<td class="align-middle p-4" style="word-wrap: break-word; max-width: 85px;">' . $row['e_mail'] . '</td>';
                                                echo '<td class="align-middle p-4 w-25 bg-success text-white">' . $row['name'] . '</td>';
                                                echo '<td class="align-middle p-4 w-25 bg-success text-white">' . $row['contact_address'] . '</td>';
                                                echo '<td class="p-0 align-middle w-25 bg-success"><a href="troubleshooting_details.php"><i class="fas fa-tools text-white"></i></a></td>';
                                            }
                                            
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
                    <div class="pt-2 w-75 m-auto">
                        <a href="add_troubleshooting.php" class="btn send border-0 bg-white z-depth-1a mt-4 mb-3 text-dark">Ajouter un chantier</a>
                        <a href="#" class="btn finish border-0 bg-white z-depth-1a mt-4 mb-3">Supprimer un chantier</a>
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