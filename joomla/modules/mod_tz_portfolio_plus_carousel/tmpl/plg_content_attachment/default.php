<?php
/*------------------------------------------------------------------------
# plg_extravote - ExtraVote Plugin
# ------------------------------------------------------------------------
# author    Joomla!Vargas
# copyright Copyright (C) 2010 joomla.vargas.co.cr. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://joomla.vargas.co.cr
# Technical Support:  Forum - http://joomla.vargas.co.cr/forum
-------------------------------------------------------------------------*/

// No direct access
defined('_JEXEC') or die;

if($list && $params -> get('show_attachment', 1)):
    $doc    = JFactory::getDocument();
    $doc -> addStyleSheet(TZ_Portfolio_PlusUri::root(true).'/addons/content/attachment/css/style.css');

    if($params -> get('attachment_enable_awesome_font', 1)){
        $doc -> addStyleSheet(TZ_Portfolio_PlusUri::root(true).'/addons/content/attachment/css/font-awesome.min.css');
    }
?>
<ul class="list-striped attachments">
    <?php foreach($list as $attach){
        $item   = $attach -> value;
        ?>
        <li><a href="<?php echo $attach -> link;?>"
               title="<?php echo $item -> title_attrib;?>"><?php
                if($params -> get('attachment_show_icon', 1) && isset($item -> icon) && $item -> icon){
                    echo $item -> icon.' ';
                }
                echo $item -> title;
                ?></a>
            <?php if($params -> get('attachment_show_hit', 1)){?>
                <span class="hit"><?php echo ($item -> hits < 2)?JText::sprintf('PLG_CONTENT_ATTACHMENT_DOWNLOAD_1', $item -> hits)
                        :JText::sprintf('PLG_CONTENT_ATTACHMENT_DOWNLOAD_N', $item -> hits)?></span>
            <?php }?>
        </li>
    <?php }?>
</ul>
<?php
endif;