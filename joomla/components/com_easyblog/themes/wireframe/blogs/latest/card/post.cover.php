<?php
/**
* @package		EasyBlog
* @copyright	Copyright (C) 2010 - 2019 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<div class="eb-card__hd">
	<div class="embed-responsive embed-responsive-16by9">
		<?php if ($post->posttype == 'video') { ?>
			<?php foreach ($post->videos as $video) { ?>
				<?php echo $video->html;?>
			<?php } ?>
		<?php } else { ?>
		<div class="embed-responsive-item" style="
			background-image: url('<?php echo $post->getImage($this->config->get('cover_size', 'large'), true, true, $this->config->get('cover_firstimage', 0));?>');
			background-position: center;
		 ">
		</div>
		<?php } ?>
	</div>
</div>