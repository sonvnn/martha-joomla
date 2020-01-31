/*------------------------------------------------------------------------

 # TZ Extension

 # ------------------------------------------------------------------------

 # author    DuongTVTemPlaza

 # copyright Copyright (C) 2012 templaza.com. All Rights Reserved.

 # @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

 # Websites: http://www.templaza.com

 # Technical Support:  Forum - http://templaza.com/Forum

 -------------------------------------------------------------------------*/

(function($) {
    'use strict';

    $.mtzmultifield   = function(el, options){
        var service = $(el);

        // making variables public
        service.vars = $.extend({}, $.mtzmultifield.defaults, options);

        var messages = service.vars.eMessages,
            position = -1,
            $hidden_name = service.vars.fieldName;

        // Store a reference to the slider object
        $.data(el, "mtzmultifield", service);

        service.htmlspecialchars    = function(str){
            if (typeof(str) == "string") {
                str = str.replace(/&/g, "&amp;"); /* must do &amp; first */
                str = str.replace(/"/g, "&quot;");
                str = str.replace(/'/g, "&#039;");
                str = str.replace(/</g, "&lt;");
                str = str.replace(/>/g, "&gt;");
            }
            return str;
        };

        // Add new data row
        $(service.vars.selector + " .tz_btn-add").bind("click",function(e){

            // Create input hidden with data were put
            var $tbodyHtmlClone   = service.vars.tbodyHtml;
            var $tbody_bool             = true;
            var $content                = {};

            $.each(service.vars.fields,function(key,value){
                var input_name  = value["name"].replace(/\[/,"\\[")
                    .replace(/\]/,"\\]");

                if(value["field_required"]){
                    $tbody_bool = false;
                    if(!$("#" + value["id"]).val().length){
                        alert(messages.invalidField + value["label"]);
                        $("#" + value["id"]).focus();
                        return false;
                    }
                }

                if(value["value_validate"]){
                    if($("#" + value["id"]).val() == value["value_validate"]){
                        alert(messages.failToValue + value['value_validate'] + messages.failOfField
                            + value["label"]);
                        return false;
                    }
                }

                // Check required and create row for table
                if(value["table_required"]){
                    var pattern = "\\{"+value["id"]+"\\}";
                    var regex   = new RegExp(pattern,'gi');
                    $tbodyHtmlClone   = $tbodyHtmlClone.replace(regex,$("#" + value["id"]).val());
                }

                $tbody_bool = true;

                if(value["type"].toLowerCase() == 'editor'){
                    // Get content of editor
                    if(service.vars.editorUsed == "jce") {
                        $content[value["fieldname"]] = WFEditor.getContent(value["id"]);
                    }else{
                        if(service.vars.editorUsed == "tinymce"){
                            $content[value["fieldname"]]    =  tinyMCE.activeEditor.getContent();
                        }else{
                            if(service.vars.editorUsed == "codemirror") {
                                $content[value["fieldname"]] = Joomla.editors.instances[value["id"]].getValue();
                            }else{
                                $content[value["fieldname"]] = $("#" + value["id"]).val();
                            }
                        }
                    }
                }else {
                    if($("[name=" + input_name + "]").prop('tagName').toLowerCase() == 'input'
                        && $("[name=" + input_name + "]").prop('type') == 'radio') {
                        $content[value["fieldname"]] = $("[name="+ value["name"].replace(/\[/,"\\[")
                                .replace(/\]/,"\\]")+"]:checked").val();
                    }else {
                        $content[value["fieldname"]] = $("#" + value["id"]).val();
                    }
                }
            });

            if($tbody_bool && Object.keys($content).length){
                var pattern2 = "\\{"+service.vars.parentFieldId+"\\}";
                var regex2   = new RegExp(pattern2,'gi');
                $tbodyHtmlClone   = $tbodyHtmlClone.replace(regex2,service.htmlspecialchars(JSON.stringify($content)));
                if(position > -1 ) {
                    $(service.vars.selector + " .tzmultifield__table tbody tr")
                        .eq( position).after($tbodyHtmlClone).remove();
                    position = -1;
                }else {
                    $(service.vars.selector + " .tzmultifield__table tbody").prepend($tbodyHtmlClone);
                }

                // Call trigger reset form
                $(service.vars.selector + " .tz_btn-reset").trigger("click");

                service.tzPricingTableAction();
            }

        });

        // Reset form
        $(service.vars.selector + " .tz_btn-reset").bind("click",function(){
            if(service.vars.fields.length) {
                $.each(service.vars.fields, function (key, value) {
                    var input_name  = value["name"].replace(/\[/,"\\[")
                        .replace(/\]/,"\\]");
                    if (value["type"].toLowerCase() == 'editor') {
                        if(service.vars.editorUsed == "jce") {
                            WFEditor.setContent(value["id"], value["default"]);
                        }else{
                            if(service.vars.editorUsed == "tinymce"){
                                tinyMCE.activeEditor.setContent(value["default"]);
                            }else{
                                if(service.vars.editorUsed == "codemirror") {
                                    Joomla.editors.instances[value["id"]].setValue(value["default"]);
                                }else{
                                    $("#" + value["id"]).val('');
                                }
                            }
                        }
                    } else {
                        if($("[name=" + input_name + "]").prop('tagName').toLowerCase() == 'select') {
                            $("#" + value["id"]).val(value["default"])
                                .trigger("liszt:updated");
                        }else{
                            if($("[name=" + input_name + "]").prop('tagName').toLowerCase() == 'input'
                                && $("[name=" + input_name + "]").prop('type') == 'radio') {
                                $("[name=" + input_name + "]").removeAttr("checked");
                                $("#" + value["id"]+" label[for=" + $("[name=" + input_name + "][value="
                                        + value["default"] +"]").attr("id")
                                    +"]").trigger("click");
                            }else {
                                $("#" + value["id"]).val(value["default"]);
                            }
                        }
                    }
                });
                position = -1;
            }
        });

        service.tzPricingTableAction    = function() {
            // Edit data
            $(service.vars.selector + " .tz_btn-edit").unbind("click").bind("click", function () {
                var $hidden_value = $(this).parents("td").first()
                    .find("input[name=\"" + $hidden_name + "\"]").val();
                if ($hidden_value.length) {
                    var $hidden_obj_value = $.parseJSON($hidden_value);
                    if (service.vars.fields.length) {
                        $.each(service.vars.fields, function (key, value) {
                            var input_name  = value["name"].replace(/\[/,"\\[")
                                .replace(/\]/,"\\]");
                            if(value["showon"]) {
                                $("[name=\"" + value["showon"] + "\"]").trigger("change");
                            }

                            if (value["type"].toLowerCase() == 'editor') {

                                if(service.vars.editorUsed == "jce") {
                                    WFEditor.setContent(value["id"], $hidden_obj_value[value["fieldname"]]);
                                }else{
                                    if(service.vars.editorUsed == "tinymce"){
                                        tinyMCE.activeEditor.setContent($hidden_obj_value[value["fieldname"]]);
                                    }else{
                                        if(service.vars.editorUsed == "codemirror") {
                                            Joomla.editors.instances[value["id"]].setValue($hidden_obj_value[value["fieldname"]]);
                                        }else{
                                            $("#" + value["id"]).val($hidden_obj_value[value["fieldname"]]);
                                        }
                                    }
                                }
                            } else{
                                if($("[name=" + input_name + "]").prop('tagName').toLowerCase() == 'select') {
                                    $("#" + value["id"]).val($hidden_obj_value[value["fieldname"]])
                                        .trigger("liszt:updated");
                                }else{
                                    if($("[name=" + input_name + "]").prop('tagName').toLowerCase() == 'input'
                                        && $("[name=" + input_name + "]").prop('type') == 'radio') {
                                        $("[name=" + input_name + "]").removeAttr("checked");
                                        $("#" + value["id"]+" label[for=" + $("[name=" + input_name + "][value="
                                                + $hidden_obj_value[value["fieldname"]] +"]").attr("id")
                                            +"]").trigger("click");
                                    }else {
                                        $("#" + value["id"]).val($hidden_obj_value[value["fieldname"]]);
                                    }
                                }
                            }

                            if($(service.vars.selector + " .field-media-wrapper").data("fieldMedia")){
                                var fieldMedia = $(service.vars.selector + " .field-media-wrapper").data("fieldMedia");
                                fieldMedia.updatePreview();
                            }
                        });
                        position = $(service.vars.selector + " .tzmultifield__table tbody tr")
                            .index($(this).parents("tr").first());
                    }
                }
            });

            // Remove data row
            $(service.vars.selector + " .tz_btn-remove").unbind("click").bind("click", function () {
                var message = confirm(messages.removeItem);
                if (message) {
                    var curpos = $(service.vars.selector + " .tzmultifield__table tbody tr")
                        .index($(this).parents("tr").first());
                    $(this).parents('tr').first().remove();
                    if(position != -1){
                        if(curpos < position){
                            position -= 1;
                        }else{
                            if(curpos == position) {
                                position = -1;
                            }
                        }
                    }
                }
            });
        };

        service.tzPricingTableAction();

        // Sortable row
        $(service.vars.selector + " .tzmultifield__table tbody").sortable({
            cursor: "move",
            items: "> tr",
            revert: true,
            handle: ".icon-move",
            forceHelperSize: true,
            placeholder: "ui-state-highlight"
        });
        return this;
    };
    $.mtzmultifield.defaults  = {
        id: "",
        tbodyHtml: "",
        fields: "",
        fieldName: "",
        parentFieldId: "",
        editorUsed: "tinymce",
        eMessages: {
            invalidField: "Invalid field: ",
            failOfField: "of field ",
            failToValue: "Failed to put value:",
            removeItem: "Are you sure to remove this item?"
        }
    };

    $.fn.mtzmultifield = function(options){

        if(options === undefined) options   = {};
        if(typeof options === 'object'){
            if(!options.selector){options.selector  = this.selector; }
            // Call function
            return this.each(function() {
                var $this = $(this);
                // Call function
                if ($this.data("mtzmultifield") === undefined) {
                    new $.mtzmultifield(this, options);
                }
            });
        }else{
            
        }
    }
})(jQuery);