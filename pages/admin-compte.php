<?php

session_start();
require('../config.php');
require_once('../models/User.php');

unset($_SESSION['erreur']);
unset($_SESSION['succes']);


if (isset($_POST['validPass'])) {

    if (!empty($_POST['currentPass']) && !empty($_POST['newPass'])) {
        $userPseudo = htmlspecialchars($_SESSION['userPseudo']);
        $currentPass = htmlspecialchars($_POST['currentPass']);
        $newPass = $_POST['newPass'];
        $user = new User();
        $user->updatePass($userPseudo, $currentPass, $newPass);
        $_POST = array();
    }
}

if (isset($_POST['validPseudo'])) {

    if (!empty($_POST['newPseudo'])) {
        $userPseudo = $_SESSION['userPseudo'];
        $newPseudo = $_POST['newPseudo'];
        $user = new User();
        $user->updatePseudo($userPseudo, $newPseudo);
        $_POST = array();
    }
}

?>


<?php include_once('../include/header-admin.php'); ?>

<?php if (isset($_SESSION['userPseudo'])): ?>
    <main class="main-admin">

        <form action="" method="post" class="login-box">

            <?php if (isset($_POST['updatePseudo'])): ?>
                <div class="title-input">
                    <label for="newPseudo">Pseudo : </label>
                    <input type="text" name="newPseudo" id="newPseudo" class="input-text" placeholder="<?= $_SESSION['userPseudo']; ?>">
                    <ul class="pseudo-condition">
                        <li>- doit avoir au moins 3 lettres</li>
                        <li>- maximum 20 caratères</li>
                        <li>- aucun espace, ni tiret, ni caratères spéciaux</li>
                    </ul>
                    <div class="button-account">
                        <button type="submit" name="cancelPseudo" class="small-button" value="Annuler"><img src="../assets/img/cancel.png" /></button>
                        <button type="submit" name="validPseudo" class="small-button" value="Valider"><img src="../assets/img/valid.png" /></button>
                    </div>
                </div>

            <?php elseif (isset($_POST['updatePass'])): ?>

                <div class="title-input">
                    <label for="newPass">Mot de passe: </label>
                    <input type="password" name="currentPass" id="newPass" placeholder="mot de passe actuel" class="input-text">
                    <input type="password" name="newPass" id="newPass" placeholder="nouveau mot de passe" class="input-text">
                    <div class="button-account">
                        <button type="submit" name="cancelPass" class="small-button" value="Annuler"><img src="../assets/img/cancel.png" /></button>
                        <button type="submit" name="validPass" class="small-button" value="Valider"><img src="../assets/img/valid.png" /></button>
                    </div>
                </div>
            <?php else: ?>
                <div class="title-input">
                    <div class="pseudo-box">
                        <p class="bold">Pseudo : </p>
                        <p><?= $_SESSION['userPseudo']; ?></p>
                        <button type="submit" name="updatePseudo" class="smaller-button" value="Modifier">
                            <img src="../assets/img/edit.png" />
                        </button>
                    </div>
                    <div class="pseudo-box">
                        <p class="bold">Mot de passe: </p>
                        <p>****</p>
                        <button type="submit" name="updatePass" class="smaller-button" value="Modifier">
                            <img src="../assets/img/edit.png" />
                        </button>
                    </div>
                </div>
                <!-- message -->
                <?php if (isset($_SESSION['succes'])): ?>
                    <p class="alert-green"><?= $_SESSION['succes']; ?></p>
                <?php endif; ?>
                <?php if (isset($_SESSION['erreur'])): ?>
                    <p class="alert-red"><?= $_SESSION['erreur']; ?></p>
                <?php endif; ?>
            <?php endif; ?>

        </form>

    </main>

    <?php include_once('../include/footer-admin.php'); ?>
<?php endif; ?>