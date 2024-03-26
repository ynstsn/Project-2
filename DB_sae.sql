-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : gigondas
-- Généré le : jeu. 11 jan. 2024 à 12:11
-- Version du serveur : 10.11.3-MariaDB-1
-- Version de PHP : 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `chahboum`
--

-- --------------------------------------------------------

--
-- Structure de la table `analyses`
--

CREATE TABLE `analyses` (
  `CodeAnalyse` char(10) NOT NULL,
  `LibelleAnalyse` varchar(50) DEFAULT NULL,
  `CodeTypePrel` char(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `analyses`
--

INSERT INTO `analyses` (`CodeAnalyse`, `LibelleAnalyse`, `CodeTypePrel`) VALUES
('CONSE', 'constantes erythrocytaires', 'PI'),
('FORML', 'formule leucocytaire (Pour 100)', 'PI'),
('HEMO', 'hemogramme', 'PI'),
('NUMG', 'numeration globulaire', 'PI'),
('VITESG', 'vitesse de sedimentastion globulaire', 'PI');

-- --------------------------------------------------------

--
-- Structure de la table `chapitre`
--

CREATE TABLE `chapitre` (
  `Lettre` char(1) NOT NULL,
  `Libelle` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `chapitre`
--

INSERT INTO `chapitre` (`Lettre`, `Libelle`) VALUES
('A', 'Anatomie et cytologie pathologique\n'),
('B', 'Hématologie'),
('C', 'Microbiologie'),
('D', 'Immunologie'),
('E', 'Epreuves fonctionnelles'),
('F', 'Hormonologie'),
('G', 'Enzymologie'),
('H', 'Chimie biologique');

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

CREATE TABLE `client` (
  `NoClient` int(11) NOT NULL,
  `NomClient` varchar(50) DEFAULT NULL,
  `PrenomClient` varchar(50) DEFAULT NULL,
  `Adresse` varchar(50) DEFAULT NULL,
  `CodePostal` char(5) DEFAULT NULL,
  `Ville` varchar(50) DEFAULT NULL,
  `DateNaissance` date DEFAULT NULL,
  `Sexe` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`NoClient`, `NomClient`, `PrenomClient`, `Adresse`, `CodePostal`, `Ville`, `DateNaissance`, `Sexe`) VALUES
(1, 'Chahboune', 'Marwane', '150 rue wesh wesh', '25000', 'Valence', '2023-11-13', 1),
(8, 'Chahboune', 'Marwanito', '54', '584', '684468', '2023-11-15', 1),
(11, 'Boulle', 'Didier', '37 Rue des morts', '26000', 'Valence', '2001-06-20', 1),
(14, 'Tosun', 'Ouassim', '28 Rue de Laffemas', '26000', 'Valence', '2001-07-05', 1);

-- --------------------------------------------------------

--
-- Structure de la table `comporter`
--

CREATE TABLE `comporter` (
  `CodeAnalyse` char(10) NOT NULL,
  `NoOrdonnance` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `comporter`
--

INSERT INTO `comporter` (`CodeAnalyse`, `NoOrdonnance`) VALUES
('CONSE', 163),
('CONSE', 165),
('FORML', 163),
('FORML', 166),
('HEMO', 163),
('HEMO', 165),
('HEMO', 166),
('NUMG', 163),
('VITESG', 163),
('VITESG', 165),
('VITESG', 166);

-- --------------------------------------------------------

--
-- Structure de la table `demandeur`
--

CREATE TABLE `demandeur` (
  `NoDemandeur` int(11) NOT NULL,
  `Typedemandeur` varchar(50) DEFAULT NULL,
  `NomDemandeur` varchar(50) DEFAULT NULL,
  `AdresseDemandeur` varchar(50) DEFAULT NULL,
  `CodePostalDemandeur` char(5) DEFAULT NULL,
  `VilleDemandeur` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `demandeur`
--

INSERT INTO `demandeur` (`NoDemandeur`, `Typedemandeur`, `NomDemandeur`, `AdresseDemandeur`, `CodePostalDemandeur`, `VilleDemandeur`) VALUES
(1, 'Entreprise', 'Hopitale Valence', '150 rue de la grosse', '26260', 'VilleBelle');

-- --------------------------------------------------------

--
-- Structure de la table `operation`
--

CREATE TABLE `operation` (
  `NoOperation` int(11) NOT NULL,
  `LibelleOpe` varchar(50) DEFAULT NULL,
  `TypeResultat` char(10) DEFAULT NULL,
  `NormeInf` decimal(12,2) DEFAULT NULL,
  `NormeSup` decimal(12,2) DEFAULT NULL,
  `Unite` char(10) DEFAULT NULL,
  `Lettre` char(1) NOT NULL,
  `CodeAnalyse` char(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `operation`
--

INSERT INTO `operation` (`NoOperation`, `LibelleOpe`, `TypeResultat`, `NormeInf`, `NormeSup`, `Unite`, `Lettre`, `CodeAnalyse`) VALUES
(1, 'hematocrite', 'int', '35.00', '46.00', '%', 'A', 'HEMO'),
(14, 'hemoglobine', 'int', '12.00', '16.00', 'G/100ML', 'A', 'HEMO'),
(15, 'globules rouges', 'int', '4.10', '5.40', 'M/mm3', 'A', 'NUMG'),
(16, 'globules blancs', 'int', '4000.00', '8000.00', 'M/mm3', 'A', 'NUMG'),
(17, 'volume glob. moyen', 'int', '83.00', '98.00', 'FL', 'A', 'CONSE'),
(18, 'charge glob. moyen', 'int', '27.00', '32.00', 'PG', 'A', 'CONSE'),
(19, 'concentration', 'int', '32.00', '36.00', '%', 'A', 'CONSE'),
(20, 'polynucléaires neutrophiles', 'int', '55.00', '70.00', '%', 'A', 'FORML'),
(21, 'polynucléaires eosimophiles', 'int', '0.00', '3.00', '%', 'A', 'FORML'),
(22, 'polynucléaires basophiles', 'int', '0.00', '2.00', '%', 'A', 'FORML'),
(23, 'lymphocytes', 'int', '20.00', '35.00', '%', 'A', 'FORML'),
(24, 'monocytes', 'int', '0.00', '10.00', '%', 'A', 'FORML'),
(25, '1 ere heure', 'time', '0.00', '0.00', 'MM', 'A', 'VITESG'),
(26, '1 eme heure', 'time', '0.00', '0.00', 'MM', 'A', 'VITESG');

-- --------------------------------------------------------

--
-- Structure de la table `ordonnance`
--

CREATE TABLE `ordonnance` (
  `NoOrdonnance` int(11) NOT NULL,
  `DateOrdonnance` date DEFAULT NULL,
  `DateRealisation` date DEFAULT NULL,
  `InformationsPrelevements` text DEFAULT NULL,
  `Clos` tinyint(1) DEFAULT NULL,
  `NoDemandeur` int(11) NOT NULL,
  `NoClient` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ordonnance`
--

INSERT INTO `ordonnance` (`NoOrdonnance`, `DateOrdonnance`, `DateRealisation`, `InformationsPrelevements`, `Clos`, `NoDemandeur`, `NoClient`) VALUES
(163, '2023-12-12', '2023-12-28', 'Problème de fois, mal de têe\r\n', 1, 1, 11),
(165, '2024-01-10', '2024-01-17', 'Mal de ventre\r\n', 1, 1, 11),
(166, '2023-09-15', '2023-12-14', 'Problème de foie.', 0, 1, 14);

-- --------------------------------------------------------

--
-- Structure de la table `personnel`
--

CREATE TABLE `personnel` (
  `Id_Personnel` int(11) NOT NULL,
  `Nom` varchar(50) DEFAULT NULL,
  `Prenom` varchar(50) DEFAULT NULL,
  `Sexe` tinyint(1) DEFAULT NULL,
  `Adresse` varchar(50) DEFAULT NULL,
  `Ville` varchar(50) DEFAULT NULL,
  `Cp` int(11) DEFAULT NULL,
  `Dob` date DEFAULT NULL,
  `Id_Type_Personnel` int(11) NOT NULL,
  `Pseudo` varchar(40) NOT NULL,
  `Mdp` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `personnel`
--

INSERT INTO `personnel` (`Id_Personnel`, `Nom`, `Prenom`, `Sexe`, `Adresse`, `Ville`, `Cp`, `Dob`, `Id_Type_Personnel`, `Pseudo`, `Mdp`) VALUES
(1, 'Chahboune', 'Marwane', 1, '150 rue de la grosses', 'VilleBelless', 26261, '2004-08-07', 1, 'operateur', '$2y$12$lsZUI2SA3glbEhiSM18L7.dm8xOaWJiN07ui9kV2gLbSIGO0OjWhS'),
(182, 'Chahboune', 'Marwane', 1, '150 rue de la grosses', 'VilleBelless', 26261, '2004-08-07', 2, 'administratif', '$2y$12$lsZUI2SA3glbEhiSM18L7.dm8xOaWJiN07ui9kV2gLbSIGO0OjWhS'),
(183, 'Chahboune', 'Marwane', 1, '150 rue de la grosses', 'VilleBelless', 26261, '2004-08-07', 3, 'admin', '$2y$12$lsZUI2SA3glbEhiSM18L7.dm8xOaWJiN07ui9kV2gLbSIGO0OjWhS'),
(184, 'Slimani', 'Ouassime ', 1, '37 rue des mort', 'Montelimar', 26200, '2001-06-06', 1, 'slimanio', NULL),
(185, 'Tosun', 'Yunus-Emre', 1, '47  Hamilton Avenue', 'Valence', 26000, '2004-06-15', 2, 'tosuny', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `possède`
--

CREATE TABLE `possède` (
  `CodeQualif` char(5) NOT NULL,
  `Id_Personnel` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `possède`
--

INSERT INTO `possède` (`CodeQualif`, `Id_Personnel`) VALUES
('MED', 1);

-- --------------------------------------------------------

--
-- Structure de la table `qualification`
--

CREATE TABLE `qualification` (
  `CodeQualif` char(5) NOT NULL,
  `NomQualif` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `qualification`
--

INSERT INTO `qualification` (`CodeQualif`, `NomQualif`) VALUES
('INF', 'Infirmier'),
('MED', 'Medecin');

-- --------------------------------------------------------

--
-- Structure de la table `réaliser`
--

CREATE TABLE `réaliser` (
  `NoOrdonnance` int(11) NOT NULL,
  `NoOperation` int(11) NOT NULL,
  `ResultatNum` decimal(10,2) DEFAULT NULL,
  `ResultatTemps` time DEFAULT NULL,
  `ReultatTexte` text DEFAULT NULL,
  `Observations` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `réaliser`
--

INSERT INTO `réaliser` (`NoOrdonnance`, `NoOperation`, `ResultatNum`, `ResultatTemps`, `ReultatTexte`, `Observations`) VALUES
(163, 1, '50.00', NULL, NULL, NULL),
(163, 14, '115.00', NULL, NULL, NULL),
(163, 15, '15.00', NULL, NULL, NULL),
(163, 16, '51.00', NULL, NULL, NULL),
(163, 17, '84.00', NULL, NULL, NULL),
(163, 18, '28.00', NULL, NULL, NULL),
(163, 19, '15.00', NULL, NULL, NULL),
(163, 20, '15.00', NULL, NULL, NULL),
(163, 21, '15.00', NULL, NULL, NULL),
(163, 22, '10.00', NULL, NULL, NULL),
(163, 23, '51.00', NULL, NULL, NULL),
(163, 24, '51.00', NULL, NULL, NULL),
(163, 25, NULL, '07:00:25', NULL, NULL),
(163, 26, NULL, '21:00:00', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `type_personnel`
--

CREATE TABLE `type_personnel` (
  `Id_Type_Personnel` int(11) NOT NULL,
  `typeLibellé` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `type_personnel`
--

INSERT INTO `type_personnel` (`Id_Type_Personnel`, `typeLibellé`) VALUES
(1, 'Opérateur'),
(2, 'Administratif'),
(3, 'Administrateur');

-- --------------------------------------------------------

--
-- Structure de la table `type_prelevement`
--

CREATE TABLE `type_prelevement` (
  `CodeTypePrel` char(10) NOT NULL,
  `LibelleTypePrel` varchar(50) DEFAULT NULL,
  `CodeQualif` char(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `type_prelevement`
--

INSERT INTO `type_prelevement` (`CodeTypePrel`, `LibelleTypePrel`, `CodeQualif`) VALUES
('P5', 'Perfusion intraveineuse', 'MED'),
('PI', 'Perfusion intraveineuse', 'MED'),
('PI2', 'Perfusion intraveineuse', 'MED'),
('PI3', 'Perfusion intraveineuse', 'MED'),
('PI4', 'Perfusion intraveineuse', 'MED'),
('PI6', 'Perfusion intraveineuse', 'MED'),
('PI7', 'Perfusion intraveineuse', 'MED'),
('PI8', 'Perfusion intraveineuse', 'MED');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `analyses`
--
ALTER TABLE `analyses`
  ADD PRIMARY KEY (`CodeAnalyse`),
  ADD KEY `CodeTypePrel` (`CodeTypePrel`);

--
-- Index pour la table `chapitre`
--
ALTER TABLE `chapitre`
  ADD PRIMARY KEY (`Lettre`);

--
-- Index pour la table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`NoClient`);

--
-- Index pour la table `comporter`
--
ALTER TABLE `comporter`
  ADD PRIMARY KEY (`CodeAnalyse`,`NoOrdonnance`),
  ADD KEY `NoOrdonnance` (`NoOrdonnance`);

--
-- Index pour la table `demandeur`
--
ALTER TABLE `demandeur`
  ADD PRIMARY KEY (`NoDemandeur`);

--
-- Index pour la table `operation`
--
ALTER TABLE `operation`
  ADD PRIMARY KEY (`NoOperation`),
  ADD KEY `Lettre` (`Lettre`),
  ADD KEY `CodeAnalyse` (`CodeAnalyse`);

--
-- Index pour la table `ordonnance`
--
ALTER TABLE `ordonnance`
  ADD PRIMARY KEY (`NoOrdonnance`),
  ADD KEY `NoDemandeur` (`NoDemandeur`),
  ADD KEY `NoClient` (`NoClient`);

--
-- Index pour la table `personnel`
--
ALTER TABLE `personnel`
  ADD PRIMARY KEY (`Id_Personnel`),
  ADD KEY `Id_Type_Personnel` (`Id_Type_Personnel`);

--
-- Index pour la table `possède`
--
ALTER TABLE `possède`
  ADD PRIMARY KEY (`CodeQualif`,`Id_Personnel`),
  ADD KEY `Id_Personnel` (`Id_Personnel`);

--
-- Index pour la table `qualification`
--
ALTER TABLE `qualification`
  ADD PRIMARY KEY (`CodeQualif`);

--
-- Index pour la table `réaliser`
--
ALTER TABLE `réaliser`
  ADD PRIMARY KEY (`NoOrdonnance`,`NoOperation`),
  ADD KEY `NoOperation` (`NoOperation`);

--
-- Index pour la table `type_personnel`
--
ALTER TABLE `type_personnel`
  ADD PRIMARY KEY (`Id_Type_Personnel`);

--
-- Index pour la table `type_prelevement`
--
ALTER TABLE `type_prelevement`
  ADD PRIMARY KEY (`CodeTypePrel`),
  ADD KEY `CodeQualif` (`CodeQualif`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `client`
--
ALTER TABLE `client`
  MODIFY `NoClient` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `demandeur`
--
ALTER TABLE `demandeur`
  MODIFY `NoDemandeur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `operation`
--
ALTER TABLE `operation`
  MODIFY `NoOperation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT pour la table `ordonnance`
--
ALTER TABLE `ordonnance`
  MODIFY `NoOrdonnance` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=167;

--
-- AUTO_INCREMENT pour la table `personnel`
--
ALTER TABLE `personnel`
  MODIFY `Id_Personnel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=186;

--
-- AUTO_INCREMENT pour la table `type_personnel`
--
ALTER TABLE `type_personnel`
  MODIFY `Id_Type_Personnel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `analyses`
--
ALTER TABLE `analyses`
  ADD CONSTRAINT `analyses_ibfk_1` FOREIGN KEY (`CodeTypePrel`) REFERENCES `type_prelevement` (`CodeTypePrel`);

--
-- Contraintes pour la table `comporter`
--
ALTER TABLE `comporter`
  ADD CONSTRAINT `comporter_ibfk_1` FOREIGN KEY (`CodeAnalyse`) REFERENCES `analyses` (`CodeAnalyse`),
  ADD CONSTRAINT `comporter_ibfk_2` FOREIGN KEY (`NoOrdonnance`) REFERENCES `ordonnance` (`NoOrdonnance`);

--
-- Contraintes pour la table `operation`
--
ALTER TABLE `operation`
  ADD CONSTRAINT `operation_ibfk_1` FOREIGN KEY (`Lettre`) REFERENCES `chapitre` (`Lettre`),
  ADD CONSTRAINT `operation_ibfk_2` FOREIGN KEY (`CodeAnalyse`) REFERENCES `analyses` (`CodeAnalyse`);

--
-- Contraintes pour la table `ordonnance`
--
ALTER TABLE `ordonnance`
  ADD CONSTRAINT `ordonnance_ibfk_1` FOREIGN KEY (`NoDemandeur`) REFERENCES `demandeur` (`NoDemandeur`),
  ADD CONSTRAINT `ordonnance_ibfk_2` FOREIGN KEY (`NoClient`) REFERENCES `client` (`NoClient`);

--
-- Contraintes pour la table `personnel`
--
ALTER TABLE `personnel`
  ADD CONSTRAINT `personnel_ibfk_1` FOREIGN KEY (`Id_Type_Personnel`) REFERENCES `type_personnel` (`Id_Type_Personnel`);

--
-- Contraintes pour la table `possède`
--
ALTER TABLE `possède`
  ADD CONSTRAINT `possède_ibfk_1` FOREIGN KEY (`CodeQualif`) REFERENCES `qualification` (`CodeQualif`),
  ADD CONSTRAINT `possède_ibfk_2` FOREIGN KEY (`Id_Personnel`) REFERENCES `personnel` (`Id_Personnel`);

--
-- Contraintes pour la table `type_prelevement`
--
ALTER TABLE `type_prelevement`
  ADD CONSTRAINT `type_prelevement_ibfk_1` FOREIGN KEY (`CodeQualif`) REFERENCES `qualification` (`CodeQualif`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
