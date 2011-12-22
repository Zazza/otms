-- phpMyAdmin SQL Dump
-- version 3.3.7deb3
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Дек 23 2011 г., 17:02
-- Версия сервера: 5.1.58
-- Версия PHP: 5.3.8-1+b1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `otms_free`
--

-- --------------------------------------------------------

--
-- Структура таблицы `comments_status`
--

CREATE TABLE IF NOT EXISTS `comments_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `comments_status`
--

INSERT INTO `comments_status` (`id`, `status`) VALUES
(1, 'Готово'),
(2, 'Уточнить'),
(3, 'Отправить в другой отдел'),
(4, 'Отложено');

-- --------------------------------------------------------

--
-- Структура таблицы `draft`
--

CREATE TABLE IF NOT EXISTS `draft` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL,
  `who` int(11) NOT NULL,
  `imp` tinyint(4) NOT NULL,
  `secure` tinyint(4) NOT NULL DEFAULT '0',
  `text` text NOT NULL,
  `opening` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ending` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `gid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `draft`
--


-- --------------------------------------------------------

--
-- Структура таблицы `draft_attach`
--

CREATE TABLE IF NOT EXISTS `draft_attach` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL,
  `md5` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tid` (`tid`,`md5`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `draft_attach`
--


-- --------------------------------------------------------

--
-- Структура таблицы `draft_deadline`
--

CREATE TABLE IF NOT EXISTS `draft_deadline` (
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
-- Дамп данных таблицы `draft_deadline`
--


-- --------------------------------------------------------

--
-- Структура таблицы `draft_responsible`
--

CREATE TABLE IF NOT EXISTS `draft_responsible` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `gid` int(11) NOT NULL DEFAULT '0',
  `all` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq` (`tid`,`uid`,`gid`,`all`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `draft_responsible`
--


-- --------------------------------------------------------

--
-- Структура таблицы `fm_fs`
--

CREATE TABLE IF NOT EXISTS `fm_fs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `md5` varchar(64) NOT NULL,
  `filename` varchar(256) NOT NULL,
  `pdirid` int(11) NOT NULL DEFAULT '0',
  `size` int(11) NOT NULL,
  `close` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `fm_fs`
--


-- --------------------------------------------------------

--
-- Структура таблицы `group_tt`
--

CREATE TABLE IF NOT EXISTS `group_tt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `group_tt`
--


-- --------------------------------------------------------

--
-- Структура таблицы `logs`
--

CREATE TABLE IF NOT EXISTS `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(8) NOT NULL,
  `event` text NOT NULL,
  `uid` int(11) NOT NULL,
  `oid` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `logs`
--


-- --------------------------------------------------------

--
-- Структура таблицы `logs_object`
--

CREATE TABLE IF NOT EXISTS `logs_object` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `log_oid` int(11) NOT NULL,
  `key` varchar(64) NOT NULL,
  `val` text NOT NULL,
  KEY `id` (`id`),
  KEY `log_oid` (`log_oid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `logs_object`
--


-- --------------------------------------------------------

--
-- Структура таблицы `mail_contacts`
--

CREATE TABLE IF NOT EXISTS `mail_contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL,
  `email` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `oid` (`oid`,`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `mail_contacts`
--


-- --------------------------------------------------------

--
-- Структура таблицы `objects`
--

CREATE TABLE IF NOT EXISTS `objects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template` int(11) NOT NULL,
  `typeid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
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
  `title` varchar(128) NOT NULL,
  `val` text NOT NULL,
  `who` int(11) NOT NULL,
  `euid` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `edittime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `who` (`who`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `objects_advanced`
--


-- --------------------------------------------------------

--
-- Структура таблицы `objects_forms`
--

CREATE TABLE IF NOT EXISTS `objects_forms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `objects_forms`
--


-- --------------------------------------------------------

--
-- Структура таблицы `objects_forms_fields`
--

CREATE TABLE IF NOT EXISTS `objects_forms_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ofid` int(11) NOT NULL,
  `field` varchar(128) NOT NULL,
  `expand` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `objects_forms_fields`
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
  `uid` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `oid` (`oid`,`fid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `objects_vals`
--


-- --------------------------------------------------------

--
-- Структура таблицы `otms_fastmenu`
--

CREATE TABLE IF NOT EXISTS `otms_fastmenu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `otms_fastmenu`
--

INSERT INTO `otms_fastmenu` (`id`, `content`) VALUES
(1, '{"0":"\n<img src="/freeotms/img/plus-button.png" alt="" style="vertical-align: middle;" border="0">\nНовая задача\n"}');

-- --------------------------------------------------------

--
-- Структура таблицы `otms_mail`
--

CREATE TABLE IF NOT EXISTS `otms_mail` (
  `email` varchar(128) NOT NULL,
  `server` varchar(128) NOT NULL,
  `protocol` varchar(8) NOT NULL,
  `port` int(11) NOT NULL,
  `auth` tinyint(4) NOT NULL,
  `login` varchar(64) NOT NULL,
  `password` varchar(64) NOT NULL,
  `ssl` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `otms_mail`
--


-- --------------------------------------------------------

--
-- Структура таблицы `otms_menu`
--

CREATE TABLE IF NOT EXISTS `otms_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `otms_menu`
--

INSERT INTO `otms_menu` (`id`, `content`) VALUES
(1, '{"0":"Задачи","1":"Объекты","2":"Пользователи","3":"Система"}');

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
  `name` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`)
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
  `remote_id` int(11) NOT NULL DEFAULT '0',
  `mail_id` int(11) NOT NULL DEFAULT '0',
  `oid` int(11) NOT NULL,
  `who` int(11) NOT NULL,
  `imp` tinyint(4) NOT NULL,
  `secure` tinyint(4) NOT NULL DEFAULT '0',
  `text` text NOT NULL,
  `opening` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `edittime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ending` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `gid` int(11) NOT NULL DEFAULT '0',
  `close` tinyint(4) NOT NULL DEFAULT '0',
  `cuid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `text` (`text`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `troubles`
--


-- --------------------------------------------------------

--
-- Структура таблицы `troubles_attach`
--

CREATE TABLE IF NOT EXISTS `troubles_attach` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL,
  `md5` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tid` (`tid`,`md5`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `troubles_attach`
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
  `remote` tinyint(4) NOT NULL DEFAULT '0',
  `mail_id` int(11) NOT NULL DEFAULT '0',
  `object` int(11) NOT NULL DEFAULT '0',
  `sendmail` tinyint(4) NOT NULL DEFAULT '0',
  `text` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `troubles_discussion`
--


-- --------------------------------------------------------

--
-- Структура таблицы `troubles_discussion_attach`
--

CREATE TABLE IF NOT EXISTS `troubles_discussion_attach` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tdid` int(11) NOT NULL,
  `md5` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tid` (`tdid`,`md5`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `troubles_discussion_attach`
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
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq` (`tid`,`uid`,`gid`,`all`)
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
-- Структура таблицы `troubles_view`
--

CREATE TABLE IF NOT EXISTS `troubles_view` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `tid` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`,`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `troubles_view`
--


-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(32) NOT NULL,
  `pass` varchar(32) NOT NULL,
  `name` varchar(64) NOT NULL,
  `soname` varchar(64) NOT NULL,
  `signature` varchar(64) NOT NULL,
  `icq` varchar(64) NOT NULL,
  `skype` varchar(64) NOT NULL,
  `adres` varchar(256) NOT NULL,
  `phone` varchar(16) NOT NULL,
  `avatar` varchar(32) NOT NULL,
  `email` varchar(64) NOT NULL,
  `notify` tinyint(4) NOT NULL,
  `time_notify` time NOT NULL DEFAULT '08:00:00',
  `last_notify` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `email_for_task` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `pass`, `name`, `soname`, `signature`, `icq`, `skype`, `adres`, `phone`, `avatar`, `email`, `notify`, `time_notify`, `last_notify`, `email_for_task`) VALUES
(1, 'otmsadmin', '038d38e540dc0d88d29f8b406ea04b97', 'Имя', 'Фамилия', '', '', '', '', '', '', 'mail@domen.ru', 1, '08:00:00', '2011-09-29 08:00:04', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `users_auth`
--

CREATE TABLE IF NOT EXISTS `users_auth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `auth` tinyint(4) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `users_auth`
--

INSERT INTO `users_auth` (`id`, `uid`, `auth`, `timestamp`) VALUES
(1, 1, 0, '2011-12-23 16:47:51'),
(2, 1, 1, '2011-12-23 16:48:06'),
(3, 1, 0, '2011-12-23 17:01:13'),
(4, 1, 1, '2011-12-23 17:01:21');

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
(1, 'Разработчик');

-- --------------------------------------------------------

--
-- Структура таблицы `users_priv`
--

CREATE TABLE IF NOT EXISTS `users_priv` (
  `id` int(11) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `group` smallint(6) NOT NULL DEFAULT '0',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users_priv`
--

INSERT INTO `users_priv` (`id`, `admin`, `group`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `users_settings`
--

CREATE TABLE IF NOT EXISTS `users_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `skin` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `users_settings`
--

INSERT INTO `users_settings` (`id`, `uid`, `skin`) VALUES
(1, 1, 'standart');

-- --------------------------------------------------------

--
-- Структура таблицы `users_subgroup`
--

CREATE TABLE IF NOT EXISTS `users_subgroup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Дамп данных таблицы `users_subgroup`
--

INSERT INTO `users_subgroup` (`id`, `pid`, `name`) VALUES
(1, 1, 'Разработчик');
