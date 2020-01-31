<?php 
defined('_JEXEC') or die( 'Restricted access' );
$uri		= JFactory::getURI();
$action		= $uri->toString();
$action		= str_replace('&', '&amp;', $action);
//$packages   =   $this->packages;

if($items = $this -> items):
?>
<script language="javascript" type="text/javascript">
//<!--

	function ticketpricing(pressbutton,tkpricingtype) {
		if (pressbutton) {
			document.adminForm.task.value=pressbutton;
            document.adminForm.tkpricingtype.value=tkpricingtype;
		}
		if (typeof document.adminForm.onsubmit == "function") {
			document.adminForm.onsubmit();
		}
		document.adminForm.submit();
	}

//-->
</script>
	<?php if($this->params->get('show_page_heading',1)) : ?>
    <div class="tz-membership-heading<?php echo $this->escape($this->params->get('pageclass_sfx')) ?>">
		<?php echo $this->escape($this->params->get('page_heading')); ?>
    </div>
<?php endif; ?>
<div class="tz_membership tz-member-ticket-pricing card">
    <h3 class="card-header">Ticket Pricing detail</h3>
    <div class="card-body">
        <form action="<?php echo $action; ?>" name="adminForm" method="post">
            <table id="tz_ticket-pricing" class="table table-borderless table-hover">
                <tbody>
			    <?php foreach($items as $item){?>
                    <tr>
                        <td><strong><?php echo $item -> title;?></strong></td>
                        <td>
                            <div class="lead"><?php
	                            if($item -> price){
		                            echo '$'.$item -> price;
	                            }elseif($item -> price_text){
		                            echo '$'.$item -> price_text.' onwards';
	                            } else {
	                                echo '$$';
                                }
	                            ?></div>
                        </td>
                        <td><?php echo $item -> description;?></td>
                        <td>
						    <?php
						    $text   = 'Get A Quote';
						    if($item -> price){
							    $text   = 'Order Now';
						    }
						    if(isset($item -> button_text)){
							    $button_text    = trim($item -> button_text);
							    if(!empty($button_text)){
								    $text   = $button_text;
							    }
						    }
						    ?>
                            <button class="btn btn-success" onclick="ticketpricing('ticketpricing', <?php echo $item -> id;?>)"><?php echo $text;?></button>
                        </td>
                    </tr>
			    <?php }?>
                </tbody>
            </table>
		    <?php echo JHTML::_( 'form.token' ); ?>
            <input type="hidden" name="task" value="" />
            <input type="hidden" name="tkpricingtype" value="" />
        </form>
    </div>
    <div class="card-footer">Payment Method: Paypal</div>
</div>
<?php endif;