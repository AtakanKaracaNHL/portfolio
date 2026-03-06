-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Gegenereerd op: 06 mrt 2026 om 11:21
-- Serverversie: 12.0.2-MariaDB-ubu2404
-- PHP-versie: 8.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `portfolio_db`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  `comment_text` text NOT NULL,
  `status` enum('open','done') NOT NULL DEFAULT 'open',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('submitted','approved','rejected') NOT NULL DEFAULT 'submitted',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `files`
--

INSERT INTO `files` (`id`, `module_id`, `title`, `description`, `status`, `created_at`) VALUES
(1, 2, 'Plannen & Organiseren', 'Opdracht 1', 'approved', '2026-01-19 15:50:05'),
(2, 2, 'Module Plannen & Organiseren opdracht 4', '', 'submitted', '2026-03-06 08:42:33'),
(3, 2, 'Module Plannen & Organisren', 'opdracht 7', 'submitted', '2026-03-06 08:43:43'),
(4, 2, 'Module Plannen & Organiseren', 'opdracht 8', 'submitted', '2026-03-06 08:46:37'),
(5, 2, 'Module samenwerken', 'opdracht 1', 'submitted', '2026-03-06 09:12:57'),
(6, 2, 'Module samenwerken', 'opdracht 3', 'submitted', '2026-03-06 09:13:21'),
(7, 2, 'Module samenwerken', 'opdracht 5', 'submitted', '2026-03-06 09:13:37'),
(8, 1, 'Module samenwerken', 'Opdracht 8', 'submitted', '2026-03-06 09:34:11'),
(9, 2, 'Module verken je mogelijkheden', 'opdracht 2', 'submitted', '2026-03-06 09:52:59'),
(10, 2, 'Module verken je mogelijkheden', 'opdracht 4', 'submitted', '2026-03-06 09:53:16'),
(11, 2, 'Module leren studeren', 'opdracht 2', 'submitted', '2026-03-06 09:53:42'),
(12, 2, 'Module leren studeren', 'opdracht 3', 'submitted', '2026-03-06 09:54:05'),
(13, 2, 'Module reflecteren', 'opdracht 1', 'submitted', '2026-03-06 09:54:27'),
(14, 2, 'Module reflecteren', 'opdracht 5', 'submitted', '2026-03-06 09:54:50'),
(15, 3, 'Vergadering Agenda', '', 'submitted', '2026-03-06 10:01:33'),
(16, 3, 'Notulen', '', 'submitted', '2026-03-06 10:03:44'),
(17, 4, 'Beoordelingsformulier Presentatie Web Development', '', 'submitted', '2026-03-06 10:06:33'),
(18, 4, 'Individuele presentatie', '', 'submitted', '2026-03-06 10:12:10'),
(19, 4, 'Groeps presentatie', '', 'submitted', '2026-03-06 10:14:04'),
(20, 7, 'Feedback', '', 'submitted', '2026-03-06 10:18:07'),
(21, 5, 'Feedback', '', 'submitted', '2026-03-06 10:41:53'),
(22, 6, 'Plan van Aanpak de Morgenster', '', 'submitted', '2026-03-06 10:48:16'),
(23, 6, 'Gantchart', '', 'submitted', '2026-03-06 10:48:34'),
(24, 6, 'Urenverantwoording', '', 'submitted', '2026-03-06 10:49:13'),
(25, 7, 'Feedback hele groep', '', 'submitted', '2026-03-06 10:55:57'),
(26, 1, 'Reflectieverslag', '', 'submitted', '2026-03-06 11:12:16');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `file_access`
--

CREATE TABLE `file_access` (
  `visitor_id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  `can_view` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `file_access`
--

INSERT INTO `file_access` (`visitor_id`, `file_id`, `can_view`) VALUES
(2, 1, 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `file_versions`
--

CREATE TABLE `file_versions` (
  `id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  `version_number` int(11) NOT NULL,
  `stored_name` varchar(255) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `mime` varchar(120) NOT NULL,
  `uploaded_by` int(11) NOT NULL,
  `uploaded_at` timestamp NULL DEFAULT current_timestamp(),
  `mime_type` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `file_versions`
--

INSERT INTO `file_versions` (`id`, `file_id`, `version_number`, `stored_name`, `original_name`, `mime`, `uploaded_by`, `uploaded_at`, `mime_type`) VALUES
(1, 1, 1, 'v1_807a829914216e3f0dd13d979fc49afd.pdf', 'Plannen & Organiseren Opdracht 1.pdf', 'application/pdf', 1, '2026-01-19 15:50:05', NULL),
(2, 2, 1, 'v1_b05681a4aad756c7b2282f0884d6e3f0.pdf', 'Plannen en Organiseren Opdracht 4.pdf', 'application/pdf', 1, '2026-03-06 08:42:33', NULL),
(3, 3, 1, 'v1_26d093637f4eaa50240ee99f8b9e70aa.pdf', 'Plannen Organiseren Opdracht 7.pdf', 'application/pdf', 1, '2026-03-06 08:43:43', NULL),
(5, 4, 1, 'v1_cda219f3ffb50e85afa2e7b46ccedecb.pdf', 'Plannen en Organiseren Opdracht 8.pdf', 'application/pdf', 1, '2026-03-06 09:07:17', NULL),
(6, 4, 2, 'v2_5e8af864cafce30e2bf1c51d1136ae9d.pdf', 'Plannen en Organiseren Opdracht 8.pdf', 'application/pdf', 1, '2026-03-06 09:07:44', NULL),
(7, 5, 1, 'v1_2213e94d65329316e0b588b8217f0889.pdf', 'Samenwerken Opdracht 1.pdf', 'application/pdf', 1, '2026-03-06 09:12:57', NULL),
(8, 6, 1, 'v1_96235414fe50c9642063d8011a42109e.pdf', 'Samenwerken Opdracht 3.pdf', 'application/pdf', 1, '2026-03-06 09:13:21', NULL),
(9, 7, 1, 'v1_f59c0caafb86824b42dde45b10271741.pdf', 'Samenwerken Opdracht 5.pdf', 'application/pdf', 1, '2026-03-06 09:13:37', NULL),
(10, 8, 1, 'v1_5c6a393e1de2e17f8f108be18cb49f3d.pdf', 'Samenwerken Opdracht 8.pdf', 'application/pdf', 1, '2026-03-06 09:34:11', NULL),
(11, 9, 1, 'v1_16394680024f1e4764e1ded53b1439a6.pdf', 'Verken je mogelijkheden Opdracht 2.pdf', 'application/pdf', 1, '2026-03-06 09:52:59', NULL),
(12, 10, 1, 'v1_835e3aafe44bdb7ad35cc09dec482b32.pdf', 'Verken je mogelijkheden Opdracht 4.pdf', 'application/pdf', 1, '2026-03-06 09:53:16', NULL),
(13, 11, 1, 'v1_371bbcbba7d1dc5b7afdec5ead1cabc7.pdf', 'Leren studeren Opdracht 2.pdf', 'application/pdf', 1, '2026-03-06 09:53:42', NULL),
(14, 12, 1, 'v1_01597037badbf979f04053c72e746353.pdf', 'Leren studeren Opdracht 3.pdf', 'application/pdf', 1, '2026-03-06 09:54:05', NULL),
(15, 13, 1, 'v1_78dca76c2c0daa361528855aac4c5469.pdf', 'Reflecteren Opdracht 1.pdf', 'application/pdf', 1, '2026-03-06 09:54:27', NULL),
(16, 14, 1, 'v1_e498aeddc8a7939fa13306873d058f1d.pdf', 'Reflecteren Opdracht 5.pdf', 'application/pdf', 1, '2026-03-06 09:54:50', NULL),
(17, 15, 1, 'v1_0622641180956522b96109ea181286e1.pdf', 'EMail Vergadering inplannen.pdf', 'application/pdf', 1, '2026-03-06 10:01:33', NULL),
(18, 16, 1, 'v1_ef5fb1f07d38d2759c09f6da08e418bc.pdf', 'Notulen-Plenair-INF-1A-7_Maart_2026.pdf', 'application/pdf', 1, '2026-03-06 10:03:44', NULL),
(19, 17, 1, 'v1_6c62f37f54062fddafe53dbb6a91f7f0.docx', 'Beoordelingsformulier Presenteren Nederlands 1A (1).docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 1, '2026-03-06 10:06:33', NULL),
(20, 17, 2, 'v2_dc9678c49a42da42e0fa594ef1c49219.pdf', 'Beoordelingsformulier Presenteren Nederlands 1A (1).pdf', 'application/pdf', 1, '2026-03-06 10:08:08', NULL),
(21, 18, 1, 'v1_d7657882d55534a93581d366b05b1bbd.pdf', 'Individuele presentatie.pdf', 'application/pdf', 1, '2026-03-06 10:12:10', NULL),
(22, 19, 1, 'v1_4337b768a48ee76b825f3b4ad51a52e4.pdf', 'Groeps presentatie.pdf', 'application/pdf', 1, '2026-03-06 10:14:04', NULL),
(23, 20, 1, 'v1_9cef8cadba1982970486076a10bf33cd.pdf', 'Feedback.pdf', 'application/pdf', 1, '2026-03-06 10:18:07', NULL),
(24, 21, 1, 'v1_b7088e1cab2f5b332389ca6c7a35f627.pdf', 'Feedback.pdf', 'application/pdf', 1, '2026-03-06 10:41:53', NULL),
(25, 22, 1, 'v1_0049ccceff64c506f9ff56deb7419b24.pdf', 'Plan van Aanpak (1).pdf', 'application/pdf', 1, '2026-03-06 10:48:16', NULL),
(26, 23, 1, 'v1_613bfaa98024c9c47f6d155e5c898963.pdf', 'Planning.pdf', 'application/pdf', 1, '2026-03-06 10:48:34', NULL),
(27, 24, 1, 'v1_e252e4c669b83c88cd81ef0d2a9e2fc2.pdf', 'Werk-Document.pdf', 'application/pdf', 1, '2026-03-06 10:49:13', NULL),
(28, 20, 2, 'v2_139cb7f6195ab3135401f193732f1567.pdf', 'Feedback Studenten.pdf', 'application/pdf', 1, '2026-03-06 10:55:32', NULL),
(29, 25, 1, 'v1_78f7d7262412d267640eebf938dc1c95.pdf', 'Feedback.pdf', 'application/pdf', 1, '2026-03-06 10:55:57', NULL),
(30, 26, 1, 'v1_7bb5c2a3b64c94d326d2c3b97a6b5ca2.pdf', 'Reflectieverslag Eerste half jaar.pdf', 'application/pdf', 1, '2026-03-06 11:12:16', NULL);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `modules`
--

CREATE TABLE `modules` (
  `id` int(11) NOT NULL,
  `year_id` tinyint(4) NOT NULL,
  `name` varchar(120) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `modules`
--

INSERT INTO `modules` (`id`, `year_id`, `name`, `description`, `created_at`) VALUES
(1, 1, 'Reflecteren', 'Edomundo opdracht Reflecteren', '2026-01-19 15:38:28'),
(2, 1, 'Edumundo', 'Alle opdrachten van Edumundo', '2026-01-19 15:40:32'),
(3, 1, 'Vergader technieken', '', '2026-03-06 09:55:25'),
(4, 1, 'Presenteren', '', '2026-03-06 09:55:40'),
(5, 1, 'Communiceren', '', '2026-03-06 09:55:50'),
(6, 1, 'Plan van aanpak', '', '2026-03-06 09:55:57'),
(7, 1, 'Feedback geven en krijgen', '', '2026-03-06 09:56:07');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','visitor') NOT NULL DEFAULT 'visitor',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password_hash`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$12$hULtkQa1bk/UW/LXgdG4FeHt.MK8cSDC.HH0EPvRrt7dPmUo8YOX6', 'admin', '2026-01-19 15:36:12'),
(2, 'test', '$2y$12$ptFQlT6u8qJfOC399P59M.hcWa4iipwZdDXxrkq6ioY4Wn70e5SEO', 'visitor', '2026-01-19 15:37:51');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `years`
--

CREATE TABLE `years` (
  `id` tinyint(4) NOT NULL,
  `label` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `years`
--

INSERT INTO `years` (`id`, `label`) VALUES
(1, 'Jaar 1'),
(2, 'Jaar 2'),
(3, 'Jaar 3'),
(4, 'Jaar 4');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `file_id` (`file_id`);

--
-- Indexen voor tabel `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `module_id` (`module_id`);

--
-- Indexen voor tabel `file_access`
--
ALTER TABLE `file_access`
  ADD PRIMARY KEY (`visitor_id`,`file_id`),
  ADD KEY `file_id` (`file_id`);

--
-- Indexen voor tabel `file_versions`
--
ALTER TABLE `file_versions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `file_id` (`file_id`,`version_number`),
  ADD KEY `uploaded_by` (`uploaded_by`);

--
-- Indexen voor tabel `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `year_id` (`year_id`);

--
-- Indexen voor tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexen voor tabel `years`
--
ALTER TABLE `years`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT voor een tabel `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT voor een tabel `file_versions`
--
ALTER TABLE `file_versions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT voor een tabel `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT voor een tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `files_ibfk_1` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `file_access`
--
ALTER TABLE `file_access`
  ADD CONSTRAINT `file_access_ibfk_1` FOREIGN KEY (`visitor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `file_access_ibfk_2` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `file_versions`
--
ALTER TABLE `file_versions`
  ADD CONSTRAINT `file_versions_ibfk_1` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `file_versions_ibfk_2` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`);

--
-- Beperkingen voor tabel `modules`
--
ALTER TABLE `modules`
  ADD CONSTRAINT `modules_ibfk_1` FOREIGN KEY (`year_id`) REFERENCES `years` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
