<?php

session_start();
require('../config.php');
require_once('../models/User.php');

unset($_SESSION['message']);

if (empty($_SESSION)) {
    header("../pages/admin.php?page=home");
}

if (isset($_POST['connexion'])) {
    if (!empty($_POST['userPseudo']) and !empty($_POST['userPassword'])) {
        $userPseudo = $_POST['userPseudo'];
        $userPass = $_POST['userPassword'];
        $user = new User($userPseudo, $userPass);
        $user->userConnexion();
    }
}

?>


<?php include_once('../include/header-admin.php'); ?>

<main class="main-admin">
    <!-- si pas connecté -->
    <?php if (!isset($_SESSION['userPseudo'])) : ?>
        <form action="" method="post" class="login-box">
            <div class="title-input">
                <p>Pseudo</p>
                <input type="text" name="userPseudo" class="input-text">
            </div>
            <div class="title-input">
                <p>Mot de passe</p>
                <input type="password" name="userPassword" class="input-text">
            </div>
            <button type="submit" name="connexion" class="button" value="Connexion"><img src="../assets/img/login.png" /></button>

            <?php if (isset($_SESSION['message'])): ?>
                <p class="alert"><?= $_SESSION['message']; ?></p>
            <?php endif; ?>
        </form>
        <!-- sinon -->
    <?php else: ?>
        <nav class="menu-admin">
            <a href="../pages/admin-compte.php?page=compte"><img src="../assets/img/settings.png" /> Compte</a>
            <a href="../pages/admin-projets.php?page=projets"><img src="../assets/img/projet.png" /> Projets</a>
            <a href="../pages/admin-tech.php?page=tech"><img src="../assets/img/techs.png" /> Techs</a>
        </nav>
    <?php endif; ?>

</main>

<?php include_once('../include/footer-admin.php'); ?>