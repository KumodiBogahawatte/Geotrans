<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Best Experience Always Wins</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom styles for specific elements not easily handled by utility classes */
        .dot-marker {
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            background-color: #8D4887;
            /* brand color */
        }
    </style>
</head>

<body class="font-sans bg-white text-gray-800">

    <!-- Include Header -->
    <?php include 'includes/header.php'; ?>

    <header class="bg-gray-100 pb-8 sm:pb-12 relative overflow-hidden" style="background-image: linear-gradient(to right, #FFFACD, #D8BFD8, #FFFDD0, #D8BFD8);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 pt-12 sm:pt-16 md:pt-20 lg:pt-24 xl:pt-32 pb-8 sm:pb-12 text-left">
            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-extrabold tracking-tight text-gray-900 leading-tight">
                Best experience <br class="hidden sm:block"> 
                <span class="sm:hidden">always wins</span>
                <span class="hidden sm:inline">always wins</span>
            </h1>
            <p class="mt-4 sm:mt-6 text-sm sm:text-base lg:text-lg text-gray-600 max-w-lg">
                # Online Marketplace for Electronics & Technology
                <br> in Colombo, SL
            </p>
        </div>

        <!-- Right side image slightly shifted -->
        <div class="absolute top-0 right-0 h-full w-full sm:w-4/5 md:w-3/4 lg:w-3/5 xl:w-1/2 z-0 overflow-hidden">
            <img src="assets/images/about/about-banner-removebg-preview.png" alt="Banner"
                class="w-full h-auto object-contain transform translate-x-8 translate-y-8 sm:-translate-x-20 sm:translate-y-20 md:-translate-x-32 md:translate-y-32 lg:-translate-x-52 lg:translate-y-52 scale-75 sm:scale-[1.2] md:scale-[1.4] lg:scale-[1.6] xl:scale-[2]">

        </div>
    </header>


    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        <div class="flex flex-col lg:flex-row justify-between items-center text-center lg:text-left space-y-8 lg:space-y-0">
            <div class="lg:mb-0">
                <h3 class="text-xs tracking-widest uppercase text-gray-500">OUR PURPOSE IS TO</h3>
                <p class="text-lg sm:text-xl font-semibold text-gray-900">
                    <span class="text-[#8D4887]"> ENRICH AND ENHANCE LIVES </span> 
                    <br>THROUGH TECHNOLOGY
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 sm:gap-8 text-gray-900 w-full lg:w-auto">
                <div class="sm:border-r sm:pr-6 lg:pr-8 border-gray-300">
                    <p class="text-3xl sm:text-4xl font-extrabold">$12,5M</p>
                    <p class="text-sm mt-1 text-gray-500">REVENUE <br> 2021 - 2025</p>
                </div>
                <div class="sm:border-r sm:pr-6 lg:pr-8 border-gray-300">
                    <p class="text-3xl sm:text-4xl font-extrabold">12K+</p>
                    <p class="text-sm mt-1 text-gray-500">HAPPY <br> CUSTOMERS</p>
                </div>
                <div>
                    <p class="text-3xl sm:text-4xl font-extrabold">725+</p>
                    <p class="text-sm mt-1 text-gray-500">CERTIFIED <br> PARTNERS</p>
                </div>
            </div>
        </div>
    </section>

    <hr class="max-w-7xl mx-auto">

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        <div class="flex flex-col lg:flex-row bg-gray-50 rounded-lg overflow-hidden">
            <div class="lg:w-1/2 order-2 lg:order-1">
                <img src="assets/images/about/close-up-man-shopping-with-laptop 1.png" alt="Man working on a laptop" class="w-full h-64 sm:h-80 lg:h-full object-cover">
            </div>

            <div class="lg:w-1/2 p-6 sm:p-8 lg:p-12 flex items-center bg-gray-100 order-1 lg:order-2">
                <div>
                    <h2 class="text-base sm:text-lg font-bold mb-4 leading-relaxed">Deotrans Pvt Ltd, was founded in 2008 and specializes in providing IT solutions and office automation products.</h2>
                    <p class="text-gray-600 mb-6 text-sm leading-relaxed">
                        Our primary objective is to provide the latest Office automation products to our clients. We have an extensive collection and affordable supply of all types of Office automation products... (Rest of the introductory text)
                    </p>
                    <a href="#" class="inline-block px-6 py-3 bg-[#8D4887] text-white font-semibold text-sm rounded-full hover:bg-[#7a3a6f] transition duration-300">
                        FIND OUT MORE
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">

            <div class="text-center md:text-left p-4">
                <div class="flex justify-center md:justify-start items-center mb-4">
                    <div class="dot-marker bg-[#8D4887] flex items-center justify-center flex-shrink-0">
                        <!-- Shield/Check icon (white) -->
                        <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M16.704 5.29a1 1 0 00-1.414-1.414L8 11.177 4.71 7.887a1 1 0 00-1.414 1.414l4 4a1 1 0 001.414 0l8-8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span class="ml-3 font-bold text-gray-900 text-sm sm:text-base">100% AUTHENTIC PRODUCTS</span>
                </div>
                <p class="text-sm text-gray-600 leading-relaxed">
                    Deotro Tech part distributes 100% authorized products & guarantee quality. Beta nulla nulla nec leo et sapien, id euismod...
                </p>
            </div>

            <div class="text-center md:text-left p-4">
                <div class="flex justify-center md:justify-start items-center mb-4">
                    <div class="dot-marker bg-[#8D4887] flex items-center justify-center flex-shrink-0">
                        <!-- Lightning icon (white) -->
                        <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M11.3 1L1 11h6v8l10-10h-6L11.3 1z" />
                        </svg>
                    </div>
                    <span class="ml-3 font-bold text-gray-900 text-sm sm:text-base">FAST DELIVERY</span>
                </div>
                <p class="text-sm text-gray-600 leading-relaxed">
                    Fast shipping with a safe of option to delivery. 100% guarantee that your goods always on time and personas...
                </p>
            </div>

            <div class="text-center md:text-left p-4">
                <div class="flex justify-center md:justify-start items-center mb-4">
                    <div class="dot-marker bg-[#8D4887] flex items-center justify-center flex-shrink-0">
                        <!-- Tag icon (white) -->
                        <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M7 3a2 2 0 00-2 2v4.586a2 2 0 00.586 1.414l6 6A2 2 0 0014.586 18H18a2 2 0 002-2v-3.414a2 2 0 00-.586-1.414l-6-6A2 2 0 007 3H7zm3 5a1 1 0 110-2 1 1 0 010 2z" />
                        </svg>
                    </div>
                    <span class="ml-3 font-bold text-gray-900 text-sm sm:text-base">AFFORDABLE PRICE</span>
                </div>
                <p class="text-sm text-gray-600 leading-relaxed">
                    We offer an affordable & competitive price with a lots of special promotions.
                </p>
            </div>
        </div>
    </section>

    <hr class="max-w-7xl mx-auto">

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        <h2 class="text-lg sm:text-xl font-bold mb-4">OUR MISSION AND VISION</h2>
        <p class="text-sm text-gray-600 leading-relaxed mb-6 sm:mb-8">
            <!-- <span class="italic">PHP Placeholder: Fetch/Display Mission & Vision text</span> -->
            <br>Lorem ipsum dolor sit amet, non euismod mauris tempus. Cras nec elit vel magna molestie pellentesque in eu dui. Donec lorem quam erat vitae finibus. Vestibulum vitae enim, eget eget quam ut, euismod dictum elit. Nullam eu tempus magna. Maecenas mollis mi eu felis placerat placerat nec eget sapien. Vivamus mollis mauris vitae rhoncus egestas. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.
        </p>

        <div class="w-full h-64 sm:h-80 lg:h-96 overflow-hidden rounded-lg shadow-xl">
            <img src="assets/images/about/about3.png.png" alt="Modern cityscape with abstract building" class="w-full h-full object-cover">
        </div>
    </section>
    <hr class="max-w-7xl mx-auto">
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        <h2 class="text-lg sm:text-xl font-bold mb-6 sm:mb-8">FROM A RETAIL STORE TO THE GLOBAL CHAIN OF STORES</h2>
        <p class="text-sm text-gray-600 leading-relaxed mb-6 sm:mb-8">
            Pellentesque eget justo nec ex sodales euismod. Aliquat et tortor. Maecenas nec ultricies ex, at auctor purus. Maecenas in consectetur erat.
        </p>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8 lg:gap-12 text-sm">
            <div class="space-y-4">
                <p class="mb-4">
                    <span class="font-bold text-gray-900">1997:</span> Small store located in Brooklyn Town, USA.
                </p>
                <p class="mb-4">
                    <span class="font-bold text-gray-900">1998:</span> All the lorem ipsum generators on the Internet tend to repeat predefined chunks as necessary.
                </p>
                <p class="mb-4">
                    <span class="font-bold text-gray-900">2000:</span> Contrary to popular belief, Lorem Ipsum is not simply random text.
                </p>
                <p class="mb-4">
                    <span class="font-bold text-gray-900">2004:</span> There are many variations of passages of lorem ipsum, but the majority have suffered alteration.
                </p>
                <p class="mb-4">
                    <span class="font-bold text-gray-900">2006:</span> The standard chunk of Lorem Ipsum used since the 1500s.
                </p>
                <p class="mb-4">
                    <span class="font-bold text-gray-900">2010:</span> Sed ultrices lacus id nunc porta gravida. Pellentesque dolor.
                </p>
            </div>

            <div class="space-y-4">
                <p class="mb-4">
                    <span class="font-bold text-gray-900">2016:</span> There are many variations of passages of lorem ipsum available, but the majority have suffered alteration in some form. <span class="text-gray-500">(PHP Placeholder: Timeline Data 2)</span>
                </p>
                <p class="mb-4">
                    <span class="font-bold text-gray-900">2018:</span> All the lorem ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet.
                </p>
                <p class="mb-4">
                    <span class="font-bold text-gray-900">2019:</span> Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum".
                </p>
                <p class="mb-4">
                    <span class="font-bold text-gray-900">2020:</span> Lorem Ipsum is therefore always free from repetition, injected humour.
                </p>
                <p class="mb-4">
                    <span class="font-bold text-gray-900">2021:</span> There are many variations of passages of lorem ipsum available, but the majority have suffered alteration.
                </p>
                <p class="mb-4">
                    <span class="font-bold text-gray-900">2022:</span> The standard chunk of Lorem Ipsum used since the 1500s.
                </p>
            </div>
        </div>

    </section>

    <hr class="max-w-7xl mx-auto">
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        <div class="flex flex-col sm:flex-row items-center justify-between mb-6 sm:mb-8">
            <h2 class="text-lg sm:text-xl font-bold mb-4 sm:mb-0">LEADERSHIPS</h2>
            <a href="#" class="text-[#8D4887] font-semibold text-sm hover:underline">View All &rarr;</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 sm:gap-6">
            <div class="text-center">
                <div class="h-60 sm:h-64 lg:h-72 w-full overflow-hidden rounded-md">
                    <img src="assets/images/about/L1.png" alt="Henry Avery" class="w-full h-full object-cover">
                </div>
                <p class="mt-3 font-semibold text-gray-900 text-sm">Henry Avery</p>
                <p class="text-xs text-gray-500">Director</p>
            </div>

            <div class="text-center">
                <div class="h-60 sm:h-64 lg:h-72 w-full overflow-hidden rounded-md">
                    <img src="assets/images/about/L2.png" alt="Michael Edward" class="w-full h-full object-cover">
                </div>
                <p class="mt-3 font-semibold text-gray-900 text-sm">Michael Edward</p>
                <p class="text-xs text-gray-500">Executive</p>
            </div>

            <div class="text-center">
                <div class="h-60 sm:h-64 lg:h-72 w-full overflow-hidden rounded-md">
                    <img src="assets/images/about/L3.png" alt="Eden Hazard" class="w-full h-full object-cover">
                </div>
                <p class="mt-3 font-semibold text-gray-900 text-sm">Eden Hazard</p>
                <p class="text-xs text-gray-500">Chairman</p>
            </div>

            <div class="text-center">
                <div class="h-60 sm:h-64 lg:h-72 w-full overflow-hidden rounded-md">
                    <img src="assets/images/about/L4.png" alt="Robert Downey Jr" class="w-full h-full object-cover">
                </div>
                <p class="mt-3 font-semibold text-gray-900 text-sm">Robert Downey Jr</p>
                <p class="text-xs text-gray-500">Executive</p>
            </div>

            <div class="text-center">
                <div class="h-60 sm:h-64 lg:h-72 w-full overflow-hidden rounded-md">
                    <img src="assets/images/about/L5.png" alt="Nathan Drake" class="w-full h-full object-cover">
                </div>
                <p class="mt-3 font-semibold text-gray-900 text-sm">Nathan Drake</p>
                <p class="text-xs text-gray-500">Chairman</p>
            </div>

        </div>
    </section>

    <!-- Include footer -->
    <?php include 'includes/footer.php'; ?>

</body>

</html>