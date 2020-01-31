<?php
/*------------------------------------------------------------------------

# TZ Portfolio Plus Extension

# ------------------------------------------------------------------------

# Author:    DuongTVTemPlaza

# Copyright: Copyright (C) 2011-2018 TZ Portfolio.com. All Rights Reserved.

# @License - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Website: http://www.tzportfolio.com

# Technical Support:  Forum - https://www.tzportfolio.com/help/forum.html

# Family website: http://www.templaza.com

# Family Support: Forum - https://www.templaza.com/Forums.html

-------------------------------------------------------------------------*/

// no direct access
defined('_JEXEC') or die;

class PlgTZ_Portfolio_PlusUserProfileViewPortfolio extends JViewLegacy{

    protected $params;
    protected $authorAbout;

    public function display($tpl = null)
    {
        $state                  = $this -> get('State');
        $addon                  = $state -> get($this -> getName().'.addon');
        $this -> params         = $addon -> params;
        $this -> authorAbout    = $this -> get('AuthorAbout');
        return parent::display($tpl); // TODO: Change the autogenerated stub
    }
}