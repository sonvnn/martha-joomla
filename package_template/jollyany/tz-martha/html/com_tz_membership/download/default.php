<?php 
defined('_JEXEC') or die( 'Restricted access' ); 

JHTML::_('behavior.tooltip');
?>
<script language="javascript" type="text/javascript">

	function submitform(pressbutton){
		if (pressbutton) {
			document.adminForm.task.value=pressbutton;
		}
		if (typeof document.adminForm.onsubmit == "function") {
			document.adminForm.onsubmit();
		}
		document.adminForm.submit();
	}
	
	function setgood() {
		
		return true;
	}
	
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		
		try {
			form.onsubmit();
		} catch(e) {
			alert(e);
		}
		
		submitform(pressbutton);
	}

</script>

<div class="tz_membership_download <?php echo $this->escape($this->params->get('pageclass_sfx')) ?>">
<?php if($this->params->get('show_page_heading',1)) : ?>

<div class="tz-membership-heading">
	<?php echo $this->escape($this->params->get('page_title')) ?> - <span><?php echo $this->lists['categoryname']; ?></span>
</div>
<?php endif; ?>

<form action="<?php echo $this->action; ?>" id="adminForm" name="adminForm" method="post" onSubmit="setgood();">
	<?php if ($this->params->get('filter') || $this->params->get('show_pagination_limit')) : ?>
		<div class="sortby clearfix">
			<?php if ($this->params->get('filter')) : ?>
			<div class="filter">
				<?php echo JText::_( 'Filter' ); ?>:
				<input type="text" name="search" id="searchvalue" value="<?php echo $this->lists['search'];?>" class="inputbox" onchange="document.adminForm.submit();" />
			</div>
			<?php endif; ?>
			
			<?php if ($this->params->get('show_pagination_limit')) : ?>
			<div class="display">
				<?php echo JText::_('Display Num'); ?>&nbsp;
				<?php echo $this->pageNav->getLimitBox(); ?>
			</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	
	<div class="products">
		<?php
		$k 		= 0;
		$db		= JFactory::getDBO();
		for ($i=0, $n=count($this->rows); $i < $n; $i++) {
			$row 	= $this->rows[$i];;
			$link	= JRoute::_('index.php?option=com_tz_membership&view=downloadinfo&id='.$row->id.'&Itemid='.$this->Itemid);

            preg_match('/<img.*?src="(.*?)".*?\/>/i', $row->description, $match);
            $img        =   $match ? $match[1] : 'components/com_tz_membership/assets/images/no-image.jpg';
			
//			$query		= "SELECT v.name, v.modified FROM #__tz_produce as p INNER JOIN #__tz_version as v ON p.id = v.pid WHERE p.id = ".$row->id." ORDER BY v.name DESC";
//			$db->setQuery($query);
//			$lversion 	= $db->loadObjectList();
//            //sort
//            for ($u=0; $u<count($lversion)-1; $u++) {
//                for ($v=$u+1; $v<count($lversion); $v++) {
//                    $arri   =   explode('.', $lversion[$u]->name);
//                    $arrj   =   explode('.', $lversion[$v]->name);
//                    $less   =   1;
//                    for ($j = 0; $j< count($arri) && $j<count($arrj); $j++) {
//                        if ($arri[$j] > $arrj[$j]) {
//                            $less  =   0;
//                            break;
//                        }
//                    }
//                    if ($less && count($arri)> count($arrj)) {
//                        $less   =   0;
//                    }
//                    if ($less) {
//                        $tmp        =   $lversion[$u];
//                        $lversion[$u]   =   $lversion[$v];
//                        $lversion[$v]   =   $tmp;
//                    }
//                }
//            }
//            $lversion   =   $lversion[0];
			?>
			<div class="card product">
				<div class="card-body">
					<div class="float-left mr-4">
                        <a href="<?php echo $link; ?>" class="tz-checkversion-download"><img src="<?php echo $img; ?>" alt="<?php echo $row->produce_name; ?>" /></a>
			 		</div>
                    <div class="d-flex flex-column justify-content-center">
                        <h4 class="card-title">
                            <a href="<?php echo $link; ?>"><?php echo $row->produce_name; ?></a>
                        </h4>
                        <div class="card-subtitle row mb-3">
                            <div class="col-12 col-md-6">
                                <div class="latest-version">
		                            <?php
		                            if(isset($row->version))
			                            echo JText::_('Latest Version').': '.($row->version?$row -> version:'N/A');
		                            ?>
                                </div>
                                <div class="latest-update">
		                            <?php
		                            if(isset($row->modified))
			                            echo JHTML::_('date', $row->modified, JText::_('DATE_FORMAT_LC1'));
		                            ?>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="download-count">
		                            <?php
		                            if(isset($row->downloadnumber))
			                            echo JText::_('Downloads').': '.$row->downloadnumber;
		                            ?>
                                </div>
                                <div class="package-type">
		                            <?php
		                            if(isset($row->status) && $row->status == 0) {
			                            echo JText::_('Type').': '.JText::_('Free');
		                            } else {
			                            echo JText::_('Type').': '.JText::_('Commercial');
		                            }
		                            ?>
                                </div>
                            </div>
                        </div>

                        <div class="download-now">
                            <a href="<?php echo $link; ?>" title="Download Now" class="btn btn-primary">Download now</a>
                        </div>

                    </div>
				</div>
			</div>
			<?php
			$k = 1 - $k;
		}
		?>
	</div>
	
	<?php if ($this->params->get('show_pagination') && $this->pageNav->getPagesCounter()) : ?>
    <div class="tz-membership-pageNav">
	    <?php echo $this->pageNav->getPagesLinks();?>
    </div>
	<?php endif; ?>
	
	<input type="hidden" name="view" value="download" />
	<input type="hidden" name="option" value="com_tz_membership"/>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
	<input type="hidden" name="task" value="" />
</form>
<?php echo JHTML::_('behavior.keepalive'); ?>
</div>