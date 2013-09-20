-- phpMyAdmin SQL Dump
-- version 3.5.8.1
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Erstellungszeit: 20. Sep 2013 um 00:05
-- Server Version: 5.5.24-0ubuntu0.12.04.1
-- PHP-Version: 5.3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `universeDevelopment`
--
CREATE DATABASE `universeDevelopment` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `universeDevelopment`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `adminMessages`
--

CREATE TABLE IF NOT EXISTS `adminMessages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` int(11) NOT NULL,
  `author` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `attachment` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `buddylist`
--

CREATE TABLE IF NOT EXISTS `buddylist` (
  `owner` int(11) NOT NULL,
  `buddy` int(11) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `group` int(11) NOT NULL,
  `request` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `typeid` int(11) NOT NULL,
  `author` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `text` varchar(255) NOT NULL,
  `votes` varchar(255) NOT NULL DEFAULT '0',
  `score` varchar(255) NOT NULL DEFAULT '0',
  `privacy` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `elements`
--

CREATE TABLE IF NOT EXISTS `elements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `folder` int(11) NOT NULL,
  `creator` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `year` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `author` int(11) NOT NULL,
  `license` varchar(255) NOT NULL DEFAULT 'Creative Commons Attribution/Share Alike',
  `timestamp` int(11) NOT NULL,
  `info1` varchar(255) NOT NULL,
  `info2` varchar(255) NOT NULL,
  `info3` varchar(255) NOT NULL,
  `privacy` varchar(255) NOT NULL DEFAULT 'p' COMMENT 'if empty: public. else: element is just visible in group x',
  `hidden` int(11) NOT NULL COMMENT 'just visible for author',
  `votes` int(11) NOT NULL DEFAULT '0',
  `score` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Daten für Tabelle `elements`
--

INSERT INTO `elements` (`id`, `title`, `folder`, `creator`, `name`, `year`, `type`, `author`, `license`, `timestamp`, `info1`, `info2`, `info3`, `privacy`, `hidden`, `votes`, `score`) VALUES
(1, 'myFiles', 4, '', '', '', 'myFiles', 1, 'Creative Commons Attribution/Share Alike', 1379628108, '', '', '', 'h', 0, 0, 0),
(2, 'profile pictures', 5, '', '', '', 'image', 1, 'Creative Commons Attribution/Share Alike', 1379628108, '', '', '', 'p', 0, 0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fav`
--

CREATE TABLE IF NOT EXISTS `fav` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `item` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `hidden` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `feed`
--

CREATE TABLE IF NOT EXISTS `feed` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author` int(11) NOT NULL,
  `feed` text NOT NULL,
  `timestamp` int(11) NOT NULL,
  `validity` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `attachedItem` varchar(255) NOT NULL,
  `attachedItemId` int(11) NOT NULL,
  `privacy` varchar(255) NOT NULL,
  `votes` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `folder` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `size` varchar(255) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `language` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `owner` int(11) NOT NULL,
  `votes` int(11) NOT NULL DEFAULT '0',
  `score` int(11) NOT NULL DEFAULT '0',
  `privacy` varchar(255) NOT NULL DEFAULT 'p',
  `download` tinyint(1) NOT NULL,
  `var1` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `folders`
--

CREATE TABLE IF NOT EXISTS `folders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `folder` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `creator` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `privacy` text NOT NULL COMMENT 'if empty: public. else: element is just visible in group x',
  `hidden` int(11) NOT NULL COMMENT 'just visible for author',
  `votes` int(11) NOT NULL DEFAULT '0',
  `score` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Daten für Tabelle `folders`
--

INSERT INTO `folders` (`id`, `folder`, `name`, `path`, `creator`, `timestamp`, `privacy`, `hidden`, `votes`, `score`) VALUES
(1, 0, 'universe', '', 1, 1373158724, 'p;PROTECTED', 0, 0, 0),
(2, 1, 'userFiles', '/userFiles', 1, 1373158735, 'p;PROTECTED', 0, 2, 0),
(3, 1, 'groupFiles', '/groupFiles', 1, 1373158745, 'p;PROTECTED;PROTECTED', 0, 16, 8),
(4, 2, '1', 'upload/userFiles/1', 1, 1379628108, 'h', 0, 0, 0),
(5, 4, 'userPictures', 'upload/userFiles/1/userPictures', 1, 1379628108, 'h', 0, 0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `groupAttachments`
--

CREATE TABLE IF NOT EXISTS `groupAttachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group` int(11) NOT NULL,
  `item` varchar(255) NOT NULL,
  `itemId` int(11) NOT NULL,
  `author` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `validated` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `public` text NOT NULL,
  `admin` int(11) NOT NULL,
  `membersInvite` int(11) NOT NULL COMMENT 'allow all members not just admins to invite users',
  `homeFolder` int(11) NOT NULL,
  `homeElement` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `internLinks`
--

CREATE TABLE IF NOT EXISTS `internLinks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parentType` varchar(255) NOT NULL,
  `parentId` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `typeId` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `links`
--

CREATE TABLE IF NOT EXISTS `links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `folder` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `link` text NOT NULL,
  `privacy` text NOT NULL,
  `author` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `votes` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender` int(11) NOT NULL,
  `receiver` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `text` text NOT NULL,
  `read` int(11) NOT NULL,
  `crypt` int(11) NOT NULL DEFAULT '0',
  `seen` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `personalEvents`
--

CREATE TABLE IF NOT EXISTS `personalEvents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `event` varchar(255) NOT NULL,
  `info` text NOT NULL,
  `eventId` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `seen` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `playlist`
--

CREATE TABLE IF NOT EXISTS `playlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `folders` text NOT NULL,
  `elements` text NOT NULL,
  `links` text NOT NULL,
  `files` text NOT NULL,
  `youTube` text NOT NULL,
  `privacy` varchar(255) NOT NULL,
  `played` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `protocoll_type`
--

CREATE TABLE IF NOT EXISTS `protocoll_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `user` int(11) NOT NULL,
  `link` text NOT NULL,
  `space` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `staticContents`
--

CREATE TABLE IF NOT EXISTS `staticContents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `comment` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `staticContents`
--

INSERT INTO `staticContents` (`id`, `title`, `content`, `comment`) VALUES
(1, 'Contact', '<p style="font-size: 19pt;">Â <br>Contact | Site Notice</p>\r\n<p>Â </p>\r\n<p><b>Angaben gemaeÃŸ Â§ 5 TMG:</b><br></p>\r\n<p>Nicolas Zemke</p>\r\n<p>Transparency Everywhere</p>\r\n<p>Oldersumer StraÃŸe 47</p>\r\n<p>26603 Aurich<br><br></p>\r\n<p>Oder<br><br></p>\r\n<p>Stefan Pabst</p>\r\n<p>Transparency Everywhere</p>\r\n<p>Extumer Gaste 8</p>\r\n<p>26624 Suedbrookmerland</p>\r\n<p style=" font-size: 19pt;"><br><br><br>Kontakt:<br></p>\r\n<p>Â </p>\r\n<table>\r\n<tbody>\r\n<tr>\r\n<td>Telefon:</td>\r\n<td>0 49 41 88 00 1</td>\r\n</tr>\r\n<tr>\r\n<td>E-Mail:</td>\r\n<td>info[aaat]transparency-everywhere.com</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style="font-size: 19pt;"><br><br><br>Haftungsausschluss:</br></p>\r\n<p><br><br><strong>Haftung fuer Inhalte</strong><br></p>\r\n<p>Die Inhalte unserer Seiten wurden mit groeÃŸter Sorgfalt erstellt. Fuer die Richtigkeit, Vollstaendigkeit und Aktualitaet der Inhalte koennen wir jedoch keine Gewaehr uebernehmen. Als Diensteanbieter sind wir gemaeÃŸ Â§ 7 Abs.1 TMG fuer eigene Inhalte auf diesen Seiten nach den allgemeinen Gesetzen verantwortlich. Nach Â§Â§ 8 bis 10 TMG sind wir als Diensteanbieter jedoch nicht verpflichtet, uebermittelte oder gespeicherte fremde Informationen zu ueberwachen oder nach Umstaenden zu forschen, die auf eine rechtswidrige Taetigkeit hinweisen. Verpflichtungen zur Entfernung oder Sperrung der Nutzung von Informationen nach den allgemeinen Gesetzen bleiben hiervon unberuehrt. Eine diesbezuegliche Haftung ist jedoch erst ab dem Zeitpunkt der Kenntnis einer konkreten Rechtsverletzung moeglich. Bei Bekanntwerden von entsprechenden Rechtsverletzungen werden wir diese Inhalte umgehend entfernen.</p>\r\n<p><br><br><strong>Haftung fuer Links</strong><br></p>\r\n<p>Unser Angebot enthaelt Links zu externen Webseiten Dritter, auf deren Inhalte wir keinen Einfluss haben. Deshalb koennen wir fuer diese fremden Inhalte auch keine Gewaehr uebernehmen. Fuer die Inhalte der verlinkten Seiten ist stets der jeweilige Anbieter oder Betreiber der Seiten verantwortlich. Die verlinkten Seiten wurden zum Zeitpunkt der Verlinkung auf moegliche RechtsverstoeÃŸe ueberprueft. Rechtswidrige Inhalte waren zum Zeitpunkt der Verlinkung nicht erkennbar. Eine permanente inhaltliche Kontrolle der verlinkten Seiten ist jedoch ohne konkrete Anhaltspunkte einer Rechtsverletzung nicht zumutbar. Bei Bekanntwerden von Rechtsverletzungen werden wir derartige Links umgehend entfernen.</p>\r\n<p><br><br><strong>Urheberrecht</strong><br></p>\r\n<p>Die durch die Seitenbetreiber erstellten Inhalte und Werke auf diesen Seiten unterliegen dem deutschen Urheberrecht. Die Vervielfaeltigung, Bearbeitung, Verbreitung und jede Art der Verwertung auÃŸerhalb der Grenzen des Urheberrechtes beduerfen der schriftlichen Zustimmung des jeweiligen Autors bzw. Erstellers. Soweit die Inhalte auf dieser Seite nicht vom Betreiber erstellt wurden, werden die Urheberrechte Dritter beachtet. Insbesondere werden Inhalte Dritter als solche gekennzeichnet. Sollten Sie trotzdem auf eine Urheberrechtsverletzung aufmerksam werden, bitten wir um einen entsprechenden Hinweis. Bei Bekanntwerden von Rechtsverletzungen werden wir derartige Inhalte umgehend entfernen.</p>\r\n<p><br><br><strong>Datenschutz</strong><br></p>\r\n<p>Die Nutzung unserer Webseite ist in der Regel ohne Angabe personenbezogener Daten moeglich. Soweit auf unseren Seiten personenbezogene Daten (beispielsweise Name, Anschrift oder eMail-Adressen) erhoben werden, erfolgt dies, soweit moeglich, stets auf freiwilliger Basis. Diese Daten werden ohne Ihre ausdrueckliche Zustimmung nicht an Dritte weitergegeben.</p>\r\n<p>Wir weisen darauf hin, dass die Datenuebertragung im Internet (z.B. bei der Kommunikation per E-Mail) Sicherheitsluecken aufweisen kann. Ein lueckenloser Schutz der Daten vor dem Zugriff durch Dritte ist nicht moeglich.</p>\r\n<p>Der Nutzung von im Rahmen der Impressumspflicht veroeffentlichten Kontaktdaten durch Dritte zur uebersendung von nicht ausdruecklich angeforderter Werbung und Informationsmaterialien wird hiermit ausdruecklich widersprochen. Die Betreiber der Seiten behalten sich ausdruecklich rechtliche Schritte im Falle der unverlangten Zusendung von Werbeinformationen, etwa durch Spam-Mails, vor.</p>\r\n<p>\r\nUnserer Website verwendet Piwik, dabei handelt es sich um einen sogenannten Webanalysedienst. Piwik verwendet sog. â€œCookiesâ€, dass sind Textdateien, die auf Ihrem Computer gespeichert werden und die unsererseits eine Analyse der Benutzung der Webseite ermÃ¶glichen. Zu diesem Zweck werden die durch den Cookie erzeugten Nutzungsinformationen (einschlieÃŸlich Ihrer gekÃ¼rzten IP-Adresse) an unseren Server Ã¼bertragen und zu Nutzungsanalysezwecken gespeichert, was der Webseitenoptimierung unsererseits dient. Ihre IP-Adresse wird bei diesem Vorgang umgeÂ­hend anonyÂ­miÂ­siert, so dass Sie als Nutzer fÃ¼r uns anonym bleiben. Die durch den Cookie erzeugten Informationen Ã¼ber Ihre Benutzung dieser Webseite werden nicht an Dritte weitergegeben. Sie kÃ¶nnen die Verwendung der Cookies durch eine entsprechende Einstellung Ihrer Browser Software verhindern, es kann jedoch sein, dass Sie in diesem Fall gegebenenfalls nicht sÃ¤mtliche Funktionen dieser Website voll umfÃ¤nglich nutzen kÃ¶nnen.(www.datenschutzbeauftragter-info.de)\r\n</p>\r\n', 'Impressum/Contact');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `userid` int(11) NOT NULL AUTO_INCREMENT,
  `usergroup` int(11) NOT NULL DEFAULT '0',
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `homefolder` int(11) NOT NULL,
  `myFiles` int(11) NOT NULL,
  `profilepictureelement` int(11) NOT NULL COMMENT 'the element wich contains the profilepictures',
  `userPicture` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `regdate` int(11) NOT NULL,
  `lastactivity` int(11) NOT NULL,
  `birthdate` varchar(255) NOT NULL,
  `realname` varchar(255) NOT NULL,
  `home` varchar(255) NOT NULL COMMENT 'where I come From',
  `place` varchar(255) NOT NULL COMMENT 'where I stay at the moment',
  `gender` varchar(255) NOT NULL,
  `school1` varchar(255) NOT NULL,
  `school2` varchar(255) NOT NULL,
  `school3` varchar(255) NOT NULL,
  `university1` varchar(255) NOT NULL,
  `university2` varchar(255) NOT NULL,
  `employer` varchar(255) NOT NULL,
  `typeofwork` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `openChatWindows` text NOT NULL,
  `priv_activateProfile` tinyint(1) NOT NULL DEFAULT '1',
  `priv_showProfile` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'shows groups wich can see the profile(0 = everyone)',
  `priv_profileInformation` tinyint(1) NOT NULL DEFAULT '1',
  `priv_profilePicture` tinyint(1) NOT NULL DEFAULT '1',
  `priv_profileFav` tinyint(1) NOT NULL DEFAULT '0',
  `priv_profileLog` tinyint(1) NOT NULL DEFAULT '0',
  `priv_activateFeed` tinyint(1) NOT NULL DEFAULT '1',
  `priv_buddyRequest` tinyint(1) NOT NULL DEFAULT '1',
  `priv_foreignerMessages` tinyint(1) NOT NULL,
  `priv_foreignerFeeds` tinyint(1) NOT NULL,
  `hash` varchar(255) NOT NULL,
  `backgroundImg` text NOT NULL,
  `startLink` varchar(255) NOT NULL DEFAULT 'doit.php?action=showStartMessage',
  PRIMARY KEY (`userid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`userid`, `usergroup`, `username`, `password`, `homefolder`, `myFiles`, `profilepictureelement`, `userPicture`, `email`, `regdate`, `lastactivity`, `birthdate`, `realname`, `home`, `place`, `gender`, `school1`, `school2`, `school3`, `university1`, `university2`, `employer`, `typeofwork`, `status`, `openChatWindows`, `priv_activateProfile`, `priv_showProfile`, `priv_profileInformation`, `priv_profilePicture`, `priv_profileFav`, `priv_profileLog`, `priv_activateFeed`, `priv_buddyRequest`, `priv_foreignerMessages`, `priv_foreignerFeeds`, `hash`, `backgroundImg`, `startLink`) VALUES
(1, 1, 'admin', '440ac85892ca43ad26d44c7ad9d47d3e', 4, 1, 2, '', '', 1379628108, 1379628318, '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1, 1, 1, 1, 0, 0, 1, 1, 0, 0, '9daac2a5464b45fa16ae355469622277', '', 'doit.php?action=showStartMessage');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `userGroups`
--

CREATE TABLE IF NOT EXISTS `userGroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `showAdminPanel` tinyint(1) NOT NULL,
  `protectFileSystemItems` tinyint(1) NOT NULL,
  `editProtectedFilesystemItem` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `userGroups`
--

INSERT INTO `userGroups` (`id`, `title`, `showAdminPanel`, `protectFileSystemItems`, `editProtectedFilesystemItem`) VALUES
(0, 'standard user', 0, 0, 0),
(1, 'admin', 1, 1, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `userfeeds`
--

CREATE TABLE IF NOT EXISTS `userfeeds` (
  `feedid` int(11) NOT NULL AUTO_INCREMENT,
  `owner` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `validity` int(11) NOT NULL,
  `privacy` text NOT NULL,
  `feed` text NOT NULL,
  `protocoll_type` varchar(255) NOT NULL COMMENT 'LINK TO protocol_types',
  `feedLink1` varchar(255) NOT NULL,
  `feedLink2` varchar(255) NOT NULL,
  `votes` int(11) NOT NULL DEFAULT '0',
  `score` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`feedid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
