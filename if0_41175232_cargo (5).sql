-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Хост: sql303.infinityfree.com
-- Время создания: Мар 19 2026 г., 13:16
-- Версия сервера: 11.4.10-MariaDB
-- Версия PHP: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `if0_41175232_cargo`
--

-- --------------------------------------------------------

--
-- Структура таблицы `amats`
--

CREATE TABLE `amats` (
  `AmataID` int(11) NOT NULL,
  `Nosaukums` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_latvian_ci;

--
-- Дамп данных таблицы `amats`
--

INSERT INTO `amats` (`AmataID`, `Nosaukums`) VALUES
(1, 'Admins'),
(2, 'Darbinieks');

-- --------------------------------------------------------

--
-- Структура таблицы `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_latvian_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_latvian_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `contacts`
--

CREATE TABLE `contacts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_latvian_ci;

--
-- Дамп данных таблицы `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `email`, `subject`, `message`, `created_at`, `updated_at`) VALUES
(1, 'dfg', 'dfg', 'dfg', 'dfg', '2025-03-12 11:56:23', '2025-03-12 11:56:23'),
(2, 'dfg', 'dfg', 'dfg', 'dfg', '2025-03-12 11:58:26', '2025-03-12 11:58:26'),
(3, 'tyu', 'tyu', 'tyu', 'tyu', '2025-03-12 12:06:04', '2025-03-12 12:06:04'),
(4, '56756', '567', '567', '567', '2025-03-14 05:10:07', '2025-03-14 05:10:07'),
(5, 'fgh', 'fgh', 'fgh', 'fgh', '2025-03-14 05:12:09', '2025-03-14 05:12:09'),
(6, 'sdf', 'sdf', 'sdf', 'sdf', '2025-03-14 05:20:55', '2025-03-14 05:20:55'),
(7, 'ery', 'ery', 'ery', 'ery', '2025-03-14 05:26:20', '2025-03-14 05:26:20'),
(8, 'dfg', 'dfg', 'dfg', 'dfg', '2025-03-14 05:29:16', '2025-03-14 05:29:16'),
(9, 'ert', 'ert', 'ert', 'ert', '2025-03-14 05:29:37', '2025-03-14 05:29:37'),
(10, 'sdfg', 'wer', 'wer', 'wer', '2025-03-14 05:31:26', '2025-03-14 05:31:26'),
(11, 'wer', 'wer', 'wer', 'wer', '2025-03-14 05:32:03', '2025-03-14 05:32:03'),
(12, 'wsedf', 'wer', 'wer', 'wer', '2025-03-14 05:35:09', '2025-03-14 05:35:09'),
(13, 'sf', 'wer', 'wer', 'wer', '2025-03-14 05:36:54', '2025-03-14 05:36:54'),
(14, 'sdf', 'sdf', 'sdf', 'sdf', '2025-03-18 10:01:35', '2025-03-18 10:01:35'),
(15, 'wer', 'wer', 'wer', 'wer', '2025-03-18 10:19:27', '2025-03-18 10:19:27'),
(16, 'dfg', 'dfg', 'dfg', 'dfg', '2025-09-23 04:29:47', '2025-09-23 04:29:47');

-- --------------------------------------------------------

--
-- Структура таблицы `darbinieki`
--

CREATE TABLE `darbinieki` (
  `DarbiniekaID` int(11) NOT NULL,
  `Vards` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Uzvards` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Parole` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Epasts` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `TelefonaNumurs` int(8) NOT NULL,
  `AmataID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_latvian_ci;

--
-- Дамп данных таблицы `darbinieki`
--

INSERT INTO `darbinieki` (`DarbiniekaID`, `Vards`, `Uzvards`, `Parole`, `Epasts`, `TelefonaNumurs`, `AmataID`) VALUES
(1, 'Jānis', 'Bērziņš', 'pass123', 'janis.berzins@email.com', 23654744, 1),
(2, 'Andris', 'Kalniņš', 'pass123', 'andris.kalnins@email.com', 26455453, 2),
(3, 'Pēteris', 'Ozols', 'pass123', 'peteris.ozols@email.com', 28970578, 2),
(4, 'Māris', 'Liepa', 'pass123', 'maris.liepa@email.com', 23225798, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `data`
--

CREATE TABLE `data` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_latvian_ci;

--
-- Дамп данных таблицы `data`
--

INSERT INTO `data` (`id`, `name`, `email`, `subject`, `message`, `created_at`, `updated_at`, `type_id`) VALUES
(17, 'fghAAAAABBBBBCCCCCzzzzzzzzzzzzz', 'fgh@.llCCCCCsssaaaaa', 'sdfsdfsdAAAAA', 'sdfsdfsdAAAAAA', NULL, '2025-10-10 06:23:03', 2),
(20, 'sdfsdf', 'dfg@dd.ll', 'dfgh', 'dfg', '2025-10-07 05:12:51', '2025-10-07 05:12:51', 1),
(22, 'dfgdfgdf', 'sdf@fff.ll', 'cfgdcfgb', 'dfgdfg', '2025-10-07 05:15:40', '2025-10-07 05:15:40', 1),
(23, 'wewer', 'sdfsd@dfgdfg.ll', 'sdfsdf', 'sdfsdf', '2025-10-07 05:20:49', '2025-10-07 05:20:49', 3),
(24, 'asdasd', 'asdfsa@ff.ll', 'sdfgsdfsd', 'sdfsdf', '2025-10-07 05:23:55', '2025-10-07 05:23:55', 2),
(25, 'werwe', 'werwe@gg.ll', 'qwqwer', 'werwer', '2025-10-07 05:26:33', '2025-10-07 05:26:33', 2),
(26, 'sdfsdf', 'dfg@dd.ll', 'dfgh', 'dfg', '2025-10-07 06:01:28', '2025-10-07 06:01:28', 1),
(27, 'fgh', 'fgh@fgh.ll', 'fgfgh', 'fghfgh', '2025-10-07 06:04:23', '2025-10-07 06:04:23', 2),
(28, 'fgh', 'fgh@fgh.ll', 'fgfgh', 'fghfgh', '2025-10-07 06:05:11', '2025-10-07 06:05:11', 1),
(29, 'zzz', 'zzzz@ss.ll', 'ssss', 'ssss', '2025-10-10 05:22:32', '2025-10-10 05:22:32', 3),
(30, 'ssss', 'sss@ss.ll', 'sssss', 'ssss', '2025-10-10 05:24:38', '2025-10-10 05:24:38', 3),
(31, 'qqqq', 'qqqq@qq.xx', 'xxxxx', 'xxxx', '2025-10-10 05:25:44', '2025-10-10 05:25:44', 4),
(32, 'asd', 'asd@gg.ll', 'asd', 'asd', '2025-10-10 06:51:10', '2025-10-10 06:51:10', 3),
(33, 'dfgdfg', 'fgh@ggtg.ll', 'dfg', 'dfg', '2025-10-10 07:22:18', '2025-10-10 07:22:18', 3);

-- --------------------------------------------------------

--
-- Структура таблицы `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_latvian_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_latvian_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_latvian_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `klienti`
--

CREATE TABLE `klienti` (
  `KlientaID` int(11) NOT NULL,
  `Vards` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Uzvards` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Parole` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Epasts` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `TelefonaNumurs` int(8) NOT NULL,
  `UznemumaNosaukums` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `JuridiskaAdrese` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `RegistracijasNumurs` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `KontaNumurs` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_latvian_ci;

--
-- Дамп данных таблицы `klienti`
--

INSERT INTO `klienti` (`KlientaID`, `Vards`, `Uzvards`, `Parole`, `Epasts`, `TelefonaNumurs`, `UznemumaNosaukums`, `JuridiskaAdrese`, `RegistracijasNumurs`, `KontaNumurs`) VALUES
(31, 'Anna', 'Ziediņa', 'parole111', 'anna.z@mail.lv', 22124456, 'BalticCargo', 'Rīgas iela 1', 'LV111111', 'LVK001'),
(32, 'Jānis', 'Kalns', 'parole222', 'janis.k@mail.lv', 22123458, 'TransLog', 'Liepājas iela 2', 'LV222222', 'LVK002'),
(33, 'Inese', 'Liepa', 'parole333', 'inese.l@mail.lv', 22453456, 'CargoExpress', 'Daugavas iela 3', 'LV333333', 'LVK003'),
(34, 'Roberts', 'Ozols', 'parole444', 'roberts.o@mail.lv', 23411456, 'RailTrans ', 'Brīvības iela 4', 'LV444444', 'LVK004'),
(35, 'Elīna', 'Pētersone', 'parole555', 'elina.p@mail.lv', 24678666, 'NordCargo', 'Krasta iela 5', 'LV555555', 'LVK005'),
(36, 'Māris', 'Bērziņš', 'parole666', 'maris.b@mail.lv', 28465676, 'ExpressLine', 'Viestura iela 6', 'LV666666', 'LVK006'),
(37, 'Laura', 'Kalniņa', 'parole777', 'laura.k@mail.lv', 20989072, 'CargoFast', 'Jomas iela 7', 'LV777777', 'LVK007'),
(38, 'Gints', 'Liepa', 'parole888', 'gints.l@mail.lv', 22621456, 'TransBaltic', 'Pils iela 8', 'LV888888', 'LVK008'),
(39, 'Kristīne', 'Ozola', 'parole999', 'kristine.o@mail.lv', 22165576, 'RailCargo', 'Meža iela 9', 'LV999999', 'LVK009'),
(40, 'Edgars', 'Ziediņš', 'parole101', 'edgars.z@mail.lv', 22709096, 'LatCargo', 'Bāriņu iela 10', 'LV101010', 'LVK010'),
(41, 'Dace', 'Kalniņa', 'parole111', 'dace.k@mail.lv', 21234234, 'TransFast', 'Raiņa iela 11', 'LV111111', 'LVK011'),
(42, 'Toms', 'Liepa', 'parole222', 'toms.l@mail.lv', 28534567, 'SpeedCargo', 'Ziedoņu iela 12', 'LV121212', 'LVK012'),
(43, 'Inga', 'Ozola', 'parole333', 'inga.o@mail.lv', 22887878, 'NordCargo', 'Dārza iela 13', 'LV131313', 'LVK013'),
(44, 'Paula', 'Ziediņa', 'parole444', 'paula.z@mail.lv', 28312456, 'EastCargo', 'Tirgus iela 14', 'LV141414', 'LVK014'),
(45, 'Raimonds', 'Kalns', 'parole555', 'raimonds.k@mail.lv', 29788784, 'WestCargo', 'Lielā iela 15', 'LV151515', 'LVK015'),
(46, 'Anna', 'Ziediņa', 'parole111', 'anna.z@mail.lv', 22124456, 'BalticCargo', 'Rīgas iela 1', 'LV111111', 'LVK001'),
(47, 'Jānis', 'Kalns', 'parole222', 'janis.k@mail.lv', 22123458, 'TransLog', 'Liepājas iela 2', 'LV222222', 'LVK002'),
(48, 'Inese', 'Liepa', 'parole333', 'inese.l@mail.lv', 22453456, 'CargoExpress', 'Daugavas iela 3', 'LV333333', 'LVK003'),
(49, 'Roberts', 'Ozols', 'parole444', 'roberts.o@mail.lv', 23411456, 'RailTrans ', 'Brīvības iela 4', 'LV444444', 'LVK004'),
(50, 'Elīna', 'Pētersone', 'parole555', 'elina.p@mail.lv', 24678666, 'NordCargo', 'Krasta iela 5', 'LV555555', 'LVK005'),
(51, 'Māris', 'Bērziņš', 'parole666', 'maris.b@mail.lv', 28465676, 'ExpressLine', 'Viestura iela 6', 'LV666666', 'LVK006'),
(52, 'Laura', 'Kalniņa', 'parole777', 'laura.k@mail.lv', 20989072, 'CargoFast', 'Jomas iela 7', 'LV777777', 'LVK007'),
(53, 'Gints', 'Liepa', 'parole888', 'gints.l@mail.lv', 22621456, 'TransBaltic', 'Pils iela 8', 'LV888888', 'LVK008'),
(54, 'Kristīne', 'Ozola', 'parole999', 'kristine.o@mail.lv', 22165576, 'RailCargo', 'Meža iela 9', 'LV999999', 'LVK009'),
(55, 'Edgars', 'Ziediņš', 'parole101', 'edgars.z@mail.lv', 22709096, 'LatCargo', 'Bāriņu iela 10', 'LV101010', 'LVK010'),
(56, 'Dace', 'Kalniņa', 'parole111', 'dace.k@mail.lv', 21234234, 'TransFast', 'Raiņa iela 11', 'LV111111', 'LVK011'),
(57, 'Toms', 'Liepa', 'parole222', 'toms.l@mail.lv', 28534567, 'SpeedCargo', 'Ziedoņu iela 12', 'LV121212', 'LVK012'),
(58, 'Inga', 'Ozola', 'parole333', 'inga.o@mail.lv', 22887878, 'NordCargo', 'Dārza iela 13', 'LV131313', 'LVK013'),
(59, 'Paula', 'Ziediņa', 'parole444', 'paula.z@mail.lv', 28312456, 'EastCargo', 'Tirgus iela 14', 'LV141414', 'LVK014'),
(60, 'Raimonds', 'Kalns', 'parole555', 'raimonds.k@mail.lv', 29788784, 'WestCargo', 'Lielā iela 15', 'LV151515', 'LVK015');

-- --------------------------------------------------------

--
-- Структура таблицы `krava`
--

CREATE TABLE `krava` (
  `KravasID` int(8) NOT NULL,
  `Nosaukums` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `VeidaID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_latvian_ci;

--
-- Дамп данных таблицы `krava`
--

INSERT INTO `krava` (`KravasID`, `Nosaukums`, `VeidaID`) VALUES
(1, 'Naftas produkti', 1),
(2, 'Tehnika', 2),
(3, 'Graudu produkcija', 3),
(4, 'Metāla izstradājumi', 4),
(5, 'Pārtikas produkti', 5),
(14, 'Graudi', 3),
(15, 'Koks', 3),
(16, 'Automašīna', 3),
(17, 'Dārzeņi', 4),
(18, 'Augļi', 4),
(19, 'Tekstils', 3),
(20, 'Celtniecības materiāli', 3),
(21, 'Saldējums', 4),
(22, 'Pārtikas produkti', 4),
(23, 'Graudi', 3),
(24, 'Koks', 3),
(25, 'Automašīna', 3),
(26, 'Dārzeņi', 4),
(27, 'Augļi', 4),
(28, 'Tekstils', 3),
(29, 'Celtniecības materiāli', 3),
(30, 'Saldējums', 4),
(31, 'Pārtikas produkti', 4);

-- --------------------------------------------------------

--
-- Структура таблицы `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_latvian_ci;

--
-- Дамп данных таблицы `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(7, '2025_03_12_124111_create_contacts_table', 2),
(10, '2025_09_25_072152_create_data_table', 3),
(11, '2025_10_02_065444_create_data_table', 4),
(12, '0001_01_01_000000_create_users_table', 5),
(13, '0001_01_01_000001_create_cache_table', 5),
(14, '0001_01_01_000002_create_jobs_table', 5),
(15, '2026_02_22_000000_create_vagonunomas_table', 5);

-- --------------------------------------------------------

--
-- Структура таблицы `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_latvian_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_latvian_ci;

--
-- Дамп данных таблицы `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('6ze8DZCEYxT5kndvOssFc0M2MqmyUy7D313ZqBg5', 2, '94.30.166.157', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 OPR/128.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUkRic2NqdEI1VWQ3SEVzV0E1Z0Zock5LWjBtUHpSdlZCZG9oSkJGeSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTk6Imh0dHBzOi8vY2FyZ28uZ3QudGMiO3M6NToicm91dGUiO047fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==', 1773940542),
('U9zfU0AeSfeCtTv8yPpy0ITCB901hK5BEDnsyVcA', NULL, '80.232.249.145', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRGU0N1BwTzJjeU9VUHl3M2h2aXM2dXJjQ3U0b0o2TXBlTWYzUnRHQiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTk6Imh0dHBzOi8vY2FyZ28uZ3QudGMiO3M6NToicm91dGUiO047fX0=', 1773917914),
('xD6OhqxEXIKFpaeEB8whRyKwTAQ2lUlSch9UzxEe', 2, '80.232.249.145', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoic0QxNW5mMk5jdHg4S0xxQ0NUbW5FQmJ6cjF0ODU4Z3Rvek5JQUVXOCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjQ6Imh0dHBzOi8vY2FyZ28uZ3QudGMvTm9tYSI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mjt9', 1773928355);

-- --------------------------------------------------------

--
-- Структура таблицы `type`
--

CREATE TABLE `type` (
  `id` int(11) NOT NULL,
  `type` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_latvian_ci;

--
-- Дамп данных таблицы `type`
--

INSERT INTO `type` (`id`, `type`) VALUES
(1, 'Info'),
(2, 'Cena'),
(3, 'Ziņas'),
(4, 'Piedāvajums');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `AmataID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_latvian_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `AmataID`) VALUES
(2, 'Violeta', 'violetatarasova@gmail.com', NULL, '$2y$12$mwok8LwVClXwI/QJ8S.cG.YSGk7dd8rxGDnusCps0G4yBnOWqpOiK', NULL, '2026-03-08 02:35:36', '2026-03-08 02:35:36', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `vagonaraksturojums`
--

CREATE TABLE `vagonaraksturojums` (
  `VagonaID` int(11) NOT NULL,
  `VeidaID` int(3) NOT NULL,
  `KravasID` int(11) NOT NULL,
  `Celtspeja` float NOT NULL,
  `VagonaNumurs` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_latvian_ci;

--
-- Дамп данных таблицы `vagonaraksturojums`
--

INSERT INTO `vagonaraksturojums` (`VagonaID`, `VeidaID`, `KravasID`, `Celtspeja`, `VagonaNumurs`) VALUES
(1, 1, 2, 65000, 65040310),
(2, 2, 1, 60000, 79800954),
(3, 3, 5, 68000, 26665432),
(19, 1, 2, 65000, 65040310),
(20, 2, 1, 60000, 79800954),
(21, 3, 5, 68000, 26665432),
(22, 3, 3, 70000, 34567890),
(23, 2, 4, 50000, 45678901),
(24, 2, 6, 55000, 56789012),
(25, 1, 7, 60000, 67890123),
(26, 3, 7, 65000, 78901234),
(27, 3, 6, 70000, 89012345),
(28, 2, 7, 75000, 90123456);

-- --------------------------------------------------------

--
-- Структура таблицы `vagonudati`
--

CREATE TABLE `vagonudati` (
  `DatuID` int(11) NOT NULL,
  `NomasID` int(11) NOT NULL,
  `VagonaID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_latvian_ci;

--
-- Дамп данных таблицы `vagonudati`
--

INSERT INTO `vagonudati` (`DatuID`, `NomasID`, `VagonaID`) VALUES
(2, 1, 1),
(3, 2, 2),
(4, 2, 2),
(5, 3, 3),
(6, 3, 3);

-- --------------------------------------------------------

--
-- Структура таблицы `vagonunoma`
--

CREATE TABLE `vagonunoma` (
  `NomasID` int(11) NOT NULL,
  `KlientaID` int(11) NOT NULL,
  `KravasID` int(8) NOT NULL,
  `Svars` float NOT NULL,
  `VeidaID` int(11) NOT NULL,
  `VagonuSkaits` int(30) NOT NULL,
  `NomasSakumaPeriods` date NOT NULL,
  `NomasBeiguPeriods` date NOT NULL,
  `KopejaMaksa` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_latvian_ci;

--
-- Дамп данных таблицы `vagonunoma`
--

INSERT INTO `vagonunoma` (`NomasID`, `KlientaID`, `KravasID`, `Svars`, `VeidaID`, `VagonuSkaits`, `NomasSakumaPeriods`, `NomasBeiguPeriods`, `KopejaMaksa`) VALUES
(79, 41, 1, 50, 1, 3, '2026-03-11', '2026-03-15', 210),
(80, 42, 2, 65, 2, 5, '2026-03-12', '2026-03-16', 275),
(81, 43, 3, 67, 2, 2, '2026-03-13', '2026-03-17', 150),
(82, 44, 4, 69, 3, 3, '2026-03-14', '2026-03-18', 180),
(83, 45, 5, 70, 4, 4, '2026-03-15', '2026-03-19', 240),
(84, 31, 1, 59, 1, 5, '2026-03-01', '2026-03-05', 250),
(85, 32, 2, 45, 2, 6, '2026-03-02', '2026-03-06', 300),
(86, 33, 3, 50, 3, 4, '2026-03-03', '2026-03-07', 280),
(87, 34, 4, 60, 5, 3, '2026-03-04', '2026-03-08', 180),
(88, 35, 5, 69, 1, 2, '2026-03-05', '2026-03-09', 150),
(89, 36, 1, 65, 5, 5, '2026-03-06', '2026-03-10', 225),
(90, 37, 2, 79, 2, 4, '2026-03-07', '2026-03-11', 200),
(91, 38, 3, 78, 1, 3, '2026-03-08', '2026-03-12', 195),
(92, 39, 4, 75, 1, 2, '2026-03-09', '2026-03-13', 120),
(93, 40, 5, 55, 5, 4, '2026-03-10', '2026-03-14', 220),
(94, 41, 1, 50, 2, 3, '2026-03-11', '2026-03-15', 210),
(95, 42, 2, 65, 3, 5, '2026-03-12', '2026-03-16', 275),
(96, 43, 3, 67, 1, 2, '2026-03-13', '2026-03-17', 150),
(97, 44, 4, 69, 1, 3, '2026-03-14', '2026-03-18', 180),
(98, 45, 5, 70, 3, 4, '2026-03-15', '2026-03-19', 240),
(99, 31, 1, 59, 1, 5, '2026-03-01', '2026-03-05', 250),
(100, 32, 2, 45, 2, 6, '2026-03-02', '2026-03-06', 300),
(101, 33, 3, 50, 3, 4, '2026-03-03', '2026-03-07', 280),
(102, 34, 4, 60, 1, 3, '2026-03-04', '2026-03-08', 180),
(103, 35, 5, 69, 1, 2, '2026-03-05', '2026-03-09', 150),
(104, 36, 1, 65, 5, 5, '2026-03-06', '2026-03-10', 225),
(105, 37, 2, 79, 3, 4, '2026-03-07', '2026-03-11', 200),
(106, 38, 3, 78, 3, 3, '2026-03-08', '2026-03-12', 195),
(107, 39, 4, 75, 4, 2, '2026-03-09', '2026-03-13', 120),
(108, 40, 5, 55, 5, 4, '2026-03-10', '2026-03-14', 220),
(109, 41, 1, 50, 5, 3, '2026-03-11', '2026-03-15', 210),
(110, 42, 2, 65, 2, 5, '2026-03-12', '2026-03-16', 275),
(111, 43, 3, 67, 3, 2, '2026-03-13', '2026-03-17', 150),
(112, 44, 4, 69, 4, 3, '2026-03-14', '2026-03-18', 180),
(113, 45, 5, 70, 3, 4, '2026-03-15', '2026-03-19', 240),
(114, 31, 1, 59, 1, 5, '2026-03-01', '2026-03-05', 250),
(115, 32, 2, 45, 2, 6, '2026-03-02', '2026-03-06', 300),
(116, 33, 3, 50, 3, 4, '2026-03-03', '2026-03-07', 280),
(117, 34, 4, 60, 4, 3, '2026-03-04', '2026-03-08', 180),
(118, 35, 5, 69, 4, 2, '2026-03-05', '2026-03-09', 150),
(119, 36, 1, 65, 5, 5, '2026-03-06', '2026-03-10', 225),
(120, 37, 2, 79, 3, 4, '2026-03-07', '2026-03-11', 200),
(121, 38, 3, 78, 3, 3, '2026-03-08', '2026-03-12', 195),
(122, 39, 4, 75, 4, 2, '2026-03-09', '2026-03-13', 120),
(123, 40, 5, 55, 5, 4, '2026-03-10', '2026-03-14', 220),
(124, 41, 1, 50, 5, 3, '2026-03-11', '2026-03-15', 210),
(125, 42, 2, 65, 2, 5, '2026-03-12', '2026-03-16', 275),
(126, 43, 3, 67, 3, 2, '2026-03-13', '2026-03-17', 150),
(127, 44, 4, 69, 4, 3, '2026-03-14', '2026-03-18', 180),
(128, 45, 5, 70, 3, 4, '2026-03-15', '2026-03-19', 240);

-- --------------------------------------------------------

--
-- Структура таблицы `vagonunomas`
--

CREATE TABLE `vagonunomas` (
  `NomasID` bigint(20) UNSIGNED NOT NULL,
  `KlientaID` int(11) DEFAULT NULL,
  `KlientaVards` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `KlientaUzvards` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `KlientaUznemumaNos` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `DarbiniekaID` int(11) DEFAULT NULL,
  `VagonaID` int(11) DEFAULT NULL,
  `KravasID` int(11) DEFAULT NULL,
  `VagonuSkaits` int(11) DEFAULT NULL,
  `NomasSakumaPeriods` date DEFAULT NULL,
  `NomasBeiguPeriods` date DEFAULT NULL,
  `NosutisanasStacija` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Galastacija` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `KopejaMaksa` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_latvian_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `veidi`
--

CREATE TABLE `veidi` (
  `VeidaID` int(11) NOT NULL,
  `Nosaukums` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Celtspeja` float NOT NULL,
  `VagonuSkaits` int(11) NOT NULL,
  `CenaParDiennakti` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_latvian_ci;

--
-- Дамп данных таблицы `veidi`
--

INSERT INTO `veidi` (`VeidaID`, `Nosaukums`, `Celtspeja`, `VagonuSkaits`, `CenaParDiennakti`) VALUES
(1, 'Pusvagons', 60, 5, 50),
(2, 'Hopervagons', 75, 5, 45),
(3, 'Platforma', 80, 4, 40),
(4, 'Termos vagons', 65, 3, 60),
(5, 'Cisterna', 70, 10, 57);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `amats`
--
ALTER TABLE `amats`
  ADD PRIMARY KEY (`AmataID`);

--
-- Индексы таблицы `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Индексы таблицы `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Индексы таблицы `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `darbinieki`
--
ALTER TABLE `darbinieki`
  ADD PRIMARY KEY (`DarbiniekaID`),
  ADD KEY `AmataID` (`AmataID`);

--
-- Индексы таблицы `data`
--
ALTER TABLE `data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type_id` (`type_id`);

--
-- Индексы таблицы `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Индексы таблицы `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Индексы таблицы `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `klienti`
--
ALTER TABLE `klienti`
  ADD PRIMARY KEY (`KlientaID`);

--
-- Индексы таблицы `krava`
--
ALTER TABLE `krava`
  ADD PRIMARY KEY (`KravasID`),
  ADD KEY `VeidaID` (`VeidaID`);

--
-- Индексы таблицы `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Индексы таблицы `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Индексы таблицы `type`
--
ALTER TABLE `type`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `AmataID` (`AmataID`);

--
-- Индексы таблицы `vagonaraksturojums`
--
ALTER TABLE `vagonaraksturojums`
  ADD PRIMARY KEY (`VagonaID`),
  ADD KEY `KravasID` (`KravasID`),
  ADD KEY `VeidaID` (`VeidaID`);

--
-- Индексы таблицы `vagonudati`
--
ALTER TABLE `vagonudati`
  ADD PRIMARY KEY (`DatuID`),
  ADD KEY `NomasID` (`NomasID`,`VagonaID`),
  ADD KEY `VagonaID` (`VagonaID`);

--
-- Индексы таблицы `vagonunoma`
--
ALTER TABLE `vagonunoma`
  ADD PRIMARY KEY (`NomasID`),
  ADD KEY `VeidaID` (`VeidaID`),
  ADD KEY `KlientaID` (`KlientaID`),
  ADD KEY `KravasID` (`KravasID`),
  ADD KEY `KravasID_2` (`KravasID`),
  ADD KEY `fk_veidii` (`VeidaID`);

--
-- Индексы таблицы `vagonunomas`
--
ALTER TABLE `vagonunomas`
  ADD PRIMARY KEY (`NomasID`);

--
-- Индексы таблицы `veidi`
--
ALTER TABLE `veidi`
  ADD PRIMARY KEY (`VeidaID`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `amats`
--
ALTER TABLE `amats`
  MODIFY `AmataID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT для таблицы `darbinieki`
--
ALTER TABLE `darbinieki`
  MODIFY `DarbiniekaID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT для таблицы `data`
--
ALTER TABLE `data`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT для таблицы `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `klienti`
--
ALTER TABLE `klienti`
  MODIFY `KlientaID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT для таблицы `krava`
--
ALTER TABLE `krava`
  MODIFY `KravasID` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT для таблицы `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `type`
--
ALTER TABLE `type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `vagonaraksturojums`
--
ALTER TABLE `vagonaraksturojums`
  MODIFY `VagonaID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT для таблицы `vagonudati`
--
ALTER TABLE `vagonudati`
  MODIFY `DatuID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `vagonunoma`
--
ALTER TABLE `vagonunoma`
  MODIFY `NomasID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT для таблицы `vagonunomas`
--
ALTER TABLE `vagonunomas`
  MODIFY `NomasID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `veidi`
--
ALTER TABLE `veidi`
  MODIFY `VeidaID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `darbinieki`
--
ALTER TABLE `darbinieki`
  ADD CONSTRAINT `darbinieki_ibfk_1` FOREIGN KEY (`AmataID`) REFERENCES `amats` (`AmataID`);

--
-- Ограничения внешнего ключа таблицы `data`
--
ALTER TABLE `data`
  ADD CONSTRAINT `data_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `krava`
--
ALTER TABLE `krava`
  ADD CONSTRAINT `fk_krava_veidi` FOREIGN KEY (`VeidaID`) REFERENCES `veidi` (`VeidaID`),
  ADD CONSTRAINT `krava_ibfk_1` FOREIGN KEY (`VeidaID`) REFERENCES `veidi` (`VeidaID`);

--
-- Ограничения внешнего ключа таблицы `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_usserrr` FOREIGN KEY (`AmataID`) REFERENCES `amats` (`AmataID`);

--
-- Ограничения внешнего ключа таблицы `vagonunoma`
--
ALTER TABLE `vagonunoma`
  ADD CONSTRAINT `fk_veidii` FOREIGN KEY (`VeidaID`) REFERENCES `veidi` (`VeidaID`),
  ADD CONSTRAINT `vagonunoma_ibfk_1` FOREIGN KEY (`KlientaID`) REFERENCES `klienti` (`KlientaID`),
  ADD CONSTRAINT `vagonunoma_ibfk_3` FOREIGN KEY (`KravasID`) REFERENCES `krava` (`KravasID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
