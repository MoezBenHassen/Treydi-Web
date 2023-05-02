-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 02 mai 2023 à 01:34
-- Version du serveur : 10.4.24-MariaDB
-- Version de PHP : 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `treydi_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

CREATE TABLE `article` (
  `id` int(11) NOT NULL,
  `id_categorie_id` int(11) DEFAULT NULL,
  `id_user_id` int(11) DEFAULT NULL,
  `auteur_id` int(11) DEFAULT NULL,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contenu` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_publication` date DEFAULT NULL,
  `archived` tinyint(1) DEFAULT NULL,
  `avg_rating` double DEFAULT NULL,
  `image_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_size` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`id`, `id_categorie_id`, `id_user_id`, `auteur_id`, `titre`, `description`, `contenu`, `date_publication`, `archived`, `avg_rating`, `image_name`, `image_size`, `updated_at`) VALUES
(2, 5, 7, 1, 'Dragon Ball Z: Budokai Tenkaichi 4 Release Date', 'The crowd roared as the trailer for Dragon Ball Z: Budokai Tenkaichi 4 played at the Dragon Ball Games Battle Hour 2023 yesterday.', 'The crowd roared as the trailer for Dragon Ball Z: Budokai Tenkaichi 4 played at the Dragon Ball Games Battle Hour 2023 yesterday. With there being many years passing since the previous installment, the crowd\'s reaction is justified for this incredibly beloved series.\r\n\r\nThe trailers show a seamless transition from the previous installment into this one, then showing Goku powering up into his Super Saiyan form, a feature of the combat portion of the game.\r\n\r\nThe games have players play as iconic Dragon Ball Z characters while fighting others, with animations reminiscent of the anime\'s style, and unique moves for each character, this game has solidified itself as a titan within the gaming industry.\r\n\r\nEven though the official trailer for the game dropped yesterday, there was no mention of a release date for the project. The trailer did not show any indication of a time window for players, with a note at the bottom of the screen saying footage is not final and the game is still in development.\r\n\r\n<blockquote>\r\n<p> this is what we were waiting for!</p>\r\n</blockquote>\r\n\r\nHype for this game is sure to continue for a long while, but hope is sure to be held for a release towards either the end of this year, or the beginning of next. However, only time will tell as to the validity of this vision.\r\n<center>\r\n\r\n<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/O5CzC1aFVAw\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe>\r\n</center>', '2023-04-09', 0, 2.25, 'dbz-6443f10079622502352592.jpg', 128413, '2023-04-22 16:36:48'),
(3, 5, 7, 1, 'Every Video Game Release Coming Soon For Nintendo Switch', 'The Nintendo Switch\'s library is vast, and it is constantly growing. Here are all the upcoming major Switch games and their release dates.', 'The Nintendo Switch has been a runaway success, proving that raw power is not the be-all and end-all for consoles. Through a combination of Nintendo\'s first-party games, a solid selection of triple-A third-party titles, and all the indie projects anyone could ever want, the Switch has amassed a stellar library that can match most platforms in terms of quality and quantity.\r\n\r\nThe Legend of Zelda: Breath of the Wild and Super Mario Odyssey are two of the greatest games of the last five years, but there is always a chance that the best Nintendo Switch game has yet to be released. 2021 produced Super Mario 3D World + Bowser\'s Fury, Monster Hunter Rise, NEO: The World Ends With You, The Legend of Zelda: Skyward Sword HD, Shin Megami Tensei 5, and Metroid Dread, and 2022 was decent as well\r\n\r\nHere\'s a look at all the titles we can expect to see on the Nintendo Switch in 2023 and beyond. Which big Nintendo Switch games have release dates? Please note the focus is on North American release dates.\r\n\r\nUpdated March 4, 2023: The upcoming Nintendo Switch games were added to the calendar over the last week: Vanaris Tactics, Vernal Edge, Strayed Lights, Disaster Detective Saiga: An Indescribable Mystery, Scrap Games, Puss in Boots: Interactive Book, Antigravity Racing, Island Cities, Mario Kart 8 Deluxe: Booster Course Pass - Wave 4, Mythology Waifus Mahjong, Titanium Hound, EvilUP, Life of Delta, Terminal Velocity: Boosted Edition, Tents & Trees, Link The Cubes, Post Void, Subway Midnight, Off The Tracks, Omen of Sorrow, Flashout 3, Gripper, Infinite Guitars, Anyaroth: The Queen\'s Tyranny, Orebody: Binder\'s Tale, Doodle World Deluxe, Assault Suits Valken Declassified, The Last Worker, Pretty Girls Tile Match, Alekon, Moe Waifu H, Trinity Trigger, Magical Drop 6, Neko Rescue Tale, Mugen Souls, Family Fun Night, Garden Simulator, Fitness Circuit.\r\n\r\nMarch 2023 has a couple of exciting games slated for release. ONI: Road to be the Mightiest Oni and DC Justice League: Cosmic Chaos could be fun picks for younger players, although fans of DC Comics might get a kick out of the latter as well.\r\n\r\nAfter more than a decade, Fatal Frame: Mask of the Lunar Eclipse is finally set for a Western release. Originally released in 2008, Koei Tecmo\'s horror game was a Nintendo Wii Japanese exclusive, and the title generally received positive reviews. Currently, the biggest Nintendo Switch exclusive of March 2023 is Bayonetta Origins: Cereza and the Lost Demon, a prequel centering around the titular Umbra Witch\'s early life and encounter with her first demon.\r\n\r\nMarch 1: BROK the InvestiGator (PS5, PS4, XBX/S, XBO, Switch)\r\nMarch 1: A Fox and His Robot (Switch, PC)\r\nMarch 1: Green Soldiers Heroes (Switch)\r\nMarch 1: Ken Follett\'s The Pillars of the Earth (Switch)\r\nMarch 1: Norn9: Var Commons (Switch)\r\nMarch 2: Aery - Calm Mind 3 (Switch)\r\nMarch 2: Bonfire Peaks: Lost Memories (Switch)\r\nMarch 2: Chess Pills (Switch)\r\nMarch 2: Dream Park Story (Switch)\r\nMarch 2: Fitness Boxing Fist of the North Star (Switch)\r\nMarch 2: Live Factory (Switch)\r\nMarch 2: Mario + Rabbids Sparks of Hope: Tower of Doooom (Switch)\r\nMarch 2: Mayhem in Single Valley (PS5, PS4, XBX/S, XBO, Switch, PC)\r\nMarch 2: Meg\'s Monster (Xbox One, Switch, PC)\r\nMarch 2: PowerWash Simulator: Midgar Special Pack (PS5, PS4, XBX/S, XBO, Switch, PC)\r\nMarch 2: Pretty Girls Breakers! PLUS (Switch)\r\nMarch 2: The Smile Alchemist (Switch, PC)\r\nMarch 2: Vanaris Tactics (XBO, Switch)\r\nMarch 3: The Atla Archives (Switch)\r\nMarch 3: Disaster Detective Saiga: An Indescribable Mystery (Switch)\r\nMarch 3: Gunman Tales (Switch)\r\nMarch 3: Puss in Boots: Interactive Book (Switch)\r\nMarch 3: Ro (Switch)\r\nMarch 3: Ruku\'s Heart Balloon (Switch)\r\nMarch 3: Scrap Games (Switch)\r\nMarch 3: Void Scrappers (Switch)\r\nMarch 6: Dead Cells: Return to Castlevania (PS4, XBO, Switch, PC)\r\nMarch 7: Dead by Daylight: Tools of Torment (PS5, PS4, XBX/S, XBO, Switch, PC)\r\nMarch 7: Little Witch Nobeta (PS4, Switch)\r\nMarch 7: Pronty: Fishy Adventure (Switch)\r\nMarch 9: Antigravity Racing (Switch)\r\nMarch 9: Cannon Dancer Osman (Switch)\r\nMarch 9: Caverns of Mars: Recharged (XBO, Switch, PC)\r\nMarch 9: Chippy & Noppo (Switch, PC)\r\nMarch 9: Fatal Frame: Mask of the Lunar Eclipse (PS5, PS4, XBX/S, XBO, Switch, PC)\r\nMarch 9: Figment 2: Creed Valley (PS5, PS4, XBX/S, XBO, Switch, PC)\r\nMarch 9: The Good Life - Behind the Secret of Rainy Woods (Switch)\r\nMarch 9: Know by Heart... (Switch)\r\nMarch 9: The Last Spell (PS5, PS4, Switch, PC)\r\nMarch 9: Ib (Switch)\r\nMarch 9: Island Cities (Switch)\r\nMarch 9: Mari and Bayu - The Road Home (Switch)\r\nMarch 9: Mario Kart 8 Deluxe: Booster Course Pass - Wave 4 (Switch)\r\nMarch 9: Mystic Gate (Switch, PC)\r\nMarch 9: ONI: Road to be the Mightiest Oni (Switch, PC)\r\nMarch 9: Paranormasight: The Seven Mysteries of Honjo (Switch, PC)\r\nMarch 9: Record of Agarest War (Switch)\r\nMarch 9: Tiny Troopers: Global Ops (PS5, PS4, XBX/S, XBO, Switch, PC)\r\nMarch 9: Zapling Bygone (XBX/S, XBO, Switch)\r\nMarch 10: DC Justice League: Cosmic Chaos (PS5, PS4, XBX/S, XBO, Switch, PC)\r\nMarch 10: EvilUP (Switch)\r\nMarch 10: Felix the Toy DX (Switch)\r\nMarch 10: Mato Anomalies (PS5, PS4, XBX/S, XBO, Switch, PC)\r\nMarch 10: Mythology Waifus Mahjong (Switch, PC)\r\nMarch 11: Titanium Hound (Switch)\r\nMarch 13: Life of Delta (Switch)\r\nMarch 14: The Legend of Heroes: Trails to Azure (PS4, Switch, PC)\r\nMarch 14: Tents & Trees (Switch)\r\nMarch 14: Terminal Velocity: Boosted Edition (Switch)\r\nMarch 14: Vernal Edge (PS5, PS4, XBX/S, XBO, Switch, PC)\r\nMarch 14: The Wreck (PS5, PS4, XBX/S, XBO, Switch, PC)\r\nMarch 15: Tricky Thief (PS5, PS4, XBX/S, XBO, Switch, PC)\r\nMarch 16: Alice Gear Aegis CS: Concerto of Simulatrix (PS5, PS4, Switch)\r\nMarch 16: Backbeat (Switch)\r\nMarch 16: Link The Cubes (Switch)\r\nMarch 16: Loot Box Simulator - Heroes of the Dark Age (Switch)\r\nMarch 16: Nono Adventure (Switch)\r\nMarch 16: Numolition (Switch)\r\nMarch 16: Post Void (PS5, PS4, Switch)\r\nMarch 16: Session: Skate Sim (Switch)\r\nMarch 16: Sixtar Gate: Startrail (Switch)\r\nMarch 16: Terracotta (Switch)\r\nMarch 17: Backbeat (XBX/S, XBO, Switch)\r\nMarch 17: Bayonetta Origins: Cereza and the Lost Demon (Switch)\r\nMarch 17: Flame Keeper (Switch)\r\nMarch 17: FUR Squadron (Switch, PC)\r\nMarch 17: Peppa Pig: World Adventures (PS5, PS4, XBX/S, XBO, Switch, PC)\r\nMarch 18: Fantasy Ball (Switch)\r\nMarch 20: Animals Names (Switch)\r\nMarch 20: FurryFury: Smash & Roll (Switch)\r\nMarch 21: Deceive Inc. (PS5, XBX/S, PC)\r\nMarch 21: Remnant: From the Ashes (Switch)\r\nMarch 22: Have a Nice Death (Switch, PC)\r\nMarch 23: Monorail Stories (Switch)\r\nMarch 23: Omen of Sorrow (PS5, Switch)\r\nMarch 23: Rakuen (Switch)\r\nMarch 23: The Settlers: New Allies (PS5, PS4, XBX/S, XBO, Switch)\r\nMarch 23: Storyteller (Switch, PC)\r\nMarch 23: Sushi Bar Express (Switch)\r\nMarch 24: Atelier Ryza 3: Alchemist of the End & the Secret Key (PS5, PS4, Switch, PC)\r\nMarch 24: Flashout 3 (Switch)\r\nMarch 24: Nefasto\'s Misadventure: Meeting Noeroze (Switch)\r\nMarch 28: Chef Life: A Restaurant Simulator (PS5, PS4, XBX/S, XBO, Switch, PC)\r\nMarch 28: Kana Quest (Switch)\r\nMarch 28: MLB The Show 23 (PS5, PS4, XBX/S, XBO, Switch)\r\nMarch 29: Gripper (Switch)\r\nMarch 29: RunBean Galactic (PS5, PS4, XBX/S, XBO, Switch)\r\nMarch 30: Anyaroth: The Queen\'s Tyranny (Switch, PC)\r\nMarch 30: Assault Suits Valken Declassified (Switch)\r\nMarch 30: Doodle World Deluxe (Switch)\r\nMarch 30: Dredge (PS5, PS4, XBX/S, XBO, Switch, PC)\r\nMarch 30: Guns N\' Runs (Switch)\r\nMarch 30: Infinite Guitars (XBO, Switch, PC)\r\nMarch 30: The Last Worker (PS5, XBX/S, Switch, PC)\r\nMarch 30: Lunark (PS5, PS4, XBX/S, XBO, Switch, PC)\r\nMarch 30: The Last Worker (Switch)\r\nMarch 30: Norn9: Var Commons (Switch)\r\nMarch 30: Orebody: Binder\'s Tale (Switch)\r\nMarch 30: Papertris (Switch)\r\nMarch 30: Saga of Sins (PS5, PS4, XBX/S, Switch, PC)\r\nMarch 31: Blade Assault (PS5, PS4, Switch)\r\nMarch 31: Formula Retro Racing: World Tour (Switch, PC)', '2023-04-18', 0, 0, 'h2x1-nintendodirect-genericlogo-6442cf2bba041255906070.jpg', 64979, '2023-04-21 20:00:11');

-- --------------------------------------------------------

--
-- Structure de la table `article_ratings`
--

CREATE TABLE `article_ratings` (
  `id` int(11) NOT NULL,
  `id_article_id` int(11) DEFAULT NULL,
  `id_user_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `authors`
--

CREATE TABLE `authors` (
  `id` int(11) NOT NULL,
  `auteur_id` int(11) DEFAULT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_de_naissance` date DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `archived` tinyint(1) DEFAULT NULL,
  `image_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_size` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `authors`
--

INSERT INTO `authors` (`id`, `auteur_id`, `full_name`, `date_de_naissance`, `description`, `archived`, `image_name`, `image_size`, `updated_at`) VALUES
(1, NULL, 'flen ben foulen', '2023-04-27', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec nibh nulla, sodales vitae efficitur in, posuere quis ligula. Mauris porta justo nec purus facilisis bibendum. Vivamus efficitur dolor ut elementum interdum. In sed magna molestie, tincidunt au', 0, 'screenshot-5-644d27ffcccba813272443.jpg', 68904, '2023-04-29 16:21:51');

-- --------------------------------------------------------

--
-- Structure de la table `categorie_article`
--

CREATE TABLE `categorie_article` (
  `id` int(11) NOT NULL,
  `libelle_cat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `archived` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categorie_article`
--

INSERT INTO `categorie_article` (`id`, `libelle_cat`, `archived`) VALUES
(1, 'News', 0),
(2, 'Updates', 0),
(3, 'Technology', 0),
(4, 'Anime', 0),
(5, 'Gaming', 0),
(6, 'Esports', 0),
(7, 'Game development', 0),
(8, 'Manga', 0),
(9, 'Cosplay', 0),
(10, 'Retro gaming', 0),
(11, 'Game design theory', 0),
(12, 'Streaming', 0),
(13, 'Technology', 0),
(14, 'Anime', 0),
(15, 'Gaming', 0),
(16, 'Esports', 0),
(17, 'Game development', 0),
(18, 'Manga', 0),
(19, 'Cosplay', 0),
(20, 'Retro gaming', 0),
(21, 'Game design theory', 0),
(22, 'Streaming', 0);

-- --------------------------------------------------------

--
-- Structure de la table `categorie_coupon`
--

CREATE TABLE `categorie_coupon` (
  `id` int(11) NOT NULL,
  `nom_categorie` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description_categorie` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `archived` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categorie_coupon`
--

INSERT INTO `categorie_coupon` (`id`, `nom_categorie`, `description_categorie`, `archived`) VALUES
(1, 'CouponCasual', 'Réduction sur une livraison', 0),
(2, 'CouponSpecial', 'Livraison Gratuite', 0),
(3, 'CouponExclusif', 'Carte de recharge gratuite', 1);

-- --------------------------------------------------------

--
-- Structure de la table `categorie_items`
--

CREATE TABLE `categorie_items` (
  `id` int(11) NOT NULL,
  `nom_categorie` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qt` int(11) NOT NULL,
  `archived` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categorie_items`
--

INSERT INTO `categorie_items` (`id`, `nom_categorie`, `qt`, `archived`) VALUES
(1, 'Console de Jeux', 4, 0),
(2, 'Peluche', 1, 0),
(3, 'Réparation PC', 1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `comment_items`
--

CREATE TABLE `comment_items` (
  `id` int(11) NOT NULL,
  `userid_id` int(11) NOT NULL,
  `itemid` int(11) NOT NULL,
  `comment` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `coupon`
--

CREATE TABLE `coupon` (
  `id` int(11) NOT NULL,
  `id_user_id` int(11) DEFAULT NULL,
  `id_categorie_id` int(11) DEFAULT NULL,
  `titre_coupon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description_coupon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_expiration` date DEFAULT NULL,
  `etat_coupon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `archived` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `coupon`
--

INSERT INTO `coupon` (`id`, `id_user_id`, `id_categorie_id`, `titre_coupon`, `description_coupon`, `date_expiration`, `etat_coupon`, `code`, `archived`) VALUES
(28, 13, 3, 'CouponExclusif', 'azezarzaer zaeraz ', '2023-04-30', NULL, NULL, 0),
(29, 13, 1, 'Coupon Mai Special', '100% off Delivery', '2021-05-31', 'VALID', 'SpecMaiCoupon1', NULL),
(30, 13, 2, 'Coupon Mai Special', '100% off Delivery', '2021-05-31', 'VALID', 'SpecMaiCoupon1', NULL),
(31, 13, 2, 'Coupon Mai Special', '100% off Delivery', '2021-05-31', 'VALID', 'SpecMaiCoupon1', NULL),
(32, 13, 3, 'Coupon Mai Exclusif', 'Carte de recharge gratuite!', '2021-05-31', 'VALID', 'ExcluMaiCoupon1', NULL),
(33, 13, 1, 'Coupon Mai Mensuel', '50% off Delivery', '2021-05-31', 'VALID', 'CasMaiCoupon1', NULL),
(34, 13, 2, 'Coupon Mai Special', '100% off Delivery', '2021-05-31', 'VALID', 'SpecMaiCoupon1', NULL),
(35, 13, 1, 'Coupon Mai Mensuel', '50% off Delivery', '2021-05-31', 'VALID', 'CasMaiCoupon1', NULL),
(36, 13, 1, 'Coupon Mai Mensuel', '50% off Delivery', '2021-05-31', 'VALID', 'CasMaiCoupon1', NULL),
(37, 13, 2, 'Coupon Mai Special', '100% off Delivery', '2021-05-31', 'VALID', 'SpecMaiCoupon1', NULL),
(38, 13, 2, 'Coupon Mai Special', '100% off Delivery', '2021-05-31', 'VALID', 'SpecMaiCoupon1', NULL),
(39, 13, 2, 'Coupon Mai Special', '100% off Delivery', '2021-05-31', 'VALID', 'SpecMaiCoupon1', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20230501223148', '2023-05-02 00:31:59', 9526);

-- --------------------------------------------------------

--
-- Structure de la table `echange`
--

CREATE TABLE `echange` (
  `id` int(11) NOT NULL,
  `id_user1_id` int(11) DEFAULT NULL,
  `id_user2_id` int(11) DEFAULT NULL,
  `date_echange` date DEFAULT NULL,
  `liv_etat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `titre_echange` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `archived` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `echange_proposer`
--

CREATE TABLE `echange_proposer` (
  `id` int(11) NOT NULL,
  `id_echange_id` int(11) NOT NULL,
  `id_user_id` int(11) NOT NULL,
  `date_proposer` date DEFAULT NULL,
  `archived` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `item`
--

CREATE TABLE `item` (
  `id` int(11) NOT NULL,
  `id_user_id` int(11) DEFAULT NULL,
  `id_categorie_id` int(11) DEFAULT NULL,
  `id_echange_id` int(11) DEFAULT NULL,
  `libelle` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `etat` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `imageurl` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `likes` int(11) DEFAULT NULL,
  `dislikes` int(11) DEFAULT NULL,
  `views` int(11) NOT NULL,
  `archived` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `like_items`
--

CREATE TABLE `like_items` (
  `itemid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `liked` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `livraison`
--

CREATE TABLE `livraison` (
  `id` int(11) NOT NULL,
  `id_livreur_id` int(11) DEFAULT NULL,
  `id_echange_id` int(11) DEFAULT NULL,
  `date_creation_livraison` date DEFAULT NULL,
  `etat_livraison` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse_livraison1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse_livraison2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_terminer_livraison` date DEFAULT NULL,
  `archived` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `messenger_messages`
--

INSERT INTO `messenger_messages` (`id`, `body`, `headers`, `queue_name`, `created_at`, `available_at`, `delivered_at`) VALUES
(1, 'O:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\":2:{s:44:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\\";a:1:{s:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\";a:1:{i:0;O:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\":1:{s:55:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\\";s:21:\\\"messenger.bus.default\\\";}}}s:45:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\\";O:51:\\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\\":2:{s:60:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\\";O:39:\\\"Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\TemplatedEmail\\\":4:{i:0;s:30:\\\"reset_password/email.html.twig\\\";i:1;N;i:2;a:1:{s:10:\\\"resetToken\\\";O:58:\\\"SymfonyCasts\\\\Bundle\\\\ResetPassword\\\\Model\\\\ResetPasswordToken\\\":4:{s:65:\\\"\\0SymfonyCasts\\\\Bundle\\\\ResetPassword\\\\Model\\\\ResetPasswordToken\\0token\\\";s:40:\\\"NfZfVCXc24vyqGgevdixiF5XCQojqfNe6YXtDZIQ\\\";s:69:\\\"\\0SymfonyCasts\\\\Bundle\\\\ResetPassword\\\\Model\\\\ResetPasswordToken\\0expiresAt\\\";O:17:\\\"DateTimeImmutable\\\":3:{s:4:\\\"date\\\";s:26:\\\"2023-05-02 01:50:04.784072\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:13:\\\"Europe/Berlin\\\";}s:71:\\\"\\0SymfonyCasts\\\\Bundle\\\\ResetPassword\\\\Model\\\\ResetPasswordToken\\0generatedAt\\\";i:1682981404;s:73:\\\"\\0SymfonyCasts\\\\Bundle\\\\ResetPassword\\\\Model\\\\ResetPasswordToken\\0transInterval\\\";i:0;}}i:3;a:6:{i:0;N;i:1;N;i:2;N;i:3;N;i:4;a:0:{}i:5;a:2:{i:0;O:37:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\\":2:{s:46:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\\";a:3:{s:4:\\\"from\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:4:\\\"From\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:26:\\\"treydiechange@no-reply.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:6:\\\"Treydi\\\";}}}}s:2:\\\"to\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:2:\\\"To\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:28:\\\"mohamedadem.torkhani@adem.tn\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:0:\\\"\\\";}}}}s:7:\\\"subject\\\";a:1:{i:0;O:48:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:7:\\\"Subject\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:55:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\\";s:27:\\\"Your password reset request\\\";}}}s:49:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\\";i:76;}i:1;N;}}}s:61:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\\";N;}}', '[]', 'default', '2023-05-02 00:50:04', '2023-05-02 00:50:04', NULL),
(2, 'O:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\":2:{s:44:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\\";a:1:{s:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\";a:1:{i:0;O:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\":1:{s:55:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\\";s:21:\\\"messenger.bus.default\\\";}}}s:45:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\\";O:51:\\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\\":2:{s:60:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\\";O:39:\\\"Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\TemplatedEmail\\\":4:{i:0;s:30:\\\"reset_password/email.html.twig\\\";i:1;N;i:2;a:1:{s:10:\\\"resetToken\\\";O:58:\\\"SymfonyCasts\\\\Bundle\\\\ResetPassword\\\\Model\\\\ResetPasswordToken\\\":4:{s:65:\\\"\\0SymfonyCasts\\\\Bundle\\\\ResetPassword\\\\Model\\\\ResetPasswordToken\\0token\\\";s:40:\\\"Ea3FXdxUHbDKDIFNysDrafJPNZdzRzktcizg7gO2\\\";s:69:\\\"\\0SymfonyCasts\\\\Bundle\\\\ResetPassword\\\\Model\\\\ResetPasswordToken\\0expiresAt\\\";O:17:\\\"DateTimeImmutable\\\":3:{s:4:\\\"date\\\";s:26:\\\"2023-05-02 01:51:54.109570\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:13:\\\"Europe/Berlin\\\";}s:71:\\\"\\0SymfonyCasts\\\\Bundle\\\\ResetPassword\\\\Model\\\\ResetPasswordToken\\0generatedAt\\\";i:1682981514;s:73:\\\"\\0SymfonyCasts\\\\Bundle\\\\ResetPassword\\\\Model\\\\ResetPasswordToken\\0transInterval\\\";i:0;}}i:3;a:6:{i:0;N;i:1;N;i:2;N;i:3;N;i:4;a:0:{}i:5;a:2:{i:0;O:37:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\\":2:{s:46:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\\";a:3:{s:4:\\\"from\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:4:\\\"From\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:26:\\\"treydiechange@no-reply.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:6:\\\"Treydi\\\";}}}}s:2:\\\"to\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:2:\\\"To\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:20:\\\"moezbh.mbh@gmail.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:0:\\\"\\\";}}}}s:7:\\\"subject\\\";a:1:{i:0;O:48:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:7:\\\"Subject\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:55:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\\";s:27:\\\"Your password reset request\\\";}}}s:49:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\\";i:76;}i:1;N;}}}s:61:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\\";N;}}', '[]', 'default', '2023-05-02 00:51:54', '2023-05-02 00:51:54', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `reclamation`
--

CREATE TABLE `reclamation` (
  `id` int(11) NOT NULL,
  `id_user_id` int(11) DEFAULT NULL,
  `titre_reclamation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description_reclamation` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `etat_reclamation` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_creation` date DEFAULT NULL,
  `date_cloture` date DEFAULT NULL,
  `archived` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `reclamation`
--

INSERT INTO `reclamation` (`id`, `id_user_id`, `titre_reclamation`, `description_reclamation`, `etat_reclamation`, `date_creation`, `date_cloture`, `archived`) VALUES
(1, 1, 'reclamation2', 'je ne peux pas accéder a mon compte', 'Traité', '2023-04-22', NULL, 0),
(2, 8, 'reclamation5', 'mot de passe oublier', 'en cours', '2023-04-22', NULL, 0),
(3, 1, 'reclamation4', 'Je n\'ai pas ressue mon coli', 'Traité', '2023-04-22', NULL, 0),
(4, 13, 'reclamation 7', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In in neque vitae nisi mattis pharetra. Etiam rhoncus turpis ac libero cursus vestibulum. Viv', 'en cours', '2023-04-24', NULL, 0),
(5, NULL, 'azerazeraz', 'azerazerazeraz', 'en cours', '2023-04-29', NULL, 0),
(6, 13, 'azerezrzaer', 'azerazerazerazerzaeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee', 'en cours', '2023-04-29', NULL, 0),
(7, 13, 'test', 'test', 'en cours', '2023-04-29', NULL, 0),
(8, 13, 'tez', 'tez', 'en cours', '2023-04-29', NULL, 0),
(9, 13, 'xez', 'xez', 'en cours', '2023-04-29', NULL, 0);

-- --------------------------------------------------------

--
-- Structure de la table `reponse`
--

CREATE TABLE `reponse` (
  `id` int(11) NOT NULL,
  `id_reclamation_id` int(11) DEFAULT NULL,
  `titre_reponse` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description_reponse` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_reponse` date DEFAULT NULL,
  `archived` tinyint(1) DEFAULT NULL,
  `avis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `reponse`
--

INSERT INTO `reponse` (`id`, `id_reclamation_id`, `titre_reponse`, `description_reponse`, `date_reponse`, `archived`, `avis`) VALUES
(1, 1, 'reponse 1', 'aaaaaaaaaaaaaaaaaaaaaa', '2023-04-22', 0, 'satisfait'),
(2, 3, 'reponse 3', 'qqqqqqqqqqqqqqqqqqqqq', '2023-04-22', 0, 'satisfait');

-- --------------------------------------------------------

--
-- Structure de la table `reset_password_request`
--

CREATE TABLE `reset_password_request` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `selector` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hashed_token` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `requested_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `expires_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `reset_password_request`
--

INSERT INTO `reset_password_request` (`id`, `user_id`, `selector`, `hashed_token`, `requested_at`, `expires_at`) VALUES
(1, 10, 'NfZfVCXc24vyqGgevdix', 'iTfe654v/AdMfJCqYTIYZOea1YlgqsB1Xg47NyhMyVI=', '2023-05-02 00:50:04', '2023-05-02 01:50:04'),
(2, 15, 'Ea3FXdxUHbDKDIFNysDr', 'L2mZ5KGP7vnrAlGjQKCLeqhl/elZO/kj/oJ0lNfnlPQ=', '2023-05-02 00:51:54', '2023-05-02 01:51:54');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id` int(11) NOT NULL,
  `google_authenticator_secret` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_size` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `score` int(11) DEFAULT NULL,
  `archived` tinyint(1) DEFAULT NULL,
  `date_naissance` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `google_authenticator_secret`, `email`, `roles`, `password`, `nom`, `prenom`, `adresse`, `avatar_url`, `image_size`, `updated_at`, `score`, `archived`, `date_naissance`) VALUES
(1, NULL, 'trader@trader.com', '[\"ROLE_TRADER\"]', '$2y$13$WOY6j9jyl03MGEPK/YJaCubZqw2BUCdLXgghW9uo2aW3WKAzfl.kC', 'adem', 'med', 'tunis', '/assets/img/avatars/1.png', NULL, NULL, 30, 0, '2000-04-05'),
(3, NULL, 'adem@adem.com', '[\"ROLE_ADMIN\"]', '$2y$13$9/wynMO9Pa0g8lyKVuU2yei7/yjIc0lHqB9HyJ/uyStgocB328906', 'adem', 'med', 'tunis', '/assets/img/team/breadcrumb_team.png', NULL, NULL, NULL, 0, '2000-01-18'),
(5, 'EKH6RNXT7MPEGLBRPJGYJUEZITWMTVLNUZBBAD74IMGZCPDVC7FQ', 'trader3@trader.com', '[\"ROLE_TRADER\"]', '$2y$13$6Wcy8VFHwwLvJ1JBrgtEn.ZMRbERnnCkG7WUMNXwKF3OGi71IiUda', 'med', 'torkhani', 'ariana', '/assets/img/team/breadcrumb_team.png', NULL, NULL, 500, 0, '2023-04-12'),
(6, 'N4KIMRTNKSWYTMVEIAF3YLZUMZYJJUQGMEPJJWPVIYXXCZD7YVAA', 'med@med.com', '[\"ROLE_TRADER\"]', '$2y$13$bu61bI2BKFW2bcCcCHzwYeV/VGvX3/w9nCfHXC5BV3ezE0htpID/O', 'adem', 'med', 'tunis', '/assets/img/team/breadcrumb_team.png', NULL, NULL, NULL, 0, '2023-04-11'),
(7, '5TT5427OYGDKOKUJ3CSNCYCMXM643WDKINCMUXZODUFPSWOHX4QQ', 'aaa@aaa.com', '[\"ROLE_TRADER\"]', '$2y$13$MVQzOrGmcmKKfYJY7462DOeMB1oyCtio8nfQUoJwB9mOfg5SV8YbS', 'eeee', 'rrrrr', 'tunis', '/assets/img/team/breadcrumb_team.png', NULL, NULL, NULL, 0, '2023-04-05'),
(8, 'TF4C3EPONSYWBCF3HQCZIX4LTYF4NB2G6RJFUTBOCFILCBFVZW5A', 'rrr@rrr.com', '[\"ROLE_TRADER\"]', '$2y$13$TUIthM7wQcdwu8dB0tBWBOu5HkmuvAVqNN1zO5HmBf5vi9M9gLAU6', 'fff', 'ggg', 'ggg', '/assets/img/avatars/1.png', NULL, NULL, 600, 0, '2023-04-12'),
(10, 'KGIJQYGNYDGKJDCFYRX445XFRJGUR7JTFZTNQT45OSENPI4YLL7Q', 'mohamedadem.torkhani@adem.tn', '[\"ROLE_LIVREUR\"]', '$2y$13$o.fdfbMQmojp7bPvaZyTbeQ76Je7YuplDsuGHKU.fcx6heBCxDe6e', 'Mohamed adem', 'Torkhani', 'tunis', NULL, NULL, NULL, NULL, 0, '2023-04-08'),
(12, 'BEZOFWZKOQ2W6RZXUQEVCANLFGOFE7MW3LDOYUBTCJCRMQPOP5VA', 'livreur@livreur.com', '[\"ROLE_LIVREUR\"]', '$2y$13$rqTwxIJNKu/0pHEyJRk8ReA/WQtsH48pE2RhpPIAhjvnvVFUDQpNC', 'medd', 'ademmm', 'ariana', NULL, NULL, NULL, NULL, 0, '2000-04-05'),
(13, '754ITRZ3N44QASFF5XITC4JLKWKM56RHNKF2U7JMWLDI7J4U3CRA', 'testrader@gmail.com', '[\"ROLE_TRADER\"]', '$2y$13$FH2/epJ2g7Z/TxXrKH6Lfuv7UwQAETcZqAkoYukMmyull2a/w1Gsi', 'testrader', 'testrader', 'testrader', '/assets/img/avatars/avatar4.png', NULL, NULL, 1000, 0, '2023-04-04'),
(14, 'NOMSXEM5TA4YZOD5XCEMIBYWV74UTLSKWX76O6WMAYUOTAZMORZQ', 'ademtrader@gmail.com', '[\"ROLE_TRADER\"]', '$2y$13$FlF3TD2ObDHq63CrxS3gQ.AYiHQ9l46XLJLtXSYoZLH/eNwtq2IUC', 'adem', 'adem', 'adem', NULL, NULL, NULL, NULL, 0, '2023-04-10'),
(15, 'YES7GC7MM6ZKVEAMGAZR2YUHSA2J3YNOBQAXYM2HSJ53RQUKWXWQ', 'moezbh.mbh@gmail.com', '[\"ROLE_TRADER\"]', '$2y$13$S3QQGj9yG36wkAK67hXBbuxZylBG84tpsQf5CgA.K/OcsSWvZfB2u', 'moez', 'moez', 'moez', NULL, NULL, NULL, NULL, 0, '2023-04-10');

-- --------------------------------------------------------

--
-- Structure de la table `view_items`
--

CREATE TABLE `view_items` (
  `itemid` int(11) NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_23A0E669F34925F` (`id_categorie_id`),
  ADD KEY `IDX_23A0E6679F37AE5` (`id_user_id`),
  ADD KEY `IDX_23A0E6660BB6FE6` (`auteur_id`);

--
-- Index pour la table `article_ratings`
--
ALTER TABLE `article_ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_2364437ED71E064B` (`id_article_id`),
  ADD KEY `IDX_2364437E79F37AE5` (`id_user_id`);

--
-- Index pour la table `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_8E0C2A5160BB6FE6` (`auteur_id`);

--
-- Index pour la table `categorie_article`
--
ALTER TABLE `categorie_article`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `categorie_coupon`
--
ALTER TABLE `categorie_coupon`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `categorie_items`
--
ALTER TABLE `categorie_items`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `comment_items`
--
ALTER TABLE `comment_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_488471BC58E0A285` (`userid_id`);

--
-- Index pour la table `coupon`
--
ALTER TABLE `coupon`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_64BF3F0279F37AE5` (`id_user_id`),
  ADD KEY `IDX_64BF3F029F34925F` (`id_categorie_id`);

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `echange`
--
ALTER TABLE `echange`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B577E3BF675C81E` (`id_user1_id`),
  ADD KEY `IDX_B577E3BF14C067F0` (`id_user2_id`);

--
-- Index pour la table `echange_proposer`
--
ALTER TABLE `echange_proposer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_F60C68D8B6FBB8CF` (`id_echange_id`),
  ADD KEY `IDX_F60C68D879F37AE5` (`id_user_id`);

--
-- Index pour la table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_1F1B251E79F37AE5` (`id_user_id`),
  ADD KEY `IDX_1F1B251E9F34925F` (`id_categorie_id`),
  ADD KEY `IDX_1F1B251EB6FBB8CF` (`id_echange_id`);

--
-- Index pour la table `like_items`
--
ALTER TABLE `like_items`
  ADD PRIMARY KEY (`itemid`,`userid`);

--
-- Index pour la table `livraison`
--
ALTER TABLE `livraison`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_A60C9F1F5DEEE7D6` (`id_livreur_id`),
  ADD KEY `IDX_A60C9F1FB6FBB8CF` (`id_echange_id`);

--
-- Index pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Index pour la table `reclamation`
--
ALTER TABLE `reclamation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_CE60640479F37AE5` (`id_user_id`);

--
-- Index pour la table `reponse`
--
ALTER TABLE `reponse`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_5FB6DEC7100D1FDF` (`id_reclamation_id`);

--
-- Index pour la table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_7CE748AA76ED395` (`user_id`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_1D1C63B3E7927C74` (`email`);

--
-- Index pour la table `view_items`
--
ALTER TABLE `view_items`
  ADD PRIMARY KEY (`itemid`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `article`
--
ALTER TABLE `article`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `article_ratings`
--
ALTER TABLE `article_ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `authors`
--
ALTER TABLE `authors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `categorie_article`
--
ALTER TABLE `categorie_article`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT pour la table `categorie_coupon`
--
ALTER TABLE `categorie_coupon`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `categorie_items`
--
ALTER TABLE `categorie_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `comment_items`
--
ALTER TABLE `comment_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `coupon`
--
ALTER TABLE `coupon`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT pour la table `echange`
--
ALTER TABLE `echange`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `echange_proposer`
--
ALTER TABLE `echange_proposer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `item`
--
ALTER TABLE `item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `livraison`
--
ALTER TABLE `livraison`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `reclamation`
--
ALTER TABLE `reclamation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `reponse`
--
ALTER TABLE `reponse`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `FK_23A0E6660BB6FE6` FOREIGN KEY (`auteur_id`) REFERENCES `authors` (`id`),
  ADD CONSTRAINT `FK_23A0E6679F37AE5` FOREIGN KEY (`id_user_id`) REFERENCES `utilisateur` (`id`),
  ADD CONSTRAINT `FK_23A0E669F34925F` FOREIGN KEY (`id_categorie_id`) REFERENCES `categorie_article` (`id`);

--
-- Contraintes pour la table `article_ratings`
--
ALTER TABLE `article_ratings`
  ADD CONSTRAINT `FK_2364437E79F37AE5` FOREIGN KEY (`id_user_id`) REFERENCES `utilisateur` (`id`),
  ADD CONSTRAINT `FK_2364437ED71E064B` FOREIGN KEY (`id_article_id`) REFERENCES `article` (`id`);

--
-- Contraintes pour la table `authors`
--
ALTER TABLE `authors`
  ADD CONSTRAINT `FK_8E0C2A5160BB6FE6` FOREIGN KEY (`auteur_id`) REFERENCES `authors` (`id`);

--
-- Contraintes pour la table `comment_items`
--
ALTER TABLE `comment_items`
  ADD CONSTRAINT `FK_488471BC58E0A285` FOREIGN KEY (`userid_id`) REFERENCES `utilisateur` (`id`);

--
-- Contraintes pour la table `coupon`
--
ALTER TABLE `coupon`
  ADD CONSTRAINT `FK_64BF3F0279F37AE5` FOREIGN KEY (`id_user_id`) REFERENCES `utilisateur` (`id`),
  ADD CONSTRAINT `FK_64BF3F029F34925F` FOREIGN KEY (`id_categorie_id`) REFERENCES `categorie_coupon` (`id`);

--
-- Contraintes pour la table `echange`
--
ALTER TABLE `echange`
  ADD CONSTRAINT `FK_B577E3BF14C067F0` FOREIGN KEY (`id_user2_id`) REFERENCES `utilisateur` (`id`),
  ADD CONSTRAINT `FK_B577E3BF675C81E` FOREIGN KEY (`id_user1_id`) REFERENCES `utilisateur` (`id`);

--
-- Contraintes pour la table `echange_proposer`
--
ALTER TABLE `echange_proposer`
  ADD CONSTRAINT `FK_F60C68D879F37AE5` FOREIGN KEY (`id_user_id`) REFERENCES `utilisateur` (`id`),
  ADD CONSTRAINT `FK_F60C68D8B6FBB8CF` FOREIGN KEY (`id_echange_id`) REFERENCES `echange` (`id`);

--
-- Contraintes pour la table `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `FK_1F1B251E79F37AE5` FOREIGN KEY (`id_user_id`) REFERENCES `utilisateur` (`id`),
  ADD CONSTRAINT `FK_1F1B251E9F34925F` FOREIGN KEY (`id_categorie_id`) REFERENCES `categorie_items` (`id`),
  ADD CONSTRAINT `FK_1F1B251EB6FBB8CF` FOREIGN KEY (`id_echange_id`) REFERENCES `echange` (`id`);

--
-- Contraintes pour la table `livraison`
--
ALTER TABLE `livraison`
  ADD CONSTRAINT `FK_A60C9F1F5DEEE7D6` FOREIGN KEY (`id_livreur_id`) REFERENCES `utilisateur` (`id`),
  ADD CONSTRAINT `FK_A60C9F1FB6FBB8CF` FOREIGN KEY (`id_echange_id`) REFERENCES `echange` (`id`);

--
-- Contraintes pour la table `reclamation`
--
ALTER TABLE `reclamation`
  ADD CONSTRAINT `FK_CE60640479F37AE5` FOREIGN KEY (`id_user_id`) REFERENCES `utilisateur` (`id`);

--
-- Contraintes pour la table `reponse`
--
ALTER TABLE `reponse`
  ADD CONSTRAINT `FK_5FB6DEC7100D1FDF` FOREIGN KEY (`id_reclamation_id`) REFERENCES `reclamation` (`id`);

--
-- Contraintes pour la table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  ADD CONSTRAINT `FK_7CE748AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `utilisateur` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
