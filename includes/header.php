<?php
if (!function_exists('isLoggedIn')) {
    require_once __DIR__ . '/../includes/helpers.php';
}
require_once __DIR__ . '/../classes/Cart.php';
require_once __DIR__ . '/../classes/Category.php';
require_once __DIR__ . '/../classes/Wishlist.php';
// require_once __DIR__ . '/../includes/currency.php';

// Define base URL for navigation
$base_url = '/Geotrans/';

$cart = new Cart();
$category = new Category();
$wishlist = new Wishlist();

$user_id = getUserId();
$session_id = !$user_id ? getSessionId() : null;
$cart_count = $cart->getCount($user_id, $session_id);
$wishlist_count = $user_id ? $wishlist->getCount($user_id) : 0;
$categories = $category->getAll(true); // Get only parent categories
// $selected_currency = CurrencyConverter::getSelectedCurrency();

?>

    <!-- Top Banner -->
    <div class="text-white py-2 px-4 sm:px-6 lg:px-20 text-center text-xs sm:text-sm" style="background-color: #8D4887;">
        <span class="bg-white px-2 sm:px-3 py-1 rounded-full font-semibold text-xs mr-1 sm:mr-2" style="color: #8D4887;">Special</span>
        <span class="hidden sm:inline">Get 10% <span class="font-bold">DISCOUNT</span> for first order</span>
        <span class="sm:hidden">10% <span class="font-bold">OFF</span> First Order</span>
        <?php if (!isLoggedIn()): ?>
        <a href="<?= $base_url ?>register.php" class="underline ml-1 sm:ml-2 font-semibold" style="color: #FFFFFF;" onmouseover="this.style.color='#C4A3D1'" onmouseout="this.style.color='#FFFFFF'">Register Now</a>
        <?php endif; ?>
    </div>

    <!-- Main Header -->
    <header id="main-header" class="bg-white shadow-sm sticky top-0 z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <!-- Mobile Header -->
            <div class="lg:hidden flex items-center justify-between">
                <!-- Mobile Menu Button -->
                <button id="mobile-menu-button" class="p-2" style="color: #8D4887;">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                
                <!-- Logo -->
                <a href="<?= $base_url ?>index.php" class="flex items-center">
                    <img src="<?= $base_url ?>assets/images/logo.png" alt="GeoTrans Pvt Ltd Logo" class="h-12 object-contain">
                </a>

                <!-- Mobile Icons -->
                <div class="flex items-center space-x-3">
                    <!-- Search Icon -->
                    <button id="mobile-search-button" style="color: #8D4887;">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                    
                    <!-- Wishlist -->
                    <a href="<?= $base_url ?>wishlist.php" class="relative" style="color: #8D4887;">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        <?php if ($wishlist_count > 0): ?>
                        <span class="wishlist-count absolute -top-2 -right-2 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center" style="background-color: #8D4887;"><?php echo $wishlist_count; ?></span>
                        <?php endif; ?>
                    </a>

                    <!-- Cart -->
                    <a href="<?= $base_url ?>cart.php" class="relative" style="color: #8D4887;">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <?php if ($cart_count > 0): ?>
                        <span id="mobile-cart-count" class="absolute -top-2 -right-2 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center" style="background-color: #8D4887;"><?php echo $cart_count; ?></span>
                        <?php endif; ?>
                    </a>
                </div>
            </div>

            <!-- Mobile Search Bar (Hidden by default) -->
            <div id="mobile-search" class="lg:hidden hidden mt-4">
                <div class="flex flex-col gap-2">
                    <select id="category-select-mobile" class="bg-gray-100 border-0 rounded-lg px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2" style="--tw-ring-color: #8D4887;">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['category_id']; ?>"><?php echo htmlspecialchars($cat['category_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="flex items-center">
                        <div class="relative flex-1">
                            <input type="text" id="search-input-mobile" placeholder="Search anything..."
                                class="w-full px-3 py-2 border-0 bg-gray-100 text-sm rounded-l-lg focus:outline-none focus:ring-2" style="--tw-ring-color: #8D4887;" />
                        </div>
                        <button onclick="performMobileSearch()" class="text-white px-4 py-2 rounded-r-lg transition-colors" style="background-color: #8D4887;" onmouseover="this.style.backgroundColor='#9C5AA2'" onmouseout="this.style.backgroundColor='#8D4887'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Desktop Header -->
            <div class="hidden lg:flex items-center justify-between">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="<?= $base_url ?>index.php" class="flex items-center">
                        <img src="<?= $base_url ?>assets/images/logo.png" alt="GeoTrans Pvt Ltd Logo" class="h-16 object-contain">
                    </a>
                </div>

                <!-- Search Bar -->
                <div class="flex items-center flex-1 max-w-2xl mx-8 search-container relative">
                    <select id="category-select" class="bg-gray-100 border-0 rounded-l-lg px-4 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2" style="--tw-ring-color: #8D4887;">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['category_id']; ?>"><?php echo htmlspecialchars($cat['category_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="relative flex-1">
                        <input type="text" id="search-input" placeholder="Search anything..."
                            class="w-full px-4 py-2 border-0 bg-gray-100 text-sm focus:outline-none focus:ring-2" style="--tw-ring-color: #8D4887;" />
                        <div id="search-results" class="absolute top-full left-0 right-0 bg-white mt-1 rounded-lg shadow-lg max-h-96 overflow-y-auto z-50 hidden"></div>
                    </div>
                    <button onclick="performSearch()" class="text-white px-6 py-2 rounded-r-lg transition-colors" style="background-color: #8D4887;" onmouseover="this.style.backgroundColor='#9C5AA2'" onmouseout="this.style.backgroundColor='#8D4887'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </div>

                <!-- Right Side Icons & Contact -->
                <div class="flex items-center space-x-6">
                    <!-- Hotline -->
                    <div class="text-right hidden xl:block">
                        <div class="text-xs text-gray-500">Hotline 24/7</div>
                        <div class="text-sm font-semibold" style="color: #8D4887;">+94 71 3757555</div>
                    </div>

                    <!-- Wishlist -->
                    <a href="<?= $base_url ?>wishlist.php" class="relative" style="color: #8D4887;">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        <?php if ($wishlist_count > 0): ?>
                        <span class="wishlist-count absolute -top-2 -right-2 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center" style="background-color: #8D4887;"><?php echo $wishlist_count; ?></span>
                        <?php endif; ?>
                    </a>

                    <!-- Cart -->
                    <a href="<?= $base_url ?>cart.php" class="relative" style="color: #8D4887;">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <?php if ($cart_count > 0): ?>
                        <span id="cart-count" class="absolute -top-2 -right-2 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center" style="background-color: #8D4887;"><?php echo $cart_count; ?></span>
                        <?php else: ?>
                        <span id="cart-count" class="absolute -top-2 -right-2 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center hidden" style="background-color: #8D4887;">0</span>
                        <?php endif; ?>
                    </a>

                    <!-- User Account -->
                    <?php if (isLoggedIn()): ?>
                    <div class="relative" id="user-dropdown">
                        <button id="user-menu-btn" class="flex items-center gap-2" style="color: #8D4887;">
                            <?php 
                            $profile_photo = getUserData('profile_photo');
                            if (!empty($profile_photo) && file_exists(__DIR__ . '/../assets/images/profiles/' . $profile_photo)): 
                            ?>
                                <img src="<?= $base_url ?>assets/images/profiles/<?= htmlspecialchars($profile_photo) ?>" 
                                     alt="<?= htmlspecialchars(getUserData('first_name')) ?>" 
                                     class="w-8 h-8 rounded-full object-cover border-2 border-purple-300">
                            <?php else: ?>
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-semibold text-sm" style="background-color: #8D4887;">
                                    <?= strtoupper(substr(getUserData('first_name'), 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                            <div class="hidden lg:block text-left">
                                <div class="text-xs text-gray-500">Hello</div>
                                <div class="text-sm font-semibold"><?php echo htmlspecialchars(getUserData('first_name')); ?></div>
                            </div>
                        </button>
                        <div id="user-menu" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 hidden z-50">
                            <a href="<?= $base_url ?>account/profile.php" class="block px-4 py-2 text-sm hover:bg-gray-100">My Profile</a>
                            <a href="<?= $base_url ?>account/orders.php" class="block px-4 py-2 text-sm hover:bg-gray-100">My Orders</a>
                            <a href="<?= $base_url ?>account/addresses.php" class="block px-4 py-2 text-sm hover:bg-gray-100">My Addresses</a>
                            <a href="<?= $base_url ?>wishlist.php" class="block px-4 py-2 text-sm hover:bg-gray-100">My Wishlist</a>
                            <?php if (isAdmin()): ?>
                            <a href="<?= $base_url ?>admin/" class="block px-4 py-2 text-sm hover:bg-gray-100">Admin Panel</a>
                            <?php endif; ?>
                            <hr class="my-2">
                            <a href="<?= $base_url ?>logout.php" class="block px-4 py-2 text-sm hover:bg-gray-100 text-red-600">Logout</a>
                        </div>
                    </div>
                    <?php else: ?>
                    <a href="<?= $base_url ?>login.php" class="text-sm font-semibold px-4 py-2 rounded hover:bg-purple-50" style="color: #8D4887;">Login / Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="border-t border-gray-200">
            <!-- Mobile Menu (Hidden by default) -->
            <div id="mobile-menu" class="lg:hidden hidden border-b border-gray-200">
                <div class="px-4 py-3 space-y-3">
                    <!-- User Section -->
                    <?php if (isLoggedIn()): ?>
                    <div class="flex items-center gap-3 pb-3 border-b border-gray-200">
                        <?php 
                        $profile_photo = getUserData('profile_photo');
                        if (!empty($profile_photo) && file_exists(__DIR__ . '/../assets/images/profiles/' . $profile_photo)): 
                        ?>
                            <img src="<?= $base_url ?>assets/images/profiles/<?= htmlspecialchars($profile_photo) ?>" 
                                 alt="<?= htmlspecialchars(getUserData('first_name')) ?>" 
                                 class="w-10 h-10 rounded-full object-cover border-2 border-purple-300">
                        <?php else: ?>
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-semibold" style="background-color: #8D4887;">
                                <?= strtoupper(substr(getUserData('first_name'), 0, 1)) ?>
                            </div>
                        <?php endif; ?>
                        <div>
                            <div class="text-xs text-gray-500">Hello</div>
                            <div class="text-sm font-semibold" style="color: #8D4887;"><?php echo htmlspecialchars(getUserData('first_name')); ?></div>
                        </div>
                    </div>
                    <div class="space-y-2 pb-3 border-b border-gray-200">
                        <a href="<?= $base_url ?>account/profile.php" class="block py-2 text-sm" style="color: #8D4887;">My Profile</a>
                        <a href="<?= $base_url ?>account/orders.php" class="block py-2 text-sm" style="color: #8D4887;">My Orders</a>
                        <a href="<?= $base_url ?>account/addresses.php" class="block py-2 text-sm" style="color: #8D4887;">My Addresses</a>
                        <a href="<?= $base_url ?>wishlist.php" class="block py-2 text-sm" style="color: #8D4887;">My Wishlist</a>
                        <?php if (isAdmin()): ?>
                        <a href="<?= $base_url ?>admin/" class="block py-2 text-sm" style="color: #8D4887;">Admin Panel</a>
                        <?php endif; ?>
                        <a href="<?= $base_url ?>logout.php" class="block py-2 text-sm text-red-600">Logout</a>
                    </div>
                    <?php else: ?>
                    <div class="pb-3 border-b border-gray-200">
                        <a href="<?= $base_url ?>login.php" class="block w-full text-center text-white font-semibold px-4 py-3 rounded-lg transition-colors" style="background-color: #8D4887;" onmouseover="this.style.backgroundColor='#9C5AA2'" onmouseout="this.style.backgroundColor='#8D4887'">Login / Register</a>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Main Navigation -->
                    <div class="space-y-2">
                        <a href="<?= $base_url ?>index.php" class="block py-2 text-sm" style="color: #8D4887;">Home</a>
                        <a href="<?= $base_url ?>about.php" class="block py-2 text-sm" style="color: #8D4887;">About</a>
                        <a href="<?= $base_url ?>products.php" class="block py-2 text-sm" style="color: #8D4887;">Products</a>
                        <a href="<?= $base_url ?>contact.php" class="block py-2 text-sm" style="color: #8D4887;">Contact</a>
                    </div>
                    
                    <!-- Additional Links -->
                    <div class="border-t border-gray-200 pt-3 space-y-2">
                        <a href="<?= $base_url ?>submit-feedback.php" class="flex items-center py-2 text-sm" style="color: #8D4887;">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            Submit Feedback
                        </a>
                        <a href="<?= $base_url ?>order-tracking.php" class="flex items-center py-2 text-sm" style="color: #8D4887;">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            Order Tracking
                        </a>
                        <a href="<?= $base_url ?>recently-viewed.php" class="block py-2 text-sm" style="color: #8D4887;">Recently Viewed</a>
                    </div>
                    
                    <!-- Currency -->
                    <div class="border-t border-gray-200 pt-3">
                        <div class="text-xs text-gray-500 mb-2">Hotline: +94 71 3757555</div>
                        <!-- <div class="flex items-center space-x-4">
                            <select id="currency-selector-mobile" class="bg-gray-100 border rounded px-2 py-1 text-sm focus:outline-none" style="color: #8D4887;">
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
            <div class="hidden lg:block max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between py-3">
                    <ul class="flex items-center space-x-8 text-sm">
                        <li>
                            <a href="<?= $base_url ?>index.php" class="flex items-center hover:text-purple-700" style="color: #8D4887;">
                                Home
                            </a>
                        </li>
                        <li>
                            <a href="<?= $base_url ?>about.php" class="flex items-center hover:text-purple-700" style="color: #8D4887;">
                                About
                            </a>
                        </li>
                        <li>
                            <a href="<?= $base_url ?>products.php" class="flex items-center hover:text-purple-700" style="color: #8D4887;">
                                Products
                            </a>
                        </li>
                        <li>
                            <a href="<?= $base_url ?>contact.php" class="hover:text-purple-700" style="color: #8D4887;">Contact</a>
                        </li>
                    </ul>

                    <ul class="flex items-center space-x-6 text-sm">
                        <li class="hidden xl:block">
                            <a href="<?= $base_url ?>submit-feedback.php" class="flex items-center" style="color: #8D4887;">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                Submit Feedback
                            </a>
                        </li>
                        <li class="hidden xl:block">
                            <a href="<?= $base_url ?>order-tracking.php" class="flex items-center" style="color: #8D4887;">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                Order Tracking
                            </a>
                        </li>
                        <li class="hidden lg:block relative" id="recently-viewed-dropdown">
                            <button type="button" class="flex items-center" style="color: #8D4887;">
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
                                <select id="currency-selector" class="bg-transparent border-0 text-xs lg:text-sm text-gray-600 focus:outline-none cursor-pointer" style="color: #8D4887;">
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
        });

        // Perform search (redirect to products page)
        function performSearch() {
            const searchInput = document.getElementById('search-input');
            const categorySelect = document.getElementById('category-select');
            const query = searchInput.value.trim();
            
            if (query.length > 0) {
                let url = 'products.php?search=' + encodeURIComponent(query);
                if (categorySelect && categorySelect.value) {
                    url += '&category=' + categorySelect.value;
                }
                window.location.href = url;
            }
        }
        
        // Perform mobile search
        function performMobileSearch() {
            const searchInput = document.getElementById('search-input-mobile');
            const categorySelect = document.getElementById('category-select-mobile');
            const query = searchInput.value.trim();
            
            if (query.length > 0) {
                let url = 'products.php?search=' + encodeURIComponent(query);
                if (categorySelect && categorySelect.value) {
                    url += '&category=' + categorySelect.value;
                }
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
            
            const searchInputMobile = document.getElementById('search-input-mobile');
            if (searchInputMobile) {
                searchInputMobile.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        performMobileSearch();
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

