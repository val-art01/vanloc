<?php
    session_start();
    require_once './../23_config.php';
    require_once("./23menu.php"); // vérifie session + affiche menu

    $db = new DB();
    $erreur = '';

    // Récupérer les modèles pour la liste déroulante
    $modeles = $db->query(
        "SELECT id_modele, nom_modele, nombre_places_route, prix_jour
         From modele
         ORDER BY nom_modele
        "
    )->fetchAll();

    // Traitement du formulaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $plaque = trim($_POST['plaque'] ?? '');
        $id_modele = (int)($_POST['id_modele'] ?? 0);

        // Validation format plaque belge : 1-ABC-123
        if(!preg_match('/^\d-[A-Z]{3}-\d{3}$/', $plaque)) {
            $erreur = 'Format de plaque invalide. ex: 1-ABC-123';
        }elseif($id_modele === 0) {
            $erreur = 'Veuillez selectionner un modele.';
        }else{

            // Vérifier si la plaque existe déjà
            $existe = $db->query(
                "SELECT COUNT(*) AS nombre FROM van WHERE plaque = ?",
                [$plaque]
            )->fetch();

            if($existe["nombre"] > 0) {
                $erreur = "Cette plaque est déjà enregistrée."; 
            }else{
                $db->execute(
                    "INSERT INTO van (plaque, id_modele) VALUES (?, ?)",
                    [$plaque, $id_modele]
                );
                header('Location: 23list_van.php?success=1');
                exit();
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>Ajouter un Van</title>
    </head>

    <body>
        <div class="admin-content">
            <div class="page-header">
                <h1 class="page-title">Ajouter un Van</h1>
                <a href="23list_van.php" class="btn">Retour</a>
            </div>

            <?php if ($erreur){ ?>
                <div class="alert-error"><?= htmlspecialchars($erreur) ?></div>
            <?php } ?>

            <div class="form-card">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="plaque">Numéro de plaque</label>
                        <input type="text" id="plaque" name="plaque" placeholder="ex: 1-ABC-123" value="<?= htmlspecialchars($_POST['nom_modele'] ?? '') ?>" required>
                        <small style="color:#888;">Format belge : chiffre-3lettres-3chiffres</small>
                    </div>

                    <div class="form-group">
                        <label for="id_modele">Modèle</label>
                        <select id="id_modele" name="id_modele" required>
                            <option value="">--Choisir un modèle--</option>
                            <?php foreach ($modeles as $model){ ?>
                                <option value="<?= $model['id_modele']?>" <?= (($_POST['id_modele'] ?? '') == $model['id_modele']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($model['nom_modele']) ?>
                                    — <?= $model['nombre_places_route'] ?> places route
                                    — <?= number_format($model['prix_jour'], 2) ?> €/jour
                                </option>
                            <?php } ?>
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

