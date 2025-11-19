<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gaming Laptops - Game Beyond Limits</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50">

    <!-- Include Header -->
    <?php include 'includes/header.php'; ?>
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-r from-purple-900 via-pink-800 to-blue-900 text-white overflow-hidden" style="background-image: url('assets/images/pages/asuz-banner.jpg'); background-size: cover; background-position: center;">
        <div class="absolute inset-0 bg-black opacity-40"></div>
        <div class="relative max-w-7xl mx-auto px-4 py-10 sm:py-16 text-center">
            <h1 class="text-3xl md:text-5xl font-bold mb-4">GAME BEYOND LIMITS.</h1>
            <p class="text-base md:text-xl mb-8">Unleash legendary strength with cutting-edge gaming technology</p>
        </div>
    </div>

    <div class="flex flex-wrap justify-center items-center gap-6 mt-8 px-4">
        <img src="assets/images/home/hp-300x300-1 1.png" alt="HP" class="h-20 md:h-32 p-2 rounded">
        <img src="assets/images/home/hp-300x300-1 2.png" alt="ASUS" class="h-20 md:h-32 p-2 rounded">
        <img src="assets/images/home/hp-300x300-1 3.png" alt="Lenovo" class="h-20 md:h-32 p-2 rounded">
        <img src="assets/images/home/hp-300x300-1 4.png" alt="MSI" class="h-20 md:h-32 p-2 rounded">
        <img src="assets/images/home/hp-300x300-1 5.png" alt="Dell" class="h-20 md:h-32 p-2 rounded">
        <img src="assets/images/home/hp-300x300-1 6.png" alt="Acer" class="h-20 md:h-32 p-2 rounded">
    </div>

    <hr class="max-w-7xl mx-auto">

    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row gap-8">
            <!-- (mobile Filters button moved into the products header row to avoid duplicate controls) -->

            <!-- Sidebar Filters (hidden on small screens; duplicated in mobile drawer) -->
            <aside id="sidebarFilters" class="hidden md:block w-64 bg-white rounded-lg shadow-sm p-6 h-fit sticky top-4">
                <h3 class="font-bold text-lg mb-4">All Laptops</h3>

                <div class="mb-6">
                    <h4 class="font-semibold mb-3">CATEGORIES</h4>
                    <div class="space-y-2 text-sm">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" class="mr-2"> Gaming Laptops
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" class="mr-2"> Business Laptops
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" class="mr-2"> Ultrabooks
                        </label>
                    </div>
                </div>

                <div class="mb-6">
                    <h4 class="font-semibold mb-3">BRAND</h4>
                    <div class="space-y-2 text-sm">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" class="mr-2"> HP
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" class="mr-2"> ASUS
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" class="mr-2"> Lenovo
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" class="mr-2"> MSI
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" class="mr-2"> Dell
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" class="mr-2"> Acer
                        </label>
                    </div>
                </div>

                <div class="mb-6">
                    <h4 class="font-semibold mb-3">PRICE RANGE</h4>
                    <div class="space-y-2 text-sm">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" class="mr-2"> Under Rs1000
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" class="mr-2"> Rs1000 - Rs1500
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" class="mr-2"> Rs1500 - Rs2000
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" class="mr-2"> Over Rs2000
                        </label>
                    </div>
                </div>

                <div class="mb-6">
                    <h4 class="font-semibold mb-3">PROCESSOR</h4>
                    <div class="space-y-2 text-sm">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" class="mr-2"> Intel Core i5
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" class="mr-2"> Intel Core i7
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" class="mr-2"> Intel Core i9
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" class="mr-2"> AMD Ryzen 7
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" class="mr-2"> AMD Ryzen 9
                        </label>
                    </div>
                </div>

                <div class="mb-6">
                    <h4 class="font-semibold mb-3">GRAPHICS</h4>
                    <div class="space-y-2 text-sm">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" class="mr-2"> RTX 4050
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" class="mr-2"> RTX 4060
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" class="mr-2"> RTX 4070
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" class="mr-2"> RTX 4080
                        </label>
                    </div>
                </div>

                <div>
                    <h4 class="font-semibold mb-3">SCREEN SIZE</h4>
                    <div class="flex gap-2 flex-wrap">
                        <button class="px-3 py-1 border rounded hover:bg-gray-100 text-sm">14"</button>
                        <button class="px-3 py-1 border rounded hover:bg-gray-100 text-sm">15.6"</button>
                        <button class="px-3 py-1 border rounded hover:bg-gray-100 text-sm">17"</button>
                    </div>
                </div>
            </aside>

            <!-- Mobile Filters Drawer (hidden by default) -->
            <div id="mobile-filters" class="fixed inset-0 z-40 flex md:hidden hidden" aria-hidden="true">
                <div id="mobile-filters-overlay" class="absolute inset-0 bg-black opacity-50"></div>
                <aside class="relative z-50 w-11/12 max-w-sm bg-white h-full p-6 overflow-auto">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-lg">Filters</h3>
                        <button id="closeFiltersBtn" class="text-gray-600">Close</button>
                    </div>

                    <!-- BEGIN: duplicated filter content for mobile drawer -->
                    <div class="mb-6">
                        <h4 class="font-semibold mb-3">CATEGORIES</h4>
                        <div class="space-y-2 text-sm">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" class="mr-2"> Gaming Laptops
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" class="mr-2"> Business Laptops
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" class="mr-2"> Ultrabooks
                            </label>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h4 class="font-semibold mb-3">BRAND</h4>
                        <div class="space-y-2 text-sm">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" class="mr-2"> HP
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" class="mr-2"> ASUS
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" class="mr-2"> Lenovo
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" class="mr-2"> MSI
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" class="mr-2"> Dell
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" class="mr-2"> Acer
                            </label>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h4 class="font-semibold mb-3">PRICE RANGE</h4>
                        <div class="space-y-2 text-sm">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" class="mr-2"> Under Rs1000
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" class="mr-2"> Rs1000 - Rs1500
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" class="mr-2"> Rs1500 - Rs2000
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" class="mr-2"> Over Rs2000
                            </label>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h4 class="font-semibold mb-3">PROCESSOR</h4>
                        <div class="space-y-2 text-sm">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" class="mr-2"> Intel Core i5
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" class="mr-2"> Intel Core i7
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" class="mr-2"> Intel Core i9
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" class="mr-2"> AMD Ryzen 7
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" class="mr-2"> AMD Ryzen 9
                            </label>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h4 class="font-semibold mb-3">GRAPHICS</h4>
                        <div class="space-y-2 text-sm">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" class="mr-2"> RTX 4050
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" class="mr-2"> RTX 4060
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" class="mr-2"> RTX 4070
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" class="mr-2"> RTX 4080
                            </label>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-semibold mb-3">SCREEN SIZE</h4>
                        <div class="flex gap-2 flex-wrap">
                            <button class="px-3 py-1 border rounded hover:bg-gray-100 text-sm">14"</button>
                            <button class="px-3 py-1 border rounded hover:bg-gray-100 text-sm">15.6"</button>
                            <button class="px-3 py-1 border rounded hover:bg-gray-100 text-sm">17"</button>
                        </div>
                    </div>
                    <!-- END: duplicated filter content for mobile drawer -->
                </aside>
            </div>

            <!-- Product Grid -->
            <main class="flex-1 w-full">
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center gap-3">
                        <h2 class="text-2xl font-bold">Gaming Laptops</h2>
                        <!-- Mobile-only Filters button (opens the same mobile drawer) -->
                        <button id="openFiltersBtn" aria-controls="mobile-filters" aria-expanded="false" class="md:hidden px-3 py-1 bg-[#8D4887] text-white rounded">Filters</button>
                    </div>
                    <select class="border rounded px-4 py-2">
                        <option>Sort by: Featured</option>
                        <option>Price: Low to High</option>
                        <option>Price: High to Low</option>
                        <option>Newest First</option>
                    </select>
                </div>

                <!-- Products Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Product Card 1 -->
                    <div class="bg-white rounded-lg shadow-sm hover:shadow-lg transition p-4">
                        <div class="relative">
                            <span class="absolute top-2 left-2 bg-green-500 text-white text-xs px-2 py-1 rounded">IN STOCK</span>
                            <img src="https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=400" alt="Gaming Laptop" class="w-full h-40 sm:h-48 object-cover rounded mb-4">
                        </div>
                        <h3 class="font-semibold text-sm mb-2">HP Omen 16 Gaming Laptop, Intel Core i7</h3>
                        <div class="flex items-baseline gap-2 mb-3">
                            <span class="text-2xl font-bold text-green-600">Rs1,299</span>
                            <span class="text-sm text-gray-400 line-through">Rs1,499</span>
                        </div>
                        <button class="w-full bg-[#8D4887] text-white py-2 rounded hover:bg-[#71386a] transition" aria-label="Add to Cart">Add to Cart</button>
                    </div>

                    <!-- Product Card 2 -->
                    <div class="bg-white rounded-lg shadow-sm hover:shadow-lg transition p-4">
                        <div class="relative">
                            <span class="absolute top-2 left-2 bg-green-500 text-white text-xs px-2 py-1 rounded">IN STOCK</span>
                            <img src="https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=400" alt="Gaming Laptop" class="w-full h-40 sm:h-48 object-cover rounded mb-4">
                        </div>
                        <h3 class="font-semibold text-sm mb-2">ASUS ROG Strix G15, AMD Ryzen 9</h3>
                        <div class="flex items-baseline gap-2 mb-3">
                            <span class="text-2xl font-bold text-green-600">Rs1,599</span>
                            <span class="text-sm text-gray-400 line-through">Rs1,799</span>
                        </div>
                        <button class="w-full bg-[#8D4887] text-white py-2 rounded hover:bg-[#71386a] transition" aria-label="Add to Cart">Add to Cart</button>
                    </div>

                    <!-- Product Card 3 -->
                    <div class="bg-white rounded-lg shadow-sm hover:shadow-lg transition p-4">
                        <div class="relative">
                            <span class="absolute top-2 left-2 bg-green-500 text-white text-xs px-2 py-1 rounded">IN STOCK</span>
                            <img src="https://images.unsplash.com/photo-1593642632823-8f785ba67e45?w=400" alt="Gaming Laptop" class="w-full h-40 sm:h-48 object-cover rounded mb-4">
                        </div>
                        <h3 class="font-semibold text-sm mb-2">Lenovo Legion 5 Pro, Intel Core i7</h3>
                        <div class="flex items-baseline gap-2 mb-3">
                            <span class="text-2xl font-bold text-green-600">Rs1,449</span>
                            <span class="text-sm text-gray-400 line-through">Rs1,649</span>
                        </div>
                        <button class="w-full bg-[#8D4887] text-white py-2 rounded hover:bg-[#71386a] transition" aria-label="Add to Cart">Add to Cart</button>
                    </div>

                    <!-- Product Card 4 -->
                    <div class="bg-white rounded-lg shadow-sm hover:shadow-lg transition p-4">
                        <div class="relative">
                            <span class="absolute top-2 left-2 bg-green-500 text-white text-xs px-2 py-1 rounded">IN STOCK</span>
                            <img src="https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=400" alt="Gaming Laptop" class="w-full h-40 sm:h-48 object-cover rounded mb-4">
                        </div>
                        <h3 class="font-semibold text-sm mb-2">MSI Stealth 15M, Intel Core i9</h3>
                        <div class="flex items-baseline gap-2 mb-3">
                            <span class="text-2xl font-bold text-green-600">Rs1,899</span>
                            <span class="text-sm text-gray-400 line-through">Rs2,099</span>
                        </div>
                        <button class="w-full bg-[#8D4887] text-white py-2 rounded hover:bg-[#71386a] transition" aria-label="Add to Cart">Add to Cart</button>
                    </div>

                    <!-- Product Card 5 -->
                    <div class="bg-white rounded-lg shadow-sm hover:shadow-lg transition p-4">
                        <div class="relative">
                            <span class="absolute top-2 left-2 bg-green-500 text-white text-xs px-2 py-1 rounded">IN STOCK</span>
                            <img src="https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=400" alt="Gaming Laptop" class="w-full h-40 sm:h-48 object-cover rounded mb-4">
                        </div>
                        <h3 class="font-semibold text-sm mb-2">Dell G15 Gaming Laptop, AMD Ryzen 7</h3>
                        <div class="flex items-baseline gap-2 mb-3">
                            <span class="text-2xl font-bold text-green-600">Rs1,199</span>
                            <span class="text-sm text-gray-400 line-through">Rs1,399</span>
                        </div>
                        <button class="w-full bg-[#8D4887] text-white py-2 rounded hover:bg-[#71386a] transition" aria-label="Add to Cart">Add to Cart</button>
                    </div>

                    <!-- Product Card 6 -->
                    <div class="bg-white rounded-lg shadow-sm hover:shadow-lg transition p-4">
                        <div class="relative">
                            <span class="absolute top-2 left-2 bg-green-500 text-white text-xs px-2 py-1 rounded">IN STOCK</span>
                            <img src="https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=400" alt="Gaming Laptop" class="w-full h-40 sm:h-48 object-cover rounded mb-4">
                        </div>
                        <h3 class="font-semibold text-sm mb-2">Acer Predator Helios 300, Intel Core i7</h3>
                        <div class="flex items-baseline gap-2 mb-3">
                            <span class="text-2xl font-bold text-green-600">Rs1,349</span>
                            <span class="text-sm text-gray-400 line-through">Rs1,549</span>
                        </div>
                        <button class="w-full bg-[#8D4887] text-white py-2 rounded hover:bg-[#71386a] transition" aria-label="Add to Cart">Add to Cart</button>
                    </div>

                    <!-- Product Card 7 -->
                    <div class="bg-white rounded-lg shadow-sm hover:shadow-lg transition p-4">
                        <div class="relative">
                            <span class="absolute top-2 left-2 bg-green-500 text-white text-xs px-2 py-1 rounded">IN STOCK</span>
                            <img src="https://images.unsplash.com/photo-1602080858428-57174f9431cf?w=400" alt="Gaming Laptop" class="w-full h-40 sm:h-48 object-cover rounded mb-4">
                        </div>
                        <h3 class="font-semibold text-sm mb-2">ASUS TUF Gaming A15, AMD Ryzen 9</h3>
                        <div class="flex items-baseline gap-2 mb-3">
                            <span class="text-2xl font-bold text-green-600">Rs1,499</span>
                            <span class="text-sm text-gray-400 line-through">Rs1,699</span>
                        </div>
                        <button class="w-full bg-[#8D4887] text-white py-2 rounded hover:bg-[#71386a] transition" aria-label="Add to Cart">Add to Cart</button>
                    </div>

                    <!-- Product Card 8 -->
                    <div class="bg-white rounded-lg shadow-sm hover:shadow-lg transition p-4">
                        <div class="relative">
                            <span class="absolute top-2 left-2 bg-green-500 text-white text-xs px-2 py-1 rounded">IN STOCK</span>
                            <img src="https://images.unsplash.com/photo-1593642634443-44adaa06623a?w=400" alt="Gaming Laptop" class="w-full h-40 sm:h-48 object-cover rounded mb-4">
                        </div>
                        <h3 class="font-semibold text-sm mb-2">HP Victus 16, Intel Core i5</h3>
                        <div class="flex items-baseline gap-2 mb-3">
                            <span class="text-2xl font-bold text-green-600">Rs999</span>
                            <span class="text-sm text-gray-400 line-through">Rs1,199</span>
                        </div>
                        <button class="w-full bg-[#8D4887] text-white py-2 rounded hover:bg-[#71386a] transition" aria-label="Add to Cart">Add to Cart</button>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="flex justify-center items-center gap-2 mt-8">
                    <button class="px-4 py-2 border rounded hover:bg-gray-100">&lt;</button>
                    <button class="px-4 py-2 bg-[#8D4887] text-white rounded">1</button>
                    <button class="px-4 py-2 border rounded hover:bg-gray-100">2</button>
                    <button class="px-4 py-2 border rounded hover:bg-gray-100">3</button>
                    <button class="px-4 py-2 border rounded hover:bg-gray-100">4</button>
                    <button class="px-4 py-2 border rounded hover:bg-gray-100">&gt;</button>
                </div>
            </main>
        </div>
    </div>

    <!-- Mobile filters drawer script -->
    <script>
        (function(){
            const openBtn = document.getElementById('openFiltersBtn');
            const closeBtn = document.getElementById('closeFiltersBtn');
            const mobileFilters = document.getElementById('mobile-filters');
            const overlay = document.getElementById('mobile-filters-overlay');

            function openFilters(){
                if(!mobileFilters) return;
                mobileFilteRsclassList.remove('hidden');
                mobileFilteRssetAttribute('aria-hidden','false');
                openBtn && openBtn.setAttribute('aria-expanded','true');
                document.body.classList.add('overflow-hidden');
            }

            function closeFilters(){
                if(!mobileFilters) return;
                mobileFilteRsclassList.add('hidden');
                mobileFilteRssetAttribute('aria-hidden','true');
                openBtn && openBtn.setAttribute('aria-expanded','false');
                document.body.classList.remove('overflow-hidden');
            }

            openBtn && openBtn.addEventListener('click', openFilters);
            closeBtn && closeBtn.addEventListener('click', closeFilters);
            overlay && overlay.addEventListener('click', closeFilters);

            // close on Escape key
            document.addEventListener('keydown', function(e){
                if(e.key === 'Escape') closeFilters();
            });
        })();
    </script>

    <!-- Include Footer -->
    <?php include 'includes/footer.php'; ?>
</body>

</html>