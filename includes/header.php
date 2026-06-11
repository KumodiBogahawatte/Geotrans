<?php
if (!function_exists('isLoggedIn')) {
    require_once __DIR__ . '/../includes/helpers.php';
}
require_once __DIR__ . '/../classes/Cart.php';
require_once __DIR__ . '/../classes/Category.php';
require_once __DIR__ . '/../classes/Wishlist.php';
require_once __DIR__ . '/../classes/Product.php';
// require_once __DIR__ . '/../includes/currency.php';

// Define base URL for navigation
$base_url = '/Geotrans/';

$cart = new Cart();
$category = new Category();
$wishlist = new Wishlist();
$productObj = new Product();

$user_id = getUserId();
$session_id = !$user_id ? getSessionId() : null;
$cart_count = $cart->getCount($user_id, $session_id);
$wishlist_count = $user_id ? $wishlist->getCount($user_id) : 0;
$categories = $category->getAll(true); // Get only parent categories
// $selected_currency = CurrencyConverter::getSelectedCurrency();

// Load general settings for header (phone, site name)
$site_settings = ['site_phone' => '+94 71 375 7555', 'site_name' => 'GeoTrans'];
if (file_exists(__DIR__ . '/../config/database.php')) {
    require_once __DIR__ . '/../config/database.php';
    $db = new Database();
    $conn = $db->getConnection();
    $stmt = $conn->query("SELECT setting_key, setting_value FROM site_settings WHERE setting_group IN ('general', 'social')");
    if ($stmt) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $site_settings[$row['setting_key']] = $row['setting_value'] ?? '';
        }
    }
}

?>

<link rel="stylesheet" href="<?= htmlspecialchars($base_url) ?>assets/css/wishlist-buttons.css">

<!-- Top Banner -->
<div class="text-white py-2 px-6 sm:px-8 lg:px-20 text-center text-sm sticky top-0 z-[70]" style="background: linear-gradient(90deg, #64388D 0%, #5E0456 50%, #900684 100%); margin-bottom:0;">
    <span class="bg-white px-3 py-1 rounded-full font-semibold text-xs mr-2" style="color: #680e68;">Special</span>
    Get 10% <span class="font-bold">DISCOUNT</span> for first order
    <?php if (!isLoggedIn()): ?>
        <a href="<?= $base_url ?>register.php" class="underline ml-2" style="color: #FFFFFF;" onmouseover="this.style.color='#C4A3D1'" onmouseout="this.style.color='#FFFFFF'">Register Now</a>
    <?php endif; ?>
</div>

<!-- Main Header -->
<header id="main-header" class="shadow-sm sticky top-[unset] z-[80] transition-all duration-300 text-white" style="top:0; background: #f0e8f1;">
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-4 text-white">
        <!-- Mobile Header -->
        <div class="lg:hidden flex items-center justify-between w-full">
            <!-- Mobile Menu Button -->
            <button id="mobile-menu-button" class="p-2" style="color: #680e68;">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            <!-- Logo -->
            <a href="<?= $base_url ?>index.php" class="flex items-center">
                <img src="<?= $base_url ?>assets/images/geotrans-logo.png" alt="GeoTrans Pvt Ltd Logo" class="h-8 object-contain">
            </a>

            <!-- Mobile Icons -->
            <div class="flex items-center space-x-3">
                <!-- Search Icon -->
                <button id="mobile-search-button" style="color: #680e68;">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>

                <!-- Wishlist -->
                <a href="<?= $base_url ?>wishlist.php" class="relative" style="color: #680e68;">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    <span class="wishlist-count absolute -top-2 -right-2 text-black text-xs rounded-full min-w-[1.25rem] h-5 px-0.5 flex items-center justify-center <?= (int) $wishlist_count > 0 ? '' : 'hidden' ?>" style="background-color: #ffffff;" aria-live="polite"><?= (int) $wishlist_count ?></span>
                </a>

                <!-- Cart -->
                <a href="<?= $base_url ?>cart.php" class="relative" style="color: #680e68;">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <?php if ($cart_count > 0): ?>
                        <span id="mobile-cart-count" class="absolute -top-2 -right-2 text-black text-xs rounded-full w-5 h-5 flex items-center justify-center" style="background-color: #ffffff;"><?php echo $cart_count; ?></span>
                    <?php endif; ?>
                </a>
            </div>
        </div>

        <!-- Mobile Search Bar (Hidden by default) -->
        <div id="mobile-search" class="lg:hidden hidden mt-4">
            <div class="flex items-center">
                <div class="relative">
                    <button id="mobile-all-categories-btn" type="button" aria-haspopup="true" aria-expanded="false" class="bg-white border-2 rounded-l-lg px-3 py-2 text-sm text-gray-700 focus:outline-none flex items-center justify-between gap-2 font-medium h-10" style="--tw-ring-color: #680e68; min-width: 90px;">
                        <span>All</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="mobile-categories-dropdown" class="absolute left-0 top-full mt-2 w-64 bg-black shadow-lg z-50 p-2 rounded hidden max-h-72 overflow-y-auto">
                        <?php foreach ($categories as $cat): ?>
                            <?php $catCount = $productObj->getCount(['category_id' => $cat['category_id']]); ?>
                            <a href="<?= $base_url ?>products-category.php?category=<?= urlencode($cat['category_slug']) ?>" class="group flex items-center justify-between px-3 py-2 rounded hover:bg-purple-50 text-white hover:text-black transition">
                                <span class="text-xs font-medium"><?= htmlspecialchars($cat['category_name']) ?></span>
                                <span class="text-xs text-gray-400 group-hover:text-gray-600">(<?= $catCount ?>)</span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="relative flex-1">
                    <input type="text" id="mobile-search-input" placeholder="Search anything..."
                        class="w-full px-3 border-2 bg-white text-gray-900 text-sm focus:outline-none h-10" style="--tw-ring-color: #680e68; border-color: #E5E7EB;" />
                </div>
                <button onclick="performSearch('mobile')" class="text-white px-3 py-2 rounded-r-lg transition-colors font-semibold h-10 flex items-center justify-center" style="background-color: #680e68;" onmouseover="this.style.backgroundColor='#4f0a4f'" onmouseout="this.style.backgroundColor='#680e68'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Desktop Header -->
        <div class="hidden lg:flex flex-wrap items-center justify-between w-full gap-4">
            <!-- Logo -->
            <div class="flex items-center flex-shrink-0 min-w-[120px]">
                <a href="<?= $base_url ?>index.php" class="flex items-center">
                    <img src="<?= $base_url ?>assets/images/geotrans-logo.png" alt="GeoTrans Pvt Ltd Logo" class="h-16 object-contain max-w-full">
                </a>
            </div>

            <!-- Search Bar -->
            <div class="flex flex-1 min-w-[220px] max-w-2xl mx-4 w-full">
                <?php
                require_once __DIR__ . '/../classes/Brand.php';
                $brandObj = new Brand();
                $brands = $brandObj->getAll();
                // Find the laptops category (by slug or name)
                $laptopCat = null;
                foreach ($categories as $cat) {
                    if (strtolower($cat['category_name']) === 'laptops' || strtolower($cat['category_slug']) === 'laptops') {
                        $laptopCat = $cat;
                        break;
                    }
                }
                ?>
                <div class="relative min-w-[140px] max-w-[180px] w-full">
                    <button id="all-categories-btn" type="button" aria-haspopup="true" aria-expanded="false" tabindex="0" class="bg-white border-2 rounded-l-lg px-3 sm:px-4 py-2 text-xs sm:text-sm text-gray-700 focus:outline-none w-full flex items-center justify-between font-medium h-10" style="--tw-ring-color: #680e68;">
                        <span>All Categories</span>
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="categories-dropdown" role="menu" aria-label="Categories" class="absolute left-1/2 top-full mt-2 bg-black shadow-lg z-50 p-4 grid gap-4 sm:gap-6 hidden"
                        style="min-width:220px; width: 90vw; max-width:1000px; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));">
                        <?php foreach ($categories as $cat): ?>
                            <?php $catCount = $productObj->getCount(['category_id' => $cat['category_id']]); ?>
                            <a href="<?= $base_url ?>products-category.php?category=<?= urlencode($cat['category_slug']) ?>" role="menuitem" tabindex="0" class="group flex flex-col items-center p-2 hover:bg-purple-50 text-white hover:text-black rounded transition w-full focus:bg-purple-100 focus:outline-none">
                                <img src="<?= $base_url ?>assets/images/categories/<?= htmlspecialchars($cat['category_image'] ?? 'default.png') ?>" alt="<?= htmlspecialchars($cat['category_name']) ?>" class="w-16 sm:w-24 md:w-28 h-16 sm:h-24 md:h-28 object-contain mb-2" />
                                <span class="text-xs text-center font-medium text-white group-hover:text-black"><?= htmlspecialchars($cat['category_name']) ?> <span class="text-xs text-gray-400 group-hover:text-gray-600">(<?= $catCount ?>)</span></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="relative flex-1 min-w-[120px]">
                    <input type="text" id="search-input" placeholder="Search anything..."
                        class="w-full px-3 sm:px-4 border-2 bg-white text-gray-900 text-xs sm:text-sm focus:outline-none h-10" style="--tw-ring-color: #680e68; border-color: #E5E7EB;" />
                    <div id="search-results" class="absolute top-full left-0 right-0 bg-white mt-1 rounded-lg shadow-lg max-h-96 overflow-y-auto z-50 hidden"></div>
                </div>
                <button onclick="performSearch()" class="text-white px-3 sm:px-4 py-2 rounded-r-lg transition-colors font-semibold h-10 flex items-center justify-center" style="background-color: #680e68;" onmouseover="this.style.backgroundColor='#4f0a4f'" onmouseout="this.style.backgroundColor='#680e68'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
            </div>

            <!-- Right Side Icons & Contact -->
            <div class="flex items-center space-x-4 flex-shrink-0 min-w-[180px] mt-4 lg:mt-0">
                <!-- Hotline -->
                <div class="text-right hidden xl:block">
                    <div class="text-sm text-purple-800">Hotline 24/7</div>
                    <a href="tel:<?= preg_replace('/\D/', '', $site_settings['site_phone'] ?? '') ?>" class="text-base font-semibold block" style="color: #680e68;"><?= htmlspecialchars($site_settings['site_phone'] ?? '+94 71 375 7555') ?></a>
                </div>

                <!-- Wishlist -->
                <a href="<?= $base_url ?>wishlist.php" class="relative" style="color: #680e68;" onmouseover="this.style.color='#bb88bb'" onmouseout="this.style.color='#680e68'">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    <span class="wishlist-count absolute -top-2 -right-2 text-black text-xs rounded-full min-w-[1.25rem] h-5 px-0.5 flex items-center justify-center <?= (int) $wishlist_count > 0 ? '' : 'hidden' ?>" style="background-color: #ffffff;" aria-live="polite"><?= (int) $wishlist_count ?></span>
                </a>

                <!-- Cart -->
                <a href="<?= $base_url ?>cart.php" class="relative" style="color: #680e68;" onmouseover="this.style.color='#bb88bb'" onmouseout="this.style.color='#680e68'">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <?php if ($cart_count > 0): ?>
                        <span id="cart-count" class="absolute -top-2 -right-2 text-black text-xs rounded-full w-5 h-5 flex items-center justify-center" style="background-color: #ffffff;"><?php echo $cart_count; ?></span>
                    <?php else: ?>
                        <span id="cart-count" class="absolute -top-2 -right-2 text-black text-xs rounded-full w-5 h-5 flex items-center justify-center hidden" style="background-color: #ffffff;">0</span>
                    <?php endif; ?>
                </a>

                <!-- User Account -->
                <?php if (isLoggedIn()): ?>
                    <div class="relative" id="user-dropdown">
                        <button id="user-menu-btn" class="flex items-center gap-2" style="color: #680e68;" onmouseover="this.style.color='#bb88bb'" onmouseout="this.style.color='#680e68'">
                            <?php
                            $profile_photo = getUserData('profile_photo');
                            if (!empty($profile_photo) && file_exists(__DIR__ . '/../assets/images/profiles/' . $profile_photo)):
                            ?>
                                <img src="<?= $base_url ?>assets/images/profiles/<?= htmlspecialchars($profile_photo) ?>"
                                    alt="<?= htmlspecialchars(getUserData('first_name')) ?>"
                                    class="w-8 h-8 rounded-full object-cover border-2 border-purple-300">
                            <?php else: ?>
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-semibold text-sm" style="background-color: #ffffff;">
                                    <?= strtoupper(substr(getUserData('first_name'), 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                            <div class="hidden lg:block text-left">
                                <div class="text-sm text-gray-400">Hello</div>
                                <div class="text-base font-semibold"><?php echo htmlspecialchars(getUserData('first_name')); ?></div>
                            </div>
                        </button>
                        <div id="user-menu" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 hidden z-50">
                            <a href="<?= $base_url ?>account/profile.php" class="block px-4 py-2 text-sm hover:bg-gray-100 text-black">My Profile</a>
                            <a href="<?= $base_url ?>account/orders.php" class="block px-4 py-2 text-sm hover:bg-gray-100 text-black">My Orders</a>
                            <a href="<?= $base_url ?>account/addresses.php" class="block px-4 py-2 text-sm hover:bg-gray-100 text-black">My Addresses</a>
                            <a href="<?= $base_url ?>wishlist.php" class="block px-4 py-2 text-sm hover:bg-gray-100 text-black">My Wishlist</a>
                            <?php if (isAdmin()): ?>
                                <a href="<?= $base_url ?>admin/" class="block px-4 py-2 text-sm hover:bg-gray-100">Admin Panel</a>
                            <?php endif; ?>
                            <hr class="my-2">
                            <a href="<?= $base_url ?>logout.php" class="block px-4 py-2 text-sm hover:bg-gray-100 text-red-600">Logout</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="<?= $base_url ?>login.php" class="text-sm font-semibold px-4 py-2 rounded" onmouseover="this.style.color='#bb88bb'" onmouseout="this.style.color='#680e68'" style="color: #680e68;">Login / Register</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="border-t border-gray-500">
        <!-- Mobile Menu (Hidden by default) -->
        <div id="mobile-menu" class="lg:hidden hidden border-b border-gray-200">
            <div class="px-4 py-3 space-y-3">
                <!-- Main Navigation -->
                <div class="space-y-2">
                    <a href="<?= $base_url ?>index.php" class="block py-2 text-sm" style="color: #680e68;">Home</a>
                    <a href="<?= $base_url ?>about.php" class="block py-2 text-sm" style="color: #680e68;">About</a>
                    <a href="<?= $base_url ?>products.php" class="block py-2 text-sm" style="color: #680e68;">Products</a>
                    <a href="<?= $base_url ?>contact.php" class="block py-2 text-sm" style="color: #680e68;">Contact</a>
                </div>

                <!-- Additional Links -->
                <div class="border-t border-gray-200 pt-3 space-y-2">
                    <a href="<?= $base_url ?>submit-feedback.php" class="flex items-center py-2 text-sm" style="color: #680e68;">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        Submit Feedback
                    </a>
                    <a href="<?= $base_url ?>order-tracking.php" class="flex items-center py-2 text-sm" style="color: #680e68;">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Order Tracking
                    </a>
                    <a href="<?= $base_url ?>recently-viewed.php" class="block py-2 text-sm" style="color: #680e68;">Recently Viewed</a>
                </div>

                <!-- Currency -->
                <div class="border-t border-gray-200 pt-3">
                    <div class="text-xs text-purple-900 mb-2">Hotline: <?= htmlspecialchars($site_settings['site_phone'] ?? '+94 71 375 7555') ?></div>
                    <!-- <div class="flex items-center space-x-4">
                            <select id="currency-selector-mobile" class="bg-gray-100 border rounded px-2 py-1 text-sm focus:outline-none" style="color: #680e68;">
                                <option value="LKR">LKR</option>
                                <option value="USD">USD</option>
                                <option value="EUR">EUR</option>
                                <option value="GBP">GBP</option>
                            </select>
                        </div> -->
                </div>
            </div>
        </div>

        <!-- Desktop Navigation -->
        <div class="hidden lg:block max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-center justify-between py-3 w-full">
                <ul class="flex items-center space-x-8 text-sm">
                    <li>
                        <a href="<?= $base_url ?>index.php" class="flex items-center" style="color: #680e68; font-size:larger" onmouseover="this.style.color='#bb88bb'" onmouseout="this.style.color='#680e68'">
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="<?= $base_url ?>about.php" class="flex items-center" style="color: #680e68; font-size:larger" onmouseover="this.style.color='#bb88bb'" onmouseout="this.style.color='#680e68'">
                            About
                        </a>
                    </li>
                    <li>
                        <a href="<?= $base_url ?>products.php" class="flex items-center" style="color: #680e68; font-size:larger" onmouseover="this.style.color='#bb88bb'" onmouseout="this.style.color='#680e68'">
                            Products
                        </a>
                    </li>
                    <li>
                        <a href="<?= $base_url ?>contact.php" class="flex items-center" style="color: #680e68; font-size:larger" onmouseover="this.style.color='#bb88bb'" onmouseout="this.style.color='#680e68'">Contact</a>
                    </li>
                </ul>

                <ul class="flex items-center space-x-6 text-sm">
                    <li class="hidden xl:block">
                        <a href="submit-feedback.php" class="flex items-center" style="color: #680e68; font-size:larger" onmouseover="this.style.color='#bb88bb'" onmouseout="this.style.color='#680e68'">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            Submit Feedback
                        </a>
                    </li>
                    <li class="hidden xl:block">
                        <a href="<?= $base_url ?>order-tracking.php" class="flex items-center" style="color: #680e68; font-size:larger" onmouseover="this.style.color='#bb88bb'" onmouseout="this.style.color='#680e68'">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            Order Tracking
                        </a>
                    </li>
                    <li class="hidden lg:block relative" id="recently-viewed-dropdown">
                        <button type="button" class="flex items-center" style="color: #680e68; font-size:larger" onmouseover="this.style.color='#bb88bb'" onmouseout="this.style.color='#680e68'">
                            Recently Viewed
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="hidden absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg z-50 border border-gray-200 max-h-96 overflow-y-auto">
                            <div id="recently-viewed-content" class="p-4">
                                <div class="text-center text-gray-500 py-4">Loading...</div>
                            </div>
                        </div>
                    </li>
                    <!-- <li>
                            Currency Selector
                            <div class="flex items-center space-x-2 lg:space-x-4">
                                <select id="currency-selector" class="bg-transparent border-0 text-xs lg:text-sm text-gray-600 focus:outline-none cursor-pointer" style="color: #680e68;">
                                    <option value="LKR">LKR</option>
                                    <option value="USD">USD</option>
                                    <option value="EUR">EUR</option>
                                    <option value="GBP">GBP</option>
                                </select>
                            </div>
                        </li> -->
                </ul>
            </div>
        </div>
    </nav>
</header>

<script>
    // Mobile menu toggle
    document.getElementById('mobile-menu-button').addEventListener('click', function() {
        const mobileMenu = document.getElementById('mobile-menu');
        mobileMenu.classList.toggle('hidden');
    });

    // All Categories dropdown toggle and accessibility
    const catBtn = document.getElementById('all-categories-btn');
    const catDropdown = document.getElementById('categories-dropdown');
    if (catBtn && catDropdown) {
        catBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            const expanded = catDropdown.classList.toggle('hidden') ? 'false' : 'true';
            catBtn.setAttribute('aria-expanded', expanded);
        });
        catBtn.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                catBtn.click();
            } else if (e.key === 'ArrowDown') {
                e.preventDefault();
                if (!catDropdown.classList.contains('hidden')) {
                    const firstItem = catDropdown.querySelector('a');
                    if (firstItem) firstItem.focus();
                }
            }
        });
        document.addEventListener('click', function(e) {
            if (!catDropdown.contains(e.target) && !catBtn.contains(e.target)) {
                catDropdown.classList.add('hidden');
                catBtn.setAttribute('aria-expanded', 'false');
            }
        });
        // Keyboard navigation for dropdown
        catDropdown.addEventListener('keydown', function(e) {
            const items = Array.from(catDropdown.querySelectorAll('a'));
            const currentIndex = items.indexOf(document.activeElement);
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                if (currentIndex < items.length - 1) items[currentIndex + 1].focus();
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                if (currentIndex > 0) items[currentIndex - 1].focus();
            } else if (e.key === 'Escape') {
                catDropdown.classList.add('hidden');
                catBtn.setAttribute('aria-expanded', 'false');
                catBtn.focus();
            }
        });
    }

    // Mobile categories dropdown
    const mobileCatBtn = document.getElementById('mobile-all-categories-btn');
    const mobileCatDropdown = document.getElementById('mobile-categories-dropdown');
    if (mobileCatBtn && mobileCatDropdown) {
        mobileCatBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            const expanded = mobileCatDropdown.classList.toggle('hidden') ? 'false' : 'true';
            mobileCatBtn.setAttribute('aria-expanded', expanded);
        });
    }

    // Mobile search toggle
    document.getElementById('mobile-search-button').addEventListener('click', function() {
        const mobileSearch = document.getElementById('mobile-search');
        mobileSearch.classList.toggle('hidden');
    });

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileSearch = document.getElementById('mobile-search');
        const mobileSearchButton = document.getElementById('mobile-search-button');

        if (!mobileMenu.contains(event.target) && !mobileMenuButton.contains(event.target)) {
            mobileMenu.classList.add('hidden');
        }

        if (!mobileSearch.contains(event.target) && !mobileSearchButton.contains(event.target)) {
            mobileSearch.classList.add('hidden');
        }

        if (mobileCatDropdown && mobileCatBtn && !mobileCatDropdown.contains(event.target) && !mobileCatBtn.contains(event.target)) {
            mobileCatDropdown.classList.add('hidden');
            mobileCatBtn.setAttribute('aria-expanded', 'false');
        }
    });

    // Perform search (redirect to products page)
    function performSearch(source = 'desktop') {
        const searchInput = source === 'mobile'
            ? document.getElementById('mobile-search-input')
            : document.getElementById('search-input');
        const query = searchInput ? searchInput.value.trim() : '';

        if (query.length > 0) {
            let url = 'products.php?search=' + encodeURIComponent(query);
            window.location.href = url;
        }
    }

    // Allow Enter key to trigger search
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-input');
        if (searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    performSearch();
                }
            });
        }
        const mobileSearchInput = document.getElementById('mobile-search-input');
        if (mobileSearchInput) {
            mobileSearchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    performSearch('mobile');
                }
            });
        }

        // User dropdown menu toggle
        const userMenuBtn = document.getElementById('user-menu-btn');
        const userMenu = document.getElementById('user-menu');

        if (userMenuBtn && userMenu) {
            userMenuBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                userMenu.classList.toggle('hidden');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!document.getElementById('user-dropdown').contains(e.target)) {
                    userMenu.classList.add('hidden');
                }
            });

            // Prevent dropdown from closing when clicking inside it
            userMenu.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }

        // Currency selector handlers
        // const currencySelector = document.getElementById('currency-selector');
        // const currencySelectorMobile = document.getElementById('currency-selector-mobile');

        // if (currencySelector) {
        //     currencySelector.addEventListener('change', function() {
        //         changeCurrency(this.value);
        //     });
        // }

        // if (currencySelectorMobile) {
        //     currencySelectorMobile.addEventListener('change', function() {
        //         changeCurrency(this.value);
        //     });
        // }

        // Recently viewed dropdown
        const recentlyViewedDropdown = document.getElementById('recently-viewed-dropdown');
        if (recentlyViewedDropdown) {
            const button = recentlyViewedDropdown.querySelector('button');
            const dropdown = recentlyViewedDropdown.querySelector('div');

            button.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdown.classList.toggle('hidden');

                if (!dropdown.classList.contains('hidden')) {
                    loadRecentlyViewed();
                }
            });

            document.addEventListener('click', function(e) {
                if (!recentlyViewedDropdown.contains(e.target)) {
                    dropdown.classList.add('hidden');
                }
            });
        }
    });

    // Change currency via AJAX
    // function changeCurrency(currency) {
    //     fetch('<?= $base_url ?>api/set-currency.php', {
    //         method: 'POST',
    //         headers: {
    //             'Content-Type': 'application/x-www-form-urlencoded',
    //         },
    //         body: 'currency=' + currency
    //     })
    //     .then(response => response.json())
    //     .then(data => {
    //         if (data.success) {
    //             window.location.reload();
    //         }
    //     })
    //     .catch(error => console.error('Currency change error:', error));
    // }

    // Load recently viewed products
    function loadRecentlyViewed() {
        const content = document.getElementById('recently-viewed-content');

        fetch('<?= $base_url ?>api/get-recently-viewed.php')
            .then(response => response.json())
            .then(data => {
                if (data.products && data.products.length > 0) {
                    let html = '<div class="space-y-3">';
                    html += '<div class="flex items-center justify-between mb-3">';
                    html += '<h3 class="font-semibold text-gray-900">Recently Viewed</h3>';
                    html += '<a href="<?= $base_url ?>recently-viewed.php" class="text-sm text-purple-custom hover:underline">View All</a>';
                    html += '</div>';

                    data.products.forEach(product => {
                        html += '<a href="<?= $base_url ?>product_detail.php?slug=' + product.product_slug + '" class="flex items-center space-x-3 hover:bg-gray-50 p-2 rounded">';
                        html += '<img src="<?= $base_url ?>assets/images/products/' + (product.main_image || 'default.png') + '" alt="' + product.product_name + '" class="w-16 h-16 object-contain">';
                        html += '<div class="flex-1 min-w-0">';
                        html += '<p class="text-sm font-medium text-gray-900 truncate">' + product.product_name + '</p>';
                        html += '<p class="text-sm text-purple-custom font-semibold">Rs' + parseFloat(product.price).toFixed(2) + '</p>';
                        html += '</div>';
                        html += '</a>';
                    });

                    html += '</div>';
                    content.innerHTML = html;
                } else {
                    content.innerHTML = '<div class="text-center text-gray-500 py-4">No recently viewed products</div>';
                }
            })
            .catch(error => {
                console.error('Error loading recently viewed:', error);
                content.innerHTML = '<div class="text-center text-red-500 py-4">Error loading products</div>';
            });
    }
</script>

<!-- Include Cart and Search Scripts -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="<?= $base_url ?>assets/js/cart.js" defer></script>
<script src="<?= $base_url ?>assets/js/search.js" defer></script>
<script src="<?= $base_url ?>assets/js/wishlist.js" defer></script>