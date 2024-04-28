-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 28 avr. 2024 à 16:20
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `exo2`
--

-- --------------------------------------------------------

--
-- Structure de la table `book`
--

CREATE TABLE `book` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `book`
--

INSERT INTO `book` (`id`, `title`, `author`, `date`, `description`) VALUES
(25, 'Les Sables Mouvants', 'Marie-Louise Dubois', '2023-10-09', 'Une histoire émouvante de famille et de survie dans les déserts arides de l\'Afrique, mêlant aventure et introspection.'),
(26, 'L\'Énigme de l\'Horloge Brisée', 'Jacques Lemaire', '2022-01-06', 'Un roman policier captivant où un détective atypique résout un meurtre en démêlant les mystères d\'une horloge antique.'),
(27, 'Le Jardin des Âmes', 'Amélie Durand', '2014-01-28', 'Un conte fantastique sur l\'amour, le sacrifice et la magie des fleurs qui révèlent les secrets enfouis dans le cœur des hommes.'),
(28, 'Le Chant des Étoiles', 'Antoine Moreau', '2012-01-31', 'Une épopée intergalactique où l\'humanité lutte pour sa survie contre une force extraterrestre mystérieuse, avec des révélations bouleversantes.'),
(30, 'La Clé du Mystère', 'Sophie Blanchard', '2016-09-14', 'Un thriller psychologique haletant où les secrets d\'une petite ville tranquille sont déterrés, laissant les habitants confrontés à leurs propres démons.'),
(31, 'Au-Delà des Nuages', 'Lucie Martin', '2020-10-21', 'Un roman poétique sur le voyage intérieur d\'une jeune femme à travers les nuages, à la recherche de la vérité et de la rédemption.'),
(32, 'Les Ombres du Passé', 'Thomas Besson', '2018-07-05', 'Un drame historique bouleversant qui plonge au cœur de la Seconde Guerre mondiale, explorant les liens indéfectibles forgés dans l\'adversité.'),
(33, 'L\'Artiste et le Mécanicien', 'Camille Dupont', '2002-01-01', 'Une histoire d\'amour improbable entre deux univers opposés, l\'art et la mécanique, dans le Paris tumultueux du début du 20e siècle.'),
(34, 'Les Murmures de la Forêt', 'Julien Renaud', '2015-01-08', 'Un conte écologique où la nature prend la parole pour défendre sa propre existence face à la cupidité et à la destruction humaine.'),
(35, 'Le Souffle du Dragon', 'Éric Dubois', '2024-04-10', 'Une saga fantastique épique où un jeune héros doit affronter des dragons et des sorciers pour sauver son royaume de l\'ombre imminente.');

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20240322140944', '2024-03-22 15:09:53', 55),
('DoctrineMigrations\\Version20240322144823', '2024-03-22 15:48:30', 10);

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'Arthur', '1234');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `book`
--
ALTER TABLE `book`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
