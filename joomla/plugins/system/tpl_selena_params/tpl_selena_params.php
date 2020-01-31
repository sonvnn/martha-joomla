<?php
/*------------------------------------------------------------------------

# TZ Portfolio Extension

# ------------------------------------------------------------------------

# author    DuongTVTemPlaza

# copyright Copyright (C) 2012 templaza.com. All Rights Reserved.

# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Websites: http://www.templaza.com

# Technical Support:  Forum - http://templaza.com/Forum

-------------------------------------------------------------------------*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class plgSystemTpl_Selena_Params extends JPlugin
{
    public function onContentPrepareForm($form, $data){
        $app    = JFactory::getApplication();
        if($app -> isAdmin()){
            $input  = $app -> input;

            $name = $form->getName();
            if($name == 'com_tz_portfolio_plus.article') {
                $language   = JFactory::getLanguage();
                $language -> load('plg_system_tpl_selena_params');
                JForm::addFormPath(__DIR__.'/forms');
                $form->loadFile('params', false);
            }
        }
        return true;
    }
}