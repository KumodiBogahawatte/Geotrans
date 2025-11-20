-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 20, 2025 at 09:32 AM
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
-- Database: `geotrans_ecommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `brand_id` int(11) NOT NULL,
  `brand_name` varchar(100) NOT NULL,
  `brand_slug` varchar(100) NOT NULL,
  `brand_logo` varchar(255) DEFAULT NULL,
  `brand_description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`brand_id`, `brand_name`, `brand_slug`, `brand_logo`, `brand_description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'ASUS', 'asus', 'asus-1763609096.png', 'Leading technology company known for innovative laptops, including the ROG gaming series and ZenBook line for professionals.', 1, '2025-11-20 03:24:56', '2025-11-20 03:24:56'),
(2, 'Lenovo', 'lenovo', 'lenovo-1763609110.png', 'Global technology leader offering reliable laptops including the ThinkPad business series and Legion gaming lineup.', 1, '2025-11-20 03:25:10', '2025-11-20 03:25:10'),
(3, 'Acer', 'acer', 'acer-1763610152.png', '', 1, '2025-11-20 03:42:32', '2025-11-20 03:42:32'),
(4, 'Dell', 'dell', 'dell-1763610160.png', '', 1, '2025-11-20 03:42:40', '2025-11-20 03:42:40'),
(5, 'MSI', 'msi', 'msi-1763610169.png', '', 1, '2025-11-20 03:42:49', '2025-11-20 03:42:49'),
(6, 'HP', 'hp', 'hp-1763610189.png', '', 1, '2025-11-20 03:43:09', '2025-11-20 03:43:09'),
(7, 'Razer', 'razer', 'razer-1763611023.png', 'High-end gaming keyboards with Chroma RGB', 1, '2025-11-20 03:55:29', '2025-11-20 03:57:03');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `category_slug` varchar(100) NOT NULL,
  `category_description` text DEFAULT NULL,
  `category_image` varchar(255) DEFAULT NULL,
  `parent_category_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `category_slug`, `category_description`, `category_image`, `parent_category_id`, `is_active`, `display_order`, `created_at`, `updated_at`) VALUES
(1, 'Laptops', 'laptops', '', '691e897fc004c-laptop-screens-with-financial-charts-graphs-white-background1.png', NULL, 1, 0, '2025-11-20 03:22:39', '2025-11-20 03:22:39'),
(2, 'processor', 'processor', '', '691e89a38ab95-processor.png', NULL, 1, 0, '2025-11-20 03:23:15', '2025-11-20 03:23:15'),
(3, 'Mouse', 'mouse', '', '691e89ad965ee-computer-mouse1.png', NULL, 1, 0, '2025-11-20 03:23:25', '2025-11-20 03:23:25'),
(4, 'Keyboard', 'keyboard', '', '691e89bd241d1-gaming-keyboard-with-cacklight1.png', NULL, 1, 0, '2025-11-20 03:23:41', '2025-11-20 06:54:16'),
(5, 'Headphones', 'headphones', '', '691e89cc38402-gaming-headphones-black-red-with-wire1.png', NULL, 1, 0, '2025-11-20 03:23:56', '2025-11-20 03:23:56');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `message_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`message_id`, `name`, `email`, `phone`, `subject`, `message`, `is_read`, `created_at`) VALUES
(1, 'Kumodi Bogahawatte', 'kumodib@gmail.com', '0768501850', 'Kalada beelada', 'Man kewa oya kewada ?', 1, '2025-11-20 06:47:30');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `coupon_id` int(11) NOT NULL,
  `coupon_code` varchar(50) NOT NULL,
  `coupon_type` enum('percentage','fixed') DEFAULT 'percentage',
  `discount_value` decimal(10,2) NOT NULL,
  `min_purchase_amount` decimal(10,2) DEFAULT 0.00,
  `max_discount_amount` decimal(10,2) DEFAULT NULL,
  `usage_limit` int(11) DEFAULT NULL,
  `used_count` int(11) DEFAULT 0,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_subscribers`
--

CREATE TABLE `newsletter_subscribers` (
  `subscriber_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `subscribed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `order_number` varchar(50) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `guest_email` varchar(255) DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `discount_amount` decimal(10,2) DEFAULT 0.00,
  `shipping_cost` decimal(10,2) DEFAULT 0.00,
  `tax_amount` decimal(10,2) DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_status` enum('pending','paid','failed','refunded') DEFAULT 'pending',
  `order_status` enum('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
  `shipping_address_id` int(11) DEFAULT NULL,
  `billing_address_id` int(11) DEFAULT NULL,
  `tracking_number` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `order_number`, `user_id`, `guest_email`, `subtotal`, `discount_amount`, `shipping_cost`, `tax_amount`, `total_amount`, `payment_method`, `payment_status`, `order_status`, `shipping_address_id`, `billing_address_id`, `tracking_number`, `notes`, `ip_address`, `created_at`, `updated_at`) VALUES
(1, 'GEO-20251120-DC62E1', 2, 'kumodib@gmail.com', 176500.00, 0.00, 0.00, 0.00, 176500.00, 'cod', 'pending', 'processing', 1, 1, NULL, NULL, '::1', '2025-11-20 06:38:53', '2025-11-20 06:40:15');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_sku` varchar(100) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `product_name`, `product_sku`, `quantity`, `unit_price`, `subtotal`) VALUES
(1, 1, 5, 'Lenovo IdeaPad Slim 5 14″ – Ryzen 7 5700U – 16GB – 512GB SSD', 'SKU-5', 1, 176500.00, 176500.00);

-- --------------------------------------------------------

--
-- Table structure for table `order_status_history`
--

CREATE TABLE `order_status_history` (
  `history_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `old_status` varchar(50) DEFAULT NULL,
  `new_status` varchar(50) NOT NULL,
  `comment` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_status_history`
--

INSERT INTO `order_status_history` (`history_id`, `order_id`, `old_status`, `new_status`, `comment`, `created_by`, `created_at`) VALUES
(1, 1, NULL, 'pending', 'Order placed', NULL, '2025-11-20 06:38:53'),
(2, 1, NULL, 'processing', 'Status updated by admin', NULL, '2025-11-20 06:40:15');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_slug` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `sku` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `short_description` varchar(500) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `cost_price` decimal(10,2) DEFAULT NULL,
  `stock_quantity` int(11) DEFAULT 0,
  `min_stock_level` int(11) DEFAULT 5,
  `is_featured` tinyint(1) DEFAULT 0,
  `is_bestseller` tinyint(1) DEFAULT 0,
  `is_new_arrival` tinyint(1) DEFAULT 0,
  `is_on_sale` tinyint(1) DEFAULT 0,
  `discount_percentage` int(11) DEFAULT 0,
  `main_image` varchar(255) DEFAULT NULL,
  `rating` decimal(3,2) DEFAULT 0.00,
  `review_count` int(11) DEFAULT 0,
  `view_count` int(11) DEFAULT 0,
  `sold_count` int(11) DEFAULT 0,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `product_slug`, `category_id`, `brand_id`, `sku`, `description`, `short_description`, `price`, `sale_price`, `cost_price`, `stock_quantity`, `min_stock_level`, `is_featured`, `is_bestseller`, `is_new_arrival`, `is_on_sale`, `discount_percentage`, `main_image`, `rating`, `review_count`, `view_count`, `sold_count`, `meta_title`, `meta_description`, `meta_keywords`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'ASUS ROG Strix G16 Gaming Laptop', 'asus-rog-strix-g16-gaming-laptop', 1, 1, 'ASUS-ROG-G16-2024', 'The ASUS ROG Strix G16 is a powerful gaming laptop featuring the latest Intel Core i9 processor and NVIDIA GeForce RTX 4070 graphics card. With a stunning 16-inch QHD display at 240Hz, this laptop delivers smooth gameplay and immersive visuals.', NULL, 485000.00, 459900.00, NULL, 8, 5, 1, 1, 1, 0, 0, '691e8d8aabcd1-691dbfb345417-images.jpeg', 0.00, 0, 1, 0, NULL, NULL, NULL, 1, '2025-11-20 03:39:54', '2025-11-20 06:57:05'),
(2, 'MSI Katana 15 B13VGK Gaming Laptop', 'msi-katana-15-b13vgk-gaming-laptop', 1, 5, 'MSI-KATANA-15-B13VGK', '', NULL, 285000.00, 265000.00, NULL, 12, 5, 1, 0, 1, 0, 0, '691e8f28dcd2e-691dc0efb50df-images2.jpeg', 0.00, 0, 2, 0, NULL, NULL, NULL, 1, '2025-11-20 03:46:48', '2025-11-20 04:29:41'),
(3, 'Acer Nitro 5 AN515-58 Gaming Laptop', 'acer-nitro-5-an515-58-gaming-laptop', 1, 3, 'ACER-NITRO5-AN515-58', '', NULL, 295000.00, 279000.00, NULL, 15, 5, 1, 1, 0, 0, 0, '691e8fafcc7c2-691dc263c4c2d-images4.jpeg', 0.00, 0, 0, 0, NULL, NULL, NULL, 1, '2025-11-20 03:49:03', '2025-11-20 03:49:03'),
(4, 'HP Pavilion 15-eg3000 i5 13th Gen', 'hp-pavilion-15-eg3000-i5-13th-gen', 1, 6, 'HP-PAV15-EG3000-I5', '', NULL, 219000.00, 199000.00, NULL, 22, 5, 0, 1, 1, 0, 0, '691e900e27051-691dc05fe16b9-images1.jpeg', 0.00, 0, 1, 0, NULL, NULL, NULL, 1, '2025-11-20 03:50:38', '2025-11-20 08:30:07'),
(5, 'Lenovo IdeaPad Slim 5 14″ – Ryzen 7 5700U – 16GB – 512GB SSD', 'lenovo-ideapad-slim-5-14-ryzen-7-5700u-16gb-512gb-ssd', 1, 2, 'LNV-IPSLIM5-R7-16-512', 'The Lenovo IdeaPad Slim 5 is a lightweight, stylish, and performance-packed ultrabook designed for productivity and entertainment. With AMD Ryzen 7 power, fast SSD storage, and a crisp 14-inch display, it is perfect for students, professionals, and everyday users.', NULL, 189000.00, 176500.00, NULL, 11, 5, 1, 0, 1, 0, 0, '691e9088174f9-691dc15ef1b7b-images3.jpeg', 0.00, 0, 24, 0, NULL, NULL, NULL, 1, '2025-11-20 03:52:40', '2025-11-20 07:34:59'),
(6, 'Razer BlackWidow V4 Mechanical Gaming Keyboard', 'razer-blackwidow-v4-mechanical-gaming-keyboard', 4, 7, 'RAZ-BWV4-MECH', 'Brand New', NULL, 24500.00, 22000.00, NULL, 12, 5, 1, 1, 1, 0, 0, '691e925658535-040baf0a51abbe8360e477d7158da5c9.jpg', 0.00, 0, 8, 0, NULL, NULL, NULL, 1, '2025-11-20 04:00:22', '2025-11-20 07:40:36');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `image_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `is_primary` tinyint(1) DEFAULT 0,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_reviews`
--

CREATE TABLE `product_reviews` (
  `review_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `review_title` varchar(255) DEFAULT NULL,
  `review_text` text DEFAULT NULL,
  `is_verified_purchase` tinyint(1) DEFAULT 0,
  `is_approved` tinyint(1) DEFAULT 0,
  `helpful_count` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_reviews`
--

INSERT INTO `product_reviews` (`review_id`, `product_id`, `user_id`, `rating`, `review_title`, `review_text`, `is_verified_purchase`, `is_approved`, `helpful_count`, `created_at`, `updated_at`) VALUES
(1, 5, 2, 3, 'Good product', 'Good Quality', 0, 1, 0, '2025-11-20 07:01:04', '2025-11-20 08:18:15');

-- --------------------------------------------------------

--
-- Table structure for table `product_specifications`
--

CREATE TABLE `product_specifications` (
  `spec_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `spec_name` varchar(100) NOT NULL,
  `spec_value` varchar(255) NOT NULL,
  `display_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_specifications`
--

INSERT INTO `product_specifications` (`spec_id`, `product_id`, `spec_name`, `spec_value`, `display_order`) VALUES
(1, 1, 'Processor', 'Intel Core i9-14900HX (24 cores / 32 threads; up to 5.8 GHz)', 0),
(2, 1, 'Graphics (GPU)', 'RTX 4060 (8GB GDDR6)', 1),
(3, 1, 'Memory (RAM)', '16 GB DDR5-5600 SO-DIMM (stock)', 2),
(4, 1, 'Storage', '1 TB PCIe Gen 4 NVMe M.2 SSD', 3),
(5, 2, 'Processor', 'Intel Core i7-13620H 13th Gen', 0),
(6, 3, 'Processor', 'Intel Core i7-12700H 12th Gen', 0),
(7, 3, 'Graphics', 'NVIDIA', 1),
(8, 4, 'Processor', 'Intel Core i5-1335U 13th Gen', 0),
(9, 5, 'Processor', 'AMD Ryzen 7 5700U', 0),
(10, 5, 'Graphics', 'Integrated AMD Radeon Graphics', 1),
(11, 5, 'Memory', '16GB DDR4 RAM', 2),
(12, 5, 'Camera', '720p HD Webcam with Privacy Shutter', 3),
(16, 6, 'Keyboard Type', 'Mechanical Gaming Keyboard', 0),
(17, 6, 'Switches', 'Razer Green Mechanical Switches (Clicky)', 1),
(18, 6, 'Backlight', 'Full RGB Chroma Lighting', 2);

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `setting_id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_group` varchar(50) DEFAULT NULL,
  `setting_type` varchar(50) DEFAULT 'text',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`setting_id`, `setting_key`, `setting_value`, `setting_group`, `setting_type`, `updated_at`) VALUES
(1, 'site_name', 'GeoTrans', 'general', 'text', '2025-11-20 04:00:55'),
(2, 'site_tagline', 'Your One-Stop Tech Shop', 'general', 'text', '2025-11-20 04:00:55'),
(3, 'site_email', 'info@geotrans.com', 'general', 'text', '2025-11-20 04:00:55'),
(4, 'site_phone', '+94 11 234 1411', 'general', 'text', '2025-11-20 06:55:56'),
(5, 'site_address', 'Colombo, Sri Lanka', 'general', 'text', '2025-11-20 04:00:55'),
(6, 'facebook_url', '', 'social', 'text', '2025-11-20 04:00:55'),
(7, 'instagram_url', '', 'social', 'text', '2025-11-20 04:00:55'),
(8, 'twitter_url', '', 'social', 'text', '2025-11-20 04:00:55'),
(9, 'youtube_url', '', 'social', 'text', '2025-11-20 04:00:55'),
(10, 'linkedin_url', '', 'social', 'text', '2025-11-20 04:00:55'),
(11, 'currency_symbol', 'Rs', 'business', 'text', '2025-11-20 04:00:55'),
(12, 'tax_rate', '0', 'business', 'text', '2025-11-20 04:00:55'),
(13, 'shipping_cost', '350', 'business', 'text', '2025-11-20 04:00:55'),
(14, 'free_shipping_threshold', '5000', 'business', 'text', '2025-11-20 04:00:55'),
(15, 'smtp_host', '', 'email', 'text', '2025-11-20 04:00:55'),
(16, 'smtp_port', '587', 'email', 'text', '2025-11-20 04:00:55'),
(17, 'smtp_username', '', 'email', 'text', '2025-11-20 04:00:55'),
(18, 'smtp_password', '', 'email', 'text', '2025-11-20 04:00:55'),
(19, 'smtp_from_email', '', 'email', 'text', '2025-11-20 04:00:55'),
(20, 'smtp_from_name', 'GeoTrans', 'email', 'text', '2025-11-20 04:00:55'),
(21, 'meta_description', 'Shop the latest electronics and gadgets at GeoTrans', 'seo', 'text', '2025-11-20 04:00:55'),
(22, 'meta_keywords', 'electronics, gadgets, computers, laptops, phones', 'seo', 'text', '2025-11-20 04:00:55'),
(23, 'google_analytics_id', '', 'seo', 'text', '2025-11-20 04:00:55');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `testimonial_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_role` varchar(100) NOT NULL,
  `customer_image` varchar(255) DEFAULT NULL,
  `rating` int(1) NOT NULL DEFAULT 5,
  `feedback_text` text NOT NULL,
  `feedback_date` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `is_verified` tinyint(1) DEFAULT 0,
  `admin_notes` text DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`testimonial_id`, `user_id`, `customer_name`, `customer_role`, `customer_image`, `rating`, `feedback_text`, `feedback_date`, `is_active`, `is_verified`, `admin_notes`, `display_order`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Ashan Perera', 'Business Owner', '', 4, 'Excellent service and quality products! I purchased a laptop for my business and the team at GeoTrans was incredibly helpful. Fast delivery and great after-sales support.', '2 weeks ago', 1, 0, NULL, 1, '2025-11-20 04:37:59', '2025-11-20 04:39:35'),
(2, NULL, 'Nimali Fernando', 'IT Manager', NULL, 5, 'Best place to buy office equipment in Sri Lanka. We\'ve been ordering from GeoTrans for over 3 years now. Their product range is excellent and prices are very competitive.', '1 month ago', 1, 0, NULL, 2, '2025-11-20 04:37:59', '2025-11-20 04:37:59'),
(3, NULL, 'Kasun Silva', 'Software Engineer', NULL, 5, 'Recently bought an ASUS ROG laptop from GeoTrans. The purchasing process was smooth, and I got genuine products with warranty. Highly recommend!', '3 weeks ago', 1, 0, NULL, 3, '2025-11-20 04:37:59', '2025-11-20 04:37:59'),
(4, NULL, 'Dilini Rathnayake', 'Graphic Designer', NULL, 4, 'Great experience shopping at GeoTrans. Found exactly what I needed for my design work. The staff was knowledgeable and helped me choose the right specifications.', '1 week ago', 1, 0, NULL, 4, '2025-11-20 04:37:59', '2025-11-20 04:37:59'),
(5, NULL, 'Ravindu Jayasinghe', 'Entrepreneur', '', 3, 'Outstanding customer service! I had questions about different laptop models and the team patiently explained all the features. Very satisfied with my purchase.', '2 days ago', 1, 0, NULL, 5, '2025-11-20 04:37:59', '2025-11-20 04:48:34'),
(6, NULL, 'Sachini Wijesinghe', 'Accountant', NULL, 5, 'Reliable and trustworthy. Got my ThinkPad delivered within 2 days. Product was genuine and in perfect condition. Will definitely buy from GeoTrans again!', '5 days ago', 1, 0, NULL, 6, '2025-11-20 04:37:59', '2025-11-20 04:37:59'),
(7, 2, 'Kumodi Bogahawatte', 'CEO of amathapadam comapny', NULL, 4, 'Supiri website eka ;)', 'Recently', 1, 0, NULL, 0, '2025-11-20 06:34:27', '2025-11-20 08:24:02');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `user_type` enum('customer','admin','superadmin') DEFAULT 'customer',
  `verification_token` varchar(255) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `password_hash`, `first_name`, `last_name`, `profile_photo`, `phone`, `is_verified`, `is_active`, `user_type`, `verification_token`, `reset_token`, `reset_token_expiry`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'admin@geotrans.com', '$2y$10$EtadVBZAtBdIIENRAVXrJuAAGMVP9Gz8WyZfLbFiRaw/JbVOA3o/a', 'Admin', 'Geotrans', NULL, '+94123456789', 1, 1, 'admin', NULL, NULL, NULL, '2025-11-20 10:09:13', '2025-11-20 03:19:25', '2025-11-20 04:39:13'),
(2, 'kumodib@gmail.com', '$2y$10$zKAX4B5.J3Ub.DYJBWpWdOu2vF5dd0QyOGbhcDlb/JJeKavIpi8kW', 'Kumodi', 'Bogahawatte', NULL, '0768501850', 0, 1, 'customer', NULL, NULL, NULL, '2025-11-20 14:00:28', '2025-11-20 04:02:06', '2025-11-20 08:30:28');

-- --------------------------------------------------------

--
-- Table structure for table `user_addresses`
--

CREATE TABLE `user_addresses` (
  `address_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `address_type` enum('billing','shipping') DEFAULT 'shipping',
  `full_name` varchar(200) DEFAULT NULL,
  `address_line1` varchar(255) NOT NULL,
  `address_line2` varchar(255) DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `country` varchar(100) DEFAULT 'Sri Lanka',
  `phone` varchar(20) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_addresses`
--

INSERT INTO `user_addresses` (`address_id`, `user_id`, `address_type`, `full_name`, `address_line1`, `address_line2`, `city`, `state`, `postal_code`, `country`, `phone`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 2, 'shipping', 'Kumodi Bogahawatte', '17,\r\nH K Dharmadasa Mawatha', NULL, 'Colombo', NULL, '00200', 'Sri Lanka', '0768501850', 0, '2025-11-20 06:38:53', '2025-11-20 06:38:53'),
(2, 2, 'shipping', 'Kumodi Bogahawatte', '17,\r\nGangarama', NULL, 'Colombo', NULL, '00200', 'Sri Lanka', '0768501850', 0, '2025-11-20 06:46:07', '2025-11-20 06:46:07');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `wishlist_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`brand_id`),
  ADD UNIQUE KEY `brand_slug` (`brand_slug`),
  ADD KEY `idx_slug` (`brand_slug`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD UNIQUE KEY `unique_cart_item` (`user_id`,`product_id`,`session_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_session` (`session_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_slug` (`category_slug`),
  ADD KEY `parent_category_id` (`parent_category_id`),
  ADD KEY `idx_slug` (`category_slug`),
  ADD KEY `idx_active` (`is_active`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `idx_read` (`is_read`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`coupon_id`),
  ADD UNIQUE KEY `coupon_code` (`coupon_code`),
  ADD KEY `idx_code` (`coupon_code`),
  ADD KEY `idx_active` (`is_active`);

--
-- Indexes for table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  ADD PRIMARY KEY (`subscriber_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `idx_order_number` (`order_number`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_status` (`order_status`),
  ADD KEY `idx_payment_status` (`payment_status`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `idx_order` (`order_id`);

--
-- Indexes for table `order_status_history`
--
ALTER TABLE `order_status_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `idx_order` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD UNIQUE KEY `product_slug` (`product_slug`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD KEY `idx_slug` (`product_slug`),
  ADD KEY `idx_category` (`category_id`),
  ADD KEY `idx_brand` (`brand_id`),
  ADD KEY `idx_featured` (`is_featured`),
  ADD KEY `idx_bestseller` (`is_bestseller`),
  ADD KEY `idx_price` (`price`);
ALTER TABLE `products` ADD FULLTEXT KEY `idx_search` (`product_name`,`description`,`short_description`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `idx_product` (`product_id`);

--
-- Indexes for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_product` (`product_id`),
  ADD KEY `idx_approved` (`is_approved`);

--
-- Indexes for table `product_specifications`
--
ALTER TABLE `product_specifications`
  ADD PRIMARY KEY (`spec_id`),
  ADD KEY `idx_product` (`product_id`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`setting_id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`),
  ADD KEY `idx_key` (`setting_key`),
  ADD KEY `idx_group` (`setting_group`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`testimonial_id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_type` (`user_type`);

--
-- Indexes for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD PRIMARY KEY (`address_id`),
  ADD KEY `idx_user` (`user_id`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`wishlist_id`),
  ADD UNIQUE KEY `unique_wishlist_item` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `idx_user` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `brand_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `coupon_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  MODIFY `subscriber_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_status_history`
--
ALTER TABLE `order_status_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `product_specifications`
--
ALTER TABLE `product_specifications`
  MODIFY `spec_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `testimonial_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_addresses`
--
ALTER TABLE `user_addresses`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `wishlist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`parent_category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE SET NULL;

--
-- Constraints for table `order_status_history`
--
ALTER TABLE `order_status_history`
  ADD CONSTRAINT `order_status_history_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`brand_id`) ON DELETE SET NULL;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD CONSTRAINT `product_reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `product_specifications`
--
ALTER TABLE `product_specifications`
  ADD CONSTRAINT `product_specifications_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD CONSTRAINT `user_addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
