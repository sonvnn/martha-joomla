<?php 
defined('_JEXEC') or die( 'Restricted access' ); 

JHTML::_('behavior.tooltip');

$uri    					= JURI::getInstance();
$myurl 						= $uri->toString();
?>
<?php if($this->params->get('show_page_heading',1)) : ?>
    <div class="tz-membership-heading<?php echo $this->escape($this->params->get('pageclass_sfx')) ?>">
		<?php echo $this->escape($this->params->get('page_title')) ?>
    </div>
<?php endif; ?>
<div class="tz_membership card">
    <h3 class="card-header">Ticket Manager</h3>
    <div class="card-body">
        <p class="card-text">Manage your ticket! Our team will support as soon as possible. Please be patient!</p>
        <form action="<?php echo $this->action; ?>" id="ticketForm" name="ticketForm" method="post">
            <table class="table table-borderless">
                <tr>
                    <td align="left" width="100%">
				        <?php echo JText::_( 'Filter' ); ?>:
                        <input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
                        <button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
                        <button onclick="document.getElementById('search').value='';this.form.getElementById('filter_produce').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
                    </td>
                    <td nowrap="nowrap">
				        <?php echo $this->lists['status'];
				        echo $this->lists['catid'];
				        ?>
                    </td>
                </tr>
            </table>
            <table class="table">
                <thead class="thead-dark">
                <tr>
                    <th width="1%">
				        <?php echo JText::_( 'Num' ); ?>
                    </th>
                    <th class="title">
                        Subject
                    </th>
                    <th width="20%">
                        Category
                    </th>
                    <th width="1%">
                        Price
                    </th>
                    <th width="1%">
                        State
                    </th>
                    <th width="1%">
                        Status
                    </th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <td colspan="7">
				        <?php echo $this->pageNav->getPagesLinks(). $this->pageNav->getResultsCounter(); ?>
                    </td>
                </tr>
                </tfoot>
                <tbody>
		        <?php
		        $k = 0;

		        for ($i=0, $n=count($this->rows); $i < $n; $i++) {
			        $row = $this->rows[$i];

			        ?>
                    <tr class="<?php echo "row$k"; ?>">
                        <td align="center">
					        <?php echo $this->pageNav->getRowOffset( $i ); ?>
                        </td>
                        <td class="leader">
                        <span class="editlinktip hasTip" title="<?php echo JText::_( 'View Ticket' );?>::<?php echo $row->title; ?>">
                            <a href="<?php echo JRoute::_( 'index.php?option=com_tz_membership&view=tickets&doTask=edit&id='.$row->id.'&Itemid='.$this->Itemid ) ;?>"><?php echo $row->subject; ?></a>
                        </span>
                        </td>
                        <td>
					        <?php echo $row->title; ?>
                        </td>
                        <td>
					        <?php if($row -> price){
						        echo $row -> price.' '.$this -> params -> get('currency', 'USD');
					        }else{
						        echo '$'.$this -> params -> get('currency', 'USD');
					        }?>
                        </td>
                        <td>
					        <?php if($row -> state){?>
                                <font color="blue">Paid</font>
					        <?php }else{?>
                                <font color="red">Unpaid</font>
					        <?php }?>
                        </td>
                        <td>
					        <?php
					        switch ($row->status) {
						        case 0:
							        echo '<strong>Closed</strong>';
							        break;
						        case 1:
						        case 2:
						        default:
							        echo '<font color="blue"><strong>Open</strong></font>';
							        break;
					        }
					        ?>
                        </td>
                    </tr>
			        <?php
			        $k = 1 - $k;
		        }
		        ?>
                </tbody>
            </table>

		    <?php echo JHTML::_( 'form.token' ); ?>
        </form>
    </div>
</div>
