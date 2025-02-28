<?php

session_start();
require('../config.php');
require_once('../models/Projet.php');
require_once('../models/Tech.php');
require_once('../models/Image.php');

unset($_SESSION['succes']);
unset($_SESSION['erreur']);

$projet = new Projet();
$tech = new Tech();
$image = new Image();

// recupere tous les infos de tous les projets
$listProjet = $projet->getAllProjets();

// recupere tech front et back avec leur id (lors du modifier)
$listTech = $tech->getTechNameId();

// AJOUTER UN PROJET
if (isset($_POST['validProjet'])) {

    // messages d'erreurs
    if (empty($_POST['newProjet'])) {
        $_SESSION['erreur'] = "Erreur - Nom manquant";
        $_POST['addProjet'] = "+";
    } elseif (!isset($_POST['newDesc'])) {
        $_SESSION['erreur'] = "Erreur - Description manquante";
        $_POST['addProjet'] = "+";
    } elseif (!isset($_POST['newLink'])) {
        $_SESSION['erreur'] = "Erreur - Lien github manquant";
        $_POST['addProjet'] = "+";
    } elseif (!isset($_POST['chooseTech'])) {
        $_SESSION['erreur'] = "Erreur - Tech manquante";
        $_POST['addProjet'] = "+";
    } elseif ($_FILES['addImage']['error'] == 4) {
        $_SESSION['erreur'] = "Erreur - Image manquante";
        $_POST['addProjet'] = "+";
    } else {

        $projetName = htmlspecialchars($_POST['newProjet']);
        $projetDesc = htmlspecialchars($_POST['newDesc']);
        $projetLink = htmlspecialchars($_POST['newLink']);
        $projetTech = $_POST['chooseTech'];

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

            if ($image->addImage($imageName, $imageType, $imageSize, $imageBin)) {

                // ajout des infos du projet + lie image
                $imageId = $image->getLastImageId();
                $projet->insertProject($projetName, $projetDesc, $projetLink, $projetTech, $imageId);

                $_SESSION['succes'] = "Succès - Projet ajouté";
                $_POST['addProjet'] = "+";
                header('refresh: 1; url=../pages/admin-projets.php?page=projets');
                exit();
            }
        }
    }
}

// MODIFIER PROJET
// recuperation du projet a modifier
if (isset($_POST['updateProjet'])) {
    $idProjet = $_POST['updateProjet'];
    $_SESSION['projetId'] = $idProjet;

    $selectedProjet = $projet->getProjetById($idProjet);

    $_SESSION['projetName'] = $selectedProjet['nom'];
    $_SESSION['projetDesc'] = $selectedProjet['desc'];
    $_SESSION['projetLien'] = $selectedProjet['lien'];
    $_SESSION['projetTechs'] = $selectedProjet['techs'];
}

// modification projet et son image
if (isset($_POST['validUpdateP'])) {
    $idProjet = $_SESSION['projetId'];

    // modification infos du projet
    if (!empty($_POST['updateName'])) {
        $projetName = htmlspecialchars($_POST['updateName']);
    } else {
        $projetName = $_SESSION['projetName'];
    }
    if (!empty($_POST['updateDesc'])) {
        $projetDesc = htmlspecialchars($_POST['updateDesc']);
    } else {
        $projetDesc = $_SESSION['projetDesc'];
    }
    if (!empty($_POST['updateLink'])) {
        $projetLink = htmlspecialchars($_POST['updateLink']);
    } else {
        $projetLink = $_SESSION['projetLien'];
    }

    $projet->updateProjet($idProjet, $projetName, $projetDesc, $projetLink);

    // modification des techs du projet
    if (!empty($_POST['updateTech'])) {
        $projetTech = $_POST['updateTech'];

        if ($projetTech != $_SESSION['projetTechs']) {
            $projet->deleteProjetTech($idProjet);
            $projet->insertProjetTech($idProjet, $projetTech);
        }
    } else {
        $projetTech = $_SESSION['projetTechs'];
    }


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
            $image->updateImageTech($idProjet, $imageName, $imageType, $imageSize, $imageBin);
        }
    }

    $_SESSION['succes'] = "Succès - Projet mis à jour";
    unset($_SESSION['projetId']);
    unset($_SESSION['projetName']);
    unset($_SESSION['projetDesc']);
    unset($_SESSION['projetLien']);
    unset($_SESSION['projetTechs']);
    header('refresh: 1; url=../pages/admin-projets.php?page=projets');
    exit();
}

if (isset($_POST['cancelProjetP'])) {
    unset($_SESSION['projetId']);
    unset($_SESSION['projetName']);
    unset($_SESSION['projetDesc']);
    unset($_SESSION['projetLien']);
    unset($_SESSION['projetTechs']);
    unset($_POST);
    header('Location: ../pages/admin-projets.php?page=projets');
    exit();
}

// SUPPRIMER UN PROJET
if (isset($_POST['deleteProjet'])) {

    $projetId = $_POST['deleteProjet'];

    // supprime les techs associé au projet
    $projet->deleteProjetTech($projetId);

    // supprimer l'image de la tech supprime la tech associé
    $image->deleteImageProjet($projetId);
    $_SESSION['succes'] = "Succès - Projet supprimé";
    header('refresh: 1; url=../pages/admin-projets.php?page=projets');
    exit();
}


?>


<?php include_once('../include/header-admin.php'); ?>

<?= var_dump($_SESSION); ?>
<?= var_dump($_POST); ?>

<?php if (isset($_SESSION['userPseudo'])): ?>
    <main class="main-projet">

        <!-- message -->
        <?php if (isset($_SESSION['succes'])): ?>
            <p class="alert-green"><?= $_SESSION['succes']; ?></p>
        <?php endif; ?>
        <?php if (isset($_SESSION['erreur'])): ?>
            <p class="alert-red"><?= $_SESSION['erreur']; ?></p>
        <?php endif; ?>

        <!-- ajout de projet -->
        <?php if (isset($_POST['addProjet'])): ?>

            <form action="" method="post" enctype="multipart/form-data" class="box-projet">
                <!-- add nom -->
                <div class="projet-input">
                    <label for="newProjet" class="bold">Nom : </label>
                    <input type="text" name="newProjet" id="newProjet" class="input-text" placeholder="nom du projet" require>
                </div>
                <!-- add description -->
                <div class="projet-input">
                    <label for="newDesc" class="bold">Description : </label>
                    <textarea name="newDesc" id="newDesc" class="input-text" placeholder="décris le projet" rows="5" cols="38"
                        require></textarea>
                </div>
                <!-- add lien -->
                <div class="projet-input">
                    <label for="newLink" class="bold">Lien Github : </label>
                    <input type="text" name="newLink" id="newLink" class="input-text" placeholder="www.github.com/jams-sanchez/"
                        require>
                </div>
                <!-- add tech -->
                <div class="projet-input">
                    <p class="bold">Tech : </p>
                    <div class="projet-input-tech">
                        <?php foreach ($listTech as $id => $nom): ?>
                            <div class="tech-box">
                                <input type="checkbox" name="chooseTech[]" id="chooseTech" value="<?= $id ?>">
                                <label for="chooseTech"><?= $nom ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
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

            <!-- modifie un projet -->
        <?php elseif (isset($_POST['updateProjet'])): ?>

            <form action="" method="post" enctype="multipart/form-data" class="box-projet">
                <!-- update nom -->
                <div class="projet-input">
                    <label for="updateProjet" class="bold">Nom : </label>
                    <input type="text" name="updateName" id="updateName" class="input-text"
                        placeholder="<?= $_SESSION['projetName']; ?>">
                </div>
                <!-- update description -->
                <div class="projet-input">
                    <label for="updateDesc" class="bold">Description : </label>
                    <textarea name="updateDesc" id="updateDesc" class="input-text" placeholder="<?= $_SESSION['projetDesc']; ?>"
                        rows="5" cols="38"></textarea>
                </div>
                <!-- update lien -->
                <div class="projet-input">
                    <label for="updateLink" class="bold">Lien Github : </label>
                    <input type="text" name="updateLink" id="updateLink" class="input-text"
                        placeholder="<?= $_SESSION['projetLien']; ?>">
                </div>
                <!-- update tech -->
                <div class="projet-input">
                    <p class="bold">Tech : </p>
                    <div class="projet-input-tech">
                        <?php foreach ($listTech as $id => $nom): ?>
                            <?php if (in_array($nom, $_SESSION['projetTechs'])): ?>
                                <div class="tech-box">
                                    <label for="updateTech"><?= $nom ?></label>
                                    <input type="checkbox" name="updateTech[]" id="updateTech" value="<?= $id ?>" checked>
                                </div>
                            <?php else: ?>
                                <div class="tech-box">
                                    <label for="updateTech"><?= $nom ?></label>
                                    <input type="checkbox" name="updateTech[]" id="updateTech" value="<?= $id ?>">
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <!-- update logo -->
                <div class="projet-input">
                    <label for="updateImage" class="bold">Image : </label>
                    <input type="file" name="updateImage" id="updateImage" class="input-file" accept=".jpg,.jpeg,.webp,.png"
                        require>
                </div>
                <div class="duo-button">
                    <input type="submit" name="cancelProjetP" class="button-red" value="Annuler">
                    <input type="submit" name="validUpdateP" class="button-green" value="Valider">

                </div>

            </form>

        <?php else: ?>

            <!-- affiche toutes les techs -->

            <form action="" method="post" class="button-add-box">
                <input type="submit" name="addProjet" class="button-green bold" value="+">
            </form>

            <section class="show-projet">
                <?php foreach ($listProjet as $projet => $info): ?>
                    <div class="box-projet">
                        <div class="box-projet-info">
                            <div class="projet-img">
                                <img src="<?= $info['image'] ?>" />
                            </div>
                            <section class="text-projet-info">
                                <div class="projet-title-box">
                                    <h2><?= $info['nom'] ?> </h2>
                                    <div class="projet-tech">
                                        <span class="bold upp">Tech: </span>
                                        <?php foreach ($info['techs'] as $tech): ?>
                                            <div class="box-projet-tech">
                                                <p><?= $tech ?></p>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <article class="projet-text-box">
                                    <p><?= $info['desc'] ?></p>
                                    <a href="<?= $info['lien'] ?>" target="_blank">Github</a>
                                </article>
                            </section>
                        </div>
                        <form action="" method="post" class="duo-button">
                            <button type="submit" name="deleteProjet" class="button-red" value="<?= $projet ?>">Supprimer</button>
                            <button type="submit" name="updateProjet" class="button-yellow" value="<?= $projet ?>">Modifier</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>
    </main>

    <?php include_once('../include/footer-admin.php'); ?>
<?php endif; ?>