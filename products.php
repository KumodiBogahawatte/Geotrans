<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white">

    <!-- Include Header -->
    <?php include 'includes/header.php'; ?>

    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Top Banners -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
            <div class="bg-gray-800 rounded-lg p-8 flex items-center justify-between" style="background-color: #A1A4AD; background-image: url('assets/images/pages/noice-cancel-headphone.png'); background-size: cover; background-position: center;">
                <div class="text-white">
                    <p class="text-sm uppercase mb-2">The best place to play</p>
                    <h2 class="text-2xl font-bold mb-4">Noise Cancelling<br>Headphones</h2>
                    <button class="bg-white text-black px-6 py-2 rounded-full text-sm font-medium">SHOP NOW</button>
                </div>
                <!-- <img src="assets/images/pages/noice-cancel-headphone.png" alt="Headphones" class="w-32 h-32 object-cover rounded"> -->
            </div>
            <div class="bg-red-700 rounded-lg p-8 flex items-center justify-between" style="background-image: url('assets/images/pages/homepod-mini.jpeg'); background-size: cover; background-position: center;">
                <div class="text-white">
                    <p class="text-sm uppercase mb-2">Introducing New</p>
                    <h2 class="text-2xl font-bold mb-4">Apple Homepod<br>Mini</h2>
                    <button class="bg-white text-black px-6 py-2 rounded-full text-sm font-medium">SHOP NOW</button>
                </div>
                <!-- <img src="" alt="Homepod" class="w-32 h-32 object-cover rounded"> -->
            </div>
        </div>

        <!-- Popular Categories -->
        <div class="mb-8">
            <h3 class="text-sm font-semibold uppercase mb-4">Popular Categories</h3>
            <div class="grid grid-cols-3 md:grid-cols-6 gap-4">
                <div class="text-center">
                    <div class="bg-gray-100 rounded-lg p-4 mb-2">
                        <img src="https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=80&h=80&fit=crop" alt="Cameras" class="w-12 h-12 mx-auto">
                    </div>
                    <p class="text-xs">Cameras</p>
                </div>
                <div class="text-center">
                    <div class="bg-gray-100 rounded-lg p-4 mb-2">
                        <img src="https://images.unsplash.com/photo-1593078165-7dc5e5e1e31e?w=80&h=80&fit=crop" alt="Computers" class="w-12 h-12 mx-auto">
                    </div>
                    <p class="text-xs">Computers</p>
                </div>
                <div class="text-center">
                    <div class="bg-gray-100 rounded-lg p-4 mb-2">
                        <img src="https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=80&h=80&fit=crop" alt="Gaming" class="w-12 h-12 mx-auto">
                    </div>
                    <p class="text-xs">Gaming</p>
                </div>
                <div class="text-center">
                    <div class="bg-gray-100 rounded-lg p-4 mb-2">
                        <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=80&h=80&fit=crop" alt="Headphones" class="w-12 h-12 mx-auto">
                    </div>
                    <p class="text-xs">Headphones</p>
                </div>
                <div class="text-center">
                    <div class="bg-gray-100 rounded-lg p-4 mb-2">
                        <img src="https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=80&h=80&fit=crop" alt="Phones" class="w-12 h-12 mx-auto">
                    </div>
                    <p class="text-xs">Phones</p>
                </div>
                <div class="text-center">
                    <div class="bg-gray-100 rounded-lg p-4 mb-2">
                        <img src="https://images.unsplash.com/photo-1546868871-7041f2a55e12?w=80&h=80&fit=crop" alt="Smart Watches" class="w-12 h-12 mx-auto">
                    </div>
                    <p class="text-xs">Smart Watches</p>
                </div>
            </div>
        </div>

        <div class="flex flex-col md:flex-row gap-6">
            <!-- Sidebar -->
            <div class="w-full md:w-64 flex-shrink-0 space-y-6">
                <!-- Categories -->
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-semibold">CATEGORIES</h3>
                        <button class="text-sm text-gray-500">View All</button>
                    </div>
                    <ul class="space-y-2 text-sm">
                        <li class="flex justify-between"><span>Computer & Laptop</span><span class="text-gray-400">→</span></li>
                        <li class="flex justify-between"><span>SmartPhone</span><span class="text-gray-400">→</span></li>
                        <li class="flex justify-between"><span>Headphone</span><span class="text-gray-400">→</span></li>
                        <li class="flex justify-between"><span>Accessories</span><span class="text-gray-400">→</span></li>
                        <li class="flex justify-between"><span>Camera & Photo</span><span class="text-gray-400">→</span></li>
                        <li class="flex justify-between"><span>TV & Homes</span><span class="text-gray-400">→</span></li>
                    </ul>
                </div>

                <!-- Price Range -->
                <div>
                    <h3 class="font-semibold mb-4">PRICE</h3>
                    <input type="range" class="w-full mb-2" min="0" max="5000" value="1000">
                    <div class="flex justify-between text-sm">
                        <span>Rs0</span>
                        <span>Rs5000</span>
                    </div>
                </div>

                <!-- Colors -->
                <div>
                    <h3 class="font-semibold mb-4">COLORS</h3>
                    <div class="grid grid-cols-5 gap-2">
                        <div class="w-6 h-6 rounded-full bg-blue-500 border-2 border-gray-300"></div>
                        <div class="w-6 h-6 rounded-full bg-green-500"></div>
                        <div class="w-6 h-6 rounded-full bg-red-500"></div>
                        <div class="w-6 h-6 rounded-full bg-purple-500"></div>
                        <div class="w-6 h-6 rounded-full bg-black"></div>
                    </div>
                </div>

                <!-- Brands -->
                <div>
                    <h3 class="font-semibold mb-4">BRANDS</h3>
                    <div class="space-y-2 text-sm">
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2">
                            <span>Apple</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2">
                            <span>Samsung</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2">
                            <span>Xiaomi</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2">
                            <span>Huawei</span>
                        </label>
                    </div>
                </div>

                <!-- Promo Banner -->
                <div class="bg-black rounded-lg p-6 text-white" style="background-image: url('assets/images/home/headphone1.png'); background-size: cover; background-position: center;">
                    <p class="text-sm mb-2">SUMMER SALES</p>
                    <h3 class="text-lg font-bold mb-2">CANON New T3i<br>3X Wireless</h3>
                    <p class="text-2xl font-bold mb-4 text-yellow-400">Rs699</p>
                    <button class="bg-yellow-400 text-black px-4 py-2 rounded text-sm font-medium w-full">SHOP NOW</button>
                </div>
            </div>

            <!-- Main Content -->
            <div class="flex-1">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold">BEST SELLERS IN THE CATEGORY</h2>
                    <div class="flex gap-2">
                        <button class="px-4 py-1 border rounded text-sm">All Product</button>
                        <button class="px-4 py-1 border rounded text-sm">Smart Phone</button>
                    </div>
                </div>

                <!-- Product Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Product Card 1 -->
                    <div class="border rounded-lg p-4 hover:shadow-lg transition">
                        <div class="relative mb-4">
                            <span class="absolute top-2 left-2 bg-green-500 text-white text-xs px-2 py-1 rounded">14%</span>
                            <img src="https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=300&h=300&fit=crop" alt="Product" class="w-full h-40 object-cover rounded">
                        </div>
                        <h3 class="text-sm mb-2">TOZO T6 True Wireless Earbuds Bluetooth</h3>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-blue-600 font-bold">Rs70.00</span>
                            <span class="text-gray-400 line-through text-sm">Rs100.00</span>
                        </div>
                        <div class="flex items-center text-yellow-400 text-xs mb-3">
                            ★★★★★ <span class="text-gray-400 ml-1">(536)</span>
                        </div>
                        <button class="w-full bg-green-500 text-white py-2 rounded text-sm font-medium" style="background-color: #8D4887;">Add to Cart</button>
                    </div>

                    <!-- Product Card 2 -->
                    <div class="border rounded-lg p-4 hover:shadow-lg transition">
                        <div class="relative mb-4">
                            <span class="absolute top-2 left-2 bg-green-500 text-white text-xs px-2 py-1 rounded">25%</span>
                            <img src="https://images.unsplash.com/photo-1585792180666-f7347c490ee2?w=300&h=300&fit=crop" alt="Product" class="w-full h-40 object-cover rounded">
                        </div>
                        <h3 class="text-sm mb-2">Portable Wshing Machine, 11lbs capacity Model 18NMF</h3>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-blue-600 font-bold">Rs80.00</span>
                            <span class="text-gray-400 line-through text-sm">Rs100.00</span>
                        </div>
                        <div class="flex items-center text-yellow-400 text-xs mb-3">
                            ★★★★★ <span class="text-gray-400 ml-1">(600)</span>
                        </div>
                        <button class="w-full bg-green-500 text-white py-2 rounded text-sm font-medium" style="background-color: #8D4887;">Add to Cart</button>
                    </div>

                    <!-- Product Card 3 -->
                    <div class="border rounded-lg p-4 hover:shadow-lg transition">
                        <div class="relative mb-4">
                            <span class="absolute top-2 left-2 bg-green-500 text-white text-xs px-2 py-1 rounded">11%</span>
                            <img src="https://images.unsplash.com/photo-1587202372775-e229f172b9d7?w=300&h=300&fit=crop" alt="Product" class="w-full h-40 object-cover rounded">
                        </div>
                        <h3 class="text-sm mb-2">Sony DSCHX8 High Zoom Point & Shoot Camera</h3>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-blue-600 font-bold">Rs60.00</span>
                            <span class="text-gray-400 line-through text-sm">Rs100.00</span>
                        </div>
                        <div class="flex items-center text-yellow-400 text-xs mb-3">
                            ★★★★★ <span class="text-gray-400 ml-1">(423)</span>
                        </div>
                        <button class="w-full bg-green-500 text-white py-2 rounded text-sm font-medium" style="background-color: #8D4887;">Add to Cart</button>
                    </div>

                    <!-- Product Card 4 -->
                    <div class="border rounded-lg p-4 hover:shadow-lg transition">
                        <div class="relative mb-4">
                            <span class="absolute top-2 left-2 bg-green-500 text-white text-xs px-2 py-1 rounded">32%</span>
                            <img src="https://images.unsplash.com/photo-1593305841991-05c297ba4575?w=300&h=300&fit=crop" alt="Product" class="w-full h-40 object-cover rounded">
                        </div>
                        <h3 class="text-sm mb-2">Dell Optiplex 7000x7480 All-in-One Computer Monitor</h3>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-blue-600 font-bold">Rs250.00</span>
                            <span class="text-gray-400 line-through text-sm">Rs350.00</span>
                        </div>
                        <div class="flex items-center text-yellow-400 text-xs mb-3">
                            ★★★★★ <span class="text-gray-400 ml-1">(738)</span>
                        </div>
                        <button class="w-full bg-green-500 text-white py-2 rounded text-sm font-medium" style="background-color: #8D4887;">Add to Cart</button>
                    </div>

                    <!-- Repeat more products -->
                    <div class="border rounded-lg p-4 hover:shadow-lg transition">
                        <div class="relative mb-4">
                            <span class="absolute top-2 left-2 bg-green-500 text-white text-xs px-2 py-1 rounded">18%</span>
                            <img src="https://images.unsplash.com/photo-1595044426077-d36d9236d54a?w=300&h=300&fit=crop" alt="Product" class="w-full h-40 object-cover rounded">
                        </div>
                        <h3 class="text-sm mb-2">4K UHD LED Smart TV with Chromecast Built-in</h3>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-blue-600 font-bold">Rs220.00</span>
                            <span class="text-gray-400 line-through text-sm">Rs250.00</span>
                        </div>
                        <div class="flex items-center text-yellow-400 text-xs mb-3">
                            ★★★★★ <span class="text-gray-400 ml-1">(652)</span>
                        </div>
                        <button class="w-full bg-green-500 text-white py-2 rounded text-sm font-medium" style="background-color: #8D4887;">Add to Cart</button>
                    </div>

                    <div class="border rounded-lg p-4 hover:shadow-lg transition">
                        <div class="relative mb-4">
                            <span class="absolute top-2 left-2 bg-green-500 text-white text-xs px-2 py-1 rounded">20%</span>
                            <img src="https://images.unsplash.com/photo-1625948515291-69613efd103f?w=300&h=300&fit=crop" alt="Product" class="w-full h-40 object-cover rounded">
                        </div>
                        <h3 class="text-sm mb-2">Polaroid 57-Inch Photo/Video Tripod with Deluxe Case</h3>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-blue-600 font-bold">Rs80.00</span>
                            <span class="text-gray-400 line-through text-sm">Rs100.00</span>
                        </div>
                        <div class="flex items-center text-yellow-400 text-xs mb-3">
                            ★★★★★ <span class="text-gray-400 ml-1">(490)</span>
                        </div>
                        <button class="w-full bg-green-500 text-white py-2 rounded text-sm font-medium" style="background-color: #8D4887;">Add to Cart</button>
                    </div>

                    <div class="border rounded-lg p-4 hover:shadow-lg transition">
                        <div class="relative mb-4">
                            <span class="absolute top-2 left-2 bg-green-500 text-white text-xs px-2 py-1 rounded">15%</span>
                            <img src="https://images.unsplash.com/photo-1587202372634-32705e3bf49c?w=300&h=300&fit=crop" alt="Product" class="w-full h-40 object-cover rounded">
                        </div>
                        <h3 class="text-sm mb-2">Gaming Keyboard RGB Backlit Mechanical Feel</h3>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-blue-600 font-bold">Rs45.00</span>
                            <span class="text-gray-400 line-through text-sm">Rs50.00</span>
                        </div>
                        <div class="flex items-center text-yellow-400 text-xs mb-3">
                            ★★★★★ <span class="text-gray-400 ml-1">(382)</span>
                        </div>
                        <button class="w-full bg-green-500 text-white py-2 rounded text-sm font-medium" style="background-color: #8D4887;">Add to Cart</button>
                    </div>

                    <div class="border rounded-lg p-4 hover:shadow-lg transition">
                        <div class="relative mb-4">
                            <span class="absolute top-2 left-2 bg-green-500 text-white text-xs px-2 py-1 rounded">22%</span>
                            <img src="https://images.unsplash.com/photo-1593305841991-05c297ba4575?w=300&h=300&fit=crop" alt="Product" class="w-full h-40 object-cover rounded">
                        </div>
                        <h3 class="text-sm mb-2">Dell Optiplex 7000x7480 All-in-One Computer Monitor</h3>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-blue-600 font-bold">Rs299.00</span>
                            <span class="text-gray-400 line-through text-sm">Rs380.00</span>
                        </div>
                        <div class="flex items-center text-yellow-400 text-xs mb-3">
                            ★★★★★ <span class="text-gray-400 ml-1">(820)</span>
                        </div>
                        <button class="w-full bg-green-500 text-white py-2 rounded text-sm font-medium" style="background-color: #8D4887;">Add to Cart</button>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="flex justify-center items-center gap-2 mt-8">
                    <button class="px-3 py-1 border rounded">←</button>
                    <button class="px-3 py-1 bg-green-500 text-white rounded" style="background-color: #8D4887;">1</button>
                    <button class="px-3 py-1 border rounded">2</button>
                    <button class="px-3 py-1 border rounded">3</button>
                    <button class="px-3 py-1 border rounded">4</button>
                    <button class="px-3 py-1 border rounded">→</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Header -->
    <?php include 'includes/footer.php'; ?>
</body>

</html>