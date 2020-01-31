<?php
defined('_JEXEC') or die( 'Restricted access' );

//JHTML::_('behavior.mootools');
?>

<script type="text/javascript">

    function submitform(pressbutton){
        if (pressbutton) {
            document.adminForm.dotask.value=pressbutton;
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

<div class="tz_membership">
    <?php if($this->params->get('show_page_heading',1)) : ?>
        <div class="tz-membership-heading<?php echo $this->escape($this->params->get('pageclass_sfx')) ?>">
            <?php echo JText::_('Download'); ?>
        </div>
    <?php endif; ?>
    <div class="card">
        <h3 class="card-header">Get your invoice</h3>
        <div class="card-body">
            <p class="card-text">Enter your business information below:</p>
            <div class="tz-license-user">
                <div class="tz-license-detail"><?php echo JText::_('User details');?></div>
                <table>
                    <tr>
                        <td width="35%" class="paramlist_key">
                            <label><?php echo JText::_('Name')?>:</label>
                        </td>
                        <td class="paramlist_value">
                            <span><?php echo $this->user->name;?></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="paramlist_key">
                            <label><?php echo JText::_('Email')?>:</label>
                        </td>
                        <td class="paramlist_value">
                            <span><?php echo $this->user->email;?></span>
                        </td>
                    </tr>
                </table>
            </div>
            <form action="<?php echo $this->action; ?>" id="adminForm" name="adminForm" method="post" onSubmit="setgood();">
                <table>
                    <tr>
                        <td width="40%" class="paramlist_key">
                            <label><?php echo JText::_('Business Name')?>:</label>
                        </td>
                        <td class="paramlist_value">
                            <div style=" padding-left: 10px;">
                                <input type="text" id="business_name" name="business_name" class="form-control" value="" />
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td width="40%" class="paramlist_key">
                            <label><?php echo JText::_('TAX ID')?>:</label>
                        </td>
                        <td class="paramlist_value">
                            <div style=" padding-left: 10px;">
                                <input type="text" id="tax_id" name="tax_id" class="form-control" value="" />
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td width="40%" class="paramlist_key">
                            <label><?php echo JText::_('Address')?>:</label>
                        </td>
                        <td class="paramlist_value">
                            <div style=" padding-left: 10px;">
                                <input type="text" id="address" name="address" class="form-control" value="" />
                            </div>
                        </td>
                    </tr>
                </table>

                <div class="cta">
                    <button class="btn btn-primary" type="button" onclick="submitbutton('getinvoice');"><?php echo JText::_('Get Invoice'); ?></button>
                </div>

                <input type="hidden" name="view" value="invoice" />
                <input type="hidden" name="option" value="com_tz_membership"/>
		        <?php echo JHTML::_( 'form.token' ); ?>
                <input type="hidden" name="dotask" value="" />
            </form>
        </div>

    </div>
</div>