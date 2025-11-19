<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASUS Laptops - In Search of Incredible</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Ensure Add to Cart buttons line up at the bottom of each product card */
        .product-grid > .bg-white {
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .product-grid > .bg-white > .p-4 {
            display: flex;
            flex-direction: column;
            flex: 1 1 auto;
        }
        .product-grid > .bg-white > .p-4 > button {
            margin-top: auto;
        }
    </style>
</head>
<body class="bg-gray-50">

<!-- Include Header -->
        <?php include 'includes/header.php'; ?>
    <!-- Hero Banner -->
    <div class="relative h-48 bg-gradient-to-r from-blue-900 via-purple-900 to-pink-900 overflow-hidden">
        <img src="https://images.unsplash.com/photo-1593642632823-8f785ba67e45?w=1200" alt="Laptop" class="absolute inset-0 w-full h-full object-cover opacity-40">
    <div class="relative z-10 max-w-7xl mx-auto px-4 h-full flex flex-col justify-center">
            <h1 class="text-white text-4xl font-bold mb-2">In Search of Incredible.</h1>
            <p class="text-gray-200 text-lg">Pushing the boundaries of innovation since 1989</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar -->
            <aside class="lg:w-64 flex-shrink-0">
                <!-- ASUS Logo -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-2xl font-bold mb-2">ASUS Laptops</h2>
                    <p class="text-sm text-gray-600">BEST SELLERS THIS CATEGORY</p>
                </div>

                <!-- Categories -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h3 class="font-bold text-lg mb-4 flex items-center">
                        <i class="fas fa-list mr-2"></i>CATEGORIES
                    </h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-700 hover:text-blue-600 flex items-center"><i class="fas fa-laptop mr-2"></i>Laptop</a></li>
                        <li><a href="#" class="text-gray-700 hover:text-blue-600 flex items-center"><i class="fas fa-desktop mr-2"></i>Desktop</a></li>
                        <li><a href="#" class="text-gray-700 hover:text-blue-600 flex items-center"><i class="fas fa-tv mr-2"></i>Monitor</a></li>
                        <li><a href="#" class="text-gray-700 hover:text-blue-600 flex items-center"><i class="fas fa-microchip mr-2"></i>Motherboard</a></li>
                        <li><a href="#" class="text-gray-700 hover:text-blue-600 flex items-center"><i class="fas fa-wifi mr-2"></i>Networking</a></li>
                    </ul>
                </div>

                <!-- Price Filter -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h3 class="font-bold text-lg mb-4">PRICE</h3>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2">
                            <span class="text-gray-700">Under Rs1,000</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2">
                            <span class="text-gray-700">Rs1,000 - Rs2,000</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2">
                            <span class="text-gray-700">Rs2,000 - Rs3,000</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2">
                            <span class="text-gray-700">Over Rs3,000</span>
                        </label>
                    </div>
                </div>

                <!-- Screen Size -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h3 class="font-bold text-lg mb-4">SCREEN SIZE</h3>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2">
                            <span class="text-gray-700">13" - 14"</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2">
                            <span class="text-gray-700">15" - 16"</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2">
                            <span class="text-gray-700">17"+</span>
                        </label>
                    </div>
                </div>

                <!-- Color Filter -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h3 class="font-bold text-lg mb-4">COLOR</h3>
                    <div class="flex gap-3 flex-wrap">
                        <button class="w-8 h-8 rounded-full bg-gray-800 border-2 border-gray-300 hover:border-blue-500"></button>
                        <button class="w-8 h-8 rounded-full bg-gray-400 border-2 border-gray-300 hover:border-blue-500"></button>
                        <button class="w-8 h-8 rounded-full bg-blue-600 border-2 border-gray-300 hover:border-blue-500"></button>
                        <button class="w-8 h-8 rounded-full bg-white border-2 border-gray-300 hover:border-blue-500"></button>
                    </div>
                </div>

                <!-- Promo Banner -->
                <div class="bg-gradient-to-br from-purple-600 to-pink-600 rounded-lg shadow p-6 text-white">
                    <h3 class="font-bold text-xl mb-2">BLACK FRIDAY</h3>
                    <p class="text-sm mb-4">SALE UP TO 50% OFF</p>
                    <img src="https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=300" alt="Laptop" class="w-full rounded-lg">
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1">
                <!-- Sort Bar -->
                <div class="bg-white rounded-lg shadow p-4 mb-6 flex justify-between items-center">
                    <div class="text-gray-600">
                        <span class="font-semibold">1-20</span> of <span class="font-semibold">242</span> results for <span class="font-semibold">ASUS Laptops</span>
                    </div>
                    <select class="border border-gray-300 rounded px-4 py-2">
                        <option>Sort by: Newest</option>
                        <option>Price: Low to High</option>
                        <option>Price: High to Low</option>
                        <option>Best Selling</option>
                    </select>
                </div>

                <!-- Product Grid -->
                <div class="grid product-grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <!-- Product Card 1 -->
                    <div class="bg-white rounded-lg shadow hover:shadow-xl transition-shadow overflow-hidden group">
                        <div class="relative">
                            <span class="absolute top-2 left-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded">SALE</span>
                            <img src="https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=400" alt="ASUS Laptop" class="w-full h-48 object-cover group-hover:scale-105 transition-transform">
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">ASUS VivoBook 15 Intel Core i5</h3>
                            <div class="flex items-center mb-2">
                                <div class="flex text-yellow-400">
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="fas fa-star-half-alt text-xs"></i>
                                </div>
                                <span class="text-gray-500 text-xs ml-2">(245)</span>
                            </div>
                            <div class="mb-3">
                                <span class="text-2xl font-bold text-green-600">Rs799.99</span>
                                <span class="text-gray-400 line-through ml-2">Rs999.99</span>
                            </div>
                            <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition-colors" style="background-color: #8D4887;">
                                <i class="fas fa-shopping-cart mr-2"></i>Add to Cart
                            </button>
                        </div>
                    </div>

                    <!-- Product Card 2 -->
                    <div class="bg-white rounded-lg shadow hover:shadow-xl transition-shadow overflow-hidden group">
                        <div class="relative">
                            <span class="absolute top-2 left-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded">SALE</span>
                            <img src="https://images.unsplash.com/photo-1593642632823-8f785ba67e45?w=400" alt="ASUS Laptop" class="w-full h-48 object-cover group-hover:scale-105 transition-transform">
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">ASUS ZenBook 14 Ultra-Slim</h3>
                            <div class="flex items-center mb-2">
                                <div class="flex text-yellow-400">
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="fas fa-star text-xs"></i>
                                </div>
                                <span class="text-gray-500 text-xs ml-2">(189)</span>
                            </div>
                            <div class="mb-3">
                                <span class="text-2xl font-bold text-green-600">Rs1,299.99</span>
                                <span class="text-gray-400 line-through ml-2">Rs1,599.99</span>
                            </div>
                            <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition-colors" style="background-color: #8D4887;">
                                <i class="fas fa-shopping-cart mr-2"></i>Add to Cart
                            </button>
                        </div>
                    </div>

                    <!-- Product Card 3 -->
                    <div class="bg-white rounded-lg shadow hover:shadow-xl transition-shadow overflow-hidden group">
                        <div class="relative">
                            <span class="absolute top-2 left-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded">SALE</span>
                            <img src="https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=400" alt="ASUS Laptop" class="w-full h-48 object-cover group-hover:scale-105 transition-transform">
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">ASUS ROG Strix G15 Gaming</h3>
                            <div class="flex items-center mb-2">
                                <div class="flex text-yellow-400">
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="far fa-star text-xs"></i>
                                </div>
                                <span class="text-gray-500 text-xs ml-2">(312)</span>
                            </div>
                            <div class="mb-3">
                                <span class="text-2xl font-bold text-green-600">Rs1,799.99</span>
                                <span class="text-gray-400 line-through ml-2">Rs2,199.99</span>
                            </div>
                            <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition-colors" style="background-color: #8D4887;">
                                <i class="fas fa-shopping-cart mr-2"></i>Add to Cart
                            </button>
                        </div>
                    </div>

                    <!-- Product Card 4 -->
                    <div class="bg-white rounded-lg shadow hover:shadow-xl transition-shadow overflow-hidden group">
                        <div class="relative">
                            <span class="absolute top-2 left-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded">SALE</span>
                            <img src="https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=400" alt="ASUS Laptop" class="w-full h-48 object-cover group-hover:scale-105 transition-transform">
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">ASUS TUF Gaming A15</h3>
                            <div class="flex items-center mb-2">
                                <div class="flex text-yellow-400">
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="fas fa-star-half-alt text-xs"></i>
                                </div>
                                <span class="text-gray-500 text-xs ml-2">(428)</span>
                            </div>
                            <div class="mb-3">
                                <span class="text-2xl font-bold text-green-600">Rs1,099.99</span>
                                <span class="text-gray-400 line-through ml-2">Rs1,399.99</span>
                            </div>
                            <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition-colors" style="background-color: #8D4887;">
                                <i class="fas fa-shopping-cart mr-2"></i>Add to Cart
                            </button>
                        </div>
                    </div>

                    <!-- Product Card 5 -->
                    <div class="bg-white rounded-lg shadow hover:shadow-xl transition-shadow overflow-hidden group">
                        <div class="relative">
                            <img src="https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=400" alt="ASUS Laptop" class="w-full h-48 object-cover group-hover:scale-105 transition-transform">
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">ASUS Chromebook Flip C434</h3>
                            <div class="flex items-center mb-2">
                                <div class="flex text-yellow-400">
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="far fa-star text-xs"></i>
                                </div>
                                <span class="text-gray-500 text-xs ml-2">(156)</span>
                            </div>
                            <div class="mb-3">
                                <span class="text-2xl font-bold text-green-600">Rs599.99</span>
                            </div>
                            <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition-colors" style="background-color: #8D4887;">
                                <i class="fas fa-shopping-cart mr-2"></i>Add to Cart
                            </button>
                        </div>
                    </div>

                    <!-- Product Card 6 -->
                    <div class="bg-white rounded-lg shadow hover:shadow-xl transition-shadow overflow-hidden group">
                        <div class="relative">
                            <span class="absolute top-2 left-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded">SALE</span>
                            <img src="https://images.unsplash.com/photo-1624705002806-5d72df19c3ad?w=400" alt="ASUS Laptop" class="w-full h-48 object-cover group-hover:scale-105 transition-transform">
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">ASUS ProArt StudioBook</h3>
                            <div class="flex items-center mb-2">
                                <div class="flex text-yellow-400">
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="fas fa-star text-xs"></i>
                                </div>
                                <span class="text-gray-500 text-xs ml-2">(92)</span>
                            </div>
                            <div class="mb-3">
                                <span class="text-2xl font-bold text-green-600">Rs2,499.99</span>
                                <span class="text-gray-400 line-through ml-2">Rs2,999.99</span>
                            </div>
                            <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition-colors" style="background-color: #8D4887;">
                                <i class="fas fa-shopping-cart mr-2"></i>Add to Cart
                            </button>
                        </div>
                    </div>

                    <!-- Product Card 7 -->
                    <div class="bg-white rounded-lg shadow hover:shadow-xl transition-shadow overflow-hidden group">
                        <div class="relative">
                            <img src="https://images.unsplash.com/photo-1587202372634-32705e3bf49c?w=400" alt="ASUS Laptop" class="w-full h-48 object-cover group-hover:scale-105 transition-transform">
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">ASUS VivoBook S15 Thin</h3>
                            <div class="flex items-center mb-2">
                                <div class="flex text-yellow-400">
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="fas fa-star-half-alt text-xs"></i>
                                    <i class="far fa-star text-xs"></i>
                                </div>
                                <span class="text-gray-500 text-xs ml-2">(178)</span>
                            </div>
                            <div class="mb-3">
                                <span class="text-2xl font-bold text-green-600">Rs699.99</span>
                            </div>
                            <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition-colors" style="background-color: #8D4887;">
                                <i class="fas fa-shopping-cart mr-2"></i>Add to Cart
                            </button>
                        </div>
                    </div>

                    <!-- Product Card 8 -->
                    <div class="bg-white rounded-lg shadow hover:shadow-xl transition-shadow overflow-hidden group">
                        <div class="relative">
                            <span class="absolute top-2 left-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded">SALE</span>
                            <img src="https://images.unsplash.com/photo-1504707748692-419802cf939d?w=400" alt="ASUS Laptop" class="w-full h-48 object-cover group-hover:scale-105 transition-transform">
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">ASUS ZenBook Pro Duo 15</h3>
                            <div class="flex items-center mb-2">
                                <div class="flex text-yellow-400">
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="fas fa-star text-xs"></i>
                                    <i class="fas fa-star text-xs"></i>
                                </div>
                                <span class="text-gray-500 text-xs ml-2">(67)</span>
                            </div>
                            <div class="mb-3">
                                <span class="text-2xl font-bold text-green-600">Rs2,999.99</span>
                                <span class="text-gray-400 line-through ml-2">Rs3,499.99</span>
                            </div>
                            <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition-colors" style="background-color: #8D4887;">
                                <i class="fas fa-shopping-cart mr-2"></i>Add to Cart
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="flex justify-center items-center gap-2 mt-8">
                    <button class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="px-4 py-2 bg-blue-600 text-white rounded" style="background-color: #8D4887;">1</button>
                    <button class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">2</button>
                    <button class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">3</button>
                    <button class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">...</button>
                    <button class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">13</button>
                    <button class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </main>
        </div>
    </div>

    <!-- Include Header -->
        <?php include 'includes/footer.php'; ?>
</body>
</html>