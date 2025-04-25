-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 25, 2025 at 07:42 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `odisha_tourism`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `place_id` int(11) NOT NULL,
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `username` varchar(255) NOT NULL,
  `status` enum('Pending','Approved') NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `place_id`, `booking_date`, `username`, `status`) VALUES
(4, 1, 1, '2025-04-25 05:38:26', 'Om Prakash Behera', 'Pending'),
(5, 1, 2, '2025-04-25 05:38:33', 'Om Prakash Behera', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `districts`
--

CREATE TABLE `districts` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `culture` text DEFAULT NULL,
  `festivals` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guides`
--

CREATE TABLE `guides` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `language` varchar(100) DEFAULT NULL,
  `availability` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guides`
--

INSERT INTO `guides` (`id`, `name`, `contact`, `language`, `availability`) VALUES
(1, 'Gyana Ranjan Rout', '1234567890', 'Odia', 1);

-- --------------------------------------------------------

--
-- Table structure for table `guide_bookings`
--

CREATE TABLE `guide_bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `guide_id` int(11) NOT NULL,
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guide_bookings`
--

INSERT INTO `guide_bookings` (`id`, `user_id`, `guide_id`, `booking_date`) VALUES
(8, 1, 1, '2025-04-25 05:38:40');

-- --------------------------------------------------------

--
-- Table structure for table `tourist_places`
--

CREATE TABLE `tourist_places` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `media` varchar(255) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tourist_places`
--

INSERT INTO `tourist_places` (`id`, `name`, `description`, `media`, `photo`, `latitude`, `longitude`) VALUES
(1, 'Bargarh', 'Nursinghanath Temple', 'nursinghanath.mp4', 'nrusinghanath.jpg', 21.23199220, 83.56527370),
(2, 'Jharsuguda ', 'Koilighugar Waterfall\r\n', 'koilighugar.mp4', 'koilighugar waterfall.jpg', 21.80193680, 83.97139645),
(3, 'Sambalpur', 'Hirakud Dam', 'hirakud.mp4', 'hirakud.jpg', 21.55706060, 84.15285151),
(4, 'Deogarh', 'Kailash Palace', 'kailashpalace.mp4', 'kailashpalace.jpg', 24.48983180, 86.69901820),
(5, 'Keonjhar', 'Maa Tarini Temple', 'tarini.mp4', 'MAA.TARINI.jpg', 21.50000000, 85.50000000),
(6, 'Mayurbhanj', 'Similipal National Park ', 'similipal.mp4', 'simlipal-national-park.jpg', 21.91563380, 86.39618240),
(7, 'Bhadrak', 'Dhamra Port', 'dhamra.mp4', 'dhamra.jpg', 20.98540520, 86.59342265),
(8, 'Kendrapara', 'Bhitarkanika National Park', 'bhitarkanika.mp4', 'bhitarkanika.jpg', 20.54751640, 86.52961682),
(9, 'Jagatsinghpur', 'Paradip Port', 'Paradip_Port.mp4', 'paradip.jpg', 20.25938720, 86.16608780),
(10, 'Cuttack', 'Barabati Stadium', 'BARABATI.mp4', 'barabati_stadium..jpg', 20.46860000, 85.87920000),
(11, 'Jajpur', 'Kusuma Park', 'KUSUMA.mp4', 'KUSUMA.jpg', 20.87467875, 86.12153933),
(12, 'Dhenkanal', 'Kapilash Temple', 'KAPILASH.mp4', 'KAPILASH.jpg', 20.83514935, 85.60223048),
(13, 'Angul', 'Satkosia Tiger Reserve', 'Satkosia.mp4', 'satkosia-tiger-reserve-.jpg', 21.10253800, 84.95368249),
(14, 'Nayagarh', 'Ram Mandir', 'ram-mandir.mp4', 'ram-mandir.jpg', 20.23936825, 85.05072800),
(15, 'Khordha', 'Biju Patnaik Airport', 'AIRPORT.mp4', 'AIRPORT.jpg', 20.05381485, 85.50233082),
(16, 'Bhubaneswar', 'Nandankanan Zoological Park\r\n', 'nandankanan.mp4', 'nandankanan.jpg', 20.26029640, 85.83945210),
(17, 'Puri', 'Shree Jagannath Temple, Puri', 'puri mandir.mp4', 'JAGANNATH TEMPLE PURI.jpg', 19.80477255, 85.81808106),
(18, 'Ganjam', 'Tara Tarini Temple', 'tara_tarini.mp4', 'taratarini.jpg', 19.48882470, 84.89919070),
(19, 'Gajapati', 'Jirang Buddhist Monastery, Gajapati', 'jiranga.mp4', 'jiranga.jpg', 19.26815020, 84.26216140),
(20, 'kandhamala', 'Daringbadi', 'daringbadi.mp4', 'daringbadi.jpg', 19.91608425, 84.11649059),
(21, 'Boudh', 'Nayakpada Cave', 'nayakapada.mp4', 'nayakpada.jpg', 20.77929820, 84.28150990),
(22, 'Subarnapur', 'Patali Srikhetra', 'PATALI_SRIKHESTRA.mp4', 'patali-srikhetra-.jpg', 21.01307050, 83.94962980),
(23, 'Bolangir', 'Shree Harishankar Temple', 'harishankar.mp4', 'harishankar.jpg', 20.70111100, 83.48460690),
(24, 'Nuapada', 'Yogeshwar Temple', 'yogeshwar.mp4', 'YOGESWAR.jpg', 20.73800910, 82.44093960),
(25, 'Kalahandi', 'Karlapat Wildlife Sanctuary', 'karlapat.mp4', 'karlapat.jpg', 19.67192540, 83.03356270),
(26, 'Rayagada', 'Hanging Bridge', 'Hanging Bridge.mp4', 'HANGING.jpg', 19.16650390, 83.43607520),
(27, 'Nabarangapur', 'Deer Park', 'deer_park.mp4', 'DEERPARK.jpg', 19.32820660, 82.51651820),
(28, 'Malkangiri', 'Chitrakonda', 'chitrakonda.mp4', 'CHITRAKONDA.jpg', 18.10947000, 82.09177450),
(29, 'Sundargarh', 'Kanakund', 'Kanakund.mp4', 'KANAKUND.jpg', 22.24659250, 83.86360370),
(30, 'Balasore', 'Emami Jagannath Temple', 'Emami.mp4', 'EMAMI.JAGANNATH.jpg', 21.53734430, 86.81831390);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'Om Prakash Behera', 'admin@gmail.com', '$2y$10$ktECegAxIvERZBIzNvLsWubOr6.VHpMTjQ6yDSsRB.j8c7rIbYVUm', '2025-04-25 05:30:09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `place_id` (`place_id`);

--
-- Indexes for table `districts`
--
ALTER TABLE `districts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `guides`
--
ALTER TABLE `guides`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `guide_bookings`
--
ALTER TABLE `guide_bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `guide_id` (`guide_id`);

--
-- Indexes for table `tourist_places`
--
ALTER TABLE `tourist_places`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `districts`
--
ALTER TABLE `districts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guides`
--
ALTER TABLE `guides`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `guide_bookings`
--
ALTER TABLE `guide_bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tourist_places`
--
ALTER TABLE `tourist_places`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`place_id`) REFERENCES `tourist_places` (`id`);

--
-- Constraints for table `guide_bookings`
--
ALTER TABLE `guide_bookings`
  ADD CONSTRAINT `guide_bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `guide_bookings_ibfk_2` FOREIGN KEY (`guide_id`) REFERENCES `guides` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
