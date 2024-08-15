-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 20, 2024 at 08:57 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cab2u_new`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_menu`
--

CREATE TABLE `admin_menu` (
  `id` int(10) UNSIGNED NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT 0,
  `order` int(11) NOT NULL DEFAULT 0,
  `title` varchar(50) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `uri` varchar(50) DEFAULT NULL,
  `permission` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_menu`
--

INSERT INTO `admin_menu` (`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `permission`, `created_at`, `updated_at`) VALUES
(1, 0, 1, 'Dashboard', 'fa-bar-chart', '/', NULL, NULL, '2021-03-02 08:03:07'),
(8, 9, 4, 'App Settings', 'fa-cog', 'app-settings', '*', '2020-04-02 12:42:07', '2023-12-29 14:35:22'),
(9, 0, 2, 'Settings', 'fa-cogs', NULL, '*', '2020-04-02 13:34:23', '2022-12-08 12:19:29'),
(10, 0, 56, 'Cancellation Reasons', 'fa-th-list', 'cancellation-reasons', '*', '2020-04-02 14:15:00', '2024-01-18 17:20:18'),
(12, 0, 54, 'Tax Lists', 'fa-briefcase', 'tax-lists', '*', '2020-04-02 15:45:48', '2024-01-18 17:20:18'),
(16, 9, 5, 'Trip Settings', 'fa-asterisk', 'trip-settings', '*', '2020-04-03 06:46:34', '2023-12-29 14:35:22'),
(18, 0, 58, 'Faqs', 'fa-bars', 'faqs', '*', '2020-04-03 07:29:41', '2024-01-18 17:20:18'),
(19, 0, 59, 'Privacy Policies', 'fa-eye', 'privacy-policies', '*', '2020-04-03 07:56:48', '2024-01-18 17:20:18'),
(20, 74, 50, 'Payment Methods', 'fa-hand-o-right', 'payment-methods', '*', '2020-04-03 08:23:01', '2024-01-18 17:20:18'),
(21, 0, 53, 'Promo Codes', 'fa-gift', 'promo-codes', '*', '2020-04-03 08:39:09', '2024-01-18 17:20:18'),
(23, 0, 28, 'Vehicle Categories', 'fa-automobile', 'vehicle-categories', '*', '2020-04-04 00:19:34', '2024-01-18 17:20:18'),
(24, 38, 46, 'Complaint Categories', 'fa-close', 'complaint-categories', '*', '2020-04-04 00:34:52', '2024-01-18 17:20:18'),
(25, 38, 47, 'Complaint Sub Categories', 'fa-adjust', 'complaint-sub-categories', '*', '2020-04-04 01:15:57', '2024-01-18 17:20:18'),
(27, 38, 48, 'Complaints', 'fa-comment', 'complaints', '*', '2020-04-04 12:30:58', '2024-01-18 17:20:18'),
(28, 45, 13, 'Customers', 'fa-female', 'customers', NULL, '2020-04-04 12:58:58', '2024-01-18 17:20:18'),
(29, 41, 19, 'Drivers', 'fa-compass', 'drivers', '*', '2020-04-04 13:42:27', '2024-01-18 17:20:18'),
(31, 41, 20, 'Driver Vehicles', 'fa-cab', 'driver-vehicles', '*', '2020-04-06 04:03:38', '2024-01-18 17:20:18'),
(32, 9, 6, 'Contact Settings', 'fa-pinterest-p', 'contact-settings', '*', '2020-04-06 11:26:03', '2023-12-29 14:35:22'),
(33, 0, 55, 'Notification Messages', 'fa-bell', 'notification-messages', '*', '2020-04-07 07:50:56', '2024-01-18 17:20:18'),
(38, 0, 45, 'Complaints', 'fa-bars', NULL, '*', '2020-09-07 07:58:36', '2024-01-18 17:20:18'),
(39, 0, 57, 'User Types', 'fa-bars', 'user-types', '*', '2020-09-27 10:17:53', '2024-01-18 17:20:18'),
(40, 45, 15, 'Customer Wallet Histories', 'fa-cc-mastercard', 'customer-wallet-histories', '*', '2020-09-27 11:41:29', '2024-01-18 17:20:18'),
(41, 0, 18, 'Drivers', 'fa-male', NULL, '*', '2020-10-01 22:10:55', '2024-01-18 17:20:18'),
(42, 41, 21, 'Driver Wallet Histories', 'fa-bars', 'driver-wallet-histories', '*', '2020-10-01 22:11:53', '2024-01-18 17:20:18'),
(43, 41, 23, 'Driver Earnings', 'fa-bars', 'driver-earnings', '*', '2020-10-01 22:12:25', '2024-01-18 17:20:18'),
(44, 41, 24, 'Driver Withdrawals', 'fa-bars', 'driver-withdrawals', '*', '2020-10-01 22:14:36', '2024-01-18 17:20:18'),
(45, 0, 12, 'Customers', 'fa-group', NULL, NULL, '2020-10-01 22:37:39', '2024-01-18 17:20:18'),
(46, 41, 25, 'Driver Bank Details', 'fa-bars', 'driver-bank-kyc-details', '*', '2020-10-02 03:08:01', '2024-01-18 17:20:18'),
(47, 41, 27, 'Driver Tutorials', 'fa-bars', 'driver-tutorials', '*', '2020-10-04 06:17:55', '2024-01-18 17:20:18'),
(48, 62, 30, 'Trips', 'fa-bars', 'trips', '*', '2020-10-14 23:02:27', '2024-01-18 17:20:18'),
(49, 62, 34, 'Booking Statuses', 'fa-bars', 'booking-statuses', '*', '2020-10-15 07:05:00', '2024-01-18 17:20:18'),
(55, 74, 51, 'Payment Types', 'fa-bars', 'payment-types', '*', '2020-11-25 05:54:07', '2024-01-18 17:20:18'),
(61, 45, 14, 'Customer Sos Contacts', 'fa-bars', 'customer-sos-contacts', '*', '2021-01-08 06:28:28', '2024-01-18 17:20:18'),
(62, 0, 29, 'Trips', 'fa-angle-double-right', NULL, '*', '2021-02-05 04:30:35', '2024-01-18 17:20:18'),
(63, 62, 31, 'Trip Types', 'fa-bars', 'trip-types', '*', '2021-02-05 04:31:29', '2024-01-18 17:20:18'),
(64, 65, 39, 'Daily Fare Managements', 'fa-dollar', 'daily-fare-managements', '*', '2021-02-12 07:37:37', '2024-01-18 17:20:18'),
(65, 0, 38, 'Fare Managements', 'fa-dollar', NULL, '*', '2021-02-12 07:38:09', '2024-01-18 17:20:18'),
(66, 65, 42, 'Outstation Fare Managements', 'fa-dollar', 'outstation-fare-managements', '*', '2021-02-12 08:18:55', '2024-01-18 17:20:18'),
(67, 65, 40, 'Packages', 'fa-dollar', 'packages', '*', '2021-02-12 08:22:15', '2024-01-18 17:20:18'),
(68, 65, 41, 'Rental Fare Managements', 'fa-dollar', 'rental-fare-managements', '*', '2021-02-12 08:23:42', '2024-01-18 17:20:18'),
(69, 62, 36, 'Trip Request Statuses', 'fa-arrow-right', 'trip-request-statuses', '*', '2021-02-26 10:09:00', '2024-01-18 17:20:18'),
(70, 62, 35, 'Driver Trip Requests', 'fa-bars', 'driver-trip-requests', '*', '2021-02-26 10:45:56', '2024-01-18 17:20:18'),
(73, 74, 52, 'Payment histories', 'fa-bars', 'payment-histories', '*', '2021-03-02 08:01:45', '2024-01-18 17:20:18'),
(74, 0, 49, 'Payments', 'fa-dollar', NULL, '*', '2021-03-02 08:02:17', '2024-01-18 17:20:18'),
(75, 62, 33, 'Trip Requests', 'fa-arrow-circle-right', 'trip-requests', '*', '2021-03-02 08:05:43', '2024-01-18 17:20:18'),
(76, 0, 60, 'Status', 'fa-arrow-circle-right', 'statuses', '*', '2021-03-06 02:50:54', '2024-01-18 17:20:18'),
(78, 41, 26, 'Driver Recharges', 'fa-money', 'driver_recharges', NULL, '2021-03-23 17:27:58', '2024-01-18 17:20:18'),
(79, 45, 16, 'Customer Favourites', 'fa-bars', 'customer-favourites', '*', '2021-03-29 09:51:11', '2024-01-18 17:20:18'),
(80, 62, 32, 'Trip Sub Types', 'fa-bars', 'trip-sub-types', '*', '2021-04-12 04:25:47', '2024-01-18 17:20:18'),
(81, 65, 43, 'Delivery Fare Management', 'fa-bars', 'delivery-fare-managements', '*', '2021-04-12 09:58:56', '2024-01-18 17:20:18'),
(85, 62, 37, 'Stops', 'fa-arrow-right', 'stops', '*', '2021-06-27 15:07:52', '2024-01-18 17:20:18'),
(87, 41, 22, 'Driver Tips', 'fa-money', 'driver_tips', NULL, '2022-06-26 10:38:04', '2024-01-18 17:20:18'),
(88, 0, 10, 'Zones', 'fa-map-marker', 'zones', NULL, '2022-06-27 14:08:57', '2023-12-29 14:35:22'),
(98, 9, 7, 'Shared Trip Setting', 'fa-users', 'shared-trip-settings', NULL, '2022-12-29 12:07:28', '2023-12-29 14:35:22'),
(100, 9, 8, 'Vehicle Slug', 'fa-car', 'vehicle-slug', NULL, '2023-03-11 12:43:36', '2023-12-29 14:35:22'),
(101, 9, 3, 'App Versions', 'fa-angle-double-right', 'app_versions', '*', '2023-03-21 18:09:19', '2023-03-31 15:37:59'),
(103, 0, 9, 'Dispatch Panel', 'fa-desktop', 'dispatch_panel', NULL, '2023-12-29 14:33:00', '2023-12-29 14:35:22'),
(104, 0, 17, 'Livechat', 'fa-wechat', 'live_chat', NULL, '2023-12-29 14:34:37', '2024-01-18 17:20:18'),
(105, 65, 44, 'Shared Fare Managements', 'fa-money', 'shared-fare-managements', NULL, '2023-12-29 14:36:52', '2024-01-18 17:20:18');

-- --------------------------------------------------------

--
-- Table structure for table `admin_operation_log`
--

CREATE TABLE `admin_operation_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `path` varchar(255) NOT NULL,
  `method` varchar(10) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `input` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_operation_log`
--

INSERT INTO `admin_operation_log` (`id`, `user_id`, `path`, `method`, `ip`, `input`, `created_at`, `updated_at`) VALUES
(1, 1, 'admin/customers', 'GET', '106.51.150.164', '[]', '2024-01-19 15:38:34', '2024-01-19 15:38:34'),
(2, 1, 'admin/customers', 'GET', '106.51.150.164', '[]', '2024-01-19 15:40:28', '2024-01-19 15:40:28'),
(3, 1, 'admin/customers', 'GET', '106.51.150.164', '[]', '2024-01-19 15:49:04', '2024-01-19 15:49:04'),
(4, 1, 'admin', 'GET', '106.51.150.164', '[]', '2024-01-19 15:52:29', '2024-01-19 15:52:29'),
(5, 1, 'admin', 'GET', '106.51.150.164', '[]', '2024-01-19 15:52:52', '2024-01-19 15:52:52'),
(6, 1, 'admin/customers', 'GET', '106.51.150.164', '[]', '2024-01-19 15:52:58', '2024-01-19 15:52:58'),
(7, 1, 'admin/drivers', 'GET', '106.51.150.164', '[]', '2024-01-19 15:53:05', '2024-01-19 15:53:05'),
(8, 1, 'admin/driver-vehicles', 'GET', '106.51.150.164', '[]', '2024-01-19 15:53:09', '2024-01-19 15:53:09'),
(9, 1, 'admin/driver-wallet-histories', 'GET', '106.51.150.164', '[]', '2024-01-19 15:53:13', '2024-01-19 15:53:13'),
(10, 1, 'admin/zones', 'GET', '106.51.150.164', '[]', '2024-01-19 15:54:14', '2024-01-19 15:54:14'),
(11, 1, 'admin/view_zones/99', 'GET', '106.51.150.164', '{\"_pjax\":\"#pjax-container\"}', '2024-01-19 15:54:21', '2024-01-19 15:54:21'),
(12, 1, 'admin/view_zones/99', 'GET', '106.51.150.164', '[]', '2024-01-19 15:54:22', '2024-01-19 15:54:22'),
(13, 1, 'admin', 'GET', '106.51.150.164', '[]', '2024-01-19 15:54:43', '2024-01-19 15:54:43'),
(14, 1, 'admin/zones', 'GET', '106.51.150.164', '[]', '2024-01-19 15:54:49', '2024-01-19 15:54:49'),
(15, 1, 'admin/zones', 'GET', '106.51.150.164', '[]', '2024-01-19 15:55:08', '2024-01-19 15:55:08'),
(16, 1, 'admin', 'GET', '5.191.119.34', '[]', '2024-01-19 15:57:29', '2024-01-19 15:57:29'),
(17, 1, 'admin', 'GET', '5.191.119.34', '[]', '2024-01-19 15:57:37', '2024-01-19 15:57:37'),
(18, 1, 'admin/payment-methods', 'GET', '5.191.119.34', '[]', '2024-01-19 15:57:45', '2024-01-19 15:57:45'),
(19, 1, 'admin/trip-requests', 'GET', '106.51.150.164', '[]', '2024-01-19 16:02:20', '2024-01-19 16:02:20'),
(20, 1, 'admin', 'GET', '5.191.121.40', '[]', '2024-01-19 16:04:04', '2024-01-19 16:04:04'),
(21, 1, 'admin', 'GET', '5.191.121.40', '[]', '2024-01-19 16:04:04', '2024-01-19 16:04:04'),
(22, 1, 'admin/dispatch_panel', 'GET', '5.191.121.40', '[]', '2024-01-19 16:04:10', '2024-01-19 16:04:10'),
(23, 1, 'admin/customers', 'GET', '106.51.150.164', '[]', '2024-01-19 16:06:39', '2024-01-19 16:06:39'),
(24, 1, 'admin', 'GET', '2409:40f4:1008:32af:8530:5e23:9fc9:eb1d', '[]', '2024-01-19 16:10:33', '2024-01-19 16:10:33'),
(25, 1, 'admin/customers', 'GET', '2409:40f4:1008:32af:8530:5e23:9fc9:eb1d', '[]', '2024-01-19 16:10:44', '2024-01-19 16:10:44'),
(26, 1, 'admin/customers', 'GET', '2409:40f4:1008:32af:8530:5e23:9fc9:eb1d', '[]', '2024-01-19 16:23:04', '2024-01-19 16:23:04'),
(27, 1, 'admin/drivers', 'GET', '2409:40f4:1008:32af:8530:5e23:9fc9:eb1d', '[]', '2024-01-19 16:38:05', '2024-01-19 16:38:05'),
(28, 1, 'admin', 'GET', '106.51.150.164', '[]', '2024-01-19 17:00:29', '2024-01-19 17:00:29'),
(29, 1, 'admin/zones', 'GET', '106.51.150.164', '[]', '2024-01-19 17:00:37', '2024-01-19 17:00:37'),
(30, 1, 'admin/zones', 'GET', '106.51.150.164', '[]', '2024-01-19 17:00:42', '2024-01-19 17:00:42'),
(31, 1, 'admin/zones', 'GET', '106.51.150.164', '[]', '2024-01-19 17:10:13', '2024-01-19 17:10:13'),
(32, 1, 'admin/complaint-sub-categories', 'GET', '106.51.150.164', '[]', '2024-01-19 17:10:23', '2024-01-19 17:10:23'),
(33, 1, 'admin/promo-codes', 'GET', '106.51.150.164', '[]', '2024-01-19 17:10:28', '2024-01-19 17:10:28'),
(34, 1, 'admin/cancellation-reasons', 'GET', '106.51.150.164', '[]', '2024-01-19 17:10:36', '2024-01-19 17:10:36'),
(35, 1, 'admin/notification-messages', 'GET', '106.51.150.164', '[]', '2024-01-19 17:10:40', '2024-01-19 17:10:40'),
(36, 1, 'admin/tax-lists', 'GET', '106.51.150.164', '[]', '2024-01-19 17:10:46', '2024-01-19 17:10:46'),
(37, 1, 'admin/promo-codes', 'GET', '106.51.150.164', '[]', '2024-01-19 17:10:53', '2024-01-19 17:10:53'),
(38, 1, 'admin/faqs', 'GET', '106.51.150.164', '[]', '2024-01-19 17:11:04', '2024-01-19 17:11:04'),
(39, 1, 'admin/privacy-policies', 'GET', '106.51.150.164', '[]', '2024-01-19 17:11:09', '2024-01-19 17:11:09'),
(40, 1, 'admin/app_versions', 'GET', '106.51.150.164', '[]', '2024-01-19 17:11:13', '2024-01-19 17:11:13'),
(41, 1, 'admin/app-settings', 'GET', '106.51.150.164', '[]', '2024-01-19 17:11:17', '2024-01-19 17:11:17'),
(42, 1, 'admin/trip-settings', 'GET', '106.51.150.164', '[]', '2024-01-19 17:11:19', '2024-01-19 17:11:19'),
(43, 1, 'admin/contact-settings', 'GET', '106.51.150.164', '[]', '2024-01-19 17:11:22', '2024-01-19 17:11:22'),
(44, 1, 'admin/shared-trip-settings', 'GET', '106.51.150.164', '[]', '2024-01-19 17:11:25', '2024-01-19 17:11:25'),
(45, 1, 'admin/notification-messages', 'GET', '106.51.150.164', '[]', '2024-01-19 17:25:07', '2024-01-19 17:25:07'),
(46, 1, 'admin/promo-codes', 'GET', '106.51.150.164', '[]', '2024-01-19 17:25:14', '2024-01-19 17:25:14'),
(47, 1, 'admin', 'GET', '103.48.181.21', '[]', '2024-01-19 18:02:24', '2024-01-19 18:02:24'),
(48, 1, 'admin/zones', 'GET', '103.48.181.21', '[]', '2024-01-19 18:02:30', '2024-01-19 18:02:30'),
(49, 1, 'admin/app_versions', 'GET', '103.48.181.21', '[]', '2024-01-19 18:02:51', '2024-01-19 18:02:51'),
(50, 1, 'admin/app-settings', 'GET', '103.48.181.21', '[]', '2024-01-19 18:03:00', '2024-01-19 18:03:00'),
(51, 1, 'admin/complaint-categories', 'GET', '2407:f440:2000:7028:10b1:2961:a9cb:fd2e', '[]', '2024-01-19 18:07:25', '2024-01-19 18:07:25'),
(52, 1, 'admin/complaint-sub-categories', 'GET', '2407:f440:2000:7028:10b1:2961:a9cb:fd2e', '[]', '2024-01-19 18:08:21', '2024-01-19 18:08:21'),
(53, 1, 'admin/complaint-categories', 'GET', '2407:f440:2000:7028:10b1:2961:a9cb:fd2e', '[]', '2024-01-19 18:09:02', '2024-01-19 18:09:02'),
(54, 1, 'admin/contact-settings', 'GET', '2407:f440:2000:7028:10b1:2961:a9cb:fd2e', '[]', '2024-01-19 18:09:47', '2024-01-19 18:09:47'),
(55, 1, 'admin', 'GET', '183.82.105.57', '[]', '2024-01-19 18:26:16', '2024-01-19 18:26:16'),
(56, 1, 'admin/zones', 'GET', '183.82.105.57', '[]', '2024-01-19 18:30:41', '2024-01-19 18:30:41'),
(57, 1, 'admin/auth/logout', 'GET', '183.82.105.57', '[]', '2024-01-19 18:30:45', '2024-01-19 18:30:45'),
(58, 1, 'admin', 'GET', '106.51.150.164', '[]', '2024-01-19 19:26:08', '2024-01-19 19:26:08'),
(59, 1, 'admin/zones', 'GET', '106.51.150.164', '[]', '2024-01-19 19:26:11', '2024-01-19 19:26:11'),
(60, 1, 'admin/app_versions', 'GET', '106.51.150.164', '[]', '2024-01-19 19:26:16', '2024-01-19 19:26:16'),
(61, 1, 'admin/contact-settings', 'GET', '106.51.150.164', '[]', '2024-01-19 19:26:20', '2024-01-19 19:26:20'),
(62, 1, 'admin/customers', 'GET', '106.51.150.164', '[]', '2024-01-19 19:26:25', '2024-01-19 19:26:25'),
(63, 1, 'admin/daily-fare-managements', 'GET', '106.51.150.164', '[]', '2024-01-19 19:26:38', '2024-01-19 19:26:38'),
(64, 1, 'admin/vehicle-categories', 'GET', '106.51.150.164', '[]', '2024-01-19 19:26:46', '2024-01-19 19:26:46'),
(65, 1, 'admin/payment-histories', 'GET', '106.51.150.164', '[]', '2024-01-19 19:26:57', '2024-01-19 19:26:57'),
(66, 1, 'admin/trips', 'GET', '106.51.150.164', '[]', '2024-01-19 19:27:02', '2024-01-19 19:27:02'),
(67, 1, 'admin/drivers', 'GET', '106.51.150.164', '[]', '2024-01-19 19:27:09', '2024-01-19 19:27:09'),
(68, 1, 'admin/driver-vehicles', 'GET', '106.51.150.164', '[]', '2024-01-19 19:27:16', '2024-01-19 19:27:16'),
(69, 1, 'admin/driver-wallet-histories', 'GET', '106.51.150.164', '[]', '2024-01-19 19:27:23', '2024-01-19 19:27:23'),
(70, 1, 'admin/driver-earnings', 'GET', '106.51.150.164', '[]', '2024-01-19 19:27:30', '2024-01-19 19:27:30'),
(71, 1, 'admin/driver-withdrawals', 'GET', '106.51.150.164', '[]', '2024-01-19 19:27:33', '2024-01-19 19:27:33'),
(72, 1, 'admin/driver-bank-kyc-details', 'GET', '106.51.150.164', '[]', '2024-01-19 19:27:38', '2024-01-19 19:27:38'),
(73, 1, 'admin/driver_recharges', 'GET', '106.51.150.164', '[]', '2024-01-19 19:27:43', '2024-01-19 19:27:43'),
(74, 1, 'admin/driver-tutorials', 'GET', '106.51.150.164', '[]', '2024-01-19 19:29:43', '2024-01-19 19:29:43'),
(75, 1, 'admin/vehicle-categories', 'GET', '106.51.150.164', '[]', '2024-01-19 19:29:48', '2024-01-19 19:29:48'),
(76, 1, 'admin/promo-codes', 'GET', '106.51.150.164', '[]', '2024-01-19 19:29:52', '2024-01-19 19:29:52'),
(77, 1, 'admin/tax-lists', 'GET', '106.51.150.164', '[]', '2024-01-19 19:30:01', '2024-01-19 19:30:01'),
(78, 1, 'admin/notification-messages', 'GET', '106.51.150.164', '[]', '2024-01-19 19:30:05', '2024-01-19 19:30:05'),
(79, 1, 'admin', 'GET', '2407:f440:2000:7028:8d99:9574:79d5:3c4f', '[]', '2024-01-19 20:17:34', '2024-01-19 20:17:34'),
(80, 1, 'admin/customers', 'GET', '2407:f440:2000:7028:8d99:9574:79d5:3c4f', '[]', '2024-01-19 20:17:37', '2024-01-19 20:17:37'),
(81, 1, 'admin', 'GET', '2407:f440:2000:7028:b509:5c7f:42cf:9c9', '[]', '2024-01-19 22:09:55', '2024-01-19 22:09:55'),
(82, 1, 'admin/statuses', 'GET', '2407:f440:2000:7028:b509:5c7f:42cf:9c9', '[]', '2024-01-19 22:10:06', '2024-01-19 22:10:06'),
(83, 1, 'admin/customers', 'GET', '2407:f440:2000:7028:8d99:9574:79d5:3c4f', '[]', '2024-01-19 22:13:55', '2024-01-19 22:13:55'),
(84, 1, 'admin', 'GET', '39.62.110.220', '[]', '2024-01-19 23:44:34', '2024-01-19 23:44:34'),
(85, 1, 'admin/dispatch_panel', 'GET', '39.62.110.220', '[]', '2024-01-19 23:44:42', '2024-01-19 23:44:42'),
(86, 1, 'admin/user-types', 'GET', '39.62.110.220', '[]', '2024-01-19 23:44:55', '2024-01-19 23:44:55');

-- --------------------------------------------------------

--
-- Table structure for table `admin_permissions`
--

CREATE TABLE `admin_permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `http_method` varchar(255) DEFAULT NULL,
  `http_path` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_permissions`
--

INSERT INTO `admin_permissions` (`id`, `name`, `slug`, `http_method`, `http_path`, `created_at`, `updated_at`) VALUES
(1, 'All permission', '*', '', '*', NULL, NULL),
(2, 'Dashboard', 'dashboard', 'GET', '/', NULL, NULL),
(3, 'Login', 'auth.login', '', '/auth/login\r\n/auth/logout', NULL, NULL),
(4, 'User setting', 'auth.setting', 'GET,PUT', '/auth/setting', NULL, NULL),
(5, 'Auth management', 'auth.management', '', '/auth/roles\r\n/auth/permissions\r\n/auth/menu\r\n/auth/logs', NULL, NULL),
(6, 'Customers', 'customers', 'GET,POST,PUT,DELETE,PATCH,OPTIONS,HEAD', '/customers*', '2021-04-19 20:39:47', '2021-04-19 20:39:47'),
(7, 'ONKAR UG', 'GERMAN', 'HEAD', NULL, '2022-07-11 18:35:33', '2022-07-11 18:35:33'),
(8, 'Dispatch Panel', 'dispatch_panel', 'GET,POST,PUT,DELETE,PATCH,OPTIONS,HEAD', '/dispatch_panel*', '2022-12-29 09:48:32', '2022-12-29 09:48:32'),
(9, 'Admin Chat', 'admin_chat', 'GET,POST,PUT,DELETE,PATCH,OPTIONS', '/live_chat*', '2023-12-22 11:58:50', '2023-12-22 12:00:21');

-- --------------------------------------------------------

--
-- Table structure for table `admin_roles`
--

CREATE TABLE `admin_roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_roles`
--

INSERT INTO `admin_roles` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'administrator', '2020-04-02 02:49:21', '2020-04-02 02:49:21'),
(2, 'manager', 'manager', '2021-04-19 20:36:19', '2021-04-19 20:36:19'),
(3, 'Sub admin', 'sub_admin', '2022-12-29 09:50:37', '2022-12-29 09:50:37'),
(4, 'Chat Manager', 'chat_manager', '2023-12-22 12:01:11', '2023-12-22 12:01:11');

-- --------------------------------------------------------

--
-- Table structure for table `admin_role_menu`
--

CREATE TABLE `admin_role_menu` (
  `role_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_role_menu`
--

INSERT INTO `admin_role_menu` (`role_id`, `menu_id`, `created_at`, `updated_at`) VALUES
(1, 8, NULL, NULL),
(1, 9, NULL, NULL),
(1, 10, NULL, NULL),
(1, 12, NULL, NULL),
(1, 16, NULL, NULL),
(1, 18, NULL, NULL),
(1, 19, NULL, NULL),
(1, 20, NULL, NULL),
(1, 21, NULL, NULL),
(1, 23, NULL, NULL),
(1, 24, NULL, NULL),
(1, 25, NULL, NULL),
(1, 27, NULL, NULL),
(1, 28, NULL, NULL),
(1, 29, NULL, NULL),
(1, 31, NULL, NULL),
(1, 32, NULL, NULL),
(1, 33, NULL, NULL),
(1, 38, NULL, NULL),
(1, 39, NULL, NULL),
(1, 40, NULL, NULL),
(1, 41, NULL, NULL),
(1, 42, NULL, NULL),
(1, 43, NULL, NULL),
(1, 44, NULL, NULL),
(1, 45, NULL, NULL),
(1, 46, NULL, NULL),
(1, 47, NULL, NULL),
(1, 48, NULL, NULL),
(1, 49, NULL, NULL),
(1, 50, NULL, NULL),
(1, 51, NULL, NULL),
(1, 52, NULL, NULL),
(1, 55, NULL, NULL),
(1, 57, NULL, NULL),
(1, 58, NULL, NULL),
(1, 61, NULL, NULL),
(1, 62, NULL, NULL),
(1, 63, NULL, NULL),
(1, 64, NULL, NULL),
(1, 65, NULL, NULL),
(1, 66, NULL, NULL),
(1, 67, NULL, NULL),
(1, 68, NULL, NULL),
(1, 69, NULL, NULL),
(1, 70, NULL, NULL),
(1, 73, NULL, NULL),
(1, 74, NULL, NULL),
(1, 75, NULL, NULL),
(1, 76, NULL, NULL),
(1, 78, NULL, NULL),
(1, 79, NULL, NULL),
(1, 80, NULL, NULL),
(1, 81, NULL, NULL),
(2, 28, NULL, NULL),
(1, 1, NULL, NULL),
(2, 1, NULL, NULL),
(2, 45, NULL, NULL),
(1, 85, NULL, NULL),
(1, 87, NULL, NULL),
(1, 88, NULL, NULL),
(1, 2, NULL, NULL),
(1, 8, NULL, NULL),
(1, 9, NULL, NULL),
(1, 10, NULL, NULL),
(1, 12, NULL, NULL),
(1, 16, NULL, NULL),
(1, 18, NULL, NULL),
(1, 19, NULL, NULL),
(1, 20, NULL, NULL),
(1, 21, NULL, NULL),
(1, 23, NULL, NULL),
(1, 24, NULL, NULL),
(1, 25, NULL, NULL),
(1, 27, NULL, NULL),
(1, 28, NULL, NULL),
(1, 29, NULL, NULL),
(1, 31, NULL, NULL),
(1, 32, NULL, NULL),
(1, 33, NULL, NULL),
(1, 38, NULL, NULL),
(1, 39, NULL, NULL),
(1, 40, NULL, NULL),
(1, 41, NULL, NULL),
(1, 42, NULL, NULL),
(1, 43, NULL, NULL),
(1, 44, NULL, NULL),
(1, 45, NULL, NULL),
(1, 46, NULL, NULL),
(1, 47, NULL, NULL),
(1, 48, NULL, NULL),
(1, 49, NULL, NULL),
(1, 50, NULL, NULL),
(1, 51, NULL, NULL),
(1, 52, NULL, NULL),
(1, 53, NULL, NULL),
(1, 55, NULL, NULL),
(1, 57, NULL, NULL),
(1, 58, NULL, NULL),
(1, 61, NULL, NULL),
(1, 62, NULL, NULL),
(1, 63, NULL, NULL),
(1, 64, NULL, NULL),
(1, 65, NULL, NULL),
(1, 66, NULL, NULL),
(1, 67, NULL, NULL),
(1, 68, NULL, NULL),
(1, 69, NULL, NULL),
(1, 70, NULL, NULL),
(1, 73, NULL, NULL),
(1, 74, NULL, NULL),
(1, 75, NULL, NULL),
(1, 76, NULL, NULL),
(1, 78, NULL, NULL),
(1, 79, NULL, NULL),
(1, 80, NULL, NULL),
(1, 81, NULL, NULL),
(2, 28, NULL, NULL),
(1, 1, NULL, NULL),
(2, 1, NULL, NULL),
(2, 45, NULL, NULL),
(1, 85, NULL, NULL),
(1, 87, NULL, NULL),
(1, 88, NULL, NULL),
(1, 98, NULL, NULL),
(1, 101, NULL, NULL),
(1, 104, NULL, NULL),
(1, 105, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admin_role_permissions`
--

CREATE TABLE `admin_role_permissions` (
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_role_permissions`
--

INSERT INTO `admin_role_permissions` (`role_id`, `permission_id`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL),
(2, 2, NULL, NULL),
(2, 3, NULL, NULL),
(2, 5, NULL, NULL),
(2, 6, NULL, NULL),
(1, 1, NULL, NULL),
(2, 2, NULL, NULL),
(2, 3, NULL, NULL),
(2, 5, NULL, NULL),
(2, 6, NULL, NULL),
(3, 2, NULL, NULL),
(3, 8, NULL, NULL),
(4, 2, NULL, NULL),
(4, 9, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admin_role_users`
--

CREATE TABLE `admin_role_users` (
  `role_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_role_users`
--

INSERT INTO `admin_role_users` (`role_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL),
(1, 1, NULL, NULL),
(1, 2, NULL, NULL),
(2, 3, NULL, NULL),
(2, 4, NULL, NULL),
(1, 5, NULL, NULL),
(2, 6, NULL, NULL),
(2, 7, NULL, NULL),
(4, 8, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(190) NOT NULL,
  `password` varchar(60) NOT NULL,
  `name` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`, `name`, `avatar`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$cFYR6LpTr9E8KL4KdJZfE./gR/5v.6KGSyjpT12cAOqbayZdQ3Kee', 'Admin', 'image/CAB2U LOGO.png', 'JQUdT3HtcqkGS0XiCMtFoMJUg6XrFcIWeeLRp7x4vqsrQtDOnQExBTkD0y7x', '2020-04-02 02:49:21', '2023-01-26 17:18:57'),
(8, 'chatmanager', '$2y$10$advq.a.sFTB44A6qp5yO8ekHQWi5h.qF1KDejJI.g6xt.BAw9P1Aa', 'chat Admin', 'image/flower.jpeg', 'S7lHYG7tHMC14HtbgMHDvFznvoPDkkrgJCfGU9sXJ2Xwqc6K2shsGSgGzZhS', '2023-12-22 12:03:16', '2023-12-22 12:03:16');

-- --------------------------------------------------------

--
-- Table structure for table `admin_user_permissions`
--

CREATE TABLE `admin_user_permissions` (
  `user_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_user_permissions`
--

INSERT INTO `admin_user_permissions` (`user_id`, `permission_id`, `created_at`, `updated_at`) VALUES
(2, 1, NULL, NULL),
(4, 3, NULL, NULL),
(5, 1, NULL, NULL),
(6, 2, NULL, NULL),
(6, 3, NULL, NULL),
(6, 6, NULL, NULL),
(7, 2, NULL, NULL),
(7, 3, NULL, NULL),
(7, 4, NULL, NULL),
(7, 5, NULL, NULL),
(7, 6, NULL, NULL),
(2, 1, NULL, NULL),
(4, 3, NULL, NULL),
(5, 1, NULL, NULL),
(6, 2, NULL, NULL),
(6, 3, NULL, NULL),
(6, 6, NULL, NULL),
(7, 2, NULL, NULL),
(7, 3, NULL, NULL),
(7, 4, NULL, NULL),
(7, 5, NULL, NULL),
(7, 6, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `app_settings`
--

CREATE TABLE `app_settings` (
  `id` int(11) NOT NULL,
  `app_name` varchar(250) NOT NULL,
  `logo` varchar(250) NOT NULL,
  `app_version` varchar(10) NOT NULL,
  `driver_app_version` varchar(10) NOT NULL,
  `default_currency` varchar(100) NOT NULL,
  `default_country` varchar(250) DEFAULT NULL,
  `phone_code` varchar(10) DEFAULT NULL,
  `default_currency_symbol` varchar(10) NOT NULL,
  `currency_short_code` varchar(10) NOT NULL,
  `about_us` text NOT NULL,
  `about_us_ar` text DEFAULT NULL,
  `referral_amount` double DEFAULT NULL,
  `driver_referral_amount` double NOT NULL,
  `language_status` int(11) NOT NULL DEFAULT 1,
  `default_language` varchar(100) NOT NULL DEFAULT 'en',
  `subscription_status` int(11) NOT NULL DEFAULT 1,
  `polyline_status` int(11) NOT NULL DEFAULT 1,
  `driver_trip_time` double NOT NULL DEFAULT 10,
  `capital_lat` float NOT NULL DEFAULT 0,
  `capital_lng` float NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `app_settings`
--

INSERT INTO `app_settings` (`id`, `app_name`, `logo`, `app_version`, `driver_app_version`, `default_currency`, `default_country`, `phone_code`, `default_currency_symbol`, `currency_short_code`, `about_us`, `about_us_ar`, `referral_amount`, `driver_referral_amount`, `language_status`, `default_language`, `subscription_status`, `polyline_status`, `driver_trip_time`, `capital_lat`, `capital_lng`, `created_at`, `updated_at`) VALUES
(1, 'Cab2U', 'image/e84f15c6eabaa788cfdf0a23b1e2e851.png', '3.0', '1.0', 'INR', 'India', '+91', '₹', 'INR', 'You can now get an advanced app for taxi booking  for Android and iOS. The application works on real time and has integrated Mobile Payment feature which ensures that the payment for signed up drivers can be automatically taken care of. There are two mobile applications that come with the Taxi Booking app', 'يمكنك الآن الحصول على تطبيق متقدم لحجز سيارات الأجرة لنظامي Android و iOS. يعمل التطبيق في الوقت الفعلي ويحتوي على ميزة الدفع عبر الهاتف المحمول المتكاملة التي تضمن إمكانية دفع رسوم السائقين المسجلين تلقائيًا. هناك نوعان من تطبيقات الهاتف المحمول التي تأتي مع تطبيق Taxi Booking', 100, 100, 1, 'en', 1, 1, 10, 9.92407, 78.0932, '2022-12-18 11:10:55', '2023-10-18 18:43:29');

-- --------------------------------------------------------

--
-- Table structure for table `app_versions`
--

CREATE TABLE `app_versions` (
  `id` int(11) NOT NULL,
  `platform` int(11) NOT NULL,
  `version_number` varchar(10) NOT NULL,
  `version_code` varchar(10) NOT NULL,
  `date_of_upload` date NOT NULL,
  `date_of_approved` date NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `app_versions`
--

INSERT INTO `app_versions` (`id`, `platform`, `version_number`, `version_code`, `date_of_upload`, `date_of_approved`, `created_at`, `updated_at`) VALUES
(1, 1, '1.0', '1', '2023-03-21', '2023-03-21', '2023-03-21 18:28:40', '2023-11-09 10:28:38'),
(2, 2, '9', '123', '2023-10-20', '2023-10-21', '2023-10-21 18:03:17', '2023-10-21 18:03:17');

-- --------------------------------------------------------

--
-- Table structure for table `booking_statuses`
--

CREATE TABLE `booking_statuses` (
  `id` int(11) NOT NULL,
  `status_name` varchar(100) NOT NULL,
  `customer_status_name` varchar(250) NOT NULL,
  `status_name_ar` varchar(100) DEFAULT NULL,
  `customer_status_name_ar` varchar(250) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `booking_statuses`
--

INSERT INTO `booking_statuses` (`id`, `status_name`, `customer_status_name`, `status_name_ar`, `customer_status_name_ar`, `created_at`, `updated_at`) VALUES
(1, 'Accepted', 'Your ride on the way', 'وافقت', 'رحلتك على الطريق', '2020-10-13 15:10:16', '2021-03-06 03:35:43'),
(2, 'At Point', 'Driver reached your location', 'عند نقطة', 'وصل السائق إلى موقعك', '2020-10-13 15:10:16', '2021-03-06 03:36:23'),
(3, 'Start Trip', 'Your trip started,  Enjoy your doorstep pick-up on-time', 'ابدأ الرحلة', 'بدأت رحلتك ، واستمتع باستلامك عند عتبة بابك في الوقت المحدد', '2020-10-13 15:11:58', '2023-06-30 14:53:47'),
(4, 'End Trip', 'Reached your drop point. Hope you enjoy the trip.', 'نهاية الرحلة', 'وصلت إلى نقطة إسقاطك. أتمنى أن تستمتع بالسفر.', '2020-10-13 15:11:58', '2023-06-30 14:54:17'),
(5, 'Completed', 'The trip was completed. We are waiting for your other booking', 'مكتمل', 'اكتملت الرحلة. نحن ننتظر حجزك الآخر', '2020-10-13 15:13:14', '2023-06-30 14:54:53'),
(6, 'Cancelled By Customer', 'Your trip was cancelled', 'ألغى العميل', 'تم إلغاء رحلتك', '2020-10-13 15:12:32', '2021-03-06 03:40:20'),
(7, 'Cancelled By Driver', 'Your trip was cancelled by this driver, sorry for your inconvenience', 'ألغى السائق', 'تم إلغاء رحلتك من قبل هذا السائق ، نأسف للإزعاج', '2020-10-13 15:12:32', '2021-03-06 03:41:56');

-- --------------------------------------------------------

--
-- Table structure for table `cancellation_reasons`
--

CREATE TABLE `cancellation_reasons` (
  `id` int(11) NOT NULL,
  `reason` varchar(250) NOT NULL,
  `reason_ar` varchar(250) DEFAULT NULL,
  `type` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cancellation_reasons`
--

INSERT INTO `cancellation_reasons` (`id`, `reason`, `reason_ar`, `type`, `created_at`, `updated_at`) VALUES
(1, 'Change in our destination place', 'تغيير في مكان وجهتنا', 1, '2021-03-01 10:14:28', '2021-03-06 03:44:28'),
(2, 'Want to change vehicle', 'تريد تغيير السيارة', 1, '2021-03-01 12:50:47', '2021-03-06 03:44:47'),
(3, 'No need this driver', 'لا حاجة لهذا السائق', 1, '2021-03-01 12:51:29', '2021-03-06 03:45:04'),
(4, 'Vehicle is not good', 'المركبة غير نظيفة', 1, '2021-03-19 12:51:25', '2021-03-19 12:51:25'),
(5, 'Customer didn\'t pick phone call', 'الزبون لم يختار مكالمة هاتفية', 2, '2021-06-03 12:48:47', '2021-06-03 12:48:47'),
(6, 'Long distance drop location', 'موقع إسقاط لمسافات طويلة', 2, '2021-06-03 12:49:51', '2021-06-03 12:49:51'),
(7, 'Bad location', 'موقع سيء', 2, '2021-06-03 12:50:48', '2021-06-03 12:50:48'),
(8, 'Some personal issues', 'بعض القضايا الشخصية', 2, '2021-06-03 12:51:28', '2021-06-03 12:51:28'),
(9, 'hi', NULL, 2, '2023-12-24 03:56:11', '2023-12-24 03:56:11');

-- --------------------------------------------------------

--
-- Table structure for table `cancellation_settings`
--

CREATE TABLE `cancellation_settings` (
  `id` int(11) NOT NULL,
  `no_of_free_cancellation` int(11) NOT NULL,
  `cancellation_charge` double NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` int(11) NOT NULL,
  `trip_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `complaint_category` int(11) NOT NULL,
  `complaint_sub_category` int(11) NOT NULL,
  `subject` varchar(250) NOT NULL,
  `description` text DEFAULT NULL,
  `status` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `complaint_categories`
--

CREATE TABLE `complaint_categories` (
  `id` int(11) NOT NULL,
  `complaint_category_name` varchar(250) NOT NULL,
  `complaint_category_name_ar` varchar(250) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `complaint_categories`
--

INSERT INTO `complaint_categories` (`id`, `complaint_category_name`, `complaint_category_name_ar`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Payment Related', NULL, 1, '2023-03-11 12:18:54', '2023-03-11 12:18:54'),
(2, 'Driver Related', NULL, 1, '2023-03-11 12:19:11', '2023-03-11 12:19:11'),
(3, 'Ride Related', NULL, 1, '2023-03-11 12:19:29', '2023-03-11 12:19:29'),
(4, 'Safety', NULL, 1, '2023-03-11 12:19:43', '2023-03-11 12:19:43'),
(5, 'Drivers late', 'gh', 1, '2023-11-09 10:30:21', '2023-11-09 10:30:21'),
(6, 'payment issues', 'gh', 1, '2023-11-09 12:14:51', '2023-11-09 12:14:51');

-- --------------------------------------------------------

--
-- Table structure for table `complaint_sub_categories`
--

CREATE TABLE `complaint_sub_categories` (
  `id` int(11) NOT NULL,
  `complaint_category` int(11) NOT NULL,
  `complaint_sub_category_name` varchar(250) NOT NULL,
  `short_description` varchar(250) DEFAULT NULL,
  `complaint_sub_category_name_ar` varchar(250) DEFAULT NULL,
  `short_description_ar` varchar(250) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `complaint_sub_categories`
--

INSERT INTO `complaint_sub_categories` (`id`, `complaint_category`, `complaint_sub_category_name`, `short_description`, `complaint_sub_category_name_ar`, `short_description_ar`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'I need a copy of my invoice', 'You need a copy of invoice  to send email address details. Please inform us.', NULL, NULL, 1, '2023-03-11 12:20:33', '2023-03-11 13:58:41'),
(2, 1, 'I have issues with recharging my Wallet Balance', 'After recharging your wallet account, we will try  to fetch your updated balance again when you are redirected to the app.After your wallet recharges, please recheck the status of your transaction and restart the app.', NULL, NULL, 1, '2023-03-11 12:21:22', '2023-03-11 13:20:54'),
(3, 1, 'I was not able to change the payment method during the ride', 'You are not allowed to change your payment method once the ride starts as we will block the ongoing ride amount in your wallet beforehand.', NULL, NULL, 1, '2023-03-11 12:21:55', '2023-03-11 13:24:27'),
(4, 1, 'I paid extra cash to my driver', 'Please inform what happened to your ride  details and clarify it.', NULL, NULL, 1, '2023-03-11 12:22:19', '2023-03-11 13:26:05'),
(5, 1, 'My ride fare is higher than  the estimated fare', 'Estimated fare range for your ride  fare may be higher than if you exceed the number of kilometers or number of hours. An additional kilometer fare before you confirm a booking.', NULL, NULL, 1, '2023-03-11 12:26:48', '2023-03-11 13:28:48'),
(6, 1, 'I was charged without taking a ride', 'Our customer do not share OTP with the driver until they have boarded the cab.', NULL, NULL, 1, '2023-03-11 12:27:08', '2023-03-11 13:29:59'),
(7, 1, 'I did not get my offer discount', 'If you applied a coupon and it\'s not showing in your invoice, please call us. Some offers might give you a discount on the next ride. Please refer to the terms&conditions  of the offers.', NULL, NULL, 1, '2023-03-11 12:27:26', '2023-03-11 13:32:11'),
(8, 1, 'I was charged toll/parking fee incorrectly', 'Please note that toll/parking charges if applicable on your ride are automatically added to your total ride fare. You can always check the invoice  sent on your registered email ID for toll/parking charges.', NULL, NULL, 1, '2023-03-11 12:28:02', '2023-03-11 13:34:23'),
(9, 1, 'My debit/credit card/UPI ID/ Wallet payment failed', 'When your payment fails through your selected payment method we request you to change your payment method and retry.', NULL, NULL, 1, '2023-03-11 12:28:35', '2023-03-11 13:35:42'),
(10, 1, 'I was charged twice on my credit card/debit card / UPI id/ Wallet', 'We are sorry for the trouble. we assure you that a refund will be initiated automatically and you will be receiving a communication for the same within 7 working days.', NULL, NULL, 1, '2023-03-11 12:29:18', '2023-03-11 13:37:24'),
(11, 1, 'I was not able to pay online for this ride', 'If you weren\'t able to pay during the ride, please ask your driver-Partner to check this app at the end of your trip. If the fare needs to be paid in cash, please pay the Driver-partner.', NULL, NULL, 1, '2023-03-11 12:29:39', '2023-03-11 13:39:18'),
(12, 2, 'My driver asked to cancel the ride and pay offline', 'We advise our customers to strictly take rides on the app to enjoy the benefits of the safety features provided while taking the ride. we are sorry for the inconvenience you had to face due to driver behaviour.', NULL, NULL, 1, '2023-03-11 12:30:10', '2023-03-11 13:41:13'),
(13, 2, 'My driver took a long or incorrect route', 'We advise our drivers to take the optimal route to the destination as suggested by google maps.', NULL, NULL, 1, '2023-03-11 12:30:33', '2023-03-11 13:42:03'),
(14, 2, 'My driver requested extra cash', 'In case the driver collected any extra cash for this ride, please inform us.', NULL, NULL, 1, '2023-03-11 12:30:55', '2023-03-11 13:42:52'),
(15, 2, 'My driver stopped the trip midway', 'We are always committed to offer you a safe, convenient and comfortable travel. we are very sorry to hear that your trip was not completed. Please call us.', NULL, NULL, 1, '2023-03-11 12:31:18', '2023-03-11 13:44:44'),
(16, 2, 'My driver was late for pickup', 'We are sorry  to hear that your pickup was delayed. Please let us details send it.', NULL, NULL, 1, '2023-03-11 12:31:53', '2023-03-11 13:45:44'),
(17, 2, 'A different driver came for pickup', 'Safety of all passengers is our priority .If the driver picking you up is different from the one allotted by for your ride,we request to let us know.', NULL, NULL, 1, '2023-03-11 12:32:14', '2023-03-11 13:47:04'),
(18, 2, 'I missed my flight/train', 'We regret for this inconvenience caused due to the cab delay in your trip. please send the details of your missed event and we will look into the issue.', NULL, NULL, 1, '2023-03-11 12:32:30', '2023-03-11 13:48:28'),
(19, 2, 'Other driver related issue?', 'We apologize for the inconvenience you had to face. Please send your feedback to take an approriate action against the driver.', NULL, NULL, 1, '2023-03-11 12:32:49', '2023-03-11 13:50:14'),
(20, 3, 'I had an issue with the cab quality', 'We instruct our driver partners to keep their cars clean, well maintained, and in excellent condition.If you feel that the cab quality wasn\'t upto your expectations, please let us know what went wrong.', NULL, NULL, 1, '2023-03-11 12:33:14', '2023-03-11 13:52:27'),
(21, 3, 'My vehicle broke down during the ride', 'We insist that our drive-partners keep the vehicles in optimum condition. We\'re really sorry that you had to face this inconvenience.', NULL, NULL, 1, '2023-03-11 12:33:34', '2023-03-11 13:53:31'),
(22, 3, 'I left a belonging in the vehicle', 'Sorry for the inconvenience caused. We request you to call the driver and arrange for a pickup of the belonging at a convenient location.', NULL, NULL, 1, '2023-03-11 12:33:57', '2023-03-11 13:54:38'),
(23, 4, 'I met  with an accident during the ride', 'We are very sorry to hear that you had to face such an unfortunate event. We hope that you are safe. Please call us for immediate assistance.', NULL, NULL, 1, '2023-03-11 12:34:39', '2023-03-11 13:56:00'),
(24, 4, 'I didn\'t feel safe during the ride', 'We are very sorry to hear that you felt unsafe during a trip. Your safety is of utmost importance to us and we can never compromise on that.', NULL, NULL, 1, '2023-03-11 12:35:34', '2023-03-11 13:57:18');

-- --------------------------------------------------------

--
-- Table structure for table `contact_settings`
--

CREATE TABLE `contact_settings` (
  `id` int(11) NOT NULL,
  `phone_number` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `address` text NOT NULL,
  `lat` varchar(250) NOT NULL,
  `lng` varchar(250) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_settings`
--

INSERT INTO `contact_settings` (`id`, `phone_number`, `email`, `address`, `lat`, `lng`, `created_at`, `updated_at`) VALUES
(1, '+91 9363671699', 'contact.menpani@gmail.com', 'No 29, Mullai Street, Nehru nagar, Palanganatham, Madurai - Tamilnadu', '9.8899873', '78.0818419', '2022-12-18 10:49:40', '2023-10-04 00:00:04'),
(2, '9080365973', 'nabeelkutty1@gmail.com', '122', '222', '222', '2023-10-04 00:02:51', '2023-10-04 00:02:51'),
(3, '9876543289', 'r@gmail.com', 'madurai kalavasal', '1', '1', '2023-11-09 11:59:50', '2023-11-09 11:59:50');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` int(11) NOT NULL,
  `phone_code` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_name` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `short_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_name_ar` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `short_name_ar` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `timezone` varchar(250) DEFAULT NULL,
  `capital_lat` varchar(250) NOT NULL,
  `capital_lng` varchar(250) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `first_name` varchar(250) NOT NULL,
  `last_name` varchar(250) DEFAULT NULL,
  `country_code` varchar(10) NOT NULL,
  `phone_number` varchar(250) NOT NULL,
  `phone_with_code` varchar(100) NOT NULL,
  `email` varchar(250) DEFAULT NULL,
  `email_verification_status` int(11) NOT NULL DEFAULT 0,
  `email_verification_code` varchar(250) DEFAULT NULL,
  `profile_picture` varchar(250) DEFAULT 'customers/avatar.png',
  `password` varchar(250) DEFAULT NULL,
  `fcm_token` text DEFAULT NULL,
  `status` int(11) NOT NULL,
  `wallet` double DEFAULT 0,
  `gender` int(11) DEFAULT 0,
  `referral_code` varchar(100) DEFAULT NULL,
  `overall_ratings` double NOT NULL DEFAULT 0,
  `no_of_ratings` int(11) NOT NULL DEFAULT 0,
  `refered_by` varchar(100) DEFAULT NULL,
  `current_sub_id` int(11) NOT NULL DEFAULT 0,
  `subscription_trips` int(11) NOT NULL DEFAULT 0,
  `sub_purchased_at` date DEFAULT NULL,
  `sub_expired_at` date DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_chat_messages`
--

CREATE TABLE `customer_chat_messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `receiver` int(11) NOT NULL,
  `message` longtext NOT NULL,
  `is_seen` int(11) NOT NULL DEFAULT 0,
  `file` varchar(191) DEFAULT NULL,
  `file_name` varchar(191) DEFAULT NULL,
  `is_admin` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_favourites`
--

CREATE TABLE `customer_favourites` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `address` varchar(250) NOT NULL,
  `lat` varchar(250) NOT NULL,
  `lng` varchar(250) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_offers`
--

CREATE TABLE `customer_offers` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL DEFAULT 0,
  `title` varchar(250) NOT NULL,
  `image` varchar(500) NOT NULL,
  `description` text NOT NULL,
  `view_status` int(11) NOT NULL DEFAULT 0,
  `type` int(11) NOT NULL,
  `ref_id` int(11) NOT NULL DEFAULT 0,
  `title_ar` varchar(150) DEFAULT NULL,
  `description_ar` varchar(250) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_promo_histories`
--

CREATE TABLE `customer_promo_histories` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `promo_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_sos_contacts`
--

CREATE TABLE `customer_sos_contacts` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `phone_number` varchar(250) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_subscription_histories`
--

CREATE TABLE `customer_subscription_histories` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `sub_id` int(11) NOT NULL DEFAULT 0,
  `purchased_at` date NOT NULL,
  `expiry_at` date NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_wallet_histories`
--

CREATE TABLE `customer_wallet_histories` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `message` varchar(250) NOT NULL,
  `message_ar` varchar(150) DEFAULT NULL,
  `amount` double NOT NULL,
  `transaction_type` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `daily_fare_management`
--

CREATE TABLE `daily_fare_management` (
  `id` int(11) NOT NULL,
  `vehicle_type` int(11) NOT NULL DEFAULT 1,
  `base_fare` varchar(250) NOT NULL,
  `price_per_km` varchar(250) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `daily_fare_management`
--

INSERT INTO `daily_fare_management` (`id`, `vehicle_type`, `base_fare`, `price_per_km`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, '50', '5', 1, '2023-10-26 14:44:45', '2023-10-26 14:44:45'),
(2, 2, '50', '7', 1, '2023-10-26 14:45:02', '2023-10-26 14:45:59'),
(3, 3, '60', '8', 1, '2023-10-26 14:45:21', '2023-10-26 14:46:19'),
(4, 4, '50', '6', 1, '2023-10-26 14:45:38', '2023-10-26 14:45:38'),
(5, 1, '100', '2', 1, '2023-11-09 12:12:21', '2023-11-09 12:12:21');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_fare_management`
--

CREATE TABLE `delivery_fare_management` (
  `id` int(11) NOT NULL,
  `vehicle_type` int(11) NOT NULL DEFAULT 0,
  `trip_sub_type_id` int(11) NOT NULL,
  `base_fare` varchar(250) NOT NULL,
  `price_per_km` varchar(250) NOT NULL,
  `driver_allowance` varchar(250) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `delivery_fare_management`
--

INSERT INTO `delivery_fare_management` (`id`, `vehicle_type`, `trip_sub_type_id`, `base_fare`, `price_per_km`, `driver_allowance`, `status`, `created_at`, `updated_at`) VALUES
(1, 9, 3, '100', '20', '100', 1, '2023-10-26 15:00:47', '2023-10-26 15:00:47'),
(2, 9, 4, '100', '30', '100', 1, '2023-10-26 15:01:03', '2023-10-26 15:01:03');

-- --------------------------------------------------------

--
-- Table structure for table `dispatch_trips`
--

CREATE TABLE `dispatch_trips` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(150) NOT NULL,
  `customer_phone` varchar(150) NOT NULL,
  `pickup_address` varchar(250) DEFAULT NULL,
  `drop_address` varchar(250) DEFAULT NULL,
  `pickup_lat` varchar(100) NOT NULL,
  `pickup_lng` varchar(100) NOT NULL,
  `drop_lat` varchar(100) NOT NULL,
  `drop_lng` varchar(100) NOT NULL,
  `vehicle_type` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `drivers`
--

CREATE TABLE `drivers` (
  `id` int(11) NOT NULL,
  `first_name` varchar(250) NOT NULL,
  `last_name` varchar(250) NOT NULL,
  `phone_number` varchar(250) NOT NULL,
  `phone_with_code` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `profile_picture` varchar(250) NOT NULL DEFAULT 'drivers/avatar.png',
  `date_of_birth` date NOT NULL,
  `address` varchar(250) DEFAULT NULL,
  `licence_number` varchar(250) NOT NULL,
  `id_proof` varchar(250) NOT NULL DEFAULT 'static_images/id_proof_icon.png	',
  `id_proof_status` int(11) NOT NULL DEFAULT 14,
  `rejected_reason` text DEFAULT NULL,
  `online_status` int(11) NOT NULL DEFAULT 0,
  `wallet` double NOT NULL DEFAULT 0,
  `overall_ratings` varchar(50) NOT NULL DEFAULT '0',
  `no_of_ratings` varchar(50) NOT NULL DEFAULT '0',
  `otp` varchar(50) DEFAULT NULL,
  `fcm_token` text DEFAULT NULL,
  `shared_ride_status` int(11) NOT NULL DEFAULT 0,
  `driver_hiring_status` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL,
  `referral_code` varchar(100) DEFAULT NULL,
  `refered_by` varchar(100) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `driver_bank_kyc_details`
--

CREATE TABLE `driver_bank_kyc_details` (
  `id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `bank_name` varchar(50) NOT NULL,
  `bank_account_number` varchar(50) NOT NULL,
  `ifsc_code` varchar(50) NOT NULL,
  `aadhar_number` varchar(50) NOT NULL,
  `pan_number` varchar(50) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `driver_checkins`
--

CREATE TABLE `driver_checkins` (
  `id` int(11) NOT NULL,
  `driver-id` int(11) NOT NULL,
  `checkin_time` datetime NOT NULL,
  `checkout_time` datetime DEFAULT NULL,
  `total_hours` double NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `driver_commissions`
--

CREATE TABLE `driver_commissions` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `picked_up` varchar(250) NOT NULL,
  `dropped` varchar(250) NOT NULL,
  `commission` varchar(250) NOT NULL,
  `mode_of_payment` int(11) NOT NULL,
  `date` varchar(250) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `driver_earnings`
--

CREATE TABLE `driver_earnings` (
  `id` int(11) NOT NULL,
  `trip_id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `driver_hiring_fare_management`
--

CREATE TABLE `driver_hiring_fare_management` (
  `id` int(11) NOT NULL,
  `base_fare` double NOT NULL,
  `base_hours` int(11) NOT NULL,
  `extra_hour_charge` double NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `driver_hiring_requests`
--

CREATE TABLE `driver_hiring_requests` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `pickup_location` text NOT NULL,
  `pickup_lat` varchar(100) NOT NULL,
  `pickup_lng` varchar(100) NOT NULL,
  `drop_location` text NOT NULL,
  `drop_lat` varchar(100) NOT NULL,
  `drop_lng` varchar(100) NOT NULL,
  `pickup_date` date NOT NULL,
  `pickup_time` time NOT NULL,
  `drop_date` date DEFAULT NULL,
  `drop_time` time DEFAULT NULL,
  `zone` int(11) NOT NULL,
  `total` double NOT NULL DEFAULT 0,
  `tax` double NOT NULL DEFAULT 0,
  `sub_total` double NOT NULL DEFAULT 0,
  `payment_method` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `driver_hiring_statuses`
--

CREATE TABLE `driver_hiring_statuses` (
  `id` int(11) NOT NULL,
  `status_name` varchar(100) NOT NULL,
  `customer_status_name` varchar(150) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `driver_queries`
--

CREATE TABLE `driver_queries` (
  `id` int(11) NOT NULL,
  `first_name` varchar(250) NOT NULL,
  `phone_number` varchar(250) NOT NULL,
  `status` int(11) NOT NULL,
  `email` varchar(250) NOT NULL,
  `last_name` varchar(250) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `driver_recharges`
--

CREATE TABLE `driver_recharges` (
  `id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `driver_tips`
--

CREATE TABLE `driver_tips` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `trip_id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `tip` double NOT NULL,
  `tip_mode` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `driver_trip_requests`
--

CREATE TABLE `driver_trip_requests` (
  `id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL DEFAULT 0,
  `trip_request_id` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `driver_tutorials`
--

CREATE TABLE `driver_tutorials` (
  `id` int(11) NOT NULL,
  `title` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `title_ar` varchar(150) DEFAULT NULL,
  `description_ar` text DEFAULT NULL,
  `file` text NOT NULL,
  `thumbnail_image` varchar(250) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `driver_vehicles`
--

CREATE TABLE `driver_vehicles` (
  `id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `vehicle_type` int(11) NOT NULL,
  `brand` varchar(250) NOT NULL,
  `color` varchar(250) NOT NULL,
  `vehicle_name` varchar(250) NOT NULL,
  `vehicle_number` varchar(250) NOT NULL,
  `vehicle_image` varchar(250) NOT NULL DEFAULT 'static_images/vehicle_image_icon.png',
  `vehicle_image_status` int(11) NOT NULL DEFAULT 14,
  `vehicle_certificate` varchar(500) NOT NULL DEFAULT 'static_images/vehicle_certificate_icon.png',
  `vehicle_certificate_status` int(11) NOT NULL DEFAULT 14,
  `vehicle_insurance` varchar(500) NOT NULL DEFAULT 'static_images/vehicle_insurance_icon.png',
  `vehicle_insurance_status` int(11) NOT NULL DEFAULT 14,
  `status` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `driver_wallet_histories`
--

CREATE TABLE `driver_wallet_histories` (
  `id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `message` varchar(250) NOT NULL,
  `message_ar` varchar(250) DEFAULT NULL,
  `amount` double NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `driver_withdrawals`
--

CREATE TABLE `driver_withdrawals` (
  `id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `reference_proof` varchar(200) DEFAULT NULL,
  `reference_no` text DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` int(11) NOT NULL,
  `user_type_id` int(11) NOT NULL,
  `question` varchar(250) NOT NULL,
  `answer` text NOT NULL,
  `question_ar` varchar(250) DEFAULT NULL,
  `answer_ar` text DEFAULT NULL,
  `status` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `user_type_id`, `question`, `answer`, `question_ar`, `answer_ar`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'What is cancellation fee?', 'In Cab2U we appreciate the partners’ time and, thus, we always compensate the time spent on the road to the passenger if the trip never took place. In that case, user can be charged with a volume of 40 USD, if: -you cancelled trip more than 5 minutes after you have accepted the request -you are late for more than 5 minutes after driver has arrived at the pickup location, and you didn’t contact the driver to let him know about your delay User doesn’t charged a cancellation fee, if: -you cancelled trip during first 5 minutes after making request -driver is late for more than 5 minutes (after ETA, which you’ve accepted while made request) Mention that if you are late, you can notify driver that you are late via call or sms.', 'ما هي رسوم الإلغاء؟', 'في Cab2door نقدر وقت الشركاء ، وبالتالي ، فإننا نعوض دائمًا الوقت الذي يقضيه الراكب على الطريق إذا لم تحدث الرحلة مطلقًا. في هذه الحالة ، يمكن تحميل المستخدم مبلغ 40 دولارًا أمريكيًا ، إذا: - ألغيت الرحلة بعد أكثر من 5 دقائق من قبولك للطلب - تأخرت لأكثر من 5 دقائق بعد وصول السائق إلى موقع الالتقاء ، و لم تتصل بالسائق لإخباره بالتأخير الذي أجريته ، لم يتقاضى المستخدم رسوم إلغاء ، إذا: - ألغيت الرحلة خلال أول 5 دقائق بعد تقديم الطلب - تأخر السائق لأكثر من 5 دقائق (بعد ETA ، والتي لقد قبلت أثناء تقديم الطلب) اذكر أنه إذا تأخرت ، يمكنك إخطار السائق بأنك تأخرت عن طريق الاتصال أو الرسائل القصيرة.', 1, '2021-03-01 10:00:28', '2023-03-22 17:08:20'),
(2, 1, 'How to change language settings of the app?', 'Cab2U app automatically sets the language based on the language settings of your device, so in order to change the language of the application, you should change the language settings of your smartphone.', 'كيفية تغيير إعدادات لغة التطبيق؟', 'يضبط تطبيق Cab2door اللغة تلقائيًا بناءً على إعدادات اللغة بجهازك ، لذلك لتغيير لغة التطبيق ، يجب عليك تغيير إعدادات اللغة بهاتفك الذكي.', 1, '2021-03-01 10:01:18', '2023-03-22 17:08:00'),
(3, 1, 'Do you provide self drive cars?', 'Sorry, we do not provide self-drive cars. Our driver drives up to your doorstep with the vehicle and drives you around during your entire journey.', 'هل تقدمون سيارات ذاتية القيادة؟', 'عذرا ، نحن لا نقدم سيارات ذاتية القيادة. سائقنا يقود سيارتك حتى عتبة داركم بالسيارة ويقودك خلال رحلتك بأكملها.', 1, '2021-03-01 10:02:09', '2021-03-06 07:16:07'),
(4, 1, 'What if my cab shows up late?', 'We are proud of our on-time performance but sometimes delays do happen. If the nature of your booking is time-sensative ...involving an airport pickup/drop or meeting at your destination, please budget for additional travel time (usually add 30mins for traffic delays for every 2 hours of estimated travel time) and also let us know of this as a special request when making your reservation. We will make additional efforts to ensure that we\'re vigilant and ensure a safe & punctual transit for you. if for any case, your cab is delayed and you have to cancel your reservation we will issue a full refund of any payment that you may have made in the form of advance towards the taxi reservation', 'ماذا لو تأخرت سيارتي؟', 'نحن فخورون بأدائنا في الوقت المحدد ولكن في بعض الأحيان يحدث تأخير. إذا كانت طبيعة حجزك حساسة للوقت ... بما في ذلك النقل من المطار / النزول أو الاجتماع في وجهتك ، فالرجاء تخصيص ميزانية لوقت السفر الإضافي (عادةً ما تضيف 30 دقيقة لتأخير حركة المرور لكل ساعتين من وقت السفر المقدر) وكذلك السماح نحن نعرف هذا كطلب خاص عند إجراء الحجز الخاص بك. سنبذل جهودًا إضافية للتأكد من أننا يقظين ونضمن لك عبورًا آمنًا ودقيقًا. إذا تأخرت سيارة الأجرة الخاصة بك على أي حال وكان عليك إلغاء الحجز الخاص بك ، فسنقوم بإرجاع كامل المبلغ المدفوع الذي ربما تكون قد سددته في شكل مقدم تجاه حجز سيارة الأجرة', 1, '2021-03-01 10:03:20', '2021-03-06 07:16:43'),
(5, 1, 'What if the cab breaks down during the journey?', 'All our taxi\'s are regularly inspected along over 30 different points. However, breakdowns cannot be anticipated and do happen. In those cases, we expediently arrange a backup cab to ensure that your travel plans continue uninterrupted and with the least possible delay.', NULL, NULL, 1, '2021-03-01 10:04:00', '2021-03-01 10:04:00'),
(6, 1, 'Do you provide an English-speaking driver?', 'We do try our best to provide a English speaking driver if the request is received on your booking under the additional requests section ahead of time. This is generally subject to availability of a English-speaking driver. If you are not a resident of the region, we suggest that you install Google Translate on your phone. Using the apps voice transcription features, you can speak in your native language and the app would translate it into spoken words of the language of your choice.', NULL, NULL, 1, '2021-03-01 10:04:46', '2021-03-01 10:04:46'),
(7, 1, 'How can I get a bill/receipt for my trip?', 'Invoices are automatically generated and sent to you by email for every trip that you complete with us.', NULL, NULL, 1, '2021-03-01 10:05:32', '2021-03-01 10:05:32'),
(8, 2, 'how to enter or change my destinations?', 'You will need to enter your destination before confirming your booking. You can do this by: Entering the address in the ‘destination’ field at the top of the screen. You can also change your destination during your ride by: Clicking ‘Edit’ and entering the correct destination', 'كيف أدخل وجهتي أو أغيرها', 'ستحتاج إلى إدخال وجهتك قبل تأكيد حجزك. يمكنك القيام بذلك عن طريق: إدخال العنوان في حقل \"الوجهة\" أعلى الشاشة. يمكنك أيضًا تغيير وجهتك أثناء رحلتك عن طريق: النقر على \"تعديل\" وإدخال الوجهة الصحيحة', 1, '2021-03-01 10:09:21', '2023-06-19 11:47:33'),
(9, 2, 'how to track your ride?', 'Once your booking is confirmed, you’ll be able to track your ride and see the following details on your app in real time: Your driver’s ETA and current location. The driver’s route to your pick-up address. The entire route of your ride.', 'كيفية تتبع رحلتك', 'بمجرد تأكيد حجزك ، ستتمكن من تتبع رحلتك والاطلاع على التفاصيل التالية على تطبيقك في الوقت الفعلي: الوقت المقدر للسائق والموقع الحالي. طريق السائق إلى عنوان سيارتك. المسار الكامل لرحلتك.', 1, '2021-03-01 10:10:06', '2023-06-19 11:47:51'),
(10, 2, 'how to rate our ride?', 'Ratings enable us to ensure both our riders and drivers are having a great experience using Ola. You’ll always be prompted to rate your driver after you take a ride with us. Once you reach your destination, a notification will appear that will prompt you to rate your driver. You’ll be able to rate your ride from 1 to 5 stars. Select certain fields about the ride and provide specific details.', 'كيف تقيم رحلتنا', 'تمكننا التقييمات من ضمان تمتع كل من ركابنا وسائقينا بتجربة رائعة باستخدام Ola. ستتم مطالبتك دائمًا بتقييم سائقك بعد أن تأخذ مشوارًا معنا. بمجرد وصولك إلى وجهتك ، سيظهر إشعار يطالبك بتقييم سائقك. ستتمكن من تقييم رحلتك من نجمة إلى 5 نجوم. حدد حقولًا معينة حول الرحلة وقدم تفاصيل محددة.', 1, '2021-03-01 10:10:54', '2023-06-19 11:49:56'),
(11, 1, 'What is cancellation fee?', 'In Cab2door we appreciate the partners’ time and, thus, we always compensate the time spent on the road to the passenger if the trip never took place. In that case, user can be charged with a volume of 40 USD, if: -you cancelled trip more than 5 minutes after you have accepted the request -you are late for more than 5 minutes after driver has arrived at the pickup location, and you didn’t contact the driver to let him know about your delay User doesn’t charged a cancellation fee, if: -you cancelled trip during first 5 minutes after making request -driver is late for more than 5 minutes (after ETA, which you’ve accepted while made request) Mention that if you are late, you can notify driver that you are late via call or sms.', 'ما هي رسوم الإلغاء؟', 'في Cab2door نقدر وقت الشركاء ، وبالتالي ، فإننا نعوض دائمًا الوقت الذي يقضيه الراكب على الطريق إذا لم تحدث الرحلة مطلقًا. في هذه الحالة ، يمكن تحميل المستخدم مبلغ 40 دولارًا أمريكيًا ، إذا: - ألغيت الرحلة بعد أكثر من 5 دقائق من قبولك للطلب - تأخرت لأكثر من 5 دقائق بعد وصول السائق إلى موقع الالتقاء ، و لم تتصل بالسائق لإخباره بالتأخير الذي أجريته ، لم يتقاضى المستخدم رسوم إلغاء ، إذا: - ألغيت الرحلة خلال أول 5 دقائق بعد تقديم الطلب - تأخر السائق لأكثر من 5 دقائق (بعد ETA ، والتي لقد قبلت أثناء تقديم الطلب) اذكر أنه إذا تأخرت ، يمكنك إخطار السائق بأنك تأخرت عن طريق الاتصال أو الرسائل القصيرة.', 1, '2021-05-15 09:16:22', '2021-05-18 07:52:53'),
(12, 1, 'How to change language settings of the app?', 'Cab2U app automatically sets the language based on the language settings of your device, so in order to change the language of the application, you should change the language settings of your smartphone.', 'كيفية تغيير إعدادات لغة التطبيق؟', 'يضبط تطبيق Cab2door اللغة تلقائيًا بناءً على إعدادات اللغة بجهازك ، لذلك لتغيير لغة التطبيق ، يجب عليك تغيير إعدادات اللغة بهاتفك الذكي.', 1, '2021-05-15 09:17:47', '2023-03-22 17:08:40'),
(13, 1, 'Do you provide self drive cars?', 'Sorry, we do not provide self-drive cars. Our driver drives up to your doorstep with the vehicle and drives you around during your entire journey.', 'هل تقدمون سيارات ذاتية القيادة؟', 'عذرا ، نحن لا نقدم سيارات ذاتية القيادة. سائقنا يقود سيارتك حتى عتبة داركم بالسيارة ويقودك خلال رحلتك بأكملها.', 1, '2021-05-15 09:18:46', '2022-10-29 04:36:14'),
(14, 2, 'How to enter or change my destinations?', 'You will need to enter your destination before confirming your booking. You can do this by: Entering the address in the ‘destination’ field at the top of the screen. You can also change your destination during your ride by: Clicking ‘Edit’ and entering the correct destination', 'كيف أدخل وجهتي أو أغيرها', 'ستحتاج إلى إدخال وجهتك قبل تأكيد حجزك. يمكنك القيام بذلك عن طريق: إدخال العنوان في حقل \"الوجهة\" أعلى الشاشة. يمكنك أيضًا تغيير وجهتك أثناء رحلتك عن طريق: النقر على \"تعديل\" وإدخال الوجهة الصحيحة', 1, '2021-05-15 09:20:07', '2021-05-18 07:53:23'),
(15, 2, 'How to track your ride?', 'Once your booking is confirmed, you’ll be able to track your ride and see the following details on your app in real time: Your driver’s ETA and current location. The driver’s route to your pick-up address. The entire route of your ride.', 'كيفية تتبع رحلتك', 'بمجرد تأكيد حجزك ، ستتمكن من تتبع رحلتك والاطلاع على التفاصيل التالية على تطبيقك في الوقت الفعلي: الوقت المقدر للسائق والموقع الحالي. طريق السائق إلى عنوان سيارتك. المسار الكامل لرحلتك.', 1, '2021-05-15 09:21:11', '2021-05-18 07:53:33'),
(16, 2, 'How to rate our ride?', 'Ratings enable us to ensure both our riders and drivers are having a great experience using Ola. You’ll always be prompted to rate your driver after you take a ride with us. Once you reach your destination, a notification will appear that will prompt you to rate your driver. You’ll be able to rate your ride from 1 to 5 stars. Select certain fields about the ride and provide specific details.', 'كيف تقيم رحلتنا', 'تمكننا التقييمات من ضمان تمتع كل من ركابنا وسائقينا بتجربة رائعة باستخدام Ola. ستتم مطالبتك دائمًا بتقييم سائقك بعد أن تأخذ مشوارًا معنا. بمجرد وصولك إلى وجهتك ، سيظهر إشعار يطالبك بتقييم سائقك. ستتمكن من تقييم رحلتك من نجمة إلى 5 نجوم. حدد حقولًا معينة حول الرحلة وقدم تفاصيل محددة.', 1, '2021-05-15 09:22:35', '2021-05-18 07:53:44'),
(17, 1, 'What if my cab is canceled?', 'Our operators are monitoring all flights. If there is long time delay we recommend that you let us know.', NULL, NULL, 1, '2023-01-10 13:46:02', '2023-01-10 13:46:02'),
(18, 2, 'How to change language settings of the app?', 'Cab2U app automatically sets the language based on the language settings of your device, so in order to change the language of the application, you should change the language settings of your smartphone.', NULL, NULL, 1, '2023-01-10 13:47:10', '2023-01-10 13:56:51'),
(19, 2, 'What if the cab breaks down during the journey?', 'All our taxi\'s are regularly inspected along over 30 different points. However, breakdowns cannot be anticipated and do happen. In those cases, we expediently arrange a backup cab to ensure that your travel plans continue uninterrupted and with the least possible delay.', NULL, NULL, 1, '2023-01-10 13:48:01', '2023-01-10 13:57:01'),
(20, 2, 'How to change language settings of the app?', 'Cab2U app automatically sets the language based on the language settings of your device, so in order to change the language of the application, you should change the language settings of your smartphone.', NULL, NULL, 1, '2023-01-10 13:53:38', '2023-03-22 17:09:02'),
(21, 2, 'What if the cab breaks down during the journey?', 'All our taxi\'s are regularly inspected along over 30 different points. However, breakdowns cannot be anticipated and do happen. In those cases, we expediently arrange a backup cab to ensure that your travel plans continue uninterrupted and with the least possible delay.', NULL, NULL, 1, '2023-01-10 13:54:27', '2023-01-10 13:54:27'),
(22, 2, 'Do you provide an English-speaking driver?', 'We do try our best to provide a English speaking driver if the request is received on your booking under the additional requests section ahead of time. This is generally subject to availability of a English-speaking driver. If you are not a resident of the region, we suggest that you install Google Translate on your phone. Using the apps voice transcription features, you can speak in your native language and the app would translate it into spoken words of the language of your choice.', NULL, NULL, 1, '2023-01-10 13:55:40', '2023-01-10 13:55:40'),
(23, 2, 'How can I get a bill/receipt for my trip?', 'Invoices are automatically generated and sent to you by email for every trip that you complete with us.', NULL, NULL, 1, '2023-01-10 13:56:12', '2023-01-10 13:56:12'),
(24, 2, 'Are you comfortable driving long distances?', 'Driving a cab can involve long distances, especially if you work in an area with high demand. Employers ask this question to make sure you’re physically and mentally prepared for the job.', 'هل أنت مرتاح للقيادة لمسافات طويلة؟', 'يمكن أن تنطوي قيادة الكابينة على مسافات طويلة ، خاصة إذا كنت تعمل في منطقة ذات طلب مرتفع. يطرح أصحاب العمل هذا السؤال للتأكد من أنك مستعد جسديًا وعقليًا للوظيفة.', 1, '2023-01-26 17:20:32', '2023-01-26 17:20:32'),
(25, 2, 'Do Provide an example of a time when you went above and beyond for a passenger?', 'In addition to taking the alternate route, I also provided the passenger with information about local attractions and restaurants they could visit if they had more time. This made the ride much more enjoyable and gave the passenger some ideas for future trips.', 'هل تقدم مثالاً على الوقت الذي ذهبت فيه إلى أبعد الحدود للراكب؟', 'بالإضافة إلى اتخاذ المسار البديل ، فقد قدمت أيضًا للركاب معلومات حول مناطق الجذب والمطاعم المحلية التي يمكنهم زيارتها إذا كان لديهم المزيد من الوقت. جعل هذا الركوب أكثر إمتاعًا وأعطى الركاب بعض الأفكار للرحلات المستقبلية.', 1, '2023-01-26 17:23:35', '2023-01-26 17:23:35'),
(26, 2, 'How well can you communicate with people from different cultural backgrounds?', 'As a cab driver, you may have to communicate with people from different cultural backgrounds. Employers ask this question to make sure you can do so effectively and respectfully. In your answer, explain that you are willing to learn about the cultures of others.', 'إلى أي مدى يمكنك التواصل مع أشخاص من خلفيات ثقافية مختلفة؟', 'بصفتك سائق سيارة أجرة ، قد تضطر إلى التواصل مع أشخاص من خلفيات ثقافية مختلفة. يطرح أرباب العمل هذا السؤال للتأكد من أنه يمكنك القيام بذلك بشكل فعال ومحترم. اشرح في إجابتك أنك على استعداد للتعرف على ثقافات الآخرين.', 1, '2023-01-26 17:26:40', '2023-01-26 17:26:40'),
(27, 2, 'Do you have a valid driver’s license and vehicle registration?', 'Yes, I do have a valid driver’s license and vehicle registration. I have been driving professionally for the past five years and have a clean driving record. During this time, I have developed an extensive knowledge of the local roads and traffic patterns. I am also familiar with all relevant laws and regulations regarding cab drivers in my area.', 'هل لديك رخصة قيادة سارية المفعول وتسجيل مركبة؟', 'نعم ، لدي رخصة قيادة سارية المفعول وتسجيل مركبة. لقد كنت أقود السيارة باحتراف على مدى السنوات الخمس الماضية ولدي سجل قيادة نظيف. خلال هذا الوقت ، طورت معرفة واسعة بالطرق المحلية وأنماط حركة المرور. أنا أيضًا على دراية بجميع القوانين واللوائح ذات الصلة فيما يتعلق بسائقي سيارات الأجرة في منطقتي.', 1, '2023-01-26 17:28:46', '2023-01-26 17:28:46'),
(28, 2, 'Describe your process for cleaning your vehicle after each shift?', 'My process for cleaning my vehicle after each shift is thorough and efficient. I start by vacuuming the interior of the car, paying special attention to any spills or messes that may have occurred during the shift. Then, I use a damp cloth with mild soap and water to wipe down all surfaces, including the dashboard, windows, and door handles.', 'صف عمليتك لتنظيف سيارتك بعد كل وردية؟', 'عمليتي لتنظيف سيارتي بعد كل وردية شاملة وفعالة. أبدأ بتنظيف الجزء الداخلي من السيارة ، مع إيلاء اهتمام خاص لأي انسكابات أو عبث قد حدث أثناء التحول. بعد ذلك ، أستخدم قطعة قماش مبللة بالماء والصابون المعتدل لمسح جميع الأسطح ، بما في ذلك لوحة القيادة والنوافذ ومقابض الأبواب.', 1, '2023-01-26 17:30:10', '2023-01-26 17:30:10'),
(29, 1, 'How do you ask a taxi driver?', 'When you call up the taxi company, you can ask to book a taxi at a certain time by saying “May I book a taxi at (time)?” or if you would like one right away', 'كيف تسأل سائق تاكسي؟', 'عند الاتصال بشركة سيارات الأجرة ، يمكنك طلب حجز سيارة أجرة في وقت معين بقول \"هل يمكنني حجز سيارة أجرة في (وقت)؟\" أو إذا كنت ترغب في الحصول على واحدة على الفور', 1, '2023-01-26 18:01:44', '2023-01-26 18:01:44'),
(30, 1, 'What makes a good taxi service?', 'Taxi refers to for-hire automobile travel supplied by private companies. Taxi service is an important Transportation Option that meets a variety of needs, including Basic Mobility in emergencies, general transportation for non-drivers, and mobility for Tourists and visitors.', 'ما الذي يجعل خدمة سيارات الأجرة جيدة؟', 'يشير مصطلح تاكسي إلى السفر بالسيارات للإيجار الذي توفره الشركات الخاصة. تعد خدمة سيارات الأجرة أحد خيارات النقل المهمة التي تلبي مجموعة متنوعة من الاحتياجات ، بما في ذلك التنقل الأساسي في حالات الطوارئ ، والنقل العام لغير السائقين ، والتنقل للسياح والزوار.', 1, '2023-01-26 18:06:12', '2023-01-26 18:06:12'),
(31, 1, 'When will I receive Driver & Cab details?', 'We try to allocate the cab and driver as soon as possible. Please check your reservation confirmation email. In there you find a \"Live booking updates URL\". As soon as your cab and driver are allocated, the details become visible there. In any case, the details are made available to you well ahead of your time of travel', 'متى سأتلقى تفاصيل السائق والكابينة؟', 'نحاول تخصيص الكابينة والسائق في أسرع وقت ممكن. يرجى التحقق من البريد الإلكتروني لتأكيد الحجز الخاص بك. هناك تجد \"عنوان URL لتحديثات الحجز الحية\". بمجرد تخصيص الكابينة والسائق الخاصين بك ، تصبح التفاصيل مرئية هناك. على أي حال ، يتم توفير التفاصيل لك قبل وقت السفر بوقت كاف', 1, '2023-01-26 18:08:10', '2023-01-26 18:08:10'),
(32, 1, 'Does the driver have GPS to track the route?', 'Most of our drivers do use GPS to track the route but it is not implemented compulsorily. But customers can track their route through Google Map as we use that to track locations. Moreover, we have 24*7 customer service over web chat & call, customers can contact us for any doubts they have.', 'هل لدى السائق نظام تحديد المواقع العالمي (GPS) لتتبع المسار؟', 'يستخدم معظم السائقين لدينا نظام تحديد المواقع العالمي (GPS) لتتبع المسار ولكن لا يتم تنفيذه بشكل إلزامي. لكن يمكن للعملاء تتبع مسارهم من خلال خريطة Google حيث نستخدم ذلك لتتبع المواقع. علاوة على ذلك ، لدينا خدمة عملاء على مدار الساعة طوال أيام الأسبوع عبر الدردشة والمكالمات عبر الإنترنت ، ويمكن للعملاء الاتصال بنا لأي شكوك لديهم.', 1, '2023-01-26 18:09:13', '2023-01-26 18:09:13'),
(33, 1, 'What car categories do you provide?', 'Compact - includes models like Indica and Indigo\r\nSedan cars - includes models like Etios, D?zire\r\nFamily cars/SUV - includes models like Xylo, Tavera\r\nFamily Lux/SUV - Innovas\r\nAdditional services like tempo traveller or buses can be arranged upon request, simply call our customer service help line. In many cases, additional requests for a car with carrier will cost extra.', 'ما هي فئات السيارات التي تقدمها؟', 'مدمج - يتضمن طرازات مثل Indica و Indigo\r\nسيارات السيدان - تشمل موديلات مثل Etios و D؟ zire\r\nسيارات عائلية / سيارات الدفع الرباعي - تشمل موديلات مثل Xylo و Tavera\r\nFamily Lux / SUV - Innovas\r\nيمكن ترتيب خدمات إضافية مثل مسافر الإيقاع أو الحافلات عند الطلب ، ما عليك سوى الاتصال بخط مساعدة خدمة العملاء. في كثير من الحالات ، ستكلف الطلبات الإضافية لسيارة مع شركة نقل تكلفة إضافية.', 1, '2023-01-26 18:10:38', '2023-01-26 18:10:38'),
(34, 1, 'Can I book cab by calling customer support?', 'Of course you can do that. Prices for items (products or services) that are ordered with assistance from a team member may be priced slightly higher than the prices on self-service platforms (website or mobile apps). These support facility charges are included in your order price to cover the costs of the support facilities & personnel involved in enabling such orders. Upon cancellation of such (assisted) orders, applicable support facility charges shall not be refunded. These costs are already incurred by us as part of providing the support facilities and hence these charges shall be added to the cancellation charges applicable to your order upon its cancellation.', 'هل يمكنني حجز سيارة أجرة عن طريق الاتصال بدعم العملاء؟', 'بالطبع يمكنك فعل ذلك. قد تكون أسعار العناصر (المنتجات أو الخدمات) التي يتم طلبها بمساعدة أحد أعضاء الفريق أعلى قليلاً من الأسعار على منصات الخدمة الذاتية (موقع الويب أو تطبيقات الأجهزة المحمولة). يتم تضمين رسوم تسهيلات الدعم هذه في سعر الطلب الخاص بك لتغطية تكاليف مرافق الدعم والموظفين المشاركين في تمكين مثل هذه الطلبات. عند إلغاء هذه الطلبات (المساعدة) ، لن يتم رد رسوم مرفق الدعم المعمول بها. لقد تكبدنا هذه التكاليف بالفعل كجزء من توفير تسهيلات الدعم ، ومن ثم تضاف هذه الرسوم إلى رسوم الإلغاء المطبقة على طلبك عند إلغائه.', 1, '2023-01-26 18:12:21', '2023-01-26 18:12:21');

-- --------------------------------------------------------

--
-- Table structure for table `fare_management`
--

CREATE TABLE `fare_management` (
  `id` int(11) NOT NULL,
  `vehicle_type` int(11) NOT NULL DEFAULT 0,
  `fare_type` float NOT NULL,
  `base_fare` float NOT NULL,
  `price_per_km` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feature_settings`
--

CREATE TABLE `feature_settings` (
  `id` int(11) NOT NULL,
  `enable_sms` int(2) NOT NULL,
  `enable_mail` int(2) NOT NULL,
  `enable_referral_module` int(2) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `instant_offers`
--

CREATE TABLE `instant_offers` (
  `id` int(11) NOT NULL,
  `discount_type` int(11) NOT NULL DEFAULT 1,
  `discount` varchar(10) NOT NULL,
  `offer_name` varchar(150) NOT NULL,
  `offer_description` text NOT NULL,
  `offer_name_ar` varchar(150) DEFAULT NULL,
  `offer_description_ar` text DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lucky_offers`
--

CREATE TABLE `lucky_offers` (
  `id` int(11) NOT NULL,
  `offer_name` varchar(250) NOT NULL,
  `offer_description` text DEFAULT NULL,
  `offer_name_ar` varchar(150) DEFAULT NULL,
  `offer_description_ar` text DEFAULT NULL,
  `image` varchar(500) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mail_contents`
--

CREATE TABLE `mail_contents` (
  `id` int(11) NOT NULL,
  `code` varchar(250) NOT NULL,
  `title` varchar(250) NOT NULL,
  `content` text NOT NULL,
  `title_ar` varchar(250) DEFAULT NULL,
  `content_ar` text DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `code` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` varchar(500) NOT NULL,
  `message_ar` text DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `missed_trip_requests`
--

CREATE TABLE `missed_trip_requests` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `zone` int(11) NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `weight` float NOT NULL DEFAULT 0.25,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification_messages`
--

CREATE TABLE `notification_messages` (
  `id` int(11) NOT NULL,
  `type` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(250) NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_ar` varchar(250) DEFAULT NULL,
  `message_ar` text DEFAULT NULL,
  `image` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `offer_types`
--

CREATE TABLE `offer_types` (
  `id` int(11) NOT NULL,
  `type` varchar(250) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `outstation_fare_management`
--

CREATE TABLE `outstation_fare_management` (
  `id` int(11) NOT NULL,
  `vehicle_type` int(11) NOT NULL DEFAULT 0,
  `trip_sub_type_id` int(11) NOT NULL,
  `base_fare` varchar(250) NOT NULL,
  `price_per_km` varchar(250) NOT NULL,
  `driver_allowance` varchar(250) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `outstation_fare_management`
--

INSERT INTO `outstation_fare_management` (`id`, `vehicle_type`, `trip_sub_type_id`, `base_fare`, `price_per_km`, `driver_allowance`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '50', '10', '100', 1, '2023-10-26 14:49:14', '2023-10-26 14:49:14'),
(2, 2, 1, '50', '11', '100', 1, '2023-10-26 14:49:39', '2023-10-26 14:49:39'),
(3, 3, 1, '50', '12', '100', 1, '2023-10-26 14:49:57', '2023-10-26 14:49:57'),
(4, 4, 1, '50', '9', '100', 1, '2023-10-26 14:50:23', '2023-10-26 14:50:23'),
(5, 1, 2, '100', '20', '100', 1, '2023-10-26 14:51:42', '2023-10-26 14:51:42'),
(6, 2, 2, '100', '21', '100', 1, '2023-10-26 14:52:00', '2023-10-26 14:52:00'),
(7, 3, 2, '100', '22', '100', 1, '2023-10-26 14:52:25', '2023-10-26 14:52:25'),
(8, 4, 2, '100', '20', '100', 1, '2023-10-26 14:53:00', '2023-10-26 14:53:00');

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` int(11) NOT NULL,
  `package_name` varchar(250) NOT NULL,
  `package_name_ar` varchar(150) DEFAULT NULL,
  `hours` varchar(250) NOT NULL,
  `kilometers` varchar(250) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `package_name`, `package_name_ar`, `hours`, `kilometers`, `created_at`, `updated_at`) VALUES
(1, 'Minimum', 'Minimum', '1', '5', '2022-12-18 11:16:00', '2022-12-18 11:16:00'),
(2, 'Normal', 'Normal', '2', '10', '2022-12-18 11:16:23', '2022-12-18 11:16:23'),
(3, 'Moderate', 'Moderate', '3', '15', '2022-12-18 11:16:39', '2022-12-18 11:16:39'),
(4, 'Maximum', 'Maximum', '4', '20', '2022-12-18 11:16:58', '2022-12-18 11:17:06'),
(5, 'six hours', 'six hours', '6', '50', '2023-01-23 18:41:13', '2023-11-15 10:09:54'),
(6, 'seven hours', 'seven hours', '7', '30', '2023-01-23 18:41:56', '2023-01-23 18:41:56'),
(7, 'eight hours', 'eight hours', '8', '35', '2023-01-23 18:42:45', '2023-01-23 18:42:45'),
(8, 'Nine hours', 'Nine hours', '9', '40', '2023-01-23 18:43:13', '2023-01-23 18:43:13'),
(9, 'Ten hours', 'Ten hours', '10', '80', '2023-01-23 18:43:43', '2023-11-15 10:16:36'),
(10, 'nine hours', 'nine', '9', '40', '2023-11-09 12:11:18', '2023-11-16 12:14:03');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_histories`
--

CREATE TABLE `payment_histories` (
  `id` int(11) NOT NULL,
  `trip_id` int(11) NOT NULL,
  `mode` varchar(100) NOT NULL,
  `amount` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` int(11) NOT NULL,
  `payment` varchar(250) NOT NULL,
  `payment_ar` varchar(250) DEFAULT NULL,
  `payment_type` int(11) NOT NULL,
  `icon` varchar(250) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `payment`, `payment_ar`, `payment_type`, `icon`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Cash', 'نقدي', 1, 'image/b79753e8695478caf96b3d7e9965a340.png', 1, '2021-05-18 07:30:05', '2023-10-27 10:55:42'),
(2, 'Wallet', 'محفظة', 1, 'image/c955b858843e764c94642959b639a6b5.png', 1, '2021-05-18 07:32:05', '2023-10-27 10:55:53'),
(3, 'Cash/Wallet', 'النقدية / المحفظة', 1, 'image/cc800b8ad7027905f7f824af681dde2f.png', 1, '2021-05-18 07:33:10', '2023-10-26 18:59:18'),
(4, 'Subscription', 'الاشتراك', 1, 'image/b65f0a8baec20eeb6eb169acc284b226.png', 2, '2022-10-03 06:21:45', '2023-11-08 18:32:01'),
(5, 'Razorpay', 'رازورباي', 2, 'image/063badfccecaa6ffff1b3526150e1a14.png', 1, '2022-10-03 07:29:49', '2023-03-18 18:32:16'),
(6, 'PayPal', NULL, 2, 'image/685636eb444ff5cce62f3ac07de138b2.png', 2, '2023-03-18 18:32:44', '2023-10-27 10:57:40'),
(7, 'FlutterWave', NULL, 2, 'image/1b0e215ba954787eb3d5b4e859d562e3.png', 1, '2023-03-18 18:33:14', '2023-11-09 14:37:24'),
(37, 'Stripe', 'Stripe', 2, 'image/de4cf6a0dd6321e650de9d6da3322e40.png', 2, '2023-05-03 10:20:35', '2023-10-27 10:57:06'),
(38, 'PayStack', NULL, 2, 'image/f60019354afb28786a3f849bdb034292.png', 2, '2023-07-03 18:37:57', '2023-10-27 10:56:52');

-- --------------------------------------------------------

--
-- Table structure for table `payment_types`
--

CREATE TABLE `payment_types` (
  `id` int(11) NOT NULL,
  `payment_type` varchar(250) NOT NULL,
  `payment_type_ar` varchar(150) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_types`
--

INSERT INTO `payment_types` (`id`, `payment_type`, `payment_type_ar`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Offline', 'غير متصل على الانترنت', 1, '2021-03-01 10:17:25', '2021-03-06 07:34:21'),
(2, 'Online', 'متصل', 1, '2021-03-01 10:18:28', '2021-03-06 07:34:40');

-- --------------------------------------------------------

--
-- Table structure for table `paypal_settings`
--

CREATE TABLE `paypal_settings` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `privacy_policies`
--

CREATE TABLE `privacy_policies` (
  `id` int(11) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `title` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `title_ar` varchar(250) DEFAULT NULL,
  `description_ar` text DEFAULT NULL,
  `status` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `user_type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `privacy_policies`
--

INSERT INTO `privacy_policies` (`id`, `slug`, `title`, `description`, `title_ar`, `description_ar`, `status`, `created_at`, `updated_at`, `user_type_id`) VALUES
(1, '', 'Information', 'We collect information you provide to us in connection with our Services, such as when you create or update your account, log into your account, request or reserve transportation, contact our customer service, or otherwise communicate with us via telephone, our websites, mobile applications or otherwise. This information includes your name, telephone number, email address, mailing address, photographs or other text or images you use, e.g., for your profile, the types of Services you request (collectively, “Personal Information”), as well as transaction details, billing and payment information, and other information you use or provide to us in using the Services. If you do not wish to provide any Personal Information, you may simply decline to use our Services.', 'معلومة', 'نقوم بجمع المعلومات التي تقدمها لنا فيما يتعلق بخدماتنا ، على سبيل المثال عندما تقوم بإنشاء أو تحديث حسابك ، أو تسجيل الدخول إلى حسابك ، أو طلب أو حجز وسيلة نقل ، أو الاتصال بخدمة العملاء لدينا ، أو التواصل معنا بطريقة أخرى عبر الهاتف ، أو مواقعنا الإلكترونية ، أو الهاتف المحمول. التطبيقات أو غير ذلك. تتضمن هذه المعلومات اسمك ورقم هاتفك وعنوان بريدك الإلكتروني وعنوانك البريدي والصور الفوتوغرافية أو النصوص أو الصور الأخرى التي تستخدمها ، على سبيل المثال ، لملفك الشخصي ، وأنواع الخدمات التي تطلبها (يُشار إليها إجمالاً باسم \"المعلومات الشخصية\") ، فضلاً عن تفاصيل المعاملة ومعلومات الفوترة والدفع والمعلومات الأخرى التي تستخدمها أو تزودنا بها في استخدام الخدمات. إذا كنت لا ترغب في تقديم أي معلومات شخصية ، يمكنك ببساطة رفض استخدام خدماتنا.', 1, '2021-03-01 10:45:16', '2021-03-06 07:49:50', 1),
(2, 'location', 'Location', 'In order for us to be able to provide your requested Services, you will need to grant us permission to obtain your geographic location from your device. Thereafter, you can disable this function in the settings of your device, understanding that you may not be able to avail yourself of our Services that require your location.', 'موقع', 'لكي نتمكن من تقديم الخدمات المطلوبة ، ستحتاج إلى منحنا إذنًا بالحصول على موقعك الجغرافي من جهازك. بعد ذلك ، يمكنك تعطيل هذه الوظيفة في إعدادات جهازك ، مع العلم أنك قد لا تتمكن من الاستفادة من خدماتنا التي تتطلب موقعك.', 1, '2021-03-01 11:02:19', '2022-05-20 05:08:44', 1),
(3, 'driver_notes', 'Driver notes', 'The personal data of those who order or receive trips or deliveries via partner websites or apps (such as when ordering from a restaurant or grocery store), or arranged by other account owners (collectively “Guest Users”) is used solely to provide such trips, deliveries, or other services requested through a third party, and for purposes of safety and security, customer support, research and development, enabling communication between users, and in connection with legal proceedings and requirements, each as described in “How we use personal data” below. Guest User data may be shared with third parties for these purposes. Such data may be associated with, and accessible by, the owner of that account. This specifically includes Guest Users who receive rides/deliveries ordered by owners of Uber Health, Uber Central, Uber Direct or Uber for Business accounts, or who receive rides or deliveries ordered by friends, family members or others. To submit questions, comments or complaints regarding Guest User data, or to submit requests regarding such data', 'ملاحظات السائق', 'تُستخدم البيانات الشخصية لأولئك الذين يطلبون أو يستقبلون الرحلات أو التوصيلات عبر مواقع الويب أو التطبيقات الشريكة (على سبيل المثال عند الطلب من مطعم أو متجر بقالة) ، أو يتم ترتيبها بواسطة مالكي حسابات آخرين (يشار إليهم جميعًا باسم \"المستخدمون الضيوف\") فقط لتوفير هذه الرحلات أو عمليات التسليم أو الخدمات الأخرى المطلوبة من خلال طرف ثالث ، ولأغراض السلامة والأمن ، ودعم العملاء ، والبحث والتطوير ، وتمكين الاتصال بين المستخدمين ، وفيما يتعلق بالإجراءات والمتطلبات القانونية ، كل على النحو الموضح في \"كيف نستخدم الشخصية البيانات \"أدناه. قد تتم مشاركة بيانات المستخدم الضيف مع جهات خارجية لهذه الأغراض. قد تكون هذه البيانات مرتبطة بمالك هذا الحساب ويمكن الوصول إليه من قبله. يشمل هذا تحديدًا المستخدمين الضيوف الذين يتلقون المشاوير / التوصيلات التي طلبها مالكو حسابات Uber Health أو Uber Central أو Uber Direct أو Uber for Business ، أو الذين يتلقون المشاوير أو التوصيلات التي طلبها الأصدقاء أو أفراد العائلة أو غيرهم. لتقديم أسئلة أو تعليقات أو شكاوى بخصوص بيانات المستخدم الضيف ، أو لتقديم طلبات بخصوص هذه البيانات', 1, '2021-03-01 12:43:33', '2022-05-20 05:08:57', 2),
(4, 'driver_data', 'Devices data', 'We collect data generated by rental devices, such as bicycles, scooters, or other light electric vehicles or devices, when they’re in use. This includes the date and time of use, and the location, route, and distance traveled. To the extent permitted by law, the location data collected from the rental device during the trip will be linked to the renter’s account, even if they have not enabled Uber to collect location data from their mobile device. In certain jurisdictions, and where permitted by law, users can record the audio of their trips through an in-app feature. Recordings are encrypted and stored on users’ devices, and are only shared with Uber if submitted to customer support by the users in connection with safety incidents.', 'بيانات الأجهزة', 'نجمع البيانات التي تم إنشاؤها بواسطة الأجهزة المستأجرة ، مثل الدراجات أو الدراجات البخارية أو غيرها من المركبات أو الأجهزة الكهربائية الخفيفة ، عندما تكون قيد الاستخدام. يتضمن ذلك تاريخ ووقت الاستخدام والموقع والطريق والمسافة المقطوعة. إلى الحد الذي يسمح به القانون ، سيتم ربط بيانات الموقع التي تم جمعها من الجهاز المستأجر أثناء الرحلة بحساب المستأجر ، حتى إذا لم يتم تمكين أوبر من جمع بيانات الموقع من أجهزتهم المحمولة. في بعض الولايات القضائية ، وحيثما يسمح القانون ، يمكن للمستخدمين تسجيل صوت رحلاتهم من خلال ميزة داخل التطبيق. يتم تشفير التسجيلات وتخزينها على أجهزة المستخدمين ، ولا تتم مشاركتها إلا مع Uber إذا تم إرسالها إلى دعم العملاء من قبل المستخدمين فيما يتعلق بحوادث السلامة.', 1, '2021-03-01 12:45:00', '2022-05-20 05:09:11', 2),
(5, 'information', 'Information', 'We collect information you provide to us in connection with our Services, such as when you create or update your account, log into your account, request or reserve transportation, contact our customer service, or otherwise communicate with us via telephone, our websites, mobile applications or otherwise. This information includes your name, telephone number, email address, mailing address, photographs or other text or images you use, e.g., for your profile, the types of Services you request (collectively, “Personal Information”), as well as transaction details, billing and payment information, and other information you use or provide to us in using the Services. If you do not wish to provide any Personal Information, you may simply decline to use our Services.', 'معلومة', 'نقوم بجمع المعلومات التي تقدمها لنا فيما يتعلق بخدماتنا ، على سبيل المثال عندما تقوم بإنشاء أو تحديث حسابك ، أو تسجيل الدخول إلى حسابك ، أو طلب أو حجز وسيلة نقل ، أو الاتصال بخدمة العملاء لدينا ، أو التواصل معنا بطريقة أخرى عبر الهاتف ، أو مواقعنا الإلكترونية ، أو الهاتف المحمول. التطبيقات أو غير ذلك. تتضمن هذه المعلومات اسمك ورقم هاتفك وعنوان بريدك الإلكتروني وعنوانك البريدي والصور الفوتوغرافية أو النصوص أو الصور الأخرى التي تستخدمها ، على سبيل المثال ، لملفك الشخصي ، وأنواع الخدمات التي تطلبها (يُشار إليها إجمالاً باسم \"المعلومات الشخصية\") ، فضلاً عن تفاصيل المعاملة ومعلومات الفوترة والدفع والمعلومات الأخرى التي تستخدمها أو تزودنا بها في استخدام الخدمات. إذا كنت لا ترغب في تقديم أي معلومات شخصية ، يمكنك ببساطة رفض استخدام خدماتنا.', 1, '2021-05-15 09:53:59', '2022-12-26 10:56:12', 1),
(6, 'location', 'Location', 'In order for us to be able to provide your requested Services, you will need to grant us permission to obtain your geographic location from your device. Thereafter, you can disable this function in the settings of your device, understanding that you may not be able to avail yourself of our Services that require your location.', 'موقع', 'لكي نتمكن من تقديم الخدمات المطلوبة ، ستحتاج إلى منحنا إذنًا بالحصول على موقعك الجغرافي من جهازك. بعد ذلك ، يمكنك تعطيل هذه الوظيفة في إعدادات جهازك ، مع العلم أنك قد لا تتمكن من الاستفادة من خدماتنا التي تتطلب موقعك.', 1, '2021-05-15 09:54:57', '2022-05-20 05:09:55', 1),
(7, 'driver_notes', 'Driver notes', 'The personal data of those who order or receive trips or deliveries via partner websites or apps (such as when ordering from a restaurant or grocery store), or arranged by other account owners (collectively “Guest Users”) is used solely to provide such trips, deliveries, or other services requested through a third party, and for purposes of safety and security, customer support, research and development, enabling communication between users, and in connection with legal proceedings and requirements, each as described in “How we use personal data” below. Guest User data may be shared with third parties for these purposes. Such data may be associated with, and accessible by, the owner of that account. This specifically includes Guest Users who receive rides/deliveries ordered by owners of Uber Health, Uber Central, Uber Direct or Uber for Business accounts, or who receive rides or deliveries ordered by friends, family members or others. To submit questions, comments or complaints regarding Guest User data, or to submit requests regarding such data', 'ملاحظات السائق', 'تُستخدم البيانات الشخصية لأولئك الذين يطلبون أو يستقبلون الرحلات أو التوصيلات عبر مواقع الويب أو التطبيقات الشريكة (على سبيل المثال عند الطلب من مطعم أو متجر بقالة) ، أو يتم ترتيبها بواسطة مالكي حسابات آخرين (يشار إليهم جميعًا باسم \"المستخدمون الضيوف\") فقط لتوفير هذه الرحلات أو عمليات التسليم أو الخدمات الأخرى المطلوبة من خلال طرف ثالث ، ولأغراض السلامة والأمن ، ودعم العملاء ، والبحث والتطوير ، وتمكين الاتصال بين المستخدمين ، وفيما يتعلق بالإجراءات والمتطلبات القانونية ، كل على النحو الموضح في \"كيف نستخدم الشخصية البيانات \"أدناه. قد تتم مشاركة بيانات المستخدم الضيف مع جهات خارجية لهذه الأغراض. قد تكون هذه البيانات مرتبطة بمالك هذا الحساب ويمكن الوصول إليه من قبله. يشمل هذا تحديدًا المستخدمين الضيوف الذين يتلقون المشاوير / التوصيلات التي طلبها مالكو حسابات Uber Health أو Uber Central أو Uber Direct أو Uber for Business ، أو الذين يتلقون المشاوير أو التوصيلات التي طلبها الأصدقاء أو أفراد العائلة أو غيرهم. لتقديم أسئلة أو تعليقات أو شكاوى بخصوص بيانات المستخدم الضيف ، أو لتقديم طلبات بخصوص هذه البيانات', 1, '2021-05-15 09:56:05', '2022-05-20 05:11:14', 2),
(8, 'devices_data', 'Devices data', 'We collect data generated by rental devices, such as bicycles, scooters, or other light electric vehicles or devices, when they’re in use. This includes the date and time of use, and the location, route, and distance traveled. To the extent permitted by law, the location data collected from the rental device during the trip will be linked to the renter’s account, even if they have not enabled Uber to collect location data from their mobile device. In certain jurisdictions, and where permitted by law, users can record the audio of their trips through an in-app feature. Recordings are encrypted and stored on users’ devices, and are only shared with Uber if submitted to customer support by the users in connection with safety incidents.', 'بيانات الأجهزة', 'نجمع البيانات التي تم إنشاؤها بواسطة الأجهزة المستأجرة ، مثل الدراجات أو الدراجات البخارية أو غيرها من المركبات أو الأجهزة الكهربائية الخفيفة ، عندما تكون قيد الاستخدام. يتضمن ذلك تاريخ ووقت الاستخدام والموقع والطريق والمسافة المقطوعة. إلى الحد الذي يسمح به القانون ، سيتم ربط بيانات الموقع التي تم جمعها من الجهاز المستأجر أثناء الرحلة بحساب المستأجر ، حتى إذا لم يتم تمكين أوبر من جمع بيانات الموقع من أجهزتهم المحمولة. في بعض الولايات القضائية ، وحيثما يسمح القانون ، يمكن للمستخدمين تسجيل صوت رحلاتهم من خلال ميزة داخل التطبيق. يتم تشفير التسجيلات وتخزينها على أجهزة المستخدمين ، ولا تتم مشاركتها إلا مع Uber إذا تم إرسالها إلى دعم العملاء من قبل المستخدمين فيما يتعلق بحوادث السلامة.', 1, '2021-05-15 09:57:05', '2022-05-20 05:11:43', 2),
(9, 'cancellation_policy', 'Cancellation Policies', 'The minimum night stay at your property. Simply click Edit on your base or seasonal rates and select the desired minimum stay\r\nThe changeover day (the day guests can arrive at your property). Either set it to the day of the week that suits you or keep it flexible. Click Edit on your base or seasonal rates, select Require changeover day and choose an option\r\nhe currency your listing is advertised in. Please note that this should be a currency that you can receive payouts in on PayPal or in your bank account\r\nPlease note: any changes you make will only apply to future bookings, not to an existing booking where the guest has paid the booking deposit. This is because you’ve already agreed on the contract - it keeps things secure for both you and your guest.\r\nFor all free listings and most annual listings using online booking, you’ll need to have a rental agreement attached to all quotes. This is to ensure that there are clear terms agreed between you and the guest should any disputes arise. Our standard rental agreement is the default contract automatically attached to your quotes. You can also upload your own one by going to Booking policies.\r\nFor all free listings and most annual listings using online booking, you’ll need to have a rental agreement attached to all quotes. This is to ensure that there are clear terms agreed between you and the guest should any disputes arise. Our standard rental agreement is the default contract automatically attached to your quotes. You can also upload your own one by going to Booking policies.', NULL, NULL, 1, '2022-05-20 06:47:32', '2022-05-20 08:41:20', 1),
(10, 'cancellation_policy', 'Cancellation Policies', 'The minimum night stay at your property. Simply click Edit on your base or seasonal rates and select the desired minimum stay\r\nThe changeover day (the day guests can arrive at your property). Either set it to the day of the week that suits you or keep it flexible. Click Edit on your base or seasonal rates, select Require changeover day and choose an option\r\nhe currency your listing is advertised in. Please note that this should be a currency that you can receive payouts in on PayPal or in your bank account\r\nPlease note: any changes you make will only apply to future bookings, not to an existing booking where the guest has paid the booking deposit. This is because you’ve already agreed on the contract - it keeps things secure for both you and your guest.\r\nFor all free listings and most annual listings using online booking, you’ll need to have a rental agreement attached to all quotes. This is to ensure that there are clear terms agreed between you and the guest should any disputes arise. Our standard rental agreement is the default contract automatically attached to your quotes. You can also upload your own one by going to Booking policies.\r\nFor all free listings and most annual listings using online booking, you’ll need to have a rental agreement attached to all quotes. This is to ensure that there are clear terms agreed between you and the guest should any disputes arise. Our standard rental agreement is the default contract automatically attached to your quotes. You can also upload your own one by going to Booking policies.', NULL, NULL, 1, '2022-05-20 07:14:51', '2022-05-20 08:39:28', 1),
(11, 'cancellation_policy', 'Cancellation Policy', 'The minimum night stay at your property. Simply click Edit on your base or seasonal rates and select the desired minimum stay\r\nThe changeover day (the day guests can arrive at your property). Either set it to the day of the week that suits you or keep it flexible. Click Edit on your base or seasonal rates, select Require changeover day and choose an option\r\nhe currency your listing is advertised in. Please note that this should be a currency that you can receive payouts in on PayPal or in your bank account\r\nPlease note: any changes you make will only apply to future bookings, not to an existing booking where the guest has paid the booking deposit. This is because you’ve already agreed on the contract - it keeps things secure for both you and your guest.\r\nFor all free listings and most annual listings using online booking, you’ll need to have a rental agreement attached to all quotes. This is to ensure that there are clear terms agreed between you and the guest should any disputes arise. Our standard rental agreement is the default contract automatically attached to your quotes. You can also upload your own one by going to Booking policies.\r\nFor all free listings and most annual listings using online booking, you’ll need to have a rental agreement attached to all quotes. This is to ensure that there are clear terms agreed between you and the guest should any disputes arise. Our standard rental agreement is the default contract automatically attached to your quotes. You can also upload your own one by going to Booking policies.', NULL, NULL, 1, '2022-12-02 05:58:22', '2022-12-02 05:58:38', 1);

-- --------------------------------------------------------

--
-- Table structure for table `promo_codes`
--

CREATE TABLE `promo_codes` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL DEFAULT 0,
  `promo_name` varchar(250) NOT NULL,
  `promo_code` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `promo_name_ar` varchar(250) DEFAULT NULL,
  `promo_code_ar` varchar(150) DEFAULT NULL,
  `description_ar` text DEFAULT NULL,
  `promo_type` int(11) NOT NULL,
  `discount` double NOT NULL,
  `min_fare` double NOT NULL DEFAULT 0,
  `max_discount_value` double NOT NULL DEFAULT 0,
  `redemptions` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `promo_codes`
--

INSERT INTO `promo_codes` (`id`, `customer_id`, `promo_name`, `promo_code`, `description`, `promo_name_ar`, `promo_code_ar`, `description_ar`, `promo_type`, `discount`, `min_fare`, `max_discount_value`, `redemptions`, `status`, `created_at`, `updated_at`) VALUES
(1, 0, 'First50', 'FIRST50', '50% offer for first order', 'First50', 'FIRST50', '50% offer for first order', 6, 50, 100, 100, 100, 1, '2022-12-18 13:45:38', '2023-11-09 13:13:06'),
(2, 0, 'Christmas offer', 'Christ', 'Enjoy your christmas offer', NULL, NULL, NULL, 5, 20, 100, 50, 50, 1, '2022-12-23 04:50:53', '2023-10-26 15:55:35'),
(3, 0, 'Get your free ride now', 'FIRSTFREE', 'Get your first ride free for booking rental trip with bike and enjoy your ride.', 'TEST', 'TEST', 'TEST', 5, 100, 50, 200, 50, 1, '2023-03-10 23:36:04', '2023-10-26 14:56:48'),
(4, 1, 'Cust', 'CUST', 'f', 'fix', 'ww', 'w', 6, 41, 100, 10, 50, 1, '2023-11-09 10:09:37', '2023-11-09 11:35:48'),
(5, 4, 'Cust', '1234', '67', 'fix', '33', NULL, 5, 20, 100, 30, 50, 1, '2023-11-09 11:07:55', '2023-11-09 11:07:55'),
(6, 99, 'Gooo', '1', '8', NULL, NULL, NULL, 5, 41, 100, 10, 50, 1, '2023-11-09 13:02:24', '2023-11-09 13:03:22'),
(7, 1, 'customer', '12', '6', 'fix', '9', '9', 5, 41, 100, 10, 50, 1, '2023-11-09 13:10:35', '2023-11-09 13:10:35');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `trip_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `rating` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `referral_settings`
--

CREATE TABLE `referral_settings` (
  `id` int(11) NOT NULL,
  `referral_message` text NOT NULL,
  `referral_message_ar` text DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `referral_settings`
--

INSERT INTO `referral_settings` (`id`, `referral_message`, `referral_message_ar`, `created_at`, `updated_at`) VALUES
(1, 'Hi, when your friends or relatives register with your referral code, you get referral amount added to your wallet.', NULL, '2022-12-18 10:50:12', '2022-12-18 10:50:12');

-- --------------------------------------------------------

--
-- Table structure for table `rental_fare_management`
--

CREATE TABLE `rental_fare_management` (
  `id` int(11) NOT NULL,
  `vehicle_type` int(11) NOT NULL DEFAULT 0,
  `package_id` int(11) NOT NULL DEFAULT 0,
  `price_per_km` double NOT NULL,
  `price_per_hour` double NOT NULL,
  `package_price` double NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rental_fare_management`
--

INSERT INTO `rental_fare_management` (`id`, `vehicle_type`, `package_id`, `price_per_km`, `price_per_hour`, `package_price`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 2, 2, 10, 1, '2023-10-26 14:46:59', '2023-11-06 11:55:22'),
(2, 1, 2, 3, 2, 10, 1, '2023-10-26 14:47:35', '2023-11-06 12:00:23'),
(3, 2, 1, 3, 5, 10, 1, '2023-10-26 14:47:56', '2023-10-26 14:47:56'),
(4, 3, 1, 3, 15, 70, 1, '2023-10-26 14:48:22', '2023-11-17 11:09:37'),
(5, 4, 1, 2, 5, 10, 1, '2023-10-26 14:48:39', '2023-10-26 14:48:39'),
(6, 1, 3, 2, 10, 50, 1, '2023-10-26 15:29:51', '2023-10-27 13:07:33'),
(7, 2, 2, 4, 5, 12, 1, '2023-10-26 15:30:32', '2023-10-26 15:30:32'),
(8, 3, 2, 5, 6, 22, 1, '2023-10-26 15:31:08', '2023-10-26 15:31:08'),
(9, 4, 2, 3, 5, 14, 1, '2023-10-26 15:31:50', '2023-10-26 15:31:50'),
(10, 1, 4, 7, 4, 25, 1, '2023-10-26 15:32:42', '2023-10-26 15:37:53'),
(11, 1, 5, 5, 6, 30, 1, '2023-10-26 15:34:32', '2023-10-26 15:39:26'),
(12, 3, 4, 5, 7, 20, 1, '2023-10-26 15:40:48', '2023-10-26 15:40:48'),
(13, 3, 4, 20, 4, 35, 1, '2023-10-26 15:41:50', '2023-10-26 15:41:50'),
(14, 4, 4, 3, 5, 20, 1, '2023-10-26 15:42:13', '2023-10-26 15:42:13'),
(15, 2, 3, 3, 11, 50, 1, '2023-10-27 13:07:57', '2023-10-27 13:07:57'),
(16, 3, 3, 4, 12, 50, 1, '2023-10-27 13:08:20', '2023-10-27 13:08:20'),
(17, 4, 3, 2, 9, 50, 1, '2023-10-27 13:09:03', '2023-10-27 13:09:03'),
(18, 1, 2, 5, 6, 150, 1, '2023-11-09 12:13:07', '2023-11-09 12:13:07');

-- --------------------------------------------------------

--
-- Table structure for table `scratch_card_settings`
--

CREATE TABLE `scratch_card_settings` (
  `id` int(11) NOT NULL,
  `coupon_type` int(11) NOT NULL DEFAULT 1,
  `lucky_offer` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shared_fare_management`
--

CREATE TABLE `shared_fare_management` (
  `id` int(11) NOT NULL,
  `vehicle_type` int(11) NOT NULL DEFAULT 1,
  `base_fare` varchar(250) NOT NULL,
  `price_per_km` varchar(250) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shared_fare_management`
--

INSERT INTO `shared_fare_management` (`id`, `vehicle_type`, `base_fare`, `price_per_km`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, '50', '1', 1, '2023-10-26 14:53:50', '2023-11-06 12:03:28'),
(2, 2, '50', '2', 1, '2023-10-26 14:54:06', '2023-11-06 12:03:41'),
(3, 3, '100', '3', 1, '2023-10-26 14:54:30', '2023-11-06 12:04:22'),
(4, 4, '150', '5', 1, '2023-10-26 14:54:47', '2023-11-06 12:04:36');

-- --------------------------------------------------------

--
-- Table structure for table `shared_trip_settings`
--

CREATE TABLE `shared_trip_settings` (
  `id` int(11) NOT NULL,
  `pickup_radius` double NOT NULL,
  `drop_radius` double NOT NULL,
  `max_bookings` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shared_trip_settings`
--

INSERT INTO `shared_trip_settings` (`id`, `pickup_radius`, `drop_radius`, `max_bookings`, `created_at`, `updated_at`) VALUES
(1, 5, 5, 2, '2022-12-29 10:20:52', '2022-12-29 10:20:52'),
(2, 4, 7, 2, '2023-11-09 11:13:06', '2023-11-09 11:13:06');

-- --------------------------------------------------------

--
-- Table structure for table `statuses`
--

CREATE TABLE `statuses` (
  `id` int(11) NOT NULL,
  `type` varchar(250) NOT NULL,
  `name` varchar(250) NOT NULL,
  `name_ar` varchar(250) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `statuses`
--

INSERT INTO `statuses` (`id`, `type`, `name`, `name_ar`, `created_at`, `updated_at`) VALUES
(1, 'general', 'Active', 'نشيط', '2020-04-04 00:00:00', '2021-03-06 08:23:29'),
(2, 'general', 'InActive', 'غير نشط', '2020-04-04 00:00:00', '2021-03-06 08:23:56'),
(3, 'enable_disable', 'Enable', 'يمكن', '2020-04-06 00:00:00', '2021-03-06 08:24:25'),
(4, 'enable_disable', 'Disable', 'تعطيل', '2020-04-06 00:00:00', '2021-03-06 08:24:53'),
(5, 'promo_type', 'Fixed', 'مثبت', '2020-02-20 08:05:49', '2021-03-06 08:25:34'),
(6, 'promo_type', 'Percentage', 'النسبة المئوية', '2020-02-20 08:44:49', '2021-03-06 08:26:08'),
(7, 'user_type', 'Customer', 'عميل', '2020-02-20 08:44:49', '2021-03-06 08:27:24'),
(8, 'user_type', 'Driver', 'سائق', '2020-02-20 08:44:49', '2021-03-06 08:27:53'),
(9, 'trip_status', 'Accepted', 'وافقت', '2020-10-03 18:54:54', '2021-03-06 08:28:24'),
(10, 'trip_status', 'Completed', 'مكتمل', '2020-10-03 18:54:54', '2021-03-06 08:28:42'),
(11, 'withdrawal', 'Pending', 'قيد الانتظار', '2020-10-03 23:37:18', '2021-03-06 08:29:25'),
(12, 'withdrawal', 'Completed', 'مكتمل', '2020-10-03 23:37:18', '2021-03-06 08:29:46'),
(13, 'withdrawal', 'Rejected', 'مرفوض', '2020-10-03 23:37:18', '2021-03-06 08:30:05'),
(14, 'verification_status', 'Waiting For Upload', 'في انتظار التحميل', '2021-03-08 06:03:00', '2021-03-09 15:15:13'),
(15, 'verification_status', 'Waiting for Approval', 'بانتظار الموافقة', '2021-03-08 06:04:19', '2021-03-09 15:15:50'),
(16, 'verification_status', 'Approved', 'وافق', '2021-03-08 06:07:50', '2021-03-09 15:16:20'),
(17, 'verification_status', 'Rejected', 'مرفوض', '2021-03-09 15:16:55', '2021-03-09 15:16:55'),
(18, 'vehicle_mode', 'Passenger Vehicle', 'سيارة ركاب', '2021-04-12 04:35:23', '2021-04-12 04:35:23'),
(19, 'vehicle_mode', 'Commercial Vehicle', 'مركبة تجارية', '2021-04-12 04:36:06', '2021-04-12 04:36:06'),
(21, 'customer_complaints', 'Initiated', 'بدأت', '2023-03-11 11:49:56', '2023-03-11 11:49:56'),
(22, 'customer_complaints', 'Resolved', 'تم الحل', '2023-03-11 11:50:35', '2023-03-11 11:50:35'),
(23, 'customer_complaints', 'Processing', 'يعالج', '2023-03-11 11:51:41', '2023-03-11 11:51:41'),
(24, 'general', 'Madurai', 'Madurai', '2023-11-09 10:47:09', '2023-11-09 16:38:17');

-- --------------------------------------------------------

--
-- Table structure for table `stops`
--

CREATE TABLE `stops` (
  `id` int(11) NOT NULL,
  `trip_request_id` int(11) NOT NULL,
  `trip_id` int(11) NOT NULL DEFAULT 0,
  `address` varchar(250) NOT NULL,
  `latitude` varchar(100) NOT NULL,
  `longitude` varchar(100) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` int(11) NOT NULL,
  `sub_name` varchar(250) NOT NULL,
  `sub_image` varchar(250) NOT NULL,
  `sub_description` text NOT NULL,
  `amount` double NOT NULL DEFAULT 0,
  `free_bookings` int(11) NOT NULL DEFAULT 0,
  `validity` int(11) NOT NULL DEFAULT 0,
  `validity_label` varchar(250) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `surge_fare_settings`
--

CREATE TABLE `surge_fare_settings` (
  `id` int(11) NOT NULL,
  `surge` float NOT NULL,
  `requests` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `surge_fare_settings`
--

INSERT INTO `surge_fare_settings` (`id`, `surge`, `requests`, `created_at`, `updated_at`) VALUES
(1, 1.5, 1, '2022-12-03 04:11:09', '2022-12-03 04:11:09'),
(2, 2, 2, '2022-12-03 04:11:23', '2022-12-03 04:11:23'),
(3, 2.5, 3, '2022-12-03 04:11:34', '2022-12-03 04:11:34'),
(4, 3, 4, '2022-12-03 04:11:42', '2022-12-03 04:11:42'),
(5, 3.5, 6, '2023-10-04 00:10:23', '2023-10-04 00:10:23');

-- --------------------------------------------------------

--
-- Table structure for table `surge_settings`
--

CREATE TABLE `surge_settings` (
  `id` int(11) NOT NULL,
  `searching_time` int(11) NOT NULL,
  `minimum_trips` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `surge_settings`
--

INSERT INTO `surge_settings` (`id`, `searching_time`, `minimum_trips`, `created_at`, `updated_at`) VALUES
(1, 300, 10, '2022-12-03 04:55:52', '2023-10-04 02:44:39');

-- --------------------------------------------------------

--
-- Table structure for table `tax_lists`
--

CREATE TABLE `tax_lists` (
  `id` int(11) NOT NULL,
  `tax_name` varchar(250) NOT NULL,
  `percent` double NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tax_lists`
--

INSERT INTO `tax_lists` (`id`, `tax_name`, `percent`, `status`, `created_at`, `updated_at`) VALUES
(1, 'GST', 5, 1, '2023-05-19 17:36:09', '2023-05-19 17:36:09'),
(2, 'GST', 3, 1, '2023-11-09 11:58:36', '2023-11-09 11:58:36');

-- --------------------------------------------------------

--
-- Table structure for table `tips`
--

CREATE TABLE `tips` (
  `id` int(11) NOT NULL,
  `tip` double NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `payment_method` int(11) NOT NULL,
  `type` varchar(250) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trips`
--

CREATE TABLE `trips` (
  `id` int(11) NOT NULL,
  `trip_id` varchar(250) DEFAULT NULL,
  `customer_id` int(11) NOT NULL,
  `trip_type` int(11) NOT NULL DEFAULT 0,
  `trip_sub_type` int(11) NOT NULL DEFAULT 0,
  `booking_type` int(11) NOT NULL DEFAULT 1,
  `package_id` int(11) NOT NULL DEFAULT 0,
  `driver_id` int(11) NOT NULL,
  `pickup_date` datetime NOT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `pickup_address` varchar(250) NOT NULL,
  `actual_pickup_address` text DEFAULT NULL,
  `actual_pickup_lat` varchar(100) DEFAULT NULL,
  `actual_pickup_lng` varchar(100) DEFAULT NULL,
  `pickup_lat` varchar(250) NOT NULL,
  `pickup_lng` varchar(250) NOT NULL,
  `drop_address` text DEFAULT NULL,
  `actual_drop_address` text DEFAULT NULL,
  `actual_drop_lat` varchar(100) NOT NULL DEFAULT '0',
  `actual_drop_lng` varchar(100) NOT NULL DEFAULT '0',
  `drop_lat` varchar(250) NOT NULL DEFAULT '0',
  `drop_lng` varchar(250) NOT NULL DEFAULT '0',
  `zone` int(11) NOT NULL,
  `distance` double NOT NULL DEFAULT 0,
  `vehicle_id` int(11) NOT NULL,
  `vehicle_type` int(11) NOT NULL DEFAULT 0,
  `payment_method` int(11) NOT NULL,
  `total` double NOT NULL,
  `collection_amount` double NOT NULL DEFAULT 0,
  `sub_total` double NOT NULL DEFAULT 0,
  `discount` double NOT NULL,
  `tax` double NOT NULL DEFAULT 0,
  `promo_code` int(11) NOT NULL,
  `tip` double NOT NULL DEFAULT 0,
  `otp` int(4) NOT NULL,
  `ratings` varchar(50) NOT NULL DEFAULT '0',
  `customer_rating` double NOT NULL DEFAULT 0,
  `static_map` varchar(250) DEFAULT NULL,
  `is_multiple_drops` int(11) NOT NULL DEFAULT 0,
  `is_subscription_trip` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `surge` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trip_cancellations`
--

CREATE TABLE `trip_cancellations` (
  `id` int(11) NOT NULL,
  `trip_id` int(11) NOT NULL,
  `reason_id` int(11) NOT NULL,
  `cancelled_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trip_histories`
--

CREATE TABLE `trip_histories` (
  `id` int(11) NOT NULL,
  `trip_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trip_requests`
--

CREATE TABLE `trip_requests` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL DEFAULT 0,
  `package_id` int(11) NOT NULL DEFAULT 0,
  `distance` double NOT NULL DEFAULT 0,
  `vehicle_type` int(11) NOT NULL,
  `trip_type` int(11) NOT NULL DEFAULT 1,
  `trip_sub_type` int(11) NOT NULL DEFAULT 0,
  `booking_type` int(11) NOT NULL DEFAULT 1,
  `pickup_address` text NOT NULL,
  `pickup_date` datetime NOT NULL,
  `pickup_lat` varchar(250) NOT NULL,
  `pickup_lng` varchar(250) NOT NULL,
  `drop_address` text DEFAULT NULL,
  `drop_lat` varchar(250) NOT NULL DEFAULT '0',
  `drop_lng` varchar(250) NOT NULL DEFAULT '0',
  `surge` double NOT NULL DEFAULT 1,
  `zone` int(11) NOT NULL,
  `payment_method` int(11) NOT NULL DEFAULT 0,
  `total` double NOT NULL DEFAULT 0,
  `sub_total` double NOT NULL DEFAULT 0,
  `promo` int(11) NOT NULL,
  `discount` double NOT NULL DEFAULT 0,
  `tax` double NOT NULL DEFAULT 0,
  `static_map` varchar(250) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `is_multiple_drops` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trip_request_statuses`
--

CREATE TABLE `trip_request_statuses` (
  `id` int(11) NOT NULL,
  `status` varchar(100) NOT NULL DEFAULT '1',
  `status_ar` varchar(150) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `trip_request_statuses`
--

INSERT INTO `trip_request_statuses` (`id`, `status`, `status_ar`, `created_at`, `updated_at`) VALUES
(1, 'Booking Placed', 'تم وضع الحجز', '2021-02-26 09:55:55', '2021-03-06 08:32:50'),
(2, 'Ride Later', 'اركب لاحقًا', '2021-02-26 09:55:55', '2021-03-06 08:33:12'),
(3, 'Accepted', 'وافقت', '2021-02-26 09:55:55', '2021-03-06 08:33:31'),
(4, 'Not Picked', 'غير منتقى', '2021-02-26 09:55:55', '2021-03-06 08:33:49'),
(5, 'Timeout', 'نفذ الوقت', '2021-02-26 09:55:55', '2021-03-06 08:34:05'),
(6, 'Cancelled by customer', 'ألغى العميل', '2021-02-26 09:55:55', '2021-03-06 08:34:05');

-- --------------------------------------------------------

--
-- Table structure for table `trip_settings`
--

CREATE TABLE `trip_settings` (
  `id` int(11) NOT NULL,
  `admin_commission` double NOT NULL DEFAULT 20,
  `maximum_searching_time` int(11) DEFAULT NULL,
  `booking_searching_radius` double NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `trip_settings`
--

INSERT INTO `trip_settings` (`id`, `admin_commission`, `maximum_searching_time`, `booking_searching_radius`, `created_at`, `updated_at`) VALUES
(1, 10, 30, 1000, '2021-01-06 07:46:51', '2023-12-24 03:22:21');

-- --------------------------------------------------------

--
-- Table structure for table `trip_sub_types`
--

CREATE TABLE `trip_sub_types` (
  `id` int(11) NOT NULL,
  `trip_type` int(11) NOT NULL,
  `trip_sub_type` varchar(100) NOT NULL,
  `trip_sub_type_ar` varchar(100) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `trip_sub_types`
--

INSERT INTO `trip_sub_types` (`id`, `trip_type`, `trip_sub_type`, `trip_sub_type_ar`, `created_at`, `updated_at`) VALUES
(1, 3, 'Rounded Trip', NULL, '2022-05-10 05:40:15', '2022-05-10 05:40:15'),
(2, 3, 'One Way Trip', NULL, '2022-05-10 05:40:40', '2022-05-10 05:40:40'),
(3, 4, 'Rounded Trip', NULL, '2022-05-10 05:40:55', '2022-05-10 05:40:55'),
(4, 4, 'One Way Trip', NULL, '2022-05-10 05:41:08', '2022-05-10 05:41:08'),
(5, 9, 'Rounded  Trip', NULL, '2023-10-26 16:24:59', '2023-11-16 16:16:00'),
(6, 9, 'one-way Trip', NULL, '2023-10-26 16:29:58', '2023-11-16 16:16:16'),
(7, 9, 'Rounded  Trip', NULL, '2023-10-26 16:32:02', '2023-11-16 16:02:26'),
(8, 9, 'one-way Trip', NULL, '2023-10-26 16:32:16', '2023-11-16 16:02:42'),
(9, 9, 'Rounded  Trip', NULL, '2023-10-26 16:33:00', '2023-11-16 16:16:47'),
(10, 9, 'one-way Trip', NULL, '2023-10-26 16:33:23', '2023-11-16 16:17:10');

-- --------------------------------------------------------

--
-- Table structure for table `trip_types`
--

CREATE TABLE `trip_types` (
  `id` int(11) NOT NULL,
  `active_icon` varchar(250) NOT NULL,
  `Inactive_icon` varchar(250) NOT NULL,
  `name` varchar(250) NOT NULL,
  `vehicle_mode` int(11) NOT NULL,
  `name_ar` varchar(150) DEFAULT NULL,
  `sort` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `trip_types`
--

INSERT INTO `trip_types` (`id`, `active_icon`, `Inactive_icon`, `name`, `vehicle_mode`, `name_ar`, `sort`, `status`, `created_at`, `updated_at`) VALUES
(1, 'trip_types//Daily-Ac.webp', 'trip_types//Daily-In.webp', 'Daily', 18, 'يوميا', 1, 1, '2021-03-01 10:37:21', '2023-06-15 13:20:33'),
(2, 'trip_types//Rental-Ac.webp', 'trip_types//Rental-In.webp', 'Rental', 18, 'تأجير', 3, 1, '2021-03-01 10:38:00', '2023-03-11 15:04:55'),
(3, 'trip_types//Outstation-Ac.webp', 'trip_types//Outstation-In.webp', 'Outstation', 18, 'النائية', 4, 1, '2021-03-01 10:38:44', '2023-03-11 15:05:20'),
(4, 'trip_types//Commercial_Ac-removebg-preview.webp', 'trip_types//Commercial In.webp', 'Delivery', 19, 'توصيل', 5, 1, '2021-04-12 04:29:08', '2023-12-26 11:19:26'),
(5, 'trip_types//Shared-Ac.webp', 'trip_types//Shared-In.webp', 'Shared', 18, 'مشترك', 2, 1, '2021-03-01 10:37:21', '2023-04-23 12:49:31');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_types`
--

CREATE TABLE `user_types` (
  `id` int(11) NOT NULL,
  `type_name` varchar(250) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_types`
--

INSERT INTO `user_types` (`id`, `type_name`, `created_at`, `updated_at`) VALUES
(1, 'Customer', '2021-03-01 09:58:53', '2021-03-01 09:58:53'),
(2, 'Driver', '2021-03-01 09:59:15', '2021-03-01 09:59:15');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_categories`
--

CREATE TABLE `vehicle_categories` (
  `id` int(11) NOT NULL,
  `vehicle_type` varchar(250) NOT NULL,
  `vehicle_mode` int(11) NOT NULL,
  `base_fare` double NOT NULL DEFAULT 0,
  `price_per_km` double NOT NULL DEFAULT 0,
  `active_icon` varchar(250) NOT NULL,
  `inactive_icon` varchar(250) DEFAULT NULL,
  `description` text NOT NULL,
  `vehicle_type_ar` varchar(150) DEFAULT NULL,
  `description_ar` text DEFAULT NULL,
  `vehicle_slug` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vehicle_categories`
--

INSERT INTO `vehicle_categories` (`id`, `vehicle_type`, `vehicle_mode`, `base_fare`, `price_per_km`, `active_icon`, `inactive_icon`, `description`, `vehicle_type_ar`, `description_ar`, `vehicle_slug`, `status`, `created_at`, `updated_at`) VALUES
(1, 'HatchBack', 18, 0, 0, 'vehicle_categories//becd7cc701f9a719394cd06f39bc5540.png', 'vehicle_categories//0311701279d8ec08f856250b47880a3b.png', 'Image result for hatchback description\r\nA hatchback is a car body configuration with a rear door that swings upward to provide access to a cargo area.', 'Hatchback', 'Comfy, economical cars', 1, 1, '2022-12-18 10:54:06', '2023-10-25 10:52:14'),
(2, 'Sedan', 18, 0, 0, 'vehicle_categories//5fde9103792fd174594467b87ce77c29.png', 'vehicle_categories//7f83d6786f72f64e018dfd721958c117.png', 'Top sedans', 'Sedan', 'A sedan is defined as a 4-door passenger car with a trunk that is separate from the passengers with a three-box body: the engine, the area for passengers, and the trunk.', 1, 1, '2022-12-18 10:55:32', '2023-03-18 19:28:34'),
(3, 'SUV', 18, 0, 0, 'vehicle_categories//a06a6bc46d9d888c89f4224641fa5fec.png', 'vehicle_categories//86204de34697c15e0f3637112d3d8105.png', 'Spacious SUVs', 'SUV', 'The term \'SUV\' is car industry jargon that\'s an acronym for Sports Utility Vehicle. It refers to a type of car that sits high off the ground and which often has four-wheel drive and rugged styling.', 1, 1, '2022-12-18 10:57:18', '2023-03-18 19:29:07'),
(4, 'Bike', 18, 0, 0, 'vehicle_categories//446a126c016da340aa4669858cf02835.png', 'vehicle_categories//f3691f9de9bd49cca0d6363b360aac5c.png', 'bike', 'bike', 'bike', 2, 1, '2022-12-18 10:58:42', '2023-03-11 12:57:00'),
(9, 'Truck', 19, 0, 0, 'vehicle_categories//3f313041df8fcff128c585d8f4522316.jpeg', 'vehicle_categories//4c4c79b631290d81934a30408debb8fd.jpeg', 'Truck will be used as a commercial vehicle for  transfering goods from one place to another.', 'Truck', 'Truck will be used as a commercial vehicle for  transfering goods from one place to another.', 3, 1, '2023-01-24 15:26:14', '2023-03-11 12:57:36'),
(10, 'mini', 19, 0, 0, 'vehicle_categories//ef3a23face6c30d99245d0ae11a7941c.png', 'vehicle_categories//63d4f6a034a3d0405093d70c596305d6.png', 'dd', NULL, NULL, 3, 1, '2023-12-24 03:43:31', '2023-12-24 03:43:31');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_slugs`
--

CREATE TABLE `vehicle_slugs` (
  `id` int(11) NOT NULL,
  `slug` varchar(250) NOT NULL,
  `icon` varchar(250) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vehicle_slugs`
--

INSERT INTO `vehicle_slugs` (`id`, `slug`, `icon`, `created_at`, `updated_at`) VALUES
(1, 'car', 'customers/de6330ed5ced5c006fe4347a5977a6dc.png', '2023-03-11 12:55:36', '2023-03-11 12:55:36'),
(2, 'bike', 'customers/8be67d1b1fb61e19495cda430d0703cd.png', '2023-03-11 12:55:52', '2023-03-11 12:55:52'),
(3, 'truck', 'customers/467a757156d8a00931fc38681f6823cd.png', '2023-03-11 12:56:05', '2023-03-11 12:56:05'),
(4, 'sedan', 'customers/69f12f854d2a98d7f45dd2d88b88ec47.jpg', '2023-10-26 16:01:01', '2023-10-26 16:03:55');

-- --------------------------------------------------------

--
-- Table structure for table `zones`
--

CREATE TABLE `zones` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `name_ar` varchar(250) NOT NULL,
  `polygon` text DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_menu`
--
ALTER TABLE `admin_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_operation_log`
--
ALTER TABLE `admin_operation_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_operation_log_user_id_index` (`user_id`);

--
-- Indexes for table `admin_permissions`
--
ALTER TABLE `admin_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admin_permissions_name_unique` (`name`),
  ADD UNIQUE KEY `admin_permissions_slug_unique` (`slug`);

--
-- Indexes for table `admin_roles`
--
ALTER TABLE `admin_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admin_roles_name_unique` (`name`),
  ADD UNIQUE KEY `admin_roles_slug_unique` (`slug`);

--
-- Indexes for table `admin_role_menu`
--
ALTER TABLE `admin_role_menu`
  ADD KEY `admin_role_menu_role_id_menu_id_index` (`role_id`,`menu_id`);

--
-- Indexes for table `admin_role_permissions`
--
ALTER TABLE `admin_role_permissions`
  ADD KEY `admin_role_permissions_role_id_permission_id_index` (`role_id`,`permission_id`);

--
-- Indexes for table `admin_role_users`
--
ALTER TABLE `admin_role_users`
  ADD KEY `admin_role_users_role_id_user_id_index` (`role_id`,`user_id`);

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admin_users_username_unique` (`username`);

--
-- Indexes for table `admin_user_permissions`
--
ALTER TABLE `admin_user_permissions`
  ADD KEY `admin_user_permissions_user_id_permission_id_index` (`user_id`,`permission_id`);

--
-- Indexes for table `app_settings`
--
ALTER TABLE `app_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_versions`
--
ALTER TABLE `app_versions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `booking_statuses`
--
ALTER TABLE `booking_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cancellation_reasons`
--
ALTER TABLE `cancellation_reasons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cancellation_settings`
--
ALTER TABLE `cancellation_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `complaint_categories`
--
ALTER TABLE `complaint_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `complaint_sub_categories`
--
ALTER TABLE `complaint_sub_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_settings`
--
ALTER TABLE `contact_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_chat_messages`
--
ALTER TABLE `customer_chat_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_favourites`
--
ALTER TABLE `customer_favourites`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_offers`
--
ALTER TABLE `customer_offers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_promo_histories`
--
ALTER TABLE `customer_promo_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_sos_contacts`
--
ALTER TABLE `customer_sos_contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_subscription_histories`
--
ALTER TABLE `customer_subscription_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_wallet_histories`
--
ALTER TABLE `customer_wallet_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `daily_fare_management`
--
ALTER TABLE `daily_fare_management`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivery_fare_management`
--
ALTER TABLE `delivery_fare_management`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dispatch_trips`
--
ALTER TABLE `dispatch_trips`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `drivers`
--
ALTER TABLE `drivers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `driver_bank_kyc_details`
--
ALTER TABLE `driver_bank_kyc_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `driver_checkins`
--
ALTER TABLE `driver_checkins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `driver_commissions`
--
ALTER TABLE `driver_commissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `driver_earnings`
--
ALTER TABLE `driver_earnings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `driver_hiring_fare_management`
--
ALTER TABLE `driver_hiring_fare_management`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `driver_hiring_requests`
--
ALTER TABLE `driver_hiring_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `driver_hiring_statuses`
--
ALTER TABLE `driver_hiring_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `driver_queries`
--
ALTER TABLE `driver_queries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `driver_recharges`
--
ALTER TABLE `driver_recharges`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `driver_tips`
--
ALTER TABLE `driver_tips`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `driver_trip_requests`
--
ALTER TABLE `driver_trip_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `driver_tutorials`
--
ALTER TABLE `driver_tutorials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `driver_vehicles`
--
ALTER TABLE `driver_vehicles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `driver_wallet_histories`
--
ALTER TABLE `driver_wallet_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `driver_withdrawals`
--
ALTER TABLE `driver_withdrawals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fare_management`
--
ALTER TABLE `fare_management`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feature_settings`
--
ALTER TABLE `feature_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `instant_offers`
--
ALTER TABLE `instant_offers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lucky_offers`
--
ALTER TABLE `lucky_offers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mail_contents`
--
ALTER TABLE `mail_contents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `missed_trip_requests`
--
ALTER TABLE `missed_trip_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification_messages`
--
ALTER TABLE `notification_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `offer_types`
--
ALTER TABLE `offer_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `outstation_fare_management`
--
ALTER TABLE `outstation_fare_management`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `payment_histories`
--
ALTER TABLE `payment_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_types`
--
ALTER TABLE `payment_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `privacy_policies`
--
ALTER TABLE `privacy_policies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `promo_codes`
--
ALTER TABLE `promo_codes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `referral_settings`
--
ALTER TABLE `referral_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rental_fare_management`
--
ALTER TABLE `rental_fare_management`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scratch_card_settings`
--
ALTER TABLE `scratch_card_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shared_fare_management`
--
ALTER TABLE `shared_fare_management`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shared_trip_settings`
--
ALTER TABLE `shared_trip_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `statuses`
--
ALTER TABLE `statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stops`
--
ALTER TABLE `stops`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `surge_fare_settings`
--
ALTER TABLE `surge_fare_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `surge_settings`
--
ALTER TABLE `surge_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tax_lists`
--
ALTER TABLE `tax_lists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tips`
--
ALTER TABLE `tips`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trips`
--
ALTER TABLE `trips`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trip_cancellations`
--
ALTER TABLE `trip_cancellations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trip_requests`
--
ALTER TABLE `trip_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trip_request_statuses`
--
ALTER TABLE `trip_request_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trip_settings`
--
ALTER TABLE `trip_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trip_sub_types`
--
ALTER TABLE `trip_sub_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trip_types`
--
ALTER TABLE `trip_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_types`
--
ALTER TABLE `user_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vehicle_categories`
--
ALTER TABLE `vehicle_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vehicle_slugs`
--
ALTER TABLE `vehicle_slugs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zones`
--
ALTER TABLE `zones`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_menu`
--
ALTER TABLE `admin_menu`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT for table `admin_operation_log`
--
ALTER TABLE `admin_operation_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `admin_permissions`
--
ALTER TABLE `admin_permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `admin_roles`
--
ALTER TABLE `admin_roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `app_settings`
--
ALTER TABLE `app_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `app_versions`
--
ALTER TABLE `app_versions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `booking_statuses`
--
ALTER TABLE `booking_statuses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `cancellation_reasons`
--
ALTER TABLE `cancellation_reasons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `cancellation_settings`
--
ALTER TABLE `cancellation_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `complaint_categories`
--
ALTER TABLE `complaint_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `complaint_sub_categories`
--
ALTER TABLE `complaint_sub_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `contact_settings`
--
ALTER TABLE `contact_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_chat_messages`
--
ALTER TABLE `customer_chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_favourites`
--
ALTER TABLE `customer_favourites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_offers`
--
ALTER TABLE `customer_offers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_promo_histories`
--
ALTER TABLE `customer_promo_histories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_sos_contacts`
--
ALTER TABLE `customer_sos_contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_subscription_histories`
--
ALTER TABLE `customer_subscription_histories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_wallet_histories`
--
ALTER TABLE `customer_wallet_histories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `daily_fare_management`
--
ALTER TABLE `daily_fare_management`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `delivery_fare_management`
--
ALTER TABLE `delivery_fare_management`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `dispatch_trips`
--
ALTER TABLE `dispatch_trips`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `drivers`
--
ALTER TABLE `drivers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `driver_bank_kyc_details`
--
ALTER TABLE `driver_bank_kyc_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `driver_checkins`
--
ALTER TABLE `driver_checkins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `driver_commissions`
--
ALTER TABLE `driver_commissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `driver_earnings`
--
ALTER TABLE `driver_earnings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `driver_hiring_fare_management`
--
ALTER TABLE `driver_hiring_fare_management`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `driver_hiring_requests`
--
ALTER TABLE `driver_hiring_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `driver_hiring_statuses`
--
ALTER TABLE `driver_hiring_statuses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `driver_queries`
--
ALTER TABLE `driver_queries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `driver_recharges`
--
ALTER TABLE `driver_recharges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `driver_tips`
--
ALTER TABLE `driver_tips`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `driver_trip_requests`
--
ALTER TABLE `driver_trip_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `driver_tutorials`
--
ALTER TABLE `driver_tutorials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `driver_vehicles`
--
ALTER TABLE `driver_vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `driver_wallet_histories`
--
ALTER TABLE `driver_wallet_histories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `driver_withdrawals`
--
ALTER TABLE `driver_withdrawals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `fare_management`
--
ALTER TABLE `fare_management`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feature_settings`
--
ALTER TABLE `feature_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `instant_offers`
--
ALTER TABLE `instant_offers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lucky_offers`
--
ALTER TABLE `lucky_offers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mail_contents`
--
ALTER TABLE `mail_contents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `missed_trip_requests`
--
ALTER TABLE `missed_trip_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification_messages`
--
ALTER TABLE `notification_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `offer_types`
--
ALTER TABLE `offer_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `outstation_fare_management`
--
ALTER TABLE `outstation_fare_management`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `payment_histories`
--
ALTER TABLE `payment_histories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `payment_types`
--
ALTER TABLE `payment_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `privacy_policies`
--
ALTER TABLE `privacy_policies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `promo_codes`
--
ALTER TABLE `promo_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `referral_settings`
--
ALTER TABLE `referral_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `rental_fare_management`
--
ALTER TABLE `rental_fare_management`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `scratch_card_settings`
--
ALTER TABLE `scratch_card_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shared_fare_management`
--
ALTER TABLE `shared_fare_management`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `shared_trip_settings`
--
ALTER TABLE `shared_trip_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `statuses`
--
ALTER TABLE `statuses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `stops`
--
ALTER TABLE `stops`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `surge_fare_settings`
--
ALTER TABLE `surge_fare_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `surge_settings`
--
ALTER TABLE `surge_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tax_lists`
--
ALTER TABLE `tax_lists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tips`
--
ALTER TABLE `tips`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trips`
--
ALTER TABLE `trips`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trip_cancellations`
--
ALTER TABLE `trip_cancellations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trip_requests`
--
ALTER TABLE `trip_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trip_request_statuses`
--
ALTER TABLE `trip_request_statuses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `trip_settings`
--
ALTER TABLE `trip_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `trip_sub_types`
--
ALTER TABLE `trip_sub_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `trip_types`
--
ALTER TABLE `trip_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user_types`
--
ALTER TABLE `user_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `vehicle_categories`
--
ALTER TABLE `vehicle_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `vehicle_slugs`
--
ALTER TABLE `vehicle_slugs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `zones`
--
ALTER TABLE `zones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
