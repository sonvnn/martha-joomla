jQuery(function($){
    var lightboxopen = false;
    $('.tz_portfolio_plus_grid_gallery .gallery-title').on('click', function(event) {
        event.preventDefault();
        var $pic        = $('.tz_portfolio_plus_grid_gallery');
        var $clickid    = $(this).attr('data-id');
        var $index      = 0;

        var getItems = function() {
            var items = [];
            $pic.find('.gallery-listing').each(function() {
                var thumb       =   $(this).find('.gallery-title').attr('data-thumb'),
                    $href       =   $(this).find('.gallery-title').attr('href'),
                    $dataid     =   $(this).find('.gallery-title').attr('data-id');
                if ($dataid !== 'undefined' && $dataid !== null) {
                    var item = {
                        src     : $href,
                        opts    : {
                            thumb   : thumb
                        }
                    };
                    items.push(item);
                    if ($clickid === $dataid) $index = items.length-1;
                }
            });
            return items;
        };

        if (lightboxopen === false) {
            var items       = getItems();
            if ($(window).width()<768) {
                var instance    = $.fancybox.open(items, {
                    loop : true,
                    thumbs : {
                        autoStart : false
                    },
                    buttons: grid_gallery_lightbox_buttons,
                    beforeShow: function( instance, slide ) {
                        lightboxopen = true;
                    },
                    afterClose: function( instance, slide ) {
                        lightboxopen = false;
                    }
                }, $index);
            } else {
                var instance    = $.fancybox.open(items, {
                    loop : true,
                    thumbs : {
                        autoStart : true
                    },
                    buttons: grid_gallery_lightbox_buttons,
                    beforeShow: function( instance, slide ) {
                        lightboxopen = true;
                    },
                    afterClose: function( instance, slide ) {
                        lightboxopen = false;
                    }
                }, $index);
            }
        }
    });
});