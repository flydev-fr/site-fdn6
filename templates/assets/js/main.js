

$(document).ready(function(){
    // Init Meanmenu (mobile device menu)
    $('#mobile-menu nav').meanmenu();

    // Carousel
    $('.main-carousel').slick({
        autoplay : true,
        autoplaySpeed: 2000,
        centerMode: false,
        dots: true,
        arrows: true,

        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    infinite: true,
                    dots: true
                }
            },
            {
                breakpoint: 600,
                settings: {
                    dots: false
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    dots: false
                }
            }
        ]
    });
});


