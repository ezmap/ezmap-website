(function () {
    $(window).on('scroll', function(){
        if($(window).scrollTop() > 65)
        {
            $('.theresults').css({position: "fixed", top: "0"});
        }
        else {
            $('.theresults').css({position: "absolute", top: "65px"});
        }
    });
})();