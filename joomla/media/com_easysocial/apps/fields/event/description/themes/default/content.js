
EasySocial
    .require()
    .script('apps/fields/event/description/content')
    .done(function($) {

        $('[data-field-<?php echo $field->id; ?>]').addController('EasySocial.Controller.Field.Event.Description', {
            "required": <?php echo $field->required ? 1 : 0; ?>,
            "fieldId": "es_fields_<?php echo $field->id; ?>",
            "editorName": "<?php echo $editorName; ?>",
            "editor": {

                getContent: function() {
                    <?php echo 'return ' . ES::editor()->getContent($editor, $inputName); ?>
                }
            }
        });
    });
