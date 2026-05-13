<?php
	session_start();
	require_once './../23_config.php';
	require_once("./23menu_client.php");

	$db = new DB();
	$erreur = '';

	// Tous les vans avec infos utiles (sans vérif disponibilité)
	$sql = "SELECT van.plaque, modele.nom_modele, modele.prix_jour 
			FROM van 
			INNER JOIN modele ON van.id_modele = modele.id_modele
			ORDER BY modele.nom_modele, van.plaque
			";
	$vans = $db->query($sql)->fetchAll();

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$date_debut = $_POST['date_debut'] ?? '';
		$date_fin = $_POST['date_fin'] ?? '';
		$plaque = $_POST['plaque'] ?? '';

		if ($date_debut === '' || $date_fin === '' || $plaque === '') {
       		$erreur = "Veuillez remplir tous les champs.";
		} elseif ($date_fin < $date_debut) {
			$erreur = "La date de fin doit être après la date de début.";
		} else {
			try {
				$sql_ins = "INSERT INTO location (date_debut, date_fin, plaque, id_client) VALUES (?, ?, ?, ?)";
				$db->query($sql_ins, [$date_debut, $date_fin, $plaque, $_SESSION['id_client']]);
				header('Location: 23list_location.php?success=1');
        		exit();
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
		<title>Nouvelle Location</title>
	</head>
	<body>
		<div class="client-content">
			<div class="page-header">
				<h1 class="page-title">Nouvelle location</h1>
				<a href="23list_location.php" class="btn">Retour</a>
			</div>
		
			 <?php if ($erreur): ?>
				<div class="alert-error"><?= htmlspecialchars($erreur) ?></div>
			<?php endif; ?>

			<div class="form-card">
				<form method="POST" action="">						
					<div class="form-row">
						<div class="form-group">
							<label for="date_debut">Date de début</label>
							<input type="date" id="date_debut" name="date_debut" required>
						</div>
						<div class="form-group">
							<label for="date_fin">Date de fin</label>
							<input type="date" id="date_fin" name="date_fin" required>
						</div>
					</div>								
								
					<div class="form-group">
						<label for="plaque">Van</label>
						<select id="plaque" name="plaque" required>
							<option value="">-- Choisir un van --</option>
							<?php foreach ($vans as $v): ?>
								<option value="<?= htmlspecialchars($v['plaque']) ?>"
									<?= (($_POST['plaque'] ?? '') === $v['plaque']) ? 'selected' : '' ?>>
									<?= htmlspecialchars($v['plaque']) ?>
									— <?= htmlspecialchars($v['nom_modele']) ?>									
									— <?= number_format($v['prix_jour'], 2) ?> €/jour
								</option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="form-actions">
						<a href="23list_location.php" class="btn">Annuler</a>
						<button type="submit" class="btn btn-client">Réserver</button>
					</div>
				</form>
			</div>	
		</div>
	</body>
</html>