// resources/js/main.js
import Swiper from "swiper";
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";
import "swiper/css/effect-fade";
import "swiper/css/autoplay";
import { Pagination } from "swiper/modules";
import { Navigation } from "swiper/modules";
import { EffectFade } from "swiper/modules";

document.addEventListener("DOMContentLoaded", () => {
    // Banner background swiper
    const swiperBg = new Swiper(".mySwiperBg", {
        modules: [EffectFade, Pagination, Navigation],
        slidesPerView: 1,
        spaceBetween: 30,
        loop: true,
        keyboard: {
            enabled: true,
        },
        pagination: {
            el: ".custom-swiper-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".custom-swiper-banner-next",
            prevEl: ".custom-swiper-banner-prev",
        },
        speed: 600,
        effect: "fade",
        allowTouchMove: false,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
    });

    // Banner content swiper
    const swiperContent = new Swiper(".mySwiper", {
        slidesPerView: 1,
        spaceBetween: 30,
        loop: true,
        keyboard: {
            enabled: true,
        },
        pagination: {
            el: ".custom-swiper-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".custom-swiper-button-next",
            prevEl: ".custom-swiper-button-prev",
        },
        speed: 400,
        effect: "slide",
        slideEffect: {
            slideShadows: true,
        },
        allowTouchMove: false,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
    });

    var gamingSwiper = new Swiper(".myGamingSwiper", {
        modules: [EffectFade, Pagination, Navigation],
        loop: true,
        spaceBetween: 24,
        navigation: {
            nextEl: ".custom-swiper-button-gaming-next",
            prevEl: ".custom-swiper-button-gaming-prev",
        },

        breakpoints: {
            0: {
                slidesPerView: 1,
            },
            480: {
                slidesPerView: 2,
            },
            768: {
                slidesPerView: 3,
            },
            992: {
                slidesPerView: 4,
            },
        },
    });

    var runningEventSwiper = new Swiper(".myRunningEventSwiper", {
        modules: [EffectFade, Pagination, Navigation],
        loop: true,
        spaceBetween: 24,
        navigation: {
            nextEl: ".custom-swiper-button-running-next",
            prevEl: ".custom-swiper-button-running-prev",
        },
        breakpoints: {
            0: {
                slidesPerView: 1,
            },
            480: {
                slidesPerView: 2,
            },
            768: {
                slidesPerView: 3,
            },
            992: {
                slidesPerView: 3,
            },
            1200: {
                slidesPerView: 4,
            },
        },
    });

    var upcomingEventSwiper = new Swiper(".myUpcomingEventSwiper", {
        modules: [EffectFade, Pagination, Navigation],
        loop: true,
        spaceBetween: 24,
        navigation: {
            nextEl: ".custom-swiper-button-upcoming-next",
            prevEl: ".custom-swiper-button-upcoming-prev",
        },
        breakpoints: {
            0: {
                slidesPerView: 1,
            },
            480: {
                slidesPerView: 2,
            },
            768: {
                slidesPerView: 3,
            },
            992: {
                slidesPerView: 3,
            },
            1200: {
                slidesPerView: 4,
            },
        },
    });

    var latestBlogSwiper = new Swiper(".myLatestBlogSwiper", {
        modules: [EffectFade, Pagination, Navigation],
        loop: true,
        spaceBetween: 24,
        navigation: {
            nextEl: ".custom-swiper-button-latest-next",
            prevEl: ".custom-swiper-button-latest-prev",
        },
        breakpoints: {
            0: {
                slidesPerView: 1,
            },
            480: {
                slidesPerView: 2,
            },
            768: {
                slidesPerView: 3,
            },
            992: {
                slidesPerView: 3,
            },
        },
    });

    document
        .querySelector(".custom-swiper-button-next")
        .addEventListener("click", () => {
            swiperBg.slideNext();
            swiperContent.slideNext();
        });

    document
        .querySelector(".custom-swiper-button-prev")
        .addEventListener("click", () => {
            swiperBg.slidePrev();
            swiperContent.slidePrev();
        });
});
