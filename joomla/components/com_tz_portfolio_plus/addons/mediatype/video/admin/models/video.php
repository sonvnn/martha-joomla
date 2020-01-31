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

use Joomla\Filesystem\File;
jimport('joomla.filesystem.file');

class PlgTZ_Portfolio_PlusMediaTypeModelVideo extends TZ_Portfolio_PlusPluginModelAdmin
{

    // This function to upload and save data with data saved in com
    public function save($data){

        $app    = JFactory::getApplication();
        $input  = $app -> input;

        // Get params
        $params     = $this -> getState('params');

        // Get data from form
        $video_data     = $input -> get('jform', array(), 'array');
        $video_data     = $video_data['media'][$this -> getName()];
        $files          = $input -> files -> get('jform');
        $video_files    = array();

        $mime_types     = $params -> get('video_thumbnail_mime_type','image/jpeg,image/gif,image/png,image/bmp');
        $mime_types     = explode(',',$mime_types);
        $file_types     = $params -> get('video_thumbnail_file_type','bmp,gif,jpg,jpeg,png');
        $file_types     = explode(',',$file_types);
        $file_sizes     = $params -> get('video_thumbnail_file_size',0.3);
        $file_sizes     = $file_sizes * 1024 * 1024;

        $video          = null;
        if($data -> media && !empty($data -> media)) {
            $video  = new JRegistry;
            $video -> loadString($data -> media);
            $video  = $video -> get($this -> getName());
        }

        // Set data when save as copy article
        if($input -> getCmd('task') == 'save2copy' && $input -> getInt('id')){
            if((isset($video_data['thumbnail_remove']) && $video_data['thumbnail_remove'])){
                $video_data['thumbnail_remove']   = null;
                $video_data['thumbnail']          = '';
            }
            if(!isset($video_data['thumbnail_server'])
                || (isset($video_data['thumbnail_server']) && empty($video_data['thumbnail_server']))){
                if(isset($video_data['thumbnail']) && $video_data['thumbnail']) {
                    $ext        = \JFile::getExt($video_data['thumbnail']);
                    $path_copy  = str_replace('.'.$ext,'_o.'.$ext, $video_data['thumbnail']);
                    if(\JFile::exists(JPATH_ROOT.DIRECTORY_SEPARATOR.$path_copy)) {
                        $video_data['thumbnail_server']   = $path_copy;
                        $video_data['thumbnail']          = '';
                    }
                }
            }
        }


        // Check video code if it doesn't have data remove thumbnail and reset data
        if($video_data && count($video_data)){
            if($video_data['type'] == 'embed'){
                if(!isset($video_data['embed_code'])
                    || (isset($video_data['embed_code']) && empty($video_data['embed_code']))){

                    // Delete thumbnail if it created
                    if(isset($video_data['thumbnail']) && $video_data['thumbnail']){
                        // Delete original thumbnail
                        $thumbnail_url  = str_replace('.'.\JFile::getExt($video_data['thumbnail']),'_o.'
                            .\JFile::getExt($video_data['thumbnail']), $video_data['thumbnail']);
                        File::delete(JPATH_ROOT . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR,
                                $thumbnail_url));

                        // Delete thumbnail which them resized
                        if ($params && $thumb_size = $params->get('video_thumbnail_size')) {
                            $thumb_size = $this->prepareImageSize($thumb_size);

                            foreach ($thumb_size as $_size) {
                                $size = json_decode($_size);
                                $thumbnail_url  = str_replace('.'.\JFile::getExt($video_data['thumbnail']),'_'
                                    .$size -> image_name_prefix.'.'
                                    .\JFile::getExt($video_data['thumbnail']), $video_data['thumbnail']);
                                File::delete(JPATH_ROOT . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR,
                                        $thumbnail_url));
                            }
                        }
                    }

                    foreach($video_data as $key => $vdata){
                        $video_data[$key]   = '';
                    }

                    unset($video_data['thumbnail_server']);
                    if(isset($video_data['thumbnail_remove'])){
                        unset($video_data['thumbnail_remove']);
                    }

                    $this -> __save($data,$video_data);
                    return;
                }else{
                    if(isset($video_data['thumbnail_remove'])){
                        $video_data['thumbnail']    = '';
                        unset($video_data['thumbnail_server']);
                        unset($video_data['thumbnail_remove']);

                        $this -> __save($data,$video_data);
                        return;
                    }
                }

            }elseif($video_data['type'] == 'youtube' or $video_data['type'] == 'vimeo'){
                if(!isset($video_data['code'])
                    || (isset($video_data['code']) && empty($video_data['code']))){

                    // Delete thumbnails if they were created
                    if(isset($video_data['thumbnail']) && $video_data['thumbnail']){
                        // Delete original thumbnail
                        $thumbnail_url  = str_replace('.'.\JFile::getExt($video_data['thumbnail']),'_o.'
                            .\JFile::getExt($video_data['thumbnail']), $video_data['thumbnail']);
                        File::delete(JPATH_ROOT . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR,
                                $thumbnail_url));

                        // Delete thumbnail which them resized
                        if ($params && $thumb_size = $params->get('video_thumbnail_size')) {
                            $thumb_size = $this->prepareImageSize($thumb_size);

                            foreach ($thumb_size as $_size) {
                                $size = json_decode($_size);
                                $thumbnail_url  = str_replace('.'.\JFile::getExt($video_data['thumbnail']),'_'
                                    .$size -> image_name_prefix.'.'
                                    .\JFile::getExt($video_data['thumbnail']), $video_data['thumbnail']);
                                File::delete(JPATH_ROOT . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR,
                                        $thumbnail_url));
                            }
                        }
                    }

                    foreach($video_data as $key => $vdata){
                        $video_data[$key]   = '';
                    }

                    unset($video_data['thumbnail_server']);
                    if(isset($video_data['thumbnail_remove'])){
                        unset($video_data['thumbnail_remove']);
                    }

                    $this -> __save($data,$video_data);
                    return;
                }
            }
        }

        // Upload thumbnail and create data for thumbnail to store database
        $original   = null;
        $filename   = null;
        $thumbType  = null;
        $_fileName  = ((!$data -> alias)?uniqid() .'tz_portfolio_plus_'.time():$data -> alias)
            .'-'.$data -> id.'-v';

        // Get thumbnail data from form
        if($files  && isset($files['media']['video'])){
            $files  = $files['media']['video'];
            if(isset($files['thumbnail_client']['name'])) {
                $video_files['name']        = $files['thumbnail_client']['name'];
                $video_files['type']        = $files['thumbnail_client']['type'];
                $video_files['tmp_name']    = $files['thumbnail_client']['tmp_name'];
                $video_files['error']       = $files['thumbnail_client']['error'];
                $video_files['size']        = $files['thumbnail_client']['size'];
            }
        }


        // Upload video's thumbnail
        if (count($video_files) && !empty($video_files['tmp_name'])) {
            $thumbType = \JFile::getExt($video_files['name']);
            //-- Check thumbnail information --//
            // Check MIME Type
            if (!in_array($video_files['type'], $mime_types)) {
                $app->enqueueMessage(JText::_('PLG_MEDIATYPE_VIDEO_THUMBNAIL_ERROR_WARNINVALID_MIME'), 'notice');
                return false;
            }

            // Check file type
            if (!in_array($thumbType, $file_types)) {
                $app->enqueueMessage(JText::_('PLG_MEDIATYPE_VIDEO_THUMBNAIL_ERROR_WARNFILETYPE'), 'notice');
                return false;
            }

            // Check file size
            if ($video_files['size'] > $file_sizes) {
                $app->enqueueMessage(JText::_('PLG_MEDIATYPE_VIDEO_THUMBNAIL_ERROR_WARNFILETOOLARGE'), 'notice');
                return false;
            }
            //-- End check thumbnail information --//

            // Before upload image to file must delete original file
            if ($video && isset($video->thumbnail) && !empty($video->thumbnail)) {
                $video_url = $video->thumbnail;
                $video_type = \JFile::getExt($video_url);
                $video_url = str_replace('.' . $video_type, '_o'
                    . '.' . $video_type, $video_url);
                File::delete(JPATH_ROOT . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR,
                        $video_url));
            }

            $original = COM_TZ_PORTFOLIO_PLUS_MEDIA_ARTICLE_ROOT . DIRECTORY_SEPARATOR . $_fileName . '_o.' . $thumbType;

            // Upload original thumbnail
            if (File::upload($video_files['tmp_name'], $original, false, true)) {
                $filename = COM_TZ_PORTFOLIO_PLUS_MEDIA_ARTICLE_BASE . '/' . $_fileName . '.' . $thumbType;
            }

            if($input -> getCmd('task') == 'save2copy' && $input -> getInt('id')){
                $video_data['thumbnail_server']   = null;
            }

        } elseif ($video_data['thumbnail_server'] && !empty($video_data['thumbnail_server'])) {
            $thumbType = \JFile::getExt($video_data['thumbnail_server']);
            $video_thumb = new JImage(JPATH_ROOT . DIRECTORY_SEPARATOR . $video_data['thumbnail_server']);
            $video_property = $video_thumb->getImageFileProperties($video_thumb->getPath());

            //-- Check thumbnail information --//
            // Check MIME Type
            if (!in_array($video_property->mime, $mime_types)) {
                $app->enqueueMessage(JText::_('PLG_MEDIATYPE_VIDEO_THUMBNAIL_ERROR_WARNINVALID_MIME'), 'notice');
                return false;
            }

            // Check file type
            if (!in_array($thumbType, $file_types)) {
                $app->enqueueMessage(JText::_('PLG_MEDIATYPE_VIDEO_THUMBNAIL_ERROR_WARNFILETYPE'), 'notice');
                return false;
            }

            // Check file size
            if ($video_property->filesize > $file_sizes) {
                $app->enqueueMessage(JText::_('PLG_MEDIATYPE_VIDEO_THUMBNAIL_ERROR_WARNFILETOOLARGE'), 'notice');
                return false;
            }
            //-- End check thumbnail information --//

            // Before upload image to file must delete original file
            if ($video && isset($video->thumbnail) && !empty($video->thumbnail)) {
                $video_url = $video->thumbnail;
                $video_type = \JFile::getExt($video_url);
                $video_url = str_replace('.' . $video_type, '_o'
                    . '.' . $video_type, $video_url);
                File::delete(JPATH_ROOT . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR,
                        $video_url));
            }

            $original = COM_TZ_PORTFOLIO_PLUS_MEDIA_ARTICLE_ROOT . DIRECTORY_SEPARATOR . $_fileName . '_o.' . $thumbType;

            // Upload original thumbnail
            if (File::copy($video_thumb->getPath(), $original)) {
                $filename = COM_TZ_PORTFOLIO_PLUS_MEDIA_ARTICLE_BASE . '/' . $_fileName . '.' . $thumbType;
            }
        }else{
            if(!isset($video_data['thumbnail']) || (isset($video_data['thumbnail']) && empty($video_data['thumbnail']))
                || (isset($video_data['thumbnail']) && !empty($video_data['thumbnail']) &&
                    isset($video_data['thumbnail_remove']) && !empty($video_data['thumbnail_remove']))) {
                // Upload thumbnail from youtube or vimeo server

                JLoader::register('Services_Yadis_PlainHTTPFetcher', COM_TZ_PORTFOLIO_PLUS_ADDON_PATH
                    .'/mediatype/video/admin/libraries/readfile.php');
                JLoader::register('Services_Yadis_HTTPFetcher', COM_TZ_PORTFOLIO_PLUS_ADDON_PATH
                    .'/mediatype/video/admin/libraries/http_fetcher.php');

                // Upload thumbnail from youtube or vimeo server
                $file = new Services_Yadis_PlainHTTPFetcher();
                $thumbUrl = null;

                // Create thumbnail url with code from youtube or vimeo server
                if ($video_data['type'] == 'youtube') {
                    $thumbUrl = 'http://img.youtube.com/vi/' . $video_data['code'] . '/maxresdefault.jpg';
                    $thumb = $file->get($thumbUrl);

                    if ($thumb->status == '404') {
                        $thumbUrl = 'http://img.youtube.com/vi/' . $video_data['code'] . '/mqdefault.jpg';
                    }

                    $thumbType = 'jpg';
                } elseif ($video_data['type'] == 'vimeo') {
                    $thumbUrl = 'http://vimeo.com/api/v2/video/' . $video_data['code'] . '.php';

                    $vimeo = $file->get($thumbUrl);
                    $vimeo = unserialize($vimeo->body);
                    $thumbUrl = $vimeo[0]['thumbnail_large'];

                    if ($ipos = stripos($thumbUrl, '_')) {
                        $img_w = (int)substr($thumbUrl, $ipos + 1, strlen($thumbUrl));
                        if ($img_w < $vimeo[0]['width']) {
                            $thumbType = \JFile::getExt($thumbUrl);
                            $thumbUrl = substr($thumbUrl, 0, $ipos) . '_' . $vimeo[0]['width']
                                . '.' . $thumbType;
                        }
                    }
                }

                if ($thumbUrl) {
                    $thumb = $file->get($thumbUrl);

                    // Check thumbnail exists
                    if ($thumb->status == '404') {
                        $app->enqueueMessage(JText::_('PLG_MEDIATYPE_VIDEO_THUMBNAIL_ERROR_WARNINVALID'), 'notice');
                        return false;
                    }

                    //Upload thumbnail
                    if ($thumb) {

                        //-- Check thumbnail information --//
                        // Check MIME Type
                        if (isset($thumb->headers) && isset($thumb->headers['Content-Type'])
                            && !empty($thumb->headers['Content-Type'])
                            && !in_array($thumb->headers['Content-Type'], $mime_types)
                        ) {
                            $app->enqueueMessage(JText::_('PLG_MEDIATYPE_VIDEO_THUMBNAIL_ERROR_WARNINVALID_MIME'), 'notice');
                            return false;
                        }

                        // Check file type
                        if (!in_array($thumbType, $file_types)) {
                            $app->enqueueMessage(JText::_('PLG_MEDIATYPE_VIDEO_THUMBNAIL_ERROR_WARNFILETYPE'), 'notice');
                            return false;
                        }

                        // Check file size
                        if (isset($thumb->headers) && isset($thumb->headers['Content-Type'])
                            && !empty($thumb->headers['Content-Type'])
                            && $thumb->headers['Content-Length'] > $file_sizes
                        ) {
                            $app->enqueueMessage(JText::_('PLG_MEDIATYPE_VIDEO_THUMBNAIL_ERROR_WARNFILETOOLARGE'), 'notice');
                            return false;
                        }
                        //-- End check thumbnail information --//

                        // Before upload image to file must delete original file
                        if ($video && isset($video->thumbnail) && !empty($video->thumbnail)) {
                            $video_url = $video->thumbnail;
                            $video_type = \JFile::getExt($video_url);
                            $video_url = str_replace('.' . $video_type, '_o'
                                . '.' . $video_type, $video_url);
                            File::delete(JPATH_ROOT . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR,
                                    $video_url));
                        }

                        $original = COM_TZ_PORTFOLIO_PLUS_MEDIA_ARTICLE_ROOT . DIRECTORY_SEPARATOR . $_fileName . '_o.' . $thumbType;

                        // Upload original thumbnail
                        if (File::write($original, $thumb->body)) {
                            $filename = COM_TZ_PORTFOLIO_PLUS_MEDIA_ARTICLE_BASE . '/' . $_fileName . '.' . $thumbType;
                        }
                    }
                }

                unset($video_data['thumbnail_remove']);
            }
        }
        // Upload image with resize
        if($filename && !empty($filename) && $original && !empty($original)) {
            if ($params && $thumb_size = $params->get('video_thumbnail_size')) {

                $thumb_size = $this -> prepareImageSize($thumb_size);

                if (\JFile::exists($original)) {
                    $thumbnail = new JImage($original);
                    foreach ($thumb_size as $_size) {
                        $size = json_decode($_size);

                        // Before upload image to file must delete original file
                        if($video && isset($video -> thumbnail) && !empty($video -> thumbnail)){
                            $video_url  = $video -> thumbnail;
                            $video_type = \JFile::getExt($video_url);
                            $video_url  = str_replace('.'.$video_type,'_'.$size -> image_name_prefix
                                .'.'.$video_type,$video_url);
                            File::delete(JPATH_ROOT.DIRECTORY_SEPARATOR.str_replace('/',DIRECTORY_SEPARATOR,
                                    $video_url));
                        }

                        // Create new ratio from new with of image size param
                        $thumbProperties    = $thumbnail->getImageFileProperties($original);
                        $newH               = ($thumbProperties->height * $size->width) / ($thumbProperties->width);
                        $newImage           = $thumbnail->resize($size->width, $newH);

                        $newPath = COM_TZ_PORTFOLIO_PLUS_MEDIA_ARTICLE_ROOT . DIRECTORY_SEPARATOR
                            . $_fileName . '_' . $size->image_name_prefix . '.' . $thumbType;

                        // Generate image to file
                        $newImage->toFile($newPath, $thumbProperties->type);

                    }
                }
            }
            $video_data['thumbnail'] = $filename;
        }

        unset($video_data['thumbnail_server']);

        $this -> __save($data,$video_data);

    }

    public function delete(&$article){
        if($article){
            if(is_object($article)){
                if($article -> media && !empty($article -> media)) {
                    $media  = new JRegistry;
                    $media -> loadString($article -> media);

                    $media  = $media -> get($this -> getName());
                    $params = $this -> getState('params');

                    if($media){
                        if(isset($media -> thumbnail) && !empty($media -> thumbnail)){
                            // Delete original image
                            $image_url  = str_replace('.'.\JFile::getExt($media->thumbnail),
                                '_o.'.\JFile::getExt($media->thumbnail),$media->thumbnail);
                            File::delete(JPATH_ROOT.DIRECTORY_SEPARATOR.str_replace('/',DIRECTORY_SEPARATOR,
                                    $image_url));
                        }

                        // Delete image with some size
                        if($image_size = $params -> get('video_thumbnail_size', array())){

                            $image_size = $this -> prepareImageSize($image_size);

                            if(is_array($image_size) && count($image_size)){
                                foreach($image_size as $_size){
                                    $size           = json_decode($_size);

                                    // Delete image
                                    if(isset($media -> thumbnail) && !empty($media -> thumbnail)) {
                                        // Create file name and execute delete image
                                        $image_url = str_replace('.' . \JFile::getExt($media->thumbnail), '_' . $size->image_name_prefix
                                            . '.' . \JFile::getExt($media->thumbnail), $media->thumbnail);
                                        File::delete(JPATH_ROOT . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR,
                                                $image_url));
                                    }
                                }
                            }
                        }
                    }
                }

            }
        }
    }
}