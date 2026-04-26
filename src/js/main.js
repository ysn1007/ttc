$(function() {
    // start owl slider

    $("#index .owl-carousel").owlCarousel({
        items:1,
        loop:true,
        center:true,
        margin:10,
        autoplay: true,
    });

    $("#galerie .owl-carousel").owlCarousel({
        items:3,
        loop:true,
        center:true,
        margin:10,
        autoplay: true,
    });

    $("#ostercup .oc-img-carousel").owlCarousel({
        items:1,
        loop:true,
        center:true,
        margin:10,
        autoplay: true,
    });
   

   
});