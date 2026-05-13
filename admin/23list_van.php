<?php 
    session_start();
    require_once("./../23_config.php");
    require_once("./23menu.php");

    $db = new DB();

    // Récupérer tous les vans + toutes les infos du modèle associé
    $vans = $db->query(
        "SELECT v.plaque, v.id_modele, m.nom_modele, m.nombre_places_route, m.nombre_places_couchage, m.dimensions, m.prix_jour
         FROM van v
         Inner JOIN modele m ON v.id_modele = m.id_modele
         ORDER BY v.plaque
        "
    )->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>Vans</title>
    </head>
    <body>
        <div class="admin-content">
            <div class="page-header">
                <h1 class="page-title">Vans</h1>
                <a href="23add_van.php" class="btn btn-primary">Ajouter un van</a>
            </div>

            <?php if (isset($_GET['success'])) {?>
                <div class="alert-success">Opération effectuée avec succès.</div>
            <?php }?>

             <?php if (empty($vans)) {?>
                <p class="aucun-resultat">Aucun modèle enregistré.</p>
            <?php }else{?>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Plaque</th>
                                <th>Modèle (ID)</th>
                                <th>Places route</th>
                                <th>Places couchage</th>
                                <th>Dimensions</th>
                                <th>Prix/jour</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($vans as $v): ?>
                                <tr>
                                    <td>
                                        <strong>
                                            <?= htmlspecialchars($v['plaque']) ?>
                                        </strong>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($v['nom_modele']) ?>
                                        <small style="color:#aaa; display:block;">ID: <?= $v['id_modele'] ?></small>
                                    </td>
                                    <td>
                                        <?= $v['nombre_places_route'] ?>
                                    </td>
                                    <td>
                                        <?= $v['nombre_places_couchage'] ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($v['dimensions']) ?>
                                    </td>
                                    <td>
                                        <?= number_format($v['prix_jour'], 2) ?> €
                                    </td>
                                    <td class="actions">
                                        <a href="23edit_van.php?plaque=<?= urlencode($v['plaque']) ?>" class="btn-action edit"> Modifier</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <p class="count-info"><?= count($vans) ?> van(s) affiché(s)</p>
            <?php }?>
        </div>
    </body>
</html>