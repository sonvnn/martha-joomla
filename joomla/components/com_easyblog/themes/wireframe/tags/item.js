EasyBlog.require()
.script('site/posts/posts')
.done(function($) {
	$('[data-blog-posts]').implement(EasyBlog.Controller.Posts, {
		"ratings": <?php echo $this->config->get('main_ratings') ? 'true' : 'false';?>
	});

	<?php if ($hasPinterestEmbedCode) { ?>
		EasyBlog.require().script("https://assets.pinterest.com/js/pinit_main.js");
	<?php } ?>
});
