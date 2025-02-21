<?php
// a supprimer apres test
require('../config.php');
// 

require_once('../models/Bdd.php');

class Tech extends Bdd
{

    private int $techId;
    private string $techName;
    private string $techCat;
    private string $techImage;

    public function __construct(string $techName = "", int $techId = 0)
    {
        parent::__construct();
        $this->techId = $techId;
        $this->techName = $techName;
        $this->techCat = $techCat = "";
        $this->techImage = $techImage = "";
    }

    // méthode pour récuperer toutes les techs et leurs infos
    public function getTech(): array
    {
        $getStmt = "SELECT tech.id, tech.nom AS tech, 
        tech_cat.nom AS categorie,
        image.bin
        FROM tech
        JOIN tech_cat ON tech_cat.id = tech.tech_cat_id
        JOIN image ON image.id = tech.image_id";
        $getStmt = $this->bdd->prepare($getStmt);
        $getStmt->execute();
        $listTech = $getStmt->fetchAll(PDO::FETCH_ASSOC);

        $tech = [];
        foreach ($listTech as $value) {
            $this->techId = $value['id'];
            $this->techName = $value['tech'];
            $this->techCat = $value['categorie'];
            $this->techImage = $value['bin'];

            $tech[$this->techName] = [
                'id' => $this->techId,
                'categorie' => $this->techCat,
                'image' => $this->techImage
            ];
        }
        return $tech;
    }

    // méthode pour ajouter une tech
    public function insertTech($techName, $techImageId, $techCatId): void
    {
        $newTechStmt = "INSERT INTO tech 
        (nom, image_id, tech_cat_id) VALUES (:nom, :image_id, :tech_cat_id)";
        $newTechStmt = $this->bdd->prepare($newTechStmt);
        $newTechStmt->execute([
            ':nom' => $techName,
            ':image_id' => $techImageId,
            ':tech_cat_id' => $techCatId
        ]);
    }

    // méthode pour récuperer toutes les categories tech (modifier tech)
    public function getTechCat(): array
    {
        $listCatStmt = "SELECT * 
        FROM tech_cat";
        $listCatStmt = $this->bdd->prepare(($listCatStmt));
        $listCatStmt->execute();
        $listCat = $listCatStmt->fetchAll(PDO::FETCH_ASSOC);

        $categorie = [];
        foreach ($listCat as $value) {
            $catId = $value['id'];
            $catName = $value['nom'];
            $categorie[$catId] = $catName;
        }

        return $categorie;
    }
}

// $tech = new Tech();
// var_dump($tech->getTechCat());
