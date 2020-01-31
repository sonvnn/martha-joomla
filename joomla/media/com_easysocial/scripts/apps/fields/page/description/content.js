EasySocial.module('apps/fields/page/description/content', function($) {

var module = this;

EasySocial.Controller('Field.Page.Description', {
    defaultOptions: {
        "required": false,
        "editor": null,
        "{input}": '[data-field-description]'
    }
}, function(self, opts, base) { return {

    init: function() {
        self.editor = self.options.editor;
    },

    "{self} onRender": function() {
        var data = self.input().htmlData();
        opts.error = data.error || {};
    },

    '{input} keyup': function() {
        self.validateInput();
    },

    '{input} blur': function() {
        self.validateInput();
    },

    validateInput: function() {
        self.clearError();

        var value = self.editor.getContent();

        if (self.options.required && $.isEmpty(value)) {
            self.raiseError(opts.error.required);
            return false;
        }

        return true;
    },

    raiseError: function(msg) {
        self.trigger('error', [msg]);
    },

    clearError: function() {
        self.trigger('clear');
    },

    '{self} onError': function(el, ev, type) {
        if(type === 'required') {
            self.raiseError(opts.error.required);
        }
    },

    '{self} onSubmit': function(el, ev, register) {

        var fieldId = self.options.fieldId;
        var editorName = self.options.editorName;

        // only apply for the tinymce editor
        if (editorName == 'tinymce') {
	        var textareaToggleWrapper = '#' + fieldId;

			if (typeof(tinyMCE) == 'object') {

				// if the editor form still under toggle page (HTML page)
				// We need to automatically toggle it back to the formatter page before submit
				if ($(textareaToggleWrapper).is(":visible")) {
					tinyMCE.execCommand('mceToggleEditor', false, fieldId);
				}
			}
        }

        register.push(self.validateInput());
    }
}});

module.resolve();
});
