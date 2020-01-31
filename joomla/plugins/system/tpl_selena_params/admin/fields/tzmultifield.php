<?php
/*------------------------------------------------------------------------

# TZ Extension

# ------------------------------------------------------------------------

# author    DuongTVTemPlaza

# copyright Copyright (C) 2012 templaza.com. All Rights Reserved.

# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Websites: http://www.templaza.com

# Technical Support:  Forum - http://templaza.com/Forum

-------------------------------------------------------------------------*/

// no direct access

defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('list');

class JFormFieldTzMultiField extends JFormField
{
    protected $type         = 'TzMultiField';
    protected $prefix       = 'tzform';
    protected $text_prefix  = 'PLG_SYSTEM_TZ_TEMPLAZA_MENU_PARAMS';
    protected $module       = 'tz_templaza_menu_params';
    protected $head         = false;
    protected $multiple     = true;

    protected function getName($fieldName)
    {
        return parent::getName($fieldName);
    }

    protected function getInput()
    {
        $value  = $this -> value;
        if(is_string($value)){
            if(!preg_match('/(\{.*?\})/msi',$value)){
                $services   = base64_decode($value);
                if(preg_match_all('/(\{.*?\})/msi',$services,$match)){
                    if(count($match[1])){
                        $this -> setValue($match[1]);
                    }
                }
            }
        }else{
            if(!is_array($this -> value) && preg_match_all('/(\{.*?\})/',$this -> value,$match)) {
                $this -> setValue($match[1]);
            }
        }
        $doc    = JFactory::getDocument();
        if(!$this -> head) {
            $doc->addScript(JUri::root(true) . '/plugins/system/'.$this -> module.'/admin/js/jquery-ui.min.js');
            $doc->addScript(JUri::root(true) . '/plugins/system/'.$this -> module.'/admin/js/tzmultifield.js');
            $doc->addStyleSheet(JUri::root(true) . '/plugins/system/'.$this -> module.'/admin/css/jquery-ui.min.css');
            $doc->addStyleDeclaration('.tzmultifield__table .ui-sortable-helper{
                background: #fff;
            }');
            $this -> head   = true;
        }
        $id                 = $this -> id;
        $element            = $this -> element;
        $this -> __set('multiple','true');

        // Initialize some field attributes.
        $class = !empty($this->class) ? ' class="' . $this->class . '"' : '';
        $disabled = $this->disabled ? ' disabled' : '';

        // Initialize JavaScript field attributes.
        $onchange = $this->onchange ? ' onchange="' . $this->onchange . '"' : '';

        // Get children fields from xml file
        $tzfields = $element->children();
        // Get field with tzfield tags
        $xml                = array();
        $html               = array();
        $thead              = array();
        $tbody_col_require  = array();
        $tbody_row_id       = array();
        $tbody_row_html     = array();
        $tzform_control_id  = array();
        $form_control       = array();

        $tbody_row_html[]   = '<td style="width: 3%; text-align: center;">'
            .'<span class="icon-move hasTooltip" title="'.JText::_($this -> text_prefix.'_MOVE').'"
             style="cursor: move;"></span></td>';

        ob_start();
?>
        <div id="<?php echo $id;?>">
        <div class="control-group">
            <button type="button" class="btn btn-success tz_btn-add">
                <span class="icon-plus icon-white" title="<?php echo JText::_($this -> text_prefix.'_UPDATE');?>"></span>
                <?php echo JText::_($this -> text_prefix.'_UPDATE');?>
            </button>
            <button type="button" class="btn tz_btn-reset">
                <span class="icon-cancel" title="<?php echo JText::_($this -> text_prefix.'_RESET');?>"></span>
                <?php echo JText::_($this -> text_prefix.'_RESET');?>
            </button>
        </div>
        <?php

        // Generate children fields from xml file
        if ($tzfields) {
            $i  = 0;
            foreach ($tzfields as $xmlElement) {
                $type = $xmlElement['type'];
                if (!$type) {
                    $type = 'text';
                }
                $tz_class   = 'JFormField'.ucfirst($type);

                if(!class_exists($tz_class)) {
                    JLoader::register($tz_class,JPATH_LIBRARIES.DIRECTORY_SEPARATOR.'joomla'
                        .DIRECTORY_SEPARATOR.'form'
                    .DIRECTORY_SEPARATOR.'fields'.DIRECTORY_SEPARATOR.$type.'.php');
                }

                // Check formfield class of children field
                if(class_exists($tz_class)) {

                    // Create formfield class of children field
                    $tz_class = new $tz_class();
                    $tz_class -> setForm($this -> form);
                    $tz_class->formControl = $this -> prefix;
                    // Init children field for children class
                    $tz_class -> setup($xmlElement, '');
                    $tz_class -> value      = $xmlElement['default'];
                    $tz_name                = (string)$xmlElement['name'];
                    $tz_tbl_require         = (bool)$xmlElement['table_required'];

                    $tzform_control_id[$i]                      = array();
                    $tzform_control_id[$i]["id"]                = $tz_class -> id;
                    $tzform_control_id[$i]["type"]              = $tz_class -> type;
                    $tzform_control_id[$i]["fieldname"]         = $tz_class -> fieldname;
                    $tzform_control_id[$i]["table_required"]    = 0;
                    $tzform_control_id[$i]["name"]              = $tz_class -> name;
                    $tzform_control_id[$i]["default"]           = $tz_class ->default;
                    $tzform_control_id[$i]["field_required"]    = (bool)$xmlElement['field_required'];
                    $tzform_control_id[$i]["value_validate"]    = (string)$xmlElement['value_validate'];
                    $tzform_control_id[$i]["label"]             = $tz_class -> getTitle();

                    if(isset($xmlElement['showon']) && $xmlElement['showon']){
                        $showon = (string) $xmlElement['showon'];
                        $parse  = JFormHelper::parseShowOnConditions($showon, $this -> prefix);
                        $tzform_control_id[$i]['showon']    = $parse[0]['field'];
                    }

                    // Create table's head column (check attribute table_required of children field from xml file)
                    if ($tz_tbl_require) {
                        $tbody_row_id[]                             = $tz_class -> id;
                        $tbody_col_require[]                        = $tz_class -> fieldname;
                        $tzform_control_id[$i]["table_required"]    = 1;

                        ob_start();
                        ?>
                        <th><?php echo $tz_class -> getTitle(); ?></th>
                        <?php
                        $thead[] = ob_get_clean();
                        ob_start();
                        ?>
                        <td>{<?php echo $tz_class -> id;?>}</td>
                    <?php
                        $tbody_row_html[]   = ob_get_clean();
                    }
                    ob_start();
                    // Generate children field from xml file
                    echo $tz_class -> renderField();
                    ?>
                    <?php
                    $form_control[] = ob_get_clean();
                }
                $i++;
            }
        }
        // Generate table
        if(count($thead)) {
            ?>
            <table class="table table-striped tzmultifield__table">
                <thead>
                <tr>
                    <th style="width: 3%; text-align: center;">#</th>
                    <?php echo implode("\n", $thead); ?>
                    <th style="width: 10%; text-align: center;">Status</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if($values = $this -> value){
                    if(count($values)){
                    foreach($values as $value){
                        $j_value    = json_decode($value);
                ?>
                    <tr>
                        <td style="width: 3%; text-align: center;"><span class="icon-move hasTooltip" style="cursor: move;"
                                  title="<?php echo JText::_($this -> text_prefix.'_MOVE')?>"></span></td>
                        <?php
                        if($j_value && !empty($j_value)) {
                            foreach ($j_value as $key => $_j_value) {
                                if(in_array($key,$tbody_col_require)){

                        ?>
                            <td><?php echo $_j_value ?></td>
                        <?php } }
                        }
                        ?>
                        <td style="width: 3%; text-align: center;">
                            <div class="btn-group">
                                <button class="btn btn-small tz_btn-edit hasTooltip"
                                        type="button" title="<?php echo JText::_('JACTION_EDIT');?>"><i class="icon-edit"></i></button>
                                <button class="btn btn-danger btn-small tz_btn-remove hasTooltip"
                                        type="button" title="<?php echo JText::_($this -> text_prefix.'_REMOVE');?>"><i class="icon-trash"></i></button>
                            </div>
                            <input type="hidden" name="<?php echo $this -> getName($this -> fieldname);?>"
                                   value="<?php echo htmlspecialchars( $value);?>" <?php echo $class . $disabled . $onchange?>/>
                            <?php ?>
                        </td>
                    </tr>
                <?php } } }?>
                </tbody>
            </table>
            <?php
        }

        echo implode("\n",$form_control);

        $tbody_row_html[]   = '<td style="width: 3%; text-align: center;">'
            .'<div class="btn-group">'
            .'<button type="button" class="btn btn-small tz_btn-edit hasTooltip" title="'
            .JText::_('JACTION_EDIT').'"><i class="icon-edit"></i></button>'
            .'<button type="button" class="btn btn-danger btn-small tz_btn-remove hasTooltip" title="'
            .JText::_($this -> text_prefix.'_REMOVE').'">'
            .'<i class="icon-trash"></i></button>'
            .'</div>'
            .'<input type="hidden" name="' . $this -> getName($this -> fieldname) . '" value="{'.
            $this -> id.'}"' . $class . $disabled . $onchange . ' />'
            .'</td>';

        $config = JFactory::getConfig();

        $tbody_row_html = '<tr>'.implode('',$tbody_row_html).'</tr>';

        $doc -> addScriptDeclaration('
            (function($){
                $(document).ready(function(){
                    $("#'.$this -> id.'").mtzmultifield({
                        parentFieldId: "'.$this -> id.'",
                        fields: '.json_encode($tzform_control_id ).',
                        fieldName: "'.$this -> jsmTzMultifieldAddSlashes($this -> getName($this -> fieldname)).'",
                        tbodyHtml: "'.$this -> jsmTzMultifieldAddSlashes(trim($tbody_row_html)).'"
                        ,eMessages: {
                            invalidField: "'.JText::_($this -> text_prefix.'_INVALID_FIELD').'",
                            failOfField: "'.JText::_($this -> text_prefix.'_FAIL_OF_FIELD').'",
                            failToValue: "'.JText::_($this -> text_prefix.'_FAIL_VALUE').'",
                            removeItem: "'.JText::_($this -> text_prefix.'_DELETE_QUESTION').'"
                        }
                    });
                });
            })(jQuery);
        ');
        ?>
        </div>
        <?php
        $html[] = ob_get_contents();
        ob_end_clean();

        return implode("\n",$html);
    }
    protected function jsmTzMultifieldAddSlashes($s)
    {
        $o="";
        $l=strlen($s);
        for($i=0;$i<$l;$i++)
        {
            $c=$s[$i];
            switch($c)
            {
                case '<': $o.='\\x3C'; break;
                case '>': $o.='\\x3E'; break;
                case '\'': $o.='\\\''; break;
                case '\\': $o.='\\\\'; break;
                case '"':  $o.='\\"'; break;
                case "\n": $o.='\\n'; break;
                case "\r": $o.='\\r'; break;
                default:
                    $o.=$c;
            }
        }
        return $o;
    }

}