<?php
/*------------------------------------------------------------------------

# Image Gallery Add-on

# ------------------------------------------------------------------------

# Author:    DuongTVTemPlaza

# Copyright: Copyright (C) 2011-2019 TZ Portfolio.com. All Rights Reserved.

# @License - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Website: http://www.tzportfolio.com

# Technical Support:  Forum - https://www.tzportfolio.com/help/forum.html

# Family website: http://www.templaza.com

# Family Support: Forum - https://www.templaza.com/Forums.html

-------------------------------------------------------------------------*/

// no direct access
defined('_JEXEC') or die;


tzportfolioplusimport('controller.form');

class PlgTZ_Portfolio_PlusMediaTypeImage_GalleryControllerGallery extends TZ_Portfolio_Plus_AddOnControllerForm
{
    public function ajax()
    {
        $app        = JFactory::getApplication();
        $input      = $app -> input;
        $input -> set('addon_task', 'gallery.ajax');

        $adController   = TZ_Portfolio_Plus_AddOnControllerLegacy::getInstance('TZ_Portfolio_Plus_AddOn_Image_Gallery',
            array('base_path' => COM_TZ_PORTFOLIO_PLUS_ADDON_PATH.'/mediatype/image_gallery/admin'));
        $adController -> ajax();
        $app -> close();
    }

}