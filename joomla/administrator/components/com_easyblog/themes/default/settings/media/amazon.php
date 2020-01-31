<?php
/**
* @package		EasyBlog
* @copyright	Copyright (C) 2010 - 2017 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

$amazonClass = array('standard' => 'COM_EB_MEDIA_STORAGE_AMAZON_CLASS_STANDARD_STORAGE', 'reduced' => 'COM_EB_MEDIA_STORAGE_AMAZON_CLASS_REDUCED_REDUNDANCY');

$regionTypes = array('us' => 'COM_EB_MEDIA_STORAGE_AMAZON_US_EAST_NORTHERN_VIRGINIA', 
			'us-east-2' => 'COM_EB_MEDIA_STORAGE_AMAZON_US_EAST_OHIO',
			'us-west-2' => 'COM_EB_MEDIA_STORAGE_AMAZON_US_WEST_OREGON',
			'us-west-1' => 'COM_EB_MEDIA_STORAGE_AMAZON_US_WEST_NORTHERN_CALIFORNIA',
			'eu-central-1' => 'COM_EB_MEDIA_STORAGE_AMAZON_EU_FRANKFURT',
			'eu-west-1' => 'COM_EB_MEDIA_STORAGE_AMAZON_EU_IRELAND',
			'eu-west-2' => 'COM_EB_MEDIA_STORAGE_AMAZON_EU_LONDON',
			'ap-southeast-1' => 'COM_EB_MEDIA_STORAGE_AMAZON_ASIA_PACIFIC_SINGAPORE',
			'ap-southeast-2' => 'COM_EB_MEDIA_STORAGE_AMAZON_ASIA_PACIFIC_SYDNEY',
			'ap-northeast-1' => 'COM_EB_MEDIA_STORAGE_AMAZON_ASIA_PACIFIC_TOKYO',
			'sa-east-1' => 'COM_EB_MEDIA_STORAGE_AMAZON_SOUTH_AMERICA_SAU_PAULO',
			'ca-central-1' => 'COM_EB_MEDIA_STORAGE_AMAZON_CANADA_CENTRAL'
		);
?>
<div class="row">
	<div class="col-lg-6">

		<div class="panel">
			<?php echo $this->html('panel.heading', 'COM_EB_MEDIA_STORAGE_AMAZON'); ?>

			<div class="panel-body">

				<?php echo $this->html('settings.toggle', 'main_amazon_enabled', 'COM_EB_MEDIA_STORAGE_AMAZON_ENABLED'); ?>

				<?php echo $this->html('settings.text', 'main_amazon_access', 'COM_EB_MEDIA_STORAGE_AMAZON_ACCESS_KEY'); ?>

				<?php echo $this->html('settings.text', 'main_amazon_secret', 'COM_EB_MEDIA_STORAGE_AMAZON_SECRET_KEY'); ?>

				<?php echo $this->html('settings.text', 'main_amazon_bucket', 'COM_EB_MEDIA_STORAGE_AMAZON_BUCKET_PATH'); ?>

				<?php echo $this->html('settings.toggle', 'main_amazon_ssl', 'COM_EB_MEDIA_STORAGE_AMAZON_SSL'); ?>

				<?php echo $this->html('settings.dropdown', 'main_amazon_region', 'COM_EB_MEDIA_STORAGE_AMAZON_REGION', $regionTypes); ?>

				<?php echo $this->html('settings.dropdown', 'main_amazon_class', 'COM_EB_MEDIA_STORAGE_AMAZON_CLASS', $amazonClass); ?>

			</div>
		</div>

	</div>

	<div class="col-lg-6">
	</div>
</div>
