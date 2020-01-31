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

class PlgTZ_Portfolio_PlusMediaTypeGrid_GalleryViewPortfolio extends JViewLegacy{

    protected $item         = null;
    protected $params       = null;
    protected $grid_gallery = null;

    public function display($tpl = null){
        $state          = $this -> get('State');
        $params         = $state -> get('params');
        $this -> params = $params;
        $item           = $this -> item;

        if(!$item){
            $item = $this -> get('Item');
        }

        if($item){
            if($media = $item -> media){
                if(isset($media -> grid_gallery)){

                    if($params -> get('mt_grid_gallery_show_feed_image',1)){
                        $grid_gallery  = clone($media -> grid_gallery);
                        if(isset($grid_gallery -> data) && !empty($grid_gallery -> data)
                            && count($grid_gallery -> data)){
	                        if ($grid_gallery->featured) {
		                        $image  =   $grid_gallery->featured;
	                        } else {
		                        $image  =   $grid_gallery->data[0];
	                        }
	                        jimport('joomla.filesystem.file');
	                        $image_size =   $params->get('mt_grid_gallery_feed_size','o');
	                        if ($image_size != 'o') {
		                        $image  =   'images/tz_portfolio_plus/grid_gallery/'.$item->id.'/resize/'
			                        . JFile::stripExt($image)
			                        . '_' . $image_size . '.' . JFile::getExt($image);
	                        } else {
		                        $image  =   'images/tz_portfolio_plus/grid_gallery/'.$item -> id.'/'.$image;
	                        }

                            $title = $this->escape($item->title);
                            $title = html_entity_decode($title, ENT_COMPAT, 'UTF-8');

                            $link = JRoute::_(TZ_Portfolio_PlusHelperRoute::getArticleRoute($item -> slug, $item -> catid, true, -1));

	                        echo '<a href="'.$link.'"><img src="'.$image.'" alt="'.$title.'"/></a>';
                        }
                    }
                }
            }
        }
    }
}