<?php 
    session_start();
    require_once("./../23_config.php");
    require_once("23menu.php");

    $db = new DB();

    // Récupérer filtres sélectionnés
    $filtre_client = $_GET['id_client'] ?? '';
    $filtre_modele = $_GET['id_modele'] ?? '';

    // liste deroulantes - Client
    $clients = $db->query(
        "SELECT id_client, nom_client, prenom
         FROM client
         ORDER BY nom_client
        "
    )->fetchAll();

    // liste deroulantes - Client
    $modeles = $db->query(
        "SELECT id_modele, nom_modele
         FROM modele
         ORDER BY nom_modele
        "
    )->fetchAll();

    // Construction de la requête avec filtres optionnels
    $sql = "SELECT l.id_location, l.date_debut, l.date_fin, c.nom_client, c.prenom, c.id_client, l.plaque,
            DATEDIFF(l.date_fin, l.date_debut) + 1 AS duree, m.prix_jour,
            (DATEDIFF(l.date_fin, l.date_debut) + 1) * m.prix_jour AS total
            FROM location l
            INNER JOIN client c ON l.id_client = c.id_client
            INNER JOIN van v    ON l.plaque    = v.plaque
            INNER JOIN modele m ON v.id_modele = m.id_modele
            WHERE 1=1
           ";

    $params = [] ;
    
    if ($filtre_client !== '') {
        $sql .= " AND c.id_client = ?";
        $params[] = $filtre_client;
    }

    if ($filtre_modele !== '') {
        $sql .= " AND m.id_modele = ?";
        $params[] = $filtre_modele;
    }

    $sql .= " ORDER BY l.date_debut DESC";

    $locations = $db->query($sql, $params)->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>Locations — HEC VanLife</title>
    </head>

    <body>
    <div class="admin-content">
        <div class="page-header">
            <h1 class="page-title">Locations</h1>
        </div>

        <!-- Filtres -->
        <form method="GET" action="" class="filtre-form">
            <div class="filtre-row">
                <div class="form-group">
                    <label for="id_client">Client</label>
                    <select name="id_client" id="id_client">
                        <option value="">Tous les clients</option>
                        <?php foreach ($clients as $c): ?>
                            <option value="<?= $c['id_client'] ?>"
                                <?= $filtre_client == $c['id_client'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($c['nom_client']) ?>
                                <?= htmlspecialchars($c['prenom']) ?>
                                (<?= $c['id_client'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="id_modele">Modèle de van</label>
                    <select name="id_modele" id="id_modele">
                        <option value="">Tous les modèles</option>
                        <?php foreach ($modeles as $m): ?>
                            <option value="<?= $m['id_modele'] ?>"
                                <?= $filtre_modele == $m['id_modele'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($m['nom_modele']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filtre-actions">
                    <button type="submit" class="btn btn-primary">Filtrer</button>
                    <a href="23list_location.php" class="btn">Réinitialiser</a>
                </div>

            </div>
        </form>

        <!-- Tableau -->
        <?php if (empty($locations)): ?>
            <p class="aucun-resultat">Aucune location trouvée.</p>
        <?php else: ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Dates</th>
                            <th>Client</th>
                            <th>Plaque</th>
                            <th>Durée</th>
                            <th>Prix/jour</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($locations as $l): ?>
                            <tr>
                                <td><?= $l['id_location'] ?></td>
                                <td>
                                    <?= date('d/m/Y', strtotime($l['date_debut'])) ?>
                                    <small style="display:block; color:#aaa;">
                                        -> <?= date('d/m/Y', strtotime($l['date_fin'])) ?>
                                    </small>
                                </td>
                                <td>
                                    <?= htmlspecialchars($l['nom_client']) ?>
                                    <?= htmlspecialchars($l['prenom']) ?>
                                    <small style="color:#aaa;">(<?= $l['id_client'] ?>)</small>
                                </td>
                                <td><strong><?= htmlspecialchars($l['plaque']) ?></strong></td>
                                <td><?= $l['duree'] ?> jour(s)</td>
                                <td><?= number_format($l['prix_jour'], 2) ?> €</td>
                                <td><strong><?= number_format($l['total'], 2) ?> €</strong></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <p class="count-info"><?= count($locations) ?> location(s) affichée(s)</p>
        <?php endif; ?>

    </div>
    </body>
</html>