<script src="{{ asset('assets/vendor/jquery/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.js') }}"></script>
<script src="{{ asset('assets/vendor/slimscroll/jquery.slimscroll.js') }}"></script>

<script src="{{ asset('assets/libs/js/main-js.js') }}"></script>
<script>
    setTimeout(function() {
        $('.alert-dismissible').fadeOut();
    }, 15000);

    function matchHeight() {
        var maxHeight = 0;
        $('.equal-height .card').each(function() {
            var cardHeight = $(this).outerHeight();
            if (cardHeight > maxHeight) {
                maxHeight = cardHeight;
            }
        });
        $('.equal-height .card').css('height', maxHeight);
    }
</script>
@stack('js')

@show
