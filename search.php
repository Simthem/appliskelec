<?php include 'auth.php'; ?>

<!DOCTYPE html>

<html class="overflow-y ml-auto mr-auto mb-0">
    
    <?php include 'header.php'; ?>

                <div class="icons-navbar" style="z-index: 1;">
                    <div class="menu-btn-bars text-white"><button class="menu-btn fas fa-bars text-warning w-100 fa-3x p-0"></button></div>
                    <a href="index.php" class="text-warning d-inline-flex m-auto"><img class="mr-2 ml-2" src="img/ampoule_skelec.png" alt="logo S.K.elec" height="45" width="30"><h2 class="d-inline-flex mt-0 mr-2 mb-0 ml-0">S.K.elec</h2></a>
                    <a href="add_troubleshooting.php" class="text-white pl-3"><i class="menu-btn-plus fas fa-plus-circle text-warning fa-3x rounded-circle"></i></a>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div id="container">
            <div class="content">
                <div class="pt-5 pb-3 mt-4 ml-auto mr-auto">
                    <div class="w-75 pt-4 pb-4 mt-auto ml-auto mb-auto mr-auto">
                        <h3 class="w-100 mt-3 mb-0 pt-3 pb-3 text-center border-top border-bottom">Rechercher</h3>
                    </div>
                    <form class="pt-0" id="inter" action="" method="POST">
                        <?php
                        
                            echo '<div class="w-100 d-inline-block">
                                <div class="w-50 float-left">
                                    <h5 class="w-100 m-auto text-center mb-5 pt-2 pb-3">Par date :</h5>';
                                    if (isset($_GET['store']) && !empty($_GET['store'])) {
                                        $date = date_create($_GET['store']);
                                        echo '<div class="text-center w-100 mt-4 mr-auto ml-auto pb-4"><input class="bg-white col-9 text-center pl-4" type="date" id="up_inter" name="up_inter" value="' . $_GET['store'] . '" placeholder="' . date_format($date, "d-m-Y") . '" onChange="preview1(this.form)" onfocus="(this.type=\'date\')" onblur="if(this.value==\'\'){this.type=\'text\'}" style="height: 26px;"></div>';
                                    } else {
                                        echo '<div class="text-center w-100 mt-4 mr-auto ml-auto pb-4"><input class="bg-white col-9 text-center pl-4" type="date" id="up_inter" name="up_inter"  placeholder="" style="height: 26px;" onChange="preview1(this.form)"></div>';
                                    }
                                echo '</div>';

                                echo '<div class="w-50 float-right">
                                    <h5 class="w-100 m-auto text-center mb-5 pt-2 pb-3">Par période :</h5>';
                                    if (isset($_GET['bet']) && !empty($_GET['bet'])) {
                                        echo '<div class="text-center w-100 mt-4 mr-auto ml-auto pb-4"><input class="bg-white col-9 text-center pl-4" type="date" id="bet_inter" name="bet_inter" value="' . $_GET['bet'] . '" placeholder="' . date_format($date, "d-m-Y") . '" onChange="preview_bet(this.form)" onfocus="(this.type=\'date\')" onblur="if(this.value==\'\'){this.type=\'text\'}" style="height: 26px;"></div>';
                                    } else {
                                        echo '<div class="text-center w-100 mt-4 mr-auto ml-auto pb-4"><input class="bg-white col-9 text-center pl-4" type="date" id="bet_inter" name="bet_inter"  placeholder="" style="height: 26px;" onChange="preview_bet(this.form)"></div>';
                                    }
                                echo '</div>
                            </div>';

                            echo '<div class="w-75 border-top mt-3 ml-auto mb-3 mr-auto"></div>
                            <div class="w-50 text-center mt-4">
                                <h5 class="w-100 m-auto text-center mb-5 pt-2 pb-3">Par salarié :</h5>
                            </div>
                            <div class="text-center border rounded mt-4 ml-auto mb-5 mr-auto w-50">';
                                if (isset($_GET['user']) && !empty($_GET['user'])) {
                                    echo '<select id="user" class="w-100 border bg-white" size="1" style="max-width: -webkit-fill-available;" value="' . $_GET['user'] . '" onChange="preview_user(this.form)" selected="selected">
                                        <option value="' . $_GET['user'] . '" selected="selected">' . $_GET['user'] . '</option>';
                                } else {
                                    echo '<select id="user" class="w-100 border bg-white" size="1" style="max-width: -webkit-fill-available;" onChange="preview_user(this.form)">';
                                }

                                            include './api/view/user_view.php';
                                            include './api/view/admin_view.php';

                                            echo '<option></option>';

                                            $sql_a = admin_list(0);
                                            $sql = user_list(0);
                                            
                                    echo '</select>
                            </div>';

                            echo '<div class="w-75 border-top mt-3 ml-auto mb-3 mr-auto"></div>
                            <div class="w-50 text-center mt-4">
                                <h5 class="w-100 m-auto text-center mb-5 pt-2 pb-3">Par chantier :</h5>
                            </div>';

                            echo '<div class="text-center border rounded mt-4 ml-auto mb-5 mr-auto w-50">
                                <select id="chantier_name" name="chantier_name" class="w-100 border bg-white" size="1" style="max-width: -webkit-fill-available;">';
                                    
                                    include './api/view/troubleshooting_view.php';

                                    echo '<option></option>';

                                    $troubles = trouble_list(0);

                            echo '</select>
                            </div>';

                            echo '</div>
                            <div class="w-75 mt-2 mr-auto mb-3 ml-auto pb-3">
                                <a data-toggle="collapse" href="#preview" role="button" aria-expanded="false" aria-controls="preview" class="btn send border-0 bg-white z-depth-1a mt-3 mb-4 text-dark" onClick="preview2()">Prévisualiser</a>
                            </div>';

                            echo '<div class="w-100 ml-auto mr-auto collapse" id="preview">';

                                if (isset($_GET['store']) && !empty($_GET['store'])) {

                                    include './api/view/intervention_view.php';

                                    $list_inter = list_inter($_GET['store']);
                                }

                            echo '</div>';
                        ?>
                    </form>
                </div>
            </div>
        </div>
        
        <?php include 'footer.php'; ?>