<?php

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

        <nav class="nav-barre">

            <!-- si page de connexion -->

            <?php if (!isset($_SESSION)): ?>

                <a href="#">Retour Portfolio</a>

            <?php else: ?>

                <?php if ($_GET['page'] != 'home'): ?>

                    <!-- si page != admin home -->
                    <a href="admin.php?page=home">Admin Home</a>

                    <?php if ($_GET['page'] != 'projets'): ?>
                        <a href="admin-projets.php?page=projets">Projets</a>
                    <?php endif; ?>

                    <?php if ($_GET['page'] != 'tech'): ?>
                        <a href="pages/admin-tech.php?page=tech">Tech</a>
                    <?php endif; ?>

                    <?php if ($_GET['page'] != 'images'): ?>
                        <a href="admin-images.php?page=images">Images</a>
                    <?php endif; ?>

                    <a href="admin-deco.php">Deconnexion</a>

                <?php else: ?>
                    <a href="#">Deconnexion</a>
                <?php endif; ?>


            <?php endif; ?>
        </nav>

    </header>