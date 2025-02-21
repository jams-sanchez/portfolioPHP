<?php
session_start();

require('../config.php');
require_once('../models/Tech.php');

$tech = new Tech();
$listTech = $tech->getTech();

// var_dump($listTech);
?>


<?php include_once('../include/header-admin.php'); ?>

<?php if (isset($_SESSION['userPseudo'])): ?>
    <main class="main-tech">

        <input type="submit" name="addTech" class="button-green bold" value="+">

        <section class="show-tech">

            <?php foreach ($listTech as $tech => $value): ?>
                <div class="box-tech">

                    <div class="box-tech-info">
                        <h2>Nom: <?= $tech; ?> </h2>
                        <p>Categorie: <?= $value['categorie']; ?></p>
                        <!-- image en dur -->
                        <img src="../assets/img/logo-html.webp" class="logo" />
                    </div>


                    <div class="duo-button">
                        <input type="submit" name="delete-tech" class="button-red" value="Supprimer">
                        <input type="submit" name="update-tech" class="button-yellow" value="Modifier">
                    </div>

                </div>
            <?php endforeach; ?>

        </section>
    </main>

    <?php include_once('../include/footer-admin.php'); ?>

<?php endif; ?>