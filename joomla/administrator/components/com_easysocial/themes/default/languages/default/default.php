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
<form name="adminForm" id="adminForm" class="esForm" action="index.php" method="post" data-table-grid>
	<div class="app-filter-bar">
		<div class="app-filter-bar__cell">
			<?php echo $this->html('filter.search' , $search); ?>
		</div>
		
		<div class="app-filter-bar__cell app-filter-bar__cell--divider-left">
			<div class="app-filter-bar__filter-wrap">
				<select class="o-form-control" name="published" id="filterType" data-table-grid-filter>
					<option value=""<?php echo $published == '' ? ' selected="selected"' : '';?>><?php echo JText::_('Select Install State'); ?></option>
					<option value="installed"<?php echo $published == 'installed' ? ' selected="selected"' : '';?>><?php echo JText::_('Installed'); ?></option>
					<option value="notinstalled"<?php echo $published == 'notinstalled' ? ' selected="selected"' : '';?>><?php echo JText::_('Not Installed'); ?></option>
				</select>
			</div>
		</div>
		
		<div class="app-filter-bar__cell app-filter-bar__cell--divider-left"></div>

		<div class="app-filter-bar__cell app-filter-bar__cell--divider-left app-filter-bar__cell--last t-text--center">
			<div class="app-filter-bar__filter-wrap">
				<?php echo $this->html('filter.limit' , $limit); ?>
			</div>
		</div>
	</div>

	<div class="panel-table">
		<table class="app-table table" data-mailer-list>
			<thead>
				<tr>
				<th width="1%">
					<input type="checkbox" name="toggle" class="checkAll" data-table-grid-checkall />
				</th>
				<th>
					<?php echo $this->html( 'grid.sort' , 'title' , JText::_( 'COM_EASYSOCIAL_TABLE_COLUMN_TITLE' ) , $ordering , $direction ); ?>
				</th>
				<th width="10%" class="center">
					<?php echo $this->html( 'grid.sort' , 'locale' , JText::_( 'COM_EASYSOCIAL_TABLE_COLUMN_LOCALE' ) , $ordering , $direction ); ?>
				</th>
				<th width="15%" class="center">
					<?php echo $this->html( 'grid.sort' , 'state' , JText::_( 'COM_EASYSOCIAL_TABLE_COLUMN_STATE' ) , $ordering , $direction ); ?>
				</th>
				<th width="10%" class="center">
					<?php echo $this->html( 'grid.sort' , 'progress' , JText::_( 'COM_EASYSOCIAL_TABLE_COLUMN_PROGRESS' ) , $ordering , $direction ); ?>
				</th>
				<th width="10%" class="center">
					<?php echo $this->html( 'grid.sort' , 'updated' , JText::_( 'COM_EASYSOCIAL_TABLE_COLUMN_LAST_UPDATED' ) , $ordering , $direction ); ?>
				</th>
				<th width="5%" class="center">
					<?php echo $this->html( 'grid.sort' , 'id' , JText::_( 'COM_EASYSOCIAL_TABLE_COLUMN_ID' ) , $ordering , $direction ); ?>
				</th>
				</tr>
			</thead>
			<tbody>
				<?php if( $languages ){ ?>

					<?php $i = 0; ?>
					<?php foreach( $languages as $language ){ ?>
					<tr data-mailer-item data-id="<?php echo $language->id;?>">
						<td class="center">
							<?php echo $this->html( 'grid.id' , $i , $language->id ); ?>

						</td>
						<td>
							<b><?php echo $language->title; ?></b>
						</td>
						<td class="center">
							<?php echo $language->locale;?>
						</td>
						<td class="center">
							<?php if ($language->state == SOCIAL_LANGUAGES_INSTALLED) { ?>
							<span class="t-text--success">
								<b><?php echo JText::_('COM_EASYSOCIAL_LANGUAGES_INSTALLED'); ?></b>
							</span>
							<?php } ?>

							<?php if ($language->state == SOCIAL_LANGUAGES_NEEDS_UPDATING) { ?>
							<span class="t-text--danger">
								<b><?php echo JText::_('COM_EASYSOCIAL_LANGUAGES_REQUIRES_UPDATING'); ?></b>
							</span>
								
							<?php } ?>

							<?php if ($language->state == SOCIAL_LANGUAGES_NOT_INSTALLED) { ?>
								<?php echo JText::_('COM_EASYSOCIAL_LANGUAGES_NOT_INSTALLED'); ?>
							<?php } ?>
						</td>
						<td class="center">
							<?php echo !$language->progress ? 0 : $language->progress;?> %
						</td>
						<td class="center">
							<?php echo $language->updated; ?>
						</td>
						<td class="center">
							<?php echo $language->id; ?>
						</td>
					</tr>
					<?php $i++; ?>
					<?php } ?>

				<?php } else { ?>
				<?php } ?>
			</tbody>

			<tfoot>
				<tr>
					<td colspan="8">
						<div class="footer-pagination">
							<?php echo $pagination->getListFooter(); ?>
						</div>
					</td>
				</tr>
			</tfoot>
		</table>
	</div>

	<?php echo JHTML::_('form.token'); ?>
	<input type="hidden" name="ordering" value="<?php echo $ordering;?>" data-table-grid-ordering />
	<input type="hidden" name="direction" value="<?php echo $direction;?>" data-table-grid-direction />
	<input type="hidden" name="boxchecked" value="0" data-table-grid-box-checked />
	<input type="hidden" name="task" value="" data-table-grid-task />
	<input type="hidden" name="option" value="com_easysocial" />
	<input type="hidden" name="view" value="languages" />
	<input type="hidden" name="controller" value="languages" />
</form>
