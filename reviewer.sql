-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 20, 2024 at 07:59 AM
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
-- Database: `reviewer`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(55) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `first_name`, `last_name`, `email`, `password`, `created`) VALUES
(1, 'admin', 'admin', 'admin@gmail.com', 'admin', '2024-10-12 13:09:25');

-- --------------------------------------------------------

--
-- Table structure for table `exam_answer_option`
--

CREATE TABLE `exam_answer_option` (
  `id` int(11) NOT NULL,
  `user_id` int(255) NOT NULL,
  `question_id` int(255) NOT NULL,
  `subject_id` int(255) NOT NULL,
  `course_id` int(255) NOT NULL,
  `user_answer_option` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exam_course`
--

CREATE TABLE `exam_course` (
  `id` int(11) NOT NULL,
  `course` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exam_course`
--

INSERT INTO `exam_course` (`id`, `course`) VALUES
(1, 'BLEPT'),
(2, 'CELE'),
(3, 'EELE'),
(4, 'ALE');

-- --------------------------------------------------------

--
-- Table structure for table `exam_option`
--

CREATE TABLE `exam_option` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `option` int(2) NOT NULL,
  `title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exam_option`
--

INSERT INTO `exam_option` (`id`, `question_id`, `option`, `title`) VALUES
(1, 1, 1, '570 ~ Mecca'),
(2, 1, 2, '631 ~ Arabia'),
(3, 1, 3, '344 ~ Baghdad'),
(4, 1, 4, '467 ~ Arabian Gulf'),
(5, 2, 1, 'drought'),
(6, 2, 2, 'desert'),
(7, 2, 3, 'ocean'),
(8, 2, 4, 'mountain range'),
(9, 3, 1, 'Execution of Louis XIII'),
(10, 3, 2, 'Founding of Lutheranism'),
(11, 3, 3, 'Storming of the Bastile'),
(12, 3, 4, 'Surrender of Napoleon'),
(13, 4, 1, 'Treaty of Ghent'),
(14, 4, 2, 'Declaration of Independance'),
(15, 4, 3, 'Treaty of Paris'),
(16, 4, 4, 'Declaration of The Rights of Man'),
(17, 5, 1, 'amend the Constitution'),
(18, 5, 2, 'declare actions of the president unconstitutional'),
(19, 5, 3, 'pass laws needed to carry out its other powers'),
(20, 5, 4, 'veto the supreme court.'),
(21, 6, 1, 'vote'),
(22, 6, 2, 'approve'),
(23, 6, 3, 'appea'),
(24, 6, 4, 'cancel'),
(25, 7, 1, 'Tetzel'),
(26, 7, 2, ' Cromwell'),
(27, 7, 3, 'Augustine'),
(28, 7, 4, 'Luther'),
(29, 8, 1, 'Egyptian'),
(30, 8, 2, 'Babylonian'),
(31, 8, 3, 'Phoenician'),
(32, 8, 4, 'Hittite'),
(33, 9, 1, 'Plataea'),
(34, 9, 2, ' Marathon'),
(35, 9, 3, 'Athens'),
(36, 9, 4, 'Salamis'),
(37, 10, 1, 'John Adams'),
(38, 10, 2, 'Patrick Henry'),
(39, 10, 3, ' Thomas Jefferson'),
(40, 10, 4, 'George Washington'),
(41, 11, 1, 'Marie Antoinette'),
(42, 11, 2, 'Isabella'),
(43, 11, 3, 'Joan of Arc'),
(44, 11, 4, 'Cleopatra'),
(45, 12, 1, 'creating a systematic body of laws'),
(46, 12, 2, 'putting down a terrible revolt'),
(47, 12, 3, 'reclaiming lost territory of the Roman Empire'),
(48, 12, 4, 'making the city rich and wealthy'),
(49, 13, 1, 'religion'),
(50, 13, 2, 'architecture'),
(51, 13, 3, 'alphabet'),
(52, 13, 4, 'all of the above'),
(53, 14, 1, 'Pope Leo X'),
(54, 14, 2, 'Henry VII'),
(55, 14, 3, 'God'),
(56, 14, 4, 'Ann Boleyn'),
(57, 15, 1, 'George M. Cohan'),
(58, 15, 2, 'Irving Berlin'),
(59, 15, 3, 'Jim Thorpe'),
(60, 15, 4, 'Edgar Rice Burroughs'),
(61, 16, 1, 'political instability'),
(62, 16, 2, 'Christianity'),
(63, 16, 3, 'economic and social problem'),
(64, 16, 4, 'weakening frontiers'),
(65, 17, 1, 'Doric'),
(66, 17, 2, 'Ionic'),
(67, 17, 3, 'Corinthian'),
(68, 17, 4, 'all the above'),
(69, 18, 1, 'Increased labor needs in cities'),
(70, 18, 2, 'Child labor'),
(71, 18, 3, 'Safe working conditions'),
(72, 18, 4, 'Increased pay'),
(73, 19, 1, 'Britain'),
(74, 19, 2, 'France'),
(75, 19, 3, 'Germany'),
(76, 19, 4, 'Spain'),
(77, 20, 1, 'John Fowler'),
(78, 20, 2, 'John Kay'),
(79, 20, 3, 'Andrew Meikle'),
(80, 20, 4, 'Jethro Tull'),
(137, 35, 1, 'John Fowler'),
(138, 35, 2, 'John Kay'),
(139, 35, 3, 'Andrew Meikle'),
(140, 35, 4, 'Jethro Tull'),
(141, 36, 1, 'Eastern and Western Hemispheres'),
(142, 36, 2, 'Northern and Southern Hemispheres'),
(143, 36, 3, 'The Tropic of Cancer and Tropic of Capricorn'),
(144, 36, 4, 'North and South Poles'),
(145, 37, 1, ' the Egyptians'),
(146, 37, 2, 'the Phoenicians'),
(147, 37, 3, 'the Babylonians'),
(148, 37, 4, 'the Hittite'),
(149, 38, 1, 'Atlantic Ocean'),
(150, 38, 2, ' Pacific Ocean'),
(151, 38, 3, 'Indian Ocean'),
(152, 38, 4, 'Caspian Sea'),
(153, 39, 1, 'Robert Boyle'),
(154, 39, 2, 'Antoine Lavoisier'),
(155, 39, 3, 'Sir Isaac Newton'),
(156, 39, 4, 'None of the above'),
(157, 40, 1, 'Chariot racing'),
(158, 40, 2, 'Plays'),
(159, 40, 3, 'Gladiator fights'),
(160, 40, 4, ' All of the above'),
(161, 41, 1, 'England'),
(162, 41, 2, 'France'),
(163, 41, 3, 'Germany'),
(164, 41, 4, 'Italy'),
(165, 42, 1, 'Discovered electricity'),
(166, 42, 2, 'Invented the light bulb'),
(167, 42, 3, 'Invented the telephone'),
(168, 42, 4, 'Invented the telegram'),
(169, 43, 1, 'Greek'),
(170, 43, 2, 'Arabic'),
(171, 43, 3, 'German'),
(172, 43, 4, 'Latin'),
(173, 44, 1, 'Constantine'),
(174, 44, 2, 'Diocletian'),
(175, 44, 3, 'Alaric'),
(176, 44, 4, 'Odoacer'),
(177, 45, 1, 'Mars'),
(178, 45, 2, 'Jupiter'),
(179, 45, 3, 'Ares'),
(180, 45, 4, 'Neptune'),
(181, 46, 1, 'Odoacer'),
(182, 46, 2, 'Nero'),
(183, 46, 3, 'Tiberius'),
(184, 46, 4, 'Romulus Augustulus'),
(185, 47, 1, 'Cotton Roads'),
(186, 47, 2, 'Culture Roads'),
(187, 47, 3, 'Silk Roads'),
(188, 47, 4, 'Asian Roads'),
(189, 48, 1, 'Robert Boyle'),
(190, 48, 2, 'Antoine Lavoisier'),
(191, 48, 3, 'Sir Isaac Newton'),
(192, 48, 4, 'All of the above'),
(193, 49, 1, 'Zeus'),
(194, 49, 2, 'Hector'),
(195, 49, 3, 'Paris'),
(196, 49, 4, 'Priam'),
(197, 50, 1, 'England'),
(198, 50, 2, 'France'),
(199, 50, 3, 'Netherlands'),
(200, 50, 4, 'Spain'),
(201, 51, 1, 'Intolerable Acts'),
(202, 51, 2, 'Townshend Acts'),
(203, 51, 3, 'Stamp Act'),
(204, 51, 4, 'Sugar Act'),
(205, 52, 1, 'Vizier'),
(206, 52, 2, 'Ramses II'),
(207, 52, 3, 'Khnum'),
(208, 52, 4, 'Imhotep'),
(209, 53, 1, 'poison gas'),
(210, 53, 2, 'trench'),
(211, 53, 3, 'submarines'),
(212, 53, 4, 'all of the above'),
(213, 54, 1, 'Savannah'),
(214, 54, 2, 'Sahara'),
(215, 54, 3, 'Rain forest'),
(216, 54, 4, 'All of the above'),
(217, 55, 1, 'The exact,or precise,location of a place'),
(218, 55, 2, 'An exact location using the intersection of latitude and longitude lines'),
(219, 55, 3, 'A flat representation of the world'),
(220, 55, 4, 'all of the above'),
(221, 56, 1, 'overgrazing of land contributes'),
(222, 56, 2, 'climate changes/drought contribute.'),
(223, 56, 3, 'causes famine.'),
(224, 56, 4, 'all of the above'),
(225, 57, 1, 'Micronesia'),
(226, 57, 2, 'Papa New Guinea'),
(227, 57, 3, 'Polynesia'),
(228, 57, 4, 'Melenesia'),
(229, 58, 1, 'Map Projection'),
(230, 58, 2, 'artifact'),
(231, 58, 3, 'Meridian Mississippi'),
(232, 58, 4, 'Prime Meridian'),
(233, 59, 1, 'Byzantium'),
(234, 59, 2, 'New Rome'),
(235, 59, 3, 'Constantinople'),
(236, 59, 4, 'All of the Above'),
(237, 60, 1, 'Plessy v. Ferguson'),
(238, 60, 2, 'Brown v. Board of Education'),
(239, 60, 3, 'Miranda v. Arizona'),
(240, 60, 4, 'Jordan v. Bryant'),
(241, 61, 1, ' Africa'),
(242, 61, 2, 'The Canary Islands'),
(243, 61, 3, 'Santo Domingo'),
(244, 61, 4, 'The Bahamas'),
(245, 62, 1, 'Samuel Crompton'),
(246, 62, 2, 'Denis Papin'),
(247, 62, 3, 'James Watt'),
(248, 62, 4, 'All of the above'),
(249, 63, 1, 'the Old State House'),
(250, 63, 2, 'Central High School'),
(251, 63, 3, 'his childhood home in Hope, Arkansas'),
(252, 63, 4, 'Arkansas Post National Memorial'),
(253, 64, 1, 'shogun'),
(254, 64, 2, 'samurai'),
(255, 64, 3, 'hari-kiri'),
(256, 64, 4, 'bushido'),
(257, 65, 1, 'Petrarch'),
(258, 65, 2, 'Erasmus'),
(259, 65, 3, 'Castiglione'),
(260, 65, 4, 'Sir Thomas More'),
(261, 66, 1, 'Louis XVI\'s castle'),
(262, 66, 2, 'French prison'),
(263, 66, 3, 'Court in Paris'),
(264, 66, 4, 'Where Robespierre lived'),
(265, 67, 1, 'a journey to the Holy Land'),
(266, 67, 2, 'fasting for religious reasons'),
(267, 67, 3, 'a holy war'),
(268, 67, 4, 'a political war'),
(269, 68, 1, 'town'),
(270, 68, 2, 'church'),
(271, 68, 3, 'plantation'),
(272, 68, 4, 'river'),
(273, 69, 1, ' gold and silver'),
(274, 69, 2, 'slaves and copper'),
(275, 69, 3, 'silk and ivory'),
(276, 69, 4, 'gold and salt'),
(277, 70, 1, 'the Persians'),
(278, 70, 2, 'the Egyptians'),
(279, 70, 3, 'the Greeks'),
(280, 70, 4, 'the Chinese'),
(281, 71, 1, 'Ferdinand Magellan'),
(282, 71, 2, 'Marco Polo'),
(283, 71, 3, 'Leif Eriksson'),
(284, 71, 4, 'No one'),
(285, 72, 1, 'opportunity cost'),
(286, 72, 2, 'resources'),
(287, 72, 3, 'price'),
(288, 72, 4, 'scarcity'),
(289, 73, 1, 'Illinois'),
(290, 73, 2, 'Virgina'),
(291, 73, 3, 'Georgia'),
(292, 73, 4, 'Connecticut'),
(293, 74, 1, 'Free Market'),
(294, 74, 2, 'Mixed'),
(295, 74, 3, 'Traditional'),
(296, 74, 4, 'Command'),
(297, 75, 1, 'Columbia'),
(298, 75, 2, 'Charleston'),
(299, 75, 3, 'Durham'),
(300, 75, 4, 'Atlanta'),
(301, 76, 1, 'Douglas McCarthur'),
(302, 76, 2, 'Mao Ze Dong'),
(303, 76, 3, 'Ho Chi Minh'),
(304, 76, 4, 'Jackie Cha'),
(305, 77, 1, 'Free enterprise'),
(306, 77, 2, 'market economy'),
(307, 77, 3, 'capitalism'),
(308, 77, 4, 'scarcity'),
(309, 78, 1, 'Democratic-Republican'),
(310, 78, 2, 'Democrat'),
(311, 78, 3, 'Federalist'),
(312, 78, 4, 'Republican'),
(313, 79, 1, 'Euboea'),
(314, 79, 2, 'Mycenae'),
(315, 79, 3, 'Attica'),
(316, 79, 4, 'Thebes'),
(317, 80, 1, 'to punish them for aiding the Ionian rebels'),
(318, 80, 2, 'to punish them for siding with Macedonia'),
(319, 80, 3, 'to steal their ships so they could use them for trade'),
(320, 80, 4, 'because it was on the way to Sparta'),
(321, 81, 1, 'large-brained group that modern humans belong to.'),
(322, 81, 2, 'tall ape group that existed before modern humans.'),
(323, 81, 3, 'last group of hominids that had a hunched body'),
(324, 81, 4, 'first hominid to migrate and settle in new areas'),
(325, 82, 1, 'East Anglia'),
(326, 82, 2, 'the Middle East'),
(327, 82, 3, 'the Byzantine Empire'),
(328, 82, 4, 'the Ottoman Empire'),
(329, 83, 1, 'special purpose map'),
(330, 83, 2, 'political map'),
(331, 83, 3, 'physical map'),
(332, 83, 4, 'relief map'),
(333, 84, 1, 'Oil'),
(334, 84, 2, 'Machines, engines, pumps'),
(335, 84, 3, 'Vegetables'),
(336, 84, 4, 'Vehicles'),
(337, 85, 1, 'Louisiana'),
(338, 85, 2, 'New york'),
(339, 85, 3, 'New Orleans'),
(340, 85, 4, 'St. Louis'),
(341, 86, 1, 'to cook with'),
(342, 86, 2, 'to make paper'),
(343, 86, 3, 'weapons'),
(344, 86, 4, 'to build with'),
(345, 87, 1, 'Greeks'),
(346, 87, 2, 'Serbs'),
(347, 87, 3, 'Turks'),
(348, 87, 4, 'Russians'),
(349, 88, 1, 'Popes'),
(350, 88, 2, 'monarchs'),
(351, 88, 3, 'Catholic Priests'),
(352, 88, 4, 'Protestants'),
(353, 89, 1, 'dictatorship'),
(354, 89, 2, 'constitutional monarchy'),
(355, 89, 3, 'military'),
(356, 89, 4, 'socialism'),
(357, 90, 1, 'April 7,1887'),
(358, 90, 2, 'April 12,1861'),
(359, 90, 3, 'April 12,1871'),
(360, 90, 4, 'April 12,1890'),
(361, 91, 1, 'July 25, 1941'),
(362, 91, 2, 'June 5, 1955'),
(363, 91, 3, 'August 28, 1955'),
(364, 91, 4, 'July 25, 1945'),
(365, 92, 1, 'five hills'),
(366, 92, 2, 'six hills'),
(367, 92, 3, 'seven hills'),
(368, 92, 4, 'eight hills'),
(369, 93, 1, 'Justinian\'s Constitution'),
(370, 93, 2, 'Justinian\'s Code'),
(371, 93, 3, 'Justinian\'s Law'),
(372, 93, 4, 'Justinian\'s Compact'),
(373, 94, 1, 'Charlemagne'),
(374, 94, 2, 'Henry II'),
(375, 94, 3, 'Henry VIII'),
(376, 94, 4, 'the Pope'),
(377, 95, 1, 'Eat a feast'),
(378, 95, 2, 'Pray to God'),
(379, 95, 3, 'Fasting occurs'),
(380, 95, 4, 'They sing'),
(381, 96, 1, 'Hammurabi'),
(382, 96, 2, 'The King of Uratu'),
(383, 96, 3, 'Sargon II'),
(384, 96, 4, 'Gilgamesh'),
(385, 97, 1, 'cave paintings'),
(386, 97, 2, 'bronze'),
(387, 97, 3, 'cultural diffusion'),
(388, 97, 4, 'none of the above'),
(389, 98, 1, 'democracy'),
(390, 98, 2, 'monarchy'),
(391, 98, 3, 'bureaucracy'),
(392, 98, 4, 'slavery'),
(393, 99, 1, 'the lire'),
(394, 99, 2, 'the denarius'),
(395, 99, 3, 'the shekel'),
(396, 99, 4, 'the drachma'),
(397, 100, 1, 'map'),
(398, 100, 2, 'longitude'),
(399, 100, 3, 'cartographer'),
(400, 100, 4, 'equator'),
(401, 101, 1, 'Spain'),
(402, 101, 2, 'Switzerland'),
(403, 101, 3, 'France'),
(404, 101, 4, 'Germany'),
(405, 102, 1, 'Nile River Valley'),
(406, 102, 2, 'The Sahel'),
(407, 102, 3, 'Great Rift Valley'),
(408, 102, 4, 'All of the above'),
(409, 103, 1, 'Persia'),
(410, 103, 2, 'Punjab'),
(411, 103, 3, 'Pakistan'),
(412, 103, 4, 'Bali'),
(413, 104, 1, 'artifact'),
(414, 104, 2, 'equator'),
(415, 104, 3, 'map projection'),
(416, 104, 4, 'map'),
(417, 105, 1, 'Cupid'),
(418, 105, 2, 'Pluto'),
(419, 105, 3, 'Minerva'),
(420, 105, 4, 'Venus'),
(421, 106, 1, 'Neptune'),
(422, 106, 2, ' Vulcan'),
(423, 106, 3, 'Mars'),
(424, 106, 4, 'Jupiter'),
(425, 107, 1, 'Atlantic Coastal Plain'),
(426, 107, 2, 'Climate'),
(427, 107, 3, 'Appalachian Mountains'),
(428, 107, 4, 'Central Lowlands'),
(429, 108, 1, 'many Native American people lost their land'),
(430, 108, 2, 'Europeans learned about the Americas'),
(431, 108, 3, 'Spanish colonists settled in the West Indies'),
(432, 108, 4, 'all of the above'),
(433, 109, 1, 'Headrights'),
(434, 109, 2, 'Sons of Liberty'),
(435, 109, 3, 'Colonial Courts'),
(436, 109, 4, 'Middle Passage'),
(437, 110, 1, 'mercenaries'),
(438, 110, 2, 'privateers'),
(439, 110, 3, 'Loyalists/Tories'),
(440, 110, 4, 'Hessians'),
(441, 111, 1, 'Gunpowder'),
(442, 111, 2, 'Silk'),
(443, 111, 3, 'Salt'),
(444, 111, 4, 'Iron'),
(445, 112, 1, 'Tyranny'),
(446, 112, 2, 'Oligarchy'),
(447, 112, 3, 'Aristocracy'),
(448, 112, 4, 'Democracy'),
(449, 113, 1, 'pond'),
(450, 113, 2, 'fish tank'),
(451, 113, 3, 'sea'),
(452, 113, 4, 'mountain range'),
(453, 114, 1, 'Judicial'),
(454, 114, 2, 'Executive'),
(455, 114, 3, 'Military'),
(456, 114, 4, 'Legislative'),
(457, 115, 1, 'Julius Caesar'),
(458, 115, 2, 'Mark Antony'),
(459, 115, 3, 'Octavian Augustus'),
(460, 115, 4, 'Tarquin the Proud'),
(461, 116, 1, 'Haiti'),
(462, 116, 2, 'venezuela'),
(463, 116, 3, 'Cuba'),
(464, 116, 4, 'Chile'),
(465, 117, 1, 'kings and queens of the earliest modern humans'),
(466, 117, 2, 'development and culture of the earliest hominids.'),
(467, 117, 3, 'communication and social systems of early apes'),
(468, 117, 4, 'trade and barter systems among modern humans.'),
(469, 118, 1, 'The desert sand can be shifted for up to a thousand miles, covering over lands'),
(470, 118, 2, 'the sand causes wars'),
(471, 118, 3, 'the ground water levels are dropping with shifting sand.'),
(472, 118, 4, 'Reservoirs are built after the storm.'),
(473, 119, 1, 'Bologna'),
(474, 119, 2, 'Calaise'),
(475, 119, 3, 'Nantes'),
(476, 119, 4, 'Venice'),
(545, 137, 1, 'sample '),
(546, 137, 2, 'sample'),
(547, 137, 3, 'sample'),
(548, 137, 4, 'sample');

-- --------------------------------------------------------

--
-- Table structure for table `exam_question`
--

CREATE TABLE `exam_question` (
  `id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `answer` enum('1','2','3','4') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exam_question`
--

INSERT INTO `exam_question` (`id`, `subject_id`, `question`, `answer`) VALUES
(1, 1, 'In which year was Muhammad born?', '1'),
(2, 1, 'A ____________ is a region that has very little rainfall.', '2'),
(3, 1, 'Which of these happened during the French Revolution?', '3'),
(4, 1, 'Americans and Britains met in France to end the American Revolution by signing the', '3'),
(5, 1, 'The &quot;elastic clause&quot; gives Congress the power to', '3'),
(6, 1, 'If the Supreme Court decides that a law or act of the President goes against the Constitution, it can ____the law or act.', '4'),
(7, 1, 'What man is most responsible for sparking the Protestant Reformation?', '4'),
(8, 1, 'The Greek alphabet is based on which other alphabet?', '3'),
(9, 1, 'Where did the Athenians and Persians fight their first decisive battle?', '2'),
(10, 1, 'Who said, Give me liberty or give me death!', '2'),
(11, 1, ' Last Queen of France', '1'),
(12, 1, 'Emperor Justinian is most famous for', '1'),
(13, 1, 'The Romans were heavily influenced by the Greek&#039;s', '4'),
(14, 1, 'Who told Martin Luther he could no longer be a member of the Church?', '1'),
(15, 1, 'Who wrote the song, &amp;amp;quot;Over There&amp;amp;quot;, which became the anthem for soldiers joining WWI?', '1'),
(16, 1, ' At the end of the Roman Empire, all of these factors contributed to the fall of Rome except,', '2'),
(17, 1, 'What type of Greek column is the simplest design?', '1'),
(18, 1, 'How did industrialization have a NEGATIVE impact on society?', '2'),
(19, 1, 'In 1787, abolitionists founded the Committee for the Abolition of the Slave Trade in what nation?', '1'),
(20, 1, 'Who invented the flying shuttle?', '2'),
(35, 1, 'Who invented the flying shuttle?', '2'),
(36, 1, 'The prime meridian separates the', '1'),
(37, 1, 'The Greeks adopted their alphabet from which civilization?', '2'),
(38, 1, 'Japan is a chain of islands in the northern', '2'),
(39, 1, 'Who was known as the Father Modern Chemistry?', '2'),
(40, 1, 'What forms of entertainment were popular among Roman citizens?', '4'),
(41, 1, 'Where was the Magna Carta signed?', '1'),
(42, 1, 'What impact did Thomas Edison have on American society?', '2'),
(43, 1, 'What language greatly influenced English that came out of the Roman Empire?', '4'),
(44, 1, 'After he decided the empire was too large to manage, divided the Roman Empire into four parts', '2'),
(45, 1, '____was the Roman god of war', '1'),
(46, 1, ' _____was the last emperor to rule to Roman Empire', '4'),
(47, 1, 'A series of trade and cultural transmission routes that were central to cultural interaction through regions of the Asian continent', '3'),
(48, 1, 'Who was known as the Father Modern Chemistry?', '2'),
(49, 1, 'Who was the mighty Trojan warrior that killed Patroclus?', '2'),
(50, 1, 'Alvaro de Mendaña was an explorer for which country?', '4'),
(51, 1, 'The Colonist&#039;s name for the Coercive Acts', '1'),
(52, 1, 'Who is known as the Father of Medicine?', '4'),
(53, 1, 'World War 1 was fought using what kind of warfare?', '4'),
(54, 1, 'In which region of Africa would you find large animals that eat grass?', '1'),
(55, 1, 'What is absolute location?', '2'),
(56, 1, 'Which of the following is true about desertification:', '4'),
(57, 1, 'American Samoa, a United States territory, is part of', '3'),
(58, 1, 'The meridian at Greenwich, England, from which longitude is measured east and west.', '4'),
(59, 1, 'The capital of the Eastern Roman Empire is', '4'),
(60, 1, 'What famous Supreme Court case ruled that police officers must read the constitutional rights to all people they place under arrest?', '3'),
(61, 1, 'What was the first stop on Columbus&#039; voyage to Asia?', '2'),
(62, 1, 'In 1776, the invention of the steam engine sped up industrialization, which British engineer was responsible for this?', '3'),
(63, 1, 'From which historic Landmark in Arkansas, did Bill Clinton announce his run for president of the United States?', '1'),
(64, 1, 'What is the Japanese word meaning great general?', '1'),
(65, 1, 'Who wrote a book entitled &quot;The Courtier&quot; that described proper conduct?', '3'),
(66, 1, 'The Bastille', '2'),
(67, 1, 'What is a crusade?', '3'),
(68, 1, 'An example of a natural physical feature is a', '4'),
(69, 1, 'West African trade was based mainly on which two items?', '4'),
(70, 1, 'Roman art was greatly influenced by,', '3'),
(71, 1, 'Which explorer was the first to circumnavigate(go all the way around the world)?', '1'),
(72, 1, 'The inability to satisfy all wants at the same time. All resources and goods are limited. What word goes with this definition', '4'),
(73, 1, 'What colony did not attend the 1st Continental Congress?', '3'),
(74, 1, 'There are four economies. Which one is centrally owned by the government?', '4'),
(75, 1, 'What is the capital of South Carolina?', '1'),
(76, 1, 'Who was the Leader of the Vietnamese that declared their Independence from France after defeated them in 1954?', '3'),
(77, 1, 'What is an economic system in which private business operates in competition and largely free of state control called?', '1'),
(78, 1, 'In order to continue their control over the judiciary after the election of President Jefferson, _____legislators passed the Judiciary Act of 1801.', '3'),
(79, 1, 'What was the area around Athens called?', '3'),
(80, 1, 'Why did Darius I of Persia send troops to attack Athens?', '1'),
(81, 1, 'The hominid group of Homo sapiens, or Wise Man, is the', '1'),
(82, 1, 'The Eastern Roman Empire came to be known as', '3'),
(83, 1, 'A road map is an example of a', '1'),
(84, 1, 'What is the main export of Mexico?', '4'),
(85, 1, 'Land named in honor of King Louis XIV of France', '1'),
(86, 1, 'What was papyrus used for in Ancient Egypt?', '2'),
(87, 1, 'In 1875, the peasants of Bosnia and Herzegovina rebelled against the', '3'),
(88, 1, 'Which group become more powerful in Europe after the Reformation?', '2'),
(89, 1, 'What is the form of government in the United Kingdom?', '2'),
(90, 1, 'When did the Civil war start?', '2'),
(91, 1, 'When was Emmett Till born?', '1'),
(92, 1, 'Rome is known as the city built on', '3'),
(93, 1, 'Emperor Justinian&#039;s systematic body of laws is called', '2'),
(94, 1, 'Who ruled the Holy Roman Empire?', '1'),
(95, 1, 'When people go to Mecca, what do they do there?', '2'),
(96, 1, 'Under ____ , the law did no apply equally to all.', '1'),
(97, 1, 'The spread of beliefs, institutions, or skills of one society', '3'),
(98, 1, 'A state or nation in which the supreme power is held by a single person.', '2'),
(99, 1, 'What was the basic unit of currency in ancient Greece?', '4'),
(100, 1, 'A person who makes maps', '3'),
(101, 1, 'John Calvin was the Protestant leader from', '3'),
(102, 1, 'Meaning “the shore of the desert”, what region lies between the Sahara Desert and the Savannah?', '2'),
(103, 1, 'In AD 1739, Nadir Shah, the ruler of _____ invaded India', '1'),
(104, 1, 'A representation of all parts of the earth&#039;s surface, showing countries, bodies of water and cities.', '4'),
(105, 1, 'Aphrodite was given what Roman name?', '4'),
(106, 1, 'Poseidon was given what Roman name?', '1'),
(107, 1, 'Region along the gulf of Mexico and the east coast of North America', '1'),
(108, 1, '____was a result of Columbus&#039;s voyage', '4'),
(109, 1, 'Colonists created a secret society that used violence to frighten tax collectors known as the', '2'),
(110, 1, 'German soldiers hired by the British during the Revolution', '4'),
(111, 1, 'What resource helped make some of the African empires very wealthy? In some places, it was considered even more valuable than gold.', '3'),
(112, 1, 'Which word describes a few wealthy men who held power?', '2'),
(113, 1, 'A _________ is a body of salt water not as large as an ocean', '3'),
(114, 1, 'Which one of these is not a branch of government?', '3'),
(115, 1, 'The last king to rule Rome. He was driven out of power because of his harsh rule.', '4'),
(116, 1, 'The first Latin American county to gain independence was', '1'),
(117, 1, 'A paleoanthropologist specializes in the study of the', '2'),
(118, 1, 'Sandstorms can contribute to desertification because:', '1'),
(119, 1, 'Which Italian city was located on the Adriatic Sea and had a large navy?', '4'),
(137, 5, 'sample rani ha', '2');

-- --------------------------------------------------------

--
-- Table structure for table `exam_result`
--

CREATE TABLE `exam_result` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `total_questions` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exam_subject`
--

CREATE TABLE `exam_subject` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exam_subject`
--

INSERT INTO `exam_subject` (`id`, `course_id`, `subject`) VALUES
(1, 1, 'ENGLISH'),
(2, 3, 'ENGLISH'),
(3, 4, 'MATH'),
(5, 2, 'ENGLISH');

-- --------------------------------------------------------

--
-- Table structure for table `exam_user`
--

CREATE TABLE `exam_user` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `last_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `password` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_approved` tinyint(1) DEFAULT 0,
  `course_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exam_user`
--

INSERT INTO `exam_user` (`id`, `first_name`, `last_name`, `email`, `password`, `created`, `is_approved`, `course_id`) VALUES
(1, 'user', 'users', 'users@gmail.com', 'user123', '2024-11-20 06:57:18', 1, 1),
(2, 'admin', 'admin', 'rr@gmail.com', '123', '2024-11-09 19:23:19', 1, 3),
(3, 'sample', 'sample', 'sample@gmail.com', '123', '2024-11-09 13:12:39', 0, 1),
(4, 'ss', 'ss', 'ss@gmail.com', '123', '2024-11-10 07:31:32', 0, 2),
(5, 'jj', 'jj', 'jj@gmail.com', '123', '2024-11-10 07:31:51', 0, 4),
(6, 'ee', 'ee', 'ee@gmail.com', '123', '2024-11-10 07:32:11', 0, 4),
(7, 'tt', 'tt', 'tt@gmail.com', '123', '2024-11-10 07:32:40', 0, 2),
(8, 'dd', 'dd', 'dd@gmail.com', '123', '2024-11-10 07:33:03', 0, 3),
(9, 'uu', 'uu', 'uu@gmail.com', '123', '2024-11-10 07:34:58', 0, 4);

-- --------------------------------------------------------

--
-- Table structure for table `user_activity`
--

CREATE TABLE `user_activity` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `activity_type` varchar(50) NOT NULL,
  `activity_time` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_activity`
--

INSERT INTO `user_activity` (`id`, `user_id`, `activity_type`, `activity_time`) VALUES
(1, 1, 'login', '2024-11-10 20:24:12'),
(2, 1, 'login', '2024-11-16 19:45:19'),
(3, 1, 'login', '2024-11-16 20:37:20'),
(4, 1, 'login', '2024-11-16 20:40:12'),
(5, 1, 'login', '2024-11-16 20:42:43'),
(6, 1, 'login', '2024-11-17 23:15:07'),
(7, 2, 'login', '2024-11-18 00:11:58'),
(8, 1, 'login', '2024-11-18 00:37:45'),
(9, 2, 'login', '2024-11-18 00:41:02'),
(10, 1, 'login', '2024-11-18 00:48:45'),
(11, 2, 'login', '2024-11-18 00:49:04'),
(12, 1, 'login', '2024-11-18 00:50:10'),
(13, 1, 'login', '2024-11-19 13:24:16'),
(14, 1, 'login', '2024-11-19 15:26:48'),
(15, 1, 'login', '2024-11-20 13:03:19'),
(16, 1, 'login', '2024-11-20 13:03:19'),
(17, 1, 'login', '2024-11-20 14:07:29'),
(18, 1, 'login', '2024-11-20 14:09:38'),
(19, 1, 'login', '2024-11-20 14:25:32'),
(20, 1, 'login', '2024-11-20 14:30:06'),
(21, 1, 'login', '2024-11-20 14:57:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exam_answer_option`
--
ALTER TABLE `exam_answer_option`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exam_course`
--
ALTER TABLE `exam_course`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exam_option`
--
ALTER TABLE `exam_option`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exam_question`
--
ALTER TABLE `exam_question`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exam_result`
--
ALTER TABLE `exam_result`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `exam_subject`
--
ALTER TABLE `exam_subject`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exam_user`
--
ALTER TABLE `exam_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_activity`
--
ALTER TABLE `user_activity`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `exam_answer_option`
--
ALTER TABLE `exam_answer_option`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `exam_course`
--
ALTER TABLE `exam_course`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `exam_option`
--
ALTER TABLE `exam_option`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=553;

--
-- AUTO_INCREMENT for table `exam_question`
--
ALTER TABLE `exam_question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- AUTO_INCREMENT for table `exam_result`
--
ALTER TABLE `exam_result`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `exam_subject`
--
ALTER TABLE `exam_subject`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `exam_user`
--
ALTER TABLE `exam_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user_activity`
--
ALTER TABLE `user_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `exam_result`
--
ALTER TABLE `exam_result`
  ADD CONSTRAINT `exam_result_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `exam_user` (`id`),
  ADD CONSTRAINT `exam_result_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `exam_subject` (`id`),
  ADD CONSTRAINT `exam_result_ibfk_3` FOREIGN KEY (`course_id`) REFERENCES `exam_course` (`id`);

--
-- Constraints for table `user_activity`
--
ALTER TABLE `user_activity`
  ADD CONSTRAINT `user_activity_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `exam_user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
