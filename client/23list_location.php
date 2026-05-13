<?php
	session_start();
	require_once('../23_config.php');
	require_once("./23menu_client.php");

	$db = new DB();

	// Locations du client connecté uniquement
	$sql = "SELECT
				l.id_location,
				l.date_debut,
				l.date_fin,
				l.plaque,
				l.id_client,
				m.nom_modele,
				m.nombre_places_route,
				m.nombre_places_couchage,
				m.dimensions,
				m.prix_jour,
				DATEDIFF(l.date_fin, l.date_debut) + 1 AS duree,
				(DATEDIFF(l.date_fin, l.date_debut) + 1) * m.prix_jour AS prix_total
			FROM location l
			INNER JOIN van v    ON l.plaque    = v.plaque
			INNER JOIN modele m ON v.id_modele = m.id_modele
			WHERE l.id_client = ?
			ORDER BY l.date_debut ASC";
	$locations = $db->query($sql, [$_SESSION['id_client']])->fetchAll();
	
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Mes locations</title>
	</head>
	<body>
		<div class="client-content">
			<div class="page-header">
				<h1 class="page-title">Mes locations</h1>
				<a href="23insert_location.php" class="btn btn-client">+ Nouvelle location</a>
			</div>
				
			<?php if (isset($_GET['success'])){ ?>
				<div class="alert-success">Opération effectuée avec succès.</div>
			<?php }if(empty($locations)){ ?>
				<p class="aucun-resultat">Vous n'avez pas encore de location.</p>
			<?php }else{ ?>
				<div class="table-wrapper">
            		<table>
                		<thead>
							<tr>
								<th>Dates Début</th>
								<th>Dates Fin</th>
								<th>Plaque</th>
								<th>Modèle</th>
								<th>Route</th>
								<th>Couchage</th>
								<th>Prix/j</th>
								<th>Durée</th>
								<th>Total</th>
								<th>Action</th>
							</tr>
						</thead>
					<tbody>
						<?php foreach ($locations as $l): ?>
							<tr>
								<td>
									<?= date('d/m/Y', strtotime($l['date_debut'])) ?>
								</td>
								<td>
									<?= date('d/m/Y', strtotime($l['date_fin'])) ?>
								</td>
								<td>
									<strong><?= htmlspecialchars($l['plaque']) ?></strong>
								</td>
								<td>
									<?= htmlspecialchars($l['nom_modele']) ?>
									<small style="display:block; color:#aaa;">
										<?= htmlspecialchars($l['dimensions']) ?>
									</small>
								</td>
								<td>
									<?= $l['nombre_places_route'] ?>
								</td>
								<td>
									<?= $l['nombre_places_couchage'] ?>
								</td>
								<td>
									<?= number_format($l['prix_jour'], 2) ?> €
								</td>
								<td><?= $l['duree'] ?> jour(s)</td>
								<td>
									<strong style="color:#0F6E56;">
										<?= number_format($l['prix_total'], 2) ?> €
									</strong>
								</td>
								<td>
									<a href="23delete_location.php?id=<?= $l['id_location'] ?>" style="color:#e53e3e;"
									class="btn-action delete" onclick="return confirm('Supprimer cette location ?')">
										 Supprimer
									</a>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</div> 
				<p class="count-info"><?= count($locations) ?> location(s)</p>
			<?php } ?>
		</div>
	</body>
</html>