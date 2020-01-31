
EasySocial.require()
.script('site/stream/stream')
.done(function($) {

	$('[data-es-streams]').implement("EasySocial.Controller.Stream", {
		source : "<?php echo JRequest::getCmd('view', ''); ?>",
		sourceId : "<?php echo JRequest::getInt('id', ''); ?>",
		loadmore : false,
		copiedLinkMessage: "<?php echo JText::_('COM_ES_STREAM_ITEM_LINK_COPIED_MESSAGE'); ?>",
		commentOptions: {
				'attachments': <?php echo $this->config->get('comments.attachments.enabled') ? 'true' : 'false';?>,
				'errorMessage': "<?php echo JText::_('COM_ES_COMMENT_ERROR_MESSAGE'); ?>"
		}
	});
});
