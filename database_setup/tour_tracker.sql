-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 27, 2024 at 03:24 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tour_tracker`
--

-- --------------------------------------------------------

--
-- Table structure for table `departure`
--

CREATE TABLE `departure` (
  `id` int(11) NOT NULL,
  `tour_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `watch` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departure_update`
--

CREATE TABLE `departure_update` (
  `id` int(11) NOT NULL,
  `departure_id` int(11) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `availability` tinyint(4) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tour`
--

CREATE TABLE `tour` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `operator_id` int(11) NOT NULL,
  `url` varchar(2048) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tour_operator`
--

CREATE TABLE `tour_operator` (
  `id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `web` varchar(32) NOT NULL,
  `supported` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tour_operator`
--

INSERT INTO `tour_operator` (`id`, `name`, `web`, `supported`) VALUES
(1, 'G Adventures', 'gadventures.com', 1),
(2, 'Intrepid', 'intrepidtravel.com', 1),
(3, 'Tru Travels', 'trutravels.com', 0),
(4, 'Intro Travel', 'introtravel.com', 0);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_departure_alerts`
-- (See below for the actual view)
--
CREATE TABLE `view_departure_alerts` (
`departure_id` int(11)
,`sync_date` datetime
,`sync_days_ago` int(7)
,`alert_type` varchar(22)
,`availability_change` int(11)
,`new_availability` tinyint(4)
,`price_change` decimal(9,2)
,`new_price` decimal(8,2)
,`percentage` decimal(13,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_departure_alerts_last_occurence`
-- (See below for the actual view)
--
CREATE TABLE `view_departure_alerts_last_occurence` (
`departure_id` int(11)
,`alert_type` varchar(22)
,`last_occurence` datetime
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_departure_latest_sync`
-- (See below for the actual view)
--
CREATE TABLE `view_departure_latest_sync` (
`departure_update_id` int(11)
,`departure_id` int(11)
,`latest_sync` datetime
,`availability` tinyint(4)
,`price` decimal(8,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_departure_sync_changes`
-- (See below for the actual view)
--
CREATE TABLE `view_departure_sync_changes` (
`departure_id` int(11)
,`sync_date` datetime
,`new_price` decimal(8,2)
,`new_availability` tinyint(4)
,`price_change` decimal(9,2)
,`availability_change` int(5)
,`watch` tinyint(1)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_departure_sync_history`
-- (See below for the actual view)
--
CREATE TABLE `view_departure_sync_history` (
`tour_id` int(11)
,`departure_id` int(11)
,`tour_name` varchar(128)
,`operator_id` int(11)
,`operator_name` varchar(32)
,`start_date` date
,`end_date` date
,`duration_days` int(8)
,`price` decimal(8,2)
,`availability` tinyint(4)
,`sync_date` datetime
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_departure_sync_recent`
-- (See below for the actual view)
--
CREATE TABLE `view_departure_sync_recent` (
`tour_id` int(11)
,`departure_id` int(11)
,`tour_name` varchar(128)
,`operator_id` int(11)
,`operator_name` varchar(32)
,`start_date` date
,`end_date` date
,`duration_days` int(8)
,`price` decimal(8,2)
,`availability` tinyint(4)
,`sync_date` datetime
,`watch` tinyint(1)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_tour_last_sync`
-- (See below for the actual view)
--
CREATE TABLE `view_tour_last_sync` (
`id` int(11)
,`name` varchar(128)
,`last_sync` datetime
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_tour_spaces`
-- (See below for the actual view)
--
CREATE TABLE `view_tour_spaces` (
`name` varchar(128)
,`spaces` tinyint(4)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_tour_sync_price_summary`
-- (See below for the actual view)
--
CREATE TABLE `view_tour_sync_price_summary` (
`tour_id` int(11)
,`tour_name` varchar(128)
,`sync_date` datetime
,`days_ago` int(7)
,`min_price` decimal(8,2)
,`max_price` decimal(8,2)
,`avg_price` decimal(9,2)
);

-- --------------------------------------------------------

--
-- Structure for view `view_departure_alerts`
--
DROP TABLE IF EXISTS `view_departure_alerts`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_departure_alerts`  AS SELECT `v`.`departure_id` AS `departure_id`, `v`.`sync_date` AS `sync_date`, to_days(current_timestamp()) - to_days(`v`.`sync_date`) AS `sync_days_ago`, 'PRICE REDUCED' FROM `view_departure_sync_changes` AS `v` WHERE `v`.`price_change` < 0unionselect `v`.`departure_id` AS `departure_id`,`v`.`sync_date` AS `sync_date`,to_days(current_timestamp()) - to_days(`v`.`sync_date`) AS `sync_days_ago`,'PRICE INCREASED' collate utf8mb4_general_ci AS `alert_type`,NULL AS `availability_change`,NULL AS `new_availability`,`v`.`price_change` AS `price_change`,`v`.`new_price` AS `new_price`,round(`v`.`price_change` / (`v`.`new_price` - `v`.`price_change`),2) AS `percentage` from `view_departure_sync_changes` `v` where `v`.`price_change` > 0 union select `v`.`departure_id` AS `departure_id`,`v`.`sync_date` AS `sync_date`,to_days(current_timestamp()) - to_days(`v`.`sync_date`) AS `sync_days_ago`,'PRICE INCREASED' collate utf8mb4_general_ci AS `alert_type`,NULL AS `availability_change`,NULL AS `new_availability`,`v`.`price_change` AS `price_change`,`v`.`new_price` AS `new_price`,round(`v`.`price_change` / (`v`.`new_price` - `v`.`price_change`),2) AS `percentage` from `view_departure_sync_changes` `v` where `v`.`price_change` > 0 union select `v`.`departure_id` AS `departure_id`,`v`.`sync_date` AS `sync_date`,to_days(current_timestamp()) - to_days(`v`.`sync_date`) AS `sync_days_ago`,if(`v`.`new_availability` > 0,'AVAILABILITY REDUCED','SOLD OUT') collate utf8mb4_general_ci AS `alert_type`,`v`.`availability_change` AS `availability_change`,`v`.`new_availability` AS `new_availability`,NULL AS `price_change`,NULL AS `new_price`,NULL AS `percentage` from `view_departure_sync_changes` `v` where `v`.`availability_change` < 0 union select `v`.`departure_id` AS `departure_id`,`v`.`sync_date` AS `sync_date`,to_days(current_timestamp()) - to_days(`v`.`sync_date`) AS `sync_days_ago`,'AVAILABILITY INCREASED' collate utf8mb4_general_ci AS `alert_type`,`v`.`availability_change` AS `availability_change`,`v`.`new_availability` AS `new_availability`,NULL AS `price_change`,NULL AS `new_price`,NULL AS `percentage` from `view_departure_sync_changes` `v` where `v`.`availability_change` > 0 order by `sync_date` desc  ;

-- --------------------------------------------------------

--
-- Structure for view `view_departure_alerts_last_occurence`
--
DROP TABLE IF EXISTS `view_departure_alerts_last_occurence`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_departure_alerts_last_occurence`  AS SELECT `view_departure_alerts`.`departure_id` AS `departure_id`, `view_departure_alerts`.`alert_type` AS `alert_type`, max(`view_departure_alerts`.`sync_date`) AS `last_occurence` FROM `view_departure_alerts` GROUP BY `view_departure_alerts`.`departure_id`, `view_departure_alerts`.`alert_type` ORDER BY `view_departure_alerts`.`departure_id` ASC ;

-- --------------------------------------------------------

--
-- Structure for view `view_departure_latest_sync`
--
DROP TABLE IF EXISTS `view_departure_latest_sync`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_departure_latest_sync`  AS SELECT `q`.`departure_update_id` AS `departure_update_id`, `q`.`departure_id` AS `departure_id`, `q`.`latest_sync` AS `latest_sync`, `du`.`availability` AS `availability`, `du`.`price` AS `price` FROM ((select `departure_update`.`id` AS `departure_update_id`,`departure_update`.`departure_id` AS `departure_id`,max(`departure_update`.`created_at`) AS `latest_sync` from `departure_update` group by `departure_update`.`departure_id`) `q` join `departure_update` `du` on(`du`.`departure_id` = `q`.`departure_id` and `du`.`created_at` = `q`.`latest_sync`)) ;

-- --------------------------------------------------------

--
-- Structure for view `view_departure_sync_changes`
--
DROP TABLE IF EXISTS `view_departure_sync_changes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_departure_sync_changes`  AS SELECT `q`.`departure_id` AS `departure_id`, `q`.`sync_date` AS `sync_date`, `q`.`new_price` AS `new_price`, `q`.`new_availability` AS `new_availability`, `q`.`price_change` AS `price_change`, `q`.`availability_change` AS `availability_change`, `q`.`watch` AS `watch` FROM (select `view_departure_sync_history`.`departure_id` AS `departure_id`,`view_departure_sync_history`.`sync_date` AS `sync_date`,`view_departure_sync_history`.`price` AS `new_price`,`view_departure_sync_history`.`availability` AS `new_availability`,`view_departure_sync_history`.`price` - lead(`view_departure_sync_history`.`price`,1) over ( partition by `view_departure_sync_history`.`departure_id` order by `view_departure_sync_history`.`sync_date` desc) AS `price_change`,`view_departure_sync_history`.`availability` - lead(`view_departure_sync_history`.`availability`,1) over ( partition by `view_departure_sync_history`.`departure_id` order by `view_departure_sync_history`.`sync_date` desc) AS `availability_change`,`departure`.`watch` AS `watch` from (`view_departure_sync_history` join `departure` on(`departure`.`id` = `view_departure_sync_history`.`departure_id`)) where `view_departure_sync_history`.`departure_id` in (select `departure`.`id` from `departure`) order by `view_departure_sync_history`.`departure_id`,`view_departure_sync_history`.`sync_date` desc) AS `q` WHERE `q`.`availability_change` <> 0 OR `q`.`price_change` <> 0 ;

-- --------------------------------------------------------

--
-- Structure for view `view_departure_sync_history`
--
DROP TABLE IF EXISTS `view_departure_sync_history`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_departure_sync_history`  AS SELECT `tour`.`id` AS `tour_id`, `dep`.`id` AS `departure_id`, `tour`.`name` AS `tour_name`, `top`.`id` AS `operator_id`, `top`.`name` AS `operator_name`, `dep`.`start_date` AS `start_date`, `dep`.`end_date` AS `end_date`, to_days(`dep`.`end_date`) - to_days(`dep`.`start_date`) + 1 AS `duration_days`, `du`.`price` AS `price`, `du`.`availability` AS `availability`, `du`.`created_at` AS `sync_date` FROM (((`departure_update` `du` join `departure` `dep` on(`dep`.`id` = `du`.`departure_id`)) join `tour` on(`dep`.`tour_id` = `tour`.`id`)) join `tour_operator` `top` on(`top`.`id` = `tour`.`operator_id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `view_departure_sync_recent`
--
DROP TABLE IF EXISTS `view_departure_sync_recent`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_departure_sync_recent`  AS SELECT `vdsh`.`tour_id` AS `tour_id`, `vdsh`.`departure_id` AS `departure_id`, `vdsh`.`tour_name` AS `tour_name`, `vdsh`.`operator_id` AS `operator_id`, `vdsh`.`operator_name` AS `operator_name`, `vdsh`.`start_date` AS `start_date`, `vdsh`.`end_date` AS `end_date`, `vdsh`.`duration_days` AS `duration_days`, `vdsh`.`price` AS `price`, `vdsh`.`availability` AS `availability`, `vdsh`.`sync_date` AS `sync_date`, `departure`.`watch` AS `watch` FROM ((`view_departure_sync_history` `vdsh` join `view_tour_last_sync` `vtls` on(`vdsh`.`tour_id` = `vtls`.`id` and `vdsh`.`sync_date` = `vtls`.`last_sync`)) join `departure` on(`vdsh`.`departure_id` = `departure`.`id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `view_tour_last_sync`
--
DROP TABLE IF EXISTS `view_tour_last_sync`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_tour_last_sync`  AS SELECT `tour`.`id` AS `id`, `tour`.`name` AS `name`, (select max(`du`.`created_at`) from (`departure_update` `du` join `departure` `d` on(`d`.`id` = `du`.`departure_id`)) where `d`.`tour_id` = `tour`.`id`) AS `last_sync` FROM `tour` ;

-- --------------------------------------------------------

--
-- Structure for view `view_tour_spaces`
--
DROP TABLE IF EXISTS `view_tour_spaces`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_tour_spaces`  AS SELECT `tour`.`name` AS `name`, `q`.`spaces` AS `spaces` FROM ((select `departure`.`tour_id` AS `tour_id`,max(`departure_update`.`availability`) AS `spaces` from (`departure_update` join `departure` on(`departure_update`.`departure_id` = `departure`.`id`)) group by `departure`.`tour_id`) `q` join `tour` on(`tour`.`id` = `q`.`tour_id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `view_tour_sync_price_summary`
--
DROP TABLE IF EXISTS `view_tour_sync_price_summary`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_tour_sync_price_summary`  AS SELECT `view_departure_sync_history`.`tour_id` AS `tour_id`, `view_departure_sync_history`.`tour_name` AS `tour_name`, `view_departure_sync_history`.`sync_date` AS `sync_date`, to_days(current_timestamp()) - to_days(`view_departure_sync_history`.`sync_date`) AS `days_ago`, min(`view_departure_sync_history`.`price`) AS `min_price`, max(`view_departure_sync_history`.`price`) AS `max_price`, round(avg(`view_departure_sync_history`.`price`),2) AS `avg_price` FROM `view_departure_sync_history` WHERE `view_departure_sync_history`.`tour_id` <> 0 GROUP BY `view_departure_sync_history`.`tour_id`, `view_departure_sync_history`.`tour_name`, `view_departure_sync_history`.`sync_date` ORDER BY `view_departure_sync_history`.`tour_id` ASC, to_days(current_timestamp()) - to_days(`view_departure_sync_history`.`sync_date`) ASC ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `departure`
--
ALTER TABLE `departure`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departure_update`
--
ALTER TABLE `departure_update`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tour`
--
ALTER TABLE `tour`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tour_operator`
--
ALTER TABLE `tour_operator`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `departure`
--
ALTER TABLE `departure`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departure_update`
--
ALTER TABLE `departure_update`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tour`
--
ALTER TABLE `tour`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tour_operator`
--
ALTER TABLE `tour_operator`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
