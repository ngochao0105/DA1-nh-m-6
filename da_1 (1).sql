-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 13, 2025 at 06:22 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `da_1`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int NOT NULL,
  `id_taikhoan` int DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `baocao`
--

CREATE TABLE `baocao` (
  `id` int NOT NULL,
  `id_admin` int DEFAULT NULL,
  `report_type` varchar(50) DEFAULT NULL,
  `content` text,
  `created_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `id` int NOT NULL,
  `id_tour` int NOT NULL,
  `schedule_id` int DEFAULT NULL,
  `ngay_di` date DEFAULT NULL,
  `loai_dat` varchar(20) DEFAULT 'ca_nhan',
  `trang_thai` varchar(50) DEFAULT 'cho_xac_nhan',
  `tong_tien` decimal(12,2) DEFAULT '0.00',
  `ngay_tao` datetime DEFAULT CURRENT_TIMESTAMP,
  `ngay_cap_nhat` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `trang_thai_thanh_toan` varchar(50) DEFAULT 'chua_thanh_toan'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`id`, `id_tour`, `schedule_id`, `ngay_di`, `loai_dat`, `trang_thai`, `tong_tien`, `ngay_tao`, `ngay_cap_nhat`, `trang_thai_thanh_toan`) VALUES
(7, 16, NULL, '2025-11-20', 'ca_nhan', 'cho_xac_nhan', '0.00', '2025-11-20 19:30:09', '2025-11-20 19:30:09', 'chua_thanh_toan'),
(8, 16, NULL, '2025-11-20', 'ca_nhan', 'huy', '0.00', '2025-11-20 19:30:34', '2025-11-23 14:21:46', 'da_coc'),
(9, 14, NULL, '2025-11-20', 'ca_nhan', 'cho_xac_nhan', '0.00', '2025-11-20 19:57:06', '2025-11-24 14:14:05', 'chua_thanh_toan'),
(10, 14, NULL, '2025-12-20', 'ca_nhan', 'hoan_tat', '0.00', '2025-11-20 21:27:16', '2025-11-21 16:44:50', 'chua_thanh_toan'),
(11, 10, NULL, '2025-11-21', 'ca_nhan', 'hoan_tat', '0.00', '2025-11-21 17:08:27', '2025-11-23 14:21:02', 'da_coc'),
(12, 13, NULL, '2025-01-12', 'ca_nhan', 'cho_xac_nhan', '0.00', '2025-11-23 14:23:03', '2025-11-23 14:42:24', 'da_thanh_toan_du'),
(13, 13, NULL, '0045-03-12', 'ca_nhan', 'cho_xac_nhan', '0.00', '2025-11-23 14:47:25', '2025-11-23 14:47:25', 'chua_thanh_toan'),
(14, 11, NULL, '1234-03-21', 'ca_nhan', 'cho_xac_nhan', '0.00', '2025-11-23 14:51:00', '2025-11-23 14:51:00', 'chua_thanh_toan'),
(15, 14, NULL, '2025-11-15', 'ca_nhan', 'dang_dien_ra', '0.00', '2025-11-23 14:54:00', '2025-11-24 17:24:48', 'chua_thanh_toan'),
(16, 16, NULL, '2025-11-23', 'ca_nhan', 'da_xac_nhan', '0.00', '2025-11-23 14:58:54', '2025-11-28 16:53:42', 'chua_thanh_toan'),
(17, 16, NULL, '2025-11-23', 'ca_nhan', 'dang_dien_ra', '0.00', '2025-11-23 14:59:54', '2025-11-23 15:46:29', 'da_coc'),
(19, 12, 4, '2025-12-01', 'ca_nhan', 'cho_xac_nhan', '0.00', '2025-11-24 14:46:49', '2025-11-24 14:46:49', 'chua_thanh_toan'),
(20, 16, 5, '2025-12-10', 'ca_nhan', 'cho_xac_nhan', '0.00', '2025-11-24 14:54:57', '2025-11-24 15:23:46', 'da_coc'),
(22, 14, 6, '2025-12-10', 'ca_nhan', 'dang_dien_ra', '0.00', '2025-11-24 15:31:21', '2025-11-24 17:14:32', 'da_coc'),
(23, 11, 7, '2025-12-02', 'ca_nhan', 'da_xac_nhan', '0.00', '2025-11-24 15:37:35', '2025-11-24 17:05:00', 'chua_thanh_toan');

-- --------------------------------------------------------

--
-- Table structure for table `booking_logs`
--

CREATE TABLE `booking_logs` (
  `id` int NOT NULL,
  `booking_id` int NOT NULL,
  `old_status` varchar(50) DEFAULT NULL,
  `new_status` varchar(50) DEFAULT NULL,
  `changed_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `changed_by` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `booking_logs`
--

INSERT INTO `booking_logs` (`id`, `booking_id`, `old_status`, `new_status`, `changed_at`, `changed_by`) VALUES
(9, 8, 'cho_xac_nhan', 'hoan_tat', '2025-11-20 19:31:57', 'admin'),
(10, 8, 'hoan_tat', 'cho_xac_nhan', '2025-11-20 19:38:07', 'admin'),
(11, 8, 'cho_xac_nhan', 'hoan_tat', '2025-11-20 19:40:47', 'admin'),
(12, 8, 'hoan_tat', 'cho_xac_nhan', '2025-11-20 19:47:15', 'admin'),
(13, 10, 'cho_xac_nhan', 'hoan_tat', '2025-11-21 16:44:50', 'admin'),
(14, 11, 'cho_xac_nhan', 'hoan_tat', '2025-11-21 17:08:33', 'admin'),
(15, 9, 'cho_xac_nhan', 'hoan_tat', '2025-11-22 20:45:39', 'admin'),
(16, 9, 'hoan_tat', 'da_coc', '2025-11-22 20:45:41', 'admin'),
(17, 8, 'cho_xac_nhan', 'huy', '2025-11-23 14:21:46', 'admin'),
(18, 12, 'cho_xac_nhan', 'hoan_tat', '2025-11-23 14:33:37', 'admin'),
(19, 12, 'hoan_tat', 'cho_xac_nhan', '2025-11-23 14:42:24', 'admin'),
(20, 17, 'cho_xac_nhan', 'hoan_tat', '2025-11-23 15:44:12', 'admin'),
(21, 17, 'hoan_tat', 'da_xac_nhan', '2025-11-23 15:46:12', 'admin'),
(22, 17, 'da_xac_nhan', 'dang_dien_ra', '2025-11-23 15:46:14', 'admin'),
(23, 17, 'dang_dien_ra', 'hoan_tat', '2025-11-23 15:46:16', 'admin'),
(24, 17, 'hoan_tat', 'da_xac_nhan', '2025-11-23 15:46:18', 'admin'),
(25, 17, 'da_xac_nhan', 'cho_xac_nhan', '2025-11-23 15:46:20', 'admin'),
(26, 17, 'cho_xac_nhan', 'da_xac_nhan', '2025-11-23 15:46:21', 'admin'),
(27, 17, 'da_xac_nhan', 'dang_dien_ra', '2025-11-23 15:46:29', 'admin'),
(28, 9, 'da_coc', 'cho_xac_nhan', '2025-11-24 14:14:05', 'admin'),
(30, 23, 'cho_xac_nhan', 'da_xac_nhan', '2025-11-24 17:05:00', 'admin'),
(31, 22, 'cho_xac_nhan', 'dang_dien_ra', '2025-11-24 17:14:32', 'admin'),
(32, 15, 'cho_xac_nhan', 'dang_dien_ra', '2025-11-24 17:24:48', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `chiphichitiet`
--

CREATE TABLE `chiphichitiet` (
  `id` int NOT NULL,
  `id_tour` int DEFAULT NULL,
  `expense_item` varchar(100) DEFAULT NULL,
  `amount` decimal(12,2) DEFAULT NULL,
  `notes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `loai_khach` enum('nguoi_lon','tre_em','em_be') DEFAULT 'nguoi_lon',
  `email` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `full_name`, `phone`, `loai_khach`, `email`, `created_at`) VALUES
(1, 'Nguyễn Văn A', '0900000001', 'nguoi_lon', 'vana@example.com', '2025-11-21 07:51:14'),
(2, 'Trần Thị B', '0900000002', 'nguoi_lon', 'thib@example.com', '2025-11-21 07:51:14'),
(3, 'Lê Văn C', '0900000003', 'tre_em', 'vanc@example.com', '2025-11-21 07:51:14'),
(4, 'Hoàng Thị D', '0900000004', 'em_be', 'thid@example.com', '2025-11-21 07:51:14'),
(5, 'Phạm Văn E', '0900000005', 'nguoi_lon', 'vane@example.com', '2025-11-21 07:51:14'),
(6, 'Nguyễn Thị F', '0900000006', 'nguoi_lon', 'thif@example.com', '2025-11-21 07:51:14'),
(7, 'Lê Thành G', '0900000007', 'nguoi_lon', 'g@example.com', '2025-11-21 07:51:14'),
(8, 'Đặng Thị H', '0900000008', 'tre_em', 'h@example.com', '2025-11-21 07:51:14'),
(9, 'Lý Văn I', '0900000009', 'em_be', 'i@example.com', '2025-11-21 07:51:14'),
(10, 'Đoàn Thị K', '0900000010', 'nguoi_lon', 'k@example.com', '2025-11-21 07:51:14'),
(11, 'Trương Văn L', '0900000011', 'nguoi_lon', 'l@example.com', '2025-11-21 07:51:14'),
(12, 'Lý Mạnh M', '0900000012', 'tre_em', 'm@example.com', '2025-11-21 07:51:14'),
(13, 'Phan Mỹ N', '0900000013', 'nguoi_lon', 'n@example.com', '2025-11-21 07:51:14'),
(14, 'Đỗ Văn O', '0900000014', 'em_be', 'o@example.com', '2025-11-21 07:51:14'),
(15, 'Nguyễn Phi P', '0900000015', 'nguoi_lon', 'p@example.com', '2025-11-21 07:51:14'),
(16, 'Trần Thị Q', '0900000016', 'tre_em', 'q@example.com', '2025-11-21 07:51:14'),
(17, 'Võ Văn R', '0900000017', 'nguoi_lon', 'r@example.com', '2025-11-21 07:51:14'),
(18, 'Đinh Thị S', '0900000018', 'nguoi_lon', 's@example.com', '2025-11-21 07:51:14'),
(19, 'Hà Văn T', '0900000019', 'em_be', 't@example.com', '2025-11-21 07:51:14'),
(20, 'Nguyễn Trung U', '0900000020', 'nguoi_lon', 'u@example.com', '2025-11-21 07:51:14'),
(21, 'Nguyễn Văn V', '0900000021', 'nguoi_lon', 'v@example.com', '2025-11-21 07:51:14'),
(22, 'Trần Thị X', '0900000022', 'tre_em', 'x@example.com', '2025-11-21 07:51:14'),
(23, 'Lê Văn Y', '0900000023', 'nguoi_lon', 'y@example.com', '2025-11-21 07:51:14'),
(24, 'Hoàng Minh Z', '0900000024', 'em_be', 'z@example.com', '2025-11-21 07:51:14'),
(25, 'Nguyễn Tiến Khoa', '0900000025', 'nguoi_lon', 'khoa@example.com', '2025-11-21 07:51:14'),
(26, 'Lê Mỹ Duyên', '0900000026', 'nguoi_lon', 'duyen@example.com', '2025-11-21 07:51:14'),
(27, 'Phạm Hữu Thắng', '0900000027', 'tre_em', 'thang@example.com', '2025-11-21 07:51:14'),
(28, 'Trịnh Quốc Huy', '0900000028', 'em_be', 'huy@example.com', '2025-11-21 07:51:14'),
(29, 'Phan Ngọc Châu', '0900000029', 'nguoi_lon', 'chau@example.com', '2025-11-21 07:51:14'),
(30, 'Đoàn Minh Hòa', '0900000030', 'nguoi_lon', 'hoa@example.com', '2025-11-21 07:51:14'),
(31, 'Văn Thanh Hà', '0900000031', 'tre_em', 'ha@example.com', '2025-11-21 07:51:14'),
(32, 'Ngọc Thuận', '0900000032', 'nguoi_lon', 'thuan@example.com', '2025-11-21 07:51:14'),
(33, 'Phạm Nhật Tân', '0900000033', 'nguoi_lon', 'tan@example.com', '2025-11-21 07:51:14'),
(34, 'Võ Thị Mai', '0900000034', 'em_be', 'mai@example.com', '2025-11-21 07:51:14'),
(35, 'Nguyễn Hữu Phước', '0900000035', 'nguoi_lon', 'phuoc@example.com', '2025-11-21 07:51:14'),
(36, 'Lê Quang Duy', '0900000036', 'tre_em', 'duy@example.com', '2025-11-21 07:51:14'),
(37, 'Trần Bảo Anh', '0900000037', 'nguoi_lon', 'anh@example.com', '2025-11-21 07:51:14'),
(38, 'Đỗ Minh Khôi', '0900000038', 'nguoi_lon', 'khoi@example.com', '2025-11-21 07:51:14'),
(39, 'Phạm Quốc Cường', '0900000039', 'tre_em', 'cuong@example.com', '2025-11-21 07:51:14'),
(40, 'Vũ Nhật Minh', '0900000040', 'nguoi_lon', 'minh@example.com', '2025-11-21 07:51:14');

-- --------------------------------------------------------

--
-- Table structure for table `danhmuctour`
--

CREATE TABLE `danhmuctour` (
  `id` int NOT NULL,
  `category_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `danhmuctour`
--

INSERT INTO `danhmuctour` (`id`, `category_name`) VALUES
(1, 'Tour Trong Nước'),
(2, 'Tour Nước Ngoài');

-- --------------------------------------------------------

--
-- Table structure for table `diemdanh`
--

CREATE TABLE `diemdanh` (
  `id` int NOT NULL,
  `id_khach` int DEFAULT NULL,
  `id_tour` int DEFAULT NULL,
  `id_hdv` int DEFAULT NULL,
  `check_time` datetime DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `giatour`
--

CREATE TABLE `giatour` (
  `id` int NOT NULL,
  `tour_id` int NOT NULL,
  `adult_price` int DEFAULT '0',
  `child_price` int DEFAULT '0',
  `infant_price` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hosokhach`
--

CREATE TABLE `hosokhach` (
  `id` int NOT NULL,
  `id_khach` int DEFAULT NULL,
  `health_info` text,
  `insurance_info` text,
  `notes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `khachtour`
--

CREATE TABLE `khachtour` (
  `id` int NOT NULL,
  `id_booking` int NOT NULL,
  `ten_khach` varchar(100) DEFAULT NULL,
  `sdt` varchar(20) DEFAULT NULL,
  `loai_khach` enum('nguoi_lon','tre_em','em_be') DEFAULT 'nguoi_lon',
  `yeu_cau_dac_biet` text,
  `da_checkin` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `khachtour`
--

INSERT INTO `khachtour` (`id`, `id_booking`, `ten_khach`, `sdt`, `loai_khach`, `yeu_cau_dac_biet`, `da_checkin`) VALUES
(7, 7, 'Hào', '056581e3', 'nguoi_lon', 'ăn chay', 0),
(8, 8, 'Hào', '056581e3', 'nguoi_lon', 'ẻ', 0),
(9, 9, 'Hào', '22222', 'nguoi_lon', '2', 0),
(10, 10, 'Hào', '056581e3', 'nguoi_lon', 'ăn chay', 0),
(11, 10, 'ngọc trưởng', 'r333', 'nguoi_lon', 'hkasc', 0),
(12, 11, 'Trưởng', '028289595', 'nguoi_lon', 'Ăn Chay', 0),
(13, 11, 'Đức', '0254359454', 'nguoi_lon', 'Khách sản', 0),
(14, 12, 'Nguyễn Văn Minh Tuấn', '0364902031', 'nguoi_lon', 'Đi xe điện', 0),
(15, 13, 'edew', 'ew2345', 'nguoi_lon', 'è', 0),
(16, 14, 'egregr', 'e23456789o0p', 'nguoi_lon', 'fghjklkjhg', 0),
(17, 14, 'ưerftgyhjkl', '1234567890p[', 'nguoi_lon', 'sdfghjkl.;/', 0),
(18, 15, 'dẻtg', 'eggf', 'nguoi_lon', 'dsvsdvsd', 0),
(19, 16, 'Nguyễn Văn Minh Tuấn', '234567', 'nguoi_lon', 'sdfddf', 0),
(20, 17, 'Nguyên Quoc Vuong', '123456789', 'tre_em', 'dvfvdvvdf', 0),
(21, 17, 'Hào Trần', '123456789', 'tre_em', 'ăn mặn', 0),
(24, 19, 'Nguyễn Ngọc Anh', '0975931290', 'tre_em', 'Mang theo thú cưng', 0),
(25, 20, 'Nguyễn Văn Minh Tuấn', '1234567890', 'tre_em', 'dsvdsvdfvdfbr', 0),
(32, 22, 'ưerftgyhjkl', '1234567890p[', 'nguoi_lon', 'fwefrvre', 0),
(33, 22, 'dẻtg', 'eggf', 'nguoi_lon', 'ừewfc', 0),
(34, 23, 'ngọc trưởng', 'r333', 'nguoi_lon', 'dewfefer', 0),
(35, 19, 'ngoc hao', '0101010101010', 'tre_em', 'ăn chay', 0),
(36, 19, 'd', '', 'nguoi_lon', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `lichlamviec`
--

CREATE TABLE `lichlamviec` (
  `id` int NOT NULL,
  `id_hdv` int DEFAULT NULL,
  `date` date DEFAULT NULL,
  `task` text,
  `notes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lichtrinhtour`
--

CREATE TABLE `lichtrinhtour` (
  `id` int NOT NULL,
  `id_tour` int DEFAULT NULL,
  `date` date DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `activity` text,
  `guide_task` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nhansu`
--

CREATE TABLE `nhansu` (
  `id` int NOT NULL,
  `id_taikhoan` int DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `guide_type` varchar(50) DEFAULT NULL,
  `average_rating` float DEFAULT NULL,
  `password_display` varchar(255) DEFAULT NULL,
  `competence_level` varchar(50) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `nhansu`
--

INSERT INTO `nhansu` (`id`, `id_taikhoan`, `full_name`, `birth_date`, `phone`, `email`, `guide_type`, `average_rating`, `password_display`, `competence_level`, `username`, `password`) VALUES
(1, 8, 'Trần Ngọc Hào', '2005-01-21', '0364902031', 'ngochao21012005@gmail.com', 'Tiếng Việt', 4, '123456789', '', NULL, NULL),
(2, 4, 'Nguyễn Thị Minh Châu', '1998-03-15', '0912345678', 'minhchau.travel@gmail.com', 'Tiếng Anh', 5, 'minhchau123', '', NULL, NULL),
(4, NULL, 'Phạm Thái Sơn', '1992-12-05', '0905566778', 'sonphamguide@yahoo.com', 'Tiếng Việt', 4, NULL, NULL, NULL, NULL),
(5, NULL, 'Trần Thị Mỹ Duyên', '1999-07-12', '0938899776', 'duyentravel@gmail.com', 'Tiếng Việt', 5, NULL, NULL, NULL, NULL),
(6, 12, 'Lê Quốc Vũ', '1990-11-30', '0966998877', 'vuquocguide@gmail.com', 'Tiếng Trung', 3, '123456789', 'Chuyên viên', NULL, NULL),
(7, 11, 'Trần Ngọc Hào', '2003-02-21', '0364902031', 'hao005@gmail.com', 'Tiếng Anh', NULL, '123456789', 'Nhân viên', NULL, NULL),
(8, 14, 'êwweewed', '2025-12-03', '123456789', 'h@gmail.com', 'Tiếng Anh', NULL, 'haojqk', 'Nhân viên', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `phan_cong_hdv`
--

CREATE TABLE `phan_cong_hdv` (
  `id` int NOT NULL,
  `id_hdv` int NOT NULL,
  `id_booking` int DEFAULT NULL,
  `ngay_di` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `phan_cong_hdv`
--

INSERT INTO `phan_cong_hdv` (`id`, `id_hdv`, `id_booking`, `ngay_di`) VALUES
(3, 1, 7, '2025-11-20'),
(4, 2, 8, '2025-11-20'),
(5, 4, 9, '2025-11-20'),
(6, 1, 10, '2025-12-20'),
(7, 1, 11, '2025-11-21'),
(8, 1, 12, '2025-01-12'),
(10, 6, 14, '1234-03-21'),
(11, 4, 15, '2025-11-15'),
(12, 1, 16, '2025-11-23'),
(13, 4, 17, '2025-11-23'),
(15, 7, 19, '2025-12-01'),
(16, 2, 20, '2025-12-10'),
(17, 6, 22, '2025-12-10'),
(18, 5, 23, '2025-12-02');

-- --------------------------------------------------------

--
-- Table structure for table `taikhoan`
--

CREATE TABLE `taikhoan` (
  `id` int NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `taikhoan`
--

INSERT INTO `taikhoan` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', '123456789', 'admin'),
(4, 'Minhchau123', '$2y$10$eIxzzdBXjjM2Sbp6NIZ//uTTYWt63l4Tw0EdraPLtDa6KYdF2kOni', 'hdv'),
(8, 'ngochao2101', '$2y$10$BnJiOkiTKIabwvSgQEf0ceTYAEIWeMFNg/PgX2lUhtZMUdQCifbUS', 'hdv'),
(11, 'hao05', '$2y$10$12hI1D.Z7n3EfB.TurGE0.cw09lo.BmrCZM998m.Jd1ibCu51FHUa', 'hdv'),
(12, 'quocvu001', '$2y$10$CQ/nE3E5JROtXpqdnAK5NukxkljRanwB1efTL/TvJTNe338pHXSW6', 'hdv'),
(14, 'haojqk1', '$2y$10$os1ucq3iF21Wjvo8c/.e.e6jE9jTeUydk9.YGKTD6./7zz9TIF4eO', 'hdv');

-- --------------------------------------------------------

--
-- Table structure for table `tour`
--

CREATE TABLE `tour` (
  `id` int NOT NULL,
  `tour_name` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text,
  `destination` varchar(100) DEFAULT NULL,
  `id_danh_muc` int DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `duration` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tour`
--

INSERT INTO `tour` (`id`, `tour_name`, `image`, `description`, `destination`, `id_danh_muc`, `status`, `duration`) VALUES
(3, 'Đà Nẵng 2 ngày 1 đêm', NULL, 'sfdsvdfvfd', 'Đã Nẵng', 1, 1, NULL),
(9, 'Tour Đà Lạt 3 ngày 2 đêm', NULL, 'Khám phá thành phố ngàn hoa, check-in Hồ Tuyền Lâm, Langbiang, Fresh Garden', 'Đà Lạt', 1, 1, NULL),
(10, 'Tour Đà Lạt 3 ngày 2 đêm', 'dalat.jpg', 'Khám phá thành phố ngàn hoa, check-in Hồ Tuyền Lâm, Langbiang.', 'Đà Lạt', 1, 1, NULL),
(11, 'Tour Phú Quốc 4 ngày 3 đêm', 'phuquoc.jpg', 'Tham quan VinWonders, Safari, Bãi Sao – nghỉ dưỡng cao cấp.', 'Phú Quốc', 1, 1, NULL),
(12, 'Tour Sa Pa săn mây 2 ngày 1 đêm', 'sapa.jpg', 'Trekking bản Cát Cát, ngắm đèo Ô Quy Hồ và săn mây Fansipan.', 'Sa Pa', 1, 1, NULL),
(13, 'Tour Bangkok – Pattaya 4N3Đ', 'thai.jpg', 'Khám phá chợ nổi, Safari World, Alcazar Show và biển Pattaya.', 'Bangkok – Pattaya', 2, 1, NULL),
(14, 'Tour Singapore – Malaysia 5N4Đ', 'singmalay.jpg', 'Marina Bay, Gardens by the Bay, Genting, Twin Towers.', 'Singapore – Malaysia', 2, 1, NULL),
(15, 'dcscd', NULL, 'dscdsc', 'Hà Nội', 2, 2, NULL),
(16, 'FoodTour ', NULL, 'Vi Vu Hải Phòng', 'Hải Phòng', 1, 0, NULL),
(18, 'tour1', NULL, 'adefcrdghgjklhgfdsa', 'Nha Trang', 1, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tour_guide`
--

CREATE TABLE `tour_guide` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `status` tinyint DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tour_schedule`
--

CREATE TABLE `tour_schedule` (
  `id` int NOT NULL,
  `tour_id` int NOT NULL COMMENT 'ID của tour',
  `start_date` date NOT NULL COMMENT 'Ngày bắt đầu tour',
  `end_date` date NOT NULL COMMENT 'Ngày kết thúc tour',
  `price` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Giá tour',
  `max_slots` int NOT NULL DEFAULT '0' COMMENT 'Số slot tối đa',
  `booked_slots` int NOT NULL DEFAULT '0' COMMENT 'Số slot đã đặt',
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sap_mo' COMMENT 'Trạng thái: sap_mo, dang_mo, da_dong',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng lưu trữ lịch trình tour';

--
-- Dumping data for table `tour_schedule`
--

INSERT INTO `tour_schedule` (`id`, `tour_id`, `start_date`, `end_date`, `price`, `max_slots`, `booked_slots`, `status`, `created_at`, `updated_at`) VALUES
(1, 16, '2025-11-27', '2025-11-30', '123456.00', 3, 3, 'da_dong', '2025-11-22 13:51:50', '2025-11-24 07:53:32'),
(2, 16, '2100-12-24', '2100-12-26', '123456.00', 20, 0, 'da_dong', '2025-11-23 08:03:05', '2025-11-23 08:03:05'),
(3, 16, '2025-11-25', '2025-11-27', '10000000.00', 1, 0, 'da_dong', '2025-11-24 07:20:07', '2025-11-28 09:31:13'),
(4, 12, '2025-12-01', '2025-12-03', '15000000.00', 10, 3, 'da_dong', '2025-11-24 07:45:56', '2025-12-12 16:44:19'),
(5, 16, '2025-12-10', '2025-12-13', '20000000.00', 20, 1, 'dang_mo', '2025-11-24 07:54:04', '2025-11-24 08:30:58'),
(6, 14, '2025-12-10', '2025-12-15', '50000000.00', 30, 2, 'dang_mo', '2025-11-24 08:30:08', '2025-11-24 08:31:53'),
(7, 11, '2025-12-02', '2025-12-07', '25000000.00', 25, 1, 'da_dong', '2025-11-24 08:35:45', '2025-12-12 16:44:19'),
(8, 16, '2025-12-13', '2025-12-16', '1234567890.00', 20, 0, 'dang_mo', '2025-12-01 09:40:50', '2025-12-01 09:40:57'),
(9, 18, '2025-12-21', '2025-12-22', '234567.00', 40, 0, 'dang_mo', '2025-12-01 09:41:52', '2025-12-01 09:41:55');

-- --------------------------------------------------------

--
-- Table structure for table `yeucaukhach`
--

CREATE TABLE `yeucaukhach` (
  `id` int NOT NULL,
  `id_khach` int DEFAULT NULL,
  `id_hdv` int DEFAULT NULL,
  `description` text,
  `updated_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_taikhoan` (`id_taikhoan`);

--
-- Indexes for table `baocao`
--
ALTER TABLE `baocao`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_tour` (`id_tour`);

--
-- Indexes for table `booking_logs`
--
ALTER TABLE `booking_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `chiphichitiet`
--
ALTER TABLE `chiphichitiet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_tour` (`id_tour`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `danhmuctour`
--
ALTER TABLE `danhmuctour`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `diemdanh`
--
ALTER TABLE `diemdanh`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_khach` (`id_khach`),
  ADD KEY `id_tour` (`id_tour`),
  ADD KEY `id_hdv` (`id_hdv`);

--
-- Indexes for table `giatour`
--
ALTER TABLE `giatour`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Indexes for table `hosokhach`
--
ALTER TABLE `hosokhach`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_khach` (`id_khach`);

--
-- Indexes for table `khachtour`
--
ALTER TABLE `khachtour`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_booking` (`id_booking`);

--
-- Indexes for table `lichlamviec`
--
ALTER TABLE `lichlamviec`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_hdv` (`id_hdv`);

--
-- Indexes for table `lichtrinhtour`
--
ALTER TABLE `lichtrinhtour`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_tour` (`id_tour`);

--
-- Indexes for table `nhansu`
--
ALTER TABLE `nhansu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_taikhoan` (`id_taikhoan`);

--
-- Indexes for table `phan_cong_hdv`
--
ALTER TABLE `phan_cong_hdv`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_hdv` (`id_hdv`),
  ADD KEY `fk_booking` (`id_booking`);

--
-- Indexes for table `taikhoan`
--
ALTER TABLE `taikhoan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tour`
--
ALTER TABLE `tour`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_danh_muc` (`id_danh_muc`);

--
-- Indexes for table `tour_guide`
--
ALTER TABLE `tour_guide`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tour_schedule`
--
ALTER TABLE `tour_schedule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_tour_id` (`tour_id`),
  ADD KEY `idx_start_date` (`start_date`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `yeucaukhach`
--
ALTER TABLE `yeucaukhach`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_khach` (`id_khach`),
  ADD KEY `id_hdv` (`id_hdv`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `baocao`
--
ALTER TABLE `baocao`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `booking_logs`
--
ALTER TABLE `booking_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `chiphichitiet`
--
ALTER TABLE `chiphichitiet`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `danhmuctour`
--
ALTER TABLE `danhmuctour`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `diemdanh`
--
ALTER TABLE `diemdanh`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `giatour`
--
ALTER TABLE `giatour`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hosokhach`
--
ALTER TABLE `hosokhach`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `khachtour`
--
ALTER TABLE `khachtour`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `lichlamviec`
--
ALTER TABLE `lichlamviec`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lichtrinhtour`
--
ALTER TABLE `lichtrinhtour`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nhansu`
--
ALTER TABLE `nhansu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `phan_cong_hdv`
--
ALTER TABLE `phan_cong_hdv`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `taikhoan`
--
ALTER TABLE `taikhoan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tour`
--
ALTER TABLE `tour`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tour_guide`
--
ALTER TABLE `tour_guide`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tour_schedule`
--
ALTER TABLE `tour_schedule`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `yeucaukhach`
--
ALTER TABLE `yeucaukhach`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`id_taikhoan`) REFERENCES `taikhoan` (`id`);

--
-- Constraints for table `baocao`
--
ALTER TABLE `baocao`
  ADD CONSTRAINT `baocao_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id`);

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`id_tour`) REFERENCES `tour` (`id`);

--
-- Constraints for table `chiphichitiet`
--
ALTER TABLE `chiphichitiet`
  ADD CONSTRAINT `chiphichitiet_ibfk_1` FOREIGN KEY (`id_tour`) REFERENCES `tour` (`id`);

--
-- Constraints for table `diemdanh`
--
ALTER TABLE `diemdanh`
  ADD CONSTRAINT `diemdanh_ibfk_2` FOREIGN KEY (`id_tour`) REFERENCES `tour` (`id`),
  ADD CONSTRAINT `diemdanh_ibfk_3` FOREIGN KEY (`id_hdv`) REFERENCES `nhansu` (`id`);

--
-- Constraints for table `giatour`
--
ALTER TABLE `giatour`
  ADD CONSTRAINT `giatour_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `khachtour`
--
ALTER TABLE `khachtour`
  ADD CONSTRAINT `khachtour_ibfk_1` FOREIGN KEY (`id_booking`) REFERENCES `booking` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lichlamviec`
--
ALTER TABLE `lichlamviec`
  ADD CONSTRAINT `lichlamviec_ibfk_1` FOREIGN KEY (`id_hdv`) REFERENCES `nhansu` (`id`);

--
-- Constraints for table `lichtrinhtour`
--
ALTER TABLE `lichtrinhtour`
  ADD CONSTRAINT `lichtrinhtour_ibfk_1` FOREIGN KEY (`id_tour`) REFERENCES `tour` (`id`);

--
-- Constraints for table `nhansu`
--
ALTER TABLE `nhansu`
  ADD CONSTRAINT `nhansu_ibfk_1` FOREIGN KEY (`id_taikhoan`) REFERENCES `taikhoan` (`id`);

--
-- Constraints for table `phan_cong_hdv`
--
ALTER TABLE `phan_cong_hdv`
  ADD CONSTRAINT `fk_booking` FOREIGN KEY (`id_booking`) REFERENCES `booking` (`id`),
  ADD CONSTRAINT `phan_cong_hdv_ibfk_1` FOREIGN KEY (`id_hdv`) REFERENCES `nhansu` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tour`
--
ALTER TABLE `tour`
  ADD CONSTRAINT `tour_ibfk_1` FOREIGN KEY (`id_danh_muc`) REFERENCES `danhmuctour` (`id`);

--
-- Constraints for table `tour_schedule`
--
ALTER TABLE `tour_schedule`
  ADD CONSTRAINT `fk_tour_schedule_tour` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `yeucaukhach`
--
ALTER TABLE `yeucaukhach`
  ADD CONSTRAINT `yeucaukhach_ibfk_2` FOREIGN KEY (`id_hdv`) REFERENCES `nhansu` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
