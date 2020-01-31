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

// No direct access
defined('_JEXEC') or die;

JLoader::import('flipbook_gallery',__DIR__.DIRECTORY_SEPARATOR.'libraries');

class PlgTZ_Portfolio_PlusMediaTypeFlipbook_Gallery extends TZ_Portfolio_PlusPlugin
{
    protected $autoloadLanguage = true;

    // Display html for views in front-end.
    public function onContentDisplayMediaType($context, &$article, $params, $page = 0, $layout = null){
        if($article){
            if($media = $article -> media){
                if(isset($media -> flipbook_gallery)){
                    $flipbook_gallery  = clone($media -> flipbook_gallery);
                    $this -> setVariable('flipbook_gallery', $flipbook_gallery);
                }
            }
            $this -> setVariable('item', $article);
            return parent::onContentDisplayMediaType($context, $article, $params, $page, $layout);
        }
    }
}