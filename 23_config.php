<?php

    /**
     * Classe DB
     * Gère la connexion à la base de données via PDO.
     * Une seule connexion est créée et réutilisée (singleton simple).
     */
    class DB {
        // Paramètres de connexion
        private string $host = 'localhost';
        private string $name = 'hecvanlife';
        private string $user = 'admin';
        private string $pass = '';

        // Instance PDO unique
        private static ?PDO $connexion = null;

        /**
         * Constructeur
         * Permet de surcharger les paramètres si nécessaire.
        */
        public function __construct(?string $host = null, ?string $name = null, ?string $user = null, ?string $pass = null) {
            if ($host !== null) {
                $this->host = $host;
                $this->name = $name;
                $this->user = $user;
                $this->pass = $pass;
            }

            // Création de la connexion UNE SEULE FOIS
            if (self::$connexion === null) {
                try {
                    self::$connexion = new PDO(
                        "mysql:host={$this->host};dbname={$this->name};charset=utf8mb4",
                        $this->user,
                        $this->pass,
                        [
                            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        ]
                    );
                } catch (PDOException $e) {
                    die('Erreur : connexion à la base de données impossible.');
                }
            }
        }

        /**
         * Exécute une requête SELECT.
        */
        public function query(string $sql, array $data = []): PDOStatement {
            $req = self::$connexion->prepare($sql);
            $req->execute($data);
            return $req;
        }

        /**
         * Exécute une requête INSERT / UPDATE / DELETE.
        */
        public function execute(string $sql, array $data = []): bool {
            $req = self::$connexion->prepare($sql);
            return $req->execute($data);
        }
    }

