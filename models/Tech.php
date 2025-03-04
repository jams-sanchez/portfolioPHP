<?php

require_once($_SERVER["DOCUMENT_ROOT"] . '/portfolioPHP/models/Bdd.php');

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
        $getStmt = "SELECT tech.id, tech.nom, 
        tech_cat.nom AS categorie,
        image.bin AS image
        FROM tech
        JOIN tech_cat ON tech_cat.id = tech.tech_cat_id
        JOIN image ON image.id = tech.image_id";
        $getStmt = $this->bdd->prepare($getStmt);
        $getStmt->execute();
        return $getStmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // méthode pour récupèrer une tech via son id
    public function getTechById($techId): array
    {
        $getByIdStmt = "SELECT tech.id, tech.nom, 
        tech_cat_id AS catId,
        tech_cat.nom AS categorie,
        image.bin AS image 
        FROM tech
        JOIN tech_cat ON tech_cat.id = tech.tech_cat_id
        JOIN image ON image.id = tech.image_id
        WHERE tech.id = :id";
        $getByIdStmt = $this->bdd->prepare($getByIdStmt);
        $getByIdStmt->execute([
            ':id' => $techId
        ]);
        return $getByIdStmt->fetchAll(PDO::FETCH_ASSOC);
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

    // méthode pour modifier une tech
    public function updateTech($techId, $techName, $techCatId): void
    {
        $updateStmt = "UPDATE tech SET nom = :nom, tech_cat_id = :tech_cat_id
        WHERE tech.id = :id";
        $updateStmt = $this->bdd->prepare($updateStmt);
        $updateStmt->execute([
            ':id' => $techId,
            ':nom' => $techName,
            ':tech_cat_id' => $techCatId
        ]);
    }

    // méthode pour récupèrer l'id et le nom d'une tech front et back
    public function getTechNameId()
    {
        $techStmt = "SELECT tech.id, tech.nom
        FROM tech
        JOIN tech_cat ON tech.tech_cat_id = tech_cat.id
        WHERE tech_cat.nom = 'front' OR tech_cat.nom = 'back'";
        $techStmt = $this->bdd->prepare($techStmt);
        $techStmt->execute();
        $techStmt = $techStmt->fetchAll(PDO::FETCH_ASSOC);

        $techNameId = [];
        foreach ($techStmt as $key => $value) {
            $techNameId[$value['id']] = $value['nom'];
        }

        return $techNameId;
    }

    // méthode pour récuperer les techs par categorie
    public function getTechByCat(): array
    {
        $stmt = "SELECT tech.nom AS tech, 
        tech_cat.nom AS categorie, 
        image.nom AS image
        FROM tech
        JOIN tech_cat ON tech.tech_cat_id = tech_cat.id
        JOIN image ON tech.image_id = image.id
        ORDER BY tech_cat.id ASC";
        $stmt = $this->bdd->prepare($stmt);
        $stmt->execute();
        $stmt = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $categorie = [];
        foreach ($stmt as $key => $value) {
            $categorie[$value['categorie']][$value['tech']] = $value['image'];
        }

        return $categorie;
    }
}
