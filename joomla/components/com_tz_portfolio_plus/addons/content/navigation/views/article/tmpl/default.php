<?php
/*------------------------------------------------------------------------

# Navigation Addon

# ------------------------------------------------------------------------

# Author:    DuongTVTemPlaza

# Copyright: Copyright (C) 2016 tzportfolio.com. All Rights Reserved.

# @License - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Website: http://www.tzportfolio.com

# Technical Support:  Forum - http://tzportfolio.com/forum

# Family website: http://www.templaza.com

-------------------------------------------------------------------------*/

// No direct access
defined('_JEXEC') or die;

if($row = $this -> row):
    $this -> document -> addStyleSheet(TZ_Portfolio_PlusUri::root(true).'/addons/content/navigation/css/style.css');
?>

<ul class="pager music-pager">
<?php if ($row->prev) :
    $direction = $lang->isRtl() ? 'right' : 'left'; ?>
    <li class="previous">
        <a href="<?php echo $row->prev; ?>" rel="prev">
            <?php echo '<span class="glyphicon glyphicon-arrow-' . $direction . '"></span> ' . $row->prev_label; ?>
        </a>
    </li>
<?php endif; ?>
<?php if ($row->next) :
    $direction = $lang->isRtl() ? 'left' : 'right'; ?>
    <li class="next">
        <a href="<?php echo $row->next; ?>" rel="next">
            <?php echo $row->next_label . ' <span class="glyphicon glyphicon-arrow-' . $direction . '"></span>'; ?>
        </a>
    </li>
<?php endif; ?>
</ul>
<?php endif;