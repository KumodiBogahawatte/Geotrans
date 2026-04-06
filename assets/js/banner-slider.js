// Top banner Swiper: advances to next slide only after all products on the current slide have been shown.
// https://swiperjs.com/
// ≤1023px: autoHeight so tall chip rows + image stacks are not clipped.

(function () {
    var HERO_ROT = [
        ['ViewSonic PA700W', 'Toshiba e-STUDIO 2829A', 'ASUS ExpertBook', 'LT65S982EA', 'RISO Digital Duplicator A3 SF9390', 'Cassida ARTEMIS'],
        ['HP Smart Tank 580', 'Epson Perfection V39 II', 'Canon imageCLASS MF641Cw', 'HP Color LaserJet Pro 3303sdw'],
        ['HP 23.8″ All-in-One 24-cr0073d PC', 'HP ProBook 460 G11', 'Lenovo ThinkPad L16 Gen 2', 'MSI Modern 15 F13MG', 'LENOVO 2-IN-1 / YOGA'],
        ['Epson T664 Ink Bottles', 'Epson 141 Ink Cartridges', 'HP 682 Ink Cartridge Series', 'HP GT52 Magenta Ink Bottle', 'Canon 045 Cyan / Magenta / Yellow']
    ];

    var ROTATE_MS = 2800;
    var heroRotIdx = [0, 0, 0, 0];
    var heroRotTimer;
    var bannerSwiper;
    var swiperEl;
    var mobileMq = typeof window.matchMedia === 'function'
        ? window.matchMedia('(max-width: 1023px)')
        : { matches: false, addEventListener: function () {}, addListener: function () {} };

    function useAutoHeight() {
        return mobileMq.matches;
    }

    /** One product image on mobile (left wing only) → single name + one chip */
    function isHeroMobileLayout() {
        return mobileMq.matches;
    }

    function refreshHeroHeight() {
        if (bannerSwiper && useAutoHeight() && typeof bannerSwiper.updateAutoHeight === 'function') {
            bannerSwiper.updateAutoHeight(0);
        }
    }

    /** Desktop: both wings → "A · B". Mobile: one wing → "A" only */
    function formatSpotlightText(list, i) {
        if (!list || !list.length) return '';
        if (isHeroMobileLayout()) {
            return list[i] || '';
        }
        var n = list.length;
        var a = list[i];
        var b = list[(i + 1) % n];
        if (n === 1 || a === b) return a;
        return a + ' · ' + b;
    }

    function setHeroChipsLit(chips, list, i) {
        if (!chips || !list || !list.length) return;
        var n = list.length;
        var i2 = (i + 1) % n;
        var mobile = isHeroMobileLayout();
        chips.querySelectorAll('.hero-chip').forEach(function (c, j) {
            c.classList.toggle('is-lit', mobile ? j === i : (j === i || j === i2));
        });
    }

    function syncHeroStacksInSlide(slideEl, idx) {
        if (!slideEl) return;
        var stacks = slideEl.querySelectorAll('[data-hero-stack]');
        stacks.forEach(function (stack) {
            var imgs = stack.querySelectorAll('.stack-img');
            if (!imgs.length) return;
            var pos = idx % imgs.length;
            for (var j = 0; j < imgs.length; j++) {
                imgs[j].classList.toggle('active', j === pos);
                imgs[j].classList.toggle('behind', j !== pos);
            }
        });
    }

    function resetHeroForSlide(ri) {
        var slideEl = swiperEl.querySelectorAll('.swiper-slide')[ri];
        if (!slideEl) return;
        var list = HERO_ROT[ri];
        var rotEl = slideEl.querySelector('[data-hero-rot]');
        var chips = slideEl.querySelector('[data-hero-chips]');
        var startIdx = ri === 0 ? 1 : 0;
        heroRotIdx[ri] = startIdx;
        if (rotEl && list && list[startIdx] !== undefined) {
            rotEl.textContent = formatSpotlightText(list, startIdx);
            rotEl.classList.remove('is-out');
        }
        if (chips && list) {
            setHeroChipsLit(chips, list, startIdx);
        }
        syncHeroStacksInSlide(slideEl, startIdx);
        refreshHeroHeight();
    }

    function updateRotatorUI(slideEl, ri, nextIdx) {
        var list = HERO_ROT[ri];
        var rotEl = slideEl.querySelector('[data-hero-rot]');
        if (!rotEl || !list) return;
        rotEl.classList.add('is-out');
        setTimeout(function () {
            rotEl.textContent = formatSpotlightText(list, nextIdx);
            rotEl.classList.remove('is-out');
            var chips = slideEl.querySelector('[data-hero-chips]');
            if (chips && list) {
                setHeroChipsLit(chips, list, nextIdx);
            }
            syncHeroStacksInSlide(slideEl, nextIdx);
            refreshHeroHeight();
        }, 220);
    }

    function tickHeroRotator() {
        if (!bannerSwiper) return;
        var ri = bannerSwiper.realIndex;
        var list = HERO_ROT[ri];
        if (!list || !list.length) return;
        var slideEl = swiperEl.querySelectorAll('.swiper-slide')[ri];
        if (!slideEl || !slideEl.classList.contains('swiper-slide-active')) return;

        if (heroRotIdx[ri] >= list.length - 1) {
            clearInterval(heroRotTimer);
            if (ri < HERO_ROT.length - 1) {
                bannerSwiper.slideNext();
            } else {
                bannerSwiper.slideTo(0);
            }
            return;
        }

        heroRotIdx[ri]++;
        updateRotatorUI(slideEl, ri, heroRotIdx[ri]);
    }

    function startHeroRotTimer() {
        clearInterval(heroRotTimer);
        heroRotTimer = setInterval(tickHeroRotator, ROTATE_MS);
    }

    function onViewportChange() {
        if (!bannerSwiper) return;
        bannerSwiper.params.autoHeight = useAutoHeight();
        bannerSwiper.update();
        refreshHeroHeight();
        resetHeroForSlide(bannerSwiper.realIndex);
        startHeroRotTimer();
    }

    document.addEventListener('DOMContentLoaded', function () {
        swiperEl = document.querySelector('.banner-swiper.hero-swiper');
        if (!swiperEl || typeof Swiper === 'undefined') return;

        var pag = swiperEl.querySelector('.swiper-pagination');

        function updateHeroNavDisabled() {
            if (!bannerSwiper) return;
            var beg = bannerSwiper.isBeginning;
            var end = bannerSwiper.isEnd;
            swiperEl.querySelectorAll('.swiper-button-prev').forEach(function (el) {
                el.classList.toggle('swiper-button-disabled', beg);
                el.setAttribute('aria-disabled', beg ? 'true' : 'false');
            });
            swiperEl.querySelectorAll('.swiper-button-next').forEach(function (el) {
                el.classList.toggle('swiper-button-disabled', end);
                el.setAttribute('aria-disabled', end ? 'true' : 'false');
            });
        }

        bannerSwiper = new Swiper(swiperEl, {
            loop: false,
            speed: 900,
            effect: 'fade',
            fadeEffect: { crossFade: true },
            autoHeight: useAutoHeight(),
            watchOverflow: true,
            pagination: {
                el: pag,
                clickable: true
            },
            on: {
                init: function () {
                    resetHeroForSlide(this.realIndex);
                    startHeroRotTimer();
                    updateHeroNavDisabled();
                },
                slideChange: function () {
                    resetHeroForSlide(this.realIndex);
                    startHeroRotTimer();
                    updateHeroNavDisabled();
                },
                slideChangeTransitionEnd: function () {
                    refreshHeroHeight();
                    updateHeroNavDisabled();
                }
            }
        });

        swiperEl.addEventListener('click', function (e) {
            var nextBtn = e.target.closest('.swiper-button-next');
            var prevBtn = e.target.closest('.swiper-button-prev');
            if (nextBtn && swiperEl.contains(nextBtn) && !nextBtn.classList.contains('swiper-button-disabled')) {
                e.preventDefault();
                bannerSwiper.slideNext();
            }
            if (prevBtn && swiperEl.contains(prevBtn) && !prevBtn.classList.contains('swiper-button-disabled')) {
                e.preventDefault();
                bannerSwiper.slidePrev();
            }
        });

        if (mobileMq.addEventListener) {
            mobileMq.addEventListener('change', onViewportChange);
        } else if (mobileMq.addListener) {
            mobileMq.addListener(onViewportChange);
        }

        swiperEl.addEventListener('click', function (e) {
            var chip = e.target.closest('.hero-chip');
            if (!chip) return;
            var slideEl = chip.closest('.swiper-slide');
            if (!slideEl || !slideEl.classList.contains('swiper-slide-active')) return;
            var slides = swiperEl.querySelectorAll('.swiper-slide');
            var ri = Array.prototype.indexOf.call(slides, slideEl);
            if (ri < 0) return;
            var i = parseInt(chip.getAttribute('data-i'), 10);
            if (isNaN(i)) return;
            var list = HERO_ROT[ri];
            if (!list || !list[i]) return;
            heroRotIdx[ri] = i;
            var rotEl = slideEl.querySelector('[data-hero-rot]');
            if (rotEl) {
                rotEl.classList.remove('is-out');
                rotEl.textContent = formatSpotlightText(list, i);
            }
            var chipWrap = slideEl.querySelector('[data-hero-chips]');
            if (chipWrap && list) {
                setHeroChipsLit(chipWrap, list, i);
            }
            syncHeroStacksInSlide(slideEl, i);
            startHeroRotTimer();
            refreshHeroHeight();
        });
    });
})();
