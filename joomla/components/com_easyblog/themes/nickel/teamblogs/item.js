<?php echo $this->output('site/blogs/nickel'); ?>

EasyBlog.require()
.library('masonry', 'imagesloaded')
.script('site/teamblogs','site/posts/posts')
.done(function($) {

	$('[data-team-item]').implement(EasyBlog.Controller.TeamBlogs.Item);

	$('[data-blog-posts]').implement(EasyBlog.Controller.Posts, {
		"ratings": <?php echo $this->config->get('main_ratings') ? 'true' : 'false';?>
	});

	<?php if ($hasPinterestEmbedCode) { ?>
		EasyBlog.require().script("https://assets.pinterest.com/js/pinit_main.js");
	<?php } ?>
	
	// MASONRY
	var container = $('.eb-posts-masonry');

	$('img').load(function(){
		container.imagesLoaded(function(){
			container.masonry({
				itemSelector : '.eb-post',
				isRTL: false
			});
		});
	});


	$('.eb-masonry').imagesLoaded( function(){
		$('.eb-masonry').masonry({
			itemSelector: '.eb-masonry-post'
		});
	});

	$('.eb-masonry').masonry({
		itemSelector: '.eb-masonry-post'
	});

	setTimeout(function() {
		// Cheap fix to fix social buttons
		$('.eb-masonry').masonry();
	}, 5000);

	$(document).on('click.teamblog.join', '[data-team-join]', function() {

		var id = $(this).data('id');

		EasyBlog.dialog({
			content: EasyBlog.ajax('site/views/teamblog/join', {
				"id": id,
				"return": "<?php echo base64_encode(EBFactory::getURI(true));?>"
			})
		});

	});

	$(document).on('click.teamblog.join', '[data-team-leave]', function() {

		var id = $(this).data('id');

		EasyBlog.dialog({
			content: EasyBlog.ajax('site/views/teamblog/leave', {
				"ids": id,
				"return": "<?php echo base64_encode(EBFactory::getURI(true));?>"
			})
		});

	});

});
