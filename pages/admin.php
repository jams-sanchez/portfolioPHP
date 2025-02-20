<?php

session_start();

require_once('../models/User.php');

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
            <input type="submit" name="connexion" class="button-connexion" value="Connexion">
        </form>
        <!-- sinon -->
    <?php else: ?>
        <nav class="menu-admin">
            <a href="../pages/admin-compte.php?page=compte">Compte</a>
            <a href="../pages/admin-projets.php?page=projets">Projets</a>
            <a href="../pages/admin-tech.php?page=tech">Tech</a>
            <a href="../pages/admin-images.php?page=images">Images</a>
        </nav>
    <?php endif; ?>

    <?php if (isset($_SESSION['message'])): ?>
        <?= $_SESSION['message']; ?>
    <?php endif; ?>

</main>

<?php include_once('../include/footer-admin.php'); ?>