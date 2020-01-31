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

JLoader::import('com_tz_portfolio_plus.controllers.addon_datas',JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components');

class TZ_Portfolio_Plus_Addon_Image_GalleryControllerGallery extends TZ_Portfolio_PlusControllerAddon_Datas{

    public function ajax()
    {
	    header('Content-Type: application/json');
	    try {
		    $japp   = JFactory::getApplication();
            $input  = $japp -> input;

            if(!$japp->isClient('administrator') && $input -> get('view') != 'addon'){
                throw new RuntimeException('You are not authorized!');
            }

		    // Get the uploaded file information.
		    // Do not change the filter type 'raw'. We need this to let files containing PHP code to upload. See JInputFiles::get.
		    $userfile   =   $input->files->get('file', null, 'raw');
		    $folder     =   $input->get('folder','');
		    if (
			    !isset($userfile['error']) ||
			    is_array($userfile['error'])
		    ) {
			    throw new RuntimeException('Invalid parameters.');
		    }

		    switch ($userfile['error']) {
			    case UPLOAD_ERR_OK:
				    break;
			    case UPLOAD_ERR_NO_FILE:
				    throw new RuntimeException('No file sent.');
			    case UPLOAD_ERR_INI_SIZE:
			    case UPLOAD_ERR_FORM_SIZE:
				    throw new RuntimeException('Exceeded filesize limit.');
			    default:
				    throw new RuntimeException('Unknown errors.');
		    }

		    // Build the appropriate paths.
		    jimport('joomla.filesystem.file');
		    jimport('joomla.filesystem.folder');
		    $filename           =   \JApplicationHelper::stringURLSafe(JFile::stripExt($userfile['name'])).'.'.JFile::getExt($userfile['name']);

		    $config             =   JFactory::getConfig();
		    $tmp_dest           =   $config->get('tmp_path') . '/' .$folder . '/' . $filename;
		    $tmp_resize_folder  =   $config->get('tmp_path') . '/' .$folder . '/resize';
		    $tmp_src            =   $userfile['tmp_name'];
		    if (!JFile::upload($tmp_src, $tmp_dest)) {
			    throw new RuntimeException('Failed to move uploaded file.');
		    }

		    // Resize image
		    if (JFolder::create($tmp_resize_folder)) {
			    $addon      =   TZ_Portfolio_PlusPluginHelper::getPlugin('mediatype','image_gallery');
			    $params     =   new JRegistry($addon->params);
			    if ($params && $image_size = $params->get('image_gallery_size')) {
				    if($image_size && !is_array($image_size) && preg_match_all('/(\{.*?\})/',$image_size,$match)) {
					    $image_size = $match[1];
				    }
				    $image_gallery   =   new JImage();

				    $image_gallery -> destroy();
				    $image_gallery -> loadFile($tmp_dest);

				    foreach ($image_size as $_size) {
					    $size = json_decode($_size);

					    $newPath = $tmp_resize_folder . DIRECTORY_SEPARATOR
						    . JFile::stripExt($filename)
						    . '_' . $size->image_name_prefix . '.' . JFile::getExt($filename);

					    // Create new ratio from new with of image size param
					    $imageProperties   = $image_gallery->getImageFileProperties($tmp_dest);
					    $newH              = ($imageProperties->height * $size->width) / ($imageProperties->width);
					    $newImage          = $image_gallery->resize($size->width, $newH);

					    // Before upload image to file must delete original file
					    if (JFile::exists($newPath)) {
						    // Execute delete image
						    JFile::delete($newPath);
					    }

					    // Generate image to file
					    if (!$newImage->toFile($newPath, $imageProperties->type)) {
						    throw new RuntimeException('Failed to resize image!');
					    }

				    }
			    } else {
				    throw new RuntimeException('Failed to read Addon parameter.');
			    }
		    } else {
			    throw new RuntimeException('Failed to create resize folder!.');
		    }

		    // All good, send the response
		    echo json_encode([
			    'status' => 'ok',
			    'name'  => $filename
		    ]);

	    } catch (RuntimeException $e) {
		    // Something went wrong, send the err message as JSON
		    http_response_code(400);

		    echo json_encode([
			    'status' => 'error',
			    'message' => $e->getMessage()
		    ]);
	    }
	    die();
    }

	public function delete() {
        header('Content-Type: application/json');
        try {
            $japp = JFactory::getApplication();
            if(!$japp->isAdmin()){
                throw new RuntimeException('You are not authorized!');
            }
            // Get the uploaded file information.
            $input    = $japp->input;

            // Do not change the filter type 'raw'. We need this to let files containing PHP code to upload. See JInputFiles::get.
            $file       =   $input->get('file', '');

            $source     =   $input->get('data_source', '');
            if (!$file || !$source) {
                throw new RuntimeException('Invalid parameters.');
            }
            if ($source == 'client') {
                $folder             =   $input->get('tmp_folder','');
                // Build the appropriate paths.
                $config             =   JFactory::getConfig();
                $folder             =   $config->get('tmp_path') . '/' .$folder ;
            } else {
                $article_id         =   $input->get('article_id', 0);
                if (!$article_id) {
                    throw new RuntimeException('Invalid parameters.');
                }
                $folder             =   JPATH_ROOT.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'tz_portfolio_plus'.DIRECTORY_SEPARATOR.'image_gallery'.DIRECTORY_SEPARATOR.$article_id;
            }

            jimport('joomla.filesystem.file');
            jimport('joomla.filesystem.folder');
            if (!JFile::delete( $folder . '/' . $file)) {
                throw new RuntimeException('Failed to delete file.');
            }

            // Resize image
            $addon      =   TZ_Portfolio_PlusPluginHelper::getPlugin('mediatype','image_gallery');
            $params     =   new JRegistry($addon->params);
            if ($params && $image_size = $params->get('image_gallery_size')) {
                if($image_size && !is_array($image_size) && preg_match_all('/(\{.*?\})/',$image_size,$match)) {
                    $image_size = $match[1];
                }

                foreach ($image_size as $_size) {
                    $size = json_decode($_size);

                    if (!JFile::delete($folder. DIRECTORY_SEPARATOR. 'resize' . DIRECTORY_SEPARATOR
                        . JFile::stripExt($file)
                        . '_' . $size->image_name_prefix . '.' . JFile::getExt($file))){
                        throw new RuntimeException('Failed to delete resize file.');
                    }
                }
            } else {
                throw new RuntimeException('Failed to read Addon parameter.');
            }

            // All good, send the response
            echo json_encode([
                'status' => 'ok',
                'name'  => $file
            ]);

        } catch (RuntimeException $e) {
            // Something went wrong, send the err message as JSON
            http_response_code(400);

            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
        die();
    }
}