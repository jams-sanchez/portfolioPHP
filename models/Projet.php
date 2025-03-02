<?php
// a supprimer apres test
require('../config.php');
// 


require_once('../models/Bdd.php');

class Projet extends Bdd
{

    private string $projectName;
    private string $projectDescription;
    private string $projectLink;

    public function __construct(string $projectName = "", string $projectDescription = "", string $projectLink = "")
    {
        parent::__construct();
        $this->projectName = $projectName;
        $this->projectDescription = $projectDescription;
        $this->projectLink = $projectLink;
    }

    // methode pour recuperer un projet par id
    public function getProjetById($idProjet)
    {
        $stmt = "SELECT projet.id, projet.nom, projet.description, 
        projet.lien, projet.image_id,
        image.bin AS image,
        tech.nom AS tech
        FROM projet
        JOIN projet_tech ON projet_tech.projet_id = projet.id
        JOIN tech ON projet_tech.tech_id = tech.id
        JOIN image ON projet.image_id = image.id
        WHERE projet.id = :projetId";
        $stmt = $this->bdd->prepare($stmt);
        $stmt->execute([
            ':projetId' => $idProjet
        ]);
        $stmt = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $projet = [];
        foreach ($stmt as $key => $value) {

            if (empty($projet)) {
                $projet = [
                    'id' => $value['id'],
                    'nom' => $value['nom'],
                    'desc' => $value['description'],
                    'lien' => $value['lien'],
                    'imageId' => $value['image_id'],
                    'image' => $value['image'],
                    'techs' => []
                ];
            }

            $projet['techs'][] = $value['tech'];
        }
        return $projet;
    }

    // méthode pour afficher tous les projets 
    public function getAllProjets(): array
    {
        $stmt = "SELECT projet.id, projet.nom, projet.description, 
        projet.lien, projet.image_id,
        image.bin AS image,
        tech.nom AS tech
        FROM projet
        JOIN projet_tech ON projet_tech.projet_id = projet.id
        JOIN tech ON projet_tech.tech_id = tech.id
        JOIN image ON projet.image_id = image.id
        ";
        $stmt = $this->bdd->prepare($stmt);
        $stmt->execute();
        $stmt = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $projet = [];
        foreach ($stmt as $key => $value) {
            $id = $value['id'];

            if (!isset($projet[$id])) {
                $projet[$id] = [
                    'nom' => $value['nom'],
                    'desc' => $value['description'],
                    'lien' => $value['lien'],
                    'imageId' => $value['image_id'],
                    'image' => $value['image'],
                    'techs' => []
                ];
            }

            $projet[$id]['techs'][] = $value['tech'];
        }

        return $projet;
    }

    // méthode pour ajouter un projet
    public function insertProject($nom, $description, $lien, $techs, $image): void
    {
        $addProjectStmt = "INSERT INTO projet(nom, description, lien, image_id)
        VALUES (:nom, :description, :lien, :image_id)";
        $addProjectStmt = $this->bdd->prepare($addProjectStmt);
        $addProjectStmt->execute([
            ':nom' => $nom,
            ':description' => $description,
            ':lien' => $lien,
            ':image_id' => $image
        ]);

        $newProjectId = $this->bdd->lastInsertId();

        $projectTechStmt = "INSERT INTO projet_tech (projet_id, tech_id)
        VALUES (:projet_id, :tech_id)";
        $projectTechStmt = $this->bdd->prepare($projectTechStmt);

        foreach ($techs as $tech) {
            $projectTechStmt->execute([
                ':projet_id' => $newProjectId,
                ':tech_id' => $tech
            ]);
        }
    }

    // méthode pour modifier un projet 
    public function updateProjet($id, $nom, $desc, $lien): void
    {
        $updateStmt = "UPDATE projet 
        SET nom = :nom, description = :desc, lien = :lien
        WHERE projet.id = :id";
        $updateStmt = $this->bdd->prepare($updateStmt);
        $updateStmt->execute([
            ':id' => $id,
            ':nom' => $nom,
            ':desc' => $desc,
            ':lien' => $lien
        ]);
    }

    // ---------------------------------------------------------- //
    // méthode pour supprimer l'association de tech à un projet
    public function deleteProjetTech($projetId): void
    {
        $projectTechStmt = "DELETE FROM projet_tech
        WHERE projet_id = :projet_id";
        $projectTechStmt = $this->bdd->prepare($projectTechStmt);
        $projectTechStmt->execute([
            ':projet_id' => $projetId
        ]);
    }

    public function insertProjetTech($projetId, $techs)
    {
        $projectTechStmt = "INSERT INTO projet_tech (projet_id, tech_id)
        VALUES (:projet_id, :tech_id)";
        $projectTechStmt = $this->bdd->prepare($projectTechStmt);

        foreach ($techs as $tech) {
            $projectTechStmt->execute([
                ':projet_id' => $projetId,
                ':tech_id' => $tech
            ]);
        }
    }
}
