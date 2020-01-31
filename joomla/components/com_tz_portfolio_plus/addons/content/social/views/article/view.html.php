<?php
/*------------------------------------------------------------------------

# TZ Portfolio Plus Extension

# ------------------------------------------------------------------------

# author    DuongTVTemPlaza

# copyright Copyright (C) 2015 templaza.com. All Rights Reserved.

# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Websites: http://www.templaza.com

# Technical Support:  Forum - http://templaza.com/Forum

-------------------------------------------------------------------------*/

// No direct access.
defined('_JEXEC') or die;

class PlgTZ_Portfolio_PlusContentSocialViewArticle extends JViewLegacy{
    protected $item     = null;
    protected $params   = null;
    protected $audio    = null;
    protected $head     = false;

    public function display($tpl = null){
        $this -> item   = $this -> get('Item');
        $state          = $this -> get('State');
        $params         = $state -> get('params');
        $this -> params = $params;

        if(!$this -> head) {
            $document = JFactory::getDocument();
            $document->addStyleSheet(TZ_Portfolio_PlusUri::root(true) . '/addons/content/social/css/social.css');
            $this -> head   = true;
        }

        parent::display($tpl);
    }
}