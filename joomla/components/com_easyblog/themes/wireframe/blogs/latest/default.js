EasyBlog.require()
.script('site/posts/listings')
.done(function($) {

	$('[data-blog-listings]').implement(EasyBlog.Controller.Listings, {
		"ratings": <?php echo $this->config->get('main_ratings') ? 'true' : 'false';?>,
		"autoload": <?php echo $showLoadMore ? 'true' : 'false'; ?>
	});	

	<?php if ($hasPinterestEmbedCode) { ?>
		EasyBlog.require().script("https://assets.pinterest.com/js/pinit_main.js");
	<?php } ?>
});