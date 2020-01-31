var lightboxopen    =   false;
var selena_lightbox  =   function () {
    if(jQuery('.tplSelena').length > 0) {
        jQuery('.tplSelena .selenaLink').on('click', function(event) {
            event.preventDefault();
            var image       = [];
            var $pic        = jQuery('.tplSelena');
            var $clickid    = jQuery(this).attr('data-id');
            var $index      = 0;

            var getItems = function() {
                var items = [],
                    $el = '';
                $el = $pic.find('.element');
                $el.each(function() {
                    var thumb   =   jQuery(this).find('.tpArticleMedia img').attr('src');
                    jQuery(this).find('a.selenaLink').each(function () {
                        var $href       = jQuery(this).attr('href'),
                            $dataid     = jQuery(this).attr('data-id');
                        if ($dataid !== 'undefined' && $dataid !== null) {
                            var item = {
                                src     : $href,
                                type    : 'iframe',
                                opts    : {
                                    thumb   : thumb
                                }
                            };
                            items.push(item);
                            if ($clickid === $dataid) $index = items.length-1;
                            return false;
                        }
                    });
                });
                return items;
            };

            if (lightboxopen === false) {
                var items       = getItems();
                if (jQuery(window).width()<768) {
                    var instance    = jQuery.fancybox.open(items, {
                        loop : true,
                        thumbs : {
                            autoStart : false
                        },
                        beforeShow: function( instance, slide ) {
                            lightboxopen = true;
                        },
                        afterClose: function( instance, slide ) {
                            lightboxopen = false;
                        },
                        afterShow: function (instance, slide) {
                            instance.update();
                        }
                    }, $index);
                } else {
                    var instance    = jQuery.fancybox.open(items, {
                        loop : true,
                        thumbs : {
                            autoStart : true
                        },
                        beforeShow: function( instance, slide ) {
                            lightboxopen = true;
                        },
                        afterClose: function( instance, slide ) {
                            lightboxopen = false;
                        },
                        afterShow: function (instance, slide) {
                            instance.update();
                        }
                    }, $index);
                }
            }
        });
    }
};