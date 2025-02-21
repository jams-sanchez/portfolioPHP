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

    public function getLastImageId(): int
    {
        $this->imageId = $this->bdd->lastInsertId();
        return $this->imageId;
    }

    // méthode pour ajouter une image
    public function addImage($imageName, $imageType, $imageSize, $imageBin): bool
    {

        $target_dir = "../assets/img/";
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
}
