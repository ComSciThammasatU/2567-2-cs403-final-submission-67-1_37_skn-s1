-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 12, 2025 at 06:02 PM
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
-- Database: `cs403_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `buy_requests`
--

CREATE TABLE `buy_requests` (
  `id` int(11) NOT NULL,
  `organization_id` int(11) NOT NULL,
  `plastic_type` varchar(50) NOT NULL,
  `province` varchar(100) NOT NULL,
  `district` varchar(100) NOT NULL,
  `sub_district` varchar(100) NOT NULL,
  `min_kg` decimal(10,2) NOT NULL,
  `price_per_kg` decimal(10,2) NOT NULL,
  `destination_info` text NOT NULL,
  `status` varchar(20) DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buy_requests`
--

INSERT INTO `buy_requests` (`id`, `organization_id`, `plastic_type`, `province`, `district`, `sub_district`, `min_kg`, `price_per_kg`, `destination_info`, `status`, `created_at`) VALUES
(1, 5, 'PET', 'ขอนแก่น', 'อำเภอบ้านไผ่', 'ตำบลบ้านไผ่', 45.00, 5.00, 'ofjgoijh', 'active', '2025-04-10 07:54:58'),
(2, 5, 'PET', 'ขอนแก่น', 'อำเภอเมืองขอนแก่น', 'ตำบลในเมือง', 56.00, 4.00, 'สวัสดีครับบบบบบ', 'active', '2025-04-10 08:02:05'),
(3, 5, 'PET', 'นครราชสีมา', 'อำเภอเมืองนครราชสีมา', 'ตำบลในเมือง', 70.00, 5.00, '่กหนยเ่หรนพ่เน่กดย', 'active', '2025-04-10 08:11:53'),
(4, 5, 'PET', 'กรุงเทพมหานคร', 'เขตดุสิต', 'แขวงดุสิต', 50.00, 12.00, 'ฉันก็ไม่รู้สินะ', 'active', '2025-04-16 03:54:06'),
(5, 5, 'FoodBox', 'เชียงใหม่', 'อำเภอเมืองเชียงใหม่', 'ตำบลช้างม่อย', 80.00, 3.00, 'we', 'active', '2025-04-16 03:54:50'),
(6, 6, 'HDPE', 'กรุงเทพมหานคร', 'เขตพระนคร', 'แขวงพระบรมมหาราชวัง', 70.00, 3.00, 'ออิอิิิอิอิ', 'active', '2025-04-16 05:33:38'),
(7, 6, 'PET', 'ชลบุรี', 'อำเภอเมืองชลบุรี', 'ตำบลบ้านสวน', 50.00, 4.00, 'อิอิอิอิอิอิิอ', 'active', '2025-04-16 07:08:46'),
(8, 6, 'PET', 'ชลบุรี', 'อำเภอบางละมุง', 'ตำบลนาเกลือ', 40.00, 5.00, 'oidjfoijweoijgosd', 'active', '2025-04-17 08:05:59'),
(9, 6, 'Yakult', 'ชลบุรี', 'อำเภอบางละมุง', 'ตำบลนาเกลือ', 40.00, 3.00, 'อิอิิอิอิอ', 'active', '2025-05-04 06:27:21'),
(10, 5, 'HDPE', 'กรุงเทพมหานคร', 'เขตดุสิต', 'แขวงวชิรพยาบาล', 555.00, 3.00, 'vbbvbvbvbvvbbbv', 'active', '2025-05-04 08:40:18'),
(11, 6, 'Yakult', 'ชลบุรี', 'อำเภอเมืองชลบุรี', 'ตำบลบางปลาสร้อย', 40.00, 2.00, 'vbvbvbbvbbv', 'active', '2025-05-04 09:20:10'),
(12, 7, 'PET', 'กรุงเทพมหานคร', 'เขตดุสิต', 'แขวงวชิรพยาบาล', 50.00, 2.00, 'อาร์มพีรวิชญ์ บุตรสา', 'active', '2025-05-04 09:31:49'),
(13, 7, 'Yakult', 'กรุงเทพมหานคร', 'เขตดุสิต', 'แขวงดุสิต', 100.00, 4.00, 'fdpogjpoerjgpo', 'active', '2025-05-06 07:47:38'),
(14, 7, 'HDPE', 'เชียงใหม่', 'อำเภอเมืองเชียงใหม่', 'ตำบลช้างม่อย', 1000.00, 3.00, 'sdagadsg', 'active', '2025-05-06 07:55:00'),
(15, 7, 'PET', 'เชียงใหม่', 'อำเภอเมืองเชียงใหม่', 'ตำบลศรีภูมิ', 100000.00, 4.00, 'dfkagoierjmpg', 'active', '2025-05-26 09:53:29'),
(16, 5, 'HDPE', 'ชลบุรี', 'อำเภอเมืองชลบุรี', 'ตำบลบางปลาสร้อย', 50.00, 6.00, 'HDPE รีไซเคิลเป็นอะไรได้บ้าง:\n	•	ขวดพลาสติกใหม่ เช่น ขวดแชมพู ขวดน้ำยาซักผ้า\n	•	ท่อพีวีซี (เช่น ท่อน้ำ หรือรางระบายน้ำ)\n	•	แผ่นพลาสติกหรือแผ่นไม้เทียม\n	•	ถังพลาสติก หรือภาชนะสำหรับอุตสาหกรรม\n	•	ถุงพลาสติกหนา (เช่น ถุงหูหิ้วแบบทนทาน)', 'active', '2025-05-26 17:45:49'),
(17, 5, 'HDPE', 'กรุงเทพมหานคร', 'เขตพระนคร', 'แขวงพระบรมมหาราชวัง', 50.00, 5.00, 'รขยะในต าบลห้วยทราย สืบเนื่องมาจากปริมาณขยะที่มากขึ้น และในพื้นที่มีข้อจ ากัดในการก าจัดขยะ\nจึงได้จัดเวทีประชาคม ทั้ง 5 หมู่บ้านเพื่อสอบถามความต้องการของประชาชนเกี่ยวกับโครงการจัดเก็บขยะ\nในต าบล ซึ่ งประชาชนส่วนใหญ่เห็นด้วยที่จะให้องค์การบริหารส่วนต าบลห้วยทรายด าเนินการจัดเก็บขยะ\nโดยจ้างบริษัทเอกชนด าเนินการ ทางองค์การบริหารส่วนต าบลห้วยทรายจึงสอบถามมายังท่าน เพื่อส ารวจ\nความสนใจที่จะเข้าร่วมโครงการดังกล่าว', 'active', '2025-05-27 02:38:04');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment_text` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `post_id`, `user_id`, `comment_text`, `timestamp`) VALUES
(10, 10, 1, 'สวัสดีโซเซียลแคม', '2025-03-31 16:10:09'),
(11, 15, 1, 'gfhrth', '2025-04-03 08:03:16'),
(12, 15, 1, 'ยสกเยน่นทดทำย', '2025-04-03 08:03:51'),
(13, 12, 1, 'sdsd', '2025-04-08 08:39:20'),
(14, 14, 1, 'อิอิ', '2025-05-26 06:55:48'),
(16, 22, 2, 'สวัสดีครับ\n', '2025-05-27 02:35:10');

-- --------------------------------------------------------

--
-- Table structure for table `Perissodactyla_Detritus_Manifest`
--

CREATE TABLE `Perissodactyla_Detritus_Manifest` (
  `trash_id` int(11) NOT NULL,
  `group_id` int(11) DEFAULT NULL,
  `weight` decimal(10,2) NOT NULL,
  `trash_type` varchar(255) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Perissodactyla_Detritus_Manifest`
--

INSERT INTO `Perissodactyla_Detritus_Manifest` (`trash_id`, `group_id`, `weight`, `trash_type`, `added_at`) VALUES
(1, 5, 34.00, 'plastic', '2025-04-29 06:18:03'),
(2, 5, 34.00, 'plastic', '2025-04-29 06:18:09'),
(5, 5, 1.00, 'plastic', '2025-04-29 06:54:16'),
(6, 9, 23.00, 'plastic', '2025-04-29 06:58:00'),
(7, 12, 34.00, 'plastic', '2025-04-29 07:03:36'),
(8, 12, 20.00, 'plastic', '2025-04-29 07:08:10'),
(9, 12, 34.00, 'plastic', '2025-04-29 07:13:04'),
(10, 15, 30.00, 'plastic', '2025-04-29 07:44:31'),
(11, 16, 46.00, 'plastic', '2025-04-29 08:25:10'),
(12, 16, 56.00, 'plastic', '2025-04-29 08:25:16'),
(13, 14, 45.00, 'plastic', '2025-05-06 10:58:23'),
(14, 18, 8.00, 'plastic', '2025-05-19 05:27:49'),
(15, 20, 40.00, 'plastic', '2025-05-19 05:39:29'),
(16, 20, 50.00, 'plastic', '2025-05-19 06:15:49'),
(17, 21, 50.00, 'plastic', '2025-05-19 06:16:01'),
(18, 22, 70.00, 'plastic', '2025-05-19 06:16:42'),
(19, 22, 50.00, 'plastic', '2025-05-19 06:18:54'),
(20, 22, 40.00, 'plastic', '2025-05-19 06:19:29'),
(21, 23, 24.00, 'plastic', '2025-05-19 06:26:24'),
(22, 23, 40.00, 'plastic', '2025-05-19 06:26:33'),
(23, 24, 50.00, 'plastic', '2025-05-19 06:42:05'),
(24, 25, 34.00, 'plastic', '2025-05-26 10:27:00'),
(25, 25, 45.00, 'plastic', '2025-05-26 10:27:07'),
(26, 26, 40.00, 'plastic', '2025-05-26 18:59:03'),
(27, 31, 50.00, 'plastic', '2025-05-26 19:00:38'),
(28, 32, 40.00, 'plastic', '2025-05-26 19:01:12'),
(29, 32, 600.00, 'plastic', '2025-05-26 19:01:16'),
(30, 34, 60.00, 'plastic', '2025-05-27 02:53:19'),
(31, 34, 40.00, 'plastic', '2025-05-27 02:53:25');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `category` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `user_id`, `title`, `content`, `category`, `timestamp`) VALUES
(8, 1, '“ขวดพลาสติก PET ใช้ซ้ำได้กี่ครั้ง? การรีไซเคิลมีข้อจำกัดอะไรบ้าง?”', 'หลายคนคิดว่าขวด PET สามารถใช้ซ้ำได้เรื่อย ๆ แต่จริง ๆ แล้วมีข้อจำกัดอะไรบ้าง?\r\n	•	ขวด PET มีสารเคมีตกค้างที่เป็นอันตรายหรือไม่ถ้านำมาใช้ซ้ำหลายครั้ง?\r\n	•	วิธีสังเกตว่าขวดพลาสติกควรนำไปรีไซเคิลแทนที่จะใช้ซ้ำ\r\n	•	กระบวนการรีไซเคิลขวด PET ทำอย่างไร และมีการนำไปใช้ผลิตอะไรบ้าง?', '', '2025-03-31 16:07:37'),
(9, 1, '“ทำไมขยะอาหารเป็นปัญหามากกว่าที่เราคิด? วิธีลดเศษอาหารให้เกิดประโยชน์สูงสุด”', '	ขยะอาหารที่เหลือทิ้งส่งผลกระทบต่อสิ่งแวดล้อมอย่างไร?\r\n	•	มีงานวิจัยหรือสถิติที่บอกว่าขยะอาหารเป็นปัญหาสำคัญแค่ไหน?\r\n	•	เทคนิคการจัดการเศษอาหาร เช่น การทำปุ๋ยหมัก การใช้ให้เกิดประโยชน์สูงสุด\r\n	•	ประเทศไหนมีมาตรการลดขยะอาหารที่น่าสนใจบ้าง?', '', '2025-03-31 16:07:49'),
(10, 1, '“กล่องนม UHT และซองขนม รีไซเคิลได้จริงหรือเป็นแค่โฆษณา?”', '	•	ทำไมกล่องนมและซองขนมถึงมีปัญหาในการรีไซเคิล?\r\n	•	โรงงานรีไซเคิลต้องแยกวัสดุเหล่านี้อย่างไรถึงนำกลับมาใช้ใหม่ได้?\r\n	•	มีบริษัทไหนในไทยที่รับรีไซเคิลกล่อง UHT จริง ๆ บ้าง?\r\n	•	ถ้าเราอยากช่วยลดขยะประเภทนี้ ควรทำอย่างไร?', '', '2025-03-31 16:08:08'),
(12, 1, '“ฝาขวดน้ำพลาสติกมีมูลค่ามากกว่าที่คิด! ขายได้ที่ไหนและนำไปใช้ทำอะไร?”', '	•	ฝาขวดน้ำพลาสติกทำจากวัสดุอะไร และต่างจากตัวขวดอย่างไร?\r\n	•	มีแหล่งรับซื้อฝาขวดน้ำพลาสติกที่ไหนบ้าง?\r\n	•	ฝาพลาสติกสามารถรีไซเคิลเป็นอะไรได้บ้าง เช่น เก้าอี้พลาสติก หรือวัสดุอื่น ๆ?\r\n	•	โครงการบริจาคฝาขวดเพื่อนำไปทำบุญมีที่ไหนบ้าง?', '', '2025-03-31 16:08:37'),
(13, 1, '“ราคาขยะรีไซเคิลขึ้นลงเพราะอะไร? ปัจจัยที่ทำให้ราคาขยะเปลี่ยนแปลง”', '	•	ปัจจัยที่มีผลต่อราคาขยะรีไซเคิล เช่น ราคาน้ำมัน วัตถุดิบใหม่ และความต้องการตลาด\r\n	•	ทำไมบางช่วงเวลาขวดพลาสติกขายได้ราคาแพง แต่บางช่วงราคาตก?\r\n	•	เราสามารถคาดการณ์ราคาขยะรีไซเคิลได้อย่างไร?\r\n	•	เทคนิคการเก็บขยะรีไซเคิลไว้ขายในช่วงที่ได้ราคาดี\r\n', '', '2025-03-31 16:08:44'),
(14, 1, '“DIY สุดเจ๋งจากขยะรีไซเคิล แปลงของเหลือใช้ให้เป็นของมีค่า”', '	มีไอเดีย DIY อะไรที่ทำจากขยะรีไซเคิลบ้าง? เช่น กระเป๋าจากถุงพลาสติก, เฟอร์นิเจอร์จากพาเลทไม้\r\n	•	อุปกรณ์และขั้นตอนที่ต้องใช้ในการทำของใช้จากขยะรีไซเคิล\r\n	•	มีโครงการหรือแบรนด์ที่สร้างผลิตภัณฑ์จากขยะรีไซเคิลที่น่าสนใจหรือไม่?\r\n	•	เราสามารถนำสินค้าทำมือจากวัสดุรีไซเคิลไปขายที่ไหนได้บ้าง?', '', '2025-03-31 16:08:51'),
(15, 1, '“ถุงพลาสติกใช้แล้ว ควรนำไปทำอะไรแทนการทิ้ง?”', '	•	ถุงพลาสติกแบบไหนสามารถรีไซเคิลได้ และแบบไหนต้องทิ้ง?\r\n	•	มีวิธีไหนที่สามารถลดปริมาณถุงพลาสติกที่บ้านได้บ้าง?\r\n	•	ถุงพลาสติกใช้แล้วสามารถนำไปสร้างมูลค่าใหม่ได้อย่างไร เช่น การทำอิฐพลาสติกหรือเส้นใยสำหรับเสื้อผ้า?\r\n	•	มีองค์กรหรือโครงการไหนรับบริจาคถุงพลาสติกเพื่อนำไปใช้ประโยชน์?', '', '2025-03-31 16:08:58'),
(22, 1, 'ฉันมีขยะ HDPE 50 กก รีไซเคิลได้มั้ย รีไซเคิลเป็นอะไรได้บ้าง', 'สวัสดีครับ สมาชิกทุกท่าน ตอนนี้ที่บ้านของฉันมีขยะประเภทพลาสติก HDPE (High-Density Polyethylene) ประมาณ 50 กิโลกรัม เป็นขวดน้ำยาทำความสะอาดและภาชนะพลาสติกที่ล้างสะอาดแล้ว อยากสอบถามว่าขยะ HDPE ปริมาณเท่านี้สามารถนำไปรีไซเคิลได้หรือไม่ และสามารถนำไปแปรรูปเป็นอะไรได้บ้างในกระบวนการรีไซเคิลที่ใช้กันอยู่ในปัจจุบัน? หากมีหน่วยงานหรือใครที่รับซื้อหรือรีไซเคิล HDPE ในพื้นที่ รบกวนแนะนำด้วยนะคะ ขอบคุณล่วงหน้าค่ะ\r\n', '', '2025-05-27 02:34:23');

-- --------------------------------------------------------

--
-- Table structure for table `recyclings`
--

CREATE TABLE `recyclings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `waste_type` varchar(255) DEFAULT NULL,
  `weight` decimal(10,2) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `latitude` decimal(10,6) DEFAULT NULL,
  `longitude` decimal(10,6) DEFAULT NULL,
  `buyer_name` varchar(255) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `organization_id` int(11) NOT NULL,
  `status` varchar(50) DEFAULT '''รอการตอบกลับ'''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recyclings`
--

INSERT INTO `recyclings` (`id`, `user_id`, `waste_type`, `weight`, `address`, `latitude`, `longitude`, `buyer_name`, `timestamp`, `organization_id`, `status`) VALUES
(20, 1, 'Yakult', 50.00, '27/4 ต.ตำบลบ้านสวน อ.อำเภอเมืองชลบุรี จ.ชลบุรี 20180', 0.000000, 0.000000, 'มหาลัยฬ', '2025-05-04 09:21:27', 6, 'กำลังไป'),
(21, 1, 'PET', 40.00, '27/4 ต.ตำบลบ้านสวน อ.อำเภอเมืองชลบุรี จ.ชลบุรี 20180', 0.000000, 0.000000, 'บริษัทอาร์มจำกันเก้บขยะ', '2025-05-04 09:33:01', 7, 'ยกเลิกโดยองค์กร'),
(22, 4, 'Yakult', 60.00, '27.4 ต.แขวงวังบูรพาภิรมย์ อ.เขตพระนคร จ.กรุงเทพมหานคร 13234', 0.000000, 0.000000, 'บริษัทอาร์มจำกันเก้บขยะ', '2025-05-06 08:02:59', 7, 'เสร็จสิ้น'),
(23, 1, 'PET', 100.00, '53/43 ต.ตำบลหนองบ่อ อ.อำเภอเมืองอุบลราชธานี จ.อุบลราชธานี 20183', 0.000000, 0.000000, 'บริษัทอาร์มจำกันเก้บขยะ', '2025-05-15 04:13:06', 7, 'เสร็จสิ้น'),
(24, 2, 'PET', 79.00, '27/4 ต.ตำบลหนองปรือ อ.อำเภอบางละมุง จ.ชลบุรี 20180', 0.000000, 0.000000, 'thammasat', '2025-05-21 17:08:31', 5, 'รอการตอบกลับ'),
(25, 1, 'PET', 49.00, '53/43 ต.ตำบลหนองบ่อ อ.อำเภอเมืองอุบลราชธานี จ.อุบลราชธานี 20183', 0.000000, 0.000000, 'มหาลัยฬ', '2025-05-23 03:18:52', 6, 'รอการตอบกลับ'),
(26, 2, 'HDPE', 50.00, '27/4 ต.ตำบลหนองปรือ อ.อำเภอบางละมุง จ.ชลบุรี 20180', 0.000000, 0.000000, 'thammasat', '2025-05-26 17:47:30', 5, 'เสร็จสิ้น'),
(27, 2, 'HDPE', 50.00, '27/4 ต.ตำบลหนองปรือ อ.อำเภอบางละมุง จ.ชลบุรี 20180', 0.000000, 0.000000, 'thammasat', '2025-05-26 17:51:44', 5, 'เสร็จสิ้น'),
(28, 1, 'HDPE', 50.00, '23/3 ต.แขวงดุสิต อ.เขตดุสิต จ.กรุงเทพมหานคร 20180', 0.000000, 0.000000, 'thammasat', '2025-05-27 02:43:45', 5, 'เสร็จสิ้น'),
(29, 8, 'PET', 90.00, '27/4 ต.ตำบลคำน้ำแซบ อ.อำเภอวารินชำราบ จ.อุบลราชธานี 10293', 0.000000, 0.000000, 'thammasat', '2025-06-11 11:07:46', 5, 'รอการตอบกลับ');

-- --------------------------------------------------------

--
-- Table structure for table `recycling_status_history`
--

CREATE TABLE `recycling_status_history` (
  `id` int(11) NOT NULL,
  `recycling_id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `imageUrl` varchar(255) DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `user_id`, `title`, `description`, `imageUrl`, `timestamp`) VALUES
(21, 2, '“แลกขยะเป็นแต้ม - สะสมเพื่อสิ่งแวดล้อม”', 'รายละเอียด: ผู้เข้าร่วมสามารถนำขยะรีไซเคิลมาส่งที่จุดรับแลกเพื่อสะสมแต้ม ซึ่งสามารถนำไปแลกเป็นของรางวัล เช่น ของใช้ที่เป็นมิตรกับสิ่งแวดล้อม หรือคูปองส่วนลดจากร้านค้าที่ร่วมรายการ', 'https://bangkokgourmetfestival.com/wp-content/uploads/2024/10/bangkokgourmetfestival-image-recyclebin.png', '2025-03-27 15:05:29'),
(22, 1, '“สะสมแต้มจากการขายของเก่า มีประโยชน์กว่าที่คิด!”', 'ระบบสะสมคะแนนช่วยให้คุณได้รับสิทธิพิเศษมากมาย ไม่ว่าจะเป็นส่วนลดค่าขนส่ง หรือแลกของรางวัล มาเรียนรู้วิธีสะสมแต้มให้เร็วที่สุดกันเถอะ!', 'https://media.istockphoto.com/id/845816364/th/รูปถ่าย/กองขยะในถังขยะหรือฝังกลบ-แนวคิดมลพิษ.jpg?s=612x612&w=0&k=20&c=-q1MLVcU1xFKyiDyAcFgO6nYuUyI3e9fLfeKSTSa2PU=', '2025-03-30 09:03:57'),
(33, 1, 'กิจกรรมคัดแยกขยะในสำนักงาน', 'การฝึกอบรมและรณรงค์ให้บุคลากรในสำนักงานเรียนรู้การคัดแยกขยะมูลฝอยอย่างถูกต้อง เพื่อส่งเสริมการลดปริมาณขยะและเพิ่มประสิทธิภาพในการจัดการขยะภายในองค์กร', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRINe0Nu7geGDSsgLwR75-L6J6y0W3YHHbJ6A&s', '2025-05-26 20:50:30'),
(35, 1, 'โครงการลดและคัดแยกขยะมูลฝอยในหน่วยงานภาครัฐ', 'การดำเนินมาตรการลดการใช้ถุงพลาสติก แก้วน้ำพลาสติก และโฟมบรรจุอาหารในหน่วยงานภาครัฐ พร้อมทั้งส่งเสริมการคัดแยกขยะมูลฝอยอย่างมีประสิทธิภาพ', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRXgllSW3cOhtsBKbnrISkF-5Qnl4wpMqmikQ&s', '2025-05-26 20:51:29'),
(36, 1, 'โครงการลดและคัดแยกขยะมูลฝอยในหน่วยงานภาครัฐ', 'การดำเนินมาตรการลดการใช้ถุงพลาสติก แก้วน้ำพลาสติก และโฟมบรรจุอาหารในหน่วยงานภาครัฐ พร้อมทั้งส่งเสริมการคัดแยกขยะมูลฝอยอย่างมีประสิทธิภาพ', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRXgllSW3cOhtsBKbnrISkF-5Qnl4wpMqmikQ&s', '2025-05-26 20:51:53'),
(37, 1, 'โครงการลดและคัดแยกขยะมูลฝอยในหน่วยงานภาครัฐ', 'การดำเนินมาตรการลดการใช้ถุงพลาสติก แก้วน้ำพลาสติก และโฟมบรรจุอาหารในหน่วยงานภาครัฐ พร้อมทั้งส่งเสริมการคัดแยกขยะมูลฝอยอย่างมีประสิทธิภาพ', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRXgllSW3cOhtsBKbnrISkF-5Qnl4wpMqmikQ&s', '2025-05-26 20:52:08'),
(38, 1, ' กิจกรรม “แยกขยะดีต่อโลก ดีต่อเรา ดีต่อใจ”', 'รายละเอียด: การรณรงค์ให้ประชาชนตระหนักถึงความสำคัญของการคัดแยกขยะ เพื่อส่งเสริมการจัดการขยะอย่างถูกวิธีและลดผลกระทบต่อสิ่งแวดล้อม', 'https://www.buengkok.go.th/dnm_file/news/1709792267942_138811_center.jpg', '2025-05-26 20:52:48'),
(39, 1, 'สมาชิกสภาองค์การบริการส่วนตำบลห้วยทรายได้มีนโยบายเกี่ยวกับการ จัดการขยะในตำบลห้วยทราย', 'ด้วยผู้บริหาร สมาชิกสภาองค์การบริการส่วนต าบลห้วยทรายได้มีนโยบายเกี่ยวกับการ\r\nจัดการขยะในต าบลห้วยทราย สืบเนื่องมาจากปริมาณขยะที่มากขึ้น และในพื้นที่มีข้อจ ากัดในการก าจัดขยะ\r\nจึงได้จัดเวทีประชาคม ทั้ง 5 หมู่บ้านเพื่อสอบถามความต้องการของประชาชนเกี่ยวกับโครงการจัดเก็บขยะ\r\nในต าบล ซึ่ งประชาชนส่วนใหญ่เห็นด้วยที่จะให้องค์การบริหารส่วนต าบลห้วยทรายด าเนินการจัดเก็บขยะ\r\nโดยจ้างบริษัทเอกชนด าเนินการ ทางองค์การบริหารส่วนต าบลห้วยทรายจึงสอบถามมายังท่าน เพื่อส ารวจ\r\nความสนใจที่จะเข้าร่วมโครงการดังกล่าว', 'https://www.thaihealth.or.th/data/content/29663/cms/thaihealth_c_efhiltvxyz25.jpg', '2025-05-27 04:49:08');

-- --------------------------------------------------------

--
-- Table structure for table `service_files`
--

CREATE TABLE `service_files` (
  `id` int(11) NOT NULL,
  `service_id` int(11) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_files`
--

INSERT INTO `service_files` (`id`, `service_id`, `file_path`) VALUES
(7, 21, 'https://www.earthday.org'),
(8, 22, 'https://drive.google.com/drive/folders/0B-AEBdrN7m9ZfmtLTHJOS3VOSjdXQkVWR1ZydGJKeUZKUWlFVzR5LXZ1d1pPblpWWjlPMVE?resourcekey=0-NXXDotVXmLM9hNzsjxybrA'),
(20, 37, 'https://tambonhuaisai.go.th/fileupload/download/tb_download_12_1.pdf'),
(21, 38, 'https://tambonhuaisai.go.th/fileupload/download/tb_download_12_1.pdf'),
(22, 39, 'https://tambonhuaisai.go.th/fileupload/download/tb_download_12_1.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(225) NOT NULL,
  `firstname` varchar(225) NOT NULL,
  `lastname` varchar(225) NOT NULL,
  `username` varchar(225) NOT NULL,
  `password` varchar(225) NOT NULL,
  `email` varchar(225) NOT NULL,
  `user_type` varchar(225) NOT NULL,
  `organization_id` int(255) DEFAULT NULL,
  `total_points` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `username`, `password`, `email`, `user_type`, `organization_id`, `total_points`) VALUES
(1, 'arm', 'peerwit', 'arminw1@hotmail.co.th', '$2y$10$kwgrS/HwVgUtJ6nBxIKjmOmyIJue.RJT8u2.j579jAR.nqiUhtpX.', 'arminw1@hotmail.co.th', 'user', NULL, 375),
(2, 'hot', 'hot', 'peerawit44@gmail.com', '$2y$10$cIGMBOgvQx425B93g9S3fey5hAlpC0LA/nw6tL.DSJNm/yN.xPzEa', 'peerawit44@gmail.com', 'user', NULL, 250),
(3, 'arm', 'mi', 'pooo@hotmail.com', '$2y$10$ZaQ.0XEeAVr1pZNfIqalwONu7V5SGEwW05Kuxkkc6Zry.MoEzWCjq', 'pooo@hotmail.com', 'user', NULL, 0),
(4, 'อาร์ม', 'พี', 'arm@gmail.com', '$2y$10$pUxipesI/nLXwd6tv1nkr.K5gJ8isPU6xNr6h19owc1lMCM1tlibO', 'arm@gmail.com', 'user', NULL, 1350),
(5, 'thammasat', '', 'Armpee@gmail.com', '$2y$10$1/lvbVBNVrpjifJ.pM8jyOM7lQaLINH78aVYHS.BB/pbI5pmWm53m', 'Armpee@gmail.com', 'organization', NULL, 0),
(6, 'มหาลัยฬ', '', 'chula@gmial.com', '$2y$10$rgWwy6/IlW4NA9TsDs7YU.3MV9osz4Qe2iBQ9VZ6xGQfjYrwgDkdC', 'chula@gmial.com', 'organization', NULL, 0),
(7, 'บริษัทอาร์มจำกันเก้บขยะ', '', 'peerawit11@gmail.com', '$2y$10$XnP1QH3QJ6WVwVqrlIxfAum0csoAJWd709IjarBOViXgmvu5J5FEC', 'peerawit11@gmail.com', 'organization', NULL, 0),
(8, 'ARM', 'eiei', 'arm1@hotmail.co.th', '$2y$10$OhDIiIoAr8SsP2cWMaf2Au1s7WQcLYmnD/zetUh4TJgXnN1teIXDW', 'arm1@hotmail.co.th', 'user', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_addresses`
--

CREATE TABLE `user_addresses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `house_number` varchar(255) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `sub_district` varchar(255) DEFAULT NULL,
  `province` varchar(255) DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_addresses`
--

INSERT INTO `user_addresses` (`id`, `user_id`, `house_number`, `district`, `sub_district`, `province`, `postal_code`, `latitude`, `longitude`) VALUES
(13, 5, '28/6', 'อำเภอบางละมุง', 'ตำบลนาเกลือ', 'ชลบุรี', '20180', NULL, NULL),
(31, 4, '27.4', 'เขตพระนคร', 'แขวงวังบูรพาภิรมย์', 'กรุงเทพมหานคร', '13234', NULL, NULL),
(32, 2, '27/4', 'อำเภอบางละมุง', 'ตำบลหนองปรือ', 'ชลบุรี', '20180', NULL, NULL),
(33, 2, '56/1', 'เขตพระนคร', 'แขวงพระบรมมหาราชวัง', 'กรุงเทพมหานคร', '20140', NULL, NULL),
(34, 1, '23/3', 'เขตดุสิต', 'แขวงดุสิต', 'กรุงเทพมหานคร', '20180', NULL, NULL),
(35, 8, '27/4', 'อำเภอวารินชำราบ', 'ตำบลคำน้ำแซบ', 'อุบลราชธานี', '10293', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `Xanthophyceae_Coagulum_Repository`
--

CREATE TABLE `Xanthophyceae_Coagulum_Repository` (
  `group_id` int(11) NOT NULL,
  `group_name` varchar(255) NOT NULL,
  `group_detail` text DEFAULT NULL,
  `authorized_user_ids` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `target_weight` decimal(11,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Xanthophyceae_Coagulum_Repository`
--

INSERT INTO `Xanthophyceae_Coagulum_Repository` (`group_id`, `group_name`, `group_detail`, `authorized_user_ids`, `created_at`, `target_weight`) VALUES
(3, 'กดกด', 'กดกด', '1', '2025-04-29 06:05:44', 0),
(5, 'หกหก', 'หกหกหก', '4', '2025-04-29 06:14:33', 0),
(6, 'หกดห', 'หดำดห', '_', '2025-04-29 06:39:37', 0),
(7, '23', '2323', '1', '2025-04-29 06:40:12', 0),
(8, '35', '2', '3', '2025-04-29 06:42:56', 0),
(9, '23', '232', '3', '2025-04-29 06:57:34', 0),
(10, 'หก', 'หกหก', '2,3', '2025-04-29 06:59:40', 0),
(11, 'อาร์ม', 'พีพีพีพี', '4,2', '2025-04-29 07:01:57', 0),
(12, 'sd', 'sods', '3,1', '2025-04-29 07:03:21', 0),
(13, 'ชุมชนร่วมใจ แยกขยะก่อนทิ้ง', 'รณรงค์ให้คนในชุมชนเริ่มต้นจากการแยกขยะที่บ้าน โดยเฉพาะพลาสติก เช่น ขวดน้ำ ถุงพลาสติก และกล่องอาหาร เพื่อให้ง่ายต่อการเก็บและรีไซเคิล ช่วยลดปริมาณขยะที่ปนเปื้อน', '3,4,1', '2025-04-29 07:43:06', 0),
(14, 'วันอาทิตย์สีเขียว – อาสาเก็บขยะในสวนสาธารณะ', 'จัดกิจกรรมเก็บขยะในสวนสาธารณะหรือพื้นที่สาธารณะทุกวันอาทิตย์ โดยเชิญชวนคนในพื้นที่มาร่วมกันเก็บขยะพลาสติก พร้อมให้ความรู้เรื่องการแยกขยะ', '3,4,1', '2025-04-29 07:43:39', 0),
(15, 'เก็บพลาสติก = ได้แต้มสะสม', 'สร้างระบบสะสมแต้มเมื่อมีการส่งมอบขยะพลาสติก เช่น ขวด PET หรือถุงหูหิ้ว โดยนำไปแลกรับของรางวัลหรือส่วนลดจากร้านค้าในชุมชน', '4,1', '2025-04-29 07:43:53', 0),
(16, 'รวมขยะกัน', 'รก่ดหนรเ่', '3,4,1', '2025-04-29 08:24:47', 0),
(17, 'อาร?มม', 'กยนพ่เยนทกอทนำพย', '3,4,1', '2025-05-19 05:02:59', 0),
(18, 'อาร์มม', 'หกหกห', '1', '2025-05-19 05:27:38', 8),
(19, 'หกายนพ่เ', 'กสงยนำ่ยเ', '2,3,1', '2025-05-19 05:34:57', 1),
(20, 'าสดกืเนำ', 'กดาเำพาเ', '3,1', '2025-05-19 05:38:20', 19),
(21, 'อาร์ม', 'อิอิอิออิ', '3,4,1', '2025-05-19 05:42:40', 5),
(22, '40', '23kpdokg', '3,6,1', '2025-05-19 06:16:21', 13),
(23, 'ทนดท', 'กดเยพเน', '5,3,1', '2025-05-19 06:26:10', 24),
(24, 'ดกสาน', 'กดาทสพา', '1,2', '2025-05-19 06:36:11', 3),
(25, 'sds', 'dsd', '3,4,1', '2025-05-26 10:26:44', 23),
(26, '“รวมพลคนรักษ์โลก”', 'รายละเอียด: เชิญชวนชาวบ้านหรือผู้ใช้งานที่อยู่ใกล้กันมารวมขยะรีไซเคิลเพื่อนำไปรวมขายในครั้งเดียว ช่วยลดค่าขนส่งและเพิ่มปริมาณต่อการขายให้ได้ราคาดีขึ้น', '1,2', '2025-05-26 18:58:23', 0),
(27, '“ขยะชิ้นเล็ก แต่พลังรวมยิ่งใหญ่”', 'รายละเอียด: สร้างกิจกรรมในชุมชนให้สมาชิกบ้านใกล้เรือนเคียงรวบรวมขยะพลาสติกและกระดาษทุกสัปดาห์ เพื่อนำไปรีไซเคิลร่วมกัน โดยมีจุดรับส่งกลางหมู่บ้าน', '3,4,1', '2025-05-26 18:58:44', 0),
(28, 'มือเล็กใจใหญ่ รวมขยะเพื่อชุมชน”', 'รายละเอียด: นักเรียนหรือเยาวชนในหมู่บ้านรวมกลุ่มเก็บขยะรีไซเคิลจากบ้านใกล้เคียง แล้วนำไปขายร่วมกัน โดยรายได้จะนำกลับมาพัฒนาโรงเรียนหรือกิจกรรมชุมชน', '1,2', '2025-05-26 18:59:23', 0),
(29, '“ขยะรวมกลุ่ม ลดโลกเลอะ”', 'รายละเอียด: รณรงค์การรวมขยะประเภทเดียวกัน เช่น ขวด PET, HDPE หรือกระดาษลัง ในกลุ่มผู้ใช้งาน เพื่อสะดวกต่อการคัดแยกและขายให้ได้ราคาดีจากผู้รับซื้อรายใหญ่', '3,4,1', '2025-05-26 18:59:46', 400),
(30, '“ชุมชนสะอาด ขายขยะเป็นทีม”', 'รายละเอียด: ตั้งกลุ่มแยกขยะในชุมชนแบบอาสาสมัคร และใช้ระบบเว็บประกาศการรวมขยะทุกเดือน พร้อมหาผู้รับซื้อที่เสนอราคาดีที่สุดเข้ารับที่จุดรวมกลาง', '1,2', '2025-05-26 19:00:11', 0),
(31, '“แชร์ขยะ แชร์อนาคต”', 'รายละเอียด: แพลตฟอร์มการรวมขยะโดยเปิดให้ผู้ใช้งานโพสต์ประเภทขยะที่มีอยู่ แล้วเชิญคนใกล้บ้านเข้าร่วมกลุ่มเพื่อจัดส่งพร้อมกันทุกสิ้นเดือน', '1,2', '2025-05-26 19:00:28', 50),
(32, '“ชวนกันทิ้ง ช่วยกันแยก แชร์กันขาย”', 'รายละเอียด: เปิดห้องสนทนาออนไลน์ในเว็บไซต์เพื่อให้ผู้ใช้งานเสนอการรวมกลุ่มและวันเวลาสำหรับจัดกิจกรรมขายขยะร่วมกันแบบเป็นรอบ', '1,2', '2025-05-26 19:01:02', 100),
(33, '“รวมขยะวันนี้ เปลี่ยนชุมชนพรุ่งนี้”', 'รายละเอียด: เปิดห้องสนทนาออนไลน์ในเว็บไซต์เพื่อให้ผู้ใช้งานเสนอการรวมกลุ่มและวันเวลาสำหรับจัดกิจกรรมขายขยะร่วมกันแบบเป็นรอบ', '3,4,1', '2025-05-27 02:51:15', 40),
(34, '“รวมขยะวันนี้ เปลี่ยนชุมชนพรุ่งนี้”', 'รายละเอียด: เปิดห้องสนทนาออนไลน์ในเว็บไซต์เพื่อให้ผู้ใช้งานเสนอการรวมกลุ่มและวันเวลาสำหรับจัดกิจกรรมขายขยะร่วมกันแบบเป็นรอบ', '1,3,4', '2025-05-27 02:52:57', 90);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buy_requests`
--
ALTER TABLE `buy_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `Perissodactyla_Detritus_Manifest`
--
ALTER TABLE `Perissodactyla_Detritus_Manifest`
  ADD PRIMARY KEY (`trash_id`),
  ADD KEY `group_id` (`group_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `recyclings`
--
ALTER TABLE `recyclings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recycling_status_history`
--
ALTER TABLE `recycling_status_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recycling_id` (`recycling_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_files`
--
ALTER TABLE `service_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `Xanthophyceae_Coagulum_Repository`
--
ALTER TABLE `Xanthophyceae_Coagulum_Repository`
  ADD PRIMARY KEY (`group_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buy_requests`
--
ALTER TABLE `buy_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `Perissodactyla_Detritus_Manifest`
--
ALTER TABLE `Perissodactyla_Detritus_Manifest`
  MODIFY `trash_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `recyclings`
--
ALTER TABLE `recyclings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `recycling_status_history`
--
ALTER TABLE `recycling_status_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `service_files`
--
ALTER TABLE `service_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(225) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user_addresses`
--
ALTER TABLE `user_addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `Xanthophyceae_Coagulum_Repository`
--
ALTER TABLE `Xanthophyceae_Coagulum_Repository`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `Perissodactyla_Detritus_Manifest`
--
ALTER TABLE `Perissodactyla_Detritus_Manifest`
  ADD CONSTRAINT `perissodactyla_detritus_manifest_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `Xanthophyceae_Coagulum_Repository` (`group_id`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `recycling_status_history`
--
ALTER TABLE `recycling_status_history`
  ADD CONSTRAINT `recycling_status_history_ibfk_1` FOREIGN KEY (`recycling_id`) REFERENCES `recyclings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `service_files`
--
ALTER TABLE `service_files`
  ADD CONSTRAINT `service_files_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD CONSTRAINT `user_addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
