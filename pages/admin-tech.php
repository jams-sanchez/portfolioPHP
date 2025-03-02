<?php
session_start();

require('../config.php');
require_once('../models/Tech.php');
require_once('../models/Image.php');

unset($_SESSION['succes']);
unset($_SESSION['erreur']);

$tech = new Tech();
$image = new Image();

// recupère infos tech
$listTech = $tech->getTech();
// recupère les categories pour le select pendant la modification
$listCat = $tech->getTechCat();

// AJOUTER UNE TECH
if (isset($_POST['validTech'])) {

    // messages d'erreurs
    if (empty($_POST['newTech'])) {
        $_SESSION['erreur'] = "Erreur - Nom manquant";
        $_POST['addTech'] = "+";
    } elseif ($_POST['chooseCat'] == null) {
        $_SESSION['erreur'] = "Erreur - Catégorie manquante";
        $_POST['addTech'] = "+";
    } elseif ($_FILES['addImage']['error'] == 4) {
        $_SESSION['erreur'] = "Erreur - Image manquante";
        $_POST['addTech'] = "+";
    } else {

        $techName = htmlspecialchars($_POST['newTech']);
        $techCatId = $_POST['chooseCat'];

        $imageName = $_FILES['addImage']['name'];
        $imageType = $_FILES['addImage']['type'];
        $imageSize = $_FILES['addImage']['size'];
        $imageBin = $_FILES['addImage']['tmp_name'];
        $imageError = $_FILES['addImage']['error'];

        // vérifie les erreurs d'upload
        if ($imageError !== UPLOAD_ERR_OK) {
            $_SESSION['erreur'] = "Erreur lors de l'upload de l'image.";
        } elseif ($imageSize > 5000000) {
            $_SESSION['erreur'] = "La taille de l'image dépasse la limite autorisée de 5MB.";
        } elseif (!in_array($imageType, ['image/jpeg', 'image/png', 'image/webp'])) {
            $_SESSION['erreur'] = "Le type de fichier n'est pas autorisé. Seuls les fichiers JPEG, PNG et WEBP sont acceptés.";
        } else {

            if ($image->addImageTech($imageName, $imageType, $imageSize, $imageBin)) {

                // ajout de tech info + lie image
                $imageId = $image->getLastImageId();
                $tech->insertTech($techName, $imageId, $techCatId);

                $_SESSION['succes'] = "Succès - Tech ajoutée";
                $_POST['addTech'] = "+";
                header('refresh: 1; url=../pages/admin-tech.php?page=tech');
            }
        }
    }
}

// MODIFIER TECH
// recuperation de la tech a modifier
if (isset($_POST['updateTech'])) {
    $idTech = $_POST['updateTech'];

    $selectedTech = $tech->getTechById($idTech);
    foreach ($selectedTech as $object) {
        $selectedTech = $object;
    }
    $_SESSION['selectedId'] = $selectedTech['id'];
    $_SESSION['selectedName'] = $selectedTech['nom'];
}

if (isset($_POST['cancelTech'])) {
    unset($_SESSION['selectedId']);
    unset($_SESSION['selectedName']);
}

// modification infos et image
if (isset($_POST['validUpdate'])) {
    $idTech = $_SESSION['selectedId'];

    // modification nom et catégorie
    if (!empty($_POST['updateName'])) {
        $techName = htmlspecialchars($_POST['updateName']);
    } else {
        $techName = $_SESSION['selectedName'];
    }
    $techCat = $_POST['updateCat'];
    $tech->updateTech($idTech, $techName, $techCat);

    // modification image
    if (!empty($_FILES['updateImage'])) {
        $imageName = $_FILES['updateImage']['name'];
        $imageType = $_FILES['updateImage']['type'];
        $imageSize = $_FILES['updateImage']['size'];
        $imageBin = $_FILES['updateImage']['tmp_name'];
        $imageError = $_FILES['updateImage']['error'];

        // vérifie les erreurs d'upload
        if ($imageError !== UPLOAD_ERR_OK) {
            $_SESSION['erreur'] = "Image non modifié.";
        } elseif ($imageSize > 5000000) {
            $_SESSION['erreur'] = "La taille de l'image dépasse la limite autorisée de 5MB.";
        } elseif (!in_array($imageType, ['image/jpeg', 'image/png', 'image/webp'])) {
            $_SESSION['erreur'] = "Le type de fichier n'est pas autorisé. Seuls les fichiers JPEG, PNG et WEBP sont acceptés.";
        } else {
            $image->updateImageTech($idTech, $imageName, $imageType, $imageSize, $imageBin);
        }
    }

    $_SESSION['succes'] = "Succès - Tech mise à jour";
    unset($_SESSION['selectedName']);
    unset($_SESSION['selectedId']);
    header('refresh: 1; url=../pages/admin-tech.php?page=tech');
}


// SUPPRIMER UNE TECH
if (isset($_POST['deleteTech'])) {

    $techId = $_POST['deleteTech'];

    // supprimer l'image de la tech, supprime la tech associé
    $image->deleteImageTech($techId);
    $_SESSION['succes'] = "Succès - Tech supprimée";
    header('refresh: 1; url=../pages/admin-tech.php?page=tech');
}

?>


<?php include_once('../include/header-admin.php'); ?>

<?php if (isset($_SESSION['userPseudo'])): ?>
    <main class="main-tech">

        <!-- message -->
        <?php if (isset($_SESSION['succes'])): ?>
            <p class="alert-green"><?= $_SESSION['succes']; ?></p>
        <?php endif; ?>
        <?php if (isset($_SESSION['erreur'])): ?>
            <p class="alert-red"><?= $_SESSION['erreur']; ?></p>
        <?php endif; ?>


        <!-- ajout de tech -->
        <?php if (isset($_POST['addTech'])): ?>

            <form action="" method="post" enctype="multipart/form-data" class="box-tech">
                <!-- add nom -->
                <div class="tech-input">
                    <label for="newTech" class="bold">Nom : </label>
                    <input type="text" name="newTech" id="newTech" class="input-text" require>
                </div>
                <!-- add categorie -->
                <div class="tech-input">
                    <label for="chooseCat" class="bold">Catégorie : </label>
                    <select class="select-button" name="chooseCat" id="chooseCat" require>
                        <option value="">--Choisir une catégorie--</option>
                        <?php foreach ($listCat as $key => $value): ?>
                            <option value="<?= $key ?>"><?= $value ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <!-- add logo -->
                <div class="tech-input">
                    <label for="addImage" class="bold">Logo : </label>
                    <input type="file" name="addImage" id="addImage" class="input-file" accept=".jpg,.jpeg,.webp,.png" require>
                </div>
                <div class="duo-button">
                    <button type="submit" name="cancelTech" class="small-button" value="Annuler"><img src="../assets/img/admin/cancel.png" /></button>
                    <button type="submit" name="validTech" class="small-button" value="Valider"><img src="../assets/img/admin/valid.png" /></button>
                </div>

            </form>

            <!-- modifier tech -->
        <?php elseif (isset($_POST['updateTech'])): ?>

            <form action="" method="post" enctype="multipart/form-data" class="box-tech">

                <!-- update nom -->
                <div class="tech-input">
                    <label for="updateTech" class="bold">Nom : </label>
                    <input type="text" name="updateName" id="updateName" class="input-text"
                        placeholder="<?= $selectedTech['nom']; ?>">
                </div>
                <!-- update categorie -->
                <div class="tech-input">
                    <label for="updateCat" class="bold">Catégorie : </label>
                    <select class="select-button" name="updateCat" id="updateCat">
                        <option value="<?= $selectedTech['catId']; ?>"><?= $selectedTech['categorie']; ?></option>
                        <?php foreach ($listCat as $id => $categorie): ?>
                            <?php if ($categorie != $selectedTech['categorie']): ?>
                                <option value="<?= $id ?>"><?= $categorie ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <!-- update logo -->
                <div class="tech-input">
                    <label for="updateImage" class="bold">Logo : </label>
                    <input type="file" name="updateImage" id="updateImage" class="input-file" accept=".jpg,.jpeg,.webp,.png">
                    <div class="logo-box">
                        <img src="<?= $selectedTech['image']; ?>" class="logo" />
                    </div>
                </div>

                <div class="duo-button">
                    <button type="submit" name="cancelTech" class="small-button" value="Annuler"><img src="../assets/img/admin/cancel.png" /></button>
                    <button type="submit" name="validUpdate" class="small-button" value="Valider"><img src="../assets/img/admin/valid.png" /></button>
                </div>

            </form>


        <?php else: ?>

            <!-- affiche toutes les techs -->

            <form action="" method="post" class="button-add-box">
                <button type="submit" name="addTech" class="button" value="+"><img src="../assets/img/admin/add.png" /></button>
            </form>

            <section class="show-tech">
                <?php foreach ($listTech as $tech): ?>
                    <div class="box-tech">
                        <div class="box-tech-info">
                            <p>Nom: <span class="bold upp"> <?= $tech['nom']; ?> </span></p>
                            <p>Categorie: <span class="bold upp"> <?= $tech['categorie']; ?></span></p>
                            <div class="logo-box">
                                <img src="<?= $tech['image']; ?>" class="logo" />
                            </div>
                        </div>
                        <form action="" method="post" class="duo-button">
                            <button type="submit" name="deleteTech" class="small-button" value="<?= $tech['id']; ?>"><img src="../assets/img/admin/delete.png" /></button>
                            <button type="submit" name="updateTech" class="small-button" value="<?= $tech['id']; ?>"><img src="../assets/img/admin/edit.png" /></button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </section>
    </main>

    <?php include_once('../include/footer-admin.php'); ?>

<?php endif; ?>
<?php endif; ?>