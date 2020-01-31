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
if($params -> get('music_show_countsong', 1)):
    JLoader::import('content.music.libraries.music', COM_TZ_PORTFOLIO_PLUS_ADDON_PATH);

    $num_song = TZMusic::getNumberSong($item->id);
    ?>
    <div class="music-num-song">
        <?php
        if($num_song <= 1){
            echo JText::sprintf('PLG_CONTENT_MUSIC_SONG_1', $num_song);
        }else {
            echo JText::sprintf('PLG_CONTENT_MUSIC_SONG_N', $num_song);
        }
        ?>
    </div>
<?php endif;