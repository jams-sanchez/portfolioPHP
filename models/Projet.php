<?php

require_once('../models/Bdd.php');

class Project extends Bdd
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

    // méthode pour ajouter un projet

}
