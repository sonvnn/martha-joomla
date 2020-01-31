<?php
defined('_JEXEC') or die( 'Restricted access' ); 

JHTML::_('behavior.framework');
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
<?php if($this->params->get('show_page_heading',1)) : ?>
    <div class="tz-membership-heading<?php echo $this->escape($this->params->get('pageclass_sfx')) ?>">
		<?php echo $this->escape($this->params->get('page_title')) ?>
    </div>
<?php endif; ?>
<div class="tz_membership card">
    <div class="card-body">
        <form action="<?php echo $this->action; ?>" id="adminForm" name="adminForm" method="post" onSubmit="setgood();">
            <div class="tz-membership-license">Choose a license: <?php echo $this->lists['license']; ?></div>
		    <?php
		    if ($this->license) :
			    ?>
                <script language="javascript" type="text/javascript">
                    var dcount	=	<?php echo count($this->rows); ?>;
                    var limit	=	<?php echo count($this->rows); ?>;
                    var site_no	=	<?php if(isset($this->license->site_no)) echo $this->license->site_no; ?>;
                    window.addEvent('load', function(){
                        $('newfile').addEvent('click', function(e){

                            if (site_no == -1 || (site_no != -1 && limit < site_no)) {
                                var newDiv = new Element('div', {id: 'domaindiv'+dcount, style: 'clear: both;'});

                                var newDiv2 = new Element('div');
                                newDiv2.set('html','<div style="float:left;">'
                                    +'<input type="text" value="http://" name="domains['+dcount+']" class="inputbox" />'
                                    +'<input type="hidden" value="0" name="domains_id['+dcount+']" />'
                                    +'<\/div>'
                                    +'<div style="float:left;">'
                                    +'<input type="button" class="button" style="margin-left:10px;" value="<?php echo JText::_( 'DELETE'); ?>" onclick="$(\'domaindiv'+dcount+'\').destroy();limit--;" />'
                                    +'<\/div>'
                                    +'<div class="clear" style="height:2px;"><\/div>');
                                newDiv2.inject(newDiv);

                                newDiv.inject($('domain_list'));
                                dcount	=	dcount	+1;
                                limit	=	limit	+1;
                            }
                        });
                    });
                </script>
                <div class="tz-membership-title">License Information</div>
                <div class="download_description">
                    Register your domains below to use our products!<br /><br />
                    <table width="100%">
                        <tr>
                            <td class="leader">Membership Package:</td>
                            <td class="domain-detail"><?php echo $this->license->produce_name; ?></td>
                            <td class="adddomain" rowspan="2"><input type="button" class="button" id="newfile" value="<?php echo JText::_( 'Add Domain'); ?>" style="margin: 3px 3px 3px 10px;" /></td>
                        </tr>
                        <tr>
                            <td class="leader">Domain Limit:</td>
                            <td class="domain-detail"><?php echo $this->license->site_no<0 ? 'Unlimited' : $this->license->site_no; ?> domains</td>
                        </tr>

                    </table>
                </div>
                <table width="100%" cellspacing="1" class="paramlist admintable">
                    <tr>
                        <td width="35%" class="leader">
                            <label><?php echo JText::_('Domain List')?>:</label>
                        </td>
                        <td class="domain-detail">
                            <div id="domain_list">
							    <?php
							    for ($i = 0; $i<count($this->rows); $i++) {
								    $row		= $this->rows[$i];
								    $domain 	= 'http://'.$row->domain.chr(13);
								    ?>
                                    <div class="domaindiv<?php echo ($i%2); ?>" id="domaindiv<?php echo $i; ?>" style="clear: both;">
                                        <div>
                                            <div class="domain">
                                                <a style="padding-left:5px;" href="<?php echo $domain;?>" target="_blank"><?php echo $domain;?></a>
                                                <input type="hidden" readonly value="<?php echo $domain; ?>" name="domains[<?php echo $i; ?>]" />
                                                <input type="hidden" value="<?php echo $row -> id;?>" name="domains_id[<?php echo $i;?>]"/>
                                            </div>
                                            <div style="float: left; padding: 5px;">Modified: <?php echo $row -> modified;?></div>
                                            <div style="float: left;">
                                                <input type="button" class="button" style="margin-left: 10px;" value="<?php echo JText::_( 'JGLOBAL_EDIT'); ?>"
                                                       onclick="jQuery('#domaindiv<?php echo  $i; ?> input[name=\'domains[<?php echo $i;?>]\']').attr('type','text').removeAttr('readonly');" />
                                                <input type="button" class="button" style="margin-left: 10px;" value="<?php echo JText::_( 'DELETE'); ?>" onclick="$('domaindiv<?php echo  $i; ?>').destroy();limit--;" />
                                            </div>
                                        </div>
                                        <div class="clear" style="height:2px;"></div>
                                    </div>
							    <?php }?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="domain-save" colspan="2">
                            <button type="button" class="button" onclick="submitbutton('savedomain')">
							    <?php echo JText::_('Save') ?>
                            </button>
                        </td>
                    </tr>
                </table>
		    <?php
		    else :
		    ?>
                <div class="tz-membership-warning">Please select a license first!</div>
		    <?php
		    endif;
		    ?>
            <input type="hidden" name="view" value="domainregister" />
            <input type="hidden" name="option" value="com_tz_membership"/>
		    <?php echo JHTML::_( 'form.token' ); ?>
            <input type="hidden" name="task" value="" />
        </form>
	    <?php echo JHTML::_('behavior.keepalive'); ?>
    </div>
</div>