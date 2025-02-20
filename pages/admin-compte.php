<?php

session_start();

unset($_SESSION['message']);

require_once('../models/User.php');

if (isset($_POST['validPass'])) {

    if (!empty($_POST['currentPass']) && !empty($_POST['newPass'])) {
        $userPseudo = $_SESSION['userPseudo'];
        $currentPass = $_POST['currentPass'];
        $newPass = $_POST['newPass'];
        $user = new User();
        $user->updatePass($userPseudo, $currentPass, $newPass);
    }
}

if (isset($_POST['validPseudo'])) {

    if (!empty($_POST['newPseudo'])) {
        $userPseudo = $_SESSION['userPseudo'];
        $newPseudo = $_POST['newPseudo'];
        $user = new User();
        $user->updatePseudo($userPseudo, $newPseudo);
    }
}

?>


<?php include_once('../include/header-admin.php'); ?>

<main class="main-admin">

    <form action="" method="post" class="login-box">
        <div class="title-input">
            <?php if (!isset($_POST['updatePseudo'])): ?>
                <p>Pseudo : <span><?= $_SESSION['userPseudo']; ?></span></p>
                <input type="submit" name="updatePseudo" class="button-update" value="Modifier">
            <?php else: ?>
                <p>Pseudo : </p>
                <input type="text" name="newPseudo" class="input-text">
                <ul class="pseudo-condition">
                    <li>- doit avoir au moins 3 lettres</li>
                    <li>- maximum 20 caratères</li>
                    <li>- aucun espace, ni tiret, ni caratères spéciaux</li>
                </ul>
                <div class="duo-button">
                    <input type="submit" name="cancelPseudo" class="button-update" value="Annuler">
                    <input type="submit" name="validPseudo" class="button-update" value="Valider">
                </div>
            <?php endif; ?>
        </div>
        <div class="title-input">
            <?php if (!isset($_POST['updatePass'])): ?>
                <p>Mot de passe: <span>***</span></p>
                <input type="submit" name="updatePass" class="button-update" value="Modifier">
            <?php else: ?>
                <p>Mot de passe: </p>
                <input type="password" name="currentPass" placeholder="mot de passe actuel" class="input-text">
                <input type="password" name="newPass" placeholder="nouveau mot de passe" class="input-text">
                <div class="duo-button">
                    <input type="submit" name="cancelPass" class="button-update" value="Annuler">
                    <input type="submit" name="validPass" class="button-update" value="Valider">
                </div>
            <?php endif; ?>
        </div>
        <?php if (isset($_SESSION['message'])): ?>
            <p class="alert"><?= $_SESSION['message']; ?></p>
        <?php endif; ?>
    </form>

</main>

<?php include_once('../include/footer-admin.php'); ?>