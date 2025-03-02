<?php

if (isset($_POST['logout'])) {
    unset($_SESSION);
    session_destroy();
    header('location: ../pages/admin.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style_admin.css">
    <title>Admin</title>
</head>

<body>

    <header class="header">



        <!-- si page de connexion -->

        <?php if (!isset($_SESSION['userPseudo'])): ?>
            <div class="return-portfolio">
                <a href="#">Retour Portfolio</a>
            </div>
        <?php else: ?>


            <?php if (isset($_GET['page'])): ?>
                <?php if ($_GET['page'] != 'home'): ?>

                    <!-- si page != admin home -->
                    <div class="cercle">
                        <a href="admin.php?page=home"><img src="../assets/img/home.png" class="logo-nav" /></a>
                    </div>

                    <nav class="nav-header">

                        <?php if ($_GET['page'] != 'projets'): ?>
                            <a href="admin-projets.php?page=projets">Projets</a>
                        <?php else: ?>
                            <p class="yellow-text">Projets</p>
                        <?php endif; ?>

                        <?php if ($_GET['page'] != 'tech'): ?>
                            <a href="admin-tech.php?page=tech">Techs</a>
                        <?php else: ?>
                            <p class="yellow-text">Techs</p>
                        <?php endif; ?>


                    </nav>
                    <form action="" method="post">
                        <button type="submit" name="logout" class="cercle"><img src="../assets/img/logout.png" class="logo-nav" /></button>
                    </form>


                <?php else: ?>
                    <form action="" method="post">
                        <button type="submit" name="logout" class="cercle"><img src="../assets/img/logout.png" class="logo-nav" /></button>
                    </form>
                <?php endif; ?>
            <?php endif; ?>


        <?php endif; ?>


    </header>