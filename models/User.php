<?php

require_once('./Bdd.php');

class User extends Bdd
{

    private string $userPseudo;
    private string $userPass;

    public function __construct($userPseudo = "", $userPass = "")
    {
        parent::__construct();
        $this->userPseudo = $userPseudo;
        $this->userPass = $userPass;
    }

    // méthode connexion 
    public function userConnexion(): void
    {
        // recupère les infos user
        $loginStmt = "SELECT user.pseudo, user.password
        FROM user
        WHERE pseudo = :pseudo";
        $loginStmt = $this->bdd->prepare($loginStmt);
        $loginStmt->execute([
            ':pseudo' => $this->userPseudo
        ]);
        $userInfos = $loginStmt->fetch(PDO::FETCH_ASSOC);

        // vérifie le mot de passe
        if ($userInfos && (password_verify($this->userPass, $userInfos['password'])) || ($userInfos && $this->userPass == $userInfos['password'])) {
            session_start();
            $_SESSION['pseudo'] = $this->userPseudo;
            header('location: ./admin.php');
            exit();
        } else {
            $_SESSION['message'] = 'Pseudo ou mot de passe incorrect';
        }
    }

    // méthode vérification et modification mot de passe
    public function updatePass($currentPass, $newPass): void
    {
        // vérifie le pass actuel et hash le nouveau
        if (password_verify($currentPass, $this->userPass) || $currentPass == $this->userPass) {
            $newHashPass = password_hash($newPass, PASSWORD_BCRYPT);

            // met a jour le mot de passe
            $updatePassStmt = "UPDATE user 
            SET password = :newPass
            WHERE pseudo = :pseudo";
            $updatePassStmt = $this->bdd->prepare($updatePassStmt);
            $updatePassStmt->execute([
                ':newPass' => $this->userPass,
                ':pseudo' => $this->userPseudo
            ]);

            $_SESSION['message'] = "Mot de passe changé";
        } else {
            $_SESSION['message'] = "Erreur - Mot de passe incorrect";
        }
    }
}
