
EasySocial
.require()
.script('apps/fields/user/joomla_username/content')
.done(function($) {
	$('[data-field-<?php echo $field->id; ?>]').addController('EasySocial.Controller.Field.Joomla_username', {
		id: <?php echo $field->id; ?>,
		userid: <?php echo $userid ? $userid : 0; ?>
	});
});
