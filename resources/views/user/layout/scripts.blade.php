<script src="{{asset('user1/login/vendor/jquery/jquery-3.2.1.min.js')}}"></script>
<script src="{{asset('user1/login/vendor/animsition/js/animsition.min.js')}}"></script>
<script src="{{asset('user1/login/vendor/bootstrap/js/popper.js')}}"></script>
<script src="{{asset('user1/login/vendor/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{asset('user1/login/vendor/select2/select2.min.js')}}"></script>
<script src="{{asset('user1/login/vendor/daterangepicker/moment.min.js')}}"></script>
<script src="{{asset('user1/login/vendor/daterangepicker/daterangepicker.js')}}"></script>
<script src="{{asset('user1/login/vendor/countdowntime/countdowntime.js')}}"></script>
<script src="{{asset('user1/login/js/main.js')}}"></script>
<script src="{{asset('user1/fonts/js/bootstrap.js')}}"></script>
<script src="{{asset('user1/fonts/js/bootstrap.min.js')}}"></script>
<script src="{{asset('user1/fonts/js/all.js')}}"></script>
<script src="{{asset('user1/fonts/js/fontawesome.js')}}"></script>
<script src="{{asset('user1/fonts/js/fontawesome.min.js')}}"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script type="text/javascript">
    jQuery(".main-carousel2").owlCarousel({
        autoplay: true,
        lazyLoad: true,
        loop: true,
        margin: 20,
        /*
animateOut: 'fadeOut',
animateIn: 'fadeIn',
*/
        responsiveClass: true,
        autoHeight: true,
        autoplayTimeout: 7000,
        smartSpeed: 800,
        navText: [
            "<i class='fa fa-caret-left'></i>",
            "<i class='fa fa-caret-right'></i>",
        ],
        nav: true,
        responsive: {
            0: {
                items: 1,
            },
            600: {
                items: 1,
            },
            1024: {
                items: 1,
            },
            1366: {
                items: 1,
            },
        },
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        function counter(id, start, end, duration) {
            let obj = document.getElementById(id),
                current = start,
                range = end - start,
                increment = end > start ? 1 : -1,
                step = Math.abs(Math.floor(duration / range)),
                timer = setInterval(() => {
                    current += increment;
                    if (current == end) {
                        clearInterval(timer);
                    }
                }, step);
        }
        counter("count1", 0, 900, 3000);
        counter("count2", 100, 300, 2500);
        counter("count3", 0, 70, 3000);
    });
</script>
</script>
<script src="https://fajira.com/static/front/theme1/js/aos.js"></script>

<script src="js/sidenav.js">
</script>
<script>
    AOS.init({
        duration: 1200,
    })
</script>
<script>
    $(document).ready(function() {

        var stickyToggle = function(sticky, stickyWrapper, scrollElement) {
            var stickyHeight = sticky.outerHeight();
            var stickyTop = stickyWrapper.offset().top;
            if (scrollElement.scrollTop() >= stickyTop) {
                stickyWrapper.height(stickyHeight);
                sticky.addClass("is-sticky");
            } else {
                sticky.removeClass("is-sticky");
                stickyWrapper.height('auto');
            }
        };


        $('[data-toggle="sticky-onscroll"]').each(function() {
            var sticky = $(this);
            var stickyWrapper = $('<div>').addClass('sticky-wrapper');
            sticky.before(stickyWrapper);
            sticky.addClass('sticky');


            $(window).on('scroll.sticky-onscroll resize.sticky-onscroll', function() {
                stickyToggle(sticky, stickyWrapper, $(this));
            });


            stickyToggle(sticky, stickyWrapper, $(window));
        });
    });
</script>

