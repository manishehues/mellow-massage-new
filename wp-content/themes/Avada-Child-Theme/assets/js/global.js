(function( $ ) {

    /*=======Logo Color change internal Page =======*/
    

    $(document).ready(function () {
        // $("#bars").click(function () {
        //     $("#mobileMenu").slideToggle();
        // });
        // $(".cus_menu .fa-angle-down").click(function () {
        //     $(this).parent().find(".submenus").slideToggle();
        //     //$(".submenus").slideToggle();
        // });


        jQuery('#MobileMenu ul li.dropdown-menu').each(function(e,value){
            
            if (jQuery(this).hasClass('dropdown-menu')){
                                
                var $link     = jQuery('> a',this);
                var $submenu  = $link.parent().find(".submenus");
                if (window.location.href != $link.attr('href') || true){
                    $submenu.prepend('<li><a href="' + $link.attr('href') + '">' + 'View ' +  $link.clone().remove('svg').text() + '</a></li>');
                }
                $link.click(function(e){
                    e.preventDefault();

                    $submenu.stop().slideToggle(250);
                });
            }
        });
    });

    /*var $window = $(window);
    var $sidebar = $(".siteHeader");
    var $sidebarHeight = $sidebar.innerHeight();
    var $footerOffsetTop = $(".commonClass").offset().top;
    var $sidebarOffset = $sidebar.offset();

    $window.scroll(function () {
        if ($window.scrollTop() + 100 > $footerOffsetTop) {
            $sidebar.addClass("chngLogo");
        } else {
            $sidebar.removeClass("chngLogo");
        }
        if ($window.scrollTop() + $sidebarHeight > $footerOffsetTop) {
            $sidebar.css({ "top": -($window.scrollTop() + $sidebarHeight - $footerOffsetTop) });
        } else {
            $sidebar.css({ "top": "0", });
        }
    });*/
    (function ($) {
        $(document).ready(function () {
            $(".mobile_toggle").click(function () {
                //$("#MobileMenu").slideToggle();
            });
    
    
            $(window).scroll(function () {
            var scroll = $(window).scrollTop();
            if (scroll >= 50) {
                $(".siteHeader.full").addClass("sticky");
            } else {
                $(".siteHeader.full").removeClass("sticky");
            }
        });
    
        jQuery("#footrlnk_").attr('href',window.location.href+"#book-now");
            
        });
    })(jQuery);


    
   

      $('.homeServiceSlider .fusion-column-wrapper').slick({
        infinite: true,               
        slidesToShow: 3,			
        slidesToScroll: 1,    
        arrows: true,
        centerMode: true,
        centerPadding: '40px',
        responsive: [
            {
              breakpoint: 1365,
              settings: {
                slidesToShow: 2,
                slidesToScroll: 1               
              }
            },
            {
              breakpoint: 1023,
              settings: {
                slidesToShow: 2,
                slidesToScroll: 1
              }
            },
            {
                breakpoint: 767,
                settings: {
                  slidesToShow: 1,
                  slidesToScroll: 1
                }
              }           
          ]
    });
    
    
    $('#mainServices.ipad .servAllService .fusion-column-wrapper').slick({       
        infinite: true,               
        slidesToShow: 2,			
        slidesToScroll: 1,    
        arrows: false,
        centerMode: true,        
        dots: true,
        responsive: [           
            {
              breakpoint: 767,
              settings: {
                slidesToShow: 1,
                slidesToScroll: 1
              }
            }          
          ]
    });


})( jQuery );    