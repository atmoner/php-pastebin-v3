-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le : Jeu 07 Février 2013 à 18:24
-- Version du serveur: 5.5.28
-- Version de PHP: 5.3.10-1ubuntu3.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données: `default_schema`
--

-- --------------------------------------------------------

--
-- Structure de la table `lang`
--

CREATE TABLE IF NOT EXISTS `lang` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `code` varchar(12) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=56 ;

--
-- Contenu de la table `lang`
--

INSERT INTO `lang` (`id`, `code`, `description`) VALUES
(1, 'c', 'C'),
(2, 'css', 'CSS'),
(3, 'cpp', 'C++'),
(4, 'html4strict', 'HTML (4 Strict)'),
(5, 'java', 'Java'),
(6, 'perl', 'Perl'),
(7, 'php', 'PHP'),
(8, 'python', 'Python'),
(9, 'ruby', 'Ruby'),
(10, 'text', 'Plain Text'),
(11, 'asm', 'ASM (Nasm Syntax)'),
(12, 'xhtml', 'XHTML'),
(13, 'actionscript', 'Actionscript'),
(14, 'ada', 'ADA'),
(15, 'apache', 'Apache Log'),
(16, 'applescript', 'AppleScript'),
(17, 'autoit', 'AutoIT'),
(18, 'bash', 'Bash'),
(19, 'bptzbasic', 'BptzBasic'),
(20, 'c_mac', 'C for Macs'),
(21, 'csharp', 'C#'),
(22, 'ColdFusion', 'coldfusion'),
(23, 'delphi', 'Delphi'),
(24, 'eiffel', 'Eiffel'),
(25, 'fortran', 'Fortran'),
(26, 'freebasic', 'FreeBasic'),
(27, 'gml', 'GML'),
(28, 'groovy', 'Groovy'),
(29, 'inno', 'Inno'),
(30, 'java5', 'Java 5'),
(31, 'javascript', 'Javascript'),
(32, 'latex', 'LaTeX'),
(33, 'mirc', 'mIRC'),
(34, 'mysql', 'MySQL'),
(35, 'nsis', 'NSIS'),
(36, 'objc', 'Objective C'),
(37, 'ocaml', 'OCaml'),
(38, 'oobas', 'OpenOffice BASIC'),
(39, 'orcale8', 'Orcale 8 SQL'),
(40, 'pascal', 'Pascal'),
(41, 'plsql', 'PL/SQL'),
(42, 'qbasic', 'Q(uick)BASIC'),
(43, 'robots', 'robots.txt'),
(44, 'scheme', 'Scheme'),
(45, 'sdlbasic', 'SDLBasic'),
(46, 'smalltalk', 'Smalltalk'),
(47, 'smarty', 'Smarty'),
(48, 'sql', 'SQL'),
(49, 'tcl', 'TCL'),
(50, 'vbnet', 'VB.NET'),
(51, 'vb', 'Visual BASIC'),
(52, 'winbatch', 'Winbatch'),
(53, 'xml', 'XML'),
(54, 'z80', 'z80 ASM'),
(55, '4cs', 'gadv 4Cs');

-- --------------------------------------------------------

--
-- Structure de la table `pastes`
--

CREATE TABLE IF NOT EXISTS `pastes` (
  `id` int(200) NOT NULL AUTO_INCREMENT,
  `userid` int(50) NOT NULL,
  `uniqueid` varchar(10) NOT NULL,
  `title` varchar(30) NOT NULL DEFAULT 'Untitled',
  `lang` varchar(30) NOT NULL,
  `paste` longtext CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `date` varchar(10) NOT NULL,
  `expire` varchar(30) NOT NULL,
  `exposure` enum('public','private') NOT NULL,
  `hits` varchar(100) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `pastes`
--

INSERT INTO `pastes` (`id`, `userid`, `uniqueid`, `title`, `lang`, `paste`, `date`, `expire`, `exposure`, `hits`) VALUES
(1, 1, 'ga3HtNB3', 'Your first paste', 'c', 'This is your first paste', '1353695525', 'never', 'public', '3');

-- --------------------------------------------------------

--
-- Structure de la table `plugins`
--

CREATE TABLE IF NOT EXISTS `plugins` (
  `filename` varchar(127) COLLATE utf8_bin NOT NULL DEFAULT '',
  `action` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`filename`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


-- --------------------------------------------------------

--
-- Structure de la table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `key` varchar(30) NOT NULL,
  `value` varchar(3000) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `settings`
--

INSERT INTO `settings` (`key`, `value`) VALUES
('baseurl', 'http://localhost'),
('title', 'Php-pastebin v3'),
('timecache', '0'),
('lang', 'en'),
('theme', 'bootstrap'),
('metad', 'rien'),
('metak', 'rien'),
('paypalmail', 'contact@yourdomain.com'),
('maxline_1', '200'),
('maxline_2', '5000'),
('maxline_3', '10000'),
('maxline_4', '10'),
('amout', '10'),
('submit', 'Submit');

-- --------------------------------------------------------

--
-- Structure de la table `statuts`
--

CREATE TABLE IF NOT EXISTS `statuts` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `level` varchar(50) NOT NULL,
  `maxlines` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `statuts`
--

INSERT INTO `statuts` (`id`, `level`, `maxlines`) VALUES
(1, 'Guest', '200'),
(2, 'User', '5000'),
(3, 'Premium', '10000'),
(4, 'Admin', '10');

-- --------------------------------------------------------

--
-- Structure de la table `tasks`
--

CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `time` int(10) NOT NULL DEFAULT '0',
  `last_time` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `tasks`
--

INSERT INTO `tasks` (`id`, `time`, `last_time`) VALUES
(1, 600, 1360250417),
(2, 3600, 1360247265),
(3, 86400, 1360240133),
(4, 2592000, 1358188741);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `pass` varchar(200) NOT NULL,
  `mail` varchar(50) NOT NULL,
  `level` varchar(5) NOT NULL,
  `seemail` enum('true','false') NOT NULL DEFAULT 'false',
  `signature` varchar(250) NOT NULL,
  `location` varchar(50) NOT NULL,
  `website` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`id`, `name`, `pass`, `mail`, `level`, `seemail`, `signature`, `location`, `website`) VALUES
(0, 'Anonymous', '', '', '1', 'false', '', '', '');

