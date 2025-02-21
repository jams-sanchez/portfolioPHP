<?php

session_start();
require('../config.php');


if (!isset($_SESSION['userPseudo'])) {
    header("../pages/admin.php?page=home");
}

?>


<?php include_once('../include/header-admin.php'); ?>

<?php if (isset($_SESSION['userPseudo'])): ?>
    <main>

    </main>

    <?php include_once('../include/footer-admin.php'); ?>
<?php endif; ?>