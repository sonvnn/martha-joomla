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

// No direct access
defined('_JEXEC') or die;

class PlgTZ_Portfolio_PlusMediaTypeImage_GalleryLibrary
{

    public static function getMaxKey($urls = array())
    {
        $max = 0;
        if (count($urls)) {
            foreach ($urls as $key => $value) {
                if (strrpos($value, '-') != false) {
                    $number = (int)substr($value, strrpos($value, '-') + 1, strlen($value));
                    if ($max < $number) {
                        $max = $number;
                    }
                }
            }
        }
        return $max;
    }
}