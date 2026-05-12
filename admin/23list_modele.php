<?php 
    session_start();
    require_once("./../23_config.php");
    require_once("./23menu.php");

    $db = new DB();

    // Recuperer tous les modelles et compter cle nombre de van que utilise chaque modele
    $modeles = $db->query(
        "SELECT m.*, COUNT(v.plaque) AS nb_vans
         FROM modele m
         JOIN van v ON m.id_modele = v.id_modele
         GROUP BY m.id_modele
         ORDER BY m.nom_modele
        "
    )->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>Modèles</title>
        <link rel="stylesheet" href="./../css/style.css"/>
        <link rel="stylesheet" href="./../css/admin.css"/>
    </head>
    <body>
        <div class="admin-content">
            <div class="page-header">
                <h1 class="page-title">Modèles</h1>
                <a href="23add_modele.php" class="btn btn-primary">Ajouter un modèle</a>
            </div>

            <?php if (isset($_GET['success'])) {?>
                <div class="alert-success">Opération effectuée avec succès.</div>
            <?php }?>

             <?php if (empty($modeles)) {?>
                <p class="aucun-resultat">Aucun modèle enregistré.</p>
            <?php }else{?>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Places route</th>
                                <th>Places couchage</th>
                                <th>Dimensions</th>
                                <th>Prix/jour</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($modeles as $model): ?>
                                <tr>
                                    <td><?= $model['id_modele'] ?></td>
                                    <td><?= htmlspecialchars($model['nom_modele']) ?></td>
                                    <td><?= $model['nombre_place_route'] ?></td>
                                    <td><?= $model['nombre_place_couchage'] ?></td>
                                    <td><?= htmlspecialchars($model['dimensions']) ?></td>
                                    <td><?= number_format($model['prix_jour'], 2) ?> €</td>
                                    <td class="actions">
                                        <a href="23edit_modele.php" class="btn-action edit"> Modifier</a>
                                        <?php if ($model['nb_vans'] == 0): ?>
                                            <a href="23delete_mode23edit_modele.php" class="btn-action delete" onclick="return confirm('Supprimer ce modèle ?')">
                                                Supprimer
                                            </a>
                                        <?php else: ?>
                                            <span class="btn-action delete disabled" title="Affecté à <?= $model['nb_vans'] ?> van(s)">
                                                Corbeille
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <p class="count-info"><?= count($modeles) ?> modèle(s) — grisé = affecté à un van</p>
            <?php }?>
        </div>
    </body>
</html>