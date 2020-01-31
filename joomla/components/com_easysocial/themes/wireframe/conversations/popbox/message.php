<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2018 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<?php if ($conversation->getLastMessage()) { ?>
	<?php if ($conversation->getLastMessage()->created_by == $this->my->id) { ?>
	<i class="fa fa-share" data-es-provide="tooltip" data-placement="bottom" data-original-title="<?php echo JText::_( 'COM_EASYSOCIAL_CONVERSATION_YOU_HAVE_REPLIED_HERE' );?>"></i>
	<?php } ?>
	
	<?php echo ES::string()->parseBBCode($conversation->getLastMessage()->getIntro(60), array('escape' => false, 'emoticons' => true, 'links' => false)); ?>
<?php } ?>

