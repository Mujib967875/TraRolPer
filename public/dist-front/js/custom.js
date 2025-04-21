(function ($) {
    "use strict";

    $(".scroll-top").hide();
    $(window).on("scroll", function () {
        if ($(this).scrollTop() > 300) {
            $(".scroll-top").fadeIn();
        } else {
            $(".scroll-top").fadeOut();
        }
    });
    $(".scroll-top").on("click", function () {
        $("html, body").animate(
            {
                scrollTop: 0,
            },
            700
        );
    });

    $(document).ready(function () {
        $(".select2").select2({
            theme: "bootstrap",
        });
    });

    new WOW().init();

    $(".video-button").magnificPopup({
        type: "iframe",
        gallery: {
            enabled: true,
        },
    });
    $(".magnific").magnificPopup({
        type: "image",
        gallery: {
            enabled: true,
        },
    });

    $(".slide-carousel").owlCarousel({
        loop: true,
        autoplay: true,
        autoplayHoverPause: true,
        margin: 0,
        mouseDrag: false,
        animateIn: "fadeIn",
        animateOut: "fadeOut",
        nav: true,
        navText: [
            "<i class='fas fa-long-arrow-alt-left'></i>",
            "<i class='fas fa-long-arrow-alt-right'></i>",
        ],
        responsive: {
            0: {
                items: 1,
            },
            600: {
                items: 1,
            },
            1000: {
                items: 1,
            },
        },
    });

    $(".testimonial-carousel").owlCarousel({
        loop: true,
        autoplay: true,
        autoplayHoverPause: true,
        autoplaySpeed: 1500,
        smartSpeed: 1500,
        margin: 30,
        nav: false,
        animateIn: "fadeIn",
        animateOut: "fadeOut",
        navText: [
            "<i class='fa fa-caret-left'></i>",
            "<i class='fa fa-caret-right'></i>",
        ],
        responsive: {
            0: {
                items: 1,
                dots: false,
                nav: true,
            },
            768: {
                items: 1,
                dots: true,
            },
            992: {
                items: 2,
                dots: true,
            },
        },
    });

    $(".room-detail-carousel").owlCarousel({
        loop: true,
        autoplay: false,
        autoplayHoverPause: true,
        margin: 0,
        mouseDrag: false,
        animateIn: "fadeIn",
        animateOut: "fadeOut",
        nav: true,
        navText: [
            "<i class='fa fa-angle-left'></i>",
            "<i class='fa fa-angle-right'></i>",
        ],
        responsive: {
            0: {
                items: 1,
            },
            600: {
                items: 1,
            },
            1000: {
                items: 1,
            },
        },
    });

    jQuery(".mean-menu").meanmenu({
        meanScreenWidth: "991",
    });

    $(".datepicker").datepicker({
        format: "yyyy-mm-dd",
        todayHighlight: true
    });

    $('.counter').counterUp();

    const fileInput = document.querySelector('.file-input');
    const previewContainer = document.getElementById('previewContainer');

    fileInput.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (file) handleFile(file);
    });

    function handleFile(file) {
    if (!file.type.startsWith('image/')) {
        alert('Hanya file gambar yang diizinkan');
        return;
    }

    const reader = new FileReader();
    reader.onload = (e) => {
        previewContainer.innerHTML = `
        <img class="preview-image" src="${e.target.result}" alt="${file.name}">
        <div class="file-name">${file.name}</div>
        <button class="remove-btn">&times;</button>
        `;

        previewContainer.querySelector('.remove-btn').addEventListener('click', () => {
        previewContainer.classList.remove('active');
        previewContainer.innerHTML = '';
        fileInput.value = '';
        });

        previewContainer.classList.add('active');
    };
    reader.readAsDataURL(file);
    }

    document.addEventListener('click', (e) => {
    if (!e.target.closest('.upload-wrapper') && previewContainer.classList.contains('active')) {
        previewContainer.classList.remove('active');
    }
    });

})(jQuery);
