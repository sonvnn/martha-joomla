
EasySocial
    .require()
    .script('apps/fields/group/description/content')
    .done(function($) {

        $('[data-field-<?php echo $field->id; ?>]').addController('EasySocial.Controller.Field.Group.Description', {
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
