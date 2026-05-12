<?php
    session_start();
    require_once './../23_config.php';
    require_once '23menu.php'; // vérifie session + affiche menu

    $db = new DB();

    //Récupérer la lettre filtrée (si fournie)
    $lettre = $_GET['lette'] ?? '';

    // Requete avec ou sans filtre
    if ($lettre !== '') {
        $clients = $db->query(
            "SELECT id_client, nom_client, prenom, rue, code_postal, ville, gsm, email 
             FROM client
             WHERE nom_client LIKE ?
             ORDER BY nom_client
            ",
            [$lettre . '%']
        )->fetchAll();
    }else{
         $clients = $db->query(
            "SELECT id_client, nom_client, prenom, rue, code_postal, ville, gsm, email 
             FROM client
             ORDER BY nom_client
            ",
        )->fetchAll();
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Liste - Clients</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="./../css/style.css">
        <link rel="stylesheet" href="./../css/23admin.css">
    </head>
    <body>
        <div class="admin-content">
            <h1 class="page-title">Clients</h1>

            <!-- Filtre alphabétique -->
            <div class="filtre-alpha">
                <a href="23list_client.php" class="btn-lettre <?= $lettre === '' ? 'active' : '' ?>">
                    Tous
                </a>
                <?php foreach (range('A', 'Z') as $l): ?>
                    <a href="23list_client.php?lettre=<?= $l ?>" class="btn-lettre <?= $lettre === $l ? 'active' : '' ?>">
                        <?= $l ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Tableau -->
             <?php if (empty($clients)): ?>
                <P class="aucun-resultat">Aucun client trouvé.</P>
            <?php else : ?>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Identifiant</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Adresse</th>
                                <th>GSM</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                       <tbody>
                            <?php foreach ($clients as $cl): ?>
                                <tr class="list">
                                    <td><?= $cl['id_client'] ?></td>
                                    <td><?= htmlspecialchars($cl['nom_client']) ?></td>
                                    <td><?= htmlspecialchars($cl['prenom']) ?></td>
                                    <td>
                                        <?= htmlspecialchars($cl['rue']) ?>,
                                        <?= htmlspecialchars($cl['codepostal']) ?>
                                        <?= htmlspecialchars($cl['ville']) ?>
                                    </td>
                                    <td><?= htmlspecialchars($cl['gsm']) ?></td>
                                    <td><?= htmlspecialchars($cl['email']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                       </tbody>
                    </table>
                </div>
                 <p class="count-info"><?= count($clients) ?> client(s) affiché(s)</p>
            <?php endif; ?>
        </div>
    </body>
</html>