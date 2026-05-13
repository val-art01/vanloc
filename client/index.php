<?php
	session_start();
	//Si déjà connecté -> liste Van
	if (isset($_SESSION['id_client'])){
		header("Location: ./23list_location.php");
		exit();
	}
	// Sinon, rediriger vers la page login.php
	header('Location: ./23login.php');
	exit();
?>