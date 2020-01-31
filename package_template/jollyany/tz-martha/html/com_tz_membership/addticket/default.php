<?php 
defined('_JEXEC') or die( 'Restricted access' );

$uri    					= JURI::getInstance();
$myurl 						= $uri->toString();
?>
<script language="javascript" type="text/javascript">
//<!--
	function submitform(pressbutton){
		if (pressbutton) {
			document.ticketForm.doTask.value=pressbutton;
		}

		document.ticketForm.submit();
	}	

//-->
</script>
<?php if($this->params->get('show_page_heading',1)) : ?>
    <div class="tz-membership-heading<?php echo $this->escape($this->params->get('pageclass_sfx')) ?>">
		<?php echo $this->escape($this->params->get('page_title')) ?>
    </div>
<?php endif; ?>
<div class="tz_membership card">

<h3 class="card-header">Select a department</h3>
<div class="card-body">
    <p class="card-text">If you can't find a solution to your problem in our HelpDesk, you can submit a ticket by selecting the appropriate department below.</p>

    <form action="<?php echo $this->action; ?>" id="ticketForm" name="ticketForm" method="post">
	    <?php echo $this->lists['catid']; ?>
        <button type="button" class="btn btn-primary" onclick="submitform('addticket')">Next</button>
        <input type="hidden" name="view" value="addticket" />
        <input type="hidden" name="doTask" value="" />
		<?php echo JHTML::_( 'form.token' ); ?>
    </form>
</div>

</div>