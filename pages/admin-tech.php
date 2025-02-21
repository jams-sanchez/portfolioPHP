<?php
session_start();

require('../config.php');
require_once('../models/Tech.php');

$tech = new Tech();
$listTech = $tech->getTech();
$listCat = $tech->getTechCat();

if (isset($_POST['validTech'])) {
    if (!empty($_POST['newTech']) && !empty($_POST['chooseCat']) && !empty($_POST['addImage'])) {
        $techName = htmlentities($_POST['newTech']);
        $techCat = $_POST['chooseCat'];
        $techImage = $_FILES;
    }
}



// var_dump($listTech);
?>


<?php include_once('../include/header-admin.php'); ?>

<?php var_dump($_FILES); ?>

<?php if (isset($_SESSION['userPseudo'])): ?>
    <main class="main-tech">

        <?php if (isset($_POST['addTech'])) : ?>

            <form action="" method="post" enctype="multipart/form-data" class="box-tech">
                <div class="tech-input">
                    <label for="newTech" class="bold">Nom : </label>
                    <input type="text" name="newTech" id="newTech" class="input-text" require>
                </div>
                <div class="tech-input">
                    <label for="chooseCat" class="bold">Catégorie : </label>
                    <select class="select-button" name="chooseCat" id="chooseCat" require>
                        <option>--Choisir une catégorie--</option>
                        <!-- ajouter boucle php pour les categories -->
                        <?php foreach ($listCat as $key => $value): ?>
                            <option value="<?= $key ?>"><?= $value ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="tech-input">
                    <label for="addImage" class="bold">Logo : </label>
                    <input type="file" name="addImage" id="addImage" class="input-file" accept=".jpg,.jpeg,.webp,.png" require>
                </div>
                <div class="duo-button">
                    <input type="submit" name="cancelTech" class="button-red" value="Annuler">
                    <input type="submit" name="validTech" class="button-green" value="Valider">

                </div>
            </form>

        <?php else: ?>

            <form action="" method="post">
                <input type="submit" name="addTech" class="button-green bold" value="+">
            </form>

            <section class="show-tech">

                <?php foreach ($listTech as $tech => $value): ?>
                    <div class="box-tech">

                        <div class="box-tech-info">
                            <p>Nom: <span class="bold upp"> <?= $tech; ?> </span></p>
                            <p>Categorie: <span class="bold upp"> <?= $value['categorie']; ?></span></p>
                            <!-- image en dur -->
                            <img src="../assets/img/logo-php.webp" class="logo" />
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
<?php endif; ?>