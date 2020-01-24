<?php include 'auth.php'; ?>

<!DOCTYPE html>

<html class="overflow-y ml-auto mr-auto mb-0">
    
    <?php include 'header.php'; ?>

                <div class="icons-navbar">
                    <div class="menu-btn-bars text-white"><button class="menu-btn fas fa-bars text-warning w-100 fa-3x p-0"></button></div>
                    <a href="index.php" class="text-warning d-inline-flex m-auto"><img class="mr-2 ml-2" src="img/ampoule_skelec.png" alt="logo S.K.elec" height="45" width="30"><h2 class="d-inline-flex mt-0 mr-2 mb-0 ml-0">S.K.elec</h2></a>
                    <a href="list_profil.php" class="text-white pl-3"><i class="menu-btn-plus fas fa-search text-warning fa-3x rounded-circle"></i></a>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div id="container">
            <div class="content">
                <div class="pt-5 pb-3 mt-4 ml-auto mr-auto">
                    <h3 class="text-center mb-5 pt-5">Création d'un compte</h3>
                    <form id="add_u" class="w-100 pt-3 pl-4 pb-0 pr-4" action="./api/user/signup.php" method="POST">
                        <div class="md-form mt-1">
                            <label for="username1">Username *</label>
                            <input id="username1" name="username1" type="text" class="form-control" required>
                        </div>
                        <div class="md-form mt-4">
                            <label for="first-name">First name *</label>
                            <input type="text" id="first_name" name="first_name" class="form-control" required>
                        </div>
                        <div class="md-form mt-4">
                            <label for="last_name">Last name</label>
                            <input type="text" id="last-name" name="last_name" class="form-control">
                        </div>
                        <div class="md-form mt-4">
                            <label for="e_mail">Email</label>
                            <input type="email" id="e_mail" name="e_mail" class="form-control">
                        </div>
                        <div class="md-form mt-4">
                            <label for="phone">Phone *</label>
                            <input type="phone" id="phone" name="phone" class="form-control">
                        </div>
                        <div class="md-form mt-4">
                            <label for="pass1">Password *</label>
                            <input type="password" id="pass1" name="pass1" class="form-control" required>
                        </div>
                        <div class="md-form mt-4">
                            <label for="pass2">Confirm password *</label>
                            <input type="password" id="pass2" name="pass2" class="form-control" required>
                        </div>
                        <div class="pt-5 w-75 m-auto">
                            <input type="" value="Créer" class="btn send border-0 bg-white z-depth-1a mt-3 mb-4 text-dark" onClick="checkFUser()">
                            <a href="list_profil.php" value="return" class="btn finish border-0 bg-white z-depth-1a mt-1 mb-4 text-dark">Précédent</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    <?php include 'footer.php'; ?>