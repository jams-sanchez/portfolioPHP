<?php

session_start();
require('../config.php');
require_once('../models/Projet.php');
require_once('../models/Tech.php');
require_once('../models/Image.php');


if (!isset($_SESSION['userPseudo'])) {
    header("../pages/admin.php?page=home");
}


?>


<?php include_once('../include/header-admin.php'); ?>

<?php if (isset($_SESSION['userPseudo'])): ?>
    <main class="main-projet">

        <?= var_dump($_SESSION); ?>
        <?= var_dump($_POST); ?>

        <!-- message -->
        <?php if (isset($_SESSION['succes'])): ?>
            <p class="alert-green"><?= $_SESSION['succes']; ?></p>
        <?php endif; ?>
        <?php if (isset($_SESSION['erreur'])): ?>
            <p class="alert-red"><?= $_SESSION['erreur']; ?></p>
        <?php endif; ?>

        <!-- ajout de projet -->
        <?php if (isset($_POST['addProjet'])) : ?>

            <form action="" method="post" enctype="multipart/form-data" class="box-projet">
                <!-- add nom -->
                <div class="projet-input">
                    <label for="newProjet" class="bold">Nom : </label>
                    <input type="text" name="newProjet" id="newProjet" class="input-text" require>
                </div>
                <!-- add description -->
                <div class="projet-input">
                    <label for="newDesc" class="bold">Description : </label>
                    <textarea name="newDesc" id="newDesc" class="input-text" placeholder="décris le projet" rows="5" cols="38"></textarea>
                </div>
                <!-- add lien -->
                <div class="projet-input">
                    <label for="newLink" class="bold">Lien Github : </label>
                    <input type="text" name="newLink" id="newLink" class="input-text" require>
                </div>
                <!-- add tech -->
                <div class="projet-input">

                    <p class="bold">Tech : </p>
                    <?php  ?>
                    <input type="checkbox" name="chooseTech" id="chooseTech" value="{techId}">
                    <label for="chooseTech">{techName}</label>
                    <?php ?>

                </div>
                <!-- add logo -->
                <div class="projet-input">
                    <label for="addImage" class="bold">Image : </label>
                    <input type="file" name="addImage" id="addImage" class="input-file" accept=".jpg,.jpeg,.webp,.png" require>
                </div>
                <div class="duo-button">
                    <input type="submit" name="cancelProjet" class="button-red" value="Annuler">
                    <input type="submit" name="validProjet" class="button-green" value="Valider">

                </div>

            </form>

        <?php else: ?>

            <!-- affiche toutes les techs -->

            <form action="" method="post" class="button-add-box">
                <input type="submit" name="addProjet" class="button-green bold" value="+">
            </form>

            <section class="show-projet">
                <?php  ?>
                <div class="box-projet">
                    <div class="box-projet-info">
                        <div class="projet-img">
                            <img src="../assets/img/project-matchatea.webp" class="projet-img" />
                        </div>
                        <section class="text-projet-info">
                            <div class="projet-title-box">
                                <h2>{projet titre} </h2>
                                <div class="projet-tech">
                                    <span class="bold upp">Tech: </span>
                                    <div class="box-projet-tech">
                                        <p>{tech}</p>
                                    </div>
                                </div>
                            </div>
                            <article class="projet-text-box">
                                <p>{description}</p>
                                <a href="#">{lien Github}</a>
                            </article>
                        </section>
                    </div>
                    <form action="" method="post" class="duo-button">
                        <button type="submit" name="deleteTech" class="button-red" value="">Supprimer</button>
                        <button type="submit" name="updateTech" class="button-yellow" value="">Modifier</button>
                    </form>
                </div>
                <?php  ?>
            </section>
        <?php endif; ?>
    </main>

    <?php include_once('../include/footer-admin.php'); ?>
<?php endif; ?>