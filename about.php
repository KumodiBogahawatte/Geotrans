<?php require_once __DIR__ . '/includes/helpers.php'; ?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | Geotrans</title>
    <meta name="description" content="Geotrans (PVT) LTD — office automation and IT solutions in Sri Lanka since 2006.">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --brand: #680e68;
            --brand-dark: #4f0a4f;
            --brand-soft: rgba(104, 14, 104, 0.08);
            --surface: #f1f5f9;
            --ink: #0f172a;
            --muted: #64748b;
        }

        .about-font {
            font-family: "Plus Jakarta Sans", system-ui, sans-serif;
        }

        .text-purple-custom { color: var(--brand); }
        .bg-purple-custom { background-color: var(--brand); }
        .border-purple-custom { border-color: var(--brand); }
        .ring-purple-custom { --tw-ring-color: var(--brand); }

        .about-hero {
            background-color: #f4eef8;
            background-image:
                linear-gradient(115deg, rgba(255, 255, 255, 0.97) 0%, rgba(250, 248, 252, 0.9) 38%, rgba(248, 250, 252, 0.55) 58%, rgba(248, 250, 252, 0.25) 100%),
                radial-gradient(1000px 600px at 100% 40%, rgba(104, 14, 104, 0.12), transparent 55%),
                url("assets/images/about/Geo\ Trans\ Hero\ 1.png");
            background-size: auto, auto, cover;
            background-position: 0 0, 0 0, center right;
            background-repeat: no-repeat;
        }

        .about-hero::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23680e68' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.9;
            pointer-events: none;
        }

        .about-kicker {
            letter-spacing: 0.22em;
            font-size: 0.6875rem;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--brand);
        }

        .about-card {
            border-radius: 1.25rem;
            box-shadow:
                0 1px 2px rgba(15, 23, 42, 0.04),
                0 12px 40px -12px rgba(15, 23, 42, 0.08);
        }

        .about-service-tile {
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }

        @media (hover: hover) {
            .about-service-tile:hover {
                transform: translateY(-2px);
                box-shadow: 0 16px 40px -16px rgba(104, 14, 104, 0.15);
                border-color: rgba(104, 14, 104, 0.22);
            }
        }

        .about-section-title {
            font-size: clamp(1.5rem, 2.5vw, 2rem);
            font-weight: 800;
            letter-spacing: -0.02em;
            color: var(--ink);
        }

        .about-gradient-border {
            background: linear-gradient(135deg, rgba(104, 14, 104, 0.15), rgba(251, 191, 36, 0.12));
            padding: 1px;
            border-radius: 1.25rem;
        }

        .about-mission-quote {
            border-left: 3px solid var(--brand);
        }

        /* Vision card: deep slate + soft brand glows (avoids flat “purple wall”) */
        .about-vision-surface {
            background-color: #0f172a;
            background-image:
                radial-gradient(120% 100% at 100% -30%, rgba(104, 14, 104, 0.38), transparent 52%),
                radial-gradient(90% 70% at -15% 100%, rgba(104, 14, 104, 0.22), transparent 48%),
                linear-gradient(165deg, #1e293b 0%, #0f172a 45%, #0c1222 100%);
        }

        @media (prefers-reduced-motion: reduce) {
            .about-service-tile { transition: none; }
            .about-service-tile:hover { transform: none; }
        }
    </style>
</head>

<body class="about-font bg-slate-100 text-slate-800 antialiased">

    <?php include 'includes/header.php'; ?>

    <header class="about-hero relative min-h-[320px] overflow-hidden border-b border-slate-200/80 sm:min-h-[380px]">
        <div class="relative z-10 max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-center py-12 sm:py-16 lg:py-20">
                <div class="lg:col-span-6 text-center lg:text-left order-2 lg:order-1">
                    <p class="about-kicker mb-4">About us</p>
                    <h1 class="text-[clamp(1.875rem,4.5vw,3.25rem)] font-extrabold tracking-tight text-slate-900 leading-[1.08]">
                        Office automation &amp; IT solutions for
                        <span class="text-purple-custom">Sri Lankan businesses</span>
                    </h1>
                    <p class="mt-6 text-base sm:text-lg text-slate-600 max-w-xl mx-auto lg:mx-0 leading-relaxed">
                        Geotrans (PVT) LTD delivers reliable, innovative technology that helps you work smarter—from printers and copiers to full workplace IT.
                    </p>
                    
                    <div class="mt-10 flex flex-col sm:flex-row flex-wrap gap-3 justify-center lg:justify-start">
                        <a href="products.php" class="inline-flex items-center justify-center gap-2 rounded-full bg-purple-custom px-7 py-3.5 text-sm font-bold text-white shadow-lg shadow-purple-custom/25 transition hover:bg-[#4f0a4f] focus:outline-none focus-visible:ring-2 focus-visible:ring-purple-custom focus-visible:ring-offset-2">
                            Browse products
                            <i class="fas fa-arrow-right text-xs opacity-90" aria-hidden="true"></i>
                        </a>
                        <a href="contact.php" class="inline-flex items-center justify-center gap-2 rounded-full border border-slate-300/90 bg-white/80 px-7 py-3.5 text-sm font-semibold text-slate-800 backdrop-blur-sm transition hover:border-purple-custom hover:text-purple-custom focus:outline-none focus-visible:ring-2 focus-visible:ring-purple-custom focus-visible:ring-offset-2">
                            Contact us
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="pb-8 sm:pb-12">

        <!-- Who we are -->
        <section class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 pt-8 sm:pt-12" aria-labelledby="about-who">
            <div class="about-gradient-border">
                <div class="overflow-hidden rounded-[1.24rem] bg-slate-50 about-card ring-1 ring-slate-200/90">
                    <div class="grid grid-cols-1 lg:grid-cols-2">
                        <div class="relative min-h-[280px] sm:min-h-[380px] lg:min-h-[420px] order-2 lg:order-1">
                            <img src="assets/images/about/close-up-man-shopping-with-laptop 1.png" alt=""
                                class="absolute inset-0 h-full w-full object-cover"
                                loading="lazy">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-900/35 to-slate-900/10"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-8 text-white">
                                <p class="text-[10px] sm:text-xs font-bold uppercase tracking-[0.2em] text-white/80">Geotrans</p>
                                <p class="mt-2 text-xl sm:text-2xl font-bold leading-tight tracking-tight">Built on trust, built for productivity</p>
                            </div>
                        </div>
                        <div class="order-1 lg:order-2 p-8 sm:p-10 lg:p-12 flex flex-col justify-center bg-gradient-to-br from-slate-50 to-white">
                            <p class="about-kicker text-[10px] sm:text-xs">Identity</p>
                            <h2 id="about-who" class="about-section-title mt-2">Who we are</h2>
                            <p class="mt-5 text-slate-600 leading-relaxed text-sm sm:text-base">
                                Geotrans (PVT) LTD is a trusted provider of office automation and IT solutions in Sri Lanka. Established in 2006 and incorporated on March 31, 2007, we have built a strong reputation for delivering reliable, high-quality technology solutions to businesses across the country.
                            </p>
                            <blockquote class="about-mission-quote mt-8 pl-5 py-1 text-slate-700 text-sm sm:text-base leading-relaxed bg-purple-custom/[0.04] rounded-r-xl">
                                <strong class="text-purple-custom font-semibold">Our mission</strong> — to provide <span class="font-semibold text-slate-900">efficient, innovative, and dependable office automation products</span> that enhance productivity and streamline business operations.
                            </blockquote>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- What we do -->
        <section class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 pt-8 sm:pt-12" aria-labelledby="about-services">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-10">
                <div class="min-w-0 flex-1">
                    <p class="about-kicker text-[10px] sm:text-xs">Capabilities</p>
                    <h2 id="about-services" class="about-section-title mt-1">What we do</h2>
                    <p class="mt-3 w-full max-w-full text-sm sm:text-base text-slate-600 leading-relaxed whitespace-nowrap overflow-x-auto overscroll-x-contain [scrollbar-width:thin]">
                        We offer a comprehensive range of office automation and IT solutions, making us a <span class="font-semibold text-slate-900">one-stop destination for modern workplaces:</span>
                    </p>
                </div>
                <div class="hidden sm:block h-px flex-1 max-w-xs bg-gradient-to-r from-purple-custom/30 to-transparent ml-8 mb-2" aria-hidden="true"></div>
            </div>

            <div class="rounded-3xl bg-white p-6 sm:p-8 lg:p-10 about-card ring-1 ring-slate-200/90">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-3.5">
                    <?php
                    $services = [
                        ['icon' => 'fa-print', 'label' => 'Photocopiers & Printers'],
                        ['icon' => 'fa-copy', 'label' => 'Digital Duplicators'],
                        ['icon' => 'fa-barcode', 'label' => 'Scanners'],
                        ['icon' => 'fa-video', 'label' => 'Multimedia Projectors'],
                        ['icon' => 'fa-laptop', 'label' => 'Desktop & Laptop Computers'],
                        ['icon' => 'fa-chalkboard', 'label' => 'Interactive Smart Boards'],
                        ['icon' => 'fa-cash-register', 'label' => 'POS Solutions'],
                        ['icon' => 'fa-drafting-compass', 'label' => 'Plotters'],
                        ['icon' => 'fa-battery-full', 'label' => 'UPS Systems'],
                        ['icon' => 'fa-coins', 'label' => 'Cash Counters'],
                        ['icon' => 'fa-desktop', 'label' => 'Monitors'],
                        ['icon' => 'fa-layer-group', 'label' => 'Laminators'],
                        ['icon' => 'fa-cut', 'label' => 'Paper Shredders'],
                        ['icon' => 'fa-fill-drip', 'label' => 'Toners, Ribbons & Cartridges'],
                        ['icon' => 'fa-keyboard', 'label' => 'All Computer Accessories'],
                        ['icon' => 'fa-briefcase', 'label' => 'Business Software Solutions'],
                    ];
                    foreach ($services as $s):
                    ?>
                    <div class="about-service-tile flex items-center gap-3 rounded-2xl border border-slate-200/90 bg-slate-50/80 px-4 py-3.5 text-sm font-medium text-slate-700">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white text-purple-custom ring-1 ring-slate-200/80 shadow-sm">
                            <i class="fas <?= htmlspecialchars($s['icon']) ?>" aria-hidden="true"></i>
                        </span>
                        <span><?= htmlspecialchars($s['label']) ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="mt-10 grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <figure class="group relative overflow-hidden rounded-2xl ring-1 ring-slate-200 aspect-[4/3] sm:aspect-auto sm:h-48">
                        <img src="assets/images/about/44400a4882241d8412a5c1f4b0a9fc7ee567462d (1).png" alt="Office solutions" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
                        <figcaption class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-slate-950/80 to-transparent p-4 pt-12 text-xs font-semibold text-white">Solutions</figcaption>
                    </figure>
                    <figure class="group relative overflow-hidden rounded-2xl ring-1 ring-slate-200 aspect-[4/3] sm:aspect-auto sm:h-48">
                        <img src="assets/images/about/about3.png.png" alt="Technology and service" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
                        <figcaption class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-slate-950/80 to-transparent p-4 pt-12 text-xs font-semibold text-white">Scale</figcaption>
                    </figure>
                    <figure class="group relative overflow-hidden rounded-2xl ring-1 ring-slate-200 aspect-[4/3] sm:aspect-auto sm:h-48">
                        <img src="assets/images/about/close-up-man-shopping-with-laptop 1.png" alt="Business technology" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
                        <figcaption class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-slate-950/80 to-transparent p-4 pt-12 text-xs font-semibold text-white">Partnership</figcaption>
                    </figure>
                </div>
            </div>
        </section>

        <!-- Expertise + Team (dark band) -->
        <section class="mt-8 sm:mt-12 relative overflow-hidden bg-slate-900 text-slate-100" aria-labelledby="about-expertise">
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_80%_50%_at_50%_-20%,rgba(104,14,104,0.35),transparent)] pointer-events-none" aria-hidden="true"></div>
            <div class="absolute inset-0 opacity-[0.07] bg-[url('data:image/svg+xml,%3Csvg width=\'40\' height=\'40\' viewBox=\'0 0 40 40\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath d=\'M0 40L40 0H20L0 20M40 40V20L20 40\' fill=\'%23fff\' fill-opacity=\'1\' fill-rule=\'evenodd\'/%3E%3C/svg%3E')]" aria-hidden="true"></div>
            <div class="relative max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-stretch">
                    <article class="flex h-full min-h-0 flex-col rounded-3xl border border-white/10 bg-white/[0.06] p-8 sm:p-10 backdrop-blur-md">
                        <p class="text-[10px] sm:text-xs font-bold uppercase tracking-[0.2em] text-purple-300">Depth</p>
                        <h2 id="about-expertise" class="mt-2 text-2xl sm:text-3xl font-extrabold tracking-tight text-white">Our expertise</h2>
                        <p class="mt-5 text-slate-300 leading-relaxed text-sm sm:text-base">
                            With years of industry experience, Geotrans (PVT) LTD combines <span class="font-semibold text-white">technical knowledge with practical business understanding</span> to deliver solutions that meet today’s demanding office environments.
                        </p>
                        <ul class="mt-auto space-y-4 pt-8">
                            <li class="flex gap-4">
                                <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-purple-custom/30 text-purple-200"><i class="fas fa-circle-check text-sm" aria-hidden="true"></i></span>
                                <span class="text-sm sm:text-base text-slate-200 leading-relaxed">Providing reliable and efficient technology</span>
                            </li>
                            <li class="flex gap-4">
                                <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-purple-custom/30 text-purple-200"><i class="fas fa-circle-check text-sm" aria-hidden="true"></i></span>
                                <span class="text-sm sm:text-base text-slate-200 leading-relaxed">Supporting businesses with scalable solutions</span>
                            </li>
                            <li class="flex gap-4">
                                <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-purple-custom/30 text-purple-200"><i class="fas fa-circle-check text-sm" aria-hidden="true"></i></span>
                                <span class="text-sm sm:text-base text-slate-200 leading-relaxed">Ensuring long-term value for our customers</span>
                            </li>
                        </ul>
                    </article>
                    <article class="flex h-full min-h-0 flex-col rounded-3xl border border-white/10 bg-white/[0.06] p-8 sm:p-10 backdrop-blur-md">
                        <p class="text-[10px] sm:text-xs font-bold uppercase tracking-[0.2em] text-purple-300">People</p>
                        <h2 class="mt-2 text-2xl sm:text-3xl font-extrabold tracking-tight text-white">Our team</h2>
                        <p class="mt-5 text-slate-300 leading-relaxed text-sm sm:text-base">
                            Our team consists of <span class="font-semibold text-white">qualified, skilled, and innovative professionals</span> who are dedicated to meeting the evolving needs of modern businesses.
                        </p>
                        <p class="mt-auto inline-flex items-center gap-2 rounded-full border border-purple-400/30 bg-purple-custom/25 px-5 py-2.5 text-sm text-white/95">
                            <i class="fas fa-star text-amber-300" aria-hidden="true"></i>
                            <span class="font-bold text-white tracking-tight">Exceeding customer satisfaction at every step</span>
                        </p>
                    </article>
                </div>
            </div>
        </section>

        <!-- Vision + Values (vision first on small screens so it stays visible) -->
        <section class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12" aria-labelledby="about-values">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 lg:gap-10">
                <article id="about-vision" class="about-vision-surface lg:col-span-2 order-1 lg:order-2 flex min-h-[260px] flex-col justify-between rounded-3xl p-8 text-white about-card ring-1 ring-slate-600/40 shadow-xl shadow-slate-950/40 sm:min-h-[300px] sm:p-10">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-purple-300 sm:text-xs">Geotrans</p>
                        <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-white drop-shadow-sm">Our vision</h2>
                        <p class="mt-5 text-sm leading-relaxed text-slate-200 sm:text-base">
                            To become the <span class="font-semibold text-white">leading office automation supplier in Sri Lanka</span>, recognized for delivering <span class="font-semibold text-white">quality products and exceptional service.</span>
                        </p>
                    </div>
                    <p class="mt-8 border-t border-white/15 pt-6 text-xs leading-relaxed text-slate-300 sm:text-sm">
                        We align every solution with long-term business performance, service quality, and customer trust.
                    </p>
                </article>
                <article class="lg:col-span-3 order-2 lg:order-1 rounded-3xl bg-white p-8 sm:p-10 about-card ring-1 ring-slate-200/90">
                    <p class="about-kicker text-[10px] sm:text-xs">Geotrans</p>
                    <h2 id="about-values" class="about-section-title mt-1">Our values</h2>
                    <ul class="mt-8 grid gap-4 sm:grid-cols-2">
                        <?php
                        $values = [
                            ['t' => 'Commitment', 'd' => 'Delivering dependable service'],
                            ['t' => 'Customer value', 'd' => 'Putting customers first'],
                            ['t' => 'Teamwork', 'd' => 'Working together for excellence'],
                            ['t' => 'Professionalism', 'd' => 'Maintaining high standards'],
                            ['t' => 'Flexibility & adaptability', 'd' => 'Embracing change'],
                            ['t' => 'Social responsibility', 'd' => 'Acting responsibly in society'],
                        ];
                        foreach ($values as $v):
                        ?>
                        <li class="rounded-2xl border border-slate-100 bg-slate-50/80 p-4 transition hover:border-purple-custom/20 hover:bg-purple-custom/[0.03]">
                            <p class="text-sm font-bold text-slate-900"><?= htmlspecialchars($v['t']) ?></p>
                            <p class="mt-1 text-xs sm:text-sm text-slate-600 leading-relaxed"><?= htmlspecialchars($v['d']) ?></p>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </article>
            </div>
        </section>

        <!-- Why choose + Commitment -->
        <section class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12" aria-labelledby="about-why">
            <div class="overflow-hidden rounded-3xl bg-slate-50 about-card ring-1 ring-slate-200/90">
                <div class="grid grid-cols-1 lg:grid-cols-12">
                    <div class="lg:col-span-5 relative min-h-[220px] lg:min-h-0">
                        <img src="assets/images/about/about3.png.png" alt="" class="absolute inset-0 h-full w-full object-cover" loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-r from-slate-950/90 via-slate-900/70 to-slate-900/20 lg:from-slate-950/95 lg:via-slate-900/50 lg:to-transparent"></div>
                        <div class="relative h-full flex flex-col justify-end p-8 lg:p-10 text-white">
                            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-white/70">Why Geotrans</p>
                            <p class="mt-2 text-lg font-bold leading-snug">The partner businesses choose for dependable office technology.</p>
                        </div>
                    </div>
                    <div class="lg:col-span-7 p-8 sm:p-10 lg:p-12">
                        <h2 id="about-why" class="about-section-title">Why choose Geotrans (PVT) LTD</h2>
                        <ul class="mt-8 grid gap-3 sm:grid-cols-2">
                            <li class="flex items-start gap-3 rounded-2xl border border-slate-200/80 bg-white px-4 py-3.5 text-sm text-slate-700">
                                <i class="fas fa-layer-group mt-0.5 text-purple-custom shrink-0" aria-hidden="true"></i>
                                <span>Wide range of office automation and IT products</span>
                            </li>
                            <li class="flex items-start gap-3 rounded-2xl border border-slate-200/80 bg-white px-4 py-3.5 text-sm text-slate-700">
                                <i class="fas fa-award mt-0.5 text-purple-custom shrink-0" aria-hidden="true"></i>
                                <span>Trusted experience since 2006</span>
                            </li>
                            <li class="flex items-start gap-3 rounded-2xl border border-slate-200/80 bg-white px-4 py-3.5 text-sm text-slate-700">
                                <i class="fas fa-headset mt-0.5 text-purple-custom shrink-0" aria-hidden="true"></i>
                                <span>Customer-focused service approach</span>
                            </li>
                            <li class="flex items-start gap-3 rounded-2xl border border-slate-200/80 bg-white px-4 py-3.5 text-sm text-slate-700">
                                <i class="fas fa-shield-halved mt-0.5 text-purple-custom shrink-0" aria-hidden="true"></i>
                                <span>Reliable, high-quality solutions</span>
                            </li>
                            <li class="flex items-start gap-3 rounded-2xl border border-slate-200/80 bg-white px-4 py-3.5 text-sm text-slate-700">
                                <i class="fas fa-store mt-0.5 text-purple-custom shrink-0" aria-hidden="true"></i>
                                <span>One-stop solution for business technology needs</span>
                            </li>
                        </ul>
                        <div class="mt-10 rounded-2xl border border-purple-custom/15 bg-purple-custom/[0.06] p-6">
                            <h3 class="text-base font-bold text-slate-900">Our commitment</h3>
                            <p class="mt-2 text-sm sm:text-base text-slate-600 leading-relaxed">
                                At Geotrans (PVT) LTD, we are committed to helping businesses grow by providing <span class="font-semibold text-slate-900">smart, reliable, and future-ready technology solutions</span> tailored to their needs.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>

</body>

</html>
