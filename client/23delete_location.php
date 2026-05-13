<?php
	session_start();
	require_once("./../23_config.php"); // Connexion à MySQL 
	require_once("./23menu_client.php");

	$db = new DB();
    $id = (int)($_GET['id'] ?? 0);

	// Sécurité : vérifier que la location appartient bien au client connecté
    $location = $db->query(
        "SELECT id_location FROM location
         WHERE id_location = ? AND id_client = ?",
        [$id, $_SESSION['id_client']]
    )->fetch();

    if ($location) {
        $db->execute(
            "DELETE FROM location WHERE id_location = ?",
            [$id]
        );
    }

    header('Location: 23list_location.php?success=1');
    exit();
?>
	