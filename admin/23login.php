<?php 
    session_start();

    // Deja connecter, rediriger directement
    if (isset($_SESSION['admin']) && $_SESSION['admin'] === true){
        header('Location: 23list_client.php');
        exit();
    }

    $erreur = '';

    // Traitement du formulaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($username === 'admin' && $password === 'admin') {
            $_SESSION['admin'] = true;
            header('Location: 23list_client.php');
            exit();
        } else {
            $erreur = "Identifiants incorrects. Veuillez réessayer.";
        }
    }
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Connexion Admin — HEC VanLife</title>
        <link rel="stylesheet" href="./../css/style.css">
        <link rel="stylesheet" href="./../css/23admin.css">
    </head>
    <body>
        <div class="login-wrapper">
            <div class="login-card">

                <div class="login-header">
                    <strong>Connexion administrateur</strong>
                    <p>HEC VanLife — Espace admin</p>
                </div>

                <?php if ($erreur): ?>
                    <div class="alert-error"><?= htmlspecialchars($erreur) ?></div>
                <?php endif; ?>

                <form method="POST" action="">

                    <div class="form-group">
                        <label for="username">Nom d'utilisateur</label>
                        <input type="text" id="username" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" placeholder="admin" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" id="password" name="password" placeholder="••••••" required>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width:100%;">
                        Se connecter
                    </button>

                </form>

                <p class="login-footer">
                    <a href="../index.php">Retour à l'accueil</a>
                </p>

            </div>
        </div>
    </body>
</html>