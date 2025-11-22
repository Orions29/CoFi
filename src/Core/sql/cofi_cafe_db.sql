-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 22 Nov 2025 pada 16.11
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cofi_cafe_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `cafes`
--

CREATE TABLE `cafes` (
  `cafe_id` int(11) NOT NULL,
  `cafe_name` varchar(100) NOT NULL,
  `cafe_email` varchar(100) DEFAULT NULL,
  `latitude` decimal(17,15) DEFAULT NULL,
  `longitude` decimal(18,15) DEFAULT NULL,
  `cafe_description` text DEFAULT NULL,
  `price_min` decimal(10,2) DEFAULT NULL,
  `price_max` decimal(10,2) DEFAULT NULL,
  `price_avg` decimal(10,2) DEFAULT NULL,
  `rating` decimal(2,1) DEFAULT 0.0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `cafes`
--

INSERT INTO `cafes` (`cafe_id`, `cafe_name`, `cafe_email`, `latitude`, `longitude`, `cafe_description`, `price_min`, `price_max`, `price_avg`, `rating`, `created_at`) VALUES
(14, 'Nyore Coffee & Space', 'nyore@cofi.cafe', -7.785205237626065, 110.366595105744650, 'Sangat Well Ini cafenya well banget', 8000.00, 7500.00, 8500.00, NULL, '2025-11-22 15:04:57');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cafe_categories`
--

CREATE TABLE `cafe_categories` (
  `id` int(11) NOT NULL,
  `cafe_id` int(11) NOT NULL,
  `category_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `cafe_categories`
--

INSERT INTO `cafe_categories` (`id`, `cafe_id`, `category_name`) VALUES
(45, 14, 'productivity'),
(46, 14, 'social space'),
(47, 14, 'roastery'),
(48, 14, 'live music'),
(49, 14, 'outdoor seating');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cafe_photos`
--

CREATE TABLE `cafe_photos` (
  `photo_id` int(11) NOT NULL,
  `cafe_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `cafe_photos`
--

INSERT INTO `cafe_photos` (`photo_id`, `cafe_id`, `file_path`) VALUES
(6, 14, '/uploads/cafe_image/cafe_6921d1197ebbf7.17869995.webp');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL COMMENT 'Primary Key',
  `username` varchar(100) NOT NULL,
  `user_password` varchar(100) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `admin_stat` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabel Core Auth User';

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`user_id`, `username`, `user_password`, `user_email`, `admin_stat`, `created_at`) VALUES
(1, 'orions', '$2y$12$M.nCuZkLrMrwQPjpeVWekOATJCzFlGD.EF1Rgm4MAfXz7EzwP8VFO', '123240175@student.upnyk.ac.id', 1, '2025-11-15 08:28:01'),
(2, 'dessyanto', '$2y$12$it2SvKUqgNACbKdmWcgxs.fbNHS2WQY5h3UpwJLB.pK5KucuuQZeu', 'dessyanto@student.upnyk.ac.id', 0, '2025-11-15 08:30:34'),
(6, 'darto29', '$2y$12$it2SvKUqgNACbKdmWcgxs.fbNHS2WQY5h3UpwJLB.pK5KucuuQZeu', 'hero@yahoo', 0, '2025-11-15 09:56:43'),
(9, 'DarilllBro', '$2y$12$VI2exj2DWNHNYaSbnZy9dOYHWdN9j.5Uep1yhko5Ui1O2/iXg2/f2', 'mayhem@yoyo', 0, '2025-11-15 14:10:39'),
(10, 'asdasda', '$2y$12$54Wh.rxNZ3LvatTK1Vww.uwFrmIrrf/RI8RM6SLnMFJyvxIQ0NasS', '12312@rerere', 0, '2025-11-16 15:15:43'),
(34, 'jokowi_widodo', '$2y$12$iL6AmK23063sA9uK4nDq4OXc4evp0Am2hqyivzTwxU6lKoMYkLb06', 'jokowi@istana.go.id', 0, '2025-11-21 03:13:11'),
(35, 'prabowo08', '$2y$12$cjNeKGaX24dSyf1qFTiAyeegHTFQZb5PEByM0PEU9sHx1P47AQybK', 'prabowo@kemhan.go.id', 0, '2025-11-21 03:13:11'),
(36, 'gibran_rakabuming', '$2y$12$09yYB.XxbZT.wfTsUbQi3eeL8aoq7DSA3KBSQ5AyGSh6.g96ZSo7O', 'gibran@solo.go.id', 0, '2025-11-21 03:13:11'),
(38, 'luhut_binsar', '$2y$12$I4bqCvmXEC6WFUsirJw1LeQYptTx8QwyEDdhdI2E81Rx5GCw.aYmi', 'luhut@maritim.go.id', 0, '2025-11-21 03:13:12'),
(39, 'erick_thohir', '$2y$12$lMeL7KuARo.f5eX5deEL6OkXxuH/HNA0IhkuBtxbMrDhbVFvmUkAa', 'erick@bumn.go.id', 0, '2025-11-21 03:13:12'),
(40, 'basuki_hadimuljono', '$2y$12$iQSzeMY3f2XihxbOZMqZxeUGXG96PLu/v2fHvI1nTyyT2qewnfxgy', 'pakbas@pupr.go.id', 0, '2025-11-21 03:13:13'),
(41, 'megawati_soekarno', '$2y$12$/O51x8YXBnFCtWQh7WXK4uRx7yVJy9qNsDER/2JaeHo4rnX8WIdJC', 'mega@pdip.id', 0, '2025-11-21 03:13:13'),
(42, 'anies_baswedan', '$2y$12$62phdtxAr0diXdr9IP6vGuK4jk/4t4sFyD38BZry5COtJJ9YcZEWu', 'anies@pendidikan.id', 0, '2025-11-21 03:13:13');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_details`
--

CREATE TABLE `user_details` (
  `detail_id` int(11) NOT NULL COMMENT 'PK detail',
  `user_id` int(11) NOT NULL COMMENT 'FK ke tabel users',
  `user_full_name` varchar(100) NOT NULL,
  `user_birthday` date NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `job` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabel Detail Profil User';

--
-- Dumping data untuk tabel `user_details`
--

INSERT INTO `user_details` (`detail_id`, `user_id`, `user_full_name`, `user_birthday`, `updated_at`, `job`) VALUES
(1, 1, 'Rio Sang Pencipta', '1945-08-17', '2025-11-15 08:28:01', ''),
(3, 6, 'Ronaldi Hensberg', '0000-00-00', '2025-11-15 09:56:43', 'Salarry Man'),
(4, 9, 'riomeidi awewew', '2005-01-11', '2025-11-15 14:10:39', 'Salarry Man'),
(5, 10, 'asdasdasd', '2025-11-03', '2025-11-16 15:15:43', 'College'),
(24, 34, 'Ir. H. Joko Widodo', '1961-06-21', '2025-11-21 03:13:11', 'Presiden RI'),
(25, 35, 'H. Prabowo Subianto', '1951-10-17', '2025-11-21 03:13:11', 'Menteri Pertahanan'),
(26, 36, 'Gibran Rakabuming Raka', '1987-10-01', '2025-11-21 03:13:11', 'Wapres Terpilih'),
(28, 38, 'Luhut Binsar Pandjaitan', '1947-09-28', '2025-11-21 03:13:12', 'Menko Marves'),
(29, 39, 'Erick Thohir', '1970-05-30', '2025-11-21 03:13:12', 'Menteri BUMN'),
(30, 40, 'Basuki Hadimuljono', '1954-11-05', '2025-11-21 03:13:13', 'Menteri PUPR'),
(31, 41, 'Megawati Soekarnoputri', '1947-01-23', '2025-11-21 03:13:13', 'Ketua Umum'),
(32, 42, 'Anies Rasyid Baswedan', '1969-05-07', '2025-11-21 03:13:13', 'Akademisi');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `cafes`
--
ALTER TABLE `cafes`
  ADD PRIMARY KEY (`cafe_id`);

--
-- Indeks untuk tabel `cafe_categories`
--
ALTER TABLE `cafe_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cafe_id` (`cafe_id`);

--
-- Indeks untuk tabel `cafe_photos`
--
ALTER TABLE `cafe_photos`
  ADD PRIMARY KEY (`photo_id`),
  ADD KEY `cafe_id` (`cafe_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `users_email_unique` (`user_email`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- Indeks untuk tabel `user_details`
--
ALTER TABLE `user_details`
  ADD PRIMARY KEY (`detail_id`),
  ADD KEY `fk_users_details` (`user_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `cafes`
--
ALTER TABLE `cafes`
  MODIFY `cafe_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `cafe_categories`
--
ALTER TABLE `cafe_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT untuk tabel `cafe_photos`
--
ALTER TABLE `cafe_photos`
  MODIFY `photo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key', AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT untuk tabel `user_details`
--
ALTER TABLE `user_details`
  MODIFY `detail_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK detail', AUTO_INCREMENT=34;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `cafe_categories`
--
ALTER TABLE `cafe_categories`
  ADD CONSTRAINT `cafe_categories_ibfk_1` FOREIGN KEY (`cafe_id`) REFERENCES `cafes` (`cafe_id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `cafe_photos`
--
ALTER TABLE `cafe_photos`
  ADD CONSTRAINT `cafe_photos_ibfk_1` FOREIGN KEY (`cafe_id`) REFERENCES `cafes` (`cafe_id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `user_details`
--
ALTER TABLE `user_details`
  ADD CONSTRAINT `fk_users_details` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
