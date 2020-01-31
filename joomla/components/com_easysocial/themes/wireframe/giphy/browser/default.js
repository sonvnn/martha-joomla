EasySocial.ready(function($) {

$('[data-giphy-search]').on('keyup', $.debounce(function() {
	var popboxWrapper = $(this).parents('[data-popbox-content]');
	var loading = $('[data-giphy-loading]');
	var giphyList = $('[data-giphy-list]');

	giphyList.addClass('t-hidden');
	loading.addClass('is-active');

	var query = $(this).val();
	var trendingTitle = popboxWrapper.find('[data-giphy-trending]');

	// If is not search from comment popbox giphy form, then we know is from story
	if (popboxWrapper.find('[data-giphy-trending]').length == 0) {
		trendingTitle = $('[data-giphy-trending]');
	}

	trendingTitle.removeClass('t-hidden');

	if (query != '') {
		trendingTitle.addClass('t-hidden');		
	}

	EasySocial.ajax('site/controllers/giphy/search', {
		"query": query,
		"from": "<?php echo $story ? 'story' : 'comment'; ?>"
	}).done(function(html) {
		loading.removeClass('is-active');
		giphyList.removeClass('t-hidden');

		giphyList.html(html);

		popboxWrapper.trigger('giphyAfterSearch', [query]);
	});
}, 300))

});