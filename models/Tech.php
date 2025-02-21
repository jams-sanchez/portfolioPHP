<?php

require_once('../models/Bdd.php');

class Tech extends Bdd
{

    private string $techName;
    private string $techCat;
    private string $techImage;

    public function __construct(string $techName = "")
    {
        parent::__construct();
        $this->techName = $techName;
        $this->techCat = $techCat = "";
        $this->techImage = $techImage = "";
    }

    // méthode pour récuperer toutes les techs et leurs infos
    public function getTech(): array
    {
        $getStmt = "SELECT tech.nom AS tech, 
        tech_cat.nom AS categorie
        FROM tech
        JOIN tech_cat ON tech_cat.id = tech.tech_cat_id";
        $getStmt = $this->bdd->prepare($getStmt);
        $getStmt->execute();
        $listTech = $getStmt->fetchAll(PDO::FETCH_ASSOC);

        $tech = [];
        foreach ($listTech as $value) {
            $this->techName = $value['tech'];
            $this->techCat = $value['categorie'];

            $tech[$this->techName] = [
                'categorie' => $this->techCat
            ];
        }
        return $tech;
    }
}

// $tech = new Tech();
// var_dump($tech->getTech());
