<?php
    $host     = 'localhost';
    $dbname   = 'hecvanlife';
    $user     = 'root';       // à adapter selon ton config
    $password = '';           // à adapter

    try {
        $pdo = new PDO(
            "mysql:host=$host;dbname=$dbname;charset=utf8", 
            $user, 
            $password
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
?>