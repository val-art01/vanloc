<?php 
    session_start();
    require_once("./../23_config.php");
    require_once("23menu.php");

    $db = new DB();

    // Statistiques par modèle :
    // - nombre de locations
    // - durée moyenne (DATEDIFF + 1)
    // - revenu total (SUM)
    // - revenu moyen par location (AVG)
    $stats = $db->query(
        "SELECT
            m.id_modele,
            m.nom_modele,
            m.prix_jour,
            COUNT(l.id_location) AS nb_locations,
            AVG(DATEDIFF(l.date_fin, l.date_debut) + 1) AS duree_moyenne,
            SUM((DATEDIFF(l.date_fin, l.date_debut) + 1) * m.prix_jour) AS revenu_total,
            AVG((DATEDIFF(l.date_fin, l.date_debut) + 1) * m.prix_jour) AS revenu_moyen
        FROM modele m
        LEFT JOIN van v ON m.id_modele = v.id_modele
        LEFT JOIN location l ON v.plaque = l.plaque
        GROUP BY m.id_modele, m.nom_modele, m.prix_jour
        ORDER BY revenu_total DESC"
    )->fetchAll();

    // Total général
    $total_general = $db->query(
        "SELECT
            SUM((DATEDIFF(l.date_fin, l.date_debut) + 1) * m.prix_jour) AS grand_total,
            COUNT(l.id_location) AS total_locations
        FROM location l
        INNER JOIN van v    ON l.plaque    = v.plaque
        INNER JOIN modele m ON v.id_modele = m.id_modele"
    )->fetch();
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>Revenus par modèle</title>
    </head>

    <body>
        <div class="admin-content">
            <div class="page-header">
                <h1 class="page-title">Revenus par modèle de van</h1>
            </div>
            <p style="color:#666; margin-bottom:1.5rem;">
                Chiffre d'affaires, durée moyenne et nombre de locations par modèle.
            </p>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Modèle</th>
                            <th>Prix/jour</th>
                            <th>Nb locations</th>
                            <th>Durée moyenne</th>
                            <th>Revenu moyen/location</th>
                            <th>Revenu total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($stats as $s){ ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($s['nom_modele']) ?></strong>
                                </td>
                                <td>
                                    <?= number_format($s['prix_jour'], 2) ?> €
                                </td>
                                <td> 
                                    <?= $s['nb_locations'] ?? 0 ?>
                                
                                </td>
                                <td>
                                    <?= $s['duree_moyenne'] ? number_format($s['duree_moyenne'], 1) . ' jour(s)' : '—' ?>
                                </td>
                                <td>
                                    <?= $s['revenu_moyen'] ? number_format($s['revenu_moyen'], 2) . ' €' : '—' ?>
                                </td>
                                <td>
                                    <strong>
                                        <?= $s['revenu_total'] ? number_format($s['revenu_total'], 2) . ' €' : '—' ?>
                                    </strong>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr style="background:#f8f9fb; font-weight:500;">
                            <td colspan="2">TOTAL</td>
                            <td>
                                <?= $total_general['total_locations'] ?> location(s)
                            </td>
                            <td colspan="2"></td>
                            <td>
                                <strong><?= number_format($total_general['grand_total'] ?? 0, 2) ?> €</strong>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </body>
</html>