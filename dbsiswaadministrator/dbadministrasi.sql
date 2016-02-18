-- phpMyAdmin SQL Dump
-- version 4.5.0.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 18, 2016 at 11:46 AM
-- Server version: 10.0.17-MariaDB
-- PHP Version: 5.6.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbadministrasi`
--

-- --------------------------------------------------------

--
-- Table structure for table `d_nilai`
--

CREATE TABLE `d_nilai` (
  `detail_nilai_id` int(11) NOT NULL,
  `nilai_id` int(11) NOT NULL,
  `id_mapel` int(11) NOT NULL,
  `nilai_afektif` int(11) NOT NULL,
  `nilai_komulatif` int(11) NOT NULL,
  `nilai_psikomotorik` int(11) NOT NULL,
  `nilai_rata-rata` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `d_nilai`
--

INSERT INTO `d_nilai` (`detail_nilai_id`, `nilai_id`, `id_mapel`, `nilai_afektif`, `nilai_komulatif`, `nilai_psikomotorik`, `nilai_rata-rata`) VALUES
(1, 2, 1, 79, 22, 21, 32),
(2, 4, 2, 50, 57, 79, 78),
(3, 4, 1, 40, 70, 67, 12);

-- --------------------------------------------------------

--
-- Table structure for table `guru`
--

CREATE TABLE `guru` (
  `guru_id` int(11) NOT NULL,
  `nip` char(8) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `tempatLahir` varchar(50) NOT NULL,
  `tanggalLahir` date NOT NULL,
  `alamat` varchar(100) NOT NULL,
  `agama` varchar(15) NOT NULL,
  `noHp` varchar(15) NOT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `guru`
--

INSERT INTO `guru` (`guru_id`, `nip`, `nama`, `tempatLahir`, `tanggalLahir`, `alamat`, `agama`, `noHp`, `email`) VALUES
(1, '123', 'Muhammad Iqbal', 'brebes', '2016-02-09', 'brebes', 'islam', '1234', 'iqbal.oyonz@gmail.com'),
(2, '123', 'Trio wek-wek', 'Brebes', '2016-02-18', 'Brebes', 'Islam', '093938938938983', 'trio.wek-wek@gmail.com'),
(3, '90829839', 'Abdul Kadir Hasani', 'Brebes', '1992-02-18', 'Langkap, Bumiayu', 'Islam', '098398398399', 'copala.joker@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `mapel`
--

CREATE TABLE `mapel` (
  `id_mapel` int(11) NOT NULL,
  `kodeMapel` char(8) NOT NULL,
  `namaMapel` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mapel`
--

INSERT INTO `mapel` (`id_mapel`, `kodeMapel`, `namaMapel`) VALUES
(1, '123', 'MATEMATIKA'),
(2, '899123', 'Fisika');

-- --------------------------------------------------------

--
-- Table structure for table `nilai`
--

CREATE TABLE `nilai` (
  `nilai_id` int(11) NOT NULL,
  `nis` char(8) NOT NULL,
  `nip` char(8) NOT NULL,
  `semester` varchar(50) NOT NULL,
  `kelas` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `nilai`
--

INSERT INTO `nilai` (`nilai_id`, `nis`, `nip`, `semester`, `kelas`) VALUES
(2, '34569999', '123', '7', 'A'),
(3, '34569999', '90829839', '', ''),
(4, '541344', '90829839', '5', 'B');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id_siswa` int(11) NOT NULL,
  `nis` char(8) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `tempatLahir` varchar(100) NOT NULL,
  `tanggalLahir` date NOT NULL,
  `alamat` varchar(500) NOT NULL,
  `agama` varchar(15) NOT NULL,
  `noHp` varchar(15) NOT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id_siswa`, `nis`, `nama`, `tempatLahir`, `tanggalLahir`, `alamat`, `agama`, `noHp`, `email`) VALUES
(1, '34569999', 'Tegar', 'Cilacap', '2016-02-07', 'JL. Lingkar luar Cilacap, Jawa tengah', 'Islam', '085712344232', 'omoturaget@gmail.com'),
(2, '541344', 'Trio Andianto', 'Brebes', '1992-05-12', 'Banjarharho, Brebes', 'Islam', '0857434233433', 'trio.wek-wek@gmail.com'),
(3, '343534', 'Dani Kurniawan', 'Bekasi', '1993-02-11', 'Bekasi', 'Islam', '085776748748', 'dani.kurniawan@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `d_nilai`
--
ALTER TABLE `d_nilai`
  ADD PRIMARY KEY (`detail_nilai_id`),
  ADD KEY `nilai_id` (`nilai_id`),
  ADD KEY `id_mapel` (`id_mapel`);

--
-- Indexes for table `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`guru_id`),
  ADD KEY `nip` (`nip`),
  ADD KEY `guru_id` (`guru_id`);

--
-- Indexes for table `mapel`
--
ALTER TABLE `mapel`
  ADD PRIMARY KEY (`id_mapel`);

--
-- Indexes for table `nilai`
--
ALTER TABLE `nilai`
  ADD PRIMARY KEY (`nilai_id`),
  ADD KEY `nis` (`nis`),
  ADD KEY `nip` (`nip`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id_siswa`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `d_nilai`
--
ALTER TABLE `d_nilai`
  MODIFY `detail_nilai_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `guru`
--
ALTER TABLE `guru`
  MODIFY `guru_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `mapel`
--
ALTER TABLE `mapel`
  MODIFY `id_mapel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `nilai`
--
ALTER TABLE `nilai`
  MODIFY `nilai_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id_siswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
