<?php 
    // Securite
    if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
        header('Location: 23login.php');
        exit();
    }

    // Determiner la page active pour la style
    $page_active = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="./../css/style.css">
        <link rel="stylesheet" href="./../css/23admin.css">
    </head>
    <body>
        <nav class="admin-nav">
            <span class="brand">HEC VanLife</span>

            <a href="23list_client.php"
            class="<?= $page_active === '23list_client.php' ? 'active' : '' ?>">
                Clients
            </a>

            <a href="23list_modele.php"
            class="<?= $page_active === '23list_modele.php' ? 'active' : '' ?>">
                Modèles
            </a>

            <a href="23list_van.php"
            class="<?= $page_active === '23list_van.php' ? 'active' : '' ?>">
                Vans
            </a>

            <a href="23list_location.php"
            class="<?= $page_active === '23list_location.php' ? 'active' : '' ?>">
                Locations
            </a>

            <div class="dropdown">
                <a href="#" class="<?= in_array($page_active, ['23rapport1_modele.php','23rapport2_client.php']) ? 'active' : '' ?>">
                    Rapports
                </a>
                <div class="dropdown-menu">
                    <a href="23rapport1_modele.php">Revenus par modèle</a>
                    <a href="23rapport2_client.php">Locations par client</a>
                </div>
            </div>

            <a href="23logout.php" class="logout">Se déconnecter</a>
        </nav>
    </body>
</html>