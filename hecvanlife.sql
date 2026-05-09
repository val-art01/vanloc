--
-- Table structure for table `client`
--

DROP TABLE IF EXISTS `client`;
CREATE TABLE IF NOT EXISTS `client` (
  `id_client` int NOT NULL AUTO_INCREMENT,
  `nom_client` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `prenom` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `rue` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `code_postal` int NOT NULL,
  `ville` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `gsm` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `email` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `mot_de_passe` varchar(50) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id_client`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------


--
-- Table structure for table `modele`
--

DROP TABLE IF EXISTS `modele`;
CREATE TABLE IF NOT EXISTS `modele` (
  `id_modele` int NOT NULL AUTO_INCREMENT,
  `nom_modele` varchar(50) COLLATE latin1_general_ci NOT NULL, 
  `nombre_places_route` int NOT NULL,
  `nombre_places_couchage` int NOT NULL,
  `dimensions` varchar(50) NOT NULL,
  `prix_jour` int NOT NULL, 
  PRIMARY KEY (`id_modele`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `van`
--

DROP TABLE IF EXISTS `van`;
CREATE TABLE IF NOT EXISTS `van` (
  `plaque` varchar(9) NOT NULL, 
  `id_modele` int NOT NULL, 
  PRIMARY KEY (`plaque`),
  KEY `id_modele` (`id_modele`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

DROP TABLE IF EXISTS `location`;
CREATE TABLE IF NOT EXISTS `location` (
  `id_location` int NOT NULL AUTO_INCREMENT,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,   
  `plaque` varchar(9) NOT NULL,  
  `id_client` int NOT NULL,
  PRIMARY KEY (`id_location`),
  KEY `id_van_loca` (`plaque`),
  KEY `id_client_loca` (`id_client`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;


--
-- Contraintes pour la table `location`
--
ALTER TABLE `location`
  ADD CONSTRAINT `location_ibfk_2` FOREIGN KEY (`id_client`) REFERENCES `client` (`id_client`),
  ADD CONSTRAINT `location_ibfk_1` FOREIGN KEY (`plaque`) REFERENCES `van` (`plaque`);

--
-- Contraintes pour la table `van`
--
ALTER TABLE `van`
  ADD CONSTRAINT `van_ibfk_1` FOREIGN KEY (`id_modele`) REFERENCES `modele` (`id_modele`);
