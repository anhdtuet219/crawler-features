-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 21, 2018 at 12:52 PM
-- Server version: 10.1.28-MariaDB
-- PHP Version: 7.1.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `crawler`
--

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `job_id` int(11) NOT NULL,
  `job_name` text NOT NULL,
  `job_link` text NOT NULL,
  `job_type` text NOT NULL,
  `job_salary` text NOT NULL,
  `job_location` text NOT NULL,
  `job_company` text NOT NULL,
  `source_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`job_id`, `job_name`, `job_link`, `job_type`, `job_salary`, `job_location`, `job_company`, `source_id`) VALUES
(33, 'Nhân Viên Phục Vụ Nhà Hàng Khách Sạn My Way', 'https://vieclam24h.vn/khach-san-nha-hang/nhan-vien-phuc-vu-nha-hang-khach-san-c84p0id2749980.html', 'Thực phẩm-Đồ uống', '5 - 7 triệu', 'Hà Nội', 'Công ty Cổ phần Thực phẩm và Dịch vụ My Way', 1),
(34, 'Nhân Viên Mua Hàng Tại Hà Nội', 'https://vieclam24h.vn/ke-toan-kiem-toan/nhan-vien-mua-hang-tai-ha-noi-c30p0id2895267.html', 'Thực phẩm-Đồ uống', '5 - 7 triệu', 'Hà Nội', 'Công ty cổ phần Paris Gâteaux Việt Nam', 1),
(35, 'Kế Toán Tổng Hợp - Kế Toán Thuế', 'https://vieclam24h.vn/dich-vu/ke-toan-tong-hop-ke-toan-thue-c7p0id2367223.html', ' Dịch vụ', '7 – 10 triệu', 'Hà Nội', 'Công Ty Cổ Phần Cơ Điện Lạnh Hoàng Đạt', 1),
(36, 'Nhân Viên Phục Vụ Nhà Hàng Khách Sạn My Way', 'https://vieclam24h.vn/khach-san-nha-hang/nhan-vien-phuc-vu-nha-hang-khach-san-c84p0id2749980.html', ' Dịch vụ', '5 - 7 triệu', 'Hà Nội', 'Công ty Cổ phần Thực phẩm và Dịch vụ My Way', 1),
(37, 'Kế Toán Trưởng', 'https://careerlink.vn/tim-viec-lam/ke-toan-truong/1300759', 'Kế toán / Kiểm toán', 'Cạnh tranh | Quản lý / Trưởng phòng', 'Hồ Chí Minh', 'Công ty TNHH Handee', 2),
(38, 'Nhân Viên Kế Toán Công Nợ Nước Ngoài - Mức lương 7 – 10 triệu', 'https://careerlink.vn/tim-viec-lam/nhan-vien-ke-toan-cong-no-nuoc-ngoai-muc-luong-7-%E2%80%93-10-trieu/1300720', 'Kế toán / Kiểm toán', '7,000,000VNĐ - 10,000,000VNĐ | Nhân viên', 'Hồ Chí Minh', 'CÔNG TY TNHH VẬN TẢI VIỆT NHẬT', 2),
(39, 'Trưởng Phòng Kinh Doanh Du Lịch', 'https://careerlink.vn/tim-viec-lam/truong-phong-kinh-doanh-du-lich/1287946', 'Khách sạn / Du lịch', 'Thương lượng | Quản lý / Trưởng phòng', 'Hà Nội', 'Công Ty CP ĐTTM Và DVDL Quốc Tế Xanh', 2),
(40, 'Nhân Viên Sales Tour Du Lịch', 'https://careerlink.vn/tim-viec-lam/nhan-vien-sales-tour-du-lich/1287942', 'Khách sạn / Du lịch', '7,000,000VNĐ - 15,000,000VNĐ | Nhân viên', 'Hà Nội', 'Công Ty CP ĐTTM Và DVDL Quốc Tế Xanh', 2),
(41, 'Channel Marketing Manager', 'https://careerbuilder.vn/vi/tim-viec-lam/channel-marketing-manager.35AEDAFB.html', 'Quảng cáo / Đối ngoại / Truyền Thông', 'Lương: Cạnh tranh', 'Hồ Chí Minh', 'Prudential', 3),
(42, 'Digital Marketing', 'https://careerbuilder.vn/vi/tim-viec-lam/digital-marketing.35AEE95F.html', 'Quảng cáo / Đối ngoại / Truyền Thông', 'Lương: 7 Tr - 20 Tr VND', 'Hồ Chí Minh', 'CÔNG TY TNHH GOALEVO', 3),
(43, 'Họa Viên Kiến Trúc (Revit)', 'https://careerbuilder.vn/vi/tim-viec-lam/hoa-vien-kien-truc-revit.35AF1FB3.html', 'Nội ngoại thất', 'Lương: Cạnh tranh', 'Hồ Chí Minh', 'Kume Design Asia Co., Ltd - KDA', 3),
(44, 'Nhân viên Kinh Doanh Nội Thất Hoàn Thiện', 'https://careerbuilder.vn/vi/tim-viec-lam/nhan-vien-kinh-doanh-noi-that-hoan-thien.35AF287E.html', 'Nội ngoại thất', 'Lương: Trên 10 Tr VND', 'Hồ Chí Minh', 'CÔNG TY TNHH XUẤT NHẬP KHẨU OSI', 3);

-- --------------------------------------------------------

--
-- Table structure for table `sources`
--

CREATE TABLE `sources` (
  `source_id` int(11) NOT NULL,
  `source_name` text NOT NULL,
  `source_seed_url` text NOT NULL,
  `source_type_job_tag` text NOT NULL,
  `source_link_tag` text NOT NULL,
  `source_title_tag` text NOT NULL,
  `source_company_tag` text NOT NULL,
  `source_location_tag` text NOT NULL,
  `source_salary_tag` text NOT NULL,
  `source_job_page_tag` text NOT NULL,
  `source_first_page` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sources`
--

INSERT INTO `sources` (`source_id`, `source_name`, `source_seed_url`, `source_type_job_tag`, `source_link_tag`, `source_title_tag`, `source_company_tag`, `source_location_tag`, `source_salary_tag`, `source_job_page_tag`, `source_first_page`) VALUES
(1, 'https://vieclam24h.vn/', 'https://vieclam24h.vn/viec-lam-quan-ly', 'div#gate_nganhnghe_hot_menu_right div.nganhnghe_item_right div.news-title a', 'span.title-blockjob-main a', 'span.title-blockjob-main a', 'span.title-blockjob-sub a', 'span.onecol_province', 'div.note_mucluong', '', ''),
(2, 'https://www.careerlink.vn/', 'https://www.careerlink.vn', '#search-by-category ul li a', 'h2.list-group-item-heading a', 'h2.list-group-item-heading a', 'div.list-group-item-text p a.text-accent', 'div.list-group-item-text p.priority-data', 'div.list-group-item-text div small', 'ul.pagination li a', '?view=headline&page=1'),
(3, 'https://careerbuilder.vn/', 'https://careerbuilder.vn/tim-viec-lam.html', 'div#JobCategoriesListing div.colJob div.groupJob ul li a', 'h3.job a', 'h3.job a', 'p.namecom', 'p.location', 'p.salary', 'div.paginationTwoStatus a', 'trang-1-vi.html');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`job_id`),
  ADD KEY `job_source_id` (`source_id`);

--
-- Indexes for table `sources`
--
ALTER TABLE `sources`
  ADD PRIMARY KEY (`source_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `job_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `sources`
--
ALTER TABLE `sources`
  MODIFY `source_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `jobs_ibfk_1` FOREIGN KEY (`source_id`) REFERENCES `sources` (`source_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
