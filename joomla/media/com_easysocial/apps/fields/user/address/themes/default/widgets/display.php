<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2016 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<i class="es-side-widget__icon fa fa-map-marker-alt t-text--muted t-lg-mr--md"></i> 
<span class="t-text--muted">
	<?php if ($value->state) { ?>
	<?php echo $value->state;?>, 
	<?php } ?>
	<?php if ($value->country) { ?>
		<?php echo $value->country_code;?>
	<?php } ?>
</span>