<?php 
    session_start();
    require_once("./../23_config.php");
    require_once("23menu.php");

    $db = new DB();

    // Statistiques par client :
    // - nombre de locations
    // - total de jours loués (SUM)
    // - dépenses totales (SUM)
    // - dépense moyenne par location (AVG)
    $stats = $db->query(
        "SELECT
            c.id_client,
            c.nom_client,
            c.prenom,
            COUNT(l.id_location) AS nb_locations,
            SUM(DATEDIFF(l.date_fin, l.date_debut) + 1) AS duree_moyenne,
            SUM((DATEDIFF(l.date_fin, l.date_debut) + 1) * m.prix_jour) AS depenses_totales,
            AVG((DATEDIFF(l.date_fin, l.date_debut) + 1) * m.prix_jour) AS depenses_moyen
        FROM client c
        LEFT JOIN location l ON c.id_client  = l.id_client
        LEFT JOIN van v ON l.plaque = v.plaque
        LEFT JOIN modele m ON v.id_modele  = m.id_modele
        GROUP BY c.id_client, c.nom_client, c.prenom
        ORDER BY nb_locations DESC, depenses_totales DESC"
    )->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>Classement clients</title>
    </head>

    <body>
        <div class="admin-content">
            <div class="page-header">
                <h1 class="page-title">Classement des clients</h1>
            </div>

            <p style="color:#666; margin-bottom:1.5rem;">
                Nombre de locations et dépenses totales par client, du plus actif au moins actif.
            </p>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Client</th>
                            <th>Nb locations</th>
                            <th>Total jours</th>
                            <th>Dépense moyenne</th>
                            <th>Dépenses totales</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($stats as $i => $s){ ?>
                            <tr>
                                <td style="color:#aaa;"><?= $i + 1 ?></td>
                                <td>
                                    <strong>
                                        <?= htmlspecialchars($s['nom_client']) ?>
                                        <?= htmlspecialchars($s['prenom']) ?>                                        
                                    </strong>
                                    <small style="color:#aaa;">(<?= $s['id_client'] ?>)</small>
                                </td>
                                <td><?= $s['nb_locations'] ?? 0 ?></td>
                                <td>
                                    <?= $s['total_jours'] ? $s['total_jours'] . ' jour(s)' : '—' ?>
                                </td>
                                <td>
                                    <?= $s['depense_moyenne'] ? number_format($s['depense_moyenne'], 2) . ' €' : '—' ?>
                                </td>
                                <td>
                                    <strong>
                                        <?= $s['depenses_totales'] ? number_format($s['depenses_totales'], 2) . ' €' : '—' ?>
                                    </strong>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>