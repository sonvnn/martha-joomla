<?php
/*------------------------------------------------------------------------

# Grid Gallery Addon

# ------------------------------------------------------------------------

# author    Sonny

# copyright Copyright (C) 2019 templaza.com. All Rights Reserved.

# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Websites: http://www.tzportfolio.com

# Technical Support:  Forum - https://www.tzportfolio.com/help/forum.html

-------------------------------------------------------------------------*/

// No direct access.
defined('_JEXEC') or die;

class PlgTZ_Portfolio_PlusMediaTypeGrid_GalleryViewDate extends JViewLegacy{

    protected $item             = null;
    protected $params           = null;
    protected $grid_gallery    = null;
    protected $head             = false;

    public function display($tpl = null){
	    $state          = $this -> get('State');
	    $params         = $state -> get('params');
	    $this -> params = $params;
	    $item 			= $this -> get('Item');

	    if($item){
		    if($media = $item -> media){
			    if(isset($media -> grid_gallery)){
				    $this -> grid_gallery  = clone($media -> grid_gallery);
			    }
		    }
		    $this -> item   = $item;
	    }

	    parent::display($tpl);
    }
}