<?php 
	//Securité : vérifier si le client est connecté
	if (!isset($_SESSION['id_client'])) {
		header("Location: 23login.php");
		exit();
	}

	//Determiner la page active pour le style
	$page_active = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="./../css/style.css">
		<link rel="stylesheet" type="text/css" href="./../css/23client.css">
	</head>
	<body>
		<nav class="client-nav">
			<span class="brand">HEC VanLife</span>

			<a href="23list_location.php" class="<?= $page_active === '23list_location.php' ? 'active' : '' ?>">
				Mes locations
			</a>

			<a href="23edit_compte.php"	class="<?= $page_active === '23edit_compte.php' ? 'active' : '' ?>">
				Mon compte
			</a>
			
			<span class="client-info">
				<?= htmlspecialchars($_SESSION['prenom']) ?>
        		<?= htmlspecialchars($_SESSION['nom_client']) ?>
			</span>
			<a href="23logout.php" class="logout">Se deconnecter</a>
		</nav>
	</body>
</html>		
