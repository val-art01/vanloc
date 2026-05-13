<?php	
	session_start();

	// si la session de l'utilisateur existe déjà, 
	//il n'oblige pas de s'authentifier et il renvoie directement à la page Mes_locations
	if (isset ($_SESSION['id_client'])){
		header("Location: ./23list_location.php");
		exit();
	}

	require_once'./../23_config.php'; // Etape 1: créer une connexion
	$db = new DB();
	$erreur = "";

	// Traiter les informations quand le client appuie sur le bouton "Se connecter"
	if ($_SERVER["REQUEST_METHOD"] == "POST"){
		
		// Etape 2: Recuperation des données
		$email = trim($_POST['email'] ?? '');
		$mot_de_passe = $_POST['mot_de_passe'] ?? '';

		//Etape 3: Interroger la base de données avec l'id et password donné
		$sql = "SELECT * FROM client WHERE email= ?";
		$result =  $db->query($sql, [$email]);
		$user = $result->fetch();
		
		if ($user && $mot_de_passe === $user['mot_de_passe'] ) { 
			// Etape 4: Sauvegarder le nom de l'utilisateur, 
			$_SESSION['id_client'] = $user ['id_client'];
			$_SESSION['nom_client'] = $user ['nom_client'];
			$_SESSION['prenom'] = $user ['prenom'];
			header ("Location:23list_location.php");
			exit();
		}else{ // au cas contraire, afficher le message d'erreur et rester sur la meme page;
			$erreur = 'Email ou mot de passe incorrect.';
		}
	}
?>

<!DOCTYPE html>
<html lang = "fr">
	<head>
		<meta charset="UTF-8">
		<title>Connexion Client</title>
		<link rel="stylesheet" type="text/css" href="./../css/style.css">
		<link rel="stylesheet" type="text/css" href="./../css/23client.css">
	</head>
	<body>
		<div class="auth-wrapper">
			<div class="auth-card">

				<div class="auth-header">
					<strong>Se connecter</strong>
					<p>Espace client — HEC VanLife</p>
				</div>

				<?php if ($erreur): ?>
					<div class="alert-error"><?= htmlspecialchars($erreur) ?></div>
				<?php endif; ?>

				<form method="POST" action="">
					<div class="form-group">
						<label for="email">Email</label>
						<input type="email" id="email" name="email"	value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
							placeholder="jean@mail.be" required>
					</div>
					<div class="form-group">
						<label for="mot_de_passe">Mot de passe</label>
						<input type="password" id="mot_de_passe" name="mot_de_passe" placeholder="••••••" required>
					</div>
					<button type="submit" class="btn btn-client" style="width:100%;">
						Se connecter
					</button>
				</form>

				<p class="auth-footer">
					Pas encore de compte ?
					<a href="23register.php">S'enregistrer</a>
				</p>
				<p class="auth-footer">
					<a href="../index.php">Retour à l'accueil</a>
				</p>

			</div>
		</div>
	</body>
</html>