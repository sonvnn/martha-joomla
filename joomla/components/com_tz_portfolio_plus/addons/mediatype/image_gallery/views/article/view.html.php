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

// No direct access.
defined('_JEXEC') or die;

class PlgTZ_Portfolio_PlusMediaTypeImage_GalleryViewArticle extends JViewLegacy{

    protected $item             = null;
    protected $params           = null;
    protected $image_gallery    = null;
    protected $head             = false;

    public function display($tpl = null){
        $state          = $this -> get('State');
        $params         = $state -> get('params');
        $this -> params = $params;
		$item 			= $this -> get('Item');

        if($item){
            if($media = $item -> media){
                if(isset($media -> image_gallery)){
					$doc = JFactory::getDocument();

                    if(!$this -> head) {
	                    $doc -> addStyleSheet('components/com_tz_portfolio_plus/css/all.min.css');
	                    $doc -> addStyleSheet('components/com_tz_portfolio_plus/css/jquery.fancybox.min.css');
	                    $doc -> addScript('components/com_tz_portfolio_plus/js/jquery.fancybox.min.js');
                        $doc->addStyleSheet(TZ_Portfolio_PlusUri::base(true) . '/addons/mediatype/image_gallery/css/flexslider.css');
                        $doc->addStyleSheet(TZ_Portfolio_PlusUri::base(true) . '/addons/mediatype/image_gallery/css/style.css');
                        $doc->addScript(TZ_Portfolio_PlusUri::base(true) . '/addons/mediatype/image_gallery/js/jquery.flexslider-min.js');
	                    $lightboxopt    =   $params->get('image_gallery_lightbox_option',['zoom', 'slideShow', 'fullScreen', 'thumbs', 'close']);
	                    if (is_array($lightboxopt)) {
		                    for ($i = 0 ; $i< count($lightboxopt); $i++) {
			                    $lightboxopt[$i]  =   '"'.$lightboxopt[$i].'"';
		                    }
	                    }

	                    $lightboxopt=   is_array($lightboxopt) ? implode(',', $lightboxopt) : '';

	                    $doc -> addScriptDeclaration('var image_gallery_lightbox_buttons = ['.$lightboxopt.'];');
	                    $doc -> addScript(TZ_Portfolio_PlusUri::base(true) . '/addons/mediatype/image_gallery/js/lightbox.js');
                        $this -> head   = true;
                    }

                    $image_gallery  = clone($media -> image_gallery);
                    if(isset($image_gallery -> url) && !empty($image_gallery -> url)
                        && count($image_gallery -> url)){
                        $image_gallery -> thumb_url = array();
                        foreach($image_gallery -> url as $i => &$url) {
	                        $image_url_ext  = JFile::getExt($url);
	                        if($thumb_size = $params -> get('mt_img_gallery_thumb_size','o')){
		                        if (preg_match('/media\/tz_portfolio_plus\/article\/cache/i', $url)) {
			                        $thumb_url      = str_replace('.' . $image_url_ext, '_' . $thumb_size . '.'
				                        . $image_url_ext, $url);
		                        } else {
		                        	if ($thumb_size!='o') {
				                        $thumb_url      =   'images/tz_portfolio_plus/image_gallery/'.$item->id.'/resize/'
					                        . JFile::stripExt($url)
					                        . '_' . $thumb_size . '.' . JFile::getExt($url);
			                        } else {
				                        $thumb_url      =   'images/tz_portfolio_plus/image_gallery/'.$item->id.'/'.$url;
			                        }
		                        }

		                        $thumb_url      = JURI::root() . $thumb_url;
		                        $image_gallery -> thumb_url[$i] = $thumb_url;
		                        if($i == 0){
			                        if($this -> getLayout() != 'related') {
				                        $doc->addCustomTag('<meta property="og:image" content="' . $image_gallery->thumb_url[$i] . '"/>');
				                        if($author = $item -> author_info){
					                        $doc -> setMetaData('twitter:image',$image_gallery->thumb_url[$i]);
				                        }
			                        }
		                        }
	                        }
	                        if($size = $params -> get('mt_img_gallery_size','o')){
		                        if (preg_match('/media\/tz_portfolio_plus\/article\/cache/i', $url)) {
			                        $image_url      = str_replace('.' . $image_url_ext, '_' . $size . '.'
				                        . $image_url_ext, $url);
			                        $url            = JURI::root() . $image_url;
		                        } else {
		                        	if ($size != 'o') {
				                        $url    =   JURI::root() .'images/tz_portfolio_plus/image_gallery/'.$item->id.'/resize/'
					                        . JFile::stripExt($url)
					                        . '_' . $size . '.' . JFile::getExt($url);
			                        } else {
				                        $url    =   JURI::root() .'images/tz_portfolio_plus/image_gallery/'.$item->id.'/'.$url;
			                        }
		                        }
	                        }
                        }
                    }
                    $this -> image_gallery  = $image_gallery;
                }
            }
            $this -> item   = $item;
        }

        parent::display($tpl);
    }
}