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

$flipbook_gallery   = null;
if($this -> item && isset($this -> item -> media)){
    $flipbook_gallery   = $this -> item -> media;
    if(isset($flipbook_gallery[$this -> _name])) {
        $flipbook_gallery   = $flipbook_gallery[$this -> _name];
    }else{
        $flipbook_gallery   = null;
    }
}
$flipbook_gallery_tmp       =   uniqid('flipbook_gallery_');
$addon                  =   TZ_Portfolio_PlusPluginHelper::getPlugin($this->_type,$this->_name);
$params                 =   $this->params;
$flipbook_gallery_file_type =   $params->get('flipbook_gallery_file_type','bmp,gif,jpg,jpeg,png');
$flipbook_gallery_file_type =   explode(',', $flipbook_gallery_file_type);
for ($i = 0 ; $i< count($flipbook_gallery_file_type); $i++) {
	$flipbook_gallery_file_type[$i]  =   '"'.trim($flipbook_gallery_file_type[$i]).'"';
}
$flipbook_gallery_file_type=   is_array($flipbook_gallery_file_type) ? implode(',', $flipbook_gallery_file_type) : '';
$article_id             =   $this->item->id ? $this->item->id : 0;
$japp = JFactory::getApplication();
$doc            = \JFactory::getDocument();
$doc->addStyleSheet(JUri::root().'components/com_tz_portfolio_plus/css/jquery.dm-uploader.min.css');
$doc->addStyleSheet(JUri::root().'components/com_tz_portfolio_plus/addons/mediatype/flipbook_gallery/admin/css/style.css');
$doc->addScript(JUri::root().'components/com_tz_portfolio_plus/js/jquery.dm-uploader.min.js');
if ($japp->isAdmin()) {
    $ajaxUrl    =   'index.php?option=com_tz_portfolio_plus&view=addon_datas&addon_id='.$addon->id.'&addon_task=gallery.ajax&folder='.$flipbook_gallery_tmp;
} else {
	$input      =   $japp->input;
	$id         =   $input  -> get('a_id',0);
	$editUrl    =   $id ? '&layout=edit&id='.$id : '';
	$ajaxUrl    =   'index.php?option=com_tz_portfolio_plus&view=addon&addon_task=gallery.ajax&addon_id='.$addon -> id.'&folder='.$flipbook_gallery_tmp.$editUrl;
}
$doc->addScriptDeclaration('
var FlipbookGallery = window.FlipbookGallery || {};
jQuery.extend(FlipbookGallery, {
ajaxUrl                : "'.$ajaxUrl.'",
maxFileSize            : '.$params->get('flipbook_gallery_file_size',10).',
extFilter              : ['.$flipbook_gallery_file_type.']
});
');
$doc->addScript(JUri::root().'components/com_tz_portfolio_plus/addons/mediatype/flipbook_gallery/admin/js/style-ui.js');
$doc->addScript(JUri::root().'components/com_tz_portfolio_plus/addons/mediatype/flipbook_gallery/admin/js/flipbook_gallery_uploader.js');

?>

<div id="tp-add-on__<?php echo $this -> _type.'-'.$this -> _name; ?>" class="addon-tab">
    <div class="container-addon">
        <div class="row-addon">
            <div class="col-addon">

                <!-- Our markup, the important part here! -->
                <div id="flipbook_gallery_uploader" class="dm-uploader p-5">
                    <h3 class="mb-5 mt-5 text-muted"><?php echo JText::_('PLG_MEDIATYPE_FLIPBOOK_GALLERY_DROP_DRAG'); ?></h3>

                    <div class="btn btn-primary btn-block mb-5">
                        <span><?php echo JText::_('PLG_MEDIATYPE_FLIPBOOK_GALLERY_OPEN_FILE'); ?></span>
                        <input type="file" title='Click to add Files' />
                    </div>
                </div><!-- /uploader -->

            </div>
            <div class="col-addon">
                <div class="card h-100">
                    <div class="card-header">
                        <?php echo JText::_('PLG_MEDIATYPE_FLIPBOOK_GALLERY_FILE_LIST'); ?>
                    </div>

                    <ul class="list-unstyled p-2 d-flex flex-column col" id="flipbook_gallery_files">
                        <?php if (isset($flipbook_gallery['data']) && count($flipbook_gallery['data'])) : ?>
                            <?php for ($i=0; $i<count($flipbook_gallery['data']); $i++) :
                                $flipbook_image     =   $flipbook_gallery['data'][$i];
                                $flipbook_title     =   $flipbook_gallery['title'][$i];
                                ?>
                            <li class="media" data-name="<?php echo $flipbook_image; ?>" data-source="server">
                                <img class="mr-3 mb-2 preview-img" src="<?php echo JUri::root().'/images/tz_portfolio_plus/flipbook_gallery/'.$this -> item ->id.'/'.$flipbook_image; ?>" alt="Generic placeholder image">
                                <div class="media-body mb-1">
                                    <p class="mb-2">
                                        <strong class="filename"><?php echo $flipbook_image; ?></strong> - Status: <span class="status text-success">Available</span> - <em class="grid_featured"><input type="radio" name="flipbook_image_featured" class="flipbook_image_featured" value="<?php echo $flipbook_image; ?>"<?php if ($flipbook_gallery['featured'] == $flipbook_image) echo ' checked="checked"'; ?> /> <?php echo JText::_('JFEATURED'); ?></em> - <a href="#" class="delete_flipbook_image"><?php echo JText::_('JACTION_DELETE'); ?></a>
                                    </p>
                                    <p class="mb-2">
                                        <input type="text" class="inputbox" name="flipbook_gallery_image_title[]" placeholder="Title..." value="<?php echo $flipbook_title; ?>" />
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
                                <input type="hidden" name="flipbook_gallery_image[]" class="flipbook_gallery_url" value="<?php echo $flipbook_image; ?>" />
                                <input type="hidden" name="flipbook_gallery_source[]" class="flipbook_gallery_source" value="server" />
                            </li>
                            <?php endfor; ?>
                        <?php endif; ?>
                        <li class="text-muted text-center empty"<?php if (is_array($flipbook_gallery) && count($flipbook_gallery)) echo ' style="display: none;"'; ?>><?php echo JText::_('PLG_MEDIATYPE_FLIPBOOK_GALLERY_NO_FILE_UPLOADED'); ?></li>
                    </ul>
                </div>
            </div>
        </div><!-- /file list -->

        <div class="row-addon">
            <div class="col-addon">
                <div class="card h-100">
                    <div class="card-header">
                        <?php echo JText::_('PLG_MEDIATYPE_FLIPBOOK_GALLERY_DEBUG_MESSAGES'); ?>
                    </div>

                    <ul class="list-group list-group-flush" id="flipbook_gallery_debug">
                        <li class="list-group-item text-muted empty"><?php echo JText::_('PLG_MEDIATYPE_FLIPBOOK_GALLERY_LOADING_PLUGIN'); ?></li>
                    </ul>
                </div>
            </div>
        </div> <!-- /debug -->

    </div> <!-- /container -->
    <input type="hidden" name="flipbook_gallery_folder" id="flipbook_gallery_folder" value="<?php echo $flipbook_gallery_tmp; ?>" />
    <!-- File item template -->
    <script type="text/html" id="flipbook_gallery_files_template">
        <li class="media">
            <img class="mr-3 mb-2 preview-img" src="https://via.placeholder.com/150" alt="Generic placeholder image">
            <div class="media-body mb-1">
                <p class="mb-2">
                    <strong class="filename">%%filename%%</strong> - Status: <span class="text-muted">Waiting</span> - <em class="grid_featured"><input type="radio" name="flipbook_image_featured" class="flipbook_image_featured" value="" /> <?php echo JText::_('JFEATURED'); ?></em> - <a href="#" class="delete_flipbook_image"><?php echo JText::_('JACTION_DELETE'); ?></a>
                </p>
                <p class="mb-2">
                    <input type="text" class="inputbox" name="flipbook_gallery_image_title[]" placeholder="Title..." />
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
            <input type="hidden" name="flipbook_gallery_image[]" class="flipbook_gallery_url" value="" />
            <input type="hidden" name="flipbook_gallery_source[]" class="flipbook_gallery_source" value="client" />
        </li>
    </script>

    <!-- Debug item template -->
    <script type="text/html" id="flipbook_gallery_debug_template">
        <li class="list-group-item text-%%color%%"><strong>%%date%%</strong>: %%message%%</li>
    </script>
</div>