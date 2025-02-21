<?php

class Bdd
{
    protected PDO $bdd;

    public function __construct()
    {
        $host = $_ENV["BDD_HOST"];
        $user = $_ENV["BDD_USER"];
        $pass = $_ENV["BDD_PASS"];
        $name = $_ENV["BDD_NAME"];

        try {
            $this->bdd = new PDO("mysql:host=$host;dbname=$name; charset=utf8", $user, $pass);
            $this->bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur : " . $e->getMessage());
        }
    }
}
