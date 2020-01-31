EasyBlog.require()
.script('site/vendors/swiper')
.done(function($) {

	var swiper = new Swiper($('[data-eb-featured-container]'), {
		"freeMode": false,
		"slidesPerView": 'auto',

		<?php if ($this->params->get('featured_auto_slide', true)) { ?>
		"autoplay": {
			"delay": <?php echo $this->params->get('featured_auto_slide_interval', 8) * 1000;?>
		}
		<?php } ?>
	});

	// Prev and Next button
	$('[data-featured-posts] [data-featured-previous]').on('click', function() {
		swiper.slidePrev();
	});

	$('[data-featured-posts] [data-featured-next]').on('click', function() {
		swiper.slideNext();
	});
});