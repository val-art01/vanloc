<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>HEC VanLife</title>
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/23index_racine.css">
    </head>
    <body>
        <div class="accueil">
            <h1>HEC VanLife</h1>
            <p>Plateforme de gestion de location de vans — Belgique</p>

            <div class="choix">
                <div class="card">
                    <strong>Espace Admin</strong>
                    <p>Gestion des vans, clients et locations</p>
                    <a href="admin/index.php" class="btn btn-primary">Accéder</a>
                </div>
                <div class="card">
                    <strong>Espace Client</strong>
                    <p>Réservez et gérez vos locations</p>
                    <a href="client/index.php" class="btn">Accéder</a>
                </div>
            </div>

            <p class="footer">HEC Liège — Groupe 23</p>
        </div>
    </body>
</html>