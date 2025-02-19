<?php

class Bdd
{
    protected PDO $bdd;

    private const bdd_host = "localhost";
    private const bdd_user = "root";
    private const bdd_pass = "";
    private const bdd_name = "portfolio";

    public function __construct()
    {
        try {
            $this->bdd = new PDO("mysql:host=" . self::bdd_host . ";dbname=" . self::bdd_name . "; charset=utf-8", self::bdd_user, self::bdd_pass);
            $this->bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur : " . $e->getMessage());
        }
    }

    public function getBdd(): PDO
    {
        return $this->bdd;
    }
}
