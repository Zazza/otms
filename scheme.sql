-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Май 25 2011 г., 10:57
-- Версия сервера: 5.5.8
-- Версия PHP: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `tt3`
--

-- --------------------------------------------------------

--
-- Структура таблицы `group_tt`
--

CREATE TABLE IF NOT EXISTS `group_tt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `group_tt`
--

INSERT INTO `group_tt` (`id`, `name`) VALUES
(1, 'закрыто');

-- --------------------------------------------------------

--
-- Структура таблицы `objects`
--

CREATE TABLE IF NOT EXISTS `objects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template` int(11) NOT NULL,
  `typeid` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `template` (`template`),
  KEY `typeid` (`typeid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `objects`
--


-- --------------------------------------------------------

--
-- Структура таблицы `objects_advanced`
--

CREATE TABLE IF NOT EXISTS `objects_advanced` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL,
  `val` text NOT NULL,
  `who` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `who` (`who`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `objects_advanced`
--


-- --------------------------------------------------------

--
-- Структура таблицы `objects_tags`
--

CREATE TABLE IF NOT EXISTS `objects_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oaid` int(11) NOT NULL,
  `tag` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `oaid` (`oaid`,`tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `objects_tags`
--


-- --------------------------------------------------------

--
-- Структура таблицы `objects_vals`
--

CREATE TABLE IF NOT EXISTS `objects_vals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL,
  `fid` int(11) NOT NULL,
  `val` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `oid` (`oid`,`fid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `objects_vals`
--


-- --------------------------------------------------------

--
-- Структура таблицы `templates`
--

CREATE TABLE IF NOT EXISTS `templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `templates`
--


-- --------------------------------------------------------

--
-- Структура таблицы `templates_fields`
--

CREATE TABLE IF NOT EXISTS `templates_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL,
  `field` varchar(128) NOT NULL,
  `main` tinyint(4) NOT NULL DEFAULT '0',
  `expand` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `templates_fields`
--


-- --------------------------------------------------------

--
-- Структура таблицы `templates_type`
--

CREATE TABLE IF NOT EXISTS `templates_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `templates_type`
--


-- --------------------------------------------------------

--
-- Структура таблицы `troubles`
--

CREATE TABLE IF NOT EXISTS `troubles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL,
  `who` int(11) NOT NULL,
  `imp` tinyint(4) NOT NULL,
  `secure` tinyint(4) NOT NULL DEFAULT '0',
  `text` text NOT NULL,
  `opening` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ending` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `gid` int(11) NOT NULL DEFAULT '0',
  `close` tinyint(4) NOT NULL DEFAULT '0',
  `cuid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `troubles`
--


-- --------------------------------------------------------

--
-- Структура таблицы `troubles_deadline`
--

CREATE TABLE IF NOT EXISTS `troubles_deadline` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `opening` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deadline` int(10) unsigned NOT NULL DEFAULT '0',
  `iteration` int(10) unsigned NOT NULL DEFAULT '0',
  `timetype_iteration` varchar(8) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `troubles_deadline`
--


-- --------------------------------------------------------

--
-- Структура таблицы `troubles_discussion`
--

CREATE TABLE IF NOT EXISTS `troubles_discussion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `text` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `troubles_discussion`
--


-- --------------------------------------------------------

--
-- Структура таблицы `troubles_responsible`
--

CREATE TABLE IF NOT EXISTS `troubles_responsible` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `gid` int(11) NOT NULL DEFAULT '0',
  `all` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `troubles_responsible`
--


-- --------------------------------------------------------

--
-- Структура таблицы `troubles_spam`
--

CREATE TABLE IF NOT EXISTS `troubles_spam` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `troubles_spam`
--


-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(32) NOT NULL,
  `pass` varchar(32) NOT NULL,
  `hash` varchar(32) NOT NULL,
  `name` varchar(64) NOT NULL,
  `soname` varchar(64) NOT NULL,
  `email` varchar(64) NOT NULL,
  `notify` tinyint(4) NOT NULL,
  `time_notify` time NOT NULL DEFAULT '08:00:00',
  `last_notify` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `pass`, `hash`, `name`, `soname`, `email`, `notify`, `time_notify`, `last_notify`) VALUES
(1, 'admin', 'c3284d0f94606de1fd2af172aba15bf3', '', 'admin', 'admin', 'admin@example.com', 0, '08:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Структура таблицы `users_group`
--

CREATE TABLE IF NOT EXISTS `users_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `users_group`
--

INSERT INTO `users_group` (`id`, `name`) VALUES
(1, 'Админы');

-- --------------------------------------------------------

--
-- Структура таблицы `users_priv`
--

CREATE TABLE IF NOT EXISTS `users_priv` (
  `id` int(11) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `readonly` tinyint(1) NOT NULL DEFAULT '0',
  `group` smallint(6) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users_priv`
--

INSERT INTO `users_priv` (`id`, `admin`, `readonly`, `group`) VALUES
(1, 1, 0, 2);
