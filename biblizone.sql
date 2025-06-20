-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 20 juin 2025 à 19:09
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
-- Base de données : `biblizone`
--

-- --------------------------------------------------------

--
-- Structure de la table `livres`
--

CREATE TABLE `livres` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `auteur` varchar(255) DEFAULT NULL,
  `prix` decimal(10,2) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `genre` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `date_publication` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `livres`
--

INSERT INTO `livres` (`id`, `titre`, `auteur`, `prix`, `image_url`, `genre`, `description`, `date_publication`) VALUES
(1, '1984', 'George Orwell', 8.99, '1984.png', 'Littérature & Fiction', '1984 est un roman dystopique de l\'écrivain britannique George Orwell. Publié le 8 juin 1949 il s\'agit du neuvième et dernier livre d\'Orwell achevé de son vivant. Thématiquement, il se concentre sur les conséquences du totalitarisme, de la surveillance de masse, et de l\'enrégimentement répressif des personnes et des comportements au sein de la société.Plus largement, le roman examine le rôle de la vérité et des faits au sein des sociétés et les manières dont ils peuvent être manipulés.\r\n\r\nL\'histoire se déroule dans un futur imaginaire. L\'année en cours est incertaine, mais on pense qu\'il s\'agit de 1984. Une grande partie du monde est en guerre perpétuelle (en). La Grande-Bretagne, désormais connue sous le nom d\'Airstrip One, est devenue une province du super-État (en) Océania, dirigé par Big Brother, un leader dictatorial soutenu par un culte de la personnalité intense orchestré par la police de la Pensée. Le Parti s\'engage dans une surveillance gouvernementale omniprésente et, par l\'intermédiaire du ministère de la vérité, dans un négationnisme historique et une propagande constante pour persécuter l\'individualité et la pensée indépendante. La liberté d\'expression n\'existe plus, tous les comportements sont minutieusement surveillés grâce à des machines appelées télécrans et d\'immenses affiches représentant le visage de Big Brother sont placardées dans les rues, avec l\'inscription « Big Brother vous regarde » (« Big Brother is watching you »).\r\n\r\nLe protagoniste, Winston Smith, est un employé assidu de niveau intermédiaire au ministère de la Vérité qui déteste secrètement le Parti et rêve de rébellion. Il tient un journal interdit et entame une relation sexuelle avec sa collègue Julia. Ils découvrent un groupe de résistance obscur appelé la Fraternité. Cependant, leur contact au sein de ce groupe s\'avère être un agent du Parti, et Smith et Julia sont arrêtés. Smith est soumis à des mois de manipulation psychologique et de torture de la part du ministère de l\'Amour. Il trahit finalement Julia et est libéré. Il prend conscience, dans les dernières pages du roman, qu\'il est « guéri » et qu’il aime Big Brother.', '1949-06-08'),
(2, 'Les Misérables', 'Victor Hugo', 12.50, 'les_miserables.png', 'Littérature & Fiction', 'Un chef-d\'œuvre du roman français.', '1862-03-30'),
(3, 'Madame Bovary', 'Gustave Flaubert', 7.99, 'madame_bovary.png', 'Littérature & Fiction', 'Un roman réaliste sur l\'ennui et l\'adultère.', '1856-12-15'),
(4, 'Moby Dick', 'Herman Melville', 10.25, 'moby_dick.png', 'Littérature & Fiction', 'Le roman du grand cachalot blanc.', '1851-10-18'),
(5, 'Persepolis', 'Marjane Satrapi', 15.00, 'persepolis.png', 'Jeunesse', 'Une bande dessinée autobiographique.', '2000-11-01'),
(6, 'L\'Étranger', 'Albert Camus', 6.99, 'l_etranger.png', 'Littérature & Fiction', 'Un roman philosophique existentaliste.', '1942-05-19'),
(7, 'Watchmen', 'Alan Moore', 18.99, 'watchmen.png', 'Jeunesse', 'Un roman graphique culte.', '1986-09-01'),
(8, 'Histoire de l\'Art', 'Ernst Gombrich', 25.99, 'histoire_de_l_art.png', 'Art, Culture & Société', 'Un panorama complet de l\'art.', '1950-01-01'),
(9, 'La Culture du Pauvre', 'Richard Hoggart', 14.50, 'la_culture_du_pauvre.png', 'Art, Culture & Société', 'Une étude sociologique.', '1957-01-01'),
(10, 'Annals 1', 'Platon', 9.99, 'annales1.png', 'Scolaire', 'Annales de philosophie.', '2023-09-01'),
(11, 'Annals 3', 'Aristote', 9.99, 'annales3.png', 'Scolaire', 'Annales de sciences.', '2023-09-01'),
(12, 'Annals Philo', 'Descartes', 9.99, 'annales_philo.png', 'Scolaire', 'Annales de philosophie.', '2023-09-01'),
(13, 'Chef d\'oeuvre inconnu', 'Honoré de Balzac', 5.50, 'chef_doeuvre_inconnu.png', 'Littérature & Fiction', 'Une nouvelle de Balzac.', '1831-08-11'),
(14, 'Château des étoiles', 'Alexandre Promethe', 12.00, 'chateau_des_etoiles.png', 'Jeunesse', 'Une BD d\'aventure steampunk.', '2014-05-20'),
(15, 'Petite Poucette', 'Michel Serres', 7.00, 'petite_poucette.png', 'Art, Culture & Société', 'Essai philosophique sur la nouvelle génération.', '2012-01-01'),
(16, 'La Société du spectacle', 'Guy Debord', 11.20, 'societe_du_spectacle.png', 'Art, Culture & Société', 'Un essai de critique sociale.', '1967-01-01'),
(17, 'Berserk', 'Kentaro Miura', 9.00, 'berserk.png', 'Jeunesse', 'Un manga de dark fantasy.', '1989-10-01'),
(18, 'L\'attaque des Titans', 'R. A. Lafferty', 6.00, 'adt.png', 'Jeunesse', 'Un recueil de nouvelles de science-fiction.', '1971-01-01');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `tel` int(13) NOT NULL,
  `password` varchar(255) NOT NULL,
  `access` int(1) DEFAULT 0,
  `subscription` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `name`, `email`, `tel`, `password`, `access`, `subscription`) VALUES
(1, 'Admin', 'The', 'admin@root.com', 1234567890, '$2y$10$O9z7tWAYOMB0o3SazZM/6.APvajTEE8RbWA4aoFlgKj.nrazqR.Hq', 1, 0),
(2, 'abder', 'dahabi', 'a@gmail.com', 641668299, '$2y$10$4alR/ghoIwwgNiBocn2Qou/0S/9MHG32oK/D/FyUUw6zYP8pA8a02', 0, 0),
(4, 'Beninho', 'Santos', 'benimarco127@gmail.com', 758975776, '$2y$10$O9qlNH8nyftwJndV8Y3jyu9qnz1DUefv8KuxqWQ0Wje7S/nCipIM6', 0, 0),
(7, 'Msd', 'Diallo', 'pede@woubi.com', 649530114, '$2y$10$EfCjRJYZ9wzuH2IdvPnZq.QAgiZHVmy6SzcLiY2uKfyZHOIHO59mS', 2, 0);

-- --------------------------------------------------------

--
-- Structure de la table `user_books`
--

CREATE TABLE `user_books` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `purchase_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user_books`
--

INSERT INTO `user_books` (`id`, `user_id`, `book_id`, `purchase_date`) VALUES
(1, 2, 1, '2025-06-19 19:45:08'),
(2, 2, 18, '2025-06-19 19:45:08'),
(5, 4, 18, '2025-06-20 10:41:43'),
(6, 4, 6, '2025-06-20 16:41:45'),
(7, 7, 18, '2025-06-20 16:48:41'),
(8, 7, 10, '2025-06-20 16:54:22'),
(9, 7, 11, '2025-06-20 16:54:30'),
(10, 7, 1, '2025-06-20 17:00:10'),
(11, 7, 17, '2025-06-20 17:00:23'),
(12, 7, 2, '2025-06-20 17:35:25'),
(13, 4, 16, '2025-06-20 17:51:55'),
(14, 4, 5, '2025-06-20 18:00:16');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `livres`
--
ALTER TABLE `livres`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `user_books`
--
ALTER TABLE `user_books`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`book_id`),
  ADD KEY `fk_user_books_book_id` (`book_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `livres`
--
ALTER TABLE `livres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `user_books`
--
ALTER TABLE `user_books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `user_books`
--
ALTER TABLE `user_books`
  ADD CONSTRAINT `fk_user_books_book_id` FOREIGN KEY (`book_id`) REFERENCES `livres` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user_books_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
