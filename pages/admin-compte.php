<?php

session_start();


?>


<?php include_once('../include/header-admin.php'); ?>

<main class="main-admin">

    <form action="" method="post" class="login-box">
        <div class="title-input">
            <?php if (!isset($_POST['newPseudo'])): ?>
                <p>Pseudo : <span><?= $_SESSION['userPseudo']; ?></span></p>
                <input type="submit" name="newPseudo" class="button-update" value="Modifier">
            <?php else: ?>
                <p>Pseudo : </p>
                <input type="text" name="updatePseudo" class="input-text">
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
    </form>

</main>

<?php include_once('../include/footer-admin.php'); ?>