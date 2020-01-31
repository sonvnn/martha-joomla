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

$params = $this->params;
if ($params->get('mt_grid_gallery_show',1)) {
	if($item   = $this -> item) {
		if (isset($this->grid_gallery) && $gallery = $this->grid_gallery) {
            if (isset($gallery->data) && count($gallery->data)) {
                $gallerytype    =   $params->get('mt_grid_gallery_type','masonry');
	            echo '<div class="tz_portfolio_plus_grid_gallery '.$gallerytype.'-container">';
                for ($i = 0; $i<count($gallery -> data); $i++) {
                    $image      =   $gallery->data[$i];
	                jimport('joomla.filesystem.file');
	                $image_size =   $params->get('mt_grid_gallery_size','o');
	                if ($image_size != 'o') {
		                $thumb  =   'images/tz_portfolio_plus/grid_gallery/'.$item->id.'/resize/'
			                . JFile::stripExt($image)
			                . '_' . $image_size . '.' . JFile::getExt($image);
	                } else {
		                $thumb  =   'images/tz_portfolio_plus/grid_gallery/'.$item -> id.'/'.$image;
	                }

	                ?>
                    <div class="gallery-listing element" data-date data-hits data-title>
                        <div class="gallery-inner">
                            <div class="gallery-image">
                                <a class="gallery-title" data-thumb="<?php echo JUri::root().$thumb; ?>" data-id="grid<?php echo $i; ?>" href="<?php echo 'images/tz_portfolio_plus/grid_gallery/'.$item -> id.'/'.$image; ?>"><i class="tps tp-search"></i></a>
                                <img src="<?php echo $thumb; ?>" />
                            </div>
                        </div>
                    </div>
	                <?php
                }
                echo '</div>';
            }
		}
	}
}
