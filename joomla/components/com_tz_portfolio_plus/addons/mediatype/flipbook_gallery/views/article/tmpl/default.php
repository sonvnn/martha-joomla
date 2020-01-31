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

$params = $this->params;
if ($params->get('mt_flipbook_gallery_show',1)) {
	if($item   = $this -> item) {
		if (isset($this->flipbook_gallery) && $gallery = $this->flipbook_gallery) {
            if (isset($gallery->data) && count($gallery->data)) {
	            $doc            =   JFactory::getDocument();
                $gallerytype    =   $params->get('mt_flipbook_gallery_type','masonry');
                if ($params->get('mt_flipbook_gallery_show_page_selector',1)) {
	                echo '<div id="flipbook-gallery-menu"></div>';
                }

	            echo '<div class="tz_portfolio_plus_flipbook_gallery">';
                if ($params->get('mt_flipbook_gallery_show_cover_page', 1)) :
	            ?>
                <div class="gallery-listing"title="<?php echo $this -> item -> title; ?>">
                    <div class="gallery-start">
                        <h2><?php echo $this -> item -> title; ?></h2>
                    </div>
                </div>
	            <?php
                endif;
                for ($i = 0; $i<count($gallery -> data); $i++) {
                    $image      =   $gallery->data[$i];
	                jimport('joomla.filesystem.file');
	                $image_size =   $params->get('mt_flipbook_gallery_size','o');
	                if ($image_size != 'o') {
		                $thumb  =   'images/tz_portfolio_plus/flipbook_gallery/'.$item->id.'/resize/'
			                . JFile::stripExt($image)
			                . '_' . $image_size . '.' . JFile::getExt($image);
	                } else {
		                $thumb  =   'images/tz_portfolio_plus/flipbook_gallery/'.$item -> id.'/'.$image;
	                }

	                ?>
                    <div class="gallery-listing"<?php if ($gallery->title[$i]) echo ' title="'.$gallery->title[$i].'"'; ?>>
                        <div class="gallery-image">
                            <a class="gallery-zoom" data-thumb="<?php echo JUri::root().$thumb; ?>" data-id="grid<?php echo $i; ?>" href="<?php echo 'images/tz_portfolio_plus/flipbook_gallery/'.$item -> id.'/'.$image; ?>"><i class="tps tp-search"></i></a>
                            <img src="<?php echo $thumb; ?>" />
                        </div>
                    </div>
	                <?php
                }
                echo '</div>';
            }
		}
	}
}
