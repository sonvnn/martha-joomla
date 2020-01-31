<?php if ($video->isLargeEmbed()) { ?>
EasySocial.require()
.script('site/videos/preview')
.done(function($) {
	$('[data-es-embed-container]').addController(EasySocial.Controller.Videos.Preview);
});
<?php } ?>
