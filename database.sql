

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";



--

CREATE TABLE IF NOT EXISTS `articole` (
  `id` int(5) NOT NULL,
  `user_id` int(5) NOT NULL DEFAULT '1',
  `titlu` varchar(255) NOT NULL,
  `articol` text NOT NULL,
  `categorie` tinyint(4) DEFAULT NULL,
  `tags` varchar(255) NOT NULL,
  `data_adaugare` datetime NOT NULL,
  `imagine` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--

--

--
-- Table structure for table `categorii`
--

CREATE TABLE IF NOT EXISTS `categorii` (
  `id` tinyint(4) NOT NULL,
  `nume` varchar(255) NOT NULL,
  `data_adaugare` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categorii`
--

--

CREATE TABLE IF NOT EXISTS `comentarii` (
  `id` int(5) NOT NULL,
  `id_articol` int(5) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `nume` varchar(255) DEFAULT NULL,
  `comentariu` text NOT NULL,
  `data_adaugare` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `utilizatori`
--

CREATE TABLE IF NOT EXISTS `utilizatori` (
  `id` int(5) NOT NULL,
  `username` varchar(255) NOT NULL,
  `parola` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
--
-
-
--
-- Indexes for table `articole`
--
ALTER TABLE `articole`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categorii`
--
ALTER TABLE `categorii`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `nume` (`nume`);

--
-- Indexes for table `comentarii`
--
ALTER TABLE `comentarii`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `utilizatori`
--
ALTER TABLE `utilizatori`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `username` (`username`,`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articole`
--
ALTER TABLE `articole`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `categorii`
--
ALTER TABLE `categorii`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT for table `comentarii`
--
ALTER TABLE `comentarii`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `utilizatori`
--
ALTER TABLE `utilizatori`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

