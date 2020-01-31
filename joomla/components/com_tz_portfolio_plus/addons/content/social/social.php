<?php
/*------------------------------------------------------------------------

# TZ Portfolio Plus Extension

# ------------------------------------------------------------------------

# Author:    DuongTVTemPlaza

# Copyright: Copyright (C) 2015 templaza.com. All Rights Reserved.

# @License - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Websites: http://www.templaza.com

# Technical Support:  Forum - http://templaza.com/Forum

-------------------------------------------------------------------------*/

// No direct access
defined('_JEXEC') or die;

class PlgTZ_Portfolio_PlusContentSocial extends TZ_Portfolio_PlusPlugin
{
    protected $autoloadLanguage = true;

    public function onAddContentType(){
        $type = new stdClass();
        $lang = JFactory::getLanguage();
        $lang_key = 'PLG_' . $this->_type . '_' . $this->_name . '_TITLE';
        $lang_key = strtoupper($lang_key);

        if ($lang->hasKey($lang_key)) {
            $type->text = JText::_($lang_key);
        } else {
            $type->text = $this->_name;
        }

        $type->value = $this->_name;

        return $type;
    }

    public function onBeforeDisplayAdditionInfo($context, &$article, $params, $page = 0, $layout = 'default'){

    }

    public function onAfterDisplayAdditionInfo($context, &$article, $params, $page = 0, $layout = 'default'){

    }

    public function onContentDisplayListView($context, &$article, $params, $page = 0, $layout = 'default'){

    }

    public function onContentDisplayArticleView($context, &$article, $params, $page = 0, $layout = null){
        list($extension, $vName)   = explode('.', $context);

        $item   = $article;

        if($extension == 'module' || $extension == 'modules'){
            if($path = $this -> getModuleLayout($this -> _type, $this -> _name, $extension, $vName, $layout)){
                // Display html
                ob_start();
                include $path;
                $html = ob_get_contents();
                ob_end_clean();
                $html = trim($html);
                return $html;
            }
        }else {
            if($html = $this -> _getViewHtml($context,$article, $params, $layout)){
                return $html;
            }
        }
    }
}
