-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июл 20 2016 г., 16:06
-- Версия сервера: 5.5.44-0ubuntu0.14.04.1
-- Версия PHP: 5.5.9-1ubuntu4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `rssdatabase`
--

-- --------------------------------------------------------

--
-- Структура таблицы `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `news_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `rss_url_list_id` bigint(20) unsigned NOT NULL,
  `news_title` text NOT NULL,
  `news_description` text NOT NULL,
  `news_link` text NOT NULL,
  `publication_date` int(10) unsigned NOT NULL,
  `read` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`news_id`),
  KEY `rss_url_list_id` (`rss_url_list_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `rss_url_list`
--

CREATE TABLE IF NOT EXISTS `rss_url_list` (
  `rss_url_list_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rss_url` text NOT NULL,
  PRIMARY KEY (`rss_url_list_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
