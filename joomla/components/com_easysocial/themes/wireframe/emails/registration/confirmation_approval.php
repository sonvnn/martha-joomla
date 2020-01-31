<?php
/**
* @package      EasySocial
* @copyright    Copyright (C) 2010 - 2018 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<tr>
	<td bgcolor="#ffffff">
		<table role="presentation" aria-hidden="true" cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
				<td style="padding: 24px 24px 10px; font-family: sans-serif; font-size: 13px; line-height: 20px; color: #999999; text-align: left;">
					<p style="margin: 0;"><?php echo JText::_('COM_EASYSOCIAL_EMAILS_REGISTRATION_SUBHEADING'); ?></p>
				</td>
			</tr>
			<tr>
				<td style="padding: 0px 24px; text-align: left;">
					<h1 style="margin: 0; font-family: sans-serif; font-size: 22px; line-height: 27px; color: #666666; font-weight: normal;"><?php echo JText::_('COM_EASYSOCIAL_EMAILS_REGISTRATION_HEADING'); ?></h1>
				</td>
			</tr>
		</table>
	</td>
</tr>

<tr>
	<td dir="ltr" bgcolor="#ffffff" height="100%" valign="top" width="100%" style="padding: 20px 24px 24px; font-family: sans-serif; font-size: 14px; color: #555555; text-align: center;">
		
		<!--[if mso]>
		<table role="presentation" aria-hidden="true" border="0" cellspacing="0" cellpadding="0" width="660" style="width: 660px;">
		<tr>
		<td valign="top" width="660" style="width: 660px;">
		<![endif]-->
		<table role="presentation" aria-hidden="true" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:660px;">
			<tr>
				<td bgcolor="#f6f9fb" align="center" style="padding: 24px;">
					<table role="presentation" aria-hidden="true" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:660px;">
						<tr>
							<td valign="top" width="100%">
								<p style="color: #999999;line-height:1.5;text-align:left;margin: 0;padding: 0 0 24px;" class="stack-column">
									<?php echo JText::sprintf('COM_ES_EMAILS_HI_USER', $username); ?>
								</p>
							</td>
						</tr>
						<tr>
							<td valign="top" width="100%">
								<p style="color: #999999;line-height:1.5;text-align:left;margin: 0;padding: 0 0 24px;" class="stack-column">
									<?php echo JText::sprintf('COM_ES_EMAILS_REGISTRATION_CONFIRMATION_EMAIL_ACCOUNT', $site); ?>
								</p>
							</td>
						</tr>
						<tr>
							<td style="padding: 5px 0;">
								<a href="<?php echo $activation;?>" style="color:#fff;text-decoration:none;border-radius: 2px;padding: 10px;background-color: #428bca;border-color: #357ebd;"><?php echo JText::_('COM_ES_EMAILS_CONFIRMATION_EMAIL_ACCOUNT_BUTTON');?></a>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<!--[if mso]>
		</td>
		</tr>
		</table>
		<![endif]-->
	</td>
</tr>