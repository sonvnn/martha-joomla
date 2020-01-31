
EasySocial.ready(function($) {

	<?php if ($editor != 'bbcode') { ?>
	window.toggleSave = function() {
		<?php echo ES::editor()->getEditor($editor)->save('news_content'); ?>
	}

	window.getContent = function() {
		<?php echo 'return ' . ES::editor()->getEditor($editor)->getContent( 'news_content' ); ?>
	}
	<?php } ?>

	$('[data-news-save-button]').on('click', function() {

		var contents = $('textarea[name="news_content"]').val();

		<?php if ($editor != 'bbcode') { ?>
			// Ensure that it is always toggled back.
			window.toggleSave();
			contents = window.getContent();
		<?php } ?>

		var title = $('[data-es-news-title]');

		// Do not allow it to save if it's empty
		if (title.val() == '') {
			return false
		}

		if (contents == '') {
			return false;
		}

		return true;
	});
});
