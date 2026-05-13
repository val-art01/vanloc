<?php
    session_start();
    require_once './../23_config.php';
    require_once("./23menu.php"); // vérifie session + affiche menu

    $db = new DB();
    $erreur = '';

    // On récupère la plaque en priorité depuis le POST (formulaire) sinon depuis le GET (URL)
    $plaque = $_GET['plaque'] ?? '';

    if (empty($plaque)) {
        header('Location: 23list_van.php');
        exit();
    }

    // Récupérer le van à modifier
    $van = $db->query( 
        "SELECT v.*, m.nom_modele, m.nombre_places_route, m.nombre_places_couchage, m.dimensions, m.prix_jour 
            FROM van v
            INNER JOIN modele m ON v.id_modele = m.id_modele 
            WHERE v.plaque = ?
        ", [$plaque]
    )->fetch();

    if (!$van) {
        header('Location: 23list_van.php');
        exit();
    }

    // Recuperer les modele pour la liste deroulante
    $modeles = $db->query(
        "SELECT id_modele, nom_modele, nombre_places_route, prix_jour 
         FROM modele 
         ORDER BY nom_modele
        "
    )->fetchAll();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id_modele = (int)($_POST['id_modele'] ?? 0);

        if ($id_modele === 0) {
            $erreur = "Veuillez selectionner un modele";
        }else {
            // Exécution de la modification
            $db->execute( 
                "UPDATE van SET id_modele = ? WHERE plaque = ?",
                [$id_modele, $plaque]
            );
            header('Location: 23list_van.php?success=1');
            exit();
        }
    }
?>


<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>Modifier un van</title>
    </head>

    <body>
        <div class="admin-content">
            <div class="page-header">
                <h1 class="page-title">Modifier un van</h1>
                <a href="23list_van.php" class="btn">Retour</a>
            </div>

            <?php if ($erreur): ?>
                <div class="alert-error"><?= htmlspecialchars($erreur) ?></div>
            <?php endif ?>

            <div class="form-card">
                <form method="POST" action="">
                    <!-- ID affiché mais non modifiable -->
                     <div class="form-group">
                        <label>Plaque</label>
                        <input type="text" value="<?= $van['plaque'] ?>" disabled>
                    </div>

                    <div class="form-group">
                        <label for="id_modele">Modèle</label>
                        <select id="id_modele" name="id_modele" required>
                            <option value="">-- Choisir un modèle --</option>
                            <?php foreach ($modeles as $m): ?>
                                <?php
                                $selected_id = $_POST['id_modele'] ?? $van['id_modele'];
                                ?>
                                <option value="<?= $m['id_modele'] ?>"
                                    <?= $selected_id == $m['id_modele'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($m['nom_modele']) ?>
                                    — <?= $m['nombre_places_route'] ?> places route
                                    — <?= number_format($m['prix_jour'], 2) ?> €/jour
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-actions">
                        <a href="23list_van.php" class="btn">Annuler</a>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>

