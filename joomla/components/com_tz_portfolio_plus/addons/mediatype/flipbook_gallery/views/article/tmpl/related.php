<?php
/*------------------------------------------------------------------------

# Flipbook Gallery Addon

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
$flipbook_gallery   = $this->flipbook_gallery;
$params         = $this -> params;

if($item && $flipbook_gallery && isset($flipbook_gallery -> data) && count($flipbook_gallery -> data)):

    if($params -> get('mt_flipbook_gallery_related_show_image',1)):
	    if ($flipbook_gallery->featured) {
		    $image  =   $flipbook_gallery->featured;
	    } else {
		    $image  =   $flipbook_gallery->data[0];
	    }
	    $image_size =   $params->get('mt_flipbook_gallery_related_size','o');
	    jimport('joomla.filesystem.file');
	    if ($image_size != 'o') {
		    $image  =   'images/tz_portfolio_plus/flipbook_gallery/'.$item->id.'/resize/'
			    . JFile::stripExt($image)
			    . '_' . $image_size . '.' . JFile::getExt($image);
	    } else {
		    $image  =   'images/tz_portfolio_plus/flipbook_gallery/'.$item -> id.'/'.$image;
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