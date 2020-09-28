-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 19 Mei 2020 pada 10.35
-- Versi Server: 10.1.25-MariaDB
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `astra2000`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang`
--

CREATE TABLE `barang` (
  `id` int(11) NOT NULL,
  `kode` varchar(15) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `qty` int(11) NOT NULL,
  `id_rak` int(11) NOT NULL,
  `aktif` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `karyawan`
--

CREATE TABLE `karyawan` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `id_jabatan` tinyint(4) NOT NULL,
  `aktif` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `karyawan`
--

INSERT INTO `karyawan` (`id`, `nama`, `alamat`, `id_jabatan`, `aktif`) VALUES
(1, 'ody', 'Tigarakasa, Tangerang', 1, 1),
(5, 'anwar', 'curug', 2, 1),
(6, 'Tedy', 'curug', 2, 1),
(7, 'admin2', 'Cikupa', 1, 1),
(8, 'deni', '', 1, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `peminjaman_tools`
--

CREATE TABLE `peminjaman_tools` (
  `id` varchar(11) NOT NULL,
  `id_teknisi` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `jam` time NOT NULL,
  `note` text NOT NULL,
  `user_input` int(11) NOT NULL,
  `date_input` datetime NOT NULL,
  `aktif` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `peminjaman_tools`
--

INSERT INTO `peminjaman_tools` (`id`, `id_teknisi`, `tanggal`, `jam`, `note`, `user_input`, `date_input`, `aktif`) VALUES
('TR200508001', 5, '2020-05-08', '00:05:00', 'tes', 4, '2020-05-08 14:47:06', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `peminjaman_tools_detail`
--

CREATE TABLE `peminjaman_tools_detail` (
  `id` int(11) NOT NULL,
  `id_peminjaman` varchar(11) NOT NULL,
  `id_tools` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `peminjaman_tools_detail`
--

INSERT INTO `peminjaman_tools_detail` (`id`, `id_peminjaman`, `id_tools`) VALUES
(103, 'TR200508001', 5);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengembalian_tools`
--

CREATE TABLE `pengembalian_tools` (
  `id` int(11) NOT NULL,
  `id_peminjaman_detail` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `jam` time NOT NULL,
  `user_input` int(11) NOT NULL,
  `date_input` datetime NOT NULL,
  `note` text NOT NULL,
  `aktif` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `rak_tools`
--

CREATE TABLE `rak_tools` (
  `id` int(11) NOT NULL,
  `kode_rak` varchar(3) NOT NULL,
  `nomor_rak` int(11) NOT NULL,
  `aktif` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `rak_tools`
--

INSERT INTO `rak_tools` (`id`, `kode_rak`, `nomor_rak`, `aktif`) VALUES
(1, 'A', 1, 1),
(2, 'A', 2, 1),
(3, 'A', 3, 1),
(4, 'A', 5, 0),
(5, 'A', 4, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tools`
--

CREATE TABLE `tools` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `id_rak` int(11) NOT NULL,
  `id_kondisi` int(11) NOT NULL,
  `aktif` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tools`
--

INSERT INTO `tools` (`id`, `nama`, `id_rak`, `id_kondisi`, `aktif`) VALUES
(1, 'Kikir Panjang', 1, 1, 1),
(3, 'Kikir Pendek', 3, 1, 1),
(4, 'Obeng Kembang', 2, 1, 1),
(5, 'Tang', 5, 1, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `id_karyawan` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(33) NOT NULL,
  `role` int(11) NOT NULL,
  `aktif` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `id_karyawan`, `username`, `password`, `role`, `aktif`) VALUES
(4, 1, 'ody', '4ed1253a830f0081c7598c9291989ffe', 1, 1),
(5, 7, 'admin2', 'c84258e9c39059a89ab77d846ddab909', 1, 1),
(6, 8, 'deni', '43f41d127a81c54d4c8f5f93daeb7118', 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `peminjaman_tools`
--
ALTER TABLE `peminjaman_tools`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `peminjaman_tools_detail`
--
ALTER TABLE `peminjaman_tools_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pengembalian_tools`
--
ALTER TABLE `pengembalian_tools`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rak_tools`
--
ALTER TABLE `rak_tools`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tools`
--
ALTER TABLE `tools`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `karyawan`
--
ALTER TABLE `karyawan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `peminjaman_tools_detail`
--
ALTER TABLE `peminjaman_tools_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;
--
-- AUTO_INCREMENT for table `pengembalian_tools`
--
ALTER TABLE `pengembalian_tools`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `rak_tools`
--
ALTER TABLE `rak_tools`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `tools`
--
ALTER TABLE `tools`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
