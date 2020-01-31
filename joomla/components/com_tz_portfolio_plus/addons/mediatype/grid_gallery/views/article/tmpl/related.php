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

$item           = $this -> item;
$grid_gallery   = $this->grid_gallery;
$params         = $this -> params;

if($item && $grid_gallery && isset($grid_gallery -> data) && count($grid_gallery -> data)):

    if($params -> get('mt_grid_gallery_related_show_image',1)):
	    if ($grid_gallery->featured) {
		    $image  =   $grid_gallery->featured;
	    } else {
		    $image  =   $grid_gallery->data[0];
	    }
	    $image_size =   $params->get('mt_grid_gallery_related_size','o');
	    jimport('joomla.filesystem.file');
	    if ($image_size != 'o') {
		    $image  =   'images/tz_portfolio_plus/grid_gallery/'.$item->id.'/resize/'
			    . JFile::stripExt($image)
			    . '_' . $image_size . '.' . JFile::getExt($image);
	    } else {
		    $image  =   'images/tz_portfolio_plus/grid_gallery/'.$item -> id.'/'.$image;
	    }
        ?>
        <a href="<?php echo $this -> item -> link; ?>">
            <img src="<?php echo $image; ?>"
                 alt="<?php echo $this->item->title; ?>"
                 title="<?php echo $this->item->title; ?>"
                 itemprop="thumbnailUrl"/>
        </a>
    <?php
    endif;
endif;