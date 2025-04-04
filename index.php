<?php

require('./config.php');
require_once('./models/Tech.php');
require_once('./models/Projet.php');

$tech = new Tech();
$techCategorie = $tech->getTechByCat();

$projet = new Projet();
$allProjet = $projet->getAllProjets();

?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta
    name="Vous trouverez dans ce site Portfolio, mon profils et mes compétences ainsi que divers projets effectués." />
  <link
    href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap"
    rel="stylesheet" />
  <title>Portfolio - SANCHEZ James</title>
  <link rel="stylesheet" href="assets/css/style.css" />
  <link rel="icon" type="image/x-icon" href="./assets/img/favicon.png">
</head>

<body>
  <!-- Bannière haut de page -->
  <header>
    <hgroup class="header-title">
      <h1>James Sanchez</h1>
      <h2>Développeur Web Junior</h2>
    </hgroup>
  </header>
  <!-- Contenu Principal -->
  <main>
    <!-- Boite description -->
    <section class="profil-box">
      <article class="profil-text">
        <h3 class="bold-upp orange">Salutation,</h3>
        <p>
          Moi, c'est James. Je suis un développeur web étudiant à l'école La
          Plateforme. Je suis une personne très curieuse, avec une faim
          d'apprendre immense en recherche de partages
          d'expériences et de connaissances.
        </p>
        <hr class="profil-bar" />
        <p>
          Je suis à la recherche d'un endroit pour découvrir, apprendre et
          mettre mes connaissances en oeuvre !
        </p>
      </article>
      <!-- Photo de profil -->
      <figure class="profil-picture">
        <img src="assets/img/profile.webp" alt="james" />
      </figure>
    </section>
    <!-- Titre catégorie -->
    <section class="cat-section">
      <div class="section-title">
        <h2>Compétences</h2>
        <hr />
      </div>
      <!-- Section compétences -->
      <div class="comp-group">
        <!-- Boite Compétences -->
        <?php foreach ($techCategorie as $cat => $image): ?>
          <article class="comp-box">
            <h3><?= $cat ?></h3>
            <figure class="logo">
              <?php foreach ($image as $nom => $value): ?>
                <img src="<?= "assets/img/techs/" . $value ?>" alt="<?= $nom ?>" />
              <?php endforeach; ?>
            </figure>
          </article>
        <?php endforeach; ?>
      </div>
    </section>
    <!-- Titre catégorie -->
    <section class="cat-section">
      <div class="section-title">
        <h2>Projets</h2>
        <hr />
      </div>
      <!-- Section emplacement des projets -->
      <div class="project-group">
        <!-- Boite projet -->
        <?php foreach ($allProjet as $id => $info): ?>
          <article class="project-box">
            <figure class="project-screenshot">
              <img
                src="<?= "assets/img/projets/" . $info['image'] ?>"
                alt="<?= "screenshot " . $info['nom'] ?>" />
            </figure>
            <div class="project-description">
              <hgroup class="project-title">
                <h3><?= $info['nom'] ?></h3>
                <div class="project-tech">
                  <p class="bold-upp">Tech:</p>
                  <?php foreach ($info['techs'] as $tech): ?>
                    <span class="project-tools"><?= $tech ?></span>
                  <?php endforeach; ?>
                </div>
              </hgroup>
              <div class="project-text">
                <p><?= $info['desc'] ?></p>
                <div class="project-link">
                  <?php if (!empty($info['lien'])): ?>
                    <a
                      href="<?= $info['lien'] ?>"
                      class="bold-upp"
                      target="_blank">🠒 Github
                    </a>
                  <?php endif; ?>
                  <?php if ($info['nom'] == "Jeu : Tik Tak Toe - PHP"): ?>
                    <a
                      href="/tiktaktoePHP"
                      class="bold-upp"
                      target="_blank">🠒 Jouer
                    </a>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </section>
  </main>
  <!-- Bas de page : Contact -->
  <footer>
    <h2>Contact</h2>
    <section class="contact-box">
      <div class="contact-linkGitMail">
        <a href="https://github.com/jams-sanchez" target="_blank">
          <img src="./assets/img/logo-github.webp" alt="lien profil github" />
        </a>
        <a href="https://www.linkedin.com/in/jams-sanchez/" target="_blank">
          <img src="assets/img/logo-linkedin.webp" alt="lien profil linkedin" />
        </a>
        <a href="mailto:james.sanchez@laplateforme.io" target="_blank">
          <img src="./assets/img/logo-mail.webp" alt="lien email" />
        </a>
      </div>
      <div class="contact-localisation">
        <p>Aix-En-Provence, 13100</p>
        <img src="./assets/img/logo-localisation.webp" alt="logo localisation" />
      </div>
    </section>
    <div class="copyright">
      <p>© Copyright 2025 - <a href="./pages/admin.php">SANCHEZ James</a></p>
    </div>
  </footer>
</body>

</html>