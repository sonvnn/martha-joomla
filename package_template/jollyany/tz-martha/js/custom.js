jQuery(function($){
    var updateHikashopCart = function () {
        if ($(".jollyany-hikacart").length){
            $('.jollyany-hikacart-icon > i').html('<span class="cart-count">'+$('.jollyany-hikacart .hikashop_cart_module tbody tr').length+'</span>');
        }
    };
    var removeHikashopItem = function () {
        if ($('.hikashop_cart_module_product_delete_value').length) {
            $('.hikashop_cart_module_product_delete_value > a').on('click', function (e) {
                setTimeout(
                    function() {
                        updateHikashopCart();
                        removeHikashopItem();
                    }, 3000);
            });
        }
        if ($('.hikashop_cart_product_quantity_delete').length) {
            $('.hikashop_cart_product_quantity_delete > a').on('click', function (e) {
                setTimeout(
                    function() {
                        updateHikashopCart();
                        removeHikashopItem();
                    }, 3000);
            });
        }
    };

    $(document).ready(function(){
        if ($(".jollyany-hikacart").length){
            $(".jollyany-hikacart-icon").on("click", function(e){
                e.preventDefault();
            });
            updateHikashopCart();
            removeHikashopItem();
            if ($('.hikacart').length) {
                $('.hikacart').on('click', function (e) {
                    setTimeout(
                        function() {
                            updateHikashopCart();
                            removeHikashopItem();
                        }, 3000);
                });
            }
        }
        if ($(".jollyany-login").length){
            $(".jollyany-login-icon").on("click", function(e){
                e.preventDefault();
            });
        }
        var rellax = new Rellax('.rellax', {
            // horizontal: true,
            // vertical: true,
            center: true
        });
    });
});
var startAnim = function(array, id, interval){
    var widthslider =   jQuery(id).find('.sppb-carousel-list').width(),
        defaultX    =   (widthslider - jQuery(array[0]).width())/2;
    if(array.length >= 4 ) {
        TweenMax.fromTo(array[0], 0.5, {x:defaultX, y: 0, opacity:0.75}, {x:defaultX, y: -120, opacity:0, zIndex: 0, delay:0.03, ease: Cubic.easeInOut, onComplete: sortArray(array, id, interval)});

        TweenMax.fromTo(array[1], 0.5, {x:0, y: 125, width: '100%', opacity:1, zIndex: 1}, {x:defaultX, y: 0, width: '85%', opacity:0.75, zIndex: 0, boxShadow: '-5px 8px 8px 0 rgba(82,89,129,0.05)', ease: Cubic.easeInOut});

        TweenMax.to(array[2], 0.5, {bezier:[{x:defaultX, y:250}, {x:defaultX/2, y:200}, {x:0, y:125}], boxShadow: '-5px 8px 8px 0 rgba(82,89,129,0.05)', width: '100%', zIndex: 1, opacity: 1, ease: Cubic.easeInOut});

        TweenMax.fromTo(array[3], 0.5, {x:0, y:400, opacity: 0, zIndex: 0}, {x:defaultX, y:250, opacity: 0.75, zIndex: 0, ease: Cubic.easeInOut}, );
    } else {
        jQuery(id).append('<p>Sorry, carousel should contain more than 3 slides</p>')
    }
};

var sortArray = function(array, id, interval) {
    clearTimeout(delay);
    var delay = setTimeout(function(){
        var firstElem = array.shift();
        array.push(firstElem);
        return startAnim(array, id, interval);
    },interval)
};