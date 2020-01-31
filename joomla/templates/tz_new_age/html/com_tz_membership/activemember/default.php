<?php 
defined('_JEXEC') or die( 'Restricted access' ); 

//JHTML::_('behavior.mootools');
?>
<?php if($this->params->get('show_page_heading',1)) : ?>
    <div class="tz-membership-heading<?php echo $this->escape($this->params->get('pageclass_sfx')) ?>">
		<?php echo $this->escape($this->params->get('page_title')) ?>
    </div>
<?php endif; ?>
<div class="tz_membership card">

<h3 class="card-header">License Activation</h3>
    <div class="card-body">
        <p class="card-text">Enter your license below. If you have not a license, please buy one to get more service.</p>
        <div class="tz-license-user">
            <table class="table table-borderless" width="100%" cellspacing="1">
                <thead>
                    <th colspan="2"><?php echo JText::_('User details');?></th>
                </thead>
                <tbody>
                    <tr>
                        <td width="20%" class="paramlist_key">
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
                </tbody>
            </table>
        </div>
        <div class="tz-license-member">
		    <?php
		    if (!$this -> license || ($this -> license && !count($this->license))) {
			    echo "You are free membership! Please join us to get all benefit from us!";
		    }else{
			    for ($i =0; $i<count($this->license); $i++) {
				    $licence    =   $this->license[$i];
				    ?>
                    <table class="table">
                        <thead class="thead-light">
                        <tr>
                            <th width="30%" class="paramlist_key">
                                <label><?php echo JText::_('License')?>:</label>
                            </th>
                            <th class="paramlist_value">
		                        <?php if(isset($licence->lic)) echo $licence->lic;?>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="paramlist_key">
                                <label><?php echo JText::_('Membership Type')?>:</label>
                            </td>
                            <td class="paramlist_value">

		                        <?php
		                        if(isset($licence->produce_name)){
			                        echo $licence->produce_name;
		                        } else {
			                        echo 'Registered User';
		                        }
		                        ?>

                            </td>
                        </tr>
                        <tr>
                            <td class="paramlist_key">
                                <label><?php echo JText::_('Created Date')?>:</label>
                            </td>
                            <td class="paramlist_value">

		                        <?php
		                        if(isset($licence->created_date)) {
			                        echo $licence->created_date;
		                        } else {
			                        echo 'Unknown';
		                        }
		                        ?>

                            </td>
                        </tr>
                        <tr>
                            <td class="paramlist_key">
                                <label><?php echo JText::_('Type')?>:</label>
                            </td>
                            <td class="paramlist_value">

		                        <?php
		                        if(isset($licence->limit_type)) {
			                        switch (intval($licence->limit_type)){
				                        case 0:
					                        echo  JText::_('Limited Version');
					                        break;
				                        case 1:
					                        echo  JText::_('Limited Date');
					                        break;
				                        case 2:
				                        default:
					                        echo  JText::_('Unlimited');
					                        break;
			                        }
		                        } else {
			                        echo  JText::_('Unlimited');
		                        }
		                        ?>

                            </td>
                        </tr>
                        <tr>
                            <td class="paramlist_key">
                                <label><?php echo JText::_('Date Expiry')?>:</label>
                            </td>
                            <td class="paramlist_value">

		                        <?php
		                        if(isset($licence->created_date)) {
			                        echo $licence->date_expiry;
		                        } else {
			                        echo 'Lifetime';
		                        }
		                        ?>

                            </td>
                        </tr>
                        <tr>
                            <td class="paramlist_key">
                                <label><?php echo JText::_('Support Valid')?>:</label>
                            </td>
                            <td class="paramlist_value">
		                        <?php
		                        if(isset($licence->created_date)) {
			                        echo $licence->support_expiry;
		                        } else {
			                        echo 'Unavailable';
		                        }
		                        ?>

                            </td>
                        </tr>
                        <tr>
                            <td class="paramlist_key">
                                <label><?php echo JText::_('Domains Limit')?>:</label>
                            </td>
                            <td class="paramlist_value">
		                        <?php
		                        if(isset($licence->site_no)) {
			                        echo (intval($licence->site_no) == -1) ? JText::_('Unlimited') : $licence->site_no;
		                        } else {
			                        echo JText::_('Unlimited');
		                        }
		                        ?>

                            </td>
                        </tr>
                        <tr>
                            <td class="paramlist_key">
                                <label><?php echo JText::_('Get Invoice')?>:</label>
                            </td>
                            <td class="paramlist_value">
		                        <?php
		                        echo '<a href="'.JRoute::_('index.php?option=com_tz_membership&view=invoice&license='.$licence->lic.'&Itemid='.$this->Itemid, false).'">Get Invoice</a>'
		                        ?>
                            </td>
                        </tr>
                        <?php if (isset($licence->downloadurl)) : ?>
                        <tr>
                            <td class="paramlist_key">
                                <label>Download:</label>
                            </td>
                            <td class="paramlist_value">
		                        <?php
		                        echo $licence->expired ? '<a href="'.$licence->downloadurl.'">Renew Now</a>' : '<a href="'.$licence->downloadurl.'">Download Now</a>';
		                        ?>
                            </td>
                        </tr>
                        </tbody>
                        <?php endif; ?>
                    </table>
				    <?php
			    }
		    }
		    ?>
        </div>
    </div>
    <div class="pagination">
        <?php
        echo $this->pagination->getPagesLinks(); ?>

        <div class="counter">
            <?php echo $this->pagination->getPagesCounter(); ?>
        </div>
    </div>
<?php echo JHTML::_('behavior.keepalive'); ?>
</div>