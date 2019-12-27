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
                    <h3 class="text-center mb-5 pt-5">Rechercher</h3>
                    <?php
                        echo '<h5 class="w-75 m-auto text-left mb-5">Par date</h5>';
                        /*if (isset($_GET['store']) && !empty($_GET['store'])) {
                            $date = date_create($_GET['store']);
                            echo '<div class="text-center w-75 mt-4 mr-auto ml-auto pb-4"><input class="bg-white col-7 text-center pl-4" type="date" id="up_inter" name="up_inter" value="' . $_GET['store'] . '" placeholder="' . date_format($date, "d-m-Y") . '" onChange="preview1(this.form)" onfocus="(this.type=\'date\')" onblur="if(this.value==\'\'){this.type=\'text\'}" style="height: 26px;" required="required"></div>';
                        } else {*/
                            echo '<div class="text-center w-75 mt-4 mr-auto ml-auto pb-4"><input class="bg-white col-7 text-center pl-4" type="date" id="up_inter" name="up_inter"  placeholder="" style="height: 26px;" required="required"></div>';
                        //}


                        echo '<h5 class="w-75 m-auto text-left mb-5 pt-5">Par salarié</h5>';

                        echo '<div class="w-25 text-center border rounded mt-4 ml-auto mb-5 mr-auto">
                            <select class="w-100 border bg-white">';

                            include './api/view/user_view.php';
                            include './api/view/admin_view.php';

                            $sql_a = admin_list();
                            $sql = user_list();
                            
                        echo '</select>
                        </div>';


                        echo '<h5 class="w-75 m-auto text-left mb-5 pt-5">Par chantier</h5>';

                        include './api/view/troubleshooting_view.php';

                        $troubles = trouble_list();

                    ?>
                </div>
            </div>
        </div>
        
        <?php include 'footer.php'; ?>