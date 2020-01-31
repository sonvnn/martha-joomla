<?php 
defined('_JEXEC') or die( 'Restricted access' );

$uri    					= JURI::getInstance();
$myurl 						= $uri->toString();

$mustLogin      = true;
$checkLicence   = false;
if($this -> category){
    if(isset($this -> category -> check_licence) && $this -> category -> check_licence){
        $checkLicence   = true;
    }elseif(isset($this -> category -> must_login) && !$this -> category -> must_login){
        $mustLogin  = false;
    }
}
?>
<script language="javascript" type="text/javascript">
//<!--
	function submitform(pressbutton){
        <?php if($checkLicence){ ?>
        if(document.ticketForm.support_type != undefined){
            if(document.ticketForm.support_type.value == 'domain'){
                if(document.ticketForm.domain_id.value == ''){
                    alert('Please choose your domain!');
                    return false;
                }
            }else{
                if( document.ticketForm.support_type.value == 'licence'){
                    if(document.ticketForm.lic.value == ''){
                        alert('Please choose support licence!');
                        return false;
                    }
                }
            }
        }
        <?php }elseif(!$checkLicence && !$mustLogin){ ?>
        if (document.ticketForm.email.value == '') {
            alert('Please input email!');
            document.ticketForm.email.focus();
            return false;
        }else{
            var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            if(!re.test(document.ticketForm.email.value)){
                alert('Email is not valid!');
                document.ticketForm.email.focus();
                return false;
            }
        }
        <?php } ?>
        if (document.ticketForm.subject.value == '') {
            alert('Please input subject!');
            document.ticketForm.subject.focus();
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

//-->
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
                <td><strong><?php echo $this->lists['catname']; ?></strong></td>
            </tr>
            </tbody>
        </table>
		<?php if($checkLicence){?>
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
		<?php } ?>
        <table class="table table-borderless">
            <thead>
            <tr>
                <th colspan="2">Message Details</th>
            </tr>
            </thead>
            <tbody>
            <?php if($checkLicence){?>
                <tr>
                    <td width="23%">Support Type</td>
                    <td class="btn-group radio">
			            <?php echo $this -> form -> getInput('support_type');?>
                    </td>
                </tr>
                <tr>
                    <td width="23%"><label for="domain_id">Your domain</label></td>
                    <td><?php echo $this->lists['domain'] ?><a class="btn btn-primary" href="<?php
			            echo JRoute::_('index.php?option=com_tz_membership&view=domainregister&Itemid='.$this->domainItemid); ?>">Add validate domain</a>
                    </td>
                </tr>
                <tr>
                    <td width="23%"><label for="licence_id">Support Licence</label></td>
                    <td>
                        <select name="lic" id="licence_id">
                            <option value="">- Select your licence -</option>
				            <?php
				            if($this -> spLicences) {
					            for ($i = 0; $i < count($this->spLicences); $i++) {
						            $licence = $this->spLicences[$i];
						            ?>
                                    <option value="<?php echo $licence->lic; ?>"><?php echo $licence->lic; ?></option>
					            <?php }
				            }
				            ?>
                        </select>
                    </td>
                </tr>
            <?php }elseif(!$checkLicence && !$mustLogin){ ?>
                <tr>
                    <td width="23%"><label for="subject">Your Email</label></td>
                    <td><input type="email" class="form-control" name="email" value="<?php echo $this->user->email
			            ; ?>" placeholder="Your Email.." id="email" /></td>
                </tr>
            <?php } ?>
            <tr>
                <td width="23%"><label for="subject">Subject</label></td>
                <td><input type="text" class="form-control" name="subject" value="" id="subject" /></td>
            </tr>
            <tr>
                <td valign="top"><label for="detail">Detail</label></td>
                <td><textarea id="detail" class="form-control" name="detail"></textarea></td>
            </tr>
            <?php if($this -> params -> get('ticket_sp_type', 'system') == 'system'){?>
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
            <?php }?>
            </tbody>
        </table>
        <div style="text-align: center;"><button type="button" class="btn btn-primary" onclick="submitform('addticket')">Submit</button></div>
        <input type="hidden" name="catid" value="<?php echo $this->catid; ?>" />
        <input type="hidden" name="task" value="" />
		<?php echo JHTML::_( 'form.token' ); ?>
    </form>
</div>

</div>