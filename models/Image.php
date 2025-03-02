<?php

require_once('../models/Bdd.php');

class Image extends Bdd
{
    private array $image;
    private int $imageId;

    public function __construct(array $image = array(), int $imageId = 0)
    {
        parent::__construct();
        $this->image = $image;
        $this->imageId = $imageId;
    }

    // methode pour recupere l'id de la derniere image uploader
    public function getLastImageId(): int
    {
        $this->imageId = $this->bdd->lastInsertId();
        return $this->imageId;
    }

    // méthode pour ajouter une image tech
    public function addImageTech($imageName, $imageType, $imageSize, $imageBin): bool
    {
        $target_dir = "../assets/img/techs/";
        $target_file = $target_dir . basename($imageName);

        if (file_exists($target_file)) {
            $_SESSION['erreur'] = "Erreur - Une image existe déjà";
            $_POST['addTech'] = "+";
            return false;
        } else {
            if (move_uploaded_file($imageBin, $target_file)) {
                $imageStmt = "INSERT INTO image
                (nom, type, taille, bin) 
                VALUES (:nom, :type, :taille, :bin)";
                $imageStmt = $this->bdd->prepare($imageStmt);
                $imageStmt->execute([
                    ':nom' => $imageName,
                    ':type' => $imageType,
                    ':taille' => $imageSize,
                    ':bin' => $target_file
                ]);
                return true;
            } else {
                $_SESSION['erreur'] = "Erreur - déplacement de l'image impossible.";
                $_POST['addTech'] = "+";
                return false;
            }
        }
    }

    // méthode pour modifier l'image d'une tech
    public function updateImageTech($techId, $imageName, $imageType, $imageSize, $imageBin): void
    {
        $target_dir = "../assets/img/techs/";
        $target_file = $target_dir . basename($imageName);

        // récupère l'ancienne image
        $oldImageStmt = "SELECT image.nom
        FROM image
        JOIN tech ON tech.image_id = image.id
        WHERE tech.id = :id";
        $oldImageStmt = $this->bdd->prepare($oldImageStmt);
        $oldImageStmt->execute([
            ':id' => $techId
        ]);
        $oldImageName = $oldImageStmt->fetchColumn();

        // supprime l'ancienne image du dossier
        $oldFilePath = $target_dir . $oldImageName;
        if (file_exists($oldFilePath)) {
            unlink($oldFilePath);
        }

        // nouvelle image
        if (file_exists($target_file)) {
            $_SESSION['erreur'] = "Erreur - Une image existe déjà";
            $_POST['updateTech'] = $techId;
        } else {
            if (move_uploaded_file($imageBin, $target_file)) {
                $imageStmt = "UPDATE image SET nom = :nom, type = :type, 
                taille = :taille, bin = :bin
                WHERE id = :id";
                $imageStmt = $this->bdd->prepare($imageStmt);
                $imageStmt->execute([
                    ':id' => $techId,
                    ':nom' => $imageName,
                    ':type' => $imageType,
                    ':taille' => $imageSize,
                    ':bin' => $target_file
                ]);
            } else {
                $_SESSION['erreur'] = "Erreur - déplacement de l'image impossible.";
                $_POST['updateTech'] = $techId;
            }
        }
    }

    // méthode pour supprimer l'image d'une tech
    public function deleteImageTech($techId): void
    {
        // récupère l'id de l'image via l'id de la tech
        $imageStmt = "SELECT image_id 
        FROM tech
        WHERE tech.id = :id";
        $imageStmt = $this->bdd->prepare($imageStmt);
        $imageStmt->execute([
            'id' => $techId
        ]);
        $imageId = $imageStmt->fetchColumn();

        // recupere le bin de l'image
        $binStmt = "SELECT image.bin
        FROM image
        WHERE id = :id";
        $binStmt = $this->bdd->prepare($binStmt);
        $binStmt->execute([
            ':id' => $imageId
        ]);
        $imageBin = $binStmt->fetchColumn();

        // supprime l'image
        $deleteStmt = "DELETE FROM image
        WHERE image.id = :id";
        $deleteStmt = $this->bdd->prepare($deleteStmt);
        $deleteStmt->execute([
            ':id' => $imageId
        ]);

        // supprime le fichier de l'image du dossier
        $filePath = $imageBin;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    // méthode pour ajouter une image projet
    public function addImageProjet($imageName, $imageType, $imageSize, $imageBin): bool
    {
        $target_dir = "../assets/img/projets/";
        $target_file = $target_dir . basename($imageName);

        if (file_exists($target_file)) {
            $_SESSION['erreur'] = "Erreur - Une image existe déjà";
            $_POST['addProjet'] = "+";
            return false;
        } else {
            if (move_uploaded_file($imageBin, $target_file)) {
                $imageStmt = "INSERT INTO image
                (nom, type, taille, bin) 
                VALUES (:nom, :type, :taille, :bin)";
                $imageStmt = $this->bdd->prepare($imageStmt);
                $imageStmt->execute([
                    ':nom' => $imageName,
                    ':type' => $imageType,
                    ':taille' => $imageSize,
                    ':bin' => $target_file
                ]);
                return true;
            } else {
                $_SESSION['erreur'] = "Erreur - déplacement de l'image impossible.";
                $_POST['addProjet'] = "+";
                return false;
            }
        }
    }

    // méthode pour modifier l'image d'une tech
    public function updateImageProjet($projetId, $imageName, $imageType, $imageSize, $imageBin): void
    {
        $target_dir = "../assets/img/projets/";
        $target_file = $target_dir . basename($imageName);

        // récupère l'ancienne image
        $oldImageStmt = "SELECT image.nom
        FROM image
        JOIN projet ON projet.image_id = image.id
        WHERE projet.id = :id";
        $oldImageStmt = $this->bdd->prepare($oldImageStmt);
        $oldImageStmt->execute([
            ':id' => $projetId
        ]);
        $oldImageName = $oldImageStmt->fetchColumn();

        // supprime l'ancienne image du dossier
        $oldFilePath = $target_dir . $oldImageName;
        if (file_exists($oldFilePath)) {
            unlink($oldFilePath);
        }

        // nouvelle image
        if (file_exists($target_file)) {
            $_SESSION['erreur'] = "Erreur - Une image existe déjà";
            $_POST['updateTech'] = $projetId;
        } else {
            if (move_uploaded_file($imageBin, $target_file)) {
                $imageStmt = "UPDATE image SET nom = :nom, type = :type, 
                taille = :taille, bin = :bin
                WHERE id = :id";
                $imageStmt = $this->bdd->prepare($imageStmt);
                $imageStmt->execute([
                    ':id' => $projetId,
                    ':nom' => $imageName,
                    ':type' => $imageType,
                    ':taille' => $imageSize,
                    ':bin' => $target_file
                ]);
            } else {
                $_SESSION['erreur'] = "Erreur - déplacement de l'image impossible.";
                $_POST['updateTech'] = $projetId;
            }
        }
    }

    // méthode pour supprimer l'image d'un projet
    public function deleteImageProjet($projetId): void
    {
        // récupère l'id de l'image via l'id du projet
        $imageStmt = "SELECT image_id 
        FROM projet
        WHERE projet.id = :id";
        $imageStmt = $this->bdd->prepare($imageStmt);
        $imageStmt->execute([
            'id' => $projetId
        ]);
        $imageId = $imageStmt->fetchColumn();

        // recupere le bin de l'image
        $binStmt = "SELECT image.bin
        FROM image
        WHERE id = :id";
        $binStmt = $this->bdd->prepare($binStmt);
        $binStmt->execute([
            ':id' => $imageId
        ]);
        $imageBin = $binStmt->fetchColumn();

        // supprime l'image
        $deleteStmt = "DELETE FROM image
        WHERE image.id = :id";
        $deleteStmt = $this->bdd->prepare($deleteStmt);
        $deleteStmt->execute([
            ':id' => $imageId
        ]);

        // supprime le fichier de l'image du dossier
        $filePath = $imageBin;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}
