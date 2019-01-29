-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 29. Jan 2019 um 10:50
-- Server-Version: 10.1.34-MariaDB
-- PHP-Version: 7.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `mystable`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `start_event` datetime NOT NULL,
  `end_event` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `events`
--

INSERT INTO `events` (`id`, `title`, `start_event`, `end_event`) VALUES
(2, 'Nick Kupka', '2019-01-01 12:30:00', '2019-01-01 14:30:00'),
(3, 'Nick im Stall', '2019-01-29 15:00:00', '2019-01-29 16:00:00'),
(4, 'geil', '2019-01-29 08:00:00', '2019-01-29 09:00:00');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `passwort` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `LicenseKey` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `vorname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `nachname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `active` int(11) NOT NULL,
  `ExpiryDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`id`, `email`, `passwort`, `LicenseKey`, `vorname`, `nachname`, `created_at`, `updated_at`, `active`, `ExpiryDate`) VALUES
(35, 'asdasdsad', '$2y$10$I.uzpJ0w4PnhvZFDxXGHyenZAEt6VLgE4b/XL5NYXQ5HqWY0URzzm', 'P0OK-Q7SW-XFYQ-USDX', '', '', '2019-01-26 17:43:55', NULL, 0, '0000-00-00'),
(37, 'nick.kupka@gmail.comaaaa', '$2y$10$TpGWkBVc.2JHBzQAckO7sewfvQokZodc6JfJxsaE7lROFZprRK3zy', '25ZI-96VU-F105-ZQAE', '', '', '2019-01-26 17:45:10', NULL, 0, '0000-00-00'),
(38, 'nick.kupka@q9w8er793r7tggmail.com', '$2y$10$uiArc63keXwFg8VkkbaKauiSoy4NVRWnDimnCVTFg1X4Z3vVyj3B.', 'YZW8-B3Z8-HYMT-3FU9', '', '', '2019-01-26 17:46:28', NULL, 0, '0000-00-00'),
(39, 'nick.kupka@gmail.comasdasdas', '$2y$10$X/KulM8lFzJJItAS0/mvZ.ZiDLjRPfh61tlk2g/IZpMIr9VDnLpFK', 'RFB1-L2SK-TZWW-J46L', '', '', '2019-01-27 18:59:43', NULL, 0, '0000-00-00'),
(40, 'nick.kupka@gmail.comasdasdasd', '$2y$10$doTFCve6n3Zs3xJDk8K8suUW16hDELkX3pAFbPbEIg52DC6dMvyO6', 'XDA9-FHTT-K2VY-PGZT', '', '', '2019-01-28 10:19:49', NULL, 0, '0000-00-00'),
(41, '222222', '$2y$10$90umD9X.b72kU8NjtOHK0u/DeR5A2yXSJjK.TSJREu5S2036r.uza', 'YXM9-KTSV-FRPN-GO0N', '', '', '2019-01-28 10:37:15', NULL, 1, '0000-00-00'),
(42, 'nick.kupka@gmail.com', '$2y$10$is4WQtzLMltJZjP6UKguo.GTVguobXEJEVRVsFVbVlmix1IKmUuVi', '18ER-7DWF-S8L7-TUL0', 'Nick', 'Kupka', '2019-01-28 13:42:47', NULL, 1, '0000-00-00');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
