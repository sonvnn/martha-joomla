<?php 
defined('_JEXEC') or die( 'Restricted access' ); 

JHTML::_('behavior.tooltip');
?>

<div class="tz_membership_download <?php echo $this->escape($this->params->get('pageclass_sfx')) ?>">
<?php if($this->params->get('show_page_heading',1)) : ?>
<div class="tz-membership-heading">
	<?php echo $this->escape($this->params->get('page_title')) ?>
</div>
<?php endif; ?>
	<div class="row">
		<?php
		$db		= JFactory::getDBO();
		for ($i=0, $n=count($this->rows); $i < $n; $i++) {
			$row 	= $this->rows[$i];
			$link	= JRoute::_('index.php?option=com_tz_membership&view=download&catid='.$row->id.'&Itemid='.$this->Itemid);
			?>
            <div class="col-12 col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">
                            <a href="<?php echo $link; ?>" title="<?php echo $row->name; ?>"><?php echo $row->name; ?></a>
                        </h3>
                        <p class="card-text">
							<?php echo $row->description; ?>
                        </p>
                    </div>
                </div>
            </div>
			<?php
		}
		?>
    </div>

<?php echo JHTML::_('behavior.keepalive'); ?>
</div>