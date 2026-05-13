<?php   
    session_start();
    // Etape 1 : Si déjà authentifié, renvoyer vers "Mes locations"
    if (isset($_SESSION['id_client'])) {
        header("Location: ./23list_location.php");
        exit();
    }

    require_once './../23_config.php';
    $db = new DB();
    $erreur  = '';
    $succes = false; // Initialisation logique à false


    // Etape 2 : Test si l'utilisateur a cliqué sur le bouton créer
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Récupération sécurisée des données
        $nom = trim($_POST['nom_client'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $rue = trim($_POST['rue'] ?? '');
        $code_postal = trim($_POST['code_postal'] ?? '');
        $ville = trim($_POST['ville'] ?? '');
        $gsm = trim($_POST['gsm'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $pass = $_POST['mot_de_passe'] ?? '';

        if ($nom === '' || $prenom === '' || $email === '' || $pass === '') {
            $erreur = "Veuillez remplir tous les champs obligatoires.";
        } else {
            try {
                // Requête INSERT avec des marqueurs '?' pour éviter les injections SQL
                $db->execute(
                    "INSERT INTO client (nom_client, prenom, rue, code_postal, ville, gsm, email, mot_de_passe)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
                    [$nom, $prenom, $rue, $code_postal, $ville, $gsm, $email, $pass]
                );
                $succes = true;
            } catch (PDOException $e) {
                $erreur = "Erreur lors de l'enregistrement. L'adresse email est peut-être déjà utilisée.";
                var_dump($e->errorInfo);
            }
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>S'enregistrer</title>
        <link rel="stylesheet" href="./../css/style.css">
        <link rel="stylesheet" href="./../css/23client.css">
    </head>
    <body>
        <div class="auth-wrapper">
            <div class="auth-card" style="max-width:500px;">

                <div class="auth-header">
                    <strong>Créer un compte</strong>
                    <p>HEC VanLife — Inscription</p>
                </div>

                <?php if ($succes){ ?>
                    <!-- Succès : afficher l'id_client -->
                    <div class="alert-success"> Compte créé avec succès !<br>
                    </div>
                    <p style="text-align:center; margin-top:1rem;">
                        <a href="23login.php" class="btn btn-client">Se connecter</a>
                    </p>
                <?php }else{ ?>
                    <?php if ($erreur){ ?>
                        <div class="alert-error"><?= htmlspecialchars($erreur) ?></div>
                    <?php } ?>
                    <form method="POST" action="">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="nom_client">Nom *</label>
                                <input type="text" id="nom_client" name="nom_client" value="<?= htmlspecialchars($_POST['nom_client'] ?? '') ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="prenom">Prénom *</label>
                                <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="rue">Rue</label>
                            <input type="text" id="rue" name="rue" value="<?= htmlspecialchars($_POST['rue'] ?? '') ?>">
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="code_postal">Code postal</label>
                                <input type="text" id="code_postal" name="code_postal" value="<?= htmlspecialchars($_POST['code_postal'] ?? '') ?>">
                            </div>
                            <div class="form-group">
                                <label for="ville">Ville</label>
                                <input type="text" id="ville" name="ville" value="<?= htmlspecialchars($_POST['ville'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="gsm">GSM</label>
                            <input type="text" id="gsm" name="gsm" value="<?= htmlspecialchars($_POST['gsm'] ?? '') ?>">
                        </div>

                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="mot_de_passe">Mot de passe *</label>
                            <input type="password" id="mot_de_passe" name="mot_de_passe" required>
                        </div>

                        <div class="form-actions">
                            <a href="23login.php" class="btn">Annuler</a>
                            <button type="submit" class="btn btn-client">Créer mon compte</button>
                        </div>
                    </form>
                <?php } ?>
            </div>
        </div>
    </body>
</html>