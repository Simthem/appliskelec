<?php include 'auth.php'; ?>

<!DOCTYPE html>

<html class="overflow-y ml-auto mr-auto mb-0">

    <?php include 'header.php'; ?>


                    <div class="icons-navbar">
                    <div class="menu-btn-bars text-white"><button class="menu-btn fas fa-bars text-warning w-100 fa-3x p-0"></button></div>
                    <a href="index.php" class="text-warning d-inline-flex m-auto"><img class="mr-2 ml-2" src="img/ampoule_skelec.png" alt="logo S.K.elec" height="45" width="30"><h2 class="d-inline-flex mt-0 mr-2 mb-0 ml-0">S.K.elec</h2></a>
                    <a href="add_troubleshooting.php" class="text-white pl-3"><i class="menu-btn-plus fas fa-plus-circle text-warning fa-3x rounded-circle"></i></a>
                </div>
            </div>
        </header>

        <div id="container">
            <div class="content">
                <form id="sign_ab" class="pt-4" action="api/index_global/deletion.php" method="POST">
                    <?php echo "<input type='number' id='user_id' name='user_id' value='" . $_SESSION['id'] . "' style='display: none;'>"; ?>
                        <div class="text-center">
                            <?php
                            echo '<div class="w-100 m-auto pb-4">
                            <div class="w-75 ml-auto mr-auto" id="default">
                                <div class="pt-5 pb-3 mt-4 ml-auto mr-auto">
                                    <div class="w-100 mt-3 pb-3 border-top">
                                        <h3 class="w-100 mt-3 ml-auto mb-3 mr-auto text-center">Signaler une absence</h3>
                                        <h5 class="w-100 mt-3 ml-auto mb-0 mr-auto pt-4 border-top text-center">Faites votre choix :</h5>
                                    </div>
                                </div>
                            </div>';
                                echo '<div class="m-auto pt-2 pb-3">';
                                if (isset($_GET['store']) && !empty($_GET['store'])) {
                                    $date = date_create($_GET['store']);
                                    echo '<div class="text-center w-75 mr-auto ml-auto mb-3 pb-4"><input class="bg-white col-7 text-center pl-4" type="date" id="up_inter" name="up_inter" value="' . $_GET['store'] . '" placeholder="' . date_format($date, "d-m-Y") . '" onChange="date_ab(' . $_GET['id'] . ')" onfocus="(this.type=\'date\')" onblur="if(this.value==\'\'){this.type=\'text\'}" style="height: 26px;" required="required"></div>';
                                } else {
                                    echo '<div class="text-center w-75 mr-auto ml-auto mb-3 pb-4"><input class="bg-white col-7 text-center pl-4" type="date" id="up_inter" name="up_inter"  placeholder="" onChange="date_ab(' . $_GET['id'] . ')" style="height: 26px;" required="required"></div>';
                                }
                            echo '<div class="col-6 float-left">
                                    <input type="checkbox" id="chantier" name="chantier" class="form-check-input align-middle mt-1 mb-auto" />
                                    <label class="mb-auto mt-auto ml-4 pl-1 text-center" for="chantier">Par chantier</label>
                                </div>
                                <div class="col-6 float-right">
                                    <input type="checkbox" id="ab_day" name="ab_day" class="form-check-input align-middle mt-1 mb-auto" />
                                    <label class="mb-auto mt-auto ml-4 pl-1 text-center" for="ab_day">Par journée</label>
                                </div>';

                                include './api/view/absence_view.php';

                                ab_view();
                                

                            echo '<div class="w-75 ml-auto mr-auto collapse" id="preview">
                                <div class="w-100 pt-0 pb-3">
                                    <h4 class="w-100 mt-3 ml-auto mb-3 mr-auto pt-3 border-top text-center">Récapitulatif</h4>
                                    <fieldset class="pl-3 pr-3 text-dark bg-white border rounded w-100 m-auto text-left" disabled>
                                        <br />
                                        <div class="d-inline-flex w-100">
                                            <div class="pl-0 pr-0 h6 mt-auto mb-auto w-50">Date du jour </div>:
                                            <input id="date" class="bg-white border-0 pl-3 mt-0 ml-auto mr-auto mb-0 w-50" value="' . date_format($date, 'd-m-Y') . '" />
                                        </div>
                                        <div class="d-inline-flex w-100">
                                            <div class="pl-0 pr-0 h6 mt-auto mb-auto w-50">Nom du chantier </div>:
                                            <input id="chant_name" class="bg-white border-0 pl-3 mt-0 ml-auto mr-auto mb-0 w-50" />
                                        </div>
                                        <div class="d-inline-flex w-100">
                                            <div class="pl-0 pr-0 h6 mt-auto mb-auto w-50">Total des heures </div>:
                                            <input id="inter_h" class="bg-white border-0 pl-3 mt-0 ml-auto mr-auto mb-0 w-50" />
                                        </div>
                                        <div class="d-inline-flex w-100">
                                            <div class="pl-0 pr-0 h6 mt-0 mb-auto w-50 d-inline">Commentaires </div><div class="h6 mt-0 mb-auto">:</div>
                                            <textarea id="com" class="bg-white border-0 pl-3 mt-0 ml-auto mb-0 mr-auto w-50" cols="18" rows="2" style="resize: none;"></textarea>
                                        </div>
                                    </fieldset>';
                                    echo '<div class="w-100 mt-3 ml-auto mr-auto">
                                        <input id="submit_int" type="submit" value="Soumettre" class="btn send border-0 bg-white z-depth-1a mt-4 mb-0 align-middle text-dark" />
                                    </div>
                                </div>
                            </div>
                            
                            <div id="flag_desc" name="flag_desc" class="d-block">
                                <div class="w-100 m-auto pt-5 pl-3 pb-3 pr-3">
                                    <h4 class="text-center">Par chantier</h4>
                                    <ul class="text-left pl-4" style="list-style-type:none">
                                        <li>- Séléctionnez votre jour</li>
                                        <li>- Cochez la case "Par chantier"</li>
                                        <li>- Séléctionnez le chantier correspondant</li>
                                        <li>- Sélectionnez la durée correspondante à celle de votre absence</li>
                                        <li>- Vous avez aussi la possibilité de rajouter un commentaire (à votre convenance)</li>
                                    </ul>
                                </div>
                                <div class="w-100 m-auto pt-3 pl-3 pb-3 pr-3">
                                    <h4 class="text-center">Par journée</h4>
                                    <ul class="text-left pl-4" style="list-style-type:none">
                                        <li>- Séléctionnez votre jour</li>
                                        <li>- Cochez la case "Par journée"</li>
                                        <li>- La durée est automatiquement sur la durée d\'une journée de travail [donc 7h]</li>
                                        <li>- Vous avez aussi la possibilité de rajouter un commentaire (à votre convenance)</li>
                                    </ul>
                                </div>
                            </div>';

                            ?>
                </form>
            </div>
        </div>

<?php include 'footer.php'; ?>