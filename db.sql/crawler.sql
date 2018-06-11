-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 08, 2018 at 01:27 PM
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
(1, 'https://vieclam24h.vn/', 'https://vieclam24h.vn/viec-lam-quan-ly', 'div.news-title a', 'span.title-blockjob-main a', 'span.title-blockjob-main a', 'span.title-blockjob-sub a', 'span.onecol_province', 'div.note_mucluong', '', ''),
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
  MODIFY `job_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=285;

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
