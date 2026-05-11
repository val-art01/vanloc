
<?php
    session_start();

    // Si déjà connecté -> liste clients
    if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
        header('Location: 23list_client.php');
        exit();
    }

    // Sinon -> login
    header('Location: 23login.php');
    exit();
?>