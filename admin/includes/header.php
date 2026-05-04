<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Geotrans</title>
    <link rel="icon" type="image/x-icon" href="../favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'purple-custom': '#680e68',
                    }
                }
            }
        }
    </script>
    <style>
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
            }
            .sidebar.open {
                transform: translateX(0);
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Mobile Menu Button -->
    <button id="mobileMenuBtn" class="lg:hidden fixed top-4 left-4 z-50 bg-gray-900 text-white p-2 rounded-md">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
    </button>

    <!-- Overlay for mobile -->
    <div id="overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden"></div>

    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar fixed lg:static inset-y-0 left-0 z-40 w-64 bg-gray-900 text-white overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold">Geotrans Admin</h1>
                        <p class="text-sm text-gray-400 mt-1">Welcome, <?= htmlspecialchars($_SESSION['admin_name'] ?? $_SESSION['admin_email'] ?? 'Admin') ?></p>
                    </div>
                    <button id="closeSidebar" class="lg:hidden text-gray-400 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <nav class="mt-6">
                <a href="dashboard.php" class="flex items-center px-6 py-3 hover:bg-gray-800 <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'bg-gray-800 border-l-4 border-purple-custom' : '' ?>">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Dashboard
                </a>
                
                <a href="orders.php" class="flex items-center px-6 py-3 hover:bg-gray-800 <?= basename($_SERVER['PHP_SELF']) == 'orders.php' ? 'bg-gray-800 border-l-4 border-purple-custom' : '' ?>">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    Orders
                </a>
                
                <a href="products.php" class="flex items-center px-6 py-3 hover:bg-gray-800 <?= basename($_SERVER['PHP_SELF']) == 'products.php' ? 'bg-gray-800 border-l-4 border-purple-custom' : '' ?>">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    Products
                </a>
                
                <a href="categories.php" class="flex items-center px-6 py-3 hover:bg-gray-800 <?= basename($_SERVER['PHP_SELF']) == 'categories.php' ? 'bg-gray-800 border-l-4 border-purple-custom' : '' ?>">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                    Categories
                </a>
                
                <a href="brands.php" class="flex items-center px-6 py-3 hover:bg-gray-800 <?= basename($_SERVER['PHP_SELF']) == 'brands.php' ? 'bg-gray-800 border-l-4 border-purple-custom' : '' ?>">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    Brands
                </a>
                
                <a href="users.php" class="flex items-center px-6 py-3 hover:bg-gray-800 <?= basename($_SERVER['PHP_SELF']) == 'users.php' ? 'bg-gray-800 border-l-4 border-purple-custom' : '' ?>">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    Users
                </a>
                
                <a href="reviews.php" class="flex items-center px-6 py-3 hover:bg-gray-800 <?= basename($_SERVER['PHP_SELF']) == 'reviews.php' ? 'bg-gray-800 border-l-4 border-purple-custom' : '' ?>">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                    </svg>
                    Reviews
                    <?php
                    // Show pending reviews count badge
                    if (isset($conn)) {
                        $pending_reviews_query = "SELECT COUNT(*) as total FROM product_reviews WHERE is_approved = 0";
                        $pending_reviews_result = $conn->query($pending_reviews_query);
                        if ($pending_reviews_result) {
                            $pending_reviews = $pending_reviews_result->fetch(PDO::FETCH_ASSOC);
                            if ($pending_reviews['total'] > 0) {
                                echo '<span class="ml-auto bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded-full">' . $pending_reviews['total'] . '</span>';
                            }
                        }
                    }
                    ?>
                </a>
                
                <a href="testimonials.php" class="flex items-center px-6 py-3 hover:bg-gray-800 <?= (basename($_SERVER['PHP_SELF']) == 'testimonials.php' || basename($_SERVER['PHP_SELF']) == 'testimonial-edit.php') ? 'bg-gray-800 border-l-4 border-purple-custom' : '' ?>">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    Testimonials
                </a>
                <a href="banners.php" class="flex items-center px-6 py-3 hover:bg-gray-800 <?= (basename($_SERVER['PHP_SELF']) == 'banners.php' || basename($_SERVER['PHP_SELF']) == 'banner-edit.php') ? 'bg-gray-800 border-l-4 border-purple-custom' : '' ?>">
                                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 17v-6m0 0l-3 3m3-3l3 3" />
                                                        <rect x="4" y="4" width="16" height="16" rx="2" ry="2" stroke="currentColor" stroke-width="2" fill="none" />
                                                    </svg>
                    Banners
                </a>
                <a href="settings.php" class="flex items-center px-6 py-3 hover:bg-gray-800 <?= (basename($_SERVER['PHP_SELF']) == 'settings.php' || basename($_SERVER['PHP_SELF']) == 'setting-edit.php') ? 'bg-gray-800 border-l-4 border-purple-custom' : '' ?>">
                                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    </svg>
                    Settings
                </a>
                
                <a href="contact-messages.php" class="flex items-center px-6 py-3 hover:bg-gray-800 <?= basename($_SERVER['PHP_SELF']) == 'contact-messages.php' ? 'bg-gray-800 border-l-4 border-purple-custom' : '' ?>">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Contact Messages
                    <?php
                    // Show unread count badge
                    if (isset($conn)) {
                        $unread_query = "SELECT COUNT(*) as total FROM contact_messages WHERE is_read = 0";
                        $unread_result = $conn->query($unread_query);
                        $unread_count = $unread_result->fetch(PDO::FETCH_ASSOC)['total'];
                        if ($unread_count > 0) {
                            echo '<span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">' . $unread_count . '</span>';
                        }
                    }
                    ?>
                </a>
                
                <!-- <a href="settings.php" class="flex items-center px-6 py-3 hover:bg-gray-800 <?= basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'bg-gray-800 border-l-4 border-purple-custom' : '' ?>">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Settings
                </a> -->
                
                <a href="logout.php" class="flex items-center px-6 py-3 hover:bg-gray-800 mt-6 border-t border-gray-700">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Logout
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto w-full lg:ml-0">
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between px-4 lg:px-6 py-4">
                    <h2 class="text-xl lg:text-2xl font-semibold text-gray-800 ml-12 lg:ml-0">
                        <?php
                        $page_titles = [
                            'dashboard.php' => 'Dashboard',
                            'orders.php' => 'Orders',
                            'products.php' => 'Products',
                            'categories.php' => 'Categories',
                            'brands.php' => 'Brands',
                            'users.php' => 'Users',
                            'reviews.php' => 'Reviews',
                            'contact-messages.php' => 'Contact Messages'
                        ];
                        echo $page_titles[basename($_SERVER['PHP_SELF'])] ?? 'Admin Panel';
                        ?>
                    </h2>
                    <a href="../index.php" target="_blank" class="bg-purple-custom text-white px-4 py-2 rounded-lg text-xs lg:text-sm font-semibold hover:bg-[#4f0a4f] transition">
                        View Website →
                    </a>
                </div>
            </header>
