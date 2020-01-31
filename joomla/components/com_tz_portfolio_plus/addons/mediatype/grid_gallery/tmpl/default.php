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

$grid_gallery   = null;
if($this -> item && isset($this -> item -> media)){
    $grid_gallery   = $this -> item -> media;
    if(isset($grid_gallery[$this -> _name])) {
        $grid_gallery   = $grid_gallery[$this -> _name];
    }else{
        $grid_gallery   = null;
    }
}
$grid_gallery_tmp       =   uniqid('grid_gallery_');
$addon                  =   TZ_Portfolio_PlusPluginHelper::getPlugin($this->_type,$this->_name);
$params                 =   $this->params;
$grid_gallery_file_type =   $params->get('grid_gallery_file_type','bmp,gif,jpg,jpeg,png');
$grid_gallery_file_type =   explode(',', $grid_gallery_file_type);
for ($i = 0 ; $i< count($grid_gallery_file_type); $i++) {
	$grid_gallery_file_type[$i]  =   '"'.trim($grid_gallery_file_type[$i]).'"';
}
$grid_gallery_file_type=   is_array($grid_gallery_file_type) ? implode(',', $grid_gallery_file_type) : '';
$article_id             =   $this->item->id ? $this->item->id : 0;
$japp = JFactory::getApplication();
$doc            = \JFactory::getDocument();
$doc->addStyleSheet(JUri::root().'components/com_tz_portfolio_plus/css/jquery.dm-uploader.min.css');
$doc->addStyleSheet(JUri::root().'components/com_tz_portfolio_plus/addons/mediatype/grid_gallery/admin/css/style.css');
$doc->addScript(JUri::root().'components/com_tz_portfolio_plus/js/jquery.dm-uploader.min.js');
if ($japp->isAdmin()) {
    $ajaxUrl    =   'index.php?option=com_tz_portfolio_plus&view=addon_datas&addon_id='.$addon->id.'&addon_task=gallery.ajax&folder='.$grid_gallery_tmp;
} else {
	$input      =   $japp->input;
	$id         =   $input  -> get('a_id',0);
	$editUrl    =   $id ? '&layout=edit&id='.$id : '';
	$ajaxUrl    =   'index.php?option=com_tz_portfolio_plus&view=addon&addon_task=gallery.ajax&addon_id='.$addon -> id.'&folder='.$grid_gallery_tmp.$editUrl;
}
$doc->addScriptDeclaration('
var GridGallery = window.GridGallery || {};
jQuery.extend(GridGallery, {
ajaxUrl                : "'.$ajaxUrl.'",
maxFileSize            : '.$params->get('grid_gallery_file_size',10).',
extFilter              : ['.$grid_gallery_file_type.']
});
');
$doc->addScript(JUri::root().'components/com_tz_portfolio_plus/addons/mediatype/grid_gallery/admin/js/style-ui.js');
$doc->addScript(JUri::root().'components/com_tz_portfolio_plus/addons/mediatype/grid_gallery/admin/js/grid_gallery_uploader.js');

?>

<div id="tp-add-on__<?php echo $this -> _type.'-'.$this -> _name; ?>" class="addon-tab">
    <div class="container-addon">
        <div class="row-addon">
            <div class="col-addon">

                <!-- Our markup, the important part here! -->
                <div id="grid_gallery_uploader" class="dm-uploader p-5">
                    <h3 class="mb-5 mt-5 text-muted"><?php echo JText::_('PLG_MEDIATYPE_GRID_GALLERY_DROP_DRAG'); ?></h3>

                    <div class="btn btn-primary btn-block mb-5">
                        <span><?php echo JText::_('PLG_MEDIATYPE_GRID_GALLERY_OPEN_FILE'); ?></span>
                        <input type="file" title='Click to add Files' />
                    </div>
                </div><!-- /uploader -->

            </div>
            <div class="col-addon">
                <div class="card h-100">
                    <div class="card-header">
                        <?php echo JText::_('PLG_MEDIATYPE_GRID_GALLERY_FILE_LIST'); ?>
                    </div>

                    <ul class="list-unstyled p-2 d-flex flex-column col" id="grid_gallery_files">
                        <?php if (isset($grid_gallery['data']) && count($grid_gallery['data'])) : ?>
                            <?php foreach ($grid_gallery['data'] as $image) : ?>
                            <li class="media" data-name="<?php echo $image; ?>" data-source="server">
                                <img class="mr-3 mb-2 preview-img" src="<?php echo JUri::root().'/images/tz_portfolio_plus/grid_gallery/'.$this -> item ->id.'/'.$image; ?>" alt="Generic placeholder image">
                                <div class="media-body mb-1">
                                    <p class="mb-2">
                                        <strong class="filename"><?php echo $image; ?></strong> - Status: <span class="status text-success">Available</span> - <em class="grid_featured"><input type="radio" name="grid_image_featured" class="grid_image_featured" value="<?php echo $image; ?>"<?php if ($grid_gallery['featured'] == $image) echo ' checked="checked"'; ?> /> <?php echo JText::_('JFEATURED'); ?></em> - <a href="#" class="delete_grid_image"><?php echo JText::_('JACTION_DELETE'); ?></a>
                                    </p>
                                    <div class="progress mb-2">
                                        <div class="progress-bar bg-primary bg-success"
                                             role="progressbar"
                                             style="width: 100%"
                                             aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                    <hr class="mt-1 mb-1" />
                                </div>
                                <input type="hidden" name="grid_gallery_image[]" class="grid_gallery_url" value="<?php echo $image; ?>" />
                                <input type="hidden" name="grid_gallery_source[]" class="grid_gallery_source" value="server" />
                            </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <li class="text-muted text-center empty"<?php if (is_array($grid_gallery) && count($grid_gallery)) echo ' style="display: none;"'; ?>><?php echo JText::_('PLG_MEDIATYPE_GRID_GALLERY_NO_FILE_UPLOADED'); ?></li>
                    </ul>
                </div>
            </div>
        </div><!-- /file list -->

        <div class="row-addon">
            <div class="col-addon">
                <div class="card h-100">
                    <div class="card-header">
                        <?php echo JText::_('PLG_MEDIATYPE_GRID_GALLERY_DEBUG_MESSAGES'); ?>
                    </div>

                    <ul class="list-group list-group-flush" id="grid_gallery_debug">
                        <li class="list-group-item text-muted empty"><?php echo JText::_('PLG_MEDIATYPE_GRID_GALLERY_LOADING_PLUGIN'); ?></li>
                    </ul>
                </div>
            </div>
        </div> <!-- /debug -->

    </div> <!-- /container -->
    <input type="hidden" name="grid_gallery_folder" id="grid_gallery_folder" value="<?php echo $grid_gallery_tmp; ?>" />
    <!-- File item template -->
    <script type="text/html" id="grid_gallery_files_template">
        <li class="media">
            <img class="mr-3 mb-2 preview-img" src="https://via.placeholder.com/150" alt="Generic placeholder image">
            <div class="media-body mb-1">
                <p class="mb-2">
                    <strong class="filename">%%filename%%</strong> - Status: <span class="text-muted">Waiting</span> - <em class="grid_featured"><input type="radio" name="grid_image_featured" class="grid_image_featured" value="" /> <?php echo JText::_('JFEATURED'); ?></em> - <a href="#" class="delete_grid_image"><?php echo JText::_('JACTION_DELETE'); ?></a>
                </p>
                <div class="progress mb-2">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                         role="progressbar"
                         style="width: 0%"
                         aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
                <hr class="mt-1 mb-1" />
            </div>
            <input type="hidden" name="grid_gallery_image[]" class="grid_gallery_url" value="" />
            <input type="hidden" name="grid_gallery_source[]" class="grid_gallery_source" value="client" />
        </li>
    </script>

    <!-- Debug item template -->
    <script type="text/html" id="grid_gallery_debug_template">
        <li class="list-group-item text-%%color%%"><strong>%%date%%</strong>: %%message%%</li>
    </script>
</div>