<?php
    session_start();
    require_once './../23_config.php';
    require_once("./23menu.php"); // vérifie session + affiche menu

    $db = new DB();
    $erreur = '';
    $id = (int)($_GET['id'] ?? 0);

    // Récupérer le modèle à modifier
    $modele = $db->query( "SELECT * FROM modele WHERE id_modele = ?", [$id])->fetch();


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom = trim($_POST['nom_modele'] ?? '');
        $route = (int)($_POST['nombre_places_route'] ??0);
        $couchage = (int)($_POST['nombre_places_couchage'] ??0);
        $dims = trim($_POST['dimensions'] ??'');
        $prix = (float)($_POST['prix_jour'] ?? 0);

        if ($nom === '' || $dims === '' || $prix <= 0) {
            $erreur = "Veuillez remplir tous les champs correctement.";
        }else {
            $db->execute(
                "UPDATE modele
                 SET nom_modele=?, nombre_places_route=?, nombre_places_couchage=?, dimensions=?, prix_jour=?
                 WHERE id_modele=?
                ",
                [$nom, $route, $couchage, $dims, $prix, $id]
            );
            header('Location: 23list_modele.php?success=1');
            exit();
        }
    }
?>


<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Modifier un modèle</title>
        <link rel="stylesheet" href="./../../css/style.css">
        <link rel="stylesheet" href="./../../css/admin.css">
    </head>

    <body>
        <div class="admin-content">
            <div class="page-header">
                <h1 class="page-title">Modifier un modèle</h1>
                <a href="23list_modele.php" class="btn">Retour</a>
            </div>

            <?php if ($erreur){ ?>
                <div class="alert-error"><?= htmlspecialchars($erreur) ?></div>
            <?php } ?>

            <div class="form-card">
                <form method="POST" action="">
                    <!-- ID affiché mais non modifiable -->
                     <div class="form-group">
                        <label>ID modèle</label>
                        <input type="text" value="<?= $modele['id_modele'] ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="nom_modele">Nom du modèle</label>
                        <input type="text" name="nom_modele" value="<?= htmlspecialchars($_POST['nom_modele'] ?? $modele['nom_modele']) ?>" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="nombre_places_route">Places en route</label>
                            <input type="number" name="nombre_places_route" value="<?= $_POST['nombre_places_route'] ?? $modele['nombre_places_route'] ?>" min="1" required>
                        </div>
                        <div class="form-group">
                            <label for="nombre_places_couchage">Places couchage</label>
                            <input type="number" name="nombre_places_couchage" value="<?= $_POST['nombre_places_couchage'] ?? $modele['nombre_places_couchage'] ?>" min="1" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="dimensions">Dimensions</label>
                        <input type="text" name="dimensions" placeholder="ex: 4.90m x 1.90m" value="<?= htmlspecialchars($_POST['dimensions'] ?? $modele['dimensions']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="prix_jour">Prix par jour (€)</label>
                        <input type="number" name="prix_jour" step="0.01" value="<?= $_POST['prix_jour'] ?? $modele['prix_jour'] ?>" min="0" required>
                    </div>

                    <div class="form-actions">
                        <a href="23list_modele.php" class="btn">Annuler</a>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>

