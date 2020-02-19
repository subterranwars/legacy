-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Erstellungszeit: 18. Feb 2020 um 13:10
-- Server-Version: 5.7.29
-- PHP-Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `test`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `t_allianz`
--

CREATE TABLE `t_allianz` (
  `ID_Allianz` int(10) UNSIGNED NOT NULL,
  `Bezeichnung` varchar(50) NOT NULL DEFAULT '',
  `Kürzel` varchar(10) NOT NULL DEFAULT '',
  `Beschreibung` text NOT NULL,
  `Bild` varchar(50) NOT NULL DEFAULT '',
  `ID_Meta` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `t_allianzbewerbung`
--

CREATE TABLE `t_allianzbewerbung` (
  `ID_Allianz` int(11) NOT NULL DEFAULT '0',
  `ID_User` int(11) NOT NULL DEFAULT '0',
  `Text` text NOT NULL,
  `Datum` int(14) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `t_allianzpolitik`
--

CREATE TABLE `t_allianzpolitik` (
  `ID_Allianz` int(11) NOT NULL DEFAULT '0',
  `ID_AllianzPartner` int(11) NOT NULL DEFAULT '0',
  `Status` enum('verbündet','feind') NOT NULL DEFAULT 'verbündet'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `t_auftrag`
--

CREATE TABLE `t_auftrag` (
  `ID_Auftrag` int(10) UNSIGNED NOT NULL,
  `ID_User` int(11) NOT NULL DEFAULT '0',
  `FinishTime` int(11) NOT NULL DEFAULT '0',
  `ID_Kolonie` int(11) NOT NULL DEFAULT '0',
  `Kategory` enum('Gebäude','Forschung','Kolonieausbau','Einheiten','Forscher','Vorkommensuche') NOT NULL DEFAULT 'Gebäude'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `t_auftrageinheit`
--

CREATE TABLE `t_auftrageinheit` (
  `ID_Auftrag` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `ID_Bauplan` int(11) NOT NULL DEFAULT '0',
  `Anzahl` int(11) NOT NULL DEFAULT '0',
  `Fertig` int(11) NOT NULL DEFAULT '0',
  `Dauer` int(11) NOT NULL DEFAULT '0',
  `Typ` int(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `t_auftragforschung`
--

CREATE TABLE `t_auftragforschung` (
  `ID_Auftrag` int(11) NOT NULL DEFAULT '0',
  `Prozent` double NOT NULL DEFAULT '0',
  `ID_Forschung` int(11) NOT NULL DEFAULT '0',
  `StartTime` int(11) NOT NULL DEFAULT '0',
  `LastChange` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `t_auftraggebaeude`
--

CREATE TABLE `t_auftraggebaeude` (
  `ID_Auftrag` int(11) NOT NULL DEFAULT '0',
  `Prozent` double NOT NULL DEFAULT '0',
  `ID_Gebäude` int(11) NOT NULL DEFAULT '0',
  `StartTime` int(11) NOT NULL DEFAULT '0',
  `LastChange` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `t_auftragkolonie`
--

CREATE TABLE `t_auftragkolonie` (
  `ID_Auftrag` int(11) NOT NULL DEFAULT '0',
  `Prozent` double NOT NULL DEFAULT '0',
  `StartTime` int(11) NOT NULL DEFAULT '0',
  `LastChange` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `t_auftragvorkommensuche`
--

CREATE TABLE `t_auftragvorkommensuche` (
  `ID_Auftrag` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `ID_Rohstoff` int(11) NOT NULL DEFAULT '0',
  `Dauer` int(11) NOT NULL DEFAULT '0',
  `Erfolg` enum('ja','nein') NOT NULL DEFAULT 'nein',
  `FinishReal` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `t_bevoelkerung`
--

CREATE TABLE `t_bevoelkerung` (
  `ID_Bevölkerung` int(10) UNSIGNED NOT NULL,
  `Bevölkerung` double NOT NULL DEFAULT '0',
  `Wachstumsrate` double NOT NULL DEFAULT '0',
  `LastChange` int(10) NOT NULL DEFAULT '0',
  `ID_User` int(11) NOT NULL DEFAULT '0',
  `ID_Kolonie` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `t_bugs`
--

CREATE TABLE `t_bugs` (
  `ID_Bugs` int(10) UNSIGNED NOT NULL,
  `Titel` varchar(50) NOT NULL DEFAULT '',
  `Beschreibung` text NOT NULL,
  `Datum` int(11) NOT NULL DEFAULT '0',
  `Status` enum('nicht gelöst','gelöst','waiting') NOT NULL DEFAULT 'nicht gelöst',
  `ID_User` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `t_config`
--

CREATE TABLE `t_config` (
  `ID_Config` int(10) UNSIGNED NOT NULL,
  `Status` enum('online','offline') NOT NULL DEFAULT 'online',
  `Grund` longtext NOT NULL,
  `AnzahlRegUser` int(11) NOT NULL DEFAULT '0',
  `NewRegistration` enum('ja','nein') NOT NULL DEFAULT 'ja',
  `Logs` enum('ja','nein') NOT NULL DEFAULT 'ja',
  `Administrator` varchar(50) NOT NULL DEFAULT '',
  `AdminMail` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `t_config`
--

INSERT INTO `t_config` (`ID_Config`, `Status`, `Grund`, `AnzahlRegUser`, `NewRegistration`, `Logs`, `Administrator`, `AdminMail`) VALUES
(1, 'online', 'Es wird ein Punkteupdate durchgeführt<br>Bitte versuchen Sie es später noch einmal.', 200, 'ja', 'ja', 'subterranwars', 'support@subterranwars.de');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `t_einheit`
--

CREATE TABLE `t_einheit` (
  `ID_Einheit` int(10) UNSIGNED NOT NULL,
  `ID_Bauplan` int(11) NOT NULL DEFAULT '0',
  `Erfahrung` int(11) NOT NULL DEFAULT '0',
  `LebenProzent` double NOT NULL DEFAULT '0',
  `ID_User` int(11) NOT NULL DEFAULT '0',
  `ID_Kolonie` int(11) NOT NULL DEFAULT '0',
  `ID_Flotte` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `t_ereignis`
--

CREATE TABLE `t_ereignis` (
  `ID_Ereignis` int(10) UNSIGNED NOT NULL,
  `Titel` varchar(50) NOT NULL DEFAULT '',
  `Betreff` text NOT NULL,
  `Datum` int(11) NOT NULL DEFAULT '0',
  `Status` enum('neu','gelesen') NOT NULL DEFAULT 'neu',
  `ID_User` int(11) NOT NULL DEFAULT '0',
  `ID_Kolonie` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `t_fehler`
--

CREATE TABLE `t_fehler` (
  `ID_Fehler` int(10) UNSIGNED NOT NULL,
  `Nummer` int(11) NOT NULL DEFAULT '0',
  `Meldung` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `t_fehler`
--

INSERT INTO `t_fehler` (`ID_Fehler`, `Nummer`, `Meldung`) VALUES
(1, 4, 'Wegen Wartungsarbeiten oder ähnliches ist das Spiel derzeit leider nicht erreichbar.\r\n\r\nIch bitte um Ihr Verständnis'),
(2, 5, 'Zur Zeit sind keine weiteren Registrierungen möglich !'),
(3, 3, 'Der von Ihnen gewünschter Nickname ist bereits vorhanden, bitte wählen Sie einen anderen'),
(4, 6, 'Der von Ihnen gewälte Loginname ist bereits gewählt, bitte wählen Sie einen anderen !'),
(5, 2, 'Die von Ihnen gewählte Emailadresse ist leider nicht korrekt, bitte überprüfen Sie Ihre Eingabe !'),
(6, 7, 'Die von Ihnen gewählte Emailadresse ist leider schon vergeben - Hören Sie auf zu betrügen. Dieser Vorfall wurde vom System registriert und bei einem weiteren Verstoß wird der betroffende Account gesperrt. \r\nIch bitte um Verständnis'),
(7, 1, 'Die Passwörter stimmen nicht überein.'),
(8, 8, 'Der von Ihnen gewählte Benutzername oder das Passwort stimmen nicht !'),
(9, 9, 'Bei der Aktivierung Ihres Freischaltungscodes ist Etwas schief gelaufen. Bitte probieren Sie es erneut oder kontaktieren Sie einen Administrator.'),
(10, 100, 'Der Absender konnte nicht gefunden werden'),
(11, 101, 'Der Empfaenger konnte nicht gefunden werden'),
(12, 102, 'Sie können keine Nachrichten an sich selber schreiben'),
(13, 110, 'Sie sind leider nicht eingeloggt !'),
(14, 34, 'Beim Laden der Seite ist ein Fehler aufgetreten'),
(15, 103, 'Die von Ihnen gewählte Nachricht ist nicht vorhanden !'),
(16, 104, 'Sie müssen einen Text eingeben !'),
(17, 120, 'Das von Ihnen gewählte Bild ist kein .jpeg oder kein .gif. Bitte wählen Sie ein Bild, welches entweder vom Typ .jpeg oder .gif ist.'),
(18, 130, 'So viele Laster/Drohnen können Sie nicht zum Rohstoffe abholen schicken.'),
(19, 131, 'Sie können nicht so schnell nacheinander die Anzahl ihrer Laster/Drohnen pro Vorkommen ändern. Bitte warten Sie ein bisschen.'),
(20, 132, 'Sie suchen schon zu viele Vorkommen. Bitte warten Sie bis die anderen Suchen vollendet sind !'),
(21, 140, 'Sie bauen bereits !'),
(22, 150, 'Ihre Session ist abgelaufen.'),
(23, 10, 'Sie haben sich bereits registriert!'),
(24, 200, 'Ihr Account ist noch nicht freigeschaltet.\r\nBitte überprüfen Sie ihre Emails und befolgen Sie die Anweisungen.'),
(25, 201, 'Ihr Account ist gesperrt!'),
(26, 300, 'Sie müssen einen Titel angeben'),
(27, 301, 'Sie müssen eine Beschreibung eingeben'),
(28, 400, 'Beim Ausloggen ist ein Fehler aufgetreten!<br>\r\n<a href=\"spiel.php\">Klicken Sie hier, um zum Spiel zurückzukehren und den Vorgang zu wiederholen!</a>'),
(29, 11, 'Sie müssen einen Loginnamen angeben!'),
(30, 12, 'Das alte Passwort ist falsch!\r\n<br>Sie werden nun ausgeloggt!'),
(31, 13, 'Das neue Passwort entspricht nicht der Mindeslänge!'),
(32, 500, 'Ihr Passwort konnte <b>nicht</b> zugestellt werden!'),
(33, 141, 'Sie besitzen dieses Gebäude nicht!\r\nSie müssen es erst bauen, bevor Sie dessen Funktion nutzen können!'),
(34, 14, 'Sie müssen eine Rasse wählen :-/'),
(35, 142, 'Sie können sich dieses Gebäude leider nicht leisten!'),
(36, 601, 'Der Minimalwert und der Maximalwert sind zu weit auseinander.\r\nBitte korrigieren Sie die Eingabe!'),
(37, 600, 'So viele Skillpunkte besitzen Sie nicht.\r\nBitte anders verteilen!'),
(38, 700, 'Die Auslastung muss mindestens 5% betragen. Eine geringere Auslastung ist nicht gültig!'),
(39, 701, 'Eine Auslastung größer 100% ist nicht zulässig.'),
(40, 800, 'Sie bilden bereits einen Forscher aus.'),
(41, 801, 'Sie können nicht die komplette Bevölkerung zu Forschern umfunktionieren.'),
(42, 802, 'Sie besitzen keine Forscher. Gehen Sie in die Forschungszentrale und bilden Sie welche aus. Erst wenn mindestens ein Forscher ausgebildet wurde, ist es möglich Forschung zu betreiben'),
(43, 900, 'Sie erfüllen die Requirements nicht.'),
(44, 803, 'Sie forschen bereits!'),
(45, 804, 'Sie können sich diese Forschung leider nicht leisten.'),
(46, 151, 'Ihre IP-Adresse scheint sich geändert zu haben. Dies kann mehrere Gründe haben. Zum Beispiel, wenn sich jemand anderes in Ihren Account eingeloggt hat, oder Sie sich erneut ins Internet eingewählt haben.'),
(47, 920, 'Sie haben kein Profilnamen gewählt.'),
(48, 921, 'Sie haben keinen Antrieb gewählt.'),
(49, 922, 'Ihre zulässige Zuladung und Leistung wird überschritten.'),
(50, 923, 'Der von Ihnen gewählte Munitionstyp passt nich in die Waffe.'),
(51, 924, 'Einige Ihrer gewählten Teile passen nicht in das vorgesehen Chassis.'),
(52, 925, 'Sie haben kein Chassis gewählt.'),
(53, 926, 'Sie müssen eine Waffe, die dazugehörige Munition und eine Panzerung auswählen.'),
(54, 950, 'Um Truppenverbände zu erstellen oder einsehen zu können muss entweder eine Kaserne oder Waffenfabrik vorhanden sein.'),
(55, 951, 'Die Truppe benötigt eine Bezeichnung.'),
(56, 952, 'Um einen Truppenverband zu erstellen muss diesem mindestens eine Einheit zugewisen werden.'),
(57, 927, 'Der Bauplan kann nicht gelöscht werden, da anhand dieses Planes entweder Einheiten ausgebildet werden oder noch Einheiten vorhanden sind.'),
(58, 970, 'Der Abfahrts- und Ankunftsort sind identisch.'),
(59, 971, 'Der Zielort ist nicht bewohnt. Eine unbewohnte Kolonie kann mit den gewählten Missionsparametern nicht angesteuert werden.'),
(60, 972, 'Der Zielort ist bewohnt. Eine bewohnte Kolonie kann nicht kolonisiert werden.'),
(61, 953, 'Die gewählte Truppe gehört nicht Ihnen.'),
(62, 973, 'Die gewählten Zielkoordinaten gibt es nicht.'),
(63, 974, 'Sie haben die Rohstoffprioritäten falsch gesetzt.'),
(64, 976, 'Die Zielkolonie gehört nicht Ihnen. Truppen können nicht verlegt werden.'),
(65, 977, 'Sie müssen einen Missionstypen wählen.'),
(66, 978, 'Die gewählte Truppe ist bereits einer Mission zugeordnet und kann erst nach Abschluss neu koordiniert werden.'),
(67, 928, 'Sie erfüllen die Requirements nicht!'),
(68, 929, 'Keine Chassis vorhanden. Um einen Bauplan erstellen zu können muss mindestens ein Chassis vorhanden sein. Bitte forschen Sie nach den entsprechenden Teilen.'),
(69, 930, 'Es ist kein Antrieb vorhanden. Forschen Sie bitte, um welche zu bekommen.'),
(70, 931, 'Sie haben keine Waffen erforscht. Ohne Waffen können Sie jedoch keinen Bauplan erstellen.'),
(71, 932, 'Sie haben keine Panzerung erforscht. Ohne Panzerung ist es jedoch nicht möglich einen Bauplan erfolgreich zu erstellen.'),
(72, 933, 'Sie besitzen keine Munition für den ausgewählten Waffentyp. Bitte erforschen Sie Munition um die aufgeführte Waffe zu verwenden.'),
(74, 133, 'Sie haben noch nicht den erforderlichen Wissensstand, um nach dem ausgewählten Rohstoff zu suchen.'),
(75, 134, 'Ihre Eingabe war nicht korrekt. Sie müssen eine Dauer (in Stunden) wählen, die Sie nach dem gewünschten Rohstoff suchen möchten.'),
(76, 979, 'Die maximal zulässige Zuladung wird überschritten.'),
(77, 15, 'Sie müssen die Nutzungsbedingungen akzeptieren um an Subterranwars teilzunehmen.'),
(78, 16, 'Sie haben kein Passwort gewählt.'),
(79, 17, 'Sie müssen einen Nickname wählen.'),
(80, 18, 'Sie müssen eine Emailadresse angeben.'),
(81, 934, 'Das gewählte Profil gehört nicht Ihnen.'),
(82, 935, 'Sie haben kein Profil ausgewählt'),
(84, 1000, 'Der von Ihnen angeforderte Kampfbericht existiert leider nicht.'),
(85, 805, 'Sie haben nicht genügend Nahrung um einen Forscher auszubilden.');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `t_flotte`
--

CREATE TABLE `t_flotte` (
  `ID_Flotte` int(10) UNSIGNED NOT NULL,
  `Bezeichnung` varchar(50) NOT NULL DEFAULT '',
  `ID_User` int(11) NOT NULL DEFAULT '0',
  `ID_Kolonie` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `t_general`
--

CREATE TABLE `t_general` (
  `ID_General` int(10) UNSIGNED NOT NULL,
  `Angriffsbonus` int(11) NOT NULL DEFAULT '0',
  `Verteidigungsbonus` int(11) NOT NULL DEFAULT '0',
  `Geschwindigkeitsbonus` int(11) NOT NULL DEFAULT '0',
  `Zielenbonus` int(11) NOT NULL DEFAULT '0',
  `Wendigkeitsbonus` int(11) NOT NULL DEFAULT '0',
  `Rohstoffproduktionsoptimierung` int(11) NOT NULL DEFAULT '0',
  `Forschungsvorteil` int(11) NOT NULL DEFAULT '0',
  `SkillpointsLeft` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `t_icons`
--

CREATE TABLE `t_icons` (
  `ID_Icons` int(10) UNSIGNED NOT NULL,
  `Kürzel` varchar(50) NOT NULL DEFAULT '',
  `Replacement` varchar(255) NOT NULL DEFAULT '',
  `Category` enum('smiley','betreff','code') NOT NULL DEFAULT 'smiley'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `t_kolonie`
--

CREATE TABLE `t_kolonie` (
  `ID_Kolonie` int(10) UNSIGNED NOT NULL,
  `Bezeichnung` varchar(50) NOT NULL DEFAULT '',
  `Status` int(1) NOT NULL DEFAULT '0',
  `ID_User` int(11) NOT NULL DEFAULT '0',
  `ID_Koordinaten` int(11) NOT NULL DEFAULT '0',
  `Energieniveau` enum('normal','critical') NOT NULL DEFAULT 'normal',
  `Hauptquartier` enum('ja','nein') NOT NULL DEFAULT 'ja',
  `GebäudePunkte` int(11) NOT NULL DEFAULT '0',
  `MilitärPunkte` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `t_koordinaten`
--

CREATE TABLE `t_koordinaten` (
  `ID_Koordinaten` int(15) UNSIGNED NOT NULL,
  `X` int(3) NOT NULL DEFAULT '0',
  `Y` int(3) NOT NULL DEFAULT '0',
  `Z` int(3) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `t_koordinaten`
--

INSERT INTO `t_koordinaten` (`ID_Koordinaten`, `X`, `Y`, `Z`) VALUES
(1, 1, 1, 1),
(2, 1, 1, 2),
(3, 1, 1, 3),
(4, 1, 1, 4),
(5, 1, 1, 5),
(6, 1, 2, 1),
(7, 1, 2, 2),
(8, 1, 2, 3),
(9, 1, 3, 1),
(10, 1, 3, 2),
(11, 1, 3, 3),
(12, 1, 3, 4),
(13, 1, 3, 5),
(14, 1, 3, 6),
(15, 1, 3, 7),
(16, 1, 4, 1),
(17, 1, 4, 2),
(18, 1, 4, 3),
(19, 1, 5, 1),
(20, 1, 5, 2),
(21, 1, 5, 3),
(22, 1, 6, 1),
(23, 1, 6, 2),
(24, 1, 6, 3),
(25, 1, 6, 4),
(26, 1, 7, 1),
(27, 1, 7, 2),
(28, 1, 7, 3),
(29, 1, 7, 4),
(30, 1, 7, 5),
(31, 1, 7, 6),
(32, 1, 7, 7),
(33, 1, 7, 8),
(34, 1, 7, 9),
(35, 1, 8, 1),
(36, 1, 8, 2),
(37, 1, 8, 3),
(38, 1, 8, 4),
(39, 1, 9, 1),
(40, 1, 9, 2),
(41, 1, 9, 3),
(42, 1, 9, 4),
(43, 1, 9, 5),
(44, 2, 1, 1),
(45, 2, 1, 2),
(46, 2, 1, 3),
(47, 2, 1, 4),
(48, 2, 2, 1),
(49, 2, 2, 2),
(50, 2, 3, 1),
(51, 2, 3, 2),
(52, 2, 3, 3),
(53, 2, 3, 4),
(54, 2, 3, 5),
(55, 2, 4, 1),
(56, 2, 4, 2),
(57, 2, 4, 3),
(58, 2, 4, 4),
(59, 2, 4, 5),
(60, 2, 4, 6),
(61, 2, 5, 1),
(62, 2, 5, 2),
(63, 2, 5, 3),
(64, 2, 5, 4),
(65, 2, 6, 1),
(66, 2, 6, 2),
(67, 2, 7, 1),
(68, 2, 7, 2),
(69, 2, 7, 3),
(70, 2, 7, 4),
(71, 2, 7, 5),
(72, 2, 8, 1),
(73, 2, 8, 2),
(74, 2, 8, 3),
(75, 2, 9, 1),
(76, 2, 9, 2),
(77, 2, 9, 3),
(78, 2, 9, 4),
(79, 2, 9, 5),
(80, 3, 1, 1),
(81, 3, 1, 2),
(82, 3, 1, 3),
(83, 3, 2, 1),
(84, 3, 2, 2),
(85, 3, 2, 3),
(86, 3, 2, 4),
(87, 3, 2, 5),
(88, 3, 2, 6),
(89, 3, 3, 1),
(90, 3, 3, 2),
(91, 3, 3, 3),
(92, 3, 3, 4),
(93, 3, 3, 5),
(94, 3, 3, 6),
(95, 3, 4, 1),
(96, 3, 4, 2),
(97, 3, 4, 3),
(98, 3, 4, 4),
(99, 3, 4, 5),
(100, 3, 4, 6),
(101, 3, 5, 1),
(102, 3, 5, 2),
(103, 3, 5, 3),
(104, 3, 5, 4),
(105, 3, 5, 5),
(106, 3, 5, 6),
(107, 3, 5, 7),
(108, 3, 5, 8),
(109, 3, 6, 1),
(110, 3, 6, 2),
(111, 3, 6, 3),
(112, 3, 6, 4),
(113, 3, 6, 5),
(114, 3, 6, 6),
(115, 3, 7, 1),
(116, 3, 7, 2),
(117, 3, 8, 1),
(118, 3, 8, 2),
(119, 3, 8, 3),
(120, 3, 8, 4),
(121, 3, 8, 5),
(122, 3, 8, 6),
(123, 3, 8, 7),
(124, 3, 8, 8),
(125, 3, 8, 9),
(126, 3, 9, 1),
(127, 3, 9, 2),
(128, 3, 9, 3),
(129, 4, 1, 1),
(130, 4, 1, 2),
(131, 4, 1, 3),
(132, 4, 1, 4),
(133, 4, 2, 1),
(134, 4, 2, 2),
(135, 4, 2, 3),
(136, 4, 2, 4),
(137, 4, 2, 5),
(138, 4, 3, 1),
(139, 4, 3, 2),
(140, 4, 3, 3),
(141, 4, 3, 4),
(142, 4, 3, 5),
(143, 4, 3, 6),
(144, 4, 4, 1),
(145, 4, 4, 2),
(146, 4, 4, 3),
(147, 4, 5, 1),
(148, 4, 5, 2),
(149, 4, 5, 3),
(150, 4, 6, 1),
(151, 4, 6, 2),
(152, 4, 7, 1),
(153, 4, 7, 2),
(154, 4, 7, 3),
(155, 4, 7, 4),
(156, 4, 7, 5),
(157, 4, 7, 6),
(158, 4, 8, 1),
(159, 4, 8, 2),
(160, 4, 9, 1),
(161, 4, 9, 2),
(162, 4, 9, 3),
(163, 4, 9, 4),
(164, 4, 9, 5),
(165, 4, 9, 6),
(166, 5, 1, 1),
(167, 5, 1, 2),
(168, 5, 1, 3),
(169, 5, 2, 1),
(170, 5, 2, 2),
(171, 5, 2, 3),
(172, 5, 2, 4),
(173, 5, 2, 5),
(174, 5, 3, 1),
(175, 5, 3, 2),
(176, 5, 4, 1),
(177, 5, 4, 2),
(178, 5, 5, 1),
(179, 5, 5, 2),
(180, 5, 5, 3),
(181, 5, 5, 4),
(182, 5, 5, 5),
(183, 5, 5, 6),
(184, 5, 5, 7),
(185, 5, 6, 1),
(186, 5, 6, 2),
(187, 5, 6, 3),
(188, 5, 6, 4),
(189, 5, 6, 5),
(190, 5, 6, 6),
(191, 5, 6, 7),
(192, 5, 6, 8),
(193, 5, 7, 1),
(194, 5, 7, 2),
(195, 5, 7, 3),
(196, 5, 8, 1),
(197, 5, 8, 2),
(198, 5, 8, 3),
(199, 5, 8, 4),
(200, 5, 8, 5),
(201, 5, 9, 1),
(202, 5, 9, 2),
(203, 5, 9, 3),
(204, 5, 9, 4),
(205, 5, 9, 5),
(206, 5, 9, 6),
(207, 6, 1, 1),
(208, 6, 1, 2),
(209, 6, 1, 3),
(210, 6, 1, 4),
(211, 6, 1, 5),
(212, 6, 1, 6),
(213, 6, 2, 1),
(214, 6, 2, 2),
(215, 6, 2, 3),
(216, 6, 2, 4),
(217, 6, 2, 5),
(218, 6, 2, 6),
(219, 6, 3, 1),
(220, 6, 3, 2),
(221, 6, 3, 3),
(222, 6, 3, 4),
(223, 6, 3, 5),
(224, 6, 4, 1),
(225, 6, 4, 2),
(226, 6, 4, 3),
(227, 6, 4, 4),
(228, 6, 4, 5),
(229, 6, 5, 1),
(230, 6, 5, 2),
(231, 6, 5, 3),
(232, 6, 5, 4),
(233, 6, 5, 5),
(234, 6, 5, 6),
(235, 6, 6, 1),
(236, 6, 6, 2),
(237, 6, 6, 3),
(238, 6, 6, 4),
(239, 6, 7, 1),
(240, 6, 7, 2),
(241, 6, 7, 3),
(242, 6, 7, 4),
(243, 6, 7, 5),
(244, 6, 7, 6),
(245, 6, 7, 7),
(246, 6, 8, 1),
(247, 6, 8, 2),
(248, 6, 9, 1),
(249, 6, 9, 2),
(250, 7, 1, 1),
(251, 7, 1, 2),
(252, 7, 1, 3),
(253, 7, 1, 4),
(254, 7, 2, 1),
(255, 7, 2, 2),
(256, 7, 2, 3),
(257, 7, 2, 4),
(258, 7, 2, 5),
(259, 7, 3, 1),
(260, 7, 3, 2),
(261, 7, 3, 3),
(262, 7, 3, 4),
(263, 7, 3, 5),
(264, 7, 4, 1),
(265, 7, 4, 2),
(266, 7, 4, 3),
(267, 7, 4, 4),
(268, 7, 5, 1),
(269, 7, 5, 2),
(270, 7, 5, 3),
(271, 7, 5, 4),
(272, 7, 5, 5),
(273, 7, 5, 6),
(274, 7, 5, 7),
(275, 7, 5, 8),
(276, 7, 6, 1),
(277, 7, 6, 2),
(278, 7, 6, 3),
(279, 7, 6, 4),
(280, 7, 6, 5),
(281, 7, 6, 6),
(282, 7, 7, 1),
(283, 7, 7, 2),
(284, 7, 7, 3),
(285, 7, 7, 4),
(286, 7, 7, 5),
(287, 7, 7, 6),
(288, 7, 7, 7),
(289, 7, 7, 8),
(290, 7, 8, 1),
(291, 7, 8, 2),
(292, 7, 8, 3),
(293, 7, 8, 4),
(294, 7, 8, 5),
(295, 7, 9, 1),
(296, 7, 9, 2),
(297, 7, 9, 3),
(298, 7, 9, 4),
(299, 7, 9, 5),
(300, 8, 1, 1),
(301, 8, 1, 2),
(302, 8, 1, 3),
(303, 8, 1, 4),
(304, 8, 1, 5),
(305, 8, 2, 1),
(306, 8, 2, 2),
(307, 8, 2, 3),
(308, 8, 3, 1),
(309, 8, 3, 2),
(310, 8, 3, 3),
(311, 8, 3, 4),
(312, 8, 3, 5),
(313, 8, 3, 6),
(314, 8, 4, 1),
(315, 8, 4, 2),
(316, 8, 4, 3),
(317, 8, 5, 1),
(318, 8, 5, 2),
(319, 8, 5, 3),
(320, 8, 5, 4),
(321, 8, 6, 1),
(322, 8, 6, 2),
(323, 8, 6, 3),
(324, 8, 6, 4),
(325, 8, 6, 5),
(326, 8, 6, 6),
(327, 8, 7, 1),
(328, 8, 7, 2),
(329, 8, 7, 3),
(330, 8, 7, 4),
(331, 8, 8, 1),
(332, 8, 8, 2),
(333, 8, 8, 3),
(334, 8, 8, 4),
(335, 8, 8, 5),
(336, 8, 8, 6),
(337, 8, 8, 7),
(338, 8, 8, 8),
(339, 8, 8, 9),
(340, 8, 9, 1),
(341, 8, 9, 2),
(342, 8, 9, 3),
(343, 9, 1, 1),
(344, 9, 1, 2),
(345, 9, 1, 3),
(346, 9, 1, 4),
(347, 9, 1, 5),
(348, 9, 1, 6),
(349, 9, 2, 1),
(350, 9, 2, 2),
(351, 9, 2, 3),
(352, 9, 3, 1),
(353, 9, 3, 2),
(354, 9, 3, 3),
(355, 9, 3, 4),
(356, 9, 3, 5),
(357, 9, 4, 1),
(358, 9, 4, 2),
(359, 9, 4, 3),
(360, 9, 5, 1),
(361, 9, 5, 2),
(362, 9, 6, 1),
(363, 9, 6, 2),
(364, 9, 6, 3),
(365, 9, 6, 4),
(366, 9, 6, 5),
(367, 9, 7, 1),
(368, 9, 7, 2),
(369, 9, 7, 3),
(370, 9, 7, 4),
(371, 9, 7, 5),
(372, 9, 7, 6),
(373, 9, 7, 7),
(374, 9, 8, 1),
(375, 9, 8, 2),
(376, 9, 8, 3),
(377, 9, 9, 1),
(378, 9, 9, 2),
(379, 9, 9, 3),
(380, 9, 9, 4),
(381, 1, 1, -1),
(382, 1, 1, -2),
(383, 1, 1, -3),
(384, 1, 1, -4),
(385, 1, 1, -5),
(386, 1, 1, -6),
(387, 1, 2, -1),
(388, 1, 2, -2),
(389, 1, 2, -3),
(390, 1, 2, -4),
(391, 1, 2, -5),
(392, 1, 2, -6),
(393, 1, 2, -7),
(394, 1, 3, -1),
(395, 1, 3, -2),
(396, 1, 3, -3),
(397, 1, 3, -4),
(398, 1, 3, -5),
(399, 1, 4, -1),
(400, 1, 4, -2),
(401, 1, 4, -3),
(402, 1, 4, -4),
(403, 1, 4, -5),
(404, 1, 5, -1),
(405, 1, 5, -2),
(406, 1, 5, -3),
(407, 1, 5, -4),
(408, 1, 5, -5),
(409, 1, 6, -1),
(410, 1, 6, -2),
(411, 1, 6, -3),
(412, 1, 6, -4),
(413, 1, 6, -5),
(414, 1, 7, -1),
(415, 1, 7, -2),
(416, 1, 7, -3),
(417, 1, 7, -4),
(418, 1, 7, -5),
(419, 1, 8, -1),
(420, 1, 8, -2),
(421, 1, 8, -3),
(422, 1, 8, -4),
(423, 1, 8, -5),
(424, 1, 9, -1),
(425, 1, 9, -2),
(426, 1, 9, -3),
(427, 1, 9, -4),
(428, 1, 9, -5),
(429, 1, 9, -6),
(430, 1, 9, -7),
(431, 2, 1, -1),
(432, 2, 1, -2),
(433, 2, 1, -3),
(434, 2, 1, -4),
(435, 2, 1, -5),
(436, 2, 2, -1),
(437, 2, 2, -2),
(438, 2, 2, -3),
(439, 2, 2, -4),
(440, 2, 2, -5),
(441, 2, 3, -1),
(442, 2, 3, -2),
(443, 2, 3, -3),
(444, 2, 4, -1),
(445, 2, 4, -2),
(446, 2, 4, -3),
(447, 2, 4, -4),
(448, 2, 4, -5),
(449, 2, 5, -1),
(450, 2, 5, -2),
(451, 2, 5, -3),
(452, 2, 6, -1),
(453, 2, 6, -2),
(454, 2, 7, -1),
(455, 2, 7, -2),
(456, 2, 7, -3),
(457, 2, 8, -1),
(458, 2, 8, -2),
(459, 2, 8, -3),
(460, 2, 8, -4),
(461, 2, 9, -1),
(462, 2, 9, -2),
(463, 2, 9, -3),
(464, 2, 9, -4),
(465, 2, 9, -5),
(466, 3, 1, -1),
(467, 3, 1, -2),
(468, 3, 2, -1),
(469, 3, 2, -2),
(470, 3, 2, -3),
(471, 3, 2, -4),
(472, 3, 2, -5),
(473, 3, 3, -1),
(474, 3, 3, -2),
(475, 3, 3, -3),
(476, 3, 3, -4),
(477, 3, 3, -5),
(478, 3, 4, -1),
(479, 3, 4, -2),
(480, 3, 4, -3),
(481, 3, 4, -4),
(482, 3, 4, -5),
(483, 3, 5, -1),
(484, 3, 5, -2),
(485, 3, 5, -3),
(486, 3, 5, -4),
(487, 3, 6, -1),
(488, 3, 6, -2),
(489, 3, 6, -3),
(490, 3, 6, -4),
(491, 3, 6, -5),
(492, 3, 6, -6),
(493, 3, 6, -7),
(494, 3, 6, -8),
(495, 3, 7, -1),
(496, 3, 7, -2),
(497, 3, 8, -1),
(498, 3, 8, -2),
(499, 3, 8, -3),
(500, 3, 8, -4),
(501, 3, 9, -1),
(502, 3, 9, -2),
(503, 3, 9, -3),
(504, 3, 9, -4),
(505, 3, 9, -5),
(506, 3, 9, -6),
(507, 3, 9, -7),
(508, 4, 1, -1),
(509, 4, 1, -2),
(510, 4, 1, -3),
(511, 4, 1, -4),
(512, 4, 2, -1),
(513, 4, 2, -2),
(514, 4, 2, -3),
(515, 4, 2, -4),
(516, 4, 3, -1),
(517, 4, 3, -2),
(518, 4, 3, -3),
(519, 4, 3, -4),
(520, 4, 3, -5),
(521, 4, 3, -6),
(522, 4, 3, -7),
(523, 4, 4, -1),
(524, 4, 4, -2),
(525, 4, 4, -3),
(526, 4, 4, -4),
(527, 4, 4, -5),
(528, 4, 5, -1),
(529, 4, 5, -2),
(530, 4, 5, -3),
(531, 4, 6, -1),
(532, 4, 6, -2),
(533, 4, 6, -3),
(534, 4, 6, -4),
(535, 4, 6, -5),
(536, 4, 6, -6),
(537, 4, 6, -7),
(538, 4, 7, -1),
(539, 4, 7, -2),
(540, 4, 7, -3),
(541, 4, 7, -4),
(542, 4, 7, -5),
(543, 4, 8, -1),
(544, 4, 8, -2),
(545, 4, 8, -3),
(546, 4, 8, -4),
(547, 4, 8, -5),
(548, 4, 9, -1),
(549, 4, 9, -2),
(550, 5, 1, -1),
(551, 5, 1, -2),
(552, 5, 1, -3),
(553, 5, 1, -4),
(554, 5, 2, -1),
(555, 5, 2, -2),
(556, 5, 2, -3),
(557, 5, 2, -4),
(558, 5, 2, -5),
(559, 5, 3, -1),
(560, 5, 3, -2),
(561, 5, 3, -3),
(562, 5, 3, -4),
(563, 5, 3, -5),
(564, 5, 4, -1),
(565, 5, 4, -2),
(566, 5, 4, -3),
(567, 5, 4, -4),
(568, 5, 4, -5),
(569, 5, 4, -6),
(570, 5, 4, -7),
(571, 5, 5, -1),
(572, 5, 5, -2),
(573, 5, 5, -3),
(574, 5, 5, -4),
(575, 5, 5, -5),
(576, 5, 6, -1),
(577, 5, 6, -2),
(578, 5, 6, -3),
(579, 5, 6, -4),
(580, 5, 6, -5),
(581, 5, 7, -1),
(582, 5, 7, -2),
(583, 5, 7, -3),
(584, 5, 7, -4),
(585, 5, 7, -5),
(586, 5, 7, -6),
(587, 5, 7, -7),
(588, 5, 8, -1),
(589, 5, 8, -2),
(590, 5, 8, -3),
(591, 5, 8, -4),
(592, 5, 9, -1),
(593, 5, 9, -2),
(594, 6, 1, -1),
(595, 6, 1, -2),
(596, 6, 1, -3),
(597, 6, 1, -4),
(598, 6, 1, -5),
(599, 6, 2, -1),
(600, 6, 2, -2),
(601, 6, 2, -3),
(602, 6, 2, -4),
(603, 6, 2, -5),
(604, 6, 3, -1),
(605, 6, 3, -2),
(606, 6, 3, -3),
(607, 6, 3, -4),
(608, 6, 3, -5),
(609, 6, 3, -6),
(610, 6, 3, -7),
(611, 6, 3, -8),
(612, 6, 4, -1),
(613, 6, 4, -2),
(614, 6, 4, -3),
(615, 6, 4, -4),
(616, 6, 4, -5),
(617, 6, 5, -1),
(618, 6, 5, -2),
(619, 6, 5, -3),
(620, 6, 5, -4),
(621, 6, 5, -5),
(622, 6, 6, -1),
(623, 6, 6, -2),
(624, 6, 6, -3),
(625, 6, 6, -4),
(626, 6, 7, -1),
(627, 6, 7, -2),
(628, 6, 7, -3),
(629, 6, 7, -4),
(630, 6, 8, -1),
(631, 6, 8, -2),
(632, 6, 9, -1),
(633, 6, 9, -2),
(634, 6, 9, -3),
(635, 6, 9, -4),
(636, 6, 9, -5),
(637, 6, 9, -6),
(638, 6, 9, -7),
(639, 7, 1, -1),
(640, 7, 1, -2),
(641, 7, 1, -3),
(642, 7, 2, -1),
(643, 7, 2, -2),
(644, 7, 2, -3),
(645, 7, 2, -4),
(646, 7, 2, -5),
(647, 7, 3, -1),
(648, 7, 3, -2),
(649, 7, 3, -3),
(650, 7, 3, -4),
(651, 7, 3, -5),
(652, 7, 3, -6),
(653, 7, 4, -1),
(654, 7, 4, -2),
(655, 7, 4, -3),
(656, 7, 4, -4),
(657, 7, 5, -1),
(658, 7, 5, -2),
(659, 7, 5, -3),
(660, 7, 5, -4),
(661, 7, 5, -5),
(662, 7, 6, -1),
(663, 7, 6, -2),
(664, 7, 6, -3),
(665, 7, 6, -4),
(666, 7, 6, -5),
(667, 7, 7, -1),
(668, 7, 7, -2),
(669, 7, 7, -3),
(670, 7, 7, -4),
(671, 7, 8, -1),
(672, 7, 8, -2),
(673, 7, 8, -3),
(674, 7, 8, -4),
(675, 7, 9, -1),
(676, 7, 9, -2),
(677, 7, 9, -3),
(678, 7, 9, -4),
(679, 7, 9, -5),
(680, 7, 9, -6),
(681, 7, 9, -7),
(682, 7, 9, -8),
(683, 7, 9, -9),
(684, 8, 1, -1),
(685, 8, 1, -2),
(686, 8, 1, -3),
(687, 8, 1, -4),
(688, 8, 1, -5),
(689, 8, 2, -1),
(690, 8, 2, -2),
(691, 8, 2, -3),
(692, 8, 3, -1),
(693, 8, 3, -2),
(694, 8, 3, -3),
(695, 8, 3, -4),
(696, 8, 3, -5),
(697, 8, 4, -1),
(698, 8, 4, -2),
(699, 8, 4, -3),
(700, 8, 5, -1),
(701, 8, 5, -2),
(702, 8, 5, -3),
(703, 8, 6, -1),
(704, 8, 6, -2),
(705, 8, 7, -1),
(706, 8, 7, -2),
(707, 8, 7, -3),
(708, 8, 7, -4),
(709, 8, 7, -5),
(710, 8, 8, -1),
(711, 8, 8, -2),
(712, 8, 9, -1),
(713, 8, 9, -2),
(714, 8, 9, -3),
(715, 8, 9, -4),
(716, 9, 1, -1),
(717, 9, 1, -2),
(718, 9, 1, -3),
(719, 9, 1, -4),
(720, 9, 2, -1),
(721, 9, 2, -2),
(722, 9, 2, -3),
(723, 9, 2, -4),
(724, 9, 2, -5),
(725, 9, 3, -1),
(726, 9, 3, -2),
(727, 9, 4, -1),
(728, 9, 4, -2),
(729, 9, 4, -3),
(730, 9, 4, -4),
(731, 9, 4, -5),
(732, 9, 5, -1),
(733, 9, 5, -2),
(734, 9, 5, -3),
(735, 9, 5, -4),
(736, 9, 5, -5),
(737, 9, 5, -6),
(738, 9, 6, -1),
(739, 9, 6, -2),
(740, 9, 6, -3),
(741, 9, 6, -4),
(742, 9, 6, -5),
(743, 9, 6, -6),
(744, 9, 6, -7),
(745, 9, 7, -1),
(746, 9, 7, -2),
(747, 9, 8, -1),
(748, 9, 8, -2),
(749, 9, 8, -3),
(750, 9, 8, -4),
(751, 9, 8, -5),
(752, 9, 8, -6),
(753, 9, 9, -1),
(754, 9, 9, -2),
(755, 9, 9, -3),
(756, 9, 9, -4),
(757, 9, 9, -5);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `t_mission`
--

CREATE TABLE `t_mission` (
  `ID_Mission` int(10) UNSIGNED NOT NULL,
  `Parameter` enum('Angriff','Spionage','Transport','Uebergabe','Verteidigung','Kolonisieren','Verlegen') NOT NULL DEFAULT 'Spionage',
  `Hinflug` int(11) NOT NULL DEFAULT '0',
  `Rückflug` int(11) NOT NULL DEFAULT '0',
  `Ressources` text NOT NULL,
  `IdleTime` int(11) NOT NULL DEFAULT '0',
  `ID_KoordinatenDestination` int(11) NOT NULL DEFAULT '0',
  `ID_KoordinatenSource` int(11) NOT NULL DEFAULT '0',
  `ID_Flotte` int(11) NOT NULL DEFAULT '0',
  `ID_User` int(11) NOT NULL DEFAULT '0',
  `ID_UserOpfer` int(11) NOT NULL DEFAULT '0',
  `Artillerie` int(1) NOT NULL DEFAULT '0',
  `Ausgefuehrt` int(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `t_nachrichten`
--

CREATE TABLE `t_nachrichten` (
  `ID_Nachricht` int(10) UNSIGNED NOT NULL,
  `Betreff` varchar(50) NOT NULL DEFAULT '',
  `Inhalt` text NOT NULL,
  `Datum` int(11) NOT NULL DEFAULT '0',
  `Status` enum('neu','gelesen') NOT NULL DEFAULT 'neu',
  `Deleted` enum('keiner','empfaenger','absender','both') NOT NULL DEFAULT 'keiner',
  `ID_User` int(11) NOT NULL DEFAULT '0',
  `ID_UserAbsender` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `t_news`
--

CREATE TABLE `t_news` (
  `ID_News` int(10) UNSIGNED NOT NULL,
  `Betreff` varchar(50) NOT NULL DEFAULT '',
  `Inhalt` text NOT NULL,
  `Datum` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `t_news`
--

INSERT INTO `t_news` (`ID_News`, `Betreff`, `Inhalt`, `Datum`) VALUES (1, '\o/ Installation \o/', 'Subterranwars wurde erfolgreich installert.', NOW());

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `t_profil`
--

CREATE TABLE `t_profil` (
  `ID_Profil` int(10) UNSIGNED NOT NULL,
  `Bezeichnung` varchar(40) NOT NULL DEFAULT '',
  `ChassisTyp` int(11) NOT NULL DEFAULT '0',
  `MaxLeistung` int(11) NOT NULL DEFAULT '0',
  `MaxZuladung` int(11) NOT NULL DEFAULT '0',
  `Leistung` int(11) NOT NULL DEFAULT '0',
  `Zuladung` int(11) NOT NULL DEFAULT '0',
  `Wendigkeit` int(11) NOT NULL DEFAULT '0',
  `Geschwindigkeit` int(11) NOT NULL DEFAULT '0',
  `Lebenspunkte` int(11) NOT NULL DEFAULT '0',
  `Angriff` int(11) NOT NULL DEFAULT '0',
  `Panzerung` int(11) NOT NULL DEFAULT '0',
  `Zielen` int(11) NOT NULL DEFAULT '0',
  `Eisen` int(11) NOT NULL DEFAULT '0',
  `Stahl` int(11) NOT NULL DEFAULT '0',
  `Titan` int(11) NOT NULL DEFAULT '0',
  `Kunststoff` int(11) NOT NULL DEFAULT '0',
  `Wasserstoff` int(11) NOT NULL DEFAULT '0',
  `Uran` int(11) NOT NULL DEFAULT '0',
  `Plutonium` int(11) NOT NULL DEFAULT '0',
  `Gold` int(11) NOT NULL DEFAULT '0',
  `Diamant` int(11) NOT NULL DEFAULT '0',
  `Bevölkerung` int(11) NOT NULL DEFAULT '0',
  `Bauzeit` int(11) NOT NULL DEFAULT '0',
  `Waffentyp` int(11) NOT NULL DEFAULT '0',
  `WaffenbonusTyp` int(11) NOT NULL DEFAULT '0',
  `Waffenbonus` double NOT NULL DEFAULT '0',
  `BonustypChassis` int(11) NOT NULL DEFAULT '0',
  `ChassisBonus` double NOT NULL DEFAULT '0',
  `Panzertyp` int(11) NOT NULL DEFAULT '0',
  `Panzerbonus` double NOT NULL DEFAULT '0',
  `ID_User` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `t_profilhatteile`
--

CREATE TABLE `t_profilhatteile` (
  `ID_Profil` int(11) NOT NULL DEFAULT '0',
  `ID_Teile` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `t_user`
--

CREATE TABLE `t_user` (
  `ID_User` int(10) UNSIGNED NOT NULL,
  `Nickname` varchar(50) NOT NULL DEFAULT '',
  `Loginname` varchar(50) NOT NULL DEFAULT '',
  `Passwort` varchar(50) NOT NULL DEFAULT '',
  `Email` varchar(255) NOT NULL DEFAULT '',
  `ICQ` varchar(15) NOT NULL DEFAULT '',
  `Generalpunkte` int(11) NOT NULL DEFAULT '0',
  `PunkteForschung` int(11) NOT NULL DEFAULT '0',
  `Militärpunkte` int(11) NOT NULL DEFAULT '0',
  `Gebäudepunkte` int(11) NOT NULL DEFAULT '0',
  `Avatar` varchar(50) NOT NULL DEFAULT '',
  `LastLogin` int(11) NOT NULL DEFAULT '0',
  `RegisterDate` int(11) NOT NULL DEFAULT '0',
  `Status` enum('freigeschaltet','warten','gesperrt') NOT NULL DEFAULT 'warten',
  `Skin` varchar(50) NOT NULL DEFAULT '',
  `Forscher` int(11) NOT NULL DEFAULT '0',
  `Forschungspunkte` double NOT NULL DEFAULT '0',
  `LastChange` int(11) NOT NULL DEFAULT '0',
  `ID_Rasse` int(11) NOT NULL DEFAULT '0',
  `ID_General` int(11) NOT NULL DEFAULT '0',
  `ID_Allianz` int(11) NOT NULL DEFAULT '0',
  `Allianzstatus` enum('member','botschafter','admin','co-admin') NOT NULL DEFAULT 'member'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `t_user`
--

INSERT INTO `t_user` (`ID_User`, `Nickname`, `Loginname`, `Passwort`, `Email`, `ICQ`, `Generalpunkte`, `PunkteForschung`, `Militärpunkte`, `Gebäudepunkte`, `Avatar`, `LastLogin`, `RegisterDate`, `Status`, `Skin`, `Forscher`, `Forschungspunkte`, `LastChange`, `ID_Rasse`, `ID_General`, `ID_Allianz`, `Allianzstatus`) VALUES
(2, 'RESERVED', 'RESERVED', 'RESERVED', '', '', 0, 0, 0, 0, '', 0, 0, 'warten', '', 0, 0, 0, 0, 0, 0, 'member'),
(3, 'RESERVED', 'RESERVED', 'RESERVED', '', '', 0, 0, 0, 0, '', 0, 0, 'warten', '', 0, 0, 0, 0, 0, 0, 'member'),
(4, 'RESERVED', 'RESERVED', 'RESERVED', '', '', 0, 0, 0, 0, '', 0, 0, 'warten', '', 0, 0, 0, 0, 0, 0, 'member'),
(5, 'RESERVED', 'RESERVED', 'RESERVED', '', '', 0, 0, 0, 0, '', 0, 0, 'warten', '', 0, 0, 0, 0, 0, 0, 'member'),
(6, 'RESERVED', 'RESERVED', 'RESERVED', '', '', 0, 0, 0, 0, '', 0, 0, 'warten', '', 0, 0, 0, 0, 0, 0, 'member'),
(7, 'RESERVED', 'RESERVED', 'RESERVED', '', '', 0, 0, 0, 0, '', 0, 0, 'warten', '', 0, 0, 0, 0, 0, 0, 'member'),
(8, 'RESERVED', 'RESERVED', 'RESERVED', '', '', 0, 0, 0, 0, '', 0, 0, 'warten', '', 0, 0, 0, 0, 0, 0, 'member'),
(9, 'RESERVED', 'RESERVED', 'RESERVED', '', '', 0, 0, 0, 0, '', 0, 0, 'warten', '', 0, 0, 0, 0, 0, 0, 'member'),
(10, 'RESERVED', 'RESERVED', 'RESERVED', '', '', 0, 0, 0, 0, '', 0, 0, 'warten', '', 0, 0, 0, 0, 0, 0, 'member');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `t_userfreischalten`
--

CREATE TABLE `t_userfreischalten` (
  `ID_Key` int(10) UNSIGNED NOT NULL,
  `Freischaltungscode` varchar(255) NOT NULL DEFAULT '',
  `ID_User` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `t_userhatforschung`
--

CREATE TABLE `t_userhatforschung` (
  `ID_User` int(11) NOT NULL DEFAULT '0',
  `ID_Forschung` int(11) NOT NULL DEFAULT '0',
  `Level` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `t_userhatgebaeude`
--

CREATE TABLE `t_userhatgebaeude` (
  `ID_User` int(11) NOT NULL DEFAULT '0',
  `ID_Gebäude` int(11) NOT NULL DEFAULT '0',
  `Level` int(11) NOT NULL DEFAULT '0',
  `Auslastung` double NOT NULL DEFAULT '0',
  `ID_Kolonie` int(11) NOT NULL DEFAULT '0',
  `LastChange` int(10) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `t_userhatrohstoffe`
--

CREATE TABLE `t_userhatrohstoffe` (
  `ID_User` int(11) NOT NULL DEFAULT '0',
  `ID_Rohstoff` int(11) NOT NULL DEFAULT '0',
  `Anzahl` double NOT NULL DEFAULT '0',
  `LastUpdate` int(11) NOT NULL DEFAULT '0',
  `ID_Kolonie` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `t_useronline`
--

CREATE TABLE `t_useronline` (
  `ID_Online` int(10) UNSIGNED NOT NULL,
  `ID_User` int(11) NOT NULL DEFAULT '0',
  `IP` varchar(15) NOT NULL DEFAULT '',
  `Expire` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `t_userpolitik`
--

CREATE TABLE `t_userpolitik` (
  `ID_User` int(11) NOT NULL DEFAULT '0',
  `ID_UserPartner` int(11) NOT NULL DEFAULT '0',
  `Status` enum('verbündet','feind') NOT NULL DEFAULT 'verbündet'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `t_vorkommen`
--

CREATE TABLE `t_vorkommen` (
  `ID_Vorkommen` int(10) UNSIGNED NOT NULL,
  `Größe` int(11) NOT NULL DEFAULT '0',
  `ResLeft` double NOT NULL DEFAULT '0',
  `ID_Rohstoff` int(11) NOT NULL DEFAULT '0',
  `ID_User` int(11) NOT NULL DEFAULT '0',
  `AnzahlLasterDrohnen` int(11) NOT NULL DEFAULT '0',
  `LastChange` int(11) NOT NULL DEFAULT '0',
  `LastChangeDrohnen` int(14) NOT NULL DEFAULT '0',
  `ID_Kolonie` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `t_allianz`
--
ALTER TABLE `t_allianz`
  ADD PRIMARY KEY (`ID_Allianz`);

--
-- Indizes für die Tabelle `t_auftrag`
--
ALTER TABLE `t_auftrag`
  ADD PRIMARY KEY (`ID_Auftrag`);

--
-- Indizes für die Tabelle `t_auftrageinheit`
--
ALTER TABLE `t_auftrageinheit`
  ADD PRIMARY KEY (`ID_Auftrag`);

--
-- Indizes für die Tabelle `t_auftragforschung`
--
ALTER TABLE `t_auftragforschung`
  ADD PRIMARY KEY (`ID_Auftrag`);

--
-- Indizes für die Tabelle `t_auftraggebaeude`
--
ALTER TABLE `t_auftraggebaeude`
  ADD PRIMARY KEY (`ID_Auftrag`);

--
-- Indizes für die Tabelle `t_auftragvorkommensuche`
--
ALTER TABLE `t_auftragvorkommensuche`
  ADD PRIMARY KEY (`ID_Auftrag`);

--
-- Indizes für die Tabelle `t_bevoelkerung`
--
ALTER TABLE `t_bevoelkerung`
  ADD PRIMARY KEY (`ID_Bevölkerung`);

--
-- Indizes für die Tabelle `t_bugs`
--
ALTER TABLE `t_bugs`
  ADD PRIMARY KEY (`ID_Bugs`);

--
-- Indizes für die Tabelle `t_config`
--
ALTER TABLE `t_config`
  ADD PRIMARY KEY (`ID_Config`);

--
-- Indizes für die Tabelle `t_einheit`
--
ALTER TABLE `t_einheit`
  ADD PRIMARY KEY (`ID_Einheit`);

--
-- Indizes für die Tabelle `t_ereignis`
--
ALTER TABLE `t_ereignis`
  ADD PRIMARY KEY (`ID_Ereignis`);

--
-- Indizes für die Tabelle `t_fehler`
--
ALTER TABLE `t_fehler`
  ADD PRIMARY KEY (`ID_Fehler`);

--
-- Indizes für die Tabelle `t_flotte`
--
ALTER TABLE `t_flotte`
  ADD PRIMARY KEY (`ID_Flotte`);

--
-- Indizes für die Tabelle `t_general`
--
ALTER TABLE `t_general`
  ADD PRIMARY KEY (`ID_General`);

--
-- Indizes für die Tabelle `t_icons`
--
ALTER TABLE `t_icons`
  ADD PRIMARY KEY (`ID_Icons`);

--
-- Indizes für die Tabelle `t_kolonie`
--
ALTER TABLE `t_kolonie`
  ADD PRIMARY KEY (`ID_Kolonie`);

--
-- Indizes für die Tabelle `t_koordinaten`
--
ALTER TABLE `t_koordinaten`
  ADD PRIMARY KEY (`ID_Koordinaten`);

--
-- Indizes für die Tabelle `t_mission`
--
ALTER TABLE `t_mission`
  ADD PRIMARY KEY (`ID_Mission`);

--
-- Indizes für die Tabelle `t_nachrichten`
--
ALTER TABLE `t_nachrichten`
  ADD PRIMARY KEY (`ID_Nachricht`);

--
-- Indizes für die Tabelle `t_news`
--
ALTER TABLE `t_news`
  ADD PRIMARY KEY (`ID_News`);

--
-- Indizes für die Tabelle `t_profil`
--
ALTER TABLE `t_profil`
  ADD PRIMARY KEY (`ID_Profil`);

--
-- Indizes für die Tabelle `t_user`
--
ALTER TABLE `t_user`
  ADD PRIMARY KEY (`ID_User`);

--
-- Indizes für die Tabelle `t_userfreischalten`
--
ALTER TABLE `t_userfreischalten`
  ADD PRIMARY KEY (`ID_Key`);

--
-- Indizes für die Tabelle `t_useronline`
--
ALTER TABLE `t_useronline`
  ADD PRIMARY KEY (`ID_Online`);

--
-- Indizes für die Tabelle `t_vorkommen`
--
ALTER TABLE `t_vorkommen`
  ADD PRIMARY KEY (`ID_Vorkommen`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `t_allianz`
--
ALTER TABLE `t_allianz`
  MODIFY `ID_Allianz` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `t_auftrag`
--
ALTER TABLE `t_auftrag`
  MODIFY `ID_Auftrag` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22539;

--
-- AUTO_INCREMENT für Tabelle `t_bevoelkerung`
--
ALTER TABLE `t_bevoelkerung`
  MODIFY `ID_Bevölkerung` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `t_bugs`
--
ALTER TABLE `t_bugs`
  MODIFY `ID_Bugs` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=398;

--
-- AUTO_INCREMENT für Tabelle `t_config`
--
ALTER TABLE `t_config`
  MODIFY `ID_Config` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `t_einheit`
--
ALTER TABLE `t_einheit`
  MODIFY `ID_Einheit` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `t_ereignis`
--
ALTER TABLE `t_ereignis`
  MODIFY `ID_Ereignis` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `t_fehler`
--
ALTER TABLE `t_fehler`
  MODIFY `ID_Fehler` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT für Tabelle `t_flotte`
--
ALTER TABLE `t_flotte`
  MODIFY `ID_Flotte` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `t_general`
--
ALTER TABLE `t_general`
  MODIFY `ID_General` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `t_icons`
--
ALTER TABLE `t_icons`
  MODIFY `ID_Icons` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `t_kolonie`
--
ALTER TABLE `t_kolonie`
  MODIFY `ID_Kolonie` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `t_koordinaten`
--
ALTER TABLE `t_koordinaten`
  MODIFY `ID_Koordinaten` int(15) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=758;

--
-- AUTO_INCREMENT für Tabelle `t_mission`
--
ALTER TABLE `t_mission`
  MODIFY `ID_Mission` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `t_nachrichten`
--
ALTER TABLE `t_nachrichten`
  MODIFY `ID_Nachricht` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `t_news`
--
ALTER TABLE `t_news`
  MODIFY `ID_News` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT für Tabelle `t_profil`
--
ALTER TABLE `t_profil`
  MODIFY `ID_Profil` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `t_user`
--
ALTER TABLE `t_user`
  MODIFY `ID_User` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=496;

--
-- AUTO_INCREMENT für Tabelle `t_userfreischalten`
--
ALTER TABLE `t_userfreischalten`
  MODIFY `ID_Key` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=361;

--
-- AUTO_INCREMENT für Tabelle `t_useronline`
--
ALTER TABLE `t_useronline`
  MODIFY `ID_Online` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2779;

--
-- AUTO_INCREMENT für Tabelle `t_vorkommen`
--
ALTER TABLE `t_vorkommen`
  MODIFY `ID_Vorkommen` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
