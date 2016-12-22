--
-- Tabellenstruktur für Tabelle `adminMessages`
--

CREATE TABLE `adminMessages` (
  `id` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `author` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `attachment` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `appCenterApps`
--

CREATE TABLE `appCenterApps` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_bin NOT NULL,
  `description` text COLLATE utf8_bin NOT NULL,
  `version` varchar(255) COLLATE utf8_bin NOT NULL,
  `entry_point` varchar(255) COLLATE utf8_bin NOT NULL,
  `privacy` text COLLATE utf8_bin NOT NULL,
  `folder_id` int(11) NOT NULL,
  `archive_id` int(11) NOT NULL,
  `archive_file_id` int(11) NOT NULL,
  `icon_file_id` int(11) NOT NULL,
  `temp` tinyint(1) NOT NULL,
  `creation_timestamp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `buddylist`
--

CREATE TABLE `buddylist` (
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

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `typeid` int(11) NOT NULL,
  `author` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `text` varchar(255) NOT NULL,
  `votes` varchar(255) NOT NULL DEFAULT '0',
  `score` varchar(255) NOT NULL DEFAULT '0',
  `privacy` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `elements`
--

CREATE TABLE `elements` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `folder` int(11) NOT NULL,
  `creator` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `year` varchar(255) NOT NULL,
  `originalTitle` text NOT NULL,
  `language` varchar(255) NOT NULL,
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
  `score` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `elements`
--

INSERT INTO `elements` (`id`, `title`, `folder`, `creator`, `name`, `year`, `originalTitle`, `language`, `type`, `author`, `license`, `timestamp`, `info1`, `info2`, `info3`, `privacy`, `hidden`, `votes`, `score`) VALUES
(1, 'myFiles', 4, '', '', '', '', '', 'myFiles', 1, 'Creative Commons Attribution/Share Alike', 1427772434, '', '', '', 'h', 0, 0, 0),
(2, 'profile pictures', 5, '', '', '', '', '', 'image', 1, 'Creative Commons Attribution/Share Alike', 1427772434, '', '', '', 'p', 0, 0, 0),
(3, 'groupElement', 7, '', '', '', '', '', 'groupElement', 1, 'Creative Commons Attribution/Share Alike', 1461632141, '', '', '', '1//1', 0, 0, 0),
(4, 'groupPicture', 7, '', '', '', '', '', 'groupPicture', 1, 'Creative Commons Attribution/Share Alike', 1461632141, '', '', '', '1//1', 0, 0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `startStamp` int(11) NOT NULL,
  `stopStamp` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `place` varchar(255) NOT NULL,
  `privacy` text NOT NULL,
  `invitedUsers` text NOT NULL,
  `originalEventId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fav`
--

CREATE TABLE `fav` (
  `id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `item` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `hidden` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `feed`
--

CREATE TABLE `feed` (
  `id` int(11) NOT NULL,
  `author` int(11) NOT NULL,
  `feed` text NOT NULL,
  `timestamp` int(11) NOT NULL,
  `validity` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `attachedItem` varchar(255) NOT NULL,
  `attachedItemId` int(11) NOT NULL,
  `privacy` varchar(255) NOT NULL,
  `votes` int(11) NOT NULL,
  `score` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `feed`
--

INSERT INTO `feed` (`id`, `author`, `feed`, `timestamp`, `validity`, `type`, `attachedItem`, `attachedItemId`, `privacy`, `votes`, `score`) VALUES
(1, 1, 'asd\n', 1454606426, 0, 'feed', '', 0, 'p', 0, 0),
(2, 1, 'has created a folder', 1454606793, 0, 'showThumb', 'folder', 6, 'p', 0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
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
  `temp` tinyint(1) NOT NULL,
  `var1` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `files`
--

INSERT INTO `files` (`id`, `folder`, `title`, `size`, `timestamp`, `filename`, `language`, `type`, `owner`, `votes`, `score`, `privacy`, `download`, `temp`, `var1`, `status`) VALUES
(1, 5, 'sdasd_bild.jpg', '92451', 1461814001, 'sdasd_bild.jpg', '', 'image/jpeg', 1, 0, 0, 'p', 1, 0, '', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `folders`
--

CREATE TABLE `folders` (
  `id` int(11) NOT NULL,
  `folder` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `creator` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `privacy` text NOT NULL COMMENT 'if empty: public. else: element is just visible in group x',
  `hidden` int(11) NOT NULL COMMENT 'just visible for author',
  `votes` int(11) NOT NULL DEFAULT '0',
  `score` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `folders`
--

INSERT INTO `folders` (`id`, `folder`, `name`, `path`, `creator`, `timestamp`, `privacy`, `hidden`, `votes`, `score`) VALUES
(1, 0, 'universe', '', 1, 1373158724, 'p;PROTECTED', 0, 0, 0),
(2, 1, 'userFiles', '/userFiles', 1, 1373158735, 'p;PROTECTED', 0, 2, 0),
(3, 1, 'groupFiles', '/groupFiles', 1, 1373158745, 'p;PROTECTED', 0, 0, 0),
(4, 2, '1', '/Applications/XAMPP/xamppfiles/htdocs/universe/upload/userFiles/1', 1, 1427772434, 'h', 0, 0, 0),
(5, 4, 'userPictures', '/Applications/XAMPP/xamppfiles/htdocs/universe/upload/userFiles/1/userPictures', 1, 1427772434, 'h', 0, 0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `groupAttachments`
--

CREATE TABLE `groupAttachments` (
  `id` int(11) NOT NULL,
  `group` int(11) NOT NULL,
  `item` varchar(255) NOT NULL,
  `itemId` int(11) NOT NULL,
  `author` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `validated` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `public` text NOT NULL,
  `admin` text NOT NULL,
  `membersInvite` int(11) NOT NULL COMMENT 'allow all members not just admins to invite users',
  `homeFolder` int(11) NOT NULL,
  `homeElement` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `internLinks`
--

CREATE TABLE `internLinks` (
  `id` int(11) NOT NULL,
  `parentType` varchar(255) NOT NULL,
  `parentId` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `typeId` int(11) NOT NULL,
  `title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `links`
--

CREATE TABLE `links` (
  `id` int(11) NOT NULL,
  `folder` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `link` text NOT NULL,
  `privacy` text NOT NULL,
  `author` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `votes` int(11) NOT NULL,
  `score` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `receiver` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `text` text NOT NULL,
  `read` int(11) NOT NULL,
  `crypt` int(11) NOT NULL DEFAULT '0',
  `seen` int(11) NOT NULL,
  `protocoll` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `personalEvents`
--

CREATE TABLE `personalEvents` (
  `id` int(11) NOT NULL,
  `owner` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `event` varchar(255) NOT NULL,
  `info` text NOT NULL,
  `eventId` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `seen` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `playlists`
--

CREATE TABLE `playlists` (
  `id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `privacy` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `salts`
--

CREATE TABLE `salts` (
  `type` varchar(255) NOT NULL,
  `itemId` int(11) NOT NULL,
  `receiverType` varchar(255) NOT NULL,
  `receiverId` int(11) NOT NULL,
  `salt` text NOT NULL,
  `algo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `salts`
--

INSERT INTO `salts` (`type`, `itemId`, `receiverType`, `receiverId`, `salt`, `algo`) VALUES
('auth', 1, 'user', 1, 'U2FsdGVkX19wMWrIn0nboAROK5/xn2I+GBSsOJVypN11OIrJ0zH7G6E0X7zfd5BrI5oVsMgVKbvuEssUweeQrMwKiNcOSX5qTp12bzcWol0Tvsqcw7L9v/bScMfubLExvev+vlpOdJ5sdd1kE1UzVEl2EDnV3cKlsh+NOM54eBAjjb+pDjC3npQaBu2fkAzW8kZLXZZyVxREWGks64m/KQ==', ''),
('privateKey', 1, 'user', 1, 'U2FsdGVkX1+GO1Dc+yxmIKrijqau2f29mclqXYdbLE7JbMnbz8ZMp+j6eiBZltEydkikXIoK/X5HQFQ7uFSKJx3DjrI6+OQTHQIYpM+ssA0n4b7xxptwOSovA3lmlU0yxeo0F4p5y+k5MkhYTkQW6tipa7STkDMzmQ8RYYu40E5pnYTyAu1EAR63Pb0jOZ3lAUSGAzoSxGOp3L40I3kDeA==', '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sessions`
--

CREATE TABLE `sessions` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `type` text NOT NULL,
  `title` varchar(255) NOT NULL,
  `sessionInfo` text NOT NULL,
  `fingerprint` text NOT NULL,
  `timestamp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `signatures`
--

CREATE TABLE `signatures` (
  `type` varchar(255) NOT NULL,
  `itemId` int(11) NOT NULL,
  `privateKey` text NOT NULL,
  `publicKey` text NOT NULL,
  `timestamp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `signatures`
--

INSERT INTO `signatures` (`type`, `itemId`, `privateKey`, `publicKey`, `timestamp`) VALUES
('user', 1, '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', '-----BEGIN PUBLIC KEY-----\nMIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAiJVi54hVUBogEjgaQ3Om\nPbfF/Ve5ptbgqdfn6a/o8v6+74ENbzyKvHPRQAiaBfNKSbvh2CoCjGJqLSms2Jdi\n0ck+ff6gxonav308TfS+yNzEmLk01PyrpGoH3R4gACp6/hPua8FDpLFiwq53UwAV\nhrBcmCO3ihz4OC3TzMyI4pEh28zZRdVr9c44x+JZ/D51PjHc4ubtplWuryrO/hwC\nyz2lV9obhTJQYCpl1xS+fI5vibcDUpBvcjL+xl8jLtgo13/1K1slGiohkPpgFuNL\nFiYUx0DYBzv1J1cosMkx9eUuZNonWtB4lZtV4nFRnvVPieew3ohmzl2UdFRwahIy\nxSCXbPX+CWcJ8bWQF3tSB4y3oEI1Iwcff+beOLmLNVEXpsJeIGfSEl19xrxYgk+S\nZoQZO/BBWF9d+Zht5tvr/4i1BzrUu+r1YUZh0hCrgW9ozs1OD/zhSy7nNGCdhe3n\nAhfFa5EjpPQkxhuN40xcG8BCWXJNK90HwwfNjZXjCZUtyA47Qt1mcHAYbQABHnuu\nbt8iVwpG5qNrZCbocxgT3QEFAr0unJMkIVnvdX+Sm+llsRM8r8CexgQVNSJO4nXi\ni3T7dz/bG7z2eNa3bbbKA8NAgeEkE+6Oytem40rlSK9mm74dKlpNFnETzO+zBOTz\n05VT4SFM9zy7hKz1+vY6gU0CAwEAAQ==\n-----END PUBLIC KEY-----', 1427772434);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `staticContents`
--

CREATE TABLE `staticContents` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `comment` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `staticContents`
--

INSERT INTO `staticContents` (`id`, `title`, `content`, `comment`) VALUES
(1, 'Contact', '<p style="font-size: 19pt;">Â <br>Contact | Site Notice</p>\r\n<p>Â </p>\r\n<p><b>Angaben gemaeÃŸ Â§ 5 TMG:</b><br></p>\r\n<p>Nicolas Zemke</p>\r\n<p>Transparency Everywhere</p>\r\n<p>Oldersumer StraÃŸe 47</p>\r\n<p>26603 Aurich<br><br></p>\r\n<p>Oder<br><br></p>\r\n<p>Stefan Pabst</p>\r\n<p>Transparency Everywhere</p>\r\n<p>Extumer Gaste 8</p>\r\n<p>26624 Suedbrookmerland</p>\r\n<p style=" font-size: 19pt;"><br><br><br>Kontakt:<br></p>\r\n<p>Â </p>\r\n<table>\r\n<tbody>\r\n<tr>\r\n<td>Telefon:</td>\r\n<td>0 49 41 88 00 1</td>\r\n</tr>\r\n<tr>\r\n<td>E-Mail:</td>\r\n<td>info[aaat]transparency-everywhere.com</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style="font-size: 19pt;"><br><br><br>Haftungsausschluss:</br></p>\r\n<p><br><br><strong>Haftung fuer Inhalte</strong><br></p>\r\n<p>Die Inhalte unserer Seiten wurden mit groeÃŸter Sorgfalt erstellt. Fuer die Richtigkeit, Vollstaendigkeit und Aktualitaet der Inhalte koennen wir jedoch keine Gewaehr uebernehmen. Als Diensteanbieter sind wir gemaeÃŸ Â§ 7 Abs.1 TMG fuer eigene Inhalte auf diesen Seiten nach den allgemeinen Gesetzen verantwortlich. Nach Â§Â§ 8 bis 10 TMG sind wir als Diensteanbieter jedoch nicht verpflichtet, uebermittelte oder gespeicherte fremde Informationen zu ueberwachen oder nach Umstaenden zu forschen, die auf eine rechtswidrige Taetigkeit hinweisen. Verpflichtungen zur Entfernung oder Sperrung der Nutzung von Informationen nach den allgemeinen Gesetzen bleiben hiervon unberuehrt. Eine diesbezuegliche Haftung ist jedoch erst ab dem Zeitpunkt der Kenntnis einer konkreten Rechtsverletzung moeglich. Bei Bekanntwerden von entsprechenden Rechtsverletzungen werden wir diese Inhalte umgehend entfernen.</p>\r\n<p><br><br><strong>Haftung fuer Links</strong><br></p>\r\n<p>Unser Angebot enthaelt Links zu externen Webseiten Dritter, auf deren Inhalte wir keinen Einfluss haben. Deshalb koennen wir fuer diese fremden Inhalte auch keine Gewaehr uebernehmen. Fuer die Inhalte der verlinkten Seiten ist stets der jeweilige Anbieter oder Betreiber der Seiten verantwortlich. Die verlinkten Seiten wurden zum Zeitpunkt der Verlinkung auf moegliche RechtsverstoeÃŸe ueberprueft. Rechtswidrige Inhalte waren zum Zeitpunkt der Verlinkung nicht erkennbar. Eine permanente inhaltliche Kontrolle der verlinkten Seiten ist jedoch ohne konkrete Anhaltspunkte einer Rechtsverletzung nicht zumutbar. Bei Bekanntwerden von Rechtsverletzungen werden wir derartige Links umgehend entfernen.</p>\r\n<p><br><br><strong>Urheberrecht</strong><br></p>\r\n<p>Die durch die Seitenbetreiber erstellten Inhalte und Werke auf diesen Seiten unterliegen dem deutschen Urheberrecht. Die Vervielfaeltigung, Bearbeitung, Verbreitung und jede Art der Verwertung auÃŸerhalb der Grenzen des Urheberrechtes beduerfen der schriftlichen Zustimmung des jeweiligen Autors bzw. Erstellers. Soweit die Inhalte auf dieser Seite nicht vom Betreiber erstellt wurden, werden die Urheberrechte Dritter beachtet. Insbesondere werden Inhalte Dritter als solche gekennzeichnet. Sollten Sie trotzdem auf eine Urheberrechtsverletzung aufmerksam werden, bitten wir um einen entsprechenden Hinweis. Bei Bekanntwerden von Rechtsverletzungen werden wir derartige Inhalte umgehend entfernen.</p>\r\n<p><br><br><strong>Datenschutz</strong><br></p>\r\n<p>Die Nutzung unserer Webseite ist in der Regel ohne Angabe personenbezogener Daten moeglich. Soweit auf unseren Seiten personenbezogene Daten (beispielsweise Name, Anschrift oder eMail-Adressen) erhoben werden, erfolgt dies, soweit moeglich, stets auf freiwilliger Basis. Diese Daten werden ohne Ihre ausdrueckliche Zustimmung nicht an Dritte weitergegeben.</p>\r\n<p>Wir weisen darauf hin, dass die Datenuebertragung im Internet (z.B. bei der Kommunikation per E-Mail) Sicherheitsluecken aufweisen kann. Ein lueckenloser Schutz der Daten vor dem Zugriff durch Dritte ist nicht moeglich.</p>\r\n<p>Der Nutzung von im Rahmen der Impressumspflicht veroeffentlichten Kontaktdaten durch Dritte zur uebersendung von nicht ausdruecklich angeforderter Werbung und Informationsmaterialien wird hiermit ausdruecklich widersprochen. Die Betreiber der Seiten behalten sich ausdruecklich rechtliche Schritte im Falle der unverlangten Zusendung von Werbeinformationen, etwa durch Spam-Mails, vor.</p>\r\n<p>\r\nUnserer Website verwendet Piwik, dabei handelt es sich um einen sogenannten Webanalysedienst. Piwik verwendet sog. â€œCookiesâ€, dass sind Textdateien, die auf Ihrem Computer gespeichert werden und die unsererseits eine Analyse der Benutzung der Webseite ermÃ¶glichen. Zu diesem Zweck werden die durch den Cookie erzeugten Nutzungsinformationen (einschlieÃŸlich Ihrer gekÃ¼rzten IP-Adresse) an unseren Server Ã¼bertragen und zu Nutzungsanalysezwecken gespeichert, was der Webseitenoptimierung unsererseits dient. Ihre IP-Adresse wird bei diesem Vorgang umgeÂ­hend anonyÂ­miÂ­siert, so dass Sie als Nutzer fÃ¼r uns anonym bleiben. Die durch den Cookie erzeugten Informationen Ã¼ber Ihre Benutzung dieser Webseite werden nicht an Dritte weitergegeben. Sie kÃ¶nnen die Verwendung der Cookies durch eine entsprechende Einstellung Ihrer Browser Software verhindern, es kann jedoch sein, dass Sie in diesem Fall gegebenenfalls nicht sÃ¤mtliche Funktionen dieser Website voll umfÃ¤nglich nutzen kÃ¶nnen.(www.datenschutzbeauftragter-info.de)\r\n</p>\r\n', 'Impressum/Contact');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `privacy` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE `user` (
  `userid` int(11) NOT NULL,
  `usergroup` int(11) NOT NULL DEFAULT '0',
  `username` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `cypher` varchar(255) NOT NULL DEFAULT 'md5',
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
  `passwordHash` text NOT NULL,
  `backgroundImg` text NOT NULL,
  `startLink` varchar(255) NOT NULL DEFAULT 'doit.php?action=showStartMessage',
  `buddySuggestions` text NOT NULL COMMENT 'is used to save already suggested users',
  `profile_info` text NOT NULL,
  `application_preset` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`userid`, `usergroup`, `username`, `password`, `cypher`, `homefolder`, `myFiles`, `profilepictureelement`, `userPicture`, `email`, `regdate`, `lastactivity`, `birthdate`, `realname`, `home`, `place`, `gender`, `school1`, `school2`, `school3`, `university1`, `university2`, `employer`, `typeofwork`, `status`, `openChatWindows`, `priv_activateProfile`, `priv_showProfile`, `priv_profileInformation`, `priv_profilePicture`, `priv_profileFav`, `priv_profileLog`, `priv_activateFeed`, `priv_buddyRequest`, `priv_foreignerMessages`, `priv_foreignerFeeds`, `hash`, `passwordHash`, `backgroundImg`, `startLink`, `buddySuggestions`, `profile_info`, `application_preset`) VALUES
(1, 0, 'admin', '66e5d8ae01617241fb4e66ef7170b1a60c7e6809ef37117dfdbd12808d4824897dfb13e32c8fe5aa403f6105d58dd92346ec84e4d06b63761a6f0bfd28ab9223', 'sha512_2', 4, 3, 4, '', '', 1427772434, 1481913224, '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1, 1, 1, 1, 0, 0, 1, 1, 0, 0, 'ed951a5526c2084dc8e5839ba98e84fd', '', '', 'doit.php?action=showStartMessage', '', '', 'social');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `userApps`
--

CREATE TABLE `userApps` (
  `app_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `userGroups`
--

CREATE TABLE `userGroups` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `showAdminPanel` tinyint(1) NOT NULL,
  `protectFileSystemItems` tinyint(1) NOT NULL,
  `editProtectedFilesystemItem` tinyint(1) NOT NULL,
  `undeletableFilesystemItems` tinyint(1) NOT NULL DEFAULT '0',
  `editUndeletableFilesystemItems` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `userGroups`
--

INSERT INTO `userGroups` (`id`, `title`, `showAdminPanel`, `protectFileSystemItems`, `editProtectedFilesystemItem`, `undeletableFilesystemItems`, `editUndeletableFilesystemItems`) VALUES
(0, 'admin', 1, 1, 1, 1, 1),
(1, 'standard user', 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_privacy_rights`
--

CREATE TABLE `user_privacy_rights` (
  `userid` int(11) NOT NULL,
  `profile_realname` text NOT NULL,
  `profile_fav` text NOT NULL,
  `profile_files` text NOT NULL,
  `profile_playlists` text NOT NULL,
  `profile_activity` text NOT NULL,
  `buddylist` text NOT NULL,
  `info` text NOT NULL,
  `groups` text NOT NULL,
  `receive_messages` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `user_privacy_rights`
--

INSERT INTO `user_privacy_rights` (`userid`, `profile_realname`, `profile_fav`, `profile_files`, `profile_playlists`, `profile_activity`, `buddylist`, `info`, `groups`, `receive_messages`) VALUES
(1, 'p', 'p', 'p', 'p', 'p', 'p', 'p', 'p', 'p');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `adminMessages`
--
ALTER TABLE `adminMessages`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `appCenterApps`
--
ALTER TABLE `appCenterApps`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `buddylist`
--
ALTER TABLE `buddylist`
  ADD PRIMARY KEY (`owner`,`buddy`);

--
-- Indizes für die Tabelle `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `elements`
--
ALTER TABLE `elements`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `fav`
--
ALTER TABLE `fav`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `feed`
--
ALTER TABLE `feed`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `folders`
--
ALTER TABLE `folders`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `groupAttachments`
--
ALTER TABLE `groupAttachments`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `internLinks`
--
ALTER TABLE `internLinks`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `links`
--
ALTER TABLE `links`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `personalEvents`
--
ALTER TABLE `personalEvents`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `playlists`
--
ALTER TABLE `playlists`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `staticContents`
--
ALTER TABLE `staticContents`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userid`);

--
-- Indizes für die Tabelle `userApps`
--
ALTER TABLE `userApps`
  ADD PRIMARY KEY (`app_id`,`user_id`),
  ADD UNIQUE KEY `app_id` (`app_id`,`user_id`);

--
-- Indizes für die Tabelle `userGroups`
--
ALTER TABLE `userGroups`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `user_privacy_rights`
--
ALTER TABLE `user_privacy_rights`
  ADD PRIMARY KEY (`userid`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `adminMessages`
--
ALTER TABLE `adminMessages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `appCenterApps`
--
ALTER TABLE `appCenterApps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `elements`
--
ALTER TABLE `elements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT für Tabelle `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `fav`
--
ALTER TABLE `fav`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `feed`
--
ALTER TABLE `feed`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT für Tabelle `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT für Tabelle `folders`
--
ALTER TABLE `folders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT für Tabelle `groupAttachments`
--
ALTER TABLE `groupAttachments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `internLinks`
--
ALTER TABLE `internLinks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `links`
--
ALTER TABLE `links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `personalEvents`
--
ALTER TABLE `personalEvents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `playlists`
--
ALTER TABLE `playlists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `staticContents`
--
ALTER TABLE `staticContents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT für Tabelle `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT für Tabelle `userGroups`
--
ALTER TABLE `userGroups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
