EasyBlog.require()
.script('site/vendors/swiper')
.done(function($) {
	var swiper = new Swiper($('[data-eb-grid-featured-container]'), {
		"freeMode": false,
		"slidesPerView": 'auto'

		<?php if ($this->params->get('showcase_auto_slide', true)) { ?>
		,"autoplay": {
			"delay": <?php echo $this->params->get('showcase_auto_slide_interval', 8) * 1000;?>
		}
		<?php } ?>
	});

	// Prev and Next button
	$('[data-eb-grid-featured-container] [data-featured-previous]').on('click', function() {
		swiper.slidePrev();
	});

	$('[data-eb-grid-featured-container] [data-featured-next]').on('click', function() {
		swiper.slideNext();
	});
});
