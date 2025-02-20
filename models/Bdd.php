<?php

class Bdd
{
    protected PDO $bdd;

    private const BDD_HOST = "localhost";
    private const BDD_USER = "root";
    private const BDD_PASS = "";
    private const BDD_NAME = "portfolio";

    public function __construct()
    {
        try {
            $this->bdd = new PDO("mysql:host=" . self::BDD_HOST . ";dbname=" . self::BDD_NAME . "; charset=utf8", self::BDD_USER, self::BDD_PASS);
            $this->bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur : " . $e->getMessage());
        }
    }
}
