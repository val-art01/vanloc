<?php
	// Etape 1 : initialiser la session
	session_start();
	require_once '../23_config.php';
    require_once './23menu_client.php'; // menu de navigation

	$db = new DB();
    $erreur = '';
    $succes = '';

    // Récupérer les infos actuelles du client connecté
    $client = $db->query(
        "SELECT * FROM client WHERE id_client = ?",
        [$_SESSION['id_client']]
    )->fetch();

    // Traitement de la modification
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $nom      = trim($_POST['nom_client'] ?? '');
        $prenom   = trim($_POST['prenom'] ?? '');
        $rue      = trim($_POST['rue'] ?? '');
        $cp       = trim($_POST['code_postal'] ?? '');
        $ville    = trim($_POST['ville'] ?? '');
        $gsm      = trim($_POST['gsm'] ?? '');

        if ($nom === '' || $prenom === '' || $rue === '' || $cp === '' || $ville === '' || $gsm === '') {
            $erreur = "Les champs Nom, Prénom, Rue, Code postal, ville et gsm sont obligatoires.";
        } else {
            $db->execute(
                "UPDATE client
                 SET nom_client= ?, prenom = ?, rue = ?, code_postal = ?, ville = ?, gsm = ?
                 WHERE id_client = ?",
                [$nom, $prenom, $rue, $cp, $ville, $gsm, $_SESSION['id_client']]
            );

            // Mettre à jour la session si nom/prénom changés
            $_SESSION['nom_client'] = $nom;
            $_SESSION['prenom']     = $prenom;

            // Recharger les infos depuis la DB
            $client = $db->query(
                "SELECT * FROM client WHERE id_client = ?",
                [$_SESSION['id_client']]
            )->fetch();

            $succes = "Vos informations ont été mises à jour.";
        }
    }
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>Mes données</title>
    </head>
    <body>
        <div class="client-content">
            <div class="page-header">
                <h1 class="page-title">Mon compte</h1>
            </div>
            
            <?php if ($succes){ ?>
                <div class="alert-success"><?= htmlspecialchars($succes) ?></div>
            <?php }if ($erreur){ ?>
                <div class="alert-error"><?= htmlspecialchars($erreur) ?></div>
            <?php } ?>

            <div class="form-card">
                <form method="POST" action="">
                    <!-- id_client affiché mais non modifiable -->
                    <div class="form-group">
                        <label>Identifiant client</label>
                        <input type="text" value="<?= $client['id_client'] ?>" disabled>
                        <small style="color:#888;">Cet identifiant ne peut pas être modifié.</small>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="nom_client">Nom *</label>
                            <input type="text" id="nom_client" name="nom_client"
                                value="<?= htmlspecialchars($_POST['nom_client'] ?? $client['nom_client']) ?>"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="prenom">Prénom *</label>
                            <input type="text" id="prenom" name="prenom"
                                value="<?= htmlspecialchars($_POST['prenom'] ?? $client['prenom']) ?>"
                                required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="rue">Rue *</label>
                        <input type="text" id="rue" name="rue"
                            value="<?= htmlspecialchars($_POST['rue'] ?? $client['rue']) ?>">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="code_postal">Code postal *</label>
                            <input type="text" id="code_postal" name="code_postal"
                                value="<?= htmlspecialchars($_POST['code_postal'] ?? $client['code_postal']) ?>">
                        </div>
                        <div class="form-group">
                            <label for="ville">Ville *</label>
                            <input type="text" id="ville" name="ville"
                                value="<?= htmlspecialchars($_POST['ville'] ?? $client['ville']) ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="gsm">GSM</label>
                        <input type="text" id="gsm" name="gsm"
                            value="<?= htmlspecialchars($_POST['gsm'] ?? $client['gsm']) ?>">
                    </div>

                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($client['email']) ?>" disabled>
                        <small style="color:#888;">Ce champ ne peut pas être modifié.</small>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-client">
                            Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>	
        </div>
    </body>
</html>