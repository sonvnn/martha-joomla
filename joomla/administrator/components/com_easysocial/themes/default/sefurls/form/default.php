<?php
/**
* @package      EasySocial
* @copyright    Copyright (C) 2010 - 2017 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>

<form name="adminForm" id="adminForm" method="post" enctype="multipart/form-data">
	<div class="row">
		<div class="col-md-6">
			<div class="panel">
				<?php echo $this->html('panel.heading', 'COM_ES_SEFURLS_FORM_GENERAL'); ?>

				<div class="panel-body">

					<div class="form-group">
						<?php echo $this->html('panel.label', 'COM_ES_SEFURLS_EDIT_RAWURL'); ?>

						<div class="col-md-7">
							<input type="text" class="o-form-control" value="<?php echo $url->rawurl;?>" disabled="true" />
						</div>
					</div>


					<div class="form-group">

						<?php echo $this->html('panel.label', 'COM_ES_SEFURLS_EDIT_SEFURL'); ?>

						<div class="col-md-7">
							<input type="text" class="o-form-control " value="<?php echo $url->sefurl;?>" name="sefurl" id="sefurl" />
						</div>
					</div>

				</div>
			</div>
		</div>

		<div class="col-md-6">
		</div>

	</div>

	<?php echo $this->html('form.action', 'sefurls'); ?>
	<input type="hidden" name="id" value="<?php echo $url->id; ?>" />
</form>
