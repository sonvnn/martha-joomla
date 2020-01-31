<?php 
defined('_JEXEC') or die( 'Restricted access' );
JFilterOutput::objectHTMLSafe( $this->row);
JHtml::_('bootstrap.modal');

$uri    					= JURI::getInstance();
$myurl 						= $uri->toString();
$ratingajax     =       '
var now =   new Date();
var request = jQuery.ajax({
    context: this,
    url: "'.JRoute::_("index.php?option=com_tz_membership&task=ticket_rating",false).'"+"&t="+now.getTime(),
    method: "POST",
    data: { id : jQuery(this).attr(\'data-id\'), point: jQuery(this).attr(\'data-point\') }
}).done(function(){
    jQuery(this).parent().find(\'a.tzrating\').removeClass(\'active\');
    jQuery(this).addClass(\'active\');
});
';

$app	= JFactory::getApplication();
$menu   =   $app->getMenu();
$ticketItemid	= 0;
$menuticket  	=   $menu->getItems('link', 'index.php?option=com_tz_membership&view=ticket_pricing');
if ($menuticket) $ticketItemid    =   $menuticket[0]->id;
?>
<script language="javascript" type="text/javascript">
	function submitform(pressbutton){
        if (pressbutton == 'closeticket' || pressbutton == 'cancelticket') {
            document.ticketForm.task.value=pressbutton;
            document.ticketForm.submit();
            return false;
        }
        if (document.ticketForm.detail.value == '') {
            alert('Please input detail!');
            document.ticketForm.detail.focus();
            return false;
        }
		if (pressbutton) {
			document.ticketForm.task.value=pressbutton;
		}

		document.ticketForm.submit();
	}

    <?php if((!$this -> row -> state && $this -> row -> status && $this->row->uid == $this->juser->id)
    || ($this -> row -> status && $this->authorise)){?>
	function paysubmitform(pressbutton, pricecheck){
        if(pricecheck){
            if(document.ticketForm.price.value == ''){
                alert('Please input Price!');
                document.ticketForm.detail.focus();
                return false;
            }
        }

		if (pressbutton) {
			document.ticketForm.task.value=pressbutton;
		}else{
            document.ticketForm.action  = "<?php echo JRoute::_('index.php?option=com_tz_membership&amp;view=ticket_checkout&amp;Itemid='.$ticketItemid);?>";
        }

		document.ticketForm.submit();
	}
    <?php }?>

    jQuery(document).ready(function(){
        jQuery('.ticket_rating a.tzrating').click(function(event){
            event.preventDefault();
            <?php
             if (!$this->authorise && $this->row->uid == $this->juser->id) echo $ratingajax;
            ?>
        });
    });
</script>
<?php if($this->params->get('show_page_heading',1)) : ?>
    <div class="tz-membership-heading<?php echo $this->escape($this->params->get('pageclass_sfx')) ?>">
		<?php echo $this->escape($this->params->get('page_title')) ?>
    </div>
<?php endif; ?>
<div class="tz_membership card">

<h3 class="card-header">Your ticket details</h3>
<div class="card-body">
    <p class="card-text">Enter your ticket details below. If you are reporting a problem, please remember to provide as much information that is relevant to the issue as possible.</p>
    <form enctype="multipart/form-data" action="<?php echo $this->action; ?>" id="ticketForm" name="ticketForm" method="post">
        <table class="table">
            <thead>
            <tr>
                <th colspan="2">General Information</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td width="23%">Full Name:</td>
                <td><strong><?php echo $this->user->name; ?></strong></td>
            </tr>
            <tr>
                <td>Email:</td>
                <td><strong><?php echo $this->user->email; ?></strong></td>
            </tr>
            <tr>
                <td>Departments:</td>
                <td><strong><?php echo $this->row->category; ?></strong></td>
            </tr>
            </tbody>
        </table>
        <table class="table">
            <thead>
            <tr>
                <th colspan="2">Membership Information</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if (!$this->licences) {
	            ?>
                <tr>
                    <td width="100%" colspan="2">You are a free member. Please <a href="<?php echo JRoute::_("index.php?option=com_tz_membership&view=signup"); ?>">join us</a> to use full service!</td>
                </tr>
	            <?php
            } else {
	            ?>
                <tr>
                    <td width="23%">Licenses:</td>
                    <td>
			            <?php
			            for ($i=0; $i<count($this->licences); $i++) {
				            $licence    =   $this->licences[$i];
				            echo $licence->lic.'<br />';
			            }
			            ?>
                    </td>
                </tr>
	            <?php
            }
            ?>
            </tbody>
        </table>
        <table class="table table-borderless">
            <thead>
            <tr>
                <th colspan="2">Message Detail</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td width="23%">Subject</td>
                <td><strong><?php echo $this->row->subject; ?></strong></td>
            </tr>
            <tr>
                <td>Status:</td>
                <td><strong><?php echo $this->row->status ? 'Open' : 'Closed'; ?></strong></td>
            </tr>
            <?php if($this -> row -> pricing_id){?>
                <tr>
                    <td>Price:</td>
                    <td><?php if($price = $this -> row -> price) {?>
                            <strong><?php echo $price.' '.$this -> params -> get('currency', 'USD');?></strong>
			            <?php }else{?>
                            <strong><?php echo '$'.$this -> params -> get('currency','USD');?></strong>
				            <?php if($this -> row -> status && $this->authorise){?>
                                <button type="button" class="button" data-toggle="modal" data-target="#modal">Set Price</button>
                                <div id="modal" class="modal fade tz_modal-sm">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        Set Price
                                    </div>
                                    <div class="modal-body">
                                        <input type="text" name="price" value=""/>
                                    </div>
                                    <div class="modal-footer">
                                        <a href="#" class="btn" data-dismiss="modal">Close</a>
                                        <a href="#" class="btn btn-primary" onclick="paysubmitform('ticket_setprice', true)">Submit</a>
                                    </div>
                                </div>
				            <?php } }?></td>
                </tr>
                <tr>
                    <td>State:</td>
                    <td><?php if($this -> row -> state) {?>
                            <strong><?php echo 'Paid';?></strong>
			            <?php }else{?>
                            <strong>Unpaid</strong>
				            <?php if($this -> row -> status && $this->row->uid == $this->juser->id && $this -> row -> price){?>
                                <button type="button" class="button" onclick="paysubmitform()">Pay Now</button>
				            <?php }
			            }?>
                    </td>
                </tr>
            <?php }else{
	            if(isset($this -> row -> lic) && $this -> row -> lic){?>
                    <tr>
                        <td>Support Licence:</td>
                        <td><strong><?php echo $this->row->lic ? $this->row->lic : 'Free Product Support'; ?></strong></td>
                    </tr>
	            <?php }else{ ?>
                    <tr>
                        <td>Domain:</td>
                        <td><strong><?php echo $this->row->domain ? $this->row->domain : 'Free Product Support'; ?></strong></td>
                    </tr>
	            <?php }
            }?>
            <tr>
                <td valign="top">Detail</td>
                <td><?php echo nl2br($this->row->detail); ?>
		            <?php
		            if ($this->row->file1) {
			            ?><div class="file_upload"><a href="<?php echo JRoute::_('index.php?option=com_tz_membership&view=ticketfile&ticketid='.$this->row->id.'&file=file1'); ?>">upload1</a></div><?php
		            }
		            if ($this->row->file2) {
			            ?><div class="file_upload"><a href="<?php echo JRoute::_('index.php?option=com_tz_membership&view=ticketfile&ticketid='.$this->row->id.'&file=file2'); ?>">upload2</a></div><?php
		            }
		            ?>

                </td>
            </tr>
            <?php
            $k=0;
            for ($i=0; $i<count($this->replies); $i++) {
	            $reply  =   $this->replies[$i];
	            ?>
                <tr class="row<?php echo $k; ?>">
                    <td colspan="2"><p><strong><?php echo $reply->name.' - '.JHTML::_('date', $reply->created , JText::_('DATE_FORMAT_LC2')); ?> [Update #<?php echo $i+1; ?>]</strong></p><?php echo nl2br($reply->detail); ?>
			            <?php
			            if ($reply->file1) {
				            ?><div class="file_upload"><a href="<?php echo JRoute::_('index.php?option=com_tz_membership&view=ticketfile&replyid='.$reply->id.'&file=file1'); ?>">upload1</a></div><?php
			            }
			            if ($reply->file2) {
				            ?><div class="file_upload"><a href="<?php echo JRoute::_('index.php?option=com_tz_membership&view=ticketfile&replyid='.$reply->id.'&file=file2'); ?>">upload2</a></div><?php
			            }
			            ?>
                    </td>
                </tr>

	            <?php if ($reply->uid != $this->row->uid) : ?>
                    <tr class="row<?php echo $k; ?>">
                        <td colspan="2" class="ticket_rating">
                            <a href="#" class="tzrating good<?php echo $reply->rating==1 ? ' active': ''; ?>" data-id="<?php echo $reply->id; ?>" data-point="1"><i class="fa fa-thumbs-o-up"></i>Good</a>
                            <a href="#" class="tzrating bad<?php echo $reply->rating==-1 ? ' active': ''; ?>" data-id="<?php echo $reply->id; ?>" data-point="-1"><i class="fa fa-thumbs-o-down"></i>Bad</a>
                        </td>
                    </tr>
	            <?php
	            endif;
	            $k  =   1-$k;
            }
            ?>
            <?php if ($this->row->status) : ?>
                <tr>
                    <td valign="top"><label for="detail">Update Ticket</label></td>
                    <td><textarea id="detail" class="form-control" name="detail"></textarea></td>
                </tr>
                <tr>
                    <td valign="top"><label for="file1">Upload 1</label></td>
                    <td><input id="file1" name="file1" type="file" size="37" /></td>
                </tr>
                <tr>
                    <td valign="top"><label for="file2">Upload 2</label></td>
                    <td><input id="file2" name="file2" type="file" size="37" /></td>
                </tr>
                <tr>
                    <td colspan="2"><em>Max File Upload Size: 3Mb. (Accept: jpg,png,zip)</em></td>
                </tr>
            <?php endif; ?>
            </tbody>

        </table>
		<?php if ($this->row->status) : ?>
            <div style="text-align: center;"><button type="button" class="btn btn-primary" onclick="submitform('addreply')">Update</button>&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-secondary" onclick="submitform('cancelticket')">Cancel</button>
				<?php
				if ($this->authorise) {
					?>&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-warning" onclick="submitform('closeticket')">Close</button><?php
				}
				?>
            </div>
		<?php endif; ?>
        <input type="hidden" name="ticket_id" value="<?php echo $this->row->id; ?>" />
        <input type="hidden" name="ticket_name" value="<?php echo $this->row->subject; ?>" />
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="return" value="<?php echo base64_encode($myurl);?>" />
		<?php echo JHTML::_( 'form.token' ); ?>
    </form>
</div>
</div>