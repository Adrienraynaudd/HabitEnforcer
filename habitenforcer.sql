-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : lun. 21 nov. 2022 à 20:20
-- Version du serveur : 5.7.34
-- Version de PHP : 7.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `habitenforcer`
--

-- --------------------------------------------------------

--
-- Structure de la table `Groups`
--

CREATE TABLE `Groups` (
  `ID` varchar(255) NOT NULL COMMENT 'groupe ID',
  `Score` int(11) NOT NULL COMMENT 'groupe score',
  `Name` varchar(255) NOT NULL COMMENT 'group name',
  `GroupCreator` varchar(255) NOT NULL COMMENT 'groupe creator ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `Tasks`
--

CREATE TABLE `Tasks` (
  `ID` varchar(255) NOT NULL,
  `Name` varchar(255) NOT NULL COMMENT 'task name',
  `Description` varchar(255) NOT NULL COMMENT 'task descriptio,',
  `Difficulty` varchar(255) NOT NULL COMMENT 'difficulty (1: easy, 2: medium, 3:hard)',
  `Recurrence` varchar(255) NOT NULL COMMENT 'timespan (daily, weekly, monthly)',
  `LimitDate` datetime NOT NULL COMMENT 'creation date, use for time span calculation',
  `CreatorID` varchar(255) NOT NULL COMMENT 'author ID',
  `CategoryID` varchar(255) NOT NULL COMMENT 'category ID',
  `Complete` int(1) NOT NULL DEFAULT '0' COMMENT 'is task complete (0 == FALSE | 1 == TRUE)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `TasksCategories`
--

CREATE TABLE `TasksCategories` (
  `ID` varchar(255) NOT NULL COMMENT 'Category ID',
  `Name` varchar(255) NOT NULL COMMENT 'category name',
  `Color` varchar(255) NOT NULL COMMENT 'category color (format: #eeeee)',
  `CreatorID` varchar(255) NOT NULL COMMENT 'creator ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `Users`
--

CREATE TABLE `Users` (
  `ID` varchar(255) NOT NULL COMMENT 'User ID',
  `Name` varchar(255) NOT NULL COMMENT 'user username',
  `Password` varchar(255) NOT NULL COMMENT 'User hashed password',
  `Email` varchar(255) NOT NULL COMMENT 'user email',
  `GroupID` varchar(255) DEFAULT NULL COMMENT 'groupeID of users group',
  `LogDate` datetime DEFAULT NULL COMMENT 'last log date',
  `CanCreate` int(1) NOT NULL DEFAULT '1' COMMENT 'can create a new task (0 == FALSE, 1 == TRUE)',
  `confirmKey` varchar(255) DEFAULT NULL COMMENT 'confirmation key with email verif',
  `confirme` int(1) NOT NULL,
  `avatar` varchar(255) NOT NULL DEFAULT 'default.png',
  `Score` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `Groups`
--
ALTER TABLE `Groups`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `Tasks`
--
ALTER TABLE `Tasks`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Name` (`Name`),
  ADD KEY `AuthorID` (`CreatorID`),
  ADD KEY `CategoryID` (`CategoryID`);

--
-- Index pour la table `TasksCategories`
--
ALTER TABLE `TasksCategories`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `CreatorID` (`CreatorID`);

--
-- Index pour la table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Username` (`Name`),
  ADD KEY `GroupID` (`GroupID`);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `Tasks`
--
ALTER TABLE `Tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`CreatorID`) REFERENCES `Users` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `tasks_ibfk_2` FOREIGN KEY (`CategoryID`) REFERENCES `TasksCategories` (`ID`) ON DELETE CASCADE;

--
-- Contraintes pour la table `TasksCategories`
--
ALTER TABLE `TasksCategories`
  ADD CONSTRAINT `taskscategories_ibfk_1` FOREIGN KEY (`CreatorID`) REFERENCES `Users` (`ID`) ON DELETE CASCADE;

--
-- Contraintes pour la table `Users`
--
ALTER TABLE `Users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`GroupID`) REFERENCES `Groups` (`ID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
