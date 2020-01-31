
EasyBlog.ready(function($) {
	// String truncater
	// Used when there is a read more of a truncated content.
	var selector = '[data-eb-truncater] > [data-readmore]';

	$(document)
		.on('click.eb.strings.truncater', selector, function() {

			var section = $(this).parent();
			var original = section.find('[data-original]');
			var text = section.find('[data-text]');

			// Hide the link
			$(this).addClass('t-hidden');

			// Show the full contents
			text.addClass('t-hidden');
			original.removeClass('t-hidden');
		});
});