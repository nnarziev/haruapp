-- phpMyAdmin SQL Dump
-- version 4.4.15.10
-- https://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Окт 17 2018 г., 17:24
-- Версия сервера: 5.7.23
-- Версия PHP: 7.0.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `haruapp`
--

-- --------------------------------------------------------

--
-- Структура таблицы `cfx_config`
--

CREATE TABLE IF NOT EXISTS `cfx_config` (
  `config_title` varchar(255) NOT NULL DEFAULT '',
  `config_admin` varchar(255) NOT NULL DEFAULT '',
  `config_common_skin` varchar(255) NOT NULL DEFAULT '',
  `config_agreement` text NOT NULL,
  `config_privacy` text NOT NULL,
  `config_pages` tinyint(4) NOT NULL DEFAULT '0',
  `config_page_rows` tinyint(4) NOT NULL DEFAULT '0',
  `config_page_rows_list` text NOT NULL,
  `config_search_part` int(11) NOT NULL DEFAULT '0',
  `config_image_extension` varchar(255) NOT NULL DEFAULT '',
  `config_flash_extension` varchar(255) NOT NULL DEFAULT '',
  `config_movie_extension` varchar(255) NOT NULL DEFAULT '',
  `config_smtp_secure` varchar(255) NOT NULL DEFAULT '',
  `config_smtp_host` varchar(255) NOT NULL DEFAULT '',
  `config_smtp_port` int(10) NOT NULL DEFAULT '0',
  `config_smtp_username` varchar(255) NOT NULL DEFAULT '',
  `config_smtp_password` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `cfx_gift`
--

CREATE TABLE IF NOT EXISTS `cfx_gift` (
  `gift_no` int(11) NOT NULL,
  `member_id` varchar(20) NOT NULL DEFAULT '',
  `member_key` varchar(255) NOT NULL DEFAULT '',
  `gift_program` varchar(20) NOT NULL DEFAULT '0',
  `gift_count` tinyint(4) NOT NULL DEFAULT '0',
  `gift_num` tinyint(4) NOT NULL DEFAULT '0',
  `gift_memo` varchar(255) NOT NULL DEFAULT '',
  `gift_confirm_datetime` datetime DEFAULT NULL,
  `gift_created_datetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `cfx_group`
--

CREATE TABLE IF NOT EXISTS `cfx_group` (
  `group_no` int(11) NOT NULL,
  `group_name` varchar(255) NOT NULL DEFAULT '',
  `group_activated` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `cfx_group_label`
--

CREATE TABLE IF NOT EXISTS `cfx_group_label` (
  `label_no` int(11) NOT NULL,
  `group_no` int(11) NOT NULL,
  `label_name` varchar(255) NOT NULL DEFAULT '',
  `label_activated` tinyint(1) NOT NULL DEFAULT '1',
  `label_created_datetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `cfx_harucard`
--

CREATE TABLE IF NOT EXISTS `cfx_harucard` (
  `hc_no` int(11) NOT NULL,
  `hc_code` varchar(20) NOT NULL DEFAULT '',
  `hc_type` varchar(20) NOT NULL DEFAULT '',
  `hc_category` varchar(20) NOT NULL DEFAULT '',
  `hc_category_name` varchar(255) NOT NULL DEFAULT '',
  `hc_title` varchar(255) NOT NULL DEFAULT '',
  `hc_top_class` varchar(255) NOT NULL DEFAULT '',
  `hc_image_class` varchar(255) NOT NULL DEFAULT '',
  `hc_image` varchar(1000) NOT NULL DEFAULT '',
  `hc_content_class` varchar(255) NOT NULL,
  `hc_content` varchar(4000) NOT NULL DEFAULT '',
  `hc_url_class` varchar(255) NOT NULL DEFAULT '',
  `hc_url_link` varchar(1000) NOT NULL DEFAULT '',
  `hc_url_title` varchar(1000) NOT NULL DEFAULT '',
  `hc_dot_class` varchar(255) NOT NULL,
  `hc_activated` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `cfx_harucard_bookmark`
--

CREATE TABLE IF NOT EXISTS `cfx_harucard_bookmark` (
  `member_id` varchar(20) NOT NULL DEFAULT '',
  `member_key` varchar(255) NOT NULL DEFAULT '',
  `member_no` int(11) NOT NULL DEFAULT '0',
  `hc_part_no` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bookmark1` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bookmark2` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bookmark3` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bookmark4` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bookmark5` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bookmark6` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bookmark7` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bookmark8` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bookmark9` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bookmark10` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bookmark11` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bookmark12` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bookmark13` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bookmark14` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bookmark15` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bookmark16` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bookmark17` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bookmark18` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bookmark19` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bookmark20` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bookmark21` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bookmark22` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bookmark23` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bookmark24` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bookmark25` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bookmark26` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bookmark27` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bookmark28` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bookmark29` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bookmark30` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bookmark31` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bookmark32` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bookmark33` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bookmark34` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bookmark35` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bookmark36` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bookmark37` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bookmark38` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bookmark39` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bookmark40` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bookmark41` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bookmark42` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bookmark43` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bookmark44` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bookmark45` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bookmark46` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bookmark47` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bookmark48` tinyint(4) NOT NULL DEFAULT '0',
  `hc_last_datetime` datetime DEFAULT NULL,
  `hc_created_datetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `cfx_harucard_join`
--

CREATE TABLE IF NOT EXISTS `cfx_harucard_join` (
  `member_id` varchar(20) NOT NULL DEFAULT '',
  `member_key` varchar(255) NOT NULL DEFAULT '',
  `member_no` int(11) NOT NULL DEFAULT '0',
  `hc_part1` varchar(1000) NOT NULL DEFAULT '',
  `hc_part2` varchar(1000) NOT NULL DEFAULT '',
  `hc_part3` varchar(1000) NOT NULL DEFAULT '',
  `hc_current_part` tinyint(4) NOT NULL DEFAULT '1',
  `hc_activated` tinyint(1) NOT NULL DEFAULT '1',
  `hc_join1_datetime` datetime DEFAULT NULL,
  `hc_join2_datetime` datetime DEFAULT NULL,
  `hc_join3_datetime` datetime DEFAULT NULL,
  `hc_last_datetime` datetime DEFAULT NULL,
  `hc_created_datetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `cfx_harucard_point`
--

CREATE TABLE IF NOT EXISTS `cfx_harucard_point` (
  `member_id` varchar(20) NOT NULL DEFAULT '',
  `member_key` varchar(255) NOT NULL DEFAULT '',
  `member_no` int(11) NOT NULL DEFAULT '0',
  `hc_part_no` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point1` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point2` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point3` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point4` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point5` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point6` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point7` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point8` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point9` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point10` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point11` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point12` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point13` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point14` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point15` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point16` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point17` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point18` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point19` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point20` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point21` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point22` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point23` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point24` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point25` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point26` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point27` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point28` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point29` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point30` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point31` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point32` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point33` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point34` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point35` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point36` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point37` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point38` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point39` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point40` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point41` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point42` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point43` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point44` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point45` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point46` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point47` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point48` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bonus1` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bonus2` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bonus3` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bonus4` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bonus5` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bonus6` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bonus7` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bonus8` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bonus9` tinyint(4) NOT NULL DEFAULT '0',
  `hc_point_record1` datetime DEFAULT NULL,
  `hc_point_record2` datetime DEFAULT NULL,
  `hc_point_record3` datetime DEFAULT NULL,
  `hc_point_record4` datetime DEFAULT NULL,
  `hc_point_record5` datetime DEFAULT NULL,
  `hc_point_record6` datetime DEFAULT NULL,
  `hc_point_record7` datetime DEFAULT NULL,
  `hc_point_record8` datetime DEFAULT NULL,
  `hc_point_record9` datetime DEFAULT NULL,
  `hc_point_record10` datetime DEFAULT NULL,
  `hc_point_record11` datetime DEFAULT NULL,
  `hc_point_record12` datetime DEFAULT NULL,
  `hc_point_record13` datetime DEFAULT NULL,
  `hc_point_record14` datetime DEFAULT NULL,
  `hc_point_record15` datetime DEFAULT NULL,
  `hc_point_record16` datetime DEFAULT NULL,
  `hc_point_record17` datetime DEFAULT NULL,
  `hc_point_record18` datetime DEFAULT NULL,
  `hc_point_record19` datetime DEFAULT NULL,
  `hc_point_record20` datetime DEFAULT NULL,
  `hc_point_record21` datetime DEFAULT NULL,
  `hc_point_record22` datetime DEFAULT NULL,
  `hc_point_record23` datetime DEFAULT NULL,
  `hc_point_record24` datetime DEFAULT NULL,
  `hc_point_record25` datetime DEFAULT NULL,
  `hc_point_record26` datetime DEFAULT NULL,
  `hc_point_record27` datetime DEFAULT NULL,
  `hc_point_record28` datetime DEFAULT NULL,
  `hc_point_record29` datetime DEFAULT NULL,
  `hc_point_record30` datetime DEFAULT NULL,
  `hc_point_record31` datetime DEFAULT NULL,
  `hc_point_record32` datetime DEFAULT NULL,
  `hc_point_record33` datetime DEFAULT NULL,
  `hc_point_record34` datetime DEFAULT NULL,
  `hc_point_record35` datetime DEFAULT NULL,
  `hc_point_record36` datetime DEFAULT NULL,
  `hc_point_record37` datetime DEFAULT NULL,
  `hc_point_record38` datetime DEFAULT NULL,
  `hc_point_record39` datetime DEFAULT NULL,
  `hc_point_record40` datetime DEFAULT NULL,
  `hc_point_record41` datetime DEFAULT NULL,
  `hc_point_record42` datetime DEFAULT NULL,
  `hc_point_record43` datetime DEFAULT NULL,
  `hc_point_record44` datetime DEFAULT NULL,
  `hc_point_record45` datetime DEFAULT NULL,
  `hc_point_record46` datetime DEFAULT NULL,
  `hc_point_record47` datetime DEFAULT NULL,
  `hc_point_record48` datetime DEFAULT NULL,
  `hc_bonus_record1` datetime DEFAULT NULL,
  `hc_bonus_record2` datetime DEFAULT NULL,
  `hc_bonus_record3` datetime DEFAULT NULL,
  `hc_bonus_record4` datetime DEFAULT NULL,
  `hc_bonus_record5` datetime DEFAULT NULL,
  `hc_bonus_record6` datetime DEFAULT NULL,
  `hc_bonus_record7` datetime DEFAULT NULL,
  `hc_bonus_record8` datetime DEFAULT NULL,
  `hc_bonus_record9` datetime DEFAULT NULL,
  `hc_gift1` tinyint(4) NOT NULL DEFAULT '0',
  `hc_gift2` tinyint(4) NOT NULL DEFAULT '0',
  `hc_gift_record1` datetime DEFAULT NULL,
  `hc_gift_record2` datetime DEFAULT NULL,
  `hc_gift_confirm1` datetime DEFAULT NULL,
  `hc_gift_confirm2` datetime DEFAULT NULL,
  `hc_last_datetime` datetime DEFAULT NULL,
  `hc_created_datetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `cfx_harucard_rating`
--

CREATE TABLE IF NOT EXISTS `cfx_harucard_rating` (
  `member_id` varchar(20) NOT NULL DEFAULT '',
  `member_key` varchar(255) NOT NULL DEFAULT '',
  `member_no` int(11) NOT NULL DEFAULT '0',
  `hc_part_no` tinyint(4) NOT NULL DEFAULT '0',
  `hc_rating1` tinyint(4) NOT NULL DEFAULT '0',
  `hc_rating2` tinyint(4) NOT NULL DEFAULT '0',
  `hc_rating3` tinyint(4) NOT NULL DEFAULT '0',
  `hc_rating4` tinyint(4) NOT NULL DEFAULT '0',
  `hc_rating5` tinyint(4) NOT NULL DEFAULT '0',
  `hc_rating6` tinyint(4) NOT NULL DEFAULT '0',
  `hc_rating7` tinyint(4) NOT NULL DEFAULT '0',
  `hc_rating8` tinyint(4) NOT NULL DEFAULT '0',
  `hc_rating9` tinyint(4) NOT NULL DEFAULT '0',
  `hc_rating10` tinyint(4) NOT NULL DEFAULT '0',
  `hc_rating11` tinyint(4) NOT NULL DEFAULT '0',
  `hc_rating12` tinyint(4) NOT NULL DEFAULT '0',
  `hc_rating13` tinyint(4) NOT NULL DEFAULT '0',
  `hc_rating14` tinyint(4) NOT NULL DEFAULT '0',
  `hc_rating15` tinyint(4) NOT NULL DEFAULT '0',
  `hc_rating16` tinyint(4) NOT NULL DEFAULT '0',
  `hc_rating17` tinyint(4) NOT NULL DEFAULT '0',
  `hc_rating18` tinyint(4) NOT NULL DEFAULT '0',
  `hc_rating19` tinyint(4) NOT NULL DEFAULT '0',
  `hc_rating20` tinyint(4) NOT NULL DEFAULT '0',
  `hc_rating21` tinyint(4) NOT NULL DEFAULT '0',
  `hc_rating22` tinyint(4) NOT NULL DEFAULT '0',
  `hc_rating23` tinyint(4) NOT NULL DEFAULT '0',
  `hc_rating24` tinyint(4) NOT NULL DEFAULT '0',
  `hc_rating25` tinyint(4) NOT NULL DEFAULT '0',
  `hc_rating26` tinyint(4) NOT NULL DEFAULT '0',
  `hc_rating27` tinyint(4) NOT NULL DEFAULT '0',
  `hc_rating28` tinyint(4) NOT NULL DEFAULT '0',
  `hc_rating29` tinyint(4) NOT NULL DEFAULT '0',
  `hc_rating30` tinyint(4) NOT NULL DEFAULT '0',
  `hc_rating31` tinyint(4) NOT NULL DEFAULT '0',
  `hc_rating32` tinyint(4) NOT NULL DEFAULT '0',
  `hc_rating33` tinyint(4) NOT NULL DEFAULT '0',
  `hc_rating34` tinyint(4) NOT NULL DEFAULT '0',
  `hc_rating35` tinyint(4) NOT NULL DEFAULT '0',
  `hc_rating36` tinyint(4) NOT NULL DEFAULT '0',
  `hc_rating37` tinyint(4) NOT NULL DEFAULT '0',
  `hc_rating38` tinyint(4) NOT NULL DEFAULT '0',
  `hc_rating39` tinyint(4) NOT NULL DEFAULT '0',
  `hc_rating40` tinyint(4) NOT NULL DEFAULT '0',
  `hc_rating41` tinyint(4) NOT NULL DEFAULT '0',
  `hc_rating42` tinyint(4) NOT NULL DEFAULT '0',
  `hc_rating43` tinyint(4) NOT NULL DEFAULT '0',
  `hc_rating44` tinyint(4) NOT NULL DEFAULT '0',
  `hc_rating45` tinyint(4) NOT NULL DEFAULT '0',
  `hc_rating46` tinyint(4) NOT NULL DEFAULT '0',
  `hc_rating47` tinyint(4) NOT NULL DEFAULT '0',
  `hc_rating48` tinyint(4) NOT NULL DEFAULT '0',
  `hc_last_datetime` datetime DEFAULT NULL,
  `hc_created_datetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `cfx_harucard_read`
--

CREATE TABLE IF NOT EXISTS `cfx_harucard_read` (
  `member_id` varchar(20) NOT NULL DEFAULT '',
  `member_key` varchar(255) NOT NULL DEFAULT '',
  `member_no` int(11) NOT NULL DEFAULT '0',
  `hc_part_no` tinyint(4) NOT NULL DEFAULT '0',
  `hc_read1` tinyint(4) NOT NULL DEFAULT '0',
  `hc_read2` tinyint(4) NOT NULL DEFAULT '0',
  `hc_read3` tinyint(4) NOT NULL DEFAULT '0',
  `hc_read4` tinyint(4) NOT NULL DEFAULT '0',
  `hc_read5` tinyint(4) NOT NULL DEFAULT '0',
  `hc_read6` tinyint(4) NOT NULL DEFAULT '0',
  `hc_read7` tinyint(4) NOT NULL DEFAULT '0',
  `hc_read8` tinyint(4) NOT NULL DEFAULT '0',
  `hc_read9` tinyint(4) NOT NULL DEFAULT '0',
  `hc_read10` tinyint(4) NOT NULL DEFAULT '0',
  `hc_read11` tinyint(4) NOT NULL DEFAULT '0',
  `hc_read12` tinyint(4) NOT NULL DEFAULT '0',
  `hc_read13` tinyint(4) NOT NULL DEFAULT '0',
  `hc_read14` tinyint(4) NOT NULL DEFAULT '0',
  `hc_read15` tinyint(4) NOT NULL DEFAULT '0',
  `hc_read16` tinyint(4) NOT NULL DEFAULT '0',
  `hc_read17` tinyint(4) NOT NULL DEFAULT '0',
  `hc_read18` tinyint(4) NOT NULL DEFAULT '0',
  `hc_read19` tinyint(4) NOT NULL DEFAULT '0',
  `hc_read20` tinyint(4) NOT NULL DEFAULT '0',
  `hc_read21` tinyint(4) NOT NULL DEFAULT '0',
  `hc_read22` tinyint(4) NOT NULL DEFAULT '0',
  `hc_read23` tinyint(4) NOT NULL DEFAULT '0',
  `hc_read24` tinyint(4) NOT NULL DEFAULT '0',
  `hc_read25` tinyint(4) NOT NULL DEFAULT '0',
  `hc_read26` tinyint(4) NOT NULL DEFAULT '0',
  `hc_read27` tinyint(4) NOT NULL DEFAULT '0',
  `hc_read28` tinyint(4) NOT NULL DEFAULT '0',
  `hc_read29` tinyint(4) NOT NULL DEFAULT '0',
  `hc_read30` tinyint(4) NOT NULL DEFAULT '0',
  `hc_read31` tinyint(4) NOT NULL DEFAULT '0',
  `hc_read32` tinyint(4) NOT NULL DEFAULT '0',
  `hc_read33` tinyint(4) NOT NULL DEFAULT '0',
  `hc_read34` tinyint(4) NOT NULL DEFAULT '0',
  `hc_read35` tinyint(4) NOT NULL DEFAULT '0',
  `hc_read36` tinyint(4) NOT NULL DEFAULT '0',
  `hc_read37` tinyint(4) NOT NULL DEFAULT '0',
  `hc_read38` tinyint(4) NOT NULL DEFAULT '0',
  `hc_read39` tinyint(4) NOT NULL DEFAULT '0',
  `hc_read40` tinyint(4) NOT NULL DEFAULT '0',
  `hc_read41` tinyint(4) NOT NULL DEFAULT '0',
  `hc_read42` tinyint(4) NOT NULL DEFAULT '0',
  `hc_read43` tinyint(4) NOT NULL DEFAULT '0',
  `hc_read44` tinyint(4) NOT NULL DEFAULT '0',
  `hc_read45` tinyint(4) NOT NULL DEFAULT '0',
  `hc_read46` tinyint(4) NOT NULL DEFAULT '0',
  `hc_read47` tinyint(4) NOT NULL DEFAULT '0',
  `hc_read48` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bonus1` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bonus2` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bonus3` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bonus4` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bonus5` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bonus6` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bonus7` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bonus8` tinyint(4) NOT NULL DEFAULT '0',
  `hc_bonus9` tinyint(4) NOT NULL DEFAULT '0',
  `hc_last_datetime` datetime DEFAULT NULL,
  `hc_created_datetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `cfx_harutoday_bookmark`
--

CREATE TABLE IF NOT EXISTS `cfx_harutoday_bookmark` (
  `member_id` varchar(20) NOT NULL DEFAULT '',
  `member_key` varchar(255) NOT NULL DEFAULT '',
  `member_no` int(11) NOT NULL DEFAULT '0',
  `ht_part_no` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bookmark1` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bookmark2` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bookmark3` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bookmark4` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bookmark5` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bookmark6` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bookmark7` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bookmark8` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bookmark9` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bookmark10` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bookmark11` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bookmark12` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bookmark13` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bookmark14` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bookmark15` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bookmark16` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bookmark17` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bookmark18` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bookmark19` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bookmark20` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bookmark21` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bookmark22` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bookmark23` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bookmark24` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bookmark25` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bookmark26` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bookmark27` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bookmark28` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bookmark29` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bookmark30` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bookmark31` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bookmark32` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bookmark33` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bookmark34` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bookmark35` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bookmark36` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bookmark37` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bookmark38` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bookmark39` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bookmark40` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bookmark41` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bookmark42` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bookmark43` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bookmark44` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bookmark45` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bookmark46` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bookmark47` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bookmark48` tinyint(4) NOT NULL DEFAULT '0',
  `ht_last_datetime` datetime DEFAULT NULL,
  `ht_created_datetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `cfx_harutoday_config`
--

CREATE TABLE IF NOT EXISTS `cfx_harutoday_config` (
  `ht_no` int(11) NOT NULL,
  `ht_title` varchar(255) NOT NULL DEFAULT '',
  `ht_ver_android_module1` varchar(255) NOT NULL DEFAULT '',
  `ht_link_android_module1` varchar(400) NOT NULL DEFAULT '',
  `ht_ver_ios_module1` varchar(255) NOT NULL DEFAULT '',
  `ht_link_ios_module1` varchar(400) NOT NULL DEFAULT '',
  `ht_ver_android_module2` varchar(255) NOT NULL DEFAULT '',
  `ht_link_android_module2` varchar(400) NOT NULL DEFAULT '',
  `ht_ver_ios_module2` varchar(255) NOT NULL DEFAULT '',
  `ht_link_ios_module2` varchar(400) NOT NULL DEFAULT '',
  `ht_ver_android_module3` varchar(255) NOT NULL DEFAULT '',
  `ht_link_android_module3` varchar(400) NOT NULL DEFAULT '',
  `ht_ver_ios_module3` varchar(255) NOT NULL DEFAULT '',
  `ht_link_ios_module3` varchar(400) NOT NULL DEFAULT '',
  `ht_last_datetime` datetime DEFAULT NULL,
  `ht_created_datetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `cfx_harutoday_data`
--

CREATE TABLE IF NOT EXISTS `cfx_harutoday_data` (
  `ht_no` int(11) NOT NULL,
  `ht_code` varchar(20) NOT NULL DEFAULT '',
  `ht_mcode` varchar(4) NOT NULL DEFAULT '',
  `ht_zcode` varchar(2) NOT NULL DEFAULT '',
  `ht_pcode` varchar(3) NOT NULL DEFAULT '',
  `ht_scode` varchar(4) NOT NULL DEFAULT '',
  `ht_snum` tinyint(4) NOT NULL DEFAULT '0',
  `ht_tpcode` varchar(20) NOT NULL DEFAULT '',
  `ht_tpclass` varchar(255) NOT NULL DEFAULT '',
  `ht_tpimage` varchar(20) NOT NULL DEFAULT '',
  `ht_image1` varchar(255) NOT NULL DEFAULT '',
  `ht_image2` varchar(255) NOT NULL DEFAULT '',
  `ht_userdata` tinyint(4) NOT NULL DEFAULT '0',
  `ht_remark` varchar(255) NOT NULL DEFAULT '',
  `ht_title` varchar(255) NOT NULL DEFAULT '',
  `ht_text1` varchar(800) NOT NULL DEFAULT '',
  `ht_text2` varchar(800) NOT NULL DEFAULT '',
  `ht_text3` varchar(800) NOT NULL DEFAULT '',
  `ht_text4` varchar(800) NOT NULL DEFAULT '',
  `ht_text5` varchar(800) NOT NULL DEFAULT '',
  `ht_narration` varchar(800) NOT NULL DEFAULT '',
  `ht_audio` varchar(255) NOT NULL DEFAULT '',
  `ht_time` tinyint(4) NOT NULL DEFAULT '0',
  `ht_ver` tinyint(4) NOT NULL DEFAULT '1',
  `ht_activated` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `cfx_harutoday_join`
--

CREATE TABLE IF NOT EXISTS `cfx_harutoday_join` (
  `member_id` varchar(20) NOT NULL DEFAULT '',
  `member_key` varchar(255) NOT NULL DEFAULT '',
  `member_no` int(11) NOT NULL DEFAULT '0',
  `ht_current_part` tinyint(4) NOT NULL DEFAULT '1',
  `ht_activated` tinyint(1) NOT NULL DEFAULT '1',
  `ht_join1_datetime` datetime DEFAULT NULL,
  `ht_join2_datetime` datetime DEFAULT NULL,
  `ht_join3_datetime` datetime DEFAULT NULL,
  `ht_last_datetime` datetime DEFAULT NULL,
  `ht_created_datetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `cfx_harutoday_menu`
--

CREATE TABLE IF NOT EXISTS `cfx_harutoday_menu` (
  `ht_no` int(11) NOT NULL,
  `ht_mcode` varchar(4) NOT NULL DEFAULT '',
  `ht_zcode` varchar(10) NOT NULL DEFAULT '',
  `ht_pcode` varchar(10) NOT NULL DEFAULT '',
  `ht_pnum` tinyint(4) NOT NULL DEFAULT '0',
  `ht_part_no` tinyint(4) NOT NULL DEFAULT '0',
  `ht_mtitle` varchar(10) NOT NULL DEFAULT '',
  `ht_ztitle` varchar(255) NOT NULL DEFAULT '',
  `ht_ptitle` varchar(255) NOT NULL DEFAULT '',
  `ht_count` tinyint(4) NOT NULL DEFAULT '0',
  `ht_activated` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `cfx_harutoday_point`
--

CREATE TABLE IF NOT EXISTS `cfx_harutoday_point` (
  `member_id` varchar(20) NOT NULL DEFAULT '',
  `member_key` varchar(255) NOT NULL DEFAULT '',
  `member_no` int(11) NOT NULL DEFAULT '0',
  `ht_part_no` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point1` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point2` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point3` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point4` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point5` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point6` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point7` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point8` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point9` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point10` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point11` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point12` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point13` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point14` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point15` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point16` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point17` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point18` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point19` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point20` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point21` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point22` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point23` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point24` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point25` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point26` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point27` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point28` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point29` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point30` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point31` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point32` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point33` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point34` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point35` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point36` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point37` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point38` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point39` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point40` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point41` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point42` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point43` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point44` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point45` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point46` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point47` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point48` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bonus1` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bonus2` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bonus3` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bonus4` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bonus5` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bonus6` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bonus7` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bonus8` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bonus9` tinyint(4) NOT NULL DEFAULT '0',
  `ht_zone1` int(11) NOT NULL DEFAULT '0',
  `ht_zone2` int(11) NOT NULL DEFAULT '0',
  `ht_zone3` int(11) NOT NULL DEFAULT '0',
  `ht_zone4` int(11) NOT NULL DEFAULT '0',
  `ht_zone5` int(11) NOT NULL DEFAULT '0',
  `ht_quiz1` tinyint(4) NOT NULL DEFAULT '0',
  `ht_quiz2` tinyint(4) NOT NULL DEFAULT '0',
  `ht_quiz3` tinyint(4) NOT NULL DEFAULT '0',
  `ht_quiz4` tinyint(4) NOT NULL DEFAULT '0',
  `ht_quiz5` tinyint(4) NOT NULL DEFAULT '0',
  `ht_quiz6` tinyint(4) NOT NULL DEFAULT '0',
  `ht_quiz7` tinyint(4) NOT NULL DEFAULT '0',
  `ht_quiz8` tinyint(4) NOT NULL DEFAULT '0',
  `ht_quiz9` tinyint(4) NOT NULL DEFAULT '0',
  `ht_quiz10` tinyint(4) NOT NULL DEFAULT '0',
  `ht_quiz11` tinyint(4) NOT NULL DEFAULT '0',
  `ht_quiz12` tinyint(4) NOT NULL DEFAULT '0',
  `ht_quiz13` tinyint(4) NOT NULL DEFAULT '0',
  `ht_quiz14` tinyint(4) NOT NULL DEFAULT '0',
  `ht_quiz15` tinyint(4) NOT NULL DEFAULT '0',
  `ht_quiz16` tinyint(4) NOT NULL DEFAULT '0',
  `ht_quiz17` tinyint(4) NOT NULL DEFAULT '0',
  `ht_quiz18` tinyint(4) NOT NULL DEFAULT '0',
  `ht_quiz19` tinyint(4) NOT NULL DEFAULT '0',
  `ht_quiz20` tinyint(4) NOT NULL DEFAULT '0',
  `ht_quiz21` tinyint(4) NOT NULL DEFAULT '0',
  `ht_quiz22` tinyint(4) NOT NULL DEFAULT '0',
  `ht_quiz23` tinyint(4) NOT NULL DEFAULT '0',
  `ht_quiz24` tinyint(4) NOT NULL DEFAULT '0',
  `ht_quiz25` tinyint(4) NOT NULL DEFAULT '0',
  `ht_quiz26` tinyint(4) NOT NULL DEFAULT '0',
  `ht_quiz27` tinyint(4) NOT NULL DEFAULT '0',
  `ht_quiz28` tinyint(4) NOT NULL DEFAULT '0',
  `ht_quiz29` tinyint(4) NOT NULL DEFAULT '0',
  `ht_quiz30` tinyint(4) NOT NULL DEFAULT '0',
  `ht_quiz31` tinyint(4) NOT NULL DEFAULT '0',
  `ht_quiz32` tinyint(4) NOT NULL DEFAULT '0',
  `ht_quiz33` tinyint(4) NOT NULL DEFAULT '0',
  `ht_quiz34` tinyint(4) NOT NULL DEFAULT '0',
  `ht_quiz35` tinyint(4) NOT NULL DEFAULT '0',
  `ht_quiz36` tinyint(4) NOT NULL DEFAULT '0',
  `ht_quiz37` tinyint(4) NOT NULL DEFAULT '0',
  `ht_quiz38` tinyint(4) NOT NULL DEFAULT '0',
  `ht_quiz39` tinyint(4) NOT NULL DEFAULT '0',
  `ht_quiz40` tinyint(4) NOT NULL DEFAULT '0',
  `ht_quiz41` tinyint(4) NOT NULL DEFAULT '0',
  `ht_quiz42` tinyint(4) NOT NULL DEFAULT '0',
  `ht_quiz43` tinyint(4) NOT NULL DEFAULT '0',
  `ht_quiz44` tinyint(4) NOT NULL DEFAULT '0',
  `ht_quiz45` tinyint(4) NOT NULL DEFAULT '0',
  `ht_quiz46` tinyint(4) NOT NULL DEFAULT '0',
  `ht_quiz47` tinyint(4) NOT NULL DEFAULT '0',
  `ht_quiz48` tinyint(4) NOT NULL DEFAULT '0',
  `ht_point_record1` datetime DEFAULT NULL,
  `ht_point_record2` datetime DEFAULT NULL,
  `ht_point_record3` datetime DEFAULT NULL,
  `ht_point_record4` datetime DEFAULT NULL,
  `ht_point_record5` datetime DEFAULT NULL,
  `ht_point_record6` datetime DEFAULT NULL,
  `ht_point_record7` datetime DEFAULT NULL,
  `ht_point_record8` datetime DEFAULT NULL,
  `ht_point_record9` datetime DEFAULT NULL,
  `ht_point_record10` datetime DEFAULT NULL,
  `ht_point_record11` datetime DEFAULT NULL,
  `ht_point_record12` datetime DEFAULT NULL,
  `ht_point_record13` datetime DEFAULT NULL,
  `ht_point_record14` datetime DEFAULT NULL,
  `ht_point_record15` datetime DEFAULT NULL,
  `ht_point_record16` datetime DEFAULT NULL,
  `ht_point_record17` datetime DEFAULT NULL,
  `ht_point_record18` datetime DEFAULT NULL,
  `ht_point_record19` datetime DEFAULT NULL,
  `ht_point_record20` datetime DEFAULT NULL,
  `ht_point_record21` datetime DEFAULT NULL,
  `ht_point_record22` datetime DEFAULT NULL,
  `ht_point_record23` datetime DEFAULT NULL,
  `ht_point_record24` datetime DEFAULT NULL,
  `ht_point_record25` datetime DEFAULT NULL,
  `ht_point_record26` datetime DEFAULT NULL,
  `ht_point_record27` datetime DEFAULT NULL,
  `ht_point_record28` datetime DEFAULT NULL,
  `ht_point_record29` datetime DEFAULT NULL,
  `ht_point_record30` datetime DEFAULT NULL,
  `ht_point_record31` datetime DEFAULT NULL,
  `ht_point_record32` datetime DEFAULT NULL,
  `ht_point_record33` datetime DEFAULT NULL,
  `ht_point_record34` datetime DEFAULT NULL,
  `ht_point_record35` datetime DEFAULT NULL,
  `ht_point_record36` datetime DEFAULT NULL,
  `ht_point_record37` datetime DEFAULT NULL,
  `ht_point_record38` datetime DEFAULT NULL,
  `ht_point_record39` datetime DEFAULT NULL,
  `ht_point_record40` datetime DEFAULT NULL,
  `ht_point_record41` datetime DEFAULT NULL,
  `ht_point_record42` datetime DEFAULT NULL,
  `ht_point_record43` datetime DEFAULT NULL,
  `ht_point_record44` datetime DEFAULT NULL,
  `ht_point_record45` datetime DEFAULT NULL,
  `ht_point_record46` datetime DEFAULT NULL,
  `ht_point_record47` datetime DEFAULT NULL,
  `ht_point_record48` datetime DEFAULT NULL,
  `ht_bonus_record1` datetime DEFAULT NULL,
  `ht_bonus_record2` datetime DEFAULT NULL,
  `ht_bonus_record3` datetime DEFAULT NULL,
  `ht_bonus_record4` datetime DEFAULT NULL,
  `ht_bonus_record5` datetime DEFAULT NULL,
  `ht_bonus_record6` datetime DEFAULT NULL,
  `ht_bonus_record7` datetime DEFAULT NULL,
  `ht_bonus_record8` datetime DEFAULT NULL,
  `ht_bonus_record9` datetime DEFAULT NULL,
  `ht_zone_record1` datetime DEFAULT NULL,
  `ht_zone_record2` datetime DEFAULT NULL,
  `ht_zone_record3` datetime DEFAULT NULL,
  `ht_zone_record4` datetime DEFAULT NULL,
  `ht_zone_record5` datetime DEFAULT NULL,
  `ht_quiz_record1` datetime DEFAULT NULL,
  `ht_quiz_record2` datetime DEFAULT NULL,
  `ht_quiz_record3` datetime DEFAULT NULL,
  `ht_quiz_record4` datetime DEFAULT NULL,
  `ht_quiz_record5` datetime DEFAULT NULL,
  `ht_quiz_record6` datetime DEFAULT NULL,
  `ht_quiz_record7` datetime DEFAULT NULL,
  `ht_quiz_record8` datetime DEFAULT NULL,
  `ht_quiz_record9` datetime DEFAULT NULL,
  `ht_quiz_record10` datetime DEFAULT NULL,
  `ht_quiz_record11` datetime DEFAULT NULL,
  `ht_quiz_record12` datetime DEFAULT NULL,
  `ht_quiz_record13` datetime DEFAULT NULL,
  `ht_quiz_record14` datetime DEFAULT NULL,
  `ht_quiz_record15` datetime DEFAULT NULL,
  `ht_quiz_record16` datetime DEFAULT NULL,
  `ht_quiz_record17` datetime DEFAULT NULL,
  `ht_quiz_record18` datetime DEFAULT NULL,
  `ht_quiz_record19` datetime DEFAULT NULL,
  `ht_quiz_record20` datetime DEFAULT NULL,
  `ht_quiz_record21` datetime DEFAULT NULL,
  `ht_quiz_record22` datetime DEFAULT NULL,
  `ht_quiz_record23` datetime DEFAULT NULL,
  `ht_quiz_record24` datetime DEFAULT NULL,
  `ht_quiz_record25` datetime DEFAULT NULL,
  `ht_quiz_record26` datetime DEFAULT NULL,
  `ht_quiz_record27` datetime DEFAULT NULL,
  `ht_quiz_record28` datetime DEFAULT NULL,
  `ht_quiz_record29` datetime DEFAULT NULL,
  `ht_quiz_record30` datetime DEFAULT NULL,
  `ht_quiz_record31` datetime DEFAULT NULL,
  `ht_quiz_record32` datetime DEFAULT NULL,
  `ht_quiz_record33` datetime DEFAULT NULL,
  `ht_quiz_record34` datetime DEFAULT NULL,
  `ht_quiz_record35` datetime DEFAULT NULL,
  `ht_quiz_record36` datetime DEFAULT NULL,
  `ht_quiz_record37` datetime DEFAULT NULL,
  `ht_quiz_record38` datetime DEFAULT NULL,
  `ht_quiz_record39` datetime DEFAULT NULL,
  `ht_quiz_record40` datetime DEFAULT NULL,
  `ht_quiz_record41` datetime DEFAULT NULL,
  `ht_quiz_record42` datetime DEFAULT NULL,
  `ht_quiz_record43` datetime DEFAULT NULL,
  `ht_quiz_record44` datetime DEFAULT NULL,
  `ht_quiz_record45` datetime DEFAULT NULL,
  `ht_quiz_record46` datetime DEFAULT NULL,
  `ht_quiz_record47` datetime DEFAULT NULL,
  `ht_quiz_record48` datetime DEFAULT NULL,
  `ht_gift1` tinyint(4) NOT NULL DEFAULT '0',
  `ht_gift2` tinyint(4) NOT NULL DEFAULT '0',
  `ht_gift3` tinyint(4) NOT NULL DEFAULT '0',
  `ht_gift4` tinyint(4) NOT NULL DEFAULT '0',
  `ht_gift5` tinyint(4) NOT NULL DEFAULT '0',
  `ht_gift_record1` datetime DEFAULT NULL,
  `ht_gift_record2` datetime DEFAULT NULL,
  `ht_gift_record3` datetime DEFAULT NULL,
  `ht_gift_record4` datetime DEFAULT NULL,
  `ht_gift_record5` datetime DEFAULT NULL,
  `ht_gift_confirm1` datetime DEFAULT NULL,
  `ht_gift_confirm2` datetime DEFAULT NULL,
  `ht_gift_confirm3` datetime DEFAULT NULL,
  `ht_gift_confirm4` datetime DEFAULT NULL,
  `ht_gift_confirm5` datetime DEFAULT NULL,
  `ht_last_datetime` datetime DEFAULT NULL,
  `ht_created_datetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `cfx_harutoday_rating`
--

CREATE TABLE IF NOT EXISTS `cfx_harutoday_rating` (
  `member_id` varchar(20) NOT NULL DEFAULT '',
  `member_key` varchar(255) NOT NULL DEFAULT '',
  `member_no` int(11) NOT NULL DEFAULT '0',
  `ht_part_no` tinyint(4) NOT NULL DEFAULT '0',
  `ht_rating1` tinyint(4) NOT NULL DEFAULT '-1',
  `ht_rating2` tinyint(4) NOT NULL DEFAULT '-1',
  `ht_rating3` tinyint(4) NOT NULL DEFAULT '-1',
  `ht_rating4` tinyint(4) NOT NULL DEFAULT '-1',
  `ht_rating5` tinyint(4) NOT NULL DEFAULT '-1',
  `ht_rating6` tinyint(4) NOT NULL DEFAULT '-1',
  `ht_rating7` tinyint(4) NOT NULL DEFAULT '-1',
  `ht_rating8` tinyint(4) NOT NULL DEFAULT '-1',
  `ht_rating9` tinyint(4) NOT NULL DEFAULT '-1',
  `ht_rating10` tinyint(4) NOT NULL DEFAULT '-1',
  `ht_rating11` tinyint(4) NOT NULL DEFAULT '-1',
  `ht_rating12` tinyint(4) NOT NULL DEFAULT '-1',
  `ht_rating13` tinyint(4) NOT NULL DEFAULT '-1',
  `ht_rating14` tinyint(4) NOT NULL DEFAULT '-1',
  `ht_rating15` tinyint(4) NOT NULL DEFAULT '-1',
  `ht_rating16` tinyint(4) NOT NULL DEFAULT '-1',
  `ht_rating17` tinyint(4) NOT NULL DEFAULT '-1',
  `ht_rating18` tinyint(4) NOT NULL DEFAULT '-1',
  `ht_rating19` tinyint(4) NOT NULL DEFAULT '-1',
  `ht_rating20` tinyint(4) NOT NULL DEFAULT '-1',
  `ht_rating21` tinyint(4) NOT NULL DEFAULT '-1',
  `ht_rating22` tinyint(4) NOT NULL DEFAULT '-1',
  `ht_rating23` tinyint(4) NOT NULL DEFAULT '-1',
  `ht_rating24` tinyint(4) NOT NULL DEFAULT '-1',
  `ht_rating25` tinyint(4) NOT NULL DEFAULT '-1',
  `ht_rating26` tinyint(4) NOT NULL DEFAULT '-1',
  `ht_rating27` tinyint(4) NOT NULL DEFAULT '-1',
  `ht_rating28` tinyint(4) NOT NULL DEFAULT '-1',
  `ht_rating29` tinyint(4) NOT NULL DEFAULT '-1',
  `ht_rating30` tinyint(4) NOT NULL DEFAULT '-1',
  `ht_rating31` tinyint(4) NOT NULL DEFAULT '-1',
  `ht_rating32` tinyint(4) NOT NULL DEFAULT '-1',
  `ht_rating33` tinyint(4) NOT NULL DEFAULT '-1',
  `ht_rating34` tinyint(4) NOT NULL DEFAULT '-1',
  `ht_rating35` tinyint(4) NOT NULL DEFAULT '-1',
  `ht_rating36` tinyint(4) NOT NULL DEFAULT '-1',
  `ht_rating37` tinyint(4) NOT NULL DEFAULT '-1',
  `ht_rating38` tinyint(4) NOT NULL DEFAULT '-1',
  `ht_rating39` tinyint(4) NOT NULL DEFAULT '-1',
  `ht_rating40` tinyint(4) NOT NULL DEFAULT '-1',
  `ht_rating41` tinyint(4) NOT NULL DEFAULT '-1',
  `ht_rating42` tinyint(4) NOT NULL DEFAULT '-1',
  `ht_rating43` tinyint(4) NOT NULL DEFAULT '-1',
  `ht_rating44` tinyint(4) NOT NULL DEFAULT '-1',
  `ht_rating45` tinyint(4) NOT NULL DEFAULT '-1',
  `ht_rating46` tinyint(4) NOT NULL DEFAULT '-1',
  `ht_rating47` tinyint(4) NOT NULL DEFAULT '-1',
  `ht_rating48` tinyint(4) NOT NULL DEFAULT '-1',
  `ht_last_datetime` datetime DEFAULT NULL,
  `ht_created_datetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `cfx_harutoday_read`
--

CREATE TABLE IF NOT EXISTS `cfx_harutoday_read` (
  `member_id` varchar(20) NOT NULL DEFAULT '',
  `member_key` varchar(255) NOT NULL DEFAULT '',
  `member_no` int(11) NOT NULL DEFAULT '0',
  `ht_part_no` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read1` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read2` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read3` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read4` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read5` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read6` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read7` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read8` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read9` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read10` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read11` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read12` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read13` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read14` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read15` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read16` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read17` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read18` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read19` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read20` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read21` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read22` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read23` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read24` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read25` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read26` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read27` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read28` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read29` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read30` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read31` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read32` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read33` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read34` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read35` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read36` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read37` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read38` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read39` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read40` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read41` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read42` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read43` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read44` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read45` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read46` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read47` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read48` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bonus1` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bonus2` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bonus3` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bonus4` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bonus5` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bonus6` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bonus7` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bonus8` tinyint(4) NOT NULL DEFAULT '0',
  `ht_bonus9` tinyint(4) NOT NULL DEFAULT '0',
  `ht_zone1` tinyint(4) NOT NULL DEFAULT '0',
  `ht_zone2` tinyint(4) NOT NULL DEFAULT '0',
  `ht_zone3` tinyint(4) NOT NULL DEFAULT '0',
  `ht_zone4` tinyint(4) NOT NULL DEFAULT '0',
  `ht_zone5` tinyint(4) NOT NULL DEFAULT '0',
  `ht_read_datetime1` datetime DEFAULT NULL,
  `ht_read_datetime2` datetime DEFAULT NULL,
  `ht_read_datetime3` datetime DEFAULT NULL,
  `ht_read_datetime4` datetime DEFAULT NULL,
  `ht_read_datetime5` datetime DEFAULT NULL,
  `ht_read_datetime6` datetime DEFAULT NULL,
  `ht_read_datetime7` datetime DEFAULT NULL,
  `ht_read_datetime8` datetime DEFAULT NULL,
  `ht_read_datetime9` datetime DEFAULT NULL,
  `ht_read_datetime10` datetime DEFAULT NULL,
  `ht_read_datetime11` datetime DEFAULT NULL,
  `ht_read_datetime12` datetime DEFAULT NULL,
  `ht_read_datetime13` datetime DEFAULT NULL,
  `ht_read_datetime14` datetime DEFAULT NULL,
  `ht_read_datetime15` datetime DEFAULT NULL,
  `ht_read_datetime16` datetime DEFAULT NULL,
  `ht_read_datetime17` datetime DEFAULT NULL,
  `ht_read_datetime18` datetime DEFAULT NULL,
  `ht_read_datetime19` datetime DEFAULT NULL,
  `ht_read_datetime20` datetime DEFAULT NULL,
  `ht_read_datetime21` datetime DEFAULT NULL,
  `ht_read_datetime22` datetime DEFAULT NULL,
  `ht_read_datetime23` datetime DEFAULT NULL,
  `ht_read_datetime24` datetime DEFAULT NULL,
  `ht_read_datetime25` datetime DEFAULT NULL,
  `ht_read_datetime26` datetime DEFAULT NULL,
  `ht_read_datetime27` datetime DEFAULT NULL,
  `ht_read_datetime28` datetime DEFAULT NULL,
  `ht_read_datetime29` datetime DEFAULT NULL,
  `ht_read_datetime30` datetime DEFAULT NULL,
  `ht_read_datetime31` datetime DEFAULT NULL,
  `ht_read_datetime32` datetime DEFAULT NULL,
  `ht_read_datetime33` datetime DEFAULT NULL,
  `ht_read_datetime34` datetime DEFAULT NULL,
  `ht_read_datetime35` datetime DEFAULT NULL,
  `ht_read_datetime36` datetime DEFAULT NULL,
  `ht_read_datetime37` datetime DEFAULT NULL,
  `ht_read_datetime38` datetime DEFAULT NULL,
  `ht_read_datetime39` datetime DEFAULT NULL,
  `ht_read_datetime40` datetime DEFAULT NULL,
  `ht_read_datetime41` datetime DEFAULT NULL,
  `ht_read_datetime42` datetime DEFAULT NULL,
  `ht_read_datetime43` datetime DEFAULT NULL,
  `ht_read_datetime44` datetime DEFAULT NULL,
  `ht_read_datetime45` datetime DEFAULT NULL,
  `ht_read_datetime46` datetime DEFAULT NULL,
  `ht_read_datetime47` datetime DEFAULT NULL,
  `ht_read_datetime48` datetime DEFAULT NULL,
  `ht_last_datetime` datetime DEFAULT NULL,
  `ht_created_datetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `cfx_harutoday_userdata`
--

CREATE TABLE IF NOT EXISTS `cfx_harutoday_userdata` (
  `member_id` varchar(20) NOT NULL DEFAULT '',
  `member_key` varchar(255) NOT NULL DEFAULT '',
  `member_no` int(11) NOT NULL DEFAULT '0',
  `ht_part_no` tinyint(4) NOT NULL DEFAULT '0',
  `ht_pnum` tinyint(4) NOT NULL DEFAULT '0',
  `ht_userdata` varchar(4000) NOT NULL DEFAULT '',
  `ht_last_datetime` datetime DEFAULT NULL,
  `ht_created_datetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `cfx_log`
--

CREATE TABLE IF NOT EXISTS `cfx_log` (
  `log_no` int(11) NOT NULL,
  `member_id` varchar(20) NOT NULL DEFAULT '',
  `member_key` varchar(255) NOT NULL DEFAULT '',
  `log_module` varchar(10) NOT NULL DEFAULT '',
  `log_part_no` tinyint(4) NOT NULL,
  `log_target` varchar(255) NOT NULL DEFAULT '',
  `log_created_datetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `cfx_login_attempts`
--

CREATE TABLE IF NOT EXISTS `cfx_login_attempts` (
  `login_attempts_no` int(11) NOT NULL,
  `login_attempts_ip` varchar(40) NOT NULL,
  `login_attempts_id` varchar(255) NOT NULL,
  `login_attempts_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `cfx_member`
--

CREATE TABLE IF NOT EXISTS `cfx_member` (
  `member_no` int(11) NOT NULL,
  `member_key` varchar(255) NOT NULL DEFAULT '',
  `member_id` varchar(20) NOT NULL DEFAULT '',
  `member_pw` varchar(255) NOT NULL DEFAULT '',
  `member_email` varchar(255) NOT NULL DEFAULT '',
  `member_hp` varchar(20) NOT NULL DEFAULT '',
  `member_name` varchar(255) NOT NULL DEFAULT '',
  `member_level` tinyint(1) NOT NULL DEFAULT '1',
  `member_group` tinyint(4) NOT NULL DEFAULT '0',
  `member_group_label` tinyint(4) NOT NULL DEFAULT '0',
  `member_ip` varchar(255) NOT NULL DEFAULT '',
  `member_activated` tinyint(1) NOT NULL DEFAULT '1',
  `member_banned` tinyint(1) NOT NULL DEFAULT '0',
  `member_passwd_recovery_code` varchar(60) DEFAULT NULL,
  `member_passwd_recovery_datetime` datetime DEFAULT NULL,
  `member_passwd_modified_datetime` datetime DEFAULT NULL,
  `member_login_ip` varchar(255) NOT NULL DEFAULT '',
  `member_login_datetime` datetime DEFAULT NULL,
  `member_created_datetime` datetime DEFAULT NULL,
  `member_left_date` varchar(8) NOT NULL DEFAULT '',
  `member_modified_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `cfx_member_memo`
--

CREATE TABLE IF NOT EXISTS `cfx_member_memo` (
  `memo_no` int(11) NOT NULL,
  `member_no` int(11) NOT NULL,
  `memo_member_id` varchar(20) NOT NULL DEFAULT '',
  `memo_member_name` varchar(255) NOT NULL DEFAULT '',
  `memo_member_ip` varchar(255) NOT NULL DEFAULT '',
  `memo_content` varchar(800) NOT NULL DEFAULT '',
  `memo_created_datetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `cfx_moduletest_answer`
--

CREATE TABLE IF NOT EXISTS `cfx_moduletest_answer` (
  `mta_id` int(11) NOT NULL,
  `mta_member_id` varchar(20) NOT NULL DEFAULT '',
  `mta_step` tinyint(4) NOT NULL DEFAULT '0',
  `mta_case1` tinyint(4) NOT NULL DEFAULT '0',
  `mta_case2` tinyint(4) NOT NULL DEFAULT '0',
  `mta_case3` tinyint(4) NOT NULL DEFAULT '0',
  `mta_case4` tinyint(4) NOT NULL DEFAULT '0',
  `mta_case5` tinyint(4) NOT NULL DEFAULT '0',
  `mta_case6` tinyint(4) NOT NULL DEFAULT '0',
  `mta_case7` tinyint(4) NOT NULL DEFAULT '0',
  `mta_case8` tinyint(4) NOT NULL DEFAULT '0',
  `mta_point1` tinyint(4) NOT NULL DEFAULT '0',
  `mta_point2` tinyint(4) NOT NULL DEFAULT '0',
  `mta_point3` tinyint(4) NOT NULL DEFAULT '0',
  `mta_sent` set('Y','N') DEFAULT 'N',
  `mta_datetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `cfx_moduletest_question`
--

CREATE TABLE IF NOT EXISTS `cfx_moduletest_question` (
  `mtq_case1` varchar(255) NOT NULL DEFAULT '',
  `mtq_case2` varchar(255) NOT NULL DEFAULT '',
  `mtq_case3` varchar(255) NOT NULL DEFAULT '',
  `mtq_case4` varchar(255) NOT NULL DEFAULT '',
  `mtq_case5` varchar(255) NOT NULL DEFAULT '',
  `mtq_case6` varchar(255) NOT NULL DEFAULT '',
  `mtq_case7` varchar(255) NOT NULL DEFAULT '',
  `mtq_case8` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `cfx_profile`
--

CREATE TABLE IF NOT EXISTS `cfx_profile` (
  `member_id` varchar(20) NOT NULL DEFAULT '',
  `member_key` varchar(255) NOT NULL DEFAULT '',
  `member_no` int(11) NOT NULL DEFAULT '0',
  `profile_gender` set('M','F','N') NOT NULL DEFAULT 'N',
  `profile_birth` varchar(20) NOT NULL DEFAULT '',
  `profile_tel` varchar(20) NOT NULL DEFAULT '',
  `profile_zipcode` varchar(5) NOT NULL DEFAULT '',
  `profile_address1` varchar(255) NOT NULL DEFAULT '',
  `profile_address2` varchar(255) NOT NULL DEFAULT '',
  `profile_address3` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `cfx_research`
--

CREATE TABLE IF NOT EXISTS `cfx_research` (
  `member_id` varchar(20) NOT NULL DEFAULT '',
  `member_key` varchar(255) NOT NULL DEFAULT '',
  `member_no` int(11) NOT NULL DEFAULT '0',
  `research_check` set('Y','N') NOT NULL DEFAULT 'N',
  `research_name` varchar(255) NOT NULL DEFAULT '',
  `research_level` tinyint(4) NOT NULL DEFAULT '0',
  `research_date` varchar(10) NOT NULL DEFAULT '',
  `research_recur` tinyint(4) NOT NULL DEFAULT '0',
  `research_care` varchar(255) NOT NULL DEFAULT '',
  `research_test1` set('Y','N') NOT NULL DEFAULT 'N',
  `research_test2` set('Y','N') NOT NULL DEFAULT 'N',
  `research_test3` set('Y','N') NOT NULL DEFAULT 'N',
  `research_test4` set('Y','N') NOT NULL DEFAULT 'N',
  `research_test5` set('Y','N') NOT NULL DEFAULT 'N'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `cfx_screening`
--

CREATE TABLE IF NOT EXISTS `cfx_screening` (
  `screening_no` int(11) NOT NULL,
  `screening_code` tinyint(4) NOT NULL DEFAULT '1',
  `screening_group` varchar(255) NOT NULL DEFAULT '',
  `screening_order` varchar(255) NOT NULL DEFAULT '',
  `screening_title` varchar(255) NOT NULL DEFAULT '',
  `screening_text1` varchar(255) NOT NULL DEFAULT '',
  `screening_text2` varchar(255) NOT NULL DEFAULT '',
  `screening_type` tinyint(4) NOT NULL DEFAULT '1',
  `screening_activated` tinyint(1) NOT NULL DEFAULT '1',
  `screening_modified_datetime` datetime DEFAULT NULL,
  `screening_created_datetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `cfx_screening_userdata`
--

CREATE TABLE IF NOT EXISTS `cfx_screening_userdata` (
  `screening_no` int(11) NOT NULL,
  `member_id` varchar(20) NOT NULL DEFAULT '',
  `member_key` varchar(255) NOT NULL DEFAULT '',
  `screening_userdata1` tinyint(4) NOT NULL DEFAULT '0',
  `screening_userdata2` tinyint(4) NOT NULL DEFAULT '0',
  `screening_userdata3` tinyint(4) NOT NULL DEFAULT '0',
  `screening_userdata4` tinyint(4) NOT NULL DEFAULT '0',
  `screening_userdata5` tinyint(4) NOT NULL DEFAULT '0',
  `screening_userdata6` tinyint(4) NOT NULL DEFAULT '0',
  `screening_userdata7` tinyint(4) NOT NULL DEFAULT '0',
  `screening_userdata8` tinyint(4) NOT NULL DEFAULT '0',
  `screening_userdata9` tinyint(4) NOT NULL DEFAULT '0',
  `screening_userdata10` tinyint(4) NOT NULL DEFAULT '0',
  `screening_userdata11` tinyint(4) NOT NULL DEFAULT '0',
  `screening_userdata12` tinyint(4) NOT NULL DEFAULT '0',
  `screening_point1` tinyint(4) NOT NULL DEFAULT '0',
  `screening_point2` tinyint(4) NOT NULL DEFAULT '0',
  `screening_point3` tinyint(4) NOT NULL DEFAULT '0',
  `screening_point4` tinyint(4) NOT NULL DEFAULT '0',
  `screening_joined_datetime` datetime DEFAULT NULL,
  `screening_created_datetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `cfx_setup`
--

CREATE TABLE IF NOT EXISTS `cfx_setup` (
  `setup_no` int(11) NOT NULL,
  `setup_name` varchar(255) NOT NULL DEFAULT '',
  `setup_data1` varchar(255) NOT NULL DEFAULT '',
  `setup_data2` varchar(255) NOT NULL DEFAULT '',
  `setup_data3` varchar(255) NOT NULL DEFAULT '',
  `setup_data4` varchar(255) NOT NULL DEFAULT '',
  `setup_data5` varchar(255) NOT NULL DEFAULT '',
  `setup_activated` tinyint(1) NOT NULL DEFAULT '1',
  `setup_modified_datetime` datetime DEFAULT NULL,
  `setup_created_datetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `cfx_severity`
--

CREATE TABLE IF NOT EXISTS `cfx_severity` (
  `severity_no` int(11) NOT NULL,
  `severity_code` tinyint(4) NOT NULL DEFAULT '1',
  `severity_group` varchar(255) NOT NULL DEFAULT '',
  `severity_order` varchar(255) NOT NULL DEFAULT '',
  `severity_title` varchar(255) NOT NULL DEFAULT '',
  `severity_show_title` tinyint(1) NOT NULL DEFAULT '1',
  `severity_name` varchar(255) NOT NULL DEFAULT '',
  `severity_text1` varchar(255) NOT NULL DEFAULT '',
  `severity_text2` varchar(255) NOT NULL DEFAULT '',
  `severity_text3` varchar(255) NOT NULL DEFAULT '',
  `severity_text4` varchar(255) NOT NULL DEFAULT '',
  `severity_text5` varchar(255) NOT NULL DEFAULT '',
  `severity_text6` varchar(255) NOT NULL DEFAULT '',
  `severity_type` tinyint(4) NOT NULL DEFAULT '1',
  `severity_activated` tinyint(1) NOT NULL DEFAULT '1',
  `severity_modified_datetime` datetime DEFAULT NULL,
  `severity_created_datetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `cfx_severitytest_answer1`
--

CREATE TABLE IF NOT EXISTS `cfx_severitytest_answer1` (
  `sta1_id` int(11) NOT NULL,
  `sta1_member_id` varchar(20) NOT NULL DEFAULT '',
  `sta1_step` tinyint(4) NOT NULL DEFAULT '0',
  `sta1_case1` tinyint(4) NOT NULL DEFAULT '0',
  `sta1_case2` tinyint(4) NOT NULL DEFAULT '0',
  `sta1_case3` tinyint(4) NOT NULL DEFAULT '0',
  `sta1_case4` tinyint(4) NOT NULL DEFAULT '0',
  `sta1_case5` tinyint(4) NOT NULL DEFAULT '0',
  `sta1_case6` tinyint(4) NOT NULL DEFAULT '0',
  `sta1_case7` tinyint(4) NOT NULL DEFAULT '0',
  `sta1_case8` tinyint(4) NOT NULL DEFAULT '0',
  `sta1_case9` tinyint(4) NOT NULL DEFAULT '0',
  `sta1_case10` tinyint(4) NOT NULL DEFAULT '0',
  `sta1_case11` tinyint(4) NOT NULL DEFAULT '0',
  `sta1_case12` tinyint(4) NOT NULL DEFAULT '0',
  `sta1_case13` tinyint(4) NOT NULL DEFAULT '0',
  `sta1_case14` tinyint(4) NOT NULL DEFAULT '0',
  `sta1_case15` tinyint(4) NOT NULL DEFAULT '0',
  `sta1_case16` tinyint(4) NOT NULL DEFAULT '0',
  `sta1_point1` tinyint(4) NOT NULL DEFAULT '0',
  `sta1_point2` tinyint(4) NOT NULL DEFAULT '0',
  `sta1_sent` set('Y','N') NOT NULL DEFAULT 'N',
  `sta1_datetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `cfx_severitytest_question1`
--

CREATE TABLE IF NOT EXISTS `cfx_severitytest_question1` (
  `stq1_case1` varchar(255) NOT NULL DEFAULT '',
  `stq1_case2` varchar(255) NOT NULL DEFAULT '',
  `stq1_case3` varchar(255) NOT NULL DEFAULT '',
  `stq1_case4` varchar(255) NOT NULL DEFAULT '',
  `stq1_case5` varchar(255) NOT NULL DEFAULT '',
  `stq1_case6` varchar(255) NOT NULL DEFAULT '',
  `stq1_case7` varchar(255) NOT NULL DEFAULT '',
  `stq1_case8` varchar(255) NOT NULL DEFAULT '',
  `stq1_case9` varchar(255) NOT NULL DEFAULT '',
  `stq1_case10` varchar(255) NOT NULL DEFAULT '',
  `stq1_case11` varchar(255) NOT NULL DEFAULT '',
  `stq1_case12` varchar(255) NOT NULL DEFAULT '',
  `stq1_case13` varchar(255) NOT NULL DEFAULT '',
  `stq1_case14` varchar(255) NOT NULL DEFAULT '',
  `stq1_case15` varchar(255) NOT NULL DEFAULT '',
  `stq1_case16` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `cfx_severity_userdata`
--

CREATE TABLE IF NOT EXISTS `cfx_severity_userdata` (
  `severity_no` int(11) NOT NULL,
  `severity_code` tinyint(4) NOT NULL,
  `member_id` varchar(20) NOT NULL DEFAULT '',
  `member_key` varchar(255) NOT NULL DEFAULT '',
  `severity_userdata1` varchar(50) NOT NULL DEFAULT '',
  `severity_userdata2` varchar(50) NOT NULL DEFAULT '',
  `severity_userdata3` varchar(50) NOT NULL DEFAULT '',
  `severity_userdata4` varchar(50) NOT NULL DEFAULT '',
  `severity_userdata5` varchar(50) NOT NULL DEFAULT '',
  `severity_userdata6` varchar(50) NOT NULL DEFAULT '',
  `severity_userdata7` varchar(50) NOT NULL DEFAULT '',
  `severity_userdata8` varchar(50) NOT NULL DEFAULT '',
  `severity_userdata9` varchar(50) NOT NULL DEFAULT '',
  `severity_userdata10` varchar(50) NOT NULL DEFAULT '',
  `severity_userdata11` varchar(50) NOT NULL DEFAULT '',
  `severity_userdata12` varchar(50) NOT NULL DEFAULT '',
  `severity_userdata13` varchar(50) NOT NULL DEFAULT '',
  `severity_userdata14` varchar(50) NOT NULL DEFAULT '',
  `severity_userdata15` varchar(50) NOT NULL DEFAULT '',
  `severity_userdata16` varchar(50) NOT NULL DEFAULT '',
  `severity_userdata17` varchar(50) NOT NULL DEFAULT '',
  `severity_userdata18` varchar(50) NOT NULL DEFAULT '',
  `severity_point1` tinyint(4) NOT NULL DEFAULT '0',
  `severity_point2` tinyint(4) NOT NULL DEFAULT '0',
  `severity_joined_datetime` datetime DEFAULT NULL,
  `severity_created_datetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `cfx_survey`
--

CREATE TABLE IF NOT EXISTS `cfx_survey` (
  `survey_no` int(11) NOT NULL,
  `survey_code` tinyint(4) NOT NULL DEFAULT '1',
  `survey_group` varchar(255) NOT NULL DEFAULT '',
  `survey_order` varchar(255) NOT NULL DEFAULT '',
  `survey_title` varchar(255) NOT NULL DEFAULT '',
  `survey_show_title` tinyint(1) NOT NULL DEFAULT '1',
  `survey_name` varchar(255) NOT NULL DEFAULT '',
  `survey_text1` varchar(255) NOT NULL DEFAULT '',
  `survey_text2` varchar(255) NOT NULL DEFAULT '',
  `survey_text3` varchar(255) NOT NULL DEFAULT '',
  `survey_text4` varchar(255) NOT NULL DEFAULT '',
  `survey_text5` varchar(255) NOT NULL DEFAULT '',
  `survey_text6` varchar(255) NOT NULL DEFAULT '',
  `survey_type` tinyint(4) NOT NULL DEFAULT '1',
  `survey_activated` tinyint(1) NOT NULL DEFAULT '1',
  `survey_modified_datetime` datetime DEFAULT NULL,
  `survey_created_datetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `cfx_survey_userdata`
--

CREATE TABLE IF NOT EXISTS `cfx_survey_userdata` (
  `survey_no` int(11) NOT NULL,
  `survey_code` tinyint(4) NOT NULL,
  `member_id` varchar(20) NOT NULL DEFAULT '',
  `member_key` varchar(255) NOT NULL DEFAULT '',
  `survey_userdata1` varchar(50) NOT NULL DEFAULT '',
  `survey_userdata2` varchar(50) NOT NULL DEFAULT '',
  `survey_userdata3` varchar(50) NOT NULL DEFAULT '',
  `survey_userdata4` varchar(50) NOT NULL DEFAULT '',
  `survey_userdata5` varchar(50) NOT NULL DEFAULT '',
  `survey_userdata6` varchar(50) NOT NULL DEFAULT '',
  `survey_userdata7` varchar(50) NOT NULL DEFAULT '',
  `survey_userdata8` varchar(50) NOT NULL DEFAULT '',
  `survey_userdata9` varchar(50) NOT NULL DEFAULT '',
  `survey_userdata10` varchar(50) NOT NULL DEFAULT '',
  `survey_userdata11` varchar(50) NOT NULL DEFAULT '',
  `survey_userdata12` varchar(50) NOT NULL DEFAULT '',
  `survey_userdata13` varchar(50) NOT NULL DEFAULT '',
  `survey_userdata14` varchar(50) NOT NULL DEFAULT '',
  `survey_userdata15` varchar(50) NOT NULL DEFAULT '',
  `survey_userdata16` varchar(50) NOT NULL DEFAULT '',
  `survey_userdata17` varchar(50) NOT NULL DEFAULT '',
  `survey_userdata18` varchar(50) NOT NULL DEFAULT '',
  `survey_userdata19` varchar(50) NOT NULL DEFAULT '',
  `survey_userdata20` varchar(50) NOT NULL DEFAULT '',
  `survey_userdata21` varchar(50) NOT NULL DEFAULT '',
  `survey_userdata22` varchar(50) NOT NULL DEFAULT '',
  `survey_userdata23` varchar(50) NOT NULL DEFAULT '',
  `survey_userdata24` varchar(50) NOT NULL DEFAULT '',
  `survey_userdata25` varchar(50) NOT NULL DEFAULT '',
  `survey_userdata26` varchar(50) NOT NULL DEFAULT '',
  `survey_userdata27` varchar(50) NOT NULL DEFAULT '',
  `survey_userdata28` varchar(50) NOT NULL DEFAULT '',
  `survey_userdata29` varchar(50) NOT NULL DEFAULT '',
  `survey_userdata30` varchar(50) NOT NULL DEFAULT '',
  `survey_userdata31` varchar(50) NOT NULL DEFAULT '',
  `survey_userdata32` varchar(50) NOT NULL DEFAULT '',
  `survey_userdata33` varchar(50) NOT NULL DEFAULT '',
  `survey_userdata34` varchar(50) NOT NULL DEFAULT '',
  `survey_userdata35` varchar(50) NOT NULL DEFAULT '',
  `survey_userdata36` varchar(50) NOT NULL DEFAULT '',
  `survey_userdata37` varchar(50) NOT NULL DEFAULT '',
  `survey_userdata38` varchar(50) NOT NULL DEFAULT '',
  `survey_userdata39` varchar(50) NOT NULL DEFAULT '',
  `survey_userdata40` varchar(50) NOT NULL DEFAULT '',
  `survey_userdata41` varchar(50) NOT NULL DEFAULT '',
  `survey_userdata42` varchar(50) NOT NULL DEFAULT '',
  `survey_userdata43` varchar(50) NOT NULL DEFAULT '',
  `survey_count` tinyint(4) NOT NULL DEFAULT '0',
  `survey_joined_datetime` datetime DEFAULT NULL,
  `survey_created_datetime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `cfx_gift`
--
ALTER TABLE `cfx_gift`
  ADD PRIMARY KEY (`gift_no`),
  ADD KEY `gift_created_datetime` (`gift_created_datetime`);

--
-- Индексы таблицы `cfx_group`
--
ALTER TABLE `cfx_group`
  ADD PRIMARY KEY (`group_no`);

--
-- Индексы таблицы `cfx_group_label`
--
ALTER TABLE `cfx_group_label`
  ADD PRIMARY KEY (`label_no`),
  ADD KEY `label_created_datetime` (`label_created_datetime`);

--
-- Индексы таблицы `cfx_harucard`
--
ALTER TABLE `cfx_harucard`
  ADD PRIMARY KEY (`hc_no`),
  ADD UNIQUE KEY `hc_code` (`hc_code`);

--
-- Индексы таблицы `cfx_harucard_bookmark`
--
ALTER TABLE `cfx_harucard_bookmark`
  ADD PRIMARY KEY (`member_id`,`member_no`,`member_key`,`hc_part_no`);

--
-- Индексы таблицы `cfx_harucard_join`
--
ALTER TABLE `cfx_harucard_join`
  ADD PRIMARY KEY (`member_id`,`member_no`,`member_key`);

--
-- Индексы таблицы `cfx_harucard_point`
--
ALTER TABLE `cfx_harucard_point`
  ADD PRIMARY KEY (`member_id`,`member_no`,`member_key`,`hc_part_no`);

--
-- Индексы таблицы `cfx_harucard_rating`
--
ALTER TABLE `cfx_harucard_rating`
  ADD PRIMARY KEY (`member_id`,`member_no`,`member_key`,`hc_part_no`);

--
-- Индексы таблицы `cfx_harucard_read`
--
ALTER TABLE `cfx_harucard_read`
  ADD PRIMARY KEY (`member_id`,`member_no`,`member_key`,`hc_part_no`);

--
-- Индексы таблицы `cfx_harutoday_bookmark`
--
ALTER TABLE `cfx_harutoday_bookmark`
  ADD PRIMARY KEY (`member_id`,`member_no`,`member_key`,`ht_part_no`);

--
-- Индексы таблицы `cfx_harutoday_data`
--
ALTER TABLE `cfx_harutoday_data`
  ADD PRIMARY KEY (`ht_no`,`ht_code`,`ht_mcode`,`ht_zcode`,`ht_pcode`,`ht_scode`,`ht_snum`);

--
-- Индексы таблицы `cfx_harutoday_join`
--
ALTER TABLE `cfx_harutoday_join`
  ADD PRIMARY KEY (`member_id`,`member_no`,`member_key`);

--
-- Индексы таблицы `cfx_harutoday_menu`
--
ALTER TABLE `cfx_harutoday_menu`
  ADD PRIMARY KEY (`ht_no`,`ht_mcode`,`ht_zcode`,`ht_pcode`,`ht_pnum`);

--
-- Индексы таблицы `cfx_harutoday_point`
--
ALTER TABLE `cfx_harutoday_point`
  ADD PRIMARY KEY (`member_id`,`member_no`,`member_key`,`ht_part_no`);

--
-- Индексы таблицы `cfx_harutoday_rating`
--
ALTER TABLE `cfx_harutoday_rating`
  ADD PRIMARY KEY (`member_id`,`member_no`,`member_key`,`ht_part_no`);

--
-- Индексы таблицы `cfx_harutoday_read`
--
ALTER TABLE `cfx_harutoday_read`
  ADD PRIMARY KEY (`member_id`,`member_no`,`member_key`,`ht_part_no`);

--
-- Индексы таблицы `cfx_harutoday_userdata`
--
ALTER TABLE `cfx_harutoday_userdata`
  ADD PRIMARY KEY (`member_id`,`member_no`,`member_key`,`ht_part_no`,`ht_pnum`);

--
-- Индексы таблицы `cfx_log`
--
ALTER TABLE `cfx_log`
  ADD PRIMARY KEY (`log_no`),
  ADD KEY `log_created_datetime` (`log_created_datetime`);

--
-- Индексы таблицы `cfx_login_attempts`
--
ALTER TABLE `cfx_login_attempts`
  ADD PRIMARY KEY (`login_attempts_no`);

--
-- Индексы таблицы `cfx_member`
--
ALTER TABLE `cfx_member`
  ADD PRIMARY KEY (`member_no`),
  ADD UNIQUE KEY `member_id` (`member_id`),
  ADD UNIQUE KEY `member_key` (`member_key`),
  ADD KEY `member_login_datetime` (`member_login_datetime`),
  ADD KEY `member_created_datetime` (`member_created_datetime`);

--
-- Индексы таблицы `cfx_member_memo`
--
ALTER TABLE `cfx_member_memo`
  ADD PRIMARY KEY (`memo_no`),
  ADD KEY `memo_created_datetime` (`memo_created_datetime`);

--
-- Индексы таблицы `cfx_moduletest_answer`
--
ALTER TABLE `cfx_moduletest_answer`
  ADD PRIMARY KEY (`mta_id`),
  ADD KEY `mta_member_id` (`mta_member_id`);

--
-- Индексы таблицы `cfx_profile`
--
ALTER TABLE `cfx_profile`
  ADD PRIMARY KEY (`member_id`,`member_no`,`member_key`);

--
-- Индексы таблицы `cfx_research`
--
ALTER TABLE `cfx_research`
  ADD PRIMARY KEY (`member_id`,`member_no`,`member_key`);

--
-- Индексы таблицы `cfx_screening`
--
ALTER TABLE `cfx_screening`
  ADD PRIMARY KEY (`screening_no`),
  ADD KEY `screening_created_datetime` (`screening_created_datetime`);

--
-- Индексы таблицы `cfx_screening_userdata`
--
ALTER TABLE `cfx_screening_userdata`
  ADD PRIMARY KEY (`screening_no`),
  ADD KEY `screening_created_datetime` (`screening_created_datetime`);

--
-- Индексы таблицы `cfx_setup`
--
ALTER TABLE `cfx_setup`
  ADD PRIMARY KEY (`setup_no`),
  ADD KEY `setup_created_datetime` (`setup_created_datetime`);

--
-- Индексы таблицы `cfx_severity`
--
ALTER TABLE `cfx_severity`
  ADD PRIMARY KEY (`severity_no`),
  ADD KEY `severity_created_datetime` (`severity_created_datetime`);

--
-- Индексы таблицы `cfx_severitytest_answer1`
--
ALTER TABLE `cfx_severitytest_answer1`
  ADD PRIMARY KEY (`sta1_id`),
  ADD KEY `sta1_member_id` (`sta1_member_id`);

--
-- Индексы таблицы `cfx_severity_userdata`
--
ALTER TABLE `cfx_severity_userdata`
  ADD PRIMARY KEY (`severity_no`),
  ADD KEY `severity_created_datetime` (`severity_created_datetime`);

--
-- Индексы таблицы `cfx_survey`
--
ALTER TABLE `cfx_survey`
  ADD PRIMARY KEY (`survey_no`),
  ADD KEY `survey_created_datetime` (`survey_created_datetime`);

--
-- Индексы таблицы `cfx_survey_userdata`
--
ALTER TABLE `cfx_survey_userdata`
  ADD PRIMARY KEY (`survey_no`),
  ADD KEY `survey_created_datetime` (`survey_created_datetime`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `cfx_gift`
--
ALTER TABLE `cfx_gift`
  MODIFY `gift_no` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `cfx_group`
--
ALTER TABLE `cfx_group`
  MODIFY `group_no` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `cfx_group_label`
--
ALTER TABLE `cfx_group_label`
  MODIFY `label_no` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `cfx_harucard`
--
ALTER TABLE `cfx_harucard`
  MODIFY `hc_no` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `cfx_harutoday_data`
--
ALTER TABLE `cfx_harutoday_data`
  MODIFY `ht_no` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `cfx_harutoday_menu`
--
ALTER TABLE `cfx_harutoday_menu`
  MODIFY `ht_no` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `cfx_log`
--
ALTER TABLE `cfx_log`
  MODIFY `log_no` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `cfx_login_attempts`
--
ALTER TABLE `cfx_login_attempts`
  MODIFY `login_attempts_no` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `cfx_member`
--
ALTER TABLE `cfx_member`
  MODIFY `member_no` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `cfx_member_memo`
--
ALTER TABLE `cfx_member_memo`
  MODIFY `memo_no` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `cfx_moduletest_answer`
--
ALTER TABLE `cfx_moduletest_answer`
  MODIFY `mta_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `cfx_screening`
--
ALTER TABLE `cfx_screening`
  MODIFY `screening_no` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `cfx_screening_userdata`
--
ALTER TABLE `cfx_screening_userdata`
  MODIFY `screening_no` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `cfx_setup`
--
ALTER TABLE `cfx_setup`
  MODIFY `setup_no` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `cfx_severity`
--
ALTER TABLE `cfx_severity`
  MODIFY `severity_no` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `cfx_severitytest_answer1`
--
ALTER TABLE `cfx_severitytest_answer1`
  MODIFY `sta1_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `cfx_severity_userdata`
--
ALTER TABLE `cfx_severity_userdata`
  MODIFY `severity_no` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `cfx_survey`
--
ALTER TABLE `cfx_survey`
  MODIFY `survey_no` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `cfx_survey_userdata`
--
ALTER TABLE `cfx_survey_userdata`
  MODIFY `survey_no` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
