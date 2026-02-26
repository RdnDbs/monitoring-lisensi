-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 09, 2026 at 02:42 AM
-- Server version: 10.3.16-MariaDB
-- PHP Version: 7.2.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mylisensi`
--

-- --------------------------------------------------------

--
-- Table structure for table `lisensi`
--

CREATE TABLE `lisensi` (
  `id` int(11) NOT NULL,
  `nama_layanan` varchar(150) NOT NULL,
  `nama_pic` varchar(100) DEFAULT NULL,
  `kontak_pic` varchar(100) DEFAULT NULL,
  `owned_pengguna` varchar(100) DEFAULT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_berakhir` date NOT NULL,
  `keterangan` text DEFAULT NULL,
  `sisa_hari` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `lisensi`
--

INSERT INTO `lisensi` (`id`, `nama_layanan`, `nama_pic`, `kontak_pic`, `owned_pengguna`, `tanggal_mulai`, `tanggal_berakhir`, `keterangan`, `sisa_hari`, `created_at`) VALUES
(1, 'Lisensi Proxmox', 'Tim IT', 'it@company.co.id', 'Data Center', '2026-01-01', '2026-04-24', 'Cluster Proxmox Jakarta', 76, '2026-01-21 10:06:31'),
(3, 'PT. IT Konsultan', 'Andi', 'andi@mail.com', 'Media', '2026-01-06', '2026-02-24', 'Media Bar', 17, '2026-01-21 10:06:31'),
(8, 'Aplikasi 1', 'Tim Dev', 'dev@company.co.id', 'Aplikasi', '2025-12-01', '2026-04-03', 'Media Bar', 55, '2026-01-21 10:06:31'),
(9, 'Aplikasi 2', 'Tim Dev', 'dev@company.co.id', 'Aplikasi', '2025-12-01', '2026-02-27', 'Media Bar', 20, '2026-01-21 10:06:31'),
(10, 'Lisensi Mikrotik', 'Network Team', 'net@company.co.id', 'Network', '2025-01-14', '2026-12-01', 'Firewall Kantor Pusat', 297, '2026-01-21 10:06:31'),
(11, 'Lisensi Safeline', 'Security Team', 'soc@company.co.id', 'Security', '2025-01-01', '2026-05-22', 'Firewall DC Jakarta', 104, '2026-01-21 10:06:31'),
(12, 'Lisensi Proxmox 2', 'Tim IT', 'it@company.co.id', 'Data Center', '2026-01-20', '2026-04-17', NULL, 69, '2026-01-21 14:41:05'),
(13, 'Lisensi Proxmox 3', 'Tim IT', 'it@company.co.id', 'Data Center', '2025-03-02', '2027-01-02', NULL, 329, '2026-01-27 10:27:01'),
(14, 'Lisensi Fortigate', 'Security Team', 'soc@company.co.id', 'Security', '2025-12-01', '2026-12-10', NULL, 306, '2026-01-27 10:40:24'),
(15, 'Konsultan IT', 'Budia', '08123456789', 'Media', '2026-01-21', '2026-02-22', NULL, 15, '2026-02-05 22:28:11'),
(16, 'Lisensi Mikrotik Server', 'Andi', '084587876723', 'Tim Network', '2025-01-02', '2027-02-22', NULL, 380, '2026-02-05 22:28:46');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `role` enum('admin','viewer') DEFAULT 'admin',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama`, `role`, `created_at`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500', 'Administrator', 'admin', '2026-02-05 11:15:05'),
(3, 'user', '2411', 'adebos', 'viewer', '2026-02-06 04:06:21'),
(4, 'adebos', 'ddeebdeefdb7e7e7a697e1c3e3d8ef54', NULL, 'admin', '2026-02-06 04:08:24'),
(5, 'ade', 'ddeebdeefdb7e7e7a697e1c3e3d8ef54', NULL, '', '2026-02-06 04:08:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `lisensi`
--
ALTER TABLE `lisensi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_lisensi_tgl_akhir` (`tanggal_berakhir`),
  ADD KEY `idx_lisensi_nama` (`nama_layanan`),
  ADD KEY `idx_pic` (`nama_pic`),
  ADD KEY `idx_owned` (`owned_pengguna`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lisensi`
--
ALTER TABLE `lisensi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
