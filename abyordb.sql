-- phpMyAdmin SQL Dump
-- version 3.1.3.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 18, 2016 at 10:49 PM
-- Server version: 5.1.33
-- PHP Version: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `abyordb`
--

-- --------------------------------------------------------

--
-- Table structure for table `detail_nilai`
--

CREATE TABLE IF NOT EXISTS `detail_nilai` (
  `id_detail_nilai` int(11) NOT NULL AUTO_INCREMENT,
  `id_nilai` int(11) NOT NULL,
  `id_mapel` int(11) NOT NULL,
  `nilai_afektif` int(11) NOT NULL,
  `nilai_komulatif` int(11) NOT NULL,
  `nilai_psikomotorik` int(11) NOT NULL,
  `nilai_rata_rata` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_detail_nilai`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Triggers `detail_nilai`
--
DROP TRIGGER IF EXISTS `abyordb`.`detail_nilai_before_ins_tr`;
DELIMITER //
CREATE TRIGGER `abyordb`.`detail_nilai_before_ins_tr` BEFORE INSERT ON `abyordb`.`detail_nilai`
 FOR EACH ROW BEGIN
SET NEW.nilai_rata_rata = (NEW.nilai_afektif + new.nilai_komulatif + NEW.nilai_psikomotorik) / 3;
END
//
DELIMITER ;

--
-- Dumping data for table `detail_nilai`
--

INSERT INTO `detail_nilai` (`id_detail_nilai`, `id_nilai`, `id_mapel`, `nilai_afektif`, `nilai_komulatif`, `nilai_psikomotorik`, `nilai_rata_rata`) VALUES
(1, 1, 1, 30, 40, 60, 23),
(5, 1, 1, 70, 70, 70, 70);

-- --------------------------------------------------------

--
-- Table structure for table `guru`
--

CREATE TABLE IF NOT EXISTS `guru` (
  `guru_id` int(11) NOT NULL AUTO_INCREMENT,
  `nip` char(8) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `tempat_lahir` varchar(100) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `agama` varchar(20) NOT NULL,
  `no_hp` varchar(15) NOT NULL,
  `email` varchar(50) NOT NULL,
  PRIMARY KEY (`guru_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `guru`
--

INSERT INTO `guru` (`guru_id`, `nip`, `nama`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `agama`, `no_hp`, `email`) VALUES
(1, '13340939', 'M Haidar Hanif', 'Palembang', '1995-02-18', 'Depok', 'Islam', '08135439834', 'mhaidar.hanif@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `mapel`
--

CREATE TABLE IF NOT EXISTS `mapel` (
  `id_mapel` int(11) NOT NULL AUTO_INCREMENT,
  `kode_mapel` char(8) NOT NULL,
  `nama_mapel` varchar(50) NOT NULL,
  PRIMARY KEY (`id_mapel`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `mapel`
--

INSERT INTO `mapel` (`id_mapel`, `kode_mapel`, `nama_mapel`) VALUES
(1, '52234', 'Matematika'),
(2, '54399', 'Fisika'),
(3, '45009', 'Bahasa Inggris');

-- --------------------------------------------------------

--
-- Table structure for table `nilai`
--

CREATE TABLE IF NOT EXISTS `nilai` (
  `nilai_id` int(11) NOT NULL AUTO_INCREMENT,
  `nis` int(11) NOT NULL,
  `nip` int(11) NOT NULL,
  `semester` varchar(20) NOT NULL,
  `kelas` varchar(20) NOT NULL,
  PRIMARY KEY (`nilai_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `nilai`
--

INSERT INTO `nilai` (`nilai_id`, `nis`, `nip`, `semester`, `kelas`) VALUES
(1, 1, 1, '5', 'A1');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE IF NOT EXISTS `siswa` (
  `id_siswa` int(11) NOT NULL AUTO_INCREMENT,
  `nis` char(8) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `tempat_lahir` varchar(50) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `agama` varchar(50) NOT NULL,
  `no_hp` varchar(15) NOT NULL,
  `email` varchar(50) NOT NULL,
  PRIMARY KEY (`id_siswa`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id_siswa`, `nis`, `nama`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `agama`, `no_hp`, `email`) VALUES
(1, '8991', 'Muhammad Tegar Utomo', 'Cilacap', '1993-02-24', 'Jalan LIngkar Cilacap', 'Islam', '085845454343', 'omoturaget@gmail.com');
