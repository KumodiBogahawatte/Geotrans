<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | GeoTrans</title>
    <meta name="description" content="Learn about GeoTrans — your trusted electronics and technology marketplace in Colombo, Sri Lanka.">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .text-purple-custom { color: #680e68; }
        .bg-purple-custom { background-color: #680e68; }
        .border-purple-custom { border-color: #680e68; }
        .ring-purple-custom { --tw-ring-color: #680e68; }

        .about-hero-gradient {
            background: linear-gradient(135deg, #faf5fc 0%, #f3e8f6 45%, #fefce8 100%);
        }

    </style>
</head>

<body class="font-sans bg-gray-50 text-gray-800 antialiased">

    <?php include 'includes/header.php'; ?>

    <!-- Hero -->
    <header class="about-hero-gradient relative overflow-hidden border-b border-gray-100">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-14">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center min-h-[320px] sm:min-h-[380px] lg:min-h-[440px] py-8 sm:py-12 lg:py-16">
                <div class="relative z-10 text-center lg:text-left order-2 lg:order-1">
                    <p class="text-xs sm:text-sm font-semibold tracking-[0.2em] uppercase text-purple-custom mb-3">
                        Colombo, Sri Lanka
                    </p>
                    <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-[3.25rem] font-extrabold tracking-tight text-gray-900 leading-[1.1]">
                        The best experience
                        <span class="text-purple-custom block sm:inline sm:ml-2">always wins</span>
                    </h1>
                    <p class="mt-5 sm:mt-6 text-base sm:text-lg text-gray-600 max-w-xl mx-auto lg:mx-0 leading-relaxed">
                        We are an online marketplace for electronics and technology—curated products, trusted brands, and support that puts customers first.
                    </p>
                    <div class="mt-8 flex flex-col sm:flex-row flex-wrap gap-3 justify-center lg:justify-start">
                        <a href="products.php" class="inline-flex items-center justify-center gap-2 rounded-full bg-purple-custom px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#4f0a4f] focus:outline-none focus:ring-2 focus:ring-purple-custom focus:ring-offset-2">
                            <span>Shop products</span>
                            <i class="fas fa-arrow-right text-xs opacity-90"></i>
                        </a>
                        <a href="contact.php" class="inline-flex items-center justify-center gap-2 rounded-full border-2 border-gray-300 bg-white px-6 py-3 text-sm font-semibold text-gray-800 transition hover:border-purple-custom hover:text-purple-custom focus:outline-none focus:ring-2 focus:ring-purple-custom focus:ring-offset-2">
                            Contact us
                        </a>
                    </div>
                </div>
                <div class="relative flex justify-center lg:justify-end order-1 lg:order-2">
                    <div class="relative w-full max-w-md lg:max-w-lg">
                        <div class="absolute -inset-4 rounded-3xl bg-gradient-to-tr from-purple-custom/10 to-amber-100/40 blur-2xl lg:block" aria-hidden="true"></div>
                        <img src="assets/images/about/about-banner-removebg-preview.png" alt="Illustration representing GeoTrans technology marketplace"
                            class="relative w-full h-auto max-h-[280px] sm:max-h-[340px] lg:max-h-[400px] object-contain object-center drop-shadow-xl mx-auto"
                            width="600" height="400" loading="eager">
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main>
        <!-- Breadcrumb -->
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 pt-6 sm:pt-8">
            <nav class="text-sm text-gray-500" aria-label="Breadcrumb">
                <a href="index.php" class="hover:text-purple-custom transition-colors">Home</a>
                <span class="mx-2 text-gray-300">/</span>
                <span class="text-gray-900 font-medium">About</span>
            </nav>
        </div>

        <!-- Stats -->
        <section class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12" aria-labelledby="about-stats-heading">
            <h2 id="about-stats-heading" class="sr-only">Company highlights</h2>
            <div class="rounded-2xl bg-white p-6 sm:p-8 lg:p-10 shadow-sm ring-1 ring-gray-100">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-10 lg:gap-12">
                    <div class="text-center lg:text-left max-w-md mx-auto lg:mx-0">
                        <p class="text-xs font-semibold tracking-widest uppercase text-gray-500">Our purpose</p>
                        <p class="mt-3 text-xl sm:text-2xl font-bold text-gray-900 leading-snug">
                            <span class="text-purple-custom">Enrich and enhance lives</span>
                            through technology
                        </p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 sm:gap-0 sm:divide-x divide-gray-100 w-full lg:max-w-3xl">
                        <div class="text-center sm:px-6 lg:px-8 py-2">
                            <p class="text-3xl sm:text-4xl font-extrabold text-gray-900 tabular-nums">Rs. 12.5M+</p>
                            <p class="mt-2 text-xs sm:text-sm font-medium uppercase tracking-wide text-gray-500">Revenue<br class="hidden sm:inline"> <span class="sm:hidden">·</span> 2021–2025</p>
                        </div>
                        <div class="text-center sm:px-6 lg:px-8 py-2 border-t sm:border-t-0 border-gray-100 pt-6 sm:pt-2">
                            <p class="text-3xl sm:text-4xl font-extrabold text-gray-900 tabular-nums">12K+</p>
                            <p class="mt-2 text-xs sm:text-sm font-medium uppercase tracking-wide text-gray-500">Happy<br class="hidden sm:inline"> customers</p>
                        </div>
                        <div class="text-center sm:px-6 lg:px-8 py-2 border-t sm:border-t-0 border-gray-100 pt-6 sm:pt-2">
                            <p class="text-3xl sm:text-4xl font-extrabold text-gray-900 tabular-nums">725+</p>
                            <p class="mt-2 text-xs sm:text-sm font-medium uppercase tracking-wide text-gray-500">Certified<br class="hidden sm:inline"> partners</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Story -->
        <section class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
            <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100">
                <div class="grid grid-cols-1 lg:grid-cols-2">
                    <div class="relative h-64 sm:h-80 lg:h-auto lg:min-h-[420px]">
                        <img src="assets/images/about/close-up-man-shopping-with-laptop 1.png" alt="Customer shopping for technology online"
                            class="absolute inset-0 h-full w-full object-cover"
                            loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/40 to-transparent lg:hidden" aria-hidden="true"></div>
                    </div>
                    <div class="flex flex-col justify-center p-6 sm:p-10 lg:p-12 xl:p-14">
                        <p class="text-xs font-semibold tracking-widest uppercase text-purple-custom">Who we are</p>
                        <h2 class="mt-3 text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">
                            Deotrans Pvt Ltd
                        </h2>
                        <p class="mt-4 text-gray-600 leading-relaxed text-sm sm:text-base">
                            Founded in 2008, we specialize in IT solutions and office automation. Our focus is simple: bring the latest hardware and accessories to businesses and consumers with clear pricing, genuine products, and dependable fulfilment.
                        </p>
                        <p class="mt-4 text-gray-600 leading-relaxed text-sm sm:text-base">
                            Today, GeoTrans extends that mission online—so you can discover, compare, and order technology from anywhere in Sri Lanka with confidence.
                        </p>
                        <div class="mt-8">
                            <a href="products.php" class="inline-flex items-center gap-2 rounded-full bg-purple-custom px-6 py-3 text-sm font-semibold text-white transition hover:bg-[#4f0a4f] focus:outline-none focus:ring-2 focus:ring-purple-custom focus:ring-offset-2">
                                Browse catalogue
                                <i class="fas fa-chevron-right text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Shop CTA — after story, light card (no full-width purple bar) -->
        <section class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 pb-8 sm:pb-12" aria-labelledby="about-cta-heading">
            <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white p-8 sm:p-10 lg:p-12 shadow-sm ring-1 ring-gray-100">
                <div class="pointer-events-none absolute -right-8 -top-8 h-40 w-40 rounded-full bg-purple-custom/10 blur-3xl" aria-hidden="true"></div>
                <div class="pointer-events-none absolute -bottom-12 -left-8 h-36 w-36 rounded-full bg-amber-100/40 blur-3xl" aria-hidden="true"></div>
                <div class="relative mx-auto max-w-2xl text-center">
                    <h2 id="about-cta-heading" class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900">
                        Ready to upgrade your setup?
                    </h2>
                    <p class="mt-4 text-sm sm:text-base leading-relaxed text-gray-600">
                        Explore the latest devices, accessories, and deals—all in one place.
                    </p>
                    <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row sm:flex-wrap">
                        <a href="products.php" class="inline-flex items-center justify-center gap-2 rounded-full bg-purple-custom px-8 py-3.5 text-sm font-bold text-white shadow-sm transition hover:bg-[#4f0a4f] focus:outline-none focus:ring-2 focus:ring-purple-custom focus:ring-offset-2">
                            View all products
                            <i class="fas fa-arrow-right text-xs opacity-90"></i>
                        </a>
                        <a href="index.php" class="inline-flex items-center justify-center gap-2 rounded-full border-2 border-gray-300 bg-gray-50 px-8 py-3.5 text-sm font-semibold text-gray-800 transition hover:border-purple-custom hover:bg-white hover:text-purple-custom focus:outline-none focus:ring-2 focus:ring-purple-custom focus:ring-offset-2">
                            Return home
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pillars -->
        <section class="bg-white border-y border-gray-100 py-8 sm:py-12 lg:py-16">
            <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center mb-10 sm:mb-14">
                    <p class="text-xs font-semibold tracking-widest uppercase text-gray-500">Why shop with us</p>
                    <h2 class="mt-3 text-2xl sm:text-3xl font-bold text-gray-900">Built on trust and speed</h2>
                    <p class="mt-4 text-sm sm:text-base text-gray-600 leading-relaxed">
                        Three principles guide every order: authenticity, delivery you can count on, and fair pricing.
                    </p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
                    <article class="group rounded-2xl border border-gray-100 bg-gray-50/80 p-6 sm:p-8 transition hover:border-purple-custom/30 hover:shadow-md hover:bg-white">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-custom text-white shadow-sm">
                            <i class="fas fa-shield-halved text-lg" aria-hidden="true"></i>
                        </div>
                        <h3 class="mt-5 text-lg font-bold text-gray-900">100% authentic products</h3>
                        <p class="mt-3 text-sm text-gray-600 leading-relaxed">
                            We work with authorized distributors so you receive genuine items with valid warranty support wherever applicable.
                        </p>
                    </article>
                    <article class="group rounded-2xl border border-gray-100 bg-gray-50/80 p-6 sm:p-8 transition hover:border-purple-custom/30 hover:shadow-md hover:bg-white">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-custom text-white shadow-sm">
                            <i class="fas fa-bolt text-lg" aria-hidden="true"></i>
                        </div>
                        <h3 class="mt-5 text-lg font-bold text-gray-900">Fast delivery</h3>
                        <p class="mt-3 text-sm text-gray-600 leading-relaxed">
                            Streamlined logistics and clear tracking help your order reach you on time, with careful packaging every step of the way.
                        </p>
                    </article>
                    <article class="group rounded-2xl border border-gray-100 bg-gray-50/80 p-6 sm:p-8 transition hover:border-purple-custom/30 hover:shadow-md hover:bg-white">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-custom text-white shadow-sm">
                            <i class="fas fa-tags text-lg" aria-hidden="true"></i>
                        </div>
                        <h3 class="mt-5 text-lg font-bold text-gray-900">Fair, competitive prices</h3>
                        <p class="mt-3 text-sm text-gray-600 leading-relaxed">
                            Transparent pricing and regular promotions mean better value on the devices and accessories you rely on.
                        </p>
                    </article>
                </div>
            </div>
        </section>

        <!-- Mission & Vision -->
        <section class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 lg:py-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-start">
                <div>
                    <p class="text-xs font-semibold tracking-widest uppercase text-gray-500">Direction</p>
                    <h2 class="mt-3 text-2xl sm:text-3xl font-bold text-gray-900">Mission &amp; vision</h2>
                    <div class="mt-8 grid gap-4 sm:gap-6">
                        <div class="rounded-2xl border border-gray-100 bg-white p-6 sm:p-8 shadow-sm">
                            <div class="flex items-center gap-3 text-purple-custom">
                                <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-custom/10">
                                    <i class="fas fa-bullseye" aria-hidden="true"></i>
                                </span>
                                <h3 class="text-lg font-bold text-gray-900">Mission</h3>
                            </div>
                            <p class="mt-4 text-sm sm:text-base text-gray-600 leading-relaxed">
                                To make quality electronics and IT solutions accessible across Sri Lanka—through honest service, expert guidance, and a storefront that is easy to use on any device.
                            </p>
                        </div>
                        <div class="rounded-2xl border border-gray-100 bg-white p-6 sm:p-8 shadow-sm">
                            <div class="flex items-center gap-3 text-purple-custom">
                                <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-custom/10">
                                    <i class="fas fa-eye" aria-hidden="true"></i>
                                </span>
                                <h3 class="text-lg font-bold text-gray-900">Vision</h3>
                            </div>
                            <p class="mt-4 text-sm sm:text-base text-gray-600 leading-relaxed">
                                To be the region’s most trusted technology marketplace—where businesses and individuals choose GeoTrans first for selection, after-sales care, and long-term partnership.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="rounded-2xl overflow-hidden shadow-lg ring-1 ring-gray-200 aspect-[4/3] sm:aspect-auto sm:min-h-[320px] lg:min-h-full lg:sticky lg:top-24">
                    <img src="assets/images/about/about3.png.png" alt="City skyline representing growth and innovation"
                        class="h-full w-full object-cover"
                        loading="lazy">
                </div>
            </div>
        </section>

        <!-- Timeline -->
        <section class="bg-gray-100/80 border-y border-gray-200/80 py-8 sm:py-12 lg:py-16">
            <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-2xl mb-10 sm:mb-14">
                    <p class="text-xs font-semibold tracking-widest uppercase text-gray-500">Our journey</p>
                    <h2 class="mt-3 text-2xl sm:text-3xl font-bold text-gray-900">From retail to a digital storefront</h2>
                    <p class="mt-4 text-sm sm:text-base text-gray-600 leading-relaxed">
                        Decades of experience on the shop floor now power a modern e-commerce experience—same standards, wider reach.
                    </p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16">
                    <ul class="relative ml-1 sm:ml-2 border-l-2 border-purple-custom/25 pl-8 sm:pl-10 space-y-0" role="list">
                        <?php
                        $milestonesLeft = [
                            ['year' => '1997', 'text' => 'Opened our first retail location, focused on office equipment and consumer electronics.'],
                            ['year' => '1998', 'text' => 'Expanded supplier partnerships to offer wider brand coverage and spare-part support.'],
                            ['year' => '2000', 'text' => 'Launched corporate sales and on-site installation for small and medium businesses.'],
                            ['year' => '2004', 'text' => 'Invested in inventory systems and after-sales service to shorten repair turnaround.'],
                            ['year' => '2006', 'text' => 'Opened additional branches to serve growing demand across the western province.'],
                            ['year' => '2010', 'text' => 'Formalized training for staff on emerging categories: networking, storage, and peripherals.'],
                        ];
                        foreach ($milestonesLeft as $m):
                        ?>
                        <li class="relative pb-8 last:pb-0">
                            <span class="absolute -left-[1.15rem] sm:-left-[1.35rem] top-1 flex h-3.5 w-3.5 sm:h-4 sm:w-4 items-center justify-center rounded-full border-2 border-white bg-purple-custom shadow ring-2 ring-purple-custom/15" aria-hidden="true"></span>
                            <time class="text-sm font-bold text-purple-custom tabular-nums"><?= htmlspecialchars($m['year']) ?></time>
                            <p class="mt-1 text-sm text-gray-600 leading-relaxed"><?= htmlspecialchars($m['text']) ?></p>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <ul class="relative ml-1 sm:ml-2 border-l-2 border-purple-custom/25 pl-8 sm:pl-10 space-y-0" role="list">
                        <?php
                        $milestonesRight = [
                            ['year' => '2016', 'text' => 'Merged retail operations under Deotrans Pvt Ltd with a unified quality and returns policy.'],
                            ['year' => '2018', 'text' => 'Rolled out same-day dispatch for in-stock items across key Colombo postcodes.'],
                            ['year' => '2019', 'text' => 'Added dedicated B2B quoting and volume pricing for schools and enterprises.'],
                            ['year' => '2020', 'text' => 'Strengthened remote support and contactless pickup during changing market conditions.'],
                            ['year' => '2021', 'text' => 'Upgraded warehousing and QC checks to reduce defects and improve pack accuracy.'],
                            ['year' => '2022', 'text' => 'Launched GeoTrans online to bring the full catalogue to customers nationwide.'],
                        ];
                        foreach ($milestonesRight as $m):
                        ?>
                        <li class="relative pb-8 last:pb-0">
                            <span class="absolute -left-[1.15rem] sm:-left-[1.35rem] top-1 flex h-3.5 w-3.5 sm:h-4 sm:w-4 items-center justify-center rounded-full border-2 border-white bg-purple-custom shadow ring-2 ring-purple-custom/15" aria-hidden="true"></span>
                            <time class="text-sm font-bold text-purple-custom tabular-nums"><?= htmlspecialchars($m['year']) ?></time>
                            <p class="mt-1 text-sm text-gray-600 leading-relaxed"><?= htmlspecialchars($m['text']) ?></p>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Leadership -->
        <section class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 lg:py-16">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8 sm:mb-12">
                <div>
                    <p class="text-xs font-semibold tracking-widest uppercase text-gray-500">Team</p>
                    <h2 class="mt-2 text-2xl sm:text-3xl font-bold text-gray-900">Leadership</h2>
                    <p class="mt-3 text-sm text-gray-600 max-w-xl leading-relaxed">
                        People behind the strategy that keeps GeoTrans moving forward.
                    </p>
                </div>
                <a href="index.php" class="inline-flex items-center gap-2 text-sm font-semibold text-purple-custom hover:underline shrink-0">
                    Back to home
                    <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6 sm:gap-8">
                <?php
                $leaders = [
                    ['img' => 'assets/images/about/L1.png', 'name' => 'Henry Avery', 'role' => 'Director'],
                    ['img' => 'assets/images/about/L2.png', 'name' => 'Michael Edward', 'role' => 'Executive'],
                    ['img' => 'assets/images/about/L3.png', 'name' => 'Eden Hazard', 'role' => 'Chairman'],
                    ['img' => 'assets/images/about/L4.png', 'name' => 'Robert Downey Jr', 'role' => 'Executive'],
                    ['img' => 'assets/images/about/L5.png', 'name' => 'Nathan Drake', 'role' => 'Chairman'],
                ];
                foreach ($leaders as $L):
                ?>
                <article class="group text-center">
                    <div class="overflow-hidden rounded-2xl bg-gray-100 shadow-sm ring-1 ring-gray-100 transition group-hover:shadow-md group-hover:ring-purple-custom/20">
                        <div class="aspect-[3/4] sm:aspect-[4/5] overflow-hidden">
                            <img src="<?= htmlspecialchars($L['img']) ?>" alt="<?= htmlspecialchars($L['name']) ?>"
                                class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.03]"
                                loading="lazy">
                        </div>
                    </div>
                    <h3 class="mt-4 text-sm font-bold text-gray-900"><?= htmlspecialchars($L['name']) ?></h3>
                    <p class="mt-1 text-xs font-medium uppercase tracking-wide text-gray-500"><?= htmlspecialchars($L['role']) ?></p>
                </article>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>

</body>

</html>
