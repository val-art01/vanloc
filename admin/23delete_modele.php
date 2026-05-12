<?php
    session_start();
    require_once("./../23_config.php");

    $db = new DB();
    $id = (int)($_GET["id"] ?? 0);

    // verification si le modele n'est pas affecter a un van
    $nb = $db->query(
        "SELECT COUNT(*) AS nb FROM van WHERE id_modele = ?", [$id]
    )->fetch();

    // On ne supprime que si aucun van n'utilise ce modèle
    if ($nb['nb'] == 0) {
        $db->execute("DELETE FROM modele WHERE id_modele = ?", [$id]);
    }

    header('Location: ./23list_modele.php?success=1');
    exit();
?>