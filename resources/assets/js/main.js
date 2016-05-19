(function () {
    $(window).on('scroll', function () {
        if ($(window).scrollTop() > 65) {
            $('.theresults').addClass("moved");
        }
        else {
            $('.theresults').removeClass("moved");
        }
    });
})();