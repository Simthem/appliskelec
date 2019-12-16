<?php include 'auth.php'; ?>

<!DOCTYPE html>

<html class="overflow-y ml-auto mr-auto mb-0">

    <?php include 'header.php'; ?>
    
                <div class="icons-navbar">
                    <div class="menu-btn-bars text-white"><button class="menu-btn fas fa-bars text-warning w-100 fa-3x p-0"></button></div>
                    <a href="index.php" class="text-warning d-inline-flex m-auto"><img class="mr-2 ml-2" src="img/ampoule_skelec.png" alt="logo S.K.elec" height="45" width="30"><h2 class="d-inline-flex mt-0 mr-2 mb-0 ml-0">S.K.elec</h2></a>
                    <a href="troubleshooting_list.php" class="text-white pl-3"><i class="menu-btn-plus fas fa-search text-warning fa-3x rounded-circle"></i></a>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div id="container">
            <div class="content">
                <div class="pt-5 pb-3 mt-4 ml-auto mr-auto">
                    <h3 class="text-center mb-5 pt-5">Ajout d'un chantier</h3>
                    <form id="add_trouble" class="w-100 pt-3 pl-4 pb-0 pr-4" action="./api/troubleshooting/add_site.php" method="POST">
                        <?php
                            if ($_SESSION['id'] == $admin['id']) {
                                $sql = $bdd->prepare(
                                "SELECT 
                                    MAX(last_insert_id(num_chantier)) as max
                                FROM
                                    appli_skelec.chantiers
                                WHERE
                                    num_chantier != 0");

                                $sql->execute();
                                $result = $sql->fetch();
                                $new_id = $result['max'] + 1;

                                echo '<div class="md-form mt-1">
                                    <label for="num_chantier">ID de chantier</label>';
                                echo "<input type='number' value=" . $new_id . " id='num_chantier' name='num_chantier' class='form-control'>";
                        ?>          <input value="<?php echo $_SESSION['id'] ?>" id="session" name="session" style="display: none;">
                        <?php   echo '</div>';
                            } else {
                                echo '<div class="md-form mt-1">
                                    <label for="num_chantier" class="text-secondary">ID de chantier</label>
                                    <input type="number" id="num_chantier" name="num_chantier" class="form-control" disabled>';
                        ?>          <input value="" id="session" name="session" style="display: none;">
                        <?php   echo '</div>';
                            }
                        ?>
                        
                        <div class="md-form mt-4">
                            <label for="name">Libellé de chantier *</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>
                        <div class="md-form mt-4">
                            <label for="contact_name">Nom du client *</label>
                            <input type="text" id="contact_name" name="contact_name" class="form-control" required>
                        </div>
                        <div class="md-form mt-4">
                            <label for="contact_phone">Téléphone</label>
                            <input type="text" id="contact_phone" name="contact_phone" class="form-control">
                        </div>
                        <div class="md-form mt-4">
                            <label for="e_mail">E_mail</label>
                            <input type="email" id="e_mail" name="e_mail" class="form-control">
                        </div>
                        <div class="md-form mt-4">
                            <label for="contact_address">Adresse</label>
                            <input type="text" id="contact_address" name="contact_address" class="form-control">
                        </div>
                        <div class="md-form mt-4">
                            <label for="commit">Commentaires</label>
                            <textarea type="text" id="commit" name="commit" class="form-control"></textarea>
                        </div>
                        <input type="text" id="type" name="type" value="NULL" style="display: none;">
                        <input type="number" id="state" name="state" value="1" style="display: none;">
                        <div class="pt-5 w-75 m-auto">
                            <input type="submit" value="Valider" class="btn send border-0 bg-white z-depth-1a mt-3 mb-4 text-dark" onClick="checkForm()">
                            <a href="troubleshooting_list.php" value="return" class="btn finish border-0 bg-white z-depth-1a mt-1 mb-4 text-dark">Précédent</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    <?php include 'footer.php'; ?>