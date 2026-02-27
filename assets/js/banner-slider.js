// Banner slider using Swiper.js
// https://swiperjs.com/

document.addEventListener('DOMContentLoaded', function() {
    var swiper = new Swiper('.banner-swiper', {
        loop: true,
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
        },
        effect: 'fade',
        fadeEffect: { crossFade: true },
        speed: 1000,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });
});
