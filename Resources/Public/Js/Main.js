require(['jquery'], function ($) {
    var acc = $(".accordion"); //jquery accordion

    acc.click(function() //when we click on element
    {
        $(this).toggleClass("active");  //it is active
        $('.panel').not($(this).next()).hide(); //we hide all panels but not the next element
        $(this).next().toggle(); //and we show the next element
    });
});
