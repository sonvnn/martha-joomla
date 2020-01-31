EasyBlog.require()
.script('admin/grid')
.done(function($)
{
	// Implement controller on the form
	$('[data-grid-eb]').implement(EasyBlog.Controller.Grid);

	$.Joomla("submitbutton", function(action) {

		if (action == 'blogs.createTemplate') {
			window.location = '<?php echo JURI::root();?>administrator/index.php?option=com_easyblog&view=templates&tmpl=component';
			return false;
		}

		$.Joomla('submitform', [action]);
	});

	var importTemplates = $('[data-toolbar-import]');
	importTemplates.appendTo($('#toolbar'));
	importTemplates.removeClass('hidden');
	
	importTemplates.on('click', function() {
		EasyBlog.dialog({
			"content": EasyBlog.ajax('admin/views/blogs/importForm', {})			
		});
	});
});