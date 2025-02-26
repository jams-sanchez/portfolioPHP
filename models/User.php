<?php

require_once('../models/Bdd.php');

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
        unset($_SESSION['erreur']);
        unset($_SESSION['succes']);

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
            $_SESSION['userPseudo'] = $this->userPseudo;
            header('location: ../pages/admin.php?page=home');
            exit();
        } else {
            $_SESSION['erreur'] = 'Pseudo ou mot de passe incorrect';
        }
    }

    // méthode vérification et modification mot de passe
    public function updatePass($userPseudo, $currentPass, $newPass): void
    {
        // récupère mot de passe actuel
        $passStmt = 'SELECT user.password
        FROM user
        WHERE pseudo = :pseudo';
        $passStmt = $this->bdd->prepare($passStmt);
        $passStmt->execute([
            ':pseudo' => $userPseudo
        ]);
        $pass = $passStmt->fetch(PDO::FETCH_ASSOC);
        $this->userPass = $pass['password'];

        // vérifie le pass actuel
        if (password_verify($currentPass, $this->userPass) || $currentPass == $this->userPass) {
            if (password_verify($newPass, $this->userPass) || $newPass == $pass['password']) {
                $_SESSION['erreur'] = "Les mots de passe sont identiques...";
                header('refresh:1;url=../pages/admin-compte.php?page=compte');
            } else {
                $newHashPass = password_hash($newPass, PASSWORD_BCRYPT);

                // met a jour le mot de passe
                $updatePassStmt = "UPDATE user 
                SET password = :newPass
                WHERE pseudo = :pseudo";
                $updatePassStmt = $this->bdd->prepare($updatePassStmt);
                $updatePassStmt->execute([
                    ':newPass' => $newHashPass,
                    ':pseudo' => $userPseudo
                ]);

                $_SESSION['succes'] = "Mot de passe changé";
                header('refresh:1;url=../pages/admin-compte.php?page=compte');
            }
        } else {
            $_SESSION['erreur'] = "Erreur - Mot de passe incorrect";
            header('refresh:1;url=../pages/admin-compte.php?page=compte');
        }
    }

    // méthode pour changer le pseudo
    public function updatePseudo($userPseudo, $newPseudo)
    {

        if (strtolower($userPseudo) == strtolower($newPseudo)) {
            $_SESSION['erreur'] = "Le pseudo est identique";
            header('refresh:1;url=../pages/admin-compte.php?page=compte');
        } else {
            $regexCheck = "/^[a-zA-Z]{3,20}$/";

            if (preg_match($regexCheck, $newPseudo)) {
                $pseudoStmt = "UPDATE user SET pseudo = :newPseudo
                WHERE pseudo = :pseudo";
                $pseudoStmt = $this->bdd->prepare($pseudoStmt);
                $pseudoStmt->execute([
                    ':newPseudo' => $newPseudo,
                    ':pseudo' => $userPseudo
                ]);

                $_SESSION['succes'] = "Pseudo modifié";
                $_SESSION['userPseudo'] = $newPseudo;
                header('refresh:1;url=../pages/admin-compte.php?page=compte');
            } else {
                $_SESSION['erreur'] = "Le pseudo ne remplit pas les conditions";
                header('refresh:1;url=../pages/admin-compte.php?page=compte');
            }
        }
    }
}
